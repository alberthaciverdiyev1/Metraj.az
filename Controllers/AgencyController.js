async function listView(request, reply) {
    let agencies = [
            {
                "id": "1",
                "name": "Lorem House",
                "address": "2118 Thornridge Cir. Syracuse, Connecticut 35624",
                "location": "102 Ingraham St, Brooklyn, NY 11237",
                "listing_count": 7328,
                "image": "https://themesflat.co/html/proty/images/section/agencies-1.jpg",
                "hotline": "+7-445-556-8337",
                "phone": "+7-445-556-8337",
                "email": "loremhouse@gmail.com",
                "about": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...",
                "short_about": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...",
                "logo": "https://themesflat.co/html/proty/images/brands/brand-7.jpg"
            },
            {
                "id": "2",
                "name": "Lorem House-2",
                "address": "2118 Thornridge Cir. Syracuse, Connecticut 35624",
                "location": "102 Ingraham St, Brooklyn, NY 11237",
                "listing_count": 9328,
                "image": "https://themesflat.co/html/proty/images/section/agencies-2.jpg",
                "hotline": "+7-445-556-8337",
                "phone": "+7-445-556-8337",
                "email": "loremhouse@gmail.com",
                "about": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...",
                "short_about": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...",
                "logo": "https://themesflat.co/html/proty/images/brands/brand-1.jpg"
            },
            {
                "id": "3",
                "name": "Lorem House-3",
                "address": "2118 Thornridge Cir. Syracuse, Connecticut 35624",
                "location": "102 Ingraham St, Brooklyn, NY 11237",
                "listing_count": 9328,
                "image": "https://themesflat.co/html/proty/images/section/agencies-3.jpg",
                "hotline": "+7-445-556-8337",
                "phone": "+7-445-556-8337",
                "email": "loremhouse@gmail.com",
                "about": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...",
                "short_about": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus leo, blandit vitae diam a, vestibulum viverra nisi...",
                "logo": "https://themesflat.co/html/proty/images/brands/brand-1.jpg"
            }
        ]

    const view = {
        title: 'Home Page',
        css: ['agencies.css', 'app.css', 'listing.css'],
        js: ['agencies.js', 'listing.js'],
        agencies: agencies
    };

    return reply.view('Pages/Agency/List.hbs', view);
}

export default {listView}