package example

//go:generate mkdir -p src
//go:generate go build -o ../build/protoc-gen-twirp_php ..
//go:generate protoc -I . --plugin=protoc-gen-twirp_php=../build/protoc-gen-twirp_php --twirp_php_out=src/ --php_out=src/ service.proto
