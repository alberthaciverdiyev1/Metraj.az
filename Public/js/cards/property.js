import { formatPrice } from '../helpers/price.js';

function getFavoriteStatus(propertyId) {
    const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    return favorites.some(favProperty => favProperty.id === propertyId);
}

export function propertyCard(property, showRemoveButton = false) {
    const rawPrice = property.price && property.price[0]?.price;
    let priceValue = 0;

    if (rawPrice) {
        priceValue = parseFloat(rawPrice.toString().replace(/,/g, ''));
    }

    const price = formatPrice(priceValue);

    const premiumBadge = property.is_premium ? `<span class="absolute top-3 right-4 text-black font-semibold text-md bg-white px-2 py-1 rounded-full">
    <i class="fa-solid fa-crown"></i>
  </span>` : '';
    const addTypeBadge = property.add_type === 'rent' ? `<span class="bg-[color:var(--primary)] text-white text-sm font-semibold px-2 py-1 rounded-full">Kirayə</span>` : property.add_type === 'sale' ? `<span class="bg-[#80807F] text-white font-semibold text-sm px-2 py-1 rounded-full">Satışda</span>` : '';

    const isFavorite = getFavoriteStatus(property.id);
    const heartIconClass = isFavorite ? 'fa-solid' : 'fa-regular';

    const propertyData = encodeURIComponent(JSON.stringify(property));

    const favoriteOrRemoveButton = showRemoveButton ? `
        <span onclick="event.stopPropagation(); removeFavorite(${property.id});" class="absolute bottom-3 right-4 text-red-500 font-semibold text-md bg-white px-2 py-1 rounded-full cursor-pointer">
            <i class="fas fa-times"></i>
        </span>
    ` : `
        <span onclick="event.stopPropagation(); toggleFavorite(this, decodeURIComponent('${propertyData}'));" class="absolute bottom-3 right-4 text-white font-semibold text-md bg-white px-2 py-1 rounded-full cursor-pointer">
            <i class="${heartIconClass} fa-heart text-red-500"></i>
        </span>
    `;

    return `
        <div data-property-id="${property.id}" class="cursor-pointer border border-[color:var(--border-color)] rounded-2md overflow-hidden rounded-2xl group relative transition-all duration-300">
            <div class="relative overflow-hidden">
                <img src="${property.media.path}" alt="${property.title}" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />
                <div class="absolute inset-0 flex items-center justify-center overflow-hidden">
                    <div class="absolute inset-0 bg-black/40 transition-all duration-500 ease-in-out transform -translate-y-full opacity-0 group-hover:translate-y-0 group-hover:opacity-100 z-0"></div>
                    <div class="z-10 flex items-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
                <div class="absolute top-3 left-4 flex gap-1">
                    ${addTypeBadge}
                </div>
                ${premiumBadge}

                ${favoriteOrRemoveButton}
                
            </div>

            <div class="p-5">
                <h3 class="font-bold text-lg sm:text-md text-[color:var(--text-color)] transition hover:text-[color:var(--primary)] mb-2">
                    ${property.title}
                </h3>
              
                <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    ${property.address}
                </p>
                
                <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[#959699] gap-4 mb-4">
                    <span><span class="text-[#2C2E33]">${property.beds}</span> Yataq</span>
                    <span><span class="text-[#2C2E33]">${property.baths}</span> Hamam</span>
                    <span><span class="text-[#2C2E33]">${property.area}</span> Kvm</span>
                </div>
                <span class="text-sm text-gray-500 ">AdNo: ${property.adNo}</span>
                        
                <div class="flex justify-between py-2 mt-2 items-center border-t border-[color:var(--border-color)] pt-4">
                    <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">${price} AZN</span>
                    <button onclick="event.stopPropagation()" class="flex compare items-center gap-1 text-sm text-[#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                        <i class="fas fa-random"></i> Müqayisə
                    </button>
                </div>
            </div>
        </div>`;
}

window.toggleFavorite = function(element, propertyJsonString) {
    const icon = element.querySelector('i');
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    
    const property = JSON.parse(propertyJsonString);

    const isAlreadyFavorite = favorites.some(favProperty => favProperty.id === property.id);

    if (!isAlreadyFavorite) {
        icon.classList.remove('fa-regular');
        icon.classList.add('fa-solid');
        favorites.push(property);
    } else {
        icon.classList.remove('fa-solid');
        icon.classList.add('fa-regular');
        favorites = favorites.filter(favProperty => favProperty.id !== property.id);
    }
    localStorage.setItem('favorites', JSON.stringify(favorites));
    console.log('Favorites updated:', favorites);

    if (window.location.pathname === '/favorites' || window.location.pathname === '/favorites.html') {
        if (typeof renderFavorites === 'function') {
            renderFavorites();
        }
    }
};

window.removeFavorite = function(propertyId) {
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    favorites = favorites.filter(favProperty => favProperty.id !== propertyId);
    localStorage.setItem('favorites', JSON.stringify(favorites));
    console.log('Removed favorite:', propertyId, 'New list:', favorites);
    
    const cardToRemove = document.querySelector(`[data-property-id="${propertyId}"]`);
    if (cardToRemove) {
        cardToRemove.remove();
    }

    if (favorites.length === 0) {
        const favoritesContainer = document.getElementById('favoritesContainer');
        if (favoritesContainer) {
            favoritesContainer.innerHTML = '<p class="text-center text-gray-500 col-span-full">Seçilmiş elan yoxdur.</p>';
            const clearAllFavoritesBtn = document.getElementById('clearAllFavoritesBtn');
            if (clearAllFavoritesBtn) {
                clearAllFavoritesBtn.style.display = 'none';
            }
        }
    }
};