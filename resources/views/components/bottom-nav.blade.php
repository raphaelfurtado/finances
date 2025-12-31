@props(['active' => 'painel'])

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-lg"
    style="max-width: 448px; margin: 0 auto;">
    <div class="grid grid-cols-6 gap-1 px-2 py-2">
        <!-- Painel -->
        <a href="/dashboard"
            class="flex flex-col items-center justify-center py-2 {{ $active === 'painel' ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-600 transition-colors">
            <i data-lucide="layout-grid" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-medium leading-none">Painel</span>
        </a>

        <!-- Planilhas -->
        <a href="/planilhas"
            class="flex flex-col items-center justify-center py-2 {{ $active === 'planilhas' ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-600 transition-colors">
            <i data-lucide="file-spreadsheet" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-medium leading-none">Planilhas</span>
        </a>

        <!-- Limites -->
        <a href="/orcamentos"
            class="flex flex-col items-center justify-center py-2 {{ $active === 'budgets' ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-600 transition-colors">
            <i data-lucide="target" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-medium leading-none">Limites</span>
        </a>

        <!-- Empréstimos -->
        <a href="/loans"
            class="flex flex-col items-center justify-center py-2 {{ $active === 'loans' ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-600 transition-colors">
            <i data-lucide="banknote" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-medium leading-none">Crédito</span>
        </a>

        <!-- Relatórios -->
        <a href="/relatorios"
            class="flex flex-col items-center justify-center py-2 {{ $active === 'relatorios' ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-600 transition-colors">
            <i data-lucide="pie-chart" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-medium leading-none">Relat.</span>
        </a>

        <!-- Perfil -->
        <a href="/perfil"
            class="flex flex-col items-center justify-center py-2 {{ $active === 'perfil' ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-600 transition-colors">
            <i data-lucide="user" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-medium leading-none">Perfil</span>
        </a>
    </div>
</nav>

<script>
    lucide.createIcons();
</script>