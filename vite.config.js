import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/reading-test.js', 'resources/css/reading-test.css', 'resources/css/test-notepad.css', 'resources/js/app.js'],

            refresh: true,
        }),
        tailwindcss(),
    ],
});
