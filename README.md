# Instructions

[Instructions for this project](INSTRUCTIONS.md)

# Pre-requisites

- PHP 8.3
- Composer
- bcmath extension

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

Docker

```
docker run --rm -v $(pwd):/app -w /app php:8.3-cli php public/one.php
```

# Question #2

```
php public/two.php
```

Docker

```
docker run --rm -v $(pwd):/app -w /app php:8.3-cli php public/two.php
```

# Question #3

```
vendor/bin/phpunit tests/QuestionThreeTest.php
```

Docker

```
docker run --rm \
  --volume "$(pwd)":/app \
  --workdir /app \
  php:8.3-cli bash -c "\
  docker-php-ext-install bcmath && \
  vendor/bin/phpunit tests/QuestionThreeTest.php"
```

# Tests

```
vendor/bin/phpunit
```

Docker

```
docker run --rm \
  --volume "$(pwd)":/app \
  --workdir /app \
  php:8.3-cli bash -c "\
  docker-php-ext-install bcmath && \
  vendor/bin/phpunit vendor/bin/phpunit tests"
```