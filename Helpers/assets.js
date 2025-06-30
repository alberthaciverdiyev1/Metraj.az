import fs from 'fs'
import dotenv from 'dotenv'
dotenv.config()

function assets(files, type = 'js') {
    if (process.env.NODE_ENV === 'development') return files.map(fileName => `/${type}/` + fileName);

    const manifest = JSON.parse(fs.readFileSync('./Dist/.vite/manifest.json', 'utf-8'));
    return files.map(fileName => {

        const key = Object.keys(manifest).find(manifestKey => {
            return manifestKey === `Public/${type}/` + fileName;
        });

        if (!key) {
            console.warn(`⚠️ Asset not found in manifest: ${fileName}`);
            return null;
        }

        const entry = manifest[key];

        console.log('/' + entry.file)
        return '/' + entry.file;
    }).filter(Boolean);
}

export function css(files) {
    return assets(files, 'css');
}

export function js(files) {
    return assets(files, 'js');
}
