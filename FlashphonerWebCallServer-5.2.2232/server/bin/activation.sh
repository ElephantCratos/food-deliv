#!/usr/bin/env bash

PRODUCT="FlashphonerWebCallServer"
WCS_APP_HOME="/usr/local/$PRODUCT"
WCS_CORE_CONF="$WCS_APP_HOME/conf/wcs-core.properties"

OWNER=$(stat -c "%U" $WCS_APP_HOME/)
GROUP=$(stat -c "%G" $WCS_APP_HOME/)

while read -r line || [[ -n "$line" ]]; do
    if [[ $line == -Dhttp* ]]; then
        WCS_PROXY_OPTS+="$line "
    fi
done < $WCS_CORE_CONF

JDK_VERSION=$(`which java` -version 2>&1 | grep version | cut -f2 -d\" | cut -f1 -d.)

if [ $JDK_VERSION -eq 16 ]; then
    WCS_PROXY_OPTS="$WCS_PROXY_OPTS --illegal-access=permit"
elif [ $JDK_VERSION -ge 17 ]; then
    WCS_PROXY_OPTS="$WCS_PROXY_OPTS --add-exports java.base/sun.security.provider=ALL-UNNAMED"
fi

echo ""
echo "------------------------------"
echo "Flashphoner License Activation"
echo "------------------------------"
echo ""
java $WCS_PROXY_OPTS -Djavax.net.ssl.trustStore=$WCS_APP_HOME/conf/myflashphoner-ca -Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" -cp "$WCS_APP_HOME/lib/*" com.flashphoner.server.license.Activation $1
echo ""

if [[ "$OWNER" != "root" ]] || [[ "$GROUP" != "root" ]]; then
 chown $OWNER:$GROUP -R $WCS_APP_HOME/logs
 chown $OWNER:$GROUP -R $WCS_APP_HOME/conf/flashphoner.license
 chmod ug+w $WCS_APP_HOME/conf/flashphoner.license
fi
