<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Listar todas as reviews para moderação
     */
    public function index(Request $request)
    {
        $query = Review::with(['user:id,name', 'product:id,name'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('verified')) {
            $query->where('verified_purchase', $request->boolean('verified'));
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Busca por texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Quantidade de itens por página
        $perPage = $request->get('per_page', 15);
        $perPageOptions = [5, 10, 15, 25, 50];

        $reviews = $query->paginate($perPage)
            ->withQueryString();

        // Estatísticas gerais
        $stats = [
            'total' => Review::count(),
            'pending' => Review::pending()->count(),
            'approved' => Review::approved()->count(),
            'rejected' => Review::rejected()->count(),
            'verified' => Review::verified()->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats', 'perPageOptions'));
    }

    /**
     * Exibir detalhes de uma review
     */
    public function show(Review $review)
    {
        $review->load(['user', 'product', 'order']);
        
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Aprovar review
     */
    public function approve(Review $review)
    {
        try {
            $review->approve();

            Log::info('Review aprovada', [
                'review_id' => $review->id,
                'product_id' => $review->product_id,
                'user_id' => $review->user_id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review aprovada com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao aprovar review: ' . $e->getMessage(), [
                'review_id' => $review->id
            ]);

            return response()->json([
                'error' => 'Erro ao aprovar review'
            ], 500);
        }
    }

    /**
     * Rejeitar review
     */
    public function reject(Review $review)
    {
        try {
            $review->reject();

            Log::info('Review rejeitada', [
                'review_id' => $review->id,
                'product_id' => $review->product_id,
                'user_id' => $review->user_id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review rejeitada com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao rejeitar review: ' . $e->getMessage(), [
                'review_id' => $review->id
            ]);

            return response()->json([
                'error' => 'Erro ao rejeitar review'
            ], 500);
        }
    }

    /**
     * Aprovar múltiplas reviews
     */
    public function approveMultiple(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id'
        ]);

        try {
            DB::beginTransaction();

            $approvedCount = Review::whereIn('id', $validated['review_ids'])
                ->where('status', Review::STATUS_PENDING)
                ->update(['status' => Review::STATUS_APPROVED]);

            DB::commit();

            Log::info('Reviews aprovadas em lote', [
                'count' => $approvedCount,
                'review_ids' => $validated['review_ids'],
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "{$approvedCount} reviews aprovadas com sucesso!"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erro ao aprovar reviews em lote: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao aprovar reviews'
            ], 500);
        }
    }

    /**
     * Rejeitar múltiplas reviews
     */
    public function rejectMultiple(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id'
        ]);

        try {
            DB::beginTransaction();

            $rejectedCount = Review::whereIn('id', $validated['review_ids'])
                ->where('status', Review::STATUS_PENDING)
                ->update(['status' => Review::STATUS_REJECTED]);

            DB::commit();

            Log::info('Reviews rejeitadas em lote', [
                'count' => $rejectedCount,
                'review_ids' => $validated['review_ids'],
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "{$rejectedCount} reviews rejeitadas com sucesso!"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erro ao rejeitar reviews em lote: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao rejeitar reviews'
            ], 500);
        }
    }

    /**
     * Deletar review
     */
    public function destroy(Review $review)
    {
        try {
            $reviewId = $review->id;
            $review->delete();

            Log::info('Review deletada', [
                'review_id' => $reviewId,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review removida com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao deletar review: ' . $e->getMessage(), [
                'review_id' => $review->id
            ]);

            return response()->json([
                'error' => 'Erro ao remover review'
            ], 500);
        }
    }

    /**
     * Estatísticas de reviews
     */
    public function stats()
    {
        $stats = [
            'total_reviews' => Review::count(),
            'approved_reviews' => Review::approved()->count(),
            'pending_reviews' => Review::pending()->count(),
            'rejected_reviews' => Review::rejected()->count(),
            'verified_reviews' => Review::verified()->count(),
            'average_rating' => Review::approved()->avg('rating'),
            'rating_distribution' => $this->getRatingDistribution(),
            'reviews_by_month' => $this->getReviewsByMonth(),
            'top_products' => $this->getTopProductsByReviews(),
            'top_reviewers' => $this->getTopReviewers(),
        ];

        return response()->json($stats);
    }

    /**
     * Distribuição de ratings
     */
    private function getRatingDistribution(): array
    {
        $distribution = [];
        
        for ($rating = 1; $rating <= 5; $rating++) {
            $distribution[$rating] = Review::approved()
                ->where('rating', $rating)
                ->count();
        }

        return $distribution;
    }

    /**
     * Reviews por mês
     */
    private function getReviewsByMonth(): array
    {
        return Review::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->pluck('count', 'month')
            ->toArray();
    }

    /**
     * Produtos com mais reviews
     */
    private function getTopProductsByReviews(): array
    {
        return Review::selectRaw('product_id, COUNT(*) as review_count')
            ->approved()
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderBy('review_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'product' => $item->product,
                    'review_count' => $item->review_count
                ];
            })
            ->toArray();
    }

    /**
     * Usuários que mais avaliam
     */
    private function getTopReviewers(): array
    {
        return Review::selectRaw('user_id, COUNT(*) as review_count')
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderBy('review_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'user' => $item->user,
                    'review_count' => $item->review_count
                ];
            })
            ->toArray();
    }
}