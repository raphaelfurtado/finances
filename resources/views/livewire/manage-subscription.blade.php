<div class="p-6 pb-24 lg:pb-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Gerenciar Assinatura</h1>
        <p class="text-gray-600 mt-1">Controle sua assinatura Premium</p>
    </div>

    <!-- Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-xl border border-green-100 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-50 text-red-700 text-sm rounded-xl border border-red-100 flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Subscription Status -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mb-6">
        @if(auth()->user()->subscribed('premium'))
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="crown" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Plano Premium Ativo</h3>

                    @if($subscription)
                        @if($onGracePeriod)
                            <p class="text-sm text-orange-600 mt-1">
                                <i data-lucide="alert-triangle" class="w-4 h-4 inline"></i>
                                Cancelamento agendado - Acesso até {{ $subscription->ends_at->format('d/m/Y') }}
                            </p>
                        @else
                            <p class="text-sm text-gray-600 mt-1">
                                Próxima renovação:
                                {{ $subscription->asStripeSubscription()->current_period_end ? \Carbon\Carbon::createFromTimestamp($subscription->asStripeSubscription()->current_period_end)->format('d/m/Y') : 'N/A' }}
                            </p>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 pt-6 border-t border-gray-100 flex gap-3">
                @if($onGracePeriod)
                    <button wire:click="resumeSubscription"
                        class="px-6 py-3 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition-all shadow-sm active:scale-95">
                        <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                        Reativar Assinatura
                    </button>
                @else
                    <button wire:click="openCancelModal"
                        class="px-6 py-3 bg-red-50 text-red-600 font-bold rounded-2xl hover:bg-red-100 transition-all active:scale-95">
                        <i data-lucide="x-circle" class="w-4 h-4 inline mr-2"></i>
                        Cancelar Assinatura
                    </button>
                @endif
            </div>
        @else
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="lock" class="w-6 h-6 text-gray-400"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Plano Gratuito</h3>
                    <p class="text-sm text-gray-600 mt-1">Faça upgrade para desbloquear recursos premium</p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100">
                <a href="{{ route('upgrade') }}"
                    class="inline-block px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg active:scale-95">
                    <i data-lucide="sparkles" class="w-4 h-4 inline mr-2"></i>
                    Fazer Upgrade
                </a>
            </div>
        @endif
    </div>

    <!-- Cancel Confirmation Modal -->
    @if($showCancelModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeCancelModal"></div>

            <div
                class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Confirmar Cancelamento</h2>
                    <button wire:click="closeCancelModal" class="p-2 text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="text-center mb-6">
                        <div
                            class="w-16 h-16 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Tem certeza?</h3>
                        <p class="text-sm text-gray-600">
                            Você manterá acesso aos recursos Premium até o fim do período atual,
                            mas sua assinatura não será renovada automaticamente.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="closeCancelModal"
                            class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-2xl hover:bg-gray-200 transition-all">
                            Voltar
                        </button>
                        <button wire:click="cancelSubscription"
                            class="flex-1 py-3 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-lg active:scale-95">
                            Confirmar Cancelamento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    lucide.createIcons();
</script>
@endscript