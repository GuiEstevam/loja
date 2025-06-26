@php
  $cart = session('cart', []);
  $user = auth()->user();
  $isAdmin = $user && $user->hasRole('admin');
  $userDashboard = $user ? ($isAdmin ? route('admin.dashboard') : route('shop.dashboard')) : null;
@endphp

<nav x-data="{ open: false, cartDropdown: false, userDropdown: false }" class="bg-white shadow sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-6">
    <div class="flex items-center justify-between h-20">
      <!-- Logo -->
      <a href="/" class="flex items-center gap-2 text-blue-700 font-extrabold text-2xl">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
        <span>SkyFashion</span>
      </a>

      <!-- Botão Hamburguer (mobile) -->
      <button @click="open = !open"
        class="md:hidden flex items-center justify-center w-12 h-12 rounded-full hover:bg-blue-50 transition">
        <ion-icon :name="open ? 'close' : 'menu'" size="large"></ion-icon>
      </button>

      <!-- Menu Unificado -->
      <div :class="{ 'flex': open, 'hidden': !open }"
        class="flex-col md:flex md:flex-row md:items-center gap-6 md:gap-8 absolute md:static top-20 left-0 w-full md:w-auto bg-white md:bg-transparent px-6 md:px-0 py-6 md:py-0 z-40 shadow md:shadow-none transition-all">
        <a href="{{ route('home') }}"
          class="text-lg font-medium text-gray-700 hover:text-blue-700 transition">Início</a>
        <a href="{{ route('shop.products.index') }}"
          class="text-lg font-medium text-gray-700 hover:text-blue-700 transition">Produtos</a>

        <!-- Carrinho (sempre visível, badge alinhado) -->
        <div x-data="{ dropdown: false }" class="relative w-full md:w-auto"
          @mouseenter="if(window.innerWidth >= 768) dropdown = true"
          @mouseleave="if(window.innerWidth >= 768) dropdown = false">
          <button type="button" @click="if(window.innerWidth < 768) dropdown = !dropdown"
            class="flex items-center justify-between w-full md:w-auto text-lg font-medium text-gray-700 hover:text-blue-700 transition focus:outline-none">
            <span class="flex items-center gap-2">
              <ion-icon name="cart-outline" size="large" aria-label="Carrinho"></ion-icon>
              <span>Carrinho</span>
            </span>
            @if (count($cart))
              <span
                class="flex items-center justify-center bg-blue-600 text-white text-xs rounded-full px-2 py-0.5 font-bold ml-2"
                style="min-width: 1.5rem; height: 1.5rem;">
                {{ count($cart) }}
              </span>
            @endif
          </button>
          <!-- Dropdown suspenso, responsivo -->
          <div x-cloak x-show="dropdown" x-transition @click.away="dropdown = false"
            class="absolute right-0 mt-3 w-[480px] bg-white shadow-2xl rounded-xl border z-50" style="display: none;">
            <div class="p-4">
              <h3 class="text-lg font-bold mb-3">Seu Carrinho</h3>
              @if (count($cart))
                <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto mb-4">
                  @foreach ($cart as $id => $item)
                    <li class="flex items-center justify-between py-3">
                      <div class="flex items-center gap-4">
                        <img src="{{ asset('products/' . ($item['image'] ?? 'placeholder.jpg')) }}"
                          alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-lg border">
                        <div>
                          <div class="font-bold text-gray-900 text-base">{{ $item['name'] }}</div>
                          <div class="text-xs text-gray-500">Qtd: {{ $item['quantity'] }}</div>
                        </div>
                      </div>
                      <div class="text-right">
                        <div class="font-bold text-blue-600 text-base">R$
                          {{ number_format($item['price'], 2, ',', '.') }}</div>
                        <form action="{{ route('shop.cart.remove', $id) }}" method="POST">
                          @csrf
                          <button type="submit" class="text-xs text-red-500 hover:underline mt-1">Remover</button>
                        </form>
                      </div>
                    </li>
                  @endforeach
                </ul>
                <div class="flex justify-between items-center font-bold text-base mb-4">
                  <span>Total:</span>
                  <span>
                    R$
                    {{ number_format(collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']), 2, ',', '.') }}
                  </span>
                </div>
                <a href="{{ route('shop.cart.index') }}"
                  class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-bold transition">
                  Ir para o carrinho
                </a>
              @else
                <div class="text-gray-500 text-center py-8">Seu carrinho está vazio.</div>
              @endif
            </div>
          </div>
        </div>

        <!-- Usuário (dropdown igual ao carrinho, sem repetições) -->
        <div x-data="{ dropdown: false }" class="relative w-full md:w-auto"
          @mouseenter="if(window.innerWidth >= 768) dropdown = true"
          @mouseleave="if(window.innerWidth >= 768) dropdown = false">
          @auth
            <button type="button" @click="if(window.innerWidth < 768) dropdown = !dropdown"
              class="flex items-center gap-2 focus:outline-none text-lg font-medium text-gray-700 hover:text-blue-700 transition w-full md:w-auto">
              <ion-icon name="person-circle-outline" size="large" aria-label="Conta"></ion-icon>
              <span class="ml-2">{{ $user->name }}</span>
              <ion-icon name="chevron-down-outline" size="small" class="text-gray-400"></ion-icon>
            </button>
            <!-- Dropdown suspenso, responsivo -->
            <div x-cloak x-show="dropdown" x-transition @click.away="dropdown = false"
              class="absolute right-0 mt-3 w-72 bg-white shadow-2xl rounded-xl border z-50" style="display: none;">
              <ul class="flex flex-col gap-2 mb-2">
                @if ($isAdmin)
                  <li>
                    <a href="{{ route('admin.dashboard') }}"
                      class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded transition">Painel
                      Admin</a>
                  </li>
                @else
                  <li>
                    <a href="{{ $userDashboard }}"
                      class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded transition">Minha
                      Conta</a>
                  </li>
                @endif
                <li>
                  <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit"
                      class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 hover:text-red-700 rounded transition">Sair</button>
                  </form>
                </li>
              </ul>
            </div>
          @else
            <a href="/login"
              class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-bold transition w-full md:w-auto">
              <ion-icon name="person-circle-outline" size="large" aria-label="Entrar"></ion-icon>
              <span>Entrar</span>
            </a>
          @endauth
        </div>
      </div>
    </div>
  </div>
</nav>
