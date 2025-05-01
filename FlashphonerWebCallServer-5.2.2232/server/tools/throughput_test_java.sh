#!/bin/bash

WCS_ENV_CONFIG="/usr/local/FlashphonerWebCallServer/bin/setenv.sh"
. "$WCS_ENV_CONFIG"

LD_LIBRARY_PATH="$WCS_APP_HOME"/lib/so java -Xmx6G -Djava.library.path="$WCS_APP_HOME"/lib/so:"$WCS_APP_HOME"/lib \
	-Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" \
	-cp "$WCS_APP_HOME"/lib/wcs-core.jar:"$WCS_APP_HOME"/lib/* \
	com.flashphoner.media.transcoder.gpu.ThroughputTest "$@"

if [ $? -ne 0 ]; then
  echo "Failed to execute throughput test"
  exit 1
fi
