<div>
    @if($criticalCount > 0)
        <div class="mb-6">
            <div
                class="bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl p-4 shadow-lg text-white relative overflow-hidden transition-all hover:scale-[1.02] active:scale-[0.98]">
                <!-- Decorative Icon -->
                <div class="absolute -right-2 -top-2 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>

                <div class="flex items-center justify-between relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 p-2 rounded-xl backdrop-blur-sm">
                            <i data-lucide="{{ $overdueCount > 0 ? 'alert-octagon' : 'clock' }}"
                                class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-bold text-orange-100 uppercase tracking-wider mb-0.5">Pendências de
                                Pagamento</h3>
                            <p class="font-bold text-sm">
                                @if($overdueCount > 0)
                                    {{ $overdueCount }} conta(s) em atraso!
                                @else
                                    {{ $criticalCount }} conta(s) vencendo logo.
                                @endif
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('fixed-expenses') }}"
                        class="px-4 py-2 bg-white text-orange-600 text-xs font-bold rounded-xl shadow-sm hover:bg-orange-50 transition-colors flex items-center gap-1">
                        Gerenciar
                        <i data-lucide="chevron-right" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>