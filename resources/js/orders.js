/**
 * Orders Page - JavaScript Modular
 * Sistema de avaliações e modais interativos
 */

class OrdersPage {
    constructor() {
        this.currentModal = null;
        this.currentReview = null;
        this.init();
    }

    init() {
        this.initTabs();
        this.initModals();
        this.initFormHandlers();
        this.initCharacterCounters();
        this.initRatingInputs();
    }

    /**
     * Inicializa o sistema de abas
     */
    initTabs() {
        const tabButtons = document.querySelectorAll('.dashboard-tab-btn');
        const tabPanes = document.querySelectorAll('.dashboard-tab-pane');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.dataset.tab;

                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));

                // Add active class to clicked button and target pane
                button.classList.add('active');
                const targetPane = document.getElementById(targetTab);
                if (targetPane) {
                    targetPane.classList.add('active');
                }

                // Filtrar pedidos baseado na aba selecionada
                this.filterOrders(targetTab);
            });
        });

        // Filtrar pedidos na inicialização
        this.filterOrders('orders');
    }

    /**
     * Filtra os pedidos baseado no status
     */
    filterOrders(status) {
        if (!window.ordersData) return;

        const orders = window.ordersData;
        let filteredOrders = [];

        switch (status) {
            case 'pending':
                filteredOrders = orders.filter(order => order.status === 'pending');
                break;
            case 'delivered':
                filteredOrders = orders.filter(order => order.status === 'delivered');
                break;
            default:
                filteredOrders = orders;
        }

        this.renderOrders(filteredOrders, status);
    }

    /**
     * Renderiza os pedidos filtrados
     */
    renderOrders(orders, status) {
        const containerId = status === 'pending' ? 'pending-orders-list' : 
                           status === 'delivered' ? 'delivered-orders-list' : 
                           'orders-list';
        const emptyStateId = status === 'pending' ? 'pending-empty-state' : 
                            status === 'delivered' ? 'delivered-empty-state' : 
                            'orders-empty-state';

        const container = document.getElementById(containerId);
        const emptyState = document.getElementById(emptyStateId);

        if (!container) return;

        if (orders.length === 0) {
            container.innerHTML = '';
            if (emptyState) {
                emptyState.style.display = 'block';
            }
            return;
        }

        if (emptyState) {
            emptyState.style.display = 'none';
        }

        const ordersHtml = orders.map(order => {
            const statusIcon = this.getStatusIcon(order.status);
            const statusText = this.getStatusText(order.status);

            return `
                <div class="dashboard-list-item">
                    <div class="dashboard-list-icon">
                        <ion-icon name="${statusIcon}"></ion-icon>
                    </div>
                    <div class="dashboard-list-content">
                        <h4 class="dashboard-list-title">Pedido #${order.id}</h4>
                        <p class="dashboard-list-meta">
                            ${order.created_at} •
                            ${order.items_count} item(s) •
                            €${order.total_formatted}
                        </p>
                    </div>
                    <div class="dashboard-list-actions">
                        <span class="dashboard-status-badge ${order.status}">
                            ${statusText}
                        </span>
                        <a href="${order.show_url}" class="dashboard-action-btn">
                            <ion-icon name="eye-outline"></ion-icon>
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = ordersHtml;
    }

        /**
         * Retorna o ícone baseado no status
         */
        getStatusIcon(status) {
            const icons = {
                'pending': 'time-outline',
                'paid': 'card-outline',
                'processing': 'refresh-outline',
                'shipped': 'car-outline',
                'delivered': 'checkmark-circle-outline',
                'cancelled': 'close-circle-outline'
            };
            return icons[status] || 'receipt-outline';
        }

        /**
         * Retorna o texto baseado no status
         */
        getStatusText(status) {
            const texts = {
                'pending': 'Pendente',
                'paid': 'Pago',
                'processing': 'Processando',
                'shipped': 'Enviado',
                'delivered': 'Entregue',
                'cancelled': 'Cancelado'
            };
            return texts[status] || status;
        }

    /**
     * Inicializa os modais
     */
    initModals() {
        // Event listeners para fechar modais
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                this.closeCurrentModal();
            }
        });

        // Event listeners para botões de fechar
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-close')) {
                this.closeCurrentModal();
            }
        });

        // Fechar modal com ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.currentModal) {
                this.closeCurrentModal();
            }
        });
    }

    /**
     * Inicializa os handlers de formulário
     */
    initFormHandlers() {
        // Formulário de nova avaliação
        const reviewForm = document.getElementById('orderReviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitReview();
            });
        }

        // Formulário de edição de avaliação
        const editReviewForm = document.getElementById('orderEditReviewForm');
        if (editReviewForm) {
            editReviewForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitEditReview();
        });
    }
}

    /**
     * Inicializa contadores de caracteres
     */
    initCharacterCounters() {
        // Contador para título
        const titleInput = document.getElementById('orderTitle');
        const titleCounter = document.getElementById('orderTitleCounter');
        if (titleInput && titleCounter) {
            titleInput.addEventListener('input', () => {
                titleCounter.textContent = titleInput.value.length;
            });
        }

        // Contador para comentário
        const commentInput = document.getElementById('orderComment');
        const commentCounter = document.getElementById('orderCommentCounter');
        if (commentInput && commentCounter) {
            commentInput.addEventListener('input', () => {
                commentCounter.textContent = commentInput.value.length;
            });
        }

        // Contadores para edição
        const editTitleInput = document.getElementById('editOrderTitle');
        const editTitleCounter = document.getElementById('editOrderTitleCounter');
        if (editTitleInput && editTitleCounter) {
            editTitleInput.addEventListener('input', () => {
                editTitleCounter.textContent = editTitleInput.value.length;
            });
        }

        const editCommentInput = document.getElementById('editOrderComment');
        const editCommentCounter = document.getElementById('editOrderCommentCounter');
        if (editCommentInput && editCommentCounter) {
            editCommentInput.addEventListener('input', () => {
                editCommentCounter.textContent = editCommentInput.value.length;
            });
        }
    }

    /**
     * Inicializa inputs de rating
     */
    initRatingInputs() {
        // Rating para nova avaliação
        const ratingInputs = document.querySelectorAll('#orderRatingInput input[type="radio"]');
        ratingInputs.forEach((input, index) => {
            input.addEventListener('change', () => {
                this.updateRatingDisplay('#orderRatingInput', index + 1);
        });
    });
    
        // Rating para edição
        const editRatingInputs = document.querySelectorAll('#editOrderRatingInput input[type="radio"]');
        editRatingInputs.forEach((input, index) => {
            input.addEventListener('change', () => {
                this.updateRatingDisplay('#editOrderRatingInput', index + 1);
            });
        });
    }
    
    /**
     * Atualiza a exibição do rating
     */
    updateRatingDisplay(containerSelector, rating) {
        const container = document.querySelector(containerSelector);
        if (!container) return;

        const stars = container.querySelectorAll('.rating-star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    /**
     * Abre modal de nova avaliação
     */
    openOrderReviewModal(productId, productName, productImage) {
        this.currentModal = 'review';
        
        // Preencher dados do produto
        document.getElementById('modalProductId').value = productId;
        document.getElementById('modalProductName').textContent = productName;
        document.getElementById('modalProductImage').src = productImage;
        document.getElementById('modalProductImage').alt = productName;

        // Limpar formulário
        this.clearReviewForm();

        // Mostrar modal
        document.getElementById('orderReviewModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    /**
     * Fecha modal de nova avaliação
     */
    closeOrderReviewModal() {
        document.getElementById('orderReviewModal').style.display = 'none';
        document.body.style.overflow = '';
        this.currentModal = null;
    }

    /**
     * Abre modal de edição de avaliação
     */
    openOrderEditReviewModal(reviewId, productId, productName, productImage) {
        this.currentModal = 'edit';
        this.currentReview = reviewId;

        // Preencher dados do produto
        document.getElementById('editModalProductId').value = productId;
        document.getElementById('editModalProductName').textContent = productName;
        document.getElementById('editModalProductImage').src = productImage;
        document.getElementById('editModalProductImage').alt = productName;
        document.getElementById('editModalReviewId').value = reviewId;

        // Carregar dados da avaliação
        this.loadReviewData(reviewId);

        // Mostrar modal
        document.getElementById('orderEditReviewModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    /**
     * Fecha modal de edição de avaliação
     */
    closeOrderEditReviewModal() {
        document.getElementById('orderEditReviewModal').style.display = 'none';
        document.body.style.overflow = '';
        this.currentModal = null;
        this.currentReview = null;
    }

    /**
     * Fecha o modal atual
     */
    closeCurrentModal() {
        if (this.currentModal === 'review') {
            this.closeOrderReviewModal();
        } else if (this.currentModal === 'edit') {
            this.closeOrderEditReviewModal();
        }
    }

    /**
     * Carrega dados da avaliação para edição
     */
    async loadReviewData(reviewId) {
        try {
            const response = await fetch(`/api/reviews/${reviewId}`);
            const review = await response.json();

            if (review) {
                // Preencher formulário
                document.getElementById('editOrderTitle').value = review.title || '';
                document.getElementById('editOrderComment').value = review.comment || '';
                
                // Selecionar rating
                const ratingInput = document.querySelector(`#editOrderRatingInput input[value="${review.rating}"]`);
                if (ratingInput) {
                    ratingInput.checked = true;
                    this.updateRatingDisplay('#editOrderRatingInput', review.rating);
                }

                // Atualizar contadores
                document.getElementById('editOrderTitleCounter').textContent = (review.title || '').length;
                document.getElementById('editOrderCommentCounter').textContent = (review.comment || '').length;
            }
        } catch (error) {
            console.error('Erro ao carregar dados da avaliação:', error);
            this.showNotification('Erro ao carregar dados da avaliação', 'error');
        }
    }

    /**
     * Limpa formulário de avaliação
     */
    clearReviewForm() {
        document.getElementById('orderTitle').value = '';
        document.getElementById('orderComment').value = '';
        
        // Limpar rating
        const ratingInputs = document.querySelectorAll('#orderRatingInput input[type="radio"]');
        ratingInputs.forEach(input => input.checked = false);
        this.updateRatingDisplay('#orderRatingInput', 0);

        // Resetar contadores
        document.getElementById('orderTitleCounter').textContent = '0';
        document.getElementById('orderCommentCounter').textContent = '0';
    }

    /**
     * Submete nova avaliação
     */
    async submitReview() {
        const form = document.getElementById('orderReviewForm');
        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitOrderReviewBtn');

        // Validar rating
            const rating = formData.get('rating');
            if (!rating) {
            this.showNotification('Por favor, selecione uma avaliação', 'error');
                return;
            }
            
        // Desabilitar botão
            submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';
            
        try {
            const response = await fetch('/reviews', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (response.ok) {
                this.showNotification('Avaliação enviada com sucesso!', 'success');
                this.closeOrderReviewModal();
                // Recarregar página para mostrar a nova avaliação
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(result.message || 'Erro ao enviar avaliação', 'error');
            }
        } catch (error) {
            console.error('Erro ao enviar avaliação:', error);
            this.showNotification('Erro ao enviar avaliação', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Enviar Avaliação';
        }
    }

    /**
     * Submete edição de avaliação
     */
    async submitEditReview() {
        const form = document.getElementById('orderEditReviewForm');
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');

        // Validar rating
            const rating = formData.get('rating');
            if (!rating) {
            this.showNotification('Por favor, selecione uma avaliação', 'error');
                return;
            }
            
        // Desabilitar botão
            submitBtn.disabled = true;
        submitBtn.textContent = 'Salvando...';
            
        try {
            const reviewId = this.currentReview;
            const response = await fetch(`/reviews/${reviewId}`, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (response.ok) {
                this.showNotification('Avaliação atualizada com sucesso!', 'success');
                this.closeOrderEditReviewModal();
                // Recarregar página para mostrar as alterações
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(result.message || 'Erro ao atualizar avaliação', 'error');
            }
        } catch (error) {
            console.error('Erro ao atualizar avaliação:', error);
            this.showNotification('Erro ao atualizar avaliação', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Salvar Alterações';
        }
    }

    /**
     * Exclui avaliação
     */
    async deleteOrderReview(reviewId) {
        if (!confirm('Tem certeza que deseja excluir esta avaliação?')) {
            return;
        }

        try {
            const response = await fetch(`/reviews/${reviewId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok) {
                this.showNotification('Avaliação excluída com sucesso!', 'success');
                // Recarregar página para remover a avaliação
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(result.message || 'Erro ao excluir avaliação', 'error');
            }
        } catch (error) {
            console.error('Erro ao excluir avaliação:', error);
            this.showNotification('Erro ao excluir avaliação', 'error');
        }
    }

    /**
     * Mostra notificação
     */
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification--${type}`;
        notification.innerHTML = `
            <ion-icon name="${this.getNotificationIcon(type)}"></ion-icon>
            <span>${message}</span>
        `;

        document.body.appendChild(notification);

        // Mostrar notificação
        setTimeout(() => {
            notification.classList.add('notification--show');
        }, 100);

        // Remover notificação
        setTimeout(() => {
            notification.classList.remove('notification--show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    /**
     * Retorna ícone da notificação
     */
    getNotificationIcon(type) {
        const icons = {
            success: 'checkmark-circle',
            error: 'close-circle',
            warning: 'warning',
            info: 'information-circle'
        };
        return icons[type] || 'information-circle';
    }
}

// Funções globais para compatibilidade com o HTML existente
let ordersPage;

// Inicializa quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    ordersPage = new OrdersPage();
});

// Funções globais para chamadas do HTML
function openOrderReviewModal(productId, productName, productImage) {
    if (ordersPage) {
        ordersPage.openOrderReviewModal(productId, productName, productImage);
    }
}

function closeOrderReviewModal() {
    if (ordersPage) {
        ordersPage.closeOrderReviewModal();
    }
}

function openOrderEditReviewModal(reviewId, productId, productName, productImage) {
    if (ordersPage) {
        ordersPage.openOrderEditReviewModal(reviewId, productId, productName, productImage);
    }
}

function closeOrderEditReviewModal() {
    if (ordersPage) {
        ordersPage.closeOrderEditReviewModal();
    }
}

function deleteOrderReview(reviewId) {
    if (ordersPage) {
        ordersPage.deleteOrderReview(reviewId);
    }
}

// Exporta para uso global
window.OrdersPage = OrdersPage;