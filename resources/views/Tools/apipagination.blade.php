@if ($paginator->hasPages)
<nav>
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage)
        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <span class="page-link" aria-hidden="true">&lsaquo;</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link mypagination pointer" data-add="{{ $paginator->getPageName }}/{{$paginator->currentPage - 1}}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
        </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_numeric($element))
        @if($element == $paginator->currentPage)
        <li class="page-item active" aria-current="page"><span class="page-link">{{ $element }}</span></li>
        @else
        <li class="page-item"><a class="page-link mypagination pointer" data-add="{{ $paginator->getPageName }}/{{$element}}">{{ $element }}</a></li>
        @endif
        @else
        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
        @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages)
        <li class="page-item">
            <a class="page-link mypagination pointer" data-add="{{ $paginator->getPageName }}/{{$paginator->currentPage+1}}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
        </li>
        @else
        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <span class="page-link" aria-hidden="true">&rsaquo;</span>
        </li>
        @endif
    </ul>
</nav>
@endif