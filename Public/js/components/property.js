
export async function getPropertiesList(searchParams = {}) {
    try {
        const params = new URLSearchParams();

        for (const [key, value] of Object.entries(searchParams)) {
            if (value !== undefined && value !== null && value !== '' && value !== 'all') {
                params.set(key, value);
            }
        }

       // const baseUrl = "http://127.0.0.1:8000/api/property";
        const baseUrl = "/properties";
        const url = `${baseUrl}${params.toString() ? `?${params.toString()}` : ''}`;

        // if (typeof window !== 'undefined') {
        //     const currentPath = window.location.pathname;
           
        //     window.history.replaceState({}, '', `${currentPath}${params.toString() ? `?${params.toString()}` : ''}`);
        // }

        // console.log('Request URL:', url);

        const res = await fetch(url, {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        });

        if (!res.ok) {
            const errorText = await res.text();
            throw new Error(`API Xətası! Status: ${res.status}, Mesaj: ${errorText}`);
        }

        const apiResponse = await res.json();


        if (apiResponse && Array.isArray(apiResponse)) {
            return apiResponse;
        } else {
            console.warn("Backend-dən gözlənilməyən data formatı gəldi, 'data' massivi gözlənilir:", apiResponse);
            return [];
        }

    } catch (error) {
        console.error('Əmlak alınarkən xəta (getPropertiesList):', error);
        return [];
    }
}