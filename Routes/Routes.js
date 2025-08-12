import authRoutes from "./AuthRoutes.js";
import agencyRoutes from "./AgencyRoutes.js";
import baseRoutes from "./BaseRoutes.js";
import blogRoutes from "./BlogRoutes.js";
import profileRoutes from "./ProfileRoutes.js";
import propertyRoutes from "./PropertyRoutes.js";
import staticRoutes from "./StaticPagesRoutes.js";


export default async function route(fastify, options) {
    fastify.register(authRoutes);
    fastify.register(agencyRoutes);
    fastify.register(baseRoutes);
    fastify.register(blogRoutes);
    fastify.register(profileRoutes);
    fastify.register(propertyRoutes);
    fastify.register(staticRoutes);

}
