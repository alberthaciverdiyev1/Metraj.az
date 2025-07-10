
export async function getPropertiesList(searchParams = {}) {
    try {
        const params = new URLSearchParams();

        if (searchParams && typeof searchParams === 'object') {
            Object.entries(searchParams).forEach(([key, value]) => {
                if (value !== undefined && value !== null && value !== '' && value !== 'all') {
                    let paramKey = key;
                    if (key === 'adType') paramKey = 'add-type';
                    if (key === 'maxArea') paramKey = 'max-area';
                    if (key === 'minPrice') paramKey = 'min-price';
                    if (key === 'maxPrice') paramKey = 'max-price';
                    if (key === 'roomCount') paramKey = 'room-count';
                    if (key === 'cityId') paramKey = 'city-id';
                    if (key === 'propertyType') paramKey = 'property-type';
                    if (key === 'propertyCondition') paramKey = 'property-condition';
                    if (key === 'buildingType') paramKey = 'building-type';
                    if (key === 'townId') paramKey = 'town-id';
                    if (key === 'subwayId') paramKey = 'subway-id';
                    if (key === 'districtId') paramKey = 'district-id';
                    if (key === 'addNo') paramKey = 'add-no';
                    if (key === 'numberOfFloors') paramKey = 'number-of-floors';
                    if (key === 'floorLocated') paramKey = 'floor-located';
                    if (key === 'inCredit') paramKey = 'in-credit';

                    params.set(paramKey, value);
                }
            });
        }

        const queryString = params.toString();

        const url = `/properties` + (queryString ? `?${queryString}` : '');

        console.log('Frontend getPropertiesList Request URL:', url);

        const res = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            const errorText = await res.text();
            throw new Error(`API Xətası! Status: ${res.status}, Mesaj: ${errorText}`);
        }

        const apiResponse = await res.json(); 

        console.log('Raw API response in getPropertiesList :', apiResponse);

        if (Array.isArray(apiResponse)) {
            console.log('Processed (flattened) properties in getPropertiesList:', apiResponse);
            return apiResponse;
        } else {
            console.warn("Fastify-dən gözlənilməyən data formatı gəldi (getPropertiesList): massiv gözlənilir.", apiResponse);
            return [];
        }

    } catch (error) {
        console.error('Əmlak alınarkən xəta (Frontend getPropertiesList):', error);
        return []; 
    }
}