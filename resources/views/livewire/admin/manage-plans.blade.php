<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gerenciar Planos</h2>
        <button wire:click="create"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Novo Plano
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="p-4 font-semibold text-gray-600">Nome</th>
                    <th class="p-4 font-semibold text-gray-600">Preço</th>
                    <th class="p-4 font-semibold text-gray-600">Intervalo</th>
                    <th class="p-4 font-semibold text-gray-600">Stripe ID</th>
                    <th class="p-4 font-semibold text-gray-600">Status</th>
                    <th class="p-4 font-semibold text-gray-600 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($plans as $plan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <div class="font-medium text-gray-900">{{ $plan->name }}</div>
                            @if ($plan->recommended)
                                <span
                                    class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full mt-1 inline-block">Recomendado</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-600">
                            R$ {{ number_format($plan->price / 100, 2, ',', '.') }}
                        </td>
                        <td class="p-4 text-gray-600">
                            {{ $plan->interval === 'month' ? 'Mensal' : 'Anual' }}
                        </td>
                        <td class="p-4 text-gray-500 font-mono text-xs">
                            {{ $plan->stripe_id }}
                        </td>
                        <td class="p-4">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold {{ $plan->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $plan->active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <button wire:click="edit({{ $plan->id }})"
                                class="text-gray-500 hover:text-blue-600 transition-colors">
                                <i data-lucide="edit-2" class="w-5 h-5"></i>
                            </button>
                            <button wire:click="delete({{ $plan->id }})"
                                wire:confirm="Tem certeza que deseja excluir este plano?"
                                class="text-gray-500 hover:text-red-600 transition-colors">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-2xl shadow-xl max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $isEditing ? 'Editar Plano' : 'Novo Plano' }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Basic Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Plano</label>
                            <input type="text" wire:model="name"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                placeholder="Ex: Premium Mensal">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                            <input type="text" wire:model="slug"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                placeholder="Ex: premium-mensal">
                            @error('slug') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stripe Price ID</label>
                            <input type="text" wire:model="stripe_id"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                placeholder="price_...">
                            @error('stripe_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preço (R$)</label>
                            <input type="number" step="0.01" wire:model="price"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                placeholder="9.90">
                            @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Intervalo</label>
                            <select wire:model="interval"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                <option value="month">Mensal</option>
                                <option value="year">Anual</option>
                            </select>
                            @error('interval') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex flex-col gap-4 justify-end">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="active"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Ativo para venda</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="recommended"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Destacar como Recomendado</span>
                            </label>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 my-4"></div>

                    {{-- Benefits --}}
                    <div>
                        <h4 class="text-base font-semibold text-gray-900 mb-3">Benefícios do Plano</h4>
                        <div class="space-y-3 mb-4">
                            @foreach ($benefits as $index => $benefit)
                                <div class="flex items-center gap-2 group">
                                    <div
                                        class="w-full bg-gray-50 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700">
                                        {{ $benefit }}
                                    </div>
                                    <button wire:click="removeBenefit({{ $index }})"
                                        class="text-gray-400 hover:text-red-500 transition-colors p-1 opacity-0 group-hover:opacity-100">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex gap-2">
                            <input type="text" wire:model="newBenefit" wire:keydown.enter.prevent="addBenefit"
                                class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm"
                                placeholder="Adicionar novo benefício (Enter para adicionar)">
                            <button wire:click="addBenefit"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition-colors">
                                <i data-lucide="plus" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors font-medium">
                        Cancelar
                    </button>
                    <button wire:click="save"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-all active:scale-95 font-medium">
                        Salvar Plano
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>