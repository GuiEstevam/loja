@extends('layouts.app')
@section('title', 'Produtos da Marca: ' . $brand->name)

@section('content')
  <div class="max-w-7xl mx-auto py-8 px-2 md:px-0">
    <div class="bg-white shadow rounded-lg p-6 md:p-8">
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
          <a href="{{ route('admin.brands.index') }}" class="hover:underline">Marcas</a>
          <span>/</span>
          <span class="text-gray-700 font-semibold">{{ $brand->name }}</span>
        </nav>
        <a href="{{ route('admin.brands.index') }}"
          class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition text-base font-medium">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Voltar
        </a>
      </div>

      <h1 class="text-3xl font-bold mb-2 text-center md:text-left">Produtos da Marca "{{ $brand->name }}"</h1>
      <p class="text-gray-600 mb-6 text-center md:text-left">Todos os produtos relacionados a esta marca</p>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-lg">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left font-semibold">Imagem</th>
              <th class="px-4 py-3 text-left font-semibold">Produto</th>
              <th class="px-4 py-3 text-left font-semibold">Categorias</th>
              <th class="px-4 py-3 text-left font-semibold">Cores</th>
              <th class="px-4 py-3 text-left font-semibold">Tamanhos</th>
              <th class="px-4 py-3 text-center font-semibold">Estoque</th>
              <th class="px-4 py-3 text-right font-semibold">Preço</th>
              <th class="px-4 py-3 text-center font-semibold">Ações</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            @forelse($products as $product)
              <tr class="hover:bg-gray-50 transition align-middle">
                <td class="py-3 px-4">
                  <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}"
                    class="w-16 h-16 object-cover rounded border">
                </td>
                <td class="py-3 px-4 font-semibold align-middle">{{ $product->name }}</td>
                <td class="py-3 px-4 align-middle">
                  <div class="flex flex-wrap gap-1">
                    @forelse($product->categories as $category)
                      <span class="inline-block px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-semibold">
                        {{ $category->name }}
                      </span>
                    @empty
                      <span class="text-gray-400">—</span>
                    @endforelse
                  </div>
                </td>
                <td class="py-3 px-4 align-middle">
                  <div class="flex flex-wrap gap-1">
                    @foreach ($product->colors as $color)
                      @php
                        $hex = strtolower($color->hex_code ?? '');
                        $isDark = $hex === '#000000' || $hex === '#111111' || strtolower($color->name) === 'preto';
                      @endphp
                      <span class="inline-block px-2 py-1 rounded text-base font-medium"
                        style="background: {{ $color->hex_code ?? '#eee' }}; color: {{ $isDark ? '#fff' : '#222' }};">
                        {{ $color->name }}
                      </span>
                    @endforeach
                  </div>
                </td>
                <td class="py-3 px-4 align-middle">
                  <div class="flex flex-wrap gap-1">
                    @foreach ($product->sizes as $size)
                      <span class="inline-block px-2 py-1 rounded bg-gray-100 text-base font-medium">
                        {{ $size->name }}
                      </span>
                    @endforeach
                  </div>
                </td>
                <td class="py-3 px-4 text-center align-middle">
                  <span
                    class="px-3 py-2 rounded-full text-base font-bold
                                    {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $product->stock }}
                  </span>
                </td>
                <td class="py-3 px-4 text-right font-semibold align-middle">
                  R$ {{ number_format($product->price, 2, ',', '.') }}
                </td>
                <td class="py-3 px-4 whitespace-nowrap align-middle">
                  <div class="flex items-center justify-center gap-4">
                    <a href="{{ route('shop.products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900"
                      title="Ver na loja" target="_blank">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </a>
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900"
                      title="Editar">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="py-8 text-center text-gray-500 text-lg">
                  Nenhum produto nesta marca.
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
