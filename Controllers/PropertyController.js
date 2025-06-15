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
    const { id } = request.params;

    try {
        const property = await getData(`/property/${id}`, false, false, false, false);

        if (!property) {
            return reply.code(404).send('Property not found');
        }

        const view = {
            title: 'Property Detail',
            css: ['property-detail.css'],
            js: ['property-detail.js'],
            property,
            breadcrumbs: [
                { label: 'Home', url: '/' },
                { label: 'Property Listing', url: '/listing' },
                { label: 'Property Detail' }
            ]
        };

        return reply.view('Pages/Property/Details.hbs', view);

    } catch (err) {
        console.error('Error loading property:', err);
        return reply.code(500).send('Internal Server Error');
    }
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

export async function listApi(request, reply) {
    const allowedParams = [
        'type',
        'addNo',
        'townId',
        'subwayId',
        'districtId',
        'cityId',
        'propertyType',
        'addType',
        'numberOfFloors',
        'numberOfRooms',
        'floorLocated',
        'area',
        'fieldArea',
        'inCredit'
    ];

    const params = Object.fromEntries(
        Object.entries(request.query).filter(([key]) => allowedParams.includes(key))
    );

    const result = await getData('/property', params, false, false, false);
    return reply.send(result);
}


export default {listApi, detailsView, listView, addView}
