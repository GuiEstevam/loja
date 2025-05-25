@extends('layouts.app')

@section('title', 'Gerenciar Pedidos')

@section('content')
  <div class="max-w-7xl mx-auto py-8 px-2 md:px-0">
    <div class="bg-white shadow rounded-lg p-6 md:p-8">
      <h1 class="text-3xl font-bold mb-2">Gerenciar Pedidos</h1>
      <p class="text-gray-600 mb-6">Visão geral e gestão de todos os pedidos</p>

      <!-- Estatísticas rápidas -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 rounded-lg p-4 flex items-center gap-4">
          <div class="bg-blue-100 rounded-full p-2">
            <ion-icon name="receipt-outline" class="text-blue-600 text-2xl"></ion-icon>
          </div>
          <div>
            <div class="text-xs text-gray-500">Total de Pedidos</div>
            <div class="font-bold text-2xl text-blue-700">{{ $stats['total'] }}</div>
          </div>
        </div>
        <div class="bg-green-50 rounded-lg p-4 flex items-center gap-4">
          <div class="bg-green-100 rounded-full p-2">
            <ion-icon name="cash-outline" class="text-green-600 text-2xl"></ion-icon>
          </div>
          <div>
            <div class="text-xs text-gray-500">Receita Total</div>
            <div class="font-bold text-2xl text-green-700">R$ {{ number_format($stats['revenue'], 2, ',', '.') }}</div>
          </div>
        </div>
        <div class="bg-purple-50 rounded-lg p-4 flex items-center gap-4">
          <div class="bg-purple-100 rounded-full p-2">
            <ion-icon name="pricetag-outline" class="text-purple-600 text-2xl"></ion-icon>
          </div>
          <div>
            <div class="text-xs text-gray-500">Ticket Médio</div>
            <div class="font-bold text-2xl text-purple-700">R$ {{ number_format($stats['average'], 2, ',', '.') }}</div>
          </div>
        </div>
      </div>

      <!-- Filtros -->
      <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-2 md:gap-4 items-end mb-6">
        <div>
          <label class="block text-xs text-gray-500 mb-1">Buscar:</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="ID ou cliente"
            class="border rounded-lg px-3 py-2 text-sm w-full" />
        </div>
        <div>
          <label class="block text-xs text-gray-500 mb-1">Status:</label>
          <select name="status" class="border rounded-lg px-3 py-2 text-sm w-full">
            <option value="Todos">Todos</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Aguardando</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pago</option>
            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Enviado</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregue</option>
            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Cancelado</option>
          </select>
        </div>
        <div>
          <label class="block text-xs text-gray-500 mb-1">De:</label>
          <input type="date" name="date_from" value="{{ request('date_from') }}"
            class="border rounded-lg px-3 py-2 text-sm w-full" />
        </div>
        <div>
          <label class="block text-xs text-gray-500 mb-1">Até:</label>
          <input type="date" name="date_to" value="{{ request('date_to') }}"
            class="border rounded-lg px-3 py-2 text-sm w-full" />
        </div>
        <div class="flex gap-2">
          <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm w-full">Filtrar</button>
          @if (request()->hasAny(['search', 'status', 'date_from', 'date_to']))
            <a href="{{ route('admin.orders.index') }}"
              class="text-sm text-gray-500 underline flex items-center">Limpar</a>
          @endif
        </div>
      </form>

      <!-- Tabela responsiva -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left font-semibold">ID</th>
              <th class="px-4 py-3 text-left font-semibold">Cliente</th>
              <th class="px-4 py-3 text-left font-semibold">Data</th>
              <th class="px-4 py-3 text-left font-semibold">Status</th>
              <th class="px-4 py-3 text-left font-semibold">Produtos</th>
              <th class="px-4 py-3 text-right font-semibold">Total</th>
              <th class="px-4 py-3 text-center font-semibold">Ações</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            @forelse($orders as $order)
              <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-semibold">#{{ $order->id }}</td>
                <td class="px-4 py-3">
                  <div class="flex items-center gap-2">
                    <div
                      class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                      {{ strtoupper(mb_substr($order->user->name ?? $order->name, 0, 2)) }}
                    </div>
                    <div>
                      <div class="font-medium text-gray-900">{{ $order->user->name ?? $order->name }}</div>
                      <div class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3">
                  {{ $order->created_at->format('d/m/Y') }}<br>
                  <span class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</span>
                </td>
                <td class="px-4 py-3 relative">
                  <div class="inline-block">
                    <button type="button"
                      class="status-badge px-2 py-1 rounded text-xs font-medium focus:outline-none transition flex items-center gap-1
                      @if ($order->status === 'pending') bg-yellow-100 text-yellow-700
                      @elseif($order->status === 'paid') bg-green-100 text-green-700
                      @elseif($order->status === 'shipped') bg-blue-100 text-blue-700
                      @elseif($order->status === 'delivered') bg-gray-200 text-gray-700
                      @elseif($order->status === 'canceled') bg-red-100 text-red-700
                      @else bg-gray-100 text-gray-700 @endif"
                      data-order-id="{{ $order->id }}" data-current-status="{{ $order->status }}">
                      {{ [
                          'pending' => 'Aguardando',
                          'paid' => 'Pago',
                          'shipped' => 'Enviado',
                          'delivered' => 'Entregue',
                          'canceled' => 'Cancelado',
                      ][$order->status] ?? ucfirst($order->status) }}
                      <svg class="inline w-3 h-3 ml-1 text-gray-400 pointer-events-none" fill="none"
                        viewBox="0 0 20 20">
                        <path d="M6 8l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" />
                      </svg>
                    </button>
                    <!-- Dropdown -->
                    <div class="hidden absolute z-20 mt-1 w-32 bg-white border rounded shadow status-dropdown">
                      @foreach (['pending' => 'Aguardando', 'paid' => 'Pago', 'shipped' => 'Enviado', 'delivered' => 'Entregue', 'canceled' => 'Cancelado'] as $value => $label)
                        <a href="#" class="block px-4 py-2 text-xs hover:bg-gray-100 status-option"
                          data-status="{{ $value }}">{{ $label }}</a>
                      @endforeach
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3">
                  <div class="flex flex-wrap gap-1">
                    @foreach ($order->items->take(2) as $item)
                      <img src="{{ asset('storage/' . ($item->product->image ?? '')) }}"
                        class="w-8 h-8 object-cover rounded border" alt="{{ $item->product->name ?? $item->name }}"
                        title="{{ $item->product->name ?? $item->name }} (x{{ $item->quantity }})">
                    @endforeach
                    @if ($order->items->count() > 2)
                      <span
                        class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded border text-xs text-gray-500">+{{ $order->items->count() - 2 }}</span>
                    @endif
                  </div>
                </td>
                <td class="px-4 py-3 text-right font-semibold">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                <td class="px-4 py-3 text-center">
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
      </div>
      <div class="mt-6 flex justify-center">
        {{ $orders->links() }}
      </div>
    </div>
  </div>

  <!-- AJAX e Dropdown para atualização de status -->
  <script>
    document.querySelectorAll('.status-badge').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        // Fecha outros dropdowns
        document.querySelectorAll('.status-dropdown').forEach(d => d.classList.add('hidden'));
        // Abre o dropdown deste
        btn.parentElement.querySelector('.status-dropdown').classList.toggle('hidden');
      });
    });

    document.addEventListener('click', function() {
      document.querySelectorAll('.status-dropdown').forEach(d => d.classList.add('hidden'));
    });

    document.querySelectorAll('.status-option').forEach(function(opt) {
      opt.addEventListener('click', function(e) {
        e.preventDefault();
        const newStatus = this.dataset.status;
        const btn = this.closest('td').querySelector('.status-badge');
        const orderId = btn.dataset.orderId;
        const csrfToken = '{{ csrf_token() }}';
        fetch(`/admin/orders/${orderId}`, {
            method: 'PATCH',
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
              btn.innerHTML = data.label +
                '<svg class="inline w-3 h-3 ml-1 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 20 20"><path d="M6 8l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
              btn.className =
                'status-badge px-2 py-1 rounded text-xs font-medium focus:outline-none transition flex items-center gap-1 ' +
                data.class;
              btn.parentElement.querySelector('.status-dropdown').classList.add('hidden');
            } else {
              alert('Erro ao atualizar status.');
            }
          })
          .catch(() => alert('Erro ao atualizar status.'));
      });
    });
  </script>
@endsection
