#!/bin/bash

./gen.sh
docker build -t clientcompat .
docker run --rm -it clientcompat
