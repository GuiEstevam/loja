@extends('layouts.app')

@section('title', 'Cadastrar Produto')

@section('content')
  <div class="max-w-2xl mx-auto py-10">
    <div class="bg-white shadow rounded p-8">
      <h2 class="text-2xl font-bold mb-6 text-gray-800">Cadastrar Produto</h2>
      @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
          <label for="name" class="block mb-1 font-semibold">Nome</label>
          <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" required
            value="{{ old('name') }}">
        </div>
        <div class="mb-4">
          <label for="description" class="block mb-1 font-semibold">Descrição</label>
          <textarea name="description" id="description" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
        </div>
        <div class="mb-4">
          <label for="price" class="block mb-1 font-semibold">Preço</label>
          <input type="number" step="0.01" name="price" id="price" class="w-full border rounded px-3 py-2"
            required value="{{ old('price') }}">
        </div>
        <div class="mb-4">
          <label for="stock" class="block mb-1 font-semibold">Estoque</label>
          <input type="number" name="stock" id="stock" class="w-full border rounded px-3 py-2" required
            value="{{ old('stock') }}">
        </div>
        <div class="mb-4">
          <label for="active" class="block mb-1 font-semibold">Ativo</label>
          <select name="active" id="active" class="w-full border rounded px-3 py-2">
            <option value="1" {{ old('active', 1) == 1 ? 'selected' : '' }}>Sim</option>
            <option value="0" {{ old('active') == 0 ? 'selected' : '' }}>Não</option>
          </select>
        </div>
        <div class="mb-4">
          <label for="image" class="block mb-1 font-semibold">Imagem</label>
          <input type="file" name="image" id="image" class="w-full border rounded px-3 py-2">
        </div>
        <div class="flex items-center">
          <button type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-bold">Salvar</button>
          <a href="{{ route('shop.products.index') }}" class="ml-4 text-gray-600 hover:underline">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
@endsection
