<?php

namespace App\Livewire\Loans;

use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Services\LoanService;
use Livewire\Component;

class Show extends Component
{
    public Loan $loan;

    // Header Edit Modal
    public $showInfoModal = false;
    public $loanName, $loanPhone, $loanDescription, $loanNotes;

    // Single Installment Edit Modal
    public $editingInstallment = null;
    public $instDueDate, $instAmount, $instWaiveFees = false;

    // Bulk Update Modal
    public $showBulkModal = false;
    public $newInstallmentDay;

    public function mount(Loan $loan)
    {
        $this->loan = $loan;
        $this->initInfoFields();
    }

    public function initInfoFields()
    {
        $this->loanName = $this->loan->counterparty_name;
        $this->loanPhone = $this->loan->counterparty_phone;
        $this->loanDescription = $this->loan->description;
        $this->loanNotes = $this->loan->notes;
    }

    public function saveInfo()
    {
        $service = new LoanService();
        $service->updateLoanInfo($this->loan, [
            'counterparty_name' => $this->loanName,
            'counterparty_phone' => $this->loanPhone,
            'description' => $this->loanDescription,
            'notes' => $this->loanNotes,
        ]);

        $this->showInfoModal = false;
        $this->loan->refresh();
        session()->flash('success', 'Informações atualizadas!');
    }

    public function editInstallment($id)
    {
        $installment = LoanInstallment::findOrFail($id);

        if ($installment->status === 'paid') {
            session()->flash('error', 'Não é possível editar parcelas pagas.');
            return;
        }

        $this->editingInstallment = $installment;
        $this->instDueDate = \Illuminate\Support\Carbon::parse($installment->due_date)->format('Y-m-d');
        $this->instAmount = $installment->amount;
        $this->instWaiveFees = (bool) $installment->waived_penalty;
    }

    public function saveInstallment()
    {
        $service = new LoanService();
        try {
            $service->updateInstallment($this->editingInstallment, [
                'due_date' => $this->instDueDate,
                'amount' => $this->instAmount,
                'waived_penalty' => $this->instWaiveFees,
            ]);
            $this->editingInstallment = null;
            $this->loan->refresh();
            session()->flash('success', 'Parcela atualizada!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function cancelInstallmentEdit()
    {
        $this->editingInstallment = null;
    }

    public function openBulkModal()
    {
        $this->showBulkModal = true;
        // Pre-fill with first pending installment day if possible
        $firstPending = $this->loan->installments()->where('status', '!=', 'paid')->first();
        if ($firstPending) {
            $this->newInstallmentDay = \Illuminate\Support\Carbon::parse($firstPending->due_date)->day;
        }
    }

    public function applyBulkUpdate()
    {
        $this->validate([
            'newInstallmentDay' => 'required|integer|min:1|max:31'
        ]);

        $service = new LoanService();
        $service->bulkUpdateInstallmentsDay($this->loan, (int) $this->newInstallmentDay);

        $this->showBulkModal = false;
        $this->loan->refresh();
        session()->flash('success', 'Vencimentos em massa atualizados!');
    }

    public function recordPayment($installmentId, $amount)
    {
        $installment = LoanInstallment::findOrFail($installmentId);

        // Use a simple float cast for safety
        $amount = (float) $amount;

        $installment->update([
            'paid_amount' => $installment->paid_amount + $amount,
            'status' => ($installment->paid_amount + $amount >= $installment->amount) ? 'paid' : 'partial',
            'paid_at' => now(),
        ]);

        // Check if all installments are paid
        if (!$this->loan->installments()->where('status', '!=', 'paid')->exists()) {
            $this->loan->update(['status' => 'paid']);
        }

        $this->loan->refresh();
        session()->flash('success', 'Pagamento registrado!');
    }

    public function reversePayment($installmentId)
    {
        $installment = LoanInstallment::findOrFail($installmentId);

        $installment->update([
            'paid_amount' => 0,
            'status' => 'pending',
            'paid_at' => null,
        ]);

        if ($this->loan->status === 'paid') {
            $this->loan->update(['status' => 'active']);
        }

        $this->loan->refresh();
        session()->flash('success', 'Pagamento estornado!');
    }

    public function getWhatsAppLink($installment)
    {
        $phone = preg_replace('/[^0-9]/', '', $this->loan->counterparty_phone);
        if (!$phone)
            return '#';

        $dueDateStr = \Illuminate\Support\Carbon::parse($installment->due_date)->format('d/m/Y');
        $amount = number_format((float) $installment->amount, 2, ',', '.');
        $currentAmount = number_format((float) $installment->current_amount, 2, ',', '.');
        $isOverdue = $installment->isOverdue();

        if ($isOverdue) {
            $days = $installment->days_overdue;
            $message = "Olá {$this->loan->counterparty_name}, sua parcela de R$ {$amount} do empréstimo (vencimento em {$dueDateStr}) está atrasada há {$days} dias. O valor atualizado com encargos é R$ {$currentAmount}. Como podemos regularizar?";
        } else {
            $message = "Olá {$this->loan->counterparty_name}, lembrete da sua parcela de R$ {$amount} com vencimento em {$dueDateStr}.";
        }

        return "https://wa.me/55{$phone}?text=" . urlencode($message);
    }

    public function render()
    {
        return view('livewire.loans.show');
    }
}
