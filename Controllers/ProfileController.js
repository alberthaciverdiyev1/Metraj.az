import { css, js } from "../Helpers/assets.js";

export async function ProfileView(request, reply) {
    const view = {
        title: 'My profile', 
        css: css([]),
        js: js(['profile.js']),
    };
    return reply.view('Pages/Profile/Profile.hbs', view);
}