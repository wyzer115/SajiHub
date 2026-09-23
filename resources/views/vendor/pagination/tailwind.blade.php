@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Results Counter -->
        <div>
            <p class="text-xs font-semibold text-stone-600">
                Menampilkan
                @if ($paginator->firstItem())
                    <span class="font-black text-[#BD2000]">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-black text-[#BD2000]">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                dari
                <span class="font-black text-stone-900">{{ $paginator->total() }}</span>
                data
            </p>
        </div>

        <!-- Navigation Buttons -->
        <div class="inline-flex items-center gap-1 bg-white p-1 rounded-2xl border border-stone-200 shadow-xs">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="w-8 h-8 flex items-center justify-center rounded-xl text-stone-300 bg-stone-50 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" class="w-8 h-8 flex items-center justify-center rounded-xl text-stone-700 hover:text-[#BD2000] hover:bg-stone-100 transition-all font-bold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" class="w-8 h-8 flex items-center justify-center text-xs font-bold text-stone-400">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="w-8 h-8 flex items-center justify-center rounded-xl text-xs font-black bg-[#BD2000] text-white shadow-xs">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-xl text-xs font-bold text-stone-700 hover:text-[#BD2000] hover:bg-stone-100 transition-all" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" class="w-8 h-8 flex items-center justify-center rounded-xl text-stone-700 hover:text-[#BD2000] hover:bg-stone-100 transition-all font-bold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="w-8 h-8 flex items-center justify-center rounded-xl text-stone-300 bg-stone-50 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
