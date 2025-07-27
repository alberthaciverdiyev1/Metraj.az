import { propertyCard } from './cards/property.js'; // propertyCard funksiyasını import edin

document.addEventListener('DOMContentLoaded', () => {
    const favoritesContainer = document.getElementById('favoritesContainer'); // Sevimlilərin göstəriləcəyi HTML elementi
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];

    if (favorites.length === 0) {
        favoritesContainer.innerHTML = '<p class="text-center text-gray-500">Seçilmiş elan yoxdur.</p>';
        return;
    }

    let favoritesHtml = '';
    favorites.forEach(property => {
        // Hər bir sevimli əmlak üçün propertyCard funksiyasını çağırırıq
        favoritesHtml += propertyCard(property);
    });

    favoritesContainer.innerHTML = favoritesHtml;
});