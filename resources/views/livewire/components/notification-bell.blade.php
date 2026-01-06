<div class="relative" x-data="{ open: false }" @click.away="open = false" wire:poll.30s>
    <!-- Bell Icon -->
    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-indigo-600 transition-colors focus:outline-none">
        <i data-lucide="bell" class="w-6 h-6"></i>
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] text-white font-bold items-center justify-center">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            </span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-[60] overflow-hidden"
         x-cloak>
        
        <!-- Header -->
        <div class="p-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-sm font-bold text-gray-900">Notificações</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" @click="open = false" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                    Marcar todas como lidas
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <div class="group relative p-4 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 {{ $notification->read_at ? 'opacity-60' : '' }}">
                    <div class="flex gap-3">
                        <div class="shrink-0 mt-1">
                            @php
                                $type = $notification->data['type'] ?? 'default';
                                $icon = match($type) {
                                    'fixed_expense_due' => 'calendar-clock',
                                    'budget_warning' => 'alert-triangle',
                                    'admin_message' => 'info',
                                    default => 'bell'
                                };
                                $colorClass = match($type) {
                                    'fixed_expense_due' => 'text-amber-500 bg-amber-50',
                                    'budget_warning' => 'text-red-500 bg-red-50',
                                    'admin_message' => 'text-blue-500 bg-blue-50',
                                    default => 'text-indigo-500 bg-indigo-50'
                                };
                            @endphp
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $colorClass }}">
                                <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-0.5">
                                <p class="text-xs font-bold text-gray-900 truncate">
                                    {{ $notification->data['title'] ?? 'Nova Notificação' }}
                                </p>
                                <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed line-clamp-2">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="bell-off" class="w-6 h-6 text-gray-300"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Nenhuma notificação</p>
                    <p class="text-xs text-gray-500 mt-1">Você está em dia com tudo!</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if($notifications->count() > 0)
            <a href="#" class="block p-3 text-center text-xs font-bold text-gray-500 hover:text-indigo-600 hover:bg-gray-50 transition-all border-t border-gray-50 bg-gray-50/30">
                Ver todas as notificações
            </a>
        @endif
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>