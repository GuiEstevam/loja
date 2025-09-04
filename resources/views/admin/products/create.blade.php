@extends('layouts.app')

@section('title', 'Cadastrar Produto')

@push('styles')
  @vite(['resources/css/admin-products.css'])
@endpush

@section('content')
  <div class="admin-products-page">
    <div class="admin-products-container">
      <div class="admin-products-card">
        <!-- Header -->
        <div class="admin-products-header">
          <nav class="admin-products-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">
              <ion-icon name="home-outline"></ion-icon>
              Dashboard
            </a>
            <ion-icon name="chevron-forward-outline"></ion-icon>
            <a href="{{ route('admin.products.index') }}">Produtos</a>
            <ion-icon name="chevron-forward-outline"></ion-icon>
            <span>Cadastrar</span>
          </nav>

          <h1 class="admin-products-title">Cadastrar Produto</h1>
          <p class="admin-products-subtitle">Adicione um novo produto ao catálogo da loja</p>
        </div>

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
@endsection
