@extends('layouts.app')

@section('title', 'Painel Administrativo')

@push('styles')
  @vite(['resources/css/admin-dashboard.css'])
@endpush

@section('content')
  <div class="admin-dashboard-page">
    <div class="admin-dashboard-container">
      <!-- Header da Página -->
      <div class="admin-dashboard-header">
        <h1 class="admin-dashboard-title">
          <ion-icon name="settings-outline"></ion-icon>
          Painel Administrativo
        </h1>
        <p class="admin-dashboard-subtitle">Gerencie produtos, pedidos e configurações da loja</p>
      </div>

      <!-- Cards de Estatísticas Principais -->
      <div class="admin-stats-grid">
        <a href="{{ route('admin.orders.index') }}" class="admin-stat-card orders">
          <div class="admin-stat-icon">
            <ion-icon name="receipt-outline"></ion-icon>
          </div>
          <div class="admin-stat-value">{{ $totalPedidos }}</div>
          <div class="admin-stat-label">Pedidos ({{ $dias }} dias)</div>
        </a>

        <div class="admin-stat-card revenue">
          <div class="admin-stat-icon">
            <ion-icon name="cash-outline"></ion-icon>
          </div>
          <div class="admin-stat-value">€{{ number_format($totalReceita, 2, ',', '.') }}</div>
          <div class="admin-stat-label">Receita ({{ $dias }} dias)</div>
        </div>

        <div class="admin-stat-card ticket">
          <div class="admin-stat-icon">
            <ion-icon name="pricetag-outline"></ion-icon>
          </div>
          <div class="admin-stat-value">€{{ number_format($ticketMedio, 2, ',', '.') }}</div>
          <div class="admin-stat-label">Ticket Médio</div>
        </div>

        <a href="{{ route('admin.products.index') }}" class="admin-stat-card products">
          <div class="admin-stat-icon">
            <ion-icon name="cube-outline"></ion-icon>
          </div>
          <div class="admin-stat-value">{{ $totalProdutos }}</div>
          <div class="admin-stat-label">Produtos ativos</div>
        </a>

        <a href="{{ route('admin.products.index', ['filtro' => 'baixo_estoque']) }}" class="admin-stat-card low-stock">
          <div class="admin-stat-icon">
            <ion-icon name="warning-outline"></ion-icon>
          </div>
          <div class="admin-stat-value">{{ $produtosBaixoEstoque }}</div>
          <div class="admin-stat-label">Baixo estoque</div>
        </a>

        <div class="admin-stat-card new-customers">
          <div class="admin-stat-icon">
            <ion-icon name="person-add-outline"></ion-icon>
          </div>
          <div class="admin-stat-value">{{ $novosClientes }}</div>
          <div class="admin-stat-label">Novos clientes ({{ $dias }} dias)</div>
        </div>

        <div class="admin-stat-card total-customers">
          <div class="admin-stat-icon">
            <ion-icon name="people-outline"></ion-icon>
          </div>
          <div class="admin-stat-value">{{ $totalClientes }}</div>
          <div class="admin-stat-label">Clientes totais</div>
        </div>

        <div class="admin-stat-card coupons {{ is_null($cuponsAtivos) ? 'disabled' : '' }}">
          <div class="admin-stat-icon">
            <ion-icon name="pricetags-outline"></ion-icon>
          </div>
          <div class="admin-stat-value">
            {{ is_null($cuponsAtivos) ? '—' : $cuponsAtivos }}
          </div>
          <div class="admin-stat-label">
            Cupons ativos {{ is_null($cuponsAtivos) ? '(em breve)' : '' }}
          </div>
        </div>

        <div class="admin-stat-card loyalty {{ is_null($totalPontosFidelidade) ? 'disabled' : '' }}">
          <div class="admin-stat-icon">
            <ion-icon name="star-outline"></ion-icon>
          </div>
          <div class="admin-stat-value">
            {{ is_null($totalPontosFidelidade) ? '—' : $totalPontosFidelidade }}
          </div>
          <div class="admin-stat-label">
            Pontos distribuídos {{ is_null($totalPontosFidelidade) ? '(em breve)' : '' }}
          </div>
        </div>
      </div>

      <!-- Seção de Gerenciamento -->
      <div class="admin-management-section">
        <div class="admin-section-header">
          <h2 class="admin-section-title">
            <ion-icon name="settings-outline"></ion-icon>
            Gerenciamento da Loja
          </h2>
          <p class="admin-section-subtitle">Configure categorias, marcas, cores e tamanhos dos produtos</p>
        </div>

        <div class="admin-category-cards">
          <a href="{{ route('admin.categories.index') }}" class="admin-category-card categories">
            <div class="admin-category-icon">
              <ion-icon name="pricetags-outline"></ion-icon>
            </div>
            <h3 class="admin-category-title">Categorias</h3>
            <p class="admin-category-description">Gerencie as categorias dos produtos</p>
          </a>

          <a href="{{ route('admin.brands.index') }}" class="admin-category-card brands">
            <div class="admin-category-icon">
              <ion-icon name="ribbon-outline"></ion-icon>
            </div>
            <h3 class="admin-category-title">Marcas</h3>
            <p class="admin-category-description">Configure as marcas disponíveis</p>
          </a>

          <a href="{{ route('admin.colors.index') }}" class="admin-category-card colors">
            <div class="admin-category-icon">
              <ion-icon name="color-palette-outline"></ion-icon>
            </div>
            <h3 class="admin-category-title">Cores</h3>
            <p class="admin-category-description">Gerencie as cores dos produtos</p>
          </a>

          <a href="{{ route('admin.sizes.index') }}" class="admin-category-card sizes">
            <div class="admin-category-icon">
              <ion-icon name="resize-outline"></ion-icon>
            </div>
            <h3 class="admin-category-title">Tamanhos</h3>
            <p class="admin-category-description">Configure os tamanhos disponíveis</p>
          </a>
        </div>
      </div>

      <!-- Gráfico de Pedidos -->
      <div class="admin-chart-section">
        <div class="admin-chart-header">
          <h2 class="admin-chart-title">Pedidos nos últimos {{ $dias }} dias</h2>
          <div class="admin-chart-controls">
            <form method="GET" class="flex items-center gap-2">
              <label for="dias" class="admin-chart-label">Período:</label>
              <select name="dias" id="dias" class="admin-chart-select" onchange="this.form.submit()">
                <option value="7" {{ $dias == 7 ? 'selected' : '' }}>7 dias</option>
                <option value="15" {{ $dias == 15 ? 'selected' : '' }}>15 dias</option>
                <option value="30" {{ $dias == 30 ? 'selected' : '' }}>30 dias</option>
              </select>
            </form>
          </div>
        </div>
        <div class="admin-chart-content">
          <canvas id="ordersChart" height="80"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('ordersChart').getContext('2d');
    const chartData = @json($chartData);
    const chartLabels = @json($chartLabels);

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: chartLabels,
        datasets: [{
          label: 'Pedidos',
          data: chartData,
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.4,
          fill: true,
          pointBackgroundColor: '#3b82f6',
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              color: '#64748b',
              font: {
                size: 12
              }
            },
            grid: {
              color: '#e2e8f0',
              drawBorder: false
            }
          },
          x: {
            ticks: {
              color: '#64748b',
              font: {
                size: 12
              }
            },
            grid: {
              color: '#e2e8f0',
              drawBorder: false
            }
          }
        },
        interaction: {
          intersect: false,
          mode: 'index'
        }
      }
    });
  </script>
@endsection
