@extends('layouts.app')

@section('title', 'Meus Pedidos')

@push('styles')
  @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
      <div class="dashboard-page">
        <div class="dashboard-container">
          <!-- Breadcrumb -->
          <nav class="dashboard-breadcrumb">
            <div class="dashboard-breadcrumb-nav">
              <a href="{{ route('shop.dashboard') }}" class="dashboard-breadcrumb-item">
                <ion-icon name="home-outline"></ion-icon>
                Dashboard
              </a>
              <span class="dashboard-breadcrumb-separator">›</span>
              <span class="dashboard-breadcrumb-item active">
                <ion-icon name="bag-outline"></ion-icon>
                Meus Pedidos
              </span>
            </div>
            <a href="{{ route('shop.dashboard') }}" class="dashboard-breadcrumb-back">
              <ion-icon name="arrow-back-outline"></ion-icon>
              Voltar
            </a>
          </nav>

          <!-- Header da Página -->
          <div class="dashboard-header">
            <h1 class="dashboard-title">
              <ion-icon name="bag-outline"></ion-icon>
              Meus Pedidos
            </h1>
            <p class="dashboard-subtitle">Acompanhe todos os seus pedidos e status de entrega</p>
          </div>

      <!-- Navegação Rápida -->
      <div class="dashboard-nav-cards">
        <a href="{{ route('shop.dashboard') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="grid-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Dashboard</h3>
          <p class="nav-card-description">Visão geral</p>
        </a>

        <a href="{{ route('shop.profile.show') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="person-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Perfil</h3>
          <p class="nav-card-description">Configurações</p>
        </a>

        <a href="{{ route('shop.favorites.index') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="heart-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Favoritos</h3>
          <p class="nav-card-description">Produtos salvos</p>
        </a>

        <a href="{{ route('enderecos.index') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="location-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Endereços</h3>
          <p class="nav-card-description">Gerenciar</p>
        </a>
      </div>

      <!-- Sistema de Abas -->
      <div class="dashboard-tabs-container">
        <div class="dashboard-tabs-nav">
          <button class="dashboard-tab-btn active" data-tab="orders">
            <ion-icon name="bag-outline"></ion-icon>
            Todos os Pedidos
          </button>
          <button class="dashboard-tab-btn" data-tab="pending">
            <ion-icon name="time-outline"></ion-icon>
            Pendentes
          </button>
          <button class="dashboard-tab-btn" data-tab="delivered">
            <ion-icon name="checkmark-circle-outline"></ion-icon>
            Entregues
          </button>
        </div>

        <!-- Conteúdo das Abas -->
        <div class="dashboard-tab-content">
          <!-- Aba: Todos os Pedidos -->
          <div class="dashboard-tab-pane active" id="orders">

            <!-- Lista de Pedidos -->
            @if($orders->count() > 0)
              <div class="dashboard-section">
                <div class="dashboard-section-header">
                  <h3 class="dashboard-section-title">
                    <ion-icon name="list-outline"></ion-icon>
                    Histórico de Pedidos
                  </h3>
                </div>
                <div class="dashboard-section-content">
                  <div class="dashboard-list">
                    @foreach($orders as $order)
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
                              @case('paid') Pago @break
                              @case('processing') Processando @break
                              @case('shipped') Enviado @break
                              @case('delivered') Entregue @break
                              @case('cancelled') Cancelado @break
                              @default {{ ucfirst($order->status) }}
                            @endswitch
                          </span>
                          <a href="{{ route('shop.orders.show', $order) }}" class="dashboard-action-btn">
                            <ion-icon name="eye-outline"></ion-icon>
                            Ver Detalhes
                          </a>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            @else
              <!-- Estado Vazio -->
              <div class="dashboard-empty-state">
                <ion-icon name="bag-outline"></ion-icon>
                <h3>Nenhum pedido encontrado</h3>
                <p>Você ainda não fez nenhum pedido. Que tal começar a comprar?</p>
                <a href="{{ route('shop.products.index') }}" class="dashboard-btn">
                  <ion-icon name="storefront-outline"></ion-icon>
                  Ver Produtos
                </a>
              </div>
            @endif
          </div>

          <!-- Aba: Pendentes -->
          <div class="dashboard-tab-pane" id="pending">
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="time-outline"></ion-icon>
                  Pedidos Pendentes
                </h3>
              </div>
              <div class="dashboard-section-content">
                <div class="dashboard-list" id="pending-orders-list">
                  <!-- Pedidos pendentes serão filtrados via JavaScript -->
                </div>
                <div class="dashboard-empty-state" id="pending-empty-state" style="display: none;">
                  <ion-icon name="checkmark-circle-outline"></ion-icon>
                  <h3>Nenhum pedido pendente</h3>
                  <p>Todos os seus pedidos foram processados!</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Aba: Entregues -->
          <div class="dashboard-tab-pane" id="delivered">
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="checkmark-circle-outline"></ion-icon>
                  Pedidos Entregues
                </h3>
              </div>
              <div class="dashboard-section-content">
                <div class="dashboard-list" id="delivered-orders-list">
                  <!-- Pedidos entregues serão filtrados via JavaScript -->
                </div>
                <div class="dashboard-empty-state" id="delivered-empty-state" style="display: none;">
                  <ion-icon name="bag-outline"></ion-icon>
                  <h3>Nenhum pedido entregue ainda</h3>
                  <p>Seus pedidos entregues aparecerão aqui.</p>
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
    // Dados dos pedidos para o JavaScript (já formatados no controller)
    window.ordersData = @json($ordersData);
  </script>
  @vite(['resources/js/orders.js'])
@endpush

