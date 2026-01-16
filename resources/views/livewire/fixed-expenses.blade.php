<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="flex items-center px-6 py-4 bg-white border-b border-gray-100">
        <a href="/dashboard" class="p-2 -ml-2 text-gray-800 hover:text-gray-600">
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </a>
        <h1 class="flex-1 text-center text-xl font-bold text-gray-900 pr-8">Fixos</h1>
        <button wire:click="openModal" class="p-2 text-blue-600 hover:text-blue-700">
            <i data-lucide="plus-circle" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Filters -->
    <div class="px-6 py-3 bg-white border-b border-gray-100 overflow-x-auto">
        <div class="flex items-center gap-2 min-w-max">
            <button wire:click="$set('activeFilter', 'all')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $activeFilter === 'all' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
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

    <!-- Financial Summary Cards -->
    <div class="px-6 pt-6 pb-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Expenses Card -->
            <div
                class="bg-white rounded-3xl p-6 shadow-sm border {{ $this->totalFixedExpense > 0 ? 'border-red-200' : 'border-gray-100' }} relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Custo de
                                Sobrevivência</h2>
                            <h3 class="text-3xl font-bold text-gray-900">
                                R$ {{ number_format($this->totalFixedExpense, 2, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600">
                            <i data-lucide="anchor" class="w-6 h-6"></i>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600">
                        Comprometimento mensal com <strong class="text-gray-900">{{ $this->activeExpenseCount }} contas
                            ativas</strong>.
                    </p>
                </div>
                <!-- Background Decoration -->
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-red-50 rounded-full blur-3xl opacity-50"></div>
            </div>

            <!-- Income Card -->
            <div
                class="bg-white rounded-3xl p-6 shadow-sm border {{ $this->totalFixedIncome > 0 ? 'border-green-200' : 'border-gray-100' }} relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Receita Recorrente
                            </h2>
                            <h3 class="text-3xl font-bold text-gray-900">
                                R$ {{ number_format($this->totalFixedIncome, 2, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                            <i data-lucide="trending-up" class="w-6 h-6"></i>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600">
                        Valor total garantido por <strong class="text-gray-900">{{ $this->activeIncomeCount }} fontes de
                            renda</strong>.
                    </p>
                </div>
                <!-- Background Decoration -->
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-green-50 rounded-full blur-3xl opacity-50"></div>
            </div>
        </div>
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
                                class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
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
                                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm active:scale-95">
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
                        class="px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg active:scale-95">
                        Adicionar Primeiro
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Payment Confirmation Modal -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closePaymentModal"></div>

            <div
                class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Registrar Pagamento</h2>
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
                        <p class="text-sm text-gray-500">Confirme o valor para registrar no sistema.</p>
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

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeModal"></div>

            <div
                class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 max-h-[90vh] flex flex-col">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between flex-none bg-white z-20">
                    <h2 class="text-xl font-bold text-gray-900">{{ $editingId ? 'Editar' : 'Novo' }} Fixo</h2>
                    <button wire:click="closeModal" class="p-2 text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <!-- Type Toggle -->
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex gap-2 bg-gray-100 p-1 rounded-xl">
                            <button type="button" wire:click="$set('type', 'expense')"
                                class="flex-1 py-2.5 rounded-lg font-bold text-xs transition-all {{ $type === 'expense' ? 'bg-red-500 text-white shadow-md' : 'text-gray-600' }}">
                                Despesa
                            </button>
                            <button type="button" wire:click="$set('type', 'income')"
                                class="flex-1 py-2.5 rounded-lg font-bold text-xs transition-all {{ $type === 'income' ? 'bg-green-500 text-white shadow-md' : 'text-gray-600' }}">
                                Recebível
                            </button>
                        </div>
                    </div>

                    <form wire:submit.prevent="save" class="p-6 space-y-4">
                        <!-- Premium Upsell Alert -->
                        @if (session()->has('error'))
                            <div
                                class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white text-center mb-6 shadow-xl relative overflow-hidden">
                                <!-- Decor -->
                                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-white/10 rounded-full blur-2xl">
                                </div>
                                <div class="absolute bottom-0 left-0 -ml-4 -mb-4 w-20 h-20 bg-black/10 rounded-full blur-xl">
                                </div>

                                <div class="relative z-10 flex flex-col items-center gap-3">
                                    <div
                                        class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm mb-1">
                                        <i data-lucide="crown" class="w-6 h-6 text-yellow-300 fill-current"></i>
                                    </div>

                                    <h3 class="font-bold text-lg leading-tight">Limite Gratuito Atingido</h3>
                                    <p class="text-indigo-100 text-sm mb-2">
                                        Usuários gratuitos podem cadastrar apenas 1 item fixo.
                                    </p>

                                    <a href="/upgrade"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition-all shadow-lg active:scale-95 mt-2 w-full justify-center">
                                        <span>Seja Premium Agora</span>
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </a>

                                    <p class="text-[10px] text-indigo-200 mt-2">
                                        Cancele quando quiser. Acesso imediato.
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Título</label>
                            <input type="text" wire:model="title"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all"
                                placeholder="Ex: Aluguel, Netflix...">
                            @error('title') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Valor (R$)</label>
                                <input type="number" step="0.01" inputmode="decimal" wire:model="amount"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-mono"
                                    placeholder="0,00">
                                @error('amount') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Dia Vencimento</label>
                                <input type="number" inputmode="numeric" pattern="[0-9]*" wire:model="due_day"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all"
                                    placeholder="1 a 31">
                                @error('due_day') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Categoria</label>
                            <select wire:model="category_id"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all appearance-none">
                                <option value="">Selecione uma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->full_name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Descrição (Opcional)</label>
                            <textarea wire:model="description"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all"
                                rows="3" placeholder="Mais detalhes..."></textarea>
                        </div>

                        <div class="flex items-center gap-3 py-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                            <span class="text-sm font-medium text-gray-700">Ativo</span>
                        </div>

                        @if(!$editingId)
                            <div class="flex items-center gap-3 py-2 bg-blue-50 px-4 rounded-2xl border border-blue-100">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="mark_as_paid_now" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                                    </div>
                                </label>
                                <div class="flex-1">
                                    <span class="text-sm font-bold text-gray-900 block">Já paguei este mês</span>
                                    <span class="text-xs text-gray-600">Vincular transação existente</span>
                                </div>
                            </div>
                        @endif

                        <div class="pt-4 flex flex-col gap-3">
                            <button type="submit"
                                class="w-full py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2">
                                <i data-lucide="save" class="w-5 h-5"></i>
                                Salvar Transação Fixa
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="w-full py-4 text-gray-500 font-bold hover:bg-gray-50 rounded-2xl transition-all">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>