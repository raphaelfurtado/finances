<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Finanças — Suas contas fixas, sob controle</title>
    <meta name="description"
        content="App brasileiro pra quem vive das contas do mês. Lança rápido, vê quanto vai sobrar e ouve a real do Consultor Sincero. Sem sync bancário que toda hora quebra.">

    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @include('partials.assets')
</head>

<body class="font-sans antialiased bg-white text-slate-800">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <div
                        class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                    <span class="font-black text-xl tracking-tighter text-slate-900 italic uppercase">Minhas<span
                            class="text-indigo-600">Finanças</span></span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">O que faz</a>
                    <a href="#honest"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">O que NÃO
                        faz</a>
                    <a href="#pricing"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Planos</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center justify-center px-5 py-2 text-sm font-bold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg hover:shadow-indigo-500/30">
                            Acessar Painel
                            <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="hidden md:block text-sm font-semibold text-slate-600 hover:text-indigo-600">Entrar</a>
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center px-5 py-2 text-sm font-bold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg hover:shadow-indigo-500/30">
                            Começar Grátis
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero -->
        <div class="relative pt-32 pb-16 lg:pt-44 lg:pb-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <!-- Copy -->
                    <div class="text-center lg:text-left">
                        <div
                            class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold mb-6 border border-indigo-100 uppercase tracking-wider">
                            <span class="flex h-2 w-2 rounded-full bg-indigo-600 mr-2 animate-pulse"></span>
                            Em PT-BR de verdade. Sem firula.
                        </div>

                        <h1
                            class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 mb-6 leading-[1.05]">
                            Suas contas fixas, sob controle.
                            <span class="gradient-text">E um consultor que não passa pano.</span>
                        </h1>

                        <p class="text-lg md:text-xl text-slate-600 mb-8 max-w-xl lg:max-w-none mx-auto">
                            App brasileiro pra quem vive das contas do mês. Lança rápido, vê quanto vai sobrar e ouve a
                            real do <strong class="text-slate-900">Consultor Sincero</strong>. Sem sync bancário que
                            toda hora quebra. Sem promessa de "liberdade financeira em 30 dias".
                        </p>

                        <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-3">
                            @auth
                                <a href="{{ route('dashboard') }}"
                                    class="inline-flex items-center justify-center px-7 py-4 text-base font-bold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5">
                                    Ir para meu Painel
                                    <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="inline-flex items-center justify-center px-7 py-4 text-base font-bold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5">
                                    Começar Grátis
                                    <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                                </a>
                                <a href="#features"
                                    class="inline-flex items-center justify-center px-7 py-4 text-base font-bold rounded-full text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all">
                                    Ver como funciona
                                </a>
                            @endauth
                        </div>

                        <p class="text-xs text-slate-500 mt-4">
                            Sem cartão pra começar. Login com Google.
                        </p>
                    </div>

                    <!-- Mockup do dashboard real -->
                    <div class="relative">
                        <div
                            class="absolute -inset-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl blur-2xl opacity-25">
                        </div>
                        <div
                            class="relative rounded-3xl bg-white shadow-2xl border border-slate-200 overflow-hidden text-left">
                            <!-- Header (seletor de mês) -->
                            <div class="bg-white px-5 py-3 border-b border-gray-100">
                                <div class="flex items-center justify-center gap-3">
                                    <i data-lucide="chevron-left" class="w-4 h-4 text-gray-400"></i>
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Junho de
                                        2026</span>
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                                </div>
                            </div>

                            <div class="p-5 space-y-4 bg-gray-50">
                                <!-- Saldo Acumulado -->
                                <div class="bg-indigo-50 rounded-2xl p-4 border border-indigo-100">
                                    <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider mb-1">
                                        Saldo Acumulado</div>
                                    <div class="text-3xl font-bold text-gray-900 mb-1">R$ 4.382,15</div>
                                    <p class="text-xs text-gray-600">Tudo que entrou menos tudo que saiu. Não é o que tá no banco.</p>
                                </div>

                                <!-- Espelho do mês -->
                                <div>
                                    <h3
                                        class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">
                                        Espelho do Mês</h3>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div
                                            class="bg-green-50 rounded-lg p-2.5 border border-green-100 text-center">
                                            <div class="text-[10px] text-gray-500 mb-0.5">Entradas</div>
                                            <div class="text-sm font-bold text-green-600">R$ 5.200</div>
                                        </div>
                                        <div class="bg-red-50 rounded-lg p-2.5 border border-red-100 text-center">
                                            <div class="text-[10px] text-gray-500 mb-0.5">Saídas</div>
                                            <div class="text-sm font-bold text-red-600">R$ 3.117</div>
                                        </div>
                                        <div
                                            class="bg-indigo-50 rounded-lg p-2.5 border border-indigo-100 text-center">
                                            <div class="text-[10px] text-gray-500 mb-0.5">Sobrou</div>
                                            <div class="text-sm font-bold text-indigo-600">R$ 2.083</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Consultor Sincero -->
                                <div
                                    class="bg-gradient-to-r from-purple-800 to-indigo-900 rounded-2xl p-4 text-white relative overflow-hidden">
                                    <div
                                        class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl">
                                    </div>
                                    <div class="flex items-start gap-3 relative z-10">
                                        <div class="bg-white/20 p-1.5 rounded-xl backdrop-blur-sm flex-shrink-0">
                                            <i data-lucide="bot" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <div>
                                            <h3
                                                class="text-[9px] font-bold text-purple-200 uppercase tracking-wider mb-0.5">
                                                Consultor Sincero</h3>
                                            <p class="font-medium text-xs leading-relaxed">"Sobrou dinheiro? Certeza
                                                que você não esqueceu de pagar nada?"</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bg decoration -->
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10 overflow-hidden pointer-events-none">
                <div
                    class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30">
                </div>
                <div
                    class="absolute top-40 left-0 w-[35rem] h-[35rem] bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30">
                </div>
            </div>
        </div>

        <!-- Features (o que faz) -->
        <section id="features" class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-sm font-bold text-indigo-600 tracking-widest uppercase">O que ele faz</h2>
                    <p class="mt-3 text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Quatro coisas. Bem feitas.
                    </p>
                    <p class="mt-4 text-lg text-slate-600">
                        Sem virar 18 abas de planilha pra você se perder.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                    <!-- 1. Contas fixas no centro -->
                    <div
                        class="bg-white p-7 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:border-indigo-100 transition-all">
                        <div
                            class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 mb-5">
                            <i data-lucide="calendar-clock" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Contas fixas, no foco</h3>
                        <p class="text-slate-600 leading-relaxed">
                            O coração do app. Cadastra aluguel, luz, internet, Netflix… A gente avisa antes de vencer,
                            mostra quanto já saiu no mês e o que ainda vem. Lista ordenada pelo que tá atrasado primeiro
                            — não pelo dia.
                        </p>
                    </div>

                    <!-- 2. Consultor Sincero -->
                    <div
                        class="bg-white p-7 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:border-purple-100 transition-all">
                        <div
                            class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mb-5">
                            <i data-lucide="bot" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Consultor Sincero</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Sem chavão de coach. Em PT-BR de verdade: quando você gasta demais, sobra demais ou trava
                            no mesmo iFood, ele aparece. <em class="text-slate-700">"Patrocinador oficial do
                                Delivery"</em>, esse tipo de coisa.
                        </p>
                    </div>

                    <!-- 3. Sobra do mês -->
                    <div
                        class="bg-white p-7 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:border-green-100 transition-all">
                        <div
                            class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-5">
                            <i data-lucide="trending-up" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Sobra Recorrente</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Receita recorrente menos custo fixo. Aquele número que ninguém te mostra e você precisa
                            saber pra parar de se enganar no fim do mês. Mais barra de progresso de quanto já foi pago.
                        </p>
                    </div>

                    <!-- 4. Importação OFX -->
                    <div
                        class="bg-white p-7 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:border-blue-100 transition-all">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-5">
                            <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Importação de OFX e planilha</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Nada de prometer sync bancário que toda hora quebra. Você importa o OFX do seu banco ou a
                            sua planilha quando quiser. Mais honesto, e funciona.
                        </p>
                    </div>
                </div>

                <!-- "Tem mais" -->
                <div
                    class="mt-10 max-w-5xl mx-auto bg-white border border-slate-100 rounded-2xl p-6 flex flex-wrap items-center gap-x-8 gap-y-3 justify-center text-sm text-slate-600">
                    <span class="flex items-center gap-2"><i data-lucide="banknote" class="w-4 h-4 text-indigo-600"></i>
                        Crédito e parcelas</span>
                    <span class="flex items-center gap-2"><i data-lucide="target" class="w-4 h-4 text-indigo-600"></i>
                        Limites por categoria</span>
                    <span class="flex items-center gap-2"><i data-lucide="bell" class="w-4 h-4 text-indigo-600"></i>
                        Notificação push</span>
                    <span class="flex items-center gap-2"><i data-lucide="smartphone" class="w-4 h-4 text-indigo-600"></i>
                        Funciona no celular</span>
                </div>
            </div>
        </section>

        <!-- Honestidade — o que NÃO faz -->
        <section id="honest" class="py-24 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-sm font-bold text-indigo-600 tracking-widest uppercase">Honestidade</h2>
                    <p class="mt-3 text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                        O que a gente <span class="gradient-text">não faz</span>
                    </p>
                    <p class="mt-4 text-lg text-slate-600">
                        Porque app financeiro que promete demais costuma entregar de menos.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4 p-5 bg-slate-50 border border-slate-100 rounded-2xl">
                        <div
                            class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-600 flex-shrink-0">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 mb-1">Sync bancário automático</h3>
                            <p class="text-sm text-slate-600">É o que mais quebra nos concorrentes — e gera enxurrada
                                de reclamação. Em vez disso, a gente importa seu OFX ou planilha quando você quiser.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-5 bg-slate-50 border border-slate-100 rounded-2xl">
                        <div
                            class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-600 flex-shrink-0">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 mb-1">Curso de "liberdade financeira em 30 dias"</h3>
                            <p class="text-sm text-slate-600">Sem upsell de mentoria, sem grupo VIP, sem coach. Só a
                                ferramenta. E o Consultor te dizendo a real.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-5 bg-slate-50 border border-slate-100 rounded-2xl">
                        <div
                            class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-600 flex-shrink-0">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 mb-1">Notificação te chamando de "querido"</h3>
                            <p class="text-sm text-slate-600">A gente trata você como gente adulta. Vai falar quando
                                tiver algo útil a falar. E vai falar do jeito que a gente fala — direto.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section id="testimonials" class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-sm font-bold text-indigo-600 tracking-widest uppercase">Quem usa fala</h2>
                    <p class="mt-3 text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Sem texto pago de influencer
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($testimonials as $testimonial)
                        <div
                            class="bg-white p-7 rounded-2xl border border-slate-100 flex flex-col h-full shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-1 text-yellow-500 mb-5">
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star"
                                        class="w-4 h-4 {{ $testimonial->rating >= $i ? 'fill-current' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <blockquote class="flex-1 mb-5">
                                <p class="text-base text-slate-700 leading-relaxed">"{{ $testimonial->message }}"</p>
                            </blockquote>
                            <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                                @if($testimonial->show_avatar && $testimonial->user->avatar)
                                    <img src="{{ $testimonial->user->avatar }}" alt="{{ $testimonial->user->name }}"
                                        class="w-10 h-10 rounded-full">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                        {{ substr($testimonial->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">
                                        {{ $testimonial->user->name }}
                                        @if($testimonial->show_profession && $testimonial->user->profession)
                                            <span class="text-xs font-normal text-slate-500">·
                                                {{ $testimonial->user->profession }}</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500">Usuário verificado</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-3 text-center py-12 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                            <i data-lucide="message-square" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
                            <p class="text-slate-500 mb-4">Sem depoimentos ainda. Mas o app já tá no ar.</p>
                            @auth
                                <a href="{{ route('my-testimonial') }}"
                                    class="text-indigo-600 font-bold hover:text-indigo-700 hover:underline text-sm">
                                    Quer ser o primeiro? &rarr;
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-indigo-600 font-bold hover:text-indigo-700 hover:underline text-sm">
                                    Entra e usa primeiro &rarr;
                                </a>
                            @endauth
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section id="pricing" class="py-24 bg-slate-900 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-20 pointer-events-none">
                <div
                    class="absolute top-[10%] left-[10%] w-[20rem] h-[20rem] bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl">
                </div>
                <div
                    class="absolute bottom-[10%] right-[10%] w-[20rem] h-[20rem] bg-purple-500 rounded-full mix-blend-screen filter blur-3xl">
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-sm font-bold text-indigo-400 tracking-widest uppercase">Planos</h2>
                    <p class="mt-3 text-3xl md:text-4xl font-extrabold tracking-tight text-white">
                        Começa de graça. Paga só se valer a pena.
                    </p>
                    <p class="mt-4 text-lg text-slate-400">
                        Sem cartão pra entrar. Sem pegadinha de trial de 7 dias.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-{{ $plans->count() }} gap-8 max-w-5xl mx-auto">
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
                        <div
                            class="rounded-3xl p-8 border flex flex-col transition-all relative {{ $isFree ? 'bg-slate-800 border-slate-700 hover:border-slate-600' : 'bg-indigo-900 border-indigo-500 hover:border-indigo-400 md:-translate-y-4 shadow-2xl shadow-indigo-500/20' }}">
                            @if($plan->recommended)
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                                    <span
                                        class="bg-indigo-500 text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg uppercase tracking-wider">Recomendado</span>
                                </div>
                            @endif

                            <div class="mb-6">
                                <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-extrabold text-white">R$
                                        {{ number_format($plan->price / 100, 2, ',', '.') }}</span>
                                    <span
                                        class="text-slate-400">/{{ $plan->interval === 'year' ? 'ano' : 'mês' }}</span>
                                </div>
                                <p class="mt-4 text-slate-300 text-sm">
                                    {{ $isFree ? 'Pra organizar suas contas fixas sem pagar nada.' : 'Pra quem leva a sério e quer tudo na palma da mão.' }}
                                </p>
                            </div>

                            <ul class="space-y-3 mb-8 flex-1">
                                @if(!$isFree)
                                    <li class="flex items-center gap-3">
                                        <i data-lucide="check"
                                            class="w-5 h-5 text-indigo-400 flex-shrink-0"></i>
                                        <span class="text-slate-200 text-sm">Tudo do Grátis +</span>
                                    </li>
                                @endif
                                @foreach($plan->benefits as $benefit)
                                    <li class="flex items-center gap-3">
                                        <i data-lucide="check"
                                            class="w-5 h-5 {{ $isFree ? 'text-green-500' : 'text-indigo-400' }} flex-shrink-0"></i>
                                        <span class="text-slate-200 text-sm">{{ $benefit->description }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            @auth
                                @if($userOnThisPlan)
                                    <button disabled
                                        class="w-full block text-center {{ $isFree ? 'bg-slate-700 text-slate-400' : 'bg-green-600 text-white' }} font-bold py-3 rounded-xl opacity-80 cursor-default">
                                        {{ $isFree ? 'Seu plano atual' : 'Premium ativo' }}
                                    </button>
                                @else
                                    <a href="{{ route('upgrade') }}"
                                        class="w-full block text-center {{ $isFree ? 'bg-slate-100 text-slate-900' : 'bg-white text-indigo-600' }} font-bold py-3 rounded-xl hover:scale-[1.02] transition-all">
                                        Fazer Upgrade
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="w-full block text-center {{ $isFree ? 'bg-slate-100 text-slate-900' : 'bg-white text-indigo-600' }} font-bold py-3 rounded-xl hover:scale-[1.02] transition-all shadow-lg">
                                    Começar {{ $plan->name }}
                                </a>
                            @endauth
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- CTA final -->
        <section class="py-24 bg-indigo-600 text-white relative overflow-hidden">
            <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
                <h2 class="text-3xl md:text-4xl font-bold mb-5 leading-tight">
                    Cansou de fingir que tá tudo certo no fim do mês?
                </h2>
                <p class="text-lg md:text-xl text-indigo-100 mb-10 max-w-2xl mx-auto">
                    Cadastra suas contas em 5 minutos. Sem cartão. Sem cobrar nada pra começar.
                </p>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold rounded-full text-indigo-600 bg-white hover:bg-slate-50 transition-all shadow-xl hover:-translate-y-0.5">
                        Acessar Agora
                        <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold rounded-full text-indigo-600 bg-white hover:bg-slate-50 transition-all shadow-xl hover:-translate-y-0.5">
                        Começar Grátis Agora
                        <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                    </a>
                @endauth
            </div>
        </section>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600/20 rounded-lg flex items-center justify-center text-indigo-400">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                    <span class="font-bold text-xl text-white">Minhas Finanças</span>
                </div>
                <div class="flex gap-6 text-sm">
                    <a href="{{ route('legal') }}#termos" class="hover:text-white transition-colors">Termos</a>
                    <a href="{{ route('legal') }}#privacidade" class="hover:text-white transition-colors">Privacidade
                        (LGPD)</a>
                    <a href="mailto:suporte@minhasfinancas.com" class="hover:text-white transition-colors">Suporte</a>
                </div>
                <div class="text-sm text-center md:text-right">
                    &copy; {{ date('Y') }} Minhas Finanças.
                    <span class="block md:inline text-slate-600 md:ml-2">Não somos instituição financeira.</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>
