<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Buscar produtos e usuários existentes
        $products = Product::take(5)->get();
        $users = User::role('cliente')->take(3)->get();
        
        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->info('Não há produtos ou usuários suficientes para criar reviews.');
            return;
        }

        // Criar algumas reviews de exemplo
        $reviews = [
            [
                'user_id' => $users[0]->id,
                'product_id' => $products[0]->id,
                'rating' => 5,
                'title' => 'Excelente produto!',
                'comment' => 'Produto de alta qualidade, superou minhas expectativas. Recomendo!',
                'status' => 'approved',
                'verified_purchase' => true,
                'helpful_count' => 3,
            ],
            [
                'user_id' => $users[1]->id,
                'product_id' => $products[0]->id,
                'rating' => 4,
                'title' => 'Muito bom',
                'comment' => 'Produto bom, mas poderia ser melhor. Entrega rápida.',
                'status' => 'approved',
                'verified_purchase' => true,
                'helpful_count' => 1,
            ],
            [
                'user_id' => $users[0]->id,
                'product_id' => $products[1]->id,
                'rating' => 3,
                'title' => 'Regular',
                'comment' => 'Produto ok, mas não é exatamente o que esperava.',
                'status' => 'approved',
                'verified_purchase' => false,
                'helpful_count' => 0,
            ],
            [
                'user_id' => $users[2]->id,
                'product_id' => $products[1]->id,
                'rating' => 5,
                'title' => 'Perfeito!',
                'comment' => 'Produto incrível! Qualidade excelente e design moderno.',
                'status' => 'approved',
                'verified_purchase' => true,
                'helpful_count' => 5,
            ],
            [
                'user_id' => $users[1]->id,
                'product_id' => $products[2]->id,
                'rating' => 2,
                'title' => 'Não recomendo',
                'comment' => 'Produto não atendeu às expectativas. Qualidade inferior.',
                'status' => 'approved',
                'verified_purchase' => true,
                'helpful_count' => 2,
            ],
            [
                'user_id' => $users[0]->id,
                'product_id' => $products[3]->id,
                'rating' => 4,
                'title' => 'Bom custo-benefício',
                'comment' => 'Produto bom pelo preço. Entrega no prazo.',
                'status' => 'pending',
                'verified_purchase' => false,
                'helpful_count' => 0,
            ],
        ];

        foreach ($reviews as $reviewData) {
            Review::create($reviewData);
        }

        $this->command->info('Reviews de exemplo criadas com sucesso!');
    }
}
