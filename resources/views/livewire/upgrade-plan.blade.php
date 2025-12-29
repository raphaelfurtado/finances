<div class="flex flex-col h-full bg-gradient-to-br from-blue-50 to-purple-50">
    <!-- Header -->
    <div class="bg-white px-6 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <a href="/" wire:navigate class="p-2 text-gray-800 hover:text-gray-600">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-900">Desbloquear Premium</h1>
            <div class="w-10"></div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-6 py-8 mb-20">
        <!-- Premium Badge -->
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center gap-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-6 py-3 rounded-full shadow-lg">
                <i data-lucide="crown" class="w-6 h-6"></i>
                <span class="font-bold text-lg">Financas Premium</span>
            </div>
        </div>

        <!-- Pricing Cards -->
        <div class="max-w-md mx-auto space-y-4 mb-8">
            <!-- Free Plan -->
            <div class="bg-white rounded-2xl border-2 border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Plano Grátis</h3>
                    <span class="text-3xl font-bold text-gray-900">R$ 0</span>
                </div>
                <ul class="space-y-3 mb-6">
                    <li class="flex items-center gap-2 text-sm">
                        <i data-lucide="check" class="w-5 h-5 text-green-600"></i>
                        <span>Dashboard financeiro</span>
                    </li>
                    <li class="flex items-center gap-2 text-sm">
                        <i data-lucide="check" class="w-5 h-5 text-green-600"></i>
                        <span>Transações ilimitadas</span>
                    </li>
                    <li class="flex items-center gap-2 text-sm">
                        <i data-lucide="check" class="w-5 h-5 text-green-600"></i>
                        <span>Gerenciamento de categorias</span>
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-400">
                        <i data-lucide="x" class="w-5 h-5"></i>
                        <span>Relatórios avançados</span>
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-400">
                        <i data-lucide="x" class="w-5 h-5"></i>
                        <span>Planilhas exportáveis</span>
                    </li>
                </ul>
                <p class="text-xs text-gray-500 text-center">Plano atual</p>
            </div>

            <!--  Premium Plan -->
            <div
                class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl border-2 border-blue-500 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0">
                    <div class="bg-yellow-400 text-gray-900 px-3 py-1 text-xs font-bold rounded-bl-xl">
                        RECOMENDADO
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Premium</h3>
                    <div class="text-right">
                        <div class="text-3xl font-bold">R$ 9,90</div>
                        <div class="text-xs opacity-75">/mês</div>
                    </div>
                </div>
                <ul class="space-y-3 mb-6">
                    <li class="flex items-center gap-2 text-sm">
                        <i data-lucide="check" class="w-5 h-5 text-yellow-300"></i>
                        <span>Tudo do Grátis +</span>
                    </li>
                    <li class="flex items-center gap-2 text-sm">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 text-yellow-300"></i>
                        <span>Relatórios detalhados com gráficos</span>
                    </li>
                    <li class="flex items-center gap-2 text-sm">
                        <i data-lucide="file-spreadsheet" class="w-5 h-5 text-yellow-300"></i>
                        <span>Planilhas prontas para exportar</span>
                    </li>
                    <li class="flex items-center gap-2 text-sm">
                        <i data-lucide="trending-up" class="w-5 h-5 text-yellow-300"></i>
                        <span>Análise por categoria</span>
                    </li>
                    <li class="flex items-center gap-2 text-sm">
                        <i data-lucide="crown" class="w-5 h-5 text-yellow-300"></i>
                        <span>Badge Premium no perfil</span>
                    </li>
                </ul>
                <button wire:click="checkout" wire:loading.attr="disabled"
                    class="w-full bg-white text-blue-600 font-bold py-4 rounded-xl hover:bg-gray-50 transition-all active:scale-95 shadow-lg disabled:opacity-50">
                    <span wire:loading.remove wire:target="checkout">🚀 Assinar Premium Agora</span>
                    <span wire:loading wire:target="checkout">Redirecionando...</span>
                </button>
            </div>
        </div>

        <!-- Benefits Section -->
        <div class="max-w-md mx-auto space-y-4">
            <h3 class="font-bold text-gray-900 text-center mb-4">Por que escolher Premium?</h3>

            <div class="bg-white rounded-xl p-4 flex items-start gap-3">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-lucide="zap" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Insights Instantâneos</h4>
                    <p class="text-sm text-gray-500">Veja onde seu dinheiro está indo com gráficos e análises detalhadas
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 flex items-start gap-3">
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-lucide="download" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Exportação Fácil</h4>
                    <p class="text-sm text-gray-500">Baixe suas planilhas financeiras em Excel a qualquer momento</p>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 flex items-start gap-3">
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-lucide="shield-check" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Seguro e Confiável</h4>
                    <p class="text-sm text-gray-500">Seus dados são protegidos com criptografia de ponta</p>
                </div>
            </div>
        </div>

        <!-- Demo Note -->
        <div class="max-w-md mx-auto mt-8 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-yellow-900 mb-1">Modo de Demonstração</p>
                    <p class="text-xs text-yellow-700">Este é um pagamento simulado. Em produção, aqui seria integrado
                        com Stripe ou Mercado Pago.</p>
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
    });
</script>