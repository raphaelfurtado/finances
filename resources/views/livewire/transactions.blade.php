<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="/dashboard" class="p-2 -ml-2 text-gray-400 hover:text-gray-600">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-900">Transações</h1>
        </div>
        <a href="/transactions/new" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
            <i data-lucide="plus" class="w-5 h-5"></i>
        </a>
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
                <a href="/transactions/{{ $transaction->id }}"
                    class="grid grid-cols-3 gap-4 px-4 py-4 border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer last:border-0">
                    <div>
                        <div class="text-lg font-bold text-gray-900">{{ $transaction->date->format('d') }}</div>
                        <div class="text-xs text-gray-400 uppercase">
                            {{ $transaction->date->locale('pt_BR')->isoFormat('MMM') }}
                        </div>
                    </div>
                    <div>
                        <div class="font-bold text-gray-900 text-sm mb-0.5 truncate">{{ $transaction->description }}</div>
                        <div class="text-xs text-gray-400 truncate">{{ $transaction->category->name ?? 'Sem categoria' }}</div>
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
                <div class="px-4 py-12 text-center text-gray-400">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                    <p class="text-sm font-medium">Nenhuma transação encontrada</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
    
    
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>
