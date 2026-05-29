<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Em Manutenção - Financas App</title>
    @include('partials.assets')
</head>

<body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen flex items-center justify-center px-6">
    <div class="max-w-md w-full">
        <!-- Logo/Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-blue-100 rounded-full mb-4">
                <i data-lucide="wrench" class="w-12 h-12 text-blue-600"></i>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Estamos em Manutenção</h1>

            <div class="bg-blue-50 rounded-2xl p-4 mb-6">
                <p class="text-blue-900 font-medium">
                    {{ $message }}
                </p>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex items-center justify-center gap-3 text-gray-600">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                    <p class="text-sm">Voltaremos em breve</p>
                </div>
                <div class="flex items-center justify-center gap-3 text-gray-600">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                    <p class="text-sm">Seus dados estão seguros</p>
                </div>
            </div>

            <!-- Loading Animation -->
            <div class="flex justify-center gap-2 mb-6">
                <div class="w-3 h-3 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-3 h-3 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-3 h-3 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>

            <p class="text-sm text-gray-500">
                Estamos melhorando sua experiência 🚀
            </p>
        </div>

        <!-- Admin Notice -->
        @if(auth()->check() && auth()->user()->isAdmin())
            <div class="mt-4 bg-yellow-100 border-2 border-yellow-400 rounded-xl p-4 text-center">
                <p class="text-yellow-900 font-semibold text-sm mb-2">
                    ⚠️ Você está vendo esta página mas é ADMIN
                </p>
                <p class="text-yellow-800 text-xs">
                    Usuários comuns não conseguem acessar o app neste momento.
                </p>
                <a href="/admin/settings"
                    class="inline-block mt-3 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg text-sm transition-colors">
                    Ir para Configurações
                </a>
            </div>
        @endif
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>