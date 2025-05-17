@extends('webui::layout')

@section('title', 'Home')

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
<section id="settings-icon">
    <div class="container mx-auto px-4">
        <div class="settings-icon">
            <i class="bi bi-gear"></i>
        </div>
    </div>
</section>

<div class="gotop" id="scrollToTop">
    <svg class="progress-circle" width="45" height="45">
        <circle class="bg" cx="22.5" cy="22.5" r="18"></circle>
        <circle class="progress" cx="22.5" cy="22.5" r="18"></circle>
    </svg>
    <div class="up">
        <i class="bi bi-arrow-up-short"></i>
    </div>
</div>

<main>
    <div class="container  flex side mx-auto px-4">
        <section id="agencies">
            <div class="agencies-header">
                <div class="flex  flex-col lg:flex-row justify-between gap-4 mb-5">
                    <h2 class="text-2xl lg:text-4xl font-bold text-[color:var(--text-color)]">
                        Agencies
                    </h2>

                    <div class="flex flex-wrap items-center gap-2">
                        <button id="gridViewBtn" class="view-toggle active px-3 py-2 hover:bg-[color:var(--primary)] hover:text-white transition rounded-md border border-[var(--border-color)]">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </button>

                        <button id="listViewBtn" class="view-toggle px-3 py-2 rounded-md border hover:bg-[color:var(--primary)] hover:text-white transition border-[var(--border-color)]">
                            <i class="fas fa-list text-[color:var(--icon-grey)]"></i>
                        </button>




                        <div class="relative">
                            <button class="flex items-center border border-[var(--border-color)] px-4 py-2 rounded-md">
                                Oldest <i class="fas fa-chevron-down ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="agencies-cards">
                <div class="agencies-card">
                    <figure>
                        <img class="image-agency" src="webui/images/agencies-1.jpg" alt="House Image">
                    </figure>
                    <div class="logo">
                        <img src="webui/images/brand-7.jpg" alt="Logo">
                    </div>
                    <div class="text">
                        <div class="header">Lorem House</div>
                        <p>102 Ingraham St, Brooklyn, NY 11237</p>

                        <div class="desc">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ligula neque, ornare quis urna nec, congue hendrerit turpis. Quisque nec diam varius, iaculis enim aliquam...
                        </div>
                        <div class="info"><span>Listing:</span>7.328</div>
                        <div class="info"><span>Hotline:</span>+7-445-556-8337</div>
                        <div class="info"><span>Phone:</span>+7-445-556-8337</div>
                        <div class="info"><span>Email:</span>loremhouse@gmail.com</div>

                        <div class="footer">
                            <div class="icons">
                                <i class="bi bi-telephone-fill"></i>
                                <i class="bi bi-envelope-fill"></i>
                                <i class="bi bi-globe2"></i>
                            </div>
                            <div class="details-button">
                                <a href="#" class="relative inline-block px-6 py-3 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                    <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                    <span class="relative z-10">Details</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                  <div class="agencies-card">
                    <figure>
                        <img class="image-agency" src="webui/images/agencies-1.jpg" alt="House Image">
                    </figure>
                    <div class="logo">
                        <img src="webui/images/brand-7.jpg" alt="Logo">
                    </div>
                    <div class="text">
                        <div class="header">Lorem House</div>
                        <p>102 Ingraham St, Brooklyn, NY 11237</p>

                        <div class="desc">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ligula neque, ornare quis urna nec, congue hendrerit turpis. Quisque nec diam varius, iaculis enim aliquam...
                        </div>
                        <div class="info"><span>Listing:</span>7.328</div>
                        <div class="info"><span>Hotline:</span>+7-445-556-8337</div>
                        <div class="info"><span>Phone:</span>+7-445-556-8337</div>
                        <div class="info"><span>Email:</span>loremhouse@gmail.com</div>

                        <div class="footer">
                            <div class="icons">
                                <i class="bi bi-telephone-fill"></i>
                                <i class="bi bi-envelope-fill"></i>
                                <i class="bi bi-globe2"></i>
                            </div>
                            <div class="details-button">
                                <a href="#" class="relative inline-block px-6 py-3 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                    <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                    <span class="relative z-10">Details</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                  <div class="agencies-card">
                    <figure>
                        <img class="image-agency" src="webui/images/agencies-1.jpg" alt="House Image">
                    </figure>
                    <div class="logo">
                        <img src="webui/images/brand-7.jpg" alt="Logo">
                    </div>
                    <div class="text">
                        <div class="header">Lorem House</div>
                        <p>102 Ingraham St, Brooklyn, NY 11237</p>

                        <div class="desc">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ligula neque, ornare quis urna nec, congue hendrerit turpis. Quisque nec diam varius, iaculis enim aliquam...
                        </div>
                        <div class="info"><span>Listing:</span>7.328</div>
                        <div class="info"><span>Hotline:</span>+7-445-556-8337</div>
                        <div class="info"><span>Phone:</span>+7-445-556-8337</div>
                        <div class="info"><span>Email:</span>loremhouse@gmail.com</div>

                        <div class="footer">
                            <div class="icons">
                                <i class="bi bi-telephone-fill"></i>
                                <i class="bi bi-envelope-fill"></i>
                                <i class="bi bi-globe2"></i>
                            </div>
                            <div class="details-button">
                                <a href="#" class="relative inline-block px-6 py-3 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                    <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                    <span class="relative z-10">Details</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                  <div class="agencies-card">
                    <figure>
                        <img class="image-agency" src="webui/images/agencies-1.jpg" alt="House Image">
                    </figure>
                    <div class="logo">
                        <img src="webui/images/brand-7.jpg" alt="Logo">
                    </div>
                    <div class="text">
                        <div class="header">Lorem House</div>
                        <p>102 Ingraham St, Brooklyn, NY 11237</p>

                        <div class="desc">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ligula neque, ornare quis urna nec, congue hendrerit turpis. Quisque nec diam varius, iaculis enim aliquam...
                        </div>
                        <div class="info"><span>Listing:</span>7.328</div>
                        <div class="info"><span>Hotline:</span>+7-445-556-8337</div>
                        <div class="info"><span>Phone:</span>+7-445-556-8337</div>
                        <div class="info"><span>Email:</span>loremhouse@gmail.com</div>

                        <div class="footer">
                            <div class="icons">
                                <i class="bi bi-telephone-fill"></i>
                                <i class="bi bi-envelope-fill"></i>
                                <i class="bi bi-globe2"></i>
                            </div>
                            <div class="details-button">
                                <a href="#" class="relative inline-block px-6 py-3 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                    <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                    <span class="relative z-10">Details</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
        </section>
        <section id="contact">
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
            <div class="feature-listings">
                <h3>Featured Listings</h3>
                <div class="featured-listings-cards">

                    <!-- Card 1 -->
                    <div class="featured-listing-card">
                        <figure>
                            <img class="image-agency" src="webui/images/agencies-1.jpg" alt="House Image">
                        </figure>
                        <div class="card-info">
                            <h4>Casa Lomas de Machalí Machas</h4>
                            <div class="details">
                                <span>3 <span class="grey">Bed</span></span>
                                <span>3 <span class="grey">Bath</span></span>
                                <span>4,043 <span class="grey">Sqft</span></span>
                            </div>
                            <div class="price">$7250,00</div>
                        </div>
                    </div>
  <div class="featured-listing-card">
                        <figure>
                            <img class="image-agency" src="webui/images/agencies-1.jpg" alt="House Image">
                        </figure>
                        <div class="card-info">
                            <h4>Casa Lomas de Machalí Machas</h4>
                            <div class="details">
                                <span>3 <span class="grey">Bed</span></span>
                                <span>3 <span class="grey">Bath</span></span>
                                <span>4,043 <span class="grey">Sqft</span></span>
                            </div>
                            <div class="price">$7250,00</div>
                        </div>
                    </div>





                </div>
            </div>
            <div class="connect">
                <img src="{{ asset('webui/images/ads.jpg') }}" alt="Elegant studio flat" />
                <div class="content">
                    <div class="flex items-center space-x-2">
                        <div class="bg-orange-400 p-2 rounded-full text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 6 7 13 7 13s7-7 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
                            </svg>
                        </div>

                        <span class="font-bold text-lg text-white">PROTY</span> <span
                            class="text-sm text-white">Real Estate</span>
                    </div>
                    <div class="text">
                        <h2>We can help you find a<br>local real estate agent</h2>
                        <p>Connect with a trusted agent who knows the market inside out - whether you're buying or selling.</p>
                        <button>Connect with an agent</button>
                    </div>

                </div>
            </div>



        </section>
    </div>




    </div>

</main>


@endsection