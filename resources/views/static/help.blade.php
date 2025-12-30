<div class="flex flex-col h-full bg-gray-50">
    <div class="flex items-center px-6 py-4 bg-white border-b border-gray-100">
        <a href="{{ url()->previous() }}" class="p-2 -ml-2 text-gray-800 hover:text-gray-600">
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </a>
        <h1 class="flex-1 text-center text-xl font-bold text-gray-900 pr-8">Ajuda</h1>
    </div>
    <div class="p-6 overflow-y-auto">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 prose">
            <h3>Perguntas Frequentes</h3>
            <p>Precisa de ajuda? Entre em contato com nosso suporte.</p>
        </div>
    </div>
</div>
<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>