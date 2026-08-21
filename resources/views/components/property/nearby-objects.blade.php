<section id="what-is-nearby">
    <div class="what-is-nearby">
        <h3 class="text-xl font-semibold m-0 p-0">{{ __('Nearby objects') }}</h3>
        <div class="nearby-info mt-0 pt-0 grid grid-cols-1 md:grid-cols-3 w-[100%] gap-7">
            @php
                $cols = $column ?? 3;
                $chunks = $objects->chunk(ceil(max($objects->count(), 1) / $cols));
            @endphp
            @foreach($chunks as $chunk)
            <div class="boxes-feature nearby-features">
                <ul class="nearby-info-list space-y-2 gap-0">
                    @foreach($chunk as $obj)
                    <li class="feature-item nearby-item gap-0">
                        <i class="bi bi-geo-alt text-[var(--primary)]"></i>
                        <span>{{ $obj->name ?? $obj }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</section>
