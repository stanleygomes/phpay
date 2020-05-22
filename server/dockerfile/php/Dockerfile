FROM php:7.3.13-apache

RUN apt-get -y update --fix-missing

# Install tools & libraries
RUN apt-get -y install --no-install-recommends apt-utils nano wget dialog \
    build-essential git curl zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install important libraries
RUN apt-get -y install --no-install-recommends --fix-missing apt-utils build-essential git curl \
    # libmcrypt-dev libsqlite3-dev libsqlite3-0 \
    # libicu-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# PHP Extensions
RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli
    # \
    # && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    # && docker-php-ext-install -j$(nproc) gd

# Enable apache modules
RUN a2enmod rewrite headers

# permissions to apache folder
RUN chmod -R 0777 /var/www/html

# RUN cd /var/www/html && composer install

ENTRYPOINT ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
