const CACHE_NAME = 'dhakaiya-shell-v1';

self.addEventListener('install', () => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
    ).then(() => self.clients.claim())
  );
});

// Network-first for live Laravel pages & CSRF/session integrity; never cache POST requests
self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;
  const url = new URL(event.request.url);
  if (url.origin !== self.location.origin) return;

  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request).catch(() =>
        new Response(
          '<!doctype html><html><body style="font-family:sans-serif;text-align:center;padding:48px;background:#0b1428;color:#fff"><h2>ইন্টারনেট সংযোগ নেই</h2><p>অনুগ্রহ করে ইন্টারনেট সংযোগ চালু করে পুনরায় চেষ্টা করুন।</p><button onclick="location.reload()" style="padding:10px 20px;border-radius:8px;border:0;background:#10b981;color:#fff;font-weight:600">পুনরায় চেষ্টা করুন</button></body></html>',
          { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
        )
      )
    );
  }
});
