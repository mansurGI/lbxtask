#!/bin/bash

echo 'START OF INSTALLING'

echo 'docker upping'
docker compose up -d

echo 'compose installing'
docker exec fpm php /app/composer.phar install --quiet --no-interaction 

echo 'creating databases'
docker exec database mariadb -u root -proot --execute='create database app character set 'utf8mb4';'
docker exec database mariadb -u root -proot --execute='create database test character set 'utf8mb4';'

echo 'migrating migrations to databases'
docker exec fpm php /app/bin/console doctrine:migrations:migrate --env=dev --no-interaction

