<nav x-data="{ open: false }" class="bg-white shadow sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-6">
    <div class="flex items-center justify-between h-20">
      <!-- Logo -->
      <a href="/" class="flex items-center gap-2 text-blue-700 font-extrabold text-2xl">
        <span>MinhaLoja</span>
      </a>
      <!-- Menu Desktop -->
      <div class="hidden md:flex gap-8 items-center">
        <a href="/" class="text-lg font-medium text-gray-700 hover:text-gray-900 transition">Início</a>
        <a href="/produtos" class="text-lg font-medium text-gray-700 hover:text-gray-900 transition">Produtos</a>
        <a href="/sobre" class="text-lg font-medium text-gray-700 hover:text-gray-900 transition">Sobre</a>
        <a href="/contato" class="text-lg font-medium text-gray-700 hover:text-gray-900 transition">Contato</a>
        <a href="/login"
          class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-bold transition">Entrar</a>
      </div>
      <!-- Botão Hamburguer -->
      <button @click="open = !open"
        class="md:hidden flex items-center justify-center w-12 h-12 rounded-full hover:bg-blue-50 transition">
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    <!-- Menu Mobile -->
    <div x-show="open" class="md:hidden flex flex-col gap-4 py-6 animate-fade-in" @click.away="open = false">
      <a href="/" class="text-lg font-medium text-gray-700 hover:text-gray-900 transition">Início</a>
      <a href="/produtos" class="text-lg font-medium text-gray-700 hover:text-gray-900 transition">Produtos</a>
      <a href="/sobre" class="text-lg font-medium text-gray-700 hover:text-gray-900 transition">Sobre</a>
      <a href="/contato" class="text-lg font-medium text-gray-700 hover:text-gray-900 transition">Contato</a>
      <a href="/login"
        class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-bold transition text-center">Entrar</a>
    </div>
  </div>
</nav>
