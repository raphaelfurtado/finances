<div class="flex flex-col h-full bg-white">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <a href="/dashboard" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
            Cancelar
        </a>
        <h1 class="text-lg font-bold text-gray-900">{{ $transaction_id ? 'Editar' : 'Nova' }}
            {{ $type === 'expense' ? 'Despesa' : 'Receita' }}
        </h1>
        <button wire:click="save" wire:loading.attr="disabled"
            class="text-sm font-semibold text-blue-600 hover:text-blue-700 disabled:opacity-50">
            <span wire:loading.remove wire:target="save">Salvar</span>
            <span wire:loading wire:target="save">Salvando...</span>
        </button>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mx-6 mt-4 bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                <div class="flex-1">
                    <p class="font-semibold text-red-900 text-sm mb-1">Erro ao salvar</p>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Content -->
    <div class="flex-1 overflow-y-auto">
        <!-- Type Toggle -->
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex gap-2 bg-gray-100 p-1 rounded-xl">
                <button type="button" wire:click="$set('type', 'expense')"
                    class="flex-1 py-3 rounded-lg font-semibold text-sm transition-all {{ $type === 'expense' ? 'bg-red-500 text-white shadow-md' : 'text-gray-600' }}">
                    <div class="flex items-center justify-center gap-2">
                        <i data-lucide="arrow-down-circle" class="w-4 h-4"></i>
                        Despesa
                    </div>
                </button>
                <button type="button" wire:click="$set('type', 'income')"
                    class="flex-1 py-3 rounded-lg font-semibold text-sm transition-all {{ $type === 'income' ? 'bg-green-500 text-white shadow-md' : 'text-gray-600' }}">
                    <div class="flex items-center justify-center gap-2">
                        <i data-lucide="arrow-up-circle" class="w-4 h-4"></i>
                        Receita
                    </div>
                </button>
            </div>
        </div>

        <!-- Amount Input -->
        <div class="px-6 py-8 text-center border-b border-gray-100">
            <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Valor da
                {{ $type === 'expense' ? 'Despesa' : 'Receita' }}</label>
            <div class="flex items-center justify-center gap-2">
                <span class="text-3xl font-medium text-gray-400">R$</span>
                <input type="text" inputmode="decimal" wire:model="amount"
                    class="text-5xl font-bold text-blue-600 bg-transparent border-none outline-none text-center w-auto"
                    style="max-width: 300px;" placeholder="0,00">
            </div>
        </div>

        <!-- Description -->
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <i data-lucide="edit-3" class="w-5 h-5 text-gray-400"></i>
                <input type="text" wire:model="description" placeholder="Ex: Almoço, Uber..."
                    class="flex-1 text-base text-gray-900 placeholder-gray-400 bg-transparent border-none outline-none">
            </div>
        </div>

        <!-- Category Selection -->
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="mb-3 flex items-center justify-between">
                <span class="text-sm font-bold text-gray-900">Categoria</span>
                <a href="/categories" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Ver todas</a>
            </div>
            <!-- Category Driller -->
            <div x-data="{ 
                view: 'root', 
                activeParent: null,
                currentChildren: [],
                categories: {{ $categories->toJson(JSON_HEX_APOS | JSON_HEX_QUOT) }},
                
                selectCategory(category) {
                    if (category.children && category.children.length > 0) {
                        this.activeParent = category;
                        this.currentChildren = category.children;
                        this.view = 'children';
                        
                        this.$nextTick(() => {
                            lucide.createIcons();
                        });
                    } else {
                        $wire.set('category_id', category.id);
                    }
                },
                
                selectChild(child) {
                    $wire.set('category_id', child.id);
                },
                
                goBack() {
                    this.view = 'root';
                    this.activeParent = null;
                    this.currentChildren = [];
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                },

                init() {
                    
                }
            }" class="relative transition-all duration-300">

                <!-- Root View -->
                <div x-show="view === 'root'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-x-full"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="flex gap-3 overflow-x-auto pb-2 -mx-6 px-6" style="scrollbar-width: none;">

                    @foreach($categories as $category)
                        <button type="button" @click="selectCategory(categories[{{ $loop->index }}])"
                            class="flex-shrink-0 flex flex-col items-center gap-2 p-3 rounded-xl transition-all border-2
                                                        {{ $category_id == $category->id ? 'bg-blue-600 border-blue-600 shadow-md transform scale-105' : 'bg-gray-50 border-transparent hover:bg-gray-100' }}">

                            <div class="w-12 h-12 rounded-xl flex items-center justify-center relative"
                                style="background-color: {{ $category_id == $category->id ? 'rgba(255,255,255,0.2)' : $category->color . '20' }};">
                                <i data-lucide="{{ $category->icon }}" class="w-6 h-6"
                                    style="color: {{ $category_id == $category->id ? 'white' : $category->color }};"></i>

                                <!-- Indicator if has children -->
                                @if($category->children->count() > 0)
                                    <div
                                        class="absolute -bottom-1 -right-1 w-5 h-5 bg-white rounded-full flex items-center justify-center shadow-sm">
                                        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
                                    </div>
                                @endif
                            </div>

                            <span
                                class="text-xs font-bold max-w-[80px] truncate {{ $category_id == $category->id ? 'text-white' : 'text-gray-600' }}">
                                {{ $category->name }}
                            </span>
                        </button>
                    @endforeach
                </div>

                <!-- Children View -->
                <div x-show="view === 'children'" style="display: none;"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-full"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="flex gap-3 overflow-x-auto pb-2 -mx-6 px-6" style="scrollbar-width: none;">

                    <!-- Back Button -->
                    <button type="button" @click="goBack()"
                        class="flex-shrink-0 flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-100 hover:bg-gray-200 transition-all border-2 border-transparent">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gray-200 text-gray-600">
                            <i data-lucide="arrow-left" class="w-6 h-6"></i>
                        </div>
                        <span class="text-xs font-bold text-gray-500">Voltar</span>
                    </button>

                    <!-- Active Parent Header (Optional, maybe just showing items is enough) -->

                    <template x-for="child in currentChildren" :key="child.id">
                        <button type="button" @click="selectChild(child)"
                            class="flex-shrink-0 flex flex-col items-center gap-2 p-3 rounded-xl transition-all border-2"
                            :class="$wire.category_id == child.id ? 'bg-blue-600 border-blue-600 shadow-md transform scale-105' : 'bg-gray-50 border-transparent hover:bg-gray-100'">

                            <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                :style="`background-color: ${$wire.category_id == child.id ? 'rgba(255,255,255,0.2)' : child.color + '20'}`">
                                <i :data-lucide="child.icon || 'help-circle'" class="w-6 h-6"
                                    :style="`color: ${$wire.category_id == child.id ? 'white' : child.color}`"></i>
                            </div>

                            <span class="text-xs font-bold max-w-[80px] truncate"
                                :class="$wire.category_id == child.id ? 'text-white' : 'text-gray-600'"
                                x-text="child.name">
                            </span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Date -->
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="calendar" class="w-5 h-5 text-orange-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Data</span>
                </div>
                <input type="date" wire:model="date"
                    class="text-sm font-medium text-blue-600 bg-transparent border-none outline-none">
            </div>
        </div>

        <!-- Payment Method -->
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Pagamento</span>
                </div>
                <select wire:model="payment_method"
                    class="text-sm font-medium text-gray-900 bg-transparent border-none outline-none">
                    <option value="credit">Cartão de Crédito</option>
                    <option value="debit">Cartão de Débito</option>
                    <option value="cash">Dinheiro</option>
                    <option value="pix">PIX</option>
                </select>
            </div>
        </div>

        <!-- Recurring -->
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="repeat" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Repetir</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="is_recurring" class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                    </div>
                </label>
            </div>
        </div>

        <!-- Observation (Optional) -->
        <div class="px-6 py-4 mb-20">
            <div class="flex items-start gap-3">
                <i data-lucide="align-left" class="w-5 h-5 text-gray-400 mt-1"></i>
                <textarea wire:model="observation" placeholder="Observação (opcional)" rows="3"
                    class="flex-1 text-sm text-gray-900 placeholder-gray-400 bg-transparent border-none outline-none resize-none"></textarea>
            </div>
        </div>
    </div>

    <!-- Bottom Action Button -->
    <div class="p-6 border-t border-gray-100 bg-white">
        <button wire:click="save" wire:loading.attr="disabled"
            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-bold py-4 rounded-2xl flex items-center justify-center gap-2 shadow-lg shadow-blue-200 transition-all active:scale-95">
            <i data-lucide="plus" class="w-5 h-5" wire:loading.remove wire:target="save"></i>
            <svg wire:loading wire:target="save" class="animate-spin h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span wire:loading.remove wire:target="save">{{ $transaction_id ? 'Salvar Alterações' : 'Adicionar' }}
                {{ $type === 'expense' ? 'Despesa' : 'Receita' }}</span>
            <span wire:loading wire:target="save">Salvando...</span>
        </button>
    </div>
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });

    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updated', ({ component, cleanup }) => {
            lucide.createIcons();
        });

        Livewire.hook('morph.added', ({ el }) => {
            lucide.createIcons();
        });
    });
</script>