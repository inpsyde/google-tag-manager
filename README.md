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
To run Behat locally you need a running Selenium-Server. Open your CLI and enter following:

```
npm install selenium-standalone@latest -g
selenium-standalone install
selenium-standalone start
```

If Selenium is running, just go to your CLI and type in following:

```
"vendor/bin/behat"
```

## Requirements

* WordPress latest -1.
* PHP 7 or higher.
