@extends('layouts.app')

@section('title', 'Meus Pedidos')

@section('content')
  <div class="max-w-4xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6">Meus Pedidos</h1>
    @if ($orders->isEmpty())
      <p class="text-gray-500">Você ainda não fez nenhum pedido.</p>
    @else
      @foreach ($orders as $order)
        <div class="mb-8 border-b pb-6">
          <div class="mb-2 font-semibold">Pedido #{{ $order->id }} - {{ $order->created_at->format('d/m/Y H:i') }}</div>
          <div class="mb-2 text-gray-600">Status: <span class="font-semibold">{{ order_status_label($order->status) }}
            </span></div>
          <table class="w-full mb-2">
            <thead>
              <tr>
                <th class="text-left">Produto</th>
                <th>Qtd</th>
                <th>Preço</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order->items as $item)
                <tr>
                  <td class="py-2 flex items-center gap-2">
                    @if ($item->product && $item->product->image)
                      <img src="{{ asset('storage/' . $item->product->image) }}"
                        class="w-12 h-12 object-cover rounded border">
                    @else
                      <span class="text-gray-400">Sem imagem</span>
                    @endif
                    <span>{{ $item->product->name ?? $item->name }}</span>
                  </td>
                  <td class="text-center">{{ $item->quantity }}</td>
                  <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                  <td>R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="text-right font-bold">Total do pedido: R$ {{ number_format($order->total, 2, ',', '.') }}</div>
        </div>
      @endforeach
    @endif
  </div>
@endsection
