# Setting up tests

## Prerequisites

- composer
- wp-cli
- a functioning wordpress install

## Steps

From the directory you have cloned Largo to:

1. `composer install`
2. `wp scaffold theme-tests`, and skip any overwrites
2. `bash bin/install-wp-tests.sh`
3. `vendor/bin/phpunit`
