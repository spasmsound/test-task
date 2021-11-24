#!/bin/bash

DIRNAME="$( cd "$( dirname "$0" )" && pwd )"

docker-compose -p test-task exec php bash -c "composer $1"