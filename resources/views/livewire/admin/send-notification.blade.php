<div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
            <i data-lucide="megaphone" class="w-6 h-6"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-gray-900 leading-none">Enviar Notificação Global</h2>
            <p class="text-sm text-gray-500 mt-1">Isso enviará uma mensagem para todos os usuários cadastrados.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div
            class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium rounded-xl flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="send">
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Título do
                    Aviso</label>
                <input type="text" wire:model="title"
                    class="w-full px-4 py-3 bg-gray-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl text-sm font-medium transition-all"
                    placeholder="Ex: Manutenção Programada">
                @error('title') <span class="text-red-500 text-[10px] mt-1 px-1 font-bold">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Corpo da
                    Mensagem</label>
                <textarea wire:model="message" rows="4"
                    class="w-full px-4 py-3 bg-gray-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl text-sm font-medium transition-all"
                    placeholder="Descreva o aviso aqui..."></textarea>
                @error('message') <span class="text-red-500 text-[10px] mt-1 px-1 font-bold">{{ $message }}</span>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2">
                    <span wire:loading.remove>
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Enviar para Todos os Usuários
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Processando...
                    </span>
                </button>
            </div>
        </div>
    </form>

    <script>
        if (typeof lucide !== 'undefined') lucide.createIcons();
    </script>
</div>