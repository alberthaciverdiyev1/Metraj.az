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
        <div class="relative inline-block text-left">
            <!-- Dropdown trigger -->
            <button id="dropdownButton" class="flex items-center space-x-2 px-4 py-2 border rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-user text-gray-400"></i>
                <span>Themesflat</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Dropdown menu -->
            <div id="dropdownMenu" class="absolute left-0 mt-2 w-60 bg-white rounded-xl shadow-lg ring-1 ring-gray-200 hidden z-50">
                <ul class="py-2 text-sm text-gray-700">
                    <li><a href="#" class="flex rounded-md items-center px-4 py-2 hover:bg-orange-400 hover:text-white"><i class="bi bi-grid mr-3"></i> Dashboards</a></li>
                    <li><a href="#" class="flex  rounded-md items-center px-4 py-2 hover:bg-orange-400 hover:text-white"><i class="bi bi-person-gear mr-3"></i> My profile</a></li>
                    <li><a href="#" class="flex  rounded-md items-center px-4 py-2 hover:bg-orange-400 hover:text-white"><i class="bi bi-bag-check mr-3"></i> My package</a></li>
                    <li><a href="#" class="flex  rounded-md items-center px-4 py-2 hover:bg-orange-400 hover:text-white"><i class="bi bi-heart mr-3"></i> My favorites (1)</a></li>
                    <li><a href="#" class="flex  rounded-md items-center px-4 py-2 hover:bg-orange-400 hover:text-white"><i class="bi bi-folder2-open mr-3"></i> My save searches</a></li>
                    <li><a href="#" class="flex  rounded-md items-center px-4 py-2 hover:bg-orange-400 hover:text-white"><i class="bi bi-chat-left-text mr-3"></i> Reviews</a></li>
                    <li><a href="{{ route(name:'login') }}" class="flex  rounded-md items-center px-4 py-2 hover:bg-orange-400 hover:text-white"><i class="bi bi-folder-check mr-3"></i> My properties</a></li>
                    <li>
                        <a href="{{ route(name:'add-property') }}"
                            class="flex items-center px-4 py-2 bg-white  rounded-md hover:bg-orange-400 hover:text-white transition">
                            <i class="bi bi-file-earmark-plus mr-3"></i> Add property
                        </a>
                    </li>
                    <li><a href="{{ route('login') }}" class="flex items-center px-4 py-2 bg-white  rounded-md hover:bg-orange-400 hover:text-white transition"><i class="bi bi-person mr-3"></i> Login / Register</a></li>
                    <li><a href="#" class="flex items-center px-4 py-2 bg-white  rounded-md hover:bg-orange-400 hover:text-white transition"><i class="bi bi-box-arrow-right mr-3"></i> Logout</a></li>
                </ul>

            </div>
        </div>
        <button class="flex items-center px-4 py-2 bg-white  rounded-md hover:bg-orange-400 hover:text-white transition">Add property</button>
    </div>
    </div>
</header>


