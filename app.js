import Fastify from 'fastify'
import pointOfView from '@fastify/view'
import handlebars from 'handlebars'
import routes from './Routes/Routes.js'
import fastifyStatic from '@fastify/static'
import * as path from 'node:path'
import { fileURLToPath } from 'node:url'
import fastifyCookie from '@fastify/cookie'
import secureSession from '@fastify/secure-session'
import { globSync } from 'glob'
import fs from 'fs'
import { getData } from './Helpers/CallApi.js'
import dotenv from 'dotenv'
import i18n from './Plugins/i18n.js'
import i18next from 'i18next'
import multipart from '@fastify/multipart'
import fastifyMinify from 'fastify-minify'

dotenv.config()

const __dirname = path.dirname(fileURLToPath(import.meta.url))

const partialsDir = path.join(__dirname, 'Views', 'Partials')
const partials = {}
const globPattern = path.join('**', '*.hbs').replace(/\\/g, '/') 
const files = globSync(globPattern, { cwd: partialsDir, posix: true })

files.forEach(file => {
    const name = file.replace(/\.hbs$/, '').replace(/\//g, '.')
    partials[name] = path.join('Partials', file).replace(/\\/g, '/') 
})

const fastify = Fastify({ logger: false })

await fastify.register(fastifyCookie)
await fastify.register(i18n)
await fastify.register(multipart)

fastify.register(secureSession, {
    key: fs.readFileSync(path.join(__dirname, 'secret-key')),
    cookie: {
        path: '/',
        httpOnly: true,
        secure: false,
        maxAge: 30 * 24 * 60 * 60,
    },
})

await fastify.register(fastifyStatic, {
    root: path.join(__dirname, process.env.NODE_ENV === 'production' ? 'Dist' : 'Public'),
    prefix: '/',
})

await fastify.register(fastifyMinify, {
    cache: 2000,
    global: true,
    minInfix: false,
    validate: (req, reply, payload) => {
        const contentType = reply.getHeader('content-type') || ''
        return contentType.includes('application/json') && typeof payload === 'string'
    },
})

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
    const lang = options?.data?.root?.lang || 'az'
    try {
        const t = i18next.getFixedT(lang)
        return t(key)
    } catch (e) {
        console.warn('Translation error:', e)
        return key
    }
})

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
        return options.fn(this)
    } else {
        return options.inverse(this)
    }
})

handlebars.registerHelper('equal', function (a, b) {
    return a === b
})

handlebars.registerHelper('css', function (file) {
    const prefix = process.env.NODE_ENV === 'production' ? '/assets/css/' : '/css/'
    return new handlebars.SafeString(`<link rel="stylesheet" href="${prefix}${file}">`)
})

handlebars.registerHelper('js', function (file) {
    const prefix = process.env.NODE_ENV === 'production' ? '/assets/js/' : '/js/'
    return new handlebars.SafeString(`<script src="${prefix}${file}"></script>`)
})

fastify.register(pointOfView, {
    engine: { handlebars },
    root: path.join(__dirname, 'Views'),
    layout: 'Main',
    viewExt: 'hbs',
    options: {
        partials,
        cache: false,
    },
    defaultContext: {
        useLayout: true,
        isProduction: process.env.NODE_ENV === 'production',
    },
})

fastify.addHook('onRequest', async (request, reply) => {
    console.log(`[${new Date().toISOString()}] ${request.method} ${request.url}`)
})

fastify.addHook('preHandler', async (request, reply) => {
    const statics = /\.(css|js|png|jpg|jpeg|gif|webp|svg|ico|woff2?|ttf|eot|json)$/i;

    if (statics.test(request.raw.url)) {
        return;
    }

    const accept = request.headers['accept'] || '';
    if (accept.includes('application/json')) {
        return;
    }

    const originalView = reply.view.bind(reply);

    reply.view = async (template, data = {}, opts = {}) => {
        const user = request.session.get('user') || null;
        const jwtToken = request.session.get('jwt_token') || null;

        const currentRoute = (request.routerPath || request.raw.url || '').split('/').join('');


        data.session = { user, jwtToken };
        data.user = user;
        data.setting = await getData('/setting', [], false, true, true);
      //  data.seo = await getData(`/seo/${currentRoute}`, [], false, true, true);
        console.log(data.seo);
        data.lang = request.cookies.lang || 'en';

        return originalView(template, data, opts);
    };
});


fastify.register(routes, { prefix: '/' })

// Example route
fastify.get('/test', async (request, reply) => {
    return {
        message: request.t('hello'),
    }
})

fastify.listen({ port: 3300, host: '0.0.0.0' }, err => {
    if (err) {
        console.error(err)
        process.exit(1)
    }
    console.log('Server running on http://localhost:3300')
})