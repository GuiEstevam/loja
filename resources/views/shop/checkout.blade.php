@extends('layouts.app')

@section('title', 'Finalizar Compra')

@section('content')
  <div class="max-w-6xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-8 text-center">Finalizar Compra</h1>
    <div class="flex flex-col md:flex-row gap-8">
      {{-- Resumo do Pedido --}}
      <div class="w-full md:w-1/3 bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between">
        <h2 class="text-lg font-semibold mb-2 border-b pb-2">Resumo do Pedido</h2>
        <ul class="mt-2">
          @php $total = 0; @endphp
          @foreach ($cart as $item)
            @php
              $subtotal = $item['price'] * $item['quantity'];
              $total += $subtotal;
            @endphp
            <li class="flex items-center gap-4 mb-2">
              <img src="{{ asset('storage/' . $item['image']) }}" class="w-32 h-32 object-cover rounded border shadow"
                alt="{{ $item['name'] }}">
              <div>
                <div class="font-semibold text-base">{{ $item['name'] }}</div>
                <div class="text-gray-600 text-sm">Qtd: <b>{{ $item['quantity'] }}</b></div>
                <div class="text-gray-600 text-sm">Preço: R$ {{ number_format($item['price'], 2, ',', '.') }}</div>
                <div class="text-gray-800 font-bold mt-1">Subtotal: R$ {{ number_format($subtotal, 2, ',', '.') }}</div>
              </div>
            </li>
          @endforeach
        </ul>
        <div class="text-right font-bold text-xl mt-6">
          Total: R$ {{ number_format($total, 2, ',', '.') }}
        </div>
        <div class="mt-6">
          <a href="{{ route('shop.cart.index') }}" class="text-blue-600 hover:underline">&larr; Voltar ao carrinho</a>
        </div>
      </div>

      {{-- Dados para Entrega --}}
      <div class="w-full md:w-2/3 bg-gray-50 shadow-lg rounded-xl p-8">
        @if ($errors->any())
          <div class="mb-4 text-red-700 bg-red-100 border border-red-300 px-4 py-3 rounded">
            <ul class="list-disc ml-5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('shop.checkout.process') }}" method="POST">
          @csrf
          <h2 class="text-lg font-semibold mb-4 border-b pb-2">Dados para Entrega</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="name" class="block mb-1 font-semibold">Nome Completo</label>
              <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" required
                value="{{ old('name', auth()->user()->name ?? '') }}">
            </div>
            <div>
              <label for="email" class="block mb-1 font-semibold">E-mail</label>
              <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" required
                value="{{ old('email', auth()->user()->email ?? '') }}">
            </div>
            <div>
              <label for="phone" class="block mb-1 font-semibold">Telefone</label>
              <input type="text" name="phone" id="phone" class="w-full border rounded px-3 py-2"
                value="{{ old('phone', auth()->user()->phone ?? '') }}">
            </div>
            <div>
              <label for="country" class="block mb-1 font-semibold">País</label>
              <select name="country" id="country" class="w-full border rounded px-3 py-2" required
                onchange="toggleStateField(this.value)">
                <option value="BR" {{ old('country') == 'BR' ? 'selected' : '' }}>Brasil</option>
                <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>Estados Unidos</option>
                <option value="PT" {{ old('country') == 'PT' ? 'selected' : '' }}>Portugal</option>
                <option value="AR" {{ old('country') == 'AR' ? 'selected' : '' }}>Argentina</option>
                <option value="other" {{ old('country') == 'other' ? 'selected' : '' }}>Outro</option>
              </select>
            </div>
            <div>
              <label for="zip" class="block mb-1 font-semibold">CEP/Código Postal</label>
              <input type="text" name="zip" id="zip" class="w-full border rounded px-3 py-2" required
                value="{{ old('zip') }}">
            </div>
            <div>
              <label for="street" class="block mb-1 font-semibold">Rua</label>
              <input type="text" name="street" id="street" class="w-full border rounded px-3 py-2" required
                value="{{ old('street') }}">
            </div>
            <div>
              <label for="neighborhood" class="block mb-1 font-semibold">Bairro</label>
              <input type="text" name="neighborhood" id="neighborhood" class="w-full border rounded px-3 py-2" required
                value="{{ old('neighborhood') }}">
            </div>
            <div>
              <label for="city" class="block mb-1 font-semibold">Cidade</label>
              <input type="text" name="city" id="city" class="w-full border rounded px-3 py-2" required
                value="{{ old('city') }}">
            </div>
            <div>
              <label for="number" class="block mb-1 font-semibold">Número</label>
              <input type="text" name="number" id="number" class="w-full border rounded px-3 py-2" required
                value="{{ old('number') }}">
            </div>
            <div>
              <label for="complement" class="block mb-1 font-semibold">Complemento</label>
              <input type="text" name="complement" id="complement" class="w-full border rounded px-3 py-2"
                value="{{ old('complement') }}">
            </div>
            {{-- Campo estado dinâmico --}}
            <div id="state-select-br" style="display: none;">
              <label for="state" class="block mb-1 font-semibold">Estado</label>
              <select name="state" id="state" class="w-full border rounded px-3 py-2">
                <option value="">Selecione</option>
                <option value="AC">AC</option>
                <option value="AL">AL</option>
                <option value="AP">AP</option>
                <option value="AM">AM</option>
                <option value="BA">BA</option>
                <option value="CE">CE</option>
                <option value="DF">DF</option>
                <option value="ES">ES</option>
                <option value="GO">GO</option>
                <option value="MA">MA</option>
                <option value="MT">MT</option>
                <option value="MS">MS</option>
                <option value="MG">MG</option>
                <option value="PA">PA</option>
                <option value="PB">PB</option>
                <option value="PR">PR</option>
                <option value="PE">PE</option>
                <option value="PI">PI</option>
                <option value="RJ">RJ</option>
                <option value="RN">RN</option>
                <option value="RS">RS</option>
                <option value="RO">RO</option>
                <option value="RR">RR</option>
                <option value="SC">SC</option>
                <option value="SP">SP</option>
                <option value="SE">SE</option>
                <option value="TO">TO</option>
              </select>
            </div>
            <div id="state-text-other" style="display: none;">
              <label for="state_other" class="block mb-1 font-semibold">Estado/Província</label>
              <input type="text" name="state_other" id="state_other" class="w-full border rounded px-3 py-2">
            </div>
          </div>
          <div class="my-4 flex items-center gap-2">
            <input type="checkbox" name="save_address" id="save_address" value="1" class="rounded">
            <label for="save_address" class="font-medium">Salvar este endereço na minha conta</label>
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
          <button type="submit"
            class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold w-full mt-4">
            Confirmar Pedido
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    function toggleStateField(country) {
      document.getElementById('state-select-br').style.display = (country === 'BR') ? 'block' : 'none';
      document.getElementById('state-text-other').style.display = (country !== 'BR') ? 'block' : 'none';
    }
    document.addEventListener('DOMContentLoaded', function() {
      toggleStateField(document.getElementById('country').value);
      document.getElementById('country').addEventListener('change', function() {
        toggleStateField(this.value);
      });
    });
  </script>
@endsection
