<div class="amenities-and-features">
    <h3>Amenities And Features</h3>
    <div class="boxes-feature">
        @for($i = 0; $i < $columns; $i++)
            <div class="box-feature">
                <ul>
                    @foreach(array_slice($amenities, $i * ceil(count($amenities)/$columns), ceil(count($amenities)/$columns)) as $item)
                        <li class="feature-item">
                          
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endfor
    </div>
</div>