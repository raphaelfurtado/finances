<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Finanças - Controle Financeiro Inteligente</title>

    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @include('partials.assets')
</head>

<body class="font-sans antialiased bg-white text-slate-800">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2">
                    <div
                        class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                    <span class="font-black text-xl tracking-tighter text-slate-900 italic uppercase">Minhas<span
                            class="text-indigo-600">Finanças</span></span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Recursos</a>
                    <a href="#testimonials"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Depoimentos</a>
                    <a href="#pricing"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Planos</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-sm font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg hover:shadow-indigo-500/30">
                            Acessar Painel
                            <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="hidden md:block text-sm font-semibold text-slate-600 hover:text-indigo-600">Entrar</a>
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-sm font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg hover:shadow-indigo-500/30">
                            Começar Grátis
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main>
        <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <div
                    class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold mb-6 border border-indigo-100">
                    <span class="flex h-2 w-2 rounded-full bg-indigo-600 mr-2 animate-pulse"></span>
                    Novo: Gestão de Orçamentos Premium
                </div>

                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-8 leading-tight">
                    Domine suas <br>
                    <span class="gradient-text">Finanças Pessoais</span>
                </h1>

                <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-600 mb-10">
                    Simples, intuitivo e poderoso. Acompanhe seus gastos, defina metas e alcance a liberdade financeira
                    que você sempre sonhou.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 md:text-lg transition-all shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-1">
                            Ir para meu Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 md:text-lg transition-all shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-1">
                            Criar Conta Grátis
                        </a>
                        <a href="#features"
                            class="inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-full text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 md:text-lg transition-all hover:border-slate-300">
                            Saber Mais
                        </a>
                    @endauth
                </div>

                <!-- Dashboard Preview Mockup -->
                <div class="relative max-w-4xl mx-auto">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl blur opacity-30">
                    </div>
                    <div class="relative rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80"
                            alt="App Dashboard Preview" class="w-full grayscale-[0.2]">
                        <div class="absolute inset-0 bg-gradient-to-t from-white/20 to-transparent pointer-events-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Background Decorations -->
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10 overflow-hidden pointer-events-none">
                <div
                    class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob">
                </div>
                <div
                    class="absolute top-40 left-0 w-[35rem] h-[35rem] bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Recursos</h2>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">
                        Tudo que você precisa para controlar seu dinheiro
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <!-- Feature 1 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div
                            class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 mb-6">
                            <i data-lucide="pie-chart" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Relatórios Detalhados</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Visualize para onde seu dinheiro está indo com gráficos intuitivos e detalhados por
                            categoria.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div
                            class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-6">
                            <i data-lucide="target" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Metas de Orçamento</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Defina limites de gastos para cada categoria e receba alertas para não estourar seu
                            orçamento.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div
                            class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mb-6">
                            <i data-lucide="smartphone" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Acesso em Qualquer Lugar</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Instale o app no seu celular e acesse seus dados financeiros de onde estiver, a qualquer
                            momento.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Depoimentos</h2>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">
                        O que nossos usuários dizem
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse($testimonials as $testimonial)
                        <div
                            class="bg-slate-50 p-8 rounded-2xl border border-slate-100 flex flex-col h-full shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-1 text-yellow-500 mb-6">
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star"
                                        class="w-5 h-5 {{ $testimonial->rating >= $i ? 'fill-current' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <blockquote class="flex-1 mb-6">
                                <p class="text-lg text-slate-700 italic">"{{ $testimonial->message }}"</p>
                            </blockquote>
                            <div class="flex items-center gap-4 border-t border-slate-200 pt-6">
                                @if($testimonial->show_avatar && $testimonial->user->avatar)
                                    <img src="{{ $testimonial->user->avatar }}" alt="{{ $testimonial->user->name }}"
                                        class="w-12 h-12 rounded-full">
                                @else
                                    <div
                                        class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">
                                        {{ substr($testimonial->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-900">
                                        {{ $testimonial->user->name }}
                                        @if($testimonial->show_profession && $testimonial->user->profession)
                                            <span class="text-xs font-normal text-slate-500 ml-1">•
                                                {{ $testimonial->user->profession }}</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-slate-500">Usuário Verificado</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-3 text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                            <p class="text-slate-500 text-lg mb-4">Seja o primeiro a deixar um depoimento!</p>
                            @auth
                                <a href="{{ route('my-testimonial') }}"
                                    class="text-indigo-600 font-semibold hover:text-indigo-700 hover:underline">
                                    Escrever meu depoimento &rarr;
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-indigo-600 font-semibold hover:text-indigo-700 hover:underline">
                                    Faça login para avaliar &rarr;
                                </a>
                            @endauth
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-24 bg-slate-900 text-white relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
                <div
                    class="absolute top-[10%] left-[10%] w-[20rem] h-[20rem] bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl animate-blob">
                </div>
                <div
                    class="absolute bottom-[10%] right-[10%] w-[20rem] h-[20rem] bg-purple-500 rounded-full mix-blend-screen filter blur-3xl animate-blob animation-delay-2000">
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-base font-semibold text-indigo-400 tracking-wide uppercase">Planos e Preços</h2>
                    <p class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl text-white">
                        Escolha o plano ideal
                    </p>
                    <p class="mt-4 text-xl text-slate-400">
                        Comece grátis e evolua conforme suas necessidades financeiras crescem.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-{{ $plans->count() }} gap-8 max-w-6xl mx-auto">
                    @foreach($plans as $plan)
                        @php
                            $isFree = $plan->price == 0;
                            $userOnThisPlan = false;
                            if (auth()->check()) {
                                if ($isFree) {
                                    $userOnThisPlan = !auth()->user()->isPremium();
                                } else {
                                    $userOnThisPlan = auth()->user()->subscribedToPrice($plan->stripe_id, 'premium');
                                }
                            }
                        @endphp
                        <div class="rounded-3xl p-8 border flex flex-col transition-all relative {{ $isFree ? 'bg-slate-800 border-slate-700 hover:border-slate-600' : 'bg-indigo-900 border-indigo-500 hover:border-indigo-400 transform md:-translate-y-4 shadow-2xl shadow-indigo-500/20' }}">
                            @if($plan->recommended)
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                                    <span class="bg-indigo-500 text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg">POPULAR</span>
                                </div>
                            @endif
                            
                            <div class="mb-6">
                                <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-extrabold text-white">R$ {{ number_format($plan->price / 100, 2, ',', '.') }}</span>
                                    <span class="text-slate-400">/{{ $plan->interval === 'year' ? 'ano' : 'mês' }}</span>
                                </div>
                                <p class="mt-4 text-slate-300 text-sm italic">
                                    {{ $isFree ? 'Perfeito para começar a organizar suas finanças.' : 'Para quem quer controle absoluto e análises profundas.' }}
                                </p>
                            </div>

                            <ul class="space-y-4 mb-8 flex-1">
                                @if(!$isFree)
                                    <li class="flex items-center gap-3">
                                        <i data-lucide="check" class="w-5 h-5 text-indigo-400 flex-shrink-0"></i>
                                        <span class="text-slate-200 text-sm">Tudo do Grátis +</span>
                                    </li>
                                @endif
                                @foreach($plan->benefits as $benefit)
                                    <li class="flex items-center gap-3">
                                        <i data-lucide="check" class="w-5 h-5 {{ $isFree ? 'text-green-500' : 'text-indigo-400' }} flex-shrink-0"></i>
                                        <span class="text-slate-200 text-sm">{{ $benefit->description }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            @auth
                                @if($userOnThisPlan)
                                    <button disabled class="w-full block text-center {{ $isFree ? 'bg-slate-700 text-slate-400' : 'bg-green-600 text-white' }} font-bold py-3 rounded-xl opacity-75 cursor-default">
                                        {{ $isFree ? 'Plano Atual' : 'Premium Ativo' }}
                                    </button>
                                @else
                                    <a href="{{ route('upgrade') }}" class="w-full block text-center {{ $isFree ? 'bg-slate-100 text-slate-900 border-2 border-slate-100' : 'bg-white text-indigo-600' }} font-bold py-3 rounded-xl hover:scale-105 transition-all">
                                        Fazer Upgrade
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="w-full block text-center {{ $isFree ? 'bg-slate-100 text-slate-900 border-2 border-slate-100' : 'bg-indigo-600 text-white' }} font-bold py-3 rounded-xl hover:scale-105 transition-all shadow-lg">
                                    Começar {{ $plan->name }}
                                </a>
                            @endauth
                        </div>
                    @endforeach
                </div>
                </div>
            </div>

            <!-- CTA Section -->
            <section class="py-24 bg-indigo-600 text-white relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 pattern-dots"></div>
                <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">Pronto para assumir o controle?</h2>
                    <p class="text-xl text-indigo-100 mb-10 max-w-2xl mx-auto">
                        Junte-se a milhares de pessoas que já transformaram sua vida financeira com o Finanças App.
                    </p>
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold rounded-full text-indigo-600 bg-white hover:bg-slate-50 transition-all shadow-lg">
                            Acessar Agora
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold rounded-full text-indigo-600 bg-white hover:bg-slate-50 transition-all shadow-lg">
                            Começar Gratuitamente
                        </a>
                    @endauth
                </div>
            </section>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center gap-2 mb-4 md:mb-0">
                    <div class="w-8 h-8 bg-indigo-600/20 rounded-lg flex items-center justify-center text-indigo-400">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                    <span class="font-bold text-xl text-white">Minhas Finanças</span>
                </div>
                <div class="flex gap-6 text-sm">
                    <a href="{{ route('legal') }}#termos" class="hover:text-white transition-colors">Termos de Uso</a>
                    <a href="{{ route('legal') }}#privacidade" class="hover:text-white transition-colors">Privacidade
                        (LGPD)</a>
                    <a href="mailto:suporte@minhasfinancas.com" class="hover:text-white transition-colors">Suporte</a>
                </div>
                <div class="mt-8 md:mt-0 text-sm">
                    &copy; {{ date('Y') }} Minhas Finanças. <span class="text-slate-600 ml-2">Não somos uma instituição
                        financeira.</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>