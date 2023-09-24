#!/bin/bash

docker compose down

docker rmi bitnami/nginx bitnami/php-fpm mariadb

