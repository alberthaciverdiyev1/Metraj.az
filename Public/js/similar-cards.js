
import { getPropertiesList } from "./components/property.js";
import { propertyCard } from "./cards/property.js";

document.addEventListener('DOMContentLoaded', async () => {
    const similarCardsContainer = document.getElementById('similar-properties-container');
    const loader = document.getElementById('similar-cards-loader');

    if (!similarCardsContainer || !loader) {
        console.error("Similar cards container or loader element not found. Make sure IDs are correct.");
        return;
    }

    const currentPropertyAddType = similarCardsContainer.dataset.addType;
    const currentPropertyId = similarCardsContainer.dataset.currentPropertyId;

    loader.classList.remove('hidden');

    try {
        let allProperties = [];
        if (currentPropertyAddType) {
            allProperties = await getPropertiesList({ adType: currentPropertyAddType });
        } else {
            allProperties = await getPropertiesList();
        }

        loader.classList.add('hidden');

        const filteredProperties = allProperties.filter(prop => prop.id !== parseInt(currentPropertyId));

        const propertiesToDisplay = filteredProperties.slice(0, 3);

        if (propertiesToDisplay.length > 0) {
            similarCardsContainer.innerHTML = '';

            propertiesToDisplay.forEach(prop => {
                similarCardsContainer.insertAdjacentHTML('beforeend', propertyCard(prop));
            });
        } else {
            similarCardsContainer.innerHTML = '<p class="text-center text-gray-500 col-span-full">Hələlik heç bir oxşar elan tapılmadı.</p>';
        }

    } catch (error) {
        console.error("Əmlaklar yüklənərkən xəta baş verdi:", error);
        loader.classList.add('hidden');
        similarCardsContainer.innerHTML = '<p class="text-center text-red-500 col-span-full">Əmlaklar yüklənərkən xəta baş verdi.</p>';
    }
});