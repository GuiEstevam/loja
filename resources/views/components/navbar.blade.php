@php
  $cart = session('cart', []);
  $user = auth()->user();
  $isAdmin = $user && $user->hasRole('admin');
  $userDashboard = $user ? ($isAdmin ? route('admin.dashboard') : route('shop.dashboard')) : null;
@endphp

<nav class="navbar" x-data="navbarData()" :class="{ 'dark': darkMode }">
  <div class="navbar-container">
    <!-- Logo -->
    <a href="/" class="navbar-logo">
      <img src="{{ asset('images/logo_light.png') }}" alt="Logo" class="navbar-logo-light">
      <img src="{{ asset('images/logo_dark.png') }}" alt="Logo" class="navbar-logo-dark">
      <span>SKYFASHION</span>
    </a>

    <!-- Botão Mobile -->
    <button @click="open = !open" class="navbar-mobile-btn">
      <ion-icon :name="open ? 'close' : 'menu'"></ion-icon>
    </button>

    <!-- Search Bar -->
    <div class="navbar-search">
      <form action="{{ route('shop.products.index') }}" method="GET" class="navbar-search-form">
        <input type="text" name="search" placeholder="O que você está procurando?" class="navbar-search-input"
          value="{{ request('search') }}" x-model="searchQuery">
        <button type="submit" class="navbar-search-btn">
          <ion-icon name="search-outline"></ion-icon>
        </button>
      </form>
    </div>

    <!-- Menu Desktop -->
    <ul class="navbar-menu">
      <li class="navbar-menu-item">
        <a href="{{ route('shop.products.index') }}" class="navbar-link">Produtos</a>
      </li>

      <!-- Favoritos -->
      <li class="navbar-menu-item navbar-favorites"
        @mouseenter="if(window.innerWidth >= 768) { clearTimeout(favoritesDropdownTimeout); favoritesDropdown = true; }"
        @mouseleave="if(window.innerWidth >= 768) { favoritesDropdownTimeout = setTimeout(() => favoritesDropdown = false, 150); }">
        <button @click="if(window.innerWidth < 768) favoritesDropdown = !favoritesDropdown" class="navbar-icon-btn">
          <ion-icon name="heart-outline"></ion-icon>
          <span class="navbar-favorites-badge" x-text="favoritesItemCount" x-show="favoritesItemCount > 0"></span>
        </button>

        <!-- Dropdown dos Favoritos -->
        <div class="navbar-dropdown" :class="{ 'show': favoritesDropdown }" @click.stop
          @mouseenter="if(window.innerWidth >= 768) { clearTimeout(favoritesDropdownTimeout); favoritesDropdown = true; }"
          @mouseleave="if(window.innerWidth >= 768) { favoritesDropdownTimeout = setTimeout(() => favoritesDropdown = false, 150); }">
          <div class="navbar-dropdown-header">
            <h3 class="navbar-dropdown-title">Seus Favoritos</h3>
          </div>

          <div class="navbar-dropdown-content" x-show="favoritesItems.length > 0">
            <template x-for="item in favoritesItems" :key="item.id">
              <div class="navbar-dropdown-item">
                <div class="navbar-dropdown-item-content">
                  <img :src="item.image" :alt="item.name">
                  <div class="navbar-dropdown-item-info">
                    <h4 x-text="item.name"></h4>
                    <p x-text="'€' + item.price.toFixed(2).replace('.', ',')"></p>
                  </div>
                </div>
                <div class="navbar-dropdown-item-actions">
                  <button @click.stop="removeFromFavorites(item.id)" class="action-btn action-btn--remove"
                    title="Remover dos favoritos">
                    <ion-icon name="heart-dislike-outline"></ion-icon>
                  </button>
                  <button @click.stop="addToCartFromFavorites(item.id, item.name, item.price, item.image)"
                    class="action-btn action-btn--add" title="Adicionar ao carrinho">
                    <ion-icon name="bag-add-outline"></ion-icon>
                  </button>
                </div>
              </div>
            </template>
            <div class="navbar-dropdown-total" x-show="favoritesItems.length > 0">
              <span>Total de itens:</span>
              <span x-text="favoritesItemCount"></span>
            </div>
          </div>
          <div class="navbar-dropdown-actions" x-show="favoritesItems.length > 0">
            <a href="{{ route('shop.favorites.index') }}" class="navbar-dropdown-btn"
              @click="favoritesDropdown = false">Ver todos os favoritos</a>
          </div>
          <div class="navbar-dropdown-empty" x-show="favoritesItems.length === 0">
            Você ainda não tem favoritos.
          </div>
        </div>
      </li>

      <!-- Carrinho -->
      <li class="navbar-menu-item navbar-cart"
        @mouseenter="if(window.innerWidth >= 768) { clearTimeout(cartDropdownTimeout); cartDropdown = true; }"
        @mouseleave="if(window.innerWidth >= 768) { cartDropdownTimeout = setTimeout(() => cartDropdown = false, 150); }">
        <button @click="if(window.innerWidth < 768) cartDropdown = !cartDropdown" class="navbar-icon-btn">
          <ion-icon name="cart-outline"></ion-icon>
          <span class="navbar-cart-badge" x-text="cartItemCount" x-show="cartItemCount > 0"></span>
        </button>

        <!-- Dropdown do Carrinho -->
        <div class="navbar-dropdown" :class="{ 'show': cartDropdown }" @click.stop
          @mouseenter="if(window.innerWidth >= 768) { clearTimeout(cartDropdownTimeout); cartDropdown = true; }"
          @mouseleave="if(window.innerWidth >= 768) { cartDropdownTimeout = setTimeout(() => cartDropdown = false, 150); }">
          <div class="navbar-dropdown-header">
            <h3 class="navbar-dropdown-title">Seu Carrinho</h3>
          </div>

          <div class="navbar-dropdown-content" x-show="cartItems.length > 0">
            <template x-for="item in cartItems" :key="item.id">
              <div class="navbar-dropdown-item">
                <div class="navbar-dropdown-item-content">
                  <img :src="item.image" :alt="item.name">
                  <div class="navbar-dropdown-item-info">
                    <h4 x-text="item.name"></h4>
                    <div class="item-details">
                      <span x-text="'Qtd: ' + item.quantity"></span>
                      <span x-text="'€' + item.price.toFixed(2).replace('.', ',')" class="item-price"></span>
                    </div>
                  </div>
                </div>
                <div class="navbar-dropdown-item-actions">
                  <button @click.stop="removeFromCart(item.id)" class="action-btn action-btn--remove" title="Remover">
                    <ion-icon name="trash-outline"></ion-icon>
                  </button>
                </div>
              </div>
            </template>
            <div class="navbar-dropdown-total" x-show="cartItems.length > 0">
              <span>Total:</span>
              <span x-text="'€' + cartTotal.toFixed(2).replace('.', ',')"></span>
            </div>
          </div>

          <div class="navbar-dropdown-actions" x-show="cartItems.length > 0">
            <a href="{{ route('shop.cart.index') }}" class="navbar-dropdown-btn" @click="cartDropdown = false">Ir
              para o carrinho</a>
          </div>

          <div class="navbar-dropdown-empty" x-show="cartItems.length === 0">
            Seu carrinho está vazio.
          </div>
        </div>
      </li>

      <!-- Usuário -->
      <li class="navbar-menu-item"
        @mouseenter="if(window.innerWidth >= 768) { clearTimeout(userDropdownTimeout); userDropdown = true; }"
        @mouseleave="if(window.innerWidth >= 768) { userDropdownTimeout = setTimeout(() => userDropdown = false, 150); }">
        @auth
          <button @click="if(window.innerWidth < 768) userDropdown = !userDropdown" class="navbar-link">
            <ion-icon name="person-circle-outline"></ion-icon>
            <span>{{ $user->name }}</span>
            <ion-icon name="chevron-down-outline"></ion-icon>
          </button>

          <!-- Dropdown do Usuário -->
          <div class="navbar-dropdown" :class="{ 'show': userDropdown }" @click.stop
            @mouseenter="if(window.innerWidth >= 768) { clearTimeout(userDropdownTimeout); userDropdown = true; }"
            @mouseleave="if(window.innerWidth >= 768) { userDropdownTimeout = setTimeout(() => userDropdown = false, 150); }">
            <ul class="navbar-user-menu">
              @if ($isAdmin)
                <li class="navbar-user-item">
                  <a href="{{ route('admin.dashboard') }}" class="navbar-user-link">
                    <ion-icon name="settings-outline"></ion-icon>
                    <span>Painel Admin</span>
                  </a>
                </li>
              @else
                <li class="navbar-user-item">
                  <a href="{{ $userDashboard }}" class="navbar-user-link">
                    <ion-icon name="person-outline"></ion-icon>
                    <span>Minha Conta</span>
                  </a>
                </li>
              @endif
              <li class="navbar-user-item">
                <button @click="toggleDarkMode()" class="navbar-user-link">
                  <ion-icon :name="darkMode ? 'sunny' : 'moon'"></ion-icon>
                  <span x-text="darkMode ? 'Modo Claro' : 'Modo Escuro'"></span>
                </button>
              </li>
              <li class="navbar-user-item">
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="navbar-user-link logout">
                    <ion-icon name="log-out-outline"></ion-icon>
                    <span>Sair</span>
                  </button>
                </form>
              </li>
            </ul>
          </div>
        @else
          <a href="/login" class="navbar-login-btn">
            <ion-icon name="person-circle-outline"></ion-icon>
            <span>Entrar</span>
          </a>
        @endauth
      </li>
    </ul>

    <!-- Menu Mobile -->
    <div class="navbar-mobile-menu" :class="{ 'show': open }">
      <!-- Mobile Search -->
      <div class="navbar-mobile-search">
        <form action="{{ route('shop.products.index') }}" method="GET" class="navbar-search-form">
          <input type="text" name="search" placeholder="O que você está procurando?" class="navbar-search-input"
            value="{{ request('search') }}">
          <button type="submit" class="navbar-search-btn">
            <ion-icon name="search-outline"></ion-icon>
          </button>
        </form>
      </div>

      <a href="{{ route('shop.products.index') }}" class="navbar-link">
        <ion-icon name="bag-outline"></ion-icon>
        <span>Produtos</span>
      </a>
      <a href="{{ route('shop.favorites.index') }}" class="navbar-link">
        <ion-icon name="heart-outline"></ion-icon>
        <span>Favoritos</span>
        <span class="navbar-favorites-badge" x-text="favoritesItemCount" x-show="favoritesItemCount > 0"></span>
      </a>
      <a href="{{ route('shop.cart.index') }}" class="navbar-link">
        <ion-icon name="cart-outline"></ion-icon>
        <span>Carrinho</span>
        <span class="navbar-cart-badge" x-text="cartItemCount" x-show="cartItemCount > 0"></span>
      </a>

      @auth
        <a href="{{ $userDashboard }}" class="navbar-link">
          <ion-icon name="person-circle-outline"></ion-icon>
          <span>Minha Conta</span>
        </a>
        <button @click="toggleDarkMode()" class="navbar-link">
          <ion-icon :name="darkMode ? 'sunny' : 'moon'"></ion-icon>
          <span x-text="darkMode ? 'Modo Claro' : 'Modo Escuro'"></span>
        </button>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="navbar-link" style="color: #ef4444;">
            <ion-icon name="log-out-outline"></ion-icon>
            <span>Sair</span>
          </button>
        </form>
      @else
        <a href="/login" class="navbar-link">
          <ion-icon name="person-circle-outline"></ion-icon>
          <span>Entrar</span>
        </a>
      @endauth
    </div>
  </div>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  });
</script>
