@php
    $isAdmin = request()->is('admin*');
    $analyticsActive = request()->is('relatorios*') || request()->is('planilhas*');
@endphp

@if($isAdmin)
    {{-- Admin bottom bar: scrollable flex with all admin links --}}
    <nav class="fixed bottom-0 inset-x-0 z-40 bg-white border-t border-gray-100 shadow-[0_-4px_16px_rgba(0,0,0,0.06)] lg:hidden overflow-x-auto">
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar px-4 py-2 min-w-full pb-[env(safe-area-inset-bottom)]">
            <a href="/admin"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="layout-dashboard" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[10px] font-bold tracking-tighter">Admin</span>
            </a>
            <a href="/admin/users"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin/users*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="users" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[10px] font-bold tracking-tighter">Usuários</span>
            </a>
            <a href="/admin/settings"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin/settings*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="settings" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[10px] font-bold tracking-tighter">Config</span>
            </a>
            <a href="{{ route('admin.monitoring') }}"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin/monitoring*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="activity" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[10px] font-bold tracking-tighter">Monitor</span>
            </a>
            <a href="{{ route('admin.push-broadcast') }}"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin/push-broadcast*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="send" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[10px] font-bold tracking-tighter">Push</span>
            </a>
            <a href="{{ route('admin.cron') }}"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin/cron*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="clock" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[10px] font-bold tracking-tighter">Cron</span>
            </a>
            <a href="/pulse" target="_blank"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] text-gray-400">
                <i data-lucide="activity" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[10px] font-bold tracking-tighter">Pulse</span>
            </a>
            <a href="/dashboard"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] text-indigo-600 font-bold border-l border-gray-100 ms-2">
                <i data-lucide="home" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[10px] tracking-tighter">App</span>
            </a>
        </div>
    </nav>
@else
    <div x-data="{ open: false }" class="lg:hidden">
        {{-- "Mais" bottom sheet --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-50" x-transition.opacity>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>

            <div x-show="open" x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
                class="absolute bottom-0 inset-x-0 bg-white rounded-t-3xl shadow-2xl px-5 pt-3 pb-8">
                <div class="w-10 h-1.5 bg-gray-200 rounded-full mx-auto mb-4"></div>
                <h3 class="text-sm font-bold text-gray-900 mb-4 px-1">Mais opções</h3>

                <div class="grid grid-cols-4 gap-2">
                    @php
                        $more = [
                            ['/planilhas', 'file-spreadsheet', 'Planilhas'],
                            ['/orcamentos', 'target', 'Limites'],
                            ['/gastos-fixos', 'calendar-clock', 'Fixos'],
                            ['/loans', 'banknote', 'Crédito'],
                            ['/importar-transacoes', 'upload-cloud', 'Importar'],
                            ['/perfil', 'user', 'Perfil'],
                        ];
                    @endphp
                    @foreach($more as [$url, $icon, $label])
                        <a href="{{ $url }}" wire:navigate @click="open = false"
                            class="flex flex-col items-center gap-2 py-3 rounded-2xl hover:bg-gray-50 active:bg-gray-100 transition-colors">
                            <span class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
                            </span>
                            <span class="text-[11px] font-semibold text-gray-600 text-center leading-tight">{{ $label }}</span>
                        </a>
                    @endforeach

                    @if(auth()->user()->isAdmin())
                        <a href="/admin" wire:navigate @click="open = false"
                            class="flex flex-col items-center gap-2 py-3 rounded-2xl hover:bg-red-50 active:bg-red-100 transition-colors">
                            <span class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center">
                                <i data-lucide="shield-check" class="w-6 h-6"></i>
                            </span>
                            <span class="text-[11px] font-semibold text-red-600 text-center leading-tight">Admin</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bottom navigation bar (4 tabs + center action) --}}
        <nav class="fixed bottom-0 inset-x-0 z-40 bg-white border-t border-gray-100 shadow-[0_-4px_16px_rgba(0,0,0,0.06)]">
            {{-- Center add button --}}
            <a href="/transactions/new" wire:navigate title="Novo lançamento"
                class="absolute left-1/2 -translate-x-1/2 -top-5 w-14 h-14 rounded-full bg-gradient-to-br from-indigo-600 to-blue-600 text-white flex items-center justify-center shadow-lg shadow-indigo-300/60 ring-4 ring-white active:scale-95 transition-transform">
                <i data-lucide="plus" class="w-7 h-7"></i>
            </a>

            <div class="grid grid-cols-5 h-16 pb-[env(safe-area-inset-bottom)]">
                <a href="/dashboard" wire:navigate
                    class="flex flex-col items-center justify-center gap-0.5 {{ request()->is('dashboard*') ? 'text-indigo-600' : 'text-gray-400' }}">
                    <i data-lucide="home" class="w-6 h-6"></i>
                    <span class="text-[10px] font-semibold">Início</span>
                </a>

                <a href="/transactions" wire:navigate
                    class="flex flex-col items-center justify-center gap-0.5 {{ request()->is('transactions') ? 'text-indigo-600' : 'text-gray-400' }}">
                    <i data-lucide="receipt-text" class="w-6 h-6"></i>
                    <span class="text-[10px] font-semibold">Extrato</span>
                </a>

                {{-- Spacer for the floating center button --}}
                <span aria-hidden="true"></span>

                <a href="/relatorios" wire:navigate
                    class="flex flex-col items-center justify-center gap-0.5 {{ $analyticsActive ? 'text-indigo-600' : 'text-gray-400' }}">
                    <i data-lucide="pie-chart" class="w-6 h-6"></i>
                    <span class="text-[10px] font-semibold">Análises</span>
                </a>

                <button type="button" @click="open = !open" :class="open ? 'text-indigo-600' : 'text-gray-400'"
                    class="flex flex-col items-center justify-center gap-0.5">
                    <i data-lucide="ellipsis" class="w-6 h-6"></i>
                    <span class="text-[10px] font-semibold">Mais</span>
                </button>
            </div>
        </nav>
    </div>
@endif

<script>
    lucide.createIcons();
</script>
