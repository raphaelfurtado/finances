<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Url;

class Planilhas extends Component
{
    #[Url]
    public $month;

    #[Url]
    public $year;

    public $transactions = [];
    public $totalIncome = 0;
    public $totalExpenses = 0;
    public $balance = 0;

    public function mount()
    {
        $this->month = $this->month ?? now()->month;
        $this->year = $this->year ?? now()->year;
        $this->loadData();
    }

    public function loadData()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->with('category.parent')
            ->orderBy('date', 'desc');

        $this->transactions = $query->get();

        $this->totalIncome = $this->transactions->where('type', 'income')->sum('amount');
        $this->totalExpenses = $this->transactions->where('type', 'expense')->sum('amount');
        $this->balance = $this->totalIncome - $this->totalExpenses;
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
        $this->loadData();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
        $this->loadData();
    }

    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\MonthlyExport(auth()->id(), $this->month, $this->year),
            "financas_{$this->month}_{$this->year}.xlsx"
        );
    }

    public function applyTemplate()
    {
        \App\Services\CategoryTemplateService::applyPersonalFinancesTemplate(auth()->user());

        session()->flash('message', '✅ Modelos de categoria aplicados com sucesso!');
        $this->dispatch('categories-updated'); // Optional, if we had a listener
    }

    public function render()
    {
        return view('livewire.planilhas');
    }
}
