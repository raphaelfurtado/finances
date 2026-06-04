<!-- Payment Confirmation Modal -->
@if($showPaymentModal)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closePaymentModal"></div>

        <div
            class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Registrar Pagamento</h2>
                <button wire:click="closePaymentModal" class="p-2 text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form wire:submit.prevent="registerPayment" class="p-6 space-y-6">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                        style="background-color: {{ $paymentColor }}20; color: {{ $paymentColor }};">
                        <i data-lucide="{{ $paymentIcon }}" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $paymentTitle }}</h3>
                    <p class="text-sm text-gray-500">Confirme o valor para registrar no sistema.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Valor Pago (R$)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">R$</span>
                        <input type="number" step="0.01" inputmode="decimal" wire:model="paymentAmount"
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-mono text-xl font-bold text-gray-900 text-center"
                            autofocus>
                    </div>
                </div>

                <div class="pt-2 flex flex-col gap-3">
                    <button type="submit"
                        class="w-full py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2">
                        <i data-lucide="check" class="w-5 h-5"></i>
                        Confirmar Pagamento
                    </button>
                    <button type="button" wire:click="closePaymentModal"
                        class="w-full py-4 text-gray-500 font-bold hover:bg-gray-50 rounded-2xl transition-all">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif