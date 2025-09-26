<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Exibe a página de perfil do usuário
     */
    public function show()
    {
        $user = Auth::user();
        
        // Estatísticas do perfil
        $stats = [
            'totalPedidos' => $user->orders()->count(),
            'totalGasto' => $user->orders()->where('status', '!=', 'cancelled')->sum('total') ?: 0,
            'totalAvaliacoes' => $user->reviews()->count(),
            'totalFavoritos' => $user->favorites()->count(),
            'membroDesde' => $user->created_at->format('d/m/Y'),
            'ultimoLogin' => $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca',
        ];

        // Atividades recentes
        $atividadesRecentes = collect();
        
        // Últimos pedidos
        $ultimosPedidos = $user->orders()
            ->with(['items.product'])
            ->latest()
            ->take(3)
            ->get();
        
        // Últimas avaliações
        $ultimasAvaliacoes = $user->reviews()
            ->with('product')
            ->latest()
            ->take(3)
            ->get();

        return view('shop.profile.show', [
            'user' => $user,
            'stats' => $stats,
            'ultimosPedidos' => $ultimosPedidos,
            'ultimasAvaliacoes' => $ultimasAvaliacoes,
        ]);
    }

    /**
     * Atualiza as informações do perfil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('shop.profile.show')
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Atualiza a senha do usuário
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('shop.profile.show')
            ->with('success', 'Senha atualizada com sucesso!');
    }

    /**
     * Atualiza a foto do perfil
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = Auth::user();

        // Remove foto anterior se existir
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Salva nova foto
        $path = $request->file('photo')->store('profile-photos', 'public');
        
        $user->update([
            'profile_photo_path' => $path,
        ]);

        return redirect()->route('shop.profile.show')
            ->with('success', 'Foto do perfil atualizada com sucesso!');
    }

    /**
     * Remove a foto do perfil
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            
            $user->update([
                'profile_photo_path' => null,
            ]);
        }

        return redirect()->route('shop.profile.show')
            ->with('success', 'Foto do perfil removida com sucesso!');
    }

    /**
     * Exclui a conta do usuário
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        // Remove foto do perfil se existir
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Logout antes de excluir
        Auth::logout();
        
        // Exclui a conta
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Sua conta foi excluída com sucesso.');
    }

    /**
     * Encerra outras sessões do usuário
     */
    public function logoutOtherSessions(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        Auth::logoutOtherDevices($request->password);

        return redirect()->route('shop.profile.show')
            ->with('success', 'Outras sessões foram encerradas com sucesso!');
    }
}
