import { css, js } from "../Helpers/assets.js";

export async function FavoriteView(request, reply) {
    const view = {
        title: 'My Favorites', 
        css: css([]),
        js: js(['favorites.js']),
    };
    return reply.view('Pages/Favorites/Favorites.hbs', view);
}