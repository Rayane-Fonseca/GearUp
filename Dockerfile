FROM php:8.2-apache

# Instala dependências do sistema e o Node.js (necessário para compilar o Tailwind/Vite)
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libpq-dev libicu-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip intl \
    && a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Instala dependências do PHP
RUN composer install --optimize-autoloader --no-dev

# Instala dependências do Node e gera a pasta public/build (Vite/Tailwind)
RUN npm install && npm run build

# Garante que nenhum arquivo "hot" de dev (npm run dev) vá para produção,
# senão o Laravel tenta carregar os assets do servidor Vite local
RUN rm -f public/hot

RUN chown -R www-data:www-data storage bootstrap/cache

# Aponta a raiz do Apache para a pasta public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/apache2.conf

COPY start.sh /start.sh
RUN chmod +x /start.sh
EXPOSE 80
CMD ["/start.sh"]