import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/pwa.js',
                'resources/js/webpush.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        // host: '127.0.0.1'
        // host: '192.168.1.5'
        // host: '192.168.1.13'
        // host: '192.168.1.183'
        // host: '192.168.1.184'
    }
});
