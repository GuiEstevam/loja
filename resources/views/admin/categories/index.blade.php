@extends('layouts.app')

@section('title', 'Categorias')

@section('content')
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <div class="admin-card">
                <!-- Header -->
                <div class="admin-card-header">
                    <nav class="admin-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">
                            <ion-icon name="home-outline"></ion-icon>
                            Dashboard
                        </a>
                        <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                        <span>Categorias</span>
                    </nav>

                    <div class="admin-header-content">
                        <div class="admin-header-text">
                            <h1 class="admin-card-title">
                                <ion-icon name="pricetags-outline"></ion-icon>
                                Gerenciar Categorias
                            </h1>
                            <p class="admin-card-subtitle">Visão geral e gestão de todas as categorias</p>
                        </div>
                        
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="add-outline"></ion-icon>
                                Nova Categoria
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
                                        <ion-icon name="pricetags-outline"></ion-icon>
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
                                    <div class="admin-stat-label">Ativas</div>
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
                                    <div class="admin-stat-label">Inativas</div>
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
                                <label class="admin-form-label">Buscar Categoria</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Nome ou slug..." class="admin-form-input">
                            </div>

                            <div class="admin-form-group">
                                <label class="admin-form-label">Status</label>
                                <select name="status" class="admin-form-select">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativas</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativas</option>
                                </select>
                            </div>
                        </div>

                        <div class="admin-filters-actions">
                            <button type="submit" class="admin-btn admin-btn-primary">
                                <ion-icon name="search-outline"></ion-icon>
                                Pesquisar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela -->
            <div class="admin-card">
                <div class="admin-card-body">
                    <div class="admin-table-container">
                    <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Slug</th>
                                        <th>Produtos</th>
                                        <th>Status</th>
                                        <th class="admin-table-center">Ações</th>
                                    </tr>
                                </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>
                                        <div class="admin-table-cell">
                                            <strong>{{ $category->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="admin-table-cell">
                                            <code class="admin-code">{{ $category->slug }}</code>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="admin-table-cell">
                                            <span class="admin-badge admin-badge-category">
                                                {{ $category->products->count() }} produtos
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="admin-table-cell">
                                            @if($category->active)
                                                <span class="admin-status-badge status-active">
                                                    <ion-icon name="checkmark-circle"></ion-icon>
                                                    Ativa
                                                </span>
                                            @else
                                                <span class="admin-status-badge status-inactive">
                                                    <ion-icon name="close-circle"></ion-icon>
                                                    Inativa
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="admin-table-cell">
                                                    <div class="admin-actions">
                                                        <a href="{{ route('admin.categories.show', $category) }}" 
                                                           class="admin-action-btn admin-action-btn-view" 
                                                           title="Ver produtos">
                                                            <ion-icon name="eye-outline"></ion-icon>
                                                        </a>
                                                        <a href="{{ route('admin.categories.edit', $category) }}" 
                                                           class="admin-action-btn admin-action-btn-edit" 
                                                           title="Editar">
                                                            <ion-icon name="create-outline"></ion-icon>
                                                        </a>
                                                    </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="admin-empty-state">
                                        <ion-icon name="pricetags-outline"></ion-icon>
                                        <h3>Nenhuma categoria encontrada</h3>
                                        <p>Não há categorias cadastradas no sistema.</p>
                                        <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-primary">
                                            <ion-icon name="add-outline"></ion-icon>
                                            Criar Primeira Categoria
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>

                    <!-- Paginação -->
                    @if($categories->hasPages())
                        <div class="admin-pagination">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
