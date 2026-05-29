<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Minhas Finanças' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @include('partials.assets')
</head>

<body class="font-sans antialiased bg-white text-slate-800">
    <!-- Simple Navbar -->
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center text-white shadow-md">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
                <span class="font-black text-lg tracking-tighter text-slate-900 italic uppercase">Minhas<span
                        class="text-indigo-600">Finanças</span></span>
            </div>
            <a href="/"
                class="text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Voltar ao Início
            </a>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-gray-50 border-t border-gray-100 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm text-gray-500 font-medium">
                &copy; {{ date('Y') }} Minhas Finanças. Todos os direitos reservados.
            </p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
    </script>
</body>

</html>