import Fastify from 'fastify'
import pointOfView from '@fastify/view'
import handlebars from 'handlebars'
import routes from './Routes/Routes.js'
import fastifyStatic from '@fastify/static'
import * as path from 'node:path'
import fastifyCookie from '@fastify/cookie'
import secureSession from '@fastify/secure-session'
import { globSync } from 'glob'
import fs from 'fs'

const partialsDir = path.join(process.cwd(), 'Views', 'Partials')
const partials = {}

globSync('**/*.hbs', { cwd: partialsDir }).forEach(file => {
    const name = file.replace(/\.hbs$/, '').replace(/\//g, '.')
    partials[name] = path.join('Partials', file)
})

const fastify = Fastify({
    logger: false
})

fastify.register(fastifyCookie)

fastify.register(secureSession, {
    key: fs.readFileSync(path.join(process.cwd(), 'secret-key')),
    cookie: {
        path: '/',
        httpOnly: true,
        secure: false, // prod true
        maxAge: 30 * 24 * 60 * 60 // 30 gün
    }
})

// Handlebars helpers
handlebars.registerHelper('strLimit', function (text, limit) {
    if (!text || typeof text !== 'string') return ''
    return text.length > limit ? text.substring(0, limit) + '...' : text
})

handlebars.registerHelper('forLoop', function (n, block) {
    let accum = ''
    for (let i = 1; i <= n; ++i) {
        accum += block.fn(i)
    }
    return accum
})

handlebars.registerHelper('range', function (start, end, options) {
    let accum = []
    for (let i = start; i <= end; ++i) accum.push(i)
    return accum
})

handlebars.registerHelper('splitArray', function (array, parts, options) {
    const chunkSize = Math.ceil(array.length / parts)
    const chunks = []
    for (let i = 0; i < parts; i++) {
        chunks.push(array.slice(i * chunkSize, (i + 1) * chunkSize))
    }
    return chunks.map(chunk => options.fn(chunk)).join('')
})

// Static files
fastify.register(fastifyStatic, {
    root: path.join(process.cwd(), 'Public'),
    prefix: '/',
})

// View engine
fastify.register(pointOfView, {
    engine: { handlebars },
    root: path.join(process.cwd(), 'Views'),
    layout: 'Main',
    viewExt: 'hbs',
    options: {
        partials,
        cache: false, // production true
    },
    defaultContext: {
        useLayout: true,
    }
})

fastify.addHook('onRequest', async (request, reply) => {
    console.log(`[${new Date().toISOString()}] ${request.method} ${request.url}`)
})

fastify.addHook('preHandler', async (request, reply) => {
    const originalView = reply.view.bind(reply)

    reply.view = (template, data = {}, opts = {}) => {
        const user = request.session.get('user') || null
        const jwt_token = request.session.get('jwt_token') || null

        data.session = { user, jwt_token }
        data.user = user
        return originalView(template, data, opts)
    }
})

fastify.register(routes, { prefix: '/' })

fastify.listen({ port: 3300 }, err => {
    if (err) throw err
    console.log('http://localhost:3300')
})
