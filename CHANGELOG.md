# Change Log


All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [Unreleased]

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


[Unreleased]: https://github.com/goph/nest/compare/v0.3.2...HEAD
[0.3.2]: https://github.com/goph/nest/compare/v0.3.1...v0.3.2
[0.3.1]: https://github.com/goph/nest/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/goph/nest/compare/v0.2.1...v0.3.0
[0.2.1]: https://github.com/goph/nest/compare/v0.2.0...v0.2.1
[0.2.0]: https://github.com/goph/nest/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/goph/nest/compare/v0.1.0...v0.1.1
