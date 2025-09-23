@extends('layouts.app')

@section('title', 'Detalhes do Usuário - ' . $user->name)

@section('content')
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <!-- Header da Página -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-header-content">
                        <div class="admin-header-info">
                            <div class="admin-breadcrumb">
                                <a href="{{ route('admin.users.index') }}">Usuários</a>
                                <ion-icon name="chevron-forward"></ion-icon>
                                <span>{{ $user->name }}</span>
                            </div>
                            <h1 class="admin-card-title">
                                <ion-icon name="person-outline"></ion-icon>
                                {{ $user->name }}
                            </h1>
                            <p class="admin-card-subtitle">Detalhes completos do usuário</p>
                        </div>
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.users.edit', $user) }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="create-outline"></ion-icon>
                                Editar
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="arrow-back-outline"></ion-icon>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sistema de Abas -->
            <div class="admin-tabs">
                <!-- Navegação das Abas -->
                <div class="admin-tabs-nav">
                    <button class="admin-tab-btn active" data-tab="overview">
                        <ion-icon name="eye-outline"></ion-icon>
                        Visão Geral
                    </button>
                    <button class="admin-tab-btn" data-tab="orders">
                        <ion-icon name="receipt-outline"></ion-icon>
                        Pedidos
                    </button>
                    <button class="admin-tab-btn" data-tab="reviews">
                        <ion-icon name="star-outline"></ion-icon>
                        Avaliações
                    </button>
                    <button class="admin-tab-btn" data-tab="addresses">
                        <ion-icon name="location-outline"></ion-icon>
                        Endereços
                    </button>
                </div>

                <!-- Conteúdo das Abas -->
                <div class="admin-tabs-content">
                    <!-- Aba: Visão Geral -->
                    <div id="overview" class="admin-tab-panel active">
                        <div class="admin-tab-content">
                            <!-- Informações Básicas -->
                            <div class="admin-info-section">
                                <h3 class="admin-section-title">
                                    <ion-icon name="information-circle-outline"></ion-icon>
                                    Informações Básicas
                                </h3>
                                <div class="admin-info-grid">
                                    <div class="admin-info-item">
                                        <span class="admin-info-label">Nome:</span>
                                        <span class="admin-info-value">{{ $user->name }}</span>
                                    </div>
                                    <div class="admin-info-item">
                                        <span class="admin-info-label">Email:</span>
                                        <span class="admin-info-value">{{ $user->email }}</span>
                                    </div>
                                    @if($user->phone)
                                        <div class="admin-info-item">
                                            <span class="admin-info-label">Telefone:</span>
                                            <span class="admin-info-value">{{ $user->phone }}</span>
                                        </div>
                                    @endif
                                    <div class="admin-info-item">
                                        <span class="admin-info-label">Status:</span>
                                        <span class="admin-info-value">
                                            @if($user->active)
                                                <span class="admin-status-badge status-active">
                                                    <ion-icon name="checkmark-circle"></ion-icon>
                                                    Ativo
                                                </span>
                                            @else
                                                <span class="admin-status-badge status-inactive">
                                                    <ion-icon name="close-circle"></ion-icon>
                                                    Inativo
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="admin-info-item">
                                        <span class="admin-info-label">Funções:</span>
                                        <span class="admin-info-value">
                                            <div class="admin-user-roles">
                                                @foreach($user->roles as $role)
                                                    <span class="admin-role-badge role-{{ $role->name }}">
                                                        {{ ucfirst($role->name) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </span>
                                    </div>
                                    <div class="admin-info-item">
                                        <span class="admin-info-label">Cadastro:</span>
                                        <span class="admin-info-value">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="admin-info-item">
                                        <span class="admin-info-label">Última Atualização:</span>
                                        <span class="admin-info-value">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Estatísticas do Usuário -->
                            <div class="admin-stats-section">
                                <h3 class="admin-section-title">
                                    <ion-icon name="analytics-outline"></ion-icon>
                                    Estatísticas
                                </h3>
                                <div class="admin-stats-grid">
                                    <div class="admin-stat-card">
                                        <div class="admin-stat-icon">
                                            <ion-icon name="receipt-outline"></ion-icon>
                                        </div>
                                        <div class="admin-stat-content">
                                            <div class="admin-stat-value">{{ $userStats['total_orders'] }}</div>
                                            <div class="admin-stat-label">Total de Pedidos</div>
                                        </div>
                                    </div>
                                    <div class="admin-stat-card">
                                        <div class="admin-stat-icon">
                                            <ion-icon name="cash-outline"></ion-icon>
                                        </div>
                                        <div class="admin-stat-content">
                                            <div class="admin-stat-value">€{{ number_format($userStats['total_spent'], 2, ',', '.') }}</div>
                                            <div class="admin-stat-label">Total Gasto</div>
                                        </div>
                                    </div>
                                    <div class="admin-stat-card">
                                        <div class="admin-stat-icon">
                                            <ion-icon name="star-outline"></ion-icon>
                                        </div>
                                        <div class="admin-stat-content">
                                            <div class="admin-stat-value">{{ $userStats['total_reviews'] }}</div>
                                            <div class="admin-stat-label">Avaliações</div>
                                        </div>
                                    </div>
                                    <div class="admin-stat-card">
                                        <div class="admin-stat-icon">
                                            <ion-icon name="star-half-outline"></ion-icon>
                                        </div>
                                        <div class="admin-stat-content">
                                            <div class="admin-stat-value">{{ number_format($userStats['avg_rating_given'], 1) }}</div>
                                            <div class="admin-stat-label">Avaliação Média</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Último Pedido -->
                            @if($userStats['last_order'])
                                <div class="admin-last-order-section">
                                    <h3 class="admin-section-title">
                                        <ion-icon name="receipt-outline"></ion-icon>
                                        Último Pedido
                                    </h3>
                                    <div class="admin-last-order-card">
                                        <div class="admin-last-order-header">
                                            <div class="admin-last-order-info">
                                                <h4 class="admin-last-order-title">Pedido #{{ $userStats['last_order']->id }}</h4>
                                                <p class="admin-last-order-date">{{ $userStats['last_order']->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <div class="admin-last-order-status">
                                                <span class="admin-status-badge status-{{ $userStats['last_order']->status }}">
                                                    {{ translateOrderStatus($userStats['last_order']->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="admin-last-order-footer">
                                            <div class="admin-last-order-total">
                                                <strong>Total: €{{ number_format($userStats['last_order']->total, 2, ',', '.') }}</strong>
                                            </div>
                                            <a href="{{ route('admin.orders.show', $userStats['last_order']) }}" class="admin-btn admin-btn-primary admin-btn-sm">
                                                Ver Detalhes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Aba: Pedidos -->
                    <div id="orders" class="admin-tab-panel">
                        <div class="admin-tab-content">
                            <h3 class="admin-section-title">
                                <ion-icon name="receipt-outline"></ion-icon>
                                Histórico de Pedidos
                            </h3>
                            @if($user->orders->count() > 0)
                                <div class="admin-orders-list">
                                    @foreach($user->orders as $order)
                                        <div class="admin-order-item">
                                            <div class="admin-order-info">
                                                <h4>Pedido #{{ $order->id }}</h4>
                                                <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <div class="admin-order-status">
                                                <span class="admin-status-badge status-{{ $order->status }}">
                                                    {{ translateOrderStatus($order->status) }}
                                                </span>
                                            </div>
                                            <div class="admin-order-total">
                                                €{{ number_format($order->total, 2, ',', '.') }}
                                            </div>
                                            <div class="admin-order-actions">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                                    Ver Detalhes
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="admin-empty-state">
                                    <ion-icon name="receipt-outline"></ion-icon>
                                    <h3>Nenhum pedido encontrado</h3>
                                    <p>Este usuário ainda não realizou nenhum pedido.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Aba: Avaliações -->
                    <div id="reviews" class="admin-tab-panel">
                        <div class="admin-tab-content">
                            <h3 class="admin-section-title">
                                <ion-icon name="star-outline"></ion-icon>
                                Avaliações Realizadas
                            </h3>
                            @if($user->reviews->count() > 0)
                                <div class="admin-reviews-list">
                                    @foreach($user->reviews as $review)
                                        <div class="admin-review-item">
                                            <div class="admin-review-header">
                                                <div class="admin-review-product">
                                                    <h4>{{ $review->product->name }}</h4>
                                                    <div class="admin-review-rating">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <ion-icon name="{{ $i <= $review->rating ? 'star' : 'star-outline' }}"></ion-icon>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="admin-review-status">
                                                <span class="admin-status-badge status-{{ $review->status }}">
                                                    {{ translateOrderStatus($review->status) }}
                                                </span>
                                                </div>
                                            </div>
                                            <div class="admin-review-content">
                                                <h5>{{ $review->title }}</h5>
                                                <p>{{ $review->comment }}</p>
                                            </div>
                                            <div class="admin-review-footer">
                                                <span class="admin-review-date">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                                <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-primary">
                                                    Ver Detalhes
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="admin-empty-state">
                                    <ion-icon name="star-outline"></ion-icon>
                                    <h3>Nenhuma avaliação encontrada</h3>
                                    <p>Este usuário ainda não avaliou nenhum produto.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Aba: Endereços -->
                    <div id="addresses" class="admin-tab-panel">
                        <div class="admin-tab-content">
                            <h3 class="admin-section-title">
                                <ion-icon name="location-outline"></ion-icon>
                                Endereços Cadastrados
                            </h3>
                            @if($user->addresses->count() > 0)
                                <div class="admin-addresses-list">
                                    @foreach($user->addresses as $address)
                                        <div class="admin-address-item">
                                            <div class="admin-address-header">
                                                <h4>{{ $address->name }}</h4>
                                                @if($address->is_default)
                                                    <span class="admin-status-badge status-active">Padrão</span>
                                                @endif
                                            </div>
                                            <div class="admin-address-content">
                                                <p>{{ $address->address_line1 }}</p>
                                                @if($address->address_line2)
                                                    <p>{{ $address->address_line2 }}</p>
                                                @endif
                                                <p>{{ $address->city }}, {{ $address->state }} - {{ $address->zipcode }}</p>
                                                <p>{{ $address->country }}</p>
                                                @if($address->phone)
                                                    <p>Telefone: {{ $address->phone }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="admin-empty-state">
                                    <ion-icon name="location-outline"></ion-icon>
                                    <h3>Nenhum endereço cadastrado</h3>
                                    <p>Este usuário ainda não cadastrou nenhum endereço.</p>
                                </div>
                            @endif
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
</script>
@endsection
