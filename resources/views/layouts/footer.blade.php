<footer class="bg-neutral-950 text-neutral-400 mt-20 border-t border-neutral-900">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-6">
                <a href="{{ route('home') }}" class="flex items-center space-x-2.5">
                    <img class="h-9 w-auto object-contain brightness-0 invert" src="{{ asset('images/metrajlogo1.png') }}" alt="Metraj" />
                    <div class="leading-tight">
                        <div class="text-xl font-bold text-white tracking-tight">Metraj.az</div>
                        <div class="text-[8px] text-orange-500 underline underline-offset-4 uppercase tracking-[0.15em] font-semibold">{{ __('footer.tagline') }}</div>
                    </div>
                </a>
                <p class="text-sm text-neutral-400 leading-relaxed max-w-sm">
                    {{ __('footer.description') }}
                </p>
                <div class="flex items-center space-x-4 pt-2">
                    <a href="#" class="w-10 h-10 rounded-xl bg-neutral-900 text-neutral-400 hover:text-white hover:bg-orange-500 flex items-center justify-center border border-neutral-800 transition duration-300 shadow-sm">
                        <i class="bi bi-facebook text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-neutral-900 text-neutral-400 hover:text-white hover:bg-orange-500 flex items-center justify-center border border-neutral-800 transition duration-300 shadow-sm">
                        <i class="bi bi-instagram text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-neutral-900 text-neutral-400 hover:text-white hover:bg-orange-500 flex items-center justify-center border border-neutral-800 transition duration-300 shadow-sm">
                        <i class="bi bi-linkedin text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-neutral-900 text-neutral-400 hover:text-white hover:bg-orange-500 flex items-center justify-center border border-neutral-800 transition duration-300 shadow-sm">
                        <i class="bi bi-youtube text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Column 2: Navigation -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider">{{ __('footer.quick_links') }}</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white hover:underline transition">{{ __('footer.home') }}</a></li>
                    <li><a href="{{ route('listing') }}" class="hover:text-white hover:underline transition">{{ __('footer.listings') }}</a></li>
                    <li><a href="{{ route('requests.index') }}" class="hover:text-white hover:underline transition">{{ __('footer.requests') }}</a></li>
                    <li><a href="{{ route('agencies.list') }}" class="hover:text-white hover:underline transition">{{ __('footer.agencies') }}</a></li>
                    <li><a href="{{ route('blog.list') }}" class="hover:text-white hover:underline transition">{{ __('footer.blog') }}</a></li>
                    <li><a href="{{ route('about-us') }}" class="hover:text-white hover:underline transition">{{ __('footer.about_us') }}</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-white hover:underline transition">{{ __('footer.faq') }}</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white hover:underline transition">{{ __('footer.contact') }}</a></li>
                </ul>
            </div>

            <!-- Column 3: Locations -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider">{{ __('footer.popular_regions') }}</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('listing.path1', ['first' => 'girne']) }}" class="hover:text-white hover:underline transition">{{ __('footer.girne') }}</a></li>
                    <li><a href="{{ route('listing.path1', ['first' => 'lefkosa']) }}" class="hover:text-white hover:underline transition">{{ __('footer.lefkosa') }}</a></li>
                    <li><a href="{{ route('listing.path1', ['first' => 'gazimagusa']) }}" class="hover:text-white hover:underline transition">{{ __('footer.gazimagusa') }}</a></li>
                    <li><a href="{{ route('listing.path1', ['first' => 'iskele']) }}" class="hover:text-white hover:underline transition">{{ __('footer.iskele') }}</a></li>
                    <li><a href="{{ route('listing.path1', ['first' => 'guzelyurt']) }}" class="hover:text-white hover:underline transition">{{ __('footer.guzelyurt') }}</a></li>
                </ul>
            </div>

            <!-- Column 4: Contact -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider">{{ __('footer.contact_support') }}</h4>
                <ul class="space-y-3.5 text-sm">
                    <li class="flex items-start space-x-3">
                        <i class="bi bi-telephone text-orange-500 mt-0.5 text-base"></i>
                        <span class="text-neutral-300 font-semibold">+90 (548) 888-8888</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <i class="bi bi-envelope text-orange-500 mt-0.5 text-base"></i>
                        <a href="mailto:info@metraj.az" class="hover:text-white transition">info@metraj.az</a>
                    </li>
                    <li class="flex items-start space-x-3">
                        <i class="bi bi-geo-alt text-orange-500 mt-0.5 text-base"></i>
                        <span>{{ __('footer.location_address') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-neutral-900 my-12">

        <!-- Bottom Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <div>
                &copy; {{ date('Y') }} <span class="text-white font-semibold">Metraj.az</span> — {{ __('footer.all_rights_reserved') }}
            </div>
            <div class="flex items-center space-x-6">
                <a href="#" class="hover:text-white transition">{{ __('footer.user_agreement') }}</a>
                <a href="#" class="hover:text-white transition">{{ __('footer.privacy_policy') }}</a>
            </div>
        </div>
    </div>
</footer>
