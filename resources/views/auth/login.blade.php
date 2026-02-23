<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Minhas Finanças</title>
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
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        function handleGoogleLogin(response) {
            fetch("{{ route('google.js.login') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    credential: response.credential
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "{{ route('dashboard') }}";
                    } else {
                        alert(data.message || 'Erro ao fazer login com o Google.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Erro na conexão com o servidor.');
                });
        }
    </script>
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
                    class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl mb-4 shadow-xl shadow-blue-500/40">
                    <i data-lucide="wallet" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-3xl font-black text-white mb-2 tracking-tight uppercase italic">Minhas<span
                        class="text-blue-400">Finanças</span></h1>
                <p class="text-slate-400">Gerencie seu dinheiro de forma inteligente e rápida.</p>
            </div>

            <div class="space-y-4">
                <div id="g_id_onload" data-client_id="{{ config('services.google.client_id') }}" data-context="signin"
                    data-ux_mode="popup" data-callback="handleGoogleLogin" data-auto_prompt="false">
                </div>

                <div class="g_id_signin w-full flex justify-center" data-type="standard" data-shape="rectangular"
                    data-theme="outline" data-text="signin_with" data-size="large" data-logo_alignment="left"
                    data-width="320">
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-800">
                <p class="text-slate-500 text-[11px] leading-relaxed">
                    Protegemos seus dados conforme a LGPD brasileira. <br>
                    Ao entrar, você concorda com nossos <br>
                    <a href="{{ route('legal') }}#termos"
                        class="text-blue-400 font-bold hover:text-blue-300 transition-colors">Termos de Uso</a>
                    e
                    <a href="{{ route('legal') }}#privacidade"
                        class="text-blue-400 font-bold hover:text-blue-300 transition-colors">Política de
                        Privacidade</a>.
                </p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>