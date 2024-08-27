@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center">
        {{-- Pagination information --}}
        <small>
            {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </small>

        {{-- Pagination buttons --}}
        <div class="btn-group btn-group-sm ms-3">
            {{-- Previous Page Button --}}
            <button {{ $paginator->onFirstPage() ? 'disabled' : '' }} class="btn btn-outline-secondary px-1" type="button" onclick="window.location='{{ $paginator->previousPageUrl() }}'">
                <span class="material-symbols-rounded fs-5 align-middle">arrow_back</span>
            </button>

            {{-- Next Page Button --}}
            <button {{ $paginator->hasMorePages() ? '' : 'disabled' }} class="btn btn-outline-secondary px-1" type="button" onclick="window.location='{{ $paginator->nextPageUrl() }}'">
                <span class="material-symbols-rounded fs-5 align-middle">arrow_forward</span>
            </button>
        </div>
    </div>
@endif
