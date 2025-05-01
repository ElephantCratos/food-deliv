#!/bin/bash

WCS_ENV_CONFIG="/usr/local/FlashphonerWebCallServer/bin/setenv.sh"
. "$WCS_ENV_CONFIG"

OUTPUT_FILE=$2
if [ -z "$2" ]; then
    OUTPUT_FILE=stun-tcp.log
fi

LD_LIBRARY_PATH="$WCS_APP_HOME"/lib/so java -Xmx512m -Djava.library.path="$WCS_APP_HOME"/lib/so:"$WCS_APP_HOME"/lib \
	-Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" \
	-cp "$WCS_APP_HOME"/lib/wcs-core.jar:"$WCS_APP_HOME"/lib/* \
	com.flashphoner.tools.stun.StunTcpParser "$1" > "$OUTPUT_FILE" 2>&1
