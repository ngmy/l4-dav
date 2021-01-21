#!/bin/bash
set -Ceuo pipefail

local NAME='my:up'
local DESCRIPTION='Start up my development environment'

handle() {
  cp -f ../.laradock/env-development .env
  docker-compose up -d --build apache2 workspace
  docker-compose exec apache2 mkdir -p .laradock/data/apache2/var .laradock/data/apache2/webdav
  docker-compose exec apache2 chown -R www-data:www-data .laradock/data/apache2
  docker-compose exec -u laradock workspace composer install
  docker-compose exec -u laradock workspace cp phpunit.xml.dist phpunit.xml
  docker-compose exec -u laradock workspace sed -i -z 's/<!--\(<testsuite name="Feature".*\)-->/\1/g' phpunit.xml
  if ! docker-compose exec workspace bash -c 'test -f /usr/local/bin/phive'; then
    docker-compose exec workspace curl -fsSL https://phar.io/releases/phive.phar -o /tmp/phive.phar
    docker-compose exec workspace curl -fsSL https://phar.io/releases/phive.phar.asc -o /tmp/phive.phar.asc
    docker-compose exec workspace gpg --keyserver ipv4.pool.sks-keyservers.net --recv-keys 0x9D8A98B29B2D5D79
    docker-compose exec workspace gpg --verify /tmp/phive.phar.asc /tmp/phive.phar
    docker-compose exec workspace chmod +x /tmp/phive.phar
    docker-compose exec workspace mv /tmp/phive.phar /usr/local/bin/phive
  fi
  docker-compose exec -u laradock workspace phive --home .laradock/data/phive install
}
