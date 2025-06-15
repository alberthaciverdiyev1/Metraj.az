import {getData} from "../Helpers/CallApi.js";

async function listView(request, reply) {
    const Blogs = await getData('/blog', [], false, false, false);

    const view = {
        title: 'Home Page',
        css:  ['blog.css', 'app.css', 'components.css', 'listing-details.css', 'agencies.css'],
        js:  ['blog.js', 'gotop.js','app.js'],
        blogs: Blogs
    };

    return reply.view('Pages/Blog/List.hbs', view);
}

export default {listView}


