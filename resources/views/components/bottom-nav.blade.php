@props(['active' => null])
@php
    if (!function_exists('isActive')) {
        function isActive($active, $current)
        {
            if ($active === $current)
                return true;

            switch ($current) {
                case 'painel':
                    return request()->is('dashboard*');
                case 'planilhas':
                    return request()->is('planilhas*');
                case 'budgets':
                    return request()->is('orcamentos*');
                case 'loans':
                    return request()->is('loans*');
                case 'relatorios':
                    return request()->is('relatorios*');
                case 'gastos-fixos':
                    return request()->is('gastos-fixos*');
                case 'perfil':
                    return request()->is('perfil*') || request()->is('configuracoes*') || request()->is('meu-depoimento*');
                default:
                    return false;
            }
        }
    }
@endphp

<nav
    class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] z-40 lg:hidden text-center overflow-x-auto">
    <div
        class="@if(request()->is('admin*')) flex items-center gap-2 overflow-x-auto no-scrollbar px-4 @else grid grid-cols-7 gap-0 px-1 @endif py-2 min-w-full">
        @if(!request()->is('admin*'))
            <!-- Dashboard -->
            <a href="/dashboard" wire:navigate
                class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'painel') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
                <i data-lucide="layout-grid" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold leading-none tracking-tighter">Painel</span>
            </a>

            <!-- Planilhas -->
            <a href="/planilhas" wire:navigate
                class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'planilhas') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
                <i data-lucide="file-spreadsheet" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold leading-none tracking-tighter">Planilhas</span>
            </a>

            <!-- Limites -->
            <a href="/orcamentos" wire:navigate
                class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'budgets') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
                <i data-lucide="target" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold leading-none tracking-tighter">Limites</span>
            </a>

            <!-- Crédito -->
            <a href="/loans" wire:navigate
                class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'loans') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
                <i data-lucide="banknote" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold leading-none tracking-tighter">Crédito</span>
            </a>

            <!-- Fixos -->
            <a href="/gastos-fixos" wire:navigate
                class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'gastos-fixos') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
                <i data-lucide="calendar-clock" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold leading-none tracking-tighter">Fixos</span>
            </a>

            <!-- Relatórios -->
            <a href="/relatorios" wire:navigate
                class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'relatorios') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
                <i data-lucide="pie-chart" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold leading-none tracking-tighter">Relat.</span>
            </a>

            <!-- Perfil/Config -->
            <a href="/perfil" wire:navigate
                class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'perfil') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
                <i data-lucide="user" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold leading-none tracking-tighter">Perfil</span>
            </a>

            @if(auth()->user()->isAdmin())
                <a href="/admin" class="flex flex-col items-center justify-center py-1 text-red-500 transition-colors">
                    <i data-lucide="shield-check" class="w-6 h-6 mb-0.5"></i>
                    <span class="text-[9px] font-bold leading-none tracking-tighter">Admin</span>
                </a>
            @endif
        @else
            <!-- Admin Links (Scrollable Flex) -->
            <a href="/admin"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="layout-dashboard" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold tracking-tighter">Admin</span>
            </a>
            <a href="/admin/users"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin/users*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="users" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold tracking-tighter">Usuários</span>
            </a>
            <a href="/admin/settings"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin/settings*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="settings" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold tracking-tighter">Config</span>
            </a>
            <a href="{{ route('admin.monitoring') }}"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin/monitoring*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="activity" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold tracking-tighter">Monitor</span>
            </a>
            <a href="{{ route('admin.push-broadcast') }}"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin/push-broadcast*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="send" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold tracking-tighter">Push</span>
            </a>
            <a href="{{ route('admin.cron') }}"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] {{ request()->is('admin/cron*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400' }}">
                <i data-lucide="clock" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold tracking-tighter">Cron</span>
            </a>
            <a href="/pulse" target="_blank"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] text-gray-400">
                <i data-lucide="activity" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] font-bold tracking-tighter">Pulse</span>
            </a>
            <a href="/dashboard"
                class="flex flex-col items-center justify-center py-1 px-4 min-w-[70px] text-indigo-600 font-bold border-l border-gray-100 ms-2">
                <i data-lucide="home" class="w-6 h-6 mb-0.5"></i>
                <span class="text-[9px] tracking-tighter">App</span>
            </a>
        @endif
    </div>
</nav>

<script>
    lucide.createIcons();
</script>