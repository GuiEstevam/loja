@extends('layouts.app')

@section('title', 'Cores')

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
                        <span>Cores</span>
                    </nav>

                    <div class="admin-header-content">
                        <div class="admin-header-text">
                            <h1 class="admin-card-title">
                                <ion-icon name="color-palette-outline"></ion-icon>
                                Gerenciar Cores
                            </h1>
                            <p class="admin-card-subtitle">Visão geral e gestão de todas as cores</p>
                        </div>
                        
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.colors.create') }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="add-outline"></ion-icon>
                                Nova Cor
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="home-outline"></ion-icon>
                                Voltar ao Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="admin-card-body">
                    <form method="GET" class="admin-filters">
                        <div class="admin-filters-main">
                            <div class="admin-form-group">
                                <label for="search" class="admin-form-label">Buscar Cor</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="admin-form-input" 
                                       placeholder="Nome ou código da cor">
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
                            <a href="{{ route('admin.colors.index') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="refresh-outline"></ion-icon>
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Cores -->
            <div class="admin-card">
                <div class="admin-card-body">
                    @if($colors->count() > 0)
                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Código</th>
                                        <th>Swatch</th>
                                        <th>Produtos</th>
                                        <th>Status</th>
                                        <th class="admin-table-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($colors as $color)
                                        <tr>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <strong>{{ $color->name }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <span class="admin-text-secondary">{{ $color->hex_code ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <span class="admin-color-swatch" 
                                                          style="background: {{ $color->hex_code ?? '#eee' }};"></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <span class="admin-badge admin-badge-category">
                                                        {{ $color->products->count() }} produtos
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <span class="admin-status-badge {{ $color->active ? 'status-active' : 'status-inactive' }}">
                                                        {{ $color->active ? 'Ativa' : 'Inativa' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <div class="admin-actions">
                                                        <a href="{{ route('admin.colors.show', $color) }}" 
                                                           class="admin-action-btn admin-action-btn-view" 
                                                           title="Visualizar cor">
                                                            <ion-icon name="eye-outline"></ion-icon>
                                                        </a>
                                                        <a href="{{ route('admin.colors.edit', $color) }}" 
                                                           class="admin-action-btn admin-action-btn-edit" 
                                                           title="Editar cor">
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
                            {{ $colors->links() }}
                        </div>
                    @else
                        <div class="admin-empty-state">
                            <ion-icon name="color-palette-outline"></ion-icon>
                            <h3>Nenhuma cor encontrada</h3>
                            <p>Não há cores cadastradas no sistema.</p>
                            <a href="{{ route('admin.colors.create') }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="add-outline"></ion-icon>
                                Criar Primeira Cor
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection