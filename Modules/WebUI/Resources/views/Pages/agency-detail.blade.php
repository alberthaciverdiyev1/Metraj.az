@extends('webui::layout')

@section('title', $agency['name'])

@section('content')
<x-breadcrumb :items="[
    ['label' => 'Home', 'url' => route('home')],
    ['label' => 'Agencies', 'url' => route('agencies')],
]" />
<x-settings-icon />
<x-scroll-to-top />
<section id="side">
    <div class="container mx-auto px-4 ">
        <div class="side-agency">
            <section class="side-left">
                <div class="agencies-header">
                    <img src="{{ $agency['image'] }}" alt="">
                    <div class="agency-header-info">
                        <div class="logo">
                            <img src="{{ $agency['logo'] }}" alt="Logo">
                        </div>
                        <div class="">
                            <h2 class="text-2xl lg:text-4xl font-bold text-[color:var(--text-color)]">
                                {{ $agency['name'] }}
                            </h2>
                            <p class="text-gray-600">
                                <i class="bi bi-geo-alt"></i>
                                {{ $agency['address'] }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="agencies-info">
                    <h2>About {{ $agency['name'] }}</h2>
                    <p>{{ $agency['description'] }}</p>
                    <h2 class="title">Location</h2>
                    <div class="map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12120.809245605833!2d49.6735533!3d40.581289549999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x403097a58506ef15%3A0x698ff01b1e2a5565!2sSumgait%20beach!5e0!3m2!1sen!2saz!4v1747627310183!5m2!1sen!2saz"
                            width="100%"
                            height="450"
                            style="border-radius:24px;"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                </div>
                <div class="listing-tab">
                    <div class="listing-tab-header">
                        <h2 class="title">Listing</h2>
                        <div class="tab-buttons">
                            <button class="tab-btn active" data-filter="all">All</button>
                            <button class="tab-btn" data-filter="rent">For rent</button>
                            <button class="tab-btn" data-filter="sale">For sale</button>
                        </div>
                    </div>


                    <div class="listing-cards g-3" id="property-listing">
                        @foreach ($properties->chunk(3) as $pageIndex => $chunk)
                        @foreach ($chunk as $property)
                        <x-property-card
                            class="properti-card property-card"
                            :id="$property['id']"
                            :image="$property['image']"
                            :title="$property['title']"
                            :address="$property['address']"
                            :beds="$property['beds']"
                            :baths="$property['baths']"
                            :area="$property['area']"
                            :price="$property['price']"
                            data-status="{{ strtolower(str_replace(' ', '', $property['extra']['status'] ?? 'all')) }}"
                            data-page="{{ $pageIndex + 1 }}" />

                        @endforeach
                        @endforeach
                    </div>

                    <div class="pagination-agencies">
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item active" aria-current="page"><a class="page-link" href="#">2</a></li>
                                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                                <li class="page-item"><a class="page-link" href="#">20</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>


                </div>
            </section>
            <section class="side-right sticky">
                <div class="">
                    <x-contact-form />
                    <x-feature--listings />
                    <x-connect-agent />
                </div>



            </section>
        </div>


    </div>

</section>


@endsection