# 🚀 Comandos para Commit e Setup - Sprint 1

## 📋 **Comandos para Fazer Commit**

### **1. Verificar Status:**
```bash
git status
```

### **2. Adicionar Todos os Arquivos:**
```bash
git add .
```

### **3. Fazer Commit:**
```bash
git commit -m "Sprint 1: Sistema de Avaliações Completo

✅ Funcionalidades Implementadas:
- Sistema completo de avaliações de produtos
- Modal de edição com estrelas funcionais
- Sistema 'Útil' para reviews
- Filtros e ordenação
- Moderação admin
- Validação robusta

✅ Correções Implementadas:
- 10 bugs críticos resolvidos
- Interface moderna e responsiva
- Arquitetura refatorada (CSS/JS separados)
- Validação de carrinho robusta
- Redirecionamento inteligente

✅ Arquivos Criados:
- resources/css/reviews.css
- resources/css/review-form.css
- resources/js/reviews.js
- resources/js/review-form.js
- resources/views/components/review-form.blade.php
- resources/views/components/reviews-list.blade.php
- app/Http/Controllers/Shop/ReviewController.php
- app/Http/Controllers/Admin/ReviewController.php
- app/Models/Review.php
- database/migrations/xxxx_create_reviews_table.php
- database/seeders/ReviewSeeder.php

✅ Documentação:
- docs/SPRINT_1_FINALIZACAO_COMPLETA.md
- docs/ROTEIRO_TESTES_AVALIACOES.md
- docs/TESTE_MANUAL_AVALIACOES.md
- docs/ROADMAP_REORGANIZADO.md

Status: Sprint 1 CONCLUÍDA - Pronto para Sprint 2"
```

### **4. Push para Repositório:**
```bash
git push origin main
```

---

## 🔧 **Setup em Outro Local**

### **1. Clone do Repositório:**
```bash
git clone [url-do-repositorio]
cd loja
```

### **2. Instalar Dependências:**
```bash
# PHP
composer install

# Node.js
npm install
```

### **3. Configurar Ambiente:**
```bash
# Copiar arquivo de ambiente
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate

# Configurar banco de dados no .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skyfashion
DB_USERNAME=root
DB_PASSWORD=
```

### **4. Executar Migrações e Seeders:**
```bash
# Executar migrações
php artisan migrate

# Executar seeders
php artisan db:seed --class=ReviewSeeder

# Verificar dados criados
php artisan tinker --execute="echo 'Reviews: ' . \App\Models\Review::count();"
```

### **5. Compilar Assets:**
```bash
# Para desenvolvimento
npm run dev

# Para produção
npm run build
```

### **6. Iniciar Servidor:**
```bash
php artisan serve
```

### **7. Verificar Funcionamento:**
```bash
# Acessar URLs de teste:
# http://localhost:8000/produtos/[id] - Página do produto
# http://localhost:8000/pedidos - Lista de pedidos
# http://localhost:8000/admin/reviews - Painel admin
```

---

## 📊 **Verificações Pós-Setup**

### **1. Verificar Banco de Dados:**
```bash
php artisan tinker --execute="
echo 'Users: ' . \App\Models\User::count() . PHP_EOL;
echo 'Products: ' . \App\Models\Product::count() . PHP_EOL;
echo 'Reviews: ' . \App\Models\Review::count() . PHP_EOL;
echo 'Orders: ' . \App\Models\Order::count() . PHP_EOL;
"
```

### **2. Verificar Arquivos CSS/JS:**
```bash
# Verificar se arquivos foram compilados
ls -la public/build/
```

### **3. Verificar Rotas:**
```bash
php artisan route:list | grep review
```

### **4. Verificar Migrações:**
```bash
php artisan migrate:status
```

---

## 🎯 **Testes Rápidos**

### **1. Teste de Criação de Review:**
```bash
# Acessar produto e tentar criar review
# Verificar se formulário aparece
# Verificar se validação funciona
```

### **2. Teste de Modal de Edição:**
```bash
# Criar review
# Clicar em "Editar"
# Verificar se estrelas mostram rating correto
# Verificar se hover funciona
```

### **3. Teste de Responsividade:**
```bash
# Testar em mobile (375px)
# Testar em tablet (768px)
# Testar em desktop (1920px)
```

---

## 🚨 **Problemas Comuns e Soluções**

### **1. Erro de Migração:**
```bash
# Se der erro, fazer rollback e executar novamente
php artisan migrate:rollback
php artisan migrate
```

### **2. Erro de Seeder:**
```bash
# Verificar se tabelas existem
php artisan migrate:status

# Executar seeder específico
php artisan db:seed --class=ReviewSeeder
```

### **3. Erro de Assets:**
```bash
# Limpar cache e recompilar
npm run build
php artisan cache:clear
php artisan config:clear
```

### **4. Erro de Permissões:**
```bash
# Dar permissões corretas
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

---

## 📝 **Checklist Final**

### **Antes do Commit:**
- [ ] Todos os arquivos adicionados
- [ ] Testes funcionando
- [ ] Documentação atualizada
- [ ] Sem erros de lint

### **Após o Setup:**
- [ ] Migrações executadas
- [ ] Seeders executados
- [ ] Assets compilados
- [ ] Servidor funcionando
- [ ] Testes básicos passando

---

**Status**: ✅ **PRONTO PARA COMMIT E SETUP**
**Próximo**: 🚀 **SPRINT 2 - SISTEMA DE FAVORITOS MELHORADO**

---

**Última atualização**: {{ date('d/m/Y H:i') }}
**Versão**: Sprint 1 - Final
**Projeto**: SkyFashion E-commerce
