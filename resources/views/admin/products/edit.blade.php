@extends('layouts.app')

@section('title', 'Editar Produto')

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
            <span>Editar</span>
          </nav>

          <h1 class="admin-products-title">Editar Produto</h1>
          <p class="admin-products-subtitle">Atualize as informações do produto</p>
        </div>

        @include('admin.products._form', [
            'formAction' => route('admin.products.update', $product),
            'product' => $product,
            'brands' => $brands,
            'categories' => $categories,
            'colors' => $colors,
            'sizes' => $sizes,
        ])
      </div>
    </div>
  </div>
@endsection
