/* ========================================
   REVIEWS SYSTEM JAVASCRIPT
   ======================================== */

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
