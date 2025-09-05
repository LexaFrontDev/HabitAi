self.addEventListener('install', (event) => {
    console.log('Service Worker установлен');
    event.waitUntil(self.skipWaiting());
});

self.addEventListener('activate', (event) => {
    console.log('Service Worker активирован');
    event.waitUntil(self.clients.claim());
});

self.addEventListener('push', (event) => {
    const data = event.data ? event.data.json() : { title: 'Push', body: 'Новое уведомление' };
    event.waitUntil(
        self.registration.showNotification(data.title, { body: data.body })
    );
});
