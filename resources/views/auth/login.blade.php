<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Finanças App</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top right, #3b82f6, transparent),
                radial-gradient(circle at bottom left, #1d4ed8, transparent),
                #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .google-btn {
            background: #ffffff;
            color: #1f2937;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .google-btn:hover {
            background: #f9fafb;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .google-btn:active {
            transform: translateY(0);
        }

        .animated-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .blob {
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(59, 130, 246, 0.2);
            filter: blur(50px);
            border-radius: 50%;
            animation: move 20s infinite alternate;
        }

        @keyframes move {
            from {
                transform: translate(-10%, -10%);
            }

            to {
                transform: translate(10%, 10%);
            }
        }
    </style>
</head>

<body>
    <x-global-banner />
    <div class="animated-bg">
        <div class="blob" style="top: 10%; left: 10%;"></div>
        <div class="blob" style="bottom: 10%; right: 10%; background: rgba(30, 64, 175, 0.2);"></div>
    </div>

    <div class="w-full max-w-md p-8">
        <div class="glass-card rounded-3xl p-10 text-center">
            <div class="mb-8">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4 shadow-lg shadow-blue-500/50">
                    <i data-lucide="wallet" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Bem-vindo ao Finanças</h1>
                <p class="text-slate-400">Gerencie seu dinheiro de forma inteligente e rápida.</p>
            </div>

            <div class="space-y-4">
                <a href="{{ route('auth.google') }}"
                    class="google-btn w-full flex items-center justify-center gap-3 py-4 rounded-2xl font-semibold text-lg">
                    <svg viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4" />
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853" />
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05" />
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 12-4.53z"
                            fill="#EA4335" />
                    </svg>
                    Entrar com o Google
                </a>
            </div>

            <div class="mt-8 pt-8 border-t border-slate-800">
                <p class="text-slate-500 text-sm">
                    Ao entrar, você concorda com nossos <br>
                    <a href="#" class="text-blue-400 hover:text-blue-300 transition-colors">Termos de Uso</a> e <a
                        href="#" class="text-blue-400 hover:text-blue-300 transition-colors">Privacidade</a>.
                </p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>