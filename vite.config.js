import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    build: {
        outDir: __dirname + '/public/build',
        emptyOutDir: true,
        // manifest: true,
        minify: 'esbuild',
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build',
            input: [__dirname + '/Modules/WebUI/Resources/assets/app.js'],
            refresh: true,
        }),
        tailwindcss(),

    ],
});
