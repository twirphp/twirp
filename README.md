# PHP code generator for [Twirp](https://twitchtv.github.io/twirp/)

[![Build Status](https://img.shields.io/travis/twirphp/protoc-gen-twirp_php.svg?style=flat-square)](https://travis-ci.org/twirphp/protoc-gen-twirp_php)
[![Twirp Version](http://img.shields.io/badge/twirp--version-v5.3.0-orange.svg?style=flat-square)](https://godoc.org/github.com/twirphp/protoc-gen-twirp_php)
[![Go Report Card](https://goreportcard.com/badge/github.com/twirphp/protoc-gen-twirp_php?style=flat-square)](https://goreportcard.com/report/github.com/twirphp/protoc-gen-twirp_php)
[![GoDoc](http://img.shields.io/badge/godoc-reference-5272B4.svg?style=flat-square)](https://godoc.org/github.com/twirphp/protoc-gen-twirp_php)

**Work in progress! First estimated preview: end of April**

## Installation

Download the [latest](https://github.com/twirphp/protoc-gen-twirp_php/releases/latest) release from the Releases page
and put it into your `$PATH` prefix (or any location, but that requires some configuration, see later).

This is a [protoc](https://github.com/golang/protobuf), so you will have to install that as well.

Alternatively you can manually download and build the project. For that, you are going to need [dep](https://golang.github.io/dep/).

```bash
$ go get github.com/twirphp/protoc-gen-twirp_php
$ cd $GOROOT/src/github.com/twirphp/protoc-gen-twirp_php
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


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
