@extends('webui::layout')
@section('content')
<x-settings-icon />
<x-scroll-to-top />


<main>
    <section id="hero" class="relative bg-cover bg-fixed bg-center py-20" style="background-image: url('{{ asset('webui/images/bg-hero.jpg') }}')">
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="container mx-auto px-5 my-[100px] text-left text-white relative z-10">
            <h1 class="lg:text-6xl md:text-4xl text-xl font-bold mb-4">Your Way Home Starts Here</h1>
            <p class="md:text-md mb-8 mt-5">Thousands of luxury home enthusiasts just like you visit our website</p>

            <div class="flex space-x-4 mt-8" id="toggleButtons">
                <div class="relative group">
                    <button data-type="rent" class="toggle-btn px-6 py-3 bg-red-400 text-sm text-white font-semibold rounded-[16px] focus:outline-none">
                        For Rent
                    </button>
                    <div class="triangle-indicator absolute left-1/2 -translate-x-1/2 -bottom-1 w-0 h-0 border-l-8 border-r-8 border-t-8 border-l-transparent border-r-transparent border-t-red-400"></div>
                </div>

                <div class="relative group">
                    <button data-type="sale" class="text-sm toggle-btn px-6 py-3 bg-white text-black font-semibold rounded-[16px] border border-gray-300 hover:bg-gray-100 hover:text-black">
                        For Sale
                    </button>
                    <div class="triangle-indicator hidden absolute left-1/2 -translate-x-1/2 -bottom-1 w-0 h-0 border-l-8 border-r-8 border-t-8 border-l-transparent border-r-transparent border-t-red-400"></div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 mt-8 w-[100%]">
                <div class="flex flex-col sm:flex-row flex-wrap items-center gap-3 md:gap-4">
                    <input type="text" placeholder="Address, City, ZIP..."
                        class="px-4 py-3 rounded-xl w-full sm:flex-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-300 text-black order-1" />

                    <div class="relative w-full sm:w-auto md:w-[170px] lg:w-[190px] order-2 select-wrapper">
                        <select class="custom-select px-4 py-3 rounded-xl w-full border bg-gray-50 border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-300 text-black appearance-none">
                            <option value="">Location</option>
                            <option value="tx">Texas</option>
                            <option value="fl">Florida</option>
                        </select>
                        <div class="select-arrow absolute inset-y-0 right-0 flex items-center px-3 text-gray-700 pointer-events-none">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative w-full sm:w-auto md:w-[170px] lg:w-[190px] order-3 select-wrapper">
                        <select class="custom-select px-4 py-3 rounded-xl w-full border bg-gray-50 border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-300 text-black appearance-none">
                            <option value="">Province / State</option>
                            <option value="ca">California</option>
                            <option value="ny">New York</option>
                        </select>
                        <div class="select-arrow absolute inset-y-0 right-0 flex items-center px-3 text-gray-700 pointer-events-none">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto order-4">
                        <button id="filterButton" class="bg-orange-300 text-white px-5 py-4 rounded-xl flex items-center justify-center flex-shrink-0 hover:bg-orange-400 focus:outline-none">
                            <i class="fa-solid fa-sliders"></i>
                        </button>
                        <button class="w-full sm:w-auto bg-orange-400 text-white px-8 py-3 rounded-lg flex items-center justify-center button-hover all-btn hover:bg-orange-500 focus:outline-none">
                            Search
                            <i class="bi ml-1 bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div id="filterModal" class="text-black absolute z-50 mt-2 right-0 w-[100%] p-6 bg-white rounded-2xl shadow-xl 
                                         opacity-0 -translate-y-3 invisible 
                                         transition-all duration-300 ease-out">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="w-full md:w-1/2">
                            <p class="font-semibold">Price range from <span class="text-red-500">$100</span> to <span class="text-red-500">$500,000</span></p>
                            <input
                                type="range"
                                class="w-full accent-orange-500 bg-gray-300 h-1 rounded-md"
                                step="10000"
                                min="100"
                                max="500000">
                        </div>
                        <div class="w-full md:w-1/2">
                            <p class="font-semibold">Size range from <span class="text-red-500">0 m²</span> to <span class="text-red-500">1,000 m²</span></p>
                            <input
                                type="range"
                                class="w-full accent-orange-500 bg-gray-300 h-1 rounded-md"
                                step="50"
                                min="0"
                                max="1000">
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <select class="border border-gray-300 rounded-xl px-4 py-2 w-full bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option>Province / States</option>
                        </select>
                        <select class="border border-gray-300 rounded-xl px-4 py-2 w-full bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option>Rooms</option>
                        </select>
                        <select class="border border-gray-300 rounded-xl px-4 py-2 w-full bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option>Bath: Any</option>
                        </select>
                        <select class="border border-gray-300 rounded-xl px-4 py-2 w-full bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option>Beds: Any</option>
                        </select>
                    </div>
                    <div class="mt-6">
                        <p class="text-gray-500 text-sm font-medium mb-2">Amenities:</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-x-4 gap-y-2">
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Bed linens</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Carbon alarm</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Check-in lockbox</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Extra pillows</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> First aid kit</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Hangers</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Refrigerator</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Security cameras</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Smoke alarm</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Coffee maker</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Dishwasher</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Microwave</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Iron</label>
                            <label class="flex items-center text-sm"><input type="checkbox" class="mr-2 accent-orange-400"> Fireplace</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="luxury-listings">

        <div class="container mx-auto px-3">
            <x-title
                title="Today’s Luxury Listings"
                subtitle="Thousands of luxury home enthusiasts just like you visit our website." />

            <div class="luxury-listings">
                <div id="propertyContainer" class="pt-8 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-7">

                    @foreach ($properties->take(6) as $property)
                    <x-property-card
                        :id="$property['id']"
                        :image="$property['image']"
                        :title="$property['title']"
                        :address="$property['address']"
                        :beds="$property['beds']"
                        :baths="$property['baths']"
                        :area="$property['area']"
                        :price="$property['price']" />

                    @endforeach


                </div>
            </div>

        </div>

    </section>

    <section id="discover-how-we-can-help">
        <div class="container">
            <div class="discover-how-we-can-help">
                   <x-title
                title="Discover how we can help"
                subtitle="Thousands of luxury home enthusiasts just like you visit our website." />


            </div>
        </div>
        

    </section>



</main>




@endsection