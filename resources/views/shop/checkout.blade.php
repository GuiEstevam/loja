@extends('layouts.app')

@section('title', 'Finalizar Compra')

@push('styles')
  @vite(['resources/css/checkout.css'])
@endpush

@section('content')
  <div class="checkout-page">
    <div class="checkout-container">
      <!-- Header da Página -->
      <div class="checkout-header">
        <h1 class="checkout-title">
          <ion-icon name="card-outline"></ion-icon>
          Finalizar Compra
        </h1>
        <p class="checkout-subtitle">Complete seus dados para finalizar o pedido</p>
      </div>

      <!-- Progresso do Checkout -->
      <div class="checkout-progress">
        <div class="progress-step completed">
          <ion-icon name="checkmark-circle"></ion-icon>
          <span>Carrinho</span>
        </div>
        <div class="progress-connector completed"></div>
        <div class="progress-step active">
          <ion-icon name="card-outline"></ion-icon>
          <span>Pagamento</span>
        </div>
        <div class="progress-connector"></div>
        <div class="progress-step">
          <ion-icon name="checkmark-outline"></ion-icon>
          <span>Confirmação</span>
        </div>
      </div>

      <!-- Layout Principal -->
      <div class="checkout-main">
        <!-- Formulário de Checkout -->
        <div class="checkout-form-section">
          <form action="{{ route('shop.checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="cart_data" id="cart-data-input">

            <!-- Informações Pessoais -->
            <div class="form-section">
              <div class="section-header">
                <ion-icon name="person-outline"></ion-icon>
                <h2>Informações Pessoais</h2>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="name">Nome Completo *</label>
                  <input type="text" name="name" id="name" required
                    value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Seu nome completo">
                </div>
                <div class="form-group">
                  <label for="email">E-mail *</label>
                  <input type="email" name="email" id="email" required
                    value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="seu@email.com">
                </div>
                <div class="form-group">
                  <label for="phone">Telefone *</label>
                  <input type="tel" name="phone" id="phone" required
                    value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="(11) 99999-9999">
                </div>
              </div>
            </div>

            <!-- Endereço de Entrega -->
            <div class="form-section">
              <div class="section-header">
                <ion-icon name="location-outline"></ion-icon>
                <h2>Endereço de Entrega</h2>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="country">País *</label>
                  <select name="country" id="country" required onchange="toggleStateField(this.value)">
                    <option value="">Selecione o país</option>
                    <option value="BR"
                      {{ old('country', $user->addresses->where('is_default', true)->first()->country ?? '') == 'BR' ? 'selected' : '' }}>
                      Brasil</option>
                    <option value="US"
                      {{ old('country', $user->addresses->where('is_default', true)->first()->country ?? '') == 'US' ? 'selected' : '' }}>
                      Estados Unidos</option>
                    <option value="PT"
                      {{ old('country', $user->addresses->where('is_default', true)->first()->country ?? '') == 'PT' ? 'selected' : '' }}>
                      Portugal</option>
                    <option value="AR"
                      {{ old('country', $user->addresses->where('is_default', true)->first()->country ?? '') == 'AR' ? 'selected' : '' }}>
                      Argentina</option>
                    <option value="other"
                      {{ old('country', $user->addresses->where('is_default', true)->first()->country ?? '') == 'other' ? 'selected' : '' }}>
                      Outro</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="zip">CEP/Código Postal *</label>
                  <input type="text" name="zip" id="zip" required
                    value="{{ old('zip', $user->addresses->where('is_default', true)->first()->zipcode ?? '') }}"
                    placeholder="00000-000">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="street">Rua *</label>
                  <input type="text" name="street" id="street" required
                    value="{{ old('street', $user->addresses->where('is_default', true)->first()->address_line1 ?? '') }}"
                    placeholder="Nome da rua">
                </div>
                <div class="form-group">
                  <label for="number">Número *</label>
                  <input type="text" name="number" id="number" required value="{{ old('number') }}"
                    placeholder="123">
                </div>
                <div class="form-group">
                  <label for="neighborhood">Bairro *</label>
                  <input type="text" name="neighborhood" id="neighborhood" required value="{{ old('neighborhood') }}"
                    placeholder="Nome do bairro">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="city">Cidade *</label>
                  <input type="text" name="city" id="city" required
                    value="{{ old('city', $user->addresses->where('is_default', true)->first()->city ?? '') }}"
                    placeholder="Nome da cidade">
                </div>
                <div class="form-group" id="state-select-br" style="display: none;">
                  <label for="state">Estado *</label>
                  <select name="state" id="state">
                    <option value="">Selecione o estado</option>
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>
                    <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>
                    <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>
                    <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>
                    <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>
                    <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>
                    <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>
                    <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option>
                    <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>
                    <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>
                    <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>
                  </select>
                </div>
                <div class="form-group" id="state-text-other" style="display: none;">
                  <label for="state_other">Estado/Província *</label>
                  <input type="text" name="state_other" id="state_other" value="{{ old('state_other') }}"
                    placeholder="Nome do estado/província">
                </div>
                <div class="form-group">
                  <label for="complement">Complemento</label>
                  <input type="text" name="complement" id="complement" value="{{ old('complement') }}"
                    placeholder="Apartamento, bloco, etc.">
                </div>
              </div>

              <div class="save-address-checkbox">
                <input type="checkbox" name="save_address" id="save_address" value="1"
                  {{ old('save_address') ? 'checked' : '' }}>
                <label for="save_address">Salvar este endereço na minha conta</label>
              </div>
            </div>

            <!-- Forma de Pagamento -->
            <div class="form-section">
              <div class="section-header">
                <ion-icon name="card-outline"></ion-icon>
                <h2>Forma de Pagamento</h2>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="payment_method">Método de Pagamento *</label>
                  <select name="payment_method" id="payment_method" required>
                    <option value="">Selecione a forma de pagamento</option>
                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Cartão de
                      Crédito</option>
                    <option value="debit_card" {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>Cartão de
                      Débito</option>
                    <option value="pix" {{ old('payment_method') == 'pix' ? 'selected' : '' }}>PIX</option>
                    <option value="boleto" {{ old('payment_method') == 'boleto' ? 'selected' : '' }}>Boleto Bancário
                    </option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="card_number">Número do Cartão *</label>
                  <input type="text" name="card_number" id="card_number" required value="{{ old('card_number') }}"
                    placeholder="0000 0000 0000 0000">
                </div>
                <div class="form-group">
                  <label for="card_name">Nome no Cartão *</label>
                  <input type="text" name="card_name" id="card_name" required value="{{ old('card_name') }}"
                    placeholder="Nome como está no cartão">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="card_expiry">Data de Validade *</label>
                  <input type="text" name="card_expiry" id="card_expiry" required value="{{ old('card_expiry') }}"
                    placeholder="MM/AA">
                </div>
                <div class="form-group">
                  <label for="card_cvv">CVV *</label>
                  <input type="text" name="card_cvv" id="card_cvv" required value="{{ old('card_cvv') }}"
                    placeholder="123">
                </div>
              </div>
            </div>

            <!-- Observações -->
            <div class="form-section">
              <div class="section-header">
                <ion-icon name="chatbubble-outline"></ion-icon>
                <h2>Observações</h2>
              </div>
              <div class="form-group full-width">
                <label for="notes">Observações do Pedido</label>
                <textarea name="notes" id="notes" rows="3" placeholder="Alguma observação especial para o pedido?">{{ old('notes') }}</textarea>
              </div>
            </div>

            <!-- Botões de Ação -->
            <div class="form-actions">
              <a href="{{ route('shop.cart.index') }}" class="btn btn-secondary">
                <ion-icon name="arrow-back-outline"></ion-icon>
                Voltar ao Carrinho
              </a>
              <button type="submit" class="btn btn-primary" id="confirm-btn">
                <ion-icon name="checkmark-outline"></ion-icon>
                Finalizar Compra
              </button>
            </div>

            <!-- Mensagens de Erro -->
            @if ($errors->any())
              <div class="form-errors" id="error-modal">
                <button type="button" class="close-btn" onclick="closeErrorModal()">
                  <ion-icon name="close-outline"></ion-icon>
                </button>
                <h4>Erros encontrados:</h4>
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
          </form>
        </div>

        <!-- Resumo do Pedido -->
        <div class="checkout-summary-section">
          <div class="summary-card">
            <div class="summary-header">
              <ion-icon name="bag-outline"></ion-icon>
              <h2>Resumo do Pedido</h2>
            </div>

            <div class="summary-content">
              <div class="summary-items" id="checkout-items">
                <!-- Os itens serão carregados via JavaScript -->
                <div class="summary-loading">
                  <ion-icon name="refresh-outline"></ion-icon>
                  <span>Carregando itens do carrinho...</span>
                </div>
              </div>

              <div class="summary-totals">
                <div class="summary-row">
                  <span class="summary-label">Subtotal</span>
                  <span class="summary-value" id="checkout-subtotal">€0,00</span>
                </div>
                <div class="summary-row">
                  <span class="summary-label">Frete</span>
                  <span class="summary-value">Grátis</span>
                </div>
                <div class="summary-row total">
                  <span class="summary-label">Total</span>
                  <span class="summary-value" id="checkout-total">€0,00</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Função para carregar itens do carrinho
    function loadCartItems() {
      const cart = JSON.parse(localStorage.getItem('cart') || '{}');
      const itemsContainer = document.getElementById('checkout-items');
      const subtotalElement = document.getElementById('checkout-subtotal');
      const totalElement = document.getElementById('checkout-total');

      itemsContainer.innerHTML = ''; // Limpar container

      let total = 0;

      // Verificar se há itens no carrinho
      if (Object.keys(cart).length === 0) {
        itemsContainer.innerHTML = `
                <div class="summary-empty">
                    <ion-icon name="bag-outline"></ion-icon>
                    <p>Seu carrinho está vazio</p>
                    <a href="{{ route('shop.cart.index') }}">
                        Voltar ao carrinho
                    </a>
                </div>
            `;
        return;
      }

      // Renderizar cada item
      Object.values(cart).forEach(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        // Verificar se a imagem existe e construir URL correta
        let imageSrc = '{{ asset('images/placeholder.svg') }}';
        if (item.image && item.image !== 'undefined' && item.image !== 'null' && item.image !== '' && item.image !==
          'null') {
          // A imagem já vem com o path completo do localStorage
          imageSrc = item.image;
        }

        // Extrair informações de variação
        let variationInfo = '';
        if (item.colorName || item.size) {
          variationInfo = `
            <div class="summary-item-variations">
              ${item.colorName ? `<span class="summary-item-variation summary-item-color">Cor: ${item.colorName}</span>` : ''}
              ${item.size ? `<span class="summary-item-variation summary-item-size">Tamanho: ${item.size}</span>` : ''}
            </div>
          `;
        }

        const itemHtml = `
           <div class="summary-item">
             <div class="summary-item-image">
               <img src="${imageSrc}" alt="${item.name}"
                 onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<ion-icon name=\\"image-outline\\" style=\\"font-size: 1.5rem; color: var(--checkout-text-secondary);\\"></ion-icon>
             </div>
             <div class="summary-item-details">
               <h3 class="summary-item-name">${item.name}</h3>
               ${variationInfo}
               <div class="summary-item-meta">
                 <span>Quantidade: <strong>${item.quantity}</strong></span>
                 <span>Preço: <strong>€${item.price.toFixed(2).replace('.', ',')}</strong></span>
               </div>
               <div class="summary-item-price">
                 Subtotal: €${subtotal.toFixed(2).replace('.', ',')}
               </div>
             </div>
           </div>
         `;

        itemsContainer.innerHTML += itemHtml;
      });

      // Atualizar totais em euro com formato correto
      const formattedTotal = total.toFixed(2).replace('.', ',');
      subtotalElement.textContent = `€${formattedTotal}`;
      totalElement.textContent = `€${formattedTotal}`;
    }

    // Função para alternar campos de estado
    function toggleStateField(country) {
      const stateSelectBr = document.getElementById('state-select-br');
      const stateTextOther = document.getElementById('state-text-other');
      const stateSelect = document.getElementById('state');
      const stateOther = document.getElementById('state_other');

      // Ocultar ambos os campos
      stateSelectBr.style.display = 'none';
      stateTextOther.style.display = 'none';
      stateSelect.removeAttribute('required');
      stateOther.removeAttribute('required');

      // Mostrar o campo apropriado baseado no país
      if (country === 'BR') {
        stateSelectBr.style.display = 'block';
        stateSelect.setAttribute('required', 'required');
      } else if (country === 'other' || country === 'US' || country === 'PT' || country === 'AR') {
        stateTextOther.style.display = 'block';
        stateOther.setAttribute('required', 'required');
      }
    }

    // Inicialização quando a página carrega
    document.addEventListener('DOMContentLoaded', function() {
      // Carregar itens do carrinho
      loadCartItems();

      // Carregar dados persistentes do usuário
      loadPersistentData();

      // Configurar campo de estado baseado no país selecionado
      const countrySelect = document.getElementById('country');
      if (countrySelect.value) {
        toggleStateField(countrySelect.value);
      }

      // Adicionar listener para mudanças no país
      countrySelect.addEventListener('change', function() {
        toggleStateField(this.value);
      });

      // Configurar validação em tempo real
      const form = document.getElementById('checkout-form');
      const confirmBtn = document.getElementById('confirm-btn');

      // Adicionar validação em tempo real para todos os campos obrigatórios
      const requiredFields = form.querySelectorAll('[required]');
      requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
          if (this.value.trim() === '') {
            this.classList.add('error');
          } else {
            this.classList.remove('error');
          }
        });
      });

      // Feedback visual durante o envio do formulário
      form.addEventListener('submit', function(e) {
        // Enviar dados do localStorage
        const cartData = localStorage.getItem('cart') || '{}';
        document.getElementById('cart-data-input').value = cartData;

        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = `
                <ion-icon name="refresh-outline" class="spinning"></ion-icon>
                Processando...
            `;
        confirmBtn.disabled = true;

        // Re-habilitar após 5 segundos caso haja erro
        setTimeout(() => {
          confirmBtn.innerHTML = originalText;
          confirmBtn.disabled = false;
        }, 5000);
      });
    });

    // Função para carregar dados persistentes
    function loadPersistentData() {
      // Se não há dados persistentes, não fazer nada
      const defaultAddress = @json($defaultAddress);
      if (!defaultAddress) return;

      // Preencher campos se estiverem vazios
      const fields = {
        'phone': defaultAddress.phone,
        'zip': defaultAddress.zipcode,
        'street': defaultAddress.address_line1,
        'city': defaultAddress.city,
        'state': defaultAddress.state,
        'country': defaultAddress.country
      };

      Object.keys(fields).forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && !field.value && fields[fieldName]) {
          field.value = fields[fieldName];
        }
      });

      // Configurar estado se país foi carregado
      const countrySelect = document.getElementById('country');
      if (countrySelect.value) {
        toggleStateField(countrySelect.value);
      }
    }

    // Função para fechar modal de erro
    function closeErrorModal() {
      const errorModal = document.getElementById('error-modal');
      if (errorModal) {
        errorModal.style.display = 'none';
      }
    }

    // Fechar modal ao clicar fora dele
    document.addEventListener('click', function(e) {
      const errorModal = document.getElementById('error-modal');
      if (errorModal && e.target === errorModal) {
        closeErrorModal();
      }
    });
  </script>
@endsection
