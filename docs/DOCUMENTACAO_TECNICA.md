# 📖 Documentação Técnica - SkyFashion

## 🎯 Visão Geral

O **SkyFashion** é uma plataforma de e-commerce desenvolvida em **Laravel 12** com foco em moda e produtos de vestuário. Este documento apresenta a documentação técnica completa do sistema.

## 🏗️ Arquitetura Técnica

### **Stack Tecnológico**
- **Framework**: Laravel 12 (PHP 8.2+)
- **Autenticação**: Laravel Fortify + Jetstream
- **Autorização**: Spatie Laravel Permission
- **Frontend**: Blade Templates + CSS Customizado
- **Banco de Dados**: MySQL/PostgreSQL
- **Assets**: Vite + CSS Modules
- **APIs**: Laravel Sanctum

### **Estrutura do Projeto**
```
SkyFashion/
├── app/
│   ├── Http/Controllers/     # Controladores
│   │   ├── Admin/            # Painel administrativo
│   │   └── Shop/             # Funcionalidades da loja
│   ├── Models/               # Modelos Eloquent
│   ├── Services/             # Serviços de negócio
│   └── Helpers/              # Funções auxiliares
├── database/
│   ├── migrations/           # Migrações do banco
│   ├── seeders/              # Dados iniciais
│   └── factories/             # Factories para testes
├── resources/
│   ├── views/                # Templates Blade
│   ├── css/                  # Estilos CSS
│   └── js/                   # JavaScript
├── routes/
│   └── web.php               # Rotas da aplicação
└── public/                   # Arquivos públicos
```

## 📊 Modelos de Dados

### **User (Usuário)**
```php
// Campos principais
- id, name, email, password, phone
- email_verified_at, created_at, updated_at

// Relacionamentos
- hasMany(Order::class) - Pedidos do usuário
- hasMany(Address::class) - Endereços salvos
- hasOne(LoyaltyPoint::class) - Pontos de fidelidade
- hasMany(CartItem::class) - Itens do carrinho
- hasMany(Favorite::class) - Produtos favoritos

// Traits especiais
- HasRoles (Spatie) - Sistema de roles (admin/cliente)
- TwoFactorAuthenticatable - Autenticação 2FA
- HasApiTokens - Tokens para API
```

### **Product (Produto)**
```php
// Campos principais
- id, name, slug, description, price, stock, image, sku
- brand_id, active, featured, weight, dimensions
- free_shipping, rating, rating_count
- installments, installment_value
- is_new, is_sale, sale_price, sale_ends_at

// Relacionamentos Many-to-Many
- belongsToMany(Category::class) - Categorias
- belongsToMany(Color::class) - Cores disponíveis
- belongsToMany(Size::class) - Tamanhos disponíveis
- belongsTo(Brand::class) - Marca do produto
- hasMany(OrderItem::class) - Itens em pedidos
```

### **Order (Pedido)**
```php
// Campos principais
- id, user_id, subtotal, discount, total, status
- name, email, phone, address, country, zip
- street, number, complement, neighborhood, city, state
- payment_method, notes, created_at, updated_at

// Relacionamentos
- belongsTo(User::class) - Cliente
- hasMany(OrderItem::class) - Itens do pedido
- hasOne(Payment::class) - Informações de pagamento
```

### **CartItem (Item do Carrinho)**
```php
// Campos principais
- id, user_id, session_id, product_id, quantity
- color, color_hex, size, price

// Funcionalidades especiais
- Suporte a usuários logados e sessões anônimas
- Chave única para variações (produto+cor+tamanho)
- Sincronização automática entre localStorage e banco
```

## 🔐 Sistema de Autenticação

### **Laravel Fortify**
- Login/Registro com email e senha
- Autenticação de dois fatores (2FA)
- Recuperação de senha
- Verificação de email
- Rate limiting para tentativas de login

### **Spatie Laravel Permission**
- **Roles**: `admin`, `cliente`
- **Redirecionamento automático**:
  - Admin → `/admin/dashboard`
  - Cliente → `/shop/dashboard`

### **Middleware de Segurança**
- `auth` - Usuário autenticado
- `role:admin` - Acesso administrativo
- `SyncCartOnLogin` - Sincronização de dados após login

## 🛒 Funcionalidades do E-commerce

### **1. Catálogo de Produtos**
- Listagem com filtros: busca, categoria, marca, cor, tamanho, faixa de preço
- Ordenação: preço, nome, data de criação, popularidade
- Paginação: 12 produtos por página
- Detalhes do produto: imagens, variações, descrição, avaliações

### **2. Sistema de Carrinho Avançado**
- Adicionar/remover produtos
- Seleção de cor e tamanho
- Quantidade personalizável
- Persistência em sessão e banco de dados
- Sincronização automática entre dispositivos

### **3. Sistema de Favoritos**
- Adicionar/remover produtos dos favoritos
- Sincronização entre sessão e banco de dados
- Interface dedicada para gerenciar favoritos

### **4. Processo de Checkout**
- Revisão do carrinho
- Informações de entrega
- Método de pagamento
- Confirmação do pedido

## 🎛️ Painel Administrativo

### **Dashboard Administrativo**
- Métricas principais: pedidos, receita, ticket médio, novos clientes
- Produtos ativos e baixo estoque
- Gráficos de pedidos nos últimos 30 dias
- Ações rápidas para gerenciamento

### **Gestão de Produtos**
- CRUD completo: criar/editar/excluir produtos
- Upload de imagens
- Associação com categorias, cores, tamanhos
- Controle de estoque
- Busca por nome, SKU, categoria
- Filtro de baixo estoque

### **Gestão de Pedidos**
- Visualização de todos os pedidos
- Detalhes completos do pedido
- Atualização de status do pedido

## 🎨 Sistema de Design

### **Arquitetura CSS**
- Modular: Arquivos CSS separados por funcionalidade
- Variáveis CSS: Cores, espaçamentos, tipografia centralizados
- Dark Mode: Suporte nativo com classe `.dark`
- Responsivo: Mobile-first design

### **Arquivos CSS Principais**
```
resources/css/
├── variables.css      # Variáveis globais
├── base.css          # Reset e estilos base
├── components.css    # Componentes reutilizáveis
├── admin.css         # Estilos do painel admin
├── products.css      # Páginas de produtos
├── cart.css          # Carrinho de compras
├── checkout.css      # Processo de checkout
├── dashboard.css     # Dashboard do usuário
└── welcome.css       # Página inicial
```

## 🛣️ Estrutura de Rotas

### **Rotas Públicas**
```php
// Página inicial
GET / → HomeController@welcome

// Catálogo
GET /produtos → ShopProductController@index
GET /produtos/{product} → ShopProductController@show
POST /produtos/buscar → ShopProductController@search

// Carrinho (acesso público)
GET /carrinho → ShopCartController@index
POST /carrinho/adicionar/{product} → ShopCartController@add
POST /carrinho/remover/{product} → ShopCartController@remove

// APIs de Sincronização
POST /api/sync/cart → ApiSyncController@syncCart
POST /api/sync/favorites → ApiSyncController@syncFavorites
```

### **Rotas Autenticadas**
```php
// Dashboard do usuário
GET /minha-conta → ShopDashboardController@index

// Pedidos
GET /meus-pedidos → ShopOrderController@index
GET /meus-pedidos/{order} → ShopOrderController@show
POST /checkout → ShopOrderController@process

// Endereços (CRUD completo)
Route::resource('enderecos', ShopAddressController::class);
```

### **Rotas Administrativas**
```php
// Dashboard admin
GET /admin/dashboard → AdminDashboardController@index

// Gestão de produtos
Route::resource('admin/products', AdminProductController::class);

// Gestão de pedidos
Route::resource('admin/orders', AdminOrderController::class)
    ->only(['index', 'show', 'update']);

// Gestão de entidades
Route::resource('admin/categories', AdminCategoryController::class);
Route::resource('admin/brands', AdminBrandController::class);
```

## 🔄 Serviços Avançados

### **CartSyncService**
Serviço responsável pela sincronização inteligente de dados:

```php
// Funcionalidades principais
- syncCartToDatabase() - Sincronizar carrinho local → banco
- loadCartFromDatabase() - Carregar carrinho do banco
- mergeCarts() - Mesclar carrinhos local e banco
- syncFavoritesToDatabase() - Sincronizar favoritos
- loadFavoritesFromDatabase() - Carregar favoritos do banco
- mergeFavorites() - Mesclar favoritos local e banco
```

### **Sistema de Sincronização**
- **Usuários anônimos**: Dados salvos em localStorage + session
- **Usuários logados**: Dados persistidos no banco de dados
- **Login**: Merge automático de dados locais com dados do banco
- **APIs REST**: Endpoints para sincronização via JavaScript

## 📱 Experiência do Usuário

### **Página Inicial**
- Banner Hero com slider de produtos em destaque
- Seção de marcas com carrossel de logos
- Produtos em destaque em grid responsivo
- Navegação com Swiper.js para carrosséis interativos

### **Catálogo de Produtos**
- Filtros laterais: busca, preço, categoria, marca, cor, tamanho
- Grid responsivo com adaptação automática
- Paginação suave entre páginas
- Múltiplas opções de ordenação

### **Detalhes do Produto**
- Galeria de imagens com visualização ampliada
- Seleção de variações (cor e tamanho)
- Controles intuitivos de quantidade
- Ações: adicionar ao carrinho, comprar agora, favoritar

## 🔧 Configuração e Deploy

### **Dependências Principais**
```json
{
  "laravel/framework": "^12.0",
  "laravel/jetstream": "^5.3",
  "laravel/sanctum": "^4.0",
  "livewire/livewire": "^3.0",
  "spatie/laravel-permission": "^6.18"
}
```

### **Scripts de Desenvolvimento**
```json
{
  "dev": "php artisan serve + queue:listen + pail + npm run dev",
  "test": "php artisan config:clear + php artisan test"
}
```

## 📈 Métricas e Analytics

### **Dashboard Administrativo**
- Pedidos: Total, receita, ticket médio
- Clientes: Novos clientes, crescimento
- Produtos: Ativos, baixo estoque, performance
- Período configurável (7, 30, 90 dias)

### **Dashboard do Cliente**
- Pedidos: Total de pedidos, pendentes
- Gastos: Total gasto na plataforma
- Endereços: Quantidade de endereços salvos
- Acesso rápido a funcionalidades principais

## 🚀 Funcionalidades Futuras

### **Sistema de Pontos de Fidelidade**
- Acúmulo de pontos por compras
- Troca de pontos por descontos
- Programa de referência

### **Sistema de Cupons**
- Cupons de desconto
- Cupons de frete grátis
- Validação automática

### **Sistema de Pagamento**
- Integração com gateways de pagamento
- Parcelamento automático
- Confirmação de pagamento

### **Sistema de Avaliações**
- Avaliações de produtos
- Sistema de estrelas
- Comentários de clientes

## 📋 Conclusão

O sistema **SkyFashion** representa uma solução completa de e-commerce com:

✅ **Arquitetura Robusta**: Laravel 12 com padrões modernos
✅ **Experiência do Usuário**: Interface intuitiva e responsiva
✅ **Funcionalidades Avançadas**: Carrinho inteligente, favoritos, sincronização
✅ **Painel Administrativo**: Gestão completa de produtos e pedidos
✅ **Segurança**: Autenticação robusta com 2FA e controle de acesso
✅ **Escalabilidade**: Estrutura preparada para crescimento
✅ **Manutenibilidade**: Código organizado e documentado

O sistema está pronto para produção e pode ser facilmente estendido com novas funcionalidades conforme necessário.

---

**Última atualização**: {{ date('d/m/Y H:i') }}
**Versão**: 1.0
**Projeto**: SkyFashion E-commerce
