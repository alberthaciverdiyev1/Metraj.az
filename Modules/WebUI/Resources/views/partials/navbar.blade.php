<header class="bg-white shadow-md">
    <div class=" mx-auto container px-4 py-4 flex flex-wrap items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <div class="bg-orange-400 p-2 rounded-full text-white">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C8.13 2 5 5.13 5 9c0 6 7 13 7 13s7-7 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
                </svg>
            </div>
            <span class="font-bold text-lg text-gray-800">PROTY <span
                    class="text-sm text-gray-500">Real Estate</span></span>
        </div>

        <!-- Nav -->
        <nav class="hidden md:flex space-x-7 items-center">
            <a href="{{ route('home') }}" class="text-orange-400 font-bold">Home</a>
            <a href="{{ route('listing') }}" class="">Listing</a>
            <a href="{{ route('agencies') }}" class="text-gray-700">Agencies</a>
            <a href="{{ route('blog') }}" class="text-gray-700">Blog</a>
            <a href="{{ route('contact') }}" class="text-gray-700">Contact</a>
            <a href="{{ route('faqs') }}" class="text-gray-700">FAQS</a>
            <a href="{{ route('comingSoon') }}" class="text-gray-700">Coming Soon</a>
        </nav>

        <!-- Right -->
        <div class="flex items-center space-x-4">
            <button class="hidden md:inline-flex items-center space-x-1 text-gray-700">
                <i class="fas fa-user text-gray-400 border px-4 py-3 rounded-lg border-orange-400"></i>
                <span>Themesflat</span>
            </button>
            <button class="border border-orange-400 text-black rounded-lg hover:text-white px-4 py-2 rounded-md hover:bg-orange-400">Add property</button>
        </div>
    </div>
</header>