<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])->orderBy('id', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->filled('status') && $request->status != 'Todos') {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Itens por página
        $perPage = $request->get('per_page', 10);
        $perPageOptions = [5, 10, 15, 20, 25];

        $orders = $query->paginate($perPage)->withQueryString();

        // Estatísticas rápidas
        $stats = [
            'total' => Order::count(),
            'revenue' => Order::whereIn('status', ['paid', 'shipped', 'delivered'])->sum('total'),
            'average' => Order::whereIn('status', ['paid', 'shipped', 'delivered'])->avg('total') ?: 0,
        ];

        // Se for uma requisição AJAX, retorna apenas as estatísticas
        if ($request->ajax() && $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'stats' => $stats
            ]);
        }

        return view('admin.orders.index', compact('orders', 'stats', 'perPage', 'perPageOptions'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string',
        ]);
        $order->update($validated);

        // AJAX: retorna label e classe CSS do status
        if ($request->wantsJson()) {
            $labels = [
                'pending' => ['Aguardando', 'bg-yellow-100 text-yellow-700'],
                'paid' => ['Pago', 'bg-green-100 text-green-700'],
                'shipped' => ['Enviado', 'bg-blue-100 text-blue-700'],
                'delivered' => ['Entregue', 'bg-gray-200 text-gray-700'],
                'canceled' => ['Cancelado', 'bg-red-100 text-red-700'],
            ];
            $status = $order->status;
            return response()->json([
                'success' => true,
                'label' => $labels[$status][0] ?? ucfirst($status),
                'class' => $labels[$status][1] ?? 'bg-gray-100 text-gray-700',
            ]);
        }

        return back()->with('success', 'Status do pedido atualizado com sucesso!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pedido removido com sucesso!');
    }
}
