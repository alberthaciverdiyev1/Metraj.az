import {getData, postData} from "../Helpers/CallApi.js";
import fs from 'fs'
import path from 'path'
import {randomUUID} from 'crypto'
import pump from 'pump'
import {uploadToBunny} from "../Helpers/bunnyCDN.js";

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

export async function add(req, res) {
    const fields = {};
    const media = [];

    const features = [];
    const nearbyObjects = [];

    const parts = req.parts();

    for await (const part of parts) {
        if (part.file) {
            const ext = path.extname(part.filename);
            const filename = `${randomUUID()}${ext}`;
            const remotePath = `images/properties/${filename}`;

            const url = await uploadToBunny(part, remotePath);

            media.push({
                type: part.fieldname === 'video' ? 'video' : ('image' ?? 'document'),
                path: url
            });
        } else {
            const name = part.fieldname;
            const value = part.value;

            if (name.startsWith('features[')) {
                features.push(parseInt(value));
            } else if (name.startsWith('nearbyObjects[')) {
                nearbyObjects.push(parseInt(value));
            } else {
                fields[name] = value;
            }
        }
    }

    const data = {
        add_no: fields.add_no || null,
        slug: fields.slug,
        town_id: parseInt(+fields.town_id),
        subway_id: parseInt(+fields.subway_id),
        district_id: parseInt(+fields.district_id),
        city_id: parseInt(+fields.city_id),
        address: fields.address,
        property_condition: fields.property_condition,
        add_type: fields.add_type,
        building_type: fields.building_type,
        number_of_floors: parseInt(+fields.floorCount),
        number_of_rooms: parseInt(+fields.roomCount),
        floor_located: parseInt(+fields.locatedFloor),
        area: parseInt(+fields.area),
        field_area: parseInt(+fields.field_area),
        advertiser: fields.advertiser,
        advertiser_name: fields.advertiser_name,
        phone_1: fields.phone_1,
        phone_2: fields.phone_2 || null,
        phone_3: fields.phone_3 || null,
        phone_4: fields.phone_4 || null,
        mail: fields.email,
        description: fields.description,
        in_credit: fields.isCredit === 'true',
        note_to_admin: fields.noteToAdmin,
        has_video: media.some(m => m.type === 'video'),
        google_map_location: fields.map,
        is_active: true,
        is_premium: true,
        user_id: 1,
        realtor_id: 1,
        price: parseInt(+fields.price),
        media,
        features,
        nearby_objects: nearbyObjects,
    };

    return await postData('/property', data)
    //return res.send({status: 'ok', data});
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


export default {listApi, detailsView, listView, addView, add}
