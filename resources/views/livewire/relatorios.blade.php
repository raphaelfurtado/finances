<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="bg-white px-6 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-xl font-bold text-gray-900">Relatórios</h1>
            <button class="p-2 text-gray-400 hover:text-gray-600">
                <i data-lucide="download" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="flex items-center justify-center gap-4 mt-3">
            <button wire:click="previousMonth" class="p-1 text-gray-400 hover:text-gray-600">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </button>
            <span class="text-sm font-medium text-gray-600">
                {{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->locale('pt_BR')->isoFormat('MMMM YYYY') }}
            </span>
            <button wire:click="nextMonth" class="p-1 text-gray-400 hover:text-gray-600">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto px-6 py-6 mb-20">
        <!-- Summary Cards -->
        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-4 text-white">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                    <span class="text-xs font-medium opacity-90">Receitas</span>
                </div>
                <p class="text-2xl font-bold">R$ {{ number_format($totalIncome, 2, ',', '.') }}</p>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-4 text-white">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="trending-down" class="w-5 h-5"></i>
                    <span class="text-xs font-medium opacity-90">Despesas</span>
                </div>
                <p class="text-2xl font-bold">R$ {{ number_format($totalExpenses, 2, ',', '.') }}</p>
            </div>
        </div>

        <!-- Expenses by Category -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Despesas por Categoria</h2>

            @if($categoryExpenses->isEmpty())
                <div class="text-center py-8">
                    <i data-lucide="pie-chart" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                    <p class="text-sm text-gray-400">Nenhuma despesa neste mês</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($categoryExpenses as $item)
                        @php
                            $percentage = $totalExpenses > 0 ? ($item['total'] / $totalExpenses) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                        style="background-color: {{ $item['category']->color ?? '#3B82F6' }}20;">
                                        <i data-lucide="{{ $item['category']->icon ?? 'circle' }}" class="w-4 h-4"
                                            style="color: {{ $item['category']->color ?? '#3B82F6' }};"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">
                                            {{ $item['category']->name ?? 'Sem categoria' }}</p>
                                        <p class="text-xs text-gray-400">{{ $item['count'] }} transações</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900 text-sm">R$
                                        {{ number_format($item['total'], 2, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($percentage, 1) }}%</p>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all"
                                    style="width: {{ $percentage }}%; background-color: {{ $item['category']->color ?? '#3B82F6' }};">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Visual Chart Placeholder -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Gráfico de Despesas</h2>
            <div class="flex items-center justify-center h-48 bg-gray-50 rounded-xl">
                <div class="text-center">
                    <i data-lucide="bar-chart-3" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                    <p class="text-sm text-gray-400">Gráfico em desenvolvimento</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <x-bottom-nav active="relatorios" />
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>