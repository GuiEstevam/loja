/* ========================================
   REVIEWS SYSTEM JAVASCRIPT
   ======================================== */

// Funções globais para serem chamadas pelos botões
window.markHelpful = function(reviewId) {
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
};

window.editReview = function(reviewId) {
    // Buscar dados da review atual
    fetch(`/reviews/${reviewId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const review = data.review;
                
                // Preencher formulário com dados atuais
                document.getElementById('editReviewId').value = reviewId;
                document.getElementById('editTitle').value = review.title || '';
                document.getElementById('editComment').value = review.comment || '';
                
                // Selecionar rating atual
                const ratingInput = document.querySelector(`#edit-star-${review.rating}`);
                if (ratingInput) {
                    ratingInput.checked = true;
                    
                    // Aplicar classe selected para visual correto
                    const editRatingStars = document.querySelectorAll('#editRatingInput .rating-star');
                    editRatingStars.forEach(star => {
                        star.classList.remove('selected');
                    });
                    
                    // Agora as estrelas estão ordenadas de 1 para 5, então é mais simples
                    for (let i = 0; i < review.rating; i++) {
                        editRatingStars[i].classList.add('selected');
                    }
                }
                
                // Atualizar contadores de caracteres
                updateCharCounters();
                
                // Mostrar modal
                document.getElementById('editModal').style.display = 'flex';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar review:', error);
            alert('Erro ao carregar dados da avaliação');
        });
};

window.deleteReview = function(reviewId) {
    showConfirmModal(
        'Excluir Avaliação',
        'Tem certeza que deseja excluir esta avaliação? Esta ação não pode ser desfeita.',
        function() {
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
    );
};

document.addEventListener('DOMContentLoaded', function() {
    // Filtros de reviews
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

// Funções do Modal de Confirmação
function showConfirmModal(title, message, onConfirm) {
    const modal = document.getElementById('confirmModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    
    // Remover listeners anteriores
    const newConfirmBtn = modalConfirmBtn.cloneNode(true);
    modalConfirmBtn.parentNode.replaceChild(newConfirmBtn, modalConfirmBtn);
    
    // Adicionar novo listener
    newConfirmBtn.addEventListener('click', function() {
        closeConfirmModal();
        onConfirm();
    });
    
    modal.style.display = 'flex';
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.style.display = 'none';
}

// Fechar modal ao clicar fora
document.addEventListener('click', function(event) {
    const modal = document.getElementById('confirmModal');
    if (event.target === modal) {
        closeConfirmModal();
    }
    
    const editModal = document.getElementById('editModal');
    if (event.target === editModal) {
        closeEditModal();
    }
});

// Funções do Modal de Edição
function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'none';
    
    // Limpar formulário
    document.getElementById('editReviewForm').reset();
    document.getElementById('editTitleCounter').textContent = '0';
    document.getElementById('editCommentCounter').textContent = '0';
}

function updateCharCounters() {
    const titleInput = document.getElementById('editTitle');
    const commentInput = document.getElementById('editComment');
    const titleCounter = document.getElementById('editTitleCounter');
    const commentCounter = document.getElementById('editCommentCounter');
    
    if (titleInput && titleCounter) {
        titleCounter.textContent = titleInput.value.length;
    }
    
    if (commentInput && commentCounter) {
        commentCounter.textContent = commentInput.value.length;
    }
}

// Event listeners para contadores de caracteres
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('editTitle');
    const commentInput = document.getElementById('editComment');
    
    if (titleInput) {
        titleInput.addEventListener('input', updateCharCounters);
    }
    
    if (commentInput) {
        commentInput.addEventListener('input', updateCharCounters);
    }
    
    // Controle de seleção das estrelas no modal de edição
    const editRatingInputs = document.querySelectorAll('#editRatingInput input[name="rating"]');
    const editRatingStars = document.querySelectorAll('#editRatingInput .rating-star');
    
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
    
    // Controle de hover para mostrar preview no modal
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
    
    // Remove hover quando sair do container do modal
    const editRatingInput = document.querySelector('#editRatingInput');
    if (editRatingInput) {
        editRatingInput.addEventListener('mouseleave', function() {
            editRatingStars.forEach(star => {
                star.classList.remove('hover');
            });
        });
    }
    
    // Botão de cancelar edição
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', closeEditModal);
    }
    
    // Botão de salvar edição
    const saveEditBtn = document.getElementById('saveEditBtn');
    if (saveEditBtn) {
        saveEditBtn.addEventListener('click', function() {
            const reviewId = document.getElementById('editReviewId').value;
            const formData = new FormData(document.getElementById('editReviewForm'));
            
            // Validar rating
            const rating = formData.get('rating');
            if (!rating) {
                alert('Por favor, selecione uma avaliação');
                return;
            }
            
            // Enviar dados
            fetch(`/reviews/${reviewId}`, {
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
                    closeEditModal();
                    location.reload(); // Recarregar para mostrar mudanças
                } else {
                    alert(data.error || 'Erro ao atualizar avaliação');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao atualizar avaliação');
            });
        });
    }
});
