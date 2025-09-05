@if ($errors->any())
  <div class="admin-form-section">
    <div class="admin-error-message">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  </div>
@endif

<!-- Formulário -->
<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="admin-form">
  @csrf
  @if (isset($product))
    @method('PUT')
  @endif

  <!-- Sistema de Abas -->
  <div class="admin-tabs">
    <!-- Navegação das Abas -->
    <div class="admin-tabs-nav">
      <button type="button" class="admin-tab-btn active" data-tab="basic">
        <ion-icon name="information-circle-outline"></ion-icon>
        Informações Básicas
      </button>
      <button type="button" class="admin-tab-btn" data-tab="pricing">
        <ion-icon name="cash-outline"></ion-icon>
        Preço e Estoque
      </button>
      <button type="button" class="admin-tab-btn" data-tab="categorization">
        <ion-icon name="pricetags-outline"></ion-icon>
        Categorização
      </button>
      <button type="button" class="admin-tab-btn" data-tab="variations">
        <ion-icon name="options-outline"></ion-icon>
        Variações
      </button>
      <button type="button" class="admin-tab-btn" data-tab="physical">
        <ion-icon name="scale-outline"></ion-icon>
        Características
      </button>
      <button type="button" class="admin-tab-btn" data-tab="media">
        <ion-icon name="image-outline"></ion-icon>
        Mídia
      </button>
      <button type="button" class="admin-tab-btn" data-tab="settings">
        <ion-icon name="settings-outline"></ion-icon>
        Configurações
      </button>
    </div>

    <!-- Conteúdo das Abas -->
    <div class="admin-tabs-content">
      <!-- Aba: Informações Básicas -->
      <div class="admin-tab-panel active" id="basic">
        <div class="admin-form-grid">
          <div class="admin-form-group">
            <label for="name" class="admin-form-label">Nome do Produto *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}"
              class="admin-form-input" placeholder="Ex: Nike Air Max 90" required>
          </div>

          <div class="admin-form-group">
            <label for="sku" class="admin-form-label">SKU *</label>
            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}"
              class="admin-form-input" placeholder="Ex: NIKE-AM90-001" required>
          </div>

          <div class="admin-form-group">
            <label for="slug" class="admin-form-label">URL Amigável *</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug ?? '') }}"
              class="admin-form-input" placeholder="Ex: nike-air-max-90" required>
            <small class="admin-form-help">Será gerado automaticamente baseado no nome</small>
          </div>
        </div>

        <div class="admin-form-group">
          <label for="description" class="admin-form-label">Descrição Detalhada *</label>
          <textarea name="description" id="description" class="admin-form-textarea"
            placeholder="Descreva as características, benefícios e detalhes do produto..." required>{{ old('description', $product->description ?? '') }}</textarea>
        </div>
      </div>

      <!-- Aba: Preço e Estoque -->
      <div class="admin-tab-panel" id="pricing">
        <div class="admin-form-grid">
          <div class="admin-form-group">
            <label for="price" class="admin-form-label">Preço Normal (€) *</label>
            <input type="number" step="0.01" name="price" id="price"
              value="{{ old('price', $product->price ?? '') }}" class="admin-form-input" placeholder="0.00" required>
          </div>

          <div class="admin-form-group">
            <label for="stock" class="admin-form-label">Estoque Disponível *</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock ?? '') }}"
              class="admin-form-input" placeholder="0" required>
          </div>

          <div class="admin-form-group">
            <label for="sale_price" class="admin-form-label">Preço de Promoção (€)</label>
            <input type="number" step="0.01" name="sale_price" id="sale_price"
              value="{{ old('sale_price', $product->sale_price ?? '') }}" class="admin-form-input"
              placeholder="0.00">
          </div>

          <div class="admin-form-group">
            <label for="sale_ends_at" class="admin-form-label">Fim da Promoção</label>
            <input type="date" name="sale_ends_at" id="sale_ends_at"
              value="{{ old('sale_ends_at', isset($product) ? $product->sale_ends_at?->format('Y-m-d') : '') }}"
              class="admin-form-input">
          </div>
        </div>

        <div class="admin-form-grid">
          <div class="admin-form-group">
            <label for="installments" class="admin-form-label">Parcelas</label>
            <input type="number" name="installments" id="installments"
              value="{{ old('installments', $product->installments ?? 1) }}" class="admin-form-input"
              placeholder="1" min="1" max="12">
          </div>

          <div class="admin-form-group">
            <label for="installment_value" class="admin-form-label">Valor da Parcela (€)</label>
            <input type="number" step="0.01" name="installment_value" id="installment_value"
              value="{{ old('installment_value', $product->installment_value ?? '') }}" class="admin-form-input"
              placeholder="0.00">
          </div>
        </div>
      </div>

      <!-- Aba: Categorização -->
      <div class="admin-tab-panel" id="categorization">
        <div class="admin-form-grid">
          <div class="admin-form-group">
            <label for="brand_id" class="admin-form-label">Marca *</label>
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
            <label for="categories" class="admin-form-label">Categorias *</label>
            <select name="categories[]" id="categories" class="admin-form-select" multiple required>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                  {{ collect(old('categories', isset($product) ? $product->categories->pluck('id') : []))->contains($category->id) ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            <small class="admin-form-help">Pressione Ctrl (ou Cmd) para selecionar múltiplas categorias</small>
          </div>
        </div>
      </div>

      <!-- Aba: Variações -->
      <div class="admin-tab-panel" id="variations">
        <div class="admin-form-group">
          <label class="admin-form-label">Cores Disponíveis *</label>
          <div class="admin-form-checkbox-group">
            @foreach ($colors as $color)
              <label class="admin-form-checkbox-item">
                <input type="checkbox" name="colors[]" value="{{ $color->id }}"
                  {{ collect(old('colors', isset($product) ? $product->colors->pluck('id') : []))->contains($color->id) ? 'checked' : '' }}>
                <div class="admin-form-checkbox-content">
                  <span class="admin-color-preview" style="background: {{ $color->hex_code }}"></span>
                  <span>{{ $color->name }}</span>
                </div>
                <div class="admin-checkbox-indicator">
                  <ion-icon name="checkmark"></ion-icon>
                </div>
              </label>
            @endforeach
          </div>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label">Tamanhos Oferecidos *</label>
          <div class="admin-form-checkbox-group">
            @foreach ($sizes as $size)
              <label class="admin-form-checkbox-item size-item">
                <input type="checkbox" name="sizes[]" value="{{ $size->id }}"
                  {{ collect(old('sizes', isset($product) ? $product->sizes->pluck('id') : []))->contains($size->id) ? 'checked' : '' }}>
                <div class="admin-form-checkbox-content">
                  <span>{{ $size->name }}</span>
                </div>
                <div class="admin-checkbox-indicator">
                  <ion-icon name="checkmark"></ion-icon>
                </div>
              </label>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Aba: Características Físicas -->
      <div class="admin-tab-panel" id="physical">
        <div class="admin-form-grid">
          <div class="admin-form-group">
            <label for="weight" class="admin-form-label">Peso (kg)</label>
            <input type="number" step="0.001" name="weight" id="weight"
              value="{{ old('weight', $product->weight ?? '') }}" class="admin-form-input" placeholder="0.000">
          </div>

          <div class="admin-form-group">
            <label for="dimensions" class="admin-form-label">Dimensões</label>
            <input type="text" name="dimensions" id="dimensions"
              value="{{ old('dimensions', $product->dimensions ?? '') }}" class="admin-form-input"
              placeholder="Ex: 30x20x10cm">
          </div>
        </div>
      </div>

      <!-- Aba: Mídia -->
      <div class="admin-tab-panel" id="media">
        <div class="admin-form-group">
          <div class="admin-image-upload">
            @if (isset($product) && $product->image)
              <img id="image-preview" src="{{ asset('products/' . $product->image) }}"
                class="admin-image-preview show">
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

      <!-- Aba: Configurações -->
      <div class="admin-tab-panel" id="settings">
        <div class="admin-form-grid">
          <div class="admin-form-group">
            <label for="active" class="admin-form-label">Status</label>
            <select name="active" id="active" class="admin-form-select">
              <option value="1" {{ old('active', $product->active ?? 1) == 1 ? 'selected' : '' }}>Ativo
              </option>
              <option value="0" {{ old('active', $product->active ?? 1) == 0 ? 'selected' : '' }}>Inativo
              </option>
            </select>
          </div>

          <div class="admin-form-group">
            <label for="featured" class="admin-form-label">Produto em Destaque</label>
            <select name="featured" id="featured" class="admin-form-select">
              <option value="0" {{ old('featured', $product->featured ?? 0) == 0 ? 'selected' : '' }}>Não
              </option>
              <option value="1" {{ old('featured', $product->featured ?? 0) == 1 ? 'selected' : '' }}>Sim
              </option>
            </select>
          </div>

          <div class="admin-form-group">
            <label for="is_new" class="admin-form-label">Produto Novo</label>
            <select name="is_new" id="is_new" class="admin-form-select">
              <option value="0" {{ old('is_new', $product->is_new ?? 0) == 0 ? 'selected' : '' }}>Não</option>
              <option value="1" {{ old('is_new', $product->is_new ?? 0) == 1 ? 'selected' : '' }}>Sim</option>
            </select>
          </div>

          <div class="admin-form-group">
            <label for="is_sale" class="admin-form-label">Produto em Promoção</label>
            <select name="is_sale" id="is_sale" class="admin-form-select">
              <option value="0" {{ old('is_sale', $product->is_sale ?? 0) == 0 ? 'selected' : '' }}>Não
              </option>
              <option value="1" {{ old('is_sale', $product->is_sale ?? 0) == 1 ? 'selected' : '' }}>Sim
              </option>
            </select>
          </div>

          <div class="admin-form-group">
            <label for="free_shipping" class="admin-form-label">Frete Grátis</label>
            <select name="free_shipping" id="free_shipping" class="admin-form-select">
              <option value="0" {{ old('free_shipping', $product->free_shipping ?? 0) == 0 ? 'selected' : '' }}>
                Não</option>
              <option value="1" {{ old('free_shipping', $product->free_shipping ?? 0) == 1 ? 'selected' : '' }}>
                Sim</option>
            </select>
          </div>
        </div>
      </div>
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

  // SKU automático
  function generateSKU(name) {
    const base = name
      .toUpperCase()
      .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
      .replace(/[^A-Z0-9]+/g, '')
      .substring(0, 8);
    const random = Math.random().toString(36).substring(2, 6).toUpperCase();
    return base + '-' + random;
  }

  const nameInput = document.getElementById('name');
  const slugInput = document.getElementById('slug');
  const skuInput = document.getElementById('sku');
  let slugTouched = false;
  let skuTouched = false;

  if (nameInput && slugInput) {
    nameInput.addEventListener('input', function() {
      if (!slugTouched) {
        slugInput.value = slugify(this.value);
      }
      if (!skuTouched) {
        skuInput.value = generateSKU(this.value);
      }
    });

    slugInput.addEventListener('input', function() {
      slugTouched = this.value.length > 0;
    });

    skuInput.addEventListener('input', function() {
      skuTouched = this.value.length > 0;
    });
  }

  // Cálculo automático do valor da parcela
  const priceInput = document.getElementById('price');
  const installmentsInput = document.getElementById('installments');
  const installmentValueInput = document.getElementById('installment_value');

  function calculateInstallmentValue() {
    const price = parseFloat(priceInput.value) || 0;
    const installments = parseInt(installmentsInput.value) || 1;
    if (price > 0 && installments > 0) {
      installmentValueInput.value = (price / installments).toFixed(2);
    }
  }

  if (priceInput && installmentsInput && installmentValueInput) {
    priceInput.addEventListener('input', calculateInstallmentValue);
    installmentsInput.addEventListener('input', calculateInstallmentValue);
  }

  // Sistema de Abas
  document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.admin-tab-btn');
    const tabPanels = document.querySelectorAll('.admin-tab-panel');

    tabButtons.forEach(button => {
      button.addEventListener('click', function() {
        const targetTab = this.getAttribute('data-tab');

        // Remove active de todos os botões e painéis
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabPanels.forEach(panel => panel.classList.remove('active'));

        // Adiciona active ao botão clicado e painel correspondente
        this.classList.add('active');
        document.getElementById(targetTab).classList.add('active');
      });
    });
  });
</script>
