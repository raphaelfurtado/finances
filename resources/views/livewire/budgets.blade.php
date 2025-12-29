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
                
                <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="lock" class="w-8 h-8"></i>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Recurso Premium</h2>
                <p class="text-gray-500 mb-6">
                    Tenha controle total com Alertas de Limites. Defina tetos de gastos e nunca mais gaste mais do que planejou.
                </p>

                <div class="space-y-3 mb-8">
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i data-lucide="check" class="w-5 h-5 text-green-500"></i> Limites personalizáveis
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i data-lucide="check" class="w-5 h-5 text-green-500"></i> Alertas visuais
                    </div>
                </div>

                <a href="#" class="block w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-colors shadow-lg shadow-amber-200">
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

            <div class="space-y-4">
                @forelse($budgetItems as $item)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100"
                        wire:key="budget-item-{{ $item['category']->id }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                    style="background-color: {{ $item['category']->color ?? '#3B82F6' }}20; color: {{ $item['category']->color ?? '#3B82F6' }}">
                                    <i data-lucide="{{ $item['category']->icon ?? 'circle' }}" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $item['category']->name }}</h3>
                                    @if($editingCategoryId === $item['category']->id)
                                        <span class="text-xs text-indigo-600 font-medium">Editando...</span>
                                    @else
                                        <span class="text-xs text-gray-400">Gasto Atual: R$
                                            {{ number_format($item['spent'], 2, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Edit Action -->
                            <div>
                                @if($editingCategoryId === $item['category']->id)
                                    <div class="flex gap-2">
                                        <!-- Actions are inside form below -->
                                    </div>
                                @else
                                    <button wire:click="editBudget({{ $item['category']->id }}, {{ $item['limit'] }})"
                                        class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Edit Form or Progress -->
                        @if($editingCategoryId === $item['category']->id)
                            <form wire:submit.prevent="saveBudget" class="mt-2 text-left">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Novo Limite (R$)</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" step="0.01" wire:model="editingAmount"
                                        class="flex-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 font-bold"
                                        placeholder="0,00" autofocus>

                                    <button type="submit"
                                        class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-colors">
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                    </button>
                                    <button type="button" wire:click="cancelEdit"
                                        class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                                @error('editingAmount') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </form>
                        @else
                            @if($item['limit'] > 0)
                                <div class="mt-2">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span
                                            class="font-medium {{ $item['percentage'] >= 100 ? 'text-red-600' : ($item['percentage'] >= 80 ? 'text-yellow-600' : 'text-gray-600') }}">
                                            {{ number_format($item['percentage'], 1) }}% usado
                                        </span>
                                        <span class="text-gray-500">Limite: R$ {{ number_format($item['limit'], 2, ',', '.') }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="h-2.5 rounded-full transition-all duration-500 {{ $item['percentage'] >= 100 ? 'bg-red-500' : ($item['percentage'] >= 80 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                            style="width: {{ min($item['percentage'], 100) }}%">
                                        </div>
                                    </div>
                                    @if($item['percentage'] >= 100)
                                        <p class="text-xs text-red-500 mt-1 font-medium flex items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3 h-3"></i> Teto estourado!
                                        </p>
                                    @elseif($item['percentage'] >= 80)
                                        <p class="text-xs text-yellow-600 mt-1 font-medium flex items-center gap-1">
                                            <i data-lucide="alert-triangle" class="w-3 h-3"></i> Atenção, perto do limite.
                                        </p>
                                    @endif
                                </div>
                            @else
                                <div class="mt-2 p-3 bg-gray-50 rounded-xl border border-dashed border-gray-200 text-center">
                                    <span class="text-xs text-gray-400">Nenhum limite definido</span>
                                </div>
                            @endif
                        @endif
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-gray-500">Nenhuma categoria encontrada.</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>

    <!-- Bottom Navigation -->
    <x-bottom-nav active="budgets" />

    <script>
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
    </script>
</div>