#!/bin/bash

WCS_APP_HOME="/usr/local/FlashphonerWebCallServer"
WCS_ENV_CONFIG="/usr/local/FlashphonerWebCallServer/bin/setenv.sh"
[ -r "$WCS_ENV_CONFIG" ] && . "$WCS_ENV_CONFIG"

ulimit -n 20000 > /dev/null 2>&1




TEST_ENV_OPTS="-XX:+UseConcMarkSweepGC -XX:+UseCMSInitiatingOccupancyOnly -XX:CMSInitiatingOccupancyFraction=70 -XX:+PrintGCDateStamps -XX:+PrintGCDetails -Xloggc:$WCS_APP_HOME/logs/gc-test.log -XX:+ExplicitGCInvokesConcurrent -Dsun.rmi.dgc.client.gcInterval=36000000000 -Dsun.rmi.dgc.server.gcInterval=36000000000"

if [[ $1 = *[!\ ]* ]]; then
    $_EXECJAVA $TEST_ENV_OPTS $WCS_JAVA_OPTS -Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" -Djava.library.path="$WCS_APP_HOME/lib/so:$WCS_APP_HOME/lib" -cp "$WCS_APP_HOME/lib/*" org.junit.runner.JUnitCore $@
else
    echo "Please specify the name of the test"
fi
