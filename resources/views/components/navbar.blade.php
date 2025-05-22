<nav class="flex items-center justify-between px-8 py-4 bg-white shadow">
  <div class="text-2xl font-extrabold tracking-tight">
    <span class="text-black">Loja</span><span class="text-blue-600">Dropshipping</span>
  </div>
  <div class="space-x-6">
    <a href="{{ url('/') }}" class="hover:text-blue-600 font-semibold">Início</a>
    <a href="{{ route('products.index') }}" class="hover:text-blue-600 font-semibold">Produtos</a>
    <a href="#" class="hover:text-blue-600 font-semibold">Sobre</a>
    @auth
      <a href="{{ route('dashboard') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold">Painel</a>
    @else
      <a href="{{ route('login') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold">Entrar</a>
    @endauth
  </div>
</nav>
