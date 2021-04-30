# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.1] - 2021-04-30
### Changed
- Account for models folder in newer Laravel applications

## [1.1.1] - 2020-06-30
### Changed
- User transactions are now retrieve in DESC order

## [1.1.0] - 2020-06-29
### Added
- Eagerload users table (coin/transaction models) (warning: system user (0) will cause problems, detect this in your output for now)

## [1.0.0] - 2020-06-26
### Added
- Initial release

### Changed
- n/a

### Removed
- n/a

[Unreleased]: https://github.com/mechawrench/laracoins/compare/v1.1.1...HEAD
[1.1.1]: https://github.com/mechawrench/laracoins/compare/v1.1.0...v1.1.1
[1.2.1]: https://github.com/mechawrench/laracoins/compare/v1.1.0...v1.2.1
[1.1.0]: https://github.com/mechawrench/laracoins/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/mechawrench/laracoins/releases/tag/v1.0.0
