@props(['searches' => null, 'currentSlug' => null])

@php
    $items = $searches ?? \App\Modules\Property\Models\QuickSearch::popular()->limit(24)->get();
@endphp

@if($items->isNotEmpty())
<div class="w-full bg-white rounded-3xl p-5 sm:p-6 border border-gray-200/80 shadow-xs">
    <div class="flex items-center gap-2 sm:gap-2.5 flex-wrap">
        @foreach($items as $item)
            @php
                $isActive = ($currentSlug === $item->slug) || request()->is('axtaris/' . $item->slug) || request()->is('search/' . $item->slug);
            @endphp
            <a href="{{ $item->url }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium transition duration-200 select-none
                      {{ $isActive ? 'bg-orange-500 text-white font-semibold shadow-sm' : 'bg-gray-50 text-gray-700 hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 border border-gray-200/70' }}">
                <span>{{ $item->localized_title }}</span>
                <i class="bi bi-arrow-up-right text-[10px] opacity-60"></i>
            </a>
        @endforeach
    </div>
</div>
@endif
