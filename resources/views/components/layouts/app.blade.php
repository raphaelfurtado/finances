<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Finanças App' }}</title>

    <meta name="theme-color" content="#4f46e5">
    <link rel="manifest" href="/manifest.json">

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
                    <div class="hidden md:block">
                        <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <div class="flex items-center">
                    <button id="installAppBtn"
                        class="hidden px-3 py-1.5 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded-lg text-xs font-bold transition-colors mr-2 flex items-center gap-1">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Instalar
                    </button>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </header>
        @endauth

        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>

    <script>
        lucide.createIcons();

        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', ({ component, cleanup }) => {
                lucide.createIcons();
            });
        });

        // Configurar Axios para Laravel
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        // PWA Install Logic
        let deferredPrompt;
        const installBtn = document.getElementById('installAppBtn');

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent the mini-infobar from appearing on mobile
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI notify the user they can install the PWA
            if (installBtn) installBtn.classList.remove('hidden');
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                // Show the install prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                const { outcome } = await deferredPrompt.userChoice;
                // Optionally, send analytics event with outcome of user choice
                console.log(`User response to the install prompt: ${outcome}`);
                // We've used the prompt, and can't use it again, throw it away
                deferredPrompt = null;
                // Hide the button
                installBtn.classList.add('hidden');
            });
        }
    </script>
</body>

</html>