{{-- resources/views/components/review-form.blade.php --}}
@props(['product'])

@push('styles')
    @vite('resources/css/review-form.css')
@endpush

@push('scripts')
    @vite('resources/js/review-form.js')
@endpush

@auth
    @php
        $canReview = $product->canUserReview(auth()->id());
        $hasReviewed = $product->reviews()->where('user_id', auth()->id())->exists();
    @endphp

    @if($canReview && !$hasReviewed)
        <div class="review-form-container">
            <div class="review-form-header">
                <h3>Avaliar Produto</h3>
                <p>Avalie sua experiência com este produto</p>
            </div>

            <form id="review-form" class="review-form" action="{{ route('shop.reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <!-- Rating -->
                <div class="form-group">
                    <label class="form-label">Sua Avaliação *</label>
                    <div class="rating-input">
                        @for($i = 1; $i <= 5; $i++)
                            <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" required>
                            <label for="rating{{ $i }}" class="rating-star">
                                <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </label>
                        @endfor
                    </div>
                    <div class="rating-labels">
                        <span class="rating-label" data-rating="1">Muito Ruim</span>
                        <span class="rating-label" data-rating="2">Ruim</span>
                        <span class="rating-label" data-rating="3">Regular</span>
                        <span class="rating-label" data-rating="4">Bom</span>
                        <span class="rating-label" data-rating="5">Excelente</span>
                    </div>
                </div>

                <!-- Título -->
                <div class="form-group">
                    <label for="title" class="form-label">Título da Avaliação</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="form-input" 
                           placeholder="Resumo da sua experiência"
                           maxlength="255">
                    <div class="char-count">
                        <span id="title-count">0</span>/255 caracteres
                    </div>
                </div>

                <!-- Comentário -->
                <div class="form-group">
                    <label for="comment" class="form-label">Comentário Detalhado</label>
                    <textarea id="comment" 
                              name="comment" 
                              class="form-textarea" 
                              rows="4" 
                              placeholder="Conte sua experiência com o produto, qualidade, entrega, etc."
                              maxlength="1000"></textarea>
                    <div class="char-count">
                        <span id="comment-count">0</span>/1000 caracteres
                    </div>
                </div>

                <!-- Botões -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">Limpar</button>
                    <button type="submit" class="btn btn-primary">
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                        Enviar Avaliação
                    </button>
                </div>
            </form>
        </div>
    @elseif($hasReviewed)
        <div class="review-already-submitted">
            <div class="alert alert-info">
                <svg class="alert-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                <div class="alert-content">
                    <h4>Você já avaliou este produto</h4>
                    <p>Sua avaliação foi enviada e está sendo exibida abaixo.</p>
                </div>
            </div>
        </div>
    @else
        <div class="review-not-eligible">
            <div class="alert alert-warning">
                <svg class="alert-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
                <div class="alert-content">
                    <h4>Apenas compradores podem avaliar</h4>
                    <p>Compre este produto e compartilhe sua experiência com outros clientes. Sua avaliação é valiosa para a comunidade!</p>
                    <a href="{{ route('shop.cart.index') }}" class="btn btn-primary">Adicionar ao Carrinho</a>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="review-login-required">
        <div class="alert alert-info">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            <div class="alert-content">
                <h4>Apenas compradores podem avaliar</h4>
                <p>Compre este produto e compartilhe sua experiência com outros clientes. Sua avaliação é valiosa para a comunidade!</p>
                <a href="{{ route('shop.cart.index') }}" class="btn btn-primary">Adicionar ao Carrinho</a>
            </div>
        </div>
    </div>
@endauth