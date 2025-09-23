{{-- resources/views/admin/reviews/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detalhes da Review')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header-content">
            <a href="{{ route('admin.reviews.index') }}" class="back-link">
                <svg class="back-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Voltar para Reviews
            </a>
            
            <h1 class="admin-title">
                <svg class="admin-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Detalhes da Review
            </h1>
        </div>
        
        <div class="admin-actions">
            @if($review->status === 'pending')
                <button type="button" class="btn btn-success" onclick="approveReview({{ $review->id }})">
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    Aprovar Review
                </button>
                
                <button type="button" class="btn btn-danger" onclick="rejectReview({{ $review->id }})">
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                    Rejeitar Review
                </button>
            @endif
            
            <button type="button" class="btn btn-danger" onclick="deleteReview({{ $review->id }})">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                </svg>
                Excluir Review
            </button>
        </div>
    </div>

    <div class="review-detail-grid">
        <!-- Informações da Review -->
        <div class="review-detail-card">
            <div class="card-header">
                <h3>Informações da Review</h3>
                <span class="status-badge status-{{ $review->status }}">
                    {{ ucfirst($review->status) }}
                </span>
            </div>
            
            <div class="card-content">
                <div class="review-rating-section">
                    <div class="rating-display">
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="star {{ $i <= $review->rating ? 'filled' : '' }}" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            @endfor
                        </div>
                        <div class="rating-info">
                            <div class="rating-value">{{ $review->rating }}/5</div>
                            <div class="rating-label">{{ $review->rating_label }}</div>
                        </div>
                    </div>
                </div>
                
                @if($review->title)
                    <div class="review-section">
                        <h4>Título</h4>
                        <p class="review-title">{{ $review->title }}</p>
                    </div>
                @endif
                
                @if($review->comment)
                    <div class="review-section">
                        <h4>Comentário</h4>
                        <div class="review-comment">{{ $review->comment }}</div>
                    </div>
                @endif
                
                <div class="review-meta">
                    <div class="meta-item">
                        <span class="meta-label">Data da Avaliação:</span>
                        <span class="meta-value">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Útil:</span>
                        <span class="meta-value">{{ $review->helpful_count }} pessoa(s)</span>
                    </div>
                    
                    @if($review->verified_purchase)
                        <div class="meta-item">
                            <span class="meta-label">Compra Verificada:</span>
                            <span class="meta-value verified">
                                <svg class="verified-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                </svg>
                                Sim
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informações do Cliente -->
        <div class="review-detail-card">
            <div class="card-header">
                <h3>Informações do Cliente</h3>
            </div>
            
            <div class="card-content">
                <div class="user-profile">
                    <div class="user-avatar">{{ substr($review->user->name, 0, 1) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ $review->user->name }}</div>
                        <div class="user-email">{{ $review->user->email }}</div>
                        <div class="user-stats">
                            <div class="stat-item">
                                <span class="stat-label">Reviews:</span>
                                <span class="stat-value">{{ $review->user->total_reviews }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Média:</span>
                                <span class="stat-value">{{ number_format($review->user->average_rating_given, 1) }}/5</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($review->order)
                    <div class="order-info">
                        <h4>Pedido Relacionado</h4>
                        <div class="order-details">
                            <div class="order-item">
                                <span class="order-label">Número:</span>
                                <span class="order-value">#{{ $review->order->id }}</span>
                            </div>
                            <div class="order-item">
                                <span class="order-label">Data:</span>
                                <span class="order-value">{{ $review->order->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="order-item">
                                <span class="order-label">Status:</span>
                                <span class="order-value">{{ ucfirst($review->order->status) }}</span>
                            </div>
                            <div class="order-item">
                                <span class="order-label">Total:</span>
                                <span class="order-value">€ {{ number_format($review->order->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Informações do Produto -->
        <div class="review-detail-card">
            <div class="card-header">
                <h3>Informações do Produto</h3>
            </div>
            
            <div class="card-content">
                <div class="product-info">
                    <div class="product-image">
                        <img src="{{ asset('products/' . $review->product->image) }}" alt="{{ $review->product->name }}">
                    </div>
                    <div class="product-details">
                        <div class="product-name">{{ $review->product->name }}</div>
                        <div class="product-sku">SKU: {{ $review->product->sku }}</div>
                        <div class="product-price">€ {{ number_format($review->product->price, 2, ',', '.') }}</div>
                        
                        <div class="product-stats">
                            <div class="stat-item">
                                <span class="stat-label">Avaliação Média:</span>
                                <span class="stat-value">{{ number_format($review->product->average_rating, 1) }}/5</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total de Reviews:</span>
                                <span class="stat-value">{{ $review->product->total_reviews }}</span>
                            </div>
                        </div>
                        
                        <div class="product-actions">
                            <a href="{{ route('shop.products.show', $review->product) }}" class="btn btn-secondary" target="_blank">
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                                Ver Produto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.admin-container {
    padding: 24px;
    max-width: 1400px;
    margin: 0 auto;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid #e5e7eb;
}

.back-link {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    text-decoration: none;
    font-size: 0.875rem;
    margin-bottom: 16px;
    transition: color 0.2s ease;
}

.back-link:hover {
    color: #3b82f6;
}

.back-icon {
    width: 16px;
    height: 16px;
}

.admin-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.admin-icon {
    width: 32px;
    height: 32px;
    color: #3b82f6;
}

.admin-actions {
    display: flex;
    gap: 12px;
}

.review-detail-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

.review-detail-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.card-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.status-approved {
    background: #d1fae5;
    color: #059669;
}

.status-rejected {
    background: #fee2e2;
    color: #dc2626;
}

.card-content {
    padding: 24px;
}

.review-rating-section {
    margin-bottom: 24px;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 16px;
}

.rating-stars {
    display: flex;
    gap: 4px;
}

.rating-stars .star {
    width: 24px;
    height: 24px;
    color: #d1d5db;
}

.rating-stars .star.filled {
    color: #fbbf24;
}

.rating-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.rating-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.rating-label {
    color: #6b7280;
    font-size: 0.875rem;
}

.review-section {
    margin-bottom: 20px;
}

.review-section h4 {
    margin: 0 0 8px 0;
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
}

.review-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.review-comment {
    background: #f9fafb;
    padding: 16px;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
    color: #374151;
    line-height: 1.6;
    margin: 0;
}

.review-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.meta-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.meta-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.meta-value {
    color: #1f2937;
    font-weight: 500;
}

.meta-value.verified {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #059669;
}

.verified-icon {
    width: 14px;
    height: 14px;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #3b82f6;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.5rem;
}

.user-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.user-email {
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 8px;
}

.user-stats {
    display: flex;
    gap: 16px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
}

.stat-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
}

.order-info {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.order-info h4 {
    margin: 0 0 12px 0;
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
}

.order-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

.order-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.order-label {
    font-size: 0.75rem;
    color: #6b7280;
}

.order-value {
    font-size: 0.875rem;
    font-weight: 500;
    color: #1f2937;
}

.product-info {
    display: flex;
    gap: 16px;
}

.product-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-details {
    flex: 1;
}

.product-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.product-sku {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 4px;
}

.product-price {
    font-size: 1rem;
    font-weight: 600;
    color: #059669;
    margin-bottom: 12px;
}

.product-stats {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
}

.product-actions {
    margin-top: 16px;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.btn-icon {
    width: 16px;
    height: 16px;
}

@media (max-width: 768px) {
    .admin-container {
        padding: 16px;
    }
    
    .admin-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .admin-actions {
        width: 100%;
        flex-wrap: wrap;
    }
    
    .review-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .user-profile {
        flex-direction: column;
        text-align: center;
    }
    
    .product-info {
        flex-direction: column;
    }
    
    .product-image {
        width: 100%;
        height: 200px;
    }
}
</style>

<script>
function approveReview(reviewId) {
    if (confirm('Tem certeza que deseja aprovar esta review?')) {
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
    if (confirm('Tem certeza que deseja rejeitar esta review?')) {
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
    if (confirm('Tem certeza que deseja excluir esta review? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/reviews/${reviewId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/admin/reviews';
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }
}
</script>
@endsection
