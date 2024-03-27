console.log('Webpush subscription started ...');

// Initiates the process of subscribing to push notifications
pushSubscriptionStart();

/**
 * Checks for service worker support and initiates registration and push subscription.
 */
function pushSubscriptionStart() {
    // Check if service workers are supported
    if (!('serviceWorker' in navigator)) {
        console.log('Service workers are not supported by this browser.');
        return;
    }
  
    // Register the service worker upon window load
    window.addEventListener("load", () => {
        navigator.serviceWorker.getRegistration().then(registration => {
            if (registration) {
                console.log('Service Worker already registered.');
                initPush();
            } else {
                // Register the service worker
                navigator.serviceWorker.register("sw.js").then(registration => {
                        console.log("ServiceWorker registration successful with scope: ", registration.scope);
                        initPush();
                    }).catch(err => {
                        console.log("ServiceWorker registration failed: ", err);
                    });
            }
        });
    });
}

/**
 * Initializes push notifications by requesting user permission.
 */
function initPush() {
    if (!navigator.serviceWorker.ready) {
        return;
    }

    // Request notification permission
    Notification.requestPermission().then(permissionResult => {
        if (permissionResult !== 'granted') {
            throw new Error('We weren\'t granted permission.');
        }
        subscribeUser();
    }).catch(error => {
        console.error("Permission request failed", error);
    });
}

/**
 * Subscribes the user to push notifications.
 */
function subscribeUser() {
    navigator.serviceWorker.ready.then(registration => {
        const subscribeOptions = {
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(
                'BCKyDcIKu8-4in4IJ6A9-MP4RICqHiYUYOXWsi6dhwQ-TUh1XWb6NkyfPa3otzW2Vfy_I-Q-Hr4hY7-adogSDkI'
            )
        };

        return registration.pushManager.subscribe(subscribeOptions);
    }).then(pushSubscription => {
        console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
        storePushSubscription(pushSubscription);
    });
}

/**
 * Converts a URL-safe base64 string to a Uint8Array.
 * @param {string} base64String The base64 string to convert.
 * @returns {Uint8Array} The resulting Uint8Array.
 */
function urlBase64ToUint8Array(base64String) {
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

/**
 * Stores the push subscription on the server.
 * @param {PushSubscription} pushSubscription The push subscription to store.
 */
function storePushSubscription(pushSubscription) {
    const token = document.querySelector('meta[name=csrf-token]').getAttribute('content');

    fetch('/push_store', {
        method: 'POST',
        body: JSON.stringify(pushSubscription),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-Token': token
        }
    }).then(response => response.json())
    .then(response => {
        console.log('Response from storePushSubscription', response);
    }).catch(err => {
        console.error('Error from storePushSubscription', err);
    });
}
