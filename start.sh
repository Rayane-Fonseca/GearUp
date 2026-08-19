#!/bin/bash

# Ajusta a porta do Apache para a porta dinâmica do Render
sed -i "s/80/${PORT:-80}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Limpa qualquer cache de configuração antigo para garantir a leitura do .env do Render
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Executa as migrações do banco de dados no Neon
php artisan migrate --force

# Inicia o Apache no primeiro plano
apache2-foreground