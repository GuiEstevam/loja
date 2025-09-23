@extends('layouts.app')
@section('title', 'Novo Tamanho')

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
                        <span>Novo</span>
                    </nav>

                    <div class="admin-header-content">
                        <div class="admin-header-text">
                            <h1 class="admin-card-title">
                                <ion-icon name="add-circle-outline"></ion-icon>
                                Criar Novo Tamanho
                            </h1>
                            <p class="admin-card-subtitle">Adicione um novo tamanho ao sistema</p>
                        </div>
                        
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.sizes.index') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="arrow-back-outline"></ion-icon>
                                Voltar para Lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário de Criação de Tamanho -->
            <form method="POST" action="{{ route('admin.sizes.store') }}" class="admin-form">
                @csrf

                @include('admin.sizes._form')

                <!-- Ações do Formulário -->
                <div class="admin-form-actions">
                    <button type="submit" class="admin-btn admin-btn-primary">
                        <ion-icon name="add-outline"></ion-icon>
                        Criar Tamanho
                    </button>
                    <a href="{{ route('admin.sizes.index') }}" class="admin-btn admin-btn-secondary">
                        <ion-icon name="close-outline"></ion-icon>
                        Voltar para Lista
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection