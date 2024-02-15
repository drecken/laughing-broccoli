# Pre-requisites

- PHP 8.3
- Composer

# Installation

Download the repository

```
git clone git@github.com:drecken/laughing-broccoli.git
cd laughing-broccoli
```

## Install dependencies

```
php composer.phar install
```

Docker

```
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer:2 install
```

# Question #1

```
php public/one.php
```

Run in Docker

```
docker run --rm -v $(pwd):/app -w /app php:8.3-cli php public/one.php
```

# Question #2

```
php public/two.php
```

Run in Docker

```
docker run --rm -v $(pwd):/app -w /app php:8.3-cli php public/two.php
```

# Tests

Runs tests

```
vendor/bin/phpunit
```

Docker

```
docker run --rm \
--volume $PWD:/app \
--workdir /app \
php:8.3-cli vendor/bin/phpunit tests
```