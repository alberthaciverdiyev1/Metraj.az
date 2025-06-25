import {getData, postData} from "../Helpers/CallApi.js";
import fs from 'fs'
import path from 'path'
import {randomUUID} from 'crypto'
import pump from 'pump'
import {uploadToBunny} from "../Helpers/bunnyCDN.js";
import axios from 'axios'
async function listView(request, reply) {
    const buildingTypes = await getData(`/property-types`, false, true, true, true);
    const citiesResponse = await axios.get('https://api.porfolio.space/api/city');
    const cities = citiesResponse?.data?.data || [];
    

    const view = {
        title: 'Listing Page',
        css: ['listing.css', 'app.css'],
        js: ['listing.js'],
        breadcrumbs: [
            {label: 'Home', url: '/'},
            {label: 'Property Listing', url: '/listing'}
        ],
        cities 
,
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
        town_id: fields.townId ? parseInt(+fields.townId) : null,
        subway_id: fields.subwayId ? parseInt(+fields.subwayId) : null,
        district_id: fields.district_id ? parseInt(+fields.districtId) : null,
        city_id: fields.cityId ? parseInt(+fields.cityId) : null,
        address: fields.address,
        property_condition: fields.propertyCondition,
        add_type: fields.addType,
        building_type: fields.buildingType,
        number_of_floors: parseInt(+fields.floorCount),
        number_of_rooms: parseInt(+fields.roomCount),
        floor_located: parseInt(+fields.locatedFloor),
        area: fields.area ? parseInt(+fields.area) : null,
        field_area: +fields.fieldArea ? parseInt(+fields.fieldArea) : null,
        advertiser: fields.advertiser,
        advertiser_name: fields.advertiserName,
        phone_1: fields.phone1,
        phone_2: fields.phone2 || null,
        phone_3: fields.phone3 || null,
        phone_4: fields.phone4 || null,
        mail: fields.email,
        description: fields.description,
        in_credit: fields.isCredit === 'true',
        note_to_admin: fields.noteToAdmin,
        has_video: media.some(m => m.type === 'video'),
        google_map_location: fields.map,
        is_active: true,
        is_premium: false,
        user_id: 1,
        realtor_id: 1,
        price: parseInt(+fields.price),
        media,
        features,
        nearby_objects: nearbyObjects,
    };
    console.log(data)
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
