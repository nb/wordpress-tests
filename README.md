## Current Status

The framework is currently [being ported](http://unit-test.trac.wordpress.org/ticket/42) to the official testing suite.

## Rationale

WordPress already has an automated [testing suite](http://unit-tests.trac.wordpress.org/). What you see here is an alternative testing framework, with the following goals:

* faster
* runs every test case in a clean WordPress install
* uses the default PHPUnit runner, instead of custom one
* doesn't encourage or support the usage of shared/prebuilt fixtures

It uses SQL transactions to clean up automatically after each test.

## Installation

0. Clone the project.
1. Copy `unittests-config-sample.php` to `unittests-config.php`.
2. Edit the config. USE A NEW DATABASE, BECAUSE ALL THE DATA INSIDE WILL BE DELETED.
3. $ phpunit all
4. $ phpunit test_test.php
