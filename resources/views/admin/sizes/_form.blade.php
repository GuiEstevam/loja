<!-- Informações Básicas -->
<div class="admin-form-section">
    <h3 class="admin-form-section-title">
        <ion-icon name="information-circle-outline"></ion-icon>
        Informações do Tamanho
    </h3>
    
    <div class="admin-form-row">
        <div class="admin-form-group">
            <label for="name" class="admin-form-label">Nome do Tamanho *</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   value="{{ old('name', $size->name ?? '') }}"
                   class="admin-form-input @error('name') admin-form-input-error @enderror" 
                   placeholder="Ex: P, M, G, GG, 38, 40, Único..." 
                   required>
            <div class="admin-form-help">Ex: P, M, G, GG, 38, 40, Único, etc.</div>
            @error('name')
                <span class="admin-form-error">{{ $message }}</span>
            @enderror
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
            <label class="admin-form-label">Status do Tamanho</label>
            <div class="admin-toggle-group">
                <div class="admin-toggle-item">
                    <input type="checkbox" 
                           id="active" 
                           name="active" 
                           value="1"
                           {{ old('active', $size->active ?? true) ? 'checked' : '' }}
                           class="admin-toggle">
                    <label for="active" class="admin-toggle-label">
                        <span class="admin-toggle-slider"></span>
                        <span class="admin-toggle-text">
                            <span class="admin-toggle-text-on">Ativo</span>
                            <span class="admin-toggle-text-off">Inativo</span>
                        </span>
                    </label>
                </div>
                <div class="admin-form-help">Quando ativo, o tamanho fica visível para os clientes</div>
            </div>
            @error('active')
                <span class="admin-form-error">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
