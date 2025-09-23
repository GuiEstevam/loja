@extends('layouts.app')

@section('title', 'Nova Cor')

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
                        <a href="{{ route('admin.colors.index') }}">Cores</a>
                        <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
                        <span>Nova</span>
                    </nav>

                    <h1 class="admin-card-title">
                        <ion-icon name="add-outline"></ion-icon>
                        Nova Cor
                    </h1>
                    <p class="admin-card-subtitle">Adicione uma nova cor ao sistema</p>
                </div>
            </div>

            <!-- Formulário de Criação -->
            <div class="admin-card">
                <div class="admin-card-body">
                    <form method="POST" action="{{ route('admin.colors.store') }}" class="admin-form">
                        @csrf

                        <!-- Informações Básicas -->
                        <div class="admin-form-section">
                            <h3 class="admin-form-section-title">
                                <ion-icon name="information-circle-outline"></ion-icon>
                                Informações Básicas
                            </h3>
                            
                            <div class="admin-form-row">
                                <div class="admin-form-group">
                                    <label for="name" class="admin-form-label">Nome da Cor *</label>
                                    <select name="name" id="name" class="admin-form-select @error('name') admin-form-input-error @enderror" required>
                                        <option value="">Selecione uma cor...</option>
                                        @foreach ([
                                            'Preto' => '#000000',
                                            'Branco' => '#ffffff',
                                            'Vermelho' => '#ff0000',
                                            'Verde' => '#00ff00',
                                            'Azul' => '#0000ff',
                                            'Amarelo' => '#ffff00',
                                            'Laranja' => '#ffa500',
                                            'Roxo' => '#800080',
                                            'Marrom' => '#a52a2a',
                                            'Cinza' => '#808080',
                                            'Bege' => '#f5f5dc',
                                            'Rosa' => '#ffc0cb',
                                            'Dourado' => '#ffd700',
                                            'Prata' => '#c0c0c0',
                                            'Bordô' => '#800000',
                                            'Turquesa' => '#40e0d0',
                                            'Vinho' => '#8b0000',
                                            'Lilás' => '#c8a2c8',
                                            'Fúcsia' => '#ff00ff',
                                            'Oliva' => '#808000',
                                            'Salmão' => '#fa8072',
                                            'Azul Royal' => '#4169e1',
                                            'Verde Limão' => '#32cd32',
                                            'Verde Água' => '#20b2aa',
                                            'Magenta' => '#ff00ff',
                                            'Caramelo' => '#af6e4d',
                                            'Pêssego' => '#ffe5b4',
                                            'Mostarda' => '#ffdb58',
                                            'Cobre' => '#b87333',
                                            'Champanhe' => '#f7e7ce',
                                        ] as $colorName => $hexCode)
                                            <option value="{{ $colorName }}" data-hex="{{ $hexCode }}">{{ $colorName }}</option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                    <div class="admin-form-help">Selecione uma cor para preencher o código automaticamente</div>
                                </div>
                                
                                <div class="admin-form-group">
                                    <label for="hex_code" class="admin-form-label">Código Hexadecimal *</label>
                                    <div class="admin-form-input-group">
                                        <input type="text" 
                                               id="hex_code" 
                                               name="hex_code" 
                                               value="{{ old('hex_code', '#000000') }}" 
                                               class="admin-form-input @error('hex_code') admin-form-input-error @enderror" 
                                               maxlength="7" 
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" 
                                               placeholder="#000000" 
                                               required>
                                        <div class="admin-color-preview" id="colorPreview" style="background: {{ old('hex_code', '#000000') }};"></div>
                                    </div>
                                    @error('hex_code')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="admin-form-row">
                                <div class="admin-form-group">
                                    <label for="color_picker" class="admin-form-label">Seletor Visual</label>
                                    <input type="color" 
                                           id="color_picker" 
                                           class="admin-form-color" 
                                           value="{{ old('hex_code', '#000000') }}">
                                    <div class="admin-form-help">Use o seletor visual para escolher a cor</div>
                                </div>
                            </div>
                        </div>

                        <!-- Configurações -->
                        <div class="admin-form-section">
                            <h3 class="admin-form-section-title">
                                <ion-icon name="settings-outline"></ion-icon>
                                Configurações
                            </h3>
                            
                            <div class="admin-form-row">
                                <div class="admin-form-group">
                                    <label class="admin-form-label">Status da Cor</label>
                                    <div class="admin-toggle-group">
                                        <div class="admin-toggle-item">
                                            <input type="checkbox" 
                                                   id="active" 
                                                   name="active" 
                                                   value="1"
                                                   {{ old('active', true) ? 'checked' : '' }}
                                                   class="admin-toggle">
                                            <label for="active" class="admin-toggle-label">
                                                <span class="admin-toggle-slider"></span>
                                                <span class="admin-toggle-text">
                                                    <span class="admin-toggle-text-on">Ativa</span>
                                                    <span class="admin-toggle-text-off">Inativa</span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="admin-form-help">Quando ativa, a cor fica visível para os clientes</div>
                                    </div>
                                    @error('active')
                                        <span class="admin-form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Ações do Formulário -->
                        <div class="admin-form-actions">
                            <button type="submit" class="admin-btn admin-btn-primary">
                                <ion-icon name="add-outline"></ion-icon>
                                Criar Cor
                            </button>
                            <a href="{{ route('admin.colors.index') }}" class="admin-btn admin-btn-secondary">
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

<script>
// Sincronização entre os seletores
document.addEventListener('DOMContentLoaded', function() {
    const nameSelect = document.getElementById('name');
    const colorPicker = document.getElementById('color_picker');
    const hexInput = document.getElementById('hex_code');
    const colorPreview = document.getElementById('colorPreview');

    // Atualiza ao selecionar nome
    nameSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const hex = selectedOption.getAttribute('data-hex') || '#000000';
        hexInput.value = hex;
        colorPicker.value = hex;
        colorPreview.style.backgroundColor = hex;
    });

    // Atualiza ao usar o seletor visual
    colorPicker.addEventListener('input', function(e) {
        hexInput.value = e.target.value;
        colorPreview.style.backgroundColor = e.target.value;

        // Atualiza o select para "Custom" se não corresponder
        const hex = e.target.value.toLowerCase();
        let found = false;

        Array.from(nameSelect.options).forEach(option => {
            if (option.getAttribute('data-hex')?.toLowerCase() === hex) {
                nameSelect.value = option.value;
                found = true;
            }
        });

        if (!found) nameSelect.value = '';
    });

    // Atualiza ao digitar no campo hex
    hexInput.addEventListener('input', function(e) {
        const hex = e.target.value;
        if (hex.match(/^#[0-9A-Fa-f]{6}$/)) {
            colorPicker.value = hex;
            colorPreview.style.backgroundColor = hex;
        }
    });
});
</script>
@endsection