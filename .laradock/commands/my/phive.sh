#!/bin/bash
set -Ceuo pipefail

local NAME='my:phive'
local DESCRIPTION='Execute a PHIVE command'

handle() {
  docker-compose exec -u laradock workspace phive --home .laradock/data/phive "$@"
}
