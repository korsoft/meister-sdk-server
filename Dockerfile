FROM php:7.2-fpm
RUN apt-get update -y && apt-get install -y openssl zip unzip git libxml2-dev 
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring soap xml
WORKDIR /app
COPY . /app
RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=80
EXPOSE 80
