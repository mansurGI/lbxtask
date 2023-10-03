#!/bin/bash

docker compose down

docker rmi bitnami/nginx php-fpm-custom mariadb bitnami/rabbitmq

