<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dias = (int) $request->input('dias', 7);

        // Pedidos e receita no período (defensivo contra diferenças de schema)
        $totalPedidos = 0;
        $totalReceita = 0.0;
        if (Schema::hasTable('orders')) {
            $ordersInPeriod = Order::where('created_at', '>=', now()->subDays(max($dias - 1, 0)));
            $totalPedidos = (int) $ordersInPeriod->count();

            // Nem todos ambientes podem ter a coluna "total"; calcular de forma segura
            if (Schema::hasColumn('orders', 'total')) {
                $totalReceita = (float) $ordersInPeriod->sum('total');
            } else {
                // fallback: somar subtotal - discount se existir
                $totalReceita = (float) DB::table('orders')
                    ->where('created_at', '>=', now()->subDays(max($dias - 1, 0)))
                    ->selectRaw('COALESCE(SUM(COALESCE(subtotal,0) - COALESCE(discount,0)),0) as soma')
                    ->value('soma');
            }
        }
        $ticketMedio = $totalPedidos > 0 ? ($totalReceita / $totalPedidos) : 0.0;

        // Novos clientes no período
        $novosClientes = 0;
        if (Schema::hasTable('users')) {
            $novosClientes = (int) User::role('cliente')
                ->where('created_at', '>=', now()->subDays(max($dias - 1, 0)))
                ->count();
        }

        // Produtos ativos e baixo estoque
        $totalProdutos = 0;
        $produtosBaixoEstoque = 0;
        if (Schema::hasTable('products')) {
            $totalProdutos = (int) Product::where('active', true)->count();
            $produtosBaixoEstoque = (int) Product::where('active', true)->where('stock', '<', 5)->count();
        }

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
        $orders = collect();
        if (Schema::hasTable('orders')) {
            // Alguns drivers/bancos exigem date() diferente; usar DATE() de forma segura
            $driver = DB::getDriverName();
            $dateExpr = $driver === 'pgsql' ? 'DATE(created_at)' : 'DATE(created_at)';
            $orders = Order::selectRaw($dateExpr . ' as date, COUNT(*) as total')
                ->where('created_at', '>=', now()->subDays(max($dias - 1, 0)))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }

        $labels = [];
        $values = [];
        try {
            foreach ($orders as $order) {
                $data = Carbon::parse($order->date)->locale('pt_BR');
                $labels[] = $data->translatedFormat('d M');
                $values[] = (int) ($order->total ?? 0);
            }
        } catch (\Throwable $e) {
            // Em caso de qualquer problema com datas/locale, não quebrar o dashboard
            $labels = [];
            $values = [];
        }

        // Produtos recentes (últimos 5)
        $produtosRecentes = collect();
        if (Schema::hasTable('products')) {
            $produtosRecentes = Product::with(['brand'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        // Produtos com baixo estoque
        $produtosBaixoEstoqueList = collect();
        if (Schema::hasTable('products')) {
            $produtosBaixoEstoqueList = Product::with(['brand'])
                ->where('active', true)
                ->where('stock', '<', 5)
                ->orderBy('stock', 'asc')
                ->limit(5)
                ->get();
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
