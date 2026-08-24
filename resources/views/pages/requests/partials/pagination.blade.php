@if ($requests->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between py-6">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($requests->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default rounded-xl">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $requests->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($requests->hasMorePages())
                <a href="{{ $requests->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50">
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
                    <span class="font-bold text-gray-900">{{ $requests->firstItem() ?? 0 }}</span>
                    -
                    <span class="font-bold text-gray-900">{{ $requests->lastItem() ?? 0 }}</span>
                    /
                    <span class="font-bold text-gray-900">{{ $requests->total() }}</span>
                    {{ __('tələb') }}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-xl shadow-xs gap-1">
                    {{-- Previous Link --}}
                    @if ($requests->onFirstPage())
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-default rounded-lg">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $requests->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition">
                            <i class="bi bi-chevron-left text-xs"></i>
                        </a>
                    @endif

                    {{-- Pagination Links --}}
                    @foreach ($requests->getUrlRange(1, $requests->lastPage()) as $page => $url)
                        @if ($page == $requests->currentPage())
                            <span aria-current="page">
                                <span class="relative inline-flex items-center px-3.5 py-2 text-sm font-bold text-white bg-[#f1913d] border border-[#f1913d] rounded-lg cursor-default">
                                    {{ $page }}
                                </span>
                            </span>
                        @elseif($page == 1 || $page == $requests->lastPage() || abs($page - $requests->currentPage()) <= 2)
                            <a href="{{ $url }}" class="relative inline-flex items-center px-3.5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition">
                                {{ $page }}
                            </a>
                        @elseif(abs($page - $requests->currentPage()) == 3)
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-white cursor-default">...</span>
                        @endif
                    @endforeach

                    {{-- Next Link --}}
                    @if ($requests->hasMorePages())
                        <a href="{{ $requests->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition">
                            <i class="bi bi-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-default rounded-lg">
                                <i class="bi bi-chevron-right text-xs"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
