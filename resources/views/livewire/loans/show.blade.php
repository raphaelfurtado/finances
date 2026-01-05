<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
    x-data="{ openBulk: @entangle('showBulkModal'), openInfo: @entangle('showInfoModal') }">
    <div class="mb-8 flex justify-between items-center">
        <a href="{{ route('loans.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm inline-block">
            ← Voltar para Empréstimos
        </a>

        <div class="flex gap-2">
            <button wire:click="openBulkModal"
                class="bg-gray-100 p-2 rounded-lg text-gray-500 hover:text-blue-600 transition"
                title="Alterar dia de vencimento">
                <i data-lucide="calendar-range" class="w-5 h-5"></i>
            </button>
            <button wire:click="$set('showInfoModal', true)"
                class="bg-gray-100 p-2 rounded-lg text-gray-500 hover:text-blue-600 transition"
                title="Editar Informações">
                <i data-lucide="edit-3" class="w-5 h-5"></i>
            </button>
            @if(auth()->user()->isPremium())
                <button wire:confirm="Tem certeza que deseja excluir este empréstimo? Esta ação não pode ser desfeita."
                    wire:click="deleteLoan" class="bg-gray-100 p-2 rounded-lg text-gray-500 hover:text-red-600 transition"
                    title="Excluir Empréstimo">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </button>
            @else
                <button wire:click="$dispatch('show-premium-upsell')"
                    class="bg-gray-100 p-2 rounded-lg text-gray-300 cursor-not-allowed border border-dashed border-gray-300"
                    title="Excluir (Exclusivo Premium)">
                    <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('success'))
        <div
            class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Loan Header -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $loan->counterparty_name }}</h1>
                <p class="text-gray-500 mt-1 flex items-center gap-2">
                    @if($loan->type === 'receivable')
                        <span
                            class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Crédito</span>
                    @else
                        <span
                            class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Débito</span>
                    @endif
                    @if($loan->counterparty_phone)
                        <span class="text-gray-400">•</span>
                        <span class="text-sm">{{ $loan->counterparty_phone }}</span>
                    @endif
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-4 py-2 rounded-full text-sm font-medium
                    @if($loan->status === 'active') bg-blue-100 text-blue-700
                    @elseif($loan->status === 'paid') bg-green-100 text-green-700
                    @else bg-red-100 text-red-700
                    @endif">
                    @if($loan->status === 'active') Ativo
                    @elseif($loan->status === 'paid') Pago
                    @else {{ ucfirst($loan->status) }}
                    @endif
                </span>
                @if(!$loan->installments()->whereIn('status', ['paid', 'refinanced'])->exists() && $loan->paid_amount == 0)
                    <a href="{{ route('loans.edit', $loan) }}"
                        class="bg-gray-100 p-2 rounded-lg text-gray-500 hover:text-blue-600 transition"
                        title="Configurações Contratuais">
                        <i data-lucide="settings" class="w-5 h-5"></i>
                    </a>
                @endif
            </div>
        </div>

        @if($loan->description || $loan->notes)
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($loan->description)
                    <div class="text-sm">
                        <span class="block text-gray-400 font-medium uppercase text-[10px] mb-1">Descrição</span>
                        <p class="text-gray-700">{{ $loan->description }}</p>
                    </div>
                @endif
                @if($loan->notes)
                    <div class="text-sm">
                        <span class="block text-gray-400 font-medium uppercase text-[10px] mb-1">Observações Privadas</span>
                        <p class="text-gray-700 bg-amber-50 p-2 rounded-lg border border-amber-100">{{ $loan->notes }}</p>
                    </div>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Valor Principal</p>
                <p class="text-2xl font-black text-gray-900">R$
                    {{ number_format($loan->principal_amount, 2, ',', '.') }}
                </p>
                <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">
                    {{ match ($loan->installment_frequency) {
    'weekly' => 'Semanal',
    'biweekly' => 'Quinzenal',
    'monthly' => 'Mensal',
    'bimonthly' => 'Bimestral',
    'quarterly' => 'Trimestral',
    'yearly' => 'Anual',
    default => 'Mensal'
} }}
                </p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100 shadow-sm">
                <div class="flex justify-between items-center mb-1">
                    <p class="text-xs text-blue-600 font-bold uppercase tracking-wider">Valor Total</p>
                    <span
                        class="text-[10px] bg-blue-200 text-blue-800 px-1.5 py-0.5 rounded font-bold">{{ number_format($loan->interest_rate, 2, ',', '.') }}%</span>
                </div>
                <p class="text-2xl font-black text-blue-800">R$ {{ number_format($loan->total_amount, 2, ',', '.') }}
                </p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border border-green-100 shadow-sm">
                <p class="text-xs text-green-600 font-bold uppercase tracking-wider mb-1">Total Recebido</p>
                <p class="text-2xl font-black text-green-800">R$ {{ number_format($loan->paid_amount, 2, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-4">
            <div class="flex justify-between text-xs font-bold text-gray-500 mb-2 uppercase tracking-widest">
                <span>Progresso</span>
                <span>{{ $loan->progress }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-4 p-1 border border-gray-200">
                <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-green-500 h-2 rounded-full transition-all duration-700 shadow-sm shadow-blue-200"
                    style="width: {{ $loan->progress }}%"></div>
            </div>
        </div>
    </div>

    <!-- Installments List -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm overflow-hidden">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">Plano de Pagamento</h2>
            <span class="text-sm font-medium text-gray-400">{{ $loan->installments_count }} parcelas</span>
        </div>

        <div class="space-y-4">
            @foreach($loan->installments as $installment)
                <div class="group relative bg-white border border-gray-200 rounded-2xl p-4 transition-all hover:border-blue-400 hover:shadow-xl shadow-blue-100/20
                                                        @if($installment->status === 'paid') bg-green-50/30 border-green-100
                                                        @elseif($installment->status === 'refinanced') bg-purple-50/30 border-purple-100
                                                        @elseif($installment->isOverdue()) bg-red-50/30 border-red-100
                                                        @endif">

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <!-- Left Side: Basic Info -->
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm
                                                                    @if($installment->status === 'paid') bg-green-100 text-green-700
                                                                    @elseif($installment->status === 'refinanced') bg-purple-100 text-purple-700
                                                                    @elseif($installment->isOverdue()) bg-red-100 text-red-700
                                                                    @else bg-gray-100 text-gray-600
                                                                    @endif">
                                {{ $installment->installment_number }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-gray-900">R$
                                        {{ number_format((float) $installment->amount, 2, ',', '.') }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                                                            @if($installment->status === 'paid') bg-green-100 text-green-700
                                                                            @elseif($installment->isOverdue()) bg-red-100 text-red-700
                                                                            @elseif($installment->status === 'partial') bg-yellow-100 text-yellow-700
                                                                            @else bg-gray-100 text-gray-700
                                                                            @endif">
                                        @if($installment->status === 'paid') Pago
                                        @elseif($installment->status === 'refinanced') Renegociada
                                        @elseif($installment->isOverdue()) Atrasada
                                        @elseif($installment->status === 'partial') Parcial
                                        @else Pendente
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="text-xs {{ $installment->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                        Vencimento:
                                        {{ \Illuminate\Support\Carbon::parse($installment->due_date)->format('d/m/Y') }}
                                    </span>
                                    @if($installment->status !== 'paid' && $installment->status !== 'refinanced')
                                        <button wire:click="editInstallment({{ $installment->id }})"
                                            class="p-1 text-gray-300 hover:text-blue-600 transition-colors">
                                            <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Status/Actions -->
                        <div
                            class="flex items-center justify-between md:justify-end gap-3 border-t md:border-0 pt-3 md:pt-0">
                            @if($installment->isOverdue() && $installment->current_amount > $installment->amount)
                                <div class="text-right">
                                    <p class="text-[10px] text-red-500 font-bold uppercase tracking-tight">Com Penalidades</p>
                                    <p class="text-sm font-black text-red-600 italic">R$
                                        {{ number_format((float) $installment->current_amount, 2, ',', '.') }}
                                    </p>
                                </div>
                            @endif

                            <div class="flex gap-2">
                                @if($installment->status !== 'paid' && $installment->status !== 'refinanced')
                                    @if($loan->type === 'receivable' && $loan->counterparty_phone)
                                        <a href="{{ $this->getWhatsAppLink($installment) }}" target="_blank"
                                            class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition shadow-sm border border-green-100 flex items-center justify-center">

                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                            </svg>

                                        </a>
                                    @endif

                                    <button wire:click="openRolloverModal({{ $installment->id }})"
                                        class="p-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition shadow-sm border border-purple-100 flex items-center justify-center"
                                        title="Rolar Dívida">
                                        <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                                    </button>

                                    <button
                                        wire:click="recordPayment({{ $installment->id }}, {{ $installment->current_amount - $installment->paid_amount }})"
                                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-xl font-bold transition shadow-lg shadow-blue-100">
                                        Baixar
                                    </button>
                                @elseif($installment->status === 'refinanced')
                                    <div
                                        class="px-3 py-1.5 bg-purple-50 rounded-xl border border-purple-100 text-purple-600 text-xs font-bold text-center">
                                        Renegociada
                                    </div>
                                @else
                                    <div
                                        class="flex items-center gap-3 bg-green-50 px-3 py-1.5 rounded-xl border border-green-100">
                                        <div class="flex flex-col text-right">
                                            <span class="text-[10px] text-green-600 font-bold uppercase">Pago em</span>
                                            <span
                                                class="text-xs text-green-700">{{ $installment->paid_at ? \Illuminate\Support\Carbon::parse($installment->paid_at)->format('d/m/Y') : '-' }}</span>
                                        </div>
                                        <button wire:click="reversePayment({{ $installment->id }})"
                                            class="text-gray-300 hover:text-red-500 transition-colors p-1" title="Estornar">
                                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal: Editar Informações -->
    <div x-show="openInfo" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-100"
            x-on:click.away="openInfo = false">
            <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-black text-gray-900">Editar Cadastro</h3>
                <button x-on:click="openInfo = false" class="text-gray-400 hover:text-gray-600 transition">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form wire:submit.prevent="saveInfo" class="p-8 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nome do
                        Contato</label>
                    <input type="text" wire:model="loanName"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">WhatsApp/Telefone</label>
                    <input type="text" wire:model="loanPhone"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Descrição
                        Curta</label>
                    <input type="text" wire:model="loanDescription"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Notas
                        Privadas</label>
                    <textarea wire:model="loanNotes" rows="3"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"></textarea>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" x-on:click="openInfo = false"
                        class="flex-1 py-3 text-gray-500 font-bold hover:bg-gray-100 rounded-xl transition">Cancelar</button>
                    <button type="submit"
                        class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition flex items-center justify-center gap-2"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Salvar Alterações</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Salvando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Editar Parcela Individual -->
    @if($editingInstallment)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden border border-red-100">
                <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="font-bold">Parcela #{{ $editingInstallment->installment_number }}</h3>
                    <button wire:click="cancelInstallmentEdit" class="text-blue-100 hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveInstallment" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Vencimento</label>
                        <input type="date" wire:model="instDueDate"
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Valor da Parcela</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-400 text-sm">R$</span>
                            <input type="number" step="0.01" wire:model="instAmount"
                                class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg font-bold">
                        </div>
                    </div>

                    @if($editingInstallment->isOverdue())
                        <div class="flex items-center gap-2 bg-amber-50 p-3 rounded-xl border border-amber-100">
                            <input type="checkbox" wire:model="instWaiveFees" id="waiveFees"
                                class="w-4 h-4 text-blue-600 rounded">
                            <label for="waiveFees" class="text-xs font-bold text-amber-800 cursor-pointer">Isentar
                                Multas/Juros</label>
                        </div>
                    @endif

                    <div class="pt-2 flex flex-col gap-2">
                        <button type="submit"
                            class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition flex items-center justify-center gap-2"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Salvar Alteração</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Salvando...
                            </span>
                        </button>
                        <button type="button" wire:click="cancelInstallmentEdit"
                            class="w-full py-2 text-gray-400 text-sm font-medium">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal: Vencimento em Massa -->
    <div x-show="openBulk" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden border border-gray-100"
            x-on:click.away="openBulk = false">
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="calendar-days" class="w-8 h-8 text-blue-600"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Alterar Vencimentos</h3>
                <p class="text-gray-500 text-sm mb-6">Escolha o novo dia de vencimento para todas as parcelas
                    <b>pendentes</b>.
                </p>

                <div class="flex items-center justify-center gap-4 mb-8">
                    <span class="text-sm font-bold text-gray-400 uppercase">Todo Dia</span>
                    <input type="number" wire:model="newInstallmentDay" min="1" max="31"
                        class="w-20 text-center text-2xl font-black text-blue-600 bg-blue-50 border-2 border-blue-200 rounded-2xl py-3 focus:ring-0">
                </div>

                <div class="flex flex-col gap-3">
                    <button wire:click="applyBulkUpdate"
                        class="w-full py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition flex items-center justify-center gap-2"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Atualizar Pendentes</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Atualizando...
                        </span>
                    </button>
                    <button x-on:click="openBulk = false"
                        class="w-full py-2 text-gray-400 font-medium">Descartar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Rolar Dívida -->
    @if($showRolloverModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden border border-purple-100">
                <div class="bg-purple-600 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="font-bold flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                        Rolar Dívida
                    </h3>
                    <button wire:click="$set('showRolloverModal', false)" class="text-purple-100 hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-purple-50 p-4 rounded-xl border border-purple-100 text-sm mb-4">
                        <p class="text-purple-900 mb-2 font-medium">Resumo da Operação:</p>
                        <ul class="space-y-1 text-purple-700">
                            <li>• A parcela atual será marcada como <span class="font-bold">Renegociada</span>.</li>
                            <li>• Uma nova parcela será criada com o saldo devedor atualizado + juros.</li>
                        </ul>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Entrada (Pagamento
                            Parcial)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-400 text-sm">R$</span>
                            <input type="number" step="0.01" wire:model.live.debounce.500ms="rolloverUpfrontPayment"
                                class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">Opcional. Este valor será abatido antes do novo cálculo.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Novo Vencimento</label>
                        <input type="date" wire:model="rolloverNewDate"
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Taxa de Juros (%)</label>
                        <input type="number" step="0.01" wire:model.live.debounce.500ms="rolloverNewRate"
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Simulação Nova Parcela</p>
                        <p class="text-2xl font-black text-gray-900">R$
                            {{ number_format($rolloverPreviewAmount, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            (Saldo Atualizado + {{ $rolloverNewRate }}% Juros)
                        </p>
                    </div>

                    <div class="pt-2 flex flex-col gap-2">
                        <button wire:click="confirmRollover"
                            class="w-full py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition shadow-lg shadow-purple-100 flex items-center justify-center gap-2"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Confirmar Rolagem</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Processando...
                            </span>
                        </button>
                        <button wire:click="$set('showRolloverModal', false)"
                            class="w-full py-2 text-gray-400 text-sm font-medium">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif


</div>