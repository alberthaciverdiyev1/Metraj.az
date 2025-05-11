import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'
export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['./Resources/css/app.css', './Resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        manifest: false,
    }
})
