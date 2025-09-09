# 🚀 Sprint 1 - Sistema de Avaliações de Produtos

## 🎯 **Objetivo da Sprint**
Implementar sistema completo de reviews e avaliações de produtos, permitindo que clientes avaliem produtos comprados e outros clientes vejam essas avaliações.

**Duração**: 1 semana
**Prioridade**: Alta
**Complexidade**: Média

---

## 📋 **Funcionalidades a Implementar**

### **1. Sistema de Reviews**
- [ ] **Modelo Review**: Tabela e modelo com relacionamentos
- [ ] **Validações**: Campos obrigatórios e regras de negócio
- [ ] **Relacionamentos**: User, Product, Order (para validação)

### **2. Interface de Avaliação**
- [ ] **Formulário de Review**: Campos para rating, título e comentário
- [ ] **Validação de Compra**: Apenas compradores podem avaliar
- [ ] **Interface Responsiva**: Design adaptável para mobile

### **3. Exibição de Avaliações**
- [ ] **Lista de Reviews**: Exibição no produto com paginação
- [ ] **Filtros**: Por rating, data, verificadas
- [ ] **Ordenação**: Por data, rating, útil

### **4. Sistema de Moderação**
- [ ] **Painel Admin**: Aprovar/rejeitar reviews
- [ ] **Status**: Pendente, Aprovada, Rejeitada
- [ ] **Notificações**: Alertas para admin

### **5. Métricas e Estatísticas**
- [ ] **Média de Avaliações**: Cálculo automático
- [ ] **Contadores**: Total de reviews por produto
- [ ] **Distribuição**: Gráfico de distribuição de ratings

---

## 🛠️ **Tarefas Técnicas**

### **T001: Criar Migration para Tabela Reviews**
```php
// database/migrations/xxxx_create_reviews_table.php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
    $table->integer('rating')->unsigned()->min(1)->max(5);
    $table->string('title')->nullable();
    $table->text('comment')->nullable();
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->boolean('verified_purchase')->default(false);
    $table->integer('helpful_count')->default(0);
    $table->timestamps();
    
    $table->unique(['user_id', 'product_id']); // Um review por usuário por produto
    $table->index(['product_id', 'status']);
    $table->index(['user_id', 'status']);
});
```

### **T002: Criar Modelo Review**
```php
// app/Models/Review.php
class Review extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'order_id', 'rating', 
        'title', 'comment', 'status', 'verified_purchase'
    ];

    protected $casts = [
        'verified_purchase' => 'boolean',
        'helpful_count' => 'integer',
    ];

    // Relacionamentos
    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function order() { return $this->belongsTo(Order::class); }

    // Scopes
    public function scopeApproved($query) { return $query->where('status', 'approved'); }
    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeVerified($query) { return $query->where('verified_purchase', true); }

    // Métodos helper
    public function isApproved() { return $this->status === 'approved'; }
    public function isPending() { return $this->status === 'pending'; }
    public function isRejected() { return $this->status === 'rejected'; }
}
```

### **T003: Criar Controller ReviewController**
```php
// app/Http/Controllers/Shop/ReviewController.php
class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // Validação
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ]);

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
            'status' => $hasPurchased ? 'approved' : 'pending',
        ]);

        return redirect()->back()->with('success', 'Avaliação enviada com sucesso!');
    }

    private function hasUserPurchasedProduct($productId): bool
    {
        return Auth::user()->orders()
            ->whereHas('items', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', '!=', 'cancelled')
            ->exists();
    }
}
```

### **T004: Implementar Interface de Avaliação**
```blade
{{-- resources/views/shop/products/review-form.blade.php --}}
@auth
    @if(!$hasReviewed && $canReview)
        <div class="review-form">
            <h3>Avaliar Produto</h3>
            <form action="{{ route('shop.reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="form-group">
                    <label>Avaliação</label>
                    <div class="rating-input">
                        @for($i = 1; $i <= 5; $i++)
                            <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}">
                            <label for="rating{{ $i }}">★</label>
                        @endfor
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Título (opcional)</label>
                    <input type="text" name="title" class="form-control" placeholder="Resumo da sua avaliação">
                </div>
                
                <div class="form-group">
                    <label>Comentário (opcional)</label>
                    <textarea name="comment" class="form-control" rows="4" placeholder="Conte sua experiência com o produto"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Enviar Avaliação</button>
            </form>
        </div>
    @endif
@endauth
```

### **T005: Implementar Exibição de Avaliações**
```blade
{{-- resources/views/shop/products/reviews-list.blade.php --}}
<div class="reviews-section">
    <h3>Avaliações dos Clientes</h3>
    
    <div class="reviews-summary">
        <div class="rating-average">
            <span class="rating-value">{{ $averageRating }}</span>
            <div class="rating-stars">
                @for($i = 1; $i <= 5; $i++)
                    <span class="star {{ $i <= $averageRating ? 'filled' : '' }}">★</span>
                @endfor
            </div>
            <span class="rating-count">({{ $totalReviews }} avaliações)</span>
        </div>
    </div>
    
    <div class="reviews-list">
        @foreach($reviews as $review)
            <div class="review-item">
                <div class="review-header">
                    <div class="reviewer-info">
                        <span class="reviewer-name">{{ $review->user->name }}</span>
                        @if($review->verified_purchase)
                            <span class="verified-badge">✓ Compra verificada</span>
                        @endif
                    </div>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                        @endfor
                    </div>
                    <div class="review-date">{{ $review->created_at->format('d/m/Y') }}</div>
                </div>
                
                @if($review->title)
                    <h4 class="review-title">{{ $review->title }}</h4>
                @endif
                
                @if($review->comment)
                    <p class="review-comment">{{ $review->comment }}</p>
                @endif
                
                <div class="review-actions">
                    <button class="helpful-btn" data-review-id="{{ $review->id }}">
                        👍 Útil ({{ $review->helpful_count }})
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    
    {{ $reviews->links() }}
</div>
```

---

## 🎨 **Design e Interface**

### **Componentes Visuais**
- **Rating Stars**: Sistema de 5 estrelas interativo
- **Review Cards**: Cards com informações do reviewer
- **Verified Badge**: Badge para compras verificadas
- **Helpful Button**: Botão para marcar review como útil
- **Pagination**: Navegação entre páginas de reviews

### **Estados da Interface**
- **Formulário de Review**: Aparece apenas para usuários logados que podem avaliar
- **Lista de Reviews**: Sempre visível para todos os usuários
- **Empty State**: Mensagem quando não há reviews
- **Loading States**: Indicadores de carregamento

---

## 🔒 **Regras de Negócio**

### **Quem Pode Avaliar**
- ✅ **Usuários logados** que compraram o produto
- ✅ **Apenas uma avaliação** por usuário por produto
- ✅ **Avaliações verificadas** são aprovadas automaticamente
- ❌ **Usuários não logados** não podem avaliar
- ❌ **Usuários que não compraram** precisam de moderação

### **Validações**
- **Rating**: Obrigatório, entre 1 e 5
- **Título**: Opcional, máximo 255 caracteres
- **Comentário**: Opcional, máximo 1000 caracteres
- **Produto**: Deve existir e estar ativo
- **Usuário**: Deve estar logado

### **Moderação**
- **Compras verificadas**: Aprovadas automaticamente
- **Outras avaliações**: Ficam pendentes para moderação
- **Admin pode**: Aprovar, rejeitar ou editar reviews

---

## 📊 **Métricas e Estatísticas**

### **Cálculos Automáticos**
- **Média de Rating**: Soma de ratings / número de reviews
- **Total de Reviews**: Contagem de reviews aprovadas
- **Distribuição**: Quantidade por rating (1-5 estrelas)
- **Taxa de Resposta**: % de produtos com reviews

### **Relatórios Admin**
- **Reviews Pendentes**: Lista para moderação
- **Produtos Sem Reviews**: Lista para incentivar avaliações
- **Reviews Mais Úteis**: Ranking por helpful_count
- **Estatísticas Gerais**: Métricas do sistema

---

## 🧪 **Testes**

### **Testes Unitários**
- [ ] **Modelo Review**: Relacionamentos e validações
- [ ] **Controller**: Lógica de criação e validação
- [ ] **Scopes**: Filtros e consultas
- [ ] **Métodos Helper**: Cálculos e verificações

### **Testes de Integração**
- [ ] **Fluxo Completo**: Criação de review
- [ ] **Validação de Compra**: Verificação de compra
- [ ] **Moderação**: Aprovação/rejeição
- [ ] **Interface**: Formulário e exibição

### **Testes de Interface**
- [ ] **Responsividade**: Mobile e desktop
- [ ] **Acessibilidade**: Screen readers
- [ ] **Usabilidade**: Fluxo do usuário
- [ ] **Performance**: Carregamento de reviews

---

## 📚 **Documentação**

### **Documentação Técnica**
- [ ] **API Documentation**: Endpoints e parâmetros
- [ ] **Database Schema**: Estrutura da tabela
- [ ] **Business Rules**: Regras de negócio
- [ ] **Integration Guide**: Como integrar com outros sistemas

### **Documentação de Usuário**
- [ ] **User Guide**: Como avaliar produtos
- [ ] **Admin Guide**: Como moderar reviews
- [ ] **FAQ**: Perguntas frequentes
- [ ] **Troubleshooting**: Solução de problemas

---

## 🚀 **Cronograma da Sprint**

### **Dia 1-2: Backend**
- [ ] Migration e Modelo
- [ ] Controller e Validações
- [ ] Relacionamentos e Scopes

### **Dia 3-4: Frontend**
- [ ] Interface de Avaliação
- [ ] Exibição de Reviews
- [ ] Componentes Visuais

### **Dia 5-6: Funcionalidades**
- [ ] Sistema de Moderação
- [ ] Métricas e Estatísticas
- [ ] Validação de Compra

### **Dia 7: Testes e Documentação**
- [ ] Testes Automatizados
- [ ] Documentação Completa
- [ ] Deploy e Validação

---

## ✅ **Critérios de Aceitação**

### **Funcionalidades Core**
- [ ] Usuários podem avaliar produtos comprados
- [ ] Reviews são exibidas no produto
- [ ] Sistema de moderação funciona
- [ ] Métricas são calculadas automaticamente

### **Qualidade**
- [ ] Interface responsiva e intuitiva
- [ ] Validações funcionando corretamente
- [ ] Performance adequada
- [ ] Testes passando

### **Documentação**
- [ ] Documentação técnica completa
- [ ] Guia de usuário disponível
- [ ] Código bem comentado
- [ ] README atualizado

---

## 🎯 **Entregáveis da Sprint**

### **Código**
- ✅ Migration para tabela reviews
- ✅ Modelo Review com relacionamentos
- ✅ Controller ReviewController
- ✅ Views para avaliação e exibição
- ✅ Rotas e middleware

### **Funcionalidades**
- ✅ Sistema de avaliação completo
- ✅ Interface de moderação
- ✅ Métricas e estatísticas
- ✅ Validação de compra

### **Documentação**
- ✅ Documentação técnica
- ✅ Guia de usuário
- ✅ Testes automatizados
- ✅ README atualizado

---

**Status**: 🚀 **INICIANDO SPRINT 1**
**Objetivo**: ✅ **SISTEMA DE AVALIAÇÕES COMPLETO**
**Duração**: ⏱️ **1 SEMANA**
