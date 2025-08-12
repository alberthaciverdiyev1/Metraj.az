import Property from "../Controllers/PropertyController.js";

export default async function propertyRoutes(fastify, options) {

    fastify.get('/', Property.listView)
    fastify.get('/property', Property.listView)
    fastify.get('/property/:id', Property.detailsView)
    fastify.get('/properties', Property.listApi)

    fastify.get('/add-property', Property.addView)
    fastify.post('/add-property', Property.add)
}