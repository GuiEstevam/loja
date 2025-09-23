@forelse($products as $product)
  <tr>
    <td>
      <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}" class="admin-product-image">
    </td>
    <td class="admin-product-cell">
      <div class="admin-product-name">{{ $product->name }}</div>
      <div class="admin-product-sku">SKU: {{ $product->sku }}</div>
    </td>
    <td>{{ $product->brand->name ?? '-' }}</td>
    <td>
      <div class="admin-badges-container">
        @foreach ($product->categories as $category)
          <span class="admin-badge admin-badge-category">{{ $category->name }}</span>
        @endforeach
      </div>
    </td>
    <td>
      <div class="admin-badges-container">
        @foreach ($product->colors as $color)
          <span class="admin-badge admin-badge-color">
            <span class="admin-color-indicator" style="background: {{ $color->hex_code ?? '#eee' }};"></span>
            {{ $color->name }}
          </span>
        @endforeach
      </div>
    </td>
    <td>
      <div class="admin-badges-container">
        @foreach ($product->sizes as $size)
          <span class="admin-badge admin-badge-size">{{ $size->name }}</span>
        @endforeach
      </div>
    </td>
    <td>€{{ number_format($product->price, 2, ',', '.') }}</td>
    <td>
      <span
        class="admin-badge admin-badge-stock {{ $product->stock > 0 ? 'admin-badge-stock-high' : 'admin-badge-stock-low' }}">
        {{ $product->stock }}
      </span>
    </td>
    <td>
      <span
        class="admin-badge admin-badge-status {{ $product->active ? 'admin-badge-active' : 'admin-badge-inactive' }}">
        {{ $product->active ? 'Ativo' : 'Inativo' }}
      </span>
    </td>
    <td>
      <div class="admin-actions">
        <a href="{{ route('shop.products.show', $product) }}" class="admin-action-btn admin-action-btn-view"
          title="Visualizar" target="_blank">
          <ion-icon name="eye-outline"></ion-icon>
        </a>
        <a href="{{ route('admin.products.edit', $product) }}" class="admin-action-btn admin-action-btn-edit"
          title="Editar">
          <ion-icon name="create-outline"></ion-icon>
        </a>
        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;"
          onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="admin-action-btn admin-action-btn-delete" title="Excluir">
            <ion-icon name="trash-outline"></ion-icon>
          </button>
        </form>
      </div>
    </td>
  </tr>
@empty
  <tr>
    <td colspan="10">
      <div class="admin-empty-state">
        <ion-icon name="cube-outline"></ion-icon>
        <p>Nenhum produto encontrado</p>
      </div>
    </td>
  </tr>
@endforelse
