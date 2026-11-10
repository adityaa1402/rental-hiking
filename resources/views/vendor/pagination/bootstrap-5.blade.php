@if ($paginator->hasPages())
    <nav aria-label="Navigasi halaman">
        <ul class="pagination pagination-sm justify-content-center mb-0">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">&lsaquo; Sebelumnya</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        &lsaquo; Sebelumnya
                    </a>
                </li>
            @endif

            <li class="page-item disabled" aria-current="page">
                <span class="page-link">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
            </li>

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Berikutnya &rsaquo;
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">Berikutnya &rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
