<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="flex items-center px-6 py-4 bg-white border-b border-gray-100">
        <a href="/perfil" class="p-2 -ml-2 text-gray-800 hover:text-gray-600">
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </a>
        <h1 class="flex-1 text-center text-xl font-bold text-gray-900 pr-8">Configurações</h1>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto px-6 py-6">
        <!-- App Settings -->
        <div class="mb-6">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Aplicativo</h2>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <i data-lucide="bell" class="w-5 h-5 text-gray-600"></i>
                        <span class="font-medium text-gray-900">Notificações</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <i data-lucide="moon" class="w-5 h-5 text-gray-600"></i>
                        <span class="font-medium text-gray-900">Modo Escuro</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-between px-5 py-4">
                    <div class="flex items-center gap-3">
                        <i data-lucide="globe" class="w-5 h-5 text-gray-600"></i>
                        <span class="font-medium text-gray-900">Idioma</span>
                    </div>
                    <span class="text-sm text-gray-500">Português (BR)</span>
                </div>
            </div>
        </div>

        <!-- Financial Settings -->
        <div class="mb-6">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Finanças</h2>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <i data-lucide="dollar-sign" class="w-5 h-5 text-gray-600"></i>
                        <span class="font-medium text-gray-900">Moeda</span>
                    </div>
                    <span class="text-sm text-gray-500">BRL (R$)</span>
                </div>

                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <i data-lucide="calendar" class="w-5 h-5 text-gray-600"></i>
                        <span class="font-medium text-gray-900">Início do Mês</span>
                    </div>
                    <span class="text-sm text-gray-500">Dia 1</span>
                </div>

                <div class="flex items-center justify-between px-5 py-4">
                    <div class="flex items-center gap-3">
                        <i data-lucide="repeat" class="w-5 h-5 text-gray-600"></i>
                        <span class="font-medium text-gray-900">Transações Recorrentes</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Data & Privacy -->
        <div class="mb-6">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Dados e Privacidade</h2>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button
                    class="w-full flex items-center justify-between px-5 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="download" class="w-5 h-5 text-gray-600"></i>
                        <span class="font-medium text-gray-900">Exportar Dados</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                </button>

                <button
                    class="w-full flex items-center justify-between px-5 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shield" class="w-5 h-5 text-gray-600"></i>
                        <span class="font-medium text-gray-900">Privacidade</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                </button>

                <button class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="trash-2" class="w-5 h-5 text-red-600"></i>
                        <span class="font-medium text-red-600">Excluir Conta</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                </button>
            </div>
        </div>

        <!-- About -->
        <div class="mb-20">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Sobre</h2>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button
                    class="w-full flex items-center justify-between px-5 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="info" class="w-5 h-5 text-gray-600"></i>
                        <span class="font-medium text-gray-900">Sobre o App</span>
                    </div>
                    <span class="text-sm text-gray-500">v1.0.0</span>
                </button>

                <button
                    class="w-full flex items-center justify-between px-5 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-text" class="w-5 h-5 text-gray-600"></i>
                        <span class="font-medium text-gray-900">Termos de Uso</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                </button>

                <button class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="help-circle" class="w-5 h-5 text-gray-600"></i>
                        <span class="font-medium text-gray-900">Ajuda e Suporte</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>