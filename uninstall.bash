#!/bin/bash

docker compose down

docker rmi bitnami/ngin bitnami/php-fpm mariadb

