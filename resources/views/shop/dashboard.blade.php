@extends('layouts.app')

@section('title', 'Minha Conta')

@push('styles')
  @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
  <div class="dashboard-page">
    <div class="dashboard-container">
      <!-- Header da Página -->
      <div class="dashboard-header">
        <h1 class="dashboard-title">
          <ion-icon name="person-circle-outline"></ion-icon>
          Minha Conta
        </h1>
        <p class="dashboard-subtitle">Gerencie seus pedidos, endereços e informações pessoais</p>
        <p class="dashboard-welcome">Olá, {{ $user->name }}! 👋</p>
      </div>

      <!-- Estatísticas Rápidas -->
      <div class="dashboard-stats">
        <div class="stat-card">
          <div class="stat-value">{{ $totalOrders }}</div>
          <div class="stat-label">Total de Pedidos</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ $pendingOrders }}</div>
          <div class="stat-label">Pedidos Pendentes</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">€{{ number_format($totalSpent, 2, ',', '.') }}</div>
          <div class="stat-label">Total Gasto</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ $savedAddresses }}</div>
          <div class="stat-label">Endereços Salvos</div>
        </div>
      </div>

      <!-- Cards Principais -->
      <div class="dashboard-cards">
        <div class="dashboard-card">
          <a href="{{ route('shop.orders.index') }}" class="dashboard-card-link">
            <div class="dashboard-card-icon">
              <ion-icon name="bag-outline"></ion-icon>
            </div>
            <h3 class="dashboard-card-title">Meus Pedidos</h3>
            <p class="dashboard-card-description">Acompanhe todos os seus pedidos e status de entrega</p>
          </a>
        </div>

        <div class="dashboard-card">
          <a href="{{ route('enderecos.index') }}" class="dashboard-card-link">
            <div class="dashboard-card-icon">
              <ion-icon name="location-outline"></ion-icon>
            </div>
            <h3 class="dashboard-card-title">Meus Endereços</h3>
            <p class="dashboard-card-description">Gerencie seus endereços de entrega salvos</p>
          </a>
        </div>

        <div class="dashboard-card">
          <a href="{{ route('shop.favorites.index') }}" class="dashboard-card-link">
            <div class="dashboard-card-icon">
              <ion-icon name="heart-outline"></ion-icon>
            </div>
            <h3 class="dashboard-card-title">Favoritos</h3>
            <p class="dashboard-card-description">Veja os produtos que você salvou para comprar depois</p>
          </a>
        </div>

        <div class="dashboard-card">
          <a href="{{ route('profile.show') }}" class="dashboard-card-link">
            <div class="dashboard-card-icon">
              <ion-icon name="settings-outline"></ion-icon>
            </div>
            <h3 class="dashboard-card-title">Configurações</h3>
            <p class="dashboard-card-description">Atualize suas informações pessoais e senha</p>
          </a>
        </div>
      </div>

      <!-- Seções Específicas -->
      <div class="dashboard-sections">
        <!-- Pedidos Recentes -->
        <div class="dashboard-section">
          <div class="section-header">
            <h2 class="section-title">
              <ion-icon name="time-outline"></ion-icon>
              Pedidos Recentes
            </h2>
            <a href="{{ route('shop.orders.index') }}" class="section-action">
              <ion-icon name="arrow-forward-outline"></ion-icon>
              Ver Todos
            </a>
          </div>
          <div class="section-content">
            @if ($orders->count() > 0)
              <div class="items-list">
                @foreach ($orders as $order)
                  <div class="item-card">
                    <div class="item-image">
                      <ion-icon name="receipt-outline"></ion-icon>
                    </div>
                    <div class="item-details">
                      <h3 class="item-title">Pedido #{{ $order->id }}</h3>
                      <p class="item-meta">
                        {{ $order->created_at->format('d/m/Y H:i') }} •
                        {{ $order->items->count() }} item(s) •
                        €{{ number_format($order->total, 2, ',', '.') }}
                      </p>
                    </div>
                    <span class="item-status {{ $order->status }}">
                      @switch($order->status)
                        @case('pending')
                          Pendente
                        @break

                        @case('processing')
                          Processando
                        @break

                        @case('shipped')
                          Enviado
                        @break

                        @case('delivered')
                          Entregue
                        @break

                        @default
                          {{ ucfirst($order->status) }}
                      @endswitch
                    </span>
                    <a href="{{ route('shop.orders.show', $order) }}" class="item-action">
                      Ver Detalhes
                    </a>
                  </div>
                @endforeach
              </div>
            @else
              <div class="empty-state">
                <ion-icon name="bag-outline"></ion-icon>
                <h3>Nenhum pedido ainda</h3>
                <p>Você ainda não fez nenhum pedido. Que tal começar a comprar?</p>
                <a href="{{ route('shop.products.index') }}" class="section-action">
                  <ion-icon name="bag-outline"></ion-icon>
                  Ver Produtos
                </a>
              </div>
            @endif
          </div>
        </div>

        <!-- Endereços Salvos -->
        <div class="dashboard-section">
          <div class="section-header">
            <h2 class="section-title">
              <ion-icon name="location-outline"></ion-icon>
              Endereços Salvos
            </h2>
            <a href="{{ route('enderecos.index') }}" class="section-action">
              <ion-icon name="add-outline"></ion-icon>
              Adicionar Novo
            </a>
          </div>
          <div class="section-content">
            @if ($addresses->count() > 0)
              <div class="items-list">
                @foreach ($addresses as $address)
                  <div class="item-card">
                    <div class="item-image">
                      <ion-icon name="home-outline"></ion-icon>
                    </div>
                    <div class="item-details">
                      <h3 class="item-title">
                        {{ $address->address_line1 }}, {{ $address->number }}
                        @if ($address->is_default)
                          <span class="item-status delivered">Padrão</span>
                        @endif
                      </h3>
                      <p class="item-meta">
                        {{ $address->neighborhood }}, {{ $address->city }}/{{ $address->state }} •
                        CEP: {{ $address->zipcode }}
                      </p>
                    </div>
                    <a href="{{ route('enderecos.edit', $address) }}" class="item-action">
                      Editar
                    </a>
                  </div>
                @endforeach
              </div>
            @else
              <div class="empty-state">
                <ion-icon name="location-outline"></ion-icon>
                <h3>Nenhum endereço salvo</h3>
                <p>Adicione um endereço para facilitar suas próximas compras</p>
                <a href="{{ route('enderecos.create') }}" class="section-action">
                  <ion-icon name="add-outline"></ion-icon>
                  Adicionar Endereço
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
