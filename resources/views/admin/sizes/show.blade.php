@extends('layouts.app')
@section('title', 'Produtos do Tamanho: ' . $size->name)

@section('content')
  <div class="max-w-7xl mx-auto py-8 px-2 md:px-0">
    <div class="bg-white shadow rounded-lg p-6 md:p-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-6">
        <nav class="flex items-center gap-2 text-base md:text-lg text-gray-500">
          <a href="{{ route('admin.dashboard') }}" class="hover:underline flex items-center gap-1">
            <!-- ícone dashboard -->
          </a>
          <span>/</span>
          <a href="{{ route('admin.sizes.index') }}" class="hover:underline">Tamanhos</a>
          <span>/</span>
          <span class="text-gray-700 font-semibold">{{ $size->name }}</span>
        </nav>
        <a href="{{ route('admin.sizes.index') }}"
          class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition text-base font-medium">
          <!-- ícone voltar -->
          Voltar
        </a>
      </div>

      <h1 class="text-3xl font-bold mb-2 text-center md:text-left">
        Produtos do tamanho "{{ $size->name }}"
      </h1>
      <p class="text-gray-600 mb-6 text-center md:text-left">Todos os produtos que possuem este tamanho</p>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-lg">
          <thead class="bg-gray-50">
            <tr>
              <th class="py-4 px-6 text-left font-semibold">Imagem</th>
              <th class="py-4 px-6 text-left font-semibold">Produto</th>
              <th class="py-4 px-6 text-left font-semibold">Categorias</th>
              <th class="py-4 px-6 text-left font-semibold">Marca</th>
              <th class="py-4 px-6 text-left font-semibold">Cores</th>
              <th class="py-4 px-6 text-center font-semibold">Estoque</th>
              <th class="py-4 px-6 text-right font-semibold">Preço</th>
              <th class="py-4 px-6 text-center font-semibold">Ações</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            @forelse($products as $product)
              <tr class="hover:bg-gray-50 transition align-middle">
                <td class="py-3 px-6">
                  <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}"
                    class="w-16 h-16 object-cover rounded border">
                </td>
                <td class="py-3 px-6 font-semibold align-middle">{{ $product->name }}</td>
                <td class="py-3 px-6 align-middle">
                  <div class="flex flex-wrap gap-1">
                    @foreach ($product->categories as $category)
                      <span class="inline-block px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-semibold">
                        {{ $category->name }}
                      </span>
                    @endforeach
                  </div>
                </td>
                <td class="py-3 px-6 align-middle">{{ $product->brand->name ?? '-' }}</td>
                <td class="py-3 px-6 align-middle">
                  <div class="flex flex-wrap gap-1">
                    @foreach ($product->colors as $color)
                      <span class="inline-block px-2 py-1 rounded text-base font-medium"
                        style="background: {{ $color->hex_code ?? '#eee' }}; color: #222;">
                        {{ $color->name }}
                      </span>
                    @endforeach
                  </div>
                </td>
                <td class="py-3 px-6 text-center align-middle">
                  <span
                    class="px-3 py-2 rounded-full text-base font-bold
                    {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $product->stock }}
                  </span>
                </td>
                <td class="py-3 px-6 text-right font-semibold align-middle">
                  R$ {{ number_format($product->price, 2, ',', '.') }}
                </td>
                <td class="py-3 px-6 whitespace-nowrap align-middle">
                  <div class="flex items-center justify-center gap-4">
                    <a href="{{ route('shop.products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900"
                      title="Ver na loja" target="_blank">
                      <!-- ícone olho -->
                    </a>
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900"
                      title="Editar">
                      <!-- ícone lápis -->
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="py-8 text-center text-gray-500 text-lg">
                  Nenhum produto com este tamanho.
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
