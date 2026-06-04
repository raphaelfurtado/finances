<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="flex items-center px-6 py-4 bg-white border-b border-gray-100">
        <a href="/dashboard" class="p-2 -ml-2 text-gray-800 hover:text-gray-600">
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </a>
        <h1 class="flex-1 text-center text-xl font-bold text-gray-900 pr-8">Fixos</h1>
        <button wire:click="openModal" class="p-2 text-indigo-600 hover:text-indigo-700">
            <i data-lucide="plus-circle" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Filters -->
    <div class="px-6 py-3 bg-white border-b border-gray-100 overflow-x-auto">
        <div class="flex items-center gap-2 min-w-max">
            <button wire:click="$set('activeFilter', 'all')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $activeFilter === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                Todos
            </button>
            <button wire:click="$set('activeFilter', 'expense')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $activeFilter === 'expense' ? 'bg-red-500 text-white shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                Despesas
            </button>
            <button wire:click="$set('activeFilter', 'income')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $activeFilter === 'income' ? 'bg-green-600 text-white shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                Recebíveis
            </button>
        </div>
    </div>

    <!-- Financial Summary -->
    @php $ov = $this->overview; @endphp
    <div class="px-6 pt-6 pb-2 space-y-4">
        <!-- Sobra Recorrente (hero) -->
        <div class="bg-indigo-600 rounded-3xl p-5 shadow-sm text-white relative overflow-hidden">
            <div class="absolute -right-6 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <p class="text-[11px] font-bold text-indigo-200 uppercase tracking-wider mb-1">Sobra Recorrente</p>
                <p class="text-3xl font-bold {{ $ov['recurringLeftover'] < 0 ? 'text-red-200' : '' }}">
                    R$ {{ number_format($ov['recurringLeftover'], 2, ',', '.') }}
                </p>
                <p class="text-xs text-indigo-100 mt-1">
                    O que sobra por mês depois do básico (receita recorrente − custo fixo).
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Expenses Card -->
            <div
                class="bg-white rounded-3xl p-6 shadow-sm border {{ $ov['expenseTotal'] > 0 ? 'border-red-200' : 'border-gray-100' }} relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Custo de
                                Sobrevivência</h2>
                            <h3 class="text-3xl font-bold text-gray-900">
                                R$ {{ number_format($ov['expenseTotal'], 2, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600">
                            <i data-lucide="anchor" class="w-6 h-6"></i>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600">
                        Comprometimento mensal com <strong class="text-gray-900">{{ $ov['expenseCount'] }} contas
                            ativas</strong>.
                    </p>
                </div>
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-red-50 rounded-full blur-3xl opacity-50"></div>
            </div>

            <!-- Income Card -->
            <div
                class="bg-white rounded-3xl p-6 shadow-sm border {{ $ov['incomeTotal'] > 0 ? 'border-green-200' : 'border-gray-100' }} relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Receita Recorrente
                            </h2>
                            <h3 class="text-3xl font-bold text-gray-900">
                                R$ {{ number_format($ov['incomeTotal'], 2, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                            <i data-lucide="trending-up" class="w-6 h-6"></i>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600">
                        Valor total garantido por <strong class="text-gray-900">{{ $ov['incomeCount'] }} fontes de
                            renda</strong>.
                    </p>
                </div>
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-green-50 rounded-full blur-3xl opacity-50"></div>
            </div>
        </div>

        <!-- Progresso do mês -->
        @if($ov['expenseCount'] > 0)
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-bold text-gray-700">Pago este mês</p>
                    <p class="text-sm font-bold text-indigo-600">{{ $ov['percent'] }}%</p>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 mb-2">
                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all" style="width: {{ $ov['percent'] }}%"></div>
                </div>
                <p class="text-xs text-gray-500">
                    R$ {{ number_format($ov['paid'], 2, ',', '.') }} de R$ {{ number_format($ov['expenseTotal'], 2, ',', '.') }}
                    ·
                    <span class="font-semibold {{ $ov['pendingCount'] > 0 ? 'text-orange-600' : 'text-green-600' }}">
                        {{ $ov['pendingCount'] }} pendente(s)
                    </span>
                </p>
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto px-6 py-6">
        @if (session()->has('message'))
            <div
                class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-xl border border-green-100 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-3 bg-red-50 text-red-700 text-sm rounded-xl border border-red-100 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($fixedExpenses as $expense)
                @php
                    $status = $expense->is_active ? $expense->status_this_month : 'inactive';
                    $statusConfig = [
                        'paid' => [
                            'label' => 'Pago',
                            'class' => 'bg-green-100 text-green-700',
                            'icon' => 'check',
                            'button' => false
                        ],
                        'overdue' => [
                            'label' => 'Atrasado',
                            'class' => 'bg-red-100 text-red-700',
                            'icon' => 'alert-triangle',
                            'button' => true
                        ],
                        'due_soon' => [
                            'label' => 'Urgente',
                            'class' => 'bg-yellow-100 text-yellow-700',
                            'icon' => 'alert-circle',
                            'button' => true
                        ],
                        'pending' => [
                            'label' => 'Pendente',
                            'class' => 'bg-gray-100 text-gray-600',
                            'icon' => 'clock',
                            'button' => true
                        ],
                        'inactive' => [
                            'label' => 'Inativo',
                            'class' => 'bg-gray-200 text-gray-500',
                            'icon' => 'pause-circle',
                            'button' => false
                        ],
                        'not_started' => [
                            'label' => 'Em breve',
                            'class' => 'bg-indigo-100 text-indigo-700',
                            'icon' => 'calendar',
                            'button' => false
                        ],
                    ][$status];
                @endphp

                <div
                    class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 flex flex-col gap-4 transition-all {{ !$expense->is_active ? 'opacity-60 grayscale-[0.5]' : '' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-2xl flex items-center justify-center relative {{ $expense->type === 'income' ? 'bg-green-50' : 'bg-red-50' }}">
                                <i data-lucide="{{ $expense->category->icon ?? 'credit-card' }}"
                                    class="w-6 h-6 {{ $expense->type === 'income' ? 'text-green-600' : 'text-red-600' }}"></i>

                                <div
                                    class="absolute -top-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center bg-white shadow-sm border border-gray-100">
                                    <i data-lucide="{{ $expense->type === 'income' ? 'arrow-down' : 'arrow-up' }}"
                                        class="w-3 h-3 {{ $expense->type === 'income' ? 'text-green-600' : 'text-red-600' }}"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $expense->title }}</h3>
                                <p class="text-xs text-gray-400 uppercase font-semibold">Dia {{ $expense->due_day }} • R$
                                    {{ number_format($expense->amount, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <span
                            class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusConfig['class'] }}">
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                        <div class="flex items-center gap-1">
                            <button wire:click="edit({{ $expense->id }})"
                                class="p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                                <i data-lucide="edit-3" class="w-5 h-5"></i>
                            </button>
                            <button wire:click="delete({{ $expense->id }})"
                                wire:confirm="Tem certeza que deseja excluir este gasto fixo?"
                                class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>

                        @if($statusConfig['button'])
                            <button wire:click="openPaymentModal({{ $expense->id }})"
                                class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm active:scale-95">
                                <i data-lucide="check" class="w-3 h-3"></i>
                                {{ $expense->type === 'income' ? 'Registrar' : 'Dar Baixa' }}
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-200">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="calendar-days" class="w-10 h-10 text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Nada fixo por aqui</h3>
                    <p class="text-gray-500 text-sm mb-6">Cadastre seus gastos ou recebimentos recorrentes para não
                        esquecer.</p>
                    <button wire:click="openModal"
                        class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-lg active:scale-95">
                        Adicionar Primeiro
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    @include('livewire.partials.fixed-expense-payment-modal')

    @include('livewire.partials.fixed-expense-form-modal')
</div>