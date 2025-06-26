@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
  <div class="max-w-7xl mx-auto py-6 px-4 md:px-0 flex flex-col md:flex-row gap-8">

    <!-- Sidebar de Filtros (desktop) -->
    <aside class="w-full md:w-64 mb-6 md:mb-0 md:block sticky top-20 self-start">
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-bold mb-4 text-lg">Filtros</h2>
        <form method="GET" action="{{ route('shop.products.index') }}" class="space-y-6">

          <!-- Filtro por Marca -->
          <div>
            <h3 class="font-semibold mb-2">Marca</h3>
            <select name="brand" class="w-full border rounded px-3 py-2 text-sm">
              <option value="">Todas as marcas</option>
              @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                  {{ $brand->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Filtro por Categoria -->
          <div>
            <h3 class="font-semibold mb-2">Categoria</h3>
            <select name="category" class="w-full border rounded px-3 py-2 text-sm">
              <option value="">Todas as categorias</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Filtro por Cores -->
          <div>
            <h3 class="font-semibold mb-2">Cores</h3>
            <div class="flex flex-wrap gap-2">
              @foreach ($colors as $color)
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" name="colors[]" value="{{ $color->id }}"
                    {{ is_array(request('colors')) && in_array($color->id, request('colors')) ? 'checked' : '' }}
                    class="form-checkbox">
                  <span class="w-6 h-6 rounded-full border"
                    style="background-color: {{ $color->hex_code ?? '#ccc' }}"></span>
                  <span class="text-sm">{{ $color->name }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <!-- Filtro por Tamanhos -->
          <div>
            <h3 class="font-semibold mb-2">Tamanhos</h3>
            <div class="flex flex-wrap gap-2">
              @foreach ($sizes as $size)
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" name="sizes[]" value="{{ $size->id }}"
                    {{ is_array(request('sizes')) && in_array($size->id, request('sizes')) ? 'checked' : '' }}
                    class="form-checkbox">
                  <span class="text-sm">{{ $size->name }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <!-- Botão aplicar filtros -->
          <div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Aplicar
              filtros</button>
          </div>

        </form>
      </div>
    </aside>

    <!-- Conteúdo principal -->
    <div class="flex-1 flex flex-col">

      <!-- Barra superior: busca e ordenação -->
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-4">
        <!-- Busca -->
        <form method="GET" action="{{ route('shop.products.index') }}" class="flex-1 flex gap-2">
          <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Buscar por nome do produto..."
            class="border rounded-full px-4 py-2 w-full focus:outline-none text-sm" />
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700">
            <ion-icon name="search-outline"></ion-icon>
          </button>
        </form>

        <!-- Ordenação -->
        <form method="GET" action="{{ route('shop.products.index') }}" class="flex items-center gap-2">
          <label for="sort" class="text-sm font-medium">Ordenar por:</label>
          <select name="sort" id="sort" onchange="this.form.submit()" class="border rounded px-3 py-2 text-sm">
            <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Padrão</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Preço: menor para maior
            </option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Preço: maior para menor
            </option>
            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nome: A a Z</option>
            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nome: Z a A</option>
          </select>
        </form>
      </div>

      <!-- Breadcrumb -->
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
              <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}"
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
              @if ($product->fakerprice)
                <span class="line-through text-gray-400 text-xs">R$
                  {{ number_format($product->fakerprice, 2, ',', '.') }}</span>
              @endif
            </div>
            <!-- Tamanhos (desktop e mobile, nunca estoura) -->
            @if ($product->sizes && $product->sizes->count())
              <div class="flex flex-wrap gap-1 mt-1">
                @foreach ($product->sizes as $size)
                  <span class="border rounded px-2 py-1 text-xs font-medium min-w-[2.2rem] text-center bg-gray-50">
                    {{ $size->name }}
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
