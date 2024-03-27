import './bootstrap';
import './register-sw';

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))


if (typeof window.Livewire !== 'undefined') {
    window.Echo.channel('Test-channel').listen('.Test-event', (e) => {
        console.log(e.message);
        window.Livewire.dispatch('refreshComponent');
    });
} else {
    console.error("Livewire is not defined.");
}
