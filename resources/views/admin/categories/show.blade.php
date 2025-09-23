@extends('layouts.app')

@section('title', 'Categoria - ' . $category->name)

@section('content')
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <!-- Header da Página -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-header-content">
                        <div class="admin-header-info">
                            <nav class="admin-breadcrumb">
                                <a href="{{ route('admin.dashboard') }}">
                                    <ion-icon name="home-outline"></ion-icon>
            Dashboard
          </a>
                                <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                                <a href="{{ route('admin.categories.index') }}">Categorias</a>
                                <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                                <span>{{ $category->name }}</span>
        </nav>

                            <h1 class="admin-card-title">
                                <ion-icon name="pricetags-outline"></ion-icon>
                                {{ $category->name }}
                            </h1>
                            <p class="admin-card-subtitle">Detalhes da categoria e produtos associados</p>
                        </div>
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="create-outline"></ion-icon>
                                Editar Categoria
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="arrow-back-outline"></ion-icon>
                                Voltar para Lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações da Categoria -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        Informações da Categoria
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="admin-info-grid">
                        <div class="admin-info-item">
                            <span class="admin-info-label">Nome:</span>
                            <span class="admin-info-value">{{ $category->name }}</span>
                        </div>
                        <div class="admin-info-item">
                            <span class="admin-info-label">Slug:</span>
                            <span class="admin-info-value">
                                <code class="admin-code">{{ $category->slug }}</code>
                            </span>
                        </div>
                        @if($category->description)
                            <div class="admin-info-item admin-info-item-full">
                                <span class="admin-info-label">Descrição:</span>
                                <span class="admin-info-value">{{ $category->description }}</span>
                            </div>
                        @endif
                        <div class="admin-info-item">
                            <span class="admin-info-label">Status:</span>
                            <span class="admin-info-value">
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
                            </span>
                        </div>
                        <div class="admin-info-item">
                            <span class="admin-info-label">Total de Produtos:</span>
                            <span class="admin-info-value">
                                <span class="admin-badge admin-badge-category">
                                    {{ $category->products->count() }} produtos
                                </span>
                            </span>
                        </div>
      </div>

                </div>
            </div>

            <!-- Produtos da Categoria -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-header-content">
                        <div class="admin-header-info">
                            <h3 class="admin-card-title">
                                <ion-icon name="cube-outline"></ion-icon>
                                Produtos desta Categoria
                            </h3>
                            <p class="admin-card-subtitle">
                                {{ $products->total() }} produto(s) encontrado(s) 
                                @if($products->hasPages())
                                    - Página {{ $products->currentPage() }} de {{ $products->lastPage() }}
                                @endif
                            </p>
                        </div>
                        <div class="admin-header-actions">
                            <form method="GET" class="admin-pagination-form">
                                <label for="per_page" class="admin-form-label">Itens por página:</label>
                                <select name="per_page" id="per_page" class="admin-form-select" onchange="this.form.submit()">
                                    <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 itens</option>
                                    <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10 itens</option>
                                    <option value="15" {{ request('per_page', 5) == 15 ? 'selected' : '' }}>15 itens</option>
                                    <option value="25" {{ request('per_page', 5) == 25 ? 'selected' : '' }}>25 itens</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="admin-card-body">
                    @if($products->count() > 0)
                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Preço</th>
                                        <th>Estoque</th>
                                        <th>Status</th>
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
                                                <div class="admin-table-cell admin-table-center">
                                                    <span class="admin-product-price">€ {{ number_format($product->price, 2, ',', '.') }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    <span class="admin-badge {{ $product->stock > 10 ? 'admin-badge-stock-high' : ($product->stock > 0 ? 'admin-badge-stock-low' : 'admin-badge-stock-out') }}">
                                                        {{ $product->stock }} unidades
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-table-cell admin-table-center">
                                                    @if($product->active)
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
                                                <div class="admin-table-cell admin-table-center">
                                                    <div class="admin-actions">
                                                        <a href="{{ route('shop.products.show', $product) }}" 
                                                           class="admin-action-btn admin-action-btn-view" 
                                                           title="Ver detalhes"
                                                           target="_blank">
                                                            <ion-icon name="eye-outline"></ion-icon>
                                                        </a>
                                                        <a href="{{ route('admin.products.edit', $product) }}" 
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
                        @if($products->hasPages())
                            <div class="admin-pagination">
                                <div class="admin-pagination-info">
                                    Mostrando {{ $products->firstItem() ?? 0 }} até {{ $products->lastItem() ?? 0 }} de
                                    {{ $products->total() }} resultados
                                </div>

                                <ul class="pagination">
                                    {{-- Botão Anterior --}}
                                    @if ($products->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <ion-icon name="chevron-back-outline"></ion-icon>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $products->previousPageUrl() }}">
                                                <ion-icon name="chevron-back-outline"></ion-icon>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Páginas Numeradas --}}
                                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                        @if ($page == $products->currentPage())
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
                                    @if ($products->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $products->nextPageUrl() }}">
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
                            <ion-icon name="cube-outline"></ion-icon>
                            <h3>Nenhum produto encontrado</h3>
                            <p>Esta categoria ainda não possui produtos associados.</p>
                            <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-primary">
                                <ion-icon name="add-outline"></ion-icon>
                                Criar Novo Produto
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informações de Data -->
            <div class="admin-card admin-meta-info">
                <div class="admin-card-body">
                    <div class="admin-meta-grid">
                        <div class="admin-meta-item">
                            <ion-icon name="calendar-outline"></ion-icon>
                            <span class="admin-meta-label">Criada em:</span>
                            <span class="admin-meta-value">{{ $category->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="admin-meta-item">
                            <ion-icon name="time-outline"></ion-icon>
                            <span class="admin-meta-label">Última atualização:</span>
                            <span class="admin-meta-value">{{ $category->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection