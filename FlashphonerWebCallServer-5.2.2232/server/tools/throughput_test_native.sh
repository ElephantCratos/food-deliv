#!/bin/bash

WCS_ENV_CONFIG="/usr/local/FlashphonerWebCallServer/bin/setenv.sh"
. "$WCS_ENV_CONFIG"

env LD_LIBRARY_PATH="$WCS_APP_HOME"/lib/so "$WCS_APP_HOME"/bin/throughput_test "$@"

if [ $? -ne 0 ]; then
  echo "Failed to execute throughput test"
  exit 1
fi
