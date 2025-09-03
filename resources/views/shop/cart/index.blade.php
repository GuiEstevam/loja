@extends('layouts.app')

@section('title', 'Carrinho de Compras')

@push('styles')
  @vite(['resources/css/cart.css'])
@endpush

@section('content')
  <div class="cart-container">
    <div class="cart-header">
      <h1 class="cart-title">Seu Carrinho</h1>
      <p class="cart-subtitle">Revise seus itens antes de finalizar a compra</p>
    </div>

    @if (session('success'))
      <div class="cart-success">
        <ion-icon name="checkmark-circle-outline"></ion-icon>
        {{ session('success') }}
      </div>
    @endif

    <div class="cart-content" id="cart-content">
      <!-- Conteúdo será carregado via JavaScript -->
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    // Função para carregar o carrinho
    function loadCart() {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      const cartContent = document.getElementById('cart-content');

      if (cart.length === 0) {
        cartContent.innerHTML = `
          <div class="cart-empty">
            <ion-icon name="bag-outline" class="cart-empty-icon"></ion-icon>
            <h3 class="cart-empty-title">Seu carrinho está vazio</h3>
            <p class="cart-empty-text">Adicione alguns produtos para começar suas compras!</p>
            <a href="/produtos" class="cart-empty-btn">
              <ion-icon name="arrow-back-outline"></ion-icon>
              Continuar Comprando
            </a>
          </div>
        `;
        return;
      }

      let total = 0;
      let html = `
        <div class="cart-items">
          <div class="cart-items-header">
            <h3 class="cart-items-title">Itens no Carrinho (${cart.length})</h3>
          </div>
          <div class="cart-items-list">
      `;

      cart.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        html += `
          <div class="cart-item" data-product-id="${item.id}">
            <div class="cart-item-image">
              <img src="${item.image}" alt="${item.name}" class="item-image">
            </div>
            <div class="cart-item-info">
              <h4 class="item-name">${item.name}</h4>
              <div class="item-details">
                <span class="item-price">€${item.price.toFixed(2).replace('.', ',')}</span>
              </div>
            </div>
            <div class="cart-item-quantity">
              <div class="quantity-controls">
                <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity - 1})" ${item.quantity <= 1 ? 'disabled' : ''}>
                  <ion-icon name="remove-outline"></ion-icon>
                </button>
                <span class="quantity-value">${item.quantity}</span>
                <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">
                  <ion-icon name="add-outline"></ion-icon>
                </button>
              </div>
            </div>
            <div class="cart-item-subtotal">
              <span class="subtotal-value">€${subtotal.toFixed(2).replace('.', ',')}</span>
            </div>
            <div class="cart-item-actions">
              <button class="remove-btn" onclick="removeFromCart(${item.id})" title="Remover item">
                <ion-icon name="trash-outline"></ion-icon>
              </button>
            </div>
          </div>
        `;
      });

      html += `
          </div>
        </div>
        <div class="cart-summary">
          <div class="summary-header">
            <h3 class="summary-title">Resumo da Compra</h3>
          </div>
          <div class="summary-content">
            <div class="summary-row">
              <span class="summary-label">Subtotal (${cart.length} itens)</span>
              <span class="summary-value">€${total.toFixed(2).replace('.', ',')}</span>
            </div>
            <div class="summary-row">
              <span class="summary-label">Frete</span>
              <span class="summary-value">Grátis</span>
            </div>
            <div class="summary-divider"></div>
            <div class="summary-row summary-total">
              <span class="summary-label">Total</span>
              <span class="summary-value">€${total.toFixed(2).replace('.', ',')}</span>
            </div>
          </div>
          <div class="summary-actions">
            <button class="checkout-btn" onclick="proceedToCheckout()">
              <ion-icon name="card-outline"></ion-icon>
              Finalizar Compra
            </button>
            <a href="/produtos" class="continue-shopping-btn">
              <ion-icon name="arrow-back-outline"></ion-icon>
              Continuar Comprando
            </a>
          </div>
        </div>
      `;

      cartContent.innerHTML = html;
    }

    // Função para atualizar quantidade
    function updateQuantity(productId, newQuantity) {
      if (newQuantity < 1) return;

      let cart = JSON.parse(localStorage.getItem('cart') || '[]');
      const itemIndex = cart.findIndex(item => item.id === productId);

      if (itemIndex !== -1) {
        cart[itemIndex].quantity = newQuantity;
        localStorage.setItem('cart', JSON.stringify(cart));

        // Atualizar display
        loadCart();

        // Sincronizar com navbar
        window.dispatchEvent(new CustomEvent('cartUpdated', {
          detail: {
            totalItems: cart.reduce((sum, item) => sum + item.quantity, 0)
          }
        }));

        // Mostrar feedback
        showCartFeedback('Quantidade atualizada!');
      }
    }

    // Função para remover item
    function removeFromCart(productId) {
      let cart = JSON.parse(localStorage.getItem('cart') || '[]');
      cart = cart.filter(item => item.id !== productId);
      localStorage.setItem('cart', JSON.stringify(cart));

      // Atualizar display
      loadCart();

      // Sincronizar com navbar
      window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: {
          totalItems: cart.reduce((sum, item) => sum + item.quantity, 0)
        }
      }));

      // Mostrar feedback
      showCartFeedback('Item removido do carrinho!');
    }

    // Função para finalizar compra
    function proceedToCheckout() {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      if (cart.length === 0) {
        showCartFeedback('Carrinho vazio!', 'error');
        return;
      }

      // Redirecionar para checkout
      window.location.href = '{{ route('shop.checkout') }}';
    }

    // Função para mostrar feedback
    function showCartFeedback(message, type = 'success') {
      const toast = document.createElement('div');
      toast.className = `cart-toast cart-toast--${type}`;
      toast.innerHTML = `
        <ion-icon name="${type === 'success' ? 'checkmark-circle-outline' : 'alert-circle-outline'}"></ion-icon>
        <span>${message}</span>
      `;
      document.body.appendChild(toast);

      setTimeout(() => {
        toast.remove();
      }, 3000);
    }

    // Carregar carrinho ao inicializar
    document.addEventListener('DOMContentLoaded', function() {
      loadCart();
    });

    // Listener para mudanças no carrinho
    window.addEventListener('cartUpdated', function() {
      loadCart();
    });
  </script>
@endpush
