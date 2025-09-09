<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Exibir reviews de um produto
     */
    public function index(Request $request, Product $product)
    {
        $query = $product->approvedReviews()
            ->with(['user:id,name'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('verified')) {
            $query->where('verified_purchase', $request->boolean('verified'));
        }

        // Ordenação
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $query->orderBy('rating', 'asc');
                break;
            case 'helpful':
                $query->orderBy('helpful_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $reviews = $query->paginate(10);

        // Estatísticas do produto
        $stats = [
            'average_rating' => $product->average_rating,
            'total_reviews' => $product->total_reviews,
            'rating_distribution' => $product->rating_distribution,
        ];

        return response()->json([
            'reviews' => $reviews,
            'stats' => $stats,
        ]);
    }

    /**
     * Criar nova review
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Verificar se usuário pode avaliar
        if (!$product->canUserReview(Auth::id())) {
            return response()->json([
                'error' => 'Você não pode avaliar este produto. Apenas compradores podem avaliar.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Verificar se usuário comprou o produto
            $hasPurchased = $this->hasUserPurchasedProduct($validated['product_id']);

            // Criar review
            $review = Review::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
                'rating' => $validated['rating'],
                'title' => $validated['title'],
                'comment' => $validated['comment'],
                'verified_purchase' => $hasPurchased,
                'status' => $hasPurchased ? Review::STATUS_APPROVED : Review::STATUS_PENDING,
            ]);

            // Buscar pedido relacionado se existir
            if ($hasPurchased) {
                $order = $this->getUserOrderForProduct($validated['product_id']);
                if ($order) {
                    $review->update(['order_id' => $order->id]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $hasPurchased 
                    ? 'Avaliação enviada com sucesso!' 
                    : 'Avaliação enviada e será analisada pela nossa equipe.',
                'review' => $review->load('user:id,name')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar review: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
                'request_data' => $request->all()
            ]);

            return response()->json([
                'error' => 'Erro ao enviar avaliação. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Atualizar review do usuário
     */
    public function update(Request $request, Review $review)
    {
        // Verificar se review pertence ao usuário
        if ($review->user_id !== Auth::id()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        // Verificar se review pode ser editada
        if ($review->isApproved() && $review->created_at->diffInHours(now()) > 24) {
            return response()->json([
                'error' => 'Reviews aprovadas só podem ser editadas em até 24 horas'
            ], 403);
        }

        $validated = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Avaliação atualizada com sucesso!',
            'review' => $review->load('user:id,name')
        ]);
    }

    /**
     * Deletar review do usuário
     */
    public function destroy(Review $review)
    {
        // Verificar se review pertence ao usuário
        if ($review->user_id !== Auth::id()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Avaliação removida com sucesso!'
        ]);
    }

    /**
     * Marcar review como útil
     */
    public function markHelpful(Review $review)
    {
        $review->markAsHelpful();

        return response()->json([
            'success' => true,
            'helpful_count' => $review->fresh()->helpful_count
        ]);
    }

    /**
     * Verificar se usuário pode avaliar produto
     */
    public function canReview(Product $product)
    {
        $canReview = $product->canUserReview(Auth::id());
        $hasReviewed = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        return response()->json([
            'can_review' => $canReview && !$hasReviewed,
            'has_reviewed' => $hasReviewed,
            'reason' => $hasReviewed ? 'Você já avaliou este produto' : 
                       (!$canReview ? 'Apenas compradores podem avaliar' : null)
        ]);
    }

    /**
     * Verificar se usuário comprou o produto
     */
    private function hasUserPurchasedProduct($productId): bool
    {
        return Order::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();
    }

    /**
     * Buscar pedido do usuário para o produto
     */
    private function getUserOrderForProduct($productId): ?Order
    {
        return Order::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->orderBy('created_at', 'desc')
            ->first();
    }
}