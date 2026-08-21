@extends('layouts.app')

@section('content')
<div class="w-full py-8">
    <div class="flex items-center mb-8">
        <h3 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-balance-scale text-[var(--primary)] mr-3"></i>M&eacute;hsullar&imath; M&uuml;qayis&eacute; Et
        </h3>
    </div>

    <!-- Compare container -->
    <div id="compareContainer" class="bg-white p-6 rounded-xl shadow-lg"></div>
</div>

@push('scripts')
<script src="/js/pages/compare/compare.js"></script>
@endpush
@endsection
