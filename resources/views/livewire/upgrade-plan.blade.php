<div class="flex flex-col h-full bg-gradient-to-br from-blue-50 to-purple-50">
    <!-- Header -->
    <div class="bg-white px-6 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <a href="/perfil" wire:navigate class="p-2 text-gray-800 hover:text-gray-600">
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
            @foreach($plans as $plan)
                @php
                    $isFree = $plan->price == 0;
                    $userOnThisPlan = false;
                    if (auth()->check()) {
                        if ($isFree) {
                            $userOnThisPlan = !auth()->user()->isPremium();
                        } else {
                            $userOnThisPlan = auth()->user()->subscribedToPrice($plan->stripe_id, 'premium');
                        }
                    }
                @endphp
                <div
                    class="{{ $isFree ? 'bg-white border-gray-200' : 'bg-gradient-to-br from-blue-600 to-purple-600 border-blue-500 text-white' }} rounded-2xl border-2 p-6 relative overflow-hidden transition-all shadow-md">
                    @if($plan->recommended)
                        <div class="absolute top-0 right-0">
                            <div class="bg-yellow-400 text-gray-900 px-3 py-1 text-xs font-bold rounded-bl-xl shadow-sm">
                                RECOMENDADO
                            </div>
                        </div>
                    @endif
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold {{ $isFree ? 'text-gray-900' : 'text-white' }}">{{ $plan->name }}</h3>
                        <div class="text-right">
                            <div class="text-3xl font-bold {{ $isFree ? 'text-gray-900' : 'text-white' }}">R$
                                {{ number_format($plan->price / 100, 2, ',', '.') }}</div>
                            <div class="text-xs {{ $isFree ? 'text-gray-500' : 'opacity-75' }}">/
                                {{ $plan->interval === 'year' ? 'ano' : 'mês' }}</div>
                        </div>
                    </div>
                    <ul class="space-y-3 mb-6">
                        @if(!$isFree)
                            <li class="flex items-center gap-2 text-sm">
                                <i data-lucide="check" class="w-5 h-5 text-yellow-300"></i>
                                <span>Tudo do Grátis +</span>
                            </li>
                        @endif
                        @foreach($plan->benefits as $benefit)
                            <li class="flex items-center gap-2 text-sm">
                                <i data-lucide="check" class="w-5 h-5 {{ $isFree ? 'text-green-600' : 'text-yellow-300' }}"></i>
                                <span class="{{ $isFree ? 'text-gray-700' : 'text-white' }}">{{ $benefit->description }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if($userOnThisPlan)
                        <div
                            class="w-full py-4 text-center rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center gap-2 font-bold {{ $isFree ? 'text-gray-500' : 'bg-green-100 text-green-700' }}">
                            <i data-lucide="{{ $isFree ? 'check' : 'crown' }}" class="w-5 h-5"></i>
                            {{ $isFree ? 'Plano Atual' : 'Premium Ativo' }}
                        </div>
                    @else
                        <button wire:click="checkout('{{ $plan->stripe_id }}')" wire:loading.attr="disabled"
                            class="w-full font-bold py-4 rounded-xl transition-all active:scale-95 shadow-lg disabled:opacity-50 {{ $isFree ? 'bg-gray-100 text-gray-600' : 'bg-white text-blue-600 hover:bg-gray-50' }}">
                            <span wire:loading.remove wire:target="checkout">🚀
                                {{ $isFree ? 'Começar Grátis' : 'Assinar ' . $plan->name }}</span>
                            <span wire:loading wire:target="checkout">Redirecionando...</span>
                        </button>
                    @endif
                </div>
            @endforeach
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