#!/bin/bash

cd /home/metraj.porfolio.space || exit

git pull origin main

/usr/bin/php artisan down
/usr/bin/composer install --no-interaction --prefer-dist --optimize-autoloader

/usr/bin/php artisan migrate --force
/usr/bin/php artisan config:cache
/usr/bin/php artisan route:cache
/usr/bin/php artisan view:cache
/usr/bin/php artisan up

echo "Deployed at $(date)" >> /home/metraj.porfolio.space/deploy.log
