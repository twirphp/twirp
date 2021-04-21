# Main targets for a single-binary Go project
#
# A Self-Documenting Makefile: http://marmelab.com/blog/2016/02/29/auto-documented-makefile.html

OS = $(shell uname | tr A-Z a-z)
export PATH := $(abspath bin/):${PATH}

# Build variables
BUILD_DIR ?= build
VERSION ?= $(shell git describe --tags --exact-match 2>/dev/null || git symbolic-ref -q --short HEAD)
COMMIT_HASH ?= $(shell git rev-parse --short HEAD 2>/dev/null)
DATE_FMT = +%FT%T%z
ifdef SOURCE_DATE_EPOCH
    BUILD_DATE ?= $(shell date -u -d "@$(SOURCE_DATE_EPOCH)" "$(DATE_FMT)" 2>/dev/null || date -u -r "$(SOURCE_DATE_EPOCH)" "$(DATE_FMT)" 2>/dev/null || date -u "$(DATE_FMT)")
else
    BUILD_DATE ?= $(shell date "$(DATE_FMT)")
endif
LDFLAGS += -X main.version=${VERSION} -X main.commitHash=${COMMIT_HASH} -X main.buildDate=${BUILD_DATE}
export CGO_ENABLED ?= 0

# Dependency versions
PROTOC_VERSION = 3.15.8
TWIRP_VERSION = v5.12.1
GOLANGCI_VERSION = 1.38.0

.PHONY: build
build: ## Build binaries
	@mkdir -p ${BUILD_DIR}
	go build -mod=mod -trimpath -ldflags "${LDFLAGS}" -o ${BUILD_DIR}/ ./protoc-gen-twirp_php/

bin/golangci-lint: bin/golangci-lint-${GOLANGCI_VERSION}
	@ln -sf golangci-lint-${GOLANGCI_VERSION} bin/golangci-lint
bin/golangci-lint-${GOLANGCI_VERSION}:
	@mkdir -p bin
	curl -sfL https://install.goreleaser.com/github.com/golangci/golangci-lint.sh | BINARY=golangci-lint bash -s -- v${GOLANGCI_VERSION}
	@mv bin/golangci-lint $@

.PHONY: lint
lint: bin/golangci-lint ## Run linter
	bin/golangci-lint run

.PHONY: fix
fix: bin/golangci-lint ## Fix lint violations
	bin/golangci-lint run --fix

bin/protoc: bin/protoc-${PROTOC_VERSION}
	@ln -sf protoc-${PROTOC_VERSION}/bin/protoc bin/protoc
bin/protoc-${PROTOC_VERSION}:
	@mkdir -p bin/protoc-${PROTOC_VERSION}
ifeq (${OS}, darwin)
	curl -L https://github.com/protocolbuffers/protobuf/releases/download/v${PROTOC_VERSION}/protoc-${PROTOC_VERSION}-osx-x86_64.zip > bin/protoc.zip
endif
ifeq (${OS}, linux)
	curl -L https://github.com/protocolbuffers/protobuf/releases/download/v${PROTOC_VERSION}/protoc-${PROTOC_VERSION}-linux-x86_64.zip > bin/protoc.zip
endif
	unzip bin/protoc.zip -d bin/protoc-${PROTOC_VERSION}
	rm bin/protoc.zip

.PHONY: generate
generate: build bin/protoc ## Generate example and clientcompat files
	@mkdir -p example/generated
	@mkdir -p clientcompat/generated
	bin/protoc -I ./example/ --plugin=protoc-gen-twirp_php=build/protoc-gen-twirp_php --twirp_php_out=example/generated/ --php_out=example/generated/ service.proto
	bin/protoc -I ./clientcompat/ --plugin=protoc-gen-twirp_php=build/protoc-gen-twirp_php --twirp_php_out=clientcompat/generated/ --php_out=clientcompat/generated/ clientcompat.proto

bin/clientcompat: bin/clientcompat-${TWIRP_VERSION}
	@ln -sf clientcompat-${TWIRP_VERSION} bin/clientcompat
bin/clientcompat-${TWIRP_VERSION}:
	@mkdir -p bin
	@unlink bin/clientcompat || true
	GOBIN=${PWD}/bin/ go install github.com/twitchtv/twirp/clientcompat@${TWIRP_VERSION}
	mv bin/clientcompat bin/clientcompat-${TWIRP_VERSION}

clientcompat: build bin/protoc bin/clientcompat ## Run the client compatibility test suite
	@mkdir -p clientcompat/generated
	bin/protoc -I ./example/ --plugin=protoc-gen-twirp_php=build/protoc-gen-twirp_php --twirp_php_out=example/generated/ --php_out=example/generated/ service.proto
	bin/clientcompat -client clientcompat/compat.sh

.PHONY: help
.DEFAULT_GOAL := help
help:
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
