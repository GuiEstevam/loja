@extends('layouts.app')

@section('title', 'Avaliações')

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
                        <span>Avaliações</span>
                    </nav>

                    <div class="admin-header-content">
                        <div class="admin-header-text">
                            <h1 class="admin-card-title">
                                <ion-icon name="star-outline"></ion-icon>
                                Gerenciar Avaliações
                            </h1>
                            <p class="admin-card-subtitle">Moderação e gestão de avaliações dos clientes</p>
                        </div>
                        
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.reviews.stats') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="stats-chart-outline"></ion-icon>
                                Estatísticas
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="home-outline"></ion-icon>
                                Voltar ao Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas Resumidas -->
            <div class="admin-stats-grid">
                <div class="admin-stat-card">
                    <div class="admin-stat-icon admin-stat-pending">
                        <ion-icon name="time-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-number">{{ $stats['pending'] }}</div>
                        <div class="admin-stat-label">Pendentes</div>
                    </div>
                </div>
                
                <div class="admin-stat-card">
                    <div class="admin-stat-icon admin-stat-approved">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-number">{{ $stats['approved'] }}</div>
                        <div class="admin-stat-label">Aprovadas</div>
                    </div>
                </div>
                
                <div class="admin-stat-card">
                    <div class="admin-stat-icon admin-stat-rejected">
                        <ion-icon name="close-circle-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-number">{{ $stats['rejected'] }}</div>
                        <div class="admin-stat-label">Rejeitadas</div>
                    </div>
                </div>
                
                <div class="admin-stat-card">
                    <div class="admin-stat-icon admin-stat-total">
                        <ion-icon name="star-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-number">{{ $stats['total'] }}</div>
                        <div class="admin-stat-label">Total</div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="admin-card">
                <div class="admin-card-body">
                    <form method="GET" class="admin-filters">
                        <div class="admin-filters-main">
                            <div class="admin-form-group">
                                <label for="search" class="admin-form-label">Buscar Avaliação</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="admin-form-input" 
                                       placeholder="Cliente, produto, comentário...">
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="status" class="admin-form-label">Status</label>
                                <select id="status" name="status" class="admin-form-select">
                                    <option value="">Todos</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovadas</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitadas</option>
                                </select>
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="rating" class="admin-form-label">Avaliação</label>
                                <select id="rating" name="rating" class="admin-form-select">
                                    <option value="">Todas</option>
                                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 estrelas</option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 estrelas</option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 estrelas</option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 estrelas</option>
                                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 estrela</option>
                                </select>
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="verified" class="admin-form-label">Compra Verificada</label>
                                <select id="verified" name="verified" class="admin-form-select">
                                    <option value="">Todas</option>
                                    <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Sim</option>
                                    <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Não</option>
                                </select>
                            </div>
                        </div>

                        <div class="admin-filters-actions">
                            <button type="submit" class="admin-btn admin-btn-primary">
                                <ion-icon name="search-outline"></ion-icon>
                                Pesquisar
                            </button>
                            <a href="{{ route('admin.reviews.index') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="refresh-outline"></ion-icon>
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ações em Lote -->
            @if($reviews->where('status', 'pending')->count() > 0)
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">
                            <ion-icon name="layers-outline"></ion-icon>
                            Ações em Lote
                        </h3>
                        <p class="admin-card-subtitle">Selecione avaliações pendentes para aprovar ou rejeitar em lote</p>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-bulk-actions">
                            <div class="admin-bulk-selection">
                                <label class="admin-checkbox-label">
                                    <input type="checkbox" id="select-all-pending" class="admin-checkbox">
                                    <span class="admin-checkmark"></span>
                                    Selecionar todas pendentes
                                </label>
                            </div>
                            
                            <div class="admin-bulk-buttons">
                                <button type="button" class="admin-btn admin-btn-success" onclick="approveSelected()">
                                    <ion-icon name="checkmark-outline"></ion-icon>
                                    Aprovar Selecionadas
                                </button>
                                
                                <button type="button" class="admin-btn admin-btn-danger" onclick="rejectSelected()">
                                    <ion-icon name="close-outline"></ion-icon>
                                    Rejeitar Selecionadas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lista de Avaliações -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-header-content">
                        <div class="admin-header-info">
                            <h3 class="admin-card-title">
                                <ion-icon name="list-outline"></ion-icon>
                                Avaliações ({{ $reviews->total() }})
                            </h3>
                            <p class="admin-card-subtitle">
                                @if($reviews->hasPages())
                                    Página {{ $reviews->currentPage() }} de {{ $reviews->lastPage() }}
                                @endif
                            </p>
                        </div>
                        <div class="admin-header-actions">
                            <form method="GET" class="admin-pagination-form">
                                <label for="per_page" class="admin-form-label">Itens por página:</label>
                                <select name="per_page" id="per_page" class="admin-form-select" onchange="this.form.submit()">
                                    @foreach($perPageOptions as $option)
                                        <option value="{{ $option }}" {{ request('per_page', 15) == $option ? 'selected' : '' }}>
                                            {{ $option }} itens
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="admin-card-body">
                    @if($reviews->count() > 0)
                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Produto</th>
                                        <th>Avaliação</th>
                                        <th>Status</th>
                                        <th>Data</th>
                                        <th class="admin-table-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reviews as $review)
                                        <tr>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <div class="admin-user-info">
                                                        <div class="admin-user-avatar">{{ substr($review->user->name, 0, 1) }}</div>
                                                        <div class="admin-user-details">
                                                            <div class="admin-table-primary">{{ $review->user->name }}</div>
                                                            @if($review->verified_purchase)
                                                                <div class="admin-verified-badge">
                                                                    <ion-icon name="checkmark-circle"></ion-icon>
                                                                    Verificado
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <div class="admin-table-primary">{{ $review->product->name }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <div class="admin-rating-display">
                                                        <div class="admin-rating-stars">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <ion-icon name="star" class="admin-star {{ $i <= $review->rating ? 'filled' : '' }}"></ion-icon>
                                                            @endfor
                                                        </div>
                                                        <div class="admin-rating-text">{{ $review->rating }} estrela(s)</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
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
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <div class="admin-date-info">
                                                        <div class="admin-date">{{ $review->created_at->format('d/m/Y') }}</div>
                                                        <div class="admin-time">{{ $review->created_at->format('H:i') }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <div class="admin-actions">
                                                        @if($review->status === 'pending')
                                                            <input type="checkbox" class="admin-review-checkbox" value="{{ $review->id }}">
                                                        @endif
                                                        
                                                        <a href="{{ route('admin.reviews.show', $review) }}" 
                                                           class="admin-action-btn admin-action-btn-view" 
                                                           title="Ver detalhes">
                                                            <ion-icon name="eye-outline"></ion-icon>
                                                        </a>
                                                        
                                                        @if($review->status === 'pending')
                                                            <button type="button" class="admin-action-btn admin-action-btn-success" 
                                                                    onclick="approveReview({{ $review->id }})" title="Aprovar">
                                                                <ion-icon name="checkmark-outline"></ion-icon>
                                                            </button>
                                                            
                                                            <button type="button" class="admin-action-btn admin-action-btn-danger" 
                                                                    onclick="rejectReview({{ $review->id }})" title="Rejeitar">
                                                                <ion-icon name="close-outline"></ion-icon>
                                                            </button>
                                                        @endif
                                                        
                                                        <button type="button" class="admin-action-btn admin-action-btn-danger" 
                                                                onclick="deleteReview({{ $review->id }})" title="Excluir">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        @if($reviews->hasPages())
                            <div class="admin-pagination">
                                <div class="admin-pagination-info">
                                    Mostrando {{ $reviews->firstItem() ?? 0 }} até {{ $reviews->lastItem() ?? 0 }} de
                                    {{ $reviews->total() }} resultados
                                </div>

                                <ul class="pagination">
                                    {{-- Botão Anterior --}}
                                    @if ($reviews->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <ion-icon name="chevron-back-outline"></ion-icon>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $reviews->previousPageUrl() }}">
                                                <ion-icon name="chevron-back-outline"></ion-icon>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Páginas Numeradas --}}
                                    @foreach ($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)
                                        @if ($page == $reviews->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Botão Próximo --}}
                                    @if ($reviews->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $reviews->nextPageUrl() }}">
                                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    @else
                        <div class="admin-empty-state">
                            <ion-icon name="star-outline"></ion-icon>
                            <h3>Nenhuma avaliação encontrada</h3>
                            <p>Não há avaliações que correspondam aos filtros aplicados.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleção de todas as reviews pendentes
    const selectAllCheckbox = document.getElementById('select-all-pending');
    const reviewCheckboxes = document.querySelectorAll('.admin-review-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            reviewCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});

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
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }
}

function approveSelected() {
    const selectedCheckboxes = document.querySelectorAll('.admin-review-checkbox:checked');
    const reviewIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (reviewIds.length === 0) {
        alert('Selecione pelo menos uma avaliação para aprovar.');
        return;
    }
    
    if (confirm(`Tem certeza que deseja aprovar ${reviewIds.length} avaliação(ões)?`)) {
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
    const selectedCheckboxes = document.querySelectorAll('.admin-review-checkbox:checked');
    const reviewIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (reviewIds.length === 0) {
        alert('Selecione pelo menos uma avaliação para rejeitar.');
        return;
    }
    
    if (confirm(`Tem certeza que deseja rejeitar ${reviewIds.length} avaliação(ões)?`)) {
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