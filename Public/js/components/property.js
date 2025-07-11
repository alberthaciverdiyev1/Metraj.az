export async function getPropertiesList(searchParams = {}) {
    try {
        const params = new URLSearchParams();

        for (const [key, value] of Object.entries(searchParams)) {
            if (value !== undefined && value !== null && value !== '' && value !== 'all') {
                params.set(key, value);
            }
        }

        const queryString = params.toString();
        const url = `/properties${queryString ? `?${queryString}` : ''}`;

        if (typeof window !== 'undefined') {
            const currentPath = window.location.pathname;
            window.history.replaceState({}, '', `${currentPath}${queryString ? `?${queryString}` : ''}`);
        }


        console.log('Request URL:', url);

        const res = await fetch(url, {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        });

        if (!res.ok) {
            const errorText = await res.text();
            throw new Error(`API Xətası! Status: ${res.status}, Mesaj: ${errorText}`);
        }

        const apiResponse = await res.json();

        if (Array.isArray(apiResponse)) {
            return apiResponse;
        } else {
            console.warn("Fastify-dən gözlənilməyən data formatı gəldi, massiv gözlənilir:", apiResponse);
            return [];
        }

    } catch (error) {
        console.error('Əmlak alınarkən xəta (getPropertiesList):', error);
        return [];
    }
}
