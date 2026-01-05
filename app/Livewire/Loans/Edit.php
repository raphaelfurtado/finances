<?php

namespace App\Livewire\Loans;

use App\Models\Loan;
use App\Services\LoanService;
use Livewire\Component;

class Edit extends Component
{
    public Loan $loan;
    public $type;
    public $counterparty_name;
    public $counterparty_phone;
    public $principal_amount;
    public $interest_rate;
    public $late_fee_percentage;
    public $daily_interest_rate;
    public $interest_calculation_type;
    public $installments_count;
    public $installment_frequency;
    public $start_date;

    // Preview
    public $preview_total = 0;
    public $preview_installment = 0;
    public $preview_interest = 0;

    protected $rules = [
        'type' => 'required|in:receivable,payable',
        'counterparty_name' => 'required|string|max:255',
        'counterparty_phone' => 'nullable|string|max:20',
        'principal_amount' => 'required|numeric|min:0.01',
        'interest_rate' => 'nullable|numeric|min:0',
        'late_fee_percentage' => 'nullable|numeric|min:0',
        'daily_interest_rate' => 'nullable|numeric|min:0',
        'interest_calculation_type' => 'required|in:fixed_on_total,simple_monthly',
        'installments_count' => 'required|integer|min:1',
        'installment_frequency' => 'required|in:weekly,biweekly,monthly,bimonthly,quarterly,yearly',
        'start_date' => 'required|date',
    ];

    public function mount(Loan $loan)
    {
        $this->loan = $loan;
        $this->type = $loan->type;
        $this->counterparty_name = $loan->counterparty_name;
        $this->counterparty_phone = $loan->counterparty_phone;
        $this->principal_amount = $loan->principal_amount;
        $this->interest_rate = $loan->interest_rate;
        $this->late_fee_percentage = $loan->late_fee_percentage;
        $this->daily_interest_rate = $loan->daily_interest_rate;
        $this->interest_calculation_type = $loan->interest_calculation_type;
        $this->installments_count = $loan->installments_count;
        $this->installment_frequency = $loan->installment_frequency ?? 'monthly';
        $this->start_date = \Illuminate\Support\Carbon::parse($loan->start_date)->format('Y-m-d');

        $this->updatePreview();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['principal_amount', 'interest_rate', 'interest_calculation_type', 'installments_count'])) {
            $this->updatePreview();
        }
    }

    public function updatePreview()
    {
        $principal = (float) ($this->principal_amount ?: 0);
        $rate = (float) ($this->interest_rate ?: 0);
        $installments = (int) ($this->installments_count ?: 1);

        $service = new LoanService();
        $preview = $service->calculatePreview($principal, $rate, $installments, $this->interest_calculation_type);

        $this->preview_total = $preview['total_amount'];
        $this->preview_installment = $preview['installment_amount'];
        $this->preview_interest = $preview['interest_amount'];
    }

    public function save()
    {
        $this->validate();

        $service = new LoanService();

        try {
            // Update basic info
            $service->updateLoanInfo($this->loan, [
                'counterparty_name' => $this->counterparty_name,
                'counterparty_phone' => $this->counterparty_phone,
                'description' => $this->loan->description,
                'notes' => $this->loan->notes,
            ]);

            // Update contract (will throw exception if paid installments exist and values changed)
            // But wait, the user wants to *always* update info, but *block* contract changes if paid.
            // If the user didn't change principal/installments, it's fine.
            // However, the service method updateLoanContract always regenerates.

            // Logic: if any payment exists, and user tries to change principal or installments, block.
            if ($this->loan->paid_amount > 0 || $this->loan->installments()->where('status', 'paid')->exists()) {
                if (
                    (float) $this->principal_amount != (float) $this->loan->principal_amount ||
                    (int) $this->installments_count != (int) $this->loan->installments_count ||
                    (float) $this->interest_rate != (float) $this->loan->interest_rate ||
                    $this->interest_calculation_type != $this->loan->interest_calculation_type ||
                    $this->installment_frequency != $this->loan->installment_frequency ||
                    \Illuminate\Support\Carbon::parse($this->start_date)->format('Y-m-d') != \Illuminate\Support\Carbon::parse($this->loan->start_date)->format('Y-m-d')
                ) {
                    throw new \Exception("Dados do contrato (valor, parcelas, taxa, data início) não podem ser alterados pois já existem pagamentos registrados.");
                }

                // If only late fees changed, we could allow it in a more complex setup, but let's keep it simple for now as per user request.
                $this->loan->update([
                    'type' => $this->type,
                    'late_fee_percentage' => $this->late_fee_percentage,
                    'daily_interest_rate' => $this->daily_interest_rate,
                ]);
            } else {
                $service->updateLoanContract($this->loan, [
                    'type' => $this->type,
                    'principal_amount' => $this->principal_amount,
                    'interest_rate' => $this->interest_rate,
                    'late_fee_percentage' => $this->late_fee_percentage,
                    'daily_interest_rate' => $this->daily_interest_rate,
                    'interest_calculation_type' => $this->interest_calculation_type,
                    'installments_count' => $this->installments_count,
                    'installment_frequency' => $this->installment_frequency,
                    'start_date' => $this->start_date,
                ]);
            }

            session()->flash('success', 'Empréstimo atualizado com sucesso!');
            return redirect()->route('loans.show', $this->loan);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function delete()
    {
        $this->authorize('delete', $this->loan);
        $this->loan->delete();
        session()->flash('success', 'Empréstimo excluído.');
        return redirect()->route('loans.index');
    }

    public function render()
    {
        return view('livewire.loans.edit');
    }
}
