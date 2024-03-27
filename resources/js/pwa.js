console.log("PWA Worker Loaded...");

/**
 * Checks if the application is already installed.
 * Hides the install prompt if the app is installed.
 */
function checkAppInstalled() {
    if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
        hideInstallPrompt();
    }
}
checkAppInstalled(); // Initial check

// Holds the event to allow for a deferred prompt
let deferredPrompt;

/**
 * Prepares the app for installation.
 * Prevents the default install prompt and shows a custom install prompt.
 */
window.addEventListener('beforeinstallprompt', (e) => {
    console.log('beforeinstallprompt event fired');
    e.preventDefault(); // Prevent the default install prompt
    deferredPrompt = e; // Save the event for later use
    showInstallPrompt(); // Show our custom install UI
});

/**
 * Shows the custom install prompt modal if it has not been shown before.
 */
function showInstallPrompt() {
    // Check if the modal has already been shown
    if (!localStorage.getItem('pwaInstallPrompt')) {
        let installModal = document.getElementById('installModal');
        if (installModal) {
            installModal.style.display = 'block';
        }
    }
}

/**
 * Hides the custom install prompt modal and records that the prompt has been shown.
 */
function hideInstallPrompt() {
    let installModal = document.getElementById('installModal');
    if (installModal) {
        installModal.style.display = 'none';
        localStorage.setItem('pwaInstallPrompt', '1'); // Mark that the install prompt has been shown
    }
}

/**
 * Sets up event listeners after the DOM content is loaded.
 */
document.addEventListener('DOMContentLoaded', () => {
    setupInstallButton();
    setupCloseButton();
});

/**
 * Sets up the install button to trigger the install prompt.
 */
function setupInstallButton() {
    let installButton = document.getElementById('installBtn');
    if (installButton) {
        installButton.addEventListener('click', async () => {
            if (deferredPrompt) {
                console.log('Install button clicked');
                deferredPrompt.prompt(); // Show the install prompt
                const { outcome } = await deferredPrompt.userChoice; // Wait for the user to respond to the install prompt
                console.log(`User response to the install prompt: ${outcome}`);
                if (outcome === 'accepted') {
                    console.log('User accepted the A2HS prompt');
                } else {
                    console.log('User dismissed the A2HS prompt');
                }
                deferredPrompt = null; // Clear the deferredPrompt after it's used
                hideInstallPrompt(); // Hide the install prompt modal
            }
        });
    }
}

/**
 * Sets up the close button to hide the install modal.
 */
function setupCloseButton() {
    let closeButton = document.querySelector('.close');
    if (closeButton) {
        closeButton.addEventListener('click', hideInstallPrompt);
    }
}

/**
 * Allows for the manual triggering of the install prompt.
 */
window.installPWA = function() {
    console.log('installPWA called');
    localStorage.removeItem('pwaInstallPrompt'); // Reset the prompt shown flag
    showInstallPrompt(); // Show the install prompt again
};
