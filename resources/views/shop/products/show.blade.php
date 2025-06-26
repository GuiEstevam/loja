@extends('layouts.app')

@section('title', $product->name)

@section('content')
  <div class="max-w-6xl mx-auto py-10 px-4 flex flex-col md:flex-row gap-8">

    <!-- Imagem à esquerda -->
    <div class="w-full md:w-1/2 flex flex-col gap-4">
      <div class="bg-white rounded-lg shadow flex items-center justify-center aspect-square overflow-hidden">
        <img src="{{ asset('products/' . ($product->image ?? 'placeholder.jpg')) }}" alt="{{ $product->name }}"
          class="w-full h-full object-cover" style="object-position: center;">
      </div>
      <!-- Miniaturas de cores (se houver) -->
      @if ($product->colors && $product->colors->count())
        <div class="flex gap-2 justify-center mt-2">
          @foreach ($product->colors as $color)
            <div class="flex flex-col items-center">
              <span class="w-8 h-8 rounded-full border-2 border-gray-300"
                style="background: {{ $color->hex_code }}"></span>
              <span class="text-xs text-gray-500 mt-1">{{ $color->name }}</span>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Informações do produto à direita -->
    <div class="w-full md:w-1/2 flex flex-col gap-4">
      <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-2">{{ $product->name }}</h1>
      <div class="flex items-center gap-2 text-gray-600 text-base mb-2">
        @if ($product->brand)
          <span class="font-semibold">{{ $product->brand->name }}</span>
          <span class="mx-1 text-gray-400">|</span>
        @endif
        @if ($product->categories && $product->categories->count())
          <span>
            {{ $product->categories->pluck('name')->join(', ') }}
          </span>
        @endif
        <span class="mx-1 text-gray-400">|</span>
        <span class="text-xs text-gray-400">SKU: {{ $product->sku }}</span>
      </div>
      <div class="flex items-center gap-2 mb-2">
        <span class="text-yellow-400 text-lg">★ ★ ★ ★ ☆</span>
        <span class="text-gray-500 text-sm">(12 avaliações)</span>
      </div>
      <div class="flex items-baseline gap-2">
        <span class="text-3xl font-bold text-blue-600">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
        @if ($product->old_price)
          <span class="line-through text-gray-400 text-lg">R$ {{ number_format($product->old_price, 2, ',', '.') }}</span>
        @endif
        @if ($product->discount_percent)
          <span class="text-green-600 font-semibold">{{ $product->discount_percent }}% off</span>
        @endif
      </div>
      <div class="text-green-700 font-semibold">Ganhe 5% de desconto no Pix</div>
      <div class="mt-4 text-gray-700">{{ $product->description }}</div>
      <!-- Escolha de cor (se houver) -->
      @if ($product->colors && $product->colors->count())
        <div class="mt-4">
          <div class="font-semibold mb-2">Cor</div>
          <div class="flex gap-2 flex-wrap">
            @foreach ($product->colors as $color)
              <label class="flex flex-col items-center cursor-pointer">
                <input type="radio" name="color" value="{{ $color->id }}" class="hidden peer">
                <span class="w-8 h-8 rounded-full border-2 border-gray-300 peer-checked:border-blue-600"
                  style="background: {{ $color->hex_code }}"></span>
                <span class="text-xs text-gray-500 mt-1">{{ $color->name }}</span>
              </label>
            @endforeach
          </div>
        </div>
      @endif
      <!-- Escolha de tamanho (se houver) -->
      @if ($product->sizes && $product->sizes->count())
        <div class="mt-4">
          <div class="font-semibold mb-2">Tamanhos disponíveis</div>
          <div class="flex gap-2 flex-wrap">
            @foreach ($product->sizes as $size)
              <label class="inline-flex items-center cursor-pointer">
                <input type="radio" name="size" value="{{ $size->id }}" class="hidden peer">
                <span
                  class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 peer-checked:bg-blue-600 peer-checked:text-white transition-colors">
                  {{ $size->name }}
                </span>
              </label>
            @endforeach
          </div>
        </div>
      @endif

      @if (session('success'))
        <div class="mt-4 text-green-700 bg-green-100 border border-green-300 px-4 py-3 rounded">
          {{ session('success') }}
        </div>
      @endif

      <div class="mt-6 flex gap-4">
        <!-- Botão de compra direta -->
        <form action="{{ route('shop.cart.buy', $product) }}" method="POST">
          @csrf
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded font-bold transition">
            Comprar
          </button>
        </form>
        <!-- Botão de adicionar ao carrinho -->
        <form action="{{ route('shop.cart.add', $product) }}" method="POST">
          @csrf
          <button type="submit"
            class="border border-blue-600 text-blue-600 px-6 py-3 rounded font-bold hover:bg-blue-600 hover:text-white transition">
            Adicionar ao carrinho
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Avaliações (fake) -->
  <div class="max-w-3xl mx-auto mt-12 bg-white rounded-xl shadow p-8 mb-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Avaliações dos clientes</h2>
    <div class="space-y-6">
      <div class="border-b pb-4">
        <div class="flex items-center gap-2 mb-1">
          <span class="font-bold text-gray-900">Maria S.</span>
          <span class="text-yellow-400 text-sm">★★★★★</span>
        </div>
        <div class="text-gray-700 mb-1">Produto de ótima qualidade, chegou antes do prazo!</div>
        <div class="text-xs text-gray-400">há 2 dias</div>
      </div>
      <div class="border-b pb-4">
        <div class="flex items-center gap-2 mb-1">
          <span class="font-bold text-gray-900">João P.</span>
          <span class="text-yellow-400 text-sm">★★★★☆</span>
        </div>
        <div class="text-gray-700 mb-1">Gostei bastante, mas achei o tamanho um pouco pequeno.</div>
        <div class="text-xs text-gray-400">há 5 dias</div>
      </div>
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="font-bold text-gray-900">Ana L.</span>
          <span class="text-yellow-400 text-sm">★★★★★</span>
        </div>
        <div class="text-gray-700 mb-1">Super confortável e bonito. Recomendo!</div>
        <div class="text-xs text-gray-400">há 1 semana</div>
      </div>
    </div>
  </div>
@endsection
