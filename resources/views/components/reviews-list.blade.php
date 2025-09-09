{{-- resources/views/components/reviews-list.blade.php --}}
@props(['product', 'reviews'])

<div class="reviews-section">
    <div class="reviews-header">
        <h3>Avaliações dos Clientes</h3>
        <div class="reviews-summary">
            <div class="rating-overview">
                <div class="rating-average">
                    <span class="rating-value">{{ number_format($product->average_rating, 1) }}</span>
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="star {{ $i <= $product->average_rating ? 'filled' : '' }}" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="rating-count">({{ $product->total_reviews }} avaliações)</span>
                </div>
            </div>

            @if($product->total_reviews > 0)
                <div class="rating-distribution">
                    @foreach($product->rating_distribution as $rating => $count)
                        <div class="rating-bar">
                            <span class="rating-number">{{ $rating }}</span>
                            <div class="bar-container">
                                <div class="bar" style="width: {{ $product->total_reviews > 0 ? ($count / $product->total_reviews) * 100 : 0 }}%"></div>
                            </div>
                            <span class="rating-count">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if($reviews->count() > 0)
        <div class="reviews-filters">
            <div class="filter-group">
                <label for="rating-filter">Filtrar por:</label>
                <select id="rating-filter" class="filter-select">
                    <option value="">Todas as avaliações</option>
                    <option value="5">5 estrelas</option>
                    <option value="4">4 estrelas</option>
                    <option value="3">3 estrelas</option>
                    <option value="2">2 estrelas</option>
                    <option value="1">1 estrela</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="sort-filter">Ordenar por:</label>
                <select id="sort-filter" class="filter-select">
                    <option value="newest">Mais recentes</option>
                    <option value="oldest">Mais antigas</option>
                    <option value="rating_high">Maior avaliação</option>
                    <option value="rating_low">Menor avaliação</option>
                    <option value="helpful">Mais úteis</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="verified-only">
                    <span class="checkmark"></span>
                    Apenas compras verificadas
                </label>
            </div>
        </div>

        <div class="reviews-list" id="reviews-list">
            @foreach($reviews as $review)
                <div class="review-item" data-rating="{{ $review->rating }}" data-verified="{{ $review->verified_purchase ? 'true' : 'false' }}">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div class="reviewer-details">
                                <span class="reviewer-name">{{ $review->user->name }}</span>
                                @if($review->verified_purchase)
                                    <span class="verified-badge">
                                        <svg class="badge-icon" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                        </svg>
                                        Compra verificada
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="review-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="star {{ $i <= $review->rating ? 'filled' : '' }}" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            @endfor
                        </div>
                        
                        <div class="review-date">{{ $review->created_at->format('d/m/Y') }}</div>
                    </div>
                    
                    @if($review->title)
                        <h4 class="review-title">{{ $review->title }}</h4>
                    @endif
                    
                    @if($review->comment)
                        <p class="review-comment">{{ $review->comment }}</p>
                    @endif
                    
                    <div class="review-actions">
                        <button class="helpful-btn" data-review-id="{{ $review->id }}" onclick="markHelpful({{ $review->id }})">
                            <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 14c-1.66 0-3 1.34-3 3 0 1.31-1.16 2-2 2 .92 1.22 2.49 2 4 2 2.21 0 4-1.79 4-4 0-1.66-1.34-3-3-3zm13.71-9.37l-1.34-1.34c-.39-.39-1.02-.39-1.41 0L9 12.25 11.75 15l8.96-8.96c.39-.39.39-1.02 0-1.41z"/>
                            </svg>
                            <span class="helpful-text">Útil</span>
                            <span class="helpful-count">({{ $review->helpful_count }})</span>
                        </button>
                        
                        @auth
                            @if($review->user_id === auth()->id())
                                <div class="review-owner-actions">
                                    <button class="edit-btn" onclick="editReview({{ $review->id }})">Editar</button>
                                    <button class="delete-btn" onclick="deleteReview({{ $review->id }})">Excluir</button>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <div class="reviews-pagination">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="no-reviews">
            <div class="no-reviews-content">
                <svg class="no-reviews-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <h4>Nenhuma avaliação ainda</h4>
                <p>Seja o primeiro a avaliar este produto!</p>
            </div>
        </div>
    @endif
</div>

<style>
.reviews-section {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    margin: 24px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.reviews-header {
    margin-bottom: 24px;
}

.reviews-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 16px 0;
}

.rating-overview {
    display: flex;
    align-items: center;
    gap: 24px;
    margin-bottom: 16px;
}

.rating-average {
    display: flex;
    align-items: center;
    gap: 12px;
}

.rating-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
}

.rating-stars {
    display: flex;
    gap: 2px;
}

.rating-stars .star {
    width: 20px;
    height: 20px;
    color: #d1d5db;
}

.rating-stars .star.filled {
    color: #fbbf24;
}

.rating-count {
    color: #6b7280;
    font-size: 0.875rem;
}

.rating-distribution {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 200px;
}

.rating-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
}

.rating-number {
    width: 16px;
    text-align: center;
    color: #6b7280;
}

.bar-container {
    flex: 1;
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
}

.bar {
    height: 100%;
    background: #fbbf24;
    transition: width 0.3s ease;
}

.reviews-filters {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
    padding: 16px;
    background: #f9fafb;
    border-radius: 8px;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 120px;
}

.filter-group label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: white;
    font-size: 0.875rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 0.875rem;
    padding: 8px 0;
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

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.review-item {
    padding: 20px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #fafafa;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.reviewer-avatar {
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

.reviewer-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.reviewer-name {
    font-weight: 600;
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

.review-rating {
    display: flex;
    gap: 2px;
}

.review-rating .star {
    width: 16px;
    height: 16px;
    color: #d1d5db;
}

.review-rating .star.filled {
    color: #fbbf24;
}

.review-date {
    color: #6b7280;
    font-size: 0.875rem;
}

.review-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 8px 0;
}

.review-comment {
    color: #374151;
    line-height: 1.6;
    margin: 0 0 16px 0;
}

.review-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.helpful-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    color: #374151;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.helpful-btn:hover {
    background: #e5e7eb;
}

.helpful-btn.clicked {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.btn-icon {
    width: 14px;
    height: 14px;
}

.review-owner-actions {
    display: flex;
    gap: 8px;
}

.edit-btn,
.delete-btn {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.edit-btn {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.edit-btn:hover {
    background: #e5e7eb;
}

.delete-btn {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.delete-btn:hover {
    background: #fee2e2;
}

.no-reviews {
    text-align: center;
    padding: 48px 24px;
}

.no-reviews-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.no-reviews-icon {
    width: 48px;
    height: 48px;
    color: #d1d5db;
}

.no-reviews h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin: 0;
}

.no-reviews p {
    color: #6b7280;
    margin: 0;
}

.reviews-pagination {
    margin-top: 24px;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .reviews-section {
        padding: 16px;
        margin: 16px 0;
    }
    
    .rating-overview {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .rating-distribution {
        width: 100%;
    }
    
    .reviews-filters {
        flex-direction: column;
        gap: 12px;
    }
    
    .review-header {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
    
    .review-actions {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtros
    const ratingFilter = document.getElementById('rating-filter');
    const sortFilter = document.getElementById('sort-filter');
    const verifiedOnly = document.getElementById('verified-only');
    
    if (ratingFilter) {
        ratingFilter.addEventListener('change', filterReviews);
    }
    
    if (sortFilter) {
        sortFilter.addEventListener('change', filterReviews);
    }
    
    if (verifiedOnly) {
        verifiedOnly.addEventListener('change', filterReviews);
    }
});

function filterReviews() {
    const ratingFilter = document.getElementById('rating-filter');
    const sortFilter = document.getElementById('sort-filter');
    const verifiedOnly = document.getElementById('verified-only');
    
    const selectedRating = ratingFilter ? ratingFilter.value : '';
    const selectedSort = sortFilter ? sortFilter.value : 'newest';
    const onlyVerified = verifiedOnly ? verifiedOnly.checked : false;
    
    // Aqui você implementaria a lógica de filtro
    // Por enquanto, vamos apenas mostrar/ocultar elementos
    const reviewItems = document.querySelectorAll('.review-item');
    
    reviewItems.forEach(item => {
        const itemRating = item.dataset.rating;
        const itemVerified = item.dataset.verified === 'true';
        
        let show = true;
        
        if (selectedRating && itemRating !== selectedRating) {
            show = false;
        }
        
        if (onlyVerified && !itemVerified) {
            show = false;
        }
        
        item.style.display = show ? 'block' : 'none';
    });
}

function markHelpful(reviewId) {
    fetch(`/reviews/${reviewId}/helpful`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const btn = document.querySelector(`[data-review-id="${reviewId}"]`);
            const countSpan = btn.querySelector('.helpful-count');
            countSpan.textContent = `(${data.helpful_count})`;
            btn.classList.add('clicked');
            btn.disabled = true;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

function editReview(reviewId) {
    // Implementar edição de review
    console.log('Editar review:', reviewId);
}

function deleteReview(reviewId) {
    if (confirm('Tem certeza que deseja excluir esta avaliação?')) {
        fetch(`/reviews/${reviewId}`, {
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
</script>
