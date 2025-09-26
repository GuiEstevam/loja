@extends('layouts.app')

@section('title', 'Detalhes do Pedido #' . $order->id)

@push('styles')
@vite(['resources/css/orders.css', 'resources/css/dashboard.css'])
@endpush

@section('content')
<div class="dashboard-page">
    <div class="dashboard-container">
        <!-- Breadcrumb -->
        <nav class="dashboard-breadcrumb">
            <div class="dashboard-breadcrumb-nav">
                <a href="{{ route('shop.dashboard') }}" class="dashboard-breadcrumb-item">
                    <ion-icon name="home-outline"></ion-icon>
                    Dashboard
                </a>
                <span class="dashboard-breadcrumb-separator">›</span>
                <a href="{{ route('shop.orders.index') }}" class="dashboard-breadcrumb-item">
                    <ion-icon name="bag-outline"></ion-icon>
                    Meus Pedidos
                </a>
                <span class="dashboard-breadcrumb-separator">›</span>
                <span class="dashboard-breadcrumb-item active">
                    <ion-icon name="receipt-outline"></ion-icon>
                    Pedido #{{ $order->id }}
                </span>
            </div>
            <a href="{{ route('shop.orders.index') }}" class="dashboard-breadcrumb-back">
                <ion-icon name="arrow-back-outline"></ion-icon>
                Voltar
            </a>
        </nav>

        <!-- Header da Página -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <ion-icon name="receipt-outline"></ion-icon>
                Pedido #{{ $order->id }}
            </h1>
            <p class="dashboard-subtitle">Detalhes completos do seu pedido</p>
        </div>

        <!-- Informações do Pedido -->
        <div class="dashboard-section">
            <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                    <ion-icon name="information-circle-outline"></ion-icon>
                    Informações do Pedido
                </h3>
            </div>
            <div class="dashboard-section-content">
                <div class="dashboard-info-grid">
                    <div class="dashboard-info-item">
                        <div class="dashboard-info-icon">
                            <ion-icon name="calendar-outline"></ion-icon>
                        </div>
                        <div class="dashboard-info-content">
                            <h4 class="dashboard-info-label">Data do Pedido</h4>
                            <p class="dashboard-info-value">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="dashboard-info-item">
                        <div class="dashboard-info-icon">
                            <ion-icon name="cube-outline"></ion-icon>
                        </div>
                        <div class="dashboard-info-content">
                            <h4 class="dashboard-info-label">Quantidade de Itens</h4>
                            <p class="dashboard-info-value">{{ $order->items->count() }} produto(s)</p>
                        </div>
                    </div>
                    
                    <div class="dashboard-info-item">
                        <div class="dashboard-info-icon">
                            <ion-icon name="cash-outline"></ion-icon>
                        </div>
                        <div class="dashboard-info-content">
                            <h4 class="dashboard-info-label">Valor Total</h4>
                            <p class="dashboard-info-value">€{{ number_format($order->total, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <div class="dashboard-info-item">
                        <div class="dashboard-info-icon">
                            <ion-icon name="flag-outline"></ion-icon>
                        </div>
                        <div class="dashboard-info-content">
                            <h4 class="dashboard-info-label">Status</h4>
                            <span class="dashboard-status-badge {{ $order->status }}">
                                @switch($order->status)
                                    @case('pending') Pendente @break
                                    @case('paid') Pago @break
                                    @case('processing') Processando @break
                                    @case('shipped') Enviado @break
                                    @case('delivered') Entregue @break
                                    @case('cancelled') Cancelado @break
                                    @default {{ ucfirst($order->status) }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Sistema de Abas -->
            <div class="order-tabs">
                <!-- Navegação das Abas -->
                <div class="order-tabs-nav">
                    <button class="order-tab-btn active" data-tab="overview">
                        <ion-icon name="eye-outline"></ion-icon>
                        Visão Geral
                    </button>
                    <button class="order-tab-btn" data-tab="payment">
                        <ion-icon name="card-outline"></ion-icon>
                        Pagamento
                    </button>
                    <button class="order-tab-btn" data-tab="shipping">
                        <ion-icon name="location-outline"></ion-icon>
                        Entrega
                    </button>
                    <button class="order-tab-btn" data-tab="products">
                        <ion-icon name="cube-outline"></ion-icon>
                        Produtos
                    </button>
                </div>

                <!-- Conteúdo das Abas -->
                <div class="order-tabs-content">
                    <!-- Aba: Visão Geral -->
                    <div id="overview" class="order-tab-panel active">
                        <div class="tab-panel-content">
                            <!-- Informações Detalhadas do Pedido -->
                            <div class="dashboard-info-grid">
                                <div class="dashboard-info-item">
                                    <div class="dashboard-info-icon">
                                        <ion-icon name="receipt-outline"></ion-icon>
                                    </div>
                                    <div class="dashboard-info-content">
                                        <h4 class="dashboard-info-label">Número do Pedido</h4>
                                        <p class="dashboard-info-value">#{{ $order->id }}</p>
                                    </div>
                                </div>
                                
                                <div class="dashboard-info-item">
                                    <div class="dashboard-info-icon">
                                        <ion-icon name="calendar-outline"></ion-icon>
                                    </div>
                                    <div class="dashboard-info-content">
                                        <h4 class="dashboard-info-label">Data do Pedido</h4>
                                        <p class="dashboard-info-value">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                
                                <div class="dashboard-info-item">
                                    <div class="dashboard-info-icon">
                                        <ion-icon name="flag-outline"></ion-icon>
                                    </div>
                                    <div class="dashboard-info-content">
                                        <h4 class="dashboard-info-label">Status</h4>
                                        <span class="dashboard-status-badge {{ $order->status }}">
                                            @switch($order->status)
                                                @case('pending') Pendente @break
                                                @case('paid') Pago @break
                                                @case('processing') Processando @break
                                                @case('shipped') Enviado @break
                                                @case('delivered') Entregue @break
                                                @case('cancelled') Cancelado @break
                                                @default {{ ucfirst($order->status) }}
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="dashboard-info-item">
                                    <div class="dashboard-info-icon">
                                        <ion-icon name="cash-outline"></ion-icon>
                                    </div>
                                    <div class="dashboard-info-content">
                                        <h4 class="dashboard-info-label">Valor Total</h4>
                                        <p class="dashboard-info-value">€{{ number_format($order->total, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                                
                                <div class="dashboard-info-item">
                                    <div class="dashboard-info-icon">
                                        <ion-icon name="person-outline"></ion-icon>
                                    </div>
                                    <div class="dashboard-info-content">
                                        <h4 class="dashboard-info-label">Nome do Cliente</h4>
                                        <p class="dashboard-info-value">{{ $order->name ?? $order->user->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="dashboard-info-item">
                                    <div class="dashboard-info-icon">
                                        <ion-icon name="mail-outline"></ion-icon>
                                    </div>
                                    <div class="dashboard-info-content">
                                        <h4 class="dashboard-info-label">Email</h4>
                                        <p class="dashboard-info-value">{{ $order->email ?? $order->user->email }}</p>
                                    </div>
                                </div>
                                
                                @if($order->phone)
                                    <div class="dashboard-info-item">
                                        <div class="dashboard-info-icon">
                                            <ion-icon name="call-outline"></ion-icon>
                                        </div>
                                        <div class="dashboard-info-content">
                                            <h4 class="dashboard-info-label">Telefone</h4>
                                            <p class="dashboard-info-value">{{ $order->phone }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Aba: Pagamento -->
                    <div id="payment" class="order-tab-panel">
                        <div class="tab-panel-content">
                            <h3 class="tab-section-title">
                                <ion-icon name="card-outline"></ion-icon>
                                Informações de Pagamento
                            </h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Método:</span>
                                    <span class="info-value">{{ $order->payment_method ? translatePaymentMethod($order->payment_method) : 'Não informado' }}</span>
                                </div>
                                @if($order->payment)
                                    <div class="info-item">
                                        <span class="info-label">Status:</span>
                                        <span class="info-value status-{{ $order->payment->status }}">
                                            {{ translateOrderStatus($order->payment->status) }}
                                        </span>
                                    </div>
                                    @if($order->payment->transaction_id)
                                        <div class="info-item">
                                            <span class="info-label">ID da Transação:</span>
                                            <span class="info-value">{{ $order->payment->transaction_id }}</span>
                                        </div>
                                    @endif
                                    @if($order->payment->processed_at)
                                        <div class="info-item">
                                            <span class="info-label">Processado em:</span>
                                            <span class="info-value">{{ $order->payment->processed_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    @endif
                                    @if($order->payment->amount)
                                        <div class="info-item">
                                            <span class="info-label">Valor Pago:</span>
                                            <span class="info-value">€{{ number_format($order->payment->amount, 2, ',', '.') }}</span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Aba: Entrega -->
                    <div id="shipping" class="order-tab-panel">
                        <div class="tab-panel-content">
                            @if($order->street || $order->address)
                                <h3 class="tab-section-title">
                                    <ion-icon name="location-outline"></ion-icon>
                                    Endereço de Entrega
                                </h3>
                                <div class="shipping-address">
                                    @if($order->name)
                                        <div class="address-item">
                                            <span class="address-label">Nome:</span>
                                            <span class="address-value">{{ $order->name }}</span>
                                        </div>
                                    @endif
                                    @if($order->street && $order->number)
                                        <div class="address-item">
                                            <span class="address-label">Endereço:</span>
                                            <span class="address-value">{{ $order->street }}, {{ $order->number }}</span>
                                        </div>
                                    @elseif($order->address)
                                        <div class="address-item">
                                            <span class="address-label">Endereço:</span>
                                            <span class="address-value">{{ $order->address }}</span>
                                        </div>
                                    @endif
                                    @if($order->complement)
                                        <div class="address-item">
                                            <span class="address-label">Complemento:</span>
                                            <span class="address-value">{{ $order->complement }}</span>
                                        </div>
                                    @endif
                                    @if($order->neighborhood)
                                        <div class="address-item">
                                            <span class="address-label">Bairro:</span>
                                            <span class="address-value">{{ $order->neighborhood }}</span>
                                        </div>
                                    @endif
                                    @if($order->city && $order->state)
                                        <div class="address-item">
                                            <span class="address-label">Cidade/Estado:</span>
                                            <span class="address-value">{{ $order->city }}/{{ $order->state }}</span>
                                        </div>
                                    @endif
                                    @if($order->zip)
                                        <div class="address-item">
                                            <span class="address-label">CEP:</span>
                                            <span class="address-value">{{ $order->zip }}</span>
                                        </div>
                                    @endif
                                    @if($order->country)
                                        <div class="address-item">
                                            <span class="address-label">País:</span>
                                            <span class="address-value">{{ $order->country }}</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="empty-state">
                                    <ion-icon name="location-outline"></ion-icon>
                                    <p>Nenhum endereço de entrega cadastrado</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Aba: Produtos -->
                    <div id="products" class="order-tab-panel">
                        <div class="tab-panel-content">
                            <h3 class="tab-section-title">
                                <ion-icon name="cube-outline"></ion-icon>
                                Produtos do Pedido
                            </h3>
                            
                            <div class="order-items">
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
                        @if(in_array($order->status, ['delivered', 'paid', 'processing', 'shipped']))
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

                            <!-- Resumo Financeiro -->
                            <div class="order-summary">
                                <h3 class="summary-title">
                                    <ion-icon name="calculator-outline"></ion-icon>
                                    Resumo do Pedido
                                </h3>
                                <div class="summary-details">
                                    <div class="summary-item">
                                        <span>Subtotal:</span>
                                        <span>€{{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 2, ',', '.') }}</span>
                                    </div>
                                    @if($order->shipping_cost > 0)
                                        <div class="summary-item">
                                            <span>Frete:</span>
                                            <span>€{{ number_format($order->shipping_cost, 2, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if($order->discount > 0)
                                        <div class="summary-item discount">
                                            <span>Desconto:</span>
                                            <span>-€{{ number_format($order->discount, 2, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div class="summary-item total">
                                        <span><strong>Total:</strong></span>
                                        <span><strong>€{{ number_format($order->total, 2, ',', '.') }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    <!-- Ações do Pedido -->
        <div class="dashboard-section">
            <div class="dashboard-section-content">
                <div class="dashboard-actions">
                    <a href="{{ route('shop.orders.index') }}" class="dashboard-back-btn">
                        <ion-icon name="arrow-back-outline"></ion-icon>
                        Voltar para Pedidos
                    </a>
                    @if($order->status === 'pending')
                        <a href="{{ route('shop.checkout') }}" class="dashboard-btn">
                            <ion-icon name="card-outline"></ion-icon>
                            Finalizar Pagamento
                        </a>
                    @endif
                </div>
            </div>
        </div>
        </div>


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

<script>
// Sistema de abas
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.order-tab-btn');
    const tabPanels = document.querySelectorAll('.order-tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class de todos os botões e painéis
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            // Adiciona active class ao botão clicado e painel correspondente
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });
});
</script>
@endsection

@push('scripts')
@vite(['resources/js/orders.js'])
@endpush