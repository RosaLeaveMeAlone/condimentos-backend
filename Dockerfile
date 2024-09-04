FROM php:8.2-apache

# Copy composer.lock and composer.json
#COPY composer.lock composer.json /var/www/html
RUN rm /etc/apache2/sites-available/000-default.conf
COPY 000-default.conf /etc/apache2/sites-available/

COPY ./php.ini "$PHP_INI_DIR/"


# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libwebp-dev \
    libzip-dev \
    libxml2-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle libpq-dev \
    curl \
    tzdata


# TZDATA
RUN ln -fs /usr/share/zoneinfo/America/Santiago /etc/localtime
RUN dpkg-reconfigure -f noninteractive tzdata
RUN echo 'America/Caracas' > /etc/timezone

RUN a2enmod rewrite

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
# RUN docker-php-ext-install mysqli pdo_mysql mbstring zip exif pcntl simplexml intl
#RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip exif pcntl simplexml intl
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

RUN composer install

# RUN php artisan install:broadcasting

RUN chown www-data:www-data -R storage/
RUN chmod 755 -R storage/

