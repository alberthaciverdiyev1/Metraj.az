export async function getPropertiesList() {
    try {
        const res = await fetch('/properties', {
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

