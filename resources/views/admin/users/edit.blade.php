@extends('layouts.app')

@section('title', 'Editar Usuário - ' . $user->name)

@section('content')
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <!-- Header da Página -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-header-content">
                        <div class="admin-header-info">
                            <div class="admin-breadcrumb">
                                <a href="{{ route('admin.users.index') }}">Usuários</a>
                                <ion-icon name="chevron-forward"></ion-icon>
                                <a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a>
                                <ion-icon name="chevron-forward"></ion-icon>
                                <span>Editar</span>
                            </div>
                            <h1 class="admin-card-title">
                                <ion-icon name="create-outline"></ion-icon>
                                Editar Usuário
                            </h1>
                            <p class="admin-card-subtitle">Modifique as informações do usuário</p>
                        </div>
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.users.show', $user) }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="arrow-back-outline"></ion-icon>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário de Edição -->
            <div class="admin-card">
                <div class="admin-card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="admin-form">
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
                                    <label for="name" class="admin-form-label">Nome *</label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           class="admin-form-input @error('name') admin-form-input-error @enderror" 
                                           required>
                                    @error('name')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="admin-form-group">
                                    <label for="email" class="admin-form-label">Email *</label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           class="admin-form-input @error('email') admin-form-input-error @enderror" 
                                           required>
                                    @error('email')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="admin-form-row">
                                <div class="admin-form-group">
                                    <label for="phone" class="admin-form-label">Telefone</label>
                                    <input type="text" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone) }}" 
                                           class="admin-form-input @error('phone') admin-form-input-error @enderror">
                                    @error('phone')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Senha -->
                        <div class="admin-form-section">
                            <h3 class="admin-form-section-title">
                                <ion-icon name="lock-closed-outline"></ion-icon>
                                Alterar Senha
                            </h3>
                            <p class="admin-form-help">Deixe em branco para manter a senha atual</p>
                            
                            <div class="admin-form-row">
                                <div class="admin-form-group">
                                    <label for="password" class="admin-form-label">Nova Senha</label>
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="admin-form-input @error('password') admin-form-input-error @enderror">
                                    @error('password')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="admin-form-group">
                                    <label for="password_confirmation" class="admin-form-label">Confirmar Nova Senha</label>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           class="admin-form-input @error('password_confirmation') admin-form-input-error @enderror">
                                    @error('password_confirmation')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Funções -->
                        <div class="admin-form-section">
                            <h3 class="admin-form-section-title">
                                <ion-icon name="shield-checkmark-outline"></ion-icon>
                                Funções e Permissões
                            </h3>
                            
                            <div class="admin-form-group">
                                <label class="admin-form-label">Tipo de Usuário *</label>
                                <div class="admin-form-help">
                                    <ion-icon name="information-circle-outline"></ion-icon>
                                    <span>Selecione se o usuário é Administrador ou Cliente.</span>
                                </div>
                                <div class="admin-toggle-group">
                                    <div class="admin-toggle-item">
                                        <input type="radio" 
                                               id="role_admin" 
                                               name="user_type" 
                                               value="admin"
                                               {{ $user->hasRole('admin') ? 'checked' : '' }}
                                               class="admin-toggle">
                                        <label for="role_admin" class="admin-toggle-label">
                                            <span class="admin-toggle-slider"></span>
                                            <span class="admin-toggle-text admin-toggle-text-on">Administrador</span>
                                        </label>
                                    </div>
                                    <div class="admin-toggle-item">
                                        <input type="radio" 
                                               id="role_cliente" 
                                               name="user_type" 
                                               value="cliente"
                                               {{ $user->hasRole('cliente') ? 'checked' : '' }}
                                               class="admin-toggle">
                                        <label for="role_cliente" class="admin-toggle-label">
                                            <span class="admin-toggle-slider"></span>
                                            <span class="admin-toggle-text admin-toggle-text-off">Cliente</span>
                                        </label>
                                    </div>
                                </div>
                                @error('user_type')
                                    <span class="admin-form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Ações do Formulário -->
                        <div class="admin-form-actions">
                            <button type="submit" class="admin-btn admin-btn-primary">
                                <ion-icon name="save-outline"></ion-icon>
                                Salvar Alterações
                            </button>
                            <a href="{{ route('admin.users.show', $user) }}" class="admin-btn admin-btn-secondary">
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
                        <!-- Ativar/Desativar Usuário -->
                        <div class="admin-action-item">
                            <div class="admin-action-info">
                                <h4>Status da Conta</h4>
                                <p>
                                    @if($user->active)
                                        Conta ativa - usuário pode fazer login
                                    @else
                                        Conta inativa - usuário não pode fazer login
                                    @endif
                                </p>
                            </div>
                            <div class="admin-action-button">
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="admin-action-form">
                                    @csrf
                                    <button type="submit" 
                                            class="admin-btn {{ $user->active ? 'admin-btn-warning' : 'admin-btn-success' }}"
                                            onclick="showToggleStatusModal('{{ $user->name }}', {{ $user->active ? 'true' : 'false' }}); return false;">
                                        <ion-icon name="{{ $user->active ? 'pause-outline' : 'play-outline' }}"></ion-icon>
                                        {{ $user->active ? 'Desativar Conta' : 'Ativar Conta' }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Excluir Usuário -->
                        @if($user->id !== auth()->id())
                            <div class="admin-action-item">
                                <div class="admin-action-info">
                                    <h4>Excluir Usuário</h4>
                                    <p>Esta ação não pode ser desfeita. Todos os dados do usuário serão removidos permanentemente.</p>
                                </div>
                                <div class="admin-action-button">
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="admin-action-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="admin-btn admin-btn-danger"
                                                onclick="return confirm('ATENÇÃO: Esta ação não pode ser desfeita!\\n\\nTem certeza que deseja excluir permanentemente o usuário {{ $user->name }}?')">
                                            <ion-icon name="trash-outline"></ion-icon>
                                            Excluir Usuário
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="admin-action-item">
                                <div class="admin-action-info">
                                    <h4>Excluir Usuário</h4>
                                    <p>Você não pode excluir sua própria conta.</p>
                                </div>
                                <div class="admin-action-button">
                                    <button type="button" class="admin-btn admin-btn-secondary" disabled>
                                        <ion-icon name="trash-outline"></ion-icon>
                                        Não Disponível
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação para Ativar/Desativar Usuário -->
<div id="toggleStatusModal" class="admin-modal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3 id="toggleStatusModalTitle">Confirmar Ação</h3>
            <button type="button" class="admin-modal-close" onclick="closeToggleStatusModal()">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>
        <div class="admin-modal-body">
            <p id="toggleStatusModalMessage">Tem certeza que deseja realizar esta ação?</p>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="admin-btn admin-btn-secondary" onclick="closeToggleStatusModal()">
                Cancelar
            </button>
            <button type="button" class="admin-btn admin-btn-primary" id="confirmToggleStatusBtn">
                Confirmar
            </button>
        </div>
    </div>
</div>

<script>
function showToggleStatusModal(userName, isActive) {
    const modal = document.getElementById('toggleStatusModal');
    const title = document.getElementById('toggleStatusModalTitle');
    const message = document.getElementById('toggleStatusModalMessage');
    const confirmBtn = document.getElementById('confirmToggleStatusBtn');
    
    const action = isActive ? 'desativar' : 'ativar';
    const actionColor = isActive ? 'warning' : 'success';
    
    title.textContent = `${isActive ? 'Desativar' : 'Ativar'} Usuário`;
    message.textContent = `Tem certeza que deseja ${action} a conta de "${userName}"?`;
    
    // Remover classes anteriores
    confirmBtn.className = 'admin-btn admin-btn-primary';
    // Adicionar classe baseada na ação
    if (isActive) {
        confirmBtn.classList.add('admin-btn-warning');
    } else {
        confirmBtn.classList.add('admin-btn-success');
    }
    
    // Configurar ação do botão de confirmação
    confirmBtn.onclick = function() {
        // Encontrar o formulário e submetê-lo
        const form = document.querySelector('form[action*="toggle-status"]');
        if (form) {
            form.submit();
        }
        closeToggleStatusModal();
    };
    
    modal.style.display = 'flex';
}

function closeToggleStatusModal() {
    document.getElementById('toggleStatusModal').style.display = 'none';
}

// Fechar modal ao clicar fora dele
window.onclick = function(event) {
    const modal = document.getElementById('toggleStatusModal');
    if (event.target === modal) {
        closeToggleStatusModal();
    }
}
</script>
@endsection
