@extends('layouts.app')

@section('title', 'Detalhes do Pedido')

@section('content')
  <div class="admin-page">
    <div class="admin-content">
      <div class="admin-container">
        <div class="admin-card">
          <!-- Header -->
          <div class="admin-card-header">
            <nav class="admin-breadcrumb">
              <a href="{{ route('admin.dashboard') }}">
                <ion-icon name="home-outline"></ion-icon>
                Dashboard
              </a>
              <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
              <a href="{{ route('admin.orders.index') }}">Pedidos</a>
              <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
              <span>Pedido #{{ $order->id }}</span>
            </nav>

            <h1 class="admin-card-title">
              <ion-icon name="receipt-outline"></ion-icon>
              Detalhes do Pedido #{{ $order->id }}
            </h1>
            <p class="admin-card-subtitle">Visualize e gerencie os detalhes completos do pedido</p>
          </div>

          <!-- Sistema de Abas -->
          <div class="admin-card-body">
            <div class="admin-tabs">
              <!-- Navegação das Abas -->
              <div class="admin-tabs-nav">
                <button class="admin-tab-btn active" data-tab="overview">
                  <ion-icon name="eye-outline"></ion-icon>
                  Visão Geral
                </button>
                <button class="admin-tab-btn" data-tab="customer">
                  <ion-icon name="person-outline"></ion-icon>
                  Cliente
                </button>
                <button class="admin-tab-btn" data-tab="items">
                  <ion-icon name="cart-outline"></ion-icon>
                  Itens
                </button>
                <button class="admin-tab-btn" data-tab="financial">
                  <ion-icon name="wallet-outline"></ion-icon>
                  Financeiro
                </button>
              </div>

              <!-- Conteúdo das Abas -->
              <div class="admin-tabs-content">
                <!-- Aba: Visão Geral -->
                <div class="admin-tab-panel active" id="overview">
                  <div class="admin-overview-grid">
                    <!-- Status e Informações Principais -->
                    <div class="admin-overview-section">
                      <h3 class="admin-section-title">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        Informações Principais
                      </h3>

                      <div class="admin-status-dropdown">
                        <button type="button" class="admin-status-badge admin-status-{{ $order->status }}"
                          data-order-id="{{ $order->id }}" data-current-status="{{ $order->status }}">
                          {{ order_status_label($order->status) }}
                          <ion-icon name="chevron-down-outline"></ion-icon>
                        </button>
                        <div class="admin-status-options">
                          @foreach (['pending' => 'Aguardando', 'paid' => 'Pago', 'shipped' => 'Enviado', 'delivered' => 'Entregue', 'canceled' => 'Cancelado'] as $value => $label)
                            <button type="button" class="admin-status-option"
                              data-status="{{ $value }}">{{ $label }}</button>
                          @endforeach
                        </div>
                      </div>

                      <div class="admin-overview-info">
                        <div class="admin-overview-item">
                          <span class="admin-overview-label">Pedido</span>
                          <span
                            class="admin-overview-value admin-order-number">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="admin-overview-item">
                          <span class="admin-overview-label">Data</span>
                          <span class="admin-overview-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="admin-overview-item">
                          <span class="admin-overview-label">Pagamento</span>
                          <span
                            class="admin-overview-value admin-payment-method">{{ translatePaymentMethod($order->payment_method ?? 'credit_card') }}</span>
                        </div>
                        @if ($order->tracking_code)
                          <div class="admin-overview-item">
                            <span class="admin-overview-label">Rastreio</span>
                            <span class="admin-overview-value admin-tracking-code">{{ $order->tracking_code }}</span>
                          </div>
                        @endif
                      </div>
                    </div>

                    <!-- Cliente -->
                    <div class="admin-overview-section">
                      <h3 class="admin-section-title">
                        <ion-icon name="person-outline"></ion-icon>
                        Cliente
                      </h3>

                      <div class="admin-customer-overview">
                        <div class="admin-customer-profile">
                          <div class="admin-customer-avatar">
                            {{ strtoupper(mb_substr($order->user->name ?? $order->name, 0, 2)) }}
                          </div>
                          <div class="admin-customer-info">
                            <div class="admin-customer-name">{{ $order->user->name ?? $order->name }}</div>
                            <div class="admin-customer-email">{{ $order->user->email ?? $order->email }}</div>
                          </div>
                        </div>
                        <div class="admin-customer-phone">{{ $order->phone ?? 'Telefone não informado' }}</div>
                      </div>
                    </div>

                    <!-- Resumo dos Itens -->
                    <div class="admin-overview-section">
                      <h3 class="admin-section-title">
                        <ion-icon name="cart-outline"></ion-icon>
                        Itens do Pedido
                      </h3>

                      <div class="admin-items-overview">
                        <div class="admin-items-count">
                          <span class="admin-items-number">{{ $order->items->count() }}</span>
                          <span class="admin-items-label">{{ $order->items->count() == 1 ? 'item' : 'itens' }}</span>
                        </div>
                        <div class="admin-items-preview">
                          @foreach ($order->items->take(3) as $item)
                            <div class="admin-item-preview">
                              <img src="{{ asset('products/' . ($item->product->image ?? '')) }}"
                                alt="{{ $item->product->name ?? $item->name }}"
                                onerror="this.src='{{ asset('images/placeholder.png') }}'">
                              <span class="admin-item-quantity">{{ $item->quantity }}</span>
                            </div>
                          @endforeach
                          @if ($order->items->count() > 3)
                            <div class="admin-item-more">+{{ $order->items->count() - 3 }}</div>
                          @endif
                        </div>
                      </div>
                    </div>

                    <!-- Resumo Financeiro -->
                    <div class="admin-overview-section">
                      <h3 class="admin-section-title">
                        <ion-icon name="wallet-outline"></ion-icon>
                        Resumo Financeiro
                      </h3>

                      <div class="admin-financial-overview">
                        <div class="admin-total-amount">
                          <span class="admin-total-label">Total</span>
                          <span class="admin-total-value">€{{ number_format($order->total, 2, ',', '.') }}</span>
                        </div>
                        <div class="admin-financial-details">
                          <div class="admin-financial-item">
                            <span>Subtotal</span>
                            <span>€{{ number_format($order->subtotal ?? $order->total, 2, ',', '.') }}</span>
                          </div>
                          @if ($order->discount > 0)
                            <div class="admin-financial-item">
                              <span>Desconto</span>
                              <span>-€{{ number_format($order->discount, 2, ',', '.') }}</span>
                            </div>
                          @endif
                          <div class="admin-financial-item">
                            <span>Frete</span>
                            <span>€{{ number_format($order->shipping, 2, ',', '.') }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Ações -->
                  <div class="admin-overview-actions">
                    <button type="button" class="admin-btn admin-btn-primary" onclick="printOrder()">
                      <ion-icon name="print-outline"></ion-icon>
                      Imprimir Pedido
                    </button>
                    <button type="button" class="admin-btn admin-btn-secondary" onclick="copyOrderLink()">
                      <ion-icon name="link-outline"></ion-icon>
                      Copiar Link
                    </button>
                  </div>
                </div>

                <!-- Aba: Cliente -->
                <div class="admin-tab-panel" id="customer">
                  <!-- Informações Detalhadas do Cliente -->
                  <div class="admin-customer-details-section">
                    <h3 class="admin-section-title">
                      <ion-icon name="person-circle-outline"></ion-icon>
                      Informações Detalhadas do Cliente
                    </h3>

                    <div class="admin-customer-profile">
                      <div class="admin-customer-avatar">
                        {{ strtoupper(mb_substr($order->user->name ?? $order->name, 0, 2)) }}
                      </div>
                      <div class="admin-customer-info">
                        <div class="admin-customer-name">{{ $order->user->name ?? $order->name }}</div>
                        <div class="admin-customer-email">{{ $order->user->email ?? $order->email }}</div>
                        <div class="admin-customer-phone">{{ $order->phone ?? 'Telefone não informado' }}</div>
                      </div>
                    </div>
                  </div>

                  <!-- Endereço de Entrega -->
                  <div class="admin-delivery-section">
                    <h3 class="admin-section-title">
                      <ion-icon name="location-outline"></ion-icon>
                      Endereço de Entrega
                    </h3>
                    <div class="admin-address-card">
                      <div class="admin-address-line">{{ $order->address }}</div>
                      <div class="admin-address-details">
                        <span class="admin-address-city">{{ $order->city }}/{{ $order->state }}</span>
                        <span class="admin-address-zip">CEP: {{ $order->zip ?? 'Não informado' }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Histórico do Cliente (se disponível) -->
                  <div class="admin-customer-history-section">
                    <h3 class="admin-section-title">
                      <ion-icon name="time-outline"></ion-icon>
                      Histórico do Cliente
                    </h3>
                    <div class="admin-history-card">
                      <div class="admin-history-item">
                        <span class="admin-history-label">Cliente desde</span>
                        <span
                          class="admin-history-value">{{ $order->user->created_at->format('d/m/Y') ?? 'N/A' }}</span>
                      </div>
                      <div class="admin-history-item">
                        <span class="admin-history-label">Total de pedidos</span>
                        <span class="admin-history-value">{{ $order->user->orders->count() ?? 'N/A' }}</span>
                      </div>
                      <div class="admin-history-item">
                        <span class="admin-history-label">Valor total gasto</span>
                        <span
                          class="admin-history-value">€{{ number_format($order->user->orders->sum('total') ?? 0, 2, ',', '.') }}</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Aba: Itens -->
                <div class="admin-tab-panel" id="items">
                  <div class="admin-order-items">
                    @foreach ($order->items as $item)
                      <div class="admin-order-item">
                        <div class="admin-order-item-image">
                          <img src="{{ asset('products/' . ($item->product->image ?? '')) }}"
                            alt="{{ $item->product->name ?? $item->name }}"
                            onerror="this.src='{{ asset('images/placeholder.png') }}'">
                        </div>
                        <div class="admin-order-item-details">
                          <div class="admin-order-item-name">{{ $item->product->name ?? $item->name }}</div>
                          <div class="admin-order-item-sku">{{ $item->product->sku ?? 'N/A' }}</div>
                          <div class="admin-order-item-variations">
                            <span class="admin-order-item-variation">Qtd: {{ $item->quantity }}</span>
                            @if ($item->color)
                              <span class="admin-order-item-variation">Cor: {{ $item->color }}</span>
                            @endif
                            @if ($item->size)
                              <span class="admin-order-item-variation">Tamanho: {{ $item->size }}</span>
                            @endif
                          </div>
                        </div>
                        <div class="admin-order-item-quantity">{{ $item->quantity }}</div>
                        <div class="admin-order-item-price">
                          €{{ number_format($item->price * $item->quantity, 2, ',', '.') }}</div>
                      </div>
                    @endforeach
                  </div>
                </div>

                <!-- Aba: Financeiro -->
                <div class="admin-tab-panel" id="financial">
                  <div class="admin-order-summary">
                    <div class="admin-summary-grid">
                      <div class="admin-summary-item">
                        <span class="admin-summary-label">Subtotal</span>
                        <span
                          class="admin-summary-value">€{{ number_format($order->subtotal ?? $order->total, 2, ',', '.') }}</span>
                      </div>

                      @if ($order->discount > 0)
                        <div class="admin-summary-item">
                          <span class="admin-summary-label">Desconto</span>
                          <span class="admin-summary-value">-€{{ number_format($order->discount, 2, ',', '.') }}</span>
                        </div>
                      @endif

                      <div class="admin-summary-item">
                        <span class="admin-summary-label">Frete</span>
                        <span class="admin-summary-value">€{{ number_format($order->shipping, 2, ',', '.') }}</span>
                      </div>

                      <div class="admin-summary-item">
                        <span class="admin-summary-label">Total</span>
                        <span class="admin-summary-value">€{{ number_format($order->total, 2, ',', '.') }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="admin-card-body">
              <div class="admin-form-actions">
                <a href="{{ route('admin.orders.index') }}" class="admin-btn admin-btn-secondary">
                  <ion-icon name="arrow-back-outline"></ion-icon>
                  Voltar para Pedidos
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Sistema de abas
    document.addEventListener('DOMContentLoaded', function() {
      const tabButtons = document.querySelectorAll('.admin-tab-btn');
      const tabPanels = document.querySelectorAll('.admin-tab-panel');

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

    // Dropdown de status
    document.querySelectorAll('.admin-status-badge').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.stopPropagation();

        // Fecha outros dropdowns
        document.querySelectorAll('.admin-status-options').forEach(d => d.classList.remove('show'));

        // Abre o dropdown deste
        const dropdown = this.parentElement.querySelector('.admin-status-options');

        // Calcula a posição do botão
        const rect = this.getBoundingClientRect();

        // Posiciona o dropdown
        dropdown.style.left = rect.left + 'px';
        dropdown.style.top = (rect.bottom + 5) + 'px';
        dropdown.style.minWidth = rect.width + 'px';

        dropdown.classList.toggle('show');
      });
    });

    // Fecha dropdowns ao clicar fora
    document.addEventListener('click', function() {
      document.querySelectorAll('.admin-status-options').forEach(d => d.classList.remove('show'));
    });

    // Reposiciona dropdowns quando a página rola
    window.addEventListener('scroll', function() {
      document.querySelectorAll('.admin-status-options.show').forEach(dropdown => {
        const badge = dropdown.parentElement.querySelector('.admin-status-badge');
        if (badge) {
          const rect = badge.getBoundingClientRect();
          dropdown.style.left = rect.left + 'px';
          dropdown.style.top = (rect.bottom + 5) + 'px';
          dropdown.style.minWidth = rect.width + 'px';
        }
      });
    });

    // Reposiciona dropdowns quando a janela é redimensionada
    window.addEventListener('resize', function() {
      document.querySelectorAll('.admin-status-options.show').forEach(dropdown => {
        const badge = dropdown.parentElement.querySelector('.admin-status-badge');
        if (badge) {
          const rect = badge.getBoundingClientRect();
          dropdown.style.left = rect.left + 'px';
          dropdown.style.top = (rect.bottom + 5) + 'px';
          dropdown.style.minWidth = rect.width + 'px';
        }
      });
    });

    // Atualização de status
    document.querySelectorAll('.admin-status-option').forEach(function(opt) {
      opt.addEventListener('click', function(e) {
        e.preventDefault();
        const newStatus = this.dataset.status;
        const btn = this.closest('.admin-status-dropdown').querySelector('.admin-status-badge');
        const orderId = btn.dataset.orderId;
        const csrfToken = '{{ csrf_token() }}';

        // Atualiza visualmente primeiro
        const labels = {
          'pending': 'Aguardando',
          'paid': 'Pago',
          'shipped': 'Enviado',
          'delivered': 'Entregue',
          'canceled': 'Cancelado'
        };

        btn.textContent = labels[newStatus] + ' ';
        btn.className = `admin-status-badge admin-status-${newStatus}`;
        btn.dataset.currentStatus = newStatus;

        // Adiciona o ícone de volta
        const icon = document.createElement('ion-icon');
        icon.name = 'chevron-down-outline';
        btn.appendChild(icon);

        // Fecha o dropdown
        btn.parentElement.querySelector('.admin-status-options').classList.remove('show');

        // Faz a requisição AJAX
        fetch(`/admin/orders/${orderId}`, {
            method: 'PATCH',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              status: newStatus
            })
          })
          .then(response => response.json())
          .then(data => {
            if (!data.success) {
              alert('Erro ao atualizar status.');
              // Reverte a mudança visual em caso de erro
              location.reload();
            }
          })
          .catch(() => {
            alert('Erro ao atualizar status.');
            location.reload();
          });
      });
    });

    // Função para imprimir pedido
    function printOrder() {
      window.print();
    }

    // Função para copiar link do pedido
    function copyOrderLink() {
      const url = window.location.href;
      navigator.clipboard.writeText(url).then(() => {
        // Mostra uma notificação de sucesso
        const notification = document.createElement('div');
        notification.className = 'admin-notification';
        notification.textContent = 'Link copiado para a área de transferência!';
        document.body.appendChild(notification);

        setTimeout(() => {
          notification.remove();
        }, 3000);
      }).catch(() => {
        alert('Erro ao copiar link.');
      });
    }
  </script>
@endsection
