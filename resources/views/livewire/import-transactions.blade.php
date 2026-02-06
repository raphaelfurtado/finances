<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Importar Transações</h1>

        <div class="bg-white shadow sm:rounded-lg p-6">
            <!-- Upload Section -->
            @if(!$transactions)
                <div class="space-y-6">
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-lg p-12 text-center hover:border-indigo-500 transition-colors cursor-pointer relative">
                        <input type="file" wire:model.live="file"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="text-gray-600">
                            @if ($file)
                                <svg class="mx-auto h-12 w-12 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-1 text-sm text-gray-900 font-medium">
                                    {{ $file->getClientOriginalName() }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">Clique para alterar</p>
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-1 text-sm text-gray-600">
                                    <span class="font-medium text-indigo-600 hover:text-indigo-500">Faça upload de um
                                        arquivo</span>
                                    ou arraste e solte
                                </p>
                                <p class="mt-1 text-xs text-gray-500">OFX ou CSV até 10MB</p>
                            @endif
                        </div>
                    </div>

                    @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    <div class="flex justify-end">
                        <button wire:click="parseFile" wire:loading.attr="disabled" wire:target="file, parseFile"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg wire:loading wire:target="file, parseFile"
                                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="file, parseFile">Processar Arquivo</span>
                            <span wire:loading wire:target="file, parseFile">Processando...</span>
                        </button>
                    </div>
                </div>
            @else
                <!-- Review Section -->
                <div class="relative">
                    <!-- Global Loading Overlay for Review Section - Centered in Viewport -->
                    <div wire:loading wire:target="toggleSelectAll, selectedRows, togglePayments, removeTransaction"
                        class="fixed inset-0 bg-gray-900/40 backdrop-blur-[2px] z-[100] grid place-items-center p-4"
                        style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;">
                        <div
                            class="bg-white p-8 rounded-3xl shadow-2xl flex flex-col items-center gap-6 border border-gray-100 max-w-sm w-full animate-in fade-in zoom-in duration-300">
                            <div class="relative flex items-center justify-center">
                                <div class="absolute inset-0 rounded-full bg-indigo-100 animate-ping opacity-30 scale-150">
                                </div>
                                <div class="relative bg-white rounded-full p-2">
                                    <svg class="animate-spin h-16 w-16 text-indigo-600" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-center">
                                <h3 class="text-xl font-black text-gray-900 tracking-tight">Calculando...</h3>
                                <p class="text-sm text-gray-500 mt-2 font-medium">Estamos processando os registros para
                                    garantir que tudo esteja correto.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 mb-6">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                            <h2 class="text-lg font-medium text-gray-900">Revisar Transações</h2>

                            <button wire:click="$set('transactions', [])"
                                class="text-sm text-gray-500 hover:text-red-600 flex items-center gap-1 self-end md:self-auto">
                                <i data-lucide="x" class="w-4 h-4"></i>
                                Cancelar e Enviar Novo
                            </button>
                        </div>

                        <!-- Date Override Controls -->
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center bg-yellow-50 px-4 py-3 rounded-lg border border-yellow-200 gap-3">
                            <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                                <input type="checkbox" wire:model.live="useTargetDate"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="font-bold text-gray-800">Considerar Vencimento:</span>
                            </label>
                            <input type="date" wire:model.live="targetDate"
                                class="w-full sm:w-auto border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm p-2 disabled:bg-gray-100 disabled:text-gray-400"
                                @if(!$useTargetDate) disabled @endif>
                        </div>
                    </div>

                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
                        <i data-lucide="info" class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-blue-700">
                            <strong>Dica:</strong> As transações que você deixar sem categoria serão salvas automaticamente
                            em <span class="font-bold bg-blue-100 text-blue-800 px-1 py-0.5 rounded">Outros</span>.
                        </p>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <!-- Removed wire:model to avoid conflict, relying on manual toggle and checked state -->
                                        <input type="checkbox" wire:click="toggleSelectAll" @if($selectAll) checked @endif
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Descrição</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Valor</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Categoria</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transactions as $index => $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" wire:model.live="selectedRows" value="{{ $index }}"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($transaction['date'])->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <input type="text" wire:model="transactions.{{ $index }}.description"
                                                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center">
                                                <span
                                                    class="mr-1 {{ $transaction['type'] == 'expense' ? 'text-red-600' : 'text-green-600' }}">R$</span>
                                                <input type="number" step="0.01"
                                                    wire:model.live.blur="transactions.{{ $index }}.amount"
                                                    class="w-24 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm sm:text-sm {{ $transaction['type'] == 'expense' ? 'text-red-600' : 'text-green-600' }} font-bold text-right">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <select wire:model="transactions.{{ $index }}.category_id"
                                                class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Selecione...</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" class="font-bold">{{ $category->name }}
                                                    </option>
                                                    @foreach($category->children as $child)
                                                        <option value="{{ $child->id }}">&nbsp;&nbsp;↳ {{ $child->name }}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button wire:click="removeTransaction({{ $index }})"
                                                class="text-red-600 hover:text-red-900">Remover</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div
                        class="mt-8 flex flex-col md:flex-row justify-end items-center gap-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <!-- Action Link -->
                        <button wire:click="togglePayments"
                            class="text-sm text-indigo-600 hover:text-indigo-800 font-medium underline decoration-indigo-200 hover:decoration-indigo-500 transition-all">
                            Ignorar Pagamentos
                        </button>

                        <!-- Totals Block -->
                        <div
                            class="flex flex-col items-end text-sm text-gray-600 bg-white px-4 py-2 rounded-lg border border-gray-100 shadow-sm w-full md:w-auto">
                            <div class="flex justify-between w-full md:w-48 gap-4">
                                <span class="font-medium">Entradas:</span>
                                <span class="text-green-600 font-bold">R$
                                    {{ number_format($this->summary['income'], 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between w-full md:w-48 gap-4 mt-1">
                                <span class="font-medium">Saídas:</span>
                                <span class="text-red-600 font-bold">R$
                                    {{ number_format($this->summary['expense'], 2, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-gray-100 mt-2 pt-2 flex justify-between w-full md:w-48 gap-4">
                                <span class="font-bold text-gray-800">Total:</span>
                                <span
                                    class="font-bold {{ $this->summary['net'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    R$ {{ number_format($this->summary['net'], 2, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Count & Action -->
                        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                            <span class="text-sm text-gray-500 font-medium whitespace-nowrap">
                                <span class="font-bold text-gray-900 text-lg mx-1">
                                    {{ is_array($selectedRows) ? count($selectedRows) : 0 }}
                                </span>
                                itens selecionados
                            </span>

                            <button wire:click="confirmImport" wire:loading.attr="disabled"
                                wire:confirm="Tem certeza que deseja importar as transações selecionadas?"
                                class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:ring-4 focus:ring-green-200 transition-all shadow-md flex justify-center items-center disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg wire:loading wire:target="confirmImport"
                                    class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span>Importar Selecionadas</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>