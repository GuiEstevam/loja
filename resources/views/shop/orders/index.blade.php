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
                    <div class="item-card-container">
                      <a href="{{ route('shop.products.show', $item->product_id) }}" 
                         target="_blank" 
                         class="item-card-clickable">
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
                        <div class="item-price">
                          <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                            €{{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                          </p>
                        </div>
                      </a>
                      
                      <!-- Botões de Avaliação (fora do card clicável) -->
                      @if($order->status === 'delivered' || ($order->status === 'paid' && $order->payment?->status === 'approved'))
                        @php
                          $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                              ->where('product_id', $item->product_id)
                              ->exists();
                        @endphp
                        
                        <div class="item-actions">
                          @if(!$hasReviewed)
                            <button type="button" 
                                    class="review-link" 
                                    onclick="openOrderReviewModal({{ $item->product_id }}, '{{ $item->name }}', '{{ $item->image ?: $item->product->image }}')">
                              <ion-icon name="star-outline"></ion-icon>
                              Avaliar Produto
                            </button>
                          @else
                            <span class="reviewed-badge">
                              <ion-icon name="checkmark-circle"></ion-icon>
                              Avaliado
                            </span>
                          @endif
                        </div>
                      @endif
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

<!-- Modal de Avaliação -->
<div id="orderReviewModal" class="modal-overlay" style="display: none;">
    <div class="modal-content order-review-modal">
        <div class="modal-header">
            <h3>Avaliar Produto</h3>
            <button type="button" class="modal-close" onclick="closeOrderReviewModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="product-info">
                <img id="modalProductImage" src="" alt="" class="product-image">
                <div class="product-details">
                    <h4 id="modalProductName"></h4>
                    <p>Avalie sua experiência com este produto</p>
                </div>
            </div>
            
            <form id="orderReviewForm">
                <input type="hidden" id="modalProductId" name="product_id">
                
                <div class="form-group">
                    <label for="orderRating">Sua Avaliação *</label>
                    <div class="rating-input" id="orderRatingInput">
                        <input type="radio" id="order-star-5" name="rating" value="5">
                        <label for="order-star-5" class="rating-star">
                            <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </label>
                        
                        <input type="radio" id="order-star-4" name="rating" value="4">
                        <label for="order-star-4" class="rating-star">
                            <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </label>
                        
                        <input type="radio" id="order-star-3" name="rating" value="3">
                        <label for="order-star-3" class="rating-star">
                            <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </label>
                        
                        <input type="radio" id="order-star-2" name="rating" value="2">
                        <label for="order-star-2" class="rating-star">
                            <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </label>
                        
                        <input type="radio" id="order-star-1" name="rating" value="1">
                        <label for="order-star-1" class="rating-star">
                            <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="orderTitle">Título da Avaliação</label>
                    <input type="text" id="orderTitle" name="title" class="form-input" placeholder="Resumo da sua experiência" maxlength="255">
                    <div class="char-counter">
                        <span id="orderTitleCounter">0</span>/255 caracteres
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="orderComment">Comentário Detalhado</label>
                    <textarea id="orderComment" name="comment" class="form-textarea" placeholder="Conte sua experiência com o produto, qualidade, entrega, etc." maxlength="1000"></textarea>
                    <div class="char-counter">
                        <span id="orderCommentCounter">0</span>/1000 caracteres
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeOrderReviewModal()">Cancelar</button>
            <button type="button" class="btn btn-primary" id="submitOrderReviewBtn">Enviar Avaliação</button>
        </div>
    </div>
</div>

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

/* Modal de Avaliação */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 12px;
  padding: 0;
  max-width: 600px;
  width: 95%;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
}

.modal-close {
  background: none;
  border: none;
  font-size: 24px;
  color: #6b7280;
  cursor: pointer;
  padding: 0;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-close:hover {
  color: #374151;
}

.modal-body {
  padding: 20px 24px;
}

.modal-footer {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding: 20px 24px;
  border-top: 1px solid #e5e7eb;
}

.btn {
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
}

.btn-primary {
  background: #3b82f6;
  color: white;
}

.btn-primary:hover {
  background: #2563eb;
}

.btn-secondary {
  background: #6b7280;
  color: white;
}

.btn-secondary:hover {
  background: #4b5563;
}

.product-info {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 24px;
  padding: 16px;
  background: #f8fafc;
  border-radius: 8px;
}

.product-info .product-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
}

.product-details h4 {
  margin: 0 0 4px 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #1f2937;
}

.product-details p {
  margin: 0;
  color: #6b7280;
  font-size: 0.875rem;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #374151;
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.875rem;
  transition: border-color 0.2s ease;
  box-sizing: border-box;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 100px;
}

.char-counter {
  text-align: right;
  font-size: 0.75rem;
  color: #6b7280;
  margin-top: 4px;
}

.rating-input {
  display: flex;
  gap: 12px;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}

.rating-input input[type="radio"] {
  display: none;
}

.rating-star {
  cursor: pointer;
  transition: transform 0.2s ease;
}

.rating-star:hover {
  transform: scale(1.1);
}

.rating-star .star-icon {
  width: 40px;
  height: 40px;
  color: #d1d5db;
  transition: color 0.2s ease;
}

.rating-star.selected .star-icon {
  color: #fbbf24;
}

.rating-star:not(.selected) .star-icon {
  color: #d1d5db;
}

.rating-star.hover .star-icon {
  color: #fbbf24;
}

/* Card clicável */
.item-card-container {
  margin-bottom: 16px;
}

.item-card-clickable {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px;
  background: #f9fafb;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  text-decoration: none;
  color: inherit;
  transition: all 0.2s ease;
  cursor: pointer;
}

.item-card-clickable:hover {
  background: #f3f4f6;
  border-color: #3b82f6;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
}

.item-card-clickable .item-image {
  width: 60px;
  height: 60px;
  border-radius: 8px;
  overflow: hidden;
  background: #f3f4f6;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.item-card-clickable .item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.item-card-clickable .item-image ion-icon {
  font-size: 1.5rem;
  color: #9ca3af;
}

.item-card-clickable .item-details {
  flex: 1;
  min-width: 0;
}

.item-card-clickable .item-title {
  margin: 0 0 8px 0;
  font-size: 1rem;
  font-weight: 600;
  color: #1f2937;
  line-height: 1.4;
}

.item-card-clickable .item-meta {
  margin: 0;
  font-size: 0.875rem;
  color: #6b7280;
  line-height: 1.4;
}

.item-card-clickable .item-price {
  flex-shrink: 0;
  text-align: right;
}

.item-card-clickable .item-price p {
  margin: 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #3b82f6;
}

.item-actions {
  margin-top: 12px;
  display: flex;
  justify-content: flex-end;
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
  
  .item-card-clickable {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }
  
  .item-card-clickable .item-price {
    text-align: left;
    width: 100%;
  }
  
  .item-actions {
    justify-content: flex-start;
  }
}
</style>
@endpush

@push('scripts')
<script>
// Modal de Avaliação para Pedidos
function openOrderReviewModal(productId, productName, productImage) {
    const modal = document.getElementById('orderReviewModal');
    const productImageEl = document.getElementById('modalProductImage');
    const productNameEl = document.getElementById('modalProductName');
    const productIdEl = document.getElementById('modalProductId');
    
    // Preencher dados do produto
    productIdEl.value = productId;
    productNameEl.textContent = productName;
    
    // Tratar imagem do produto
    if (productImage && productImage.trim() !== '') {
        productImageEl.src = productImage;
        productImageEl.alt = productName;
        productImageEl.style.display = 'block';
    } else {
        // Se não houver imagem, mostrar ícone
        productImageEl.style.display = 'none';
        const iconContainer = productImageEl.parentElement;
        if (!iconContainer.querySelector('.no-image-icon')) {
            const icon = document.createElement('ion-icon');
            icon.name = 'image-outline';
            icon.className = 'no-image-icon';
            icon.style.cssText = 'font-size: 2rem; color: #9ca3af; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;';
            iconContainer.appendChild(icon);
        }
    }
    
    // Limpar formulário
    document.getElementById('orderReviewForm').reset();
    document.getElementById('orderTitleCounter').textContent = '0';
    document.getElementById('orderCommentCounter').textContent = '0';
    
    // Remover classes de seleção das estrelas
    const stars = document.querySelectorAll('#orderRatingInput .rating-star');
    stars.forEach(star => {
        star.classList.remove('selected', 'hover');
    });
    
    // Mostrar modal
    modal.style.display = 'flex';
}

function closeOrderReviewModal() {
    const modal = document.getElementById('orderReviewModal');
    modal.style.display = 'none';
}

// Função para mostrar modal padrão do app.blade.php
function showModal(message, type = 'info') {
    // Remover modais existentes
    const existingModals = document.querySelectorAll('#feedbackModal');
    existingModals.forEach(modal => modal.remove());
    
    // Criar novo modal usando o padrão do app.blade.php
    const modal = document.createElement('div');
    modal.id = 'feedbackModal';
    modal.className = 'fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50';
    modal.innerHTML = `
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full shadow-lg relative">
            <button type="button" id="closeModal"
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-2xl font-bold leading-none focus:outline-none"
                aria-label="Fechar">
                &times;
            </button>
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Mensagem</h2>
            <div class="mb-4 ${type === 'success' ? 'text-green-600 dark:text-green-400' : type === 'error' ? 'text-red-600 dark:text-red-400' : type === 'warning' ? 'text-yellow-600 dark:text-yellow-400' : 'text-blue-600 dark:text-blue-400'}">${message}</div>
        </div>
    `;
    
    // Adicionar ao body
    document.body.appendChild(modal);
    
    // Configurar eventos de fechamento
    const closeBtn = modal.querySelector('#closeModal');
    closeBtn.onclick = function() {
        modal.remove();
    };
    
    modal.onclick = function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    };
    
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") {
            modal.remove();
        }
    });
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Controle de seleção das estrelas
    const ratingInputs = document.querySelectorAll('#orderRatingInput input[name="rating"]');
    const ratingStars = document.querySelectorAll('#orderRatingInput .rating-star');
    
    ratingInputs.forEach((input, index) => {
        input.addEventListener('change', function() {
            // Remove todas as classes de seleção
            ratingStars.forEach(star => {
                star.classList.remove('selected');
            });
            
            // Adiciona classe de seleção até a estrela clicada
            for (let i = 0; i <= index; i++) {
                ratingStars[i].classList.add('selected');
            }
        });
    });
    
    // Controle de hover para mostrar preview
    ratingStars.forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            // Remove todas as classes de hover
            ratingStars.forEach(s => s.classList.remove('hover'));
            
            // Adiciona classe hover até a estrela atual
            for (let i = 0; i <= index; i++) {
                ratingStars[i].classList.add('hover');
            }
        });
    });
    
    // Remove hover quando sair do container
    const ratingInput = document.querySelector('#orderRatingInput');
    if (ratingInput) {
        ratingInput.addEventListener('mouseleave', function() {
            ratingStars.forEach(star => {
                star.classList.remove('hover');
            });
        });
    }
    
    // Contadores de caracteres
    const titleInput = document.getElementById('orderTitle');
    const commentInput = document.getElementById('orderComment');
    const titleCounter = document.getElementById('orderTitleCounter');
    const commentCounter = document.getElementById('orderCommentCounter');
    
    if (titleInput && titleCounter) {
        titleInput.addEventListener('input', function() {
            titleCounter.textContent = this.value.length;
        });
    }
    
    if (commentInput && commentCounter) {
        commentInput.addEventListener('input', function() {
            commentCounter.textContent = this.value.length;
        });
    }
    
    // Botão de enviar avaliação
    const submitBtn = document.getElementById('submitOrderReviewBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            const formData = new FormData(document.getElementById('orderReviewForm'));
            
            // Validar rating
            const rating = formData.get('rating');
            if (!rating) {
                showModal('Por favor, selecione uma avaliação', 'warning');
                return;
            }
            
            // Mostrar loading
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>Enviando...';
            submitBtn.disabled = true;
            
            // Enviar dados
            fetch('/reviews', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: parseInt(formData.get('product_id')),
                    rating: parseInt(rating),
                    title: formData.get('title'),
                    comment: formData.get('comment')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModal(data.message, 'success');
                    closeOrderReviewModal();
                    location.reload(); // Recarregar para atualizar botões
                } else {
                    showModal(data.error || 'Erro ao enviar avaliação', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showModal('Erro ao enviar avaliação', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Fechar modal ao clicar fora
    const modal = document.getElementById('orderReviewModal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeOrderReviewModal();
            }
        });
    }
});
</script>
@endpush
