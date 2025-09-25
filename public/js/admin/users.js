/**
 * Admin Users Management
 * Funcionalidades para gerenciamento de usuários no painel administrativo
 */

/**
 * Exibir modal de confirmação de exclusão
 * @param {string} itemName - Nome do usuário a ser excluído
 * @param {string} deleteUrl - URL para exclusão
 */
function showDeleteModal(itemName, deleteUrl) {
    const modal = document.getElementById('deleteModal');
    const itemNameElement = document.getElementById('deleteItemName');
    const deleteForm = document.getElementById('deleteForm');
    
    itemNameElement.textContent = itemName;
    deleteForm.action = deleteUrl;
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

/**
 * Fechar modal de confirmação de exclusão
 */
function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

/**
 * Submeter formulário de exclusão
 */
function submitDeleteForm() {
    const deleteForm = document.getElementById('deleteForm');
    if (deleteForm && deleteForm.action) {
        closeDeleteModal();
        deleteForm.submit();
    }
}

// Event listener para fechar modal com tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
