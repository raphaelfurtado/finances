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
                case 'perfil':
                    return request()->is('perfil*') || request()->is('configuracoes*') || request()->is('meu-depoimento*');
                default:
                    return false;
            }
        }
    }
@endphp

<nav
    class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] z-40 lg:hidden">
    <div class="grid grid-cols-6 gap-1 px-1 py-2">
        <!-- Dashboard -->
        <a href="/dashboard"
            class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'painel') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
            <i data-lucide="layout-grid" class="w-6 h-6 mb-0.5"></i>
            <span class="text-[9px] font-bold leading-none tracking-tighter">Painel</span>
        </a>

        <!-- Planilhas -->
        <a href="/planilhas"
            class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'planilhas') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
            <i data-lucide="file-spreadsheet" class="w-6 h-6 mb-0.5"></i>
            <span class="text-[9px] font-bold leading-none tracking-tighter">Planilhas</span>
        </a>

        <!-- Limites -->
        <a href="/orcamentos"
            class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'budgets') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
            <i data-lucide="target" class="w-6 h-6 mb-0.5"></i>
            <span class="text-[9px] font-bold leading-none tracking-tighter">Limites</span>
        </a>

        <!-- Crédito -->
        <a href="/loans"
            class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'loans') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
            <i data-lucide="banknote" class="w-6 h-6 mb-0.5"></i>
            <span class="text-[9px] font-bold leading-none tracking-tighter">Crédito</span>
        </a>

        <!-- Relatórios -->
        <a href="/relatorios"
            class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'relatorios') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
            <i data-lucide="pie-chart" class="w-6 h-6 mb-0.5"></i>
            <span class="text-[9px] font-bold leading-none tracking-tighter">Relat.</span>
        </a>

        <!-- Perfil/Config -->
        <a href="/perfil"
            class="flex flex-col items-center justify-center py-1 {{ isActive($active, 'perfil') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors">
            <i data-lucide="user" class="w-6 h-6 mb-0.5"></i>
            <span class="text-[9px] font-bold leading-none tracking-tighter">Perfil</span>
        </a>
    </div>
</nav>

<script>
    lucide.createIcons();
</script>