FROM golang:1.10 as builder

RUN go get github.com/twitchtv/twirp/example/cmd/server


FROM ubuntu:xenial

COPY --from=builder /go/bin/server /usr/bin

CMD ["server"]

EXPOSE 8080
