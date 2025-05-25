@extends('layouts.app')
@section('title', 'Categorias')
@section('content')
  <div class="max-w-3xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Categorias</h1>
      <a href="{{ route('admin.categories.create') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nova Categoria</a>
    </div>
    <table class="min-w-full bg-white rounded shadow">
      <thead>
        <tr>
          <th class="py-3 px-4 text-left">Nome</th>
          <th class="py-3 px-4 text-left">Slug</th>
          <th class="py-3 px-4 text-left">Ativa</th>
          <th class="py-3 px-4 text-center">Ações</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
          <tr>
            <td class="py-2 px-4">{{ $category->name }}</td>
            <td class="py-2 px-4">{{ $category->slug }}</td>
            <td class="py-2 px-4">{{ $category->active ? 'Sim' : 'Não' }}</td>
            <td class="py-2 px-4 text-center">
              <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:underline">Editar</a>
              <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                onsubmit="return confirm('Remover?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline ml-2">Excluir</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="py-6 text-center text-gray-500">Nenhuma categoria encontrada.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-4">{{ $categories->links() }}</div>
  </div>
@endsection
