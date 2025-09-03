@extends('layouts.app')

@section('title', 'Confirmação do Pedido')

@push('styles')
  @vite(['resources/css/confirmation.css'])
@endpush

@section('content')
  <div class="confirmation-page">
    <div class="confirmation-container">
      <!-- Header da Página -->
      <div class="confirmation-header">
        <h1 class="confirmation-title">
          <ion-icon name="checkmark-circle"></ion-icon>
          Pedido Confirmado!
        </h1>
        <p class="confirmation-subtitle">Seu pedido foi realizado com sucesso</p>
      </div>

      <!-- Progresso do Checkout -->
      <div class="confirmation-progress">
        <div class="progress-step completed">
          <ion-icon name="checkmark-circle"></ion-icon>
          <span>Carrinho</span>
        </div>
        <div class="progress-connector completed"></div>
        <div class="progress-step completed">
          <ion-icon name="card-outline"></ion-icon>
          <span>Pagamento</span>
        </div>
        <div class="progress-connector completed"></div>
        <div class="progress-step active">
          <ion-icon name="checkmark-outline"></ion-icon>
          <span>Confirmação</span>
        </div>
      </div>

      <!-- Layout Principal -->
      <div class="confirmation-main">
        <!-- Resumo do Pedido -->
        <div class="confirmation-summary-section">
          <div class="confirmation-summary">
            <div class="summary-header">
              <h3 class="summary-title">
                <ion-icon name="receipt-outline"></ion-icon>
                Resumo do Pedido #{{ $order->id }}
              </h3>
            </div>

            <div class="summary-content">
              <!-- Informações do Pedido -->
              <div class="order-info">
                <div class="info-row">
                  <span class="info-label">Status:</span>
                  <span class="info-value status-{{ $order->status }}">
                    @switch($order->status)
                      @case('pending')
                        <ion-icon name="time-outline"></ion-icon>
                        {{ translateOrderStatus('pending') }}
                      @break

                      @case('processing')
                        <ion-icon name="sync-outline"></ion-icon>
                        {{ translateOrderStatus('processing') }}
                      @break

                      @case('shipped')
                        <ion-icon name="car-outline"></ion-icon>
                        {{ translateOrderStatus('shipped') }}
                      @break

                      @case('delivered')
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                        {{ translateOrderStatus('delivered') }}
                      @break

                      @default
                        <ion-icon name="help-circle-outline"></ion-icon>
                        {{ translateOrderStatus($order->status) }}
                    @endswitch
                  </span>
                </div>

                <div class="info-row">
                  <span class="info-label">Data:</span>
                  <span class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>

                <div class="info-row">
                  <span class="info-label">Método de Pagamento:</span>
                  <span class="info-value">{{ translatePaymentMethod($order->payment_method) }}</span>
                </div>
              </div>

              <!-- Itens do Pedido -->
              <div class="order-items">
                <h4 class="items-title">Itens do Pedido</h4>
                <!-- Debug: Total de itens: {{ $order->items->count() }} -->
                <div class="items-list">
                  @if ($order->items->count() > 0)
                    @foreach ($order->items as $item)
                      <div class="order-item">
                        <div class="item-image">
                          @if ($item->image)
                            <!-- Debug: {{ $item->image }} -->
                            <img src="{{ $item->image }}" alt="{{ $item->name }}"
                              onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<ion-icon name=\'image-outline\' style=\'font-size: 1.5rem; color: var(--confirmation-text-secondary);\'></ion-icon>';">
                          @else
                            <ion-icon name="image-outline"></ion-icon>
                          @endif
                        </div>
                        <div class="item-details">
                          <span class="item-name">{{ $item->name }}</span>
                          <span class="item-quantity">Quantidade: {{ $item->quantity }}</span>
                          @if ($item->color_name)
                            <span class="item-color">Cor: {{ $item->color_name }}</span>
                          @endif
                          @if ($item->size)
                            <span class="item-size">Tamanho: {{ $item->size }}</span>
                          @endif
                          <span class="item-price">€{{ number_format($item->price, 2, ',', '.') }}</span>
                        </div>
                        <div class="item-subtotal">
                          €{{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                        </div>
                      </div>
                    @endforeach
                  @else
                    <div class="order-item">
                      <div class="item-image">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                      </div>
                      <div class="item-details">
                        <span class="item-name">Nenhum item encontrado</span>
                        <span class="item-quantity">Verifique se o pedido foi processado corretamente</span>
                      </div>
                    </div>
                  @endif
                </div>
              </div>

              <!-- Totais -->
              <div class="order-totals">
                <div class="total-row">
                  <span class="total-label">Subtotal:</span>
                  <span class="total-value">€{{ number_format($order->subtotal, 2, ',', '.') }}</span>
                </div>

                @if ($order->discount > 0)
                  <div class="total-row">
                    <span class="total-label">Desconto:</span>
                    <span class="total-value discount">-€{{ number_format($order->discount, 2, ',', '.') }}</span>
                  </div>
                @endif

                <div class="total-row">
                  <span class="total-label">Frete:</span>
                  <span class="total-value">Grátis</span>
                </div>

                <div class="total-divider"></div>

                <div class="total-row total-final">
                  <span class="total-label">Total:</span>
                  <span class="total-value">€{{ number_format($order->total, 2, ',', '.') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Informações de Entrega -->
        <div class="confirmation-details-section">
          <div class="confirmation-details">
            <div class="details-header">
              <h3 class="details-title">
                <ion-icon name="location-outline"></ion-icon>
                Endereço de Entrega
              </h3>
            </div>

            <div class="details-content">
              <div class="address-info">
                <p class="address-name">{{ $order->name }}</p>
                <p class="address-street">{{ $order->street }}, {{ $order->number }}</p>
                @if ($order->complement)
                  <p class="address-complement">{{ $order->complement }}</p>
                @endif
                <p class="address-neighborhood">{{ $order->neighborhood }}</p>
                <p class="address-city">{{ $order->city }}/{{ $order->state }}</p>
                <p class="address-zip">CEP: {{ $order->zip }}</p>
                <p class="address-country">{{ $order->country }}</p>
              </div>
            </div>
          </div>

          <!-- Informações de Contato -->
          <div class="confirmation-details">
            <div class="details-header">
              <h3 class="details-title">
                <ion-icon name="person-outline"></ion-icon>
                Informações de Contato
              </h3>
            </div>

            <div class="details-content">
              <div class="contact-info">
                <div class="contact-row">
                  <span class="contact-label">Nome:</span>
                  <span class="contact-value">{{ $order->name }}</span>
                </div>
                <div class="contact-row">
                  <span class="contact-label">E-mail:</span>
                  <span class="contact-value">{{ $order->email }}</span>
                </div>
                @if ($order->phone)
                  <div class="contact-row">
                    <span class="contact-label">Telefone:</span>
                    <span class="contact-value">{{ $order->phone }}</span>
                  </div>
                @endif
              </div>
            </div>
          </div>

          <!-- Próximos Passos -->
          <div class="confirmation-details">
            <div class="details-header">
              <h3 class="details-title">
                <ion-icon name="information-circle-outline"></ion-icon>
                Próximos Passos
              </h3>
            </div>

            <div class="details-content">
              <div class="next-steps">
                <div class="step-item">
                  <ion-icon name="mail-outline"></ion-icon>
                  <div class="step-content">
                    <span class="step-title">E-mail de Confirmação</span>
                    <span class="step-description">Você receberá um e-mail com os detalhes do pedido</span>
                  </div>
                </div>

                <div class="step-item">
                  <ion-icon name="card-outline"></ion-icon>
                  <div class="step-content">
                    <span class="step-title">Processamento do Pagamento</span>
                    <span class="step-description">Seu pagamento será processado em breve</span>
                  </div>
                </div>

                <div class="step-item">
                  <ion-icon name="car-outline"></ion-icon>
                  <div class="step-content">
                    <span class="step-title">Preparação e Envio</span>
                    <span class="step-description">Seu pedido será preparado e enviado</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Ações -->
          <div class="confirmation-actions">
            <a href="{{ route('shop.orders.show', $order) }}" class="btn btn-primary">
              <ion-icon name="eye-outline"></ion-icon>
              Ver Detalhes do Pedido
            </a>

            <a href="{{ route('shop.products.index') }}" class="btn btn-secondary">
              <ion-icon name="bag-outline"></ion-icon>
              Continuar Comprando
            </a>

            <a href="{{ route('home') }}" class="btn btn-outline">
              <ion-icon name="home-outline"></ion-icon>
              Voltar ao Início
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    // Limpar localStorage após confirmação do pedido
    document.addEventListener('DOMContentLoaded', function() {
      // Limpar carrinho do localStorage
      localStorage.removeItem('cart');
      localStorage.removeItem('appliedCoupon');

      // Disparar evento de atualização do carrinho
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: 0
        }
      }));
    });
  </script>
@endpush
