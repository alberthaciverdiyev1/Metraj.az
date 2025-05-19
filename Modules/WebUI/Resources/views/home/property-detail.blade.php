@extends('webui::layout')
@props(['id', 'image', 'title', 'address', 'beds', 'baths', 'area', 'price'])

@section('content')


<section id="navigation" class="py-3">
    <div class="container mx-auto px-4 flex items-center gap-2">
        <a href="{{ route('home') }}" class="text-[color:var(--primary)] font-bold flex items-center hover:text-black">
            Home
        </a>
        <span class="text-gray-400">›</span>
        <a href="{{ route('listing') }}" class="text-gray-600">
            Property Listing
        </a>
    </div>
</section>

<section id="listing-detail-gallery">
    <div class="container mx-auto px-4">
        <div class="gallery-grid">
            <figure onclick="openModal(0)">
                <img src="{{ asset($property['altimages']['first_image'] ?? '') }}" alt="Property Image">
            </figure>
            <figure onclick="openModal(1)">
                <img src="{{ asset($property['altimages']['second_image'] ?? '') }}" alt="Property Image">
            </figure>
            <figure onclick="openModal(2)">
                <img src="{{ asset($property['altimages']['third_image'] ?? '') }}" alt="Property Image">
            </figure>
            <figure onclick="openModal(3)">
                <img src="{{ asset($property['altimages']['fourth_image'] ?? '') }}" alt="Property Image">
            </figure>
        </div>
    </div>



    <div id="modal" class="modal">
        <div class="modal-header">
            <span id="counter">1/4</span>
            <div class="modal-actions">
                <button onclick="startSlideshow()"><i class="bi bi-play-fill"></i></button>
                <button onclick="toggleFullscreen()"><i class="bi bi-fullscreen"></i></button>
                <button onclick="toggleThumbnails()"><i class="bi bi-images"></i></button>
                <button onclick="shareImage()"><i class="bi bi-share"></i></button>
                <button onclick="closeModal()"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
        <div class="modal-navigation">
            <button onclick="prevImage()"><i class="bi bi-arrow-left"></i></button>
            <img id="modal-image" src="" alt="Modal Image">
            <button onclick="nextImage()"><i class="bi bi-arrow-right"></i></button>
        </div>
        <div class="thumbnails" id="thumbnails">
            <img src="{{ asset($property['altimages']['first_image'] ?? '') }}" onclick="openModal(0)">
            <img src="{{ asset($property['altimages']['second_image'] ?? '') }}" onclick="openModal(1)">
            <img src="{{ asset($property['altimages']['third_image'] ?? '') }}" onclick="openModal(2)">
            <img src="{{ asset($property['altimages']['fourth_image'] ?? '') }}" onclick="openModal(3)">
        </div>
    </div>


    <div id="imgModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content modal-content-custom">
                <div class="modal-top-left" id="imageCount">1 / 4</div>
                <div class="modal-top-right">
                    <i class="bi bi-play-btn" title="Start Slideshow"></i>
                    <i class="bi bi-fullscreen" title="Fullscreen"></i>
                    <i class="bi bi-grid" title="Thumbnails"></i>
                    <i class="bi bi-share" title="Share"></i>
                    <i class="bi bi-x-lg" data-bs-dismiss="modal" title="Close"></i>
                </div>
                <span class="modal-control left" onclick="changeImage(-1)"><i class="bi bi-arrow-left"></i></span>
                <img id="modalImage" class="modal-img">
                <span class="modal-control right" onclick="changeImage(1)"><i class="bi bi-arrow-right"></i></span>
            </div>
        </div>
    </div>


</section>
<section id="side">
    <div class="container mx-auto px-4">
        <div class="side">
            <div class="side-left">
                <div class="info-box">
                    <div class="info-box-title mb-4">
                        <h2>{{ $property['title'] }}</h2>
                        <h2>${{ $property['price'] }} <span>/month</span></h2>
                    </div>

                    <div class="info-box-desc">
                        <div class="left">
                            <div class="adress">
                                <i class="bi bi-geo-alt me-2"></i>
                                <span>{{ $property['address'] }}</span>
                            </div>
                            <div class="flex bed-info">
                                <div class="me-3 bed-info-desc">{{ $property['beds'] }} <span>Bed</span></div>
                                <div class="me-3 bed-info-desc "> {{ $property['baths'] }} <span>
                                        Bath
                                    </span></div>
                                <div class="bed-info-desc">{{ $property['area'] }}<span>
                                        Sqft
                                    </span> </div>
                            </div>



                        </div>
                        <div class="right">
                            <i class="bi bi-heart"></i>
                            <i class="bi bi-arrow-left-right"></i>
                            <i class="bi bi-printer"></i>
                            <i class="bi bi-share"></i>

                        </div>

                    </div>



                    <div class="flex flex-wrap gap-4 mb-4">
                        <div class="buttons-desc ">
                            <div class=" button-desc">

                                <i class="bi bi-house-door"></i>
                                <div class="">
                                    <p>ID:</p>
                                    <p>{{ $property['extra']['id'] }}</p>
                                </div>


                            </div>
                            <div class=" button-desc">

                                <i class="bi bi-sliders"></i>
                                <div class="">
                                    <p>Type</p>
                                    <p>{{ $property['extra']['type'] }}</p>
                                </div>


                            </div>
                            <div class=" button-desc">

                                <i class="bi bi-house-door"></i>
                                <div class="">
                                    <p>ID:</p>
                                    <p>{{ $property['extra']['id'] }}</p>
                                </div>


                            </div>
                            <div class=" button-desc">

                                <i class="bi bi-house-door"></i>
                                <div class="">
                                    <p>ID:</p>
                                    <p>{{ $property['extra']['id'] }}</p>
                                </div>


                            </div>
                            <div class=" button-desc">

                                <i class="bi bi-house-door"></i>
                                <div class="">
                                    <p>ID:</p>
                                    <p>{{ $property['extra']['id'] }}</p>
                                </div>


                            </div>
                            <div class=" button-desc">

                                <i class="bi bi-house-door"></i>
                                <div class="">
                                    <p>ID:</p>
                                    <p>{{ $property['extra']['id'] }}</p>
                                </div>


                            </div>
                            <div class=" button-desc">

                                <i class="bi bi-house-door"></i>
                                <div class="">
                                    <p>ID:</p>
                                    <p>{{ $property['extra']['id'] }}</p>
                                </div>


                            </div>
                            <div class=" button-desc">

                                <i class="bi bi-house-door"></i>
                                <div class="">
                                    <p>ID:</p>
                                    <p>{{ $property['extra']['id'] }}</p>
                                </div>


                            </div>


                        </div>


                    </div>

                    <!--back gelende iconlari elave etmek-->
                    <!--                           
                            <div><i class="bi bi-house"></i> <strong>Garages:</strong> 1</div>
                            <div><strong>Bedrooms:</strong> 2 Rooms</div>
                            <div><i class="bi bi-droplet"></i> <strong>Bathrooms:</strong> 2 Rooms</div>
                            <div><i class="bi bi-fullscreen"></i> <strong>Land Size:</strong> 2,000 SqFt</div>
                            <div><i class="bi bi-hammer"></i> <strong>Year Built:</strong> 2023</div>
                            <div><i class="bi bi-rulers"></i> <strong>Size:</strong> 900 SqFt</div> -->
                    <button class="question-btn all-btn button-hover">Ask a question</button>

                </div>
                <section id="video" class="video-section">
                    <div class="video-container">
                        <iframe id="video-frame" src="{{ $property['video'] }}" allowfullscreen></iframe>
                        <div class="play-button">
                            <i class="bi bi-play-fill"></i>
                        </div>
                    </div>
                </section>
                <x-property-details
                    :details="$property['details']"
                    :extra="$property['extra']" />

                <x-amenities :amenities="$property['amenities']" :columns="3" />
                <div class="map-detail">
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12120.809245605833!2d49.6735533!3d40.581289549999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x403097a58506ef15%3A0x698ff01b1e2a5565!2sSumgait%20beach!5e0!3m2!1sen!2saz!4v1747627310183!5m2!1sen!2saz" width="100%" height="450" style="border-radius:24px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="map-info px-4">
                        <ul class="map-info-list">
                            <li>
                                <p>Adress</p>
                                <p> {{ $property['map']['address'] }}</p>
                            </li>
                            <li>
                                <p>City</p>
                                <p> {{ $property['map']['city'] }}</p>
                            </li>
                            <li>
                                <p>State</p>
                                <p> {{ $property['map']['state'] }}</p>
                            </li>
                            
                            </li>
                        </ul>
                        <ul class="map-info-list">
                            <li>
                                <p>Postal Code</p>
                                <p> {{ $property['map']['postal_code'] }}</p>
                            </li>
                            <li>
                                <p>Country</p>
                                <p> {{ $property['map']['country'] }}</p>
                                <li>
                                <p>Postal Code</p>
                                <p> {{ $property['map']['postal_code'] }}</p>
                            </li>
                             
                          
                        </ul>
                    </div>
                </div>

                <div class="floor-plan">
                    <h3>Floor Plan</h3>
                    <div class="accordions-floor-pla">
                        <div class="floor-plan-accordion">
                            <div class="left">
                                <i></i>
                                <h4>First Floor</h4>
                              
                            </div>
                            <div class="right">
                                <div class="bed">
                                    
                                </div>

                            </div>


                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="side-right">
            kdsjk

        </div>

    </div>
</section>






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