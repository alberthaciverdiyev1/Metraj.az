import fp from 'fastify-plugin'
import i18next from 'i18next'
import Backend from 'i18next-fs-backend'
import path from 'path'

export default fp(async function (fastify, opts) {
    await i18next.use(Backend).init({
        backend: {
            loadPath: path.resolve('./Locales/{{lng}}.json')
        },
        fallbackLng: 'az',
        preload: ['en', 'az','ru'],
        debug: false
    })

    fastify.decorateRequest('t', null)
    fastify.addHook('onRequest', async (request, reply) => {
        const lang = request.cookies?.lang || 'az'
        request.t = i18next.getFixedT(lang)
    })

})
