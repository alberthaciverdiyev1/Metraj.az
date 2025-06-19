import Fastify from 'fastify'
import pointOfView from '@fastify/view'
import handlebars from 'handlebars'
import routes from './Routes/Routes.js'
import fastifyStatic from '@fastify/static'
import * as path from 'node:path'
import fastifyCookie from '@fastify/cookie'
import secureSession from '@fastify/secure-session'
import {globSync} from 'glob'
import fs from 'fs'
import {getData} from "./Helpers/CallApi.js";
import dotenv from 'dotenv';
import i18n from './Plugins/i18n.js';
import i18next from "i18next";
import multipart from '@fastify/multipart'

dotenv.config();

// Fix for Windows paths
const partialsDir = path.join(process.cwd(), 'Views', 'Partials')
const partials = {}

// Use path.posix for consistent forward slashes in glob patterns
const globPattern = path.posix.join('**', '*.hbs')
const files = globSync(globPattern, {cwd: partialsDir, posix: true})

files.forEach(file => {
    // Use path.posix for consistent forward slashes
    const name = file.replace(/\.hbs$/, '').replace(/\//g, '.')
    partials[name] = path.posix.join('Partials', file)
})

const fastify = Fastify({logger: false})
await fastify.register(fastifyCookie)
await fastify.register(i18n)
await fastify.register(multipart)

fastify.register(secureSession, {
    key: fs.readFileSync(path.join(process.cwd(), 'secret-key')),
    cookie: {
        path: '/',
        httpOnly: true,
        secure: false, // prod true
        maxAge: 30 * 24 * 60 * 60 // 30 day
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

handlebars.registerHelper('t', function (key, options) {
    const lang = options?.data?.root?.lang || 'en';
    try {
        const t = i18next.getFixedT(lang);
        return t(key);
    } catch (e) {
        console.warn('Translation error:', e);
        return key;
    }
});

handlebars.registerHelper('splitArray', function (array, parts, options) {
    const chunkSize = Math.ceil(array.length / parts)
    const chunks = []
    for (let i = 0; i < parts; i++) {
        chunks.push(array.slice(i * chunkSize, (i + 1) * chunkSize))
    }
    return chunks.map(chunk => options.fn(chunk)).join('')
})

handlebars.registerHelper('ifNo', function (value, options) {
    if (!value) {
        return options.fn(this);
    } else {
        return options.inverse(this);
    }
});

handlebars.registerHelper('equal', function (a, b) {
    return a === b;
});

// Static files
fastify.register(fastifyStatic, {
    root: path.join(process.cwd(), 'Public'),
    prefix: '/',
})

fastify.register(pointOfView, {
    engine: {handlebars},
    root: path.join(process.cwd(), 'Views'),
    layout: 'Main',
    viewExt: 'hbs',
    options: {
        partials,
        cache: false, // production true
    },
    defaultContext: {
        useLayout: true,
        isProduction: process.env.NODE_ENV === 'production',
    }
})


fastify.addHook('onRequest', async (request, reply) => {
    console.log(`[${new Date().toISOString()}] ${request.method} ${request.url}`)
})

fastify.addHook('preHandler', async (request, reply) => {
    const originalView = reply.view.bind(reply)

    reply.view = async (template, data = {}, opts = {}) => {
        const user = request.session.get('user') || null
        const jwt_token = request.session.get('jwt_token') || null

        data.session = {user, jwt_token}
        data.user = user
        data.setting = await getData('/setting', [], false, true, true)
        data.lang = request.cookies.lang || 'en'
        return originalView(template, data, opts)
    }
})

fastify.register(routes, {prefix: '/'})
fastify.get('/aaa', async (request, reply) => {
    return {
        message: request.t('hello')
    }
})

fastify.listen({port: 3300}, err => {
    if (err) throw err
    console.log('http://localhost:3300')
})