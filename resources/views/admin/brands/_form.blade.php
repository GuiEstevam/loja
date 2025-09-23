@props(['brand' => null])

@php
    $isEdit = $brand !== null;
    $formAction = $isEdit ? route('admin.brands.update', $brand) : route('admin.brands.store');
    $formMethod = $isEdit ? 'PUT' : 'POST';
    $submitText = $isEdit ? 'Salvar Alterações' : 'Criar Marca';
    $cancelRoute = $isEdit ? route('admin.brands.show', $brand) : route('admin.brands.index');
    $cancelText = $isEdit ? 'Cancelar' : 'Voltar para Lista';
@endphp

<form method="POST" action="{{ $formAction }}" class="admin-form" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <!-- Informações Básicas -->
    <div class="admin-form-section">
        <h3 class="admin-form-section-title">
            <ion-icon name="information-circle-outline"></ion-icon>
            Informações Básicas
        </h3>
        
        <div class="admin-form-row">
            <div class="admin-form-group">
                <label for="name" class="admin-form-label">Nome da Marca *</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $brand->name ?? '') }}" 
                       class="admin-form-input @error('name') admin-form-input-error @enderror" 
                       required
                       placeholder="Ex: Nike">
                @error('name')
                    <span class="admin-form-error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="admin-form-group">
                <label for="slug" class="admin-form-label">Slug *</label>
                <input type="text" 
                       id="slug" 
                       name="slug" 
                       value="{{ old('slug', $brand->slug ?? '') }}" 
                       class="admin-form-input @error('slug') admin-form-input-error @enderror" 
                       required
                       placeholder="Ex: nike">
                @error('slug')
                    <span class="admin-form-error">{{ $message }}</span>
                @enderror
                <div class="admin-form-help">
                    URL amigável para a marca 
                    @if(!$isEdit)
                        (será gerada automaticamente se deixada em branco)
                    @endif
                </div>
            </div>
        </div>

        <div class="admin-form-row">
            <div class="admin-form-group">
                <label for="description" class="admin-form-label">Descrição</label>
                <textarea id="description" 
                          name="description" 
                          class="admin-form-textarea @error('description') admin-form-input-error @enderror" 
                          rows="4"
                          placeholder="Descrição opcional da marca">{{ old('description', $brand->description ?? '') }}</textarea>
                @error('description')
                    <span class="admin-form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Logo da Marca -->
    <div class="admin-form-section">
        <h3 class="admin-form-section-title">
            <ion-icon name="image-outline"></ion-icon>
            Logo da Marca
        </h3>
        
        <div class="admin-form-row">
            <div class="admin-form-group">
                <label for="logo" class="admin-form-label">Logo</label>
                <input type="file" 
                       id="logo" 
                       name="logo" 
                       class="admin-form-input @error('logo') admin-form-input-error @enderror" 
                       accept="image/*">
                @error('logo')
                    <span class="admin-form-error">{{ $message }}</span>
                @enderror
                <div class="admin-form-help">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</div>
                
                @if($isEdit && $brand->logo)
                    <div class="admin-form-preview">
                        <img src="{{ asset('brands/' . $brand->logo) }}" 
                             alt="{{ $brand->name }}" 
                             class="admin-preview-image">
                        <p class="admin-form-help">Logo atual</p>
                    </div>
                @endif
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
                <label class="admin-form-label">Status da Marca</label>
                <div class="admin-checkbox-group">
                    <div class="admin-checkbox-item">
                        <input type="checkbox" 
                               id="active" 
                               name="active" 
                               value="1"
                               {{ old('active', $brand->active ?? true) ? 'checked' : '' }}
                               class="admin-checkbox">
                        <label for="active" class="admin-checkbox-label">
                            <span class="admin-checkbox-custom"></span>
                            Marca ativa (visível para clientes)
                        </label>
                    </div>
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
            <ion-icon name="{{ $isEdit ? 'save-outline' : 'add-outline' }}"></ion-icon>
            {{ $submitText }}
        </button>
        <a href="{{ $cancelRoute }}" class="admin-btn admin-btn-secondary">
            <ion-icon name="{{ $isEdit ? 'close-outline' : 'arrow-back-outline' }}"></ion-icon>
            {{ $cancelText }}
        </a>
    </div>
</form>

<script>
// Auto-gerar slug baseado no nome (apenas se o slug estiver vazio)
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
            const slug = this.value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '') // Remove acentos
                .replace(/[^a-z0-9\s-]/g, '') // Remove caracteres especiais
                .replace(/\s+/g, '-') // Substitui espaços por hífens
                .replace(/-+/g, '-') // Remove hífens duplicados
                .trim();
            
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });
    
    // Marcar como não auto-gerado quando o usuário editar manualmente
    slugInput.addEventListener('input', function() {
        this.dataset.autoGenerated = 'false';
    });
});
</script>
