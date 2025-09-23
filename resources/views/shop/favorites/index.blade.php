@extends('layouts.app')

@section('title', 'Meus Favoritos')

@push('styles')
  @vite(['resources/css/favorites.css'])
@endpush

@section('content')
  <div class="favorites-container">
    <div class="favorites-header">
      <h1 class="favorites-title">Meus Favoritos</h1>
      <p class="favorites-subtitle">Produtos que você salvou para comprar depois</p>
    </div>

    <div class="favorites-content">
      <!-- Lista de favoritos será carregada via JavaScript -->
      <div id="favorites-list" class="favorites-grid">
        <!-- Produtos serão inseridos aqui dinamicamente -->
      </div>

      <!-- Estado vazio -->
      <div id="favorites-empty" class="favorites-empty" style="display: none;">
        <div class="favorites-empty-content">
          <ion-icon name="heart-outline" class="favorites-empty-icon"></ion-icon>
          <h2 class="favorites-empty-title">Você ainda não tem favoritos</h2>
          <p class="favorites-empty-text">
            Adicione produtos aos seus favoritos para encontrá-los facilmente aqui.
          </p>
          <a href="{{ route('shop.products.index') }}" class="favorites-empty-btn">
            <ion-icon name="bag-outline"></ion-icon>
            Ver Produtos
          </a>
        </div>
      </div>

      <!-- Loading state -->
      <div id="favorites-loading" class="favorites-loading">
        <div class="favorites-loading-spinner"></div>
        <p>Carregando seus favoritos...</p>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    // Função para carregar favoritos
    function loadFavorites() {
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      const favoritesList = document.getElementById('favorites-list');
      const favoritesEmpty = document.getElementById('favorites-empty');
      const favoritesLoading = document.getElementById('favorites-loading');

      // Esconder loading
      favoritesLoading.style.display = 'none';

      if (favorites.length === 0) {
        // Mostrar estado vazio
        favoritesList.style.display = 'none';
        favoritesEmpty.style.display = 'flex';
      } else {
        // Mostrar favoritos
        favoritesList.style.display = 'grid';
        favoritesEmpty.style.display = 'none';

        // Limpar lista
        favoritesList.innerHTML = '';

        // Adicionar cada favorito
        favorites.forEach(favorite => {
          const favoriteCard = createFavoriteCard(favorite);
          favoritesList.appendChild(favoriteCard);
        });
      }
    }

    // Função para criar card de favorito
    function createFavoriteCard(favorite) {
      const card = document.createElement('div');
      card.className = 'favorite-card';
      card.setAttribute('data-product-id', favorite.id);

      card.innerHTML = `
        <div class="favorite-card-image">
          <img src="${favorite.image}" alt="${favorite.name}">
          <button class="favorite-remove-btn" onclick="removeFromFavorites(${favorite.id})" title="Remover dos favoritos">
            <ion-icon name="close"></ion-icon>
          </button>
        </div>

        <div class="favorite-card-content">
          <h3 class="favorite-card-title">${favorite.name}</h3>
          <div class="favorite-card-price">€${favorite.price.toFixed(2).replace('.', ',')}</div>

          <div class="favorite-card-actions">
            <button class="favorite-add-cart-btn" onclick="addToCartFromFavorites(${favorite.id}, '${favorite.name}', ${favorite.price}, '${favorite.image}')">
              <ion-icon name="bag-outline"></ion-icon>
              Adicionar ao Carrinho
            </button>
          </div>
        </div>
      `;

      return card;
    }

    // Função para remover dos favoritos
    function removeFromFavorites(productId) {
      let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      favorites = favorites.filter(item => item.id !== productId);
      localStorage.setItem('favorites', JSON.stringify(favorites));

      // Recarregar página
      loadFavorites();

      // Disparar evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('favoritesUpdated', {
        detail: {
          totalItems: favorites.length
        }
      }));

      // Mostrar feedback
      showRemoveFeedback();
    }

    // Função para adicionar ao carrinho a partir dos favoritos
    function addToCartFromFavorites(productId, productName, price, image) {
      let cart = JSON.parse(localStorage.getItem('cart') || '{}'); // Mudado de '[]' para '{}'

      // Encontrar a chave correta do item (pode ser produto-id, produto-id-c1, produto-id-c1-s2, etc.)
      const cartKey = Object.keys(cart).find(key => key.startsWith(productId.toString())) || productId.toString();

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

      // Disparar evento para sincronizar com navbar
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: Object.values(cart).reduce((sum, item) => sum + item.quantity, 0)
        }
      }));

      // Mostrar feedback
      showAddToCartFeedback();
    }

    // Função para mostrar feedback de remoção
    function showRemoveFeedback() {
      const toast = document.createElement('div');
      toast.className = 'favorite-toast favorite-toast--removed';
      toast.innerHTML = `
        <ion-icon name="heart-outline"></ion-icon>
        <span>Produto removido dos favoritos!</span>
      `;

      document.body.appendChild(toast);

      setTimeout(() => {
        toast.remove();
      }, 3000);
    }

    // Função para mostrar feedback de adição ao carrinho
    function showAddToCartFeedback() {
      const toast = document.createElement('div');
      toast.className = 'cart-toast';
      toast.innerHTML = `
        <ion-icon name="checkmark-circle"></ion-icon>
        <span>Produto adicionado ao carrinho!</span>
      `;

      document.body.appendChild(toast);

      setTimeout(() => {
        toast.remove();
      }, 3000);
    }

    // Carregar favoritos quando a página carregar
    document.addEventListener('DOMContentLoaded', function() {
      loadFavorites();
    });

    // Listener para atualizações dos favoritos
    window.addEventListener('favoritesUpdated', function() {
      loadFavorites();
    });
  </script>
@endpush
