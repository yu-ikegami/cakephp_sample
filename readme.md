# 作業用コマンド

## docker-compose up
docker-compose up -d --build

## docker-compose exec
docker-compose exec web sh

docker-compose exec db bash

## docker-compose run

docker-compose run --rm php_cli



# メモ

## composer.pharの作成

curl -s https://getcomposer.org/installer | docker-compose run --rm php_cli

## cakephp3.0.xのインストール

docker-compose run --rm php_cli composer.phar create-project --prefer-dist "cakephp/app:3.0.*" app
mv app/* app/.[^\.]* .
rmdir app

docker-compose run --rm php_cli composer.phar install
docker-compose run --rm php_cli composer.phar require --no-update cakephp/cakephp ~3.0.0
docker-compose run --rm php_cli composer.phar require --dev --no-update cakephp/debug_kit ~3.1.0
docker-compose run --rm php_cli composer.phar require --dev --no-update cakephp/bake ~1.0.0
docker-compose run --rm php_cli composer.phar require --no-update cakephp/migrations ~1.3.0

rm -rf vendor
rm composer.lock

docker-compose run --rm php_cli composer.phar install


===

# CakePHP Application Skeleton

[![Build Status](https://api.travis-ci.org/cakephp/app.png)](https://travis-ci.org/cakephp/app)
[![License](https://poser.pugx.org/cakephp/app/license.svg)](https://packagist.org/packages/cakephp/app)

A skeleton for creating applications with [CakePHP](http://cakephp.org) 3.0.

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

## Installation

1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app [app_name]`.

If Composer is installed globally, run
```bash
composer create-project --prefer-dist cakephp/app [app_name]
```

You should now be able to visit the path to where you installed the app and see
the setup traffic lights.

## Configuration

Read and edit `config/app.php` and setup the 'Datasources' and any other
configuration relevant for your application.
