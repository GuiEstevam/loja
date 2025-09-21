#!/bin/bash

# Script de Deploy Simplificado para SkyFashion
# Execute este script após fazer upload dos arquivos para o servidor

set -e

echo "🚀 Deploy Simplificado do SkyFashion"
echo "====================================="

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log() {
    echo -e "${GREEN}[$(date +'%H:%M:%S')] $1${NC}"
}

warning() {
    echo -e "${YELLOW}[AVISO] $1${NC}"
}

# Verificar se está no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: Execute este script no diretório raiz do projeto Laravel"
    exit 1
fi

log "Instalando dependências PHP..."
composer install --no-dev --optimize-autoloader

log "Instalando dependências Node.js..."
npm install

log "Compilando assets..."
npm run build

log "Configurando Laravel..."

# Verificar se .env existe
if [ ! -f ".env" ]; then
    warning "Arquivo .env não encontrado. Copiando de env.example..."
    cp env.example .env
    warning "⚠️  IMPORTANTE: Configure o arquivo .env antes de continuar!"
    echo "Execute: nano .env"
    echo "Depois execute este script novamente."
    exit 1
fi

log "Gerando chave da aplicação..."
php artisan key:generate

log "Executando migrações..."
php artisan migrate --force

log "Criando caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

log "Configurando permissões..."
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

log "✅ Deploy concluído com sucesso!"
echo ""
echo "📋 Próximos passos:"
echo "1. Configure o arquivo .env com suas credenciais"
echo "2. Configure o Nginx (use nginx-skyfashion.conf)"
echo "3. Configure o Supervisor (use supervisor-skyfashion.conf)"
echo "4. Instale SSL com: sudo certbot --nginx -d seudominio.com"
echo ""
echo "🔍 Para verificar se está funcionando:"
echo "sudo systemctl status nginx"
echo "sudo systemctl status php8.2-fpm"
echo "sudo systemctl status mysql"
