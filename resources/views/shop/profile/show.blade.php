@extends('layouts.app')

@section('title', 'Meu Perfil')

@push('styles')
  @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
  <div class="dashboard-page">
    <div class="dashboard-container">
      <!-- Breadcrumb -->
      <nav class="dashboard-breadcrumb">
        <div class="dashboard-breadcrumb-nav">
          <a href="{{ route('shop.dashboard') }}" class="dashboard-breadcrumb-item">
            <ion-icon name="home-outline"></ion-icon>
            Dashboard
          </a>
          <span class="dashboard-breadcrumb-separator">›</span>
          <span class="dashboard-breadcrumb-item active">
            <ion-icon name="person-circle-outline"></ion-icon>
            Meu Perfil
          </span>
        </div>
        <a href="{{ route('shop.dashboard') }}" class="dashboard-breadcrumb-back">
          <ion-icon name="arrow-back-outline"></ion-icon>
          Voltar
        </a>
      </nav>

      <!-- Header da Página -->
      <div class="dashboard-header">
        <h1 class="dashboard-title">
          <ion-icon name="person-circle-outline"></ion-icon>
          Meu Perfil
        </h1>
        <p class="dashboard-subtitle">Gerencie suas informações pessoais e configurações de conta</p>
        <p class="dashboard-welcome">Olá, {{ $user->name }}! 👋</p>
      </div>

      <!-- Navegação Rápida -->
      <div class="dashboard-nav-cards">
        <a href="{{ route('shop.dashboard') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="grid-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Dashboard</h3>
          <p class="nav-card-description">Visão geral</p>
        </a>

        <a href="{{ route('shop.orders.index') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="bag-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Meus Pedidos</h3>
          <p class="nav-card-description">Histórico</p>
        </a>

        <a href="{{ route('shop.favorites.index') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="heart-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Favoritos</h3>
          <p class="nav-card-description">Produtos salvos</p>
        </a>

        <a href="{{ route('enderecos.index') }}" class="nav-card">
          <div class="nav-card-icon">
            <ion-icon name="location-outline"></ion-icon>
          </div>
          <h3 class="nav-card-title">Endereços</h3>
          <p class="nav-card-description">Gerenciar</p>
        </a>
      </div>

      <!-- Sistema de Abas -->
      <div class="dashboard-tabs-container">
        <div class="dashboard-tabs-nav">
          <button class="dashboard-tab-btn active" data-tab="overview">
            <ion-icon name="grid-outline"></ion-icon>
            Visão Geral
          </button>
          <button class="dashboard-tab-btn" data-tab="personal">
            <ion-icon name="person-outline"></ion-icon>
            Informações Pessoais
          </button>
          <button class="dashboard-tab-btn" data-tab="security">
            <ion-icon name="lock-closed-outline"></ion-icon>
            Segurança
          </button>
          <button class="dashboard-tab-btn" data-tab="activity">
            <ion-icon name="time-outline"></ion-icon>
            Atividades
          </button>
          <button class="dashboard-tab-btn" data-tab="settings">
            <ion-icon name="settings-outline"></ion-icon>
            Configurações
          </button>
        </div>

        <!-- Conteúdo das Abas -->
        <div class="dashboard-tab-content">
          <!-- Aba: Visão Geral -->
          <div class="dashboard-tab-pane active" id="overview">
            <!-- Estatísticas do Perfil -->
            <div class="dashboard-stats-grid">
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="bag-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['totalPedidos'] }}</div>
                  <div class="dashboard-stat-label">Total de Pedidos</div>
                </div>
              </div>
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="card-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">€{{ number_format($stats['totalGasto'], 2, ',', '.') }}</div>
                  <div class="dashboard-stat-label">Total Gasto</div>
                </div>
              </div>
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="star-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['totalAvaliacoes'] }}</div>
                  <div class="dashboard-stat-label">Avaliações</div>
                </div>
              </div>
              <div class="dashboard-stat-card">
                <div class="dashboard-stat-icon">
                  <ion-icon name="heart-outline"></ion-icon>
                </div>
                <div class="dashboard-stat-content">
                  <div class="dashboard-stat-value">{{ $stats['totalFavoritos'] }}</div>
                  <div class="dashboard-stat-label">Favoritos</div>
                </div>
              </div>
            </div>

            <!-- Informações da Conta -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="information-circle-outline"></ion-icon>
                  Informações da Conta
                </h3>
              </div>
              <div class="dashboard-section-content">
                <div class="profile-info-grid">
                  <div class="profile-info-item">
                    <div class="profile-info-label">Membro desde</div>
                    <div class="profile-info-value">{{ $stats['membroDesde'] }}</div>
                  </div>
                  <div class="profile-info-item">
                    <div class="profile-info-label">Último login</div>
                    <div class="profile-info-value">{{ $stats['ultimoLogin'] }}</div>
                  </div>
                  <div class="profile-info-item">
                    <div class="profile-info-label">Email verificado</div>
                    <div class="profile-info-value">
                      @if($user->email_verified_at)
                        <span class="status-badge approved">
                          <ion-icon name="checkmark-circle"></ion-icon>
                          Verificado
                        </span>
                      @else
                        <span class="status-badge pending">
                          <ion-icon name="time-outline"></ion-icon>
                          Pendente
                        </span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Aba: Informações Pessoais -->
          <div class="dashboard-tab-pane" id="personal">
            <!-- Foto do Perfil -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="camera-outline"></ion-icon>
                  Foto do Perfil
                </h3>
              </div>
              <div class="dashboard-section-content">
                <div class="profile-photo-section">
                  <div class="profile-photo-container">
                    @if($user->profile_photo_path)
                      <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="profile-photo">
                    @else
                      <div class="profile-photo-placeholder">
                        <ion-icon name="person-outline"></ion-icon>
                      </div>
                    @endif
                  </div>
                  <div class="profile-photo-actions">
                    <form action="{{ route('shop.profile.update-photo') }}" method="POST" enctype="multipart/form-data" class="profile-photo-form">
                      @csrf
                      <input type="file" name="photo" id="photo-input" accept="image/*" style="display: none;" onchange="this.form.submit()">
                      <button type="button" class="dashboard-btn" onclick="document.getElementById('photo-input').click()">
                        <ion-icon name="camera-outline"></ion-icon>
                        Alterar Foto
                      </button>
                    </form>
                    @if($user->profile_photo_path)
                      <form action="{{ route('shop.profile.delete-photo') }}" method="POST" class="profile-photo-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dashboard-btn" style="background: var(--dashboard-error);" onclick="return confirm('Tem certeza que deseja remover a foto?')">
                          <ion-icon name="trash-outline"></ion-icon>
                          Remover Foto
                        </button>
                      </form>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            <!-- Informações Pessoais -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="person-outline"></ion-icon>
                  Informações Pessoais
                </h3>
              </div>
              <div class="dashboard-section-content">
                <form action="{{ route('shop.profile.update') }}" method="POST" class="profile-form">
                  @csrf
                  @method('PUT')
                  
                  <div class="form-group">
                    <label for="name" class="form-label">Nome Completo *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                    @error('name')
                      <span class="form-error">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                    @error('email')
                      <span class="form-error">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="phone" class="form-label">Telefone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
                    @error('phone')
                      <span class="form-error">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-actions">
                    <button type="submit" class="dashboard-btn">
                      <ion-icon name="save-outline"></ion-icon>
                      Salvar Alterações
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Aba: Segurança -->
          <div class="dashboard-tab-pane" id="security">
            <!-- Alterar Senha -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="lock-closed-outline"></ion-icon>
                  Alterar Senha
                </h3>
              </div>
              <div class="dashboard-section-content">
                <form action="{{ route('shop.profile.update-password') }}" method="POST" class="profile-form">
                  @csrf
                  @method('PUT')
                  
                  <div class="form-group">
                    <label for="current_password" class="form-label">Senha Atual *</label>
                    <input type="password" id="current_password" name="current_password" class="form-input" required>
                    @error('current_password')
                      <span class="form-error">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="password" class="form-label">Nova Senha *</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                    @error('password')
                      <span class="form-error">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmar Nova Senha *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                  </div>

                  <div class="form-actions">
                    <button type="submit" class="dashboard-btn">
                      <ion-icon name="key-outline"></ion-icon>
                      Alterar Senha
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Sessões Ativas -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="desktop-outline"></ion-icon>
                  Sessões Ativas
                </h3>
              </div>
              <div class="dashboard-section-content">
                <p class="section-description">
                  Encerre todas as outras sessões em outros dispositivos. Você precisará fazer login novamente em todos os outros dispositivos.
                </p>
                <form action="{{ route('shop.profile.logout-other-sessions') }}" method="POST" class="profile-form">
                  @csrf
                  
                  <div class="form-group">
                    <label for="logout_password" class="form-label">Sua Senha *</label>
                    <input type="password" id="logout_password" name="password" class="form-input" required>
                  </div>

                  <div class="form-actions">
                    <button type="submit" class="dashboard-btn" style="background: var(--dashboard-warning);">
                      <ion-icon name="log-out-outline"></ion-icon>
                      Encerrar Outras Sessões
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Aba: Atividades -->
          <div class="dashboard-tab-pane" id="activity">
            <!-- Pedidos Recentes -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="bag-outline"></ion-icon>
                  Pedidos Recentes
                </h3>
                <a href="{{ route('shop.orders.index') }}" class="dashboard-section-action">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                  Ver Todos
                </a>
              </div>
              <div class="dashboard-section-content">
                @if ($ultimosPedidos->count() > 0)
                  <div class="dashboard-list">
                    @foreach ($ultimosPedidos as $order)
                      <div class="dashboard-list-item">
                        <div class="dashboard-list-icon">
                          <ion-icon name="receipt-outline"></ion-icon>
                        </div>
                        <div class="dashboard-list-content">
                          <h4 class="dashboard-list-title">Pedido #{{ $order->id }}</h4>
                          <p class="dashboard-list-meta">
                            {{ $order->created_at->format('d/m/Y H:i') }} •
                            {{ $order->items->count() }} item(s) •
                            €{{ number_format($order->total, 2, ',', '.') }}
                          </p>
                        </div>
                        <div class="dashboard-list-actions">
                          <span class="dashboard-status-badge {{ $order->status }}">
                            @switch($order->status)
                              @case('pending') Pendente @break
                              @case('processing') Processando @break
                              @case('shipped') Enviado @break
                              @case('delivered') Entregue @break
                              @default {{ ucfirst($order->status) }}
                            @endswitch
                          </span>
                          <a href="{{ route('shop.orders.show', $order) }}" class="dashboard-action-btn">
                            <ion-icon name="eye-outline"></ion-icon>
                            Ver
                          </a>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="dashboard-empty-state">
                    <ion-icon name="bag-outline"></ion-icon>
                    <h3>Nenhum pedido ainda</h3>
                    <p>Você ainda não fez nenhum pedido.</p>
                  </div>
                @endif
              </div>
            </div>

            <!-- Avaliações Recentes -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="star-outline"></ion-icon>
                  Avaliações Recentes
                </h3>
              </div>
              <div class="dashboard-section-content">
                @if ($ultimasAvaliacoes->count() > 0)
                  <div class="dashboard-list">
                    @foreach ($ultimasAvaliacoes as $review)
                      <div class="dashboard-list-item">
                        <div class="dashboard-list-image">
                          <img src="{{ asset('products/' . $review->product->image) }}" alt="{{ $review->product->name }}">
                        </div>
                        <div class="dashboard-list-content">
                          <h4 class="dashboard-list-title">{{ $review->product->name }}</h4>
                          <p class="dashboard-list-meta">
                            {{ $review->rating }} estrelas •
                            {{ $review->created_at->format('d/m/Y') }}
                          </p>
                        </div>
                        <div class="dashboard-list-actions">
                          <span class="dashboard-status-badge {{ $review->status }}">
                            @switch($review->status)
                              @case('pending') Pendente @break
                              @case('approved') Aprovada @break
                              @case('rejected') Rejeitada @break
                            @endswitch
                          </span>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="dashboard-empty-state">
                    <ion-icon name="star-outline"></ion-icon>
                    <h3>Nenhuma avaliação ainda</h3>
                    <p>Avalie seus produtos para ajudar outros clientes.</p>
                  </div>
                @endif
              </div>
            </div>
          </div>

          <!-- Aba: Configurações -->
          <div class="dashboard-tab-pane" id="settings">
            <!-- Excluir Conta -->
            <div class="dashboard-section">
              <div class="dashboard-section-header">
                <h3 class="dashboard-section-title">
                  <ion-icon name="warning-outline"></ion-icon>
                  Zona de Perigo
                </h3>
              </div>
              <div class="dashboard-section-content">
                <div class="danger-zone">
                  <div class="danger-zone-content">
                    <h4 class="danger-zone-title">Excluir Conta</h4>
                    <p class="danger-zone-description">
                      Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. 
                      Antes de excluir sua conta, baixe todos os dados ou informações que deseja reter.
                    </p>
                    <form action="{{ route('shop.profile.delete-account') }}" method="POST" class="profile-form">
                      @csrf
                      @method('DELETE')
                      
                      <div class="form-group">
                        <label for="delete_password" class="form-label">Sua Senha *</label>
                        <input type="password" id="delete_password" name="password" class="form-input" required>
                      </div>

                      <div class="form-actions">
                        <button type="submit" class="dashboard-btn" style="background: var(--dashboard-error);" onclick="return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')">
                          <ion-icon name="trash-outline"></ion-icon>
                          Excluir Conta
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  @vite(['resources/js/dashboard.js'])
@endpush
