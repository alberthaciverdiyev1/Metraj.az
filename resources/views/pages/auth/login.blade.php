@extends('layouts.app')

@section('title', __('Daxil ol - Metraj.az'))

@section('content')
<div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-8 sm:py-12 px-4">
    <div class="w-full max-w-md">
        
        {{-- Login Card --}}
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl space-y-6">
            
            {{-- Header & Logo --}}
            <div class="text-center space-y-2">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('images/metrajlogo1.png') }}" alt="Metraj.az" class="h-10 mx-auto object-contain">
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                    {{ __('Hesaba Daxil Ol') }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-500">
                    {{ __('Elanlarınızı və hesabınızı idarə etmək üçün məlumatlarınızı daxil edin.') }}
                </p>
            </div>

            {{-- Role info notification --}}
            <div class="p-3.5 rounded-2xl bg-orange-50/80 border border-orange-100/80 flex items-start gap-3 text-xs text-orange-900">
                <i class="bi bi-info-circle-fill text-orange-500 text-base shrink-0 mt-0.5"></i>
                <div class="leading-relaxed">
                    <span class="font-bold">{{ __('Agentlik və Rieltorlar:') }}</span>
                    <span>{{ __('Daxil olduqdan sonra avtomatik olaraq biznes idarəetmə panelinə yönləndiriləcəksiniz.') }}</span>
                </div>
            </div>

            {{-- Login Form --}}
            <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Email Input --}}
                <div class="space-y-1.5">
                    <label for="login_email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('E-poçt ünvanı') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="email" id="login_email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="nümunə@metraj.az"
                               class="w-full pl-11 pr-4 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                    </div>
                    <span id="email_error" class="text-rose-500 text-xs font-semibold hidden"></span>
                </div>

                {{-- Password Input --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="login_password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                            {{ __('Şifrə') }} <span class="text-rose-500">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="password" id="login_password" name="password" required
                               placeholder="••••••••"
                               class="w-full pl-11 pr-12 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                        <button type="button" id="togglePasswordBtn" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <i class="bi bi-eye text-lg" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    <span id="password_error" class="text-rose-500 text-xs font-semibold hidden"></span>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" id="remember"
                               class="w-4 h-4 rounded text-orange-500 border-gray-300 focus:ring-orange-500">
                        <span class="text-xs sm:text-sm text-gray-600 font-medium">{{ __('Məni xatırla') }}</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <button type="submit" id="loginSubmitBtn"
                        class="w-full py-4 bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-sm sm:text-base rounded-2xl shadow-md transition duration-200 transform active:scale-98 flex items-center justify-center gap-2 mt-2">
                    <span>{{ __('Daxil Ol') }}</span>
                    <i class="bi bi-arrow-right text-sm"></i>
                </button>
            </form>

            {{-- Footer Switch to Register --}}
            <div class="pt-4 border-t border-gray-100 text-center text-xs sm:text-sm text-gray-600 space-y-2">
                <p>
                    {{ __('Hesabınız yoxdur?') }}
                    <a href="{{ route('register') }}" class="font-bold text-orange-600 hover:text-orange-700 hover:underline">
                        {{ __('Qeydiyyatdan keçin') }}
                    </a>
                </p>
                <div>
                    <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600 transition inline-flex items-center gap-1">
                        <i class="bi bi-house-door"></i> {{ __('Ana səhifəyə qayıt') }}
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
    window.loginConfig = {
        i18n: {
            checking: "{{ __('Yoxlanılır...') }}",
            success: "{{ __('Uğurla daxil oldunuz!') }}",
            loggedIn: "{{ __('Daxil olundu!') }}",
            invalid: "{{ __('E-poçt və ya şifrə yanlışdır.') }}",
            network: "{{ __('Şəbəkə xətası baş verdi. Yenidən cəhd edin.') }}"
        }
    };
</script>
<script src="{{ asset('js/pages/auth/login.js') }}"></script>
@endpush
@endsection
