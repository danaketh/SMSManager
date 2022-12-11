FROM php:8.2-rc-alpine3.17
LABEL maintainer="daniel@tlach.cz"

## Install dependencies
RUN apk add --no-cache \
    bash \
    git

## Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -Lo /usr/local/bin/local-php-security-checker https://github.com/fabpot/local-php-security-checker/releases/download/v1.0.0/local-php-security-checker_1.0.0_linux_amd64 && chmod +x /usr/local/bin/local-php-security-checker
WORKDIR /var/www/html
RUN composer install -y
