@extends('layouts.app')
@section('title', 'Editar Cor')

@section('content')
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-10 px-2">
    <!-- Breadcrumb e botão voltar -->
    <div class="w-full max-w-2xl flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-8">
      <nav class="flex items-center gap-2 text-base md:text-lg text-gray-500">
        <a href="{{ route('admin.dashboard') }}" class="hover:underline flex items-center gap-1">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0 0h4m-4 0a2 2 0 01-2-2v-4a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4z" />
          </svg>
          Dashboard
        </a>
        <span>/</span>
        <a href="{{ route('admin.colors.index') }}" class="hover:underline">Cores</a>
        <span>/</span>
        <span class="text-gray-700 font-semibold">Editar</span>
      </nav>
      <a href="{{ route('admin.colors.index') }}"
        class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition text-base font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Voltar
      </a>
    </div>

    <div class="w-full max-w-2xl bg-white rounded-2xl shadow p-8">
      <h1 class="text-2xl font-bold mb-8 text-center">Editar Cor</h1>
      <form method="POST" action="{{ route('admin.colors.update', $color) }}" class="flex flex-col space-y-6">
        @csrf
        @method('PUT')

        <div>
          <label class="block font-semibold mb-2 text-lg" for="name">Nome</label>
          <input type="text" name="name" id="name" value="{{ old('name', $color->name) }}"
            class="border rounded-xl px-5 py-3 w-full text-lg focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror"
            required>
          @error('name')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <label class="block font-semibold mb-2 text-lg" for="hex_code">Código Hexadecimal</label>
          <input type="text" name="hex_code" id="hex_code" value="{{ old('hex_code', $color->hex_code) }}"
            class="border rounded-xl px-5 py-3 w-full text-lg focus:outline-none focus:border-blue-500 @error('hex_code') border-red-500 @enderror"
            placeholder="#000000" required>
          @error('hex_code')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <label class="block font-semibold mb-2 text-lg" for="active">Status</label>
          <select name="active" id="active"
            class="border rounded-xl px-5 py-3 w-full text-lg focus:outline-none focus:border-blue-500">
            <option value="1" {{ old('active', $color->active) == 1 ? 'selected' : '' }}>Ativa</option>
            <option value="0" {{ old('active', $color->active) == 0 ? 'selected' : '' }}>Inativa</option>
          </select>
        </div>

        <div class="flex gap-4 justify-center mt-4">
          <button type="submit"
            class="bg-blue-600 text-white px-8 py-3 rounded-xl text-lg font-semibold hover:bg-blue-700 transition">
            Salvar Alterações
          </button>
          <a href="{{ route('admin.colors.index') }}"
            class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl text-lg font-semibold hover:bg-gray-300 transition">
            Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
@endsection
