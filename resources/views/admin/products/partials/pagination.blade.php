@if ($products->hasPages())
  <div class="admin-pagination">
    <div class="admin-pagination-info">
      Mostrando {{ $products->firstItem() ?? 0 }} até {{ $products->lastItem() ?? 0 }} de
      {{ $products->total() }} resultados
    </div>

    <ul class="pagination">
      {{-- Botão Anterior --}}
      @if ($products->onFirstPage())
        <li class="page-item disabled">
          <span class="page-link">
            <ion-icon name="chevron-back-outline"></ion-icon>
          </span>
        </li>
      @else
        <li class="page-item">
          <a class="page-link" href="{{ $products->previousPageUrl() }}">
            <ion-icon name="chevron-back-outline"></ion-icon>
          </a>
        </li>
      @endif

      {{-- Páginas Numeradas --}}
      @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
        @if ($page == $products->currentPage())
          <li class="page-item active">
            <span class="page-link">{{ $page }}</span>
          </li>
        @else
          <li class="page-item">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
          </li>
        @endif
      @endforeach

      {{-- Botão Próximo --}}
      @if ($products->hasMorePages())
        <li class="page-item">
          <a class="page-link" href="{{ $products->nextPageUrl() }}">
            <ion-icon name="chevron-forward-outline"></ion-icon>
          </a>
        </li>
      @else
        <li class="page-item disabled">
          <span class="page-link">
            <ion-icon name="chevron-forward-outline"></ion-icon>
          </span>
        </li>
      @endif
    </ul>
  </div>
@endif
