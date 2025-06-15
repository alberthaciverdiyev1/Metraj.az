import Fastify from 'fastify'
import pointOfView from '@fastify/view'
import handlebars from 'handlebars'
import routes from './Routes/Routes.js'
import fastifyStatic from "@fastify/static"
import * as path from "node:path"
import fastifySession from '@fastify/session'
import fastifyCookie from '@fastify/cookie'
import {globSync} from 'glob'

const partialsDir = path.join(process.cwd(), 'Views', 'Partials')
const partials = {}

globSync('**/*.hbs', {cwd: partialsDir}).forEach(file => {
    const name = file.replace(/\.hbs$/, '').replace(/\//g, '.')
    partials[name] = path.join('Partials', file)
})

const fastify = Fastify({
    logger: true
})

fastify.register(fastifyStatic, {
    root: path.join(process.cwd(), 'Public'),
    prefix: '/',
})

fastify.register(fastifyCookie)

fastify.register(fastifySession, {
    secret: 'a_very_secret_key_that_is_at_least_32_characters',
    cookie: {secure: false},
    saveUninitialized: false
})

//Start Heplers
handlebars.registerHelper('strLimit', function (text, limit) {
    if (!text || typeof text !== 'string') return '';
    return text.length > limit ? text.substring(0, limit) + '...' : text;
});


handlebars.registerHelper('forLoop', function (n, block) {
    let accum = '';
    for (let i = 1; i <= n; ++i) {
        accum += block.fn(i);
    }
    return accum;
});

handlebars.registerHelper('range', function (start, end, options) {
    let accum = [];
    for (let i = start; i <= end; ++i)
        accum.push(i);
    return accum;
});

handlebars.registerHelper('splitArray', function (array, parts, options) {
    const chunkSize = Math.ceil(array.length / parts);
    const chunks = [];

    for (let i = 0; i < parts; i++) {
        chunks.push(array.slice(i * chunkSize, (i + 1) * chunkSize));
    }

    return chunks.map(chunk => options.fn(chunk)).join('');
});


//End Helpers

console.log({partials})
fastify.register(pointOfView, {
    engine: {
        handlebars: handlebars,
    },
    root: path.join(process.cwd(), 'Views'),
    layout: 'Main',
    viewExt: 'hbs',
    options: {
        partials,
        cache: false, //developmentde false productionda true olsun
    },
    defaultContext: {
        useLayout: true
    }
})

fastify.addHook('onRequest', async (request, reply) => {
    console.log(`[${new Date().toISOString()}] ${request.method} ${request.url}`)
})

fastify.addHook('onRequest', async (req, reply) => {
    reply.locals = reply.locals || {}
    reply.locals.user = req.session.get('user') || null
})

fastify.register(routes, {prefix: '/'})

const start = async () => {
    try {
        await fastify.listen({port: 3300})
        console.log('Server started: http://localhost:3300')
    } catch (err) {
        fastify.log.error(err)
        process.exit(1)
    }
}

start()
