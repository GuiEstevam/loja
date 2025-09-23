@extends('layouts.app')

@section('title', 'Criar Novo Usuário')

@section('content')
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <!-- Header da Página -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-header-content">
                        <div class="admin-header-info">
                            <div class="admin-breadcrumb">
                                <a href="{{ route('admin.users.index') }}">Usuários</a>
                                <ion-icon name="chevron-forward"></ion-icon>
                                <span>Criar Novo</span>
                            </div>
                            <h1 class="admin-card-title">
                                <ion-icon name="person-add-outline"></ion-icon>
                                Criar Novo Usuário
                            </h1>
                            <p class="admin-card-subtitle">Adicione um novo usuário ao sistema</p>
                        </div>
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="arrow-back-outline"></ion-icon>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário de Criação -->
            <div class="admin-card">
                <div class="admin-card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}" class="admin-form">
                        @csrf

                        <!-- Informações Básicas -->
                        <div class="admin-form-section">
                            <h3 class="admin-form-section-title">
                                <ion-icon name="information-circle-outline"></ion-icon>
                                Informações Básicas
                            </h3>
                            
                            <div class="admin-form-row">
                                <div class="admin-form-group">
                                    <label for="name" class="admin-form-label">Nome *</label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           class="admin-form-input @error('name') admin-form-input-error @enderror" 
                                           required>
                                    @error('name')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="admin-form-group">
                                    <label for="email" class="admin-form-label">Email *</label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           class="admin-form-input @error('email') admin-form-input-error @enderror" 
                                           required>
                                    @error('email')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="admin-form-row">
                                <div class="admin-form-group">
                                    <label for="phone" class="admin-form-label">Telefone</label>
                                    <input type="text" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           class="admin-form-input @error('phone') admin-form-input-error @enderror">
                                    @error('phone')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Senha -->
                        <div class="admin-form-section">
                            <h3 class="admin-form-section-title">
                                <ion-icon name="lock-closed-outline"></ion-icon>
                                Senha de Acesso
                            </h3>
                            
                            <div class="admin-form-row">
                                <div class="admin-form-group">
                                    <label for="password" class="admin-form-label">Senha *</label>
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="admin-form-input @error('password') admin-form-input-error @enderror" 
                                           required>
                                    @error('password')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                    <div class="admin-form-help">Mínimo de 8 caracteres</div>
                                </div>
                                
                                <div class="admin-form-group">
                                    <label for="password_confirmation" class="admin-form-label">Confirmar Senha *</label>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           class="admin-form-input @error('password_confirmation') admin-form-input-error @enderror" 
                                           required>
                                    @error('password_confirmation')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Funções -->
                        <div class="admin-form-section">
                            <h3 class="admin-form-section-title">
                                <ion-icon name="shield-checkmark-outline"></ion-icon>
                                Funções e Permissões
                            </h3>
                            
                            <div class="admin-form-group">
                                <label class="admin-form-label">Tipo de Usuário *</label>
                                <div class="admin-form-help">
                                    <ion-icon name="information-circle-outline"></ion-icon>
                                    <span>Selecione se o usuário é Administrador ou Cliente.</span>
                                </div>
                                <div class="admin-toggle-group">
                                    <div class="admin-toggle-item">
                                        <input type="radio" 
                                               id="role_admin" 
                                               name="user_type" 
                                               value="admin"
                                               {{ old('user_type') === 'admin' ? 'checked' : '' }}
                                               class="admin-toggle">
                                        <label for="role_admin" class="admin-toggle-label">
                                            <span class="admin-toggle-slider"></span>
                                            <span class="admin-toggle-text admin-toggle-text-on">Administrador</span>
                                        </label>
                                    </div>
                                    <div class="admin-toggle-item">
                                        <input type="radio" 
                                               id="role_cliente" 
                                               name="user_type" 
                                               value="cliente"
                                               {{ old('user_type') === 'cliente' || !old('user_type') ? 'checked' : '' }}
                                               class="admin-toggle">
                                        <label for="role_cliente" class="admin-toggle-label">
                                            <span class="admin-toggle-slider"></span>
                                            <span class="admin-toggle-text admin-toggle-text-off">Cliente</span>
                                        </label>
                                    </div>
                                </div>
                                @error('user_type')
                                    <span class="admin-form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="admin-form-section">
                            <h3 class="admin-form-section-title">
                                <ion-icon name="information-outline"></ion-icon>
                                Informações Adicionais
                            </h3>
                            
                            <div class="admin-info-box">
                                <div class="admin-info-icon">
                                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                                </div>
                                <div class="admin-info-content">
                                    <h4>Conta será ativada automaticamente</h4>
                                    <p>O usuário poderá fazer login imediatamente após a criação da conta, sem necessidade de verificação de email.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Ações do Formulário -->
                        <div class="admin-form-actions">
                            <button type="submit" class="admin-btn admin-btn-primary">
                                <ion-icon name="save-outline"></ion-icon>
                                Criar Usuário
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
                                <ion-icon name="close-outline"></ion-icon>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
