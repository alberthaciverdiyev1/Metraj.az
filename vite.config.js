import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['./Resources/css/app.css', './Resources/js/app.js'],
            refresh: true,
        }),
    ],
});
