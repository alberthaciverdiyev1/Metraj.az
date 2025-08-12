import Static from '../Controllers/StaticController.js'

export default async function staticRoutes(fastify, options) {
    fastify.get('/contact', Static.ContactView);
    fastify.get('/faqs', Static.Faqs);
    fastify.get('/comingSoon', Static.ComingSoon);
    fastify.get('/about-us', Static.AboutUs);
}