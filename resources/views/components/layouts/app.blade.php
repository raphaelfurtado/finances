<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Finanças App' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
                'Segoe UI Symbol', 'Noto Color Emoji';
        }
    </style>
</head>

<body
    class="font-sans antialiased bg-gray-50 flex flex-col items-center {{ session()->has('impersonator_id') ? 'pt-14' : '' }}">
    @if(session()->has('impersonator_id'))
        <div class="fixed top-0 left-0 right-0 bg-indigo-600 text-white z-[60] px-4 py-2 shadow-md">
            <div class="flex items-center justify-between max-w-md mx-auto">
                <div class="flex items-center gap-2">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    <span class="text-sm font-medium">Visualizando como: {{ auth()->user()->name }}</span>
                </div>
                <a href="{{ route('admin.stop-impersonating') }}"
                    class="px-3 py-1 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-bold transition-colors">
                    Sair da Visão
                </a>
            </div>
        </div>
    @endif

    <!-- Global Banner -->
    <x-global-banner />

    <div class="w-full max-w-md min-h-screen bg-white shadow-xl flex flex-col">
        @auth
            <header class="p-4 border-b flex justify-between items-center bg-gray-50">
                <div class="flex items-center gap-3">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="Avatar"
                            class="w-10 h-10 rounded-full border border-gray-200">
                    @else
                        <div
                            class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-semibold text-gray-900 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </button>
                </form>
            </header>
        @endauth

        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>

    <script>
        lucide.createIcons();

        // Configurar Axios para Laravel
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    </script>
</body>

</html>