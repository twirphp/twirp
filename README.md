# PHP port of [Twirp](https://twitchtv.github.io/twirp/)

[![CircleCI](https://circleci.com/gh/twirphp/twirp.svg?style=svg)](https://circleci.com/gh/twirphp/twirp)
[![Twirp Version](http://img.shields.io/badge/twirp%20version-v5.3.0-orange.svg?style=flat-square)](https://github.com/twitchtv/twirp/releases/tag/v5.3.0)
[![Go Report Card](https://goreportcard.com/badge/github.com/twirphp/twirp?style=flat-square)](https://goreportcard.com/report/github.com/twirphp/twirp)
[![GoDoc](http://img.shields.io/badge/godoc-reference-5272B4.svg?style=flat-square)](https://godoc.org/github.com/twirphp/twirp)
[![Quality Score](https://img.shields.io/scrutinizer/g/twirphp/twirp.svg?style=flat-square)](https://scrutinizer-ci.com/g/twirphp/twirp)
[![Composer Package](http://img.shields.io/badge/composer-twirp%2Ftwirp-green.svg?style=flat-square)](https://packagist.org/packages/twirp/twirp)

**Work in progress! First estimated preview: end of April**


## Documentation

See the [official documentation](http://twirphp.readthedocs.io).


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
