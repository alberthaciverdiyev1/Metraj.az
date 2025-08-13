import Agency from '../Controllers/AgencyController.js'

export default async function agencyRoutes(fastify, options) {
    fastify.get('/agencies', Agency.listView)
    fastify.get('/agency/:id', Agency.Details)
    fastify.get('/related-properties/:id', Agency.RelatedProperties)
    fastify.post('/agency/make-premium/:id', Agency.MakePremium)
}
