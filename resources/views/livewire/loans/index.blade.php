<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-32" x-data="{ openUpsell: false }">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 leading-tight">Organizador de dívidas pessoais</h1>
            <p class="text-xs font-semibold text-gray-500 flex items-center gap-1 mt-1">
                @if($isPremium)
                    <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded text-[10px] uppercase">Premium</span>
                    <span class="text-gray-400">•</span>
                    Registros: {{ $totalLoansCount }} / ∞
                @else
                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[10px] uppercase">Grátis</span>
                    <span class="text-gray-400">•</span>
                    Registros: {{ $totalLoansCount }} / {{ $loanLimit }}
                @endif
            </p>
        </div>

        @if($canCreate)
            <a href="{{ route('loans.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-sm">
                + Novo
            </a>
        @else
            <button x-on:click="openUpsell = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-sm">
                + Novo
            </button>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div
            class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-6 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium mb-1">🔴 Você Deve</p>
                    <p class="text-3xl font-bold text-red-700">R$ {{ number_format($totalPayable, 2, ',', '.') }}</p>
                </div>
                <div class="bg-red-200 p-3 rounded-full text-red-600">
                    <i data-lucide="trending-down" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-1">🟢 Devem a Você</p>
                    <p class="text-3xl font-bold text-green-700">R$ {{ number_format($totalReceivable, 2, ',', '.') }}
                    </p>
                </div>
                <div class="bg-green-200 p-3 rounded-full text-green-600">
                    <i data-lucide="trending-up" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-4 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-600 text-[10px] font-bold uppercase tracking-wider mb-1">Pago no Mês</p>
                        <p class="text-xl font-bold text-orange-700">R$ {{ number_format($monthlyPaid, 2, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-[10px] font-bold uppercase tracking-wider mb-1">Recebido no Mês</p>
                        <p class="text-xl font-bold text-blue-700">R$ {{ number_format($monthlyReceived, 2, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loans List -->
    <div class="space-y-4">
        @forelse($loans as $loan)
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $loan->counterparty_name }}</h3>
                        <p class="text-sm text-gray-500">
                            @if($loan->type === 'receivable')
                                <span class="text-green-600 font-medium">A Receber</span>
                            @else
                                <span class="text-red-600 font-medium">A Pagar</span>
                            @endif
                            • {{ $loan->installments_count }} parcelas
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                                        @if($loan->status === 'active') bg-blue-100 text-blue-700
                                                        @elseif($loan->status === 'paid') bg-green-100 text-green-700
                                                        @else bg-red-100 text-red-700
                                                        @endif">
                        @if($loan->status === 'active') Ativo
                        @elseif($loan->status === 'paid') Pago
                        @else {{ ucfirst($loan->status) }}
                        @endif
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500">Valor Total</p>
                        <p class="text-lg font-semibold text-gray-900">R$
                            {{ number_format($loan->total_amount, 2, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Valor Pago</p>
                        <p class="text-lg font-semibold text-green-600">R$
                            {{ number_format($loan->paid_amount, 2, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>Progresso</span>
                        <span>{{ $loan->progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full transition-all"
                            style="width: {{ $loan->progress }}%"></div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('loans.show', $loan) }}"
                        class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                        Ver Detalhes →
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="book" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum empréstimo cadastrado</h3>
                <p class="mt-1 text-sm text-gray-500">Comece criando seu primeiro empréstimo.</p>
                <div class="mt-6">
                    @if($canCreate)
                        <a href="{{ route('loans.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            + Novo Empréstimo
                        </a>
                    @else
                        <button x-on:click="openUpsell = true"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            + Novo Empréstimo
                        </button>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <!-- Upsell Modal -->
    <div x-show="openUpsell" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" x-on:click="openUpsell = false"></div>

        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden" x-show="openUpsell"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4">

            <div class="bg-amber-500 p-8 text-white relative">
                <div class="absolute top-4 right-4">
                    <button x-on:click="openUpsell = false" class="text-white/80 hover:text-white transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4">
                    <i data-lucide="crown" class="w-10 h-10 text-white"></i>
                </div>
                <h2 class="text-2xl font-bold">Limite atingido!</h2>
                <p class="text-amber-50/80 text-sm mt-1">Você atingiu o limite do plano grátis.</p>
            </div>

            <div class="p-8">
                <div class="space-y-4 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 bg-green-100 p-1 rounded-full">
                            <i data-lucide="check" class="w-3 h-3 text-green-600"></i>
                        </div>
                        <p class="text-gray-600 text-sm"><b>Empréstimos ilimitados</b> ativos</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="mt-1 bg-green-100 p-1 rounded-full">
                            <i data-lucide="check" class="w-3 h-3 text-green-600"></i>
                        </div>
                        <p class="text-gray-600 text-sm">Acesso a <b>Planilhas & Relatórios</b></p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="mt-1 bg-green-100 p-1 rounded-full">
                            <i data-lucide="check" class="w-3 h-3 text-green-600"></i>
                        </div>
                        <p class="text-gray-600 text-sm"><b>Categorias ilimitadas</b> e ícones</p>
                    </div>
                </div>

                <a href="{{ route('upgrade') }}"
                    class="block w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-center transition-all shadow-lg shadow-indigo-200">
                    Seja Premium Agora
                </a>

                <button x-on:click="openUpsell = false"
                    class="block w-full mt-4 py-2 text-gray-400 text-sm font-medium hover:text-gray-600 transition-colors">
                    Gerenciar Empréstimos Atuais
                </button>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <x-bottom-nav active="loans" />
</div>