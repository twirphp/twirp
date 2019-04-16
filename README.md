# [Twirp](https://twitchtv.github.io/twirp/) PHP port

[![CircleCI](https://circleci.com/gh/twirphp/twirp.svg?style=svg)](https://circleci.com/gh/twirphp/twirp)
[![Twirp Version](http://img.shields.io/badge/twirp%20version-v5-orange.svg?style=flat-square)](https://twitchtv.github.io/twirp/docs/spec_v5.html)
[![Go Report Card](https://goreportcard.com/badge/github.com/twirphp/twirp?style=flat-square)](https://goreportcard.com/report/github.com/twirphp/twirp)
[![GolangCI](https://golangci.com/badges/github.com/twirphp/twirp.svg)](https://golangci.com)
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
docker run --rm -it -v $PWD:/app composer install # Optionall add "-v $COMPOSER_HOME:/tmp" to the docker command

go test -v ./protoc-gen-twirp_php/...
# OR
docker run --rm -it -v $PWD:/workspace -v $GOPATH:/go twirphp go test -v ./protoc-gen-twirp_php/...

docker run --rm -it -v $PWD:/workspace twirphp vendor/bin/phpunit -v
docker run --rm -it -v $PWD:/workspace twirphp vendor/bin/phpunit -v --group example

./gencompat.sh
# OR
docker run --rm -it -v $PWD:/workspace -v $GOPATH:/go twirphp ./gencompat.sh

docker run --rm -it -v $PWD:/workspace twirphp clientcompat -client clientcompat/compat.sh
```


## Security

If you discover any security related issues, please contact us at [mark.sagikazar+twirphp@gmail.com](mailto:mark.sagikazar+twirphp@gmail.com).


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

The original Twirp library is licensed under the Apache 2.0 License.
