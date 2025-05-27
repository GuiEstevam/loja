@extends('layouts.app')

@section('title', 'Meus Pedidos')

@section('content')
  <div class="w-full py-8 px-2 md:px-8">
    <h1 class="text-2xl md:text-4xl font-extrabold mb-8">Meus Pedidos</h1>
    @if ($orders->isEmpty())
      <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500 text-xl">
        Você ainda não fez nenhum pedido.
      </div>
    @else
      <div class="flex flex-col gap-8">
        @foreach ($orders as $order)
          <div class="bg-white rounded-xl shadow p-4 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
              <div>
                <div class="font-bold text-xl md:text-2xl text-blue-700">Pedido #{{ $order->id }}</div>
                <div class="text-gray-500 text-base md:text-lg">{{ $order->created_at->format('d/m/Y H:i') }}</div>
              </div>
              <div class="mt-2 md:mt-0">
                <span
                  class="inline-block px-3 py-1 rounded-full text-sm md:text-base font-semibold
                            @if ($order->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($order->status === 'paid') bg-green-100 text-green-700
                            @elseif($order->status === 'shipped') bg-blue-100 text-blue-700
                            @elseif($order->status === 'delivered') bg-gray-200 text-gray-700
                            @elseif($order->status === 'canceled') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                  {{ order_status_label($order->status) }}
                </span>
                <a href="{{ route('shop.orders.show', $order) }}"
                  class="ml-4 text-blue-700 hover:underline text-sm md:text-base font-bold transition">
                  Ver detalhes
                </a>
              </div>
            </div>
            <div class="mt-6">
              <!-- Tabela no desktop, lista no mobile -->
              <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-lg">
                  <thead>
                    <tr>
                      <th class="py-3 text-left font-semibold">Produto</th>
                      <th class="py-3 text-center font-semibold">Qtd</th>
                      <th class="py-3 text-center font-semibold">Cor/Tam/Marca</th>
                      <th class="py-3 text-right font-semibold">Preço</th>
                      <th class="py-3 text-right font-semibold">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($order->items as $item)
                      <tr>
                        <td class="py-3 flex items-center gap-3 min-w-[200px]">
                          @if ($item->product && $item->product->image)
                            <img src="{{ asset('products/' . $item->product->image) }}"
                              class="w-16 h-16 object-cover rounded border">
                          @else
                            <span class="text-gray-400">Sem imagem</span>
                          @endif
                          <span class="font-bold text-lg">{{ $item->product->name ?? $item->name }}</span>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center">
                          @if ($item->product)
                            <div class="flex flex-col gap-1">
                              @if ($item->product->colors && $item->product->colors->count())
                                <span class="inline-block text-base text-gray-500">
                                  Cor: {{ $item->product->colors->pluck('name')->join(', ') }}
                                </span>
                              @endif
                              @if ($item->product->sizes && $item->product->sizes->count())
                                <span class="inline-block text-base text-gray-500">
                                  Tam: {{ $item->product->sizes->pluck('name')->join(', ') }}
                                </span>
                              @endif
                              @if ($item->product->brand)
                                <span class="inline-block text-base text-gray-500">
                                  Marca: {{ $item->product->brand->name }}
                                </span>
                              @endif
                            </div>
                          @else
                            <span class="text-base text-gray-400">—</span>
                          @endif
                        </td>
                        <td class="text-right font-semibold">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                        <td class="text-right font-semibold">R$
                          {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- Lista mobile -->
              <div class="md:hidden flex flex-col gap-4">
                @foreach ($order->items as $item)
                  <div class="flex gap-3 items-center bg-gray-50 rounded-lg p-3">
                    @if ($item->product && $item->product->image)
                      <img src="{{ asset('products/' . $item->product->image) }}"
                        class="w-16 h-16 object-cover rounded border">
                    @else
                      <span class="text-gray-400">Sem imagem</span>
                    @endif
                    <div class="flex-1">
                      <div class="font-bold text-base">{{ $item->product->name ?? $item->name }}</div>
                      <div class="text-gray-500 text-sm">
                        Qtd: <span class="font-semibold">{{ $item->quantity }}</span>
                      </div>
                      <div class="text-gray-500 text-sm">
                        @if ($item->product && $item->product->colors && $item->product->colors->count())
                          Cor: {{ $item->product->colors->pluck('name')->join(', ') }}<br>
                        @endif
                        @if ($item->product && $item->product->sizes && $item->product->sizes->count())
                          Tam: {{ $item->product->sizes->pluck('name')->join(', ') }}<br>
                        @endif
                        @if ($item->product && $item->product->brand)
                          Marca: {{ $item->product->brand->name }}
                        @endif
                      </div>
                      <div class="text-blue-700 font-bold mt-1">
                        R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="text-right font-extrabold text-2xl text-blue-700 mt-6">
                Total do pedido: R$ {{ number_format($order->total, 2, ',', '.') }}
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <div class="mt-12">
        {{ $orders->links() }}
      </div>
    @endif
  </div>
@endsection
