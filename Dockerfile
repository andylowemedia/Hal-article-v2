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
        zlib1g-dev \
        libicu-dev \
        g++ \
        vim \
#        libmcrypt-dev \
#        php-pear \
#        curl \
        wget \
        git \
        zip \
        cron \
        libzip-dev \
        libxml2-dev \
        libmemcached-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
#    && docker-php-ext-install mcrypt \
    && docker-php-ext-install intl \
    && docker-php-ext-install opcache \
    && docker-php-ext-install zip \
#    && docker-php-ext-install xml\
    && docker-php-ext-install pcntl \
    && pecl install redis \
    && pecl install memcached \
    && pecl install xdebug \
    && docker-php-ext-enable redis memcached xdebug;

COPY /php/config /var/www/config
COPY appcode /var/www/html

COPY php/run.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/run.sh;

VOLUME /var/www/

CMD ["/usr/local/bin/run.sh"]
