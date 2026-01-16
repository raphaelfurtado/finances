<div class="flex flex-col h-full bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold">Gerenciar Usuários</h1>
                <p class="text-indigo-100 text-sm">{{ $users->total() }} usuários total</p>
            </div>
            <a href="/admin" wire:navigate class="p-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </a>
        </div>

        <!-- Search and Filter -->
        <div class="space-y-3">
            <div class="relative">
                <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-indigo-200"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nome ou email..."
                    class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-indigo-200 focus:outline-none focus:ring-2 focus:ring-white/30">
            </div>

            <div class="flex gap-2">
                <button wire:click="$set('filterPremium', 'all')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filterPremium === 'all' ? 'bg-white text-indigo-600' : 'bg-white/10 text-white' }}">
                    Todos
                </button>
                <button wire:click="$set('filterPremium', 'premium')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filterPremium === 'premium' ? 'bg-white text-indigo-600' : 'bg-white/10 text-white' }}">
                    Premium
                </button>
                <button wire:click="$set('filterPremium', 'free')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filterPremium === 'free' ? 'bg-white text-indigo-600' : 'bg-white/10 text-white' }}">
                    Grátis
                </button>
            </div>
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

    <!-- Users List -->
    <div class="flex-1 overflow-y-auto px-6 py-4 mb-20">
        <div class="space-y-3">
            @foreach($users as $user)
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3 flex-1">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-bold text-gray-900 truncate">{{ $user->name }}</p>
                                    @if($user->isPremium())
                                        <span
                                            class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full flex items-center gap-1">
                                            <i data-lucide="crown" class="w-3 h-3"></i>
                                            Premium
                                        </span>
                                    @endif
                                    @if($user->isAdmin())
                                        <span
                                            class="px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                            Admin
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Membro desde {{ $user->created_at->locale('pt_BR')->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- User Stats & Premium Toggle -->
                    <div class="grid grid-cols-4 gap-2 mb-3 items-center">
                        <div class="bg-gray-50 rounded-xl p-2 text-center">
                            <p class="text-[10px] text-gray-500">Transações</p>
                            <p class="font-bold text-gray-900 text-sm">{{ $user->transactions->count() }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-2 text-center">
                            <p class="text-[10px] text-gray-500">Categorias</p>
                            <p class="font-bold text-gray-900 text-sm">{{ $user->categories->count() }}</p>
                        </div>
                        <div class="col-span-2 bg-gray-50 rounded-xl p-2 flex items-center justify-between px-3">
                            <div>
                                <p class="text-[10px] text-gray-500">Premium</p>
                                <p class="font-bold text-xs {{ $user->isPremium() ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $user->isPremium() ? 'SIM' : 'NÃO' }}
                                </p>
                            </div>
                            @if(!$user->isAdmin())
                                <button wire:click="togglePremium({{ $user->id }})"
                                    wire:confirm="Tem certeza que deseja {{ $user->isPremium() ? 'DESATIVAR' : 'ATIVAR' }} o acesso Premium para este usuário?"
                                    wire:loading.attr="disabled"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ $user->isPremium() ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-400' }}">
                                    <i data-lucide="{{ $user->isPremium() ? 'toggle-right' : 'toggle-left' }}"
                                        class="w-5 h-5"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button wire:click="viewUser({{ $user->id }})"
                            class="flex-1 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                            <i data-lucide="user-cog" class="w-4 h-4"></i>
                            Gerenciar Perfil
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

    <!-- User Details Modal -->
    @if($showUserModal && $selectedUser)
            <div class="fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-50" wire:click="closeModal">
                <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-md max-h-[90vh] overflow-y-auto" wire:click.stop>
                    <!-- Modal Header -->
                    <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 rounded-t-3xl">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900">Detalhes do Usuário</h2>
                            <button wire:click="closeModal" class="p-2 text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6 space-y-4">
                        <!-- User Info -->
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                {{ substr($selectedUser->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">{{ $selectedUser->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $selectedUser->email }}</p>
                            </div>
                        </div>

                        <!-- Status Badges -->
                        <div class="flex gap-2">
                            @if($selectedUser->isPremium())
                                <span
                                    class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full flex items-center gap-1">
                                    <i data-lucide="crown" class="w-4 h-4"></i>
                                    Premium
                                </span>
                            @endif
                            @if($selectedUser->isAdmin())
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-semibold rounded-full">
                                    Administrador
                                </span>
                            @endif
                        </div>

                        <!-- Premium Info -->
                        @if($selectedUser->isPremium())
                            <div class="bg-yellow-50 rounded-xl p-4">
                                <h4 class="font-semibold text-yellow-900 mb-2">Informações Premium</h4>
                                <div class="space-y-1 text-sm">
                                    <p class="text-yellow-700">
                                        Ativado em:
                                        {{ $selectedUser->premium_activated_at?->locale('pt_BR')?->isoFormat('DD/MM/YYYY') }}
                                    </p>
                                    @if($selectedUser->premium_expires_at)
                                        <p class="text-yellow-700">
                                            Expira em: {{ $selectedUser->premium_expires_at->locale('pt_BR')->isoFormat('DD/MM/YYYY') }}
                                        </p>
                                        <p class="text-yellow-700 font-semibold">
                                            {{ $selectedUser->premiumDaysRemaining() }} dias restantes
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Stats -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Estatísticas</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total de Transações</span>
                                    <span class="font-bold text-gray-900">{{ $selectedUser->transactions->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Receitas</span>
                                    <span
                                        class="font-bold text-green-600">{{ $selectedUser->transactions->where('type', 'income')->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Despesas</span>
                                    <span
                                        class="font-bold text-red-600">{{ $selectedUser->transactions->where('type', 'expense')->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Categorias Criadas</span>
                                    <span class="font-bold text-gray-900">{{ $selectedUser->categories->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Membro há</span>
                                    <span
                                        class="font-bold text-gray-900">{{ $selectedUser->created_at->locale('pt_BR')->diffForHumans(null, true) }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- Perfil Financeiro (Calibração) -->
                        <div class="bg-indigo-50 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-indigo-900 flex items-center gap-2">
                                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                                    Perfil Financeiro
                                </h4>
                                @if($selectedUser->employment_status || $selectedUser->family_structure || $selectedUser->financial_goal)
                                    <button wire:click="resetWizard({{ $selectedUser->id }})"
                                        wire:confirm="Tem certeza que deseja reiniciar o wizard deste usuário? Todos os dados demográficos serão apagados."
                                        class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg hover:bg-red-100 transition-colors uppercase">
                                        Reiniciar Wizard
                                    </button>
                                @endif
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="text-xs text-indigo-500 block mb-1">Situação de Trabalho</label>
                                    <select wire:model="employment_status" wire:change="saveOnboarding"
                                        class="w-full bg-white border border-indigo-100 rounded-lg text-sm p-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                        <option value="">Não informado</option>
                                        <option value="clt">CLT / Público</option>
                                        <option value="freelancer_pj">Freelancer / PJ</option>
                                        <option value="business_owner">Empresário</option>
                                        <option value="student">Estudante</option>
                                        <option value="retired">Aposentado</option>
                                        <option value="unemployed">Desempregado</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs text-indigo-500 block mb-1">Estrutura Familiar</label>
                                    <select wire:model="family_structure" wire:change="saveOnboarding"
                                        class="w-full bg-white border border-indigo-100 rounded-lg text-sm p-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                        <option value="">Não informado</option>
                                        <option value="single">Solteiro(a)</option>
                                        <option value="couple_no_kids">Casal sem filhos</option>
                                        <option value="couple_with_kids">Casal com filhos</option>
                                        <option value="single_parent">Mãe/Pai Solo</option>
                                        <option value="living_with_parents">Mora com os pais</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs text-indigo-500 block mb-1">Objetivo Financeiro</label>
                                    <select wire:model="financial_goal" wire:change="saveOnboarding"
                                        class="w-full bg-white border border-indigo-100 rounded-lg text-sm p-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                        <option value="">Não informado</option>
                                        <option value="debt_free">Sair das Dívidas</option>
                                        <option value="travel">Viajar</option>
                                        <option value="buy_property">Comprar Casa/Carro</option>
                                        <option value="investing">Investir / Aposentadoria</option>
                                        <option value="emergency_fund">Reserva de Emergência</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs text-indigo-500 block mb-1">Data de Nascimento</label>
                                    <input type="date" wire:model="birth_date" wire:change="saveOnboarding"
                                        class="w-full bg-white border border-indigo-100 rounded-lg text-sm p-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Impersonate Action -->
                    @if(auth()->id() !== $selectedUser->id)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <button wire:click="impersonate({{ $selectedUser->id }})"
                                class="w-full w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors flex items-center justify-center gap-2 shadow-lg shadow-indigo-200">
                                <i data-lucide="log-in" class="w-5 h-5"></i>
                                Entrar como este Usuário
                            </button>
                            <p class="text-xs text-center text-gray-400 mt-2">
                                Você navegará no sistema como se fosse este usuário.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

<!-- Bottom Nav -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 px-6 py-4"
    style="max-width: 448px; margin: 0 auto;">
    <div class="flex items-center justify-around">
        <a href="/admin" wire:navigate class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-600">
            <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
            <span class="text-xs font-medium">Dashboard</span>
        </a>
        <a href="/admin/users" wire:navigate class="flex flex-col items-center gap-1 text-blue-600">
            <i data-lucide="users" class="w-6 h-6"></i>
            <span class="text-xs font-medium">Usuários</span>
        </a>
        <a href="/dashboard" wire:navigate class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-600">
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

        Livewire.hook('morph.added', ({ el }) => {
            lucide.createIcons();
        });
    });
</script>