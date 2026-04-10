@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center gap-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-10 h-10 flex items-center justify-center rounded-full text-gray-300 cursor-default">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                class="w-10 h-10 flex items-center justify-center rounded-full text-brand-dark hover:bg-soft-mint hover:text-brand-primary transition-all shadow-sm bg-white border border-gray-100">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex items-center gap-1">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-10 h-10 flex items-center justify-center text-gray-400 cursor-default">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="w-10 h-10 flex items-center justify-center rounded-full bg-brand-primary text-white font-bold shadow-lg shadow-brand-primary/30 cursor-default">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-100 text-gray-500 font-bold hover:border-brand-primary hover:text-brand-primary transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                class="w-10 h-10 flex items-center justify-center rounded-full text-brand-dark hover:bg-soft-mint hover:text-brand-primary transition-all shadow-sm bg-white border border-gray-100">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        @else
            <span class="w-10 h-10 flex items-center justify-center rounded-full text-gray-300 cursor-default">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </span>
        @endif
    </nav>
@endif