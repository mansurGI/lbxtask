FROM bitnami/php-fpm:latest
RUN apt-get update && apt-get install -y autoconf build-essential librabbitmq-dev
RUN pecl install amqp

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php