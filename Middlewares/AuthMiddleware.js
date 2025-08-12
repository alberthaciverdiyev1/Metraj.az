import { jwtDecode } from "jwt-decode";
import { postData, getData } from "../Helpers/callApi.js";

async function verifyTokenWithApi(token) {
    try {
        const response = await getData('/me', {}, token);
        return response && response.id;
    } catch {
        return false;
    }
}

export async function guestOnly(request, reply) {
    const token = request.session.get('jwt_token');
    if (!token) return;

    try {
        const decoded = jwtDecode(token);
        const now = Date.now() / 1000;

        if (decoded.exp && decoded.exp > now) {
            return reply.redirect('/');
        } else {
            await request.session.delete();
        }
    } catch {
        await request.session.delete();
    }
}

export async function authRequired(request, reply) {
    const token = request.session.get('jwt_token');
    if (!token) {
        return reply.redirect('/login');
    }

    try {
        const decoded = jwtDecode(token);
        const now = Date.now() / 1000;

        if (!decoded.exp || decoded.exp <= now) {
            await request.session.delete();
            return reply.redirect('/login');
        }

        const isValid = await verifyTokenWithApi(token);
        if (!isValid) {
            await request.session.delete();
            return reply.redirect('/login');
        }

    } catch {
        await request.session.delete();
        return reply.redirect('/login');
    }
}
