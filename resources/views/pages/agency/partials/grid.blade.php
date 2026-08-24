@if($items->isEmpty())
    <div id="noResults" class="text-center py-16 sm:py-20 col-span-full">
        <div class="text-5xl mb-4 text-gray-300"><i class="fas fa-search"></i></div>
        <h3 class="text-lg font-semibold text-gray-500">{{ __('Nəticə tapılmadı') }}</h3>
        <p class="text-sm text-gray-400 mt-2">{{ __('Axtarış və ya filtr meyarlarını dəyişin') }}</p>
    </div>
@else
    @foreach($items as $item)
    <div data-entity-card="true" data-type="{{ $item->type }}" onclick="window.location.href='{{ $item->url }}'"
         class="cursor-pointer bg-white rounded-2xl overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-xl border border-gray-100">

        <div class="relative overflow-hidden aspect-[16/9] sm:aspect-[5/3] bg-orange-50">
            <img src="{{ $item->banner }}"
                 alt="{{ $item->name }}"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
        </div>

        <div class="flex px-4 sm:px-5 -mt-8 sm:-mt-10 relative z-10">
            @if($item->avatar)
                <img class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-full border-4 border-white shadow-md object-cover bg-white"
                     src="{{ $item->avatar }}" alt="{{ $item->name }}">
            @else
                <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-full border-4 border-white shadow-md bg-[var(--primary)] text-white flex items-center justify-center text-xl font-black">
                    {{ $item->initial }}
                </div>
            @endif
        </div>

        <div class="px-4 sm:px-5 pb-4 sm:pb-5 pt-2 sm:pt-3 flex flex-col flex-1">
            <h3 class="font-semibold text-[color:var(--text-color)] text-sm sm:text-base lg:text-lg leading-snug hover:text-[color:var(--primary)] transition-colors line-clamp-1">
                {{ $item->name }}
            </h3>

            @if($item->subtitle)
            <div class="flex items-center gap-1.5 text-xs sm:text-sm text-[color:var(--grey-text)] mt-1 sm:mt-1.5">
                @if($item->is_address)
                    <i class="fas fa-map-pin text-xs flex-shrink-0"></i>
                @else
                    <i class="fas fa-user-tie text-xs flex-shrink-0 text-orange-500"></i>
                @endif
                <span class="line-clamp-1">{{ $item->subtitle }}</span>
            </div>
            @endif

            <div class="flex items-center gap-2 sm:gap-4 mt-2 sm:mt-3 text-xs sm:text-sm text-[color:var(--grey-text)]">
                <span class="flex items-center gap-1 sm:gap-1.5 whitespace-nowrap">
                    <i class="fas fa-home text-[color:var(--primary)]"></i>
                    {{ $item->properties_count }} {{ __('elan') }}
                </span>
                @if($item->phone)
                <span class="flex items-center gap-1 sm:gap-1.5 whitespace-nowrap">
                    <i class="fas fa-phone text-green-500"></i>
                    {{ $item->phone }}
                </span>
                @endif
            </div>

            <div class="mt-auto pt-3 sm:pt-4">
                <a href="{{ $item->url }}" onclick="event.stopPropagation()"
                   class="block w-full text-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-[color:var(--primary)] text-[color:var(--primary)] text-xs sm:text-sm font-medium hover:bg-[color:var(--primary)] hover:text-white transition-all">
                    {{ __('View Profile') }}
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
@endif
