@extends('layouts.app')
@section('title', 'Editar Tamanho')

@section('content')
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-10 px-2">
    <div class="w-full max-w-2xl flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-8">
      <nav class="flex items-center gap-2 text-base md:text-lg text-gray-500">
        <a href="{{ route('admin.dashboard') }}" class="hover:underline flex items-center gap-1">
          <!-- ícone dashboard -->
        </a>
        <span>/</span>
        <a href="{{ route('admin.sizes.index') }}" class="hover:underline">Tamanhos</a>
        <span>/</span>
        <span class="text-gray-700 font-semibold">Editar</span>
      </nav>
      <a href="{{ route('admin.sizes.index') }}"
        class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition text-base font-medium">
        <!-- ícone voltar -->
        Voltar
      </a>
    </div>

    <div class="w-full max-w-2xl bg-white rounded-2xl shadow p-8">
      <h1 class="text-2xl font-bold mb-8 text-center">Editar Tamanho</h1>
      <form method="POST" action="{{ route('admin.sizes.update', $size) }}" class="flex flex-col space-y-6">
        @csrf
        @method('PUT')

        <div>
          <label class="block font-semibold mb-2 text-lg" for="name">Nome</label>
          <input type="text" name="name" id="name" value="{{ old('name', $size->name) }}"
            class="border rounded-xl px-5 py-3 w-full text-lg focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror"
            required>
          <small class="text-gray-500">Ex: P, M, G, GG, 38, 40, Único...</small>
          @error('name')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <label class="block font-semibold mb-2 text-lg" for="active">Status</label>
          <select name="active" id="active"
            class="border rounded-xl px-5 py-3 w-full text-lg focus:outline-none focus:border-blue-500">
            <option value="1" {{ old('active', $size->active) == 1 ? 'selected' : '' }}>Ativo</option>
            <option value="0" {{ old('active', $size->active) == 0 ? 'selected' : '' }}>Inativo</option>
          </select>
        </div>

        <div class="flex gap-4 justify-center mt-4">
          <button type="submit"
            class="bg-blue-600 text-white px-8 py-3 rounded-xl text-lg font-semibold hover:bg-blue-700 transition">
            Salvar Alterações
          </button>
          <a href="{{ route('admin.sizes.index') }}"
            class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl text-lg font-semibold hover:bg-gray-300 transition">
            Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
@endsection
