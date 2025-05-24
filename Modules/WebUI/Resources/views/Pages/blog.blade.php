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
            Blog grid
        </a>
    </div>
</section>
<header>
    <div class="container mx-auto px-4">
        <div class="flex  flex-col lg:flex-row justify-between gap-4 mb-5 mt-6">
            <h2 class="text-2xl lg:text-4xl font-bold text-[color:var(--text-color)]">
                Property listing
            </h2>

            <div class="flex flex-wrap items-center gap-2">


                <button id="gridViewBtn" class="px-3 grid-btn py-2 rounded-md active-filter" data-view="grid">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>

                <button id="listViewBtn" class=" px-3 py-2 list border border-[var(--border-color)] rounded-md" data-view="list">
                    <i class="fas fa-list text-[color:var(--icon-grey)] "></i>
                </button>





            </div>
        </div>
    </div>
</header>
<main>
    <section id="blog-cards">
        <div class="container mx-auto px-3">
            <div class="blog-cards">
                <div class="blog-card">
                    <div class="blog-card-image">
                        <img src="https://themesflat.co/html/proty/images/blog/blog-grid-1.jpg" alt="blog-card-image">
                        <span>Real estate</span>
                    </div>
                    <div class="blog-card-info">
                        <div class="blog-time">
                            <i><i class="bi bi-clock-history"></i></i>
                            <p>26 August, 2024</p>
                        </div>
                        <div class="blog-title">
                            <h3>How to choose the rightproperty for your family...</h3>

                        </div>
                        <div class="blog-button">Read More <i class="bi bi-arrow-right-circle"></i></div>
                    </div>

                </div>
                <div class="blog-card">
                    <div class="blog-card-image">
                        <img src="https://themesflat.co/html/proty/images/blog/blog-grid-1.jpg" alt="blog-card-image">
                        <span>Real estate</span>
                    </div>
                    <div class="blog-card-info">
                        <div class="blog-time">
                            <i><i class="bi bi-clock-history"></i></i>
                            <p>26 August, 2024</p>
                        </div>
                        <div class="blog-title">
                            <h3>How to choose the rightproperty for your family...</h3>

                        </div>
                        <div class="blog-button">Read More <i class="bi bi-arrow-right-circle"></i></div>
                    </div>

                </div>
                <div class="blog-card">
                    <div class="blog-card-image">
                        <img src="https://themesflat.co/html/proty/images/blog/blog-grid-1.jpg" alt="blog-card-image">
                        <span>Real estate</span>
                    </div>
                    <div class="blog-card-info">
                        <div class="blog-time">
                            <i><i class="bi bi-clock-history"></i></i>
                            <p>26 August, 2024</p>
                        </div>
                        <div class="blog-title">
                            <h3>How to choose the rightproperty for your family...</h3>

                        </div>
                        <div class="blog-button">Read More <i class="bi bi-arrow-right-circle"></i></div>
                    </div>

                </div>
            </div>
        </div>

    </section>
      <div class="pagination-blog">
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


</main>


@endsection