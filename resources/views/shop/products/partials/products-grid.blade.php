@forelse($products as $product)
  <div class="product-card" data-product-id="{{ $product->id }}">
    <!-- Badges -->
    <div class="product-badges">
      @if ($product->is_new)
        <span class="product-badge product-badge--new">Novo</span>
      @endif
      @if ($product->is_sale)
        <span
          class="product-badge product-badge--sale">-{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%</span>
      @endif
      @if ($product->free_shipping)
        <span class="product-badge product-badge--shipping">Frete Grátis</span>
      @endif
    </div>

    <!-- Botão de favorito -->
    <button class="product-favorite-btn" title="Adicionar aos favoritos"
      onclick="toggleFavorite({{ $product->id }}, '{{ $product->name }}', {{ $product->is_sale ? $product->sale_price : $product->price }}, '{{ asset('products/' . $product->image) }}')">
      <ion-icon name="heart-outline"></ion-icon>
    </button>

    <!-- Imagem -->
    <a href="{{ route('shop.products.show', $product) }}" class="product-image-link">
      <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}" class="product-image" />
    </a>

    <!-- Conteúdo -->
    <div class="product-content">
      <!-- Categoria e Nome -->
      <div class="product-header">
        <div class="product-category">
          {{ $product->categories->first()?->name ?? 'Calçados' }}
        </div>
        <h3 class="product-name">
          <a href="{{ route('shop.products.show', $product) }}">{{ $product->name }}</a>
        </h3>
      </div>

      <!-- Rating -->
      <div class="product-rating">
        <div class="product-stars">
          @for ($i = 1; $i <= 5; $i++)
            <ion-icon name="{{ $i <= $product->rating ? 'star' : 'star-outline' }}"></ion-icon>
          @endfor
        </div>
        <span class="product-rating-count">({{ $product->rating_count }})</span>
      </div>

      <!-- Preços e Parcelas -->
      <div class="product-pricing">
        @if ($product->is_sale)
          <div class="product-price-row">
            <span class="product-old-price">€{{ number_format($product->price, 2, ',', '.') }}</span>
            <span class="product-new-price">€{{ number_format($product->sale_price, 2, ',', '.') }}</span>
          </div>
        @else
          <span class="product-price">€{{ number_format($product->price, 2, ',', '.') }}</span>
        @endif

        <div class="product-installments">
          @if ($product->installments > 1 && $product->installment_value)
            ou {{ $product->installments }}x de €{{ number_format($product->installment_value, 2, ',', '.') }}
          @else
            ou 10x de
            €{{ number_format(($product->is_sale ? $product->sale_price : $product->price) / 10, 2, ',', '.') }}
          @endif
        </div>
      </div>

      <!-- Tamanhos -->
      @if ($product->sizes && $product->sizes->count())
        <div class="product-sizes">
          @foreach ($product->sizes->take(4) as $size)
            <span class="product-size">{{ $size->name }}</span>
          @endforeach
          @if ($product->sizes->count() > 4)
            <span class="product-size">+{{ $product->sizes->count() - 4 }}</span>
          @endif
        </div>
      @endif

      <!-- Botão de ação -->
      <div class="product-actions">
        <a href="{{ route('shop.products.show', $product) }}" class="product-view-btn" title="Ver produto">
          <ion-icon name="eye-outline"></ion-icon>
          Ver Produto
        </a>
      </div>
    </div>
  </div>
@empty
  <div class="products-empty">
    <h3 class="products-empty-title">Nenhum produto encontrado</h3>
    <p class="products-empty-text">Tente ajustar os filtros ou usar termos de busca diferentes.</p>
  </div>
@endforelse

<style>
  /* Estilo para o botão Ver Produto na lista de produtos */
  .product-view-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: #3b82f6;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    width: 100%;
    justify-content: center;
  }

  .product-view-btn:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
  }

  .product-view-btn ion-icon {
    font-size: 16px;
  }

  /* Responsividade */
  @media (max-width: 768px) {
    .product-view-btn {
      padding: 10px 16px;
      font-size: 0.8rem;
    }
  }
</style>
