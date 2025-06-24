import {getData} from "../Helpers/CallApi.js";

async function listView(request, reply) {
    let agencies = await getData('/agency', [], false, false, false);

    const view = {
        title: 'Agency Page',
        css: ['agencies.css', 'app.css', 'listing.css'],
        js: ['agencies.js'],
        agencies: agencies
    };

    return reply.view('Pages/Agency/List.hbs', view);
}

async function Details(request, reply) {
    const { id } = request.params;
    const Agency = await getData(`/agency/${id}`);

    const view = {
        title: 'Agency Page',
        css: ['agency-detail.css', 'app.css', 'components.css', 'agencies.css'],
        js: ['pages/agency-details.js', 'app.js', 'gotop.js'],
        agency: Agency,
        breadcrumbs: [
            {label: 'Home', url: '/'},
            {label: 'Agencies', url: '/agency'},
        ]
    }

    return reply.view('Pages/Agency/Details.hbs', view);

}
async function RelatedProperties(req, res) {
    const {id} = req.params;
    return await getData('/agency-property/' + id, [], false, false, false);
}

export default {listView,Details,RelatedProperties}