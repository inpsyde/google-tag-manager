# Inpsyde Google Tag Manager

[![Version](https://img.shields.io/packagist/v/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)
[![Status](https://img.shields.io/badge/status-active-brightgreen.svg)](https://github.com/inpsyde/google-tag-manager)
[![Build](https://img.shields.io/travis/inpsyde/google-tag-manager.svg)](https://travis-ci.org/inpsyde/google-tag-manager)
[![Downloads](https://img.shields.io/packagist/dt/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)
[![License](https://img.shields.io/packagist/l/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)

> Inpsyde Google Tag Manager inserts the GTM Container Code on every page of your WordPress site and writes data to the Data Layer.

![Inpsyde Google Tag Manager](resources/svn-assets/banner-1544x500.png)

## Documentation

Please see [readme.txt](readme.txt).

## Requirements

* WordPress >= 4.6.
* PHP 7 or higher.

## How to start develop

This plugin does not include build assets and PHP-dependencies. Therefore, after loading that repository via Composer or git checkout you have to install them.

**With Yarn:**

```js
yarn install && yarn run develop
```

**With NPM:**
```js
npm install && npm run develop
```

The `develop`-script does provide generate all JavaScript- and CSS-files and also run `composer install` to create the autoloading.

## Testing & Quality
To run all tests you've to install composer dev-dependencies first. This can be done via gulp task `develop`.

## PHPCS
Go to your command line and run:

```bash
"vendor/bin/phpcs"
```

## PHPUnit
Go to your command line and run:

```bash
"vendor/bin/phpunit"
```

This repository automatically generates a CodeCoverage-report into the `tmp/`-folder, which will not be committed.

## Behat

* Behat Docs: http://docs.behat.org/en/latest/guides.html
* Wordhat: https://wordhat.info/

We're currently using [Wordhat](https://wordhat.info/) to run WordPress with Behat.

To run Behat locally you need a running Selenium-Server. This package provides the [vvo/selenium-standalone](https://github.com/vvo/selenium-standalone) as `devDependency` via NPM. You can simple run `npm install` and start the `selenium`-task to have a running Selenium-Server.

Additionally you have either to configure following BEHAT_PARAMS locally:

```
export BEHAT_PARAMS={"extensions":{"Behat\\MinkExtension":{"base_url":"$WORDPRESS_URL"},"PaulGibbs\\WordpressBehatExtension":{"path":"$WORDPRESS_DIR"}}}
```

or define a own e.G. `behat.local.yml` by copying the existing one and add the missing `base_url` and `path`.

When Selenium is running, just go to your CLI and type in following:

*[!] Note:* Behat is testing WordPress currently as default installation with language "english".

```bash
"vendor/bin/behat"
```

## License
   
Copyright (c) 2017 Inpsyde GmbH.