@auth
    <div class="relative flex items-center min-h-screen justify-center bg-cover bg-center"
         style="background-image: url('/images/login-bg.svg');">
        <div class="absolute inset-0 bg-[#F1913D] opacity-80"></div>
        <div class="relative z-10 text-center px-4">
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full">
                <img src="/images/metrajlogo.png" class="w-40 mx-auto mb-6" alt="Metraj.az">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ __('Xoş gəldiniz') }}!</h2>
                <p class="text-gray-500 mb-6">{{ auth()->user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}" class="js-logout">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-xl transition duration-300">
                        <i class="bi bi-box-arrow-right mr-2"></i>{{ __('Logout') }}
                    </button>
                </form>
                <a href="/" class="block mt-4 text-sm text-orange-400 hover:underline">{{ __('Ana sehifeye qayit') }}</a>
            </div>
        </div>
    </div>
@else

@extends('layouts.app')

@section('content')
<div class="relative flex items-center min-h-screen justify-center bg-cover bg-center py-4 px-4 md:px-0"
     style="background-image: url('/images/login-bg.svg');">
    <div class="absolute inset-0 bg-[#F1913D] opacity-80"></div>

    <div class="flex flex-col md:flex-row justify-center md:w-[85%] w-full items-center gap-[30px] xl:gap-[20px] lg:gap-[15px] relative z-10">

        {{-- Left: brand & illustration --}}
        <div class="hidden md:flex flex-col items-center justify-center gap-[20px] md:gap-[10px]">
            <h1 class="text-white text-[26px] font-bold xl:text-[40px] xl:w-[340px] text-center mb-4 lg:text-[30px] md:text-[22px] md:w-[200px] lg:w-[250px]">
                Find your ideal house
            </h1>
            <img src="/images/illustation.svg" class="w-[180px] xl:w-[250px] lg:w-[200px] md:w-[150px]" alt="illustration">
        </div>

        {{-- Form --}}
        <div class="w-full md:w-1/3 p-6 md:p-8 bg-white rounded-[15px] shadow-lg">
            {{-- Logo on mobile --}}
            <img src="/images/metrajlogo.png" class="w-32 mx-auto mb-4 md:hidden" alt="Metraj.az">

            <h2 class="text-2xl font-semibold text-gray-800 mx-auto">Sign in</h2>

            <form id="login-form" class="space-y-0" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-with-icon mt-[10px]">
                    <input type="email" name="email" id="email" required placeholder="Mail address or username"
                        class="w-full border rounded-xl px-4 py-3 pl-12 text-sm md:text-base">
                    <span id="email-error" class="text-red-500 text-sm block mt-1"></span>
                </div>

                <div class="input-with-icon mt-[10px]">
                    <input type="password" name="password" id="password" required placeholder="Password"
                        class="w-full border rounded-xl px-4 py-3 pl-12 text-sm md:text-base">
                    <span id="password-error" class="text-red-500 text-sm block mt-1"></span>
                </div>

                <button type="submit" id="login-btn"
                    class="w-full bg-[var(--primary)] hover:bg-orange-600 text-white font-semibold py-4 rounded-xl transition duration-300 mt-4 text-sm md:text-base">
                    Log In
                </button>
            </form>

            <div class="flex items-center justify-center my-6">
                <hr class="flex-1 border-gray-300">
                <span class="mx-2 text-gray-500 text-sm text-center whitespace-nowrap">Also you can log in with</span>
                <hr class="flex-1 border-gray-300">
            </div>

            <div class="flex justify-between w-[70%] mx-auto">
                <a href="#"><img src="/images/devicon_google.svg" alt="google"></a>
                <a href="#"><img src="/images/logos_facebook.svg" alt="facebook"></a>
                <a href="#"><img src="/images/logos_apple.svg" alt="apple"></a>
                <a href="#"><img src="/images/logos_telegram.svg" alt="telegram"></a>
            </div>

            <div class="text-center mt-4">
                <a href="/" class="text-sm text-orange-400 hover:underline">{{ __('Ana sehifeye qayit') }}</a>
            </div>
        </div>

        {{-- Right: tagline & illustration --}}
        <div class="hidden md:flex flex-col items-center justify-center text-center">
            <h1 class="text-white text-[26px] font-bold xl:text-[40px] xl:w-[400px] mb-4 lg:text-[30px] md:text-[22px] md:w-[220px] lg:w-[300px]">
                Easiest way to find your safezone
            </h1>
            <img src="/images/ok-finger.svg" alt="ok-icon" class="w-[180px] xl:w-[250px] lg:w-[200px] md:w-[150px]">
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="/js/pages/auth/login.js"></script>
@endpush

@endauth
