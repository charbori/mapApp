#!/bin/sh
php-fpm
cd /var/www/html
php -d variables_order=EGPCS /var/www/html/artisan serve --host=0.0.0.0 --port=8080