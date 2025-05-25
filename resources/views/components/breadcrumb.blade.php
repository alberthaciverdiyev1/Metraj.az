@props(['items' => []])

<section id="navigation" class="py-3">
    <div class="container mx-auto px-4 flex items-center gap-2">
        @foreach ($items as $item)
            @if (!$loop->first)
                <span class="text-gray-400">›</span>
            @endif

            @if (isset($item['url']))
                <a href="{{ $item['url'] }}"
                   class="{{ $loop->last ? 'text-gray-600' : 'text-[color:var(--primary)] font-bold flex items-center hover:text-black' }}">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="text-gray-600">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </div>
</section>
