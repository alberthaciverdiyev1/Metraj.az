@extends('webui::layout')
@props(['id', 'image', 'title', 'address', 'beds', 'baths', 'area', 'price'])
@section('content')
<x-breadcrumb :items="[
    ['label' => 'Home', 'url' => route('home')],
    ['label' => 'Property Listing ', 'url' => route('listing')],
]" />
<x-settings-icon />
<x-scroll-to-top />
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
        <div class="lg:flex-row md:flex-row flex-col side">
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
                <x-property-map
                    :mapData="$property['map']"
                    :zoom="15" />

                <x-floor-plan :plans="$property['floor_plan']" />


                <div class="attachment-box">
                    <h2>File Attachments</h2>
                    <div class="file-list">
                        <div class="file-item">
                            <div class="file-icon">
                                <i class="fa-regular fa-file fa-2x"></i>
                                <span>PDF</span>
                            </div>
                            <div class="file-name">Villa-Document.pdf</div>
                            <div class="download-icon">
                                <a href="https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf" download>
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>

                        <div class="file-item">
                            <div class="file-icon">
                                <i class="fa-regular fa-file fa-2x"></i>
                                <span style="background: #00bcd4;">DOC</span>
                            </div>
                            <div class="file-name">Villa-Document.docx</div>
                            <div class="download-icon">
                                <a href="https://file-examples.com/wp-content/uploads/2017/02/file-sample_100kB.doc" download>
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <x-virtual-tour :property="$property" />
                <x-loan-calculator />
                <x-nearby :nearby="$property['nearby']" />

                <x-reviews

                    title="Guest Reviews"
                    formTitle="Add Reviews" />


            </div>
            <div class="side-right">
                <div class="sticky">
                    <div class="contact-card">
                        <h3>Contact Sellers</h3>
                        <div class="flex avatar-info">
                            <img src="https://themesflat.co/html/proty/images/avatar/seller.jpg" alt="Shara Conner" class="avatar">
                            <div class="avatar-info-text">
                                <h4>Shara Conner</h4>
                                <p><a href="tel:1-333-345-6868" class="phone"><i class="bi bi-telephone"></i> 1-333-345-6868</a></p>
                                <p><a href="mailto:themesflat@gmail.com" class="email">
                                        <i class="bi bi-envelope"></i> themesflat@gmail.com</a></p>
                            </div>

                        </div>



                        <form id="contact-form">
                            <input type="text" id="name" name="name" placeholder="Full Name" required>
                            <textarea id="message" name="message" placeholder="How can an agent help" required></textarea>
                            <button
                                class="send-message-btn all-btn button-hover"
                                type="submit">Send message</button>
                        </form>
                    </div>
                    <x-connect-agent />
                    <x-moreabouttisproperty />

                </div>





            </div>
        </div>


    </div>
</section>
@endsection