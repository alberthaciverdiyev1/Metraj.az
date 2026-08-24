@extends('layouts.app')

@section('title', ($agency->name ?? '') . ' - Daşınmaz Əmlak Agentliyi - Metraj.az')

@section('content')
<div class="w-full pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])

    {{-- ==================== AGENCY PROFILE CARD ==================== --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 mt-4 sm:mt-6 overflow-hidden">
        {{-- Banner strip --}}
        <div class="h-28 sm:h-36 relative overflow-hidden @if(!($agency->banner || $agency->banner_url)) bg-[var(--primary)] @endif">
            @if($agency->banner || $agency->banner_url)
                <img src="{{ $agency->banner ? (str_starts_with($agency->banner, 'http') ? $agency->banner : asset('storage/'.$agency->banner)) : $agency->banner_url }}" alt="{{ $agency->name }} banner" class="w-full h-full object-cover">
            @endif
        </div>

        <div class="px-6 sm:px-8 pb-6 sm:pb-8 relative z-10">
            {{-- Logo + Name --}}
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-10 sm:-mt-12 relative z-10">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl border-4 border-white shadow-lg overflow-hidden bg-white flex-shrink-0 relative z-20">
                    @if($agency->logo || $agency->logo_url)
                        <img src="{{ $agency->logo ? (str_starts_with($agency->logo, 'http') ? $agency->logo : asset('storage/'.$agency->logo)) : $agency->logo_url }}" alt="{{ $agency->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-[var(--primary)] text-white font-black text-3xl sm:text-4xl flex items-center justify-center">
                            {{ strtoupper(substr($agency->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0 pb-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[color:var(--text-color)] leading-tight">
                            {{ $agency->name }}
                        </h1>
                        @if($agency->is_verified)
                            <span class="bg-blue-50 text-blue-600 text-[11px] sm:text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1.5 border border-blue-100">
                                <i class="bi bi-patch-check-fill"></i>
                                {{ __('Rəsmi Partnyor') }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-[color:var(--grey-text)] mt-1">
                        {{ __('Daşınmaz əmlak agentliyi') }}
                        <span class="mx-1 text-gray-300">•</span>
                        <span class="text-[color:var(--primary)] font-semibold">{{ $properties->total() }}</span>
                        {{ __('aktiv elan') }}
                    </p>
                </div>

                {{-- Contact CTAs --}}
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 pb-1">
                    @if($agency->phone)
                        <a href="tel:{{ $agency->phone }}"
                           class="flex items-center gap-2 px-5 py-2.5 sm:py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-md transition duration-200 text-sm sm:text-base">
                            <i class="bi bi-telephone-fill text-sm"></i>
                            {{ $agency->phone }}
                        </a>
                    @endif
                    @if($agency->whatsapp)
                        @php $wa = preg_replace('/[^0-9]/', '', $agency->whatsapp); @endphp
                        <a href="https://wa.me/{{ $wa }}" target="_blank"
                           class="flex items-center gap-2 px-5 py-2.5 sm:py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-2xl shadow-md transition duration-200 text-sm sm:text-base">
                            <i class="bi bi-whatsapp text-base"></i>
                            WhatsApp
                        </a>
                    @endif
                </div>
            </div>

            {{-- Description + Contact Info --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 pt-6 border-t border-gray-100 text-sm">
                <div class="md:col-span-2">
                    <h3 class="font-bold text-[color:var(--text-color)] mb-2 flex items-center gap-2">
                        <i class="bi bi-building text-[var(--primary)]"></i>
                        {{ __('Agentlik Haqqında') }}
                    </h3>
                    <p class="text-[color:var(--grey-text)] leading-relaxed">
                        {{ $agency->description ?: __('Agentlik haqqında ətraflı məlumat daxil edilməyib.') }}
                    </p>
                </div>
                <div class="space-y-2.5 text-[color:var(--grey-text)] md:border-l md:pl-6 border-gray-100">
                    @if($agency->address)
                        <p class="flex items-start gap-2.5">
                            <i class="bi bi-geo-alt-fill text-[var(--primary)] mt-0.5"></i>
                            <span>{{ $agency->address }}</span>
                        </p>
                    @endif
                    @if($agency->email)
                        <p class="flex items-start gap-2.5">
                            <i class="bi bi-envelope-fill text-[var(--primary)] mt-0.5"></i>
                            <a href="mailto:{{ $agency->email }}" class="hover:text-[var(--primary)] transition">{{ $agency->email }}</a>
                        </p>
                    @endif
                    @if($agency->website)
                        <p class="flex items-start gap-2.5">
                            <i class="bi bi-globe2 text-[var(--primary)] mt-0.5"></i>
                            <a href="{{ $agency->website }}" target="_blank" class="hover:text-[var(--primary)] transition break-all">{{ $agency->website }}</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== AGENTS (simple list) ==================== --}}
    @if($agency->agents->isNotEmpty())
        <div class="mt-8 sm:mt-10">
            <div class="flex items-center gap-3 mb-4 sm:mb-6">
                <h2 class="text-lg sm:text-xl font-bold text-[color:var(--text-color)]">{{ __('Agentliyin Rieltorları') }}</h2>
                <div class="h-px flex-1 bg-gray-200"></div>
                <span class="text-xs sm:text-sm text-[color:var(--grey-text)]">{{ $agency->agents->count() }} {{ __('nəfər') }}</span>
            </div>
            <ul class="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-100 overflow-hidden">
                @foreach($agency->agents as $agent)
                    <li>
                        <a href="/agent/{{ $agent->id }}"
                           class="flex items-center gap-3 sm:gap-4 px-4 sm:px-5 py-3.5 sm:py-4 hover:bg-orange-50/50 transition duration-150 group">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                                @if($agent->avatar_url)
                                    <img src="{{ $agent->avatar_url }}" alt="{{ $agent->user?->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-[var(--primary)]/10 text-[var(--primary)] flex items-center justify-center">
                                        <i class="bi bi-person text-base sm:text-lg"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-[color:var(--text-color)] text-sm sm:text-base truncate group-hover:text-[var(--primary)] transition">{{ $agent->user?->name ?? __('Rieltor') }}</h3>
                                <p class="text-xs text-[color:var(--grey-text)] mt-0.5">{{ $agent->position ?? __('Rieltor') }}</p>
                            </div>
                            @if($agent->phone)
                                <span class="hidden sm:inline-flex items-center gap-1.5 text-xs text-[color:var(--grey-text)] whitespace-nowrap">
                                    <i class="bi bi-telephone text-green-500"></i>
                                    {{ $agent->phone }}
                                </span>
                            @endif
                            <i class="bi bi-chevron-right text-gray-300 group-hover:text-[var(--primary)] transition"></i>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ==================== PROPERTIES ==================== --}}
    <div class="mt-8 sm:mt-10">
        <div class="flex items-center gap-3 mb-4 sm:mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-[color:var(--text-color)]">{{ __('Agentliyin Elanları') }}</h2>
            <div class="h-px flex-1 bg-gray-200"></div>
            <span class="text-xs sm:text-sm text-[color:var(--grey-text)]">{{ $properties->total() }} {{ __('elan') }}</span>
        </div>

        @if($properties->isEmpty())
            <div class="bg-white rounded-3xl p-12 sm:p-16 text-center border border-gray-100 shadow-sm">
                <i class="bi bi-house-x text-4xl text-gray-300 mb-4 block"></i>
                <p class="text-gray-500 font-medium">{{ __('Bu agentliyə aid heç bir aktiv elan tapılmadı.') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
                @foreach($properties as $property)
                    @include('components.property-card', ['property' => $property])
                @endforeach
            </div>

            <div class="mt-10">
                {{ $properties->onEachSide(2)->appends(request()->query())->links('pagination.metraj') }}
            </div>
        @endif
    </div>
</div>

@include('components.scroll-top')
@endsection

@push('scripts')
    <script src="/js/pages/property/listing.js"></script>
@endpush
