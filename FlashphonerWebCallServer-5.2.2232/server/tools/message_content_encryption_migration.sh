#!/bin/bash

WCS_ENV_CONFIG="/usr/local/FlashphonerWebCallServer/bin/setenv.sh"
. "$WCS_ENV_CONFIG"

LD_LIBRARY_PATH="$WCS_APP_HOME"/lib/so java -Xmx512m -Djava.library.path="$WCS_APP_HOME"/lib/so:"$WCS_APP_HOME"/lib \
	-Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" \
	-cp "$WCS_APP_HOME"/lib/wcs-core.jar:"$WCS_APP_HOME"/lib/* \
	com.flashphoner.sfu.tools.migration.MessageContentEncryptionMigration "$@"
