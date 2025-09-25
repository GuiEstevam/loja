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
            <ion-icon name="analytics-outline"></ion-icon>
              Painel Administrativo
            </h1>
          <p class="admin-card-subtitle">Visão geral e métricas da loja</p>
        </div>
      </div>

      <!-- Navegação Rápida (Sempre Visível) -->
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

            <a href="{{ route('admin.users.index') }}" class="admin-category-card users">
              <div class="admin-category-icon">
                <ion-icon name="people-outline"></ion-icon>
              </div>
              <h3 class="admin-category-title">Usuários</h3>
              <p class="admin-category-description">Gerencie usuários e clientes</p>
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="admin-category-card reviews">
              <div class="admin-category-icon">
                <ion-icon name="star-outline"></ion-icon>
              </div>
              <h3 class="admin-category-title">Avaliações</h3>
              <p class="admin-category-description">Modere avaliações dos produtos</p>
            </a>
          </div>
        </div>
      </div>

      <!-- Sistema de Abas -->
      <div class="admin-tabs-container">
        <div class="admin-tabs-nav">
          <button class="admin-tab-btn active" data-tab="overview">
            <ion-icon name="grid-outline"></ion-icon>
            Visão Geral
          </button>
          <button class="admin-tab-btn" data-tab="sales">
            <ion-icon name="trending-up-outline"></ion-icon>
            Vendas
          </button>
          <button class="admin-tab-btn" data-tab="products">
            <ion-icon name="cube-outline"></ion-icon>
            Produtos
          </button>
          <button class="admin-tab-btn" data-tab="customers">
            <ion-icon name="people-outline"></ion-icon>
            Clientes
          </button>
          <button class="admin-tab-btn" data-tab="activities">
            <ion-icon name="time-outline"></ion-icon>
            Atividades
          </button>
        </div>

        <!-- Aba: Visão Geral -->
        <div class="admin-tab-content active" id="overview">
          <!-- Cards de Estatísticas Principais -->
          <div class="admin-card">
            <div class="admin-card-header">
              <h2 class="admin-card-title">
                <ion-icon name="analytics-outline"></ion-icon>
                Métricas Principais
              </h2>
            </div>
            <div class="admin-card-body">
              <div class="admin-stats-grid">
                <div class="admin-stat-card revenue">
                  <div class="admin-stat-header">
                    <div class="admin-stat-icon">
                      <ion-icon name="cash-outline"></ion-icon>
                    </div>
                  </div>
                  <div class="admin-stat-content">
                    <div class="admin-stat-value">€{{ number_format($stats['totalReceita'], 2, ',', '.') }}</div>
                    <div class="admin-stat-label">Receita ({{ $dias }} dias)</div>
                  </div>
                </div>

                <div class="admin-stat-card orders">
                  <div class="admin-stat-header">
                    <div class="admin-stat-icon">
                      <ion-icon name="receipt-outline"></ion-icon>
                    </div>
                  </div>
                  <div class="admin-stat-content">
                    <div class="admin-stat-value">{{ $stats['totalPedidos'] }}</div>
                    <div class="admin-stat-label">Pedidos ({{ $dias }} dias)</div>
                  </div>
                </div>

                <div class="admin-stat-card ticket">
                  <div class="admin-stat-header">
                    <div class="admin-stat-icon">
                      <ion-icon name="pricetag-outline"></ion-icon>
                    </div>
                  </div>
                  <div class="admin-stat-content">
                    <div class="admin-stat-value">€{{ number_format($stats['ticketMedio'], 2, ',', '.') }}</div>
                    <div class="admin-stat-label">Ticket Médio</div>
                  </div>
                </div>

                <div class="admin-stat-card customers">
                  <div class="admin-stat-header">
                    <div class="admin-stat-icon">
                      <ion-icon name="person-add-outline"></ion-icon>
                    </div>
                  </div>
                  <div class="admin-stat-content">
                    <div class="admin-stat-value">{{ $stats['novosClientes'] }}</div>
                    <div class="admin-stat-label">Novos clientes ({{ $dias }} dias)</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Gráfico de Pedidos -->
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

        <!-- Aba: Vendas -->
        <div class="admin-tab-content" id="sales">
          <div class="admin-cards-grid">
            <!-- Estatísticas de Vendas -->
            <div class="admin-card">
              <div class="admin-card-header">
                <h2 class="admin-card-title">
                  <ion-icon name="trending-up-outline"></ion-icon>
                  Performance de Vendas
                </h2>
              </div>
              <div class="admin-card-body">
                <div class="admin-stats-grid">
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="today-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['pedidosHoje'] }}</div>
                      <div class="admin-stat-label">Pedidos Hoje</div>
                    </div>
                  </div>
                  
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="cash-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">€{{ number_format($stats['receitaHoje'], 2, ',', '.') }}</div>
                      <div class="admin-stat-label">Receita Hoje</div>
                    </div>
                  </div>
                  
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="calendar-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['pedidosSemana'] }}</div>
                      <div class="admin-stat-label">Esta Semana</div>
                    </div>
                  </div>
                  
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="wallet-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">€{{ number_format($stats['receitaSemana'], 2, ',', '.') }}</div>
                      <div class="admin-stat-label">Receita Semanal</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Produtos Mais Vendidos -->
            <div class="admin-card">
              <div class="admin-card-header">
                <h2 class="admin-card-title">
                  <ion-icon name="trophy-outline"></ion-icon>
                  Produtos Mais Vendidos
                </h2>
              </div>
              <div class="admin-card-body">
                @if($produtosMaisVendidos->count() > 0)
                  <div class="admin-list">
                    @foreach($produtosMaisVendidos as $produto)
                      <div class="admin-list-item">
                        <div class="admin-list-content">
                          <div class="admin-list-title">{{ $produto->name }}</div>
                          <div class="admin-list-subtitle">{{ $produto->total_vendido }} unidades vendidas</div>
                        </div>
                        <div class="admin-list-badge">
                          <span class="admin-badge admin-badge-primary">#{{ $loop->iteration }}</span>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="admin-empty-state">
                    <ion-icon name="cube-outline"></ion-icon>
                    <p>Nenhum produto vendido ainda</p>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Aba: Produtos -->
        <div class="admin-tab-content" id="products">
          <div class="admin-cards-grid">
            <!-- Estatísticas de Produtos -->
            <div class="admin-card">
              <div class="admin-card-header">
                <h2 class="admin-card-title">
                  <ion-icon name="cube-outline"></ion-icon>
                  Estatísticas de Produtos
                </h2>
              </div>
              <div class="admin-card-body">
                <div class="admin-stats-grid">
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="cube-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['totalProdutos'] }}</div>
                      <div class="admin-stat-label">Total de Produtos</div>
                    </div>
                  </div>

                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['produtosAtivos'] }}</div>
                      <div class="admin-stat-label">Produtos Ativos</div>
                    </div>
                  </div>

                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="warning-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['produtosBaixoEstoque'] }}</div>
                      <div class="admin-stat-label">Baixo Estoque</div>
                    </div>
                  </div>
                  
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="pricetags-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['totalCategorias'] }}</div>
                      <div class="admin-stat-label">Categorias</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Produtos com Baixo Estoque -->
            <div class="admin-card">
              <div class="admin-card-header">
                <h2 class="admin-card-title">
                  <ion-icon name="warning-outline"></ion-icon>
                  Produtos com Baixo Estoque
                </h2>
              </div>
              <div class="admin-card-body">
                @if($produtosBaixoEstoqueList->count() > 0)
                  <div class="admin-list">
                    @foreach($produtosBaixoEstoqueList as $produto)
                      <div class="admin-list-item">
                        <div class="admin-list-content">
                          <div class="admin-list-title">{{ $produto->name }}</div>
                          <div class="admin-list-subtitle">Estoque: {{ $produto->stock }} unidades</div>
                        </div>
                        <div class="admin-list-badge">
                          <span class="admin-badge admin-badge-warning">Crítico</span>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="admin-empty-state">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                    <p>Todos os produtos estão com estoque adequado</p>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Aba: Clientes -->
        <div class="admin-tab-content" id="customers">
          <div class="admin-cards-grid">
            <!-- Estatísticas de Clientes -->
            <div class="admin-card">
              <div class="admin-card-header">
                <h2 class="admin-card-title">
                  <ion-icon name="people-outline"></ion-icon>
                  Estatísticas de Clientes
                </h2>
              </div>
              <div class="admin-card-body">
                <div class="admin-stats-grid">
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="people-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['totalClientes'] }}</div>
                      <div class="admin-stat-label">Total de Clientes</div>
                    </div>
                  </div>
                  
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['usuariosVerificados'] }}</div>
                      <div class="admin-stat-label">Verificados</div>
                    </div>
                  </div>

                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="person-add-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['novosUsuariosHoje'] }}</div>
                      <div class="admin-stat-label">Novos Hoje</div>
                    </div>
                  </div>
                  
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="calendar-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['novosUsuariosSemana'] }}</div>
                      <div class="admin-stat-label">Esta Semana</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Avaliações -->
            <div class="admin-card">
              <div class="admin-card-header">
                <h2 class="admin-card-title">
                  <ion-icon name="star-outline"></ion-icon>
                  Avaliações
                </h2>
              </div>
              <div class="admin-card-body">
                <div class="admin-stats-grid">
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="star-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['totalAvaliacoes'] }}</div>
                      <div class="admin-stat-label">Total</div>
                    </div>
                  </div>
                  
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="time-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['avaliacoesPendentes'] }}</div>
                      <div class="admin-stat-label">Pendentes</div>
                    </div>
                  </div>
                  
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ $stats['avaliacoesAprovadas'] }}</div>
                      <div class="admin-stat-label">Aprovadas</div>
                    </div>
                  </div>
                  
                  <div class="admin-stat-card">
                    <div class="admin-stat-header">
                      <div class="admin-stat-icon">
                        <ion-icon name="star-half-outline"></ion-icon>
                      </div>
                    </div>
                    <div class="admin-stat-content">
                      <div class="admin-stat-value">{{ number_format($stats['mediaAvaliacoes'], 1) }}</div>
                      <div class="admin-stat-label">Média Geral</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Aba: Atividades -->
        <div class="admin-tab-content" id="activities">
          <div class="admin-cards-grid">
            <!-- Pedidos Recentes -->
          <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">
                  <ion-icon name="receipt-outline"></ion-icon>
                  Pedidos Recentes
                </h2>
              </div>
              <div class="admin-card-body">
                @if($pedidosRecentes->count() > 0)
                  <div class="admin-list">
                    @foreach($pedidosRecentes as $pedido)
                      <div class="admin-list-item">
                        <div class="admin-list-content">
                          <div class="admin-list-title">Pedido #{{ $pedido->id }}</div>
                          <div class="admin-list-subtitle">{{ $pedido->user->name ?? 'Cliente' }} - €{{ number_format($pedido->total, 2, ',', '.') }}</div>
                        </div>
                        <div class="admin-list-badge">
                          <span class="admin-badge admin-badge-{{ $pedido->status }}">{{ ucfirst($pedido->status) }}</span>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="admin-empty-state">
                    <ion-icon name="receipt-outline"></ion-icon>
                    <p>Nenhum pedido encontrado</p>
                  </div>
                @endif
              </div>
            </div>

            <!-- Atividades Recentes -->
            <div class="admin-card">
              <div class="admin-card-header">
                <h2 class="admin-card-title">
                  <ion-icon name="time-outline"></ion-icon>
                  Atividades Recentes
                </h2>
            </div>
            <div class="admin-card-body">
                @if($atividadesRecentes->count() > 0)
                  <div class="admin-list">
                    @foreach($atividadesRecentes as $usuario)
                      <div class="admin-list-item">
                        <div class="admin-list-content">
                          <div class="admin-list-title">{{ $usuario->name }}</div>
                          <div class="admin-list-subtitle">Novo usuário - {{ $usuario->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="admin-list-badge">
                          <span class="admin-badge admin-badge-info">Novo</span>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="admin-empty-state">
                    <ion-icon name="time-outline"></ion-icon>
                    <p>Nenhuma atividade recente</p>
                  </div>
                @endif
              </div>
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
  // Sistema de Abas
  document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.admin-tab-btn');
    const tabContents = document.querySelectorAll('.admin-tab-content');

    tabBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const targetTab = btn.getAttribute('data-tab');
        
        // Remove active class from all buttons and contents
        tabBtns.forEach(b => b.classList.remove('active'));
        tabContents.forEach(c => c.classList.remove('active'));
        
        // Add active class to clicked button and corresponding content
        btn.classList.add('active');
        document.getElementById(targetTab).classList.add('active');
      });
    });
  });

  // Gráfico de Pedidos
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
        borderColor: '#8b5cf6',
        backgroundColor: 'rgba(139, 92, 246, 0.1)',
          tension: 0.4,
          fill: true,
        pointBackgroundColor: '#8b5cf6',
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