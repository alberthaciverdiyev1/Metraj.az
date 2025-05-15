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

    <section class="property-listing py-4">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-4xl font-bold text-[color:var(--text-color)]">Property listing</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-7">
                <div class="border border border-[color:var(--border-color)]  rounded-2xl overflow-hidden group relative transition-all duration-300">

                    <div class="relative overflow-hidden">
                        <img src="{{ asset('webui/images/box-house.jpg') }}"  alt="Elegant studio flat" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />

                        <div class="absolute inset-0 flex items-center justify-center overflow-hidden">
                            <div class="absolute inset-0 bg-black/40 transition-all duration-500 ease-in-out transform -translate-y-full opacity-0 group-hover:translate-y-0 group-hover:opacity-100 z-0"></div>

                            <div class="z-10 flex items-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <button class="bg-white p-3 rounded-full shadow hover:scale-110 transition">
                                    <i class="fas fa-heart text-[color:var(--primary)]"></i>
                                </button>
                                <button class="bg-white p-3 rounded-full shadow hover:scale-110 transition">
                                    <i class="fas fa-search-plus text-[color:var(--primary)]"></i>
                                </button>
                            </div>
                        </div>

                        <div class="absolute top-3 left-4 flex gap-2">
                            <span class="bg-[color:var(--primary)] text-white text-[14px] font-semibold px-3 py-1 rounded-full">Featured</span>
                            <span class="bg-[#80807F] text-white  font-semibold text-[14px] px-3 py-1 rounded-full">For Sale</span>
                        </div>
                    </div>

                    <div class="p-5">
                        <h3 class="text-xl font-bold text-[color:var(--text-color)] transition hover:text-[color:var(--primary)] mb-2">Elegant studio flat</h3>
                        <p class="text-[16px]  text-[color:var(--grey-text)]  flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-2 "></i>
                            102 Ingraham St, Brooklyn, NY 11237
                        </p>
                        <div class="flex items-center text-[16px] text-[color:#959699]  gap-4 mb-4">
                            <span><span class="text-[color:#2C2E33]">3</span> Beds</span>
                            <span><span class="text-[color:#2C2E33]">3</span> Baths</span>
                            <span><span  class="text-[color:#2C2E33]">4,043</span> Sqft</span>
                        </div>

                        <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)]  pt-4">
                            <span class="text-[color:var(--primary)] font-bold text-lg">$8.600</span>
                                <button class="flex items-center gap-1 text-[color:#2C2E33] hover:text-[color:var(--primary)]">
                                    <i class="fas fa-random"></i> Compare
                                </button>
                     <a href="#"
   class="relative inline-block px-6 py-2 rounded-xl border border-[color:var(--primary)] text-[color:var(--primary)] overflow-hidden transition-all duration-500 group">
  <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] group-hover:w-full transition-all duration-500 ease-in-out z-0"></span>
  <span class="relative z-10 transition-colors duration-500 group-hover:text-white">Details</span>
</a>


                        </div>
                    </div>
                </div>
              
            </div>
        </div>
    </section>
</main>
@endsection
