@extends('webui::layout')

@section('title', 'Home')

@section('content')
<main>
    <x-breadcrumb :items="[
    ['label' => 'Home', 'url' => route('home')],
    ['label' => 'Property Listing', 'url' => route('listing')],
]" />

    <x-settings-icon />
    <x-scroll-to-top />
    <section class="property-listing py-4">
        <div class="container mx-auto px-4">
            <div class="flex  flex-col lg:flex-row justify-between gap-4 mb-5">
                <h2 class="text-2xl lg:text-4xl font-bold text-[color:var(--text-color)]">
                    Property listing
                </h2>

                <div class="flex flex-wrap items-center gap-2">

                    @include('webui::components.filter-modal')
                    <button id="gridViewBtn" class="px-3 grid-btn py-2 rounded-md active-filter" data-view="grid">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>

                    <button id="listViewBtn" class=" px-3 py-2 list border border-[var(--border-color)] rounded-md" data-view="list">
                        <i class="fas fa-list text-[color:var(--icon-grey)] "></i>
                    </button>





                    <div class="relative">
                        <button class="flex items-center border border-[var(--border-color)] px-4 py-2 rounded-md">
                            Sort by (Default) <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="propertyContainer" class="pt-8 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-7">


                @foreach ($properties as $property)
                <x-property-card
                    :id="$property['id']"
                    :image="$property['image']"
                    :title="$property['title']"
                    :address="$property['address']"
                    :beds="$property['beds']"
                    :baths="$property['baths']"
                    :area="$property['area']"
                    :price="$property['price']" />

                @endforeach


            </div>
        </div>


        </div>
        <div class="result">
            <div class="text">
                Showing 1-9 of 12 results.
            </div>
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
</main>
@endsection