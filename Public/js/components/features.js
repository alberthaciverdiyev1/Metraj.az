export async function getFeatures() {
    try {
        const res = await fetch('/features', {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('API error');

        return await res.json() ?? [];
    } catch (error) {
        console.error('Xüsusiyyətlər alınamadı:', error);
        return [];
    }
}

