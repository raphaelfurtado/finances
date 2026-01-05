<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="bg-indigo-600 px-6 py-6 pb-12">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-xl font-bold text-white">Alertas de Limites</h1>
        </div>
        <p class="text-indigo-100 text-sm">Defina tetos de gastos para suas categorias e seja avisado quando estiver
            perto de estourar.</p>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto px-6 -mt-8 pb-20">
        @if(!auth()->user()->isPremium())
            <!-- PAYWALL -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-amber-400 to-yellow-500"></div>

                <div
                    class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="lock" class="w-8 h-8"></i>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">Recurso Premium</h2>
                <p class="text-gray-500 mb-6">
                    Tenha controle total com Alertas de Limites. Defina tetos de gastos e nunca mais gaste mais do que
                    planejou.
                </p>

                <div class="space-y-3 mb-8">
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i data-lucide="check" class="w-5 h-5 text-green-500"></i> Limites personalizáveis
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i data-lucide="check" class="w-5 h-5 text-green-500"></i> Alertas visuais
                    </div>
                </div>

                <a href="#"
                    class="block w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-colors shadow-lg shadow-amber-200">
                    Desbloquear Premium Agora
                </a>
            </div>

            <!-- Blurred Background (Preview) -->
            <div class="opacity-30 blur-sm pointer-events-none mt-4 select-none" aria-hidden="true">
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl p-4 border border-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl"></div>
                                <div class="w-32 h-4 bg-gray-200 rounded"></div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="w-3/4 h-2.5 bg-blue-500 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- Success Message -->
            @if (session()->has('message'))
                <div
                    class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span class="text-sm font-medium">{{ session('message') }}</span>
                </div>
            @endif

            <!-- Summary Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mb-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Orçamento Mensal</h2>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-black text-gray-900">R$
                                {{ number_format($totalSpent, 2, ',', '.') }}</span>
                            <span class="text-gray-400 text-sm font-medium">/ R$
                                {{ number_format($totalBudget, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $totalPercentage >= 100 ? 'bg-red-50 text-red-600' : 'bg-indigo-50 text-indigo-600' }}">
                        <i data-lucide="{{ $totalPercentage >= 100 ? 'alert-circle' : 'trending-down' }}"
                            class="w-6 h-6"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="h-3 rounded-full transition-all duration-700 ease-out {{ $totalPercentage >= 100 ? 'bg-red-500' : ($totalPercentage >= 80 ? 'bg-amber-500' : 'bg-indigo-600') }}"
                            style="width: {{ min($totalPercentage, 100) }}%">
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold {{ $totalPercentage >= 100 ? 'text-red-600' : 'text-indigo-600' }}">
                            {{ number_format($totalPercentage, 1) }}% consumido
                        </span>
                        <span class="text-xs text-gray-400 font-medium tracking-tight">
                            Restante: R$ {{ number_format(max(0, $totalBudget - $totalSpent), 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($budgetItems as $item)
                    @php
                        $isSelected = $selectedCategoryId == $item['category_id'];
                    @endphp
                    <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 transition-all {{ $isSelected ? 'ring-2 ring-indigo-500 bg-indigo-50/10' : '' }}"
                        wire:key="budget-item-{{ $item['category']->id }}">

                        <!-- Main Card Content -->
                        <div class="p-4 cursor-pointer" wire:click="showCategoryDetails({{ $item['category_id'] }})">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                        style="background-color: {{ $item['category']->color ?? '#3B82F6' }}20; color: {{ $item['category']->color ?? '#3B82F6' }}">
                                        <i data-lucide="{{ $item['category']->icon ?? 'circle' }}" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $item['category']->name }}</h3>
                                        @if($editingCategoryId === $item['category']->id)
                                            <span class="text-xs text-indigo-600 font-medium tracking-tight">Definindo novo
                                                limite...</span>
                                        @else
                                            <span class="text-xs text-gray-400">Gasto Atual: R$
                                                {{ number_format($item['spent'], 2, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Edit Action -->
                                <div class="flex items-center gap-2">
                                    @if($editingCategoryId !== $item['category']->id)
                                        <button wire:click.stop="editBudget({{ $item['category']->id }}, {{ $item['limit'] }})"
                                            class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors border border-gray-100 bg-gray-50/50">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                        <div class="text-gray-300">
                                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if(!$isSelected && $editingCategoryId !== $item['category']->id)
                                <div class="flex items-center justify-end -mt-1 mb-2">
                                    <span class="text-[9px] font-black text-indigo-400 uppercase tracking-widest flex items-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                        <i data-lucide="eye" class="w-3 h-3"></i> Detalhes
                                    </span>
                                </div>
                            @endif

                            <!-- Edit Form or Progress -->
                            @if($editingCategoryId === $item['category']->id)
                                <div wire:click.stop="">
                                    <form wire:submit.prevent="saveBudget"
                                        class="mt-2 text-left bg-white p-3 rounded-xl border border-indigo-100 shadow-inner">
                                        <label
                                            class="block text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1">Novo
                                            Limite (R$)</label>
                                        <div class="flex items-center gap-2">
                                            <input type="number" step="0.01" inputmode="decimal" wire:model="editingAmount"
                                                class="flex-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 font-bold"
                                                placeholder="0,00" autofocus>

                                            <button type="submit"
                                                class="p-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors shadow-sm">
                                                <i data-lucide="check" class="w-5 h-5"></i>
                                            </button>
                                            <button type="button" wire:click="cancelEdit"
                                                class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                                                <i data-lucide="x" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                        @error('editingAmount') <span
                                            class="text-[10px] text-red-500 mt-1 block font-bold uppercase">{{ $message }}</span>
                                        @enderror
                                    </form>
                                </div>
                            @else
                                @if($item['limit'] > 0)
                                    <div class="mt-2">
                                        <div class="flex justify-between text-[10px] mb-1 font-bold uppercase tracking-tight">
                                            <span
                                                class="{{ $item['percentage'] >= 100 ? 'text-red-600' : ($item['percentage'] >= 80 ? 'text-yellow-600' : 'text-indigo-600') }}">
                                                {{ number_format($item['percentage'], 1) }}% usado
                                            </span>
                                            <span class="text-gray-400">Teto: R$ {{ number_format($item['limit'], 2, ',', '.') }}</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden shadow-inner">
                                            <div class="h-2 rounded-full transition-all duration-700 ease-out {{ $item['percentage'] >= 100 ? 'bg-red-500' : ($item['percentage'] >= 80 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                                style="width: {{ min($item['percentage'], 100) }}%">
                                            </div>
                                        </div>
                                        @if($item['percentage'] >= 100)
                                            <p
                                                class="text-[10px] text-red-600 mt-1.5 font-bold uppercase flex items-center gap-1 leading-none tracking-tight">
                                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> Teto estourado!
                                            </p>
                                        @elseif($item['percentage'] >= 80)
                                            <p
                                                class="text-[10px] text-yellow-600 mt-1.5 font-bold uppercase flex items-center gap-1 leading-none tracking-tight">
                                                <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i> Atenção, perto do limite.
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-2 p-3 bg-gray-50 rounded-xl border border-dashed border-gray-200 text-center">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nenhum teto
                                            definido</span>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Details Section (Accordion) -->
                        @if($isSelected && !$editingCategoryId)
                            <div class="px-4 pb-4 animate-in fade-in slide-in-from-top-2 duration-300">
                                <div class="bg-gray-50/50 rounded-xl p-3 border border-gray-100">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Transações deste
                                        Mês</p>
                                    <div class="space-y-2">
                                        @forelse($categoryTransactions as $transaction)
                                            <div
                                                class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl shadow-sm text-xs transition-transform hover:scale-[1.01]">
                                                <div class="flex items-center gap-3">
                                                    <div class="text-center font-bold text-gray-400 leading-tight">
                                                        {{ $transaction->date->format('d') }}<br>
                                                        <span
                                                            class="text-[8px] uppercase tracking-tighter">{{ $transaction->date->locale('pt_BR')->isoFormat('MMM') }}</span>
                                                    </div>
                                                    <div class="border-l border-gray-100 h-6 mx-1"></div>
                                                    <div>
                                                        <p class="font-bold text-gray-800">{{ $transaction->description }}</p>
                                                        <p class="text-[9px] text-gray-400 uppercase font-medium">
                                                            {{ $transaction->payment_method ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-bold text-red-600 whitespace-nowrap">R$
                                                        {{ number_format($transaction->amount, 2, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-[10px] text-gray-400 italic text-center py-4">Nenhuma transação registrada
                                                neste mês.</p>
                                        @endforelse
                                    </div>
                                    <div class="text-center mt-3">
                                        <button wire:click.stop="closeCategoryDetails"
                                            class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline px-4 py-2">Recolher</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-10 bg-white rounded-3xl border border-gray-100 border-dashed">
                        <i data-lucide="inbox" class="w-12 h-12 text-gray-200 mx-auto mb-2"></i>
                        <p class="text-gray-400 font-medium">Nenhuma categoria encontrada.</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>


    <script>
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
    </script>
</div>