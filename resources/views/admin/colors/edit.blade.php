@extends('layouts.app')

@section('title', 'Editar Cor - ' . $color->name)

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
                        <a href="{{ route('admin.colors.index') }}">Cores</a>
                        <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                        <a href="{{ route('admin.colors.show', $color) }}">{{ $color->name }}</a>
                        <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                        <span>Editar</span>
                    </nav>

                    <h1 class="admin-card-title">
                        <ion-icon name="create-outline"></ion-icon>
                        Editar Cor
                    </h1>
                    <p class="admin-card-subtitle">Modifique as informações da cor</p>
                </div>
            </div>

            <!-- Formulário de Edição -->
            <div class="admin-card">
                <div class="admin-card-body">
                    <form method="POST" action="{{ route('admin.colors.update', $color) }}" class="admin-form">
                        @csrf
                        @method('PUT')

                        <!-- Informações Básicas -->
                        <div class="admin-form-section">
                            <h3 class="admin-form-section-title">
                                <ion-icon name="information-circle-outline"></ion-icon>
                                Informações Básicas
                            </h3>
                            
                            <div class="admin-form-row">
                                <div class="admin-form-group">
                                    <label for="name" class="admin-form-label">Nome da Cor *</label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $color->name) }}" 
                                           class="admin-form-input @error('name') admin-form-input-error @enderror" 
                                           required
                                           placeholder="Ex: Vermelho">
                                    @error('name')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="admin-form-group">
                                    <label for="hex_code" class="admin-form-label">Código Hexadecimal *</label>
                                    <div class="admin-form-input-group">
                                        <input type="text" 
                                               id="hex_code" 
                                               name="hex_code" 
                                               value="{{ old('hex_code', $color->hex_code) }}" 
                                               class="admin-form-input @error('hex_code') admin-form-input-error @enderror" 
                                               maxlength="7" 
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" 
                                               placeholder="#000000" 
                                               required>
                                        <div class="admin-color-preview" id="colorPreview" style="background: {{ old('hex_code', $color->hex_code) }};"></div>
                                    </div>
                                    @error('hex_code')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="admin-form-row">
                                <div class="admin-form-group">
                                    <label for="color_picker" class="admin-form-label">Seletor Visual</label>
                                    <input type="color" 
                                           id="color_picker" 
                                           class="admin-form-color" 
                                           value="{{ old('hex_code', $color->hex_code) }}">
                                    <div class="admin-form-help">Use o seletor visual para escolher a cor</div>
                                </div>
                            </div>
                        </div>

                        <!-- Configurações -->
                        <div class="admin-form-section">
                            <h3 class="admin-form-section-title">
                                <ion-icon name="settings-outline"></ion-icon>
                                Configurações
                            </h3>
                            
                            <div class="admin-form-row">
                                <div class="admin-form-group">
                                    <label class="admin-form-label">Status da Cor</label>
                                    <div class="admin-toggle-group">
                                        <div class="admin-toggle-item">
                                            <input type="checkbox" 
                                                   id="active" 
                                                   name="active" 
                                                   value="1"
                                                   {{ old('active', $color->active) ? 'checked' : '' }}
                                                   class="admin-toggle">
                                            <label for="active" class="admin-toggle-label">
                                                <span class="admin-toggle-slider"></span>
                                                <span class="admin-toggle-text">
                                                    <span class="admin-toggle-text-on">Ativa</span>
                                                    <span class="admin-toggle-text-off">Inativa</span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="admin-form-help">Quando ativa, a cor fica visível para os clientes</div>
                                    </div>
                                    @error('active')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Ações do Formulário -->
                        <div class="admin-form-actions">
                            <button type="submit" class="admin-btn admin-btn-primary">
                                <ion-icon name="save-outline"></ion-icon>
                                Salvar Alterações
                            </button>
                            <a href="{{ route('admin.colors.index') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="close-outline"></ion-icon>
                                Cancelar
                            </a>
                        </div>
                    </form>
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
                        <!-- Excluir Cor -->
                        <div class="admin-action-item">
                            <div class="admin-action-info">
                                <h4>Excluir Cor</h4>
                                <p>Esta ação não pode ser desfeita. Todos os produtos associados a esta cor serão desassociados.</p>
                            </div>
                            <div class="admin-action-button">
                                <form method="POST" action="{{ route('admin.colors.destroy', $color) }}" class="admin-action-form">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="button" 
                                            class="admin-btn admin-btn-danger"
                                            onclick="showDeleteModal('{{ $color->name }}', '{{ route('admin.colors.destroy', $color) }}')">
                                        <ion-icon name="trash-outline"></ion-icon>
                                        Excluir Cor
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
                    <p>Tem certeza que deseja excluir permanentemente a cor <strong id="deleteItemName"></strong>?</p>
                    <div class="admin-warning-details">
                        <p>• Todos os produtos associados a esta cor serão desassociados</p>
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
// Sincronização entre os seletores
document.addEventListener('DOMContentLoaded', function() {
    const colorPicker = document.getElementById('color_picker');
    const hexInput = document.getElementById('hex_code');
    const colorPreview = document.getElementById('colorPreview');

    // Atualiza ao usar o seletor visual
    colorPicker.addEventListener('input', function(e) {
        hexInput.value = e.target.value;
        colorPreview.style.backgroundColor = e.target.value;
    });

    // Atualiza ao digitar no campo hex
    hexInput.addEventListener('input', function(e) {
        const hex = e.target.value;
        if (hex.match(/^#[0-9A-Fa-f]{6}$/)) {
            colorPicker.value = hex;
            colorPreview.style.backgroundColor = hex;
        }
    });
});

// Modal de exclusão
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