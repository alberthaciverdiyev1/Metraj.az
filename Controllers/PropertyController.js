import {getData} from "../Helpers/CallApi.js";

async function listView(request, reply) {
    const buildingTypes = await getData(`/property-types`, false, true, true, true);

    const view = {
        title: 'Listing Page',
        css: ['listing.css', 'app.css'],
        js: ['listing.js'],
        breadcrumbs: [
            {label: 'Home', url: '/'},
            {label: 'Property Listing', url: '/listing'}
        ],
        params: {
            buildingTypes
        }
    }

    return reply.view('Pages/Property/List.hbs', view)
}

async function detailsView(request, reply) {
    const {id} = request.params;
    const Property = await getData(`/property/${id}`, false, false, false, false);
console.log(Property);

    const view = {
        title: 'Property Detail',
        css: ['app.css',
            'components.css',
            'listing-details.css'],
        js: ['listing-detail.js', 'app.js', 'gotop.js'],
        property: Property,
        breadcrumbs: [
            {label: 'Home', url: '/'},
            {label: 'Property Listing', url: '/listing'},
            {label: 'Property Detail'}
        ]
    };

    return reply.view('Pages/Property/Details.hbs', view);


}

async function addView(request, reply) {
    const view = {
        title: 'Add Property Page',
        css: ['add-property.css',
            'app.css',
            'components.css'
        ],
        js: ['add-property.js',
            'map-find-adress.js',
            'components/features.js',
            'components/cities.js',
            'components/propertyTypes.js',
            'app.js'
        ],
        breadcrumbs: [
            {label: 'Home', url: '/'},
            {label: 'Property Listing', url: '/listing'}
        ]
    }

    return reply.view('Pages/Property/Add.hbs', view)
}

export async function listApi(req, res) {
    const allowedParams = [
        'property-type',
        'property-condition',
        'building-type',
        'room-count',
        'city-id',


        'type',
        'add-no',
        'town-id',
        'subway-id',
        'district-id',
        'add-type',
        'number-of-floors',
        'floor-located',
        'area',
        'field-area',
        'in-credit',
    ];

    const params = Object.fromEntries(
        Object.entries(req.query).filter(([key]) => allowedParams.includes(key))
    );
    const result = await getData('/property', params, false, false, false);
    return res.send(result);
}


export default {listApi, detailsView, listView, addView}
