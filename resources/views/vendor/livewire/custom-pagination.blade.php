@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                    Previous
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-primary to-secondary border border-transparent leading-5 rounded-md hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition ease-in-out duration-150">
                    Previous
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-white bg-gradient-to-r from-primary to-secondary border border-transparent leading-5 rounded-md hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition ease-in-out duration-150">
                    Next
                </button>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                    Next
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-muted leading-5">
                    Menampilkan
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    hasil
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Previous">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-l-md leading-5" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <button wire:click="previousPage" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-primary to-secondary border border-transparent rounded-l-md leading-5 hover:from-primary/90 hover:to-secondary/90 focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary transition ease-in-out duration-150" aria-label="Previous">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-semibold text-white bg-gradient-to-r from-primary to-secondary border border-transparent cursor-default leading-5 shadow-lg">{{ $page }}</span>
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:bg-primary/10 hover:text-primary hover:border-primary focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary transition ease-in-out duration-150" aria-label="Go to page {{ $page }}">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage" rel="next" class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-white bg-gradient-to-r from-primary to-secondary border border-transparent rounded-r-md leading-5 hover:from-primary/90 hover:to-secondary/90 focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary transition ease-in-out duration-150" aria-label="Next">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @else
                        <span aria-disabled="true" aria-label="Next">
                            <span class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-r-md leading-5" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
