# 🧪 Roteiro de Testes - Sistema de Avaliações

## 📋 **Visão Geral**

Este roteiro de testes cobre todas as funcionalidades do Sistema de Avaliações implementado na Sprint 1, incluindo testes manuais, automatizados e de integração.

---

## 🎯 **Objetivos dos Testes**

- ✅ Validar funcionalidades do sistema de reviews
- ✅ Verificar regras de negócio e validações
- ✅ Testar interface do usuário e experiência
- ✅ Confirmar sistema de moderação admin
- ✅ Validar performance e responsividade

---

## 🔧 **Preparação do Ambiente**

### **Dados de Teste Necessários**

1. **Usuários:**
   - Usuário comum com pedidos
   - Usuário comum sem pedidos
   - Administrador
   - Usuário não logado

2. **Produtos:**
   - Produto com reviews existentes
   - Produto sem reviews
   - Produto com diferentes ratings

3. **Pedidos:**
   - Pedidos entregues (para reviews verificadas)
   - Pedidos cancelados (não devem permitir reviews)

### **Comandos de Preparação**

```bash
# Executar testes automatizados
php artisan test:review-system

# Limpar dados de teste
php artisan migrate:fresh --seed

# Verificar logs
tail -f storage/logs/laravel.log
```

---

## 🧪 **Teste 1: Sistema de Criação de Reviews**

### **Cenário 1.1: Usuário Logado com Compra Verificada**

**Pré-condições:**
- Usuário logado
- Produto comprado e entregue
- Acessar página do produto

**Passos:**
1. Acesse `/produtos/{id}` (produto comprado)
2. Clique na aba "Avaliações"
3. Verifique se formulário aparece
4. Preencha rating (5 estrelas)
5. Adicione título: "Produto excelente!"
6. Adicione comentário: "Qualidade muito boa, recomendo!"
7. Clique em "Enviar Avaliação"

**Resultado Esperado:**
- ✅ Review criada com status "approved"
- ✅ Badge "Compra verificada" aparece
- ✅ Review aparece na lista imediatamente
- ✅ Mensagem de sucesso: "Avaliação enviada com sucesso!"

### **Cenário 1.2: Usuário Logado sem Compra**

**Pré-condições:**
- Usuário logado
- Produto NÃO comprado
- Acessar página do produto

**Passos:**
1. Acesse `/produtos/{id}` (produto não comprado)
2. Clique na aba "Avaliações"
3. Verifique mensagem de aviso

**Resultado Esperado:**
- ✅ Mensagem: "Apenas compradores podem avaliar"
- ✅ Formulário não aparece
- ✅ Explicação clara do motivo

### **Cenário 1.3: Usuário Não Logado**

**Pré-condições:**
- Usuário não logado
- Acessar página do produto

**Passos:**
1. Acesse `/produtos/{id}` (sem login)
2. Clique na aba "Avaliações"
3. Verifique mensagem exibida

**Resultado Esperado:**
- ✅ Mensagem: "Faça login para avaliar"
- ✅ Botão "Fazer Login" disponível
- ✅ Formulário não aparece

### **Cenário 1.4: Validações do Formulário**

**Passos:**
1. Acesse formulário de review
2. Tente enviar sem rating
3. Tente enviar com rating inválido
4. Teste limite de caracteres

**Resultado Esperado:**
- ✅ Erro ao enviar sem rating
- ✅ Validação de rating 1-5
- ✅ Contador de caracteres funciona
- ✅ Limite de 255 chars no título
- ✅ Limite de 1000 chars no comentário

---

## 🧪 **Teste 2: Exibição e Filtros de Reviews**

### **Cenário 2.1: Lista de Reviews**

**Passos:**
1. Acesse produto com reviews
2. Vá para aba "Avaliações"
3. Verifique elementos da lista

**Resultado Esperado:**
- ✅ Resumo com média de ratings
- ✅ Distribuição de estrelas
- ✅ Lista de reviews paginada
- ✅ Informações do reviewer
- ✅ Data da avaliação
- ✅ Badge de compra verificada

### **Cenário 2.2: Filtros de Reviews**

**Passos:**
1. Use filtro por rating (5 estrelas)
2. Use filtro "Apenas verificadas"
3. Teste ordenação por data
4. Teste ordenação por rating

**Resultado Esperado:**
- ✅ Filtro por rating funciona
- ✅ Filtro por verificação funciona
- ✅ Ordenação por data funciona
- ✅ Ordenação por rating funciona
- ✅ Contador atualiza corretamente

### **Cenário 2.3: Sistema "Útil"**

**Passos:**
1. Clique em "Útil" em uma review
2. Verifique contador atualizado
3. Teste em review diferente

**Resultado Esperado:**
- ✅ Contador incrementa
- ✅ Botão fica destacado
- ✅ Botão fica desabilitado após clique
- ✅ Contador persiste após reload

---

## 🧪 **Teste 3: Sistema de Moderação Admin**

### **Cenário 3.1: Acesso ao Painel Admin**

**Pré-condições:**
- Usuário com role "admin"
- Reviews pendentes no sistema

**Passos:**
1. Faça login como admin
2. Acesse `/admin/reviews`
3. Verifique dashboard

**Resultado Esperado:**
- ✅ Estatísticas resumidas visíveis
- ✅ Lista de reviews com filtros
- ✅ Ações disponíveis por review
- ✅ Interface responsiva

### **Cenário 3.2: Aprovação de Review**

**Passos:**
1. Localize review pendente
2. Clique em "Aprovar"
3. Confirme ação
4. Verifique status atualizado

**Resultado Esperado:**
- ✅ Review aprovada com sucesso
- ✅ Status muda para "approved"
- ✅ Review aparece no frontend
- ✅ Log de ação registrado

### **Cenário 3.3: Rejeição de Review**

**Passos:**
1. Localize review pendente
2. Clique em "Rejeitar"
3. Confirme ação
4. Verifique status atualizado

**Resultado Esperado:**
- ✅ Review rejeitada com sucesso
- ✅ Status muda para "rejected"
- ✅ Review não aparece no frontend
- ✅ Log de ação registrado

### **Cenário 3.4: Ações em Lote**

**Passos:**
1. Selecione múltiplas reviews pendentes
2. Clique em "Aprovar Selecionadas"
3. Confirme ação
4. Verifique resultados

**Resultado Esperado:**
- ✅ Múltiplas reviews aprovadas
- ✅ Contador atualizado
- ✅ Mensagem de sucesso
- ✅ Performance adequada

### **Cenário 3.5: Filtros Admin**

**Passos:**
1. Use filtro por status
2. Use filtro por rating
3. Use busca por texto
4. Teste combinação de filtros

**Resultado Esperado:**
- ✅ Filtros funcionam individualmente
- ✅ Combinação de filtros funciona
- ✅ Resultados corretos
- ✅ Performance mantida

---

## 🧪 **Teste 4: Validações e Regras de Negócio**

### **Cenário 4.1: Review Duplicada**

**Passos:**
1. Crie review para produto
2. Tente criar segunda review para mesmo produto
3. Verifique comportamento

**Resultado Esperado:**
- ✅ Segunda review bloqueada
- ✅ Mensagem de erro clara
- ✅ Constraint de banco respeitada

### **Cenário 4.2: Edição de Review**

**Passos:**
1. Crie review aprovada
2. Tente editar após 24h
3. Tente editar antes de 24h

**Resultado Esperado:**
- ✅ Edição bloqueada após 24h
- ✅ Edição permitida antes de 24h
- ✅ Validações mantidas

### **Cenário 4.3: Exclusão de Review**

**Passos:**
1. Crie review como usuário
2. Tente excluir review
3. Confirme exclusão

**Resultado Esperado:**
- ✅ Exclusão permitida para dono
- ✅ Review removida do sistema
- ✅ Estatísticas atualizadas

---

## 🧪 **Teste 5: Performance e Responsividade**

### **Cenário 5.1: Performance com Muitas Reviews**

**Passos:**
1. Crie 100+ reviews para um produto
2. Acesse página do produto
3. Teste carregamento e paginação

**Resultado Esperado:**
- ✅ Carregamento rápido (< 2s)
- ✅ Paginação funciona
- ✅ Filtros responsivos
- ✅ Sem erros de memória

### **Cenário 5.2: Responsividade Mobile**

**Passos:**
1. Acesse site no mobile
2. Teste formulário de review
3. Teste lista de reviews
4. Teste painel admin

**Resultado Esperado:**
- ✅ Interface adaptada para mobile
- ✅ Formulário funcional
- ✅ Botões acessíveis
- ✅ Texto legível

### **Cenário 5.3: Compatibilidade de Navegadores**

**Passos:**
1. Teste no Chrome
2. Teste no Firefox
3. Teste no Safari
4. Teste no Edge

**Resultado Esperado:**
- ✅ Funcionalidade em todos navegadores
- ✅ Estilos consistentes
- ✅ JavaScript funciona
- ✅ Sem erros de console

---

## 🧪 **Teste 6: Integração com Sistema Existente**

### **Cenário 6.1: Integração com Produtos**

**Passos:**
1. Verifique exibição de reviews no produto
2. Teste cálculo de média
3. Verifique atualização em tempo real

**Resultado Esperado:**
- ✅ Reviews exibidas corretamente
- ✅ Média calculada automaticamente
- ✅ Atualização sem reload

### **Cenário 6.2: Integração com Pedidos**

**Passos:**
1. Crie pedido com produto
2. Marque como entregue
3. Teste criação de review verificada

**Resultado Esperado:**
- ✅ Pedido vinculado à review
- ✅ Badge de verificação aparece
- ✅ Auto-aprovação funciona

### **Cenário 6.3: Integração com Usuários**

**Passos:**
1. Verifique perfil do usuário
2. Teste estatísticas de reviews
3. Verifique histórico de avaliações

**Resultado Esperado:**
- ✅ Estatísticas corretas
- ✅ Histórico completo
- ✅ Relacionamentos funcionando

---

## 🧪 **Teste 7: Segurança e Validação**

### **Cenário 7.1: Validação CSRF**

**Passos:**
1. Desabilite JavaScript
2. Tente enviar review
3. Verifique proteção

**Resultado Esperado:**
- ✅ Token CSRF validado
- ✅ Requisições bloqueadas sem token
- ✅ Segurança mantida

### **Cenário 7.2: Autorização**

**Passos:**
1. Tente acessar admin sem permissão
2. Tente editar review de outro usuário
3. Teste endpoints protegidos

**Resultado Esperado:**
- ✅ Acesso negado para não-admin
- ✅ Edição bloqueada para outros usuários
- ✅ Redirecionamento adequado

### **Cenário 7.3: Validação de Dados**

**Passos:**
1. Tente enviar dados maliciosos
2. Teste SQL injection
3. Teste XSS

**Resultado Esperado:**
- ✅ Dados sanitizados
- ✅ SQL injection bloqueado
- ✅ XSS prevenido

---

## 🧪 **Teste 8: Casos Extremos**

### **Cenário 8.1: Produto Sem Reviews**

**Passos:**
1. Acesse produto sem reviews
2. Verifique estado vazio
3. Teste criação da primeira review

**Resultado Esperado:**
- ✅ Estado vazio bem exibido
- ✅ Mensagem motivacional
- ✅ Primeira review criada com sucesso

### **Cenário 8.2: Usuário com Muitas Reviews**

**Passos:**
1. Crie usuário com 50+ reviews
2. Teste performance
3. Verifique estatísticas

**Resultado Esperado:**
- ✅ Performance mantida
- ✅ Estatísticas corretas
- ✅ Interface responsiva

### **Cenário 8.3: Concorrência**

**Passos:**
1. Abra múltiplas abas
2. Crie reviews simultaneamente
3. Teste moderação simultânea

**Resultado Esperado:**
- ✅ Sem conflitos de dados
- ✅ Transações atômicas
- ✅ Consistência mantida

---

## 📊 **Checklist de Testes**

### **Funcionalidades Core**
- [ ] Criação de review com compra verificada
- [ ] Criação de review sem compra verificada
- [ ] Bloqueio para usuários não logados
- [ ] Bloqueio para usuários sem compra
- [ ] Validação de rating obrigatório
- [ ] Validação de limites de caracteres
- [ ] Prevenção de reviews duplicadas

### **Interface do Usuário**
- [ ] Formulário responsivo
- [ ] Lista de reviews com paginação
- [ ] Filtros funcionando
- [ ] Ordenação funcionando
- [ ] Sistema "útil" funcionando
- [ ] Estados vazios bem exibidos
- [ ] Mensagens de erro claras

### **Sistema Admin**
- [ ] Dashboard de moderação
- [ ] Aprovação de reviews
- [ ] Rejeição de reviews
- [ ] Ações em lote
- [ ] Filtros avançados
- [ ] Estatísticas corretas
- [ ] Logs de ações

### **Performance**
- [ ] Carregamento rápido
- [ ] Paginação eficiente
- [ ] Filtros responsivos
- [ ] Sem vazamentos de memória
- [ ] Índices de banco funcionando

### **Segurança**
- [ ] Validação CSRF
- [ ] Autorização adequada
- [ ] Sanitização de dados
- [ ] Prevenção de SQL injection
- [ ] Prevenção de XSS

### **Integração**
- [ ] Relacionamentos funcionando
- [ ] Cálculos automáticos
- [ ] Atualização em tempo real
- [ ] Consistência de dados

---

## 🚨 **Problemas Conhecidos e Soluções**

### **Problema 1: Review não aparece após criação**
**Causa:** Status não é "approved"
**Solução:** Verificar se `verified_purchase` está true

### **Problema 2: Erro de validação de compra**
**Causa:** Relacionamento order-items não existe
**Solução:** Verificar se pedido tem itens vinculados

### **Problema 3: Performance lenta**
**Causa:** Falta de índices ou eager loading
**Solução:** Verificar índices e implementar eager loading

### **Problema 4: Filtros não funcionam**
**Causa:** JavaScript não carregado
**Solução:** Verificar console para erros JS

---

## 📈 **Métricas de Sucesso**

### **Performance**
- ✅ Carregamento < 2 segundos
- ✅ Paginação < 1 segundo
- ✅ Filtros < 500ms

### **Usabilidade**
- ✅ Taxa de conclusão > 90%
- ✅ Tempo de tarefa < 2 minutos
- ✅ Erros de usuário < 5%

### **Qualidade**
- ✅ Bugs críticos: 0
- ✅ Bugs menores: < 3
- ✅ Cobertura de testes: > 95%

---

## 🎯 **Conclusão**

Este roteiro de testes garante que o Sistema de Avaliações está funcionando corretamente em todos os aspectos:

- ✅ **Funcionalidades** implementadas e testadas
- ✅ **Interface** responsiva e intuitiva
- ✅ **Segurança** validada e protegida
- ✅ **Performance** otimizada e eficiente
- ✅ **Integração** com sistema existente

**Status**: 🎉 **SISTEMA PRONTO PARA PRODUÇÃO!**

---

**Próximo passo**: Executar todos os testes e validar funcionamento completo antes do deploy.
