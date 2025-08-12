import {CompareView} from '../Controllers/CompareController.js';
import {FavoriteView} from '../Controllers/FavoritesController.js'
import {ProfileView} from '../Controllers/ProfileController.js'
import {MySaveSearchesView} from '../Controllers/MySaveSearchesController.js'
import {DashboardView} from '../Controllers/DashboardsController.js'

export default async function profileRoutes(fastify, options) {
    fastify.get('/compares', CompareView);
    fastify.get('/profile', ProfileView)
    fastify.get('/dashboard', DashboardView)
    fastify.get('/favorites', FavoriteView)
    fastify.get('/mysavesearches', MySaveSearchesView)
}