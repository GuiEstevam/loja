@if ($paginator->hasPages())
  <nav role="navigation" aria-label="Navegação de páginas">
    <ul class="pagination">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true" aria-label="Página anterior">
          <span class="page-link">
            <ion-icon name="chevron-back-outline"></ion-icon>
          </span>
        </li>
      @else
        <li class="page-item">
          <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior">
            <ion-icon name="chevron-back-outline"></ion-icon>
          </a>
        </li>
      @endif

      {{-- Pagination Elements --}}
      @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
          <li class="page-item disabled" aria-disabled="true">
            <span class="page-link">{{ $element }}</span>
          </li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li class="page-item active" aria-current="page">
                <span class="page-link">{{ $page }}</span>
              </li>
            @else
              <li class="page-item">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
              </li>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <li class="page-item">
          <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Próxima página">
            <ion-icon name="chevron-forward-outline"></ion-icon>
          </a>
        </li>
      @else
        <li class="page-item disabled" aria-disabled="true" aria-label="Próxima página">
          <span class="page-link">
            <ion-icon name="chevron-forward-outline"></ion-icon>
          </span>
        </li>
      @endif
    </ul>
  </nav>
@endif
