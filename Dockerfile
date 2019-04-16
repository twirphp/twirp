FROM quay.io/twirphp/build

WORKDIR /workspace

ENV CGO_ENABLED 0

COPY go.mod go.sum /workspace/
RUN go mod download

COPY . .
RUN composer install

CMD ["echo", "Please see the readme for help"]
