import {formatPrice} from '../helpers/price.js';

export function propertyCard(property) {
    const price = property.price 
        ? formatPrice(typeof property.price === 'string' 
            ? parseFloat(property.price.replace(/,/g, '')) 
            : property.price)
        : formatPrice(0);

        return `<div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
        <div class="relative overflow-hidden">
            <img src="${property.image}" alt="${property.title}" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />
            <div class="absolute inset-0 flex items-center justify-center overflow-hidden">
                <div class="absolute inset-0 bg-black/40 transition-all duration-500 ease-in-out transform -translate-y-full opacity-0 group-hover:translate-y-0 group-hover:opacity-100 z-0"></div>
                <div class="z-10 flex items-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    
                  
                </div>
            </div>
            <div class="absolute top-3 left-4 flex gap-2">
                <span class="bg-[color:var(--primary)] text-white text-[14px] font-semibold px-3 py-1 rounded-full">Kirayə</span>
                <span class="bg-[#80807F] text-white font-semibold text-[14px] px-3 py-1 rounded-full">Satışda</span>
            </div>
               <span class="absolute top-3 right-4 bg-red-400 text-white font-semibold text-[14px] px-3 py-1 rounded-full">Premium</span>
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
                <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$${property.price}</span>
                <button class="flex compare items-center gap-1 text-sm text-[#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                    <i class="fas fa-random"></i> Compare
                </button>
               
            </div>
        </div>
    </div>`
}