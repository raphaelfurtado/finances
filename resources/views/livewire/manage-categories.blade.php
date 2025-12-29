<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="flex items-center px-6 py-4 bg-white border-b border-gray-100">
        <a href="/" wire:navigate class="p-2 -ml-2 text-gray-800 hover:text-gray-600">
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </a>
        <h1 class="flex-1 text-center text-xl font-bold text-gray-900 pr-8">Gerenciar Categorias</h1>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mx-6 mt-4 bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                <p class="text-sm font-medium text-green-900">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <!-- Content -->
    <div class="flex-1 overflow-y-auto px-6 py-4 mb-20">
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">MINHAS CATEGORIAS</h2>

        @if($categories->isEmpty())
            <div class="text-center py-12">
                <i data-lucide="folder-open" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
                <p class="text-gray-500 mb-2">Nenhuma categoria ainda</p>
                <p class="text-sm text-gray-400">Clique em "Nova Categoria" para começar</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($categories as $category)
                    <div
                        class="group relative bg-white border border-gray-100 rounded-2xl p-4 flex items-center shadow-sm hover:shadow-md transition-all">
                        <!-- Icon Container -->
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4"
                            style="background-color: {{ $category->color }}20;">
                            <i data-lucide="{{ $category->icon }}" class="w-6 h-6" style="color: {{ $category->color }};"></i>
                        </div>

                        <!-- Text Content -->
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-400">{{ $category->description }}</p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2">
                            <button wire:click="edit({{ $category->id }})"
                                class="p-2 text-gray-300 hover:text-blue-500 transition-colors">
                                <i data-lucide="edit-3" class="w-5 h-5"></i>
                            </button>
                            <button wire:click="delete({{ $category->id }})"
                                wire:confirm="Tem certeza que deseja excluir esta categoria?"
                                class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Footer Action -->
    <div class="fixed bottom-0 left-0 right-0 p-6 bg-white border-t border-gray-100"
        style="max-width: 448px; margin: 0 auto;">
        <button wire:click="openModal"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl flex items-center justify-center gap-2 shadow-lg shadow-blue-200 transition-all active:scale-95">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Nova Categoria
        </button>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-50" wire:click="closeModal">
            <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-md max-h-[90vh] overflow-y-auto" wire:click.stop>
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 rounded-t-3xl">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $editingId ? 'Editar Categoria' : 'Nova Categoria' }}
                        </h2>
                        <button wire:click="closeModal" class="p-2 text-gray-400 hover:text-gray-600">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome da Categoria</label>
                        <input type="text" wire:model="name" placeholder="Ex: Alimentação, Transporte..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição (opcional)</label>
                        <input type="text" wire:model="description" placeholder="Ex: Restaurantes, Mercado, Ifood"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                    </div>

                    <!-- Icon Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Ícone</label>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($availableIcons as $availableIcon)
                                <button type="button" wire:click="$set('icon', '{{ $availableIcon }}')"
                                    class="p-3 rounded-xl border-2 transition-all {{ $icon === $availableIcon ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <i data-lucide="{{ $availableIcon }}"
                                        class="w-6 h-6 mx-auto {{ $icon === $availableIcon ? 'text-blue-600' : 'text-gray-600' }}"></i>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Color Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Cor</label>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($availableColors as $availableColor)
                                <button type="button" wire:click="$set('color', '{{ $availableColor }}')"
                                    class="w-12 h-12 rounded-xl border-2 transition-all {{ $color === $availableColor ? 'border-gray-900 scale-110' : 'border-gray-200' }}"
                                    style="background-color: {{ $availableColor }};">
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-gray-500 mb-3">Preview</p>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                style="background-color: {{ $color }}20;">
                                <i data-lucide="{{ $icon }}" class="w-6 h-6" style="color: {{ $color }};"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $name ?: 'Nome da Categoria' }}</p>
                                <p class="text-sm text-gray-400">{{ $description ?: 'Descrição' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
                    <div class="flex gap-3">
                        <button wire:click="closeModal"
                            class="flex-1 px-6 py-3 border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="save" wire:loading.attr="disabled"
                            class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-semibold rounded-xl transition-colors">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Salvar' : 'Criar' }}</span>
                            <span wire:loading wire:target="save">Salvando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
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