@extends('layouts.app')

@section('title', 'Painel Administrativo')

@section('content')
  <div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-8">Painel Administrativo</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">

      <!-- Card: Produtos -->
      <a href="{{ route('admin.products.index') }}"
        class="bg-blue-600 text-white rounded-lg p-6 flex flex-col items-center shadow hover:bg-blue-700 transition">
        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M3 7h18M3 12h18M3 17h18" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span class="font-semibold text-lg">Produtos</span>
      </a>

      <!-- Card: Pedidos -->
      <a href="{{ route('admin.orders.index') }}"
        class="bg-blue-100 text-blue-700 rounded-lg p-6 flex flex-col items-center shadow hover:bg-blue-200 transition">
        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M9 17v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6" />
          <path d="M5 17h14" />
        </svg>
        <span class="font-semibold text-lg">Pedidos</span>
      </a>

      <!-- Card: Clientes (placeholder) -->
      <div class="bg-gray-100 text-gray-400 rounded-lg p-6 flex flex-col items-center shadow">
        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M16 21v-2a4 4 0 0 0-3-3.87" />
          <path d="M12 7a4 4 0 0 1 4 4v1" />
        </svg>
        <span class="font-semibold text-lg">Clientes <span class="text-xs">(em breve)</span></span>
      </div>

      <!-- Card: Relatórios (placeholder) -->
      <div class="bg-gray-100 text-gray-400 rounded-lg p-6 flex flex-col items-center shadow">
        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M3 3v18h18" />
          <path d="M9 17v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6" />
        </svg>
        <span class="font-semibold text-lg">Relatórios <span class="text-xs">(em breve)</span></span>
      </div>

      <!-- Card: Cadastrar Produto -->
      <a href="{{ route('admin.products.create') }}"
        class="bg-green-600 text-white rounded-lg p-6 flex flex-col items-center shadow hover:bg-green-700 transition">
        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span class="font-semibold text-lg">Cadastrar Produto</span>
      </a>
    </div>
  </div>
@endsection
