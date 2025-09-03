@extends('layouts.app')

@section('title', 'Adicionar Endereço')

@push('styles')
  @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
  <div class="dashboard-page">
    <div class="dashboard-container">
      <!-- Header da Página -->
      <div class="dashboard-header">
        <h1 class="dashboard-title">
          <ion-icon name="add-circle-outline"></ion-icon>
          Adicionar Endereço
        </h1>
        <p class="dashboard-subtitle">Cadastre um novo endereço de entrega</p>
      </div>

      <!-- Formulário -->
      <div class="dashboard-sections">
        <div class="dashboard-section">
          <div class="section-header">
            <h2 class="section-title">
              <ion-icon name="location-outline"></ion-icon>
              Informações do Endereço
            </h2>
          </div>
          <div class="section-content">
            @if ($errors->any())
              <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc pl-5">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form action="{{ route('enderecos.store') }}" method="POST" class="space-y-6">
              @csrf

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nome do Endereço *
                  </label>
                  <input type="text" name="name" id="name"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    required value="{{ old('name') }}" placeholder="Ex: Casa, Trabalho">
                </div>

                <div>
                  <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Telefone *
                  </label>
                  <input type="tel" name="phone" id="phone"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    required value="{{ old('phone') }}" placeholder="(11) 99999-9999">
                </div>
              </div>

              <div>
                <label for="address_line1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Endereço *
                </label>
                <input type="text" name="address_line1" id="address_line1"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  required value="{{ old('address_line1') }}" placeholder="Rua, Avenida, etc.">
              </div>

              <div>
                <label for="address_line2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Complemento
                </label>
                <input type="text" name="address_line2" id="address_line2"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  value="{{ old('address_line2') }}" placeholder="Apartamento, bloco, etc.">
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Cidade *
                  </label>
                  <input type="text" name="city" id="city"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    required value="{{ old('city') }}" placeholder="Nome da cidade">
                </div>

                <div>
                  <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Estado *
                  </label>
                  <input type="text" name="state" id="state"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    required value="{{ old('state') }}" placeholder="Nome do estado">
                </div>

                <div>
                  <label for="zipcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    CEP *
                  </label>
                  <input type="text" name="zipcode" id="zipcode"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    required value="{{ old('zipcode') }}" placeholder="00000-000">
                </div>
              </div>

              <div>
                <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  País *
                </label>
                <select name="country" id="country"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  required>
                  <option value="">Selecione o país</option>
                  <option value="BR" {{ old('country') == 'BR' ? 'selected' : '' }}>Brasil</option>
                  <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>Estados Unidos</option>
                  <option value="PT" {{ old('country') == 'PT' ? 'selected' : '' }}>Portugal</option>
                  <option value="AR" {{ old('country') == 'AR' ? 'selected' : '' }}>Argentina</option>
                  <option value="other" {{ old('country') == 'other' ? 'selected' : '' }}>Outro</option>
                </select>
              </div>

              <div class="flex items-center gap-4 pt-6">
                <button type="submit" class="section-action">
                  <ion-icon name="save-outline"></ion-icon>
                  Salvar Endereço
                </button>
                <a href="{{ route('enderecos.index') }}"
                  class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                  Cancelar
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
