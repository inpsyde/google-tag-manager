# Inpsyde Google Tag Manager

[![Version](https://img.shields.io/packagist/v/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)
[![Status](https://img.shields.io/badge/status-active-brightgreen.svg)](https://github.com/inpsyde/google-tag-manager)
[![Build](https://img.shields.io/travis/inpsyde/google-tag-manager.svg)](https://travis-ci.org/inpsyde/google-tag-manager)
[![Downloads](https://img.shields.io/packagist/dt/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)
[![License](https://img.shields.io/packagist/l/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)


## Description

// TODO


## Features

// TODO

## Testing

## PHPUnit
To run PHPUnit, first you need to install all composer devDependencies. Afterwards you can run:

```
"vendor/bin/phpunit"
```

This repository automatically generates a CodeCoverage-report into the `tmp/`-folder.

## Behat

* Behat Docs: http://docs.behat.org/en/latest/guides.html
* Wordhat: https://wordhat.info/

We're currently using [Wordhat](https://wordhat.info/) to run WordPress with Behat.

To run Behat locally you need a running Selenium-Server. This package provides the [vvo/selenium-standalone](https://github.com/vvo/selenium-standalone) as `devDependency` via NPM. You can simple run `npm install` and run the `selenium` task to have a running Selenium-Server.

Additionally you have either to configure following BEHAT_PARAMS locally:

```
export BEHAT_PARAMS={"extensions":{"Behat\\MinkExtension":{"base_url":"$WORDPRESS_URL"},"PaulGibbs\\WordpressBehatExtension":{"path":"$WORDPRESS_DIR"}}}
```

or define a own e.G. `behat.local.yml` by copying the existing one and add the missing `base_url` and `path`.

When Selenium is running, just go to your CLI and type in following:

```
"vendor/bin/behat"
```

## Requirements

* WordPress latest -1.
* PHP 7 or higher.
