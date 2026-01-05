self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const data = event.data?.json() ?? {};
    const title = data.title || 'Notificação do Finanças';
    const body = data.body || 'Você tem uma nova mensagem.';
    const icon = data.icon || '/images/icon-192.png';
    const tag = data.tag || 'generic-notification';

    event.waitUntil(
        self.registration.showNotification(title, {
            body: body,
            icon: icon,
            badge: icon,
            tag: tag,
            data: data.url ? { url: data.url } : null
        })
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    if (event.notification.data && event.notification.data.url) {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    }
});
