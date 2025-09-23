@extends('layouts.app')

@section('title', 'Estatísticas de Usuários')

@section('content')
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <!-- Header da Página -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-header-content">
                        <div class="admin-header-info">
                            <div class="admin-breadcrumb">
                                <a href="{{ route('admin.users.index') }}">Usuários</a>
                                <ion-icon name="chevron-forward"></ion-icon>
                                <span>Estatísticas</span>
                            </div>
                            <h1 class="admin-card-title">
                                <ion-icon name="analytics-outline"></ion-icon>
                                Estatísticas de Usuários
                            </h1>
                            <p class="admin-card-subtitle">Análise detalhada dos usuários do sistema</p>
                        </div>
                        <div class="admin-header-actions">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <ion-icon name="arrow-back-outline"></ion-icon>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas Principais -->
            <div class="admin-stats-overview">
                <div class="admin-stat-card">
                    <div class="admin-stat-icon">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-value">{{ $stats['total_users'] }}</div>
                        <div class="admin-stat-label">Total de Usuários</div>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon">
                        <ion-icon name="person-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-value">{{ $stats['total_clients'] }}</div>
                        <div class="admin-stat-label">Clientes</div>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon">
                        <ion-icon name="shield-checkmark-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-value">{{ $stats['total_admins'] }}</div>
                        <div class="admin-stat-label">Administradores</div>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-value">{{ $stats['verified_users'] }}</div>
                        <div class="admin-stat-label">Verificados</div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas Detalhadas -->
            <div class="admin-stats-detailed">
                <div class="admin-stat-card">
                    <div class="admin-stat-icon">
                        <ion-icon name="close-circle-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-value">{{ $stats['unverified_users'] }}</div>
                        <div class="admin-stat-label">Não Verificados</div>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon">
                        <ion-icon name="calendar-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-value">{{ $stats['new_users_this_month'] }}</div>
                        <div class="admin-stat-label">Este Mês</div>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon">
                        <ion-icon name="calendar-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-value">{{ $stats['new_users_last_month'] }}</div>
                        <div class="admin-stat-label">Mês Passado</div>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon">
                        <ion-icon name="trending-up-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-content">
                        <div class="admin-stat-value">
                            @if($stats['new_users_last_month'] > 0)
                                {{ number_format((($stats['new_users_this_month'] - $stats['new_users_last_month']) / $stats['new_users_last_month']) * 100, 1) }}%
                            @else
                                {{ $stats['new_users_this_month'] > 0 ? '100%' : '0%' }}
                            @endif
                        </div>
                        <div class="admin-stat-label">Crescimento</div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Usuários por Mês -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <ion-icon name="bar-chart-outline"></ion-icon>
                        Crescimento de Usuários (Últimos 12 Meses)
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="admin-chart-container">
                        <canvas id="usersChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Análise de Distribuição -->
            <div class="admin-analysis-grid">
                <!-- Distribuição por Função -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">
                            <ion-icon name="pie-chart-outline"></ion-icon>
                            Distribuição por Função
                        </h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-distribution">
                            <div class="admin-distribution-item">
                                <div class="admin-distribution-label">
                                    <span class="admin-distribution-color" style="background-color: #3b82f6;"></span>
                                    Clientes
                                </div>
                                <div class="admin-distribution-value">
                                    {{ $stats['total_clients'] }} 
                                    ({{ $stats['total_users'] > 0 ? number_format(($stats['total_clients'] / $stats['total_users']) * 100, 1) : 0 }}%)
                                </div>
                            </div>
                            <div class="admin-distribution-item">
                                <div class="admin-distribution-label">
                                    <span class="admin-distribution-color" style="background-color: #10b981;"></span>
                                    Administradores
                                </div>
                                <div class="admin-distribution-value">
                                    {{ $stats['total_admins'] }} 
                                    ({{ $stats['total_users'] > 0 ? number_format(($stats['total_admins'] / $stats['total_users']) * 100, 1) : 0 }}%)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status de Verificação -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">
                            <ion-icon name="shield-checkmark-outline"></ion-icon>
                            Status de Verificação
                        </h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-distribution">
                            <div class="admin-distribution-item">
                                <div class="admin-distribution-label">
                                    <span class="admin-distribution-color" style="background-color: #10b981;"></span>
                                    Verificados
                                </div>
                                <div class="admin-distribution-value">
                                    {{ $stats['verified_users'] }} 
                                    ({{ $stats['total_users'] > 0 ? number_format(($stats['verified_users'] / $stats['total_users']) * 100, 1) : 0 }}%)
                                </div>
                            </div>
                            <div class="admin-distribution-item">
                                <div class="admin-distribution-label">
                                    <span class="admin-distribution-color" style="background-color: #f59e0b;"></span>
                                    Não Verificados
                                </div>
                                <div class="admin-distribution-value">
                                    {{ $stats['unverified_users'] }} 
                                    ({{ $stats['total_users'] > 0 ? number_format(($stats['unverified_users'] / $stats['total_users']) * 100, 1) : 0 }}%)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumo Executivo -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <ion-icon name="document-text-outline"></ion-icon>
                        Resumo Executivo
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="admin-executive-summary">
                        <div class="admin-summary-item">
                            <h4>Total de Usuários</h4>
                            <p>O sistema possui <strong>{{ $stats['total_users'] }}</strong> usuários cadastrados, sendo <strong>{{ $stats['total_clients'] }}</strong> clientes e <strong>{{ $stats['total_admins'] }}</strong> administradores.</p>
                        </div>
                        <div class="admin-summary-item">
                            <h4>Status de Verificação</h4>
                            <p><strong>{{ $stats['verified_users'] }}</strong> usuários têm contas verificadas ({{ $stats['total_users'] > 0 ? number_format(($stats['verified_users'] / $stats['total_users']) * 100, 1) : 0 }}%), enquanto <strong>{{ $stats['unverified_users'] }}</strong> ainda não verificaram suas contas.</p>
                        </div>
                        <div class="admin-summary-item">
                            <h4>Crescimento</h4>
                            <p>Este mês foram cadastrados <strong>{{ $stats['new_users_this_month'] }}</strong> novos usuários, 
                            @if($stats['new_users_last_month'] > 0)
                                representando um crescimento de <strong>{{ number_format((($stats['new_users_this_month'] - $stats['new_users_last_month']) / $stats['new_users_last_month']) * 100, 1) }}%</strong> em relação ao mês anterior.
                            @else
                                sendo o primeiro mês com cadastros.
                            @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('usersChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Novos Usuários',
                data: {!! json_encode($chartData) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endsection
