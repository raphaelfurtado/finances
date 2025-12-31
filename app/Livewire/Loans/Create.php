<?php

namespace App\Livewire\Loans;

use App\Services\LoanService;
use Livewire\Component;

class Create extends Component
{
    public $type = 'receivable';
    public $counterparty_name = '';
    public $counterparty_phone = '';
    public $principal_amount = '';
    public $interest_rate = 0;
    public $interest_calculation_type = 'simple_monthly';
    public $installments_count = 1;
    public $late_fee_percentage = 0;
    public $daily_interest_rate = 0;
    public $start_date;

    // Live Preview
    public $preview_total = 0;
    public $preview_installment = 0;
    public $preview_interest = 0;

    protected $rules = [
        'type' => 'required|in:receivable,payable',
        'counterparty_name' => 'required|string|max:255',
        'counterparty_phone' => 'nullable|string|max:20',
        'principal_amount' => 'required|numeric|min:0.01',
        'interest_rate' => 'nullable|numeric|min:0|max:100',
        'interest_calculation_type' => 'required|in:fixed_on_total,simple_monthly',
        'installments_count' => 'required|integer|min:1|max:360',
        'late_fee_percentage' => 'nullable|numeric|min:0|max:100',
        'daily_interest_rate' => 'nullable|numeric|min:0|max:100',
        'start_date' => 'required|date',
    ];

    public function mount()
    {
        $this->authorize('create', \App\Models\Loan::class);

        $this->start_date = now()->format('Y-m-d');
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
        $this->authorize('create', \App\Models\Loan::class);
        $this->validate();

        $service = new LoanService();
        $loan = $service->createLoan([
            'type' => $this->type,
            'counterparty_name' => $this->counterparty_name,
            'counterparty_phone' => $this->counterparty_phone,
            'principal_amount' => $this->principal_amount,
            'interest_rate' => $this->interest_rate,
            'interest_calculation_type' => $this->interest_calculation_type,
            'installments_count' => $this->installments_count,
            'late_fee_percentage' => $this->late_fee_percentage,
            'daily_interest_rate' => $this->daily_interest_rate,
            'start_date' => $this->start_date,
        ]);

        session()->flash('success', 'Empréstimo criado com sucesso!');
        return redirect()->route('loans.show', $loan);
    }

    public function render()
    {
        return view('livewire.loans.create');
    }
}
