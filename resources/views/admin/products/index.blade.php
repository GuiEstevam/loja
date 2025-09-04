@extends('layouts.app')

@section('title', 'Produtos')

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
            <span>Produtos</span>
          </nav>

          <h1 class="admin-products-title">Gerenciar Produtos</h1>
          <p class="admin-products-subtitle">Visão geral e gestão de todos os produtos</p>
        </div>

        <!-- Filtros -->
        <div class="admin-products-form" style="padding-bottom: 0;">
          <form method="GET" class="admin-filters">
            <div>
              <label class="admin-form-label">Buscar Produto</label>
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome, SKU ou categoria..."
                class="admin-search-input">
            </div>

            <div>
              <label class="admin-form-label">Itens por página</label>
              <select name="per_page" class="admin-search-input">
                @foreach ($perPageOptions as $option)
                  <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                    {{ $option }} itens
                  </option>
                @endforeach
              </select>
            </div>

            <button type="submit" class="admin-btn admin-btn-primary">
              <ion-icon name="search-outline"></ion-icon>
              Pesquisar
            </button>

            <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-primary">
              <ion-icon name="add-outline"></ion-icon>
              Novo Produto
            </a>
          </form>
        </div>

        <!-- Tabela -->
        <div class="admin-table-container">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Imagem</th>
                <th>Produto</th>
                <th>Marca</th>
                <th>Categorias</th>
                <th>Cores</th>
                <th>Tamanhos</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody id="products-table-body">
              @include('admin.products.partials.products-table')
            </tbody>
          </table>
        </div>

        <!-- Paginação -->
        <div id="products-pagination">
          @include('admin.products.partials.pagination')
        </div>
      </div>
    </div>
  </div>

  <script>
    // Variáveis globais
    let searchTimeout;
    let isLoading = false;

    // Elementos DOM
    const searchInput = document.querySelector('input[name="search"]');
    const perPageSelect = document.querySelector('select[name="per_page"]');
    const tableBody = document.getElementById('products-table-body');
    const paginationContainer = document.getElementById('products-pagination');
    const searchForm = document.querySelector('.admin-filters');

    // Função para mostrar loading
    function showLoading() {
      if (isLoading) return;
      isLoading = true;

      tableBody.innerHTML = `
        <tr>
          <td colspan="10">
            <div class="admin-loading-state">
              <div class="admin-loading-spinner"></div>
              <p>Carregando produtos...</p>
            </div>
          </td>
        </tr>
      `;
    }

    // Função para esconder loading
    function hideLoading() {
      isLoading = false;
    }

    // Função para atualizar a tabela
    function updateTable(data) {
      tableBody.innerHTML = data.html;
      paginationContainer.innerHTML = data.pagination;

      // Atualizar URL sem recarregar a página
      const url = new URL(window.location);
      url.searchParams.set('search', searchInput.value);
      url.searchParams.set('per_page', perPageSelect.value);
      window.history.pushState({}, '', url);
    }

    // Função para fazer a pesquisa Ajax
    function performSearch() {
      if (isLoading) return;

      showLoading();

      const formData = new FormData(searchForm);

      fetch('{{ route('admin.products.search') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            search: formData.get('search'),
            per_page: formData.get('per_page'),
            filtro: formData.get('filtro')
          })
        })
        .then(response => response.json())
        .then(data => {
          updateTable(data);
          hideLoading();
        })
        .catch(error => {
          console.error('Erro na pesquisa:', error);
          hideLoading();
        });
    }

    // Event listeners
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
      });
    }

    if (perPageSelect) {
      perPageSelect.addEventListener('change', performSearch);
    }

    // Interceptar cliques na paginação para usar Ajax
    document.addEventListener('click', function(e) {
      if (e.target.closest('.pagination .page-link')) {
        e.preventDefault();

        const link = e.target.closest('.page-link');
        const url = new URL(link.href);

        // Atualizar parâmetros da pesquisa
        if (searchInput && searchInput.value) {
          url.searchParams.set('search', searchInput.value);
        }
        if (perPageSelect && perPageSelect.value) {
          url.searchParams.set('per_page', perPageSelect.value);
        }

        showLoading();

        // Usar a mesma função de pesquisa, mas com a URL da paginação
        const formData = new FormData();
        formData.append('search', searchInput ? searchInput.value : '');
        formData.append('per_page', perPageSelect ? perPageSelect.value : '5');
        formData.append('page', url.searchParams.get('page') || '1');

        fetch('{{ route('admin.products.search') }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json',
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              search: searchInput ? searchInput.value : '',
              per_page: perPageSelect ? perPageSelect.value : '5',
              page: url.searchParams.get('page') || '1'
            })
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Erro na requisição: ' + response.status);
            }
            return response.json();
          })
          .then(data => {
            updateTable(data);
            hideLoading();
          })
          .catch(error => {
            console.error('Erro na paginação:', error);
            hideLoading();
            // Em caso de erro, recarregar a página
            window.location.href = url.toString();
          });
      }
    });
  </script>
@endsection
