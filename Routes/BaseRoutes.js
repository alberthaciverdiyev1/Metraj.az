import Base from '../Controllers/BaseController.js'

export default async function baseRoutes(fastify, options) {
    fastify.get('/subways', Base.Subways);
    fastify.get('/cities', Base.Cities);
    fastify.get('/features', Base.Features);
    fastify.get('/nearby-objects', Base.NearbyObjects);

    fastify.get('/property-types', Base.PropertyTypes);
    fastify.get('/repair-types', Base.RepairTypes);
    fastify.get('/room-count', Base.RoomCount);
    fastify.get('/setting', Base.Setting);
    fastify.get('/clear-cache', Base.clearAllCache);

    fastify.get('/change-lang/:lang', async (request, reply) => {
        const lang = request.params.lang || 'en'
        reply.setCookie('lang', lang, {
            path: '/',
            httpOnly: false,
            sameSite: 'lax',
        })
        return {success: true}
    })

    fastify.setNotFoundHandler(Base.NotFound);
}