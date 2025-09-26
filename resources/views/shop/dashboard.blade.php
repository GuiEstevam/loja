@extends('layouts.app')

@section('title', 'Minha Conta')

@push('styles')
  @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
  <div class="dashboard-page">
    <div class="dashboard-container">
      <!-- Breadcrumb -->
      <nav class="dashboard-breadcrumb">
        <div class="dashboard-breadcrumb-nav">
          <span class="dashboard-breadcrumb-item active">
            <ion-icon name="home-outline"></ion-icon>
            Dashboard
          </span>
        </div>
      </nav>

      <!-- Header da Página -->
      <div class="dashboard-header">
        <h1 class="dashboard-title">
          <ion-icon name="person-circle-outline"></ion-icon>
          Minha Conta
        </h1>
        <p class="dashboard-subtitle">Gerencie seus pedidos, endereços e informações pessoais</p>
        <p class="dashboard-welcome">Olá, {{ $user->name }}! 👋</p>
      </div>

      <!-- Navegação Rápida -->
      <div class="dashboard-nav-cards">
        <a href="{{ route('shop.orders.index') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="bag-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Meus Pedidos</h3>
          <p class="nav-card-description">Acompanhe seus pedidos</p>
        </a>

        <a href="{{ route('enderecos.index') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="location-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Endereços</h3>
          <p class="nav-card-description">Gerencie endereços</p>
        </a>

        <a href="{{ route('shop.favorites.index') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="heart-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Favoritos</h3>
          <p class="nav-card-description">Produtos salvos</p>
        </a>

        <a href="{{ route('shop.profile.show') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="settings-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Configurações</h3>
          <p class="nav-card-description">Perfil e senha</p>
        </a>
      </div>

      <!-- Sistema de Abas -->
      <div class="dashboard-tabs-container">
        <div class="dashboard-tabs-nav">
          <button class="dashboard-tab-btn active" data-tab="overview">
            <ion-icon name="grid-outline"></ion-icon>
            Visão Geral
          </button>
          <button class="dashboard-tab-btn" data-tab="orders">
            <ion-icon name="bag-outline"></ion-icon>
            Pedidos
          </button>
          <button class="dashboard-tab-btn" data-tab="favorites">
            <ion-icon name="heart-outline"></ion-icon>
            Favoritos
          </button>
          <button class="dashboard-tab-btn" data-tab="addresses">
            <ion-icon name="location-outline"></ion-icon>
            Endereços
          </button>
          <button class="dashboard-tab-btn" data-tab="preferences">
            <ion-icon name="settings-outline"></ion-icon>
            Preferências
          </button>
        </div>

        <!-- Conteúdo das Abas -->
        <div class="dashboard-tab-content">
          <!-- Aba: Visão Geral -->
          <div class="dashboard-tab-pane active" id="overview">
            <!-- Métricas Principais -->
            <div class="dashboard-stats-grid">
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="bag-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['totalPedidos'] }}</div>
                  <div class="dashboard-stat-label">Total de Pedidos</div>
                </div>
              </div>
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="card-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">€{{ number_format($stats['totalGasto'], 2, ',', '.') }}</div>
                  <div class="dashboard-stat-label">Total Gasto</div>
                </div>
              </div>
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="heart-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['totalFavoritos'] }}</div>
                  <div class="dashboard-stat-label">Favoritos</div>
                </div>
              </div>
            </div>

            <!-- Gráfico de Gastos -->
            <div class="dashboard-chart-section">
              <div class="dashboard-chart-header">
                <h3 class="dashboard-chart-title">
                  <ion-icon name="trending-up-outline"></ion-icon>
                  Gastos dos Últimos 7 Dias
                </h3>
              </div>
              <div class="dashboard-chart-container">
                <canvas id="spendingChart" width="400" height="200"></canvas>
              </div>
            </div>
          </div>

          <!-- Aba: Pedidos -->
          <div class="dashboard-tab-pane" id="orders">
            <!-- Estatísticas de Pedidos -->
            <div class="dashboard-stats-grid">
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="time-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['pedidosPendentes'] }}</div>
                  <div class="dashboard-stat-label">Pendentes</div>
                </div>
              </div>
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="calendar-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['pedidosHoje'] }}</div>
                  <div class="dashboard-stat-label">Hoje</div>
                </div>
              </div>
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="stats-chart-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['pedidosSemana'] }}</div>
                  <div class="dashboard-stat-label">Esta Semana</div>
                </div>
              </div>
            </div>

            <!-- Pedidos Recentes -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="time-outline"></ion-icon>
                  Pedidos Recentes
                </h3>
                <a href="{{ route('shop.orders.index') }}" class="dashboard-section-action">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                  Ver Todos
                </a>
              </div>
              <div class="dashboard-section-content">
                @if ($pedidosRecentes->count() > 0)
                  <div class="dashboard-list">
                    @foreach ($pedidosRecentes as $order)
                      <div class="dashboard-list-item">
                        <div class="dashboard-list-icon">
                          <ion-icon name="receipt-outline"></ion-icon>
                        </div>
                        <div class="dashboard-list-content">
                          <h4 class="dashboard-list-title">Pedido #{{ $order->id }}</h4>
                          <p class="dashboard-list-meta">
                            {{ $order->created_at->format('d/m/Y H:i') }} •
                            {{ $order->items->count() }} item(s) •
                            €{{ number_format($order->total, 2, ',', '.') }}
                          </p>
                        </div>
                        <div class="dashboard-list-actions">
                          <span class="dashboard-status-badge {{ $order->status }}">
                            @switch($order->status)
                              @case('pending') Pendente @break
                              @case('processing') Processando @break
                              @case('shipped') Enviado @break
                              @case('delivered') Entregue @break
                              @default {{ ucfirst($order->status) }}
                            @endswitch
                          </span>
                          <a href="{{ route('shop.orders.show', $order) }}" class="dashboard-action-btn">
                            <ion-icon name="eye-outline"></ion-icon>
                            Ver
                          </a>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="dashboard-empty-state">
                    <ion-icon name="bag-outline"></ion-icon>
                    <h3>Nenhum pedido ainda</h3>
                    <p>Você ainda não fez nenhum pedido. Que tal começar a comprar?</p>
                    <a href="{{ route('shop.products.index') }}" class="dashboard-btn">
                      <ion-icon name="bag-outline"></ion-icon>
                      Ver Produtos
                    </a>
                  </div>
                @endif
              </div>
            </div>
          </div>

          <!-- Aba: Favoritos -->
          <div class="dashboard-tab-pane" id="favorites">
            <!-- Estatísticas de Favoritos -->
            <div class="dashboard-stats-grid">
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="heart-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['totalFavoritos'] }}</div>
                  <div class="dashboard-stat-label">Total</div>
                </div>
              </div>
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="today-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['favoritosHoje'] }}</div>
                  <div class="dashboard-stat-label">Adicionados Hoje</div>
                </div>
              </div>
            </div>

            <!-- Favoritos Recentes -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="heart-outline"></ion-icon>
                  Favoritos Recentes
                </h3>
                <a href="{{ route('shop.favorites.index') }}" class="dashboard-section-action">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                  Ver Todos
                </a>
              </div>
              <div class="dashboard-section-content">
                @if ($favoritosRecentes->count() > 0)
                  <div class="dashboard-list">
                    @foreach ($favoritosRecentes as $favorite)
                      <div class="dashboard-list-item">
                        <div class="dashboard-list-image">
                          <img src="{{ asset('products/' . $favorite->product->image) }}" alt="{{ $favorite->product->name }}">
                        </div>
                        <div class="dashboard-list-content">
                          <h4 class="dashboard-list-title">{{ $favorite->product->name }}</h4>
                          <p class="dashboard-list-meta">
                            €{{ number_format($favorite->product->price, 2, ',', '.') }} •
                            Adicionado em {{ $favorite->created_at->format('d/m/Y') }}
                          </p>
                        </div>
                        <div class="dashboard-list-actions">
                          <a href="{{ route('shop.products.show', $favorite->product) }}" class="dashboard-action-btn">
                            <ion-icon name="eye-outline"></ion-icon>
                            Ver
                          </a>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="dashboard-empty-state">
                    <ion-icon name="heart-outline"></ion-icon>
                    <h3>Nenhum favorito ainda</h3>
                    <p>Comece a adicionar produtos aos seus favoritos para vê-los aqui</p>
                    <a href="{{ route('shop.products.index') }}" class="dashboard-btn">
                      <ion-icon name="bag-outline"></ion-icon>
                      Ver Produtos
                    </a>
                  </div>
                @endif
              </div>
            </div>
          </div>

          <!-- Aba: Endereços -->
          <div class="dashboard-tab-pane" id="addresses">
            <!-- Estatísticas de Endereços -->
            <div class="dashboard-stats-grid">
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="location-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['enderecosSalvos'] }}</div>
                  <div class="dashboard-stat-label">Endereços Salvos</div>
                </div>
              </div>
            </div>

            <!-- Endereços Salvos -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="location-outline"></ion-icon>
                  Endereços Salvos
                </h3>
                <a href="{{ route('enderecos.index') }}" class="dashboard-section-action">
                  <ion-icon name="add-outline"></ion-icon>
                  Adicionar Novo
                </a>
              </div>
              <div class="dashboard-section-content">
                @if ($enderecosSalvos->count() > 0)
                  <div class="dashboard-list">
                    @foreach ($enderecosSalvos as $address)
                      <div class="dashboard-list-item">
                        <div class="dashboard-list-icon">
                          <ion-icon name="home-outline"></ion-icon>
                        </div>
                        <div class="dashboard-list-content">
                          <h4 class="dashboard-list-title">
                            {{ $address->address_line1 }}, {{ $address->number }}
                            @if ($address->is_default)
                              <span class="dashboard-status-badge delivered">Padrão</span>
                            @endif
                          </h4>
                          <p class="dashboard-list-meta">
                            {{ $address->neighborhood }}, {{ $address->city }}/{{ $address->state }} •
                            CEP: {{ $address->zipcode }}
                          </p>
                        </div>
                        <div class="dashboard-list-actions">
                          <a href="{{ route('enderecos.edit', $address) }}" class="dashboard-action-btn">
                            <ion-icon name="create-outline"></ion-icon>
                            Editar
                          </a>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="dashboard-empty-state">
                    <ion-icon name="location-outline"></ion-icon>
                    <h3>Nenhum endereço salvo</h3>
                    <p>Adicione um endereço para facilitar suas próximas compras</p>
                    <a href="{{ route('enderecos.create') }}" class="dashboard-btn">
                      <ion-icon name="add-outline"></ion-icon>
                      Adicionar Endereço
                    </a>
                  </div>
                @endif
              </div>
            </div>
          </div>

          <!-- Aba: Preferências -->
          <div class="dashboard-tab-pane" id="preferences">
            <!-- Estatísticas de Avaliações -->
            <div class="dashboard-stats-grid">
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="star-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['totalAvaliacoes'] }}</div>
                  <div class="dashboard-stat-label">Avaliações</div>
                </div>
              </div>
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="checkmark-circle-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['avaliacoesAprovadas'] }}</div>
                  <div class="dashboard-stat-label">Aprovadas</div>
                </div>
              </div>
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="time-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['avaliacoesPendentes'] }}</div>
                  <div class="dashboard-stat-label">Pendentes</div>
                </div>
              </div>
            </div>

            <!-- Avaliações Recentes -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="star-outline"></ion-icon>
                  Avaliações Recentes
                </h3>
              </div>
              <div class="dashboard-section-content">
                @if ($avaliacoesRecentes->count() > 0)
                  <div class="dashboard-list">
                    @foreach ($avaliacoesRecentes as $review)
                      <div class="dashboard-list-item">
                        <div class="dashboard-list-image">
                          <img src="{{ asset('products/' . $review->product->image) }}" alt="{{ $review->product->name }}">
                        </div>
                        <div class="dashboard-list-content">
                          <h4 class="dashboard-list-title">{{ $review->product->name }}</h4>
                          <p class="dashboard-list-meta">
                            {{ $review->rating }} estrelas •
                            {{ $review->created_at->format('d/m/Y') }}
                          </p>
                        </div>
                        <div class="dashboard-list-actions">
                          <span class="dashboard-status-badge {{ $review->status }}">
                            @switch($review->status)
                              @case('pending') Pendente @break
                              @case('approved') Aprovada @break
                              @case('rejected') Rejeitada @break
                            @endswitch
                          </span>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="dashboard-empty-state">
                    <ion-icon name="star-outline"></ion-icon>
                    <h3>Nenhuma avaliação ainda</h3>
                    <p>Avalie seus produtos para ajudar outros clientes</p>
                  </div>
                @endif
              </div>
            </div>

            <!-- Configurações Rápidas -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="settings-outline"></ion-icon>
                  Configurações Rápidas
                </h3>
              </div>
              <div class="dashboard-section-content">
                <div class="dashboard-settings-grid">
                  <a href="{{ route('shop.profile.show') }}" class="dashboard-setting-card">
                    <div class="dashboard-setting-icon">
                      <ion-icon name="person-outline"></ion-icon>
                    </div>
                    <h4 class="dashboard-setting-title">Perfil</h4>
                    <p class="dashboard-setting-description">Informações pessoais</p>
                  </a>
                  <a href="{{ route('shop.profile.show') }}" class="dashboard-setting-card">
                    <div class="dashboard-setting-icon">
                      <ion-icon name="lock-closed-outline"></ion-icon>
                    </div>
                    <h4 class="dashboard-setting-title">Senha</h4>
                    <p class="dashboard-setting-description">Alterar senha</p>
                  </a>
                  <a href="{{ route('enderecos.index') }}" class="dashboard-setting-card">
                    <div class="dashboard-setting-icon">
                      <ion-icon name="location-outline"></ion-icon>
                    </div>
                    <h4 class="dashboard-setting-title">Endereços</h4>
                    <p class="dashboard-setting-description">Gerenciar endereços</p>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    // Dados do dashboard para o JavaScript (já formatados no controller)
    window.dashboardData = @json($chartData);
  </script>
  @vite(['resources/js/dashboard.js'])
@endpush
