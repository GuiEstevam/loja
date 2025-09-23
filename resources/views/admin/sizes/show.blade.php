@extends('layouts.app')

@section('title', 'Tamanho - ' . $size->name)

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
                        <a href="{{ route('admin.sizes.index') }}">Tamanhos</a>
                        <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                        <span>{{ $size->name }}</span>
                    </nav>

                    <div class="admin-header-content">
                        <div class="admin-header-text">
                            <h1 class="admin-card-title">
                                <ion-icon name="resize-outline"></ion-icon>
                                {{ $size->name }}
                            </h1>
                            <p class="admin-card-subtitle">Detalhes do tamanho</p>
                        </div>
                        
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.sizes.edit', $size) }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="create-outline"></ion-icon>
                                Editar Tamanho
                            </a>
                            <a href="{{ route('admin.sizes.index') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="arrow-back-outline"></ion-icon>
                                Voltar para Lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Tamanho -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        Informações do Tamanho
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="admin-info-grid">
                        <div class="admin-info-item">
                            <div class="admin-info-label">Nome</div>
                            <div class="admin-info-value">{{ $size->name }}</div>
                        </div>
                        
                        <div class="admin-info-item">
                            <div class="admin-info-label">Status</div>
                            <div class="admin-info-value">
                                <span class="admin-status-badge {{ $size->active ? 'status-active' : 'status-inactive' }}">
                                    {{ $size->active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas do Tamanho -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <ion-icon name="analytics-outline"></ion-icon>
                        Estatísticas do Tamanho
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="admin-stats-grid">
                        <div class="admin-stat-card">
                            <div class="admin-stat-icon">
                                <ion-icon name="cube-outline"></ion-icon>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">{{ $stats['total_products'] }}</div>
                                <div class="admin-stat-label">Total de Produtos</div>
                            </div>
                        </div>
                        
                        <div class="admin-stat-card">
                            <div class="admin-stat-icon">
                                <ion-icon name="checkmark-circle-outline"></ion-icon>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">{{ $stats['active_products'] }}</div>
                                <div class="admin-stat-label">Produtos Ativos</div>
                            </div>
                        </div>
                        
                        <div class="admin-stat-card">
                            <div class="admin-stat-icon">
                                <ion-icon name="pause-circle-outline"></ion-icon>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">{{ $stats['inactive_products'] }}</div>
                                <div class="admin-stat-label">Produtos Inativos</div>
                            </div>
                        </div>
                        
                        <div class="admin-stat-card">
                            <div class="admin-stat-icon">
                                <ion-icon name="bag-outline"></ion-icon>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">{{ $stats['total_orders'] }}</div>
                                <div class="admin-stat-label">Total de Vendas</div>
                            </div>
                        </div>
                        
                        <div class="admin-stat-card">
                            <div class="admin-stat-icon">
                                <ion-icon name="cash-outline"></ion-icon>
                            </div>
                            <div class="admin-stat-content">
                                <div class="admin-stat-value">€ {{ number_format($stats['total_revenue'], 2, ',', '.') }}</div>
                                <div class="admin-stat-label">Receita Total</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produtos Associados -->
            @if($products->count() > 0)
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">
                            <ion-icon name="cube-outline"></ion-icon>
                            Produtos com este Tamanho
                        </h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Marca</th>
                                        <th>Categorias</th>
                                        <th>Status</th>
                                        <th>Preço</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <div class="admin-product-info">
                                                        @if($product->image)
                                                            <img src="{{ asset('products/' . $product->image) }}" 
                                                                 alt="{{ $product->name }}" 
                                                                 class="admin-table-image">
                                                        @else
                                                            <div class="admin-table-placeholder">
                                                                <ion-icon name="image-outline"></ion-icon>
                                                            </div>
                                                        @endif
                                                        <div class="admin-product-details">
                                                            <strong>{{ $product->name }}</strong>
                                                            <span class="admin-text-secondary">{{ $product->sku ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
                                                    <span class="admin-text-secondary">{{ $product->brand->name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell">
                                                    @if($product->categories->count() > 0)
                                                        <div class="admin-badges">
                                                            @foreach($product->categories->take(2) as $category)
                                                                <span class="admin-badge-category">{{ $category->name }}</span>
                                                            @endforeach
                                                            @if($product->categories->count() > 2)
                                                                <span class="admin-badge-category">+{{ $product->categories->count() - 2 }}</span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="admin-text-secondary">N/A</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <span class="admin-status-badge {{ $product->active ? 'status-active' : 'status-inactive' }}">
                                                        {{ $product->active ? 'Ativo' : 'Inativo' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <span class="admin-product-price">€ {{ number_format($product->price, 2, ',', '.') }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <div class="admin-actions">
                                                        <a href="{{ route('shop.products.show', $product) }}" 
                                                           class="admin-action-btn admin-action-btn-view" 
                                                           title="Visualizar produto" 
                                                           target="_blank">
                                                            <ion-icon name="eye-outline"></ion-icon>
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
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="admin-card">
                    <div class="admin-card-body">
                        <div class="admin-empty-state">
                            <ion-icon name="cube-outline"></ion-icon>
                            <h3>Nenhum produto encontrado</h3>
                            <p>Este tamanho ainda não está associado a nenhum produto.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Informações de Data -->
            <div class="admin-card admin-meta-info">
                <div class="admin-card-body">
                    <div class="admin-meta-grid">
                        <div class="admin-meta-item">
                            <ion-icon name="calendar-outline"></ion-icon>
                            <span class="admin-meta-label">Criado em:</span>
                            <span class="admin-meta-value">{{ $size->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="admin-meta-item">
                            <ion-icon name="time-outline"></ion-icon>
                            <span class="admin-meta-label">Última atualização:</span>
                            <span class="admin-meta-value">{{ $size->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection