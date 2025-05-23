@extends('layouts.app')

@section('title', 'Finalizar Compra')

@section('content')
  <div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-8">Finalizar Compra</h1>
    <div class="flex flex-col md:flex-row gap-8">

      {{-- Bloco 1: Resumo do carrinho --}}
      <div class="w-full md:w-1/2">
        <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm p-6 h-full">
          <h2 class="text-lg font-semibold mb-4">Resumo do Pedido</h2>
          @if (empty($cart))
            <div class="mb-4 text-red-700 bg-red-100 border border-red-300 px-4 py-3 rounded">
              Seu carrinho está vazio.
            </div>
          @else
            <table class="w-full mb-4">
              <thead>
                <tr>
                  <th class="text-left">Produto</th>
                  <th>Qtd</th>
                  <th>Preço</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @php $total = 0; @endphp
                @foreach ($cart as $item)
                  @php
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                  @endphp
                  <tr>
                    <td class="py-2 flex items-center gap-2">
                      <img src="{{ asset('storage/' . $item['image']) }}" class="w-20 h-20 object-cover rounded border">
                      <span class="font-semibold">{{ $item['name'] }}</span>
                    </td>
                    <td class="text-center">{{ $item['quantity'] }}</td>
                    <td>R$ {{ number_format($item['price'], 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="text-right font-bold text-lg mb-6 border-t pt-4">
              Total: R$ {{ number_format($total, 2, ',', '.') }}
            </div>
            <a href="{{ route('shop.cart.index') }}" class="text-blue-600 hover:underline">&larr; Voltar ao carrinho</a>
          @endif
        </div>
      </div>

      {{-- Divisor vertical para telas médias+ --}}
      <div class="hidden md:flex items-stretch">
        <div class="w-px bg-gray-200 mx-2"></div>
      </div>

      {{-- Bloco 2: Formulário de checkout --}}
      <div class="w-full md:w-1/2">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 h-full">
          <form action="{{ route('shop.checkout.process') }}" method="POST">
            @csrf
            <h2 class="text-lg font-semibold mb-4">Dados para Entrega</h2>
            <div class="mb-4">
              <label for="name" class="block mb-1 font-semibold">Nome Completo</label>
              <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" required
                value="{{ old('name', auth()->user()->name ?? '') }}">
            </div>
            <div class="mb-4">
              <label for="email" class="block mb-1 font-semibold">E-mail</label>
              <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" required
                value="{{ old('email', auth()->user()->email ?? '') }}">
            </div>
            <div class="mb-4">
              <label for="phone" class="block mb-1 font-semibold">Telefone</label>
              <input type="text" name="phone" id="phone" class="w-full border rounded px-3 py-2"
                value="{{ old('phone') }}">
            </div>
            <div class="mb-4">
              <label for="address" class="block mb-1 font-semibold">Endereço de Entrega</label>
              <input type="text" name="address" id="address" class="w-full border rounded px-3 py-2" required
                value="{{ old('address') }}">
            </div>
            <div class="mb-4">
              <label for="payment" class="block mb-1 font-semibold">Forma de Pagamento</label>
              <select name="payment" id="payment" class="w-full border rounded px-3 py-2">
                <option value="pix">Pix</option>
                <option value="boleto">Boleto</option>
                <option value="cartao">Cartão de Crédito</option>
              </select>
            </div>
            <div class="mb-4">
              <label for="notes" class="block mb-1 font-semibold">Observações (opcional)</label>
              <textarea name="notes" id="notes" class="w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold w-full">
              Confirmar Pedido
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
@endsection
