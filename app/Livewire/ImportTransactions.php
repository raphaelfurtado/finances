<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\ImportService;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class ImportTransactions extends Component
{
    use WithFileUploads;

    public $file;
    public $transactions = [];
    public $categories = [];
    public $selectedRows = [];
    public $selectAll = false;
    public $transactionsSummary = [
        'income' => 0,
        'expense' => 0,
        'total' => 0
    ];

    // New props for Date Override
    public $useTargetDate = true; // Default to true as requested
    public $targetDate;

    public function mount()
    {
        $this->targetDate = now()->format('Y-m-d'); // Default to today

        $this->categories = Category::where('user_id', auth()->id())
            ->whereNull('parent_id')
            ->with([
                'children' => function ($query) {
                    $query->orderBy('name');
                }
            ])
            ->orderBy('name')
            ->get();
    }

    public function updatedUseTargetDate()
    {
        $this->applyDateOverride();
    }

    public function updatedTargetDate()
    {
        $this->applyDateOverride();
    }

    public function applyDateOverride()
    {
        foreach ($this->transactions as $index => $transaction) {
            if ($this->useTargetDate && $this->targetDate) {
                $this->transactions[$index]['date'] = $this->targetDate;
            } else {
                // Restore original date
                if (isset($transaction['original_date'])) {
                    $this->transactions[$index]['date'] = $transaction['original_date'];
                }
            }
        }
    }

    // updatedSelectAll removed to prevent state conflict with toggleSelectAll

    public function updatedSelectedRows()
    {
        // Defensive casting
        if (!is_array($this->selectedRows)) {
            $this->selectedRows = [];
        }

        $selectedCount = count($this->selectedRows);
        $totalCount = is_array($this->transactions) ? count($this->transactions) : 0;

        if ($selectedCount === $totalCount && $totalCount > 0) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    public function toggleSelectAll()
    {
        // If it was already true (checked), we are unchecking it -> clear all.
        // If it was false, we are checking it -> select all.
        // BUT strict toggle based on current state.

        // However, if we just unchecked one item, selectAll became false.
        // Clicking it again should make it true (Select All).
        // Clicking it when true should make it false (Clear All).

        // But wire:click="toggleSelectAll" just calls this.
        // AND wire:model="selectAll" updates the property.
        // This is a race condition or double update?
        // Let's REMOVE wire:model from the checkbox and rely ONLY on checked status derived or just use wire:click + wire:key?
        // OR better: keep wire:model for display state, but use wire:click.prevent to handle logic?

        // Actually best pattern for "Select All" with Livewire:
        // Input: wire:click="toggleSelectAll" 
        // Checked attribute: {{ $selectAll ? 'checked' : '' }}
        // NO wire:model to avoid conflicts.

        // But let's stick to cleaning PHP first.

        $this->selectAll = !$this->selectAll;

        if ($this->selectAll) {
            $this->selectedRows = array_keys($this->transactions);
        } else {
            $this->selectedRows = [];
        }
    }

    public function getSummaryProperty()
    {
        $income = 0;
        $expense = 0;

        // Ensure selectedRows is an array
        $selected = is_array($this->selectedRows) ? $this->selectedRows : [];

        foreach ($selected as $index) {
            if (!isset($this->transactions[$index]))
                continue;

            $t = $this->transactions[$index];
            if ($t['type'] === 'income') {
                $income += $t['amount'];
            } else {
                $expense += $t['amount'];
            }
        }

        return [
            'income' => $income,
            'expense' => $expense,
            'net' => $expense - $income // Net Expenses (Cost - Refunds)
        ];
    }

    public function togglePayments()
    {
        // Ensure selectedRows is array
        if (!is_array($this->selectedRows)) {
            $this->selectedRows = [];
        }

        // Unselect (or select) transactions that look like payments
        foreach ($this->transactions as $index => $transaction) {
            $isPayment = false;

            // Only consider Income (Negative values in CSV/OFX that were normalized)
            if ($transaction['type'] === 'income') {
                $description = mb_strtolower($transaction['description']);

                // Strong keywords for Bill Payment
                if (stripos($description, 'pagamento') !== false || stripos($description, 'payment') !== false) {
                    $isPayment = true;
                }

                // Heuristic: Small income (refunds) vs Large income (bill payment)
                // If checking > 100 doesn't contain "estorno" or "refund", maybe assume payment?
                // But safer to only ignore if clearly payment OR very large relative to others.
                // Let's assume anything > 1000 is a payment if the user asks to ignore payments.
                // Refunds are usually smaller.
                if ($transaction['amount'] > 1000) {
                    $isPayment = true;
                }

                // Explicitly valid refunds clearly labelled
                if (stripos($description, 'estorno') !== false || stripos($description, 'reembolso') !== false || stripos($description, 'refund') !== false) {
                    $isPayment = false;
                }
            }

            if ($isPayment) {
                // Let's force off for "Ignore Payments" feature
                $this->selectedRows = array_diff($this->selectedRows, [$index]);
            }
        }

        // Update selectAll since we changed selection
        $this->updatedSelectedRows();
    }

    public function parseFile(ImportService $importService)
    {
        $this->validate([
            'file' => 'required|file|max:10240', // 10MB Max
        ]);

        $mimeType = $this->file->getMimeType();
        $extension = $this->file->getClientOriginalExtension();

        try {
            $path = $this->file->getRealPath();

            if ($extension === 'ofx' || $mimeType === 'application/x-ofx' || $mimeType === 'text/ofx') {
                $parsedData = $importService->parseOfx($path);
            } else {
                // Default to CSV for everything else for now, can improve validation
                $parsedData = $importService->parseCsv($path);
            }

            if (empty($parsedData)) {
                $this->addError('file', 'Nenhuma transação encontrada ou formato inválido.');
                return;
            }

            // Enrich with original_date for toggle ability
            foreach ($parsedData as &$item) {
                $item['original_date'] = $item['date'];
            }
            unset($item);

            $this->transactions = $parsedData;

            // Apply override immediately if enabled
            $this->applyDateOverride();

            // Select all by default
            $this->selectedRows = array_keys($this->transactions);
            $this->selectAll = true;

        } catch (\Exception $e) {
            $this->addError('file', 'Erro ao processar arquivo: ' . $e->getMessage());
        }
    }

    public function removeTransaction($index)
    {
        unset($this->transactions[$index]);
        // Re-index array to avoid JS issues with sparse arrays if needed, or just let Livewire handle it
        // Better to filter the selectedRows too
        if (is_array($this->selectedRows)) {
            $this->selectedRows = array_diff($this->selectedRows, [$index]);
        } else {
            $this->selectedRows = [];
        }

        $this->updatedSelectedRows();
    }

    public function confirmImport()
    {
        if (empty($this->selectedRows)) {
            return;
        }

        $count = 0;
        foreach ($this->selectedRows as $index) {
            if (!isset($this->transactions[$index]))
                continue;

            $data = $this->transactions[$index];

            // Handle missing category
            $categoryId = $data['category_id'] ?? null;

            if (!$categoryId) {
                // Try to find "Outros" or "Geral"
                $defaultCategory = Category::where('user_id', auth()->id())
                    ->where(function ($q) {
                        $q->where('name', 'like', 'Outros%')
                            ->orWhere('name', 'like', 'Geral%');
                    })
                    ->first();

                // If not found, create "Outros"
                if (!$defaultCategory) {
                    $defaultCategory = Category::create([
                        'user_id' => auth()->id(),
                        'name' => 'Outros',
                        'type' => 'expense', // Default assumption
                        'description' => 'Categoria automática para importação',
                        'color' => '#6b7280' // Gray
                    ]);
                }

                $categoryId = $defaultCategory->id;
            }

            Transaction::create([
                'user_id' => auth()->id(),
                'category_id' => $categoryId,
                'amount' => $data['amount'],
                'description' => $data['description'],
                'date' => $data['date'],
                'type' => $data['type'],
                'payment_method' => 'credit_card', // Assuming import is mostly for cards
                'status' => 'paid', // Assuming imported transactions are already processed
                'observation' => 'Importado via arquivo',
            ]);
            $count++;
        }

        session()->flash('message', "$count transações importadas com sucesso!");
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.import-transactions');
    }
}
