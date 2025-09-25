/**
 * Admin Reviews Management
 * Funcionalidades para gerenciamento de avaliações no painel administrativo
 */

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

/**
 * Aprovar uma avaliação individual
 * @param {number} reviewId - ID da avaliação
 */
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

/**
 * Rejeitar uma avaliação individual
 * @param {number} reviewId - ID da avaliação
 */
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

/**
 * Excluir uma avaliação
 * @param {number} reviewId - ID da avaliação
 */
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

/**
 * Aprovar múltiplas avaliações selecionadas
 */
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

/**
 * Rejeitar múltiplas avaliações selecionadas
 */
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
