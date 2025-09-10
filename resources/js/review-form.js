/* ========================================
   REVIEW FORM JAVASCRIPT
   ======================================== */

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
    const ratingInput = document.querySelector('.rating-input');
    if (ratingInput) {
        ratingInput.addEventListener('mouseleave', function() {
            ratingStars.forEach(star => {
                star.classList.remove('hover');
            });
        });
    }

    // Contador de caracteres
    const titleInput = document.getElementById('title');
    const commentInput = document.getElementById('comment');
    const titleCount = document.getElementById('title-count');
    const commentCount = document.getElementById('comment-count');

    if (titleInput && titleCount) {
        titleInput.addEventListener('input', function() {
            titleCount.textContent = this.value.length;
            
            // Adicionar classe de warning/error se necessário
            const charCount = this.value.length;
            const maxLength = 255;
            
            titleCount.className = 'char-count';
            if (charCount > maxLength * 0.9) {
                titleCount.classList.add('warning');
            }
            if (charCount > maxLength) {
                titleCount.classList.add('error');
            }
        });
    }

    if (commentInput && commentCount) {
        commentInput.addEventListener('input', function() {
            commentCount.textContent = this.value.length;
            
            // Adicionar classe de warning/error se necessário
            const charCount = this.value.length;
            const maxLength = 1000;
            
            commentCount.className = 'char-count';
            if (charCount > maxLength * 0.9) {
                commentCount.classList.add('warning');
            }
            if (charCount > maxLength) {
                commentCount.classList.add('error');
            }
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
                    
                    // Atualizar lista de reviews sem reload
                    updateReviewsList();
                    
                    // Esconder formulário após envio bem-sucedido
                    setTimeout(() => {
                        form.closest('.review-form-container').style.display = 'none';
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

    // Função para atualizar lista de reviews
    function updateReviewsList() {
        // Buscar a seção de reviews
        const reviewsSection = document.querySelector('.reviews-section');
        if (!reviewsSection) return;
        
        // Fazer requisição para atualizar a lista
        const productId = document.querySelector('input[name="product_id"]').value;
        const currentUrl = window.location.href;
        
        fetch(currentUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.text())
        .then(html => {
            // Criar elemento temporário para parsear o HTML
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            
            // Substituir a seção de reviews
            const newReviewsSection = tempDiv.querySelector('.reviews-section');
            if (newReviewsSection) {
                reviewsSection.innerHTML = newReviewsSection.innerHTML;
                
                // Re-executar scripts se necessário
                const scripts = newReviewsSection.querySelectorAll('script');
                scripts.forEach(script => {
                    const newScript = document.createElement('script');
                    newScript.textContent = script.textContent;
                    document.head.appendChild(newScript);
                });
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar reviews:', error);
            // Fallback: recarregar a página
            setTimeout(() => {
                location.reload();
            }, 1000);
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
        
        // Resetar contadores
        const titleCount = document.getElementById('title-count');
        const commentCount = document.getElementById('comment-count');
        
        if (titleCount) titleCount.textContent = '0';
        if (commentCount) commentCount.textContent = '0';
        
        // Resetar estrelas
        const ratingStars = document.querySelectorAll('.rating-star');
        ratingStars.forEach(star => {
            star.classList.remove('selected');
        });
    }
}
