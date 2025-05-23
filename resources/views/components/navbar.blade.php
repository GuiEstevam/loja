<nav class="flex items-center justify-between px-8 py-4 bg-white shadow">
  <div class="text-2xl font-extrabold tracking-tight">
    <a href="{{ url('/') }}" class="hover:text-blue-600 font-semibold">
      <span class="text-black">Sky</span><span class="text-blue-600">Fashion</span>
    </a>
  </div>
  <div class="space-x-6 flex items-center">
    <a href="{{ route('shop.products.index') }}" class="hover:text-blue-600 font-semibold">Produtos</a>

    {{-- Link do carrinho --}}
    <a href="{{ route('shop.cart.index') }}" class="hover:text-blue-600 font-semibold flex items-center gap-1">
      <!-- Ícone SVG de carrinho -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 inline" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007.53 17h8.94a1 1 0 00.88-1.47L17 13M7 13V6a1 1 0 011-1h3m4 0h2a1 1 0 011 1v7" />
      </svg>
      Carrinho
    </a>

    @guest
      <a href="{{ route('login') }}" class="hover:text-blue-600 font-semibold">Entrar</a>
    @endguest
    @auth
      @if (auth()->user()->hasRole('admin'))
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 font-semibold">Painel Admin</a>
      @else
        <a href="{{ route('shop.dashboard') }}" class="hover:text-blue-600 font-semibold">Minha Conta</a>
      @endif
      <form method="POST" action="{{ route('logout') }}" class="inline">
        @csrf
        <button type="submit" class="ml-4 text-red-600 hover:text-red-800 font-semibold">
          Sair
        </button>
      </form>
    @endauth
  </div>
</nav>
