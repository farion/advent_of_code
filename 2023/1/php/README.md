# Day 1

## Prerequisites

```shell
composer install
```

## Manual Tests

```shell
# Test Data A
php app.php a ../test_a.txt

# Result Data A
php app.php a ../input.txt

# Test Data B
php app.php b ../test_b.txt

# Result Data B
php app.php b ../input.txt
```

## Run UnitTests

```shell
vendor/bin/phpunit test/Test.php
```
