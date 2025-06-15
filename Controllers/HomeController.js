export async function homePage(request, reply) {
    const view = {
        title: 'Home Page',
        css: ['home.css', 'app.css', 'components.css'],
        js: ['home.js']
    };

    return reply.view('Pages/Home.hbs', view);
}
