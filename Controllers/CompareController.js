import { css, js } from "../Helpers/assets.js";

export async function CompareView(request, reply) {
    const rawProductsData = [
        {
            "id": 10,
            "slug": "molestiae-temporibus-non-reiciendis-praesentium-est",
            "date": "18 July, 2025",
            "title": "7 otaqlı Nəriman Nərimanov",
            "address": "Salyan Sabunçu Nardaran",
            "beds": 4,
            "baths": 3,
            "area": 192,
            "price": [
                {
                    "price": 489017,
                    "currency": "0x001", 
                    "property_id": 10,
                    "created_at": "2025-07-04T15:29:35.000000Z"
                }
            ],
            "add_type": "sale",
            "is_premium": true,
            "media": {
                "type": "image",
                "path": "https://fastly.picsum.photos/id/25/5000/3333.jpg?hmac=yCz9LeSs-i72Ru0YvvpsoECnCTxZjzGde805gWrAHkM"
            }
        },
        {
            "id": 13,
            "slug": "sunt-exercitationem-nulla-sint",
            "date": "18 July, 2025",
            "title": "5 otaqlı İçərişəhər",
            "address": "Ağdaş Səbail Şamaxı kəndi",
            "beds": 4,
            "baths": 3,
            "area": 58,
            "price": [
                {
                    "price": 559913,
                    "currency": "0x001", 
                    "property_id": 13,
                    "created_at": "2025-07-04T15:29:43.000000Z"
                }
            ],
            "add_type": "rent",
            "is_premium": true,
            "media": {
                "type": "image",
                "path": "https://fastly.picsum.photos/id/19/2500/1667.jpg?hmac=7epGozH4QjToGaBf_xb2HbFTXoV5o8n_cYzB7I4lt6g"
            }
        }
    ];

    const productsToCompare = rawProductsData.map(product => {
        const currencySymbol = product.price[0].currency === "0x001" ? "AZN" : product.price[0].currency; 
        return {
            id: product.id,
            name: product.title, 
            image: product.media.path, 
            price: `${product.price[0].price} ${currencySymbol}`, 
            features: {
                // Map new features directly
                "date": product.date,
                "address": product.address,
                "beds": product.beds,
                "baths": product.baths,
                "area": `${product.area} m²`, 
                "add_type": product.add_type,
            }
        };
    });

    let allFeatures = new Set();
    productsToCompare.forEach(product => {
        Object.keys(product.features).forEach(feature => allFeatures.add(feature));
    });
    const featureKeys = Array.from(allFeatures);

    const view = {
        title: 'Compare Products',
        css: css([]),
        js: js([]),
        products: productsToCompare,
        featureKeys: featureKeys
    };

    return reply.view('Pages/Compare/Compare.hbs', view);
}
