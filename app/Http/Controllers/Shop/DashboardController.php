<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Estatísticas principais
        $stats = [
            // Estatísticas gerais
            'totalPedidos' => $user->orders()->count(),
            'totalGasto' => $user->orders()->where('status', '!=', 'cancelled')->sum('total') ?: 0,
            'pedidosPendentes' => $user->orders()->where('status', 'pending')->count(),
            'enderecosSalvos' => $user->addresses()->count(),
            
            // Estatísticas de pedidos por período
            'pedidosHoje' => $user->orders()->whereDate('created_at', today())->count(),
            'gastoHoje' => $user->orders()->whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total') ?: 0,
            'pedidosSemana' => $user->orders()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'gastoSemana' => $user->orders()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('status', '!=', 'cancelled')->sum('total') ?: 0,
            'pedidosMes' => $user->orders()->whereMonth('created_at', now()->month)->count(),
            'gastoMes' => $user->orders()->whereMonth('created_at', now()->month)->where('status', '!=', 'cancelled')->sum('total') ?: 0,
            
            // Estatísticas de favoritos
            'totalFavoritos' => $user->favorites()->count(),
            'favoritosHoje' => $user->favorites()->whereDate('created_at', today())->count(),
            
            // Estatísticas de avaliações
            'totalAvaliacoes' => $user->reviews()->count(),
            'avaliacoesPendentes' => $user->reviews()->where('status', 'pending')->count(),
            'avaliacoesAprovadas' => $user->reviews()->where('status', 'approved')->count(),
        ];

        // Pedidos recentes (últimos 5)
        $pedidosRecentes = $user->orders()
            ->with(['items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Endereços salvos (últimos 3)
        $enderecosSalvos = $user->addresses()
            ->latest()
            ->take(3)
            ->get();

        // Favoritos recentes (últimos 5)
        $favoritosRecentes = $user->favorites()
            ->with('product')
            ->latest()
            ->take(5)
            ->get();

        // Avaliações recentes (últimas 3)
        $avaliacoesRecentes = $user->reviews()
            ->with('product')
            ->latest()
            ->take(3)
            ->get();

        // Dados para gráfico de gastos (últimos 7 dias)
        $chartData = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d/m');
            $chartData[] = $user->orders()
                ->whereDate('created_at', $date)
                ->where('status', '!=', 'cancelled')
                ->sum('total') ?: 0;
        }

        // Produtos mais comprados
        $produtosMaisComprados = collect();
        if (Schema::hasTable('order_items')) {
            $produtosMaisComprados = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.user_id', $user->id)
                ->where('orders.status', '!=', 'cancelled')
                ->select('products.id', 'products.name', 'products.image', DB::raw('SUM(order_items.quantity) as total_comprado'))
                ->groupBy('products.id', 'products.name', 'products.image')
                ->orderBy('total_comprado', 'desc')
                ->limit(5)
                ->get();
        }

        // Preparar dados do gráfico para JavaScript
        $chartData = [
            'labels' => $chartLabels,
            'data' => $chartData
        ];

        return view('shop.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'pedidosRecentes' => $pedidosRecentes,
            'enderecosSalvos' => $enderecosSalvos,
            'favoritosRecentes' => $favoritosRecentes,
            'avaliacoesRecentes' => $avaliacoesRecentes,
            'produtosMaisComprados' => $produtosMaisComprados,
            'chartData' => $chartData,
        ]);
    }
}
