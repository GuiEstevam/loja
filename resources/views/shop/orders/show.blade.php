@extends('layouts.app')

@section('title', 'Detalhes do Pedido #' . $order->id)

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
          <a href="{{ route('shop.orders.index') }}">Meus Pedidos</a>
          <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
          <span>Pedido #{{ $order->id }}</span>
        </nav>
        
        <div class="dashboard-header-content">
          <h1 class="dashboard-title">
            <ion-icon name="receipt-outline"></ion-icon>
            Pedido #{{ $order->id }}
          </h1>
          <p class="dashboard-subtitle">Detalhes completos do seu pedido</p>
        </div>
        
        <div class="order-status-badges">
          <span class="order-status order-status-{{ $order->status }}">
            <ion-icon name="{{ $order->status === 'delivered' ? 'checkmark-circle' : ($order->status === 'paid' ? 'card' : ($order->status === 'cancelled' ? 'close-circle' : 'time-outline')) }}"></ion-icon>
            {{ translateOrderStatus($order->status) }}
          </span>
          
          @if($order->payment?->status)
            <span class="payment-indicator payment-{{ $order->payment->status }}">
              <ion-icon name="{{ $order->payment->status === 'approved' ? 'card' : 'card-outline' }}"></ion-icon>
              {{ $order->payment->status === 'approved' ? 'Pago' : 'Pagamento Pendente' }}
            </span>
          @endif
        </div>
      </div>

      <div class="dashboard-sections">
        <!-- Informações do Pedido -->
        <div class="dashboard-section">
          <div class="section-header">
            <h2 class="section-title">
              <ion-icon name="information-circle-outline"></ion-icon>
              Informações do Pedido
            </h2>
          </div>
          <div class="section-content">
            <div class="order-info-grid">
              <div class="info-item">
                <span class="info-label">Número do Pedido</span>
                <span class="info-value">#{{ $order->id }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Data do Pedido</span>
                <span class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Status do Pedido</span>
                <span class="info-value status-{{ $order->status }}">
                  {{ translateOrderStatus($order->status) }}
                </span>
              </div>
              @if($order->payment?->status)
                <div class="info-item">
                  <span class="info-label">Status do Pagamento</span>
                  <span class="info-value payment-status-{{ $order->payment->status }}">
                    {{ $order->payment->status === 'approved' ? 'Pago' : 'Pagamento Pendente' }}
                  </span>
                </div>
              @endif
              <div class="info-item">
                <span class="info-label">Método de Pagamento</span>
                <span class="info-value">{{ translatePaymentMethod($order->payment_method) }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Subtotal</span>
                <span class="info-value">€{{ number_format($order->subtotal, 2, ',', '.') }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Total</span>
                <span class="info-value total-value">€{{ number_format($order->total, 2, ',', '.') }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Itens do Pedido -->
        <div class="dashboard-section">
          <div class="section-header">
            <h2 class="section-title">
              <ion-icon name="bag-outline"></ion-icon>
              Itens do Pedido
            </h2>
            <span class="items-count">{{ $order->items->count() }} item(s)</span>
          </div>
          <div class="section-content">
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
                      Preço unitário: <strong>€{{ number_format($item->price, 2, ',', '.') }}</strong>
                      @if ($item->color_name)
                        • Cor: <strong>{{ $item->color_name }}</strong>
                      @endif
                      @if ($item->size)
                        • Tamanho: <strong>{{ $item->size }}</strong>
                      @endif
                    </p>
                  </div>
                  <div class="item-actions">
                    <div class="item-price">
                      <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                        €{{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                      </p>
                    </div>
                    
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

        <!-- Seção de Avaliações -->
        @if($order->status === 'delivered' && $order->payment?->status === 'approved')
          <div class="dashboard-section">
            <div class="section-header">
              <h2 class="section-title">
                <ion-icon name="star-outline"></ion-icon>
                Avaliações dos Produtos
              </h2>
              <p class="section-subtitle">Avalie os produtos que você comprou</p>
            </div>
            <div class="section-content">
              <div class="reviews-summary">
                @php
                  $totalItems = $order->items->count();
                  $reviewedItems = 0;
                  foreach ($order->items as $item) {
                    if (\App\Models\Review::where('user_id', auth()->id())
                        ->where('product_id', $item->product_id)
                        ->exists()) {
                      $reviewedItems++;
                    }
                  }
                @endphp
                
                <div class="progress-info">
                  <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $totalItems > 0 ? ($reviewedItems / $totalItems) * 100 : 0 }}%"></div>
                  </div>
                  <span class="progress-text">
                    {{ $reviewedItems }} de {{ $totalItems }} produtos avaliados
                  </span>
                </div>
              </div>
              
              <div class="reviews-list">
                @foreach ($order->items as $item)
                  @php
                    $review = \App\Models\Review::where('user_id', auth()->id())
                        ->where('product_id', $item->product_id)
                        ->first();
                  @endphp
                  
                  <div class="review-item">
                    <div class="review-product">
                      <div class="product-image">
                        @if ($item->image)
                          <img src="{{ $item->image }}" alt="{{ $item->name }}">
                        @else
                          <ion-icon name="image-outline"></ion-icon>
                        @endif
                      </div>
                      <div class="product-info">
                        <h4>{{ $item->name }}</h4>
                        <p>Quantidade: {{ $item->quantity }}</p>
                      </div>
                    </div>
                    
                    <div class="review-status">
                      @if($review)
                        <div class="review-details">
                          <div class="rating-display">
                            @for($i = 1; $i <= 5; $i++)
                              <svg class="star {{ $i <= $review->rating ? 'filled' : '' }}" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                              </svg>
                            @endfor
                          </div>
                          @if($review->title)
                            <p class="review-title">{{ $review->title }}</p>
                          @endif
                          <span class="review-date">{{ $review->created_at->format('d/m/Y') }}</span>
                        </div>
                        <span class="reviewed-badge">
                          <ion-icon name="checkmark-circle"></ion-icon>
                          Avaliado
                        </span>
                      @else
                        <a href="{{ route('shop.products.show', $item->product_id) }}#reviews" 
                           class="review-link">
                          <ion-icon name="star-outline"></ion-icon>
                          Avaliar Produto
                        </a>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Endereços -->
        <div class="dashboard-section">
          <div class="section-header">
            <h2 class="section-title">
              <ion-icon name="location-outline"></ion-icon>
              Endereços
            </h2>
          </div>
          <div class="section-content">
            <div class="addresses-grid">
              <div class="address-card">
                <h4>Endereço de Entrega</h4>
                <div class="address-content">
                  {!! nl2br(e($order->shipping_address)) !!}
                </div>
              </div>
              
              <div class="address-card">
                <h4>Endereço de Cobrança</h4>
                <div class="address-content">
                  {!! nl2br(e($order->billing_address)) !!}
                </div>
              </div>
            </div>
          </div>
        </div>
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

/* Header da Página */
.dashboard-header-content {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.order-status-badges {
  display: flex;
  flex-direction: row;
  gap: 12px;
  align-items: center;
  flex-wrap: wrap;
}

/* Status do Pedido */
.order-status {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 0.9rem;
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
  font-size: 18px;
}

/* Indicador de Pagamento */
.payment-indicator {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 16px;
  font-size: 0.8rem;
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
  font-size: 14px;
}

/* Informações do Pedido */
.order-info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.info-label {
  font-size: 0.875rem;
  color: #6b7280;
  font-weight: 500;
}

.info-value {
  font-size: 1rem;
  color: #1f2937;
  font-weight: 600;
}

.total-value {
  font-size: 1.25rem;
  color: #3b82f6;
}

/* Status nas Informações */
.status-pending {
  color: #d97706;
  font-weight: 600;
}

.status-paid {
  color: #059669;
  font-weight: 600;
}

.status-processing {
  color: #2563eb;
  font-weight: 600;
}

.status-shipped {
  color: #7c3aed;
  font-weight: 600;
}

.status-delivered {
  color: #059669;
  font-weight: 600;
}

.status-cancelled {
  color: #dc2626;
  font-weight: 600;
}

.payment-status-approved {
  color: #059669;
  font-weight: 600;
}

.payment-status-pending {
  color: #d97706;
  font-weight: 600;
}

.payment-status-failed {
  color: #dc2626;
  font-weight: 600;
}

/* Seção de Avaliações */
.section-subtitle {
  color: #6b7280;
  font-size: 0.875rem;
  margin: 0;
}

.reviews-summary {
  margin-bottom: 24px;
}

.progress-info {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.progress-bar {
  width: 100%;
  height: 8px;
  background: #f3f4f6;
  border-radius: 4px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: #3b82f6;
  transition: width 0.3s ease;
}

.progress-text {
  font-size: 0.875rem;
  color: #6b7280;
  text-align: center;
}

.reviews-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.review-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px;
  background: #f9fafb;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.review-product {
  display: flex;
  align-items: center;
  gap: 12px;
}

.review-product .product-image {
  width: 40px;
  height: 40px;
  border-radius: 6px;
  overflow: hidden;
  background: #f3f4f6;
  display: flex;
  align-items: center;
  justify-content: center;
}

.review-product .product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.review-product .product-image ion-icon {
  font-size: 20px;
  color: #9ca3af;
}

.product-info h4 {
  margin: 0 0 4px 0;
  font-size: 0.875rem;
  font-weight: 600;
  color: #1f2937;
}

.product-info p {
  margin: 0;
  font-size: 0.75rem;
  color: #6b7280;
}

.review-status {
  display: flex;
  align-items: center;
  gap: 12px;
}

.review-details {
  display: flex;
  flex-direction: column;
  gap: 4px;
  align-items: flex-end;
}

.rating-display {
  display: flex;
  gap: 2px;
}

.rating-display .star {
  width: 16px;
  height: 16px;
  color: #d1d5db;
}

.rating-display .star.filled {
  color: #fbbf24;
}

.review-title {
  font-size: 0.75rem;
  color: #374151;
  margin: 0;
  font-weight: 500;
}

.review-date {
  font-size: 0.7rem;
  color: #9ca3af;
}

/* Links de Avaliação */
.review-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background: #3b82f6;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 500;
  transition: all 0.2s ease;
}

.review-link:hover {
  background: #2563eb;
  transform: translateY(-1px);
}

.review-link ion-icon {
  font-size: 14px;
}

/* Badge de Já Avaliado */
.reviewed-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background: #d1fae5;
  color: #059669;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 500;
}

.reviewed-badge ion-icon {
  font-size: 14px;
}

/* Endereços */
.addresses-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.address-card {
  background: #f9fafb;
  border-radius: 8px;
  padding: 20px;
  border: 1px solid #e5e7eb;
}

.address-card h4 {
  margin: 0 0 12px 0;
  font-size: 1rem;
  font-weight: 600;
  color: #1f2937;
}

.address-content {
  color: #374151;
  line-height: 1.6;
  font-size: 0.875rem;
}

/* Responsividade */
@media (max-width: 768px) {
  .dashboard-breadcrumb {
    font-size: 0.8rem;
    margin-bottom: 12px;
  }
  
  .order-status-badges {
    gap: 8px;
    flex-direction: column;
    align-items: flex-start;
  }
  
  .order-status {
    font-size: 0.8rem;
    padding: 6px 12px;
  }
  
  .order-status ion-icon {
    font-size: 16px;
  }
  
  .payment-indicator {
    font-size: 0.75rem;
    padding: 4px 10px;
  }
  
  .payment-indicator ion-icon {
    font-size: 12px;
  }
  
  .order-info-grid {
    grid-template-columns: 1fr;
  }
  
  .review-item {
    flex-direction: column;
    gap: 12px;
    align-items: flex-start;
  }
  
  .review-status {
    width: 100%;
    justify-content: space-between;
  }
  
  .addresses-grid {
    grid-template-columns: 1fr;
  }
}
</style>
@endpush
