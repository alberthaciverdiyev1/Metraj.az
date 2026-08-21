<section id="property-detail" class="bg-white rounded-2xl shadow p-4 sm:p-6 space-y-4 sm:space-y-6">
    <h3 class="text-xl sm:text-2xl font-bold text-black">{{ __('Əmlakın Detalları') }}</h3>

    <p id="description" class="text-gray-600 leading-relaxed transition-all duration-300">
        {!! $item->description ?? '' !!}
    </p>

    <div class="grid grid-cols-4 sm:grid-cols-4 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="flex flex-col items-center gap-2 p-3 border border-gray-200 rounded-xl hover:border-[#F1913D] transition">
            <i class="bi bi-hash text-[#F1913D] text-2xl hidden sm:block"></i>
            <p class="text-gray-500 text-xs sm:text-sm">{{ __('Kod') }}</p>
            <p class="text-sm font-semibold">{{ $item->code ?? $item->id }}</p>
        </div>

        @if(!empty($item->area))
        <div class="flex flex-col items-center gap-2 p-3 border border-gray-200 rounded-xl hover:border-[#F1913D] transition">
            <i class="bi bi-arrows-fullscreen text-[#F1913D] text-2xl hidden sm:block"></i>
            <p class="text-gray-500 text-xs sm:text-sm">{{ __('Sahə') }}</p>
            <p class="text-sm sm:font-semibold">{{ $item->area }} m²</p>
        </div>
        @endif

        @if(!empty($item->land_area))
        <div class="flex flex-col items-center gap-2 p-3 border border-gray-200 rounded-xl hover:border-[#F1913D] transition">
            <i class="bi bi-rulers text-[#F1913D] text-2xl hidden sm:block"></i>
            <p class="text-gray-500 text-xs sm:text-sm">{{ __('Torpaq') }}</p>
            <p class="text-sm sm:font-semibold">{{ $item->land_area }} sot</p>
        </div>
        @endif

        @if(!empty($item->rooms))
        <div class="flex flex-col items-center gap-2 p-3 border border-gray-200 rounded-xl hover:border-[#F1913D] transition">
            <i class="bi bi-door-open text-[#F1913D] text-2xl hidden sm:block"></i>
            <p class="text-gray-500 text-xs sm:text-sm">{{ __('Otaqlar') }}</p>
            <p class="text-sm sm:font-semibold">{{ $item->rooms }}</p>
        </div>
        @endif
    </div>
</section>
