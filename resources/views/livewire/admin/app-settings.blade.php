<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Configurações do App</h1>
                <p class="text-indigo-100 text-sm">Modo de manutenção e mensagens globais</p>
            </div>
            <a href="/admin" wire:navigate class="p-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mx-6 mt-4 bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                <p class="text-sm font-medium text-green-900">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <div class="flex-1 overflow-y-auto px-6 py-6 mb-20">
        <!-- Maintenance Mode Section -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 {{ $maintenance_mode ? 'bg-red-50' : 'bg-green-50' }} rounded-xl flex items-center justify-center">
                        <i data-lucide="{{ $maintenance_mode ? 'alert-triangle' : 'check-circle' }}"
                            class="w-6 h-6 {{ $maintenance_mode ? 'text-red-600' : 'text-green-600' }}"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900">Modo de Manutenção</h2>
                        <p class="text-xs text-gray-500">Bloqueia acesso de todos exceto admins</p>
                    </div>
                </div>
                <button wire:click="toggleMaintenanceMode"
                    class="px-4 py-2 rounded-xl font-semibold {{ $maintenance_mode ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} transition-colors">
                    {{ $maintenance_mode ? '🔴 ATIVO' : '🟢 INATIVO' }}
                </button>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mensagem de Manutenção</label>
                <textarea wire:model="maintenance_message" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none resize-none"
                    placeholder="Esta mensagem será exibida para os usuários quando o modo de manutenção estiver ativo"></textarea>
                @error('maintenance_message') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Global Banner Section -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 {{ $global_banner_active ? 'bg-blue-50' : 'bg-gray-50' }} rounded-xl flex items-center justify-center">
                        <i data-lucide="megaphone"
                            class="w-6 h-6 {{ $global_banner_active ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900">Banner Global</h2>
                        <p class="text-xs text-gray-500">Mensagem no topo de todas as páginas</p>
                    </div>
                </div>
                <button wire:click="toggleBanner"
                    class="px-4 py-2 rounded-xl font-semibold {{ $global_banner_active ? 'bg-blue-100 text-blue-700 hover:bg-blue-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    {{ $global_banner_active ? '🔵 ATIVO' : '⚪ INATIVO' }}
                </button>
            </div>

            <div class="space-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mensagem do Banner</label>
                    <input type="text" wire:model="global_banner_message"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        placeholder="Ex: Novos recursos disponíveis! 🎉">
                    @error('global_banner_message') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo do Banner</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" wire:click="$set('global_banner_type', 'info')"
                            class="p-3 rounded-xl border-2 transition-all {{ $global_banner_type === 'info' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                                <span class="font-medium text-sm">Info</span>
                            </div>
                        </button>
                        <button type="button" wire:click="$set('global_banner_type', 'success')"
                            class="p-3 rounded-xl border-2 transition-all {{ $global_banner_type === 'success' ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                <span class="font-medium text-sm">Sucesso</span>
                            </div>
                        </button>
                        <button type="button" wire:click="$set('global_banner_type', 'warning')"
                            class="p-3 rounded-xl border-2 transition-all {{ $global_banner_type === 'warning' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200' }}">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                                <span class="font-medium text-sm">Aviso</span>
                            </div>
                        </button>
                        <button type="button" wire:click="$set('global_banner_type', 'error')"
                            class="p-3 rounded-xl border-2 transition-all {{ $global_banner_type === 'error' ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                <span class="font-medium text-sm">Erro</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Preview -->
                @if($global_banner_message)
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs font-medium text-gray-500 mb-2">Preview:</p>
                        <div
                            class="px-4 py-3 rounded-xl {{ $global_banner_type === 'info' ? 'bg-blue-50 text-blue-900' : ($global_banner_type === 'success' ? 'bg-green-50 text-green-900' : ($global_banner_type === 'warning' ? 'bg-yellow-50 text-yellow-900' : 'bg-red-50 text-red-900')) }}">
                            <p class="text-sm font-medium">{{ $global_banner_message }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Plan Settings Section -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i data-lucide="credit-card" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900">Configuração do Plano</h2>
                    <p class="text-xs text-gray-500">Altere o preço do plano premium</p>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Preço do Plano (Premium)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium">R$</span>
                    <input type="text" wire:model="plan_price"
                        class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        placeholder="Ex: 9,90">
                </div>
                @error('plan_price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Freemium Settings Section -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                    <i data-lucide="banknote" class="w-6 h-6 text-orange-600"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900">Limites Freemium</h2>
                    <p class="text-xs text-gray-500">Configure os limites para usuários gratuitos</p>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Limite de Empréstimos (Grátis)</label>
                <div class="relative">
                    <input type="number" inputmode="numeric" pattern="[0-9]*" wire:model="free_loan_limit" min="1"
                        max="100"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        placeholder="Ex: 2">
                </div>
                <p class="text-xs text-gray-500 mt-2">Usuários gratuitos poderão ter no máximo este número de
                    empréstimos ativos.</p>
                @error('free_loan_limit') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Save Button -->
        <button wire:click="saveSettings" wire:loading.attr="disabled"
            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-bold py-4 rounded-2xl transition-all active:scale-95 shadow-lg">
            <span wire:loading.remove wire:target="saveSettings">💾 Salvar Todas as Configurações</span>
            <span wire:loading wire:target="saveSettings">Salvando...</span>
        </button>
    </div>

    <!-- Bottom Nav -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 px-6 py-4"
        style="max-width: 448px; margin: 0 auto;">
        <div class="flex items-center justify-around">
            <a href="/admin" wire:navigate class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-600">
                <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
                <span class="text-xs font-medium">Dashboard</span>
            </a>
            <a href="/admin/users" wire:navigate
                class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-600">
                <i data-lucide="users" class="w-6 h-6"></i>
                <span class="text-xs font-medium">Usuários</span>
            </a>
            <a href="/admin/settings" wire:navigate class="flex flex-col items-center gap-1 text-blue-600">
                <i data-lucide="settings" class="w-6 h-6"></i>
                <span class="text-xs font-medium">Config</span>
            </a>
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