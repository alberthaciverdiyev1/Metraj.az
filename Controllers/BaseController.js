import {getData} from "../Helpers/CallApi.js";

async function Cities(request, reply) {
    return await getData('/city', [], false, false, true);
}

async function Features(request, reply) {
    return await getData('/feature', [], false, false, true);
}

async function Subways(request, reply) {
    return await getData('/subway', [], false, false, true);
}

async function PropertyTypes(request, reply) {
    return await getData('/property-types', [], true, false, true);
}

async function RepairTypes(request, reply) {
    return await getData('/repair-types', [], true, false, true);
}

async function RoomCount(request, reply) {
    return await getData('/room-count', [], true, false, true);
}

async function NotFound(request, reply) {
    return reply.view('Pages/Error/404.hbs', {useLayout: false});

}

export default {Cities, Features, Subways, PropertyTypes, RepairTypes, RoomCount, NotFound};