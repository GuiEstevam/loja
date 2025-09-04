@if ($errors->any())
  <div class="admin-form-section" style="margin: 0; padding: 0 var(--admin-products-spacing-xl);">
    <div
      style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--admin-products-danger); padding: var(--admin-products-spacing); border-radius: var(--admin-products-radius);">
      <ul style="list-style: disc; margin: 0; padding-left: var(--admin-products-spacing);">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  </div>
@endif

<!-- Formulário -->
<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="admin-products-form">
  @csrf
  @if (isset($product))
    @method('PUT')
  @endif

  <!-- Informações Básicas -->
  <div class="admin-form-section">
    <h2 class="admin-form-section-title">
      <ion-icon name="information-circle-outline"></ion-icon>
      Informações Básicas
    </h2>

    <div class="admin-form-grid">
      <div class="admin-form-group">
        <label for="name" class="admin-form-label">Nome do Produto</label>
        <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}"
          class="admin-form-input" required>
      </div>

      <div class="admin-form-group">
        <label for="slug" class="admin-form-label">URL Amigável (Slug)</label>
        <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug ?? '') }}"
          class="admin-form-input" required>
      </div>

      <div class="admin-form-group">
        <label for="sku" class="admin-form-label">SKU</label>
        <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}"
          class="admin-form-input" required>
      </div>
    </div>
  </div>

  <!-- Descrição -->
  <div class="admin-form-section">
    <h2 class="admin-form-section-title">
      <ion-icon name="document-text-outline"></ion-icon>
      Descrição
    </h2>

    <div class="admin-form-group">
      <label for="description" class="admin-form-label">Descrição Detalhada</label>
      <textarea name="description" id="description" class="admin-form-textarea" required>{{ old('description', $product->description ?? '') }}</textarea>
    </div>
  </div>

  <!-- Preço e Estoque -->
  <div class="admin-form-section">
    <h2 class="admin-form-section-title">
      <ion-icon name="cash-outline"></ion-icon>
      Preço e Estoque
    </h2>

    <div class="admin-form-grid">
      <div class="admin-form-group">
        <label for="price" class="admin-form-label">Preço (€)</label>
        <input type="number" step="0.01" name="price" id="price"
          value="{{ old('price', $product->price ?? '') }}" class="admin-form-input" required>
      </div>

      <div class="admin-form-group">
        <label for="stock" class="admin-form-label">Estoque Disponível</label>
        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock ?? '') }}"
          class="admin-form-input" required>
      </div>
    </div>
  </div>

  <!-- Categorização -->
  <div class="admin-form-section">
    <h2 class="admin-form-section-title">
      <ion-icon name="pricetags-outline"></ion-icon>
      Categorização
    </h2>

    <div class="admin-form-grid">
      <div class="admin-form-group">
        <label for="brand_id" class="admin-form-label">Marca</label>
        <select name="brand_id" id="brand_id" class="admin-form-select" required>
          <option value="">Selecione uma marca...</option>
          @foreach ($brands as $brand)
            <option value="{{ $brand->id }}"
              {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
              {{ $brand->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="admin-form-group">
        <label for="categories" class="admin-form-label">Categorias</label>
        <select name="categories[]" id="categories" class="admin-form-select" multiple required>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}"
              {{ collect(old('categories', isset($product) ? $product->categories->pluck('id') : []))->contains($category->id) ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>
  </div>

  <!-- Variações -->
  <div class="admin-form-section">
    <h2 class="admin-form-section-title">
      <ion-icon name="options-outline"></ion-icon>
      Variações do Produto
    </h2>

    <div class="admin-form-group">
      <label class="admin-form-label">Cores Disponíveis</label>
      <div class="admin-form-checkbox-group">
        @foreach ($colors as $color)
          <label class="admin-form-checkbox-item">
            <input type="checkbox" name="colors[]" value="{{ $color->id }}"
              {{ collect(old('colors', isset($product) ? $product->colors->pluck('id') : []))->contains($color->id) ? 'checked' : '' }}>
            <div class="admin-form-checkbox-content">
              <span class="admin-color-preview" style="background: {{ $color->hex_code }}"></span>
              <span>{{ $color->name }}</span>
            </div>
          </label>
        @endforeach
      </div>
    </div>

    <div class="admin-form-group">
      <label class="admin-form-label">Tamanhos Oferecidos</label>
      <div class="admin-form-checkbox-group">
        @foreach ($sizes as $size)
          <label class="admin-form-checkbox-item">
            <input type="checkbox" name="sizes[]" value="{{ $size->id }}"
              {{ collect(old('sizes', isset($product) ? $product->sizes->pluck('id') : []))->contains($size->id) ? 'checked' : '' }}>
            <div class="admin-form-checkbox-content">
              <span>{{ $size->name }}</span>
            </div>
          </label>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Imagem -->
  <div class="admin-form-section">
    <h2 class="admin-form-section-title">
      <ion-icon name="image-outline"></ion-icon>
      Imagem do Produto
    </h2>

    <div class="admin-form-group">
      <div class="admin-image-upload">
        @if (isset($product) && $product->image)
          <img id="image-preview" src="{{ asset('products/' . $product->image) }}" class="admin-image-preview show">
        @else
          <img id="image-preview" src="" class="admin-image-preview">
        @endif
        <label class="admin-upload-button">
          <ion-icon name="camera-outline"></ion-icon>
          {{ isset($product) ? 'Alterar Imagem' : 'Selecionar Imagem' }}
          <input type="file" name="image" id="image-upload" onchange="previewImage(event)"
            {{ isset($product) ? '' : 'required' }}>
        </label>
      </div>
      <p class="admin-upload-help">Formatos aceitos: jpg, jpeg, png. Máximo: 2MB.</p>
    </div>
  </div>

  <!-- Status -->
  <div class="admin-form-section">
    <h2 class="admin-form-section-title">
      <ion-icon name="settings-outline"></ion-icon>
      Status do Produto
    </h2>

    <div class="admin-form-group">
      <label for="active" class="admin-form-label">Status</label>
      <select name="active" id="active" class="admin-form-select">
        <option value="1" {{ old('active', $product->active ?? 1) == 1 ? 'selected' : '' }}>Ativo</option>
        <option value="0" {{ old('active', $product->active ?? 1) == 0 ? 'selected' : '' }}>Inativo</option>
      </select>
    </div>
  </div>

  <!-- Ações -->
  <div class="admin-form-actions">
    <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn-secondary">
      <ion-icon name="close-outline"></ion-icon>
      Cancelar
    </a>
    <button type="submit" class="admin-btn admin-btn-primary">
      <ion-icon name="save-outline"></ion-icon>
      {{ isset($product) ? 'Salvar Alterações' : 'Salvar Produto' }}
    </button>
  </div>
</form>

<script>
  // Preview de imagem
  function previewImage(event) {
    const preview = document.getElementById('image-preview');
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.classList.add('show');
      }
      reader.readAsDataURL(file);
    } else {
      preview.src = '';
      preview.classList.remove('show');
    }
  }

  // Slug automático
  function slugify(str) {
    return str
      .toLowerCase()
      .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)+/g, '');
  }

  const nameInput = document.getElementById('name');
  const slugInput = document.getElementById('slug');
  let slugTouched = false;

  if (nameInput && slugInput) {
    nameInput.addEventListener('input', function() {
      if (!slugTouched) {
        slugInput.value = slugify(this.value);
      }
    });

    slugInput.addEventListener('input', function() {
      slugTouched = this.value.length > 0;
    });
  }
</script>
