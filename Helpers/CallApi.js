import axios from 'axios'
import NodeCache from 'node-cache'

const cache = new NodeCache({ stdTTL: 864000 }) // 10 day

//const API_URL = process.env.API_URL?.replace(/\/$/, '') || 'http://localhost:8000'

const API_URL = 'https://api.porfolio.space';

export async function getData( url, params = {}, enumMode = false, allData = false, useCache = true) {
    const queryParams = allData ? params : { page: 1, ...params }
    const fullUrl = `${API_URL}/api${url}`
    const cacheKey = `api_${fullUrl}`

    if (useCache) {
        const cached = cache.get(cacheKey)
        if (cached) return cached
    }

    try {
        const response = await axios.get(fullUrl, { params: queryParams })
        const result = enumMode ? response.data : (response.data?.data || [])
        if (useCache) cache.set(cacheKey, result)
        return result
    } catch (error) {
        console.error('GET error:', error?.response?.data || error.message)
        return []
    }
}

export async function postData(url, payload = {}) {
    const fullUrl = `${API_URL}/api${url}`

    try {
        const response = await axios.post(fullUrl, payload, {
            headers: { Accept: 'application/json' }
        })
        return response.data
    } catch (error) {
        const res = error.response
        return {
            status: res?.status || 500,
            message: res?.data?.message || 'API error',
            errors: res?.data?.errors || [],
        }
    }
}

export async function putData(url, payload = {}) {
    const fullUrl = `${API_URL}/api${url}`

    try {
        const response = await axios.put(fullUrl, payload, {
            headers: { Accept: 'application/json' }
        })
        return response.data?.data || []
    } catch (error) {
        console.error('PUT error:', error?.response?.data || error.message)
        return []
    }
}

export async function patchData(url, payload = {}) {
    const fullUrl = `${API_URL}/api${url}`

    try {
        const response = await axios.patch(fullUrl, payload)
        return response.data?.data || []
    } catch (error) {
        console.error('PATCH error:', error?.response?.data || error.message)
        return []
    }
}

export async function deleteData(url, payload = {}) {
    const fullUrl = `${API_URL}/api${url}`

    try {
        const response = await axios.delete(fullUrl, {
            data: payload,
            headers: { 'Content-Type': 'application/json' }
        })
        return response.data?.data || []
    } catch (error) {
        console.error('DELETE error:', error?.response?.data || error.message)
        return []
    }
}

export function setData(input = '') {
    return String(input).trim().replace(/[&<>"']/g, match => {
        const escape = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        }
        return escape[match]
    })
}
