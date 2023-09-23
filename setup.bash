#!/bin/bash

docker compose up -d

docker exec fpm php /app/composer.phar install --quiet --no-interaction 

docker exec mariadb mysql -u root -proot --execute='create database app character set 'utf8mb4';'
docker exec mariadb mysql -u root -proot --execute='create database test character set 'utf8mb4';'

docker exec fpm php /app/bin/console doctrine:migrations:migrate --env=dev --no-interaction

