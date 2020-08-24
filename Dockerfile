FROM php:7.4-fpm

RUN mv /etc/localtime /etc/localtime.bak \
    && ln -s /usr/share/zoneinfo/Europe/London /etc/localtime

# -----------------------------------------------------------------------------
# COMPOSER INSTALLATION
# -----------------------------------------------------------------------------
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# -----------------------------------------------------------------------------
# SERVICES & PHP EXTENSIONS
# -----------------------------------------------------------------------------
RUN \
    apt-get update && \
    apt-get install -y \
        vim \
        zip \
        libzip-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install opcache \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug;

RUN echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY /php/config /var/www/config
COPY appcode /var/www/html

COPY php/setupAws.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/setupAws.sh;
COPY php/run.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/run.sh;

VOLUME /var/www/

CMD ["/usr/local/bin/run.sh"]
