FROM php:7.4.8-fpm

ARG XDEBUG_VERSION=xdebug-2.9.6

#in order to install soap, provisional fix
RUN rm /etc/apt/preferences.d/no-debian-php
RUN apt-get update && apt-get install -my procps acl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Install xdebug
RUN pecl install ${XDEBUG_VERSION} && docker-php-ext-enable xdebug
RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.idekey="PHPSTORM"' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.profiler_enable=0' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.profiler_enable_trigger=1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.profiler_output_dir="/var/www/data/profiler"' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.profiler_enable_trigger="cachegrind.out.%p"' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini



