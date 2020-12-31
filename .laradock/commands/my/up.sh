#!/bin/bash
set -Ceuo pipefail

local NAME='my:up'
local DESCRIPTION='Start up my development environment'

handle() {
  cp -f ../.laradock/env-development .env
  docker-compose up -d --build workspace
  docker-compose exec workspace composer install
}
