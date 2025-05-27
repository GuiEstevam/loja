@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
  <div class="max-w-7xl mx-auto py-8 px-2 md:px-0">
    <div class="bg-white shadow rounded-lg p-6 md:p-8">
      <!-- Breadcrumb e botão voltar -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-6">
        <nav class="flex items-center gap-2 text-base md:text-lg text-gray-500">
          <a href="{{ route('admin.dashboard') }}" class="hover:underline flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0 0h4m-4 0a2 2 0 01-2-2v-4a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4z" />
            </svg>
            Dashboard
          </a>
          <span>/</span>
          <span class="text-gray-700 font-semibold">Produtos</span>
        </nav>
        <a href="{{ route('admin.dashboard') }}"
          class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition text-base font-medium">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Voltar
        </a>
      </div>

      <h1 class="text-3xl font-bold mb-2 text-center md:text-left">Gerenciar Produtos</h1>
      <p class="text-gray-600 mb-6 text-center md:text-left">Visão geral e gestão de todos os produtos</p>

      <!-- Filtros -->
      <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4 items-end mb-6">
        <div>
          <label class="block text-xs text-gray-500 mb-1">Buscar:</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome, SKU ou categoria..."
            class="border rounded-lg px-3 py-2 text-base w-full" />
        </div>
        <div>
          <label class="block text-xs text-gray-500 mb-1 invisible md:visible">.</label>
          <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition w-full">Pesquisar</button>
        </div>
        <div class="flex gap-2">
          <label class="block text-xs text-gray-500 mb-1 invisible md:visible">.</label>
          <a href="{{ route('admin.products.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2 w-full justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Produto
          </a>
        </div>
      </form>

      <!-- Tabela responsiva -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-base">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left font-semibold">Imagem</th>
              <th class="px-4 py-3 text-left font-semibold">Produto</th>
              <th class="px-4 py-3 text-left font-semibold">Marca</th>
              <th class="px-4 py-3 text-left font-semibold">Categorias</th>
              <th class="px-4 py-3 text-left font-semibold">Cores</th>
              <th class="px-4 py-3 text-left font-semibold">Tamanhos</th>
              <th class="px-4 py-3 text-left font-semibold">Preço</th>
              <th class="px-4 py-3 text-center font-semibold">Estoque</th>
              <th class="px-4 py-3 text-center font-semibold">Status</th>
              <th class="px-4 py-3 text-center font-semibold">Ações</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            @forelse($products as $product)
              <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                  <div class="w-14 h-14 rounded-lg overflow-hidden border">
                    <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}"
                      class="w-full h-full object-cover">
                  </div>
                </td>
                <td class="px-4 py-3">
                  <div class="font-semibold text-base text-gray-900">{{ $product->name }}</div>
                  <div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                </td>
                <td class="px-4 py-3 text-gray-800">{{ $product->brand->name ?? '-' }}</td>
                <td class="px-4 py-3">
                  <div class="flex flex-wrap gap-1">
                    @foreach ($product->categories as $category)
                      <span class="inline-block px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-semibold">
                        {{ $category->name }}
                      </span>
                    @endforeach
                  </div>
                </td>
                <td class="px-4 py-3">
                  <div class="flex flex-wrap gap-1">
                    @foreach ($product->colors as $color)
                      <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold border shadow-sm"
                        style="background: {{ $color->hex_code ? $color->hex_code . '22' : '#f3f4f6' }};
                               color: #222;
                               border-color: {{ $color->hex_code ?? '#e5e7eb' }};">
                        <span class="inline-block w-3 h-3 rounded-full border"
                          style="background: {{ $color->hex_code ?? '#eee' }}; border-color: #ccc;"></span>
                        {{ $color->name }}
                      </span>
                    @endforeach
                  </div>
                </td>
                <td class="px-4 py-3">
                  <div class="flex flex-wrap gap-1">
                    @foreach ($product->sizes as $size)
                      <span class="inline-block px-2 py-1 rounded bg-gray-100 text-xs font-semibold">
                        {{ $size->name }}
                      </span>
                    @endforeach
                  </div>
                </td>
                <td class="px-4 py-3 text-gray-900 text-base">
                  R$ {{ number_format($product->price, 2, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-center">
                  <span
                    class="px-3 py-2 rounded-full text-xs font-bold
                      {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $product->stock }}
                  </span>
                </td>
                <td class="px-4 py-3 text-center">
                  <span
                    class="px-3 py-2 rounded-full text-xs font-bold
                      {{ $product->active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $product->active ? 'Ativo' : 'Inativo' }}
                  </span>
                </td>
                <td class="px-4 py-3 text-center">
                  <div class="flex gap-4 justify-center items-center">
                    <a href="{{ route('shop.products.show', $product) }}" class="text-blue-600 hover:text-blue-900"
                      title="Visualizar" target="_blank">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </a>
                    <a href="{{ route('admin.products.edit', $product) }}"
                      class="text-yellow-600 hover:text-yellow-900" title="Editar">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                    </a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                      onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-600 hover:text-red-900" title="Excluir">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m5 0H6" />
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="11" class="py-12 text-center text-gray-500 text-lg">
                  <div class="flex flex-col items-center justify-center">
                    <svg class="w-16 h-16 mb-4 text-gray-400" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="text-lg">Nenhum produto cadastrado</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-6 flex justify-center">
        {{ $products->links() }}
      </div>
    </div>
  </div>
@endsection
