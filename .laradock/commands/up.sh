#!/bin/bash
#
# The laradockctl command to start up a development environment.

set -Ceuo pipefail

local NAME='up'
local DESCRIPTION='Start up a development environment'

handle() {
  source "$(laradockctl_command_path up.sh)"
  handle

  # Set up a WebDAV server
  docker-compose exec apache2 mkdir -p .laradock/data/apache2/var \
    .laradock/data/apache2/webdav_no_auth \
    .laradock/data/apache2/webdav_basic_auth \
    .laradock/data/apache2/webdav_digest_auth
  docker-compose exec apache2 chown -R www-data:www-data .laradock/data/apache2
  docker-compose exec apache2 htpasswd -b -c /etc/apache2/.htpasswd basic basic
  # Use the following command intead of `docker-compose exec apache2 htdigest -c /etc/apache2/.htdigest 'Digest Auth' digest`
  docker-compose exec apache2 bash -c '(echo -n "digest:Digest Auth:" && echo -n "digest:Digest Auth:digest" | md5sum - | cut -d"-" -f1 | sed "s/ *$//") > /etc/apache2/.htdigest'
}
