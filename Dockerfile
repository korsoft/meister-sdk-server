FROM ubuntu:18.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -yq --no-install-recommends \
    apt-utils \
    curl \
    # Install git
    git \
    # Install apache
    apache2 \
    # Install php 7.2
    libapache2-mod-php7.2 \
    php7.2-cli \
    php7.2-curl \
    php7.2-fpm \
    php7.2-gd \
    php7.2-mbstring \
    php7.2-sqlite3 \
    php7.2-xml \
    # Install tools
    nano \
    locales \
    sqlite3 \
    composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set locales
RUN locale-gen en_US.UTF-8 en_GB.UTF-8 de_DE.UTF-8 es_ES.UTF-8 fr_FR.UTF-8 it_IT.UTF-8 km_KH sv_SE.UTF-8 fi_FI.UTF-8

# COPY meister-sdk 
ADD meister-sdk-server /var/www/meister-sdk-server

WORKDIR /var/www/meister-sdk-server
RUN composer install
COPY meister.sqlite /var/www/meister-sdk-server/storage/databases/meister.sqlite
RUN chmod -R 755 /var/www
RUN chmod -R ug+rwx /var/www/meister-sdk-server/storage/ 
RUN chgrp -R www-data /var/www/meister-sdk-server/storage/ /var/www/meister-sdk-server/bootstrap/cache/
RUN chown :www-data /var/www/meister-sdk-server/storage/databases/meister.sqlite

# Configure PHP for meister-sdk
COPY meister-sdk.ini /etc/php/7.2/mods-available/
RUN phpenmod meister-sdk
# Configure apache for meister-sdk
RUN echo "ServerName localhost" | tee /etc/apache2/conf-available/servername.conf
RUN a2enconf servername
# Configure vhost for meister-sdk
COPY meister-sdk.conf /etc/apache2/sites-available/
RUN a2ensite meister-sdk.conf
RUN a2dissite 000-default
RUN a2enmod rewrite

EXPOSE 80

#WORKDIR /var/www/html

#RUN rm index.html

RUN service apache2 restart

HEALTHCHECK --interval=5s --timeout=3s --retries=3 CMD curl -f http://localhost || exit 1

CMD apachectl -D FOREGROUND 