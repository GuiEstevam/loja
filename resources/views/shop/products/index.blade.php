@extends('layouts.app')

@section('title', 'Produtos')

@push('styles')
  @vite(['resources/css/products.css'])
@endpush

@section('content')
  <div class="products-container">
    <!-- Sidebar de Filtros -->
    <aside class="products-sidebar">
      <div class="sidebar-header">
        <h2 class="sidebar-title">Filtros</h2>
        @if ($hasActiveFilters)
          <button type="button" onclick="clearFilters()" class="clear-filters-btn">
            <ion-icon name="close-outline"></ion-icon>
            Limpar
          </button>
        @endif
      </div>

      <form method="GET" action="{{ route('shop.products.index') }}" id="filters-form">
        <!-- Busca -->
        <div class="filter-section">
          <h3 class="filter-title">Buscar</h3>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome, descrição, categoria..."
            class="filter-search-input" />
        </div>

        <!-- Filtro por Faixa de Preço -->
        <div class="filter-section">
          <h3 class="filter-title">Faixa de Preço</h3>
          <div class="price-range">
            <div class="price-inputs">
              <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                class="price-input" min="0" step="0.01" />
              <span class="price-separator">-</span>
              <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                class="price-input" min="0" step="0.01" />
            </div>
            <div class="price-stats">
              <small>€{{ number_format($stats['min_price'] ?? 0, 2, ',', '.') }} -
                €{{ number_format($stats['max_price'] ?? 0, 2, ',', '.') }}</small>
            </div>
          </div>
        </div>

        <!-- Filtros Rápidos -->
        <div class="filter-section">
          <h3 class="filter-title">Filtros Rápidos</h3>
          <div class="quick-filters">
            <label class="quick-filter-option">
              <input type="checkbox" name="is_new" value="1" {{ request('is_new') ? 'checked' : '' }}
                class="quick-filter-checkbox">
              <span class="quick-filter-text">
                <ion-icon name="star-outline"></ion-icon>
                Produtos Novos ({{ $stats['new_products'] }})
              </span>
            </label>

            <label class="quick-filter-option">
              <input type="checkbox" name="is_sale" value="1" {{ request('is_sale') ? 'checked' : '' }}
                class="quick-filter-checkbox">
              <span class="quick-filter-text">
                <ion-icon name="pricetag-outline"></ion-icon>
                Em Promoção ({{ $stats['sale_products'] }})
              </span>
            </label>

            <label class="quick-filter-option">
              <input type="checkbox" name="free_shipping" value="1" {{ request('free_shipping') ? 'checked' : '' }}
                class="quick-filter-checkbox">
              <span class="quick-filter-text">
                <ion-icon name="car-outline"></ion-icon>
                Frete Grátis ({{ $stats['free_shipping_products'] }})
              </span>
            </label>
          </div>
        </div>

        <!-- Filtro por Rating -->
        <div class="filter-section">
          <h3 class="filter-title">Avaliação Mínima</h3>
          <select name="min_rating" class="filter-select">
            <option value="">Qualquer avaliação</option>
            <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>4.5+ estrelas</option>
            <option value="4.0" {{ request('min_rating') == '4.0' ? 'selected' : '' }}>4.0+ estrelas</option>
            <option value="3.5" {{ request('min_rating') == '3.5' ? 'selected' : '' }}>3.5+ estrelas</option>
            <option value="3.0" {{ request('min_rating') == '3.0' ? 'selected' : '' }}>3.0+ estrelas</option>
          </select>
        </div>

        <!-- Filtro por Marca -->
        <div class="filter-section">
          <h3 class="filter-title">Marca</h3>
          <select name="brand" class="filter-select">
            <option value="">Todas as marcas</option>
            @foreach ($brands as $brand)
              <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Filtro por Categoria -->
        <div class="filter-section">
          <h3 class="filter-title">Categoria</h3>
          <select name="category" class="filter-select">
            <option value="">Todas as categorias</option>
            @foreach ($categories as $category)
              <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Filtro por Cores -->
        <div class="filter-section">
          <h3 class="filter-title">Cores</h3>
          <div class="color-filters">
            @foreach ($colors as $color)
              <label class="color-option">
                <input type="checkbox" name="colors[]" value="{{ $color->id }}" class="color-checkbox"
                  {{ is_array(request('colors')) && in_array($color->id, request('colors')) ? 'checked' : '' }}>
                <span class="color-circle" style="background-color: {{ $color->hex_code ?? '#ccc' }}"></span>
                <span class="color-name">{{ $color->name }}</span>
              </label>
            @endforeach
          </div>
        </div>

        <!-- Filtro por Tamanhos -->
        <div class="filter-section">
          <h3 class="filter-title">Tamanhos</h3>
          <div class="size-filters">
            @foreach ($sizes as $size)
              <label class="size-option">
                <input type="checkbox" name="sizes[]" value="{{ $size->id }}" class="size-checkbox"
                  {{ is_array(request('sizes')) && in_array($size->id, request('sizes')) ? 'checked' : '' }}>
                <span class="size-name">{{ $size->name }}</span>
              </label>
            @endforeach
          </div>
        </div>

        <!-- Botão aplicar filtros -->
        <button type="submit" class="apply-filters-btn">
          <ion-icon name="filter-outline"></ion-icon>
          Aplicar Filtros
        </button>
      </form>
    </aside>

    <!-- Conteúdo principal -->
    <div class="products-main">
      <!-- Barra superior: busca e ordenação -->
      <div class="products-top-bar">
        <div class="search-sort-container">
          <!-- Ordenação -->
          <div class="sort-container">
            <label for="sort" class="sort-label">Ordenar por:</label>
            <select name="sort" id="sort" onchange="this.form.submit()" class="sort-select">
              <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Padrão</option>
              <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Preço: menor para maior
              </option>
              <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Preço: maior para menor
              </option>
              <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nome: A a Z</option>
              <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nome: Z a A</option>
              <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Melhor avaliados
              </option>
              <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mais recentes</option>
              <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Mais antigos</option>
            </select>
          </div>
        </div>

        <!-- Breadcrumb -->
        <nav class="breadcrumb">
          <a href="{{ route('home') }}">Home</a>
          <span class="breadcrumb-separator">/</span>
          <span class="breadcrumb-current">Produtos</span>
        </nav>
      </div>

      <!-- Grid de Produtos -->
      <div class="products-grid" id="products-grid">
        @include('shop.products.partials.products-grid')
      </div>

      <!-- Resultados e Estatísticas -->
      <div class="products-results">
        <div class="results-info">
          <p class="results-count">
            Mostrando {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} de {{ $products->total() }}
            produtos
            @if ($hasActiveFilters)
              <span class="filtered-indicator">(filtrados)</span>
            @endif
          </p>

          @if ($products->total() == 0)
            <div class="no-results">
              <ion-icon name="search-outline"></ion-icon>
              <h3>Nenhum produto encontrado</h3>
              <p>Tente ajustar os filtros ou usar termos de busca diferentes.</p>
              <button onclick="clearFilters()" class="clear-filters-btn-large">
                <ion-icon name="refresh-outline"></ion-icon>
                Limpar Filtros
              </button>
            </div>
          @endif
        </div>

        <!-- Paginação -->
        @if ($products->hasPages())
          <div class="products-pagination">
            {{ $products->links('vendor.pagination.custom') }}
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    // Variáveis globais para busca AJAX
    let searchTimeout;
    let isLoading = false;

    // Função para busca AJAX
    function performSearch() {
      if (isLoading) return;

      isLoading = true;
      showLoadingState();

      const formData = new FormData(document.getElementById('filters-form'));

      fetch('{{ route('shop.products.search') }}', {
          method: 'POST',
          body: formData,
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          }
        })
        .then(response => response.json())
        .then(data => {
          // Atualizar grid de produtos
          document.getElementById('products-grid').innerHTML = data.html;

          // Atualizar contador de resultados
          const resultsCount = document.querySelector('.results-count');
          if (resultsCount) {
            resultsCount.innerHTML = data.results_count;
          }

          // Atualizar paginação
          const pagination = document.querySelector('.products-pagination');
          if (pagination) {
            pagination.innerHTML = data.pagination;

            // Adicionar event listeners para links de paginação
            const paginationLinks = pagination.querySelectorAll('a[href]');
            paginationLinks.forEach(link => {
              link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page');

                // Atualizar o formulário com a página
                const form = document.getElementById('filters-form');
                const pageInput = form.querySelector('input[name="page"]');
                if (pageInput) {
                  pageInput.value = page;
                } else {
                  const newPageInput = document.createElement('input');
                  newPageInput.type = 'hidden';
                  newPageInput.name = 'page';
                  newPageInput.value = page;
                  form.appendChild(newPageInput);
                }

                // Fazer busca com a nova página
                performSearch();
              });
            });
          }

          // Atualizar estatísticas dos filtros
          updateFilterStats(data.stats);

          // Atualizar botão limpar
          updateClearButton(data.hasActiveFilters);

          // Reinicializar eventos dos produtos
          initializeProductEvents();

          hideLoadingState();
          isLoading = false;
        })
        .catch(error => {
          console.error('Erro na busca:', error);
          hideLoadingState();
          isLoading = false;
        });
    }

    // Função para mostrar estado de carregamento
    function showLoadingState() {
      const grid = document.getElementById('products-grid');
      grid.innerHTML = `
        <div class="loading-state">
          <div class="loading-spinner"></div>
          <p>Carregando produtos...</p>
          <div class="loading-dots">
            <div class="loading-dot"></div>
            <div class="loading-dot"></div>
            <div class="loading-dot"></div>
          </div>
        </div>
      `;
    }

    // Função para esconder estado de carregamento
    function hideLoadingState() {
      // O conteúdo já foi atualizado pela busca AJAX
    }

    // Função para atualizar estatísticas dos filtros
    function updateFilterStats(stats) {
      // Atualizar contadores dos filtros rápidos
      const newProductsCount = document.querySelector('.quick-filter-text:has(ion-icon[name="star-outline"])');
      if (newProductsCount) {
        newProductsCount.textContent = `Produtos Novos (${stats.new_products})`;
      }

      const saleProductsCount = document.querySelector('.quick-filter-text:has(ion-icon[name="pricetag-outline"])');
      if (saleProductsCount) {
        saleProductsCount.textContent = `Em Promoção (${stats.sale_products})`;
      }

      const freeShippingCount = document.querySelector('.quick-filter-text:has(ion-icon[name="car-outline"])');
      if (freeShippingCount) {
        freeShippingCount.textContent = `Frete Grátis (${stats.free_shipping_products})`;
      }

      // Atualizar faixa de preço
      const priceStats = document.querySelector('.price-stats small');
      if (priceStats) {
        priceStats.textContent = `€${stats.min_price} - €${stats.max_price}`;
      }
    }

    // Função para atualizar botão limpar
    function updateClearButton(hasActiveFilters) {
      const clearBtn = document.querySelector('.clear-filters-btn');
      const sidebarHeader = document.querySelector('.sidebar-header');

      if (hasActiveFilters) {
        if (!clearBtn) {
          const newClearBtn = document.createElement('button');
          newClearBtn.type = 'button';
          newClearBtn.className = 'clear-filters-btn';
          newClearBtn.onclick = clearFilters;
          newClearBtn.innerHTML = '<ion-icon name="close-outline"></ion-icon>Limpar';
          sidebarHeader.appendChild(newClearBtn);
        }
      } else {
        if (clearBtn) {
          clearBtn.remove();
        }
      }
    }

    // Função para inicializar eventos dos produtos
    function initializeProductEvents() {
      // Reinicializar favoritos
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      favorites.forEach(favorite => {
        updateFavoriteButton(favorite.id, true);
      });
    }

    // Função para limpar filtros
    function clearFilters() {
      // Limpar formulário
      document.getElementById('filters-form').reset();

      // Fazer busca sem filtros
      performSearch();
    }

    // Event listeners para busca em tempo real
    document.addEventListener('DOMContentLoaded', function() {
      // Busca em tempo real no campo de busca
      const searchInput = document.querySelector('.filter-search-input');
      if (searchInput) {
        searchInput.addEventListener('input', function() {
          clearTimeout(searchTimeout);
          searchTimeout = setTimeout(performSearch, 500); // Delay de 500ms
        });
      }

      // Auto-submit para checkboxes de filtros rápidos
      const quickFilters = document.querySelectorAll('.quick-filter-checkbox');
      quickFilters.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
          clearTimeout(searchTimeout);
          searchTimeout = setTimeout(performSearch, 300);
        });
      });

      // Auto-submit para select de rating
      const ratingSelect = document.querySelector('select[name="min_rating"]');
      if (ratingSelect) {
        ratingSelect.addEventListener('change', function() {
          clearTimeout(searchTimeout);
          searchTimeout = setTimeout(performSearch, 300);
        });
      }

      // Auto-submit para selects de marca e categoria
      const selectFilters = document.querySelectorAll('select[name="brand"], select[name="category"]');
      selectFilters.forEach(select => {
        select.addEventListener('change', function() {
          clearTimeout(searchTimeout);
          searchTimeout = setTimeout(performSearch, 300);
        });
      });

      // Auto-submit para inputs de preço
      const priceInputs = document.querySelectorAll('.price-input');
      priceInputs.forEach(input => {
        input.addEventListener('input', function() {
          clearTimeout(searchTimeout);
          searchTimeout = setTimeout(performSearch, 1000); // Delay maior para preços
        });
      });

      // Auto-submit para checkboxes de cores e tamanhos
      const checkboxFilters = document.querySelectorAll('.color-checkbox, .size-checkbox');
      checkboxFilters.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
          clearTimeout(searchTimeout);
          searchTimeout = setTimeout(performSearch, 300);
        });
      });

      // Auto-submit para ordenação
      const sortSelect = document.getElementById('sort');
      if (sortSelect) {
        sortSelect.addEventListener('change', function() {
          clearTimeout(searchTimeout);
          searchTimeout = setTimeout(performSearch, 300);
        });
      }
    });
    // Funções reutilizadas do welcome.blade.php
    function addToCart(productId, productName, price, image) {
      let cart = JSON.parse(localStorage.getItem('cart') || '{}'); // Mudado de '[]' para '{}'
      const cartKey = productId.toString();

      if (cart[cartKey]) {
        cart[cartKey].quantity += 1;
      } else {
        cart[cartKey] = {
          id: productId,
          name: productName,
          price: price,
          image: image,
          quantity: 1,
          added_at: new Date().toISOString()
        };
      }

      localStorage.setItem('cart', JSON.stringify(cart));

      // Atualizar badge do carrinho
      updateCartBadge();

      // Mostrar feedback
      showAddToCartFeedback();

      // Disparar evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: Object.values(cart).reduce((sum, item) => sum + item.quantity, 0)
        }
      }));
    }

    function updateCartBadge() {
      const cart = JSON.parse(localStorage.getItem('cart') || '{}'); // Mudado de '[]' para '{}'
      const totalItems = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);

      // Atualizar badge se existir
      const cartBadge = document.querySelector('.navbar-cart-badge');
      if (cartBadge) {
        cartBadge.textContent = totalItems;
        cartBadge.style.display = totalItems > 0 ? 'flex' : 'none';
      }
    }

    function showAddToCartFeedback() {
      const toast = document.createElement('div');
      toast.className = 'products-toast products-toast--added';
      toast.textContent = 'Produto adicionado ao carrinho!';
      document.body.appendChild(toast);

      setTimeout(() => {
        toast.remove();
      }, 3000);
    }

    function toggleFavorite(productId, productName, price, image) {
      let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      const isFavorite = favorites.some(item => item.id === productId);

      if (isFavorite) {
        favorites = favorites.filter(item => item.id !== productId);
        showFavoriteFeedback('Removido dos favoritos', 'removed');
      } else {
        favorites.push({
          id: productId,
          name: productName,
          price: price,
          image: image
        });
        showFavoriteFeedback('Adicionado aos favoritos', 'added');
      }

      localStorage.setItem('favorites', JSON.stringify(favorites));

      // Atualizar botão
      updateFavoriteButton(productId, !isFavorite);

      // Atualizar badge dos favoritos
      updateFavoritesBadge();

      // Disparar evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('favoritesUpdated', {
        detail: {
          totalItems: favorites.length
        }
      }));
    }

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

    function updateFavoritesBadge() {
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');

      // Atualizar badge se existir
      const favoritesBadge = document.querySelector('.navbar-favorites-badge');
      if (favoritesBadge) {
        favoritesBadge.textContent = favorites.length;
        favoritesBadge.style.display = favorites.length > 0 ? 'flex' : 'none';
      }
    }

    function showFavoriteFeedback(message, type) {
      const toast = document.createElement('div');
      toast.className = `products-toast products-toast--${type}`;
      toast.textContent = message;
      document.body.appendChild(toast);

      setTimeout(() => {
        toast.remove();
      }, 3000);
    }

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
      toast.className = 'products-toast products-toast--buy';
      toast.textContent = 'Redirecionando para checkout...';
      document.body.appendChild(toast);

      setTimeout(() => {
        toast.remove();
      }, 3000);
    }

    // Inicializar favoritos ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
      updateCartBadge();
      updateFavoritesBadge();

      // Inicializar botões de favorito
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      favorites.forEach(favorite => {
        updateFavoriteButton(favorite.id, true);
      });
    });
  </script>
@endpush
