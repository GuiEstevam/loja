@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
  <div class="max-w-7xl mx-auto py-10">
    @if (session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif
    <a href="{{ route('admin.products.create') }}"
      class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Adicionar Produto</a>
    <div class="bg-white shadow rounded p-4">
      <table class="min-w-full border-separate border-spacing-y-2 border-spacing-x-4">
        <thead>
          <tr>
            <th class="px-4 py-2 text-left">Imagem</th>
            <th class="px-4 py-2 text-left">Nome</th>
            <th class="px-4 py-2 text-left">Preço</th>
            <th class="px-4 py-2 text-left">Estoque</th>
            <th class="px-4 py-2 text-left">Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($products as $product)
            <tr class="bg-white">
              <td class="px-4 py-2 align-middle">
                @if ($product->image)
                  <img src="{{ asset('storage/' . $product->image) }}" alt="Imagem Produto"
                    class="w-16 h-16 object-cover rounded">
                @else
                  <span class="text-gray-400">Sem imagem</span>
                @endif
              </td>
              <td class="px-4 py-2 align-middle">{{ $product->name }}</td>
              <td class="px-4 py-2 align-middle">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
              <td class="px-4 py-2 align-middle">{{ $product->stock }}</td>
              <td class="px-4 py-2 align-middle">
                <div class="flex space-x-3">
                  <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:underline">Ver</a>
                  <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 hover:underline">Editar</a>
                  <form action="{{ route('products.destroy', $product) }}" method="POST"
                    onsubmit="return confirm('Tem certeza?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
