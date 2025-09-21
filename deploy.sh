#!/bin/bash

# Script de Deploy para SkyFashion no DigitalOcean
# Execute este script no servidor após fazer upload dos arquivos

set -e

echo "🚀 Iniciando deploy do SkyFashion..."

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Função para log
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

error() {
    echo -e "${RED}[ERROR] $1${NC}"
    exit 1
}

warning() {
    echo -e "${YELLOW}[WARNING] $1${NC}"
}

# Verificar se está rodando como root
if [[ $EUID -eq 0 ]]; then
   error "Este script não deve ser executado como root. Use um usuário com sudo."
fi

# Definir variáveis
PROJECT_DIR="/var/www/loja"
BACKUP_DIR="/var/backups/loja"
NGINX_SITES="/etc/nginx/sites-available"
NGINX_ENABLED="/etc/nginx/sites-enabled"

log "Configurando diretórios..."

# Criar diretórios necessários
sudo mkdir -p $PROJECT_DIR
sudo mkdir -p $BACKUP_DIR
sudo mkdir -p /var/log/skyfashion

# Definir permissões
sudo chown -R www-data:www-data $PROJECT_DIR
sudo chmod -R 755 $PROJECT_DIR

log "Instalando dependências do sistema..."

# Atualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar dependências necessárias
sudo apt install -y \
    nginx \
    mysql-server \
    php8.2-fpm \
    php8.2-mysql \
    php8.2-xml \
    php8.2-gd \
    php8.2-curl \
    php8.2-zip \
    php8.2-mbstring \
    php8.2-bcmath \
    php8.2-intl \
    php8.2-redis \
    redis-server \
    supervisor \
    git \
    curl \
    unzip \
    nodejs \
    npm \
    certbot \
    python3-certbot-nginx

log "Configurando PHP..."

# Configurar PHP-FPM
sudo sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 100M/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/post_max_size = 8M/post_max_size = 100M/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/max_execution_time = 30/max_execution_time = 300/' /etc/php/8.2/fpm/php.ini

# Reiniciar PHP-FPM
sudo systemctl restart php8.2-fpm
sudo systemctl enable php8.2-fpm

log "Configurando MySQL..."

# Configurar MySQL
sudo mysql_secure_installation

# Criar banco de dados e usuário
sudo mysql -e "CREATE DATABASE IF NOT EXISTS loja CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'loja_user'@'localhost' IDENTIFIED BY 'SENHA_FORTE_AQUI';"
sudo mysql -e "GRANT ALL PRIVILEGES ON loja.* TO 'loja_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

log "Configurando Redis..."

# Configurar Redis
sudo systemctl enable redis-server
sudo systemctl start redis-server

log "Configurando Nginx..."

# Criar configuração do Nginx
sudo tee $NGINX_SITES/loja > /dev/null <<EOF
server {
    listen 80;
    server_name seudominio.com www.seudominio.com;
    root $PROJECT_DIR/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Otimizações para assets
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
}
EOF

# Habilitar site
sudo ln -sf $NGINX_SITES/loja $NGINX_ENABLED/
sudo rm -f $NGINX_ENABLED/default

# Testar configuração do Nginx
sudo nginx -t

# Reiniciar Nginx
sudo systemctl restart nginx
sudo systemctl enable nginx

log "Configurando Supervisor para queues..."

# Criar configuração do Supervisor
sudo tee /etc/supervisor/conf.d/loja-worker.conf > /dev/null <<EOF
[program:loja-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $PROJECT_DIR/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=$PROJECT_DIR/storage/logs/worker.log
stopwaitsecs=3600
EOF

# Recarregar Supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start loja-worker:*

log "Configurando SSL com Let's Encrypt..."

# Instalar certificado SSL (substitua seudominio.com pelo seu domínio)
warning "Configure seu domínio antes de executar o comando abaixo:"
echo "sudo certbot --nginx -d seudominio.com -d www.seudominio.com"

log "Configurando permissões finais..."

# Configurar permissões do Laravel
sudo chown -R www-data:www-data $PROJECT_DIR
sudo chmod -R 755 $PROJECT_DIR
sudo chmod -R 775 $PROJECT_DIR/storage
sudo chmod -R 775 $PROJECT_DIR/bootstrap/cache

log "✅ Deploy do servidor concluído!"
log "📋 Próximos passos:"
echo "1. Faça upload dos arquivos do projeto para $PROJECT_DIR"
echo "2. Configure o arquivo .env com suas credenciais"
echo "3. Execute: cd $PROJECT_DIR && composer install --no-dev --optimize-autoloader"
echo "4. Execute: cd $PROJECT_DIR && npm install && npm run build"
echo "5. Execute: cd $PROJECT_DIR && php artisan key:generate"
echo "6. Execute: cd $PROJECT_DIR && php artisan migrate --force"
echo "7. Execute: cd $PROJECT_DIR && php artisan config:cache"
echo "8. Execute: cd $PROJECT_DIR && php artisan route:cache"
echo "9. Execute: cd $PROJECT_DIR && php artisan view:cache"
echo "10. Configure o certificado SSL com: sudo certbot --nginx -d seudominio.com"

log "🎉 Servidor configurado com sucesso!"
