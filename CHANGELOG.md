# Change Log


All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [Unreleased]


## [0.9.0] - 2022-11-28

### Added

- arm64 build


## [0.8.1] - 2022-01-28

### Fixed

- Incorrect version information


## [0.8.0] - 2021-11-01

### Added

- Add previous exception to the Twirp error for client errors (thanks @bakins)
- [Nix](https://nixos.org/) based developer environment
- V7 spec support

### Changed

- **Breaking change:** Resource exhausted error is now returned as HTTP 429. Read more in the [v7 specification](https://twitchtv.github.io/twirp/docs/spec_v7.html#differences-with-v5).
- Updated Go dependencies
- Updated PHP dependencies
- Updated tools (PHP CS Fixer, PHPStan, Psalm)
- Moved the runtime library to `lib/` subdirectory from `php/`
- Improved GitHub integrations (issue templates, support template, security policy, etc)

### Removed

- Docker based developer environment (use Nix or install Go, PHP and Composer using your preferred method)


## [0.7.5] - 2021-08-26

### Added

- Support for proto3 field presence (optional fields)


## [0.7.4] - 2021-07-31

### Fixed

- Fix TypeError when exceptions with string error codes are thrown


## [0.7.3] - 2021-06-29

### Added

- Adjust generated code comment to auto-collapse in GitHub diffs


## [0.7.2] - 2021-05-12

### Fixes

- Fix segfault when input proto file does not specify options
- Fix namespacing bug when importing other proto files


## [0.7.1] - 2021-04-21

### Fixes

- Fix release workflow


## [0.7.0] - 2021-04-21

### Added

- Custom prefix support

### Changes

- Rewritten generator based on v2 Go Protobuf library
- Ignore unknown fields for JSON


## [0.6.0] - 2020-09-02

### Added

- Generated JSON client stub for development purposes


## [0.5.3] - 2019-08-20

### Fixed

- Panic when no options were found in a proto file


## [0.5.2] - 2019-05-16

### Fixed

- Generated TwirpError interface implementation


## [0.5.1] - 2019-04-17

### Added

- Windows build


## [0.5.0] - 2019-04-17

### Changes

- Drop PHP 5.6 support
- Use PSR-15, PSR-17, PSR-18

### Fixed

- Server checking the URL prefix


## [0.4.0] - 2018-06-28

### Added

- Server class to the shared library

### Changed

- The builtin generated error now receives the previous exceptions
- Replace the error system with native PHP exceptions

### Fixed

- Wrong type hint (`ServerHook`)

### Removed

- Generated server class
- Common `TwirpServer` class
- Common `TwirpClient` class


## [0.3.2] - 2018-06-26

### Added

- Composer conflict for protobuf versions lower than 3.5

### Fixed

- Add missing break statements
- Fix wrong method name


## [0.3.1] - 2018-05-02

### Fixed

- Goreleaser build


## [0.3.0] - 2018-05-01

### Added

- Compiler name and version to the generated files

### Changed

- Message factory is not invoked in the client constructor


## [0.2.1] - 2018-04-26

### Fixed

- Packr


## [0.2.0] - 2018-04-26

- Preview release


## [0.1.1] - 2018-04-14

- Improve release workflow


## 0.1.0 - 2018-04-12

- Initial release


[Unreleased]: https://github.com/twirphp/twirp/compare/v0.9.0...HEAD
[0.9.0]: https://github.com/twirphp/twirp/compare/v0.8.1...v0.9.0
[0.8.1]: https://github.com/twirphp/twirp/compare/v0.8.0...v0.8.1
[0.8.0]: https://github.com/twirphp/twirp/compare/v0.7.5...v0.8.0
[0.7.5]: https://github.com/twirphp/twirp/compare/v0.7.4...v0.7.5
[0.7.4]: https://github.com/twirphp/twirp/compare/v0.7.3...v0.7.4
[0.7.2]: https://github.com/twirphp/twirp/compare/v0.7.2...v0.7.3
[0.7.2]: https://github.com/twirphp/twirp/compare/v0.7.1...v0.7.2
[0.7.1]: https://github.com/twirphp/twirp/compare/v0.7.0...v0.7.1
[0.7.0]: https://github.com/twirphp/twirp/compare/v0.6.0...v0.7.0
[0.6.0]: https://github.com/twirphp/twirp/compare/v0.5.3...v0.6.0
[0.5.3]: https://github.com/twirphp/twirp/compare/v0.5.2...v0.5.3
[0.5.2]: https://github.com/twirphp/twirp/compare/v0.5.1...v0.5.2
[0.5.1]: https://github.com/twirphp/twirp/compare/v0.5.0...v0.5.1
[0.5.0]: https://github.com/twirphp/twirp/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/twirphp/twirp/compare/v0.3.2...v0.4.0
[0.3.2]: https://github.com/twirphp/twirp/compare/v0.3.1...v0.3.2
[0.3.1]: https://github.com/twirphp/twirp/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/twirphp/twirp/compare/v0.2.1...v0.3.0
[0.2.1]: https://github.com/twirphp/twirp/compare/v0.2.0...v0.2.1
[0.2.0]: https://github.com/twirphp/twirp/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/twirphp/twirp/compare/v0.1.0...v0.1.1
