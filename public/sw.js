// Logs the load event of the service worker.
console.log("Service Worker Loaded...");

/*

Service Worker Review

Code Organization and Clarity
1. Documentation: The code is well-documented, with clear comments explaining the purpose of each function and event listener. This is crucial for maintainability and understanding the flow of the service worker.
2. Function Separation: Functions are neatly separated based on functionality (e.g., handling push events, notification clicks, caching). This separation enhances readability and makes the codebase easier to manage.

Push Notification Handling
1. Push Event Listener: The push event listener is implemented correctly, checking for notification permission and processing the push event data to show a notification. Including the data object in the notification options is a good practice, as it allows for more interactive notifications.
2. Notification Click Handling: The notification click event listener is set up to direct the user to a specific URL based on the notification's data. This improves user engagement by providing a seamless transition from notification to application content.

Caching Strategy
1. Preloading Assets: The preloadAssets function demonstrates an effective strategy for caching both static and dynamic assets. Fetching the manifest.json and dynamically caching the build files along with static assets are best practices for a Progressive Web App (PWA).
2. Fetch Event Handling: The fetch event handler uses a cache-first strategy, falling back to network requests if the cache is not available. This approach is efficient for static assets but consider implementing a network-first strategy for dynamic content to ensure users receive the most up-to-date content.
3. Cache Management: The activate event handler's cleanup of old caches is crucial for preventing storage bloat and ensuring that users are served the latest version of your app.

Potential Areas for Enhancement
1. Error Handling: While there is basic error logging in preloadAssets, consider adding more robust error handling throughout the service worker. For instance, handling network failures in fetchAndCache by providing a fallback to a generic offline page if both cache and network fail.
2. Cache Versioning: You're using a single static cache name ('v1'). In a production environment, consider implementing a dynamic cache naming strategy based on your app version. This would automate the process of cache updates and ensure that old caches are cleaned up only when necessary.
3. Notification Data Validation: Before showing a notification, validate the existence and correctness of the data received from the push event. This prevents errors in cases where the push message structure might not meet your expectations.

Performance Considerations
1. Efficiency of Asset Caching: Ensure that the list of assets to cache is curated to include only necessary items. Over-caching can lead to unnecessary use of storage space and potential slow-downs when updating cached assets.
2. Strategic Cache Updates: Regularly review and update your caching strategy to balance between caching for performance and hitting the network for fresh content. This balance is crucial for PWAs that rely on timely content updates.

Conclusion
Overall, your service worker implementation follows many of the best practices for PWAs, including efficient caching, push notification handling, and user re-engagement through notification clicks. By addressing the areas for enhancement mentioned above, you can further improve the robustness, user experience, and maintainability of your service worker.

*/

/**
 * Listens for push events from the server.
 * Displays a notification if the push event includes data.
 */
self.addEventListener("push", function (e) {
    console.log("Push event received.");
    // Checks if notifications are supported and permission is granted.
    if (!(self.Notification && self.Notification.permission === "granted")) {
        console.log(
            "Notifications aren't supported or permission not granted."
        );
        return;
    }

    // Processes the push event data to show a notification.
    if (e.data) {
        var msg = e.data.json();
        console.log("Push data received:", msg);

        // Ensure the data object is passed to the notification options
        e.waitUntil(
            self.registration.showNotification(msg.title, {
                body: msg.body,
                icon: msg.icon,
                actions: msg.actions,
                data: msg.data, // Include the data object here
            })
        );
    }
});

/**
 * Handles notification click events.
 * Opens a URL based on the notification's action or data.
 */
self.addEventListener("notificationclick", function (event) {
    // Assuming the action URL is stored in the notification's data
    const notificationData = event.notification.data;
    const actionUrl = notificationData
        ? notificationData.url
        : "https://wsgbrand.in/"; // Default URL if no data

    event.notification.close(); // Closes the notification

    // Opens the action URL in a new window/tab or focuses if already opened.
    event.waitUntil(
        clients.matchAll({ type: "window" }).then((windowClients) => {
            for (let client of windowClients) {
                // Check if there is already a window/tab open with the target URL
                if (client.url === actionUrl && "focus" in client) {
                    return client.focus();
                }
            }
            // If no window/tab is open with the URL, open a new one
            if (clients.openWindow) {
                return clients.openWindow(actionUrl);
            }
        })
    );
});



const CACHE_NAME = 'v1';

// List of static assets to cache
const staticAssets = [
  '/',
  '/offline.html',
  '/index.php',
  '/favicon.ico',
  // '/robots.txt',
  // '/sitemap.xml',
  '/logo.png',
  '/manifest.json', // The Web App Manifest
  '/storage/assets/placeholders/default.jpg',
  '/storage/assets/notification.mp3',
  '/storage/assets/welcome.mp4',
  '/storage/assets/welcome.jpg',
];

// List of routes that should be network-only
const networkOnlyRoutes = [
  '/api/',
  '/login',
  '/panel/',
];

/**
 * Preloads static and dynamic assets into the cache.
 * @returns {Promise<void>} A promise that resolves when caching is complete.
//  */
// async function preloadAssets() {
//   const cache = await caches.open(CACHE_NAME);
//   const manifestResponse = await fetch('/build/manifest.json').catch(console.error);
//   const assets = await manifestResponse.json();
//   const buildFiles = Object.values(assets).map(asset => `/build/${asset.file}`);
//   const allFilesToCache = [...staticAssets, ...buildFiles];
//   return cache.addAll(allFilesToCache);
// }

// Event listener for installing the service worker
// self.addEventListener('install', event => {
//   console.log('Service worker installing...');
//   event.waitUntil(preloadAssets());
// });

// Fetch event handler to manage caching strategies
// self.addEventListener('fetch', event => {
//   event.respondWith(fetchAndCache(event.request));
// });

/**
 * Handles fetch requests by trying to return a cached response,
 * and falling back to network requests if the cache is not available.
 * @param {Request} request The request to handle.
 * @returns {Promise<Response>} The response from either the cache or the network.
 */
// async function fetchAndCache(request) {
//   const cache = await caches.open(CACHE_NAME);
//   const cachedResponse = await cache.match(request);
//   if (cachedResponse) {
//     return cachedResponse; // Return the cached response if available
//   }

//   // Fallback to network request if not in cache
//   const networkResponse = await fetch(request);
//   if (request.method === 'GET') {
//     // Cache the fetched response for future requests
//     cache.put(request, networkResponse.clone());
//   }
//   return networkResponse;
// }


// self.addEventListener('fetch', event => {
//   const url = new URL(event.request.url);

//   // Check if the request is for a route that should always be network-first
//   const isNetworkOnlyRoute = networkOnlyRoutes.some(route => url.pathname.includes(route));
//   if (isNetworkOnlyRoute) {
//     event.respondWith(networkFirstStrategy(event.request));
//     return;
//   }

//   // Check if the request is for an asset that should be cache-first
//   const isAssetToCache = staticAssets.some(asset => event.request.url.includes(asset));
//   if (isAssetToCache) {
//     event.respondWith(cacheFirstStrategy(event.request));
//     return;
//   }

//   // Default to network first for all other requests
//   event.respondWith(networkFirstStrategy(event.request));
// });

// // Network First Strategy
// async function networkFirstStrategy(request) {
//   try {
//     // Try to get the response from the network
//     return await fetch(request);
//   } catch (error) {
//     // If network request failed, try to get it from the cache
//     const cache = await caches.open('dynamic-content');
//     const cachedResponse = await cache.match(request);
//     return cachedResponse || new Response('Network request failed and no content is cached', { status: 504 });
//   }
// }

// // Cache First Strategy
// async function cacheFirstStrategy(request) {
//   const cache = await caches.open('static-assets');
//   const cachedResponse = await cache.match(request);
//   if (cachedResponse) {
//     // Return cached response if available
//     return cachedResponse;
//   }
//   // Otherwise, fetch from network, cache the response, and return it
//   const response = await fetch(request);
//   cache.put(request, response.clone());
//   return response;
// }



// Activate event handler for cleaning up old caches
// self.addEventListener('activate', event => {
//   event.waitUntil(cleanupOldCaches());
// });

// /**
//  * Deletes any caches that are not the current cache to free up space.
//  * @returns {Promise<void>} A promise that resolves when old caches have been deleted.
//  */
// async function cleanupOldCaches() {
//   const cacheNames = await caches.keys();
//   const oldCaches = cacheNames.filter(cacheName => cacheName !== CACHE_NAME);
//   return Promise.all(oldCaches.map(cacheName => caches.delete(cacheName)));
// }


