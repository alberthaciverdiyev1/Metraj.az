import {getData} from "../Helpers/callApi.js";
import {css, js} from "../Helpers/assets.js";

async function listView(request, reply) {
    const Blogs = await getData('/blog', [], false, false, false);

    const view = {
        title: 'Home Page',
        css: css(['blog.css', 'app.css', 'components.css', 'listing-details.css', 'agencies.css']),
        js: js(['blog.js', 'gotop.js', 'app.js']),
        blogs: Blogs
    };

    return reply.view('Pages/Blog/List.hbs', view);
}

async function Details(request, reply) {
    const {slug} = request.params;

    const Blog = await getData(`/blog/${slug}`);
    console.log(Blog)
    const view = {
        title: Blog.title || 'Blog Details',
        css: css(['blog-detail.css', 'app.css', 'components.css', 'listing-details.css', 'agencies.css', 'blog.css']),
        js: js(['blog-detail.js', 'gotop.js', 'app.js']),
        blog: Blog,
        relatedPosts:[],
        breadcrumbs: [
            {label: 'Home', url: '/'},
            {label: 'Blog', url: '/blog'},
        ]
    };
    return reply.view('Pages/Blog/Details.hbs', view);
}

export default {listView, Details}


