<!-- Create / Edit Modal -->
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
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all"
                            placeholder="Ex: Aluguel, Netflix...">
                        @error('title') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Valor (R$)</label>
                            <input type="number" step="0.01" inputmode="decimal" wire:model="amount"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-mono"
                                placeholder="0,00">
                            @error('amount') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Dia Vencimento</label>
                            <input type="number" inputmode="numeric" pattern="[0-9]*" wire:model="due_day"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all"
                                placeholder="1 a 31">
                            @error('due_day') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Categoria</label>
                        <select wire:model="category_id"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all appearance-none">
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
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all"
                            rows="3" placeholder="Mais detalhes..."></textarea>
                    </div>

                    <div class="flex items-center gap-3 py-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                            </div>
                        </label>
                        <span class="text-sm font-medium text-gray-700">Ativo</span>
                    </div>

                    @if(!$editingId)
                        <div class="space-y-4 py-2">
                            <label class="block text-sm font-bold text-gray-700">Quando começa?</label>
                            <div class="flex gap-2 bg-gray-100 p-1 rounded-xl">
                                <button type="button" wire:click="$set('start_month', 'current')"
                                    class="flex-1 py-2.5 rounded-lg font-bold text-xs transition-all {{ $start_month === 'current' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                    Este mês ({{ \Carbon\Carbon::now()->format('M') }})
                                </button>
                                <button type="button" wire:click="$set('start_month', 'next')"
                                    class="flex-1 py-2.5 rounded-lg font-bold text-xs transition-all {{ $start_month === 'next' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                    Próximo mês ({{ \Carbon\Carbon::now()->addMonth()->format('M') }})
                                </button>
                            </div>
                        </div>

                        @if($start_month === 'current')
                            <div
                                class="flex items-center gap-3 py-4 bg-indigo-50 px-4 rounded-2xl border border-indigo-100 animate-in fade-in slide-in-from-top-4 duration-300">
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
                    @endif

                    <div class="pt-4 flex flex-col gap-3">
                        <button type="submit"
                            class="w-full py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2">
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