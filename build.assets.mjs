/**
 * Front-end asset build script
 * ----------------------------------
 * Source:  resources/js  (all .js files, recursive)
 *          resources/css (all .css files, recursive)
 * Output:  public/js (minified, structure preserved)
 *          public/css (minified, structure preserved)
 *
 * This minifies the legacy hand-written front-end assets so Blade can
 * keep referencing them via asset('js/...') / asset('css/...').
 *
 * Vite-managed entries (resources/css/app.css, resources/js/app.js) and
 * Filament vendor assets (public/js/filament, public/css/filament) are
 * intentionally NOT processed here — they are excluded from the globs.
 *
 * Usage:
 *   node build.assets.mjs           # one-shot minified build
 *   node build.assets.mjs --watch   # rebuild on change
 */
import { context, build } from 'esbuild';
import { globSync } from 'node:fs';
import process from 'node:process';

const WATCH = process.argv.includes('--watch');

const isViteEntry = (f) => f.endsWith('/app.css') || f.endsWith('/app.js');

const targets = [
    {
        name: 'JS',
        entryPoints: globSync('resources/js/**/*.js').filter((f) => !isViteEntry(f)),
        outdir: 'public/js',
        outbase: 'resources/js',
        target: 'es2019',
    },
    {
        name: 'CSS',
        entryPoints: globSync('resources/css/**/*.css').filter((f) => !isViteEntry(f)),
        outdir: 'public/css',
        outbase: 'resources/css',
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
