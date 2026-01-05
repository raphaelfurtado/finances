<div class="p-6 pb-24 lg:pb-6" x-data="notificationManager($wire)">
    <div x-init="checkSubscription()"></div>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Configurações de Notificações</h1>
        <p class="text-gray-500">Gerencie como e quando você recebe alertas do sistema.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center gap-3 text-green-700">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span class="text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 text-red-700">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Push Subscription Stats -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 font-bold">
                        <i data-lucide="bell" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Notificações Push</h3>
                        <p class="text-xs text-gray-500">{{ $subscriptionCount }} dispositivo(s) cadastrado(s)</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex h-3 w-3 relative">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $subscriptionCount > 0 ? 'bg-green-400 opacity-75' : 'bg-gray-400 opacity-75' }}"></span>
                        <span
                            class="relative inline-flex rounded-full h-3 w-3 {{ $subscriptionCount > 0 ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                    </span>
                    <span class="text-xs font-bold {{ $subscriptionCount > 0 ? 'text-green-600' : 'text-gray-600' }}">
                        {{ $subscriptionCount > 0 ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
            </div>

            <div class="mb-6 p-4 bg-amber-50 border border-amber-100 rounded-2xl flex items-center justify-between"
                x-show="!isSubscribed && permissionState !== 'denied'">
                <div class="flex items-center gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-amber-600"></i>
                    <p class="text-xs text-amber-800 font-medium">Este navegador ainda não está sincronizado.</p>
                </div>
                <button @click="subscribeUser()" :disabled="loading"
                    class="px-4 py-2 bg-amber-600 text-white text-xs font-bold rounded-xl hover:bg-amber-700 transition-all disabled:opacity-50">
                    <span x-show="!loading">Sincronizar Agora</span>
                    <span x-show="loading">Sincronizando...</span>
                </button>
            </div>

            <!-- Blocked Banner -->
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl" x-show="permissionState === 'denied'">
                <div class="flex items-center gap-3 mb-2">
                    <i data-lucide="shield-off" class="w-5 h-5 text-red-600"></i>
                    <p class="text-sm text-red-800 font-bold">Notificações Bloqueadas</p>
                </div>
                <p class="text-xs text-red-700 leading-relaxed">
                    Você bloqueou as notificações para este site. Para ativá-las, clique no <b>ícone de cadeado</b> ao
                    lado da URL na barra de endereços do seu navegador e altere a permissão para <b>"Permitir"</b>.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button wire:click="sendTestNotification" wire:loading.attr="disabled"
                    class="flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-sm active:scale-95 disabled:opacity-50">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Enviar Teste</span>
                </button>

                <button wire:click="clearSubscriptions" wire:loading.attr="disabled"
                    class="flex items-center justify-center gap-2 px-6 py-3 bg-white text-red-600 border border-red-100 rounded-2xl font-bold hover:bg-red-50 transition-all active:scale-95 disabled:opacity-50">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Remover Todos</span>
                </button>
            </div>
        </div>

        <!-- Future Settings Placeholder -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 opacity-60">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400">
                    <i data-lucide="mail" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 italic">Notificações por E-mail</h3>
                    <p class="text-xs text-gray-500 italic">Em breve</p>
                </div>
            </div>
            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gray-200 w-1/3"></div>
            </div>
        </div>
    </div>

    <!-- Help Banner -->
    <div class="mt-8 bg-blue-50/50 rounded-3xl p-6 border border-blue-100 flex gap-4">
        <i data-lucide="info" class="w-6 h-6 text-blue-500 shrink-0"></i>
        <div class="text-sm text-blue-700 leading-relaxed">
            As notificações push são enviadas diretamente para o seu navegador. Se o teste não chegar, verifique se você
            não desativou as notificações nas configurações do seu navegador ou do Windows/macOS.
        </div>
    </div>
</div>

@script
<script>
    window.notificationManager = function (wire) {
        return {
            isSubscribed: false,
            loading: false,
            permissionState: Notification.permission,

            async checkSubscription() {
                this.permissionState = Notification.permission;
                if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                    return;
                }

                try {
                    const registration = await navigator.serviceWorker.ready;
                    const subscription = await registration.pushManager.getSubscription();
                    this.isSubscribed = !!subscription;
                } catch (e) {
                    console.error('Erro ao checar subscrição:', e);
                }
            },

            async subscribeUser() {
                this.loading = true;
                try {
                    const permission = await Notification.requestPermission();
                    this.permissionState = permission;
                    if (permission !== 'granted') {
                        return;
                    }

                    const registration = await navigator.serviceWorker.ready;

                    // First try to get existing
                    let subscription = await registration.pushManager.getSubscription();

                    if (!subscription) {
                        subscription = await registration.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: this.urlBase64ToUint8Array('{{ trim(config('webpush.vapid.public_key')) }}')
                        });
                    }

                    const subJson = subscription.toJSON();
                    await wire.updateSubscription({
                        endpoint: subJson.endpoint,
                        publicKey: subJson.keys.p256dh,
                        authToken: subJson.keys.auth
                    });

                    this.isSubscribed = true;
                    lucide.createIcons();
                } catch (error) {
                    console.error('Erro ao subscrever:', error);
                    alert('Erro ao ativar: ' + error.message);
                } finally {
                    this.loading = false;
                }
            },

            urlBase64ToUint8Array(base64String) {
                const padding = '='.repeat((4 - base64String.length % 4) % 4);
                const base64 = (base64String + padding)
                    .replace(/\-/g, '+')
                    .replace(/_/g, '/');

                const rawData = window.atob(base64);
                const outputArray = new Uint8Array(rawData.length);

                for (let i = 0; i < rawData.length; ++i) {
                    outputArray[i] = rawData.charCodeAt(i);
                }
                return outputArray;
            }
        }
    }

    lucide.createIcons();
</script>
@endscript