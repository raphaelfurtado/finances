<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ openUpsell: false }">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <a href="{{ route('loans.show', $loan) }}"
                class="text-blue-600 hover:text-blue-700 font-medium text-sm mb-4 inline-block">
                ← Voltar para Detalhes
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Editar Empréstimo</h1>
        </div>

        @can('delete', $loan)
            <button wire:click="delete"
                wire:confirm="Tem certeza que deseja excluir este empréstimo? Todos os dados vinculados serão removidos."
                class="bg-red-50 text-red-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-100 transition">
                Excluir Empréstimo
            </button>
        @else
            <button x-on:click="openUpsell = true"
                class="bg-gray-100 text-gray-400 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-200 transition flex items-center gap-2">
                <i data-lucide="lock" class="w-4 h-4 text-amber-500"></i>
                Excluir (Premium)
            </button>
        @endcan
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if($loan->paid_amount > 0)
        <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-xl flex items-start gap-3">
            <i data-lucide="info" class="w-5 h-5 mt-0.5 text-amber-600"></i>
            <div>
                <p class="font-bold">Atenção!</p>
                <p class="text-sm">Já existem pagamentos registrados para este empréstimo. Alterar o valor total ou o número
                    de parcelas <b>não</b> mudará as parcelas atuais, apenas atualizará os dados cadastrais.</p>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Type Selection -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <label class="block text-sm font-medium text-gray-700 mb-3">Tipo de Empréstimo</label>
            <div class="grid grid-cols-2 gap-4">
                <label
                    class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition
                    {{ $type === 'receivable' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input type="radio" wire:model.live="type" value="receivable" class="sr-only">
                    <div class="text-center">
                        <span class="text-2xl mb-2 block">🟢</span>
                        <span class="font-medium text-gray-900">A Receber</span>
                    </div>
                </label>

                <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition
                    {{ $type === 'payable' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input type="radio" wire:model.live="type" value="payable" class="sr-only">
                    <div class="text-center">
                        <span class="text-2xl mb-2 block">🔴</span>
                        <span class="font-medium text-gray-900">A Pagar</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Counterparty Information -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                <input type="text" wire:model="counterparty_name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('counterparty_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Telefone/WhatsApp</label>
                <input type="text" wire:model="counterparty_phone"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <!-- Loan Details -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Valor Principal *</label>
                    <input type="number" step="0.01" wire:model.live="principal_amount"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Taxa de Juros (%)</label>
                    <input type="number" step="0.01" wire:model.live="interest_rate"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nº de Parcelas *</label>
                    <input type="number" wire:model.live="installments_count"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Frequência *</label>
                    <select wire:model.live="installment_frequency"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="weekly">Semanal (7 dias)</option>
                        <option value="biweekly">Quinzenal (14 dias)</option>
                        <option value="monthly">Mensal (30 dias)</option>
                        <option value="bimonthly">Bimestral (60 dias)</option>
                        <option value="quarterly">Trimestral (90 dias)</option>
                        <option value="yearly">Anual (365 dias)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data de Início *</label>
                    <input type="date" wire:model="start_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Juros</label>
                    <select wire:model.live="interest_calculation_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="simple_monthly">Juros Simples Mensal (Principal × Taxa × Meses)</option>
                        <option value="fixed_on_total">Valor Fixo sobre o Total (Principal × Taxa)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Multa Fixa (%) por Atraso</label>
                    <input type="number" step="0.01" wire:model="late_fee_percentage"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Juros Diários (%)</label>
                    <input type="number" step="0.01" wire:model="daily_interest_rate"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Live Preview -->
        <div class="bg-blue-600 rounded-xl p-6 text-white shadow-xl shadow-blue-200">
            <h3 class="text-lg font-bold mb-4 flex items-center">
                <i data-lucide="calculator" class="w-5 h-5 mr-2"></i>
                Resumo da Atualização
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wider mb-1">Total Calculado</p>
                    <p class="text-2xl font-bold">R$ {{ number_format($preview_total, 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wider mb-1">Valor da Parcela</p>
                    <p class="text-2xl font-bold">R$ {{ number_format($preview_installment, 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wider mb-1">Total de Juros</p>
                    <p class="text-2xl font-bold">R$ {{ number_format($preview_interest, 2, ',', '.') }}</p>
                </div>
            </div>
            @if($interest_rate > 0)
                <div class="mt-4 bg-white/20 rounded-lg p-3">
                    <p class="text-xs text-blue-100">
                        💡 <strong>Cálculo:</strong>
                        @if($interest_calculation_type === 'fixed_on_total')
                            Taxa fixa de {{ number_format($interest_rate, 2, ',', '.') }}% aplicada uma vez sobre R$
                            {{ number_format((float) ($principal_amount ?: 0), 2, ',', '.') }}
                        @else
                            Taxa de {{ number_format($interest_rate, 2, ',', '.') }}% por intervalo × {{ $installments_count }}
                            parcelas
                            sobre R$ {{ number_format((float) ($principal_amount ?: 0), 2, ',', '.') }}
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('loans.show', $loan) }}"
                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                Cancelar
            </a>
            <button type="submit"
                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition shadow-lg shadow-blue-200">
                Salvar Alterações
            </button>
        </div>
    </form>

    <!-- Upsell Modal -->
    <div x-show="openUpsell" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-[2rem] shadow-2xl max-w-sm w-full overflow-hidden border border-gray-100"
            x-on:click.away="openUpsell = false">
            <div class="bg-indigo-600 p-8 text-white text-center relative overflow-hidden">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold">Recurso Premium</h2>
                    <p class="text-indigo-100 text-sm mt-2 font-medium">A exclusão de registros é um recurso exclusivo
                        para membros PRO.</p>
                </div>
            </div>

            <div class="p-8 text-center">
                <p class="text-gray-600 text-sm mb-8 leading-relaxed">
                    Membros premium têm controle total sobre seus dados, incluindo a exclusão de históricos, além de
                    empréstimos ilimitados.
                </p>

                <a href="{{ route('upgrade') }}"
                    class="block w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-center transition-all shadow-lg shadow-indigo-200">
                    Ver Planos Premium
                </a>

                <button x-on:click="openUpsell = false"
                    class="block w-full mt-4 py-2 text-gray-400 text-sm font-medium hover:text-gray-600 transition-colors">
                    Voltar
                </button>
            </div>
        </div>
    </div>
</div>