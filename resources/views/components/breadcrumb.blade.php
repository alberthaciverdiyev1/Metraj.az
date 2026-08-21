<section id="navigation" class="py-3">
    <div class="mx-auto px-4 text-[12px] lg:text-[20px] flex flex-row flex-nowrap items-center gap-2 mt-8 lg:pt-0 pt-0 sm:mt-10 xl:mt-10">
        @foreach($items as $item)
            @if(!$loop->first)
                <span class="text-gray-400">›</span>
            @endif
            @if(!empty($item['url']))
                <a href="{{ $item['url'] }}" class="{{ $loop->last ? 'text-[color:var(--primary)]' : 'text-gray-600 font-bold flex items-center hover:text-black' }}">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="text-[color:var(--primary)]">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </div>
</section>
