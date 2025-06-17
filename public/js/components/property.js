export async function getPropertiesList() {
    try {
        const params = new URLSearchParams(window.location.search);
        const queryString = params.toString();

        const url = '/properties' + (queryString ? `?${queryString}` : '');

        const res = await fetch(url, {
            headers: {
                'Accept': 'application/json',
            }
        });

        if (!res.ok) throw new Error('API error');

        return await res.json() ?? [];
    } catch (error) {
        console.error('Əmlak alınamadı:', error);
        return [];
    }
}


