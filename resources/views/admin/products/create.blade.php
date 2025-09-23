@extends('layouts.app')

@section('title', 'Cadastrar Produto')

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
              <a href="{{ route('admin.products.index') }}">Produtos</a>
              <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
              <span>Cadastrar</span>
            </nav>

            <h1 class="admin-card-title">
              <ion-icon name="add-circle-outline"></ion-icon>
              Cadastrar Produto
            </h1>
            <p class="admin-card-subtitle">Adicione um novo produto ao catálogo da loja</p>
          </div>

          <div class="admin-card-body">
            @include('admin.products._form', [
                'formAction' => route('admin.products.store'),
                'brands' => $brands,
                'categories' => $categories,
                'colors' => $colors,
                'sizes' => $sizes,
            ])
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
