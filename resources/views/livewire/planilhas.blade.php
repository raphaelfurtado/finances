<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="bg-white px-6 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900">Planilhas</h1>
            <button class="p-2 text-gray-400 hover:text-gray-600">
                <i data-lucide="plus" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto px-6 py-6">
        <!-- Coming Soon Message -->
        <div class="flex flex-col items-center justify-center h-full text-center px-6">
            <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                <i data-lucide="file-spreadsheet" class="w-12 h-12 text-blue-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Planilhas em Breve</h2>
            <p class="text-gray-500 mb-6 max-w-sm">
                Organize suas finanças com planilhas personalizadas. Esta funcionalidade estará disponível em breve!
            </p>
            <div class="space-y-3 w-full max-w-sm">
                <div class="bg-white rounded-xl p-4 border border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="check" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <div class="flex-1 text-left">
                        <p class="font-semibold text-gray-900 text-sm">Planilhas Mensais</p>
                        <p class="text-xs text-gray-500">Organize por mês</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="check" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div class="flex-1 text-left">
                        <p class="font-semibold text-gray-900 text-sm">Exportar Excel</p>
                        <p class="text-xs text-gray-500">Baixe suas planilhas</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="check" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <div class="flex-1 text-left">
                        <p class="font-semibold text-gray-900 text-sm">Templates</p>
                        <p class="text-xs text-gray-500">Modelos prontos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <x-bottom-nav active="planilhas" />
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>