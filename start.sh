#!/bin/bash

# Garante a criação de todas as pastas necessárias do Laravel
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/app/public
mkdir -p bootstrap/cache

# Garante permissões de escrita para a web
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Ajusta a porta do Apache para a porta dinâmica do Render
sed -i "s/80/${PORT:-80}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Limpa caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Executa as migrações no banco Neon
php artisan migrate --force

# Inicia o Apache
apache2-foreground