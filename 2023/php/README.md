# Advent Of Code PHP Edition

## Prerequisites
* Composer (https://getcomposer.org/download/)
* PHP 8+
    * Ensure also php-xml, php-mbstring, php-curl is installed.

```shell
composer install
# if you did already try on upates
compose update
```

## Test

### All Tests
```shell 
vendor/bin/phpunit test/*.php
```

### Specific Test
```shell
vendor/bin/phpunit test/TestDay1A.php
```

## Manual Tests

```shell
php src/app.php --day 1 --task a 
php src/app.php --day 1 --task a --input ../resources/1/test_a.txt
```
