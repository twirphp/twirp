# Main targets for a single-binary Go project
#
# A Self-Documenting Makefile: http://marmelab.com/blog/2016/02/29/auto-documented-makefile.html

OS = $(shell uname | tr A-Z a-z)
export PATH := $(abspath bin/protoc/bin/):$(abspath bin/):${PATH}

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

.PHONY: build
build: ## Build binaries
	@mkdir -p ${BUILD_DIR}
	go build -mod=mod -trimpath -ldflags "${LDFLAGS}" -o ${BUILD_DIR}/ ./protoc-gen-twirp_php/

.PHONY: check
check: test lint ## Run checks (tests and linters)

.PHONY: test
test: TEST_FORMAT ?= short
test: export CGO_ENABLED=1
test: ## Run tests
	gotestsum --no-summary=skipped --junitfile ${BUILD_DIR}/coverage-go.xml --jsonfile ${BUILD_DIR}/test.json --format ${TEST_FORMAT} -- -race -coverprofile=${BUILD_DIR}/coverage-go.txt -covermode=atomic ./protoc-gen-twirp_php/...
	XDEBUG_MODE=coverage lib/vendor/bin/phpunit -v --coverage-clover ${BUILD_DIR}/coverage-php.xml
	lib/vendor/bin/phpunit -v --group example
	$(MAKE) clientcompat

.PHONY: lint
lint: ## Run linter
	golangci-lint run ${LINT_ARGS}

.PHONY: generate
generate: build ## Generate example and clientcompat files
	@mkdir -p example/generated
	@mkdir -p clientcompat/generated
	protoc -I ./example/ --plugin=protoc-gen-twirp_php=build/protoc-gen-twirp_php --twirp_php_out=example/generated/ --php_out=example/generated/ service.proto
	protoc -I ./clientcompat/ --plugin=protoc-gen-twirp_php=build/protoc-gen-twirp_php --twirp_php_out=clientcompat/generated/ --php_out=clientcompat/generated/ clientcompat.proto

clientcompat: build ## Run the client compatibility test suite
	@mkdir -p clientcompat/generated
	protoc -I ./example/ --plugin=protoc-gen-twirp_php=build/protoc-gen-twirp_php --twirp_php_out=example/generated/ --php_out=example/generated/ service.proto
	clientcompat -client clientcompat/compat.sh

# Dependency versions
TWIRP_VERSION = v8.1.0
PROTOC_VERSION = 3.15.8
GOTESTSUM_VERSION = 1.7.0
GOLANGCI_VERSION = 1.38.0

deps: bin/clientcompat bin/gotestsum bin/golangci-lint bin/protoc

bin/clientcompat:
	@mkdir -p bin
	@unlink bin/clientcompat || true
	GOBIN=${PWD}/bin/ go install github.com/twitchtv/twirp/clientcompat@${TWIRP_VERSION}

bin/gotestsum:
	@mkdir -p bin
	curl -L https://github.com/gotestyourself/gotestsum/releases/download/v${GOTESTSUM_VERSION}/gotestsum_${GOTESTSUM_VERSION}_$(shell uname | tr A-Z a-z)_amd64.tar.gz | tar -zOxf - gotestsum > ./bin/gotestsum
	@chmod +x ./bin/gotestsum

bin/golangci-lint:
	@mkdir -p bin
	curl -sfL https://install.goreleaser.com/github.com/golangci/golangci-lint.sh | BINARY=golangci-lint bash -s -- v${GOLANGCI_VERSION}

bin/protoc:
	@mkdir -p bin/protoc
ifeq ($(shell uname | tr A-Z a-z), darwin)
	curl -L https://github.com/protocolbuffers/protobuf/releases/download/v${PROTOC_VERSION}/protoc-${PROTOC_VERSION}-osx-x86_64.zip > bin/protoc.zip
endif
ifeq ($(shell uname | tr A-Z a-z), linux)
	curl -L https://github.com/protocolbuffers/protobuf/releases/download/v${PROTOC_VERSION}/protoc-${PROTOC_VERSION}-linux-x86_64.zip > bin/protoc.zip
endif
	unzip bin/protoc.zip -d bin/protoc
	rm bin/protoc.zip

.PHONY: help
.DEFAULT_GOAL := help
help:
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
