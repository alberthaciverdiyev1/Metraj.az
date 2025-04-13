import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: '../../public/build-webui',
        emptyOutDir: true,
        manifest: true,
        minify: 'esbuild',
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build-webui',
            input: [
                __dirname + '/Resources/assets/app.js'
            ],
            refresh: true,
        }),
    ],
});
