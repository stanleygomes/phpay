FROM php:7.3.13-apache

RUN apt-get -y update --fix-missing

# Install tools & libraries
RUN apt-get -y install --no-install-recommends apt-utils nano wget dialog \
    build-essential git curl zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install important libraries
RUN apt-get -y update && apt-get -y install --no-install-recommends --fix-missing apt-utils build-essential git curl \
    libfreetype6-dev libpng-dev libjpeg62-turbo-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl https://getcomposer.org/composer-stable.phar --output /usr/bin/composer
RUN chmod +x /usr/bin/composer

# PHP Extensions
RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install gd

# Install debug
RUN pecl install xdebug-2.9.1 \
    && docker-php-ext-enable xdebug

# Enable apache modules
RUN a2enmod rewrite headers

# permissions to apache folder
RUN chmod -R 0777 /var/www/html

# RUN cd /var/www/html && composer install

ENTRYPOINT ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
