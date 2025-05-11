@extends('webui::layout')
@section('content')
    <!-- Hero -->
    <section class="relative bg-cover bg-center bg-no-repeat "
             style="background-image: url({{asset('webui/images/background.jpg')}})">
        <div class="bg-black/30 h-[700px]">
            <div class="container mx-auto px-4 py-48 text-center text-white">
                <h1 class="text-4xl md:text-7xl font-bold mb-8">Search Luxury Homes</h1>
                <p class="text-xl mb-12">Thousands of luxury home enthusiasts just like you visit our website.</p>

                <!-- Search Box -->
                <div
                    class="bg-white rounded-2xl shadow-lg p-3 flex flex-col md:flex-row items-center justify-between max-w-3xl mx-auto space-y-4 md:space-y-0 md:space-x-4">
                    <select class="border-none rounded-md px-4 py-2 text-gray-700 font-bold focus:outline-none">
                        <option>For sale</option>
                        <option>For rent</option>
                    </select>
                    <input type="text" placeholder="Place, neighborhood, school or agent..."
                           class="flex-1 border-none rounded-md px-4 py-2 w-full focus:outline-none text-gray-700"/>
                    <button class="bg-orange-300 text-white px-4 py-4 rounded-xl flex items-center">
                        <i class="fa-solid fa-sliders"></i>
                    </button>
                    <button class="bg-orange-400 text-white px-8 py-3 rounded-lg hover:bg-orange-600 flex items-center">
                        Search
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 21l-4.35-4.35m2.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </section>

@endsection
