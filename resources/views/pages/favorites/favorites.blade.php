@extends('layouts.app')

@section('content')
<div class="w-full py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">My favorites</h1>

    <div class="flex justify-end mb-6">
        <button id="clearAllFavoritesBtn"
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
            <i class="fas fa-trash-alt mr-2"></i> Ham&imath;s&imath;n&imath; Sil
        </button>
    </div>

    <div id="favoritesContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    </div>

</div>
@endsection
