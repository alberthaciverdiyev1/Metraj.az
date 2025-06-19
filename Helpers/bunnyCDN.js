import BunnyCDNStorage from 'node-bunny-storage'
import fs from 'fs/promises'
import { createWriteStream } from 'fs'
import path from 'path'
import { pipeline } from 'stream/promises'
import dotenv from 'dotenv'
dotenv.config()

const BUNNYCDN_STORAGE_ZONE = process.env.BUNNYCDN_STORAGE_ZONE
const BUNNYCDN_API_KEY = process.env.BUNNYCDN_API_KEY
const BUNNYCDN_PULL_ZONE = process.env.BUNNYCDN_PULL_ZONE

const bunny = new BunnyCDNStorage({
    accessKey: BUNNYCDN_API_KEY,
    storageZoneName: BUNNYCDN_STORAGE_ZONE,
    concurrency: 5,
    retryCount: 1,
    logLevel: 'silent',
})

export async function uploadToBunny(part, remoteDir = 'general') {
    try {
        const ext = path.extname(part.filename)
        const randomName = `${Date.now()}-${Math.random().toString(36).substring(2)}${ext}`
        const remotePath = `${remoteDir}/${randomName}`
        const tmpFilePath = path.join('/tmp', randomName)

        const writeStream = createWriteStream(tmpFilePath)
        await pipeline(part.file, writeStream)

        await bunny.uploadFile({
            localFilePath: tmpFilePath,
            remoteDirectory: remoteDir,
            remoteFileName: randomName,
        })

        await fs.unlink(tmpFilePath)
        return `${BUNNYCDN_PULL_ZONE}/${remotePath}`
    } catch (err) {
        console.error('📦 BunnyCDN upload error:', err)
        throw err
    }
}
