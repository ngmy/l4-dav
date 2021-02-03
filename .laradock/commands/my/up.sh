#!/bin/bash
set -Ceuo pipefail

local NAME='my:up'
local DESCRIPTION='Start up my development environment'

handle() {
  cp -f ../.laradock/env-development .env
  docker-compose up -d --build apache2 workspace
  docker-compose exec apache2 mkdir -p .laradock/data/apache2/var \
    .laradock/data/apache2/webdav_no_auth \
    .laradock/data/apache2/webdav_basic_auth \
    .laradock/data/apache2/webdav_digest_auth
  docker-compose exec apache2 chown -R www-data:www-data .laradock/data/apache2
  docker-compose exec apache2 htpasswd -b -c /etc/apache2/.htpasswd basic basic
  # Use the following command intead of `docker-compose exec apache2 htdigest -c /etc/apache2/.htdigest 'Digest Auth' digest`
  docker-compose exec apache2 bash -c '(echo -n "digest:Digest Auth:" && echo -n "digest:Digest Auth:digest" | md5sum - | cut -d"-" -f1 | sed "s/ *$//") > /etc/apache2/.htdigest'
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
