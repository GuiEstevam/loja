@extends('layouts.app')

@section('title', $product->name)

@push('styles')
  @vite(['resources/css/product-detail.css'])
@endpush

@section('content')
  <div class="product-detail-container">
    <!-- Breadcrumb -->
    <nav class="product-breadcrumb">
      <a href="{{ route('home') }}" class="breadcrumb-link">
        <ion-icon name="home-outline"></ion-icon>
        Início
      </a>
      <ion-icon name="chevron-forward"></ion-icon>
      @if ($product->categories->count() > 0)
        <a href="{{ route('shop.products.index', ['category' => $product->categories->first()->slug]) }}"
          class="breadcrumb-link">
          {{ $product->categories->first()->name }}
        </a>
        <ion-icon name="chevron-forward"></ion-icon>
      @endif
      <span class="breadcrumb-current">{{ $product->name }}</span>
    </nav>

    <div class="product-main-layout">
      <!-- Coluna Principal -->
      <div class="product-main-content">
        <!-- Galeria de Imagens -->
        <div class="product-gallery">
          <div class="product-main-image">
            <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}" id="main-product-image">
          </div>

          @if ($product->images && $product->images->count() > 0)
            <div class="product-thumbnails">
              <div class="product-thumbnail active"
                onclick="changeMainImage('{{ asset('products/' . $product->image) }}', this)">
                <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}">
              </div>
              @foreach ($product->images as $image)
                <div class="product-thumbnail"
                  onclick="changeMainImage('{{ asset('products/' . $image->image) }}', this)">
                  <img src="{{ asset('products/' . $image->image) }}" alt="{{ $product->name }}">
                </div>
              @endforeach
            </div>
          @endif
        </div>

        <!-- Informações do Produto -->
        <div class="product-info-section">
          <!-- Meta Info -->
          <div class="product-meta">
            @if ($product->categories->count() > 0)
              <span class="product-category">{{ $product->categories->first()->name }}</span>
            @endif
            @if ($product->brand)
              <span class="product-brand">{{ $product->brand->name }}</span>
            @endif
            <span class="product-sku">SKU: {{ $product->sku ?? 'N/A' }}</span>
          </div>

          <!-- Título e Rating -->
          <div class="product-header">
            <h1 class="product-title">{{ $product->name }}</h1>
            <div class="product-rating">
              <div class="product-stars">
                @for ($i = 1; $i <= 5; $i++)
                  <ion-icon name="{{ $i <= $product->rating ? 'star' : 'star-outline' }}"></ion-icon>
                @endfor
              </div>
              <span class="product-rating-count">({{ $product->rating_count }} avaliações)</span>
              <span class="product-rating-text">Excelente</span>
            </div>
          </div>

          <!-- Preços -->
          <div class="product-pricing">
            @if ($product->is_sale && $product->sale_price)
              <div class="product-price-old">€{{ number_format($product->price, 2, ',', '.') }}</div>
              <div class="product-price-current">€{{ number_format($product->sale_price, 2, ',', '.') }}</div>
              <div class="product-discount">
                -{{ number_format((($product->price - $product->sale_price) / $product->price) * 100, 0) }}% OFF</div>
            @else
              <div class="product-price-current">€{{ number_format($product->price, 2, ',', '.') }}</div>
            @endif

            <div class="product-installments">
              @if ($product->installments > 1 && $product->installment_value)
                ou {{ $product->installments }}x de €{{ number_format($product->installment_value, 2, ',', '.') }} sem
                juros
              @else
                ou 10x de
                €{{ number_format(($product->is_sale ? $product->sale_price : $product->price) / 10, 2, ',', '.') }} sem
                juros
              @endif
            </div>
          </div>

          <!-- Seleção de Cor -->
          @if ($product->colors && $product->colors->count() > 0)
            <div class="product-variation-section">
              <h3 class="variation-title">
                @if ($product->colors->count() > 1)
                  Escolha a Cor
                @else
                  Cor Disponível
                @endif
              </h3>
              <div class="product-colors-grid">
                @foreach ($product->colors as $color)
                  <div class="product-color-item"
                    onclick="selectColor(this, '{{ $color->id }}', '{{ $color->name }}')">
                    <div class="product-color-circle" style="background: {{ $color->hex_code }}"
                      data-color="{{ $color->id }}" data-color-name="{{ $color->name }}"></div>
                    <span class="product-color-name">{{ $color->name }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          <!-- Seleção de Tamanho -->
          @if ($product->sizes && $product->sizes->count() > 0)
            <div class="product-variation-section">
              <h3 class="variation-title">Escolha o Tamanho</h3>
              <div class="product-sizes-grid">
                @foreach ($product->sizes as $size)
                  <button class="product-size-btn" data-size="{{ $size->name }}" onclick="selectSize(this)">
                    {{ $size->name }}
                  </button>
                @endforeach
              </div>
            </div>
          @endif

          <!-- Quantidade -->
          <div class="product-quantity-section">
            <h3 class="variation-title">Quantidade</h3>
            <div class="quantity-selector">
              <button class="quantity-btn" onclick="changeQuantity(-1)">
                <ion-icon name="remove"></ion-icon>
              </button>
              <input type="number" id="product-quantity" value="1" min="1" max="99" readonly>
              <button class="quantity-btn" onclick="changeQuantity(1)">
                <ion-icon name="add"></ion-icon>
              </button>
            </div>
          </div>

          <!-- Ações -->
          <div class="product-actions">
            <button class="product-add-cart-btn" onclick="addToCart()">
              <ion-icon name="bag-outline"></ion-icon>
              Adicionar ao Carrinho
            </button>
            <button class="product-buy-now-btn" onclick="buyNow()">
              <ion-icon name="flash-outline"></ion-icon>
              Comprar Agora
            </button>
          </div>

          <!-- Informações Adicionais -->
          <div class="product-additional-info">
            <div class="info-item">
              <ion-icon name="shield-checkmark-outline"></ion-icon>
              <span>Garantia de 30 dias</span>
            </div>
            <div class="info-item">
              <ion-icon name="car-outline"></ion-icon>
              <span>Entrega grátis em pedidos acima de €50</span>
            </div>
            <div class="info-item">
              <ion-icon name="refresh-outline"></ion-icon>
              <span>Devolução gratuita em 7 dias</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Produtos Recomendados - Container Lateral -->
      <div class="recommended-products-container">
        <div class="recommended-section">
          <h3 class="recommended-title">Produtos Recomendados</h3>
          <div class="recommended-products">
            @foreach ($recommendedProducts ?? [] as $recProduct)
              <div class="recommended-product">
                <div class="rec-product-image">
                  <img src="{{ asset('products/' . $recProduct->image) }}" alt="{{ $recProduct->name }}">
                </div>
                <div class="rec-product-info">
                  <h4 class="rec-product-name">{{ $recProduct->name }}</h4>
                  <div class="rec-product-price">
                    @if ($recProduct->is_sale && $recProduct->sale_price)
                      <span class="rec-old-price">€{{ number_format($recProduct->price, 2, ',', '.') }}</span>
                      <span class="rec-current-price">€{{ number_format($recProduct->sale_price, 2, ',', '.') }}</span>
                    @else
                      <span class="rec-current-price">€{{ number_format($recProduct->price, 2, ',', '.') }}</span>
                    @endif
                  </div>
                  <a href="{{ route('shop.products.show', $recProduct->slug) }}" class="rec-product-link">
                    Ver Produto
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- Seção de Detalhes -->
    <div class="product-details-section">
      <div class="details-tabs">
        <button class="tab-btn active" onclick="showTab('description')">Descrição</button>
        <button class="tab-btn" onclick="showTab('specifications')">Especificações</button>
        <button class="tab-btn" onclick="showTab('reviews')">Avaliações</button>
      </div>

      <div class="tab-content">
        <!-- Descrição -->
        <div id="description" class="tab-panel active">
          <div class="product-description">
            <h3>Descrição do Produto</h3>
            <p>{{ $product->description }}</p>
          </div>
        </div>

        <!-- Especificações -->
        <div id="specifications" class="tab-panel">
          <div class="product-specifications">
            <h3>Especificações Técnicas</h3>
            <div class="specs-grid">
              @if ($product->weight)
                <div class="spec-item">
                  <span class="spec-label">Peso:</span>
                  <span class="spec-value">{{ $product->weight }}g</span>
                </div>
              @endif
              @if ($product->dimensions)
                <div class="spec-item">
                  <span class="spec-label">Dimensões:</span>
                  <span class="spec-value">{{ $product->dimensions }}</span>
                </div>
              @endif
              @if ($product->material)
                <div class="spec-item">
                  <span class="spec-label">Material:</span>
                  <span class="spec-value">{{ $product->material }}</span>
                </div>
              @endif
            </div>
          </div>
        </div>

        <!-- Avaliações -->
        <div id="reviews" class="tab-panel">
          @php
              $reviews = $product->approvedReviews()->with(['user:id,name'])->orderBy('created_at', 'desc')->paginate(10);
          @endphp
          
          <!-- Formulário de Avaliação -->
          <x-review-form :product="$product" />
          
          <!-- Lista de Avaliações -->
          <x-reviews-list :product="$product" :reviews="$reviews" />
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript para funcionalidades -->
  <script>
    // Variáveis globais para seleções
    let selectedColor = null;
    let selectedSize = null;
    let selectedQuantity = 1;

    // Função para trocar imagem principal
    function changeMainImage(imageSrc, thumbnail) {
      document.getElementById('main-product-image').src = imageSrc;

      // Remove seleção anterior
      document.querySelectorAll('.product-thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
      });

      // Adiciona seleção ao thumbnail clicado
      thumbnail.classList.add('active');
    }

    // Função para selecionar cor
    function selectColor(element, colorId, colorName) {
      // Remove seleção anterior
      document.querySelectorAll('.product-color-circle').forEach(circle => {
        circle.classList.remove('selected');
      });

      // Adiciona seleção ao elemento clicado
      element.querySelector('.product-color-circle').classList.add('selected');

      // Atualiza variável global
      selectedColor = {
        id: colorId,
        name: colorName
      };
    }

    // Função para selecionar tamanho
    function selectSize(button) {
      // Remove seleção anterior
      document.querySelectorAll('.product-size-btn').forEach(btn => {
        btn.classList.remove('selected');
      });

      // Adiciona seleção ao botão clicado
      button.classList.add('selected');

      // Atualiza variável global
      selectedSize = button.getAttribute('data-size');
    }

    // Função para alterar quantidade
    function changeQuantity(delta) {
      const quantityInput = document.getElementById('product-quantity');
      let newQuantity = parseInt(quantityInput.value) + delta;

      if (newQuantity >= 1 && newQuantity <= 99) {
        quantityInput.value = newQuantity;
        selectedQuantity = newQuantity;
      }
    }

    // Função para adicionar ao carrinho
    function addToCart() {
      const productId = {{ $product->id }};
      const productName = '{{ $product->name }}';
      const price = {{ $product->is_sale ? $product->sale_price : $product->price }};
      const image = '{{ asset('products/' . $product->image) }}';

      let cart = JSON.parse(localStorage.getItem('cart') || '{}');

      // Criar chave única incluindo cor e tamanho
      const variationKey = `${productId}_${selectedColor ? selectedColor.id : 'default'}_${selectedSize || 'default'}`;

      if (cart[variationKey]) {
        cart[variationKey].quantity += selectedQuantity;
      } else {
        cart[variationKey] = {
          id: productId,
          name: productName,
          price: price,
          image: image,
          quantity: selectedQuantity,
          color: selectedColor ? selectedColor.id : null,
          colorName: selectedColor ? selectedColor.name : null,
          size: selectedSize,
          added_at: new Date().toISOString()
        };
      }

      localStorage.setItem('cart', JSON.stringify(cart));

      // Atualiza badge do carrinho
      updateCartBadge();

      // Mostra feedback
      showToast('Produto adicionado ao carrinho!', 'success');

      // Dispara evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: Object.values(cart).reduce((sum, item) => sum + item.quantity, 0)
        }
      }));
    }

    // Função para comprar agora
    function buyNow() {
      // Primeiro adiciona ao carrinho
      addToCart();

      // Depois redireciona para checkout
      setTimeout(() => {
        window.location.href = '{{ route('shop.checkout') }}';
      }, 500);
    }

    // Função para mostrar tabs
    function showTab(tabName) {
      // Remove active de todas as tabs
      document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
      });
      document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.remove('active');
      });

      // Adiciona active à tab selecionada
      event.target.classList.add('active');
      document.getElementById(tabName).classList.add('active');
    }

    // Função para mostrar toast
    function showToast(message, type = 'success') {
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.innerHTML = `
      <ion-icon name="${type === 'success' ? 'checkmark-circle' : 'alert-circle'}"></ion-icon>
      <span>${message}</span>
    `;

      document.body.appendChild(toast);

      setTimeout(() => {
        toast.classList.add('show');
      }, 100);

      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
          toast.remove();
        }, 300);
      }, 3000);
    }

    // Função para atualizar badge do carrinho
    function updateCartBadge() {
      const cart = JSON.parse(localStorage.getItem('cart') || '{}');
      const totalItems = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);

      const cartBadge = document.querySelector('.navbar-cart-badge');
      if (cartBadge) {
        cartBadge.textContent = totalItems;
        cartBadge.style.display = totalItems > 0 ? 'flex' : 'none';
      }
    }

    // Inicializar badge do carrinho
    document.addEventListener('DOMContentLoaded', function() {
      updateCartBadge();
    });
  </script>
@endsection
