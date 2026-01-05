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

        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 flex flex-col min-h-screen">
    @if(session()->has('impersonator_id'))
        <div class="fixed top-0 left-0 right-0 bg-indigo-600 text-white z-[60] px-4 py-2 shadow-md">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
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

    <!-- Top Navbar -->
    <nav class="sticky top-0 z-50 w-full bg-white border-b border-gray-100 shadow-sm">
        <div class="px-4 h-16 flex items-center justify-between lg:px-8">
            <div class="flex items-center gap-3">
                @auth
                    <div class="relative group cursor-pointer" onclick="window.location.href='/perfil'">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Avatar"
                                class="w-10 h-10 rounded-full border-2 border-indigo-50 p-0.5 object-cover">
                        @else
                            <div
                                class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border-2 border-white shadow-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        @if(auth()->user()->isPremium())
                            <div
                                class="absolute -bottom-1 -right-1 w-4 h-4 bg-amber-400 rounded-full border-2 border-white flex items-center justify-center shadow-sm">
                                <i data-lucide="crown" class="w-2 h-2 text-white fill-current"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col min-w-0" onclick="window.location.href='/perfil'">
                        <p class="text-sm font-bold text-gray-900 leading-none truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-400 font-medium truncate">{{ auth()->user()->email }}</p>
                    </div>
                @endauth
            </div>

            <div class="flex items-center gap-2">
                <!-- Install Button -->
                <button id="pwa-install-btn"
                    class="hidden flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg text-xs font-bold transition-all border border-indigo-100 shadow-sm">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Instalar
                </button>

                @auth
                    <form action="{{ route('logout') }}" method="POST" class="ml-1">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Sair">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden">
        <!-- Desktop Sidebar -->
        <aside class="hidden lg:flex flex-col w-64 bg-white border-r border-gray-100 overflow-y-auto">
            <div class="p-6 pt-2">
                <div class="bg-indigo-600/5 p-4 rounded-2xl flex items-center gap-3 mb-6">
                    <div class="bg-indigo-600 p-2 rounded-xl">
                        <i data-lucide="piggy-bank" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="font-black text-indigo-900 tracking-tight">Minhas Finanças</span>
                </div>

                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-4">Menu Principal</p>

                <div class="space-y-1">
                    <a href="/dashboard"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->is('dashboard*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i data-lucide="layout-grid" class="w-5 h-5"></i>
                        Dashboard
                    </a>

                    <a href="/planilhas"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->is('planilhas*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                        Planilhas
                    </a>

                    <a href="/loans"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->is('loans*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i data-lucide="banknote" class="w-5 h-5"></i>
                        Crédito
                    </a>

                    <a href="/orcamentos"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->is('orcamentos*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i data-lucide="target" class="w-5 h-5"></i>
                        Limites
                    </a>

                    <a href="/relatorios"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->is('relatorios*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i data-lucide="pie-chart" class="w-5 h-5"></i>
                        Relatórios
                    </a>
                </div>

                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mt-8 mb-4">Minha Conta</p>

                <div class="space-y-1">
                    <a href="/perfil"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->is('perfil*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        Configurações
                    </a>
                </div>
            </div>

            <div class="mt-auto p-6">
                @if(!auth()->user()->isPremium())
                    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="crown" class="w-4 h-4 text-indigo-600"></i>
                            <span class="text-xs font-bold text-indigo-900">Seja Premium</span>
                        </div>
                        <p class="text-[10px] text-indigo-700 leading-relaxed mb-3">Libere planilhas ilimitadas e relatórios
                            avançados.</p>
                        <a href="/upgrade"
                            class="block w-full py-2 bg-indigo-600 text-white text-[10px] font-bold text-center rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">Upgrade</a>
                    </div>
                @else
                    <div
                        class="bg-gradient-to-br from-amber-50/50 to-yellow-50/50 border border-amber-200 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-amber-400 flex items-center justify-center text-white shadow-md">
                                <i data-lucide="crown" class="w-6 h-6 fill-current"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-amber-800 uppercase tracking-widest leading-none">
                                    Usuário</p>
                                <p class="text-xs font-black text-amber-600 uppercase">Premium</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto relative pb-24 lg:pb-0">
            <div class="w-full">
                {{ $slot }}
            </div>

            <!-- FAB (Only on Mobile Dashboard) -->
            @if(request()->routeIs('dashboard'))
                <div class="lg:hidden">
                    <x-fab-button />
                </div>
            @endif
        </main>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="lg:hidden">
        <x-bottom-nav />
    </div>

    <script>
        lucide.createIcons();

        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', ({ component, cleanup }) => {
                lucide.createIcons();
            });
        });

        // PWA Install Logic
        let deferredPrompt;
        const installBtn = document.getElementById('pwa-install-btn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (installBtn) installBtn.classList.remove('hidden');
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    deferredPrompt = null;
                    installBtn.classList.add('hidden');
                }
            });
        }

        // Configurar Axios para Laravel
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    </script>
</body>

</html>