import {getData} from "../Helpers/CallApi.js";

async function Cities(request, reply) {
    return await getData('/city', [], false, false, false);
}

async function Features(request, reply) {
    return await getData('/feature', [], false, false, false);
}

async function Subways(request, reply) {
    return await getData('/subway', [], false, false, false);
}
async function PropertyTypes(request, reply) {
    return await getData('/property-types', [], true, false, false);
}

async function RepairTypes(request, reply) {
    return await getData('/repair-types', [], true, false, false);
}

async function RoomCount(request, reply) {
    return await getData('/room-count', [], true, false, false);
}


export default {Cities, Features,Subways,PropertyTypes,RepairTypes,RoomCount};