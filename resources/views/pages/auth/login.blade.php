@extends('layouts.app')

@section('title', __('auth.login_title') . ' - KibrisKare.com')

@section('content')
<div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-8 sm:py-14 px-4">
    <div class="w-full max-w-2xl sm:max-w-3xl">
        
        {{-- Login Card --}}
        <div class="bg-white rounded-3xl p-6 sm:p-10 lg:p-12 border border-gray-100 shadow-xl space-y-6">
            
            {{-- Header & Logo --}}
            <div class="text-center space-y-2">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('images/kibriskarelogo1.png') }}" alt="KibrisKare.com" class="h-10 mx-auto object-contain">
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                    {{ __('auth.login_heading') }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-500">
                    {{ __('auth.login_subheading') }}
                </p>
            </div>

            {{-- Role info notification --}}
            <div class="p-4 rounded-2xl bg-orange-50/80 border border-orange-100/80 flex items-start gap-3 text-xs sm:text-sm text-orange-950">
                <i class="bi bi-info-circle-fill text-orange-500 text-lg shrink-0 mt-0.5"></i>
                <div class="leading-relaxed">
                    <span class="font-semibold">{{ __('auth.agency_realtor_info') }}</span>
                    <span>{{ __('auth.agency_realtor_redirect_desc') }}</span>
                </div>
            </div>

            {{-- Login Form --}}
            <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email Input --}}
                <div class="space-y-1.5">
                    <label for="login_email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        {{ __('auth.email_address') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="email" id="login_email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="nümunə@kibriskare.com"
                               class="w-full pl-11 pr-4 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                    </div>
                    <span id="email_error" class="text-rose-500 text-xs font-semibold hidden"></span>
                </div>

                {{-- Password Input --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="login_password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            {{ __('auth.password_label') }} <span class="text-rose-500">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="password" id="login_password" name="password" required
                               placeholder="••••••••"
                               class="w-full pl-11 pr-12 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                        <button type="button" id="togglePasswordBtn" 
                                onclick="togglePasswordVisibility('login_password', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition cursor-pointer p-1 z-10"
                                aria-label="Toggle Password">
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
                        <span class="text-xs sm:text-sm text-gray-600 font-medium">{{ __('auth.remember_me') }}</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <button type="submit" id="loginSubmitBtn"
                        class="w-full py-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm sm:text-base rounded-2xl shadow-md transition duration-200 transform active:scale-98 flex items-center justify-center gap-2 mt-2 cursor-pointer">
                    <span>{{ __('auth.login_btn') }}</span>
                    <i class="bi bi-arrow-right text-sm"></i>
                </button>
            </form>

            {{-- Footer Switch to Register --}}
            <div class="pt-4 border-t border-gray-100 text-center text-xs sm:text-sm text-gray-600 space-y-2">
                <p>
                    {{ __('auth.no_account') }}
                    <a href="{{ route('register') }}" class="font-semibold text-orange-600 hover:text-orange-700 hover:underline">
                        {{ __('auth.register_now') }}
                    </a>
                </p>
                <div>
                    <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600 transition inline-flex items-center gap-1">
                        <i class="bi bi-house-door"></i> {{ __('auth.back_to_home') }}
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const icon = btn.querySelector('i') || btn;
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        if (isPass) {
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    window.loginConfig = {
        i18n: {
            checking: "{{ __('auth.checking') }}",
            success: "{{ __('auth.login_success') }}",
            loggedIn: "{{ __('auth.logged_in') }}",
            invalid: "{{ __('auth.invalid_credentials') }}",
            network: "{{ __('auth.network_error') }}"
        }
    };
</script>
<script src="{{ asset('js/pages/auth/login.js') }}?v={{ time() }}"></script>
@endpush
@endsection
