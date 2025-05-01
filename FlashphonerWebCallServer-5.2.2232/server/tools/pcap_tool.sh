#!/bin/bash

#------------------------------------------------------------------------------------
# [HELP]:
# pcap_tool.sh --f [fileName] [options]
#
# Oprions:
# 	--url - set URL to server, default: rtmp://localhost:1935/live/testStream;
# 	-v - set verbose (-v, -vv, -vvv), default: -v;
# 	-d - enable debug, default: disable;
# 	--h - help.
#
# Example: pcap_tool.sh tcpdump.pcap --url rtmp://localhost:1935/live/testStream -vv
# *.pcap file should not have handshakes and connect()
#------------------------------------------------------------------------------------

WCS_ENV_CONFIG="/usr/local/FlashphonerWebCallServer/bin/setenv.sh"
. "$WCS_ENV_CONFIG"

serverUri="rtmp://localhost:1935/live/testStream"	#Url to server, default=rtmp://localhost:1935/live
verbose=1
debug=false
h='echo -e [HELP]: \n\n
'$0' --f [fileName] [options]\n\n
Oprions:\n
\t--url - set URL to server, default: rtmp://localhost:1935/live/testStream;\n
\t-v - set verbose (-v, -vv, -vvv), default: -v;\n
\t-d - enable debug, default: disable;\n
\t--h - help.\n\n
Example: '$0' tcpdump.pcap --url rtmp://localhost:1935/live/testStream -vv\n
*pcap-file should not have handshakes and connect()'

while [ -n "$1" ]
do
case "$1" in
--f) fileName="$2"
shift;;
--url) serverUri="$2"
shift;;
-v) verbose=1;;
-vv) verbose=2;;
-vvv) verbose=3;;
-d) debug=true;;
--h) $h
exit 1;;
*) echo "[ERROR] - $1 is not an option"
$h
exit 1;;
esac
shift
done

if [ -z "$fileName"] 
then
echo [ERROR] - fileName is empty\!
$h
exit 1
fi

java -Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" -classpath "$WCS_APP_HOME/lib/*" com.flashphoner.tools.pcap.PcapRtmpTool ${fileName} ${serverUri} ${verbose} ${debug}

exit 0