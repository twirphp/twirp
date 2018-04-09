FROM quay.io/twirphp/go-dependencies as godeps

FROM quay.io/twirphp/php-base

COPY --from=godeps /go/bin/clientcompat /usr/bin
COPY --from=godeps /go/bin/client /usr/bin
COPY --from=godeps /go/bin/server /usr/bin

COPY . /app

RUN php composer.phar install

CMD ["echo", "Please see the readme for help"]
