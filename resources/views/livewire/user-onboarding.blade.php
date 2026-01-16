<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-8">
            <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500 ease-out"
                style="width: {{ ($step / 4) * 100 }}%"></div>
        </div>

        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Calibrando o Consultor
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Para eu te julgar corretamente, preciso saber quem é você na fila do pão.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="bg-white py-8 px-4 shadow sm:rounded-3xl sm:px-10 relative overflow-hidden">

            <!-- STEP 1: EMPREGO -->
            @if($step === 1)
                <div class="animate-in fade-in slide-in-from-right duration-300">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">Como o dinheiro entra na sua conta?</h3>
                    <div class="space-y-3">
                        <button wire:click="setOption('employment_status', 'clt')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-blue-100 p-3 rounded-xl mr-4 group-hover:bg-blue-200 transition-colors">
                                <i data-lucide="briefcase" class="w-6 h-6 text-blue-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Todo dia 5 pinga</span>
                                <span class="block text-xs text-gray-500">CLT / Funcionário Público</span>
                            </div>
                        </button>

                        <button wire:click="setOption('employment_status', 'freelancer_pj')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-purple-100 p-3 rounded-xl mr-4 group-hover:bg-purple-200 transition-colors">
                                <i data-lucide="trending-up" class="w-6 h-6 text-purple-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Montanha russa de emoções</span>
                                <span class="block text-xs text-gray-500">Autônomo / PJ / Freelancer</span>
                            </div>
                        </button>

                        <button wire:click="setOption('employment_status', 'business_owner')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-orange-100 p-3 rounded-xl mr-4 group-hover:bg-orange-200 transition-colors">
                                <i data-lucide="building-2" class="w-6 h-6 text-orange-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Eu pago os salários</span>
                                <span class="block text-xs text-gray-500">Empresário</span>
                            </div>
                        </button>

                        <button wire:click="setOption('employment_status', 'student')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-green-100 p-3 rounded-xl mr-4 group-hover:bg-green-200 transition-colors">
                                <i data-lucide="graduation-cap" class="w-6 h-6 text-green-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Meus pais depositam</span>
                                <span class="block text-xs text-gray-500">Estudante / Dependente</span>
                            </div>
                        </button>
                    </div>
                </div>
            @endif

            <!-- STEP 2: FAMÍLIA -->
            @if($step === 2)
                <div class="animate-in fade-in slide-in-from-right duration-300">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">Quem gasta seu dinheiro com você?</h3>
                    <div class="space-y-3">
                        <button wire:click="setOption('family_structure', 'single')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-gray-100 p-3 rounded-xl mr-4 group-hover:bg-gray-200 transition-colors">
                                <i data-lucide="user" class="w-6 h-6 text-gray-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Carreira Solo</span>
                                <span class="block text-xs text-gray-500">Moro sozinho(a)</span>
                            </div>
                        </button>

                        <button wire:click="setOption('family_structure', 'couple_no_kids')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-pink-100 p-3 rounded-xl mr-4 group-hover:bg-pink-200 transition-colors">
                                <i data-lucide="heart" class="w-6 h-6 text-pink-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Dupla Dinâmica</span>
                                <span class="block text-xs text-gray-500">Casal sem filhos</span>
                            </div>
                        </button>

                        <button wire:click="setOption('family_structure', 'couple_with_kids')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-yellow-100 p-3 rounded-xl mr-4 group-hover:bg-yellow-200 transition-colors">
                                <i data-lucide="users" class="w-6 h-6 text-yellow-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">A Grande Família</span>
                                <span class="block text-xs text-gray-500">Casal com filhos</span>
                            </div>
                        </button>

                        <button wire:click="setOption('family_structure', 'living_with_parents')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-indigo-100 p-3 rounded-xl mr-4 group-hover:bg-indigo-200 transition-colors">
                                <i data-lucide="home" class="w-6 h-6 text-indigo-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Hotel Mamãe</span>
                                <span class="block text-xs text-gray-500">Moro com os pais</span>
                            </div>
                        </button>
                    </div>
                </div>
            @endif

            <!-- STEP 3: OBJETIVO -->
            @if($step === 3)
                <div class="animate-in fade-in slide-in-from-right duration-300">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">Qual seu sonho atual (ou pesadelo)?</h3>
                    <div class="space-y-3">
                        <button wire:click="setOption('financial_goal', 'debt_free')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-red-100 p-3 rounded-xl mr-4 group-hover:bg-red-200 transition-colors">
                                <i data-lucide="life-buoy" class="w-6 h-6 text-red-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Sair do Buraco</span>
                                <span class="block text-xs text-gray-500">Quitar Dívidas</span>
                            </div>
                        </button>

                        <button wire:click="setOption('financial_goal', 'travel')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-sky-100 p-3 rounded-xl mr-4 group-hover:bg-sky-200 transition-colors">
                                <i data-lucide="plane" class="w-6 h-6 text-sky-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Mochilão ou Resort?</span>
                                <span class="block text-xs text-gray-500">Viajar</span>
                            </div>
                        </button>

                        <button wire:click="setOption('financial_goal', 'buy_property')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-emerald-100 p-3 rounded-xl mr-4 group-hover:bg-emerald-200 transition-colors">
                                <i data-lucide="key" class="w-6 h-6 text-emerald-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Teto Próprio</span>
                                <span class="block text-xs text-gray-500">Comprar Casa/Carro</span>
                            </div>
                        </button>

                        <button wire:click="setOption('financial_goal', 'investing')"
                            class="w-full group relative flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left">
                            <div class="bg-purple-100 p-3 rounded-xl mr-4 group-hover:bg-purple-200 transition-colors">
                                <i data-lucide="banknote" class="w-6 h-6 text-purple-600"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Ficar Rico</span>
                                <span class="block text-xs text-gray-500">Investir / Aposentadoria</span>
                            </div>
                        </button>
                    </div>
                </div>
            @endif

            <!-- STEP 4: NASCIMENTO -->
            @if($step === 4)
                <div class="animate-in fade-in slide-in-from-right duration-300">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">Quando devemos comprar o bolo?</h3>
                    <div class="text-center mb-6">
                        <div class="inline-block p-4 bg-pink-50 rounded-full mb-4">
                            <i data-lucide="cake" class="w-12 h-12 text-pink-500"></i>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Data de Nascimento</label>
                            <input type="date" wire:model="birth_date"
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg py-3 px-4">
                            @error('birth_date') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <button wire:click="save"
                            class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all active:scale-95">
                            Finalizar Calibração
                        </button>
                    </div>
                </div>
            @endif

            <!-- Navigation Buttons (Back) -->
            @if($step > 1)
                <div class="mt-8 flex justify-center">
                    <button wire:click="prevStep" class="text-sm text-gray-400 hover:text-gray-600 underline">
                        Voltar para pergunta anterior
                    </button>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>