# Define Image to be used
FROM php:8.2

# Install necessary packages for PHP to run
RUN apt update \
    && apt install -y zip libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Make new working directory for application
RUN mkdir /var/www/scraper
WORKDIR /var/www/scraper

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Composer and install dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY composer.json /var/www/scraper
COPY composer.lock /var/www/scraper
RUN composer install --no-progress --no-interaction --no-suggest --no-scripts

# Copy contents of current workspace into working directory
COPY . /var/www/scraper
