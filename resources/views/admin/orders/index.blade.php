@extends('layouts.app')

@section('title', 'Pedidos')

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
              <span>Pedidos</span>
            </nav>

            <div class="admin-header-content">
              <div class="admin-header-text">
                <h1 class="admin-card-title">
                  <ion-icon name="receipt-outline"></ion-icon>
                  Gerenciar Pedidos
                </h1>
                <p class="admin-card-subtitle">Visão geral e gestão de todos os pedidos</p>
              </div>
              
              <div class="admin-header-actions">
                <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary">
                  <ion-icon name="home-outline"></ion-icon>
                  Voltar ao Dashboard
                </a>
              </div>
            </div>
          </div>

          <!-- Estatísticas -->
          <div class="admin-card-body">
            <div class="admin-stats-container">
              <div class="admin-stats-grid">
                <div class="admin-stat-card">
                  <div class="admin-stat-header">
                    <div class="admin-stat-icon">
                      <ion-icon name="receipt-outline"></ion-icon>
                    </div>
                  </div>
                  <div class="admin-stat-content">
                    <div class="admin-stat-value">{{ $stats['total'] }}</div>
                    <div class="admin-stat-label">Total de Pedidos</div>
                  </div>
                </div>

                <div class="admin-stat-card">
                  <div class="admin-stat-header">
                    <div class="admin-stat-icon">
                      <ion-icon name="cash-outline"></ion-icon>
                    </div>
                  </div>
                  <div class="admin-stat-content">
                    <div class="admin-stat-value">€{{ number_format($stats['revenue'], 2, ',', '.') }}</div>
                    <div class="admin-stat-label">Receita Total</div>
                  </div>
                </div>

                <div class="admin-stat-card">
                  <div class="admin-stat-header">
                    <div class="admin-stat-icon">
                      <ion-icon name="pricetag-outline"></ion-icon>
                    </div>
                  </div>
                  <div class="admin-stat-content">
                    <div class="admin-stat-value">€{{ number_format($stats['average'], 2, ',', '.') }}</div>
                    <div class="admin-stat-label">Ticket Médio</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Filtros -->
        <div class="admin-card">
          <div class="admin-card-header">
            <h2 class="admin-card-title">
              <ion-icon name="funnel-outline"></ion-icon>
              Filtros de Busca
            </h2>
          </div>
          <div class="admin-card-body">
            <form method="GET" class="admin-filters">
              <div class="admin-form-grid">
                <div class="admin-form-group">
                  <label class="admin-form-label">Buscar Pedido</label>
                  <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="ID do pedido ou nome do cliente..." class="admin-form-input">
                </div>

                <div class="admin-form-group">
                  <label class="admin-form-label">Status</label>
                  <select name="status" class="admin-form-select">
                    <option value="">Todos os status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Aguardando</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pago</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Enviado</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregue</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Cancelado</option>
                  </select>
                </div>

                <div class="admin-form-group">
                  <label class="admin-form-label">Itens por página</label>
                  <select name="per_page" class="admin-form-select">
                    @foreach ($perPageOptions as $option)
                      <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                        {{ $option }} itens
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="admin-form-group">
                  <label class="admin-form-label">Data Inicial</label>
                  <input type="date" name="date_from" value="{{ request('date_from') }}" class="admin-form-input">
                </div>

                <div class="admin-form-group">
                  <label class="admin-form-label">Data Final</label>
                  <input type="date" name="date_to" value="{{ request('date_to') }}" class="admin-form-input">
                </div>
              </div>

              <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-primary">
                  <ion-icon name="search-outline"></ion-icon>
                  Filtrar
                </button>

                @if (request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                  <a href="{{ route('admin.orders.index') }}" class="admin-btn admin-btn-secondary">
                    <ion-icon name="refresh-outline"></ion-icon>
                    Limpar Filtros
                  </a>
                @endif
              </div>
            </form>
          </div>
        </div>

        <!-- Tabela -->
        <div class="admin-card">
          <div class="admin-card-header">
            <h2 class="admin-card-title">
              <ion-icon name="list-outline"></ion-icon>
              Lista de Pedidos
            </h2>
          </div>
          <div class="admin-card-body">

            <div class="admin-table-container">
              <table class="admin-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Produtos</th>
                    <th>Total</th>
                    <th>Pagamento</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($orders as $order)
                    <tr>
                      <td>
                        <div class="admin-order-id">#{{ $order->id }}</div>
                      </td>
                      <td>
                        <div class="admin-customer-info">
                          <div class="admin-customer-avatar">
                            {{ strtoupper(mb_substr($order->user->name ?? $order->name, 0, 2)) }}
                          </div>
                          <div class="admin-customer-details">
                            <div class="admin-customer-name">{{ $order->user->name ?? $order->name }}</div>
                            <div class="admin-customer-email">{{ $order->user->email ?? '' }}</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="admin-order-date">
                          <div class="admin-order-date-main">{{ $order->created_at->format('d/m/Y') }}</div>
                          <div class="admin-order-date-time">{{ $order->created_at->format('H:i') }}</div>
                        </div>
                      </td>
                      <td>
                        <div class="admin-status-dropdown">
                          <button type="button" class="admin-status-badge admin-status-{{ $order->status }}"
                            data-order-id="{{ $order->id }}" data-current-status="{{ $order->status }}">
                            {{ [
                                'pending' => 'Aguardando',
                                'paid' => 'Pago',
                                'shipped' => 'Enviado',
                                'delivered' => 'Entregue',
                                'canceled' => 'Cancelado',
                            ][$order->status] ?? ucfirst($order->status) }}
                            <ion-icon name="chevron-down-outline"></ion-icon>
                          </button>
                          <div class="admin-status-options">
                            @foreach (['pending' => 'Aguardando', 'paid' => 'Pago', 'shipped' => 'Enviado', 'delivered' => 'Entregue', 'canceled' => 'Cancelado'] as $value => $label)
                              <button type="button" class="admin-status-option"
                                data-status="{{ $value }}">{{ $label }}</button>
                            @endforeach
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="admin-order-products">
                          @foreach ($order->items->take(3) as $item)
                            <img src="{{ asset('products/' . ($item->product->image ?? '')) }}"
                              class="admin-product-thumbnail" alt="{{ $item->product->name ?? $item->name }}"
                              title="{{ $item->product->name ?? $item->name }} (x{{ $item->quantity }})">
                          @endforeach
                          @if ($order->items->count() > 3)
                            <div class="admin-product-more">+{{ $order->items->count() - 3 }}</div>
                          @endif
                        </div>
                      </td>
                      <td>
                        <div class="admin-order-total">€{{ number_format($order->total, 2, ',', '.') }}</div>
                      </td>
                      <td>
                        <div class="admin-payment-status">
                          <span class="admin-payment-badge admin-payment-{{ $order->payment?->status ?? 'pending' }}">
                            <ion-icon
                              name="{{ ($order->payment?->status ?? 'pending') == 'approved' ? 'checkmark-circle' : 'time-outline' }}"></ion-icon>
                            {{ ($order->payment?->status ?? 'pending') == 'approved' ? 'Pago' : 'Pendente' }}
                          </span>
                        </div>
                      </td>
                      <td>
                        <div class="admin-actions">
                          <a href="{{ route('admin.orders.show', $order) }}" class="admin-icon-btn admin-icon-btn-view"
                            title="Ver Detalhes">
                            <ion-icon name="eye-outline"></ion-icon>
                          </a>
                          <button class="admin-icon-btn admin-icon-btn-print" title="Imprimir Pedido"
                            onclick="printOrder({{ $order->id }})">
                            <ion-icon name="print-outline"></ion-icon>
                          </button>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8">
                        <div class="admin-empty-state">
                          <ion-icon name="receipt-outline"></ion-icon>
                          <p>Nenhum pedido encontrado</p>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <!-- Paginação -->
            <div class="admin-pagination">
              {{ $orders->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
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
            if (data.success) {
              // Atualiza as estatísticas automaticamente
              updateStats();
            } else {
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
    function printOrder(orderId) {
      const printWindow = window.open(`/admin/orders/${orderId}/print`, '_blank');
      printWindow.onload = function() {
        printWindow.print();
      };
    }

    // Função para atualizar as estatísticas
    function updateStats() {
      fetch('{{ route('admin.orders.index') }}', {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.stats) {
            // Atualiza os valores das estatísticas
            const statCards = document.querySelectorAll('.admin-stat-card');

            // Total de Pedidos
            if (statCards[0]) {
              const totalValue = statCards[0].querySelector('.admin-stat-value');
              if (totalValue) {
                totalValue.textContent = data.stats.total;
              }
            }

            // Receita Total
            if (statCards[1]) {
              const revenueValue = statCards[1].querySelector('.admin-stat-value');
              if (revenueValue) {
                revenueValue.textContent = '€' + parseFloat(data.stats.revenue).toLocaleString('pt-BR', {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2
                });
              }
            }

            // Ticket Médio
            if (statCards[2]) {
              const averageValue = statCards[2].querySelector('.admin-stat-value');
              if (averageValue) {
                averageValue.textContent = '€' + parseFloat(data.stats.average).toLocaleString('pt-BR', {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2
                });
              }
            }
          }
        })
        .catch(error => {
          console.error('Erro ao atualizar estatísticas:', error);
        });
    }
  </script>
@endsection
