@extends('layouts.app')

@section('title', 'Meus Endereços')

@push('styles')
  @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
  <div class="dashboard-page">
    <div class="dashboard-container">
      <!-- Header da Página -->
      <div class="dashboard-header">
        <h1 class="dashboard-title">
          <ion-icon name="location-outline"></ion-icon>
          Meus Endereços
        </h1>
        <p class="dashboard-subtitle">Gerencie seus endereços de entrega salvos</p>
      </div>

      <!-- Lista de Endereços -->
      <div class="dashboard-sections">
        <div class="dashboard-section">
          <div class="section-header">
            <h2 class="section-title">
              <ion-icon name="home-outline"></ion-icon>
              Endereços Cadastrados
            </h2>
            <a href="{{ route('enderecos.create') }}" class="section-action">
              <ion-icon name="add-outline"></ion-icon>
              Adicionar Novo
            </a>
          </div>
          <div class="section-content">
            @if (session('success'))
              <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
              </div>
            @endif

            @if ($addresses->count() > 0)
              <div class="items-list">
                @foreach ($addresses as $address)
                  <div class="item-card">
                    <div class="item-image">
                      <ion-icon name="home-outline"></ion-icon>
                    </div>
                    <div class="item-details">
                      <h3 class="item-title">
                        {{ $address->name }}
                        @if ($address->is_default)
                          <span class="item-status delivered">Padrão</span>
                        @endif
                      </h3>
                      <p class="item-meta">
                        {{ $address->address_line1 }}, {{ $address->address_line2 ? $address->address_line2 . ', ' : '' }}
                        {{ $address->city }}/{{ $address->state }} •
                        CEP: {{ $address->zipcode }} •
                        {{ $address->country }}
                      </p>
                      @if ($address->phone)
                        <p class="item-meta">Telefone: {{ $address->phone }}</p>
                      @endif
                    </div>
                    <div class="flex gap-2">
                      <a href="{{ route('enderecos.edit', $address) }}" class="item-action">
                        Editar
                      </a>
                      <form action="{{ route('enderecos.destroy', $address) }}" method="POST" class="inline"
                        onsubmit="return confirm('Tem certeza que deseja excluir este endereço?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="item-action" style="background: var(--dashboard-error);">
                          Excluir
                        </button>
                      </form>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="empty-state">
                <ion-icon name="location-outline"></ion-icon>
                <h3>Nenhum endereço salvo</h3>
                <p>Adicione um endereço para facilitar suas próximas compras</p>
                <a href="{{ route('enderecos.create') }}" class="section-action">
                  <ion-icon name="add-outline"></ion-icon>
                  Adicionar Endereço
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
