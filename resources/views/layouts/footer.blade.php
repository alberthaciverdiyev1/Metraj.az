<footer class="bg-neutral-950 text-neutral-400 mt-20 border-t border-neutral-900">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-6">
                <a href="/" class="flex items-center space-x-2.5">
                    <img class="h-9 w-auto object-contain brightness-0 invert" src="/images/metrajlogo1.png" alt="Metraj" />
                    <div class="leading-tight">
                        <div class="text-xl font-extrabold text-white tracking-tight">Metraj.az</div>
                        <div class="text-[8px] text-orange-500 underline underline-offset-4 uppercase tracking-[0.15em] font-bold">sənin əmlakın</div>
                    </div>
                </a>
                <p class="text-sm text-neutral-400 leading-relaxed max-w-sm">
                    {{ __('Kiprin ən böyük əmlak elanları platforması. Ev, villa, torpaq sahəsi və kommersiya obyektlərinin alqı-satqısı və kirayəsi üçün ən doğru ünvan.') }}
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
                <h4 class="text-sm font-bold text-white uppercase tracking-wider">{{ __('Keçidlər') }}</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="/" class="hover:text-white hover:underline transition">{{ __('Ana Səhifə') }}</a></li>
                    <li><a href="/listing" class="hover:text-white hover:underline transition">{{ __('Elanlar') }}</a></li>
                    <li><a href="/otaq-yoldasi" class="hover:text-white hover:underline transition">{{ __('Otaq Yoldaşı') }}</a></li>
                    <li><a href="/agencies" class="hover:text-white hover:underline transition">{{ __('Agentliklər') }}</a></li>
                    <li><a href="/blog" class="hover:text-white hover:underline transition">{{ __('Bloq') }}</a></li>
                    <li><a href="/about-us" class="hover:text-white hover:underline transition">{{ __('Haqqımızda') }}</a></li>
                    <li><a href="/faq" class="hover:text-white hover:underline transition">{{ __('FAQ') }}</a></li>
                    <li><a href="/contact" class="hover:text-white hover:underline transition">{{ __('Əlaqə') }}</a></li>
                </ul>
            </div>

            <!-- Column 3: Locations -->
            <div class="space-y-4">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider">{{ __('Populyar Bölgələr') }}</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="/listing?cityId=1" class="hover:text-white hover:underline transition">{{ __('Girne') }}</a></li>
                    <li><a href="/listing?cityId=2" class="hover:text-white hover:underline transition">{{ __('Lefkoşa') }}</a></li>
                    <li><a href="/listing?cityId=3" class="hover:text-white hover:underline transition">{{ __('Gazimağusa') }}</a></li>
                    <li><a href="/listing?cityId=4" class="hover:text-white hover:underline transition">{{ __('İskele') }}</a></li>
                    <li><a href="/listing?cityId=5" class="hover:text-white hover:underline transition">{{ __('Güzelyurt') }}</a></li>
                </ul>
            </div>

            <!-- Column 4: Contact -->
            <div class="space-y-4">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider">{{ __('Əlaqə & Dəstək') }}</h4>
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
                        <span>{{ __('Girne, Şimali Kipr') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-neutral-900 my-12">

        <!-- Bottom Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <div>
                &copy; {{ date('Y') }} <span class="text-white font-semibold">Metraj.az</span> — {{ __('Bütün hüquqlar qorunur.') }}
            </div>
            <div class="flex items-center space-x-6">
                <a href="#" class="hover:text-white transition">{{ __('İstifadəçi Razılaşması') }}</a>
                <a href="#" class="hover:text-white transition">{{ __('Məxfilik Siyasəti') }}</a>
            </div>
        </div>
    </div>
</footer>
