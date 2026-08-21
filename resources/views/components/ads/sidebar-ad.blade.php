@props([
    'position' => 'left',   // left | right
])

@php
    $isRight = $position === 'right';
    $href = $isRight ? route('add-property') : route('listing');
@endphp

<aside class="hidden xl:block w-[220px] 2xl:w-[260px] 3xl:w-[300px] shrink-0 relative z-10 self-stretch">
    <div class="sticky top-[78px] z-10 h-[calc(100vh-88px)] w-full">
        <a href="{{ $href }}"
           class="group relative block w-full h-full rounded-2xl overflow-hidden border border-gray-200/90 shadow-sm hover:shadow-lg transition duration-300">
            @if($isRight)
                {{-- Sağ: brend loqolu reklam kartı (yerli assetlər) --}}
                <div class="absolute inset-0 bg-gradient-to-b from-orange-500 via-orange-600 to-orange-800"></div>
                <div class="absolute inset-0 opacity-15"
                     style="background-image: radial-gradient(circle at 20% 50%, white 1.5px, transparent 1.5px); background-size: 20px 20px;"></div>
                <div class="relative h-full flex flex-col items-center justify-center text-center px-5 py-6">
                    <img src="/images/metrajlogo.png" alt="Metraj.az"
                         class="w-28 mb-5 drop-shadow-sm">
                    <p class="text-white font-extrabold text-xl leading-snug">Elanınızı<br>pulsuz yerləşdirin</p>
                    <p class="text-orange-100 text-xs mt-2 leading-relaxed">Minlərlə alıcıya birbaşa çatın</p>
                    <span class="mt-5 inline-flex items-center gap-2 bg-white text-orange-600 font-bold text-xs px-5 py-2.5 rounded-xl shadow-sm group-hover:bg-orange-50 transition">
                        Elan əlavə et <i class="bi bi-arrow-right text-sm"></i>
                    </span>
                </div>
            @else
                {{-- Sol: şəkil reklamı (yerli asset) --}}
                <img src="/images/ads.jpg" alt="Metraj.az — İdeal evinizi tapın"
                     class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"/>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                <div class="absolute bottom-0 inset-x-0 p-4 pb-5">
                    <p class="text-white font-extrabold text-lg leading-snug drop-shadow">İdeal evinizi<br>tapın</p>
                    <p class="text-orange-100 text-[11px] mt-1">Yüzlərlə elan arasından seçim edin</p>
                </div>
            @endif
        </a>
    </div>
</aside>
