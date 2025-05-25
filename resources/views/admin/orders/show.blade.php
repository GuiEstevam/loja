@extends('layouts.app')

@section('title', 'Detalhes do Pedido')

@section('content')
  <div class="max-w-4xl mx-auto py-8 px-2 md:px-4">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
      <!-- Cabeçalho -->
      <div class="bg-gray-50 px-6 py-4 border-b">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
          <ion-icon name="receipt-outline" class="text-blue-600 text-xl"></ion-icon>
          Pedido #{{ $order->id }}
        </h1>
        <p class="text-gray-600 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
      </div>

      <div class="flex flex-col md:flex-row gap-6 p-6">
        <!-- Dados do Cliente -->
        <div class="w-full md:w-1/3 space-y-4">
          <div class="bg-gray-50 rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
              <ion-icon name="person-circle-outline" class="text-gray-500"></ion-icon>
              Cliente
            </h2>
            <dl class="space-y-1 text-sm">
              <div>
                <dt class="font-medium">Nome:</dt>
                <dd>{{ $order->user->name ?? $order->name }}</dd>
              </div>
              <div>
                <dt class="font-medium">E-mail:</dt>
                <dd>{{ $order->user->email ?? $order->email }}</dd>
              </div>
              <div>
                <dt class="font-medium">Telefone:</dt>
                <dd>{{ $order->phone ?? '-' }}</dd>
              </div>
              <div>
                <dt class="font-medium">Endereço:</dt>
                <dd>{{ $order->address }}, {{ $order->city }}/{{ $order->state }}</dd>
              </div>
              <div>
                <dt class="font-medium">CEP:</dt>
                <dd>{{ $order->zip ?? '-' }}</dd>
              </div>
            </dl>
          </div>
          <!-- Status e atualização AJAX -->
          <div class="bg-gray-50 rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
              <ion-icon name="sync-outline" class="text-gray-500"></ion-icon>
              Status do Pedido
            </h2>
            <div class="flex items-center gap-3">
              <span id="statusBadge"
                class="px-3 py-1 rounded-full text-sm font-medium transition
              @if ($order->status === 'pending') bg-yellow-100 text-yellow-700
              @elseif($order->status === 'paid') bg-green-100 text-green-700
              @elseif($order->status === 'shipped') bg-blue-100 text-blue-700
              @elseif($order->status === 'delivered') bg-gray-200 text-gray-700
              @elseif($order->status === 'canceled') bg-red-100 text-red-700 @endif">
                {{ order_status_label($order->status) }}
              </span>
              <button id="statusDropdownBtn" type="button"
                class="border border-gray-300 rounded px-2 py-1 bg-white hover:bg-gray-100 flex items-center gap-1 text-sm focus:outline-none">
                <ion-icon name="chevron-down-outline"></ion-icon>
              </button>
              <!-- Dropdown -->
              <div id="statusDropdown" class="hidden absolute z-30 mt-12 w-40 bg-white border rounded shadow">
                @foreach (['pending' => 'Aguardando', 'paid' => 'Pago', 'shipped' => 'Enviado', 'delivered' => 'Entregue', 'canceled' => 'Cancelado'] as $value => $label)
                  <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 status-option"
                    data-status="{{ $value }}">{{ $label }}</a>
                @endforeach
              </div>
            </div>
            @if ($order->tracking_code)
              <div class="mt-3">
                <span class="font-medium">Código de Rastreio:</span>
                <span class="ml-2">{{ $order->tracking_code }}</span>
              </div>
            @endif
          </div>
        </div>

        <!-- Itens e Resumo -->
        <div class="w-full md:w-2/3 space-y-4">
          <div class="bg-gray-50 rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
              <ion-icon name="cart-outline" class="text-gray-500"></ion-icon>
              Itens do Pedido
            </h2>
            <div class="space-y-4">
              @foreach ($order->items as $item)
                <div class="flex flex-col sm:flex-row items-center gap-4 border-b pb-4">
                  <img src="{{ asset('storage/' . ($item->product->image ?? '')) }}"
                    class="w-20 h-20 object-cover rounded-lg border shadow-sm"
                    alt="{{ $item->product->name ?? $item->name }}">
                  <div class="flex-1 w-full">
                    <div class="font-semibold">{{ $item->product->name ?? $item->name }}</div>
                    <div class="text-gray-600 text-sm">Qtd: {{ $item->quantity }} × R$
                      {{ number_format($item->price, 2, ',', '.') }}</div>
                  </div>
                  <div class="font-medium">R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</div>
                </div>
              @endforeach
            </div>
          </div>
          <div class="bg-gray-50 rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
              <ion-icon name="wallet-outline" class="text-gray-500"></ion-icon>
              Resumo Financeiro
            </h2>
            <dl class="space-y-2 text-sm">
              <div class="flex justify-between">
                <dt>Subtotal:</dt>
                <dd>R$ {{ number_format($order->subtotal ?? $order->total, 2, ',', '.') }}</dd>
              </div>
              @if ($order->discount > 0)
                <div class="flex justify-between">
                  <dt>Desconto:</dt>
                  <dd class="text-green-700">- R$ {{ number_format($order->discount, 2, ',', '.') }}</dd>
                </div>
              @endif
              <div class="flex justify-between">
                <dt>Frete:</dt>
                <dd>R$ {{ number_format($order->shipping, 2, ',', '.') }}</dd>
              </div>
              <div class="flex justify-between border-t pt-2 font-semibold text-base">
                <dt>Total:</dt>
                <dd>R$ {{ number_format($order->total, 2, ',', '.') }}</dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
      <div class="px-6 py-4 border-t">
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline flex items-center gap-1">
          <ion-icon name="arrow-back-outline"></ion-icon>
          Voltar para pedidos
        </a>
      </div>
    </div>
  </div>

  <!-- AJAX para atualização de status -->
  <script>
    document.getElementById('statusDropdownBtn').addEventListener('click', function(e) {
      e.stopPropagation();
      document.getElementById('statusDropdown').classList.toggle('hidden');
    });
    document.addEventListener('click', function() {
      document.getElementById('statusDropdown').classList.add('hidden');
    });
    document.querySelectorAll('.status-option').forEach(function(opt) {
      opt.addEventListener('click', function(e) {
        e.preventDefault();
        const newStatus = this.dataset.status;
        const csrfToken = '{{ csrf_token() }}';
        fetch('{{ route('admin.orders.update', $order) }}', {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              status: newStatus
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const badge = document.getElementById('statusBadge');
              badge.textContent = data.label;
              badge.className = 'px-3 py-1 rounded-full text-sm font-medium transition ' + data.class;
              document.getElementById('statusDropdown').classList.add('hidden');
            } else {
              alert('Erro ao atualizar status.');
            }
          })
          .catch(() => alert('Erro ao atualizar status.'));
      });
    });
  </script>
@endsection
