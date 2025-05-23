@extends('layouts.app')

@section('title', 'Minha Conta')

@section('content')
  <div class="max-w-3xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-8">Minha Conta</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
      <a href="{{ route('shop.orders.index') }}"
        class="bg-blue-100 text-blue-700 rounded-lg p-6 flex flex-col items-center shadow hover:bg-blue-200 transition">
        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M3 7h18M3 12h18M3 17h18"></path>
        </svg>
        <span class="font-semibold">Meus Pedidos</span>
      </a>
      <a href="{{ route('enderecos.index') }}"
        class="bg-blue-100 text-blue-700 rounded-lg p-6 flex flex-col items-center shadow hover:bg-blue-200 transition">
        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20z"></path>
        </svg>
        <span class="font-semibold">Meus Endereços</span>
      </a>
      <!-- Adicione outros cards conforme necessário -->
    </div>
  </div>
@endsection
