{{-- resources/views/components/review-form.blade.php --}}
@props(['product'])

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
                    <p>Para avaliar este produto, você precisa tê-lo comprado primeiro.</p>
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
                <h4>Faça login para avaliar</h4>
                <p>Entre com sua conta para compartilhar sua experiência com este produto.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Fazer Login</a>
            </div>
        </div>
    </div>
@endauth

<style>
.review-form-container {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    margin: 24px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.review-form-header {
    margin-bottom: 24px;
    text-align: center;
}

.review-form-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 8px 0;
}

.review-form-header p {
    color: #6b7280;
    margin: 0;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
}

.rating-input {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-star {
    cursor: pointer;
    transition: all 0.2s ease;
}

.rating-star .star-icon {
    width: 32px;
    height: 32px;
    color: #d1d5db;
    transition: color 0.2s ease;
}

/* Correção: Seleção da esquerda para direita */
.rating-input input[type="radio"]:checked ~ .rating-star .star-icon {
    color: #fbbf24;
}

/* Estrelas após a selecionada ficam cinzas */
.rating-input input[type="radio"]:checked ~ .rating-star ~ .rating-star .star-icon {
    color: #d1d5db;
}

/* Classe JavaScript para controle de seleção */
.rating-star.selected .star-icon {
    color: #fbbf24;
}

.rating-input:hover .rating-star .star-icon {
    color: #fbbf24;
}

.rating-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
    color: #6b7280;
}

.rating-label {
    opacity: 0;
    transition: opacity 0.2s ease;
}

.rating-input input[type="radio"]:checked ~ .rating-label {
    opacity: 1;
    color: #374151;
    font-weight: 500;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.2s ease;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.char-count {
    text-align: right;
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 4px;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
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

.btn-icon {
    width: 16px;
    height: 16px;
}

.alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border-radius: 8px;
    margin: 24px 0;
}

.alert-info {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
}

.alert-warning {
  background: #fffbeb;
  border: 1px solid #fed7aa;
  color: #92400e;
}

.alert-success {
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  color: #166534;
}

.alert-error {
  background: #fef2f2;
  border: 1px solid #fecaca;
  color: #dc2626;
}

.alert-icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

.alert-content h4 {
    margin: 0 0 4px 0;
    font-weight: 600;
}

.alert-content p {
    margin: 0 0 8px 0;
}

@media (max-width: 768px) {
    .review-form-container {
        padding: 16px;
        margin: 16px 0;
    }
    
    .rating-input {
        justify-content: center;
    }
    
    .rating-labels {
        font-size: 0.75rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Controle de seleção das estrelas (esquerda para direita)
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const ratingStars = document.querySelectorAll('.rating-star');
    
    ratingInputs.forEach((input, index) => {
        input.addEventListener('change', function() {
            // Remove todas as classes de seleção
            ratingStars.forEach(star => {
                star.classList.remove('selected');
            });
            
            // Adiciona classe de seleção até a estrela clicada
            for (let i = 0; i <= index; i++) {
                ratingStars[i].classList.add('selected');
            }
        });
    });

    // Contador de caracteres
    const titleInput = document.getElementById('title');
    const commentInput = document.getElementById('comment');
    const titleCount = document.getElementById('title-count');
    const commentCount = document.getElementById('comment-count');

    if (titleInput && titleCount) {
        titleInput.addEventListener('input', function() {
            titleCount.textContent = this.value.length;
        });
    }

    if (commentInput && commentCount) {
        commentInput.addEventListener('input', function() {
            commentCount.textContent = this.value.length;
        });
    }

    // Validação do formulário
    const form = document.getElementById('review-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const rating = document.querySelector('input[name="rating"]:checked');
            if (!rating) {
                showAlert('Por favor, selecione uma avaliação (1-5 estrelas)', 'error');
                return false;
            }

            // Mostrar loading
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>Enviando...';
            submitBtn.disabled = true;

            // Enviar formulário via AJAX
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    form.reset();
                    document.getElementById('title-count').textContent = '0';
                    document.getElementById('comment-count').textContent = '0';
                    
                    // Recarregar página após 2 segundos
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showAlert(data.error || 'Erro ao enviar avaliação', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showAlert('Erro ao enviar avaliação. Tente novamente.', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Função para mostrar alertas
    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `
            <svg class="alert-icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            <div class="alert-content">
                <p>${message}</p>
            </div>
        `;
        
        // Inserir no início do formulário
        const form = document.getElementById('review-form');
        if (form) {
            form.insertBefore(alertDiv, form.firstChild);
            
            // Remover após 5 segundos
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    }
});

function resetForm() {
    const form = document.getElementById('review-form');
    if (form) {
        form.reset();
        document.getElementById('title-count').textContent = '0';
        document.getElementById('comment-count').textContent = '0';
    }
}
</script>
