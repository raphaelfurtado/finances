<div class="p-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Monitoramento de Crons</h1>
            <p class="text-gray-500 text-sm">Acompanhe a execução automática do sistema (Scheduler e Queues).</p>
        </div>
        <button wire:click="clearLogs"
            class="px-4 py-2 bg-white text-red-600 border border-red-100 rounded-xl font-bold hover:bg-red-50 transition-all text-sm flex items-center gap-2">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
            Limpar Logs
        </button>
    </div>

    @if (session()->has('message'))
        <div
            class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium rounded-xl flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Comando</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Início</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Duração</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Ação
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span
                                    class="text-sm font-bold text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded-lg border border-gray-100">
                                    {{ $log->command }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'success' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'failed' => 'bg-red-50 text-red-600 border-red-100',
                                        'running' => 'bg-blue-50 text-blue-600 border-blue-100 animate-pulse',
                                    ][$log->status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                @endphp
                                <span
                                    class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase border {{ $statusClasses }}">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">{{ $log->started_at->format('d/m/Y H:i:s') }}
                                </div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase">
                                    {{ $log->started_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-600">
                                {{ $log->duration_ms ? $log->duration_ms . 'ms' : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button x-data @click="alert(@js($log->output))"
                                    class="p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                                    <i data-lucide="terminal" class="w-5 h-5"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                                Nenhum log registrado até o momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-50">
            {{ $logs->links() }}
        </div>
    </div>

    <script>
        if (typeof lucide !== 'undefined') lucide.createIcons();
    </script>
</div>