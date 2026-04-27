@if ($paginator->hasPages())
    <nav class="flex items-center gap-x-1" role="navigation" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <button type="button" class="btn btn-text btn-square btn-sm opacity-40 cursor-not-allowed" disabled aria-label="Previous Button">
                <span class="icon-[tabler--chevron-left] size-4 rtl:rotate-180"></span>
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-text btn-square btn-sm" aria-label="Previous Button">
                <span class="icon-[tabler--chevron-left] size-4 rtl:rotate-180"></span>
            </a>
        @endif

        <div class="flex items-center gap-x-1 text-sm">
            <button type="button" class="btn btn-text btn-square btn-sm pointer-events-none" aria-current="page">
                {{ $paginator->currentPage() }}
            </button>
            <span class="text-base-content/60">of</span>
            <button type="button" class="btn btn-text btn-square btn-sm pointer-events-none">
                {{ $paginator->lastPage() }}
            </button>
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-text btn-square btn-sm" aria-label="Next Button">
                <span class="icon-[tabler--chevron-right] size-4 rtl:rotate-180"></span>
            </a>
        @else
            <button type="button" class="btn btn-text btn-square btn-sm opacity-40 cursor-not-allowed" disabled aria-label="Next Button">
                <span class="icon-[tabler--chevron-right] size-4 rtl:rotate-180"></span>
            </button>
        @endif
    </nav>
@endif
