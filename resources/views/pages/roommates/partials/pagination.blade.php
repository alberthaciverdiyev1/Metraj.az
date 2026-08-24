@if ($listings->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between py-6">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($listings->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default rounded-xl">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $listings->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($listings->hasMorePages())
                <a href="{{ $listings->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default rounded-xl">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-600">
                    {{ __('Göstərilir') }}
                    <span class="font-semibold text-gray-900">{{ $listings->firstItem() ?? 0 }}</span>
                    -
                    <span class="font-semibold text-gray-900">{{ $listings->lastItem() ?? 0 }}</span>
                    /
                    <span class="font-semibold text-gray-900">{{ $listings->total() }}</span>
                    {{ __('elan') }}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-xl shadow-xs gap-1">
                    {{-- Previous Page Link --}}
                    @if ($listings->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-default rounded-lg" aria-hidden="true">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $listings->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition" aria-label="{{ __('pagination.previous') }}">
                            <i class="bi bi-chevron-left text-xs"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($listings->getUrlRange(1, $listings->lastPage()) as $page => $url)
                        @if ($page == $listings->currentPage())
                            <span aria-current="page">
                                <span class="relative inline-flex items-center px-3.5 py-2 text-sm font-semibold text-white bg-[#f1913d] border border-[#f1913d] rounded-lg cursor-default">
                                    {{ $page }}
                                </span>
                            </span>
                        @elseif($page == 1 || $page == $listings->lastPage() || abs($page - $listings->currentPage()) <= 2)
                            <a href="{{ $url }}" class="relative inline-flex items-center px-3.5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @elseif(abs($page - $listings->currentPage()) == 3)
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-white cursor-default">...</span>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($listings->hasMorePages())
                        <a href="{{ $listings->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition" aria-label="{{ __('pagination.next') }}">
                            <i class="bi bi-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-default rounded-lg" aria-hidden="true">
                                <i class="bi bi-chevron-right text-xs"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
