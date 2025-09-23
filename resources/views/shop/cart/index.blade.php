@extends('layouts.app')

@section('title', 'Carrinho de Compras')

@section('content')
  <div class="cart-page">
    <!-- Page Header -->
    <div class="cart-page-header">
      <div class="container">
        <div class="cart-header-content">
          <h1 class="cart-page-title">
            <ion-icon name="cart-outline"></ion-icon>
            Seu Carrinho
          </h1>
          <p class="cart-page-subtitle">Revise seus itens antes de finalizar a compra</p>
        </div>
      </div>
    </div>

    <!-- Progresso do Carrinho -->
    <div class="cart-progress">
      <div class="progress-step active">
        <ion-icon name="cart-outline"></ion-icon>
        <span>Carrinho</span>
      </div>
      <div class="progress-connector"></div>
      <div class="progress-step">
        <ion-icon name="card-outline"></ion-icon>
        <span>Pagamento</span>
      </div>
      <div class="progress-connector"></div>
      <div class="progress-step">
        <ion-icon name="checkmark-outline"></ion-icon>
        <span>Confirmação</span>
      </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
      <div class="container">
        <div class="cart-success-message">
          <ion-icon name="checkmark-circle-outline"></ion-icon>
          <span>{{ session('success') }}</span>
        </div>
      </div>
    @endif

    <!-- Main Content -->
    <div class="cart-page-content">
      <div class="container">
        <!-- Loading State -->
        <div class="cart-loading" id="cart-loading">
          <div class="loading-spinner">
            <ion-icon name="refresh-outline"></ion-icon>
          </div>
          <p>Carregando carrinho...</p>
        </div>

        <!-- Cart Content -->
        <div class="cart-content" id="cart-content">
          <!-- Content will be loaded via JavaScript -->
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    class CartPage {
      constructor() {
        this.cartContent = document.getElementById('cart-content');
        this.cartLoading = document.getElementById('cart-loading');
        this.init();
      }

      init() {
        this.loadCart();
        this.setupEventListeners();
      }

      setupEventListeners() {
        window.addEventListener('cartUpdated', () => {
          this.loadCart();
        });

        window.addEventListener('storage', (event) => {
          if (event.key === 'cart') {
            this.loadCart();
          }
        });
      }

      async loadCart() {
        this.showLoading();
        await this.delay(300);

        const cart = JSON.parse(localStorage.getItem('cart') || '{}');
        const cartItems = Object.values(cart);

        // Limpar itens inválidos do carrinho
        this.cleanInvalidCartItems(cart);

        if (cartItems.length === 0) {
          this.renderEmptyCart();
        } else {
          this.renderCart(cartItems);
        }

        this.hideLoading();
      }

      cleanInvalidCartItems(cart) {
        let hasInvalidItems = false;
        const cleanedCart = {};

        Object.keys(cart).forEach(key => {
          const item = cart[key];
          
          // Verificar se o item tem as propriedades necessárias
          if (!item || typeof item !== 'object') {
            hasInvalidItems = true;
            console.warn('Item inválido removido do carrinho (não é objeto):', item);
            return;
          }

          // Converter e validar preço
          const price = parseFloat(item.price);
          const quantity = parseInt(item.quantity);

          // Verificar se o item tem propriedades essenciais
          const hasValidPrice = !isNaN(price) && price > 0;
          const hasValidQuantity = !isNaN(quantity) && quantity > 0;
          const hasValidId = item.id && !isNaN(parseInt(item.id));
          const hasValidName = item.name && typeof item.name === 'string' && item.name.trim() !== '';

          // Manter apenas itens válidos
          if (hasValidPrice && hasValidQuantity && hasValidId && hasValidName) {
            // Garantir que price e quantity sejam números
            cleanedCart[key] = {
              ...item,
              price: price,
              quantity: quantity,
              name: item.name.trim()
            };
          } else {
            hasInvalidItems = true;
            console.warn('Item inválido removido do carrinho:', {
              item,
              priceValid: hasValidPrice,
              quantityValid: hasValidQuantity,
              idValid: hasValidId,
              nameValid: hasValidName
            });
          }
        });

        // Atualizar localStorage se houve itens inválidos
        if (hasInvalidItems) {
          localStorage.setItem('cart', JSON.stringify(cleanedCart));
          this.showToast('Itens inválidos foram removidos do carrinho', 'warning');
          
          // Forçar recarregamento do navbar também
          window.dispatchEvent(new CustomEvent('cartUpdated', {
            detail: {
              totalItems: Object.values(cleanedCart).reduce((sum, item) => sum + item.quantity, 0)
            }
          }));
        }
      }

      renderEmptyCart() {
        this.cartContent.innerHTML = `
      <div class="cart-empty-state">
        <div class="empty-cart-icon">
          <ion-icon name="bag-outline"></ion-icon>
        </div>
        <h2 class="empty-cart-title">Seu carrinho está vazio</h2>
        <p class="empty-cart-text">Adicione alguns produtos para começar suas compras!</p>
        <div class="empty-cart-actions">
          <a href="{{ route('shop.products.index') }}" class="btn btn-primary">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Continuar Comprando
          </a>
        </div>
      </div>
    `;
      }

      renderCart(cartItems) {
        // Filtrar itens válidos e calcular totais de forma segura
        const validItems = cartItems.filter(item => {
          const price = parseFloat(item.price);
          const quantity = parseInt(item.quantity);
          return !isNaN(price) && !isNaN(quantity) && price > 0 && quantity > 0;
        });

        const total = validItems.reduce((sum, item) => {
          const price = parseFloat(item.price) || 0;
          const quantity = parseInt(item.quantity) || 1;
          return sum + (price * quantity);
        }, 0);
        
        const itemCount = validItems.reduce((sum, item) => {
          const quantity = parseInt(item.quantity) || 1;
          return sum + quantity;
        }, 0);

        this.cartContent.innerHTML = `
      <div class="cart-layout">
        <!-- Cart Items -->
        <div class="cart-items-section">
          <div class="cart-items-header">
            <h2 class="cart-items-title">
              <ion-icon name="list-outline"></ion-icon>
              Itens no Carrinho
            </h2>
            <div class="cart-items-actions">
              <span class="cart-items-count">${validItems.length} ${validItems.length === 1 ? 'item' : 'itens'}</span>
              ${validItems.length > 0 ? `
                <button class="btn btn-danger btn-sm" onclick="cartPage.clearCart()" title="Limpar carrinho">
                  <ion-icon name="trash-outline"></ion-icon>
                  Limpar
                </button>
              ` : ''}
            </div>
          </div>

                    <div class="cart-items-list">
            ${validItems.map(item => this.renderCartItem(item)).join('')}
          </div>

          <!-- Opções Adicionais -->
          <div class="cart-options-section">
            <div class="options-header">
              <h3 class="options-title">
                <ion-icon name="options-outline"></ion-icon>
                Opções Adicionais
              </h3>
            </div>

            <div class="options-content">
              <div class="option-item">
                <div class="option-info">
                  <ion-icon name="shield-checkmark-outline"></ion-icon>
                  <div class="option-details">
                    <span class="option-name">Proteção do Produto</span>
                    <span class="option-description">Garantia estendida + seguro</span>
                  </div>
                </div>
                <div class="option-price">+€2,99</div>
              </div>

              <div class="option-item">
                <div class="option-info">
                  <ion-icon name="flash-outline"></ion-icon>
                  <div class="option-details">
                    <span class="option-name">Entrega Expressa</span>
                    <span class="option-description">Receba em 24h</span>
                  </div>
                </div>
                <div class="option-price">+€5,99</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary-section">
          <div class="cart-summary">
            <div class="summary-header">
              <h3 class="summary-title">
                <ion-icon name="calculator-outline"></ion-icon>
                Resumo da Compra
              </h3>
            </div>

            <div class="summary-content">
              <div class="summary-row">
                <span class="summary-label">Subtotal (${itemCount} ${itemCount === 1 ? 'item' : 'itens'})</span>
                <span class="summary-value">€${total.toFixed(2).replace('.', ',')}</span>
              </div>

              <!-- Cupom de Desconto -->
              <div class="summary-coupon-section">
                <div class="coupon-header">
                  <h4 class="coupon-title">
                    <ion-icon name="gift-outline"></ion-icon>
                    Cupom de Desconto
                  </h4>
                </div>

                <div class="coupon-form">
                  <div class="coupon-input-group">
                    <input type="text" id="coupon-code" class="coupon-input" placeholder="Digite seu cupom" maxlength="20">
                    <button type="button" class="coupon-btn" onclick="cartPage.applyCoupon()">
                      <ion-icon name="checkmark-outline"></ion-icon>
                      Aplicar
                    </button>
                  </div>
                  <div class="coupon-message" id="coupon-message"></div>
                </div>
              </div>

              <div class="summary-row" id="coupon-discount" style="display: none;">
                <span class="summary-label">Desconto Cupom</span>
                <span class="summary-value summary-discount">-€0,00</span>
              </div>

              <div class="summary-row">
                <span class="summary-label">Frete</span>
                <span class="summary-value summary-free">Grátis</span>
              </div>

              <div class="summary-divider"></div>

              <div class="summary-row summary-total">
                <span class="summary-label">Total</span>
                <span class="summary-value" id="cart-total">€${total.toFixed(2).replace('.', ',')}</span>
              </div>
            </div>

            <div class="summary-actions">
              <button class="btn btn-primary btn-checkout" onclick="cartPage.proceedToCheckout()">
                <ion-icon name="card-outline"></ion-icon>
                Finalizar Compra
              </button>

              <a href="{{ route('shop.products.index') }}" class="btn btn-secondary">
                <ion-icon name="arrow-back-outline"></ion-icon>
                Continuar Comprando
              </a>
            </div>
          </div>
        </div>
      </div>
             `;
      }

      renderCartItem(item) {
        // Validar e converter preço para número
        const price = parseFloat(item.price) || 0;
        const quantity = parseInt(item.quantity) || 1;
        const subtotal = price * quantity;

        // Extrair informações de variação da chave do item
        let variationInfo = '';
        if (item.colorName || item.size) {
          variationInfo = `
            <div class="item-variations">
              ${item.colorName ? `<span class="item-variation item-color">Cor: ${item.colorName}</span>` : ''}
              ${item.size ? `<span class="item-variation item-size">Tamanho: ${item.size}</span>` : ''}
            </div>
          `;
        }

        return `
      <div class="cart-item" data-product-id="${item.id}">
        <div class="cart-item-image">
          <img src="${item.image || '/images/default-product.jpg'}" alt="${item.name || 'Produto'}" class="item-image">
        </div>

        <div class="cart-item-details">
          <div class="cart-item-info">
            <h4 class="item-name">${item.name || 'Produto sem nome'}</h4>
            ${variationInfo}
            <div class="item-price">€${price.toFixed(2).replace('.', ',')}</div>
          </div>

          <div class="cart-item-controls">
            <div class="quantity-controls">
              <button class="quantity-btn quantity-btn--decrease"
                onclick="cartPage.updateQuantity(${item.id}, ${quantity - 1})"
                ${quantity <= 1 ? 'disabled' : ''}>
                <ion-icon name="remove-outline"></ion-icon>
              </button>

              <span class="quantity-value">${quantity}</span>

              <button class="quantity-btn quantity-btn--increase"
                onclick="cartPage.updateQuantity(${item.id}, ${quantity + 1})">
                <ion-icon name="add-outline"></ion-icon>
              </button>
            </div>

            <div class="cart-item-subtotal">
              <span class="subtotal-value">€${subtotal.toFixed(2).replace('.', ',')}</span>
            </div>

            <button class="remove-btn" onclick="cartPage.removeFromCart(${item.id})" title="Remover item">
              <ion-icon name="trash-outline"></ion-icon>
            </button>
          </div>
        </div>
      </div>
    `;
      }

      async updateQuantity(productId, newQuantity) {
        if (newQuantity < 1) return;

        let cart = JSON.parse(localStorage.getItem('cart') || '{}');

        // Encontrar a chave correta do item (pode ser produto-id, produto-id-c1, produto-id-c1-s2, etc.)
        const cartKey = Object.keys(cart).find(key => key.startsWith(productId.toString()));

        if (cartKey) {
          cart[cartKey].quantity = newQuantity;
          localStorage.setItem('cart', JSON.stringify(cart));

          await this.loadCart();
          this.dispatchCartUpdate(cart);
          this.showToast('Quantidade atualizada!', 'success');
        }
      }

      async removeFromCart(productId) {
        let cart = JSON.parse(localStorage.getItem('cart') || '{}');

        // Encontrar a chave correta do item (pode ser produto-id, produto-id-c1, produto-id-c1-s2, etc.)
        const cartKey = Object.keys(cart).find(key => key.startsWith(productId.toString()));

        if (cartKey) {
          delete cart[cartKey];
          localStorage.setItem('cart', JSON.stringify(cart));

          await this.loadCart();
          this.dispatchCartUpdate(cart);
          this.showToast('Item removido do carrinho!', 'success');
        }
      }

      clearCart() {
        localStorage.removeItem('cart');
        localStorage.removeItem('appliedCoupon');
        this.loadCart();
        this.dispatchCartUpdate({});
        this.showToast('Carrinho limpo!', 'success');
      }

      proceedToCheckout() {
        const cart = JSON.parse(localStorage.getItem('cart') || '{}');
        const cartItems = Object.values(cart);

        if (cartItems.length === 0) {
          this.showToast('Carrinho vazio!', 'error');
          return;
        }

        window.location.href = '{{ route('shop.checkout') }}';
      }

      applyCoupon() {
        const couponCode = document.getElementById('coupon-code').value.trim().toUpperCase();
        const couponMessage = document.getElementById('coupon-message');

        if (!couponCode) {
          this.showCouponMessage('Digite um código de cupom', 'error');
          return;
        }

        // Simular validação de cupom
        const validCoupons = {
          'DESCONTO10': {
            discount: 10,
            type: 'percentage'
          },
          'FREEGRATIS': {
            discount: 5,
            type: 'fixed'
          },
          'PROMO20': {
            discount: 20,
            type: 'percentage'
          }
        };

        if (validCoupons[couponCode]) {
          const coupon = validCoupons[couponCode];
          localStorage.setItem('appliedCoupon', JSON.stringify({
            code: couponCode,
            ...coupon
          }));
          this.showCouponMessage(
            `Cupom aplicado! Desconto de ${coupon.discount}${coupon.type === 'percentage' ? '%' : '€'}`, 'success');
          this.updateCartWithCoupon();
        } else {
          this.showCouponMessage('Cupom inválido ou expirado', 'error');
        }
      }

      showCouponMessage(message, type) {
        const couponMessage = document.getElementById('coupon-message');
        couponMessage.innerHTML = `
          <div class="coupon-message-${type}">
            <ion-icon name="${type === 'success' ? 'checkmark-circle-outline' : 'alert-circle-outline'}"></ion-icon>
            <span>${message}</span>
          </div>
        `;

        setTimeout(() => {
          couponMessage.innerHTML = '';
        }, 5000);
      }

      updateCartWithCoupon() {
        const appliedCoupon = JSON.parse(localStorage.getItem('appliedCoupon') || 'null');
        if (!appliedCoupon) return;

        const cart = JSON.parse(localStorage.getItem('cart') || '{}');
        const cartItems = Object.values(cart);
        
        // Calcular subtotal de forma segura
        const subtotal = cartItems.reduce((sum, item) => {
          const price = parseFloat(item.price) || 0;
          const quantity = parseInt(item.quantity) || 1;
          return sum + (price * quantity);
        }, 0);

        let discount = 0;
        if (appliedCoupon.type === 'percentage') {
          discount = (subtotal * appliedCoupon.discount) / 100;
        } else {
          discount = appliedCoupon.discount;
        }

        const discountElement = document.getElementById('coupon-discount');
        const totalElement = document.getElementById('cart-total');

        if (discountElement && totalElement) {
          discountElement.style.display = 'flex';
          discountElement.querySelector('.summary-discount').textContent = `-€${discount.toFixed(2).replace('.', ',')}`;

          const finalTotal = subtotal - discount;
          totalElement.textContent = `€${finalTotal.toFixed(2).replace('.', ',')}`;
        }
      }

      dispatchCartUpdate(cart) {
        window.dispatchEvent(new CustomEvent('cartUpdated', {
          detail: {
            totalItems: Object.values(cart).reduce((sum, item) => sum + item.quantity, 0)
          }
        }));
      }

      showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `cart-page-toast cart-page-toast--${type}`;
        
        let iconName = 'checkmark-circle-outline';
        if (type === 'error') iconName = 'alert-circle-outline';
        if (type === 'warning') iconName = 'warning-outline';
        
        toast.innerHTML = `
        <ion-icon name="${iconName}"></ion-icon>
        <span>${message}</span>
      `;

        document.body.appendChild(toast);

        setTimeout(() => {
          toast.remove();
        }, 3000);
      }

      showLoading() {
        this.cartLoading.style.display = 'flex';
        this.cartContent.style.display = 'none';
      }

      hideLoading() {
        this.cartLoading.style.display = 'none';
        this.cartContent.style.display = 'block';
      }

      delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
      }
    }

    // Initialize Cart Page
    let cartPage;
    document.addEventListener('DOMContentLoaded', function() {
      cartPage = new CartPage();
    });
  </script>

  <style>
    /* Toast de Warning */
    .cart-page-toast--warning {
      background: #fef3c7;
      color: #d97706;
      border: 1px solid #fde68a;
    }
    
    .cart-page-toast--warning ion-icon {
      color: #d97706;
    }

    /* Botão de Limpar Carrinho */
    .cart-items-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .btn-danger {
      background: #dc2626;
      color: white;
      border: 1px solid #dc2626;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      font-size: 0.875rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-danger:hover {
      background: #b91c1c;
      border-color: #b91c1c;
    }

    .btn-danger:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.75rem;
    }

  </style>
@endpush
