<div class="pagination-wrap">
    <div class="page-info">Showing {{ $doctors->firstItem() }}–{{ $doctors->lastItem() }} of {{ $doctors->total() }}</div>
    <div class="custom-pagination">
        @if($doctors->onFirstPage())
            <span class="page-btn" style="opacity:.4"><i class="fas fa-chevron-left"></i></span>
        @else
            <a href="{{ $doctors->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
        @endif
        @foreach($doctors->getUrlRange(1, $doctors->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page == $doctors->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($doctors->hasMorePages())
            <a href="{{ $doctors->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
        @else
            <span class="page-btn" style="opacity:.4"><i class="fas fa-chevron-right"></i></span>
        @endif
    </div>
</div>
