@extends('layouts.app')

@section('title', 'Bem-vindo')

@push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  @vite(['resources/css/welcome.css'])
@endpush

@section('content')
  <!-- Banner Hero Carrossel -->
  <section class="banner-section">
    <div class="swiper bannerSwiper banner-swiper">
      <div class="swiper-wrapper">
        <!-- Banner 1 -->
        <div class="swiper-slide banner-slide">
          <div class="banner-background" style="background-image: url('{{ asset('images/banner_1.jpg') }}');">
          </div>
          <div class="banner-overlay"></div>
          <div class="banner-content">
            <h1 class="banner-title">
              FRETE GRÁTIS PARA TODO O BRASIL
            </h1>
            <p class="banner-subtitle">
              Aproveite condições especiais por tempo limitado!
            </p>
            <div class="banner-buttons">
              <a href="{{ route('shop.products.index') }}" class="banner-btn banner-btn-primary">
                <ion-icon name="bag-outline" class="banner-btn-icon"></ion-icon>
                Comprar Agora
              </a>
              <a href="{{ route('shop.products.index') }}" class="banner-btn banner-btn-secondary">
                <ion-icon name="eye-outline" class="banner-btn-icon"></ion-icon>
                Ver Produtos
              </a>
            </div>
          </div>
        </div>
        <!-- Banner 2 -->
        <div class="swiper-slide banner-slide">
          <div class="banner-background" style="background-image: url('{{ asset('images/banner_2.jpg') }}');">
          </div>
          <div class="banner-overlay"></div>
          <div class="banner-content">
            <h1 class="banner-title">
              NOVA COLEÇÃO CHEGOU
            </h1>
            <p class="banner-subtitle">
              Descubra as tendências do ano com exclusividade
            </p>
            <div class="banner-buttons">
              <a href="{{ route('shop.products.index') }}" class="banner-btn banner-btn-primary">
                <ion-icon name="star-outline" class="banner-btn-icon"></ion-icon>
                Ver Novidades
              </a>
              <a href="{{ route('shop.products.index') }}" class="banner-btn banner-btn-secondary">
                <ion-icon name="trending-up-outline" class="banner-btn-icon"></ion-icon>
                Tendências
              </a>
            </div>
          </div>
        </div>
        <!-- Banner 3 -->
        <div class="swiper-slide banner-slide">
          <div class="banner-background" style="background-image: url('{{ asset('images/banner_3.jpg') }}');">
          </div>
          <div class="banner-overlay"></div>
          <div class="banner-content">
            <h1 class="banner-title">
              MAIS DO QUE APENAS "UMA LOJA"
            </h1>
            <p class="banner-subtitle">
              Um estilo de vida com qualidade e equilíbrio
            </p>
            <div class="banner-buttons">
              <a href="{{ route('shop.products.index') }}" class="banner-btn banner-btn-primary">
                <ion-icon name="heart-outline" class="banner-btn-icon"></ion-icon>
                Descobrir
              </a>
              <a href="{{ route('shop.products.index') }}" class="banner-btn banner-btn-secondary">
                <ion-icon name="information-circle-outline" class="banner-btn-icon"></ion-icon>
                Saiba Mais
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="banner-pagination"></div>
      <div class="banner-button-prev custom-swiper-arrow">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </div>
      <div class="banner-button-next custom-swiper-arrow">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </div>
    </div>
  </section>

  <!-- Slider de Marcas -->
  <section class="brands-section">
    <h2 class="brands-title">Marcas Premium</h2>
    <p class="brands-subtitle">Descubra as melhores marcas de calçados esportivos e casuais, selecionadas especialmente
      para você</p>
    <div class="brands-container">
      <div class="brands-button-prev custom-swiper-arrow">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </div>
      <div class="swiper marcasSwiper brands-swiper">
        <div class="swiper-wrapper">
          @foreach ($brands as $brand)
            <div class="swiper-slide brand-slide">
              <a href="{{ route('shop.brands.show', $brand) }}" class="brand-card">
                <img src="{{ asset('brands/' . $brand->logo) }}" alt="{{ $brand->name }}" class="brand-logo">
                <div class="brand-name">{{ $brand->name }}</div>
              </a>
            </div>
          @endforeach
        </div>
        <div class="brands-pagination"></div>
      </div>
      <div class="brands-button-next custom-swiper-arrow">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </div>
    </div>
  </section>

  <!-- Carrossel de produtos em destaque -->
  <section class="products-section">
    <h2 class="products-title">Produtos em Destaque</h2>
    <p class="products-subtitle">Os produtos mais vendidos e queridos pelos nossos clientes. Qualidade e estilo em cada
      escolha</p>
    <div class="products-container">
      <div class="product-button-prev custom-swiper-arrow">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </div>
      <div class="swiper productSwiper products-swiper">
        <div class="swiper-wrapper">
          @foreach ($products as $product)
            <div class="swiper-slide product-slide">
              <div class="product-card" data-product-id="{{ $product->id }}">
                <!-- Badges e botões de ação -->
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

                <!-- Botão de favorito -->
                <button class="product-favorite-btn" title="Adicionar aos favoritos"
                  onclick="toggleFavorite({{ $product->id }}, '{{ $product->name }}', {{ $product->is_sale ? $product->sale_price : $product->price }}, '{{ asset('products/' . $product->image) }}')">
                  <ion-icon name="heart-outline"></ion-icon>
                </button>

                <!-- Imagem do produto -->
                <a href="{{ route('shop.products.show', $product) }}" class="product-image-link">
                  <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}"
                    class="product-image">
                </a>

                <!-- Conteúdo do produto -->
                <div class="product-content">
                  <!-- Área superior -->
                  <div class="product-content-top">
                    <!-- Categoria -->
                    <div class="product-category">
                      @if ($product->categories->count() > 0)
                        {{ $product->categories->first()->name }}
                      @else
                        Calçados
                      @endif
                    </div>

                    <!-- Nome do produto -->
                    <h3 class="product-name" title="{{ $product->name }}">
                      <a href="{{ route('shop.products.show', $product) }}">
                        {{ $product->name }}
                      </a>
                    </h3>

                    <!-- Rating (sempre mostrar) -->
                    <div class="product-rating">
                      <div class="product-stars">
                        @for ($i = 1; $i <= 5; $i++)
                          <ion-icon name="{{ $i <= $product->rating ? 'star' : 'star-outline' }}"></ion-icon>
                        @endfor
                      </div>
                      <span class="product-rating-count">({{ $product->rating_count }})</span>
                    </div>
                  </div>

                  <!-- Área inferior -->
                  <div class="product-content-bottom">
                    <!-- Preços -->
                    <div class="product-price-container">
                      @if ($product->is_sale && $product->sale_price)
                        <span class="product-old-price">€{{ number_format($product->price, 2, ',', '.') }}</span>
                        <span class="product-new-price">€{{ number_format($product->sale_price, 2, ',', '.') }}</span>
                      @else
                        <span class="product-price">€{{ number_format($product->price, 2, ',', '.') }}</span>
                      @endif
                    </div>

                    <!-- Parcelas (sempre mostrar) -->
                    <div class="product-installments">
                      @if ($product->installments > 1 && $product->installment_value)
                        ou {{ $product->installments }}x de
                        €{{ number_format($product->installment_value, 2, ',', '.') }}
                      @else
                        ou 10x de €{{ number_format($product->price / 10, 2, ',', '.') }}
                      @endif
                    </div>

                    <!-- Botão de adicionar ao carrinho -->
                    <button class="product-add-cart-btn" title="Adicionar ao carrinho"
                      onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->is_sale ? $product->sale_price : $product->price }}, '{{ asset('products/' . $product->image) }}')">
                      <ion-icon name="bag-outline"></ion-icon>
                      Adicionar
                    </button>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="product-pagination"></div>
      </div>
      <div class="product-button-next custom-swiper-arrow">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
    // Banner carrossel
    new Swiper('.bannerSwiper', {
      slidesPerView: 1,
      loop: true,
      speed: 900,
      pagination: {
        el: '.banner-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.banner-button-next',
        prevEl: '.banner-button-prev',
      },
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
    });

    // Marcas
    new Swiper('.marcasSwiper', {
      slidesPerView: 3,
      spaceBetween: 16,
      loop: true,
      navigation: {
        nextEl: '.brands-button-next',
        prevEl: '.brands-button-prev',
      },
      pagination: {
        el: '.brands-pagination',
        clickable: true,
      },
      breakpoints: {
        640: {
          slidesPerView: 4,
          spaceBetween: 20
        },
        1024: {
          slidesPerView: 6,
          spaceBetween: 24
        },
        1280: {
          slidesPerView: 4,
          spaceBetween: 32
        }
      }
    });

    // Produtos em destaque
    new Swiper('.productSwiper', {
      slidesPerView: 1,
      spaceBetween: 16,
      loop: true,
      pagination: {
        el: '.product-pagination',
        clickable: true
      },
      navigation: {
        nextEl: '.product-button-next',
        prevEl: '.product-button-prev'
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 24
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 32
        },
        1280: {
          slidesPerView: 4,
          spaceBetween: 40
        }
      }
    });

    // Listener para mudanças de dark mode específico do welcome
    window.addEventListener('darkModeChanged', function(event) {
      const darkMode = event.detail.darkMode;

      // Atualizar Swiper se necessário
      if (window.bannerSwiper) {
        window.bannerSwiper.update();
      }
      if (window.marcasSwiper) {
        window.marcasSwiper.update();
      }
      if (window.productSwiper) {
        window.productSwiper.update();
      }
    });

    // Função para adicionar aos favoritos
    function toggleFavorite(productId, productName, price, image) {
      // Obter favoritos atuais do localStorage
      let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');

      // Verificar se o produto já está nos favoritos
      const existingIndex = favorites.findIndex(item => item.id === productId);

      if (existingIndex !== -1) {
        // Remover dos favoritos
        favorites.splice(existingIndex, 1);
        showFavoriteFeedback('Produto removido dos favoritos!', 'removed');
      } else {
        // Adicionar aos favoritos
        favorites.push({
          id: productId,
          name: productName,
          price: price,
          image: image,
          addedAt: new Date().toISOString()
        });
        showFavoriteFeedback('Produto adicionado aos favoritos!', 'added');
      }

      // Salvar no localStorage
      localStorage.setItem('favorites', JSON.stringify(favorites));

      // Atualizar badge dos favoritos na navbar
      updateFavoritesBadge();

      // Atualizar visual do botão
      updateFavoriteButton(productId, existingIndex === -1);

      // Disparar evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('favoritesUpdated', {
        detail: {
          totalItems: favorites.length
        }
      }));
    }

    // Função para atualizar badge dos favoritos
    function updateFavoritesBadge() {
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      const totalItems = favorites.length;

      // Atualizar badge na navbar
      const favoritesBadge = document.querySelector('.navbar-favorites-badge');
      if (favoritesBadge) {
        favoritesBadge.textContent = totalItems;
        favoritesBadge.style.display = totalItems > 0 ? 'flex' : 'none';
      }
    }

    // Função para mostrar feedback visual
    function showFavoriteFeedback(message, type) {
      // Criar toast de feedback
      const toast = document.createElement('div');
      toast.className = `favorite-toast favorite-toast--${type}`;
      toast.innerHTML = `
        <ion-icon name="${type === 'added' ? 'heart' : 'heart-outline'}"></ion-icon>
        <span>${message}</span>
      `;

      document.body.appendChild(toast);

      // Remover após 3 segundos
      setTimeout(() => {
        toast.remove();
      }, 3000);
    }

    // Função para atualizar visual do botão de favorito
    function updateFavoriteButton(productId, isFavorite) {
      const button = document.querySelector(`[data-product-id="${productId}"] .product-favorite-btn`);
      if (button) {
        const icon = button.querySelector('ion-icon');
        if (isFavorite) {
          button.classList.add('active');
          icon.setAttribute('name', 'heart');
        } else {
          button.classList.remove('active');
          icon.setAttribute('name', 'heart-outline');
        }
      }
    }

    // Função para adicionar ao carrinho
    function addToCart(productId, productName, price, image) {
      // Obter carrinho atual do localStorage
      let cart = JSON.parse(localStorage.getItem('cart') || '[]');

      // Verificar se o produto já está no carrinho
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

      // Salvar no localStorage
      localStorage.setItem('cart', JSON.stringify(cart));

      // Atualizar badge do carrinho na navbar
      updateCartBadge();

      // Disparar evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: cart.reduce((sum, item) => sum + item.quantity, 0)
        }
      }));

      // Mostrar feedback visual
      showAddToCartFeedback();
    }

    // Função para atualizar badge do carrinho
    function updateCartBadge() {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

      // Atualizar badge na navbar
      const cartBadge = document.querySelector('.navbar-cart-badge');
      if (cartBadge) {
        cartBadge.textContent = totalItems;
        cartBadge.style.display = totalItems > 0 ? 'flex' : 'none';
      }

      // Disparar evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: totalItems
        }
      }));
    }

    // Função para mostrar feedback visual
    function showAddToCartFeedback() {
      // Criar toast de sucesso
      const toast = document.createElement('div');
      toast.className = 'cart-toast';
      toast.innerHTML = `
        <ion-icon name="checkmark-circle"></ion-icon>
        <span>Produto adicionado ao carrinho!</span>
      `;

      document.body.appendChild(toast);

      // Remover após 3 segundos
      setTimeout(() => {
        toast.remove();
      }, 3000);
    }

    // Função para atualizar badge do carrinho
    function updateCartBadge() {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

      // Atualizar badge na navbar
      const cartBadge = document.querySelector('.navbar-cart-badge');
      if (cartBadge) {
        cartBadge.textContent = totalItems;
        cartBadge.style.display = totalItems > 0 ? 'flex' : 'none';
      }

      // Disparar evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: totalItems
        }
      }));
    }

    // Função para mostrar feedback visual
    function showAddToCartFeedback() {
      // Criar toast de sucesso
      const toast = document.createElement('div');
      toast.className = 'cart-toast';
      toast.innerHTML = `
        <ion-icon name="checkmark-circle"></ion-icon>
        <span>Produto adicionado ao carrinho!</span>
      `;

      document.body.appendChild(toast);

      // Remover após 3 segundos
      setTimeout(() => {
        toast.remove();
      }, 3000);
    }

    // Função para verificar estado inicial dos favoritos
    function initializeFavorites() {
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');

      // Atualizar todos os botões de favorito
      favorites.forEach(favorite => {
        updateFavoriteButton(favorite.id, true);
      });

      // Atualizar badge
      updateFavoritesBadge();
    }

    // Inicializar badge do carrinho
    document.addEventListener('DOMContentLoaded', function() {
      updateCartBadge();
      initializeFavorites();
    });
  </script>
@endpush
