@extends('layouts.app')

@section('title', $product->name)

@section('content')
  <div class="max-w-6xl mx-auto py-10 px-4 flex flex-col md:flex-row gap-8">
    <!-- Imagem à esquerda -->
    <div class="w-full md:w-1/2 flex flex-col gap-4">
      <div class="bg-white rounded-lg shadow flex items-center justify-center aspect-square overflow-hidden">
        <img src="{{ asset('storage/' . ($product->image ?? 'placeholder.jpg')) }}" alt="{{ $product->name }}"
          class="w-full h-full object-cover" style="object-position: center;">
      </div>
    </div>

    <!-- Informações do produto à direita -->
    <div class="w-full md:w-1/2 flex flex-col gap-4">
      <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-2">{{ $product->name }}</h1>
      <div class="text-gray-600 mb-2">{{ $product->category ?? 'Categoria' }}</div>
      <div class="flex items-center gap-2 mb-2">
        <span class="text-yellow-400">★ ★ ★ ★ ☆</span>
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
      <div class="mt-4">
        <div class="font-semibold mb-2">Tamanhos disponíveis</div>
        <div class="flex gap-2 flex-wrap">
          @foreach ([38, 39, 40, 41, 42, 43] as $size)
            <button
              class="border rounded px-3 py-1 hover:bg-blue-600 hover:text-white transition">{{ $size }}</button>
          @endforeach
        </div>
      </div>
      @if (session('success'))
        <div class="mt-4 text-green-700 bg-green-100 border border-green-300 px-4 py-3 rounded">
          {{ session('success') }}
        </div>
      @endif
      <div class="mt-6 flex gap-4">
        <!-- Botão de compra direta (opcional, pode futuramente ir para checkout direto) -->
        <form action="#" method="GET">
          <button type="button" class="bg-blue-300 text-white px-6 py-3 rounded font-bold cursor-not-allowed opacity-70">
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
@endsection
