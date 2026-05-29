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

            @if(empty($categoryExpenses))
                <div class="text-center py-8">
                    <i data-lucide="pie-chart" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                    <p class="text-sm text-gray-400">Nenhuma despesa neste mês</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($categoryExpenses as $item)
                        @php
                            $percentage = $totalExpenses > 0 ? ($item['total'] / $totalExpenses) * 100 : 0;
                            $isSelected = $selectedCategoryId == $item['category_id'];
                        @endphp
                        <div wire:click="showCategoryDetails({{ $item['category_id'] }})"
                            class="cursor-pointer group p-2 rounded-xl transition-all {{ $isSelected ? 'bg-indigo-50 border border-indigo-100' : 'hover:bg-gray-50' }}">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                        style="background-color: {{ $item['category']->color ?? '#3B82F6' }}20;">
                                        <i data-lucide="{{ $item['category']->icon ?? 'circle' }}" class="w-4 h-4"
                                            style="color: {{ $item['category']->color ?? '#3B82F6' }};"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">
                                            {{ $item['category']->full_name ?? 'Sem categoria' }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $item['count'] }} transações</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900 text-sm">R$
                                        {{ number_format($item['total'], 2, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ number_format($percentage, 1) }}%</p>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full transition-all"
                                    style="width: {{ $percentage }}%; background-color: {{ $item['category']->color ?? '#3B82F6' }};">
                                </div>
                            </div>
                        </div>

                        <!-- Category Details (Accordion Style) -->
                        @if($isSelected)
                            <div class="mt-2 ml-10 space-y-2 pb-4 animate-in fade-in slide-in-from-top-2 duration-300">
                                @forelse($categoryTransactions as $transaction)
                                    <div
                                        class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl shadow-sm text-xs">
                                        <div class="flex items-center gap-3">
                                            <div class="text-center font-bold text-gray-400">
                                                {{ $transaction->date->format('d') }}<br>
                                                <span
                                                    class="text-[8px] uppercase tracking-tighter">{{ $transaction->date->locale('pt_BR')->isoFormat('MMM') }}</span>
                                            </div>
                                            <div class="border-l border-gray-100 h-6 mx-1"></div>
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $transaction->description }}</p>
                                                <p class="text-[9px] text-gray-400 uppercase font-medium">
                                                    {{ $transaction->payment_method ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-red-600">R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[10px] text-gray-400 italic text-center">Nenhuma transação listada.</p>
                                @endforelse
                                <div class="text-center">
                                    <button wire:click.stop="closeCategoryDetails"
                                        class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest hover:underline">Fechar
                                        Detalhes</button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Visual Chart Placeholder -->
        <!-- Visual Chart -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Gráfico de Despesas</h2>
            <div class="h-64 relative">
                <canvas id="expensesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script>
        let chartInstance = null;

        document.addEventListener('livewire:navigated', () => {
            initChart(@json($chartData));
        });

        // Listen for livewire updates
        document.addEventListener('livewire:init', () => {
            Livewire.on('update-chart', (event) => {
                // event.data contains the data sent from dispatch
                updateChart(event.data);
            });
        });

        function initChart(data) {
            const ctx = document.getElementById('expensesChart');
            if (!ctx) return;

            if (chartInstance) {
                chartInstance.destroy();
            }

            if (!data.labels || data.labels.length === 0) {
                // Could render an "empty state" here if desired
                return;
            }

            chartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11
                                }
                            }
                        }
                    },
                    cutout: '70%',
                }
            });
        }

        function updateChart(data) {
            if (chartInstance) {
                chartInstance.data = data;
                chartInstance.update();
            } else {
                initChart(data);
            }
        }
    </script>

    <!-- Fix for first load -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof Chart !== 'undefined') {
                initChart(@json($chartData));
            }
        });
    </script>



    <script>
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });

        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                lucide.createIcons();
            });
        });
    </script>
</div>