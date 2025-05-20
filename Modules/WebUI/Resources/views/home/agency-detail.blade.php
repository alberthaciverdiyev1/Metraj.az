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
                                <i></i>
                                {{ $agency['address'] }}
                            </p>
                        </div>
                    </div>

            </section>
            <section class="side-right">
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

            </section>
        </div>


    </div>

</section>


@endsection