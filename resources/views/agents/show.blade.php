@extends('layouts.app')

@section('title', ($agent->user?->name ?? __('Rieltor')) . ' - ' . __('Rieltor Profili') . ' - Metraj.az')

@section('content')
<div class="w-full pt-4 pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    @include('components.scroll-top')

    {{-- ==================== AGENT PROFILE CARD ==================== --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 mt-4 sm:mt-6 overflow-hidden">
        {{-- Banner strip --}}
        <div class="h-28 sm:h-36 relative overflow-hidden bg-gradient-to-r from-[var(--primary)] via-orange-400 to-orange-300">
            <div class="absolute inset-0 opacity-20"
                 style="background-image: radial-gradient(circle at 20% 50%, white 1.5px, transparent 1.5px); background-size: 22px 22px;"></div>
        </div>

        <div class="px-6 sm:px-8 pb-6 sm:pb-8">
            {{-- Avatar + Name --}}
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-10 sm:-mt-12">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl border-4 border-white shadow-lg overflow-hidden bg-white flex-shrink-0">
                    @if($agent->avatar_url)
                        <img src="{{ $agent->avatar_url }}" alt="{{ $agent->user?->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-orange-500 to-orange-600 text-white font-black text-3xl sm:text-4xl flex items-center justify-center">
                            {{ strtoupper(substr($agent->user?->name ?? 'R', 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0 pb-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[color:var(--text-color)] leading-tight">
                            {{ $agent->user?->name ?? __('Rieltor') }}
                        </h1>
                        @if($agent->is_active)
                            <span class="bg-orange-50 text-orange-600 text-[11px] sm:text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1.5 border border-orange-100">
                                <i class="bi bi-patch-check-fill"></i>
                                {{ __('Təsdiqlənmiş Rieltor') }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-[color:var(--grey-text)] mt-1 flex flex-wrap items-center">
                        <span class="font-medium text-[color:var(--text-color)]">{{ $agent->position ?? __('Müstəqil Rieltor') }}</span>
                        @if($agent->agency)
                            <span class="mx-1 text-gray-300">•</span>
                            <a href="/agency/{{ $agent->agency->id }}" class="text-[color:var(--primary)] font-semibold hover:underline">
                                {{ $agent->agency->name }}
                            </a>
                        @endif
                        <span class="mx-1 text-gray-300">•</span>
                        <span class="text-[color:var(--primary)] font-semibold">{{ $properties->total() }}</span>
                        {{ __('aktiv elan') }}
                    </p>
                </div>

                {{-- Contact CTAs --}}
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 pb-1">
                    @if($agent->phone)
                        <a href="tel:{{ $agent->phone }}"
                           class="flex items-center gap-2 px-5 py-2.5 sm:py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold rounded-2xl shadow-md transition duration-200 text-sm sm:text-base">
                            <i class="bi bi-telephone-fill text-sm"></i>
                            {{ $agent->phone }}
                        </a>
                    @endif
                    @if($agent->whatsapp || $agent->phone)
                        @php $wa = preg_replace('/[^0-9]/', '', $agent->whatsapp ?: $agent->phone); @endphp
                        <a href="https://wa.me/{{ $wa }}" target="_blank"
                           class="flex items-center gap-2 px-5 py-2.5 sm:py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-2xl shadow-md transition duration-200 text-sm sm:text-base">
                            <i class="bi bi-whatsapp text-base"></i>
                            WhatsApp
                        </a>
                    @endif
                </div>
            </div>

            {{-- Real data summary + Contact Info --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 pt-6 border-t border-gray-100 text-sm">
                <div class="md:col-span-2">
                    <h3 class="font-bold text-[color:var(--text-color)] mb-3 flex items-center gap-2">
                        <i class="bi bi-person-lines-fill text-[var(--primary)]"></i>
                        {{ __('Rieltor Haqqında') }}
                    </h3>
                    <div class="space-y-2.5 text-[color:var(--grey-text)] leading-relaxed">
                        <p>
                            {{ $agent->user?->name ?? __('Rieltor') }}
                            {{ $agent->position ? __('vəzifəsində çalışır') : __('müstəqil rieltor kimi fəaliyyət göstərir') }}
                            @if($agent->agency)
                                — <a href="/agency/{{ $agent->agency->id }}" class="text-[color:var(--primary)] font-semibold hover:underline">{{ $agent->agency->name }}</a>
                                {{ __('agentliyinin üzvüdür') }}.
                            @else
                                .
                            @endif
                        </p>
                        <p>
                            {{ __('Hazırda platformada') }}
                            <strong class="text-[color:var(--text-color)]">{{ $properties->total() }}</strong>
                            {{ __('aktiv elan onun tərəfindən idarə olunur') }}.
                            @if($agent->phone)
                                {{ __('Əlaqə üçün birbaşa zəng edə və ya WhatsApp üzərindən yaza bilərsiniz') }}.
                            @endif
                        </p>
                        @if($agent->created_at)
                            <p class="text-xs text-gray-400 flex items-center gap-1.5 pt-1">
                                <i class="bi bi-calendar3"></i>
                                {{ __('Metraj.az platformasına qoşulub') }}:
                                {{ $agent->created_at->format('d.m.Y') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="space-y-2.5 text-[color:var(--grey-text)] md:border-l md:pl-6 border-gray-100">
                    @if($agent->agency && $agent->agency->address)
                        <p class="flex items-start gap-2.5">
                            <i class="bi bi-geo-alt-fill text-[var(--primary)] mt-0.5"></i>
                            <span>{{ $agent->agency->address }}</span>
                        </p>
                    @endif
                    @if($agent->user?->email)
                        <p class="flex items-start gap-2.5">
                            <i class="bi bi-envelope-fill text-[var(--primary)] mt-0.5"></i>
                            <a href="mailto:{{ $agent->user->email }}" class="hover:text-[var(--primary)] transition break-all">{{ $agent->user->email }}</a>
                        </p>
                    @endif
                    @if($agent->phone)
                        <p class="flex items-start gap-2.5">
                            <i class="bi bi-telephone-fill text-[var(--primary)] mt-0.5"></i>
                            <a href="tel:{{ $agent->phone }}" class="hover:text-[var(--primary)] transition">{{ $agent->phone }}</a>
                        </p>
                    @endif
                    @if($agent->agency)
                        <p class="flex items-start gap-2.5">
                            <i class="bi bi-building text-[var(--primary)] mt-0.5"></i>
                            <a href="/agency/{{ $agent->agency->id }}" class="hover:text-[var(--primary)] transition font-medium">{{ $agent->agency->name }}</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== AGENT PROPERTIES LIST ==================== --}}
    <div class="mt-10 sm:mt-12">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-[color:var(--text-color)]">
                    {{ __('Rieltorun Elanları') }}
                </h2>
                <p class="text-xs sm:text-sm text-[color:var(--grey-text)] mt-0.5">
                    {{ $agent->user?->name }} {{ __('tərəfindən yerləşdirilmiş bütün aktiv elanlar') }}
                </p>
            </div>
            <span class="text-xs sm:text-sm font-semibold bg-gray-100 text-gray-700 px-3 py-1.5 rounded-xl self-start sm:self-auto">
                {{ __('Ümumi:') }} <strong class="text-[color:var(--primary)]">{{ $properties->total() }}</strong> {{ __('elan') }}
            </span>
        </div>

        @if($properties->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                <div class="w-16 h-16 bg-orange-50 text-[var(--primary)] rounded-full flex items-center justify-center mx-auto text-2xl mb-4">
                    <i class="bi bi-house-door"></i>
                </div>
                <h3 class="text-base font-bold text-gray-800">{{ __('Hələlik aktiv elan yoxdur') }}</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                    {{ __('Bu rieltor tərəfindən hələ heç bir elan yerləşdirilməyib və ya elanlar moderasiyadadır.') }}
                </p>
                <a href="/agencies" class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                    <i class="bi bi-arrow-left"></i> {{ __('Digər rieltor və agentliklərə baxın') }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @foreach($properties as $property)
                    <x-property-card :property="$property" />
                @endforeach
            </div>

            @if($properties->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $properties->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
