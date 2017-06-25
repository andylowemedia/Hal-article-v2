#!/bin/bash

# cd /var/www/html/
#
# composer install --no-interaction --verbose

# cp /var/www/html/config/autoload/database.local.php.dist /var/www/html/config/autoload/database.local.php
# 
# if [ "$DB_HOST" ]; then
#     sed -i "s/#{DB_HOST}#/$DB_HOST/" "/var/www/html/config/autoload/database.local.php"
# fi
# 
# if [ "$DB_PORT" ]; then
#     sed -i "s/#{DB_PORT}#/$DB_PORT/" "/var/www/html/config/autoload/database.local.php"
# fi
# 
# if [ "$DB_NAME" ]; then
#     sed -i "s/#{DB_NAME}#/$DB_NAME/" "/var/www/html/config/autoload/database.local.php"
# fi
# 
# if [ "$DB_USER" ]; then
#     sed -i "s/#{DB_USER}#/$DB_USER/" "/var/www/html/config/autoload/database.local.php"
# fi
# 
# if [ "$DB_PASS" ]; then
#     sed -i "s/#{DB_PASS}#/$DB_PASS/" "/var/www/html/config/autoload/database.local.php"
# fi

exec php-fpm



