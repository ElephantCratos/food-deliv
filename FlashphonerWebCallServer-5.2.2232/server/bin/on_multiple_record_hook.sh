# This script copies a recorded stream to client/records
FILE_NAME=$1
CREATION_MODIFICATION_TIME=$2
DURATION_IN_MS=$3
RECORDER_URI=$4
WCS_HOME=/usr/local/FlashphonerWebCallServer
LOG_FILE=$WCS_HOME/logs/multi-record.log
MIXER_TOOL=$WCS_HOME/tools/offline_mixer_tool.sh
# Set LOGGER_ENABLED to true to enable mixing debug logging
LOGGER_ENABLED=false

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Start mixing multiple recording file $FILE_NAME" >> $LOG_FILE

if $LOGGER_ENABLED; then
    bash $MIXER_TOOL $FILE_NAME $CREATION_MODIFICATION_TIME $DURATION_IN_MS $RECORDER_URI >> $LOG_FILE 2>&1
else
    bash $MIXER_TOOL $FILE_NAME $CREATION_MODIFICATION_TIME $DURATION_IN_MS $RECORDER_URI > /dev/null 2>&1
fi

CODE=$?
if [ "$CODE" -ne "0" ]; then
    if [ "$CODE" -eq "64" ]; then
        echo "ERROR: File to mix not found" >> $LOG_FILE
    elif [ "$CODE" -eq "65" ]; then
        echo "ERROR: Offline mixer config not found" >> $LOG_FILE
    else
	    echo "ERROR: Offline mixer tool error code: $CODE" >> $LOG_FILE
	fi
	exit $CODE
fi
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Multiple recording file $FILE_NAME is mixed successfully" >> $LOG_FILE
exit 0
