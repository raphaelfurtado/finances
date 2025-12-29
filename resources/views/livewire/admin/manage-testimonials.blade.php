<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Gerenciar Depoimentos</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Avaliação</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                            Depoimento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($testimonials as $testimonial)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($testimonial->user->avatar)
                                            <img class="h-10 w-10 rounded-full" src="{{ $testimonial->user->avatar }}" alt="">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                                {{ substr($testimonial->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $testimonial->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $testimonial->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-yellow-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star"
                                            class="w-4 h-4 {{ $testimonial->rating >= $i ? 'fill-current' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900 line-clamp-2" title="{{ $testimonial->message }}">
                                    {{ $testimonial->message }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $testimonial->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $testimonial->is_approved ? 'Aprovado' : 'Pendente' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleAvatar({{ $testimonial->id }})"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $testimonial->show_avatar ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                    <span class="sr-only">Mostrar Foto</span>
                                    <span aria-hidden="true"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $testimonial->show_avatar ? 'translate-x-5' : 'translate-x-0' }}">
                                    </span>
                                </button>
                                <span
                                    class="ml-2 text-xs text-gray-500">{{ $testimonial->show_avatar ? 'Sim' : 'Não' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button wire:click="toggleApproval({{ $testimonial->id }})"
                                    class="text-indigo-600 hover:text-indigo-900"
                                    title="{{ $testimonial->is_approved ? 'Rejeitar' : 'Aprovar' }}">
                                    @if($testimonial->is_approved)
                                        <i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i>
                                    @else
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                                    @endif
                                </button>
                                <button wire:click="delete({{ $testimonial->id }})"
                                    wire:confirm="Tem certeza que deseja excluir este depoimento?"
                                    class="text-red-600 hover:text-red-900" title="Excluir">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Nenhum depoimento encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $testimonials->links() }}
        </div>
    </div>
</div>