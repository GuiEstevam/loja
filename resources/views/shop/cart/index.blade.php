@extends('layouts.app')

@section('title', 'Carrinho de Compras')

@section('content')
  <div class="max-w-3xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6">Seu Carrinho</h1>

    @if (session('success'))
      <div class="mb-4 text-green-700 bg-green-100 border border-green-300 px-4 py-3 rounded">
        {{ session('success') }}
      </div>
    @endif

    @if (empty($cart))
      <p class="text-gray-500">Seu carrinho está vazio.</p>
    @else
      <table class="w-full mb-6">
        <thead>
          <tr>
            <th class="text-left">Produto</th>
            <th>Qtd</th>
            <th>Preço</th>
            <th>Subtotal</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @php $total = 0; @endphp
          @foreach ($cart as $id => $item)
            @php
              $subtotal = $item['price'] * $item['quantity'];
              $total += $subtotal;
            @endphp
            <tr>
              <td class="py-2 flex items-center gap-2">
                <img src="{{ asset('products/' . $item['image']) }}" class="w-32 h-32 object-cover rounded border">
                <span class="font-semibold">{{ $item['name'] }}</span>
              </td>
              <td>
                <input type="number" value="{{ $item['quantity'] }}" min="1"
                  class="w-16 border rounded px-2 py-1 text-center"
                  onchange="updateCartQuantity({{ $id }}, this.value)">
              </td>
              <td>R$ {{ number_format($item['price'], 2, ',', '.') }}</td>
              <td>R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
              <td>
                <form action="{{ route('shop.cart.remove', $id) }}" method="POST" style="display:inline">
                  @csrf
                  <button type="submit" title="Remover" class="text-red-600 hover:text-red-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m5 0H6" />
                    </svg>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="text-right font-bold text-lg mb-6">
        Total: R$ {{ number_format($total, 2, ',', '.') }}
      </div>
      <a href="{{ route('shop.checkout') }}"
        class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold">
        Finalizar Compra
      </a>
    @endif
  </div>

  <script>
    function updateCartQuantity(productId, quantity) {
      fetch("{{ url('/carrinho/atualizar') }}/" + productId, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify({
            quantity: quantity
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Erro ao atualizar carrinho!');
          }
        });
    }
  </script>
@endsection
