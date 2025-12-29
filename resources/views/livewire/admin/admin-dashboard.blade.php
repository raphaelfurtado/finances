<div class="flex flex-col h-full bg-gray-50">
    <!-- Admin Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-6">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-2xl font-bold">Admin Dashboard</h1>
                <p class="text-indigo-100 text-sm">Painel de controle administrativo</p>
            </div>
            <a href="/" wire:navigate class="p-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </a>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-6 py-6 mb-20">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Total Usuários</span>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="crown" class="w-5 h-5 text-yellow-600"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Premium</span>
                </div>
                <p class="text-3xl font-bold text-yellow-600">{{ $premiumUsers }}</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="user-plus" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Novos (mês)</span>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ $monthlyNewUsers }}</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="percent" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Conversão</span>
                </div>
                <p class="text-3xl font-bold text-purple-600">{{ $premiumConversionRate }}%</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
            <h2 class="font-bold text-gray-900 mb-4">Ações Rápidas</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="/admin/users" wire:navigate
                    class="flex items-center justify-center gap-2 p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors">
                    <i data-lucide="users-cog" class="w-5 h-5 text-blue-600"></i>
                    <span class="font-semibold text-blue-700 text-sm">Gerenciar Usuários</span>
                </a>

                <a href="{{ route('admin.testimonials') }}" wire:navigate
                    class="flex items-center justify-center gap-2 p-4 bg-yellow-50 hover:bg-yellow-100 rounded-xl transition-colors">
                    <i data-lucide="star" class="w-5 h-5 text-yellow-600"></i>
                    <span class="font-semibold text-yellow-700 text-sm">Depoimentos</span>
                </a>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
            <h2 class="font-bold text-gray-900 mb-4">Usuários Recentes</h2>
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        @if($user->isPremium())
                            <span
                                class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full flex items-center gap-1">
                                <i data-lucide="crown" class="w-3 h-3"></i>
                                Premium
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Monthly Growth -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <h2 class="font-bold text-gray-900 mb-4">Crescimento Mensal</h2>
            <div class="space-y-3">
                @foreach($monthlyGrowth as $month)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::create($month->year, $month->month)->locale('pt_BR')->isoFormat('MMMM YYYY') }}
                        </span>
                        <div class="flex items-center gap-2">
                            <div class="w-24 bg-gray-100 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full"
                                    style="width: {{ min(100, ($month->count / max($monthlyGrowth->max('count'), 1)) * 100) }}%">
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-900 w-8">{{ $month->count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bottom Nav (optional) -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 px-6 py-4"
        style="max-width: 448px; margin: 0 auto;">
        <div class="flex items-center justify-around">
            <a href="/admin" wire:navigate class="flex flex-col items-center gap-1 text-blue-600">
                <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
                <span class="text-xs font-medium">Dashboard</span>
            </a>
            <a href="/admin/users" wire:navigate
                class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-600">
                <i data-lucide="users" class="w-6 h-6"></i>
                <span class="text-xs font-medium">Usuários</span>
            </a>
            <a href="/admin/settings" wire:navigate
                class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-600">
                <i data-lucide="settings" class="w-6 h-6"></i>
                <span class="text-xs font-medium">Config</span>
            </a>
            <a href="/" wire:navigate class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-600">
                <i data-lucide="home" class="w-6 h-6"></i>
                <span class="text-xs font-medium">App</span>
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