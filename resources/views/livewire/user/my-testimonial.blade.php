<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="bg-white px-6 py-4 border-b border-gray-100 mb-6">
        <div class="flex items-center gap-3">
            <a href="/perfil" wire:navigate
                class="p-2 -ml-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-900">Meu Depoimento</h1>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-6 pb-20">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Avalie sua experiência</h2>
                    <p class="text-sm text-gray-500 mt-1">Sua opinião ajuda a melhorar nossa plataforma</p>
                </div>
                <div class="p-2 bg-yellow-50 rounded-lg">
                    <i data-lucide="message-square-heart" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>

            @if (session()->has('message'))
                <div
                    class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3 animate-fade-in-down">
                    <div class="bg-green-100 p-1 rounded-full">
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </div>
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="save" class="space-y-6">
                <!-- Rating -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700">Quantas estrelas merecemos?</label>
                    <div class="flex gap-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('rating', {{ $i }})"
                                class="p-1 transition-all hover:scale-110 focus:outline-none group">
                                <i data-lucide="star"
                                    class="w-8 h-8 transition-colors {{ $rating >= $i ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300 group-hover:text-yellow-200' }}"></i>
                            </button>
                        @endfor
                    </div>
                    @error('rating') <span class="text-red-500 text-sm block mt-1 flex items-center gap-1"><i
                    data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</span> @enderror
                </div>

                <!-- Message -->
                <div class="space-y-3">
                    <label for="message" class="block text-sm font-semibold text-gray-700">O que você mais
                        gosta?</label>
                    <div class="relative">
                        <textarea wire:model="message" id="message" rows="5"
                            class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 p-4 resize-none transition-all focus:bg-white"
                            placeholder="Conte-nos como o app tem te ajudado..."></textarea>
                        <div class="absolute bottom-3 right-3 pointer-events-none">
                            <i data-lucide="pen-line" class="w-4 h-4 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-1">
                        <p class="text-xs text-gray-500">Mínimo de 10 caracteres</p>
                        <span class="text-xs text-gray-400" x-data="{ count: $wire.entangle('message').live }"
                            x-text="(count?.length || 0) + '/500'"></span>
                    </div>
                    @error('message') <span class="text-red-500 text-sm block mt-1 flex items-center gap-1"><i
                    data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</span> @enderror
                </div>

                <!-- Profession -->
                <div class="space-y-4 pt-4 border-t border-gray-100">
                    <div class="space-y-3">
                        <label for="profession" class="block text-sm font-semibold text-gray-700">Qual sua profissão?
                            (Opcional)</label>
                        <div class="relative">
                            <input type="text" wire:model="profession" id="profession"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 px-4 py-3 transition-all focus:bg-white"
                                placeholder="Pode ser: 'Empresário', 'Autônomo', 'Estudante'...">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                <i data-lucide="briefcase" class="w-4 h-4 text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex gap-3 items-center">
                            <div class="bg-indigo-100 p-2 rounded-lg">
                                <i data-lucide="eye" class="w-4 h-4 text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">Mostrar minha profissão</p>
                                <p class="text-xs text-gray-500">Exibir ao lado do meu nome no depoimento</p>
                            </div>
                        </div>
                        <button type="button" wire:click="$toggle('showProfession')"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $showProfession ? 'bg-indigo-600' : 'bg-gray-200' }}">
                            <span
                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $showProfession ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-4">
                    <div class="bg-blue-100/50 p-2 rounded-lg h-fit">
                        <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div class="text-sm text-blue-800">
                        <p class="font-bold mb-1">Transparência</p>
                        <p class="text-blue-700/80 leading-relaxed">Seu depoimento passará por uma revisão rápida antes
                            de aparecer na nossa página inicial.</p>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-6 py-4 border border-transparent text-base font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-200 transition-all hover:translate-y-px active:scale-95">
                        <span wire:loading.remove wire:target="save" class="flex items-center">
                            <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                            Enviar Depoimento
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center">
                            <i data-lucide="loader-2" class="w-5 h-5 mr-2 animate-spin"></i>
                            Enviando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para garantir ícones -->
    <script>
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
    </script>
</div>