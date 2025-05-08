@extends('webui::layout')
@section('content')
    <!-- Hero -->
    <section class="relative bg-cover bg-center bg-no-repeat" style="background-image: url({{asset('webui/images/background.jpg')}})">
        <div class="bg-black/30">
            <div class="container mx-auto px-4 py-24 text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Search Luxury Homes</h1>
                <p class="text-lg mb-8">Thousands of luxury home enthusiasts just like you visit our website.</p>

                <!-- Search Box -->
                <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col md:flex-row items-center justify-between max-w-3xl mx-auto space-y-4 md:space-y-0 md:space-x-4">
                    <select class="border border-gray-300 rounded-md px-4 py-2 text-gray-700">
                        <option>For sale</option>
                        <option>For rent</option>
                    </select>
                    <input type="text" placeholder="Place, neighborhood, school or agent..." class="flex-1 border border-gray-300 rounded-md px-4 py-2 w-full" />
                    <button class="bg-orange-400 text-white px-6 py-2 rounded-md hover:bg-orange-600 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 21l-4.35-4.35m2.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>
                </div>
            </div>
        </div>
    </section>

@endsection
