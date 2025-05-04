// import {defineConfig} from 'vite';
// import laravel from 'laravel-vite-plugin';
// import tailwindcss from '@tailwindcss/vite';
// import path from "path";
// import react from '@vitejs/plugin-react';
//
// export default defineConfig({
//     build: {
//         outDir: path.resolve(__dirname, '../../public/build'),
//         emptyOutDir: true,
//         // manifest: true,
//         minify: 'esbuild',
//     },
//     plugins: [
//         laravel({
//             publicDirectory: '../../public',
//             buildDirectory: 'build',
//             input: [__dirname + '/Modules/WebUI/Resources/react/src/main.jsx'],
//             refresh: true,
//         }),
//         tailwindcss(),
//         react()
//     ],
//     resolve: {
//         alias: {
//             '@': path.resolve(__dirname, 'src'),
//         },
//     },
// });
