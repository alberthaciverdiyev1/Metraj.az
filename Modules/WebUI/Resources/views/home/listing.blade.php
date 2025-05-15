@extends('webui::layout')

@section('content')
  <main>
<section id="navigation" class="py-3">
    <div class="container mx-auto px-4 flex items-center gap-2">
        <a href="{{ route('home') }}" class="text-orange-500 font-bold flex items-center">
           Home
        </a>
        <span class="text-gray-400">›</span>
        <a href="{{ route('listing') }}" class="text-gray-600">
            Property Listing
        </a>
    </div>
</section>



<section class="property-listing py-4">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-800">Property listing</h2>
            <div class="flex items-center gap-2">
                <button class="flex items-center gap-1 border rounded-lg px-3 py-2 hover:bg-orange-50">
                    <i class="fas fa-sliders-h text-orange-500"></i> Filter
                </button>
                <button class="bg-orange-500 text-white p-2 rounded-md">
                    <i class="fas fa-th"></i>
                </button>
                <button class="p-2 border rounded-md">
                    <i class="fas fa-list"></i>
                </button>
                <div class="relative">
                    <button class="flex items-center border px-4 py-2 rounded-md">
                        Sort by (Default) <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="property-listing-cards">
            <div class="property-listing-card">
                <figure>
                    <img src="" alt="">
                </figure>
            </div>
        </div>
    </div>
</section>

  </main>
@endsection
