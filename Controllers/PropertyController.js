import {getData} from "../Helpers/CallApi.js";

async function listView(request, reply) {
    const view = {
        title: 'Listing Page',
        css: ['listing.css', 'app.css'],
        js: ['listing.js'],
        breadcrumbs: [
            {label: 'Home', url: '/'},
            {label: 'Property Listing', url: '/listing'}
        ]
    }

    return reply.view('Pages/Property/List.hbs', view)
}

async function detailsView(request, reply) {
    const {id} = request.params;
    const Property = await getData(`/property/${id}`, false, false, false, false);


    const view = {
        title: 'Property Detail',
        css: ['app.css',
            'components.css',
            'listing-details.css'],
        js: ['listing-detail.js', 'app.js', 'gotop.js'],
        property:Property,
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
        'type',
        'addNo',
        'townId',
        'subwayId',
        'districtId',
        'cityId',
        'property-type',
        'addType',
        'numberOfFloors',
        'numberOfRooms',
        'floorLocated',
        'area',
        'fieldArea',
        'inCredit',
    ];

    const params = Object.fromEntries(
        Object.entries(req.query).filter(([key]) => allowedParams.includes(key))
    );
    const result = await getData('/property', params, false, false, false);
    return res.send(result);
}


export default {listApi, detailsView, listView, addView}
