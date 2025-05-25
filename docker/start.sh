#!/bin/bash

# Verifica se a pasta vendor existe; se não, instala as dependências
if [ ! -d "/var/www/vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader
fi

# Executa os comandos Artisan, se necessário
php artisan key:generate
php artisan config:cache
php artisan migrate --force
# php artisan storage:link

# Verifica o ambiente e inicia o servidor apropriado
if [ "$APP_ENV" = "local" ]; then
    echo "Ambiente de desenvolvimento detectado. Iniciando servidor de desenvolvimento..."
    exec php artisan serve --host=0.0.0.0 --port=10000
else
    echo "Ambiente de produção detectado. Iniciando PHP-FPM..."
    exec php-fpm
fi