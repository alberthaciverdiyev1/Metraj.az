@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/add-property.css') }}">
@endpush

@section('content')
<div class="w-full pt-4">
    @include('components.breadcrumb', ['items' => [
        ['label' => __('Ana Səhifə'), 'url' => '/'],
        ['label' => __('Yeni Elan Yerləşdir')],
    ]])
</div>

@include('components.scroll-top')

<section id="add-property" class="w-full py-4 mb-16">
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl mb-8 shadow-sm">
            <div class="flex items-center gap-2 font-semibold text-sm mb-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-600"></i>
                <span>{{ __('Zəhmət olmasa formdakı xətaları düzəldin:') }}</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('add-property.store') }}" enctype="multipart/form-data" id="propertyForm" class="space-y-8">
        @csrf

                @csrf

        @include('pages.property.partials.add.basic-info')

        @include('pages.property.partials.add.location')

        @include('pages.property.partials.add.media-amenities-contact')

        @include('pages.property.partials.add.submit-bar')
    </form></form>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Quill Rich Text Editor CDN -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
    window.addFormConfig = {
        rates: @json($dailyRates),
        amenityUrl: "{{ route('add-property.amenities') }}",
        i18n: {
            submit: "{{ __('Elanı Yerləşdir') }}",
            loading: "{{ __('Yüklənir...') }}"
        }
    };
</script>
<script src="{{ asset('js/pages/property/add-form.js') }}"></script>
@endsection
