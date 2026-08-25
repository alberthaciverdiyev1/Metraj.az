@extends('layouts.app')

@section('title', __('auth.login_title') . ' - KibrisKare.com')

@section('content')
<div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-8 sm:py-14 px-4">
    <div class="w-full max-w-4xl">
        
        {{-- Login Card (2 Columns on MD+) --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12 min-h-[560px]">
            
            {{-- Left Banner Showcase (MD+ Only) --}}
            <div class="hidden md:flex md:col-span-5 bg-gradient-to-br from-orange-500 via-orange-600 to-amber-600 text-white p-8 lg:p-10 flex-col justify-between relative overflow-hidden">
                {{-- Decorative background circles --}}
                <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
                <div class="absolute -left-12 -bottom-12 w-48 h-48 rounded-full bg-black/10 blur-xl pointer-events-none"></div>

                <div class="relative z-10 space-y-6">
                    <a href="{{ route('home') }}" class="inline-block bg-white/95 backdrop-blur-sm px-4 py-2 rounded-2xl shadow-sm">
                        <img src="{{ asset('images/kibriskarelogo1.png') }}" alt="KibrisKare.com" class="h-8 object-contain">
                    </a>

                    <div class="space-y-2 pt-2">
                        <h2 class="text-2xl lg:text-3xl font-extrabold tracking-tight leading-snug">
                            {{ __('auth.login_heading') }}
                        </h2>
                        <p class="text-xs lg:text-sm text-orange-100 font-medium leading-relaxed">
                            {{ __('auth.login_subheading') }}
                        </p>
                    </div>

                    {{-- Feature list --}}
                    <div class="space-y-3.5 pt-4">
                        <div class="flex items-center gap-3 text-xs lg:text-sm font-medium text-white/95">
                            <span class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                <i class="bi bi-house-check text-sm"></i>
                            </span>
                            <span>Kuzey Kıbrıs'ın ən geniş əmlak bazası</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs lg:text-sm font-medium text-white/95">
                            <span class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                <i class="bi bi-shield-check text-sm"></i>
                            </span>
                            <span>Doğrulanmış agentlik və sahibindən elanlar</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs lg:text-sm font-medium text-white/95">
                            <span class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                <i class="bi bi-lightning-charge text-sm"></i>
                            </span>
                            <span>Sürətli filtrasiya və müqayisə sistemi</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 pt-8 border-t border-white/20 flex items-center justify-between text-xs text-orange-100">
                    <span>© {{ date('Y') }} KibrisKare.com</span>
                    <a href="{{ route('home') }}" class="hover:text-white transition flex items-center gap-1">
                        <i class="bi bi-arrow-left"></i> Ana səhifə
                    </a>
                </div>
            </div>

            {{-- Right Form Area --}}
            <div class="md:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center space-y-6">
                
                {{-- Mobile Header --}}
                <div class="text-center md:text-left space-y-2">
                    <div class="md:hidden mb-4">
                        <a href="{{ route('home') }}" class="inline-block">
                            <img src="{{ asset('images/kibriskarelogo1.png') }}" alt="KibrisKare.com" class="h-10 mx-auto object-contain">
                        </a>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                        {{ __('auth.login_heading') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500">
                        {{ __('auth.login_subheading') }}
                    </p>
                </div>

                {{-- Role info notification --}}
                <div class="p-3.5 rounded-2xl bg-orange-50/80 border border-orange-100/80 flex items-start gap-3 text-xs text-orange-900">
                    <i class="bi bi-info-circle-fill text-orange-500 text-base shrink-0 mt-0.5"></i>
                    <div class="leading-relaxed">
                        <span class="font-semibold">{{ __('auth.agency_realtor_info') }}</span>
                        <span>{{ __('auth.agency_realtor_redirect_desc') }}</span>
                    </div>
                </div>

                {{-- Login Form --}}
                <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="space-y-4">
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
                                   class="w-full pl-11 pr-4 py-3.5 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
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
                                   class="w-full pl-11 pr-12 py-3.5 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                            <button type="button" id="togglePasswordBtn" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition cursor-pointer">
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
                </div>

            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
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
<script src="{{ asset('js/pages/auth/login.js') }}"></script>
@endpush
@endsection
