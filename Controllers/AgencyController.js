import {getData} from "../Helpers/CallApi.js";
import {js,css} from "../Helpers/assets.js";

async function listView(request, reply) {
    let agencies = await getData('/agency', [], false, false, false);

    const cssFiles = css(['agencies.css', 'app.css', 'listing.css']);
    const jsFiles = js(['agencies.js']);

    const view = {
        title: 'Agency Page',
        css: cssFiles,
        js: jsFiles,
        agencies
    };

    return reply.view('Pages/Agency/List.hbs', view);
}


async function Details(request, reply) {
    const { id } = request.params;
    const Agency = await getData(`/agency/${id}`);

    const view = {
        title: 'Agency Page',
        css: css(['agency-detail.css', 'app.css', 'components.css', 'agencies.css']),
        js: js(['pages/agency-details.js', 'app.js', 'gotop.js']),
        agency: Agency,
        breadcrumbs: [
            {label: 'Home', url: '/'},
            {label: 'Agencies', url: '/agency'},
        ]
    }

    return reply.view('Pages/Agency/Details.hbs', view);

}

// RelatedProperties funksiyasını yeniləyirik
async function RelatedProperties(req) {
    const { id } = req.params;
  
    const adType = req.query.adType || '';

    let url = `/agency-property/${id}`;

    if (adType && adType !== 'all') { 
        url += `?adType=${adType}`;
    }

    console.log(`Backend: Fetching properties from URL: ${url}`); 

    return await getData(url, [], false, true, false);
}

export default {listView,Details,RelatedProperties}