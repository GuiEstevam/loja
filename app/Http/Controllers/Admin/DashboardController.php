<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dias = (int) $request->input('dias', 7);

        // Pedidos e receita no período
        $ordersInPeriod = Order::where('created_at', '>=', now()->subDays($dias - 1));
        $totalPedidos = $ordersInPeriod->count();
        $totalReceita = (float) $ordersInPeriod->sum('total'); // ajuste 'total' para o campo de valor do pedido
        $ticketMedio = $totalPedidos > 0 ? ($totalReceita / $totalPedidos) : 0;

        // Novos clientes no período
        $novosClientes = User::role('cliente')->where('created_at', '>=', now()->subDays($dias - 1))->count();

        // Produtos ativos e baixo estoque
        $totalProdutos = Product::where('active', true)->count();
        $produtosBaixoEstoque = Product::where('active', true)->where('stock', '<', 5)->count();

        // Cupons ativos (só busca se a tabela/coluna existir)
        $cuponsAtivos = null;
        if (Schema::hasTable('discounts')) {
            $discountModel = \App\Models\Discount::query();
            if (Schema::hasColumn('discounts', 'active')) {
                $discountModel->where('active', true);
            }
            if (Schema::hasColumn('discounts', 'expires_at')) {
                $discountModel->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                });
            }
            $cuponsAtivos = $discountModel->count();
        }

        // Pontos de fidelidade distribuídos (só busca se a tabela existir)
        $totalPontosFidelidade = null;
        if (Schema::hasTable('loyalty_points') && Schema::hasColumn('loyalty_points', 'points')) {
            $totalPontosFidelidade = \App\Models\LoyaltyPoint::sum('points');
        }

        // Gráfico de pedidos por dia (últimos N dias)
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays($dias - 1))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $values = [];
        foreach ($orders as $order) {
            $data = Carbon::parse($order->date)->locale('pt_BR');
            $labels[] = $data->translatedFormat('d M');
            $values[] = $order->total;
        }

        return view('admin.dashboard', [
            'totalPedidos' => $totalPedidos,
            'totalReceita' => $totalReceita,
            'ticketMedio' => $ticketMedio,
            'totalProdutos' => $totalProdutos,
            'produtosBaixoEstoque' => $produtosBaixoEstoque,
            'cuponsAtivos' => $cuponsAtivos,
            'totalPontosFidelidade' => $totalPontosFidelidade,
            'novosClientes' => $novosClientes,
            'totalClientes' => User::role('cliente')->count(),
            'chartLabels' => $labels,
            'chartData' => $values,
            'dias' => $dias,
        ]);
    }
}
