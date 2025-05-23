@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
  <div class="max-w-xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Editar Produto</h1>
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data"
      class="space-y-6">
      @csrf
      @method('PUT')

      <div>
        <label for="name" class="block font-semibold mb-1">Nome</label>
        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
          class="w-full border rounded px-3 py-2" required>
      </div>

      <div>
        <label for="price" class="block font-semibold mb-1">Preço</label>
        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}"
          class="w-full border rounded px-3 py-2" step="0.01" required>
      </div>

      <div>
        <label for="stock" class="block font-semibold mb-1">Estoque</label>
        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}"
          class="w-full border rounded px-3 py-2" required>
      </div>

      <div>
        <label for="image" class="block font-semibold mb-1">Imagem do Produto</label>
        <!-- Preview da imagem atual -->
        <div class="mb-2">
          <img id="preview-image" src="{{ asset('storage/' . $product->image) }}" alt="Imagem atual"
            class="w-32 h-32 object-cover rounded border">
        </div>
        <input type="file" name="image" id="image" accept="image/*"
          class="block w-full text-gray-700 border rounded px-3 py-2" onchange="previewImage(event)">
        <small class="text-gray-500">Formatos aceitos: jpg, jpeg, png. Máx: 2MB.</small>
      </div>

      <div class="flex gap-4">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-bold">
          Salvar Alterações
        </button>
        <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2">Cancelar</a>
      </div>
    </form>
  </div>

  <script>
    function previewImage(event) {
      const [file] = event.target.files;
      if (file) {
        document.getElementById('preview-image').src = URL.createObjectURL(file);
      }
    }
  </script>
@endsection
