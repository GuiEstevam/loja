@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
  <div class="max-w-7xl mx-auto py-6 px-2 md:px-0 flex flex-col md:flex-row gap-8">

    <!-- Sidebar de Filtros (desktop) -->
    <aside class="w-full md:w-64 mb-4 md:mb-0 md:block">
      <div class="bg-white rounded-lg shadow p-4 md:p-6 mb-6">
        <h2 class="font-bold mb-4 text-lg">Cores</h2>
        <div class="grid grid-cols-7 md:grid-cols-5 gap-2 mb-4">
          @foreach (['Amarelo', 'Azul', 'Bege', 'Branco', 'Cinza', 'Laranja', 'Marrom', 'Multicor', 'Prata', 'Preto', 'Rosa', 'Roxo', 'Verde', 'Vermelho'] as $cor)
            <button class="w-8 h-8 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500"
              title="{{ $cor }}" style="background: {{ strtolower($cor) }};"></button>
          @endforeach
        </div>
        <h2 class="font-bold mb-4 text-lg">Tamanho</h2>
        <div class="flex flex-wrap gap-2">
          @foreach ([16, 17.5, 18.5, 20, 21, 22.5, 24, 25, 26, 27, 28, 28.5, 29, 30, 31, 32, 33, 34, 35, 36] as $tamanho)
            <button class="border rounded px-2 py-1 text-sm min-w-[2.5rem] text-center">
              {{ rtrim(rtrim(number_format($tamanho, 1, '.', ''), '0'), '.') }}
            </button>
          @endforeach
        </div>
      </div>
    </aside>

    <!-- Conteúdo principal -->
    <div class="flex-1">

      <!-- Barra superior: busca, filtros e ordenação (mobile e desktop) -->
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-4">
        <!-- Busca -->
        <form method="GET" action="{{ route('shop.products.index') }}" class="flex-1 flex gap-2">
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nome do produto..."
            class="border rounded-full px-4 py-2 w-full focus:outline-none text-sm" />
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700">
            <ion-icon name="search-outline"></ion-icon>
          </button>
        </form>
        <!-- Filtros e ordenação (mobile/desktop) -->
        <div class="flex gap-2 sm:ml-2">
          <button class="flex items-center gap-1 border rounded-full py-2 px-4 text-sm font-medium hover:bg-blue-50">
            <ion-icon name="filter-outline"></ion-icon> Filtrar
          </button>
          <button class="flex items-center gap-1 border rounded-full py-2 px-4 text-sm font-medium hover:bg-blue-50">
            <ion-icon name="swap-vertical-outline"></ion-icon> Ordenar
          </button>
        </div>
      </div>

      <!-- Breadcrumb (mobile e desktop) -->
      <nav class="text-xs text-gray-500 flex items-center gap-2 mb-3">
        <a href="/" class="hover:underline">Home</a>
        <span>/</span>
        <a href="#" class="hover:underline">Categoria</a>
        <span>/</span>
        <span class="text-gray-700 font-semibold">Produtos</span>
      </nav>

      <!-- Grid de Produtos -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @forelse($products as $product)
          <div class="relative flex flex-col bg-white rounded-xl shadow border p-2 group hover:shadow-xl transition">
            <!-- Badge de desconto -->
            @if ($product->discount_percent)
              <span class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-0.5 rounded z-10">
                -{{ $product->discount_percent }}%
              </span>
            @endif
            <!-- Imagem -->
            <a href="{{ route('shop.products.show', $product) }}" class="block relative">
              <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                class="w-full h-48 object-cover rounded-lg mb-2" />
            </a>
            <!-- Nome -->
            <div class="font-semibold text-sm text-gray-900 mb-1 truncate">{{ $product->name }}</div>
            <!-- Avaliação (opcional) -->
            <div class="flex items-center gap-1 text-yellow-400 text-xs mb-1">
              ★★★★☆
              <span class="text-gray-500 ml-1">(12)</span>
            </div>
            <!-- Preço -->
            <div class="flex items-baseline gap-2 mb-1">
              <span class="text-lg font-bold text-blue-700">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
              @if ($product->old_price)
                <span class="line-through text-gray-400 text-xs">R$
                  {{ number_format($product->old_price, 2, ',', '.') }}</span>
              @endif
            </div>
            <!-- Tamanhos (desktop e mobile, nunca estoura) -->
            @if ($product->sizes)
              <div class="flex flex-wrap gap-1 mt-1">
                @foreach ($product->sizes as $size)
                  <span class="border rounded px-2 py-1 text-xs font-medium min-w-[2.2rem] text-center bg-gray-50">
                    {{ rtrim(rtrim(number_format($size, 1, '.', ''), '0'), '.') }}
                  </span>
                @endforeach
              </div>
            @endif
          </div>
        @empty
          <p class="col-span-3 text-center text-gray-500">Nenhum produto encontrado.</p>
        @endforelse
      </div>

      <div class="mt-8">
        {{ $products->links() }}
      </div>
    </div>
  </div>
@endsection
