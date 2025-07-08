
import { formatPrice } from '../helpers/price.js';

export function propertyCard(property) {
    const price = property.price
        ? formatPrice(typeof property.price === 'string'
            ? parseFloat(property.price[0]?.price.replace(/,/g, ''))
            : property.price[0]?.price)
        : formatPrice(0);

    const premiumBadge = property.is_premium
        ? `<span class="absolute top-3 right-4 bg-red-400 text-white font-semibold text-sm px-2 py-1 rounded-full">Premium</span>` : '';
    const addTypeBadge =
        property.add_type === 'rent'
            ? `<span class="bg-[color:var(--primary)] text-white text-sm font-semibold px-2 py-1 rounded-full">Kirayə</span>`
            : property.add_type === 'sale'
                ? `<span class="bg-[#80807F] text-white font-semibold text-sm px-2 py-1 rounded-full">Satışda</span>`
                : '';

    return `
    <div onclick="window.location.href='/property/${property.id}'" class="cursor-pointer border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
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
        </div>

        <div class="p-5">
            <h3 class="font-bold text-lg sm:text-xl text-[color:var(--text-color)] transition hover:text-[color:var(--primary)] mb-2">
                ${property.title}
            </h3>
            <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                <i class="fas fa-map-marker-alt mr-2"></i>
                ${property.address}
            </p>
            <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[#959699] gap-4 mb-4">
                <span><span class="text-[#2C2E33]">${property.beds}</span> Beds</span>
                <span><span class="text-[#2C2E33]">${property.baths}</span> Baths</span>
                <span><span class="text-[#2C2E33]">${property.area}</span> Sqft</span>
            </div>
            <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">${price} AZN</span>
                <button onclick="event.stopPropagation()" class="flex compare items-center gap-1 text-sm text-[#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                    <i class="fas fa-random"></i> Compare
                </button>
            </div>
        </div>
    </div>`;
}