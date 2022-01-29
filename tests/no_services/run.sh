#!/bin/bash

set -e

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR

# Preparation
rm -rf generated/
mkdir generated

# Generate code
protoc --plugin=protoc-gen-twirp_php=${PLUGIN_BUILD_DIR:-../../build}/protoc-gen-twirp_php --twirp_php_out=./generated/ --php_out=./generated/ *.proto

# Test
test ! -f generated/Twirp/Tests/No_services/Proto/TwirpError.php || (echo "TwirpError.php should not be generated when there are no services defined in any of the proto files"; exit 1)
