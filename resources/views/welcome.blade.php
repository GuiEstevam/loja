@extends('layouts.app')

@section('title', 'Bem-vindo')

@section('content')
  <!-- Banner -->
  <section class="relative bg-gray-900 h-[400px] flex items-center justify-center">
    <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1200&q=80" alt="Banner"
      class="absolute inset-0 w-full h-full object-cover opacity-60">
    <div class="relative z-10 text-center">
      <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 drop-shadow-lg">Encontre Seu Estilo</h1>
      <p class="text-xl text-gray-100 mb-8">Produtos exclusivos, entrega rápida e pontos de fidelidade.</p>
      <a href="{{ route('products.index') }}"
        class="inline-block bg-blue-600 text-white px-8 py-3 rounded-full text-lg font-bold shadow hover:bg-blue-700 transition">
        Ver Produtos
      </a>
    </div>
  </section>

  <!-- Produtos em destaque (exemplo estático) -->
  <section class="max-w-7xl mx-auto py-16 px-6">
    <h2 class="text-3xl font-bold mb-8 text-center">Destaques</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
      <!-- Produto 1 -->
      <div class="bg-white rounded-lg shadow hover:shadow-xl transition p-6 flex flex-col items-center">
        <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=400&q=80"
          alt="Tênis" class="w-48 h-48 object-cover mb-4 rounded">
        <h3 class="font-semibold text-lg mb-2">Tênis Esportivo</h3>
        <p class="text-gray-500 mb-4">Conforto e estilo para o seu dia a dia.</p>
        <span class="font-bold text-blue-600 text-xl mb-4">R$ 299,90</span>
        <a href="{{ route('products.index') }}"
          class="bg-black text-white px-6 py-2 rounded hover:bg-blue-700 transition">Comprar</a>
      </div>
      <!-- Produto 2 -->
      <div class="bg-white rounded-lg shadow hover:shadow-xl transition p-6 flex flex-col items-center">
        <img src="https://images.unsplash.com/photo-1526178613658-3f1622045557?auto=format&fit=crop&w=400&q=80"
          alt="Mochila" class="w-48 h-48 object-cover mb-4 rounded">
        <h3 class="font-semibold text-lg mb-2">Mochila Moderna</h3>
        <p class="text-gray-500 mb-4">Praticidade e resistência para sua rotina.</p>
        <span class="font-bold text-blue-600 text-xl mb-4">R$ 179,90</span>
        <a href="{{ route('products.index') }}"
          class="bg-black text-white px-6 py-2 rounded hover:bg-blue-700 transition">Comprar</a>
      </div>
      <!-- Produto 3 -->
      <div class="bg-white rounded-lg shadow hover:shadow-xl transition p-6 flex flex-col items-center">
        <img src="https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=400&q=80"
          alt="Camiseta" class="w-48 h-48 object-cover mb-4 rounded">
        <h3 class="font-semibold text-lg mb-2">Camiseta Casual</h3>
        <p class="text-gray-500 mb-4">Ideal para todas as ocasiões.</p>
        <span class="font-bold text-blue-600 text-xl mb-4">R$ 89,90</span>
        <a href="{{ route('products.index') }}"
          class="bg-black text-white px-6 py-2 rounded hover:bg-blue-700 transition">Comprar</a>
      </div>
    </div>
  </section>
@endsection
