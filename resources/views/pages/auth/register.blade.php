@extends('layouts.app')

@section('title', __('auth.register_title') . ' - KibrisKare.com')

@section('content')
<div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-8 sm:py-12 px-4">
    <div class="w-full max-w-xl">
        
        {{-- Register Card --}}
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl space-y-6">
            
            {{-- Header & Logo --}}
            <div class="text-center space-y-2">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('images/kibriskarelogo1.png') }}" alt="KibrisKare.com" class="h-10 mx-auto object-contain">
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                    {{ __('auth.register_heading') }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-500">
                    {{ __('auth.register_subheading') }}
                </p>
            </div>

            {{-- Role Switcher Tabs (3 columns) --}}
            <div class="grid grid-cols-3 gap-2 p-1.5 bg-gray-100/80 rounded-2xl">
                <button type="button" class="role-tab-btn py-3 px-2 rounded-xl text-xs sm:text-sm font-bold transition flex flex-col sm:flex-row items-center justify-center gap-1.5 bg-white text-orange-600 shadow-sm" data-role="user">
                    <i class="bi bi-person text-base"></i>
                    <span>{{ __('auth.role_individual') }}</span>
                </button>
                <button type="button" class="role-tab-btn py-3 px-2 rounded-xl text-xs sm:text-sm font-bold transition flex flex-col sm:flex-row items-center justify-center gap-1.5 text-gray-600 hover:text-gray-900" data-role="agent">
                    <i class="bi bi-person-badge text-base"></i>
                    <span>{{ __('auth.role_agent') }}</span>
                </button>
                <button type="button" class="role-tab-btn py-3 px-2 rounded-xl text-xs sm:text-sm font-bold transition flex flex-col sm:flex-row items-center justify-center gap-1.5 text-gray-600 hover:text-gray-900" data-role="agency">
                    <i class="bi bi-building text-base"></i>
                    <span>{{ __('auth.role_agency') }}</span>
                </button>
            </div>

            {{-- Dynamic Role Alert Banner --}}
            <div id="roleInfoBanner" class="p-3.5 rounded-2xl bg-orange-50/80 border border-orange-100/80 flex items-start gap-3 text-xs text-orange-950">
                <i class="bi bi-info-circle-fill text-orange-500 text-base shrink-0 mt-0.5"></i>
                <div id="roleInfoText" class="leading-relaxed">
                    {{ __('auth.role_info_individual') }}
                </div>
            </div>

            {{-- Registration Form --}}
            <form id="registerForm" action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Hidden Role Input --}}
                <input type="hidden" name="role_type" id="role_type" value="user">

                {{-- ==================== AGENCY SPECIFIC FIELD ==================== --}}
                <div id="field_agency_name" class="space-y-1.5 hidden">
                    <label for="agency_name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        {{ __('auth.agency_name') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="bi bi-building absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" id="agency_name" name="agency_name" placeholder="{{ __('auth.agency_name_placeholder') }}"
                               class="w-full pl-11 pr-4 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                    </div>
                    <span id="agency_name_error" class="text-rose-500 text-xs font-semibold hidden"></span>
                </div>

                {{-- Full Name Input (Applies to all) --}}
                <div class="space-y-1.5">
                    <label for="reg_name" id="label_name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        {{ __('auth.full_name') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="bi bi-person absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" id="reg_name" name="name" required placeholder="{{ __('auth.full_name_placeholder') }}"
                               class="w-full pl-11 pr-4 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                    </div>
                    <span id="name_error" class="text-rose-500 text-xs font-semibold hidden"></span>
                </div>

                {{-- Email Input --}}
                <div class="space-y-1.5">
                    <label for="reg_email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        {{ __('auth.email_address') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="email" id="reg_email" name="email" required placeholder="nümunə@kibriskare.com"
                               class="w-full pl-11 pr-4 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                    </div>
                    <span id="email_error" class="text-rose-500 text-xs font-semibold hidden"></span>
                </div>

                {{-- ==================== PHONE & WHATSAPP (Agent & Agency) ==================== --}}
                <div id="contactFields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 hidden">
                    <div class="space-y-1.5">
                        <label for="reg_phone" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            {{ __('auth.phone_number') }} <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="bi bi-telephone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                            <input type="tel" id="reg_phone" name="phone" placeholder="050 123 45 67"
                                   class="w-full pl-11 pr-4 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                        </div>
                        <span id="phone_error" class="text-rose-500 text-xs font-semibold hidden"></span>
                    </div>

                    <div class="space-y-1.5">
                        <label for="reg_whatsapp" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            {{ __('auth.whatsapp') }} <span class="text-gray-400 font-normal">({{ __('auth.optional') }})</span>
                        </label>
                        <div class="relative">
                            <i class="bi bi-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-emerald-600 text-base"></i>
                            <input type="tel" id="reg_whatsapp" name="whatsapp" placeholder="050 123 45 67"
                                   class="w-full pl-11 pr-4 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                        </div>
                    </div>
                </div>

                {{-- ==================== AGENT: AGENCY SELECTION ==================== --}}
                <div id="field_agency_select" class="space-y-1.5 hidden">
                    <label for="reg_agency_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        {{ __('auth.affiliated_agency') }} <span class="text-gray-400 font-normal">({{ __('auth.optional') }})</span>
                    </label>
                    <div class="relative">
                        <i class="bi bi-diagram-3 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <select id="reg_agency_id" name="agency_id"
                                class="w-full pl-11 pr-8 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner appearance-none">
                            <option value="">{{ __('auth.independent_realtor') }}</option>
                            @foreach($agencies as $agency)
                                <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                            @endforeach
                        </select>
                        <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                {{-- ==================== AGENCY: ADDRESS ==================== --}}
                <div id="field_agency_address" class="space-y-1.5 hidden">
                    <label for="reg_address" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        {{ __('auth.office_address') }} <span class="text-gray-400 font-normal">({{ __('auth.optional') }})</span>
                    </label>
                    <div class="relative">
                        <i class="bi bi-geo-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" id="reg_address" name="address" placeholder="{{ __('auth.office_address_placeholder') }}"
                               class="w-full pl-11 pr-4 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                    </div>
                </div>

                {{-- Password & Password Confirmation --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="reg_password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            {{ __('auth.password_label') }} <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                            <input type="password" id="reg_password" name="password" required placeholder="••••••••"
                                   class="w-full pl-11 pr-11 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                            <button type="button" class="toggle-pass-btn absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition" data-target="reg_password">
                                <i class="bi bi-eye text-base"></i>
                            </button>
                        </div>
                        <span id="password_error" class="text-rose-500 text-xs font-semibold hidden"></span>
                    </div>

                    <div class="space-y-1.5">
                        <label for="reg_password_confirmation" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            {{ __('auth.password_confirmation') }} <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="bi bi-lock-fill absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                            <input type="password" id="reg_password_confirmation" name="password_confirmation" required placeholder="••••••••"
                                   class="w-full pl-11 pr-11 py-3.5 sm:py-4 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm sm:text-base text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-inner">
                            <button type="button" class="toggle-pass-btn absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition" data-target="reg_password_confirmation">
                                <i class="bi bi-eye text-base"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Terms & Agreement --}}
                <p class="text-[11px] text-gray-500 leading-relaxed pt-1">
                    {{ __('auth.terms_prefix') }}
                    <a href="{{ route('about-us') }}" class="text-orange-600 hover:underline font-semibold">{{ __('auth.terms_of_use') }}</a>
                    {{ __('auth.and') }}
                    <a href="{{ route('about-us') }}" class="text-orange-600 hover:underline font-semibold">{{ __('auth.privacy_policy') }}</a>
                    {{ __('auth.terms_suffix') }}
                </p>

                {{-- Submit Button --}}
                <button type="submit" id="registerSubmitBtn"
                        class="w-full py-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm sm:text-base rounded-2xl shadow-md transition duration-200 transform active:scale-98 flex items-center justify-center gap-2 mt-2">
                    <span id="registerBtnText">{{ __('auth.complete_registration') }}</span>
                    <i class="bi bi-arrow-right text-sm"></i>
                </button>
            </form>

            {{-- Footer Switch to Login --}}
            <div class="pt-4 border-t border-gray-100 text-center text-xs sm:text-sm text-gray-600 space-y-2">
                <p>
                    {{ __('auth.already_have_account') }}
                    <a href="{{ route('login') }}" class="font-semibold text-orange-600 hover:text-orange-700 hover:underline">
                        {{ __('auth.login_here') }}
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
    window.registerConfig = {
        i18n: {
            label_user: "{{ __('auth.full_name') }}",
            role_info_user: "{{ __('auth.role_info_individual') }}",
            btn_user: "{{ __('auth.register_as_user') }}",

            label_agent: "{{ __('auth.realtor_name') }}",
            role_info_agent: "<strong>{{ __('auth.role_agent') }}:</strong> {{ __('auth.role_info_agent') }}",
            btn_agent: "{{ __('auth.register_as_agent') }}",

            label_agency: "{{ __('auth.responsible_person_name') }}",
            role_info_agency: "<strong>{{ __('auth.role_agency') }}:</strong> {{ __('auth.role_info_agency') }}",
            btn_agency: "{{ __('auth.register_as_agency') }}",

            checking: "{{ __('auth.register_checking') }}",
            success: "{{ __('auth.register_success') }}",
            completed: "{{ __('auth.register_completed') }}",
            invalid: "{{ __('auth.invalid_form_data') }}",
            network: "{{ __('auth.network_error') }}"
        }
    };
</script>
<script src="{{ asset('js/pages/auth/register.js') }}"></script>
@endpush
@endsection
