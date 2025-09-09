{{-- resources/views/components/reviews-list.blade.php --}}
@props(['product', 'reviews'])

@push('styles')
    @vite('resources/css/reviews.css')
@endpush

@push('scripts')
    @vite('resources/js/reviews.js')
@endpush

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