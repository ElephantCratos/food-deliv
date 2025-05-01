#!/bin/bash

WCS_ENV_CONFIG="/usr/local/FlashphonerWebCallServer/bin/setenv.sh"
. "$WCS_ENV_CONFIG"

java -Djava.library.path=$WCS_APP_HOME/lib/so:$WCS_APP_HOME/lib -Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" -classpath "$WCS_APP_HOME/lib/*" com.flashphoner.tools.layoutrendeder.MixerLayoutRenderer "$@"
exit 0
