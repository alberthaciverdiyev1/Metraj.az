
export async function getPropertiesList(searchParams = {}) {
    try {
        const url = new URL('http://127.0.0.1:8000/api/property');

        if (searchParams && typeof searchParams === 'object') {
            Object.entries(searchParams).forEach(([key, value]) => {
                if (value !== undefined && value !== null && value !== '' && value !== 'all') {
                    let paramKey = key;
                    if (key === 'adType') paramKey = 'add_type';
                    if (key === 'minArea') paramKey = 'min_area';
                    if (key === 'maxArea') paramKey = 'max_area';
                    if (key === 'minPrice') paramKey = 'min_price';
                    if (key === 'maxPrice') paramKey = 'max_price';

                    url.searchParams.set(paramKey, value);
                }
            });
        }

        console.log('API Request URL:', url.toString());

        const res = await fetch(url.toString(), {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            const errorText = await res.text();
            throw new Error(`API xətası! Status: ${res.status}, Mesaj: ${errorText}`);
        }

        const data = await res.json();
        return data; 
    } catch (error) {
        console.error('Əmlak alınarkən xəta:', error);
        return { data: {} };
    }
}