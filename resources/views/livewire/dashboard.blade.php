<div class="flex flex-col h-full bg-gray-50">
    <!-- Header with Month Selector -->
    <div class="bg-white px-6 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-3">
                <i data-lucide="gift" class="w-8 h-8 text-blue-600"></i>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Minhas Finanças</h1>
                </div>
            </div>
            <a href="/configuracoes" class="p-2 text-gray-400 hover:text-gray-600">
                <i data-lucide="settings" class="w-5 h-5"></i>
            </a>
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
    <div class="flex-1 overflow-y-auto px-6 py-4">
        <!-- Income/Expense Cards -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <!-- Income Card -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="arrow-down" class="w-4 h-4 text-green-600"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Receitas</span>
                </div>
                <div class="mb-1">
                    <span class="text-2xl font-bold text-gray-900">R$ {{ number_format($income, 2, ',', '.') }}</span>
                </div>
                <div class="text-xs font-semibold {{ $incomeChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $incomeChange >= 0 ? '+' : '' }}{{ $incomeChange }}%
                </div>
            </div>

            <!-- Expense Card -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="arrow-up" class="w-4 h-4 text-red-600"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Despesas</span>
                </div>
                <div class="mb-1">
                    <span class="text-2xl font-bold text-gray-900">R$ {{ number_format($expenses, 2, ',', '.') }}</span>
                </div>
                <div class="text-xs font-semibold {{ $expenseChange <= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $expenseChange >= 0 ? '+' : '' }}{{ $expenseChange }}%
                </div>
            </div>
        </div>

        <!-- Balance Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 shadow-lg mb-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-blue-100 uppercase tracking-wide">Saldo Líquido</span>
                <span class="text-xs text-blue-200">Fórmula: =B2-C2</span>
            </div>
            <div class="mb-2">
                <span class="text-4xl font-bold text-white">R$ {{ number_format($balance, 2, ',', '.') }}</span>
            </div>
            <button
                class="px-3 py-1 bg-white/20 hover:bg-white/30 text-white text-xs font-medium rounded-lg transition-colors">
                No Controle
            </button>
        </div>

        <!-- Consultor Sincero -->
        <div class="mb-6">
            <div
                class="bg-gradient-to-r from-purple-800 to-indigo-900 rounded-2xl p-4 shadow-lg text-white relative overflow-hidden">
                <!-- Decorative Circle -->
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>

                <div class="flex items-start gap-4 relative z-10">
                    <div class="bg-white/20 p-2 rounded-xl backdrop-blur-sm">
                        <i data-lucide="bot" class="w-8 h-8 text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-purple-200 uppercase tracking-wider mb-1">Consultor Sincero
                        </h3>
                        <p class="font-medium text-sm leading-relaxed">
                            "{{ $advice }}"
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="mb-20">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Transações Recentes</h2>
                <a href="/transactions" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Ver Todas</a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Table Header -->
                <div class="grid grid-cols-3 gap-4 px-4 py-3 bg-gray-50 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase">Data</span>
                    <span class="text-xs font-bold text-gray-400 uppercase">Descrição</span>
                    <span class="text-xs font-bold text-gray-400 uppercase text-right">Valor</span>
                </div>

                <!-- Transactions List -->
                @forelse($recentTransactions as $transaction)
                    <a href="/transactions/{{ $transaction->id }}"
                        class="grid grid-cols-3 gap-4 px-4 py-4 border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer">
                        <div>
                            <div class="text-lg font-bold text-gray-900">{{ $transaction->date->format('d') }}</div>
                            <div class="text-xs text-gray-400 uppercase">
                                {{ $transaction->date->locale('pt_BR')->isoFormat('MMM') }}
                            </div>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-sm mb-0.5">{{ $transaction->description }}</div>
                            <div class="text-xs text-gray-400">{{ $transaction->category->name ?? 'Sem categoria' }}</div>
                        </div>
                        <div class="text-right">
                            <div
                                class="font-bold text-sm {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }}R$
                                {{ number_format($transaction->amount, 2, ',', '.') }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-8 text-center text-gray-400">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                        <p class="text-sm">Nenhuma transação encontrada</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <x-bottom-nav active="painel" />

    <!-- Floating Action Button -->
    <x-fab-button />
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>