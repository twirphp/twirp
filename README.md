# PHP port of [Twirp](https://twitchtv.github.io/twirp/)

[![CircleCI](https://circleci.com/gh/twirphp/twirp.svg?style=svg)](https://circleci.com/gh/twirphp/twirp)
[![Twirp Version](http://img.shields.io/badge/twirp%20version-v5.3.0-orange.svg?style=flat-square)](https://github.com/twitchtv/twirp/releases/tag/v5.3.0)
[![Go Report Card](https://goreportcard.com/badge/github.com/twirphp/twirp?style=flat-square)](https://goreportcard.com/report/github.com/twirphp/twirp)
[![GoDoc](http://img.shields.io/badge/godoc-reference-5272B4.svg?style=flat-square)](https://godoc.org/github.com/twirphp/twirp)
[![Quality Score](https://img.shields.io/scrutinizer/g/twirphp/twirp.svg?style=flat-square)](https://scrutinizer-ci.com/g/twirphp/twirp)
[![Composer Package](http://img.shields.io/badge/composer-twirp%2Ftwirp-green.svg?style=flat-square)](https://packagist.org/packages/twirp/twirp)

**Work in progress! First estimated preview: end of April**


## Installation

Download the [latest](https://github.com/twirphp/twirp/releases/latest) release from the Releases page
and put it into your `$PATH` prefix (or any location, but that requires some configuration, see later).

This is a [protoc](https://github.com/golang/protobuf), so you will have to install that as well.

Alternatively you can manually download and build the project. For that, you are going to need [dep](https://golang.github.io/dep/).

```bash
$ go get github.com/twirphp/twirp/protoc-gen-twirp_php
$ cd $GOROOT/src/github.com/twirphp/twirp/protoc-gen-twirp_php
$ dep ensure
$ go install
```


## Usage

Use it like any protoc plugin to generate both server and client code:

```bash
$ protoc -I example/ --php_out . --twirp_php . service.proto
```

If you downloaded the generator outside of your `$PATH` prefix, you need to manually pass the location to the compiler:

```bash
$ protoc -I example/ --plugin=protoc-gen-twirp_php=path/to/protoc-gen-twirp_php --php_out . --twirp_php . service.proto
```


### Server

In order to use the server you have to install some dependencies in your PHP project via [Composer](https://getcomposer.org/):

```bash
$ composer require twirp/twirp
```

You need to choose a [PSR-7](http://www.php-fig.org/psr/psr-7/) implementation along with it's [factory](https://github.com/php-http/message-factory):

```bash
$ composer require guzzlehttp/psr7 php-http/message
```

The code generator creates a service interface which you need to implement.

See the [example](example) directory for the implementation details.


### Client

In order to use the client you have to install some dependencies in your PHP project via [Composer](https://getcomposer.org/):

```bash
$ composer require twirp/twirp
```

You need to choose a [PSR-7](http://www.php-fig.org/psr/psr-7/) implementation along with it's [factory](https://github.com/php-http/message-factory) and an [HTTPlug](https://packagist.org/providers/php-http/client-implementation) compatible client:

```bash
$ composer require guzzlehttp/psr7 php-http/message php-http/guzzle6-adapter
```

See the [example](example) directory for the implementation details.


## Example

To use the example and run certain test suites you need to build a Docker image from this directory:

```bash
$ docker build -t twirphp .
```


## Tests

The following set of commands runs the complete test suite for the project:

```bash
$ docker run --rm -it twirphp go test -v ./protoc-gen-twirp_php/...
$ docker run --rm -it twirphp vendorphp/bin/phpunit -v
$ docker run --rm -it twirphp ./gen.sh
$ docker run --rm -it twirphp vendorphp/bin/phpunit -v --group example
$ docker run --rm -it twirphp clientcompat -client clientcompat/compat.sh
```


## Security

If you discover any security related issues, please contact us at [mark.sagikazar+twirphp@gmail.com](mailto:mark.sagikazar+twirphp@gmail.com).


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

The original Twirp library is licensed under the Apache 2.0 License.
