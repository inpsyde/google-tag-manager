# CHANGELOG

## 1.4.0

### Updated
- Updated `inpsyde/assets` to version `~2.5`
- Updated `inpsyde/php-coding-standards` to version `1.0.0-RC1`.
- Update most of the codebase to follow the updated standards
- Update dependencies to support composer 2


## 1.3.1

### Updated
- change psr/container to ~1.0 because of https://github.com/php-fig/container/pull/27

## 1.3

### Updated
- Updated `inpsyde/assets` to version `~2.1`

## 1.2

### Updated
- Updated `inpsyde/php-coding-standards` to version `~0.7`.
- Updated several methods according due the coding standard.

## 1.1

### Updated
- Updated `readme.txt`.
- Updated `chrico/wp-fields` to version `~0.3`.

### Fixed
- Fixed duplicated `<code>` in backend form description for noscript-tag.

### Improvements
- Allow saving empty "User > visitorRole" and don't show empty `user.role`.
- Updated tests and code according to the new `chrico/wp-fields`-version.
- Introduced new `Http\ParameterBag` and `Http\Request`.
- Removed `filter_input`-usage which causes empty data in various PHP-versions.
- Improved description for multisite-field for easier translation.
- Moved to new [Inpsyde PHP Coding standard](https://github.com/inpsyde/php-coding-standards).

## 1.0

First Release.
