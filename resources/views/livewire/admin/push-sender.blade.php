<div class="p-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Form Column -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-indigo-600 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 p-2 rounded-xl">
                            <i data-lucide="send" class="w-5 h-5 text-white"></i>
                        </div>
                        <h2 class="text-lg font-bold text-white">Nova Campanha</h2>
                    </div>
                </div>

                <div class="p-6">
                    @if (session()->has('message'))
                        <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            <span class="text-sm font-semibold">{{ session('message') }}</span>
                        </div>
                    @endif

                    <form wire:submit.prevent="send" class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5 ms-1">Título da Notificação</label>
                            <div class="relative">
                                <input type="text" wire:model.live="title" 
                                    class="w-full bg-slate-50 border-slate-200 border-2 rounded-2xl px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 transition-all text-sm"
                                    maxlength="50" placeholder="Ex: Novidade no App!">
                            </div>
                            <div class="flex justify-between mt-1.5 px-1">
                                <div>
                                    @error('title') <span class="text-rose-500 text-xs font-bold">{{ $message }}</span> @enderror
                                </div>
                                <span class="text-[10px] font-bold {{ mb_strlen($title) > 45 ? 'text-rose-500' : 'text-slate-400' }} uppercase tracking-wider">
                                    {{ mb_strlen($title) }}/50
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5 ms-1">Mensagem Sincera</label>
                            <textarea wire:model.live="body" 
                                class="w-full bg-slate-50 border-slate-200 border-2 rounded-2xl px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 transition-all text-sm resize-none"
                                rows="4" maxlength="150" placeholder="O que o Consultor Sincero tem a dizer agora?"></textarea>
                            <div class="flex justify-between mt-1.5 px-1">
                                <div>
                                    @error('body') <span class="text-rose-500 text-xs font-bold">{{ $message }}</span> @enderror
                                </div>
                                <span class="text-[10px] font-bold {{ mb_strlen($body) > 140 ? 'text-rose-500' : 'text-slate-400' }} uppercase tracking-wider">
                                    {{ mb_strlen($body) }}/150
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5 ms-1">Público Alvo</label>
                            <select wire:model="target_segment" 
                                class="w-full bg-slate-50 border-slate-200 border-2 rounded-2xl px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-0 transition-all text-sm appearance-none cursor-pointer">
                                <option value="test_user">🛡️ Eu mesmo (Teste)</option>
                                <option value="all">🌍 Todos os Usuários</option>
                                <option value="free">💸 Usuários Free</option>
                                <option value="premium">👑 Usuários Premium</option>
                            </select>
                            @error('target_segment') <span class="text-rose-500 text-xs font-bold block mt-1 ps-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" 
                            class="group relative w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl transition-all shadow-lg hover:shadow-indigo-200 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed overflow-hidden"
                            wire:loading.attr="disabled">
                            <div class="relative z-10 flex items-center justify-center gap-2">
                                <span wire:loading.remove>Lançar Notificação</span>
                                <span wire:loading class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Enviando...
                                </span>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- History Column -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full">
                <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-100 p-2 rounded-xl text-slate-500">
                            <i data-lucide="history" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">Histórico de Disparos</h2>
                    </div>
                </div>

                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Enviado em</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Campanha</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Público</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Alcance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($history as $item)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-bold text-slate-900">{{ $item->created_at->format('d/m/Y') }}</div>
                                        <div class="text-[10px] text-slate-400 font-medium tracking-tight">{{ $item->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-900 mb-0.5">{{ $item->title }}</div>
                                        <div class="text-xs text-slate-500 line-clamp-1 max-w-xs" title="{{ $item->body }}">
                                            {{ $item->body }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $segmentClasses = match($item->target_segment) {
                                                'premium' => 'bg-amber-100 text-amber-700',
                                                'all' => 'bg-indigo-100 text-indigo-700',
                                                'test_user' => 'bg-slate-100 text-slate-700',
                                                default => 'bg-blue-100 text-blue-700',
                                            };
                                        @endphp
                                        <span class="inline-flex px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $segmentClasses }}">
                                            {{ $item->target_segment }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 border border-slate-100 rounded-xl">
                                            <i data-lucide="users" class="w-3 h-3 text-slate-400"></i>
                                            <span class="text-xs font-black text-slate-700">{{ number_format($item->sent_count) }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($history->isEmpty())
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center gap-2">
                                            <i data-lucide="inbox" class="w-10 h-10 opacity-20"></i>
                                            <span class="text-sm font-medium italic">O silêncio é total por aqui...</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-50 bg-slate-50/20">
                    {{ $history->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        lucide.createIcons();
    });
</script>
