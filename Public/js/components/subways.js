export async function getSubways() {
    try {
        const res = await fetch('/subways', {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('API error');

        return await res.json() ?? [];
    } catch (error) {
        console.error('Metrostansiyalar alınamadı:', error);
        return [];
    }
}
