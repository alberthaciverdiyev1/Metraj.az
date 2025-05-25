@extends('webui::layout')

@section('title', 'FAQs')


@section('content')
<x-settings-icon />
<x-scroll-to-top />
<x-breadcrumb :items="[
    ['label' => 'Home', 'url' => route('home')],
    ['label' => 'Blog', 'url' => route('blog')],
]" />

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
                            <span>Tags:</span>
                            <a href="#">Personal</a>
                            <a href="#">Business</a>
                        </div>
                        <div class="share">
                            <span>Share this post:</span>
                            <div class="share-icons">
                                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                                <a href="#"><i class="bi bi-twitter-x"></i></a>
                                <a href="#"><i class="bi bi-linkedin"></i></a>
                                <a href="#"><i class="bi bi-instagram"></i></a>
                            </div>
                        </div>
                    </div>


                    <x-reviews
                        title="Comments"
                        formTitle="Leave A Comment" />
                </div>
                <div class="right-side">
                    <div class="blog-sidebar">
                        <div class="search-box">
                            <h3>Search Blog</h3>
                            <div class="input-group">
                                <i class="bi bi-search"></i>
                                <input type="text" placeholder="Search" />
                            </div>
                        </div>

                        <div class="categories">
                            <h3>Categories</h3>
                            <ul>
                                <li><a href="#">Market Updates <span>(50)</span></a></li>
                                <li><a href="#">Buying Tips <span>(69)</span></a></li>
                                <li><a href="#">Interior Inspiration <span>(69)</span></a></li>
                                <li><a href="#">Investment Insights <span>(25)</span></a></li>
                                <li><a href="#">Home Construction <span>(12)</span></a></li>
                                <li><a href="#">Legal Guidance <span>(12)</span></a></li>
                                <li><a href="#">Community Spotlight <span>(69)</span></a></li>
                            </ul>
                        </div>
                        <x-feature--listings />

                        <section class="newsletter-section">
                            <h2 class="newsletter-title">Join Our Newsletter</h2>
                            <p class="newsletter-description">
                                Signup to be the first to hear about exclusive deals, special offers<br>
                                and upcoming collections
                            </p>
                            <div class="newsletter-input-wrapper">
                                <input type="text" class="newsletter-input" placeholder="Search" />
                                <button class="newsletter-button">
                                    <i class="bi bi-send"></i>
                                </button>
                            </div>
                        </section>

                        <section class="tags-section">
                            <h2 class="tags-title">Popular Tags</h2>
                            <div class="tags-wrapper">
                                <span class="tag">Property</span>
                                <span class="tag">Office</span>
                                <span class="tag">Finance</span>
                                <span class="tag">Legal</span>
                                <span class="tag">Market</span>
                                <span class="tag">Invest</span>
                                <span class="tag">Renovate</span>
                            </div>
                        </section>

                        <x-connect-agent />

                    </div>


                </div>

            </div>
        </div>

    </section>

    <section id="relatied-posts">
        <div class="container mx-auto px-3">
            <div class="relation-posts">
                <h3>Related posts</h3>

                <div class="blog-cards">


                    @foreach($relatedPosts as $postId => $post)
                    <div class="blog-card">
                        <div class="blog-card-image">
                            <img src="{{ $post['images'][0] }}" alt="blog-card-image">
                            <span>{{ $post['category'] }}</span>
                        </div>
                        <div class="blog-card-info">
                            <div class="blog-time">
                                <i class="bi bi-clock-history"></i>
                                <p>{{ $post['date'] }}</p>
                            </div>
                            <div class="blog-title">
                                <h3>{{ Str::limit($post['title'], 50) }}</h3>
                            </div>
                            <a href="{{ route('blog.details', ['id' => $postId]) }}" class="blog-button">
                                Read More <i class="bi bi-arrow-right-circle"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>

    </section>

</main>

@endsection