#!/bin/bash
set -Ceuo pipefail

local NAME='my:destroy'
local DESCRIPTION='Destory my development environment'

handle() {
  docker-compose down -v
  local YN
  read -p 'Do you want to remove data? (y/N)' YN
  if [[ "${YN}" == 'y' ]]; then
    sudo rm -rf ../.laradock/data/*
  fi
}
