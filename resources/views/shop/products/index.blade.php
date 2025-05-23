@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
  <div class="max-w-7xl mx-auto py-10 flex gap-8">
    <!-- Sidebar de Filtros -->
    <aside class="w-64 hidden md:block">
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="font-bold mb-4 text-lg">Cores</h2>
        <div class="grid grid-cols-5 gap-2 mb-4">
          @foreach (['Amarelo', 'Azul', 'Bege', 'Branco', 'Cinza', 'Laranja', 'Marrom', 'Multicor', 'Prata', 'Preto', 'Rosa', 'Roxo', 'Verde', 'Vermelho'] as $cor)
            <button class="w-8 h-8 rounded-full border border-gray-300" title="{{ $cor }}"
              style="background: {{ strtolower($cor) }};"></button>
          @endforeach
        </div>
        <h2 class="font-bold mb-4 text-lg">Tamanho</h2>
        <div class="grid grid-cols-5 gap-2">
          @foreach ([16, 17.5, 18.5, 20, 21, 22.5, 24, 25, 26, 27, 28, 28.5, 29, 30, 31, 32, 33, 34, 35, 36] as $tamanho)
            <button class="border rounded px-2 py-1 text-sm">{{ $tamanho }}</button>
          @endforeach
        </div>
      </div>
      <!-- Adicione mais filtros aqui se quiser -->
    </aside>

    <!-- Grid de Produtos -->
    <div class="flex-1">
      <form method="GET" action="{{ route('shop.products.index') }}" class="mb-8 flex justify-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nome do produto..."
          class="border rounded-l px-4 py-2 w-64 focus:outline-none">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700">
          Buscar
        </button>
      </form>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @forelse($products as $product)
          <div class="flex flex-col items-center">
            <a href="{{ route('shop.products.show', $product) }}"
              class="block w-full bg-white rounded-lg overflow-hidden shadow transition-transform duration-200 hover:shadow-2xl hover:-translate-y-2 hover:scale-105 group focus:outline-none focus:ring-2 focus:ring-blue-500">
              <div class="w-full aspect-square bg-white flex items-center justify-center">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                  class="w-full h-full object-cover" style="object-position: center;">
              </div>
            </a>
            <div class="w-full px-2 mt-3 text-left">
              <h2 class="font-semibold text-lg truncate group-hover:text-blue-700 transition">
                {{ $product->name }}
              </h2>
              <div class="text-gray-500 text-sm mb-1 line-clamp-2">
                {{ $product->description }}
              </div>
              <div class="text-blue-600 font-bold text-xl mt-1">
                R$ {{ number_format($product->price, 2, ',', '.') }}
              </div>
            </div>
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
