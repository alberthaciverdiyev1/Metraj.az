import BunnyCDNStorage from 'node-bunny-storage'
import fs from 'fs/promises'
import { createWriteStream } from 'fs'
import path from 'path'
import { pipeline } from 'stream/promises'
import dotenv from 'dotenv';
dotenv.config();

const BUNNYCDN_STORAGE_ZONE = process.env.BUNNYCDN_STORAGE_ZONE
const BUNNYCDN_API_KEY = process.env.BUNNYCDN_API_KEY
const BUNNYCDN_PULL_ZONE = process.env.BUNNYCDN_PULL_ZONE
console.log({BUNNYCDN_STORAGE_ZONE, BUNNYCDN_API_KEY, BUNNYCDN_PULL_ZONE})

const bunny = new BunnyCDNStorage({
    accessKey: BUNNYCDN_API_KEY,
    storageZoneName: BUNNYCDN_STORAGE_ZONE,
    concurrency: 5,
    retryCount: 1,
    logLevel: 'silent',
})

export async function uploadToBunny(part, remotePath = '/general') {
    try {
        const ext = path.extname(part.filename)
        const tmpFilename = `${Date.now()}-${Math.random().toString(36).substring(2)}${ext}`
        const tmpFilePath = path.join('/tmp', tmpFilename)

        const writeStream = createWriteStream(tmpFilePath)
        await pipeline(part.file, writeStream)

        const remoteDir = path.dirname(remotePath)
        const fileName = path.basename(remotePath)

        await bunny.uploadFile({
            localFilePath: tmpFilePath,
            remoteDirectory: remoteDir,
        })

        await fs.unlink(tmpFilePath)

        return `https://${BUNNYCDN_PULL_ZONE}/${remotePath}`
    } catch (err) {
        console.error('📦 BunnyCDN upload error:', err)
        throw err
    }
}
