@extends('layouts.app')

@section('title', 'Editar Marca - ' . $brand->name)

@section('content')
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <!-- Header da Página -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <nav class="admin-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">
                            <ion-icon name="home-outline"></ion-icon>
                            Dashboard
                        </a>
                        <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                        <a href="{{ route('admin.brands.index') }}">Marcas</a>
                        <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                        <a href="{{ route('admin.brands.show', $brand) }}">{{ $brand->name }}</a>
                        <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                        <span>Editar</span>
                    </nav>

                    <h1 class="admin-card-title">
                        <ion-icon name="create-outline"></ion-icon>
                        Editar Marca
                    </h1>
                    <p class="admin-card-subtitle">Modifique as informações da marca</p>
                </div>
            </div>

            <!-- Formulário de Edição -->
            <div class="admin-card">
                <div class="admin-card-body">
                    @include('admin.brands._form', ['brand' => $brand])
                </div>
            </div>

            <!-- Seção de Ações Avançadas -->
            <div class="admin-card admin-danger-zone">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <ion-icon name="warning-outline"></ion-icon>
                        Área de Perigo
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="admin-advanced-actions">
                        <!-- Excluir Marca -->
                        <div class="admin-action-item">
                            <div class="admin-action-info">
                                <h4>Excluir Marca</h4>
                                <p>Esta ação não pode ser desfeita. Todos os produtos associados a esta marca serão desassociados.</p>
                                @if($brand->products->count() > 0)
                                    <div class="admin-warning">
                                        <ion-icon name="warning-outline"></ion-icon>
                                        <strong>Atenção:</strong> Esta marca possui {{ $brand->products->count() }} produto(s) associado(s).
                                    </div>
                                @endif
                            </div>
                            <div class="admin-action-button">
                                <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" class="admin-action-form">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="button" 
                                            class="admin-btn admin-btn-danger"
                                            onclick="showDeleteModal('{{ $brand->name }}', '{{ route('admin.brands.destroy', $brand) }}')">
                                        <ion-icon name="trash-outline"></ion-icon>
                                        Excluir Marca
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="deleteModal" class="admin-modal" style="display: none;">
    <div class="admin-modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3 class="admin-modal-title">
                <ion-icon name="warning-outline"></ion-icon>
                Confirmar Exclusão
            </h3>
            <button type="button" class="admin-modal-close" onclick="closeDeleteModal()">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>
        <div class="admin-modal-body">
            <div class="admin-warning-large">
                <ion-icon name="alert-circle-outline"></ion-icon>
                <div>
                    <h4>ATENÇÃO: Esta ação não pode ser desfeita!</h4>
                    <p>Tem certeza que deseja excluir permanentemente a marca <strong id="deleteItemName"></strong>?</p>
                    <div class="admin-warning-details">
                        <p>• Todos os produtos associados a esta marca serão desassociados</p>
                        <p>• Esta ação é irreversível</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="admin-btn admin-btn-secondary" onclick="closeDeleteModal()">
                <ion-icon name="close-outline"></ion-icon>
                Cancelar
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-btn admin-btn-danger" onclick="submitDeleteForm()">
                    <ion-icon name="trash-outline"></ion-icon>
                    Excluir Permanentemente
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function showDeleteModal(itemName, deleteUrl) {
    const modal = document.getElementById('deleteModal');
    const itemNameElement = document.getElementById('deleteItemName');
    const deleteForm = document.getElementById('deleteForm');
    
    itemNameElement.textContent = itemName;
    deleteForm.action = deleteUrl;
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function submitDeleteForm() {
    const deleteForm = document.getElementById('deleteForm');
    if (deleteForm && deleteForm.action) {
        // Fechar modal antes de enviar
        closeDeleteModal();
        // Enviar formulário
        deleteForm.submit();
    }
}

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection