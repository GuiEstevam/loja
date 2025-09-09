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
        <nav class="dashboard-breadcrumb">
          <a href="{{ route('home') }}">
            <ion-icon name="home-outline"></ion-icon>
            Início
          </a>
          <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
          <span>Meus Pedidos</span>
        </nav>
        
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
                <div class="flex items-center gap-3 flex-wrap">
                  <!-- Status do Pedido -->
                  <span class="order-status order-status-{{ $order->status }}">
                    <ion-icon name="{{ $order->status === 'delivered' ? 'checkmark-circle' : ($order->status === 'paid' ? 'card' : ($order->status === 'cancelled' ? 'close-circle' : 'time-outline')) }}"></ion-icon>
                    {{ translateOrderStatus($order->status) }}
                  </span>
                  
                  <!-- Status de Pagamento -->
                  @if($order->payment?->status)
                    <span class="payment-indicator payment-{{ $order->payment->status }}">
                      <ion-icon name="{{ $order->payment->status === 'approved' ? 'card' : 'card-outline' }}"></ion-icon>
                      {{ $order->payment->status === 'approved' ? 'Pago' : 'Pagamento Pendente' }}
                    </span>
                  @endif
                  
                  <!-- Botão Ver Detalhes -->
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
                        
                        <!-- Link para Avaliar -->
                        @if($order->status === 'delivered' && $order->payment?->status === 'approved')
                          @php
                            $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                                ->where('product_id', $item->product_id)
                                ->exists();
                          @endphp
                          
                          @if(!$hasReviewed)
                            <a href="{{ route('shop.products.show', $item->product_id) }}#reviews" 
                               class="review-link">
                              <ion-icon name="star-outline"></ion-icon>
                              Avaliar Produto
                            </a>
                          @else
                            <span class="reviewed-badge">
                              <ion-icon name="checkmark-circle"></ion-icon>
                              Já Avaliado
                            </span>
                          @endif
                        @endif
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

@push('styles')
<style>
/* Breadcrumb do Dashboard */
.dashboard-breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 16px;
  font-size: 0.875rem;
  color: #6b7280;
}

.dashboard-breadcrumb a {
  display: flex;
  align-items: center;
  gap: 4px;
  color: #6b7280;
  text-decoration: none;
  transition: color 0.2s ease;
}

.dashboard-breadcrumb a:hover {
  color: #3b82f6;
}

.dashboard-breadcrumb .separator {
  font-size: 12px;
  color: #9ca3af;
}

.dashboard-breadcrumb span {
  color: #374151;
  font-weight: 500;
}

/* Layout em linha para status e botões */
.flex-wrap {
  flex-wrap: wrap;
}

/* Status do Pedido */
.order-status {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.order-status-pending {
  background: #fef3c7;
  color: #d97706;
  border: 1px solid #fde68a;
}

.order-status-paid {
  background: #d1fae5;
  color: #059669;
  border: 1px solid #a7f3d0;
}

.order-status-processing {
  background: #dbeafe;
  color: #2563eb;
  border: 1px solid #bfdbfe;
}

.order-status-shipped {
  background: #e0e7ff;
  color: #7c3aed;
  border: 1px solid #c4b5fd;
}

.order-status-delivered {
  background: #d1fae5;
  color: #059669;
  border: 1px solid #a7f3d0;
}

.order-status-cancelled {
  background: #fee2e2;
  color: #dc2626;
  border: 1px solid #fecaca;
}

.order-status ion-icon {
  font-size: 16px;
}

/* Indicador de Pagamento */
.payment-indicator {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 4px 10px;
  border-radius: 16px;
  font-size: 0.7rem;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.payment-approved {
  background: #d1fae5;
  color: #059669;
  border: 1px solid #a7f3d0;
}

.payment-pending {
  background: #fef3c7;
  color: #d97706;
  border: 1px solid #fde68a;
}

.payment-failed {
  background: #fee2e2;
  color: #dc2626;
  border: 1px solid #fecaca;
}

.payment-indicator ion-icon {
  font-size: 12px;
}

/* Links de Avaliação */
.review-link {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin-top: 8px;
  padding: 6px 12px;
  background: #3b82f6;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 500;
  transition: all 0.2s ease;
}

.review-link:hover {
  background: #2563eb;
  transform: translateY(-1px);
}

.review-link ion-icon {
  font-size: 12px;
}

/* Badge de Já Avaliado */
.reviewed-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin-top: 8px;
  padding: 6px 12px;
  background: #d1fae5;
  color: #059669;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 500;
}

.reviewed-badge ion-icon {
  font-size: 12px;
}

/* Responsividade */
@media (max-width: 768px) {
  .dashboard-breadcrumb {
    font-size: 0.8rem;
    margin-bottom: 12px;
  }
  
  .order-status {
    font-size: 0.7rem;
    padding: 4px 8px;
  }
  
  .order-status ion-icon {
    font-size: 14px;
  }
  
  .payment-indicator {
    font-size: 0.65rem;
    padding: 3px 8px;
  }
  
  .payment-indicator ion-icon {
    font-size: 10px;
  }
  
  .review-link,
  .reviewed-badge {
    font-size: 0.7rem;
    padding: 4px 8px;
  }
}
</style>
@endpush
