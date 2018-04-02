FROM golang:1.10 as builder

RUN go get github.com/twitchtv/twirp/clientcompat

FROM php:7.2-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends git zip \
    && docker-php-ext-install -j$(nproc) bcmath

COPY --from=builder /go/bin/clientcompat /usr/bin

WORKDIR /app

RUN curl --silent --show-error https://getcomposer.org/installer | php

COPY . /app

RUN php composer.phar install

CMD ["clientcompat", "-client", "./compat.sh"]
