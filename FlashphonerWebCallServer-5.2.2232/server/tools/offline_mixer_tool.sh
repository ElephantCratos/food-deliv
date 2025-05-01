#!/bin/bash
show_tracks_info () {
    FILE_PATH=$2
    if [ ! -f "$FILE_PATH" ]; then
        echo "Mp4/Mkv container $FILE_PATH does not exist."
        exit 64
    fi
    java -Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" -classpath "$WCS_APP_HOME/lib/*" com.flashphoner.tools.offlinemixer.OfflineMixerTool $FILE_PATH "--show-tracks-info"
    exit 0
}

pull_streams () {
    FILE_PATH=$2
    if [ ! -f "$FILE_PATH" ]; then
        echo "Mkv container $FILE_PATH does not exist."
        exit 64
    fi
    java -Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" -classpath "$WCS_APP_HOME/lib/*" com.flashphoner.tools.offlinemixer.OfflineMixerTool $FILE_PATH "--pull-streams"
    exit 0
}

mix () {
    FILE_PATH=$1
    CONFIG_PATH=$WCS_APP_HOME/conf/offline-mixer.json
    CREATION_MODIFICATION_TIME=$2
    DURATION_IN_MS=$3
    RECORDER_URI=$4

    if [ ! -f "$FILE_PATH" ]; then
        echo "Mp4 container $FILE_PATH does not exist."
        exit 64
    fi

    if [ ! -f "$CONFIG_PATH" ]; then
        echo "Mixer config $CONFIG_PATH does not exist."
        exit 65
    fi

    java -Dcom.flashphoner.fms.AppHome="$WCS_APP_HOME" -classpath "$WCS_APP_HOME/lib/*" com.flashphoner.tools.offlinemixer.OfflineMixerTool $FILE_PATH $CONFIG_PATH $CREATION_MODIFICATION_TIME $DURATION_IN_MS $RECORDER_URI
    exit 0
}

WCS_ENV_CONFIG="/usr/local/FlashphonerWebCallServer/bin/setenv.sh"
. "$WCS_ENV_CONFIG"

if [ "$1" = "--show-tracks-info" ]; then
    show_tracks_info "$@"
elif [ "$1" = "--pull-streams" ]; then
    pull_streams "$@"
else
    mix "$@"
fi
