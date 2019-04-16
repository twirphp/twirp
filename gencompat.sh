#!/bin/bash

GIT_VERSION=$(git rev-parse --abbrev-ref HEAD)

mkdir -p clientcompat/generated
go build -ldflags "-w -X main.version=${GIT_VERSION}" -o build/protoc-gen-twirp_php ./protoc-gen-twirp_php
protoc -I ./clientcompat/ --plugin=protoc-gen-twirp_php=build/protoc-gen-twirp_php --twirp_php_out=clientcompat/generated/ --php_out=clientcompat/generated/ clientcompat.proto
