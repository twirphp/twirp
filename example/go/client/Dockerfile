FROM golang:1.10 as builder

RUN set -xe \
    && go get github.com/twitchtv/twirp/example/cmd/client \
    && sed -i -e 's|"http://localhost:8080"|os.Args[1]|g' /go/src/github.com/twitchtv/twirp/example/cmd/client/main.go \
    && sed -i -e 's|"net/http"|"net/http"\n"os"|g' /go/src/github.com/twitchtv/twirp/example/cmd/client/main.go \
    && go install github.com/twitchtv/twirp/example/cmd/client


FROM ubuntu:xenial

COPY --from=builder /go/bin/client /usr/bin

CMD ["client"]
