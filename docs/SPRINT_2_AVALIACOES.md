# 📋 Sprint 2 - Sistema de Avaliações

## 🎯 Objetivo do Sprint
Implementar sistema de avaliações reais de produtos para permitir que clientes avaliem produtos comprados e vejam avaliações de outros clientes.

## 📅 Informações do Sprint
- **Número**: Sprint 2
- **Duração**: 2 semanas (10 dias úteis)
- **Data de Início**: {{ date('d/m/Y', strtotime('+2 weeks')) }}
- **Data de Fim**: {{ date('d/m/Y', strtotime('+4 weeks')) }}
- **Sprint Goal**: Sistema de avaliações funcional com moderação

## 👥 Participantes
- **Desenvolvedor**: Guilherme Estevam de Lima
- **Product Owner**: Alex Sousa (Cliente)

---

## 📋 User Stories

### **US005: Avaliar Produtos Comprados**
**Como** cliente  
**Quero** avaliar produtos que comprei  
**Para que** eu possa compartilhar minha experiência

**Critérios de Aceitação**:
- [ ] Cliente pode avaliar apenas produtos comprados
- [ ] Sistema valida se cliente comprou o produto
- [ ] Cliente pode dar nota de 1 a 5 estrelas
- [ ] Cliente pode escrever comentário sobre o produto
- [ ] Avaliação é salva e exibida na página do produto

**Definição de Pronto**:
- [ ] Sistema de validação de compra implementado
- [ ] Interface de avaliação criada
- [ ] Testes automatizados passando
- [ ] Deploy em produção realizado
- [ ] Funcionalidade aceita pelo cliente

**Estimativa**: 5 story points

---

### **US006: Ver Avaliações de Outros Clientes**
**Como** cliente  
**Quero** ver avaliações de outros clientes  
**Para que** eu possa tomar decisões de compra informadas

**Critérios de Aceitação**:
- [ ] Avaliações são exibidas na página do produto
- [ ] Sistema mostra nota média e quantidade de avaliações
- [ ] Cliente pode filtrar avaliações por nota
- [ ] Cliente pode ordenar avaliações por data
- [ ] Sistema exibe informações do avaliador (nome, data)

**Definição de Pronto**:
- [ ] Interface de exibição de avaliações criada
- [ ] Sistema de filtros implementado
- [ ] Cálculo de nota média automático
- [ ] Testes automatizados passando
- [ ] Deploy em produção realizado

**Estimativa**: 4 story points

---

### **US007: Escrever Comentários Detalhados**
**Como** cliente  
**Quero** escrever comentários detalhados sobre produtos  
**Para que** eu possa compartilhar informações úteis

**Critérios de Aceitação**:
- [ ] Cliente pode escrever comentário de até 500 caracteres
- [ ] Sistema valida conteúdo do comentário
- [ ] Cliente pode editar comentário antes de enviar
- [ ] Sistema previne comentários duplicados
- [ ] Comentário é exibido com avaliação

**Definição de Pronto**:
- [ ] Interface de comentários implementada
- [ ] Validação de conteúdo funcionando
- [ ] Sistema anti-spam básico
- [ ] Testes automatizados passando
- [ ] Deploy em produção realizado

**Estimativa**: 3 story points

---

### **US008: Moderar Avaliações**
**Como** administrador  
**Quero** moderar avaliações de clientes  
**Para que** eu possa manter qualidade e relevância

**Critérios de Aceitação**:
- [ ] Administrador pode ver todas as avaliações
- [ ] Administrador pode aprovar/rejeitar avaliações
- [ ] Sistema marca avaliações pendentes de moderação
- [ ] Administrador pode editar avaliações se necessário
- [ ] Sistema envia notificação de nova avaliação

**Definição de Pronto**:
- [ ] Interface de moderação criada
- [ ] Sistema de aprovação implementado
- [ ] Notificações funcionando
- [ ] Testes automatizados passando
- [ ] Deploy em produção realizado

**Estimativa**: 4 story points

---

## 🛠️ Tarefas Técnicas

### **Fase 1: Modelagem e Estrutura (Dias 1-2)**
- [ ] **T001**: Criar modelo Review (avaliações)
- [ ] **T002**: Criar migration para tabela reviews
- [ ] **T003**: Definir relacionamentos com User e Product
- [ ] **T004**: Criar seeders para dados de teste
- [ ] **T005**: Atualizar modelo Product com métodos de avaliação

### **Fase 2: Validação de Compra (Dias 3-4)**
- [ ] **T006**: Implementar validação de compra para avaliar
- [ ] **T007**: Criar método para verificar se cliente comprou produto
- [ ] **T008**: Implementar sistema anti-duplicação
- [ ] **T009**: Criar middleware para validar compra
- [ ] **T010**: Implementar logs de tentativas de avaliação

### **Fase 3: Interface de Avaliação (Dias 5-6)**
- [ ] **T011**: Criar formulário de avaliação
- [ ] **T012**: Implementar sistema de estrelas interativo
- [ ] **T013**: Criar campo de comentário com validação
- [ ] **T014**: Implementar preview da avaliação
- [ ] **T015**: Criar feedback visual de sucesso/erro

### **Fase 4: Exibição de Avaliações (Dias 7-8)**
- [ ] **T016**: Atualizar página de produto com avaliações
- [ ] **T017**: Implementar exibição de nota média
- [ ] **T018**: Criar lista de avaliações com paginação
- [ ] **T019**: Implementar filtros por nota
- [ ] **T020**: Implementar ordenação por data

### **Fase 5: Sistema de Moderação (Dias 9-10)**
- [ ] **T021**: Criar interface administrativa de moderação
- [ ] **T022**: Implementar sistema de aprovação/rejeição
- [ ] **T023**: Criar dashboard de avaliações pendentes
- [ ] **T024**: Implementar notificações para administrador
- [ ] **T025**: Criar relatórios de avaliações

### **Fase 6: Testes e Deploy (Dias 11-12)**
- [ ] **T026**: Implementar testes unitários
- [ ] **T027**: Implementar testes de integração
- [ ] **T028**: Testes em ambiente de staging
- [ ] **T029**: Deploy em produção
- [ ] **T030**: Testes de aceitação com cliente

### **Fase 7: Documentação e Finalização (Dias 13-14)**
- [ ] **T031**: Documentar sistema de avaliações
- [ ] **T032**: Criar manual de moderação
- [ ] **T033**: Atualizar documentação técnica
- [ ] **T034**: Treinar cliente no uso do sistema
- [ ] **T035**: Sprint Review e Retrospectiva

---

## 📊 Burndown Chart

### **Estimativa de Esforço**
- **Total de Story Points**: 16
- **Total de Tarefas**: 35
- **Dias de Desenvolvimento**: 14
- **Velocidade Esperada**: 1.1 story points/dia

### **Progresso Diário**
```
Story Points
    16 ┤
    14 ┤ ●
    12 ┤   ●
    10 ┤     ●
     8 ┤       ●
     6 ┤         ●
     4 ┤           ●
     2 ┤             ●
     0 ┤               ●
        └─────────────────
         1 2 3 4 5 6 7 8 9 10 11 12 13 14
```

---

## 🗄️ Estrutura de Dados

### **Modelo Review**
```php
class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
        'status', // pending, approved, rejected
        'is_verified_purchase',
        'helpful_count',
        'created_at',
        'updated_at'
    ];

    // Relacionamentos
    public function user()
    public function product()
    public function order()
}
```

### **Migration da Tabela Reviews**
```sql
CREATE TABLE reviews (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    order_id BIGINT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    is_verified_purchase BOOLEAN DEFAULT TRUE,
    helpful_count INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    
    UNIQUE KEY unique_user_product_order (user_id, product_id, order_id)
);
```

---

## 🔧 Funcionalidades Técnicas

### **Validação de Compra**
```php
class ReviewService
{
    public function canUserReviewProduct($userId, $productId)
    {
        return Order::where('user_id', $userId)
            ->whereHas('items', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', 'delivered')
            ->exists();
    }
}
```

### **Cálculo de Rating Médio**
```php
class Product extends Model
{
    public function updateAverageRating()
    {
        $approvedReviews = $this->reviews()
            ->where('status', 'approved')
            ->get();
            
        $averageRating = $approvedReviews->avg('rating');
        $reviewCount = $approvedReviews->count();
        
        $this->update([
            'rating' => round($averageRating, 1),
            'rating_count' => $reviewCount
        ]);
    }
}
```

### **Sistema de Moderação**
```php
class ReviewModerationController extends Controller
{
    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);
        $review->product->updateAverageRating();
        
        // Notificar cliente
        $this->notifyUser($review->user, 'approved');
    }
    
    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);
        
        // Notificar cliente
        $this->notifyUser($review->user, 'rejected');
    }
}
```

---

## 📋 Checklist de Implementação

### **Preparação**
- [ ] Estrutura de banco de dados planejada
- [ ] Modelos e relacionamentos definidos
- [ ] Validações de negócio especificadas
- [ ] Interface de usuário projetada
- [ ] Sistema de moderação planejado

### **Desenvolvimento**
- [ ] Modelo Review criado
- [ ] Migration executada
- [ ] Validação de compra implementada
- [ ] Interface de avaliação criada
- [ ] Sistema de exibição implementado
- [ ] Sistema de moderação criado

### **Testes**
- [ ] Testes unitários passando
- [ ] Testes de integração passando
- [ ] Testes de validação de compra
- [ ] Testes de moderação
- [ ] Testes de aceitação realizados

### **Deploy**
- [ ] Código versionado
- [ ] Deploy em produção
- [ ] Dados migrados
- [ ] Monitoramento ativo
- [ ] Backup realizado

### **Documentação**
- [ ] Documentação técnica atualizada
- [ ] Manual de moderação criado
- [ ] Processo documentado
- [ ] Treinamento realizado
- [ ] Sprint Review realizado

---

## 🚨 Riscos e Mitigações

### **Riscos Identificados**
1. **Complexidade da Validação de Compra**
   - **Probabilidade**: Média
   - **Impacto**: Alto
   - **Mitigação**: Testes extensivos e validação dupla

2. **Spam de Avaliações**
   - **Probabilidade**: Alta
   - **Impacto**: Médio
   - **Mitigação**: Sistema de moderação e validação

3. **Performance com Muitas Avaliações**
   - **Probabilidade**: Média
   - **Impacto**: Médio
   - **Mitigação**: Paginação e cache

4. **Avaliações Falsas**
   - **Probabilidade**: Baixa
   - **Impacto**: Alto
   - **Mitigação**: Validação de compra obrigatória

### **Plano de Contingência**
- **Moderação Manual**: Sistema de aprovação para todas as avaliações
- **Validação Rigorosa**: Verificação dupla de compra
- **Sistema de Denúncia**: Clientes podem denunciar avaliações
- **Backup de Dados**: Preservar avaliações legítimas

---

## 📈 Métricas de Sucesso

### **Métricas Técnicas**
- **Cobertura de Testes**: > 90%
- **Tempo de Resposta**: < 2 segundos
- **Uptime**: > 99.5%
- **Bugs Críticos**: 0

### **Métricas de Negócio**
- **Taxa de Avaliação**: > 20% dos clientes
- **Qualidade das Avaliações**: > 4.0 nota média
- **Tempo de Moderação**: < 24 horas
- **Satisfação do Cliente**: > 4.5/5

### **Métricas de Qualidade**
- **Avaliações Aprovadas**: > 80%
- **Avaliações Rejeitadas**: < 20%
- **Tempo de Resposta**: < 1 segundo
- **Feedback**: Positivo do cliente

---

## 🎯 Definição de Pronto para Sprint

### **Critérios Obrigatórios**
- [ ] Todas as user stories completadas
- [ ] Critérios de aceitação atendidos
- [ ] Testes automatizados passando
- [ ] Deploy em produção realizado
- [ ] Documentação atualizada
- [ ] Cliente aceitou funcionalidades
- [ ] Retrospectiva realizada

### **Critérios de Qualidade**
- [ ] Código revisado e aprovado
- [ ] Performance dentro do esperado
- [ ] Segurança validada
- [ ] Acessibilidade verificada
- [ ] Responsividade testada

### **Critérios de Negócio**
- [ ] Funcionalidade testada pelo cliente
- [ ] Feedback positivo recebido
- [ ] Métricas de sucesso atingidas
- [ ] Próximo sprint planejado
- [ ] Lições aprendidas documentadas

---

## 📋 Sprint Review Agenda

### **Agenda da Revisão**
1. **Demonstração das Funcionalidades** (30 min)
   - Avaliação de produtos comprados
   - Exibição de avaliações
   - Sistema de moderação
   - Interface administrativa

2. **Revisão dos Critérios de Aceitação** (15 min)
   - US005: Avaliar produtos comprados
   - US006: Ver avaliações de outros clientes
   - US007: Escrever comentários detalhados
   - US008: Moderar avaliações

3. **Feedback do Cliente** (15 min)
   - Pontos positivos
   - Pontos de melhoria
   - Sugestões para próximos sprints

4. **Planejamento do Próximo Sprint** (15 min)
   - Priorização de funcionalidades
   - Ajustes no processo
   - Definição de objetivos

---

## 🔄 Retrospectiva Template

### **Template de Retrospectiva**
```
SPRINT 2 - RETROSPECTIVA
Data: [DD/MM/YYYY]

O QUE FUNCIONOU BEM:
- [Ponto positivo]
- [Ponto positivo]
- [Ponto positivo]

O QUE PODE MELHORAR:
- [Ponto de melhoria]
- [Ponto de melhoria]
- [Ponto de melhoria]

AÇÕES PARA PRÓXIMO SPRINT:
- [Ação específica]
- [Ação específica]
- [Ação específica]

LIÇÕES APRENDIDAS:
- [Lição aprendida]
- [Lição aprendida]
- [Lição aprendida]

MÉTRICAS DO SPRINT:
- Velocidade: [X] story points
- Bugs encontrados: [X]
- Bugs corrigidos: [X]
- Tempo de desenvolvimento: [X] horas
- Satisfação do cliente: [X]/5
```

---

## 📋 Conclusão

Este Sprint 2 está estruturado para:

✅ **Implementar Avaliações Reais**: Sistema funcional de avaliações
✅ **Garantir Qualidade**: Moderação e validação rigorosas
✅ **Documentar Processo**: Registro completo de todas as atividades
✅ **Entregar Valor**: Funcionalidade importante para credibilidade
✅ **Preparar Próximos Sprints**: Base sólida para evolução

O sprint está preparado para entregar um sistema de avaliações robusto e confiável, aumentando a credibilidade da loja e ajudando clientes a tomar decisões de compra informadas.

---

**Última atualização**: {{ date('d/m/Y H:i') }}
**Versão**: 1.0
**Sprint**: 2 - Sistema de Avaliações
