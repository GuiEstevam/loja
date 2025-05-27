@extends('layouts.app')
@section('title', 'Nova Cor')

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
        <span class="text-gray-700 font-semibold">Nova</span>
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
      <h1 class="text-2xl font-bold mb-8 text-center">Nova Cor</h1>
      <form method="POST" action="{{ route('admin.colors.store') }}" class="flex flex-col space-y-6">
        @csrf

        <!-- Seletor de Nome da Cor -->
        <div>
          <label class="block font-semibold mb-2 text-lg" for="name">Nome da Cor</label>
          <select name="name" id="name"
            class="border rounded-xl px-5 py-3 w-full text-lg focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror"
            required>
            <option value="">Selecione uma cor...</option>
            @foreach ([
          'Preto' => '#000000',
          'Branco' => '#ffffff',
          'Vermelho' => '#ff0000',
          'Verde' => '#00ff00',
          'Azul' => '#0000ff',
          'Amarelo' => '#ffff00',
          'Laranja' => '#ffa500',
          'Roxo' => '#800080',
          'Marrom' => '#a52a2a',
          'Cinza' => '#808080',
          'Bege' => '#f5f5dc',
          'Rosa' => '#ffc0cb',
          'Dourado' => '#ffd700',
          'Prata' => '#c0c0c0',
          'Bordô' => '#800000',
          'Turquesa' => '#40e0d0',
          'Vinho' => '#8b0000',
          'Lilás' => '#c8a2c8',
          'Fúcsia' => '#ff00ff',
          'Oliva' => '#808000',
          'Salmão' => '#fa8072',
          'Azul Royal' => '#4169e1',
          'Verde Limão' => '#32cd32',
          'Verde Água' => '#20b2aa',
          'Magenta' => '#ff00ff',
          'Caramelo' => '#af6e4d',
          'Pêssego' => '#ffe5b4',
          'Mostarda' => '#ffdb58',
          'Cobre' => '#b87333',
          'Champanhe' => '#f7e7ce',
      ] as $colorName => $hexCode)
              <option value="{{ $colorName }}" data-hex="{{ $hexCode }}">{{ $colorName }}</option>
            @endforeach
          </select>
          <small class="text-gray-500">Selecione uma cor para preencher o código automaticamente</small>
          @error('name')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Seletor Visual de Cor -->
        <div>
          <label class="block font-semibold mb-2 text-lg" for="color_picker">Seletor Visual</label>
          <input type="color" id="color_picker" class="w-full h-12 border rounded-lg cursor-pointer" value="#000000">
        </div>

        <!-- Campo Hexadecimal -->
        <div>
          <label class="block font-semibold mb-2 text-lg" for="hex_code">Código Hexadecimal</label>
          <div class="flex items-center gap-4">
            <input type="text" name="hex_code" id="hex_code" value="{{ old('hex_code', '#000000') }}"
              class="border rounded-xl px-5 py-3 w-full text-lg focus:outline-none focus:border-blue-500 @error('hex_code') border-red-500 @enderror"
              maxlength="7" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" placeholder="#000000" required readonly>
          </div>
          @error('hex_code')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Status -->
        <div>
          <label class="block font-semibold mb-2 text-lg" for="active">Status</label>
          <select name="active" id="active"
            class="border rounded-xl px-5 py-3 w-full text-lg focus:outline-none focus:border-blue-500">
            <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>Ativa</option>
            <option value="0" {{ old('active', '1') == '0' ? 'selected' : '' }}>Inativa</option>
          </select>
        </div>

        <!-- Botões -->
        <div class="flex gap-4 justify-center mt-4">
          <button type="submit"
            class="bg-blue-600 text-white px-8 py-3 rounded-xl text-lg font-semibold hover:bg-blue-700 transition">
            Criar Cor
          </button>
          <a href="{{ route('admin.colors.index') }}"
            class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl text-lg font-semibold hover:bg-gray-300 transition">
            Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Sincronização entre os seletores
    const nameSelect = document.getElementById('name');
    const colorPicker = document.getElementById('color_picker');
    const hexInput = document.getElementById('hex_code');
    const colorSwatch = document.getElementById('colorSwatch');

    // Atualiza ao selecionar nome
    nameSelect.addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const hex = selectedOption.getAttribute('data-hex') || '#000000';
      hexInput.value = hex;
      colorPicker.value = hex;
      colorSwatch.style.backgroundColor = hex;
    });

    // Atualiza ao usar o seletor visual
    colorPicker.addEventListener('input', function(e) {
      hexInput.value = e.target.value;
      colorSwatch.style.backgroundColor = e.target.value;

      // Atualiza o select para "Custom" se não corresponder
      const hex = e.target.value.toLowerCase();
      let found = false;

      Array.from(nameSelect.options).forEach(option => {
        if (option.getAttribute('data-hex')?.toLowerCase() === hex) {
          nameSelect.value = option.value;
          found = true;
        }
      });

      if (!found) nameSelect.value = '';
    });

    // Validação inicial
    window.addEventListener('DOMContentLoaded', () => {
      colorSwatch.style.backgroundColor = hexInput.value;
    });
  </script>
@endsection
