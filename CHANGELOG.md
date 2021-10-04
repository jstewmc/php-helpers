# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.2.0] - 2021-09-30

### Added

- Added [slevomat/coding-standard](https://github.com/slevomat/coding-standard) to enforce coding standards.
- Added [roave/security-advisories](https://github.com/Roave/SecurityAdvisories) to exclude dependencies with known vulnerabilities.
- Added continuous integration with CircleCI.

### Changed

- Renamed `Bool` to `Boolean` for PHP7 compatibility ([#2](https://github.com/jstewmc/php-helpers/issues/2)).
- Modernized all helpers (e.g., argument type hints, return type hints, guard clauses, etc).
- Updated `PHPUnit` from version 4 to 9.
- Moved tests for `Dir` from concrete files and folders to virtual file system using [bovigo/vfsStream](https://github.com/bovigo/vfsStream).
- Ignored `composer.phar`, `PHPUnit` files, and some system files like `.DS_Store`.
- Updated `Num::isNumeric()` to support english phrases like `two hundred and fifty-six`.

### Removed

- Lots and lots of unnecessary comments :)
- Removed `Dir::cp()` and `Dir::rm()` aliases (they would not have worked anyway).

## [0.1.1] - 2014-10-18

### Added

- Update `Num::val()` to handle strings like "first", "2nd", and "one million four thousand and forty-seven".

## [0.1.0] - 2014-08-27

The initial release.
