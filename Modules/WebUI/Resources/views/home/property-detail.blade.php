@extends('webui::layout')
@props(['id', 'image', 'title', 'address', 'beds', 'baths', 'area', 'price'])

@section('content')
    <div class="property-detail">
        <h1>{{ $property['title'] }}</h1>
        <img src="{{ $property['image'] }}" alt="{{ $property['title'] }}">

        <p><strong>Address:</strong> {{ $property['address'] }}</p>
        <p><strong>Description:</strong> {{ $property['description'] }}</p>

        <h3>Basic Info</h3>
        <ul>
            <li>Beds: {{ $property['beds'] }}</li>
            <li>Baths: {{ $property['baths'] }}</li>
            <li>Area: {{ $property['area'] }} sqft</li>
            <li>Price: ${{ $property['price'] }}</li>
        </ul>

        <h3>Property Details</h3>
        <ul>
            <li>{{ $property['details']['units'] ?? '' }}</li>
            <li>{{ $property['details']['unit_mix'] ?? '' }}</li>
            <li>{{ $property['details']['building_size'] ?? '' }}</li>
            <li>{{ $property['details']['lot_size'] ?? '' }}</li>
            <li>{{ $property['details']['access'] ?? '' }}</li>
            <li>{{ $property['details']['metering'] ?? '' }}</li>
        </ul>

        <h3>Extra Information</h3>
        <ul>
            <li>ID: {{ $property['extra']['id'] ?? '' }}</li>
            <li>Price Text: {{ $property['extra']['price_text'] ?? '' }}</li>
            <li>Size: {{ $property['extra']['size'] ?? '' }}</li>
            <li>Rooms: {{ $property['extra']['rooms'] ?? '' }}</li>
            <li>Exact Beds: {{ $property['extra']['beds_exact'] ?? '' }}</li>
            <li>Year Built: {{ $property['extra']['year_built'] ?? '' }}</li>
            <li>Type: {{ $property['extra']['type'] ?? '' }}</li>
            <li>Status: {{ $property['extra']['status'] ?? '' }}</li>
            <li>Garage: {{ $property['extra']['garage'] ?? '' }}</li>
            <li>Rent: {{ $property['extra']['rent_price'] ?? '' }}</li>
        </ul>

        <h3>Amenities</h3>
        <ul>
            @foreach($property['amenities'] ?? [] as $amenity)
                <li>{{ $amenity }}</li>
            @endforeach
        </ul>

        <h3>Floor Plan</h3>
        <ul>
            <li>First Floor: {{ $property['floor_plan']['first_floor'] ?? '' }}</li>
            <li>Second Floor: {{ $property['floor_plan']['second_floor'] ?? '' }}</li>
        </ul>

        <h3>Nearby</h3>
        <ul>
            @foreach($property['nearby'] ?? [] as $key => $distance)
                <li>{{ $key }}: {{ $distance }}</li>
            @endforeach
        </ul>
    </div>
@endsection
