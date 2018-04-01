#!/bin/bash

mkdir -p src
go build -o ../build/protoc-gen-twirp_php ..
protoc -I . --plugin=protoc-gen-twirp_php=../build/protoc-gen-twirp_php --twirp_php_out=src/ --php_out=src/ service.proto
