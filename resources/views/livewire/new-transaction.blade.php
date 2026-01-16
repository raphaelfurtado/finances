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

    <!-- Freemium Progress Bar -->
    @if(!auth()->user()->isPremium())
        @php
            $progress = auth()->user()->getTransactionLimitProgress();
            $count = auth()->user()->getMonthlyTransactionCount();
            $limit = auth()->user()->getPlanLimit();
            $barColor = $progress < 50 ? 'bg-blue-600' : ($progress > 80 ? 'bg-red-500' : 'bg-yellow-500');
        @endphp
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-100">
            <div class="flex justify-between items-end mb-1">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Uso do Plano Grátis</span>
                <span class="text-xs font-bold {{ $progress >= 100 ? 'text-red-600' : 'text-gray-700' }}">
                    {{ $count }} / {{ $limit }} lançamentos
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%">
                </div>
            </div>
            @if($progress >= 100)
                <p class="text-xs text-red-600 font-medium mt-1 text-center">Limite atingido! Faça upgrade para continuar.</p>
            @endif
        </div>
    @endif

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

    <div x-data="{ showPaywall: false }" x-on:open-paywall-modal.window="showPaywall = true"
        x-on:keydown.escape.window="showPaywall = false" style="display: none;" x-show="showPaywall"
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <!-- Backdrop -->
        <div x-show="showPaywall" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div x-show="showPaywall" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.away="showPaywall = false"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                <!-- Close Button -->
                <div class="absolute right-0 top-0 pr-4 pt-4 z-10">
                    <button @click="showPaywall = false" type="button"
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Fechar</span>
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <!-- Header with Pattern -->
                <div class="relative bg-gradient-to-br from-indigo-600 to-purple-700 p-8 text-center overflow-hidden">
                    <div class="absolute inset-0 opacity-10"
                        style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');">
                    </div>

                    <!-- Floating Icon Animation -->
                    <div
                        class="relative z-10 w-20 h-20 mx-auto bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-4 shadow-xl ring-4 ring-white/10 animate-pulse">
                        <span class="text-5xl filter drop-shadow-md">🏆</span>
                    </div>

                    <h3 class="relative z-10 text-2xl font-bold text-white mb-2 tracking-tight">Você é um Power User!
                    </h3>
                    <p class="relative z-10 text-indigo-100 text-sm font-medium">Sua organização financeira é
                        inspiradora.</p>
                </div>

                <!-- Body -->
                <div class="p-8">
                    <div class="text-center mb-8">
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Você atingiu o limite de <span
                                class="font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded">{{ auth()->user()->getPlanLimit() }}
                                lançamentos</span> do seu Plano Grátis.
                        </p>
                        <p class="text-gray-500 text-sm mt-2">Para continuar registrando, faça um upgrade.</p>
                    </div>

                    <div class="bg-indigo-50/50 rounded-xl p-5 mb-8 border border-indigo-100 ring-1 ring-indigo-50">
                        <p class="text-indigo-900 font-bold text-center mb-4 text-xs uppercase tracking-wider">
                            Benefícios do Premium</p>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3 text-sm text-gray-700">
                                <div
                                    class="mt-0.5 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="check" class="w-3 h-3 text-green-600"></i>
                                </div>
                                <span class="font-medium">Lançamentos <span
                                        class="text-indigo-600 font-bold">ILIMITADOS</span></span>
                            </li>
                            <li class="flex items-start gap-3 text-sm text-gray-700">
                                <div
                                    class="mt-0.5 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="check" class="w-3 h-3 text-green-600"></i>
                                </div>
                                <span class="font-medium">Consultor financeiro com IA</span>
                            </li>
                            <li class="flex items-start gap-3 text-sm text-gray-700">
                                <div
                                    class="mt-0.5 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="check" class="w-3 h-3 text-green-600"></i>
                                </div>
                                <span class="font-medium">Relatórios e gráficos avançados</span>
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-3">
                        <a href="/upgrade"
                            class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-base font-bold rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-indigo-200 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                            Liberar Acesso Ilimitado
                            <div
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 px-2 py-0.5 rounded text-xs">
                                A partir de R$ {{ number_format(\App\Models\Plan::min('price') / 100, 2, ',', '.') }}
                            </div>
                        </a>

                        <button @click="showPaywall = false" type="button"
                            class="w-full py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                            Agora não, obrigado
                        </button>
                    </div>
                </div>
            </div>
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
</div>