export async function getNearbyObjects() {
    try {
        const res = await fetch('/nearby-objects', {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('API error');
        return await res.json() ?? [];
    } catch (error) {
        console.error('Obyektler alınamadı:', error);
        return [];
    }
}

