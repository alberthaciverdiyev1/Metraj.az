@extends('webui::layout')

@section('title', 'Home')

@section('content')
<x-breadcrumb :items="[
    ['label' => 'Home', 'url' => route('home')],
    ['label' => 'Agencies', 'url' => route('agencies')],
]" />
<x-settings-icon />
<x-scroll-to-top />

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
                @foreach($agencies as $id => $agency)
                <div class="agencies-card">
                    <figure>
                        <img class="image-agency" src="{{ $agency['image'] }}" alt="House Image">
                    </figure>
                    <div class="logo">
                        <img src="{{ $agency['logo'] }}" alt="Logo">
                    </div>
                    <div class="text">
                        <div class="header">{{ $agency['name'] }}</div>
                        <p>{{ $agency['address'] }}</p>

                        <div class="desc">{{ \Illuminate\Support\Str::limit($agency['about'], 150) }}</div>
                        <div class="info"><span>Listing:</span> {{ $agency['listing_count'] }}</div>
                        <div class="info"><span>Hotline:</span> {{ $agency['hotline'] }}</div>
                        <div class="info"><span>Phone:</span> {{ $agency['phone'] }}</div>
                        <div class="info"><span>Email:</span> {{ $agency['email'] }}</div>

                        <div class="footer">
                            <div class="icons">
                                <i class="bi bi-telephone-fill"></i>
                                <i class="bi bi-envelope-fill"></i>
                                <i class="bi bi-globe2"></i>
                            </div>
                            <div class="details-button">
                                <a href="{{ route('agency.details', $id) }}" class="relative inline-block px-6 py-3 rounded-xl border border-[color:var(--primary)] text-sm text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
                                    <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
                                    <span class="relative z-10">Details</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
        </section>
        <section id="contact">
            <x-contact-form />
            <x-feature--listings />
            <x-connect-agent />





        </section>
    </div>




    </div>

</main>


@endsection