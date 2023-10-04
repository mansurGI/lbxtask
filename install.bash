#!/bin/bash

echo 'START OF INSTALLING'

echo 'building docker'
cd fpm || exit
# add --quite
docker build --tag php-fpm-custom:latest .
cd ..

echo 'docker up'
docker compose up -d

echo 'composer install'
docker exec fpm php /app/composer.phar install --quiet --no-interaction 

echo 'creating databases'
docker exec database mariadb -u root -proot --execute='create database if not exists app character set 'utf8mb4';'
docker exec database mariadb -u root -proot --execute='create database if not exists test character set 'utf8mb4';'

echo 'migrating migrations to databases'
docker exec fpm php /app/bin/console doctrine:migrations:migrate --env=dev --no-interaction

