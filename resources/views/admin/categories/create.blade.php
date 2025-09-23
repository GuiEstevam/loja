@extends('layouts.app')

@section('title', 'Criar Nova Categoria')

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
                        <a href="{{ route('admin.categories.index') }}">Categorias</a>
                        <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                        <span>Criar Nova</span>
                    </nav>

                    <h1 class="admin-card-title">
                        <ion-icon name="add-outline"></ion-icon>
                        Criar Nova Categoria
                    </h1>
                    <p class="admin-card-subtitle">Adicione uma nova categoria ao sistema</p>
                </div>
            </div>

            <!-- Formulário de Criação -->
            <div class="admin-card">
                <div class="admin-card-body">
                    @include('admin.categories._form')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection