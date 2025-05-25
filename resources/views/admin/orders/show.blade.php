@extends('layouts.app')

@section('title', 'Detalhes do Pedido')

@section('content')
  <div class="max-w-6xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-8 text-center">Pedido #{{ $order->id }}</h1>
    <div class="flex flex-col md:flex-row gap-8">
      {{-- Dados do Cliente e Pedido --}}
      <div class="w-full md:w-1/3 bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Dados do Cliente</h2>
        <div class="mb-2"><b>Nome:</b> {{ $order->name ?? '-' }}</div>
        <div class="mb-2"><b>E-mail:</b> {{ $order->email ?? '-' }}</div>
        <div class="mb-2"><b>Telefone:</b> {{ $order->phone ?? '-' }}</div>
        <div class="mb-2"><b>Endereço:</b> {{ $order->address ?? '-' }}</div>
        <div class="mb-2"><b>País:</b> {{ $order->country ?? '-' }}</div>
        <div class="mb-2"><b>Estado:</b> {{ $order->state ?? '-' }}</div>
        <div class="mb-2"><b>Cidade:</b> {{ $order->city ?? '-' }}</div>
        <div class="mb-2"><b>Data:</b> {{ $order->created_at->format('d/m/Y H:i') }}</div>
        <div class="mb-2"><b>Observações:</b> {{ $order->notes ?? '-' }}</div>
        <div class="mt-4 flex items-center gap-4">
          <b>Status:</b>
          <span
            class="inline-block px-4 py-1 rounded text-white text-base font-medium whitespace-nowrap
                    @if ($order->status === 'pending') bg-yellow-500
                    @elseif($order->status === 'paid') bg-green-600
                    @elseif($order->status === 'shipped') bg-blue-600
                    @elseif($order->status === 'delivered') bg-emerald-600
                    @elseif($order->status === 'canceled') bg-red-600
                    @else bg-gray-500 @endif">
            {{ order_status_label($order->status) }}
          </span>
        </div>
        <form action="{{ route('admin.orders.update', $order) }}" method="POST"
          class="flex items-center gap-4 mt-6 flex-wrap">
          @csrf
          @method('PUT')
          <select name="status"
            class="border rounded text-base px-4 py-2 pr-8 min-w-[180px] w-auto focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <option value="pending" @selected($order->status === 'pending')>Aguardando</option>
            <option value="paid" @selected($order->status === 'paid')>Pago</option>
            <option value="shipped" @selected($order->status === 'shipped')>Enviado</option>
            <option value="delivered" @selected($order->status === 'delivered')>Entregue</option>
            <option value="canceled" @selected($order->status === 'canceled')>Cancelado</option>
          </select>
          <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 text-base transition">
            Atualizar
          </button>
        </form>
      </div>

      {{-- Itens do Pedido --}}
      <div class="w-full md:w-2/3 bg-gray-50 shadow-lg rounded-xl p-8">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Itens do Pedido</h2>
        <ul>
          @foreach ($order->items as $item)
            <li class="flex items-center gap-4 mb-4">
              <img src="{{ asset('storage/' . ($item->product->image ?? '')) }}"
                class="w-32 h-32 object-cover rounded border shadow" alt="{{ $item->product->name ?? $item->name }}">
              <div class="flex-1">
                <div class="font-semibold text-base">{{ $item->product->name ?? $item->name }}</div>
                <div class="text-gray-600 text-sm">Qtd: <b>{{ $item->quantity }}</b></div>
                <div class="text-gray-600 text-sm">Preço: R$ {{ number_format($item->price, 2, ',', '.') }}</div>
                <div class="text-gray-800 font-bold mt-1">Subtotal: R$
                  {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</div>
              </div>
            </li>
          @endforeach
        </ul>
        <div class="text-right font-bold text-xl mt-6">
          Total: R$ {{ number_format($order->total, 2, ',', '.') }}
        </div>
      </div>
    </div>
    <div class="mt-6">
      <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline">&larr; Voltar para pedidos</a>
    </div>
  </div>
@endsection
