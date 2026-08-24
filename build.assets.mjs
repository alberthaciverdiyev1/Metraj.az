/**
 * Front-end asset build script
 * ----------------------------------
 * Source:  resources/frontend/js  (all .js files, recursive)
 *          resources/frontend/css (all .css files, recursive)
 * Output:  public/js (minified, structure preserved)
 *          public/css (minified, structure preserved)
 *
 * This minifies the legacy hand-written front-end assets so Blade can
 * keep referencing them via asset('js/...') / asset('css/...').
 *
 * Vite-managed entries (resources/css/app.css, resources/js/app.js) and
 * Filament vendor assets (public/js/filament, public/css/filament) are
 * intentionally NOT processed here.
 *
 * Usage:
 *   node build.assets.mjs           # one-shot minified build
 *   node build.assets.mjs --watch   # rebuild on change
 */
import { context, build } from 'esbuild';
import { globSync } from 'node:fs';
import process from 'node:process';

const WATCH = process.argv.includes('--watch');

const targets = [
    {
        name: 'JS',
        entryPoints: globSync('resources/frontend/js/**/*.js'),
        outdir: 'public/js',
        outbase: 'resources/frontend/js',
        target: 'es2019',
    },
    {
        name: 'CSS',
        entryPoints: globSync('resources/frontend/css/**/*.css'),
        outdir: 'public/css',
        outbase: 'resources/frontend/css',
    },
];

const common = {
    bundle: false,        // each file is a standalone script (no import resolution)
    minify: true,
    sourcemap: false,
    logLevel: 'info',
};

async function run() {
    for (const t of targets) {
        const { name, ...opts } = t;
        console.log(`\n[build.assets] ${name}: ${opts.entryPoints.length} file(s) -> ${opts.outdir}`);
        if (WATCH) {
            const ctx = await context({ ...common, ...opts });
            await ctx.watch();
            console.log(`  watching ${name} for changes...`);
        } else {
            await build({ ...common, ...opts });
        }
    }
    if (WATCH) {
        console.log('\n[build.assets] watch mode active (Ctrl+C to stop)');
    } else {
        console.log('\n[build.assets] done.');
    }
}

run().catch((err) => {
    console.error('[build.assets] FAILED:', err);
    process.exit(1);
});
