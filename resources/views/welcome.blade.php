@extends('layouts.app')

@section('title', 'Bem-vindo')

@push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  @vite(['resources/css/welcome.css'])
@endpush

@section('content')
  <div class="welcome-page">
    <!-- Banner Hero Carrossel -->
    <section class="welcome-banner-section">
      <div class="swiper bannerSwiper welcome-banner-swiper">
        <div class="swiper-wrapper">
          <!-- Banner 1 -->
          <div class="swiper-slide welcome-banner-slide">
            <div class="welcome-banner-background" style="background-image: url('{{ asset('images/banner_1.jpg') }}');">
            </div>
            <div class="welcome-banner-overlay"></div>
            <div class="welcome-banner-content">
              <h1 class="welcome-banner-title">FRETE GRÁTIS PARA TODO O BRASIL</h1>
              <p class="welcome-banner-subtitle">Aproveite condições especiais por tempo limitado!</p>
              <div class="welcome-banner-buttons">
                <a href="{{ route('shop.products.index') }}" class="welcome-btn welcome-btn-primary">
                  <ion-icon name="bag-outline" class="welcome-btn-icon"></ion-icon>
                  Comprar Agora
                </a>
                <a href="{{ route('shop.products.index') }}" class="welcome-btn welcome-btn-secondary">
                  <ion-icon name="eye-outline" class="welcome-btn-icon"></ion-icon>
                  Ver Produtos
                </a>
              </div>
            </div>
          </div>
          <!-- Banner 2 -->
          <div class="swiper-slide welcome-banner-slide">
            <div class="welcome-banner-background" style="background-image: url('{{ asset('images/banner_2.jpg') }}');">
            </div>
            <div class="welcome-banner-overlay"></div>
            <div class="welcome-banner-content">
              <h1 class="welcome-banner-title">NOVA COLEÇÃO CHEGOU</h1>
              <p class="welcome-banner-subtitle">Descubra as tendências do ano com exclusividade</p>
              <div class="welcome-banner-buttons">
                <a href="{{ route('shop.products.index') }}" class="welcome-btn welcome-btn-primary">
                  <ion-icon name="star-outline" class="welcome-btn-icon"></ion-icon>
                  Ver Novidades
                </a>
                <a href="{{ route('shop.products.index') }}" class="welcome-btn welcome-btn-secondary">
                  <ion-icon name="trending-up-outline" class="welcome-btn-icon"></ion-icon>
                  Tendências
                </a>
              </div>
            </div>
          </div>
          <!-- Banner 3 -->
          <div class="swiper-slide welcome-banner-slide">
            <div class="welcome-banner-background" style="background-image: url('{{ asset('images/banner_3.jpg') }}');">
            </div>
            <div class="welcome-banner-overlay"></div>
            <div class="welcome-banner-content">
              <h1 class="welcome-banner-title">MAIS DO QUE APENAS "UMA LOJA"</h1>
              <p class="welcome-banner-subtitle">Um estilo de vida com qualidade e equilíbrio</p>
              <div class="welcome-banner-buttons">
                <a href="{{ route('shop.products.index') }}" class="welcome-btn welcome-btn-primary">
                  <ion-icon name="heart-outline" class="welcome-btn-icon"></ion-icon>
                  Descobrir
                </a>
                <a href="{{ route('shop.products.index') }}" class="welcome-btn welcome-btn-secondary">
                  <ion-icon name="information-circle-outline" class="welcome-btn-icon"></ion-icon>
                  Saiba Mais
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="welcome-banner-pagination"></div>
        <div class="welcome-banner-button-prev welcome-nav-arrow">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </div>
        <div class="welcome-banner-button-next welcome-nav-arrow">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </div>
      </div>
    </section>

    <!-- Seção de Marcas Premium -->
    <section class="welcome-brands-section">
      <div class="welcome-section-header">
        <h2 class="welcome-section-title">MARCAS PREMIUM</h2>
        <p class="welcome-section-subtitle">Descubra as melhores marcas de calçados esportivos e casuais, selecionadas
          especialmente para você</p>
      </div>

      <div class="welcome-brands-container">
        <div class="welcome-brands-button-prev welcome-nav-arrow">
          <ion-icon name="chevron-back-outline"></ion-icon>
        </div>

        <div class="swiper marcasSwiper welcome-brands-swiper">
          <div class="swiper-wrapper">
            @foreach ($brands as $brand)
              <div class="swiper-slide welcome-brand-slide">
                <a href="{{ route('shop.brands.show', $brand) }}" class="welcome-brand-card">
                  <div class="welcome-brand-logo-container">
                    <img src="{{ asset('brands/' . $brand->logo) }}" alt="{{ $brand->name }}"
                      class="welcome-brand-logo">
                  </div>
                  <div class="welcome-brand-name">{{ $brand->name }}</div>
                </a>
              </div>
            @endforeach
          </div>
        </div>

        <div class="welcome-brands-button-next welcome-nav-arrow">
          <ion-icon name="chevron-forward-outline"></ion-icon>
        </div>
      </div>

      <div class="welcome-brands-pagination-container">
        <div class="welcome-brands-pagination"></div>
      </div>
    </section>

    <!-- Carrossel de produtos em destaque -->
    <section class="welcome-products-section">
      <div class="welcome-section-header">
        <h2 class="welcome-section-title">Produtos em Destaque</h2>
        <p class="welcome-section-subtitle">Os produtos mais vendidos e queridos pelos nossos clientes. Qualidade e estilo
          em cada escolha</p>
      </div>

      <div class="welcome-products-container">
        <div class="welcome-product-button-prev welcome-nav-arrow">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </div>

        <div class="swiper productSwiper welcome-products-swiper">
          <div class="swiper-wrapper">
            @foreach ($products as $product)
              <div class="swiper-slide welcome-product-slide">
                <div class="welcome-product-card" data-product-id="{{ $product->id }}">
                  <!-- Badges -->
                  <div class="welcome-product-badges">
                    @if ($product->is_new)
                      <span class="welcome-badge welcome-badge--new">Novo</span>
                    @endif
                    @if ($product->is_sale)
                      <span class="welcome-badge welcome-badge--sale">
                        {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                      </span>
                    @endif
                    @if ($product->free_shipping)
                      <span class="welcome-badge welcome-badge--shipping">Frete Grátis</span>
                    @endif
                  </div>

                  <!-- Botão de favorito -->
                  <button class="welcome-product-favorite-btn" title="Adicionar aos favoritos"
                    onclick="toggleFavorite({{ $product->id }}, '{{ $product->name }}', {{ $product->is_sale ? $product->sale_price : $product->price }}, '{{ asset('products/' . $product->image) }}')">
                    <ion-icon name="heart-outline"></ion-icon>
                  </button>

                  <!-- Imagem do produto -->
                  <a href="{{ route('shop.products.show', $product) }}" class="welcome-product-image-link">
                    <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}"
                      class="welcome-product-image">
                  </a>

                  <!-- Conteúdo do produto -->
                  <div class="welcome-product-content">
                    <div class="welcome-product-header">
                      <div class="welcome-product-category">
                        @if ($product->categories->count() > 0)
                          {{ $product->categories->first()->name }}
                        @else
                          Calçados
                        @endif
                      </div>
                      <h3 class="welcome-product-name" title="{{ $product->name }}">
                        <a href="{{ route('shop.products.show', $product) }}">{{ $product->name }}</a>
                      </h3>
                    </div>

                    <!-- Rating -->
                    <div class="welcome-product-rating">
                      <div class="welcome-product-stars">
                        @for ($i = 1; $i <= 5; $i++)
                          <ion-icon name="{{ $i <= $product->rating ? 'star' : 'star-outline' }}"></ion-icon>
                        @endfor
                      </div>
                      <span class="welcome-product-rating-count">({{ $product->rating_count }})</span>
                    </div>

                    <!-- Preços -->
                    <div class="welcome-product-pricing">
                      @if ($product->is_sale && $product->sale_price)
                        <div class="welcome-product-price-row">
                          <span
                            class="welcome-product-old-price">€{{ number_format($product->price, 2, ',', '.') }}</span>
                          <span
                            class="welcome-product-new-price">€{{ number_format($product->sale_price, 2, ',', '.') }}</span>
                        </div>
                      @else
                        <span class="welcome-product-price">€{{ number_format($product->price, 2, ',', '.') }}</span>
                      @endif

                      <div class="welcome-product-installments">
                        @if ($product->installments > 1 && $product->installment_value)
                          ou {{ $product->installments }}x de
                          €{{ number_format($product->installment_value, 2, ',', '.') }}
                        @else
                          ou 10x de
                          €{{ number_format(($product->is_sale ? $product->sale_price : $product->price) / 10, 2, ',', '.') }}
                        @endif
                      </div>
                    </div>

                    <!-- Botões de ação -->
                    <div class="welcome-product-actions">
                      <button class="welcome-product-add-cart-btn" title="Adicionar ao carrinho"
                        onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->is_sale ? $product->sale_price : $product->price }}, '{{ asset('products/' . $product->image) }}')">
                        <ion-icon name="bag-outline"></ion-icon>
                      </button>
                      <button class="welcome-product-buy-now-btn" title="Comprar agora"
                        onclick="buyNow({{ $product->id }}, '{{ $product->name }}', {{ $product->is_sale ? $product->sale_price : $product->price }}, '{{ asset('products/' . $product->image) }}')">
                        <ion-icon name="flash-outline"></ion-icon>
                        Comprar
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          <div class="welcome-product-pagination"></div>
        </div>

        <div class="welcome-product-button-next welcome-nav-arrow">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </div>
      </div>
    </section>
  </div>
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
        el: '.welcome-banner-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.welcome-banner-button-next',
        prevEl: '.welcome-banner-button-prev',
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
        nextEl: '.welcome-brands-button-next',
        prevEl: '.welcome-brands-button-prev',
      },
      pagination: {
        el: '.welcome-brands-pagination',
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
        el: '.welcome-product-pagination',
        clickable: true
      },
      navigation: {
        nextEl: '.welcome-product-button-next',
        prevEl: '.welcome-product-button-prev'
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
      const button = document.querySelector(`[data-product-id="${productId}"] .welcome-product-favorite-btn`);
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
      // Obter carrinho atual do localStorage (novo formato de objeto)
      let cart = JSON.parse(localStorage.getItem('cart') || '{}');

      // Encontrar a chave correta do item (pode ser produto-id, produto-id-c1, produto-id-c1-s2, etc.)
      const cartKey = Object.keys(cart).find(key => key.startsWith(productId.toString())) || productId.toString();

      // Verificar se o produto já está no carrinho
      if (cart[cartKey]) {
        cart[cartKey].quantity += 1;
      } else {
        cart[cartKey] = {
          id: productId,
          name: productName,
          price: price,
          image: image,
          quantity: 1,
          color: null,
          colorName: null,
          size: null,
          added_at: new Date().toISOString()
        };
      }

      // Salvar no localStorage
      localStorage.setItem('cart', JSON.stringify(cart));

      // Atualizar badge do carrinho na navbar
      updateCartBadge();

      // Disparar evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: Object.values(cart).reduce((sum, item) => sum + item.quantity, 0)
        }
      }));

      // Mostrar feedback visual
      showAddToCartFeedback();
    }

    // Função para atualizar badge do carrinho
    function updateCartBadge() {
      const cart = JSON.parse(localStorage.getItem('cart') || '{}');
      const totalItems = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);

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

    // Função para comprar agora
    function buyNow(productId, productName, price, image) {
      // Limpar carrinho atual (novo formato de objeto)
      localStorage.setItem('cart', JSON.stringify({}));

      // Adicionar apenas este produto
      const cart = {
        [productId.toString()]: {
          id: productId,
          name: productName,
          price: price,
          image: image,
          quantity: 1,
          added_at: new Date().toISOString()
        }
      };

      localStorage.setItem('cart', JSON.stringify(cart));

      // Atualizar badge do carrinho
      updateCartBadge();

      // Mostrar feedback
      showBuyNowFeedback();

      // Disparar evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: 1
        }
      }));

      // Redirecionar para checkout
      setTimeout(() => {
        window.location.href = '{{ route('shop.checkout') }}';
      }, 1000);
    }

    function showBuyNowFeedback() {
      const toast = document.createElement('div');
      toast.className = 'cart-toast cart-toast--buy';
      toast.innerHTML = `
        <ion-icon name="flash"></ion-icon>
        <span>Redirecionando para checkout...</span>
      `;

      document.body.appendChild(toast);

      setTimeout(() => {
        toast.remove();
      }, 3000);
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
