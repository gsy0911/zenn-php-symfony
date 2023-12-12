#!/bin/bash -x

# パッケージをインストール
composer install

exec "$@"
