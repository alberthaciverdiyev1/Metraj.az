@extends('webui::layout')

@section('title', 'FAQs')


@section('content')
<x-settings-icon />
<x-scroll-to-top />
<x-breadcrumb :items="[
    ['label' => 'Home', 'url' => route('home')],
    ['label' => 'Blog', 'url' => route('blog')],
]" />
<header>
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-start gap-4 md:gap-6">
            <h2 class="text-2xl lg:text-4xl font-bold py-4 text-[color:var(--text-color)]">
                Blog grid
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
                @foreach($blog as $id => $post)
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

                        <a href="{{ route('blog.details', ['id' => $id]) }}" class="blog-button">
                            Read More <i class="bi bi-arrow-right-circle"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

    </section>
    <div class="pagination-blog mx-auto">
        <nav aria-label="Page navigation" class="w-[20%] mx-auto py-3">
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