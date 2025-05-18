<section id="property-detail">
    <h3>Property Details</h3>
    <p class="units">
        {{ $details['units'] ?? '' }} {{ $details['unit_mix'] ?? '' }}. 
        The building is a total of {{ $details['building_size'] ?? '' }} and situated on a {{ $details['lot_size'] ?? '' }} lot. 
        {{ $details['access'] ?? '' }} The building is {{ $details['metering'] ?? '' }}.
    </p>
    <div class="read-more">Read more
        <i class="bi bi-arrow-down-circle"></i>
    </div>
    <div class="box">
        <ul>
            <li class="flex">
                <p class="fw-6">ID</p>
                <p>{{ $extra['id'] }}</p>
            </li>
            <li class="flex">
                <p class="fw-6">Price</p>
                <p>{{ $extra['price_text'] }}</p>
            </li>
            <li class="flex">
                <p class="fw-6">Size</p>
                <p>{{ $extra['size'] }}</p>
            </li>
            <li class="flex">
                <p class="fw-6">Rooms</p>
                <p>{{ $extra['rooms'] }}</p>
            </li>
            <li class="flex">
                <p class="fw-6">Baths</p>
                <p>{{ $extra['baths'] ?? 'N/A' }}</p>
            </li>
        </ul>
        <ul>
            <li class="flex">
                <p class="fw-6">Beds</p>
                <p>{{ $extra['beds_exact'] }}</p>
            </li>
            <li class="flex">
                <p class="fw-6">Year built</p>
                <p>{{ $extra['year_built'] }}</p>
            </li>
            <li class="flex">
                <p class="fw-6">Type</p>
                <p>{{ $extra['type'] }}</p>
            </li>
            <li class="flex">
                <p class="fw-6">Status</p>
                <p>{{ $extra['status'] }}</p>
            </li>
            <li class="flex">
                <p class="fw-6">Garage</p>
                <p>{{ $extra['garage'] }}</p>
            </li>
        </ul>
    </div>
</section>