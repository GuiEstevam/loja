@extends('layouts.app')

@section('title', $product->name)

@push('styles')
  @vite(['resources/css/product-detail.css'])
@endpush

@section('content')
  <div class="product-detail-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <a href="/" class="breadcrumb-link">Início</a>
      <ion-icon name="chevron-forward"></ion-icon>
      <a href="{{ route('shop.products.index') }}" class="breadcrumb-link">Produtos</a>
      <ion-icon name="chevron-forward"></ion-icon>
      <span class="breadcrumb-current">{{ $product->name }}</span>
    </div>

    <!-- Produto Principal -->
    <div class="product-detail-main">
      <!-- Galeria de Imagens -->
      <div class="product-gallery">
        <div class="product-main-image">
          <img src="{{ asset('products/' . ($product->image ?? 'placeholder.jpg')) }}" alt="{{ $product->name }}"
            class="product-image">

          <!-- Botão de Favorito -->
          <button class="product-favorite-btn" title="Adicionar aos favoritos"
            onclick="toggleFavorite({{ $product->id }}, '{{ $product->name }}', {{ $product->is_sale ? $product->sale_price : $product->price }}, '{{ asset('products/' . $product->image) }}')">
            <ion-icon name="heart-outline"></ion-icon>
          </button>

          <!-- Badges -->
          <div class="product-badges">
            @if ($product->is_new)
              <span class="product-badge product-badge--new">Novo</span>
            @endif
            @if ($product->is_sale)
              <span class="product-badge product-badge--sale">
                {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
              </span>
            @endif
            @if ($product->free_shipping)
              <span class="product-badge product-badge--shipping">Frete Grátis</span>
            @endif
          </div>
        </div>

        <!-- Miniaturas de Cores -->
        @if ($product->colors && $product->colors->count() > 0)
          <div class="product-colors">
            <h3 class="product-colors-title">
              @if ($product->colors->count() > 1)
                Cores disponíveis
              @else
                Cor disponível
              @endif
            </h3>
            <div class="product-colors-grid">
              @foreach ($product->colors as $color)
                <div class="product-color-item">
                  <div class="product-color-circle" style="background: {{ $color->hex_code }}"
                    title="{{ $color->name }}"></div>
                  <span class="product-color-name">{{ $color->name }}</span>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      <!-- Informações do Produto -->
      <div class="product-info">
        <!-- Categoria e Marca -->
        <div class="product-meta">
          @if ($product->categories->count() > 0)
            <span class="product-category">{{ $product->categories->first()->name }}</span>
          @endif
          @if ($product->brand)
            <span class="product-brand">{{ $product->brand->name }}</span>
          @endif
        </div>

        <!-- Nome do Produto -->
        <h1 class="product-title">{{ $product->name }}</h1>

        <!-- Rating -->
        <div class="product-rating">
          <div class="product-stars">
            @for ($i = 1; $i <= 5; $i++)
              <ion-icon name="{{ $i <= $product->rating ? 'star' : 'star-outline' }}"></ion-icon>
            @endfor
          </div>
          <span class="product-rating-count">({{ $product->rating_count }} avaliações)</span>
        </div>

        <!-- Preços -->
        <div class="product-price-container">
          @if ($product->is_sale && $product->sale_price)
            <span class="product-old-price">€{{ number_format($product->price, 2, ',', '.') }}</span>
            <span class="product-new-price">€{{ number_format($product->sale_price, 2, ',', '.') }}</span>
          @else
            <span class="product-price">€{{ number_format($product->price, 2, ',', '.') }}</span>
          @endif
        </div>

        <!-- Parcelas -->
        <div class="product-installments">
          @if ($product->installments > 1 && $product->installment_value)
            ou {{ $product->installments }}x de €{{ number_format($product->installment_value, 2, ',', '.') }}
          @else
            ou 10x de
            €{{ number_format(($product->is_sale ? $product->sale_price : $product->price) / 10, 2, ',', '.') }}
          @endif
        </div>

        <!-- Descrição -->
        <div class="product-description">
          <h3 class="product-description-title">Descrição</h3>
          <p class="product-description-text">{{ $product->description }}</p>
        </div>

        <!-- Seleção de Tamanho -->
        @if ($product->sizes && $product->sizes->count())
          <div class="product-sizes">
            <h3 class="product-sizes-title">Tamanhos disponíveis</h3>
            <div class="product-sizes-grid">
              @foreach ($product->sizes as $size)
                <button class="product-size-btn" data-size="{{ $size->name }}" onclick="selectSize(this)">
                  {{ $size->name }}
                </button>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Ações -->
        <div class="product-actions">
          <div class="product-actions-row">
            <button class="product-add-cart-btn" title="Adicionar ao carrinho"
              onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->is_sale ? $product->sale_price : $product->price }}, '{{ asset('products/' . $product->image) }}')">
              <ion-icon name="bag-outline"></ion-icon>
            </button>
          </div>
          <button class="product-buy-now-btn" title="Comprar agora"
            onclick="buyNow({{ $product->id }}, '{{ $product->name }}', {{ $product->is_sale ? $product->sale_price : $product->price }}, '{{ asset('products/' . $product->image) }}')">
            <ion-icon name="flash-outline"></ion-icon>
            Comprar Agora
          </button>
        </div>

        <!-- Informações Adicionais -->
        <div class="product-additional-info">
          <div class="product-info-item">
            <ion-icon name="shield-checkmark-outline"></ion-icon>
            <span>Garantia de 30 dias</span>
          </div>
          <div class="product-info-item">
            <ion-icon name="car-outline"></ion-icon>
            <span>Entrega em 2-5 dias úteis</span>
          </div>
          <div class="product-info-item">
            <ion-icon name="card-outline"></ion-icon>
            <span>Pagamento seguro</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Avaliações -->
    <div class="product-reviews">
      <h2 class="product-reviews-title">Avaliações dos clientes</h2>
      <div class="product-reviews-grid">
        <div class="product-review">
          <div class="product-review-header">
            <span class="product-review-author">Maria S.</span>
            <div class="product-review-stars">
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
            </div>
          </div>
          <p class="product-review-text">Produto de ótima qualidade, chegou antes do prazo!</p>
          <span class="product-review-date">há 2 dias</span>
        </div>

        <div class="product-review">
          <div class="product-review-header">
            <span class="product-review-author">João P.</span>
            <div class="product-review-stars">
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star-outline"></ion-icon>
            </div>
          </div>
          <p class="product-review-text">Gostei bastante, mas achei o tamanho um pouco pequeno.</p>
          <span class="product-review-date">há 5 dias</span>
        </div>

        <div class="product-review">
          <div class="product-review-header">
            <span class="product-review-author">Ana L.</span>
            <div class="product-review-stars">
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
            </div>
          </div>
          <p class="product-review-text">Super confortável e bonito. Recomendo!</p>
          <span class="product-review-date">há 1 semana</span>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript para funcionalidades -->
  <script>
    // Função para selecionar tamanho
    function selectSize(button) {
      // Remove seleção anterior
      document.querySelectorAll('.product-size-btn').forEach(btn => {
        btn.classList.remove('selected');
      });

      // Adiciona seleção ao botão clicado
      button.classList.add('selected');
    }

    // Função para adicionar ao carrinho
    function addToCart(productId, productName, price, image) {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');

      // Verifica se o produto já está no carrinho
      const existingItem = cart.find(item => item.id === productId);

      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({
          id: productId,
          name: productName,
          price: price,
          image: image,
          quantity: 1
        });
      }

      localStorage.setItem('cart', JSON.stringify(cart));

      // Atualiza badge do carrinho
      updateCartBadge();

      // Mostra feedback
      showToast('Produto adicionado ao carrinho!', 'success');

      // Dispara evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: cart.reduce((sum, item) => sum + item.quantity, 0)
        }
      }));
    }

    // Função para comprar agora
    function buyNow(productId, productName, price, image) {
      // Limpa carrinho atual
      localStorage.setItem('cart', JSON.stringify([]));

      // Adiciona apenas este produto
      const cart = [{
        id: productId,
        name: productName,
        price: price,
        image: image,
        quantity: 1
      }];

      localStorage.setItem('cart', JSON.stringify(cart));

      // Atualiza badge do carrinho
      updateCartBadge();

      // Mostra feedback
      showToast('Redirecionando para checkout...', 'buy');

      // Dispara evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: 1
        }
      }));

      // Redireciona para checkout
      setTimeout(() => {
        window.location.href = '{{ route('shop.cart.index') }}';
      }, 1000);
    }

    // Função para favoritar
    function toggleFavorite(productId, productName, price, image) {
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      const existingIndex = favorites.findIndex(item => item.id === productId);

      if (existingIndex > -1) {
        favorites.splice(existingIndex, 1);
        showToast('Removido dos favoritos!', 'info');
      } else {
        favorites.push({
          id: productId,
          name: productName,
          price: price,
          image: image
        });
        showToast('Adicionado aos favoritos!', 'success');
      }

      localStorage.setItem('favorites', JSON.stringify(favorites));

      // Atualiza badge dos favoritos
      updateFavoritesBadge();

      // Atualiza visual do botão
      const btn = event.target.closest('.product-favorite-btn');
      if (existingIndex > -1) {
        btn.classList.remove('active');
        btn.querySelector('ion-icon').name = 'heart-outline';
      } else {
        btn.classList.add('active');
        btn.querySelector('ion-icon').name = 'heart';
      }

      // Dispara evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('favoritesUpdated', {
        detail: {
          totalItems: favorites.length
        }
      }));
    }

    // Função para mostrar toast
    function showToast(message, type = 'success') {
      const toast = document.createElement('div');
      toast.className = `product-toast product-toast--${type}`;
      toast.innerHTML = `
        <ion-icon name="${type === 'success' ? 'checkmark-circle' : type === 'buy' ? 'flash' : 'information-circle'}"></ion-icon>
        <span>${message}</span>
    `;

      document.body.appendChild(toast);

      setTimeout(() => {
        toast.remove();
      }, 3000);
    }

    // Função para atualizar badge do carrinho
    function updateCartBadge() {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

      // Atualiza badge na navbar se existir
      const cartBadge = document.querySelector('.cart-badge');
      if (cartBadge) {
        cartBadge.textContent = totalItems;
        cartBadge.style.display = totalItems > 0 ? 'block' : 'none';
      }
    }

    // Função para atualizar badge dos favoritos
    function updateFavoritesBadge() {
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');

      // Atualiza badge na navbar se existir
      const favoritesBadge = document.querySelector('.favorites-badge');
      if (favoritesBadge) {
        favoritesBadge.textContent = favorites.length;
        favoritesBadge.style.display = favorites.length > 0 ? 'block' : 'none';
      }
    }

    // Inicializa badges ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
      updateCartBadge();
      updateFavoritesBadge();

      // Verifica se o produto está nos favoritos
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      const productId = {{ $product->id }};
      const isFavorite = favorites.some(item => item.id === productId);

      if (isFavorite) {
        const btn = document.querySelector('.product-favorite-btn');
        btn.classList.add('active');
        btn.querySelector('ion-icon').name = 'heart';
      }

      // Sincroniza dark mode com o resto do site
      const darkMode = localStorage.getItem('darkMode') === 'true';
      if (darkMode) {
        document.documentElement.classList.add('dark');
      }

      // Escuta mudanças no dark mode
      window.addEventListener('darkModeChanged', function() {
        const isDark = localStorage.getItem('darkMode') === 'true';
        if (isDark) {
          document.documentElement.classList.add('dark');
        } else {
          document.documentElement.classList.remove('dark');
        }
      });
    });
  </script>
@endsection
