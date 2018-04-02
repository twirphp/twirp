FROM php:7.2-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends git zip unzip zlib1g-dev \
    && docker-php-ext-install -j$(nproc) bcmath zip

WORKDIR /app

RUN curl --silent --show-error https://getcomposer.org/installer | php

COPY . /app

RUN php composer.phar install

CMD echo "Usage for server: docker run --rm -it twirphpexample php -S 0.0.0.0:8080 server.php\nUsage for client: docker run --rm -it twirphpexample php client.php http://localhost:8080]"

EXPOSE 8080
