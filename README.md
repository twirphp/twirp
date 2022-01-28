# TwirPHP: PHP port of Twitch's [Twirp](https://twitchtv.github.io/twirp/) RPC framework

![GitHub Workflow Status](https://img.shields.io/github/workflow/status/twirphp/twirp/CI?style=flat-square)
[![Twirp Version](http://img.shields.io/badge/twirp%20version-v7-orange.svg?style=flat-square)](https://twitchtv.github.io/twirp/docs/spec_v7.html)
[![Go Report Card](https://goreportcard.com/badge/github.com/twirphp/twirp?style=flat-square)](https://goreportcard.com/report/github.com/twirphp/twirp)
[![Composer Package](http://img.shields.io/badge/composer-twirp%2Ftwirp-green.svg?style=flat-square)](https://packagist.org/packages/twirp/twirp)


## Installation

Download prebuilt binaries for the protoc plugin from the [releases](https://github.com/twirphp/twirp/releases) page.

Alternatively, you can use the following oneliner to install the plugin:

```bash
curl -Ls https://git.io/twirphp | bash -s -- -b path/to/bin
```

See the [documentation](https://twirphp.github.io/docs/installation) for details.


## Documentation

See the [official documentation](https://twirphp.github.io/).


## Development

Install dependencies:

```shell
go mod download
composer install
```

If you change something in the protoc plugin, regenerate the examples:

```shell
make generate
```

When all coding and testing is done, please run the test suite:

```shell
make check
```

For the best developer experience, install [Nix](https://builtwithnix.org/) and [direnv](https://direnv.net/).

Alternatively, install the following dependencies manually:

- Go >=1.17
- PHP 8.x
- Composer 2.x

Then run `make deps` to install the remaining dependencies.


## Security

If you discover any security related issues, please contact us at [twirphp@sagikazarmark.dev](mailto:twirphp@sagikazarmark.dev).


## License

The project is licensed under the [MIT License](LICENSE).

The original Twirp library is licensed under the Apache 2.0 License.
