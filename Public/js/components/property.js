export async function getPropertiesList(body = null) {
    try {
        const params = new URLSearchParams(window.location.search);

        if (body && typeof body === 'object') {
            Object.entries(body).forEach(([key, value]) => {
                if (value !== undefined && value !== null) {
                    params.set(key, value);
                }
            });
        }

        const queryString = params.toString();

        console.log('Query string:', queryString);
        const url = '/properties' + (queryString ? `?${queryString}` : '');

        const res = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('API error');

        return await res.json() ?? [];
    } catch (error) {
        console.error('Əmlak alınamadı:', error);
        return [];
    }
}
