@props(['nearby' => []])

<section id="what-is-nearby">
    <div class="what-is-nearby">
        <h3>What’s Nearby?</h3>
        <p>
            Explore nearby amenities to precisely locate your property and identify surrounding conveniences,
            providing a comprehensive overview of the living environment and the property's convenience.
        </p>

        <div class="nearby-info flex flex-wrap gap-6">
            @foreach(array_chunk($nearby, ceil(count($nearby) / 2)) as $column)
                <ul class="nearby-info-list">
                    @foreach($column as $key => $value)
                        <li class="flex justify-between">
                            <p>{{ $key }}</p>
                            <p>{{ $value }}</p>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>
</section>
