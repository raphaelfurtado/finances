<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100">
        <div class="flex items-center gap-3">
            <a href="/" class="p-2 -ml-2 text-gray-800 hover:text-gray-600">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-900">Detalhes</h1>
        </div>
        <a href="/transactions/{{ $transaction->id }}/edit"
            class="text-sm font-semibold text-blue-600 hover:text-blue-700">
            Editar
        </a>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto px-6 py-6">
        <!-- Transaction Header -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-24 h-24 rounded-full flex items-center justify-center mb-4 relative"
                style="background-color: {{ $transaction->category->color ?? '#3B82F6' }}20;">
                <i data-lucide="{{ $transaction->category->icon ?? 'circle' }}" class="w-12 h-12"
                    style="color: {{ $transaction->category->color ?? '#3B82F6' }};"></i>
                <div
                    class="absolute bottom-0 right-0 w-7 h-7 bg-green-500 rounded-full flex items-center justify-center border-2 border-white">
                    <i data-lucide="check" class="w-4 h-4 text-white"></i>
                </div>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mb-2">R$ {{ number_format($transaction->amount, 2, ',', '.') }}
            </h2>
            <p class="text-lg text-gray-600 mb-1">{{ $transaction->category->name ?? 'Sem categoria' }}</p>
            <p class="text-sm text-gray-400">{{ $transaction->date->format('d') }}
                {{ $transaction->date->locale('pt_BR')->isoFormat('MMM, YYYY') }} •
                {{ $transaction->date->format('H:i') }}</p>
        </div>

        <!-- Transaction Information -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-4">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Informações da Transação</h3>

            <div class="grid grid-cols-2 gap-4">
                <!-- Account -->
                <div>
                    <span class="text-xs font-medium text-gray-400 uppercase block mb-2">Conta</span>
                    <div class="flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-4 h-4 text-gray-600"></i>
                        <span class="text-sm font-medium text-gray-900">Nubank ****1234</span>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <span class="text-xs font-medium text-gray-400 uppercase block mb-2">Status</span>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($transaction->status) }}</span>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <span class="text-xs font-medium text-gray-400 uppercase block mb-2">Método</span>
                    <span class="text-sm font-medium text-gray-900">{{ ucfirst($transaction->payment_method) }}</span>
                </div>

                <!-- Recurring -->
                <div>
                    <span class="text-xs font-medium text-gray-400 uppercase block mb-2">Recorrente</span>
                    <span
                        class="text-sm font-medium text-gray-900">{{ $transaction->is_recurring ? 'Sim' : 'Não' }}</span>
                </div>
            </div>
        </div>

        <!-- Observation -->
        @if($transaction->observation)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-4">
                <div class="flex items-start gap-3">
                    <i data-lucide="align-left" class="w-5 h-5 text-gray-400 mt-0.5"></i>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Observação</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $transaction->observation }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Receipt -->
        @if($transaction->receipt_path)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i data-lucide="receipt" class="w-5 h-5 text-gray-400"></i>
                        <span class="text-sm font-bold text-gray-900">Comprovante</span>
                    </div>
                    <button class="p-2 text-gray-400 hover:text-gray-600">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="space-y-3 mt-6 mb-20">
            <a href="/transactions/{{ $transaction->id }}/edit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl flex items-center justify-center gap-2 shadow-lg shadow-blue-200 transition-all active:scale-95">
                <i data-lucide="edit-3" class="w-5 h-5"></i>
                Editar {{ $transaction->type === 'expense' ? 'Despesa' : 'Receita' }}
            </a>

            <button wire:click="delete" wire:confirm="Tem certeza que deseja excluir esta transação?"
                class="w-full bg-white hover:bg-red-50 text-red-600 font-bold py-4 rounded-2xl flex items-center justify-center gap-2 border-2 border-red-200 transition-all active:scale-95">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
                Excluir Transação
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>