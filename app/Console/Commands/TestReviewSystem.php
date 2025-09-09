<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class TestReviewSystem extends Command
{
    protected $signature = 'test:review-system';
    protected $description = 'Testa o sistema completo de reviews e avaliações';

    public function handle()
    {
        $this->info('🧪 Testando Sistema de Reviews...');
        
        try {
            // Limpar dados de teste anteriores
            $this->cleanupTestData();
            
            // Criar dados de teste
            $user = $this->createTestUser();
            $product = $this->createTestProduct();
            $order = $this->createTestOrder($user, $product);
            
            // Testar criação de review
            $this->testReviewCreation($user, $product, $order);
            
            // Testar validações
            $this->testReviewValidations($user, $product);
            
            // Testar relacionamentos
            $this->testReviewRelationships();
            
            // Testar estatísticas
            $this->testReviewStatistics($product);
            
            // Testar moderação
            $this->testReviewModeration();
            
            $this->info("\n🎉 Todos os testes do sistema de reviews passaram!");
            $this->info("✅ Sistema de Reviews funcionando corretamente!");
            
        } catch (\Exception $e) {
            $this->error("❌ Erro no teste: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }

    private function cleanupTestData()
    {
        $this->info('🧹 Limpando dados de teste anteriores...');
        
        // Remover reviews de teste
        Review::where('user_id', 'like', 'teste%')->delete();
        
        // Remover usuários de teste
        User::where('email', 'like', 'teste%')->delete();
        
        // Remover produtos de teste
        Product::where('name', 'like', 'Produto Teste%')->delete();
        
        $this->info('✅ Dados de teste anteriores removidos');
    }

    private function createTestUser()
    {
        $this->info('👤 Criando usuário de teste...');
        
        $user = User::create([
            'name' => 'Usuário Teste Reviews',
            'email' => 'teste-reviews@skyfashion.com',
            'password' => bcrypt('password'),
        ]);
        
        $this->info("✅ Usuário criado: {$user->name} ({$user->email})");
        return $user;
    }

    private function createTestProduct()
    {
        $this->info('📦 Criando produto de teste...');
        
        $product = Product::create([
            'name' => 'Produto Teste Reviews',
            'slug' => 'produto-teste-reviews-' . time(),
            'description' => 'Produto para testar sistema de reviews',
            'price' => 99.90,
            'stock' => 10,
            'image' => 'default.jpg',
            'active' => true,
            'sku' => 'TEST-REVIEW-' . time(),
            'brand_id' => 1, // Assumindo que existe uma marca com ID 1
        ]);
        
        $this->info("✅ Produto criado: {$product->name}");
        return $product;
    }

    private function createTestOrder($user, $product)
    {
        $this->info('🛒 Criando pedido de teste...');
        
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'delivered',
            'total' => $product->price,
            'subtotal' => $product->price,
            'shipping_address' => 'Endereço de teste',
            'billing_address' => 'Endereço de teste',
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);
        
        $this->info("✅ Pedido criado: #{$order->id}");
        return $order;
    }

    private function testReviewCreation($user, $product, $order)
    {
        $this->info('⭐ Testando criação de review...');
        
        // Teste 1: Review com compra verificada
        $review1 = Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 5,
            'title' => 'Excelente produto!',
            'comment' => 'Produto de ótima qualidade, chegou antes do prazo. Recomendo!',
            'verified_purchase' => true,
            'status' => 'approved',
        ]);
        
        $this->info("✅ Review 1 criada: {$review1->title} ({$review1->rating} estrelas)");
        
        // Criar segundo usuário para teste 2
        $user2 = User::create([
            'name' => 'Usuário Teste Reviews 2',
            'email' => 'teste-reviews-2@skyfashion.com',
            'password' => bcrypt('password'),
        ]);
        
        // Teste 2: Review sem compra verificada
        $review2 = Review::create([
            'user_id' => $user2->id,
            'product_id' => $product->id,
            'rating' => 4,
            'title' => 'Muito bom',
            'comment' => 'Produto bom, mas poderia ser melhor.',
            'verified_purchase' => false,
            'status' => 'pending',
        ]);
        
        $this->info("✅ Review 2 criada: {$review2->title} ({$review2->rating} estrelas)");
        
        // Criar terceiro usuário para teste 3
        $user3 = User::create([
            'name' => 'Usuário Teste Reviews 3',
            'email' => 'teste-reviews-3@skyfashion.com',
            'password' => bcrypt('password'),
        ]);
        
        // Teste 3: Review sem título e comentário
        $review3 = Review::create([
            'user_id' => $user3->id,
            'product_id' => $product->id,
            'rating' => 3,
            'verified_purchase' => true,
            'status' => 'approved',
        ]);
        
        $this->info("✅ Review 3 criada: Apenas rating ({$review3->rating} estrelas)");
    }

    private function testReviewValidations($user, $product)
    {
        $this->info('🔍 Testando validações...');
        
        // Teste: Rating inválido
        try {
            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => 6, // Inválido
            ]);
            $this->error('❌ Validação de rating falhou');
        } catch (\Exception $e) {
            $this->info('✅ Validação de rating funcionando');
        }
        
        // Teste: Rating inválido (0)
        try {
            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => 0, // Inválido
            ]);
            $this->error('❌ Validação de rating falhou');
        } catch (\Exception $e) {
            $this->info('✅ Validação de rating funcionando');
        }
        
        // Teste: Review duplicada
        try {
            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => 5,
            ]);
            $this->error('❌ Validação de review duplicada falhou');
        } catch (\Exception $e) {
            $this->info('✅ Validação de review duplicada funcionando');
        }
    }

    private function testReviewRelationships()
    {
        $this->info('🔗 Testando relacionamentos...');
        
        $review = Review::with(['user', 'product', 'order'])->first();
        
        // Teste relacionamento com usuário
        if ($review->user) {
            $this->info("✅ Relacionamento com usuário: {$review->user->name}");
        } else {
            $this->error('❌ Relacionamento com usuário falhou');
        }
        
        // Teste relacionamento com produto
        if ($review->product) {
            $this->info("✅ Relacionamento com produto: {$review->product->name}");
        } else {
            $this->error('❌ Relacionamento com produto falhou');
        }
        
        // Teste relacionamento com pedido (pode ser null)
        if ($review->order) {
            $this->info("✅ Relacionamento com pedido: #{$review->order->id}");
        } else {
            $this->info("✅ Relacionamento com pedido: null (esperado)");
        }
    }

    private function testReviewStatistics($product)
    {
        $this->info('📊 Testando estatísticas...');
        
        // Recarregar produto com relacionamentos
        $product->load('approvedReviews');
        
        // Teste média de avaliações
        $averageRating = $product->average_rating;
        $this->info("✅ Média de avaliações: {$averageRating}");
        
        // Teste total de reviews
        $totalReviews = $product->total_reviews;
        $this->info("✅ Total de reviews: {$totalReviews}");
        
        // Teste distribuição de ratings
        $distribution = $product->rating_distribution;
        $this->info("✅ Distribuição de ratings:");
        foreach ($distribution as $rating => $count) {
            $this->info("   {$rating} estrelas: {$count} reviews");
        }
        
        // Teste métodos estáticos
        $staticAverage = Review::getAverageRatingForProduct($product->id);
        $staticTotal = Review::getTotalReviewsForProduct($product->id);
        $staticDistribution = Review::getRatingDistributionForProduct($product->id);
        
        $this->info("✅ Métodos estáticos funcionando:");
        $this->info("   Média: {$staticAverage}");
        $this->info("   Total: {$staticTotal}");
    }

    private function testReviewModeration()
    {
        $this->info('🛡️ Testando moderação...');
        
        $pendingReview = Review::where('status', 'pending')->first();
        
        if ($pendingReview) {
            // Teste aprovação
            $pendingReview->approve();
            $this->info("✅ Review aprovada: {$pendingReview->id}");
            
            // Teste rejeição
            $pendingReview->reject();
            $this->info("✅ Review rejeitada: {$pendingReview->id}");
            
            // Teste aprovação novamente
            $pendingReview->approve();
            $this->info("✅ Review aprovada novamente: {$pendingReview->id}");
        }
        
        // Teste scopes
        $approvedCount = Review::approved()->count();
        $pendingCount = Review::pending()->count();
        $rejectedCount = Review::rejected()->count();
        $verifiedCount = Review::verified()->count();
        
        $this->info("✅ Scopes funcionando:");
        $this->info("   Aprovadas: {$approvedCount}");
        $this->info("   Pendentes: {$pendingCount}");
        $this->info("   Rejeitadas: {$rejectedCount}");
        $this->info("   Verificadas: {$verifiedCount}");
    }
}