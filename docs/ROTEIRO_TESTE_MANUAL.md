# 🧪 Roteiro de Teste Manual - Sprint 0 Refatoração

## 📋 **Objetivo do Teste**
Validar manualmente todas as funcionalidades refatoradas no Sprint 0, garantindo que o sistema está funcionando corretamente antes de partir para integração de pagamento real.

---

## 🎯 **Cenários de Teste**

### **Cenário 1: Fluxo Completo de Checkout**

#### **Pré-requisitos**
- [ ] Usuário cadastrado e logado
- [ ] Produtos cadastrados com estoque disponível
- [ ] Carrinho com itens

#### **Passos do Teste**
1. **Acessar a loja**
   - [ ] Navegar para `/`
   - [ ] Verificar se a página carrega corretamente
   - [ ] Verificar se produtos são exibidos

2. **Adicionar produtos ao carrinho**
   - [ ] Clicar em "Adicionar ao Carrinho" em um produto
   - [ ] Selecionar cor e tamanho (se aplicável)
   - [ ] Definir quantidade
   - [ ] Confirmar adição
   - [ ] Verificar se produto aparece no carrinho

3. **Acessar carrinho**
   - [ ] Clicar no ícone do carrinho
   - [ ] Verificar se produtos estão listados
   - [ ] Verificar se totais estão corretos
   - [ ] Clicar em "Finalizar Compra"

4. **Preencher dados de checkout**
   - [ ] Preencher dados pessoais (nome, email, telefone)
   - [ ] Preencher endereço completo
   - [ ] Selecionar método de pagamento
   - [ ] Adicionar observações (opcional)
   - [ ] Clicar em "Finalizar Pedido"

5. **Verificar criação do pedido**
   - [ ] Verificar se redirecionou para página de confirmação
   - [ ] Verificar se pedido foi criado com status "paid"
   - [ ] Verificar se pagamento foi registrado
   - [ ] Verificar se estoque foi atualizado

#### **Resultado Esperado**
- ✅ Pedido criado com sucesso
- ✅ Status do pedido: "paid"
- ✅ Pagamento registrado com status "approved"
- ✅ Estoque dos produtos decrementado
- ✅ Carrinho limpo

---

### **Cenário 2: Validação de Estoque**

#### **Teste 2.1: Estoque Insuficiente**
1. **Preparar cenário**
   - [ ] Criar produto com estoque = 1
   - [ ] Adicionar 2 unidades ao carrinho
   - [ ] Tentar finalizar compra

2. **Verificar comportamento**
   - [ ] Sistema deve exibir erro: "Estoque insuficiente"
   - [ ] Pedido NÃO deve ser criado
   - [ ] Estoque deve permanecer inalterado

#### **Teste 2.2: Produto Inativo**
1. **Preparar cenário**
   - [ ] Desativar produto (active = false)
   - [ ] Tentar adicionar ao carrinho

2. **Verificar comportamento**
   - [ ] Sistema deve exibir erro: "Produto não está disponível"
   - [ ] Produto não deve aparecer na listagem

#### **Teste 2.3: Produto Inexistente**
1. **Preparar cenário**
   - [ ] Tentar adicionar produto com ID inexistente

2. **Verificar comportamento**
   - [ ] Sistema deve exibir erro: "Produto não encontrado"

---

### **Cenário 3: Transações de Banco**

#### **Teste 3.1: Rollback em Caso de Erro**
1. **Preparar cenário**
   - [ ] Simular erro durante criação do pedido
   - [ ] Verificar se rollback ocorre

2. **Verificar comportamento**
   - [ ] Nenhum dado deve ser salvo no banco
   - [ ] Sistema deve exibir mensagem de erro
   - [ ] Carrinho deve permanecer intacto

#### **Teste 3.2: Integridade de Dados**
1. **Verificar relacionamentos**
   - [ ] Pedido deve ter relacionamento com usuário
   - [ ] Pedido deve ter relacionamento com pagamento
   - [ ] Pedido deve ter itens associados
   - [ ] Pagamento deve ter relacionamento com pedido

---

### **Cenário 4: Sistema de Status**

#### **Teste 4.1: Transições Válidas**
1. **Criar pedido**
   - [ ] Status inicial: "pending"

2. **Processar pagamento**
   - [ ] Status deve mudar para: "paid"

3. **Testar transições**
   - [ ] paid → processing ✅
   - [ ] processing → shipped ✅
   - [ ] shipped → delivered ✅
   - [ ] delivered → refunded ✅

#### **Teste 4.2: Transições Inválidas**
1. **Testar transições proibidas**
   - [ ] delivered → pending ❌
   - [ ] cancelled → paid ❌
   - [ ] refunded → processing ❌

2. **Verificar comportamento**
   - [ ] Sistema deve exibir erro de transição inválida
   - [ ] Status deve permanecer inalterado

---

### **Cenário 5: Sistema de Pagamento**

#### **Teste 5.1: Criação de Pagamento**
1. **Finalizar pedido**
   - [ ] Verificar se Payment é criado automaticamente
   - [ ] Verificar campos obrigatórios preenchidos
   - [ ] Verificar status inicial: "pending"

#### **Teste 5.2: Processamento de Pagamento**
1. **Verificar processamento**
   - [ ] Status deve mudar para: "approved"
   - [ ] transaction_id deve ser gerado
   - [ ] gateway_response deve ser salvo
   - [ ] processed_at deve ser preenchido

#### **Teste 5.3: Falha de Pagamento**
1. **Simular falha**
   - [ ] Testar método markAsFailed()
   - [ ] Verificar se status muda para: "failed"
   - [ ] Verificar se failure_reason é salvo
   - [ ] Verificar se failed_at é preenchido

---

### **Cenário 6: Logs e Monitoramento**

#### **Teste 6.1: Logs de Estoque**
1. **Verificar logs**
   - [ ] Acessar arquivo de log
   - [ ] Verificar se movimentação de estoque é logada
   - [ ] Verificar informações: product_id, quantity_sold, remaining_stock

#### **Teste 6.2: Logs de Status**
1. **Verificar logs**
   - [ ] Mudar status do pedido
   - [ ] Verificar se mudança é logada
   - [ ] Verificar informações: order_id, old_status, new_status, reason

#### **Teste 6.3: Logs de Erro**
1. **Verificar logs**
   - [ ] Simular erro no checkout
   - [ ] Verificar se erro é logado
   - [ ] Verificar informações: user_id, cart, request_data

---

## 🔧 **Ferramentas de Teste**

### **Comando de Teste Automatizado**
```bash
php artisan test:checkout-flow
```
- [ ] Executar comando
- [ ] Verificar se todos os testes passam
- [ ] Verificar saída no console

### **Verificação no Banco de Dados**
```sql
-- Verificar pedidos
SELECT * FROM orders ORDER BY created_at DESC LIMIT 5;

-- Verificar pagamentos
SELECT * FROM payments ORDER BY created_at DESC LIMIT 5;

-- Verificar estoque
SELECT id, name, stock FROM products WHERE stock < 5;

-- Verificar logs
SELECT * FROM logs ORDER BY created_at DESC LIMIT 10;
```

### **Verificação de Arquivos de Log**
```bash
# Verificar logs do Laravel
tail -f storage/logs/laravel.log

# Verificar logs específicos
grep "Estoque atualizado" storage/logs/laravel.log
grep "Status do pedido alterado" storage/logs/laravel.log
```

---

## 📊 **Checklist de Validação**

### **Funcionalidades Core**
- [ ] ✅ Checkout completo funcionando
- [ ] ✅ Validação de estoque implementada
- [ ] ✅ Transações de banco funcionando
- [ ] ✅ Sistema de status implementado
- [ ] ✅ Sistema de pagamento funcionando
- [ ] ✅ Atualização de estoque automática
- [ ] ✅ Logs detalhados funcionando

### **Validações de Segurança**
- [ ] ✅ Rollback em caso de erro
- [ ] ✅ Validação de dados de entrada
- [ ] ✅ Verificação de permissões
- [ ] ✅ Proteção contra SQL injection
- [ ] ✅ Validação de relacionamentos

### **Performance e Confiabilidade**
- [ ] ✅ Operações atômicas
- [ ] ✅ Logs para auditoria
- [ ] ✅ Tratamento de exceções
- [ ] ✅ Validação de integridade
- [ ] ✅ Recuperação de erros

---

## 🚨 **Cenários de Falha**

### **Falhas que DEVEM ser tratadas**
1. **Estoque insuficiente**
   - [ ] Erro deve ser exibido
   - [ ] Pedido não deve ser criado
   - [ ] Carrinho deve permanecer intacto

2. **Produto inativo**
   - [ ] Erro deve ser exibido
   - [ ] Produto não deve aparecer

3. **Erro de banco de dados**
   - [ ] Rollback deve ocorrer
   - [ ] Erro deve ser logado
   - [ ] Usuário deve ser notificado

4. **Transição de status inválida**
   - [ ] Erro deve ser exibido
   - [ ] Status deve permanecer inalterado

### **Falhas que NÃO devem ocorrer**
1. **Pedido criado sem pagamento**
2. **Estoque negativo**
3. **Dados inconsistentes**
4. **Perda de dados**

---

## 📝 **Relatório de Teste**

### **Template de Relatório**
```
Data do Teste: ___________
Testador: ___________
Ambiente: ___________

Cenários Testados:
- [ ] Cenário 1: Fluxo Completo de Checkout
- [ ] Cenário 2: Validação de Estoque
- [ ] Cenário 3: Transações de Banco
- [ ] Cenário 4: Sistema de Status
- [ ] Cenário 5: Sistema de Pagamento
- [ ] Cenário 6: Logs e Monitoramento

Problemas Encontrados:
1. ________________
2. ________________
3. ________________

Status Final:
- [ ] ✅ APROVADO - Todos os testes passaram
- [ ] ❌ REPROVADO - Problemas encontrados
- [ ] ⚠️ APROVADO COM RESSALVAS - Pequenos ajustes necessários

Observações:
________________
________________
```

---

## 🎯 **Critérios de Aprovação**

### **Para Aprovação Total**
- [ ] Todos os cenários de teste passaram
- [ ] Nenhum problema crítico encontrado
- [ ] Logs funcionando corretamente
- [ ] Performance adequada
- [ ] Comando de teste automatizado passando

### **Para Aprovação com Ressalvas**
- [ ] Cenários principais funcionando
- [ ] Problemas menores identificados
- [ ] Plano de correção definido
- [ ] Não há problemas de segurança

### **Para Reprovação**
- [ ] Problemas críticos encontrados
- [ ] Funcionalidades principais não funcionando
- [ ] Problemas de segurança identificados
- [ ] Dados sendo perdidos ou corrompidos

---

## 🚀 **Próximos Passos Após Aprovação**

1. **Documentar problemas encontrados**
2. **Corrigir problemas identificados**
3. **Executar testes de regressão**
4. **Preparar ambiente para Sprint 1**
5. **Iniciar integração de pagamento real**

---

**Última atualização**: {{ date('d/m/Y H:i') }}
**Versão**: 1.0
**Sprint**: 0 - Refatoração Crítica
