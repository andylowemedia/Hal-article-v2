#!/bin/bash

mkdir ~/.aws && /usr/local/bin/setupAws.sh > ~/.aws/credentials
mkdir /var/www/.aws && /usr/local/bin/setupAws.sh > /var/www/.aws/credentials

cp "/var/www/config/$APP_ENV/php-settings.ini" "/usr/local/etc/php/conf.d/docker-php-env-settings.ini"


if [ "$APP_ENV" == "production" ]; then
    if [ -e /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ]; then
        rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
    fi
elif [ "$APP_ENV" == "staging" ]; then
    echo ""
    if [ -e /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ]; then
        rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
    fi
else
    echo ""
fi

echo 'Install Composer Dependencies'

if [ "$APP_ENV" != "development" ]; then
    echo "No development dependencies"
    composer install --no-interaction --verbose --no-dev
else
    composer install --no-interaction --verbose
fi

exec php-fpm
