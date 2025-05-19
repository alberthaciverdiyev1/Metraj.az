<div class="map-detail">
    <div class="map">
          <!-- https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q={{ $mapData['latitude'] }},{{ $mapData['longitude'] }}&zoom={{ $zoom }} -->
        @if(!empty($mapData['latitude']) && !empty($mapData['longitude']))
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12120.809245605833!2d49.6735533!3d40.581289549999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x403097a58506ef15%3A0x698ff01b1e2a5565!2sSumgait%20beach!5e0!3m2!1sen!2saz!4v1747627310183!5m2!1sen!2saz"
                width="100%"
                height="450"
                style="border-radius:24px;"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        @else
            <div class="map-placeholder">
                <p>Map location not available</p>
            </div>
        @endif
    </div>
    <div class="map-info px-4">
        <ul class="map-info-list">
            <li class="flex">
                <p>Address</p>
                <p>{{ $mapData['address'] ?? 'Not specified' }}</p>
            </li>
            <li class="flex">
                <p>City</p>
                <p>{{ $mapData['city'] ?? 'Not specified' }}</p>
            </li>
            <li class="flex">
                <p>State/Region</p>
                <p>{{ $mapData['state'] ?? 'Not specified' }}</p>
            </li>
        </ul>
        <ul class="map-info-list">
            <li class="flex">
                <p>Postal Code</p>
                <p>{{ $mapData['postal_code'] ?? 'Not specified' }}</p>
            </li>
            <li class="flex">
                <p>Country</p>
                <p>{{ $mapData['country'] ?? 'Not specified' }}</p>
            </li>
            <li class="flex">
                <p>Area</p>
                <p>{{ $mapData['area'] ?? '0' }} sqft</p>
            </li>
        </ul>
    </div>
</div>

