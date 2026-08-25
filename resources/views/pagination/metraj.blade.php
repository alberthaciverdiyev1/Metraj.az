@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('pagination.navigation') }}" class="mt-10">
        <ul class="pagination-metraj">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pm-item disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="pm-link" aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                </li>
            @else
                <li class="pm-item">
                    <a class="pm-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="pm-item disabled" aria-disabled="true"><span class="pm-link dots">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pm-item active" aria-current="page"><span class="pm-link">{{ $page }}</span></li>
                        @else
                            <li class="pm-item"><a class="pm-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pm-item">
                    <a class="pm-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="pm-item disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="pm-link" aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                </li>
            @endif
        </ul>

        {{-- Showing results text below pagination --}}
        <div class="showing-results text-center mt-5">
            <p class="text-sm text-gray-500">
                @if ($paginator->firstItem())
                    {{ __('pagination.showing') }}
                    <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
                    {{ __('pagination.to') }}
                    <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
                    {{ __('pagination.of') }}
                    <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span>
                    {{ __('pagination.results') }}
                @else
                    {{ __('pagination.showing') }}
                    <span class="font-semibold text-gray-700">{{ $paginator->count() }}</span>
                    {{ __('pagination.results') }}
                @endif
            </p>
        </div>
    </nav>
@endif
