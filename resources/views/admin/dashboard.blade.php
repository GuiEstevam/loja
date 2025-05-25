@extends('layouts.app')

@section('title', 'Painel Administrativo')

@section('content')
  <div class="max-w-6xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-8">Painel Administrativo</h1>

    <!-- Atalhos de gestão -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
      <a href="{{ route('admin.categories.index') }}"
        class="bg-gray-50 rounded-xl shadow p-6 flex flex-col items-center hover:bg-blue-50 transition group cursor-pointer">
        <ion-icon name="pricetags-outline" class="text-blue-600" size="large"></ion-icon>
        <div class="font-semibold mt-2">Categorias</div>
      </a>
      <a href="{{ route('admin.brands.index') }}"
        class="bg-gray-50 rounded-xl shadow p-6 flex flex-col items-center hover:bg-purple-50 transition group cursor-pointer">
        <ion-icon name="ribbon-outline" class="text-purple-600" size="large"></ion-icon>
        <div class="font-semibold mt-2">Marcas</div>
      </a>
      <a href="{{ route('admin.colors.index') }}"
        class="bg-gray-50 rounded-xl shadow p-6 flex flex-col items-center hover:bg-pink-50 transition group cursor-pointer">
        <ion-icon name="color-palette-outline" class="text-pink-600" size="large"></ion-icon>
        <div class="font-semibold mt-2">Cores</div>
      </a>
      <a href="{{ route('admin.sizes.index') }}"
        class="bg-gray-50 rounded-xl shadow p-6 flex flex-col items-center hover:bg-yellow-50 transition group cursor-pointer">
        <ion-icon name="resize-outline" class="text-yellow-600" size="large"></ion-icon>
        <div class="font-semibold mt-2">Tamanhos</div>
      </a>
    </div>

    <!-- Indicadores rápidos -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-10">
      <a href="{{ route('admin.orders.index') }}"
        class="bg-white rounded-xl shadow p-6 flex flex-col items-center hover:bg-green-50 transition group cursor-pointer">
        <ion-icon name="receipt-outline" class="text-green-600" size="large"></ion-icon>
        <div class="text-2xl font-bold mt-2">{{ $totalPedidos }}</div>
        <div class="text-gray-600 text-sm font-semibold mt-1">Pedidos ({{ $dias }} dias)</div>
      </a>
      <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
        <ion-icon name="cash-outline" class="text-blue-600" size="large"></ion-icon>
        <div class="text-2xl font-bold mt-2">R$ {{ number_format($totalReceita, 2, ',', '.') }}</div>
        <div class="text-gray-600 text-sm font-semibold mt-1">Receita ({{ $dias }} dias)</div>
      </div>
      <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
        <ion-icon name="pricetag-outline" class="text-orange-600" size="large"></ion-icon>
        <div class="text-2xl font-bold mt-2">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</div>
        <div class="text-gray-600 text-sm font-semibold mt-1">Ticket Médio</div>
      </div>
      <a href="{{ route('admin.products.index') }}"
        class="bg-white rounded-xl shadow p-6 flex flex-col items-center hover:bg-blue-50 transition group cursor-pointer">
        <ion-icon name="cube-outline" class="text-blue-600" size="large"></ion-icon>
        <div class="text-2xl font-bold mt-2">{{ $totalProdutos }}</div>
        <div class="text-gray-600 text-sm font-semibold mt-1">Produtos ativos</div>
      </a>
      <a href="{{ route('admin.products.index', ['filtro' => 'baixo_estoque']) }}"
        class="bg-white rounded-xl shadow p-6 flex flex-col items-center hover:bg-red-50 transition group cursor-pointer">
        <ion-icon name="warning-outline" class="text-red-600" size="large"></ion-icon>
        <div class="text-2xl font-bold mt-2">{{ $produtosBaixoEstoque }}</div>
        <div class="text-gray-600 text-sm font-semibold mt-1">Baixo estoque</div>
      </a>
      <div
        class="bg-white rounded-xl shadow p-6 flex flex-col items-center transition group
        {{ is_null($cuponsAtivos) ? 'pointer-events-none opacity-60' : 'hover:bg-purple-50 cursor-pointer' }}">
        <ion-icon name="pricetags-outline" class="text-purple-600" size="large"></ion-icon>
        <div class="text-2xl font-bold mt-2">
          {{ is_null($cuponsAtivos) ? '—' : $cuponsAtivos }}
        </div>
        <div class="text-gray-600 text-sm font-semibold mt-1">
          Cupons ativos {{ is_null($cuponsAtivos) ? '(em breve)' : '' }}
        </div>
      </div>
      <div
        class="bg-white rounded-xl shadow p-6 flex flex-col items-center transition group
        {{ is_null($totalPontosFidelidade) ? 'pointer-events-none opacity-60' : 'hover:bg-yellow-50 cursor-pointer' }}">
        <ion-icon name="star-outline" class="text-yellow-600" size="large"></ion-icon>
        <div class="text-2xl font-bold mt-2">
          {{ is_null($totalPontosFidelidade) ? '—' : $totalPontosFidelidade }}
        </div>
        <div class="text-gray-600 text-sm font-semibold mt-1">
          Pontos distribuídos {{ is_null($totalPontosFidelidade) ? '(em breve)' : '' }}
        </div>
      </div>
      <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
        <ion-icon name="person-add-outline" class="text-indigo-600" size="large"></ion-icon>
        <div class="text-2xl font-bold mt-2">{{ $novosClientes }}</div>
        <div class="text-gray-600 text-sm font-semibold mt-1">Novos clientes ({{ $dias }} dias)</div>
      </div>
      <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
        <ion-icon name="people-outline" class="text-indigo-600" size="large"></ion-icon>
        <div class="text-2xl font-bold mt-2">{{ $totalClientes }}</div>
        <div class="text-gray-600 text-sm font-semibold mt-1">Clientes totais</div>
      </div>
    </div>

    <!-- Gráfico-resumo com seletor de período -->
    <div class="bg-white rounded-xl shadow p-6 mb-10">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-2">
        <h2 class="font-semibold text-lg">Pedidos nos últimos {{ $dias }} dias</h2>
        <form method="GET" class="flex items-center gap-2">
          <label for="dias" class="text-sm font-medium">Período:</label>
          <select name="dias" id="dias" class="border rounded px-2 py-1 text-sm" onchange="this.form.submit()">
            <option value="7" {{ $dias == 7 ? 'selected' : '' }}>7 dias</option>
            <option value="15" {{ $dias == 15 ? 'selected' : '' }}>15 dias</option>
            <option value="30" {{ $dias == 30 ? 'selected' : '' }}>30 dias</option>
          </select>
        </form>
      </div>
      <canvas id="ordersChart" height="80"></canvas>
    </div>
  </div>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('ordersChart').getContext('2d');
    const chartData = @json($chartData);
    const chartLabels = @json($chartLabels);
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: chartLabels,
        datasets: [{
          label: 'Pedidos',
          data: chartData,
          borderColor: '#2563eb',
          backgroundColor: 'rgba(37,99,235,0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
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
  </script>
@endsection
