<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $userId;
    protected $month;
    protected $year;

    public function __construct(int $userId, int $month, int $year)
    {
        $this->userId = $userId;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return Transaction::where('user_id', $this->userId)
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Data',
            'Descrição',
            'Categoria',
            'Tipo',
            'Valor',
        ];
    }

    public function map($transaction): array
    {
        return [
            \Carbon\Carbon::parse($transaction->date)->format('d/m/Y'),
            $transaction->description,
            $transaction->category->name ?? 'Sem Categoria',
            $transaction->type === 'income' ? 'Receita' : 'Despesa',
            $transaction->amount,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
