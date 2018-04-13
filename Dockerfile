FROM quay.io/twirphp/build

WORKDIR /go/src/github.com/twirphp/twirp

COPY . .

RUN set -xe \
    && composer install \
    && dep ensure -vendor-only

CMD ["echo", "Please see the readme for help"]
