<!-- Pagination -->
@if(isset($paginator) && $paginator->hasPages())
    <div class="flex justify-center items-center mt-10">
        <nav class="inline-flex -space-x-px">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 rounded-l border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 rounded-l border border-gray-300 bg-white text-gray-500 hover:bg-blue-50 transition duration-200">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-2 border border-gray-300 bg-blue-800 text-white font-bold">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-blue-50 transition duration-200">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 rounded-r border border-gray-300 bg-white text-gray-500 hover:bg-blue-50 transition duration-200">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="px-3 py-2 rounded-r border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </nav>
    </div>
@else
    {{-- Simple pagination for non-paginated data --}}
    @if(isset($currentPage) && isset($totalPages) && $totalPages > 1)
        <div class="flex justify-center items-center mt-10">
            <nav class="inline-flex -space-x-px">
                {{-- Previous --}}
                @if($currentPage > 1)
                    <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" class="px-3 py-2 rounded-l border border-gray-300 bg-white text-gray-500 hover:bg-blue-50 transition duration-200">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @else
                    <span class="px-3 py-2 rounded-l border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @endif

                {{-- Pages --}}
                @for($i = 1; $i <= $totalPages; $i++)
                    @if($i == $currentPage)
                        <span class="px-3 py-2 border border-gray-300 bg-blue-800 text-white font-bold">
                            {{ $i }}
                        </span>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-blue-50 transition duration-200">
                            {{ $i }}
                        </a>
                    @endif
                @endfor

                {{-- Next --}}
                @if($currentPage < $totalPages)
                    <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" class="px-3 py-2 rounded-r border border-gray-300 bg-white text-gray-500 hover:bg-blue-50 transition duration-200">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="px-3 py-2 rounded-r border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </nav>
        </div>
    @endif
@endif
