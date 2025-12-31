<div class="p-6 bg-gray-50 min-h-screen pb-24">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-gray-900 leading-tight">Monitoramento de Uso</h1>
            <p class="text-sm text-gray-500">Saiba quais funcionalidades seus clientes mais amam.</p>
        </div>
        <select wire:model.live="days"
            class="bg-white border-gray-200 rounded-xl text-sm font-bold text-gray-700 shadow-sm focus:ring-blue-500">
            <option value="1">Últimas 24h</option>
            <option value="7">Últimos 7 dias</option>
            <option value="30">Últimos 30 dias</option>
        </select>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase">Ativos (24h)</span>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $activeUsers }}</p>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-purple-50 rounded-2xl flex items-center justify-center">
                    <i data-lucide="mouse-pointer-2" class="w-5 h-5 text-purple-600"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase">Acessos</span>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $totalInteractions }}</p>
        </div>
    </div>

    <!-- Features Usage -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Uso por Funcionalidade</h3>
        <div class="space-y-6">
            @foreach($topFeatures as $feature)
                @php
                    $percentage = $totalInteractions > 0 ? ($feature->total / $totalInteractions) * 100 : 0;
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-700">{{ $feature->feature_area }}</span>
                        <span class="text-xs font-black text-blue-600">{{ number_format($percentage, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-50 rounded-full h-3 overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full transition-all duration-1000"
                            style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1 font-medium">{{ $feature->total }} interações</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- System Health & Growth Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- System Health -->
        <div
            class="bg-white rounded-3xl p-6 shadow-sm border {{ $criticalErrors->count() > 0 ? 'border-red-100' : 'border-gray-100' }}">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="alert-circle"
                        class="w-4 h-4 {{ $criticalErrors->count() > 0 ? 'text-red-500' : 'text-green-500' }}"></i>
                    Alertas do Sistema
                </h3>
            </div>

            @if($criticalErrors->count() > 0)
                <div class="space-y-4">
                    @foreach($criticalErrors as $error)
                        <div class="p-3 bg-red-50 rounded-2xl border border-red-100">
                            <p class="text-[10px] font-black text-red-600 uppercase mb-1">
                                {{ $error->created_at->diffForHumans() }}</p>
                            <p class="text-xs font-bold text-gray-900 line-clamp-2">{{ $error->message }}</p>
                            <p class="text-[9px] text-gray-500 mt-1 truncate">Rota: {{ $error->url }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-3">
                        <i data-lucide="shield-check" class="w-8 h-8 text-green-500"></i>
                    </div>
                    <p class="text-sm font-bold text-green-600">Sistema Saudável ✅</p>
                    <p class="text-xs text-gray-400 mt-1">Nenhum erro crítico nas últimas 24h.</p>
                </div>
            @endif
        </div>

        <!-- Latest Registrations -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center gap-2 mb-6">
                <i data-lucide="user-plus" class="w-4 h-4 text-blue-500"></i>
                Últimos Cadastros
            </h3>

            <div class="space-y-4">
                @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center text-gray-500 font-bold text-xs uppercase shadow-inner">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                                <p class="text-[10px] text-gray-400 font-medium">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full">
                            {{ $user->created_at->diffForHumans(null, true) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top Pages Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Páginas mais Visitadas</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase">URL</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase text-right">Acessos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($topPages as $page)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-gray-600 font-mono">{{ $page->url }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-xs font-black text-gray-900">{{ $page->total }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back to Admin -->
    <div class="mt-8">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center justify-center gap-2 text-gray-400 hover:text-gray-600 font-bold text-sm transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Voltar ao Painel Admin
        </a>
    </div>
</div>