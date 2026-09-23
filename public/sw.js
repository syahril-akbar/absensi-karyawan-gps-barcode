const CACHE_NAME = 'absensi-pwa-v2';
const PRECACHE_URLS = [
  '/manifest.json',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
  '/icons/icon-maskable-512x512.png',
  '/icons/apple-touch-icon.png',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_URLS)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const { request } = event;

  if (request.method !== 'GET') return;

  const url = new URL(request.url);

  // Only handle same-origin requests
  if (url.origin !== self.location.origin) return;

  // Navigation: network-first, never cache auth pages (avoids stale CSRF token)
  if (request.mode === 'navigate') {
    if (
      url.pathname === '/login' ||
      url.pathname === '/register' ||
      url.pathname.startsWith('/password') ||
      url.pathname.startsWith('/reset-password')
    ) {
      return; // Always network-only for auth
    }

    event.respondWith(
      fetch(request)
        .then((response) => {
          if (response.ok && response.status === 200) {
            const copy = response.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
          }
          return response;
        })
        .catch(() =>
          caches.match(request).then((cached) => cached || new Response('Offline', { status: 503, statusText: 'Offline' }))
        )
    );
    return;
  }

  // Static assets (Vite build + manual assets): cache-first
  if (url.pathname.startsWith('/build/') || url.pathname.startsWith('/assets/')) {
    event.respondWith(
      caches.match(request).then((cached) => {
        if (cached) return cached;
        return fetch(request).then((response) => {
          if (response.ok) {
            const copy = response.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
          }
          return response;
        });
      })
    );
    return;
  }

  // Everything else (dynamic pages, Livewire, API): network only
  return;
});
