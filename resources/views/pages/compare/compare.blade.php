@extends('layouts.app')

@section('title', __('Mülkləri Müqayisə Et') . ' - Metraj.az')

@section('content')
    <div class="max-w-[1400px] mx-auto sm:px-6 lg:px-8 py-8">

        @if(isset($breadcrumbs))
            <div class="mb-6">
                @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500 shadow-sm">
                    <i class="bi bi-arrow-left-right text-2xl font-bold"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('Mülkləri Müqayisə Et') }}</h1>
                        <span id="compareTotalBadge"
                              class="{{ count($properties) > 0 ? '' : 'hidden' }} px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-600">
                        {{ count($properties) }} / 4
                    </span>
                    </div>
                </div>
            </div>

            <div id="compareActions" class="{{ count($properties) > 0 ? '' : 'hidden' }} flex items-center gap-3">
                <button id="clearAllCompareBtn" type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-sm font-semibold rounded-xl transition duration-200 border border-rose-200 cursor-pointer">
                    <i class="fa-regular fa-trash-can text-sm"></i>
                    <span>{{ __('Hamısını Təmizlə') }}</span>
                </button>
                @if(count($properties) < 4)
                    <a href="/listing"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition duration-200">
                        <i class="bi bi-plus-circle"></i>
                        <span>{{ __('Daha Çox Elan Əlavə Et') }}</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Empty State -->
        <div id="compareEmptyState"
             class="{{ count($properties) === 0 ? '' : 'hidden' }} text-center py-16 px-4 bg-white rounded-3xl border border-gray-100 shadow-sm max-w-lg mx-auto">
            <div class="w-20 h-20 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-sm">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Müqayisə siyahınız boşdur') }}</h3>
            <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto leading-relaxed">
                {{ __('Elanların üzərindəki müqayisə ikonuna klikləyərək eyni anda 4 əmlaka qədər parametri yan-yana müqayisə edə bilərsiniz.') }}
            </p>
            <a href="/listing"
               class="inline-flex items-center px-6 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-xl shadow-md transition-all duration-200 hover:shadow-lg">
                <i class="bi bi-search mr-2"></i>
                <span>{{ __('Elanları Kəşf Et') }}</span>
            </a>
        </div>

        <!-- Compare Table Container (NO horizontal scroll) -->
        @if(count($properties) > 0)
            <div id="compareTableContainer"
                 class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden w-full">
                <table class="w-full text-left border-collapse table-auto">
                    <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200">
                        <th class="p-3 sm:p-4 w-32 sm:w-44 text-xs font-bold uppercase tracking-wider text-gray-400 align-top">
                            {{ __('Parametr') }}
                        </th>
                        @foreach($properties as $property)
                            <th class="p-3 sm:p-4 text-center border-l border-gray-100 relative group align-top"
                                data-comp-header-id="{{ $property->id }}">
                                <button type="button" onclick="removeCompareItem({{ $property->id }})"
                                        class="absolute top-2 right-2 w-7 h-7 bg-white hover:bg-rose-50 text-gray-400 hover:text-rose-600 rounded-full flex items-center justify-center shadow-sm border border-gray-200 transition cursor-pointer z-10"
                                        title="{{ __('Müqayisədən çıxar') }}">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                                <div class="w-full max-w-[220px] h-[130px] sm:h-[150px] mx-auto rounded-xl overflow-hidden mb-2.5 bg-gray-100 shadow-sm relative">
                                    <img src="{{ $property->first_image_url }}" alt="{{ $property->title }}"
                                         class="w-full h-full object-cover"/>
                                </div>
                                <a href="/elan/{{ $property->slug }}"
                                   class="font-bold text-xs sm:text-sm text-gray-900 hover:text-orange-500 line-clamp-2 transition mb-1.5 block max-w-[240px] mx-auto min-h-[34px]">
                                    {{ $property->title }}
                                </a>
                                @php
                                    $displayPrice = app(\App\Modules\Property\Services\PropertyPricePresenter::class)->display($property);
                                @endphp
                                <div class="text-sm sm:text-base font-extrabold text-orange-600">
                                    {{ $displayPrice['symbol'] }} {{ $displayPrice['formatted'] }}
                                </div>
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs sm:text-sm">
                    @php
                        $getLocalizedName = function ($model) {
                            if (! $model) return '—';
                            $name = $model->name;
                            if (is_array($name)) {
                                return $name[app()->getLocale()] ?? $name['az'] ?? reset($name) ?? '—';
                            }
                            return (string) ($name ?: '—');
                        };
                    @endphp

                            <!-- Şəhər / Rayon -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('Məkan / Şəhər') }}</td>
                        @foreach($properties as $property)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-800"
                                data-comp-col-id="{{ $property->id }}">
                                {{ $getLocalizedName($property->city) }} @if($property->district)
                                    ({{ $getLocalizedName($property->district) }})
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Ünvan -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('Ünvan') }}</td>
                        @foreach($properties as $property)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-600 text-xs truncate max-w-[200px]"
                                data-comp-col-id="{{ $property->id }}">
                                {{ $property->address ?: ($property->landmark ?: '—') }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Otaq Sayı -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('Otaq Sayı') }}</td>
                        @foreach($properties as $property)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 font-medium text-gray-800"
                                data-comp-col-id="{{ $property->id }}">
                                {{ $property->rooms ? $property->rooms . ' otaqlı' : '—' }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Sahə -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('Sahə') }}</td>
                        @foreach($properties as $property)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 font-medium text-gray-800"
                                data-comp-col-id="{{ $property->id }}">
                                {{ $property->area ? $property->area . ' m²' : ($property->land_area ? $property->land_area . ' sot' : '—') }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Mərtəbə -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('Mərtəbə') }}</td>
                        @foreach($properties as $property)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-800"
                                data-comp-col-id="{{ $property->id }}">
                                @if($property->floor && $property->total_floors)
                                    {{ $property->floor }} / {{ $property->total_floors }}
                                @elseif($property->floor)
                                    {{ $property->floor }}
                                @else
                                    —
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Kupça / Sənəd -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('Çıxarış (Kupça)') }}</td>
                        @foreach($properties as $property)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100"
                                data-comp-col-id="{{ $property->id }}">
                                @if($property->has_document)
                                    <span class="inline-flex items-center text-emerald-600 font-semibold gap-1">
                                    <i class="bi bi-check-circle-fill"></i> Var
                                </span>
                                @else
                                    <span class="inline-flex items-center text-gray-400 gap-1">
                                    <i class="bi bi-dash-circle"></i> Yoxdur
                                </span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- İpoteka -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('İpoteka') }}</td>
                        @foreach($properties as $property)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100"
                                data-comp-col-id="{{ $property->id }}">
                                @if($property->has_mortgage)
                                    <span class="inline-flex items-center text-emerald-600 font-semibold gap-1">
                                    <i class="bi bi-check-circle-fill"></i> Var
                                </span>
                                @else
                                    <span class="inline-flex items-center text-gray-400 gap-1">
                                    <i class="bi bi-dash-circle"></i> Yoxdur
                                </span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Əməliyyat -->
                    <tr class="bg-gray-50/50">
                        <td class="p-3 sm:p-4 bg-gray-50/80"></td>
                        @foreach($properties as $property)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100"
                                data-comp-col-id="{{ $property->id }}">
                                <a href="/elan/{{ $property->slug }}"
                                   class="inline-flex items-center justify-center max-w-[200px] w-full py-2 sm:py-2.5 px-3 sm:px-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                    {{ __('Elana Bax') }} <i class="bi bi-arrow-right ml-1.5"></i>
                                </a>
                            </td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif

    </div>

    @push('scripts')
<script>
window.removeCompareItem = function (propertyId) {
    const csrf = window.Metraj?.csrfToken() || '';
    fetch('/api/compares/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ property_id: propertyId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    });
};

document.getElementById('clearAllCompareBtn')?.addEventListener('click', function () {
    if (confirm('Bütün müqayisə siyahısını təmizləmək istədiyinizdən əminsiniz?')) {
        const csrf = window.Metraj?.csrfToken() || '';
        fetch('/api/compares/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }
});
</script>
@endpush
@endsection
