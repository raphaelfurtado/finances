<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="bg-white px-6 py-4 border-b border-gray-100">
        <h1 class="text-xl font-bold text-gray-900">Perfil</h1>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto px-6 py-6 mb-20">
        <!-- User Info Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white mb-6">
            <div class="flex items-center gap-4 mb-4">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="Avatar" class="w-20 h-20 rounded-full border-4 border-white/30">
                @else
                    <div
                        class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center text-3xl font-bold border-4 border-white/30">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-1">{{ $user->name }}</h2>
                    <p class="text-blue-100 text-sm">{{ $user->email }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-4">
                <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm">
                    <p class="text-xs text-blue-100 mb-1">Membro desde</p>
                    <p class="font-bold">{{ $user->created_at->locale('pt_BR')->isoFormat('MMM YYYY') }}</p>
                </div>
                <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm">
                    <p class="text-xs text-blue-100 mb-1">{{ $user->isPremium() ? 'Plano' : 'Categorias' }}</p>
                    <p class="font-bold">{{ $user->isPremium() ? '💎 Premium' : $user->categories->count() }}</p>
                </div>
            </div>

            <!-- Premium Status -->
            @if(!$user->isPremium())
                <a href="/upgrade" wire:navigate
                    class="block mt-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-bold py-3 rounded-xl text-center hover:from-yellow-500 hover:to-orange-600 transition-all">
                    <div class="flex items-center justify-center gap-2">
                        <i data-lucide="crown" class="w-5 h-5"></i>
                        Fazer Upgrade para Premium
                    </div>
                </a>
            @else
                <div class="mt-4 bg-white/10 rounded-xl p-3 backdrop-blur-sm text-center">
                    <p class="text-xs text-blue-100 mb-1">Premium ativo</p>
                    <p class="font-bold">{{ $user->premiumDaysRemaining() }} dias restantes</p>
                </div>
            @endif
        </div>

        <!-- Menu Options -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
            @if($user->isAdmin())
                <a href="/admin" wire:navigate
                    class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg flex items-center justify-center">
                            <i data-lucide="shield" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Painel Admin</p>
                            <p class="text-xs text-gray-500">Gerenciar sistema</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                </a>
            @endif

            <a href="/categories"
                class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="folder" class="w-5 h-5 text-orange-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Minhas Categorias</p>
                        <p class="text-xs text-gray-500">Gerenciar categorias</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
            </a>

            <a href="/configuracoes"
                class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="settings" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Configurações</p>
                        <p class="text-xs text-gray-500">Preferências do app</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
            </a>

            <a href="{{ route('my-testimonial') }}"
                class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="star" class="w-5 h-5 text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Meu Depoimento</p>
                        <p class="text-xs text-gray-500">Avalie nossa plataforma</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
            </a>
        </div>

        <!-- Statistics -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
            <h3 class="font-bold text-gray-900 mb-4">Estatísticas</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total de Transações</span>
                    <span class="font-bold text-gray-900">{{ $user->transactions->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Transações este Mês</span>
                    <span class="font-bold text-gray-900">
                        {{ $user->transactions()->whereMonth('date', now()->month)->whereYear('date', now()->year)->count() }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Categorias Ativas</span>
                    <span class="font-bold text-gray-900">{{ $user->categories->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full bg-white hover:bg-red-50 text-red-600 font-bold py-4 rounded-2xl border-2 border-red-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Sair da Conta
            </button>
        </form>
    </div>

    <!-- Bottom Navigation -->
    <x-bottom-nav active="perfil" />
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>