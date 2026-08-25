@props(['searches' => null, 'currentSlug' => null])

@php
    $items = $searches ?? \App\Modules\Property\Models\QuickSearch::popular()->limit(15)->get();
@endphp

@if($items->isNotEmpty())
<div class="w-full py-2.5">
    <div class="flex items-center gap-2 flex-wrap">
        <div class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 shrink-0 mr-1">
            <i class="bi bi-fire text-orange-500 text-sm"></i>
            <span>{{ __('listing.popular_searches') }}:</span>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            @foreach($items as $item)
                @php
                    $isActive = ($currentSlug === $item->slug) || request()->is('axtaris/' . $item->slug) || request()->is('search/' . $item->slug);
                @endphp
                <a href="{{ $item->url }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-medium transition duration-200 shadow-2xs select-none
                          {{ $isActive ? 'bg-orange-500 text-white font-semibold shadow-sm' : 'bg-gray-100/90 text-gray-700 hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 border border-transparent' }}">
                    <span>{{ $item->localized_title }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif
