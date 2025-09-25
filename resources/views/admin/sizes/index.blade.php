@extends('layouts.app')
@section('title', 'Gerenciar Tamanhos')

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
                        <span>Tamanhos</span>
                    </nav>

                    <div class="admin-header-content">
                        <div class="admin-header-text">
                            <h1 class="admin-card-title">
                                <ion-icon name="resize-outline"></ion-icon>
                                Gerenciar Tamanhos
                            </h1>
                            <p class="admin-card-subtitle">Visão geral e gestão de todos os tamanhos</p>
                        </div>
                        
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.sizes.create') }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="add-outline"></ion-icon>
                                Novo Tamanho
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="home-outline"></ion-icon>
                                Voltar ao Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="admin-card-body">
                    <div class="admin-stats-container">
                    <div class="admin-stats-grid">
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon">
                                    <ion-icon name="resize-outline"></ion-icon>
                                </div>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">{{ $stats['total'] }}</div>
                                <div class="admin-stat-label">Total</div>
                            </div>
                        </div>
                        
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon">
                                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                                </div>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">{{ $stats['active'] }}</div>
                                <div class="admin-stat-label">Ativos</div>
                            </div>
                        </div>
                        
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon">
                                    <ion-icon name="close-circle-outline"></ion-icon>
                                </div>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">{{ $stats['inactive'] }}</div>
                                <div class="admin-stat-label">Inativos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">
                    <ion-icon name="search-outline"></ion-icon>
                    Filtros de Busca
                </h2>
            </div>
            <div class="admin-card-body">
                <form method="GET" class="admin-filters">
                    <div class="admin-filters-main">
                        <div class="admin-form-group">
                            <label for="search" class="admin-form-label">Buscar Tamanho</label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="admin-form-input" 
                                   placeholder="Nome do tamanho">
                        </div>
                        <div class="admin-form-group">
                            <label for="status" class="admin-form-label">Status</label>
                            <select id="status" name="status" class="admin-form-select">
                                <option value="">Todos</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativos</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativos</option>
                            </select>
                        </div>
                    </div>
                
                    <div class="admin-filters-actions">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <ion-icon name="search-outline"></ion-icon>
                            Pesquisar
                        </button>
                        <a href="{{ route('admin.sizes.index') }}" class="admin-btn admin-btn-secondary">
                            <ion-icon name="refresh-outline"></ion-icon>
                            Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Tamanhos -->
        <div class="admin-card">
            <div class="admin-card-body">
                    @if($sizes->count() > 0)
                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Produtos</th>
                                        <th>Status</th>
                                        <th class="admin-table-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sizes as $size)
                                        <tr>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <strong>{{ $size->name }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <span class="admin-badge admin-badge-category">
                                                        {{ $size->products->count() }} produtos
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <span class="admin-status-badge {{ $size->active ? 'status-active' : 'status-inactive' }}">
                                                        {{ $size->active ? 'Ativo' : 'Inativo' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <div class="admin-actions">
                                                        <a href="{{ route('admin.sizes.show', $size) }}" 
                                                           class="admin-action-btn admin-action-btn-view" 
                                                           title="Visualizar tamanho">
                                                            <ion-icon name="eye-outline"></ion-icon>
                                                        </a>
                                                        <a href="{{ route('admin.sizes.edit', $size) }}" 
                                                           class="admin-action-btn admin-action-btn-edit" 
                                                           title="Editar tamanho">
                                                            <ion-icon name="create-outline"></ion-icon>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="admin-pagination-container">
                            {{ $sizes->links() }}
                        </div>
                    @else
                        <div class="admin-empty-state">
                            <ion-icon name="resize-outline"></ion-icon>
                            <h3>Nenhum tamanho encontrado</h3>
                            <p>Comece criando um novo tamanho para gerenciar.</p>
                            <a href="{{ route('admin.sizes.create') }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="add-outline"></ion-icon>
                                Criar Novo Tamanho
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection