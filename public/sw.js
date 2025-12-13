// Minimal Service Worker for PWA installation criteria
self.addEventListener('install', (event) => {
    console.log('Service Worker Install');
    self.skipWaiting();
});

self.addEventListener('fetch', (event) => {
    // Just a pass-through for now, can add caching later
});
