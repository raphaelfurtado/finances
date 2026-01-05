<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanInstallment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LoanService
{
    /**
     * Create a new loan with installments.
     */
    public function createLoan(array $data): Loan
    {
        return DB::transaction(function () use ($data) {
            $calc = $this->calculatePreview(
                (float) $data['principal_amount'],
                (float) ($data['interest_rate'] ?? 0),
                (int) $data['installments_count'],
                $data['interest_calculation_type'] ?? 'simple_monthly'
            );

            $loan = Loan::create([
                'user_id' => auth()->id(),
                'type' => $data['type'],
                'counterparty_name' => $data['counterparty_name'],
                'counterparty_phone' => $data['counterparty_phone'] ?? null,
                'principal_amount' => (float) $data['principal_amount'],
                'interest_rate' => (float) ($data['interest_rate'] ?? 0),
                'late_fee_percentage' => (float) ($data['late_fee_percentage'] ?? 0),
                'daily_interest_rate' => (float) ($data['daily_interest_rate'] ?? 0),
                'interest_calculation_type' => $data['interest_calculation_type'] ?? 'simple_monthly',
                'total_amount' => $calc['total_amount'],
                'installments_count' => (int) $data['installments_count'],
                'installment_frequency' => $data['installment_frequency'] ?? 'monthly',
                'start_date' => Carbon::parse($data['start_date']),
                'status' => 'active',
            ]);

            for ($i = 1; $i <= $loan->installments_count; $i++) {
                $loan->installments()->create([
                    'installment_number' => $i,
                    'due_date' => $this->calculateDueDate($loan->start_date, $i, $loan->installment_frequency),
                    'amount' => $calc['installment_amount'],
                    'status' => 'pending',
                ]);
            }

            return $loan;
        });
    }

    /**
     * Update basic loan information.
     */
    public function updateLoanInfo(Loan $loan, array $data): Loan
    {
        $loan->update([
            'counterparty_name' => $data['counterparty_name'],
            'counterparty_phone' => $data['counterparty_phone'] ?? null,
            'description' => $data['description'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return $loan;
    }

    /**
     * Update loan contract details (principal, rate, installments count).
     */
    public function updateLoanContract(Loan $loan, array $data): Loan
    {
        if ($loan->installments()->whereIn('status', ['paid', 'refinanced'])->exists() || $loan->paid_amount > 0) {
            throw new \Exception("Não é possível alterar valores ou parcelas de um empréstimo com pagamentos ou renegociações já realizados.");
        }

        return DB::transaction(function () use ($loan, $data) {
            $calc = $this->calculatePreview(
                (float) $data['principal_amount'],
                (float) ($data['interest_rate'] ?? 0),
                (int) $data['installments_count'],
                $data['interest_calculation_type'] ?? 'simple_monthly'
            );

            $loan->update([
                'principal_amount' => (float) $data['principal_amount'],
                'interest_rate' => (float) ($data['interest_rate'] ?? 0),
                'late_fee_percentage' => (float) ($data['late_fee_percentage'] ?? 0),
                'daily_interest_rate' => (float) ($data['daily_interest_rate'] ?? 0),
                'interest_calculation_type' => $data['interest_calculation_type'] ?? 'simple_monthly',
                'total_amount' => $calc['total_amount'],
                'installments_count' => (int) $data['installments_count'],
                'installment_frequency' => $data['installment_frequency'] ?? 'monthly',
                'start_date' => Carbon::parse($data['start_date']),
            ]);

            $loan->installments()->delete();
            for ($i = 1; $i <= $loan->installments_count; $i++) {
                $loan->installments()->create([
                    'installment_number' => $i,
                    'due_date' => $this->calculateDueDate($loan->start_date, $i, $loan->installment_frequency),
                    'amount' => $calc['installment_amount'],
                    'status' => 'pending',
                ]);
            }

            return $loan;
        });
    }

    /**
     * Update a single installment.
     */
    public function updateInstallment(LoanInstallment $installment, array $data): LoanInstallment
    {
        if ($installment->status === 'paid') {
            throw new \Exception("Não é possível editar uma parcela já paga.");
        }

        $installment->update([
            'due_date' => Carbon::parse($data['due_date']),
            'amount' => (float) $data['amount'],
            'waived_penalty' => (bool) ($data['waived_penalty'] ?? false),
        ]);

        // Refresh status if overdue logic applies
        return $installment->refresh();
    }

    /**
     * Bulk update due day for pending installments.
     */
    public function bulkUpdateInstallmentsDay(Loan $loan, int $newDay): void
    {
        $loan->installments()
            ->where('status', '!=', 'paid')
            ->get()
            ->each(function ($installment) use ($newDay) {
                $date = Carbon::parse($installment->due_date);

                // Use Carbon to safely set the day
                try {
                    $date->day($newDay);
                } catch (\Exception $e) {
                    // Fail gracefully if day doesn't exist (e.g. Feb 30) - set to last day of month
                    $date->endOfMonth();
                }

                $installment->update(['due_date' => $date]);
            });
    }

    /**
     * Calculate virtual total and installment amount for preview.
     */
    public function calculatePreview(float $principal, float $rate, int $installmentsCount, string $calculationType = 'simple_monthly'): array
    {
        if ($installmentsCount <= 0) {
            return [
                'total_amount' => $principal,
                'installment_amount' => $principal,
                'interest_amount' => 0,
            ];
        }

        $interest = 0;
        if ($rate > 0) {
            if ($calculationType === 'fixed_on_total') {
                $interest = $principal * ($rate / 100);
            } else {
                $interest = ($principal * ($rate / 100)) * $installmentsCount;
            }
        }

        $totalAmount = $principal + $interest;

        return [
            'total_amount' => round($totalAmount, 2),
            'installment_amount' => round($totalAmount / $installmentsCount, 2),
            'interest_amount' => round($interest, 2),
        ];
    }
    /**
     * Rollover an installment (refinance the debt).
     */
    /**
     * Rollover an installment (refinance the debt).
     */
    public function rolloverInstallment(LoanInstallment $installment, float $newInterestRate, ?string $newDueDate, float $upfrontPayment = 0): LoanInstallment
    {
        if (in_array($installment->status, ['paid', 'refinanced'])) {
            throw new \Exception("Não é possível rolar uma parcela paga ou já renegociada.");
        }

        return DB::transaction(function () use ($installment, $newInterestRate, $newDueDate, $upfrontPayment) {
            // 1. Process upfront payment if any
            if ($upfrontPayment > 0) {
                if ($upfrontPayment > $installment->current_amount) {
                    throw new \Exception("O pagamento parcial não pode ser maior que o valor da dívida.");
                }

                $installment->update([
                    'paid_amount' => $installment->paid_amount + $upfrontPayment,
                    'paid_at' => now(), // Record payment date
                ]);
                $installment->refresh(); // getting updated paid_amount
            }

            // 2. Calculate remaining balance (Principal for new debt)
            $remainingPrincipal = $installment->current_amount - $installment->paid_amount; // Debt minus what was already paid

            // 3. Mark old as refinanced
            $installment->update(['status' => 'refinanced']);

            // 4. Calculate new interest
            $interestAmount = $remainingPrincipal * ($newInterestRate / 100);
            $newTotal = $remainingPrincipal + $interestAmount;

            // 5. Create new installment
            $nextNumber = $installment->loan->installments()->max('installment_number') + 1;

            $newInstallment = $installment->loan->installments()->create([
                'installment_number' => $nextNumber,
                'due_date' => Carbon::parse($newDueDate),
                'amount' => round($newTotal, 2),
                'status' => 'pending',
                'paid_amount' => 0,
            ]);

            return $newInstallment;
        });
    }
    /**
     * Calculate due date based on frequency.
     */
    private function calculateDueDate($startDate, int $installmentNumber, string $frequency): Carbon
    {
        $date = Carbon::parse($startDate)->copy();
        $offset = $installmentNumber - 1;

        return match ($frequency) {
            'weekly' => $date->addWeeks($offset),
            'biweekly' => $date->addWeeks($offset * 2),
            'bimonthly' => $date->addMonths($offset * 2),
            'quarterly' => $date->addMonths($offset * 3),
            'yearly' => $date->addYears($offset),
            default => $date->addMonths($offset), // 'monthly'
        };
    }
}
