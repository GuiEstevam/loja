@extends('layouts.app')

@section('title', 'Meus Pedidos')

@push('styles')
@vite(['resources/css/orders.css'])
@endpush

@section('content')
<div class="orders-page">
    <div class="orders-container">
        <!-- Breadcrumb -->
        <nav class="orders-breadcrumb">
            <a href="{{ route('shop.dashboard') }}">
                <ion-icon name="home-outline"></ion-icon>
                Início
            </a>
            <ion-icon name="chevron-forward"></ion-icon>
            <span>Meus Pedidos</span>
        </nav>

      <!-- Header da Página -->
        <div class="orders-header">
            <div class="orders-title">
          <ion-icon name="bag-outline"></ion-icon>
                <h1>Meus Pedidos</h1>
            </div>
            <p class="orders-subtitle">Acompanhe todos os seus pedidos e status de entrega</p>
      </div>

      <!-- Lista de Pedidos -->
        @if($orders->count() > 0)
            <div class="orders-list">
                @foreach($orders as $order)
                    <div class="order-card">
                        <!-- Header do Pedido -->
                        <div class="order-card-header">
                            <div class="order-header-main">
                                <div class="order-number">Pedido #{{ $order->id }}</div>
                                <div class="order-details">
                                    <div class="order-detail-item">
                                        <ion-icon name="calendar-outline"></ion-icon>
                                        <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="order-detail-item">
                                        <ion-icon name="cube-outline"></ion-icon>
                                        <span>{{ $order->items->count() }} produto(s)</span>
                                    </div>
                                    <div class="order-detail-item">
                                        <ion-icon name="card-outline"></ion-icon>
                                        <span>{{ translatePaymentMethod($order->payment_method ?? 'credit_card') }}</span>
                                    </div>
                                    <div class="order-detail-item">
                                        <ion-icon name="cash-outline"></ion-icon>
                                        <span>€{{ number_format($order->total, 2, ',', '.') }}</span>
              </div>
            </div>
          </div>
                            
                            <div class="order-status-section">
                                <div class="order-status-badges">
                                    <span class="status-badge {{ $order->status }}">
                                        <ion-icon name="checkmark-circle"></ion-icon>
                                        {{ translateOrderStatus($order->status) }}
                                    </span>
                                    @if($order->payment && $order->payment->status)
                                        <span class="status-badge paid">
                                            <ion-icon name="card"></ion-icon>
                                            {{ translateOrderStatus($order->payment->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ações do Pedido -->
                        <div class="order-card-actions">
                            <a href="{{ route('shop.orders.show', $order) }}" class="btn-view-details">
                    <ion-icon name="eye-outline"></ion-icon>
                    Ver Detalhes
                  </a>
                </div>

                        <!-- Itens do Pedido -->
                        <div class="order-items">
                            <h3 class="order-items-title">
                                <ion-icon name="cube-outline"></ion-icon>
                                Produtos do Pedido
                            </h3>
                            
                            @foreach($order->items as $item)
                                <div class="order-item">
                                    <a href="{{ route('shop.products.show', $item->product) }}" target="_blank" class="order-item-link">
                                        <img src="{{ $item->image ?: $item->product->image }}" 
                                             alt="{{ $item->name }}" 
                                             class="order-item-image">
                                        
                                        <div class="order-item-details">
                                            <h4 class="order-item-name">{{ $item->name }}</h4>
                                            <p class="order-item-specs">
                                                Quantidade: {{ $item->quantity }} • 
                                                Preço: €{{ number_format($item->price, 2, ',', '.') }} • 
                                                @if($item->color) Cor: {{ $item->color }} • @endif
                                                @if($item->size) Tamanho: {{ $item->size }} @endif
                      </p>
                    </div>
                                        
                                        <div class="order-item-price">
                                            €{{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                    </div>
                                    </a>
                                    
                                    <!-- Ações de Avaliação -->
                                    @if($order->status === 'delivered' || ($order->status === 'paid' && $order->payment?->status === 'approved'))
                                        @php
                                            $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                                                ->where('product_id', $item->product_id)
                                                ->exists();
                                        @endphp
                                        
                                        <div class="order-item-actions">
                                            @if(!$hasReviewed)
                                                <button type="button" 
                                                        class="review-link" 
                                                        onclick="openOrderReviewModal({{ $item->product_id }}, '{{ $item->name }}', '{{ $item->image ?: $item->product->image }}')">
                                                    <ion-icon name="star-outline"></ion-icon>
                                                    Avaliar Produto
                                                </button>
                                            @else
                                                @php
                                                    $review = \App\Models\Review::where('user_id', auth()->id())
                                                        ->where('product_id', $item->product_id)
                                                        ->first();
                                                @endphp
                                                <div class="review-actions">
                                                    <span class="reviewed-badge">
                                                        <ion-icon name="checkmark-circle"></ion-icon>
                                                        Avaliado
                                                    </span>
                                                    <div class="action-buttons">
                                                        <button type="button" 
                                                                class="action-btn edit-btn" 
                                                                onclick="openOrderEditReviewModal({{ $review->id }}, {{ $item->product_id }}, '{{ $item->name }}', '{{ $item->image ?: $item->product->image }}')"
                                                                title="Editar Avaliação">
                                                            <ion-icon name="create-outline"></ion-icon>
                                                        </button>
                                                        <button type="button" 
                                                                class="action-btn delete-btn" 
                                                                onclick="deleteOrderReview({{ $review->id }})"
                                                                title="Excluir Avaliação">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </button>
                  </div>
                </div>
                        @endif
                      </div>
                          @endif
                      </div>
                            @endforeach
                      </div>
                    </div>
                  @endforeach
                </div>

          <!-- Paginação -->
          @if ($orders->hasPages())
                <div class="orders-pagination">
                  {{ $orders->links() }}
                </div>
            @endif
        @else
            <!-- Estado Vazio -->
            <div class="orders-empty">
                <div class="empty-icon">
                    <ion-icon name="bag-outline"></ion-icon>
              </div>
                <h3>Nenhum pedido encontrado</h3>
                <p>Você ainda não fez nenhum pedido. Que tal começar a comprar?</p>
                <a href="{{ route('shop.products.index') }}" class="btn btn-primary">
                    <ion-icon name="storefront-outline"></ion-icon>
                    Ver Produtos
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal de Avaliação -->
<div id="orderReviewModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
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
                        <input type="radio" id="order-star-1" name="rating" value="1">
                        <label for="order-star-1" class="rating-star">
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
                        
                        <input type="radio" id="order-star-3" name="rating" value="3">
                        <label for="order-star-3" class="rating-star">
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
                        
                        <input type="radio" id="order-star-5" name="rating" value="5">
                        <label for="order-star-5" class="rating-star">
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
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeOrderReviewModal()">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="submitOrderReviewBtn">Enviar Avaliação</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Edição de Avaliação -->
<div id="orderEditReviewModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Avaliação</h3>
            <button type="button" class="modal-close" onclick="closeOrderEditReviewModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="product-info">
                <img id="editModalProductImage" src="" alt="" class="product-image">
                <div class="product-details">
                    <h4 id="editModalProductName"></h4>
                    <p>Edite sua avaliação deste produto</p>
                </div>
            </div>
            
            <form id="orderEditReviewForm">
                <input type="hidden" id="editModalReviewId" name="review_id">
                <input type="hidden" id="editModalProductId" name="product_id">
                
                <div class="form-group">
                    <label for="editOrderRating">Sua Avaliação *</label>
                    <div class="rating-input" id="editOrderRatingInput">
                        <input type="radio" id="edit-order-star-1" name="rating" value="1">
                        <label for="edit-order-star-1" class="rating-star">
                            <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </label>
                        
                        <input type="radio" id="edit-order-star-2" name="rating" value="2">
                        <label for="edit-order-star-2" class="rating-star">
                            <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </label>
                        
                        <input type="radio" id="edit-order-star-3" name="rating" value="3">
                        <label for="edit-order-star-3" class="rating-star">
                            <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </label>
                        
                        <input type="radio" id="edit-order-star-4" name="rating" value="4">
                        <label for="edit-order-star-4" class="rating-star">
                            <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </label>
                        
                        <input type="radio" id="edit-order-star-5" name="rating" value="5">
                        <label for="edit-order-star-5" class="rating-star">
                            <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="editOrderTitle">Título da Avaliação</label>
                    <input type="text" id="editOrderTitle" name="title" class="form-input" placeholder="Resumo da sua experiência" maxlength="255">
                    <div class="char-counter">
                        <span id="editOrderTitleCounter">0</span>/255 caracteres
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="editOrderComment">Comentário Detalhado</label>
                    <textarea id="editOrderComment" name="comment" class="form-textarea" placeholder="Conte sua experiência com o produto, qualidade, entrega, etc." maxlength="1000"></textarea>
                    <div class="char-counter">
                        <span id="editOrderCommentCounter">0</span>/1000 caracteres
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeOrderEditReviewModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
@vite(['resources/js/orders.js'])
@endpush