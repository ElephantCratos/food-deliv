#!/bin/bash

WCS_ENV_CONFIG="/usr/local/FlashphonerWebCallServer/bin/setenv.sh"
. "$WCS_ENV_CONFIG"

h='echo -e [HELP]: \n\n
'$0' [configFileName]\n\n
Example: '$0' config.yml\n
*config-file should be placed inside /media/ dir'

fileName=$1

if [ -z "$fileName" ]
then
echo [ERROR] - fileName is empty\!
$h
exit 1
fi

java -Xmx6G \
     -XX:+UseConcMarkSweepGC \
     -XX:NewSize=1024m \
     -XX:+UseCMSInitiatingOccupancyOnly \
     -XX:CMSInitiatingOccupancyFraction=70 \
     -Djava.net.preferIPv4Stack=true \
     -XX:+ExplicitGCInvokesConcurrent \
     -Dsun.rmi.dgc.client.gcInterval=36000000000 \
     -Dsun.rmi.dgc.server.gcInterval=36000000000 \
     -Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" \
     -DWCS_NON_ROOT=true \
     -Djava.library.path=lib/so:lib \
     -classpath "$WCS_APP_HOME/lib/*" \
     com.flashphoner.tools.rtmp.RtmpByteStreamReaderTool "${fileName}"