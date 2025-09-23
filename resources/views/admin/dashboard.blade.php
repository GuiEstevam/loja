@extends('layouts.app')

@section('title', 'Painel Administrativo')

@section('content')
  <div class="admin-page">
    <div class="admin-content">
      <div class="admin-container">
        <!-- Header da Página -->
        <div class="admin-card">
          <div class="admin-card-header">
            <h1 class="admin-card-title">
              <ion-icon name="settings-outline"></ion-icon>
              Painel Administrativo
            </h1>
            <p class="admin-card-subtitle">Gerencie produtos, pedidos e configurações da loja</p>
          </div>
        </div>

        <!-- Layout Principal -->
        <div class="admin-dashboard-layout">
          <!-- Seção de Gerenciamento -->
          <div class="admin-dashboard-header-section">
            <!-- Seção de Gerenciamento -->
            <div class="admin-management-section">
              <div class="admin-card">
                <div class="admin-card-header">
                  <h2 class="admin-card-title">
                    <ion-icon name="settings-outline"></ion-icon>
                    Gerenciamento da Loja
                  </h2>
                  <p class="admin-card-subtitle">Acesse as principais funcionalidades administrativas</p>
                </div>
                <div class="admin-card-body">
                  <div class="admin-category-cards">
                    <a href="{{ route('admin.products.index') }}" class="admin-category-card products">
                      <div class="admin-category-icon">
                        <ion-icon name="cube-outline"></ion-icon>
                      </div>
                      <h3 class="admin-category-title">Produtos</h3>
                      <p class="admin-category-description">Gerencie o catálogo de produtos</p>
                    </a>

                    <a href="{{ route('admin.orders.index') }}" class="admin-category-card orders">
                      <div class="admin-category-icon">
                        <ion-icon name="receipt-outline"></ion-icon>
                      </div>
                      <h3 class="admin-category-title">Pedidos</h3>
                      <p class="admin-category-description">Gerencie os pedidos dos clientes</p>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="admin-category-card categories">
                      <div class="admin-category-icon">
                        <ion-icon name="pricetags-outline"></ion-icon>
                      </div>
                      <h3 class="admin-category-title">Categorias</h3>
                      <p class="admin-category-description">Gerencie as categorias dos produtos</p>
                    </a>

                    <a href="{{ route('admin.brands.index') }}" class="admin-category-card brands">
                      <div class="admin-category-icon">
                        <ion-icon name="business-outline"></ion-icon>
                      </div>
                      <h3 class="admin-category-title">Marcas</h3>
                      <p class="admin-category-description">Gerencie as marcas dos produtos</p>
                    </a>

                    <a href="{{ route('admin.colors.index') }}" class="admin-category-card colors">
                      <div class="admin-category-icon">
                        <ion-icon name="color-palette-outline"></ion-icon>
                      </div>
                      <h3 class="admin-category-title">Cores</h3>
                      <p class="admin-category-description">Gerencie as cores disponíveis</p>
                    </a>

                    <a href="{{ route('admin.sizes.index') }}" class="admin-category-card sizes">
                      <div class="admin-category-icon">
                        <ion-icon name="resize-outline"></ion-icon>
                      </div>
                      <h3 class="admin-category-title">Tamanhos</h3>
                      <p class="admin-category-description">Gerencie os tamanhos disponíveis</p>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Seção de Estatísticas -->
          <div class="admin-dashboard-stats-section">
            <!-- Container das Estatísticas -->
            <div class="admin-card">
              <div class="admin-card-header">
                <h2 class="admin-card-title">
                  <ion-icon name="analytics-outline"></ion-icon>
                  Visão Geral da Loja
                </h2>
                <p class="admin-card-subtitle">Métricas principais de desempenho</p>
              </div>
              <div class="admin-card-body">
                <!-- Cards de Estatísticas Principais -->
                <div class="admin-stats-grid">
                  <div class="admin-stat-card revenue">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="cash-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">€{{ number_format($totalReceita, 2, ',', '.') }}</div>
                      <div class="admin-stat-label">Receita ({{ $dias }} dias)</div>
                    </div>
                  </div>

                  <div class="admin-stat-card ticket">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="pricetag-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">€{{ number_format($ticketMedio, 2, ',', '.') }}</div>
                      <div class="admin-stat-label">Ticket Médio</div>
                    </div>
                  </div>

                  <div class="admin-stat-card low-stock">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="warning-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $produtosBaixoEstoque }}</div>
                      <div class="admin-stat-label">Baixo estoque</div>
                    </div>
                  </div>

                  <div class="admin-stat-card new-customers">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="person-add-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $novosClientes }}</div>
                      <div class="admin-stat-label">Novos clientes ({{ $dias }} dias)</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Gráfico de Pedidos -->
        <div class="admin-chart-section">
          <div class="admin-card">
            <div class="admin-card-header">
              <h2 class="admin-card-title">Pedidos nos últimos {{ $dias }} dias</h2>
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
            <div class="admin-card-body">
              <div class="admin-chart-content">
                <canvas id="ordersChart" height="80"></canvas>
              </div>
            </div>
          </div>
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
