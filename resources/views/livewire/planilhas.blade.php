<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="bg-white px-6 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900">Planilhas</h1>
            <button class="p-2 text-gray-400 hover:text-gray-600">
                <i data-lucide="plus" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto px-6 py-6 mb-20">
        <!-- Month Selector -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <button wire:click="previousMonth"
                    class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                    <i data-lucide="chevron-left" class="w-5 h-5 text-gray-600"></i>
                </button>
                <span class="text-lg font-semibold text-gray-900 capitalize">
                    {{ \Carbon\Carbon::create($year, $month, 1)->locale('pt_BR')->isoFormat('MMMM YYYY') }}
                </span>
                <button wire:click="nextMonth" class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-600"></i>
                </button>
            </div>

            <div class="flex gap-2">
                <button wire:click="export"
                    class="flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span class="text-sm font-medium">Exportar Excel</span>
                </button>
                <button wire:click="applyTemplate"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                    <i data-lucide="layout-template" class="w-4 h-4"></i>
                    <span class="text-sm font-medium">Templates</span>
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase mb-1">Entradas</p>
                <p class="text-lg font-bold text-green-600">R$ {{ number_format($totalIncome, 2, ',', '.') }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase mb-1">Saídas</p>
                <p class="text-lg font-bold text-red-600">R$ {{ number_format($totalExpenses, 2, ',', '.') }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase mb-1">Saldo</p>
                <p class="text-lg font-bold {{ $balance >= 0 ? 'text-blue-600' : 'text-red-500' }}">R$
                    {{ number_format($balance, 2, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white border boundary-gray-200 rounded-xl shadow-sm overflow-hidden">
            @if($transactions->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <p class="text-gray-900 font-medium">Nenhuma transação encontrada</p>
                    <p class="text-sm text-gray-500 mt-1">Este mês ainda não tem lançamentos.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-medium">
                            <tr>
                                <th class="px-6 py-3">Data</th>
                                <th class="px-6 py-3">Descrição</th>
                                <th class="px-6 py-3">Categoria</th>
                                <th class="px-6 py-3 text-right">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                        {{ $transaction->description }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
                                            style="background-color: {{ $transaction->category->color ?? '#9CA3AF' }}20; color: {{ $transaction->category->color ?? '#9CA3AF' }}">
                                            <span class="w-1.5 h-1.5 rounded-full"
                                                style="background-color: {{ $transaction->category->color ?? '#9CA3AF' }}"></span>
                                            {{ $transaction->category->name ?? 'Sem Categoria' }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right text-sm font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }} R$
                                        {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>