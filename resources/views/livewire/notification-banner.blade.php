<div x-data="notificationBanner()" x-init="checkPermission()" x-show="show" x-cloak
    class="mb-6 animate-in slide-in-from-top duration-500">
    <div
        class="bg-[#e7fce3] border border-[#d1f4cb] rounded-2xl p-4 flex items-center justify-between shadow-sm relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-green-500/5 rounded-full blur-2xl"></div>

        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#25d366] shadow-sm">
                <i data-lucide="bell-off" class="w-6 h-6" x-show="!loading"></i>
                <div x-show="loading"
                    class="w-5 h-5 border-2 border-[#25d366]/30 border-t-[#25d366] rounded-full animate-spin"></div>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 text-sm md:text-base">As notificações estão desativadas</h3>
                <p class="text-xs md:text-sm text-gray-600">Receba alertas de vencimentos e avisos importantes. <button
                        @click="requestPermission()" class="text-[#075e54] font-bold hover:underline">Ativar</button>
                </p>
            </div>
        </div>

        <button @click="show = false" class="p-2 text-gray-400 hover:text-gray-600 relative z-10">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>
</div>

<script>
    function notificationBanner() {
        return {
            show: false,
            loading: false,

            checkPermission() {
                if (!('Notification' in window) || !('serviceWorker' in navigator)) {
                    this.show = false;
                    return;
                }

                if (Notification.permission === 'default') {
                    this.show = true;
                }
            },

            async requestPermission() {
                this.loading = true;
                try {
                    const permission = await Notification.requestPermission();
                    if (permission === 'granted') {
                        await this.subscribeUser();
                    } else {
                        this.show = false;
                    }
                } catch (error) {
                    console.error('Erro ao pedir permissão:', error);
                } finally {
                    this.loading = false;
                }
            },

            async subscribeUser() {
                try {
                    const registration = await navigator.serviceWorker.ready;
                    const subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: this.urlBase64ToUint8Array('{{ trim(config('webpush.vapid.public_key')) }}')
                    });

                    // Send to backend
                    const subJson = subscription.toJSON();
                    $wire.updateSubscription({
                        endpoint: subJson.endpoint,
                        publicKey: subJson.keys.p256dh,
                        authToken: subJson.keys.auth
                    });

                    this.show = false;
                } catch (error) {
                    console.error('Erro ao subscrever:', error);
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
</script>