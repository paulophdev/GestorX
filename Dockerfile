FROM php:8.2-fpm

# Argumentos definidos no docker-compose.yml
ARG user=myappuser
ARG uid=1000

# Instalando dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Limpando cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalando extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Obtendo Composer mais recente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criando usuário do sistema para executar comandos Composer e Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Configurando diretório de trabalho
WORKDIR /var/www

# Copia o código do projeto
COPY . /var/www

# Ajusta permissões
RUN chown -R $user:$user /var/www

# Instala as dependências do Composer
RUN composer install --no-dev --optimize-autoloader

# Copia o script de inicialização
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

USER $user

ENTRYPOINT ["/start.sh"]