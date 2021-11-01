# [Twirp](https://twitchtv.github.io/twirp/) PHP port

![GitHub Workflow Status](https://img.shields.io/github/workflow/status/twirphp/twirp/CI?style=flat-square)
[![Twirp Version](http://img.shields.io/badge/twirp%20version-v5-orange.svg?style=flat-square)](https://twitchtv.github.io/twirp/docs/spec_v5.html)
[![Go Report Card](https://goreportcard.com/badge/github.com/twirphp/twirp?style=flat-square)](https://goreportcard.com/report/github.com/twirphp/twirp)
[![Composer Package](http://img.shields.io/badge/composer-twirp%2Ftwirp-green.svg?style=flat-square)](https://packagist.org/packages/twirp/twirp)


## Installation

Download prebuilt binaries for the protoc plugin from the [releases](https://github.com/twirphp/twirp/releases) page.

Alternatively, you can use the following oneliner to install the plugin:

```bash
curl -Ls https://git.io/twirphp | bash -b path/to/bin
```

See the [documentation](https://twirphp.readthedocs.io/en/latest/getting-started/installation.html) for details.


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

docker run --rm -it -v $PWD:/workspace twirphp make clientcompat
```


## Security

If you discover any security related issues, please contact us at [mark.sagikazar+twirphp@gmail.com](mailto:mark.sagikazar+twirphp@gmail.com).


## License

The project is licensed under the [MIT License (MIT)](LICENSE).

The original Twirp library is licensed under the Apache 2.0 License.
