@extends('layouts.app')
@section('title', 'Categorias')

@section('content')
  <div class="max-w-7xl mx-auto py-8 px-2 md:px-0">
    <div class="bg-white shadow rounded-lg p-6 md:p-8">
      <!-- Breadcrumb e botão voltar -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-6">
        <nav class="flex items-center gap-2 text-base md:text-lg text-gray-500">
          <a href="{{ route('admin.dashboard') }}" class="hover:underline flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0 0h4m-4 0a2 2 0 01-2-2v-4a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4z" />
            </svg>
            Dashboard
          </a>
          <span>/</span>
          <span class="text-gray-700 font-semibold">Categorias</span>
        </nav>
        <a href="{{ route('admin.dashboard') }}"
          class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition text-base font-medium">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Voltar
        </a>
      </div>

      <h1 class="text-3xl font-bold mb-2 text-center md:text-left">Gerenciar Categorias</h1>
      <p class="text-gray-600 mb-6 text-center md:text-left">Visão geral e gestão de todas as categorias</p>

      <!-- Filtros -->
      <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4 items-end mb-6">
        <div>
          <label class="block text-xs text-gray-500 mb-1">Buscar:</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome ou slug"
            class="border rounded-lg px-3 py-2 text-base w-full" />
        </div>
        <div>
          <label class="block text-xs text-gray-500 mb-1 invisible md:visible">.</label>
          <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition w-full">Pesquisar</button>
        </div>
        <div class="flex gap-2">
          <label class="block text-xs text-gray-500 mb-1 invisible md:visible">.</label>
          <a href="{{ route('admin.categories.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2 w-full justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nova Categoria
          </a>
        </div>
      </form>

      <!-- Tabela responsiva -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-lg">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left font-semibold">Nome</th>
              <th class="px-4 py-3 text-left font-semibold">Slug</th>
              <th class="px-4 py-3 text-center font-semibold">Ativa</th>
              <th class="px-4 py-3 text-center font-semibold">Ações</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            @forelse($categories as $category)
              <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-semibold">{{ $category->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $category->slug }}</td>
                <td class="px-4 py-3 text-center">
                  <span
                    class="px-3 py-2 rounded-full text-base font-bold
                                    {{ $category->active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $category->active ? 'Sim' : 'Não' }}
                  </span>
                </td>
                <td class="px-4 py-3 text-center">
                  <div class="flex gap-4 justify-center items-center">
                    <!-- Visualizar produtos da categoria -->
                    <a href="{{ route('admin.categories.show', $category) }}"
                      class="text-indigo-600 hover:text-indigo-900" title="Ver produtos">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </a>
                    <!-- Editar -->
                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-900"
                      title="Editar">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                    </a>
                    <!-- Excluir -->
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                      onsubmit="return confirm('Remover esta categoria?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="text-red-600 hover:text-red-900" title="Excluir">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m5 0H6" />
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="py-8 text-center text-gray-500 text-lg">Nenhuma categoria encontrada.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-6 flex justify-center">
        {{ $categories->links() }}
      </div>
    </div>
  </div>
@endsection
