# Inpsyde Google Tag Manager

[![Version](https://img.shields.io/packagist/v/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)
[![Status](https://img.shields.io/badge/status-active-brightgreen.svg)](https://github.com/inpsyde/google-tag-manager)
[![Build](https://img.shields.io/travis/inpsyde/google-tag-manager.svg)](https://travis-ci.org/inpsyde/google-tag-manager)
[![Downloads](https://img.shields.io/packagist/dt/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)
[![License](https://img.shields.io/packagist/l/inpsyde/google-tag-manager.svg)](https://packagist.org/packages/inpsyde/google-tag-manager)

## Installation

### Requirements

* WordPress latest -1.
* PHP 7 or higher.


## Description

// TODO


## Features

// TODO

## Hooks

**`inpsyde-google-tag-manager.error`** - This action is triggered when an error occurs in our plugin.

**`inpsyde-google-tag-manager.debug`** - This action is triggered for easier debugging of the plugin and provides useful information and context about the current page.

**`inpsyde-google-tag-manager.boot`** - This action allows you to add your custom service providers and settings to the Plugin DI-container before booting all services.

**`inpsyde-google-tag-manager.before-script`** - This action allows to insert custom markup before the gtm script-tag.

**`inpsyde-google-tag-manager.after-script`** - This action allows to insert custom markup after the gtm script-tag.

**`inpsyde-google-tag-manager.render-noscript`** - This action can be called manually in content to render the `<noscript>`-tag.

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