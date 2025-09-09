# 🏗️ Arquitetura Técnica - SkyFashion

## 🎯 Visão Geral da Arquitetura

O **SkyFashion** foi desenvolvido seguindo os princípios de **Clean Architecture** e **Domain-Driven Design (DDD)**, utilizando Laravel 12 como framework base. A arquitetura foi projetada para ser escalável, manutenível e seguir as melhores práticas de desenvolvimento.

## 🏛️ Padrões Arquiteturais

### **1. Arquitetura em Camadas**
```
┌─────────────────────────────────────┐
│           Presentation Layer        │
│     (Controllers, Views, APIs)     │
├─────────────────────────────────────┤
│          Application Layer         │
│        (Services, Actions)          │
├─────────────────────────────────────┤
│            Domain Layer            │
│         (Models, Business)         │
├─────────────────────────────────────┤
│         Infrastructure Layer      │
│    (Database, External Services)   │
└─────────────────────────────────────┘
```

### **2. Princípios SOLID**
- **S** - Single Responsibility Principle
- **O** - Open/Closed Principle  
- **L** - Liskov Substitution Principle
- **I** - Interface Segregation Principle
- **D** - Dependency Inversion Principle

## 🏗️ Estrutura Detalhada

### **Presentation Layer (Camada de Apresentação)**

#### **Controllers**
```
app/Http/Controllers/
├── Admin/                    # Painel administrativo
│   ├── DashboardController   # Dashboard e métricas
│   ├── ProductController     # Gestão de produtos
│   ├── OrderController       # Gestão de pedidos
│   ├── CategoryController    # Gestão de categorias
│   ├── BrandController       # Gestão de marcas
│   ├── ColorController       # Gestão de cores
│   └── SizeController        # Gestão de tamanhos
├── Shop/                     # Funcionalidades da loja
│   ├── ProductController     # Catálogo de produtos
│   ├── CartController        # Carrinho de compras
│   ├── OrderController       # Processo de pedidos
│   ├── FavoritesController   # Sistema de favoritos
│   └── DashboardController   # Dashboard do cliente
├── Api/                      # APIs REST
│   └── SyncController        # Sincronização de dados
└── HomeController            # Página inicial
```

#### **Views (Blade Templates)**
```
resources/views/
├── admin/                    # Templates administrativos
│   ├── dashboard.blade.php
│   ├── products/             # CRUD de produtos
│   ├── orders/               # Gestão de pedidos
│   └── [entidades]/         # CRUD de outras entidades
├── shop/                     # Templates da loja
│   ├── products/             # Catálogo e detalhes
│   ├── cart/                 # Carrinho de compras
│   ├── orders/               # Pedidos do cliente
│   └── dashboard.blade.php  # Dashboard do cliente
├── auth/                     # Autenticação
├── components/               # Componentes reutilizáveis
└── layouts/                  # Layouts base
```

### **Application Layer (Camada de Aplicação)**

#### **Services**
```
app/Services/
└── CartSyncService.php       # Sincronização de carrinho e favoritos
```

**Responsabilidades dos Services:**
- Lógica de negócio complexa
- Integração entre diferentes camadas
- Operações transacionais
- Validações de negócio

#### **Actions (Fortify)**
```
app/Actions/Fortify/
├── CreateNewUser.php         # Criação de usuários
├── UpdateUserProfileInformation.php
├── UpdateUserPassword.php
└── ResetUserPassword.php
```

### **Domain Layer (Camada de Domínio)**

#### **Models (Entidades de Domínio)**
```
app/Models/
├── User.php                  # Usuário do sistema
├── Product.php               # Produto
├── Order.php                 # Pedido
├── OrderItem.php             # Item do pedido
├── CartItem.php              # Item do carrinho
├── Favorite.php              # Produto favorito
├── Address.php               # Endereço do usuário
├── Payment.php               # Informações de pagamento
├── LoyaltyPoint.php          # Pontos de fidelidade
├── Discount.php              # Cupons de desconto
├── Category.php              # Categoria de produto
├── Brand.php                 # Marca
├── Color.php                 # Cor
└── Size.php                  # Tamanho
```

#### **Relacionamentos Complexos**
```php
// User (Agregado Raiz)
- hasMany(Order::class)
- hasMany(Address::class)
- hasMany(CartItem::class)
- hasMany(Favorite::class)
- hasOne(LoyaltyPoint::class)

// Product (Entidade Principal)
- belongsTo(Brand::class)
- belongsToMany(Category::class)
- belongsToMany(Color::class)
- belongsToMany(Size::class)
- hasMany(OrderItem::class)

// Order (Agregado de Pedido)
- belongsTo(User::class)
- hasMany(OrderItem::class)
- hasOne(Payment::class)
```

### **Infrastructure Layer (Camada de Infraestrutura)**

#### **Database**
```
database/
├── migrations/              # Estrutura do banco
│   ├── create_users_table.php
│   ├── create_products_table.php
│   ├── create_orders_table.php
│   └── [outras_tabelas].php
├── seeders/                  # Dados iniciais
│   ├── DatabaseSeeder.php
│   ├── UserSeeder.php
│   ├── ProductSeeder.php
│   └── [outros_seeders].php
└── factories/                # Factories para testes
    ├── UserFactory.php
    └── ProductFactory.php
```

#### **Configurações**
```
config/
├── app.php                   # Configurações gerais
├── auth.php                  # Autenticação
├── database.php              # Banco de dados
├── fortify.php               # Fortify
├── jetstream.php             # Jetstream
├── permission.php            # Spatie Permission
└── sanctum.php               # Sanctum API
```

## 🔐 Sistema de Autenticação e Autorização

### **Arquitetura de Segurança**
```
┌─────────────────────────────────────┐
│         Frontend (Blade)            │
├─────────────────────────────────────┤
│     Laravel Fortify (Auth)          │
├─────────────────────────────────────┤
│   Spatie Permission (Roles)         │
├─────────────────────────────────────┤
│      Middleware Stack               │
│  ┌─────────────────────────────────┐│
│  │ auth → role:admin → sync        ││
│  └─────────────────────────────────┘│
├─────────────────────────────────────┤
│        Controllers                  │
└─────────────────────────────────────┘
```

### **Fluxo de Autenticação**
1. **Login**: Fortify gerencia o processo
2. **Verificação**: Middleware `auth` valida sessão
3. **Autorização**: Middleware `role:admin` verifica permissões
4. **Sincronização**: Middleware `SyncCartOnLogin` sincroniza dados
5. **Redirecionamento**: Baseado no role do usuário

## 🛒 Arquitetura do E-commerce

### **Fluxo de Compra**
```
┌─────────────────────────────────────┐
│         Catálogo de Produtos        │
├─────────────────────────────────────┤
│         Seleção de Produto          │
├─────────────────────────────────────┤
│        Adição ao Carrinho           │
├─────────────────────────────────────┤
│      Sincronização de Dados         │
├─────────────────────────────────────┤
│           Processo Checkout         │
├─────────────────────────────────────┤
│         Criação do Pedido           │
├─────────────────────────────────────┤
│        Confirmação Final            │
└─────────────────────────────────────┘
```

### **Sistema de Carrinho Inteligente**
```php
// Arquitetura de Sincronização
class CartSyncService
{
    // Sincronização Local → Banco
    public function syncCartToDatabase()
    
    // Carregamento Banco → Local
    public function loadCartFromDatabase()
    
    // Merge Inteligente
    public function mergeCarts()
    
    // Persistência por Sessão/Usuário
    public function persistCartData()
}
```

## 🎨 Arquitetura Frontend

### **Sistema CSS Modular**
```
resources/css/
├── variables.css              # Design System
│   ├── Cores                 # Paleta de cores
│   ├── Tipografia           # Fontes e tamanhos
│   ├── Espaçamentos         # Margins e paddings
│   └── Breakpoints          # Media queries
├── base.css                  # Reset e estilos base
├── components.css            # Componentes reutilizáveis
│   ├── Botões               # .btn, .btn-primary, etc.
│   ├── Formulários          # .form-input, .form-label
│   ├── Cards                # .card, .card-header
│   ├── Tabelas              # .table, .table-responsive
│   └── Alertas              # .alert, .alert-success
├── admin.css                 # Estilos administrativos
└── [páginas].css            # Estilos específicos
```

### **Padrões de Componentes**
```css
/* Padrão de Nomenclatura */
.component-name {
    /* Estilos base */
}

.component-name--modifier {
    /* Variações */
}

.component-name__element {
    /* Elementos internos */
}

.component-name__element--modifier {
    /* Elementos com modificadores */
}
```

## 🔄 Arquitetura de Dados

### **Estratégia de Persistência**
```
┌─────────────────────────────────────┐
│         Frontend (localStorage)     │
├─────────────────────────────────────┤
│         Session (PHP)               │
├─────────────────────────────────────┤
│         Database (MySQL)             │
└─────────────────────────────────────┘
```

### **Sincronização Inteligente**
1. **Usuário Anônimo**: localStorage + session
2. **Usuário Logado**: database + localStorage
3. **Login**: merge automático de dados
4. **Logout**: persistência em session

## 🚀 Arquitetura de Deploy

### **Ambiente de Desenvolvimento**
```bash
# Script de desenvolvimento
npm run dev
├── php artisan serve        # Servidor Laravel
├── php artisan queue:listen  # Processamento de filas
├── php artisan pail          # Logs em tempo real
└── npm run dev              # Build de assets
```

### **Estrutura de Produção**
```
SkyFashion/
├── app/                     # Código da aplicação
├── bootstrap/              # Bootstrap da aplicação
├── config/                 # Configurações
├── database/               # Migrações e seeders
├── public/                 # Arquivos públicos
├── resources/              # Views e assets
├── routes/                 # Definição de rotas
├── storage/                # Armazenamento
├── vendor/                 # Dependências
├── .env                    # Variáveis de ambiente
├── artisan                 # CLI do Laravel
└── composer.json           # Dependências PHP
```

## 📊 Arquitetura de Monitoramento

### **Logs e Debugging**
```
storage/logs/
├── laravel.log             # Log principal
├── [data]-laravel.log      # Logs por data
└── [canal]-laravel.log     # Logs por canal
```

### **Métricas do Sistema**
- **Performance**: Tempo de resposta das páginas
- **Uso de Memória**: Consumo de RAM
- **Banco de Dados**: Queries lentas
- **Erros**: Exceptions e erros 500

## 🔧 Arquitetura de Testes

### **Estrutura de Testes**
```
tests/
├── Feature/                # Testes de integração
│   ├── AuthenticationTest.php
│   ├── ProductTest.php
│   └── OrderTest.php
├── Unit/                   # Testes unitários
│   └── CartSyncServiceTest.php
└── TestCase.php            # Classe base
```

### **Estratégia de Testes**
- **Unit Tests**: Services e Models
- **Feature Tests**: Controllers e rotas
- **Integration Tests**: Fluxos completos
- **Browser Tests**: Interface do usuário

## 🚀 Escalabilidade

### **Pontos de Escalabilidade**
1. **Cache**: Redis para sessões e cache
2. **Queue**: Processamento assíncrono
3. **CDN**: Assets estáticos
4. **Load Balancer**: Múltiplas instâncias
5. **Database**: Read replicas

### **Otimizações Implementadas**
- **Eager Loading**: Relacionamentos otimizados
- **Pagination**: Listagens paginadas
- **Lazy Loading**: Carregamento sob demanda
- **Asset Optimization**: Minificação de CSS/JS

## 📋 Conclusão

A arquitetura do **SkyFashion** foi projetada seguindo os princípios de:

✅ **Separação de Responsabilidades**: Cada camada tem sua função específica
✅ **Baixo Acoplamento**: Componentes independentes
✅ **Alta Coesão**: Funcionalidades relacionadas agrupadas
✅ **Escalabilidade**: Preparado para crescimento
✅ **Manutenibilidade**: Código organizado e documentado
✅ **Testabilidade**: Estrutura preparada para testes
✅ **Segurança**: Múltiplas camadas de proteção

Esta arquitetura permite que o sistema cresça de forma sustentável, mantendo a qualidade do código e facilitando a manutenção e evolução do projeto.

---

**Última atualização**: {{ date('d/m/Y H:i') }}
**Versão**: 1.0
**Projeto**: SkyFashion E-commerce
