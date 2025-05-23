@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
  <div class="max-w-7xl w-full mx-auto bg-white rounded-lg shadow p-8 mt-8">
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold">Produtos</h2>
      <a href="{{ route('admin.products.create') }}"
        class="bg-blue-600 text-white px-6 py-3 rounded text-lg hover:bg-blue-700 transition flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        Adicionar Produto
      </a>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto">
        <thead class="bg-gray-100 sticky top-0">
          <tr>
            <th class="px-6 py-4 text-left text-lg">Imagem</th>
            <th class="px-6 py-4 text-left text-lg">Nome</th>
            <th class="px-6 py-4 text-left text-lg">Preço</th>
            <th class="px-6 py-4 text-left text-lg">Estoque</th>
            <th class="px-6 py-4 text-center text-lg">Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $product)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                  class="w-24 h-24 object-cover rounded shadow-sm border">
              </td>
              <td class="px-6 py-4 font-semibold text-lg">{{ $product->name }}</td>
              <td class="px-6 py-4 text-lg">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
              <td class="px-6 py-4 text-lg">{{ $product->stock }}</td>
              <td class="px-6 py-4">
                <div class="flex justify-center items-center gap-6">
                  <!-- Visualizar como cliente -->
                  <a href="{{ route('shop.products.show', $product) }}"
                    class="text-blue-600 hover:text-blue-800 flex items-center justify-center" title="Visualizar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor" style="vertical-align: middle;">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </a>
                  <!-- Editar -->
                  <a href="{{ route('admin.products.edit', $product) }}"
                    class="text-yellow-500 hover:text-yellow-700 flex items-center justify-center" title="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor" style="vertical-align: middle;">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16.862 5.487a2.121 2.121 0 113 3l-9.193 9.193a4 4 0 01-1.414.943l-3.1 1.034a.5.5 0 01-.632-.632l1.034-3.1a4 4 0 01.943-1.414l9.193-9.193z" />
                    </svg>
                  </a>
                  <!-- Excluir -->
                  <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                    onsubmit="return confirm('Tem certeza que deseja excluir?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 flex items-center justify-center"
                      title="Excluir">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" style="vertical-align: middle;">
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
              <td colspan="5" class="text-center text-gray-500 py-12 text-xl">Nenhum produto encontrado.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
      <div class="mt-8 flex justify-center">
        {{ $products->links() }}
      </div>
    </div>
  </div>
@endsection
