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
php artisan storage:link

# Inicia o servidor
php artisan serve --host=0.0.0.0 --port=10000