<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="bg-white px-6 py-4 border-b border-gray-100 flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/dashboard" class="p-2 -ml-2 text-gray-400 hover:text-gray-600">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </a>
                <h1 class="text-xl font-bold text-gray-900">Extrato</h1>
            </div>
            <a href="/transactions/new" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                <i data-lucide="plus" class="w-5 h-5"></i>
            </a>
        </div>

        <!-- Month Navigation -->
        <div class="flex items-center justify-between bg-gray-50 rounded-xl p-1">
            <button wire:click="prevMonth" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all shadow-sm">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </button>
            <span class="font-bold text-gray-700 capitalize">
                {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->locale('pt_BR')->isoFormat('MMMM YYYY') }}
            </span>
            <button wire:click="nextMonth" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all shadow-sm">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Totals Summary -->
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-green-50 rounded-2xl p-4 border border-green-100">
                <p class="text-[10px] font-bold text-green-600 uppercase tracking-wider mb-1">Receitas</p>
                <p class="text-lg font-bold text-green-700">R$ {{ number_format($totalIncome, 2, ',', '.') }}</p>
                @if($projectedIncome > 0)
                    <p class="text-[10px] text-green-600/80 font-medium mt-1">
                        (inclui R$ {{ number_format($projectedIncome, 2, ',', '.') }} previstos)
                    </p>
                @endif
            </div>
            <div class="bg-red-50 rounded-2xl p-4 border border-red-100">
                <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider mb-1">Despesas</p>
                <p class="text-lg font-bold text-red-700">R$ {{ number_format($totalExpense, 2, ',', '.') }}</p>
                @if($projectedExpense > 0)
                    <p class="text-[10px] text-red-600/80 font-medium mt-1">
                        (inclui R$ {{ number_format($projectedExpense, 2, ',', '.') }} previstos)
                    </p>
                @endif
            </div>
            <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-1">Saldo</p>
                <p class="text-lg font-bold text-blue-700">R$ {{ number_format($balance, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto px-6 py-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header -->
            <div class="grid grid-cols-3 gap-4 px-4 py-3 bg-gray-50 border-b border-gray-100">
                <span class="text-xs font-bold text-gray-400 uppercase">Data</span>
                <span class="text-xs font-bold text-gray-400 uppercase">Descrição</span>
                <span class="text-xs font-bold text-gray-400 uppercase text-right">Valor</span>
            </div>

            <!-- Transactions List -->
            @forelse($transactions as $transaction)
                @php
                    $isProjected = $transaction->is_projected ?? false;
                @endphp

                <div class="relative group">
                    <a href="{{ $isProjected ? '#' : '/transactions/' . $transaction->id }}"
                        @if($isProjected) wire:click.prevent="openPaymentModal({{ $transaction->original_fixed_id }})" @endif
                        class="grid grid-cols-3 gap-4 px-4 py-4 border-b border-gray-50 transition-colors cursor-pointer last:border-0 {{ $isProjected ? 'bg-gray-50/50 opacity-75 hover:bg-gray-100' : 'hover:bg-gray-50' }}">
                        <div>
                            <div class="text-lg font-bold text-gray-900">{{ $transaction->date->format('d') }}</div>
                            <div class="text-xs text-gray-400 uppercase">
                                {{ $transaction->date->locale('pt_BR')->isoFormat('MMM') }}
                            </div>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-sm mb-0.5 truncate flex items-center gap-2">
                                {{ $transaction->description }}
                                @if($isProjected)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] bg-blue-100 text-blue-700 uppercase font-extrabold tracking-wider">Previsto</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-400 truncate">
                                {{ $transaction->category->full_name ?? 'Sem categoria' }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-sm {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }}R$
                                {{ number_format($transaction->amount, 2, ',', '.') }}
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="px-4 py-12 text-center text-gray-400">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                    <p class="text-sm font-medium">Nenhuma transação neste mês</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Payment Confirmation Modal (Forecast) -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closePaymentModal"></div>

            <div class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Confirmar Pagamento</h2>
                    <button wire:click="closePaymentModal" class="p-2 text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <form wire:submit.prevent="registerPayment" class="p-6 space-y-6">
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                            style="background-color: {{ $paymentColor }}20; color: {{ $paymentColor }};">
                            <i data-lucide="{{ $paymentIcon }}" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $paymentTitle }}</h3>
                        <p class="text-sm text-gray-500">Este é um gasto previsto. Deseja confirmar o pagamento?</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Valor Pago (R$)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">R$</span>
                            <input type="number" step="0.01" inputmode="decimal" wire:model="paymentAmount"
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-mono text-xl font-bold text-gray-900 text-center"
                                autofocus>
                        </div>
                    </div>

                    <div class="pt-2 flex flex-col gap-3">
                        <button type="submit"
                            class="w-full py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2">
                            <i data-lucide="check" class="w-5 h-5"></i>
                            Confirmar Pagamento
                        </button>
                        <button type="button" wire:click="closePaymentModal"
                            class="w-full py-4 text-gray-500 font-bold hover:bg-gray-50 rounded-2xl transition-all">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


</div>
