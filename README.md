# Inpsyde Google Tag Manager

[![Version](https://img.shields.io/packagist/v/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)
[![Status](https://img.shields.io/badge/status-active-brightgreen.svg)](https://github.com/inpsyde/google-tag-manager)
[![Build](https://travis-ci.org/inpsyde/inpsyde-google-tag-manager.svg?branch=master)](https://travis-ci.org/inpsyde/inpsyde-google-tag-manager)
[![codecov](https://codecov.io/gh/inpsyde/google-tag-manager/branch/master/graph/badge.svg)](https://codecov.io/gh/inpsyde/google-tag-manager)
[![Downloads](https://img.shields.io/packagist/dt/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)
[![License](https://img.shields.io/packagist/l/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)
[![WordPress Playground Demo](https://img.shields.io/badge/Playground_Demo-8A2BE2?logo=wordpress&logoColor=FFFFFF&labelColor=3858E9&color=3858E9)](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/inpsyde/google-tag-manager/refs/heads/master/resources/blueprints/blueprint.json)

> Inpsyde Google Tag Manager inserts the GTM Container Code on every page of your WordPress site and writes data to the Data Layer.

![Inpsyde Google Tag Manager](resources/svn-assets/banner-1544x500.png)

## Documentation

1. [Intro](./docs/01-intro.md)
2. [Hooks](./docs/02-hooks.md)
3. [Collectors](./docs/03-collectors.md)
4. [FAQ](./docs/99-faq.md)

## How to start development

This plugin does not include build assets and PHP-dependencies. Therefore, after loading that repository via Composer or git checkout you have to install them.

**With Yarn:**

```shell
yarn install && yarn build
```

## Testing & Quality

To run all tests you've to install composer dev-dependencies first.

## PHPCS

```bash
vendor/bin/phpcs
```

## PHPUnit

```bash
vendor/bin/phpunit
```

## How to create a release

To create a release go to the `<target>-built` branch and create the tag and the release. 

**Example 1: A change is going to be added to `master` branch.**

A developer makes a PR to `master` branch adding a feature.
Once it gets merged a build & push workflow will be triggered.
[bot] This workflow will create a build and push it to `master-built` branch.
[human] Then create a tag on `master-built` and then a release.

**Example 2: A change is going to be added to `1.x` branch.**
A developer makes a PR to `1.x` branch adding a feature.
Once it gets merged a build & push workflow will be triggered.
[bot] This workflow will create a build and push it to `1.x-built` branch.
[human] Then create a tag on `1.x-built` and then a release.

## Copyright and License

This package is [free software](https://www.gnu.org/philosophy/free-sw.en.html) distributed under the terms of the GNU General Public License version 2 or (at your option) any later version. For the full license, see [LICENSE](./LICENSE).
