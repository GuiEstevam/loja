{{-- resources/views/admin/reviews/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Moderação de Reviews')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header-content">
            <h1 class="admin-title">
                <svg class="admin-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Moderação de Reviews
            </h1>
            <p class="admin-subtitle">Gerencie avaliações dos clientes</p>
        </div>
        
        <div class="admin-actions">
            <a href="{{ route('admin.reviews.stats') }}" class="btn btn-secondary">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                </svg>
                Estatísticas
            </a>
        </div>
    </div>

    <!-- Estatísticas Resumidas -->
    <div class="admin-stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-pending">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pendentes</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-approved">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['approved'] }}</div>
                <div class="stat-label">Aprovadas</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-rejected">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['rejected'] }}</div>
                <div class="stat-label">Rejeitadas</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-total">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="admin-filters">
        <form method="GET" class="filters-form">
            <div class="filter-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="filter-select">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovadas</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitadas</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="rating">Avaliação:</label>
                <select name="rating" id="rating" class="filter-select">
                    <option value="">Todas</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 estrelas</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 estrelas</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 estrelas</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 estrelas</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 estrela</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="verified">Compra Verificada:</label>
                <select name="verified" id="verified" class="filter-select">
                    <option value="">Todas</option>
                    <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Sim</option>
                    <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Não</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="search">Buscar:</label>
                <input type="text" name="search" id="search" class="filter-input" 
                       placeholder="Nome, produto, comentário..." value="{{ request('search') }}">
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Limpar</a>
            </div>
        </form>
    </div>

    <!-- Ações em Lote -->
    @if($reviews->where('status', 'pending')->count() > 0)
        <div class="admin-bulk-actions">
            <div class="bulk-actions-header">
                <h3>Ações em Lote</h3>
                <p>Selecione reviews pendentes para aprovar ou rejeitar em lote</p>
            </div>
            
            <div class="bulk-actions-content">
                <div class="bulk-selection">
                    <label class="checkbox-label">
                        <input type="checkbox" id="select-all-pending">
                        <span class="checkmark"></span>
                        Selecionar todas pendentes
                    </label>
                </div>
                
                <div class="bulk-buttons">
                    <button type="button" class="btn btn-success" onclick="approveSelected()">
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Aprovar Selecionadas
                    </button>
                    
                    <button type="button" class="btn btn-danger" onclick="rejectSelected()">
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                        </svg>
                        Rejeitar Selecionadas
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Lista de Reviews -->
    <div class="admin-table-container">
        <div class="admin-table-header">
            <h3>Reviews ({{ $reviews->total() }})</h3>
        </div>
        
        @if($reviews->count() > 0)
            <div class="admin-table">
                <div class="table-header">
                    <div class="table-cell">Cliente</div>
                    <div class="table-cell">Produto</div>
                    <div class="table-cell">Avaliação</div>
                    <div class="table-cell">Status</div>
                    <div class="table-cell">Data</div>
                    <div class="table-cell">Ações</div>
                </div>
                
                @foreach($reviews as $review)
                    <div class="table-row" data-review-id="{{ $review->id }}">
                        <div class="table-cell">
                            <div class="user-info">
                                <div class="user-avatar">{{ substr($review->user->name, 0, 1) }}</div>
                                <div class="user-details">
                                    <div class="user-name">{{ $review->user->name }}</div>
                                    @if($review->verified_purchase)
                                        <div class="verified-badge">
                                            <svg class="badge-icon" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                            </svg>
                                            Verificado
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-cell">
                            <div class="product-info">
                                <div class="product-name">{{ $review->product->name }}</div>
                            </div>
                        </div>
                        
                        <div class="table-cell">
                            <div class="rating-display">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="star {{ $i <= $review->rating ? 'filled' : '' }}" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <div class="rating-text">{{ $review->rating_label }}</div>
                            </div>
                        </div>
                        
                        <div class="table-cell">
                            <span class="status-badge status-{{ $review->status }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </div>
                        
                        <div class="table-cell">
                            <div class="date-info">
                                <div class="date">{{ $review->created_at->format('d/m/Y') }}</div>
                                <div class="time">{{ $review->created_at->format('H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="table-cell">
                            <div class="action-buttons">
                                @if($review->status === 'pending')
                                    <input type="checkbox" class="review-checkbox" value="{{ $review->id }}">
                                @endif
                                
                                <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-secondary">
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                    </svg>
                                    Ver
                                </a>
                                
                                @if($review->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-success" onclick="approveReview({{ $review->id }})">
                                        <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                        </svg>
                                        Aprovar
                                    </button>
                                    
                                    <button type="button" class="btn btn-sm btn-danger" onclick="rejectReview({{ $review->id }})">
                                        <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                        </svg>
                                        Rejeitar
                                    </button>
                                @endif
                                
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteReview({{ $review->id }})">
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                    </svg>
                                    Excluir
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="admin-pagination">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        @else
            <div class="admin-empty-state">
                <svg class="empty-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <h3>Nenhuma review encontrada</h3>
                <p>Não há reviews que correspondam aos filtros aplicados.</p>
            </div>
        @endif
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

.admin-header-content {
    display: flex;
    align-items: center;
    gap: 16px;
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

.admin-subtitle {
    color: #6b7280;
    margin: 0;
    font-size: 1.125rem;
}

.admin-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon svg {
    width: 24px;
    height: 24px;
}

.stat-pending {
    background: #fef3c7;
    color: #d97706;
}

.stat-approved {
    background: #d1fae5;
    color: #059669;
}

.stat-rejected {
    background: #fee2e2;
    color: #dc2626;
}

.stat-total {
    background: #e0e7ff;
    color: #3730a3;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
}

.admin-filters {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.filters-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group label {
    font-weight: 500;
    color: #374151;
    font-size: 0.875rem;
}

.filter-select,
.filter-input {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.2s ease;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.filter-actions {
    display: flex;
    gap: 12px;
}

.admin-bulk-actions {
    background: #f9fafb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    border: 1px solid #e5e7eb;
}

.bulk-actions-header h3 {
    margin: 0 0 8px 0;
    color: #1f2937;
    font-size: 1.125rem;
}

.bulk-actions-header p {
    margin: 0 0 16px 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.bulk-actions-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 0.875rem;
    color: #374151;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 16px;
    height: 16px;
    border: 2px solid #d1d5db;
    border-radius: 3px;
    position: relative;
    transition: all 0.2s ease;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: #3b82f6;
    border-color: #3b82f6;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: -2px;
    left: 2px;
    color: white;
    font-size: 12px;
}

.bulk-buttons {
    display: flex;
    gap: 12px;
}

.admin-table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.admin-table-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.admin-table-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 1.125rem;
}

.admin-table {
    display: flex;
    flex-direction: column;
}

.table-header {
    display: grid;
    grid-template-columns: 2fr 2fr 1fr 1fr 1fr 2fr;
    gap: 16px;
    padding: 16px 24px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.table-row {
    display: grid;
    grid-template-columns: 2fr 2fr 1fr 1fr 1fr 2fr;
    gap: 16px;
    padding: 20px 24px;
    border-bottom: 1px solid #f3f4f6;
    align-items: center;
    transition: background-color 0.2s ease;
}

.table-row:hover {
    background: #f9fafb;
}

.table-cell {
    display: flex;
    align-items: center;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #3b82f6;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.125rem;
}

.user-name {
    font-weight: 500;
    color: #1f2937;
}

.verified-badge {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: #059669;
    font-weight: 500;
}

.badge-icon {
    width: 12px;
    height: 12px;
}

.product-name {
    font-weight: 500;
    color: #1f2937;
}

.rating-display {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.rating-stars {
    display: flex;
    gap: 2px;
}

.rating-stars .star {
    width: 16px;
    height: 16px;
    color: #d1d5db;
}

.rating-stars .star.filled {
    color: #fbbf24;
}

.rating-text {
    font-size: 0.75rem;
    color: #6b7280;
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

.date-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.date {
    font-weight: 500;
    color: #1f2937;
}

.time {
    font-size: 0.75rem;
    color: #6b7280;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.review-checkbox {
    margin-right: 8px;
}

.btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.75rem;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #e5e7eb;
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

.btn-icon {
    width: 14px;
    height: 14px;
}

.admin-pagination {
    padding: 20px 24px;
    display: flex;
    justify-content: center;
    border-top: 1px solid #e5e7eb;
}

.admin-empty-state {
    text-align: center;
    padding: 48px 24px;
}

.empty-icon {
    width: 48px;
    height: 48px;
    color: #d1d5db;
    margin-bottom: 16px;
}

.admin-empty-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin: 0 0 8px 0;
}

.admin-empty-state p {
    color: #6b7280;
    margin: 0;
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
    
    .admin-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filters-form {
        grid-template-columns: 1fr;
    }
    
    .bulk-actions-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .table-cell {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleção de todas as reviews pendentes
    const selectAllCheckbox = document.getElementById('select-all-pending');
    const reviewCheckboxes = document.querySelectorAll('.review-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            reviewCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});

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
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }
}

function approveSelected() {
    const selectedCheckboxes = document.querySelectorAll('.review-checkbox:checked');
    const reviewIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (reviewIds.length === 0) {
        alert('Selecione pelo menos uma review para aprovar.');
        return;
    }
    
    if (confirm(`Tem certeza que deseja aprovar ${reviewIds.length} review(s)?`)) {
        fetch('/admin/reviews/approve-multiple', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ review_ids: reviewIds }),
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

function rejectSelected() {
    const selectedCheckboxes = document.querySelectorAll('.review-checkbox:checked');
    const reviewIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (reviewIds.length === 0) {
        alert('Selecione pelo menos uma review para rejeitar.');
        return;
    }
    
    if (confirm(`Tem certeza que deseja rejeitar ${reviewIds.length} review(s)?`)) {
        fetch('/admin/reviews/reject-multiple', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ review_ids: reviewIds }),
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
</script>
@endsection
