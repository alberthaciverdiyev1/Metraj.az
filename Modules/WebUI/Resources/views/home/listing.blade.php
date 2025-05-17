@extends('webui::layout')

@section('title', 'Home')

@section('content')
<main>
    <section id="navigation" class="py-3">
        <div class="container mx-auto px-4 flex items-center gap-2">
            <a href="{{ route('home') }}" class="text-[color:var(--primary)] font-bold flex items-center hover:text-black">
                Home
            </a>
            <span class="text-gray-400">›</span>
            <a href="{{ route('listing') }}" class="text-gray-600">
                Property Listing
            </a>
        </div>
    </section>

    <section id="settings-icon">
        <div class="container mx-auto px-4">
            <div class="settings-icon">
                <i class="bi bi-gear"></i>
            </div>
        </div>
    </section>

    <div class="gotop" id="scrollToTop">
        <svg class="progress-circle" width="45" height="45">
            <circle class="bg" cx="22.5" cy="22.5" r="18"></circle>
            <circle class="progress" cx="22.5" cy="22.5" r="18"></circle>
        </svg>
        <div class="up">
            <i class="bi bi-arrow-up-short"></i>
        </div>
    </div>

    <section class="property-listing py-4">
        <div class="container mx-auto px-4">
            <div class="flex  flex-col lg:flex-row justify-between gap-4 mb-5">
                <h2 class="text-2xl lg:text-4xl font-bold text-[color:var(--text-color)]">
                    Property listing
                </h2>

                <div class="flex flex-wrap items-center gap-2">
                    <button class="flex items-center gap-1 border border-[var(--border-color)] rounded-lg px-3 py-2 hover:text-[color:var(--primary)] transition">
                        Filter
                        <i class="fas fa-sliders-h text-[color:var(--primary)]"></i>
                    </button>

                    <button class="bg-[color:var(--primary)] text-white px-3 py-2 rounded-md">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>

                    <button class="px-3 py-2 border border-[var(--border-color)] rounded-md">
                        <i class="fas fa-list text-[color:var(--icon-grey)] hover:text-[color:var(--primary)]"></i>
                    </button>

                    <div class="relative">
                        <button class="flex items-center border border-[var(--border-color)] px-4 py-2 rounded-md">
                            Sort by (Default) <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-8 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-7">
                <!-- Property Card 1 -->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Property Card 2 -->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house-2.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Property Card 3 -->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house-3.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--Pro  card-4-->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house-4.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--Pro  card-4-->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house-5.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--Pro  card-6-->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house-6.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--Pro  card-4-->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house-2.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--Pro  card-4-->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house-4.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--Pro  card-4-->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house-4.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--Pro  card-4-->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house-4.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--Pro  card-4-->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house-4.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--Pro  card-4-->
                <div class="border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house-4.jpg') }}" alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

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
                            Elegant studio flat
                        </h3>
                        <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-sm sm:text-base md:text-[16px] text-[color:#959699] gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">$8.600</span>
                            <button class="flex items-center gap-1 text-sm text-[color:#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                                <i class="fas fa-random"></i> Compare
                            </button>
                            <a href="#" class="relative inline-block px-4 py-2 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                <span class="relative z-10">Details</span>
                            </a>
                        </div>
                    </div>
                </div>


            </div>
            <div class="result">
                <div class="text">
                    Showing 1-9 of 12 results.
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active" aria-current="page"><a class="page-link" href="#">2</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                        <li class="page-item"><a class="page-link" href="#">20</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
</main>
@endsection