FROM php:8.2-cli

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libpq-dev \
    && docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

COPY . .

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]
