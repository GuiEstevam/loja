/* ===== ORDERS PAGE JAVASCRIPT ===== */

// Funções globais para modais de avaliação
window.openOrderReviewModal = function(productId, productName, productImage) {
    const modal = document.getElementById('orderReviewModal');
    const productImageEl = document.getElementById('modalProductImage');
    const productNameEl = document.getElementById('modalProductName');
    const productIdEl = document.getElementById('modalProductId');
    
    // Preencher dados do produto
    productIdEl.value = productId;
    productNameEl.textContent = productName;
    
    // Tratar imagem do produto
    if (productImage && productImage.trim() !== '') {
        productImageEl.src = productImage;
        productImageEl.alt = productName;
        productImageEl.style.display = 'block';
    } else {
        productImageEl.style.display = 'none';
        const iconContainer = productImageEl.parentElement;
        if (!iconContainer.querySelector('.no-image-icon')) {
            const icon = document.createElement('ion-icon');
            icon.name = 'image-outline';
            icon.className = 'no-image-icon';
            icon.style.cssText = 'font-size: 2rem; color: #9ca3af; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;';
            iconContainer.appendChild(icon);
        }
    }
    
    // Limpar formulário
    document.getElementById('orderReviewForm').reset();
    document.getElementById('orderTitleCounter').textContent = '0';
    document.getElementById('orderCommentCounter').textContent = '0';
    
    // Remover classes de seleção das estrelas
    const stars = document.querySelectorAll('#orderRatingInput .rating-star');
    stars.forEach(star => {
        star.classList.remove('selected', 'hover');
    });
    
    // Mostrar modal
    modal.style.display = 'flex';
};

window.closeOrderReviewModal = function() {
    const modal = document.getElementById('orderReviewModal');
    modal.style.display = 'none';
};

window.openOrderEditReviewModal = function(reviewId, productId, productName, productImage) {
    const modal = document.getElementById('orderEditReviewModal');
    const productImageEl = document.getElementById('editModalProductImage');
    const productNameEl = document.getElementById('editModalProductName');
    const reviewIdEl = document.getElementById('editModalReviewId');
    const productIdEl = document.getElementById('editModalProductId');
    
    // Preencher dados do produto
    reviewIdEl.value = reviewId;
    productIdEl.value = productId;
    productNameEl.textContent = productName;
    
    // Tratar imagem do produto
    if (productImage && productImage.trim() !== '') {
        productImageEl.src = productImage;
        productImageEl.alt = productName;
        productImageEl.style.display = 'block';
    } else {
        productImageEl.style.display = 'none';
    }
    
    // Buscar dados da avaliação atual
    fetch(`/reviews/${reviewId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const review = data.review;
                
                // Preencher formulário
                document.getElementById('editOrderTitle').value = review.title || '';
                document.getElementById('editOrderComment').value = review.comment || '';
                
                // Selecionar rating atual
                const ratingInput = document.querySelector(`#edit-order-star-${review.rating}`);
                if (ratingInput) {
                    ratingInput.checked = true;
                    
                    // Aplicar classe selected para visual correto
                    const editRatingStars = document.querySelectorAll('#editOrderRatingInput .rating-star');
                    editRatingStars.forEach(star => {
                        star.classList.remove('selected');
                    });
                    
                    for (let i = 0; i < review.rating; i++) {
                        editRatingStars[i].classList.add('selected');
                    }
                }
                
                // Atualizar contadores
                document.getElementById('editOrderTitleCounter').textContent = (review.title || '').length;
                document.getElementById('editOrderCommentCounter').textContent = (review.comment || '').length;
            }
        })
        .catch(error => {
            console.error('Erro ao buscar avaliação:', error);
        });
    
    // Mostrar modal
    modal.style.display = 'flex';
};

window.closeOrderEditReviewModal = function() {
    const modal = document.getElementById('orderEditReviewModal');
    modal.style.display = 'none';
};

window.deleteOrderReview = function(reviewId) {
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
                showModal('Avaliação excluída com sucesso!', 'success');
                location.reload();
            } else {
                showModal(data.error || 'Erro ao excluir avaliação', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showModal('Erro ao excluir avaliação', 'error');
        });
    }
};

// Função para mostrar modal padrão do app.blade.php
window.showModal = function(message, type = 'info') {
    // Implementar modal customizado ou usar alert temporariamente
    alert(message);
};

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    initializeOrderReviewModals();
    initializeOrderEditModals();
    initializeCharacterCounters();
    initializeFormSubmissions();
});

// Inicializar modais de criação de avaliação
function initializeOrderReviewModals() {
    // Controle de seleção das estrelas
    const ratingInputs = document.querySelectorAll('#orderRatingInput input[name="rating"]');
    const ratingStars = document.querySelectorAll('#orderRatingInput .rating-star');
    
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
    
    // Controle de hover para mostrar preview
    ratingStars.forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            // Remove todas as classes de hover
            ratingStars.forEach(s => s.classList.remove('hover'));
            
            // Adiciona classe hover até a estrela atual
            for (let i = 0; i <= index; i++) {
                ratingStars[i].classList.add('hover');
            }
        });
    });
    
    // Remove hover quando sair do container
    const ratingInput = document.querySelector('#orderRatingInput');
    if (ratingInput) {
        ratingInput.addEventListener('mouseleave', function() {
            ratingStars.forEach(star => {
                star.classList.remove('hover');
            });
        });
    }
    
    // Fechar modal ao clicar fora
    const modal = document.getElementById('orderReviewModal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeOrderReviewModal();
            }
        });
    }
}

// Inicializar modais de edição de avaliação
function initializeOrderEditModals() {
    // Controle de seleção das estrelas no modal de edição
    const editRatingInputs = document.querySelectorAll('#editOrderRatingInput input[name="rating"]');
    const editRatingStars = document.querySelectorAll('#editOrderRatingInput .rating-star');
    
    editRatingInputs.forEach((input, index) => {
        input.addEventListener('change', function() {
            // Remove todas as classes de seleção
            editRatingStars.forEach(star => {
                star.classList.remove('selected');
            });
            
            // Adiciona classe de seleção até a estrela clicada
            for (let i = 0; i <= index; i++) {
                editRatingStars[i].classList.add('selected');
            }
        });
    });
    
    // Controle de hover para mostrar preview no modal de edição
    editRatingStars.forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            // Remove todas as classes de hover
            editRatingStars.forEach(s => s.classList.remove('hover'));
            
            // Adiciona classe hover até a estrela atual
            for (let i = 0; i <= index; i++) {
                editRatingStars[i].classList.add('hover');
            }
        });
    });
    
    // Remove hover quando sair do container de edição
    const editRatingInput = document.querySelector('#editOrderRatingInput');
    if (editRatingInput) {
        editRatingInput.addEventListener('mouseleave', function() {
            editRatingStars.forEach(star => {
                star.classList.remove('hover');
            });
        });
    }
    
    // Fechar modal de edição ao clicar fora
    const editModal = document.getElementById('orderEditReviewModal');
    if (editModal) {
        editModal.addEventListener('click', function(event) {
            if (event.target === editModal) {
                closeOrderEditReviewModal();
            }
        });
    }
}

// Inicializar contadores de caracteres
function initializeCharacterCounters() {
    // Contadores para modal de criação
    const titleInput = document.getElementById('orderTitle');
    const commentInput = document.getElementById('orderComment');
    const titleCounter = document.getElementById('orderTitleCounter');
    const commentCounter = document.getElementById('orderCommentCounter');
    
    if (titleInput && titleCounter) {
        titleInput.addEventListener('input', function() {
            titleCounter.textContent = this.value.length;
        });
    }
    
    if (commentInput && commentCounter) {
        commentInput.addEventListener('input', function() {
            commentCounter.textContent = this.value.length;
        });
    }
    
    // Contadores para modal de edição
    const editTitleInput = document.getElementById('editOrderTitle');
    const editCommentInput = document.getElementById('editOrderComment');
    const editTitleCounter = document.getElementById('editOrderTitleCounter');
    const editCommentCounter = document.getElementById('editOrderCommentCounter');
    
    if (editTitleInput && editTitleCounter) {
        editTitleInput.addEventListener('input', function() {
            editTitleCounter.textContent = this.value.length;
        });
    }
    
    if (editCommentInput && editCommentCounter) {
        editCommentInput.addEventListener('input', function() {
            editCommentCounter.textContent = this.value.length;
        });
    }
}

// Inicializar submissões de formulários
function initializeFormSubmissions() {
    // Submissão do formulário de criação
    const createForm = document.getElementById('orderReviewForm');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const rating = formData.get('rating');
            
            if (!rating) {
                showModal('Por favor, selecione uma avaliação', 'error');
                return;
            }
            
            const submitBtn = document.getElementById('submitOrderReviewBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>Enviando...';
            submitBtn.disabled = true;
            
            // Enviar dados
            fetch('/reviews', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: parseInt(formData.get('product_id')),
                    rating: parseInt(rating),
                    title: formData.get('title'),
                    comment: formData.get('comment')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModal(data.message, 'success');
                    closeOrderReviewModal();
                    location.reload();
                } else {
                    showModal(data.error || 'Erro ao enviar avaliação', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showModal('Erro ao enviar avaliação', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Submissão do formulário de edição
    const editForm = document.getElementById('orderEditReviewForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const rating = formData.get('rating');
            
            if (!rating) {
                showModal('Por favor, selecione uma avaliação', 'error');
                return;
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>Salvando...';
            submitBtn.disabled = true;
            
            // Enviar dados de edição
            fetch(`/reviews/${formData.get('review_id')}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    rating: parseInt(rating),
                    title: formData.get('title'),
                    comment: formData.get('comment')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModal('Avaliação atualizada com sucesso!', 'success');
                    closeOrderEditReviewModal();
                    location.reload();
                } else {
                    showModal(data.error || 'Erro ao atualizar avaliação', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showModal('Erro ao atualizar avaliação', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
}
