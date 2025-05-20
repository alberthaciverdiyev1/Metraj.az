@extends('webui::layout')

@section('title', $agency['name'])

@section('content')
<section id="navigation" class="py-3">
    <div class="container mx-auto px-4 flex items-center gap-2">
        <a href="{{ route('home') }}" class="text-[color:var(--primary)] font-bold flex items-center hover:text-black">
            Home
        </a>
        <span class="text-gray-400">›</span>
        <a href="{{ route('agencies') }}" class="text-gray-600">
            Agencies
        </a>
    </div>
</section>
<x-settings-icon />
<div class="gotop" id="scrollToTop">
    <svg class="progress-circle" width="45" height="45">
        <circle class="bg" cx="22.5" cy="22.5" r="18"></circle>
        <circle class="progress" cx="22.5" cy="22.5" r="18"></circle>
    </svg>
    <div class="up">
        <i class="bi bi-arrow-up-short"></i>
    </div>
</div>
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


                    <div class="listing-cards">

                        @foreach ($properties as $property)
                        @foreach ($properties as $property)
                        <x-property-card
                            :id="$property['id']"
                            :image="$property['image']"
                            :title="$property['title']"
                            :address="$property['address']"
                            :beds="$property['beds']"
                            :baths="$property['baths']"
                            :area="$property['area']"
                            :price="$property['price']"
                            data-status="{{ strtolower(str_replace(' ', '', $property['extra']['status'] ?? 'all')) }}"
                            class="property-card" />
                        @endforeach



                        @endforeach

                    </div>


                </div>
            </section>
            <section class="side-right sticky">
                <div class="">
                    <section id="contact-detail">
                        <div class="contact-form">
                            <form action="">
                                <h3>Contact Me</h3>
                                <input type="text" placeholder="Your name" />
                                <input type="email" placeholder="Email" />
                                <input type="tel" placeholder="Phone" />
                                <textarea placeholder="Message"></textarea>
                                <div class="buttons">
                                    <button class="send-btn">
                                        <i class="fa fa-envelope"></i> Send message
                                    </button>
                                    <button class="call-btn">
                                        <i class="fa fa-phone"></i> Call
                                    </button>
                                </div>
                            </form>
                        </div>

                    </section>
                    <div class="featured-listing">
                        <!-- burda ele bilki butun data ilk 5 denesini cixartmaliyam -->
                    </div>
                    <x-connect-agent />
                </div>



            </section>
        </div>


    </div>

</section>


@endsection