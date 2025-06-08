@extends('webui::layout')

@section('title', 'Home')
@section('content')
<x-go-top />
<x-settings-icon />
<section id="add-property">
    <div class="side">
        <div class="left-side">
            <aside>
                <ul class="py-2 text-base text-gray-800 space-y-1">
                    <li>
                        <a href="#" class="flex rounded-lg items-center px-5 py-4 hover:bg-orange-400 hover:text-white group transition">
                            <i class="bi bi-grid mr-3 text-gray-400 group-hover:text-white transition"></i> Dashboards
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex rounded-lg items-center px-5 py-4 hover:bg-orange-400 hover:text-white group transition">
                            <i class="bi bi-person-gear mr-3 text-gray-400 group-hover:text-white transition"></i> My profile
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex rounded-lg items-center px-5 py-4 hover:bg-orange-400 hover:text-white group transition">
                            <i class="bi bi-bag-check mr-3 text-gray-400 group-hover:text-white transition"></i> My package
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex rounded-lg items-center px-5 py-4 hover:bg-orange-400 hover:text-white group transition">
                            <i class="bi bi-heart mr-3 text-gray-400 group-hover:text-white transition"></i> My favorites (1)
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex rounded-lg items-center px-5 py-4 hover:bg-orange-400 hover:text-white group transition">
                            <i class="bi bi-folder2-open mr-3 text-gray-400 group-hover:text-white transition"></i> My save searches
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex rounded-lg items-center px-5 py-4 hover:bg-orange-400 hover:text-white group transition">
                            <i class="bi bi-chat-left-text mr-3 text-gray-400 group-hover:text-white transition"></i> Reviews
                        </a>
                    </li>
                    <li>
                        <a href="{{ route(name:'login') }}" class="flex rounded-lg items-center px-5 py-4 hover:bg-orange-400 hover:text-white group transition">
                            <i class="bi bi-folder-check mr-3 text-gray-400 group-hover:text-white transition"></i> My properties
                        </a>
                    </li>
                    <li>
                        <a href="{{ route(name:'add-property') }}" class="flex items-center px-5 py-4 bg-white rounded-lg hover:bg-orange-400 hover:text-white group transition">
                            <i class="bi bi-file-earmark-plus mr-3 text-gray-400 group-hover:text-white transition"></i> Add property
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="flex items-center px-5 py-4 bg-white rounded-lg hover:bg-orange-400 hover:text-white group transition">
                            <i class="bi bi-person mr-3 text-gray-400 group-hover:text-white transition"></i> Login / Register
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-5 py-4 bg-white rounded-lg hover:bg-orange-400 hover:text-white group transition">
                            <i class="bi bi-box-arrow-right mr-3 text-gray-400 group-hover:text-white transition"></i> Logout
                        </a>
                    </li>
                </ul>



            </aside>
        </div>

        <div class="right-side">
            <main>
                <section id="upload-image">
                    <div class="px-6  py-10 bg-white rounded-xl shadow-md max-w-5xl mx-auto">
                        <h2 class="text-3xl font-bold mb-4">Upload Media</h2>

                        <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg py-14 flex flex-col items-center justify-center text-center min-h-[340px] transition">
                            <label class="bg-[var(--primary)] text-white px-6 py-4 rounded-xl font-semibold hover:bg-orange-500 transition-all cursor-pointer mb-2">
                                <i class="bi bi-paperclip"></i> Select photos
                                <input id="fileInput" type="file" multiple accept="image/*" class="hidden">
                            </label>
                            <p class="text-sm text-gray-500">or drag photos here<br>(Up to 10 photos)</p>
                        </div>


                        <div id="gallery" class="mt-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>
                    </div>
                </section>
                <section id="property-information">
                    <div class="p-6 bg-white rounded-xl shadow-md max-w-5xl mx-auto mt-6">
                        <h2 class="text-2xl font-bold mb-4">Information</h2>

                        <form method="POST" action="">
                            @csrf

                            <div class="mb-4">
                                <label for="slug" class="block font-semibold mb-1">Title:*</label>
                                <input type="text" name="slug" id="slug" placeholder="Choose" class="w-full border border-gray-300 rounded-md px-4 py-2">
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block font-semibold mb-1">Description:</label>
                                <textarea name="description" id="description" placeholder="Your Description" rows="4" class="w-full border border-gray-300 rounded-md px-4 py-2"></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="address" class="block font-semibold mb-1">Full Address:*</label>
                                    <input type="text" name="address" id="address" placeholder="Enter property full address" class="w-full border border-gray-300 rounded-md px-4 py-2">
                                    <button type="button" id="searchAddress" class="mt-2 rounded-lg mt-2 bg-[var(--primary)] text-white px-4 py-1 rounded-hover:bg-blue-600">Search on Map</button>
                                </div>

                                <div>
                                    <label for="add_no" class="block font-semibold mb-1">Zip Code:*</label>
                                    <input type="text" name="add_no" id="add_no" placeholder="Enter property zip code" class="w-full border border-gray-300 rounded-md px-4 py-2">
                                </div>

                                <div>
                                    <label for="city_id" class="block font-semibold mb-1">Country:*</label>
                                    <select name="city_id" id="city_id" class="w-full border border-gray-300 rounded-md px-4 py-2">
                                        <option value="">Select Country</option>
                                        <option value="1">Azerbaijan</option>
                                        <option value="2">Other...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block font-semibold mb-1">Location on Map:</label>
                                <div id="map" style="height: 400px; width: 100%;  border-radius: 0.375rem;"></div>
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                            </div>

                            <div class="mb-4">
                                <label for="google_map_location" class="block font-semibold mb-1">Google Maps Link (optional):</label>
                                <input type="text" name="google_map_location" id="google_map_location" placeholder="https://maps.google.com/..." class="w-full border border-gray-300 rounded-md px-4 py-2">
                            </div>
                        </form>

                    </div>
                </section>
                <div class="bg-white mt-2 p-5 max-w-5xl mx-auto rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold mb-6">Additional Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                        <!-- Property Type -->
                        <!-- Property Type -->
                        <div>
                            <label class="block font-medium mb-1">Property Type:<span class="text-red-500">*</span></label>
                            <select class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option disabled selected>Choose</option>
                                <option>Villa</option>
                                <option>Office</option>
                            </select>
                        </div>

                        <!-- Property Status -->
                        <div>
                            <label class="block font-medium mb-1">Property Status:<span class="text-red-500">*</span></label>
                            <select class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option disabled selected>Choose</option>
                                <option>For Sale</option>
                                <option>For Rent</option>
                            </select>
                        </div>




                        <!-- Property Label -->
                        <div>
                            <label class="block font-medium mb-1">Property Label:<span class="text-red-500">*</span></label>
                            <select class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>Choose</option>
                                <!-- options -->
                            </select>
                        </div>

                        <!-- Size -->
                        <div>
                            <label class="block font-medium mb-1">Size (SqFt):<span class="text-red-500">*</span></label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Land Area -->
                        <div>
                            <label class="block font-medium mb-1">Land Area (SqFt):<span class="text-red-500">*</span></label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Property ID -->
                        <div>
                            <label class="block font-medium mb-1">Property ID:<span class="text-red-500">*</span></label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Rooms -->
                        <div>
                            <label class="block font-medium mb-1">Rooms:<span class="text-red-500">*</span></label>
                            <input type="number" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Bedrooms -->
                        <div>
                            <label class="block font-medium mb-1">Bedrooms:<span class="text-red-500">*</span></label>
                            <input type="number" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Bathrooms -->
                        <div>
                            <label class="block font-medium mb-1">Bathrooms:<span class="text-red-500">*</span></label>
                            <input type="number" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Garages -->
                        <div>
                            <label class="block font-medium mb-1">Garages:<span class="text-red-500">*</span></label>
                            <input type="number" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Garage Size -->
                        <div>
                            <label class="block font-medium mb-1">Garages Size (SqFt):<span class="text-red-500">*</span></label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Year Built -->
                        <div>
                            <label class="block font-medium mb-1">Year Built:<span class="text-red-500">*</span></label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                    </div>
                </div>
                <x-floor />
                <x-amenities-add-property />
                <x-agent-info />

                <div class="flex justify-center items-center space-x-4 mt-6">
                    <button class=" sm:w-auto bg-[color:var(--primary)] text-white px-6 py-3 rounded-xl flex items-center justify-center button-hover all-btn hover:bg-orange-500 focus:outline-none">
                        Add Property
                    </button>
                    <button class="relative inline-block px-5 py-2 rounded-xl border border-[color:var(--primary)] text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                        <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                        <span class="relative z-10">Save & Prewiev</span>
                    </button>

                </div>


            </main>
        </div>

    </div>
</section>



@endsection
