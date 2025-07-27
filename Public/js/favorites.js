import { propertyCard } from "./cards/property.js";


const favoritesContainer = document.getElementById('favoritesContainer');
const clearAllFavoritesBtn = document.getElementById('clearAllFavoritesBtn');

function renderFavorites() {
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    
    if (favorites.length === 0) {
        favoritesContainer.innerHTML = '<p class="text-center text-gray-500 col-span-full">Seçilmiş elan yoxdur.</p>';
        clearAllFavoritesBtn.style.display = 'none'; 
        return;
    }

    let favoritesHtml = '';
    favorites.forEach(property => {
        favoritesHtml += propertyCard(property, true); 
    });

    favoritesContainer.innerHTML = favoritesHtml;
    clearAllFavoritesBtn.style.display = 'inline-flex'; 
}

if (clearAllFavoritesBtn) {
    clearAllFavoritesBtn.addEventListener('click', () => {
        if (confirm('Bütün sevimli elanları silmək istədiyinizə əminsinizmi?')) {
            localStorage.removeItem('favorites');
            renderFavorites();
            console.log('All favorites cleared!');
        }
    });
}

document.addEventListener('DOMContentLoaded', renderFavorites);