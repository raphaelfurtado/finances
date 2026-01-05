<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('loans.index') }}"
            class="text-blue-600 hover:text-blue-700 font-medium text-sm mb-4 inline-block">
            ← Voltar para Empréstimos
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Novo Registro</h1>
    </div>

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
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
                        <span class="text-2xl mb-2 block">📈</span>
                        <span class="font-medium text-gray-900">Crédito</span>
                        <p class="text-xs text-gray-500 mt-1">Contas a receber</p>
                    </div>
                </label>

                <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition
                    {{ $type === 'payable' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input type="radio" wire:model.live="type" value="payable" class="sr-only">
                    <div class="text-center">
                        <span class="text-2xl mb-2 block">📉</span>
                        <span class="font-medium text-gray-900">Débito</span>
                        <p class="text-xs text-gray-500 mt-1">Contas a pagar</p>
                    </div>
                </label>
            </div>
            @error('type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Counterparty Information -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm space-y-4">
            <h3 class="font-semibold text-gray-900 mb-4">Informações do Contato</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                <input type="text" wire:model="counterparty_name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Ex: João Silva ou Banco Itaú">
                @error('counterparty_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Telefone/WhatsApp</label>
                <input type="text" wire:model="counterparty_phone"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="(11) 99999-9999">
                @error('counterparty_phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Interest Calculation Type -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <label class="block text-sm font-medium text-gray-700 mb-3">Tipo de Cálculo de Juros</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label
                    class="relative flex flex-col p-4 border-2 rounded-lg cursor-pointer transition
                    {{ $interest_calculation_type === 'fixed_on_total' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input type="radio" wire:model.live="interest_calculation_type" value="fixed_on_total"
                        class="sr-only">
                    <div>
                        <span class="font-medium text-gray-900 flex items-center">
                            <span class="text-xl mr-2">📊</span>
                            Taxa Fixa (Sobre o Total)
                        </span>
                        <p class="text-xs text-gray-500 mt-2">A porcentagem é aplicada uma vez sobre o valor principal,
                            independente do prazo.</p>
                        <p class="text-xs text-purple-600 mt-1 font-medium">Ex: R$ 1.000 com 30% = R$ 300 de juros</p>
                    </div>
                </label>

                <label
                    class="relative flex flex-col p-4 border-2 rounded-lg cursor-pointer transition
                    {{ $interest_calculation_type === 'simple_monthly' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input type="radio" wire:model.live="interest_calculation_type" value="simple_monthly"
                        class="sr-only">
                    <div>
                        <span class="font-medium text-gray-900 flex items-center">
                            <span class="text-xl mr-2">📈</span>
                            Taxa Mensal (Multiplicada)
                        </span>
                        <p class="text-xs text-gray-500 mt-2">A porcentagem é aplicada sobre o principal e multiplicada
                            pelos meses.</p>
                        <p class="text-xs text-blue-600 mt-1 font-medium">Ex: R$ 1.000 com 30% em 2x = R$ 600 de juros
                        </p>
                    </div>
                </label>
            </div>
            @error('interest_calculation_type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Loan Details -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm space-y-4">
            <h3 class="font-semibold text-gray-900 mb-4">Detalhes do Registro</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Valor Emprestado (Principal) *</label>
                    <input type="number" step="0.01" wire:model.live="principal_amount"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="1000.00">
                    @error('principal_amount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Taxa de Juros (% ao mês)</label>
                    <input type="number" step="0.01" wire:model.live="interest_rate"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0 (sem juros)">
                    @error('interest_rate') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Número de Parcelas *</label>
                    <input type="number" wire:model.live="installments_count"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="12">
                    @error('installments_count') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Frequência de Pagamento *</label>
                    <select wire:model.live="installment_frequency"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="weekly">Semanal (7 dias)</option>
                        <option value="biweekly">Quinzenal (14 dias)</option>
                        <option value="monthly">Mensal (30 dias)</option>
                        <option value="bimonthly">Bimestral (60 dias)</option>
                        <option value="quarterly">Trimestral (90 dias)</option>
                        <option value="yearly">Anual (365 dias)</option>
                    </select>
                    @error('installment_frequency') <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data de Início *</label>
                    <input type="date" wire:model="start_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('start_date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100">
                <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center">
                    <i data-lucide="alert-triangle" class="w-4 h-4 mr-2 text-amber-500"></i>
                    Penalidades por Atraso (Opcional)
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Multa Fixa (%)</label>
                        <input type="number" step="0.01" wire:model="late_fee_percentage"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ex: 2.00">
                        <p class="text-[10px] text-gray-500 mt-1">Aplicada uma única vez no primeiro dia de atraso.</p>
                        @error('late_fee_percentage') <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Juros Diários (%)</label>
                        <input type="number" step="0.01" wire:model="daily_interest_rate"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ex: 0.10">
                        <p class="text-[10px] text-gray-500 mt-1">Aplicado sobre o valor original por dia de atraso.</p>
                        @error('daily_interest_rate') <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Preview -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold text-blue-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                    </path>
                </svg>
                Prévia do Cálculo
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Total de Juros</p>
                    <p class="text-2xl font-bold text-orange-600">R$ {{ number_format($preview_interest, 2, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Valor Final Estimado</p>
                    <p class="text-2xl font-bold text-blue-700">R$ {{ number_format($preview_total, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Valor da Parcela</p>
                    <p class="text-2xl font-bold text-indigo-700">R$
                        {{ number_format($preview_installment, 2, ',', '.') }}
                    </p>
                </div>
            </div>
            @if($interest_rate > 0)
                <div class="mt-4 bg-white/50 rounded-lg p-3">
                    <p class="text-xs text-blue-800">
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
            <a href="{{ route('loans.index') }}"
                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                Cancelar
            </a>
            <button type="submit"
                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition flex items-center gap-2"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Salvar Registro</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Salvando...
                </span>
            </button>
        </div>
    </form>
</div>