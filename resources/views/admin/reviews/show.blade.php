@extends('layouts.app')

@section('title', 'Detalhes da Avaliação')

@section('content')
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <!-- Header da Página -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <nav class="admin-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">
                            <ion-icon name="home-outline"></ion-icon>
                            Dashboard
                        </a>
                        <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                        <a href="{{ route('admin.reviews.index') }}">Avaliações</a>
                        <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                        <span>Detalhes</span>
                    </nav>

                    <div class="admin-header-content">
                        <div class="admin-header-text">
                            <h1 class="admin-card-title">
                                <ion-icon name="star-outline"></ion-icon>
                                Detalhes da Avaliação
                            </h1>
                            <p class="admin-card-subtitle">Visualização completa da avaliação do cliente</p>
                        </div>
                        
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.reviews.index') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="arrow-back-outline"></ion-icon>
                                Voltar
                            </a>
                            <a href="{{ route('admin.products.show', $review->product) }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="cube-outline"></ion-icon>
                                Ver Produto
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações da Avaliação -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        Informações da Avaliação
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="admin-details-grid">
                        <div class="admin-detail-item">
                            <label class="admin-detail-label">ID da Avaliação</label>
                            <span class="admin-detail-value">#{{ $review->id }}</span>
                        </div>
                        
                        <div class="admin-detail-item">
                            <label class="admin-detail-label">Status</label>
                            <span class="admin-status-badge status-{{ $review->status }}">
                                @if($review->status === 'pending')
                                    <ion-icon name="time-outline"></ion-icon>
                                    Pendente
                                @elseif($review->status === 'approved')
                                    <ion-icon name="checkmark-circle"></ion-icon>
                                    Aprovada
                                @else
                                    <ion-icon name="close-circle"></ion-icon>
                                    Rejeitada
                                @endif
                            </span>
                        </div>
                        
                        <div class="admin-detail-item">
                            <label class="admin-detail-label">Data da Avaliação</label>
                            <span class="admin-detail-value">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        <div class="admin-detail-item">
                            <label class="admin-detail-label">Última Atualização</label>
                            <span class="admin-detail-value">{{ $review->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        @if($review->order)
                            <div class="admin-detail-item">
                                <label class="admin-detail-label">Pedido Relacionado</label>
                                <div class="admin-detail-value">
                                    <a href="{{ route('admin.orders.show', $review->order) }}" class="admin-link">
                                        #{{ $review->order->id }} - {{ $review->order->status }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informações do Cliente e Produto -->
            <div class="admin-info-grid">
                <!-- Informações do Cliente -->
                <div class="admin-info-card admin-info-card-user">
                    <div class="admin-info-header">
                        <div class="admin-info-icon">
                            <ion-icon name="person-outline"></ion-icon>
                        </div>
                        <div class="admin-info-title">
                            <h3>Cliente</h3>
                            <p>Informações do usuário</p>
                        </div>
                    </div>
                    <div class="admin-info-content">
                        <div class="admin-user-profile">
                            <div class="admin-user-avatar">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div class="admin-user-details">
                                <h4 class="admin-user-name">{{ $review->user->name }}</h4>
                                <p class="admin-user-email">{{ $review->user->email }}</p>
                                @if($review->verified_purchase)
                                    <div class="admin-verified-badge">
                                        <ion-icon name="checkmark-circle"></ion-icon>
                                        <span>Compra Verificada</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="admin-info-actions">
                            <a href="{{ route('admin.users.show', $review->user) }}" class="admin-btn admin-btn-primary admin-btn-sm">
                                <ion-icon name="person-outline"></ion-icon>
                                Ver Perfil
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Informações do Produto -->
                <div class="admin-info-card admin-info-card-product">
                    <div class="admin-info-header">
                        <div class="admin-info-icon">
                            <ion-icon name="cube-outline"></ion-icon>
                        </div>
                        <div class="admin-info-title">
                            <h3>Produto</h3>
                            <p>Item avaliado</p>
                        </div>
                    </div>
                    <div class="admin-info-content">
                        <div class="admin-product-showcase">
                            <div class="admin-product-image">
                                @if($review->product->image)
                                    <img src="{{ asset('products/' . $review->product->image) }}" 
                                         alt="{{ $review->product->name }}" 
                                         class="admin-product-img">
                                @else
                                    <div class="admin-product-placeholder">
                                        <ion-icon name="image-outline"></ion-icon>
                                    </div>
                                @endif
                            </div>
                            <div class="admin-product-details">
                                <h4 class="admin-product-name">{{ $review->product->name }}</h4>
                                <div class="admin-product-meta">
                                    <span class="admin-product-sku">SKU: {{ $review->product->sku }}</span>
                                    <span class="admin-product-price">R$ {{ number_format($review->product->price, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="admin-info-actions">
                            <a href="{{ route('admin.products.show', $review->product) }}" class="admin-btn admin-btn-primary admin-btn-sm">
                                <ion-icon name="eye-outline"></ion-icon>
                                Ver Produto
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avaliação -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <ion-icon name="star-outline"></ion-icon>
                        Avaliação
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="admin-review-content">
                        <div class="admin-review-rating">
                            <div class="admin-rating-stars-large">
                                @for($i = 1; $i <= 5; $i++)
                                    <ion-icon name="star" class="admin-star-large {{ $i <= $review->rating ? 'filled' : '' }}"></ion-icon>
                                @endfor
                            </div>
                            <span class="admin-rating-text-large">{{ $review->rating }} de 5 estrelas</span>
                        </div>
                        
                        @if($review->title)
                            <div class="admin-review-title">
                                <h4>{{ $review->title }}</h4>
                            </div>
                        @endif
                        
                        @if($review->comment)
                            <div class="admin-review-comment">
                                <p>{{ $review->comment }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ações -->
            @if($review->status === 'pending')
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">
                            <ion-icon name="settings-outline"></ion-icon>
                            Ações de Moderação
                        </h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-actions-large">
                            <button type="button" class="admin-btn admin-btn-success admin-btn-large" onclick="approveReview({{ $review->id }})">
                                <ion-icon name="checkmark-outline"></ion-icon>
                                Aprovar Avaliação
                            </button>
                            
                            <button type="button" class="admin-btn admin-btn-danger admin-btn-large" onclick="rejectReview({{ $review->id }})">
                                <ion-icon name="close-outline"></ion-icon>
                                Rejeitar Avaliação
                            </button>
                            
                            <button type="button" class="admin-btn admin-btn-danger admin-btn-large" onclick="deleteReview({{ $review->id }})">
                                <ion-icon name="trash-outline"></ion-icon>
                                Excluir Avaliação
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">
                            <ion-icon name="settings-outline"></ion-icon>
                            Ações
                        </h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-actions-large">
                            <button type="button" class="admin-btn admin-btn-danger admin-btn-large" onclick="deleteReview({{ $review->id }})">
                                <ion-icon name="trash-outline"></ion-icon>
                                Excluir Avaliação
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function approveReview(reviewId) {
    if (confirm('Tem certeza que deseja aprovar esta avaliação?')) {
        fetch(`/admin/reviews/${reviewId}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }
}

function rejectReview(reviewId) {
    if (confirm('Tem certeza que deseja rejeitar esta avaliação?')) {
        fetch(`/admin/reviews/${reviewId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }
}

function deleteReview(reviewId) {
    if (confirm('Tem certeza que deseja excluir esta avaliação? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/reviews/${reviewId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("admin.reviews.index") }}';
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }
}
</script>
@endsection