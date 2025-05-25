@extends('layouts.app')

@section('title', 'Pedidos')

@section('content')
  <div class="max-w-7xl mx-auto py-10 px-4">
    <div class="bg-white shadow rounded-lg p-8">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Pedidos</h1>
      </div>
      <table class="w-full table-auto">
        <thead>
          <tr>
            <th class="py-3 text-left">ID</th>
            <th class="py-3 text-left">Cliente</th>
            <th class="py-3 text-left">Data</th>
            <th class="py-3 text-left">Status</th>
            <th class="py-3 text-left">Produtos</th>
            <th class="py-3 text-right">Total</th>
            <th class="py-3 text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $order)
            <tr class="border-t align-top">
              <td class="py-4 font-semibold">{{ $order->id }}</td>
              <td class="py-4">{{ $order->user->name ?? $order->name }}</td>
              <td class="py-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
              <td class="py-4">
                <span
                  class="px-2 py-1 rounded text-white
                                @if ($order->status === 'pending') bg-yellow-500
                                @elseif($order->status === 'paid') bg-green-600
                                @elseif($order->status === 'shipped') bg-blue-600
                                @elseif($order->status === 'delivered') bg-gray-700
                                @elseif($order->status === 'canceled') bg-red-600
                                @else bg-gray-400 @endif">
                  {{ order_status_label($order->status) }}
                </span>
              </td>
              <td class="py-4">
                <div class="flex flex-col gap-2">
                  @foreach ($order->items as $item)
                    <div class="flex items-center gap-2">
                      <img src="{{ asset('storage/' . ($item->product->image ?? '')) }}"
                        class="w-16 h-16 object-cover rounded border" alt="{{ $item->product->name ?? $item->name }}">
                      <span>{{ $item->product->name ?? $item->name }}</span>
                      <span class="text-gray-500">x{{ $item->quantity }}</span>
                    </div>
                  @endforeach
                </div>
              </td>
              <td class="py-4 text-right font-semibold">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
              <td class="py-4 text-center">
                <a href="{{ route('admin.orders.show', $order) }}"
                  class="text-blue-600 hover:text-blue-800 font-bold underline">Ver detalhes</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-8 text-center text-gray-500">Nenhum pedido encontrado.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
      <div class="mt-6">
        {{ $orders->links() }}
      </div>
    </div>
  </div>
@endsection
