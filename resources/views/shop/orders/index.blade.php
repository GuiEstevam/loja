@extends('layouts.app')

@section('title', 'Meus Pedidos')

@push('styles')
  @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
  <div class="dashboard-page">
    <div class="dashboard-container">
      <!-- Header da Página -->
      <div class="dashboard-header">
        <h1 class="dashboard-title">
          <ion-icon name="bag-outline"></ion-icon>
          Meus Pedidos
        </h1>
        <p class="dashboard-subtitle">Acompanhe todos os seus pedidos e status de entrega</p>
      </div>

      <!-- Lista de Pedidos -->
      <div class="dashboard-sections">
        @if ($orders->isEmpty())
          <div class="dashboard-section">
            <div class="section-content">
              <div class="empty-state">
                <ion-icon name="bag-outline"></ion-icon>
                <h3>Nenhum pedido ainda</h3>
                <p>Você ainda não fez nenhum pedido. Que tal começar a comprar?</p>
                <a href="{{ route('shop.products.index') }}" class="section-action">
                  <ion-icon name="bag-outline"></ion-icon>
                  Ver Produtos
                </a>
              </div>
            </div>
          </div>
        @else
          @foreach ($orders as $order)
            <div class="dashboard-section">
              <div class="section-header">
                <h2 class="section-title">
                  <ion-icon name="receipt-outline"></ion-icon>
                  Pedido #{{ $order->id }}
                </h2>
                <div class="flex items-center gap-3">
                  <span class="item-status {{ $order->status }}">
                    @switch($order->status)
                      @case('pending')
                        Pendente
                      @break

                      @case('processing')
                        Processando
                      @break

                      @case('shipped')
                        Enviado
                      @break

                      @case('delivered')
                        Entregue
                      @break

                      @case('cancelled')
                        Cancelado
                      @break

                      @default
                        {{ ucfirst($order->status) }}
                    @endswitch
                  </span>
                  <a href="{{ route('shop.orders.show', $order) }}" class="section-action">
                    <ion-icon name="eye-outline"></ion-icon>
                    Ver Detalhes
                  </a>
                </div>
              </div>
              <div class="section-content">
                <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                    <div>
                      <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                      </p>
                      <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Itens:</strong> {{ $order->items->count() }} produto(s)
                      </p>
                      <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Método de Pagamento:</strong> {{ translatePaymentMethod($order->payment_method) }}
                      </p>
                    </div>
                    <div class="text-right">
                      <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                        Total: €{{ number_format($order->total, 2, ',', '.') }}
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Itens do Pedido -->
                <div class="items-list">
                  @foreach ($order->items as $item)
                    <div class="item-card">
                      <div class="item-image">
                        @if ($item->image)
                          <img src="{{ $item->image }}" alt="{{ $item->name }}"
                            onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<ion-icon name=\'image-outline\' style=\'font-size: 1.5rem; color: var(--dashboard-text-secondary);\'></ion-icon>';">
                        @else
                          <ion-icon name="image-outline"></ion-icon>
                        @endif
                      </div>
                      <div class="item-details">
                        <h3 class="item-title">{{ $item->name }}</h3>
                        <p class="item-meta">
                          Quantidade: <strong>{{ $item->quantity }}</strong> •
                          Preço: <strong>€{{ number_format($item->price, 2, ',', '.') }}</strong>
                          @if ($item->color_name)
                            • Cor: <strong>{{ $item->color_name }}</strong>
                          @endif
                          @if ($item->size)
                            • Tamanho: <strong>{{ $item->size }}</strong>
                          @endif
                        </p>
                      </div>
                      <div class="text-right">
                        <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                          €{{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                        </p>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          @endforeach

          <!-- Paginação -->
          @if ($orders->hasPages())
            <div class="dashboard-section">
              <div class="section-content">
                <div class="flex justify-center">
                  {{ $orders->links() }}
                </div>
              </div>
            </div>
          @endif
        @endif
      </div>
    </div>
  </div>
@endsection
