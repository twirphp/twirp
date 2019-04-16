FROM quay.io/twirphp/build

WORKDIR /workspace

ENV CGO_ENABLED 0

COPY . .

RUN composer install
RUN go mod download

CMD ["echo", "Please see the readme for help"]
