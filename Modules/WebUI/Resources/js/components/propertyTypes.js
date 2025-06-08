
export async function getPropertyTypes() {
    try {
        const res = await fetch('/property-types', {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('API error');

        return await res.json() ?? [];
    } catch (error) {
        console.error('Property Types alınamadı:', error);
        return [];
    }
}
