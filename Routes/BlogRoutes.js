import Blog from '../Controllers/BlogController.js'

export default async function blogRoutes(fastify, options) {
    fastify.get('/blog', Blog.listView);
    fastify.get('/blog/:slug', Blog.Details);
}