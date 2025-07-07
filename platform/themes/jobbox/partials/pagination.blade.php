@if ($paginator->hasPages())
    <div class="paginations">
        <ul class="pager">
            @if ($paginator->onFirstPage())
                <li>
                    <a class="pager-prev pagination-button text-center" href="javascript:void(0)" tabindex="-1">
                        <i class="fi fi-rr-arrow-small-left btn-prev"></i>
                    </a>
                </li>
            @else
                <li>
                    <a class="pager-prev pagination-button text-center" data-page="{{ $paginator->currentPage() - 1 }}" href="{{ $paginator->previousPageUrl() }}">
                        <i class="fi fi-rr-arrow-small-left btn-prev"></i>
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                        <li><a class="pager-number disabled" href="javascript:void(0)">{{ $element }}</a></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><a class="pager-number active" href="javascript:void(0)">{{ $page }}</a></li>
                        @else
                            <li><a class="pager-number pagination-button" data-page="{{ $page }}" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li>
                    <a class="pager-next pagination-button text-center" data-page="{{ $paginator->currentPage() + 1 }}"  href="{{ $paginator->nextPageUrl() }}">
                        <i class="fi fi-rr-arrow-small-right btn-next"></i>
                    </a>
                </li>
            @else
                <li>
                    <a class="pager-next text-center" href="javascript:void(0)" tabindex="-1">
                        <i class="fi fi-rr-arrow-small-right btn-next"></i>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif
