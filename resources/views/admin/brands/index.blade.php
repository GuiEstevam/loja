@extends('layouts.app')

@section('title', 'Marcas')

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
                        <span>Marcas</span>
                    </nav>

                    <div class="admin-header-content">
                        <div class="admin-header-text">
                            <h1 class="admin-card-title">
                                <ion-icon name="business-outline"></ion-icon>
                                Gerenciar Marcas
                            </h1>
                            <p class="admin-card-subtitle">Visão geral e gestão de todas as marcas</p>
                        </div>
                        
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.brands.create') }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="add-outline"></ion-icon>
                                Nova Marca
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
                                    <ion-icon name="business-outline"></ion-icon>
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
                                <label for="search" class="admin-form-label">Buscar Marca</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="admin-form-input" 
                                       placeholder="Nome ou slug da marca">
                            </div>
                            <div class="admin-form-group">
                                <label for="status" class="admin-form-label">Status</label>
                                <select id="status" name="status" class="admin-form-select">
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
                            <a href="{{ route('admin.brands.index') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="refresh-outline"></ion-icon>
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Marcas -->
            <div class="admin-card">
                <div class="admin-card-body">
                    @if($brands->count() > 0)
                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Logo</th>
                                        <th>Marca</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Produtos</th>
                                        <th class="admin-table-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($brands as $brand)
                                        <tr>
                                            <td>
                                                <div class="admin-table-cell">
                                                    @if($brand->logo)
                                                        <img src="{{ asset('brands/' . $brand->logo) }}" 
                                                             alt="{{ $brand->name }}" 
                                                             class="admin-table-image">
                                                    @else
                                                        <div class="admin-table-placeholder">
                                                            <ion-icon name="business-outline"></ion-icon>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <div class="admin-table-primary">{{ $brand->name }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <code class="admin-code">{{ $brand->slug }}</code>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
                                                    @if($brand->active)
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
                                                    <span class="admin-badge admin-badge-category">
                                                        {{ $brand->products->count() }} produtos
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <div class="admin-actions">
                                                        <a href="{{ route('admin.brands.show', $brand) }}" 
                                                           class="admin-action-btn admin-action-btn-view" 
                                                           title="Ver produtos">
                                                            <ion-icon name="eye-outline"></ion-icon>
                                                        </a>
                                                        <a href="{{ route('admin.brands.edit', $brand) }}" 
                                                           class="admin-action-btn admin-action-btn-edit" 
                                                           title="Editar">
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
                        @if($brands->hasPages())
                            <div class="admin-pagination">
                                <div class="admin-pagination-info">
                                    Mostrando {{ $brands->firstItem() ?? 0 }} até {{ $brands->lastItem() ?? 0 }} de
                                    {{ $brands->total() }} resultados
                                </div>

                                <ul class="pagination">
                                    {{-- Botão Anterior --}}
                                    @if ($brands->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <ion-icon name="chevron-back-outline"></ion-icon>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $brands->previousPageUrl() }}">
                                                <ion-icon name="chevron-back-outline"></ion-icon>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Páginas Numeradas --}}
                                    @foreach ($brands->getUrlRange(1, $brands->lastPage()) as $page => $url)
                                        @if ($page == $brands->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Botão Próximo --}}
                                    @if ($brands->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $brands->nextPageUrl() }}">
                                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    @else
                        <div class="admin-empty-state">
                            <ion-icon name="business-outline"></ion-icon>
                            <h3>Nenhuma marca encontrada</h3>
                            <p>Comece criando sua primeira marca.</p>
                            <a href="{{ route('admin.brands.create') }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="add-outline"></ion-icon>
                                Criar Primeira Marca
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection