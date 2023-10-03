FROM docker.io/bitname/php-fpm:8.2
RUN apt-get update && apt-get install -y autoconf build-essential librabbitmq-dev
RUN pecl install amqp