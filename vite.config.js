import { defineConfig } from 'vite'
import path from 'path'
import fs from 'fs'
import crypto from 'crypto'

function hashName(filePath) {
    const content = fs.readFileSync(filePath)
    return crypto.createHash('md5').update(content).digest('hex').slice(0, 8)
}

function getAnonymousEntries(baseDir, outputFolder, extensions) {
    const entries = {}
    const seenHashes = new Set()

    function recurse(currentDir, relPrefix = '') {
        const files = fs.readdirSync(currentDir)

        for (const file of files) {
            const fullPath = path.join(currentDir, file)
            const stat = fs.statSync(fullPath)

            if (stat.isDirectory()) {
                recurse(fullPath, path.join(relPrefix, file))
            } else {
                const ext = path.extname(file)
                if (extensions.includes(ext)) {
                    const hash = hashName(fullPath)
                    let name = path.join(outputFolder, relPrefix, hash).replace(/\\/g, '/')

                    while (seenHashes.has(name)) {
                        name += Math.floor(Math.random() * 100)
                    }

                    seenHashes.add(name)
                    entries[name] = fullPath
                }
            }
        }
    }

    recurse(baseDir)
    return entries
}

export default defineConfig({
    build: {
        outDir: 'Dist',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: {
                ...getAnonymousEntries(path.resolve(__dirname, 'Public/js'), 'js', ['.js']),
                ...getAnonymousEntries(path.resolve(__dirname, 'Public/css'), 'css', ['.css'])
            },
            output: {
                entryFileNames: asset => {
                    if (asset.name.startsWith('js/')) {
                        return '[name].[hash].js'
                    } else if (asset.name.startsWith('css/')) {
                        return '[name].[hash].js'
                    }
                    return '[name].[hash].js'
                },
                assetFileNames: asset => {
                    if (asset.name.endsWith('.css')) {
                        return '[name].[hash][extname]'
                    }
                    return '[name].[hash][extname]'
                }
            }
        }
    }
})
