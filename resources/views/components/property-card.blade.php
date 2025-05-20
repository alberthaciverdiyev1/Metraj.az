@props(['id', 'image', 'title', 'address', 'beds', 'baths', 'area', 'price'])
<div {{ $attributes->merge(['class' => 'border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300']) }}>

    <div class="relative overflow-hidden">
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />
        <div class="absolute inset-0 flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-black/40 transition-all duration-500 ease-in-out transform -translate-y-full opacity-0 group-hover:translate-y-0 group-hover:opacity-100 z-0"></div>
            <div class="z-10 flex items-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                <button title="add favorites" class="bg-white w-10 h-10 flex items-center justify-center rounded-full shadow hover:bg-[color:var(--text-color)] hover:text-white transition">
                    <i class="bi bi-bookmark text-[color:var(--primary)] hover:text-white"></i>
                </button>
                <button title="save as" class="bg-white w-10 h-10 flex items-center justify-center rounded-full shadow hover:bg-[color:var(--text-color)] hover:text-white transition">
                    <i class="fas fa-search-plus text-[color:var(--primary)] hover:text-white"></i>
                </button>
            </div>
        </div>

        <div class="absolute top-3 left-4 flex gap-2">
            <span class="bg-[color:var(--primary)] text-white text-[14px] font-semibold px-3 py-1 rounded-full">Featured</span>
            <span class="bg-[#80807F] text-white font-semibold text-[14px] px-3 py-1 rounded-full">For Sale</span>
        </div>
    </div>

    <div class="p-5">
        <h3 class="font-bold text-lg sm:text-xl text-[color:var(--text-color)] transition hover:text-[color:var(--primary)] mb-2">
            {{ $title }}
        </h3>
        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
            <i class="fas fa-map-marker-alt mr-2"></i>
            {{ $address }}
        </p>
        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
            <span><span class="text-[color:#2C2E33]">{{ $beds }}</span> Beds</span>
            <span><span class="text-[color:#2C2E33]">{{ $baths }}</span> Baths</span>
            <span><span class="text-[color:#2C2E33]">{{ $area }}</span> Sqft</span>
        </div>

        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">${{ $price }}</span>
            <button class="flex compare items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                <i class="fas fa-random"></i> Compare
            </button>
            <a href="{{ route('property.detail', ['id' => $id]) }}" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                <span class="relative z-10">Details</span>
            </a>

        </div>
    </div>
</div>