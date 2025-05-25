@extends('webui::layout')

@section('title', 'FAQs')


@section('content')
<x-settings-icon />
<x-scroll-to-top />
<section id="navigation" class="py-3">
    <div class="container mx-auto px-4 flex items-center gap-2">
        <a href="{{ route('home') }}" class="text-[color:var(--primary)] font-bold flex items-center hover:text-black">
            Home
        </a>
        <span class="text-gray-400">›</span>
        <a href="{{ route('listing') }}" class="text-gray-600">
            Blog details
        </a>
    </div>
</section>

<main>
    <section id="side ">
        <div class="container mx-auto px-3">
            <div class="side">
                <div class="left-side">
                    <div class="header">
                        <h2>{{ $blog['title'] }}</h2>

                        <div class="blog-short-info">
                            <div class="author">
                                <i class="bi bi-person"></i> {{ $blog['author'] }}
                            </div>

                            <div class="category">
                                <i class="bi bi-tags"></i> {{ $blog['category'] }}
                            </div>

                            <div class="comments">
                                <i class="bi bi-chat-dots"></i> 0
                            </div>

                            <div class="date">
                                <i class="bi bi-clock-history"></i> {{ $blog['date'] }}
                            </div>
                        </div>
                    </div>
                    <div class="description">
                        <p>
                            {{ $blog['description'] }}
                        </p>
                        <img src="{{ $blog['images'][0] }}" alt="">
                    </div>
                    <div class="understanding-stock">
                        <h3> Understanding Housing Stocks</h3>
                        <p>Housing stocks encompass companies involved in various aspects of the real estate industry, including homebuilders, developers, and related service providers. Factors influencing these stocks range from interest rates and economic indicators to trends in homeownership rates.</p>

                        <p>Pay close attention to economic indicators such as employment rates, GDP growth, and consumer confidence. A strong economy often correlates with increased demand for housing, benefiting related stocks.</p>
                    </div>
                    <div class="detail-box">
                        <p class="quote">“Lower rates can boost homebuying activity, benefiting housing stocks, while higher rates may have the opposite effect.”</p>
                        <p class="author">said Mike Fratantoni, MBA’s chief economist.</p>
                    </div>
                    <div class="images-additional">
                        <figure>
                           <img src="{{ $blog['images'][0] }}" alt="">  
                        </figure>
                        <figure>
                          <img src="{{ $blog['images'][0] }}" alt="">    
                        </figure>
                      
                    </div>
                        <div class="understanding-stock">
                        <h3>Identify Emerging Trends</h3>
                        <p>Housing stocks encompass companies involved in various aspects of the real estate industry, including homebuilders, developers, and related service providers. Factors influencing these stocks range from interest rates and economic indicators to trends in homeownership rates.</p>

                        <p>Pay close attention to economic indicators such as employment rates, GDP growth, and consumer confidence. A strong economy often correlates with increased demand for housing, benefiting related stocks.</p>
                    </div>

                    <div class="sosial">
                        <div class="tags">
                            <h3>Tags</h3>
                            <ul>
                                <li><a href="#">Real Estate</a></li>
                                <li><a href="#">Housing Market</a></li>
                                <li><a href="#">Investment</a></li>
                                <li><a href="#">Stocks</a></li>
                            </ul>
                        </div>
                        <div class="share">
                            <p>Share post with</p>
                            <div class="share-icons">
                                <a href="#"><i class="bi bi-facebook"></i></a>
                                <a href="#"><i class="bi bi-twitter"></i></a>
                                <a href="#"><i class="bi bi-linkedin"></i></a>
                                <a href="#"><i class="bi bi-instagram"></i></a>
                            </div>
                        </div>
                    </div>

                     <x-reviews />




                </div>
                <div class="right-side">
                    <p>Searh Blog</p>
                    <input type="text">

                </div>

            </div>
        </div>

    </section>

</main>

@endsection