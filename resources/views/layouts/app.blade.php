<!DOCTYPE html>
<html lang="pt-BR" x-data="navbarData()" :class="{ 'dark': darkMode }">
@php use Illuminate\Support\Str; @endphp

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Loja Dropshipping')</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

  @vite('resources/css/app.css')
  @vite('resources/css/navbar.css')
  @vite('resources/css/footer.css')
  <script src="//unpkg.com/alpinejs" defer></script>
  @stack('styles')
  <script type="module" src="https://unpkg.com/ionicons@7.2.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.2.2/dist/ionicons/ionicons.js"></script>
</head>

<body class="min-h-screen flex flex-col">
  <x-navbar />
  <main class="flex-1">
    @yield('content')
  </main>
  <x-footer />

  {{-- Modal de feedback global --}}
  @if (session('success') || $errors->any())
    <div id="feedbackModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full shadow-lg relative">
        <button type="button" id="closeModal"
          class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-2xl font-bold leading-none focus:outline-none"
          aria-label="Fechar">
          &times;
        </button>
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Mensagem</h2>
        @if (session('success'))
          <div class="mb-4 text-green-600 dark:text-green-400">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
          <div class="mb-4 text-red-600 dark:text-red-400">
            <ul class="list-disc list-inside">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('feedbackModal');
        if (modal) {
          // Fecha ao clicar no botão (X)
          modal.querySelector('#closeModal').onclick = function() {
            modal.style.display = 'none';
          };
          // Fecha ao clicar fora do conteúdo
          modal.onclick = function(e) {
            if (e.target === modal) {
              modal.style.display = 'none';
            }
          };
          // Fecha ao pressionar ESC
          document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
              modal.style.display = 'none';
            }
          });
        }
      });
    </script>
  @endif

  @stack('scripts')

  <script>
    // Função global para gerenciar o dark mode
    function navbarData() {
      return {
        open: false,
        cartDropdown: false,
        favoritesDropdown: false,
        userDropdown: false,
        darkMode: localStorage.getItem('darkMode') === 'true',
        cartItems: [],
        cartItemCount: 0,
        cartTotal: 0,
        favoritesItems: [],
        favoritesItemCount: 0,
        searchQuery: '', // Adicionado para corrigir o erro

        init() {
          this.loadCart();
          this.loadFavorites();
          this.updateCartDisplay();
          this.updateFavoritesDisplay();
          this.setupGlobalCartValidation();

          // Listener para atualizações do carrinho
          window.addEventListener('cartUpdated', (event) => {
            this.loadCart();
            this.updateCartDisplay();
          });

          // Listener para atualizações dos favoritos
          window.addEventListener('favoritesUpdated', (event) => {
            this.loadFavorites();
            this.updateFavoritesDisplay();
          });

          // Listener para mudanças no localStorage
          window.addEventListener('storage', (event) => {
            if (event.key === 'cart') {
              this.loadCart();
              this.updateCartDisplay();
            }
            if (event.key === 'favorites') {
              this.loadFavorites();
              this.updateFavoritesDisplay();
            }
          });

          // Variáveis para controlar delay dos dropdowns
          this.cartDropdownTimeout = null;
          this.favoritesDropdownTimeout = null;
          this.userDropdownTimeout = null;

          // Listener global para fechar dropdowns com delay
          document.addEventListener('click', (event) => {
            const isDropdownClick = event.target.closest('.navbar-dropdown');
            const isDropdownButton = event.target.closest('.navbar-icon-btn, .navbar-link');
            const isNavbarItem = event.target.closest('.navbar-menu-item');

            if (!isDropdownClick && !isDropdownButton && !isNavbarItem) {
              // Fechar dropdowns com delay para evitar fechamento acidental
              this.closeDropdownsWithDelay();
            }
          });
        },

        // Método para fechar dropdowns com delay
        closeDropdownsWithDelay() {
          // Limpar timeouts existentes
          if (this.cartDropdownTimeout) clearTimeout(this.cartDropdownTimeout);
          if (this.favoritesDropdownTimeout) clearTimeout(this.favoritesDropdownTimeout);
          if (this.userDropdownTimeout) clearTimeout(this.userDropdownTimeout);

          // Fechar com delay de 100ms
          this.cartDropdownTimeout = setTimeout(() => {
            this.cartDropdown = false;
          }, 100);

          this.favoritesDropdownTimeout = setTimeout(() => {
            this.favoritesDropdown = false;
          }, 100);

          this.userDropdownTimeout = setTimeout(() => {
            this.userDropdown = false;
          }, 100);
        },

        async loadCart() {
          const cart = JSON.parse(localStorage.getItem('cart') || '{}');
          
          // Validação global e limpeza automática
          const cleanedCart = this.validateAndCleanCart(cart);
          
          // Atualizar localStorage se houve limpeza
          if (JSON.stringify(cleanedCart) !== JSON.stringify(cart)) {
            localStorage.setItem('cart', JSON.stringify(cleanedCart));
            console.warn('Carrinho limpo automaticamente - itens inválidos removidos');
          }
          
          // Filtrar apenas itens válidos
          const validItems = Object.values(cleanedCart).filter(item => {
            const price = parseFloat(item.price);
            const quantity = parseInt(item.quantity);
            return !isNaN(price) && !isNaN(quantity) && price > 0 && quantity > 0 && item.id && item.name;
          });
          
          this.cartItems = validItems;
          this.cartItemCount = validItems.reduce((sum, item) => {
            const quantity = parseInt(item.quantity) || 1;
            return sum + quantity;
          }, 0);
          this.cartTotal = validItems.reduce((sum, item) => {
            const price = parseFloat(item.price) || 0;
            const quantity = parseInt(item.quantity) || 1;
            return sum + (price * quantity);
          }, 0);

          // Sincronizar com banco se usuário estiver logado (desabilitado temporariamente)
          // if (window.userAuthenticated) {
          //   await this.syncCartToDatabase();
          // }
        },

        validateAndCleanCart(cart) {
          const cleanedCart = {};
          
          Object.keys(cart).forEach(key => {
            const item = cart[key];
            
            // Verificar se o item tem as propriedades necessárias
            if (!item || typeof item !== 'object') {
              console.warn('Item inválido removido (não é objeto):', item);
              return;
            }

            // Converter e validar preço
            const price = parseFloat(item.price);
            const quantity = parseInt(item.quantity);

            // Verificar se o item tem propriedades essenciais
            const hasValidPrice = !isNaN(price) && price > 0;
            const hasValidQuantity = !isNaN(quantity) && quantity > 0;
            const hasValidId = item.id && !isNaN(parseInt(item.id));
            const hasValidName = item.name && typeof item.name === 'string' && item.name.trim() !== '';

            // Manter apenas itens válidos
            if (hasValidPrice && hasValidQuantity && hasValidId && hasValidName) {
              cleanedCart[key] = {
                ...item,
                price: price,
                quantity: quantity,
                name: item.name.trim()
              };
            } else {
              console.warn('Item inválido removido:', {
                item,
                priceValid: hasValidPrice,
                quantityValid: hasValidQuantity,
                idValid: hasValidId,
                nameValid: hasValidName
              });
            }
          });
          
          return cleanedCart;
        },

        setupGlobalCartValidation() {
          // Interceptar mudanças no localStorage
          const originalSetItem = localStorage.setItem;
          const self = this;
          
          localStorage.setItem = function(key, value) {
            if (key === 'cart') {
              try {
                const cart = JSON.parse(value);
                const cleanedCart = self.validateAndCleanCart(cart);
                
                // Se houve limpeza, usar o carrinho limpo
                if (JSON.stringify(cleanedCart) !== JSON.stringify(cart)) {
                  console.warn('Carrinho validado automaticamente - itens inválidos removidos');
                  value = JSON.stringify(cleanedCart);
                }
              } catch (error) {
                console.error('Erro ao validar carrinho:', error);
                // Se não conseguir parsear, limpar completamente
                value = '{}';
              }
            }
            
            return originalSetItem.call(this, key, value);
          };
        },

        async loadFavorites() {
          const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
          this.favoritesItems = favorites;
          this.favoritesItemCount = favorites.length;

          // Sincronizar com banco se usuário estiver logado
          if (window.userAuthenticated) {
            await this.syncFavoritesToDatabase();
          }
        },

        async syncCartToDatabase() {
          try {
            const cart = JSON.parse(localStorage.getItem('cart') || '{}');
            
            // Filtrar apenas itens válidos antes de enviar
            const validCart = {};
            Object.keys(cart).forEach(key => {
              const item = cart[key];
              const price = parseFloat(item.price);
              const quantity = parseInt(item.quantity);
              
              if (!isNaN(price) && !isNaN(quantity) && price > 0 && quantity > 0 && item.id && item.name) {
                validCart[key] = {
                  ...item,
                  price: price,
                  quantity: quantity
                };
              }
            });
            
            const response = await fetch('/api/sync/cart', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify({
                cart: validCart
              })
            });

            if (response.ok) {
              const data = await response.json();
              if (data.success && data.cart) {
                // Validar dados recebidos do banco também
                const validatedCart = {};
                Object.keys(data.cart).forEach(key => {
                  const item = data.cart[key];
                  const price = parseFloat(item.price);
                  const quantity = parseInt(item.quantity);
                  
                  if (!isNaN(price) && !isNaN(quantity) && price > 0 && quantity > 0 && item.id && item.name) {
                    validatedCart[key] = {
                      ...item,
                      price: price,
                      quantity: quantity
                    };
                  }
                });
                
                localStorage.setItem('cart', JSON.stringify(validatedCart));
                this.loadCart();
              }
            }
          } catch (error) {
            console.error('Erro ao sincronizar carrinho:', error);
          }
        },

        async syncFavoritesToDatabase() {
          try {
            const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const response = await fetch('/api/sync/favorites', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify({
                favorites
              })
            });

            if (response.ok) {
              const data = await response.json();
              if (data.success && data.favorites) {
                localStorage.setItem('favorites', JSON.stringify(data.favorites));
                this.loadFavorites();
              }
            }
          } catch (error) {
            console.error('Erro ao sincronizar favoritos:', error);
          }
        },

        updateCartDisplay() {
          const cartBadge = document.querySelector('.navbar-cart-badge');
          if (cartBadge) {
            cartBadge.textContent = this.cartItemCount;
            cartBadge.style.display = this.cartItemCount > 0 ? 'flex' : 'none';
          }
        },

        updateFavoritesDisplay() {
          const favoritesBadge = document.querySelector('.navbar-favorites-badge');
          if (favoritesBadge) {
            favoritesBadge.textContent = this.favoritesItemCount;
            favoritesBadge.style.display = this.favoritesItemCount > 0 ? 'flex' : 'none';
          }
        },

        removeFromCart(productId) {
          let cart = JSON.parse(localStorage.getItem('cart') || '{}');

          // Encontrar a chave correta do item (pode ser produto-id, produto-id-c1, produto-id-c1-s2, etc.)
          const cartKey = Object.keys(cart).find(key => key.startsWith(productId.toString()));

          if (cartKey) {
            delete cart[cartKey];
            localStorage.setItem('cart', JSON.stringify(cart));
            this.loadCart();
            this.updateCartDisplay();

            // Disparar evento para sincronizar com outras páginas
            window.dispatchEvent(new CustomEvent('cartUpdated', {
              detail: {
                totalItems: Object.values(cart).reduce((sum, item) => sum + item.quantity, 0)
              }
            }));
          }
        },

        removeFromFavorites(productId) {
          let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
          favorites = favorites.filter(item => item.id !== productId);
          localStorage.setItem('favorites', JSON.stringify(favorites));
          this.loadFavorites();
          this.updateFavoritesDisplay();

          // Disparar evento para sincronizar com welcome page
          window.dispatchEvent(new CustomEvent('favoritesUpdated', {
            detail: {
              totalItems: favorites.length
            }
          }));
        },

        addToCartFromFavorites(productId, productName, price, image) {
          let cart = JSON.parse(localStorage.getItem('cart') || '{}');

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
          this.loadCart();
          this.updateCartDisplay();

          // Disparar evento para sincronizar com welcome page
          window.dispatchEvent(new CustomEvent('cartUpdated', {
            detail: {
              totalItems: Object.values(cart).reduce((sum, item) => sum + item.quantity, 0)
            }
          }));
        },

        toggleDarkMode() {
          this.darkMode = !this.darkMode;
          localStorage.setItem('darkMode', this.darkMode);
          document.documentElement.classList.toggle('dark', this.darkMode);
          // Disparar evento para sincronizar outras páginas
          window.dispatchEvent(new CustomEvent('darkModeChanged', {
            detail: {
              darkMode: this.darkMode
            }
          }));
        }
      }
    }

    // Inicializar dark mode globalmente
    document.addEventListener('DOMContentLoaded', function() {
      const darkMode = localStorage.getItem('darkMode') === 'true';
      document.documentElement.classList.toggle('dark', darkMode);

      // Sincronizar com outras páginas
      window.dispatchEvent(new CustomEvent('darkModeChanged', {
        detail: {
          darkMode: darkMode
        }
      }));

      // Definir se usuário está autenticado
      window.userAuthenticated = @auth true
    @else
      false
    @endauth ;
    });

    // Listener para mudanças de dark mode
    window.addEventListener('darkModeChanged', function(event) {
      const darkMode = event.detail.darkMode;
      document.documentElement.classList.toggle('dark', darkMode);
    });
  </script>
</body>

</html>
