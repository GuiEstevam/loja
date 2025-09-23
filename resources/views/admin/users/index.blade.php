@extends('layouts.app')

@section('title', 'Gestão de Usuários')

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
                        <span>Usuários</span>
                    </nav>

                    <div class="admin-header-content">
                        <div class="admin-header-text">
                            <h1 class="admin-card-title">
                                <ion-icon name="people-outline"></ion-icon>
                                Gestão de Usuários
                            </h1>
                            <p class="admin-card-subtitle">Gerencie usuários, clientes e permissões</p>
                        </div>
                        
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.users.create') }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="add-outline"></ion-icon>
                                Novo Usuário
                            </a>
                            <a href="{{ route('admin.users.stats') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="analytics-outline"></ion-icon>
                                Estatísticas
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="home-outline"></ion-icon>
                                Voltar ao Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas Rápidas -->
            <div class="admin-card">
                <div class="admin-card-body">
                    <div class="admin-stats-grid">
                        <div class="admin-stat-card">
                            <div class="admin-stat-icon">
                                <ion-icon name="people-outline"></ion-icon>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">{{ $stats['total_users'] }}</div>
                                <div class="admin-stat-label">Total de Usuários</div>
                            </div>
                        </div>
                        <div class="admin-stat-card">
                            <div class="admin-stat-icon">
                                <ion-icon name="person-outline"></ion-icon>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">{{ $stats['total_clients'] }}</div>
                                <div class="admin-stat-label">Clientes</div>
                            </div>
                        </div>
                        <div class="admin-stat-card">
                            <div class="admin-stat-icon">
                                <ion-icon name="shield-checkmark-outline"></ion-icon>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">{{ $stats['total_admins'] }}</div>
                                <div class="admin-stat-label">Administradores</div>
                            </div>
                        </div>
                        <div class="admin-stat-card">
                            <div class="admin-stat-icon">
                                <ion-icon name="checkmark-circle-outline"></ion-icon>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">{{ $stats['verified_users'] }}</div>
                                <div class="admin-stat-label">Verificados</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros e Busca -->
            <div class="admin-card-body">
                <form method="GET" class="admin-filters">
                    <div class="admin-filters-main">
                        <div class="admin-form-group">
                            <label for="search" class="admin-form-label">Buscar Usuário</label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="admin-form-input" 
                                   placeholder="Nome, email ou telefone">
                        </div>
                        <div class="admin-form-group">
                            <label for="role" class="admin-form-label">Função</label>
                            <select id="role" name="role" class="admin-form-select">
                                <option value="">Todas as funções</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="admin-form-group">
                            <label for="status" class="admin-form-label">Status</label>
                            <select id="status" name="status" class="admin-form-select">
                                <option value="">Todos os status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos</option>
                            </select>
                        </div>
                    </div>
                
                    <div class="admin-filters-actions">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <ion-icon name="search-outline"></ion-icon>
                            Pesquisar
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
                            <ion-icon name="refresh-outline"></ion-icon>
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Lista de Usuários -->
            <div class="admin-card">
                <div class="admin-card-body">
                    @if($users->count() > 0)
                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Usuário</th>
                                        <th>Email</th>
                                        <th>Função</th>
                                        <th>Pedidos</th>
                                        <th>Status</th>
                                        <th>Cadastro</th>
                                        <th class="admin-table-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div class="admin-user-info">
                                                    <div class="admin-user-avatar">
                                                        @if($user->profile_photo_url)
                                                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                                        @else
                                                            <ion-icon name="person-outline"></ion-icon>
                                                        @endif
                                                    </div>
                                                    <div class="admin-user-details">
                                                        <div class="admin-user-name">{{ $user->name }}</div>
                                                        @if($user->phone)
                                                            <div class="admin-user-phone">{{ $user->phone }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <div class="admin-user-roles">
                                                    @foreach($user->roles as $role)
                                                        <span class="admin-role-badge role-{{ $role->name }}">
                                                            {{ ucfirst($role->name) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <span class="admin-badge admin-badge-category">
                                                        {{ $user->orders->count() }} pedidos
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    @if($user->active)
                                                        <span class="admin-status-badge status-active">
                                                            <ion-icon name="checkmark-circle"></ion-icon>
                                                            Ativo
                                                        </span>
                                                    @else
                                                        <span class="admin-status-badge status-inactive">
                                                            <ion-icon name="close-circle"></ion-icon>
                                                            Inativo
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-date-info">
                                                    <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                                    <div class="admin-date-time">{{ $user->created_at->format('H:i') }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <div class="admin-actions">
                                                        <a href="{{ route('admin.users.show', $user) }}" 
                                                           class="admin-action-btn admin-action-btn-view" 
                                                           title="Visualizar">
                                                            <ion-icon name="eye-outline"></ion-icon>
                                                        </a>
                                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                                           class="admin-action-btn admin-action-btn-edit" 
                                                           title="Editar">
                                                            <ion-icon name="create-outline"></ion-icon>
                                                        </a>
                                                        <form method="POST" 
                                                              action="{{ route('admin.users.toggle-status', $user) }}" 
                                                              class="admin-action-form">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="admin-action-btn {{ $user->active ? 'admin-action-btn-warning' : 'admin-action-btn-success' }}" 
                                                                    title="{{ $user->active ? 'Desativar' : 'Ativar' }}">
                                                                <ion-icon name="{{ $user->active ? 'pause-outline' : 'play-outline' }}"></ion-icon>
                                                            </button>
                                                        </form>
                                                        @if($user->id !== auth()->id())
                                                            <button type="button" 
                                                                    class="admin-action-btn admin-action-btn-danger" 
                                                                    title="Excluir"
                                                                    onclick="showDeleteModal('{{ $user->name }}', '{{ route('admin.users.destroy', $user) }}')">
                                                                <ion-icon name="trash-outline"></ion-icon>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="admin-pagination">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="admin-empty-state">
                            <ion-icon name="people-outline"></ion-icon>
                            <h3>Nenhum usuário encontrado</h3>
                            <p>Tente ajustar os filtros ou criar um novo usuário.</p>
                            <a href="{{ route('admin.users.create') }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="add-outline"></ion-icon>
                                Criar Primeiro Usuário
                            </a>
                        </div>
                    @endif
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
                    <p>Tem certeza que deseja excluir permanentemente o usuário <strong id="deleteItemName"></strong>?</p>
                    <div class="admin-warning-details">
                        <p>• Todos os dados do usuário serão removidos permanentemente</p>
                        <p>• Pedidos e avaliações associados serão mantidos</p>
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
        closeDeleteModal();
        deleteForm.submit();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection
