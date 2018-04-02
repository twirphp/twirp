#!/bin/bash

mkdir -p generated
go build -o ../build/protoc-gen-twirp_php ..
protoc -I . --plugin=protoc-gen-twirp_php=../build/protoc-gen-twirp_php --twirp_php_out=generated/ --php_out=generated/ service.proto
