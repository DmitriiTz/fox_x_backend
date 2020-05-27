@if ($paginator->hasPages())
    <ul class="paginations__list" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="paginations__item">
                <a href="javascript:void(0);" data-page="1" class="paginations__link paginations__link--back"></a>
            </li>
        @else
            <li class="paginations__item">
                <a href="{{ $paginator->previousPageUrl() }}" data-page="{{ $paginator->currentPage() - 1 }}" class="paginations__link paginations__link--back"></a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                {{--<li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>--}}
                <li class="paginations__item">
                    <a class="paginations__link paginations__link--empty">{{ $element }}</a>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        {{--<li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>--}}
                        <li class="paginations__item">
                            <a href="javascript:void(0);" data-page="{{ $page }}" class="paginations__link paginations__link--active">{{ $page }}</a>
                        </li>
                    @else
                        {{--<li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>--}}
                        <li class="paginations__item">
                            <a href="{{ $url }}" data-page="{{ $page }}" class="paginations__link">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            {{--<li class="page-item">--}}
                {{--<a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>--}}
            {{--</li>--}}
            <li class="paginations__item">
                <a href="{{ $paginator->nextPageUrl() }}" data-page="{{ $paginator->currentPage() + 1 }}" class="paginations__link paginations__link--next"></a>
            </li>
        @else
            {{--<li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">--}}
                {{--<span class="page-link" aria-hidden="true">&rsaquo;</span>--}}
            {{--</li>--}}
            <li class="paginations__item">
                <a href="javascript:void(0);" data-page="{{ $paginator->lastPage() }}" class="paginations__link paginations__link--next"></a>
            </li>
        @endif
    </ul>
@endif


{{--@if ($paginator->hasPages())--}}
    {{--<ul class="paginations__list">--}}
        {{--<li class="paginations__item">--}}
            {{--<a href="#" class="paginations__link paginations__link--back"></a>--}}
        {{--</li>--}}
        {{--<li class="paginations__item">--}}
            {{--<a href="#" class="paginations__link paginations__link--start"></a>--}}
        {{--</li>--}}
        {{--<li class="paginations__item">--}}
            {{--<a href="#" class="paginations__link paginations__link--active">1</a>--}}
        {{--</li>--}}
        {{--<li class="paginations__item">--}}
            {{--<a href="#" class="paginations__link">2</a>--}}
        {{--</li>--}}
        {{--<li class="paginations__item">--}}
            {{--<a class="paginations__link paginations__link--empty">...</a>--}}
        {{--</li>--}}
        {{--<li class="paginations__item">--}}
            {{--<a href="#" class="paginations__link">5</a>--}}
        {{--</li>--}}
        {{--<li class="paginations__item">--}}
            {{--<a href="#" class="paginations__link paginations__link--finish"></a>--}}
        {{--</li>--}}
        {{--<li class="paginations__item">--}}
            {{--<a href="#" class="paginations__link paginations__link--next"></a>--}}
        {{--</li>--}}
    {{--</ul>--}}
{{--@endif--}}
