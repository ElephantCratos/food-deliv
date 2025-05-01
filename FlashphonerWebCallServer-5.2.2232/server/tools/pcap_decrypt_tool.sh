#!/bin/bash

#------------------------------------------------------------------------------------
# [HELP]:
# pcap_decrypt_tool.sh input.pcap output.pcap [port1]:[DecryptKey1] [port2]:[DecryptKey2] ... [portN]:[DecryptKeyN]
#
# Example: pcap_decrypt_tool.sh input.pcap output.pcap 31000:1234567 32000:1234567
#
#------------------------------------------------------------------------------------

WCS_ENV_CONFIG="/usr/local/FlashphonerWebCallServer/bin/setenv.sh"
. "$WCS_ENV_CONFIG"

h="Example: ./$0 input.pcap output.pcap 31000:1234567 32000:1234567"

IFS=':'

if [ "$#" -lt "3" ]
  then
    echo [ERROR] - Bad args length\!
    echo "$h"
    exit 1
fi
for var in "${@:3}";
do
    read -a portWithKey <<< "$var"
	if [ ${#portWithKey[*]} -ne "2" ]
		then
			echo [ERROR] - Argument "$var" has bad input format\!
			echo "$h"
			exit 1
	fi

	if ! [[ ${portWithKey[0]} =~ ^[0-9]+$ ]]
		then
			echo [ERROR] - "$var" port is not number\!
			echo "$h"
			exit 1
	fi

if ! [[ ${portWithKey[1]} =~ ^[A-Za-z0-9]+$ ]]
  then
    echo [ERROR] - "$var" decryptKey is not valid\!
    echo "$h"
    exit 1
fi
done

java -Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" -classpath "$WCS_APP_HOME/lib/*" com.flashphoner.tools.pcap.PcapDecryptTool "$@"
exit 0
