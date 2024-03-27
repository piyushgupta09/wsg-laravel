if ("serviceWorker" in navigator) {
    window.addEventListener("load", function () {
        // Check for a controlling service worker
        if (navigator.serviceWorker.controller) {
            console.log(
                "An active service worker was found, no need to register"
            );
        } else {
            // No service worker is controlling the page, proceed with registration
            navigator.serviceWorker.register("sw.js").then(
                (registration) => {
                    console.log(
                        "ServiceWorker registration successful with scope: ",
                        registration.scope
                    );
                },
                (err) => {
                    console.log("ServiceWorker registration failed: ", err);
                }
            );
        }
    });
}
