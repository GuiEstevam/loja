<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Listar todos os usuários
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'orders'])->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('email_verified_at', '!=', null);
            } elseif ($request->status === 'inactive') {
                $query->where('email_verified_at', null);
            }
        }

        // Busca por texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);

        // Estatísticas gerais
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::role('cliente')->count(),
            'total_admins' => User::role('admin')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
        ];

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'stats', 'roles'));
    }

    /**
     * Visualizar detalhes do usuário
     */
    public function show(User $user)
    {
        $user->load(['roles', 'orders' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }, 'addresses', 'reviews' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(5);
        }]);

        // Estatísticas do usuário
        $userStats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->sum('total'),
            'total_reviews' => $user->reviews()->count(),
            'avg_rating_given' => $user->reviews()->avg('rating'),
            'last_order' => $user->orders()->latest()->first(),
            'favorite_categories' => $user->orders()
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('category_product', 'products.id', '=', 'category_product.product_id')
                ->join('categories', 'category_product.category_id', '=', 'categories.id')
                ->selectRaw('categories.name, COUNT(*) as count')
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
        ];

        return view('admin.users.show', compact('user', 'userStats'));
    }

    /**
     * Formulário de edição do usuário
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'user_type' => 'required|in:admin,cliente',
        ]);

        // Remove password se estiver vazio
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        // Atualizar role baseado no user_type
        $user->syncRoles([$data['user_type']]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Formulário de criação de usuário
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Criar novo usuário
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:admin,cliente',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = now(); // Auto-verificar email
        $data['active'] = true; // Usuário ativo por padrão

        $user = User::create($data);

        // Atribuir role baseado no user_type
        $user->assignRole($data['user_type']);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Excluir usuário
     */
    public function destroy(User $user)
    {
        // Não permitir excluir o próprio usuário
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Você não pode excluir sua própria conta!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Ativar/Desativar usuário
     */
    public function toggleStatus(User $user)
    {
        if ($user->active) {
            $user->update(['active' => false]);
            $message = 'Usuário desativado com sucesso!';
        } else {
            $user->update(['active' => true]);
            $message = 'Usuário ativado com sucesso!';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Estatísticas de usuários
     */
    public function stats()
    {
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::role('cliente')->count(),
            'total_admins' => User::role('admin')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'new_users_last_month' => User::whereMonth('created_at', now()->subMonth()->month)->count(),
        ];

        // Gráfico de usuários por mês (últimos 12 meses)
        $usersByMonth = User::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $chartData = [];
        $chartLabels = [];
        
        foreach ($usersByMonth as $data) {
            $date = \Carbon\Carbon::create($data->year, $data->month, 1);
            $chartLabels[] = $date->format('M/Y');
            $chartData[] = $data->count;
        }

        return view('admin.users.stats', compact('stats', 'chartLabels', 'chartData'));
    }
}
