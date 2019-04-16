FROM quay.io/twirphp/build

WORKDIR /workspace

COPY . .

RUN composer install
RUN go mod download

CMD ["echo", "Please see the readme for help"]
