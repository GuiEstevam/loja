@extends('layouts.app')
@section('title', 'Editar Produto')

@section('content')
  <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
      <!-- Cabeçalho e navegação -->
      <div class="flex flex-col space-y-4 mb-8">
        <nav class="flex items-center space-x-2 text-sm text-gray-600">
          <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0 0h4m-4 0a2 2 0 01-2-2v-4a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4z" />
            </svg>
            Dashboard
          </a>
          <span class="text-gray-400">/</span>
          <a href="{{ route('admin.products.index') }}" class="hover:text-gray-900">Produtos</a>
          <span class="text-gray-400">/</span>
          <span class="text-gray-900 font-medium">Editar</span>
        </nav>

        <h1 class="text-3xl font-bold text-gray-900">Editar Produto</h1>
      </div>

      <!-- Formulário -->
      <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data"
        class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Seção de Informações Básicas -->
        <div class="space-y-6 divide-y divide-gray-200">
          <!-- Nome e Slug -->
          <div class="grid grid-cols-1 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Produto</label>
              <input type="text" name="name" value="{{ old('name', $product->name) }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200"
                required>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">URL Amigável (Slug)</label>
              <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50"
                required>
            </div>
          </div>

          <!-- Descrição -->
          <div class="pt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição Detalhada</label>
            <textarea name="description" rows="4"
              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200"
              required>{{ old('description', $product->description) }}</textarea>
          </div>

          <!-- Preço e Estoque -->
          <div class="pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Preço (R$)</label>
              <div class="relative mt-1 rounded-md shadow-sm">
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}"
                  class="block w-full pr-12 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                  required>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Estoque Disponível</label>
              <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required>
            </div>
          </div>

          <!-- Seleções com Componentes Personalizados -->
          <div class="pt-6 space-y-6">
            <!-- Marcas -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
              <select name="brand_id"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white">
                @foreach ($brands as $brand)
                  <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                    {{ $brand->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Categorias (Select Múltiplo Estilizado) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Categorias</label>
              <div class="relative mt-1">
                <select name="categories[]" multiple
                  class="select-multiple block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white">
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                      {{ $product->categories->contains($category) ? 'selected' : '' }}>
                      {{ $category->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Cores com Mini Swatches -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Cores Disponíveis</label>
              <div class="mt-1 grid grid-cols-2 md:grid-cols-4 gap-2">
                @foreach ($colors as $color)
                  <label
                    class="flex items-center space-x-2 p-3 rounded-lg border border-gray-200 hover:border-blue-400 cursor-pointer">
                    <input type="checkbox" name="colors[]" value="{{ $color->id }}"
                      {{ $product->colors->contains($color) ? 'checked' : '' }}
                      class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="flex items-center">
                      <span class="inline-block w-4 h-4 rounded-full border mr-2"
                        style="background: {{ $color->hex_code }}"></span>
                      {{ $color->name }}
                    </span>
                  </label>
                @endforeach
              </div>
            </div>

            <!-- Tamanhos com Badges Interativos -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Tamanhos Oferecidos</label>
              <div class="mt-1 flex flex-wrap gap-2">
                @foreach ($sizes as $size)
                  <label class="inline-flex items-center">
                    <input type="checkbox" name="sizes[]" value="{{ $size->id }}"
                      {{ $product->sizes->contains($size) ? 'checked' : '' }} class="hidden peer">
                    <span
                      class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 peer-checked:bg-blue-600 peer-checked:text-white cursor-pointer transition-colors">
                      {{ $size->name }}
                    </span>
                  </label>
                @endforeach
              </div>
            </div>
          </div>

          <!-- Upload de Imagem com Preview -->
          <div class="pt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Imagem do Produto</label>
            <div class="mt-1 flex items-center space-x-4">
              <div class="shrink-0">
                <img id="image-preview" src="{{ asset('products/' . $product->image) }}"
                  class="h-24 w-24 rounded-lg object-cover border-2 border-gray-200">
              </div>
              <label class="block">
                <input type="file" name="image" id="image-upload" class="sr-only" onchange="previewImage(event)">
                <span
                  class="px-4 py-2 bg-white rounded-lg border border-gray-300 shadow-sm cursor-pointer hover:bg-gray-50 transition">
                  Alterar Imagem
                </span>
              </label>
            </div>
          </div>
        </div>

        <!-- Ações -->
        <div class="pt-6 flex justify-end space-x-4">
          <a href="{{ route('admin.products.index') }}"
            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
            Cancelar
          </a>
          <button type="submit"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
            Salvar Alterações
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function previewImage(event) {
      const preview = document.getElementById('image-preview');
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
      }
    }
  </script>

  <style>
    /* Estilização avançada para selects múltiplos */
    .select-multiple {
      background-image: none;
      padding: 0.5rem 1rem;
      min-height: 120px;
    }

    .select-multiple option {
      padding: 0.5rem;
      border-radius: 0.375rem;
      margin: 2px 0;
    }

    .select-multiple option:hover {
      background-color: #f3f4f6;
    }

    .select-multiple option:checked {
      background-color: #3b82f6;
      color: white;
    }
  </style>
@endsection
