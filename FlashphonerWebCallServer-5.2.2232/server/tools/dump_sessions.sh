#!/bin/bash

trap shutdown_dump TERM SIGTERM SIGINT

RF=$1 #path to dir with dumps
INTERVAL=$2 # in seconds
MAX_SIZE=$3 #in megabytes
MODE=$4 #can be SIGNALLING, MEDIA, SIGNALLING_MEDIA

FLASHPHONER_CONF_FILE="/usr/local/FlashphonerWebCallServer/conf/flashphoner.properties"

function check_deps() {
	#check tcpdump availability
	TCPDUMP=`which tcpdump 2>/dev/null`
	if [ -z $TCPDUMP ]; then
		echo "In order to run this script you need to install tcpdump on your system."
		echo "Please, try to run:"
		echo "yum install tcpdump"
		echo "Exiting"
		exit -1
	fi
}

function is_running() {
    OUR_PID=$$
    IS_RUNNING=`pidof -x "dump_sessions.sh" -o %PPID -o $OUR_PID | grep [0-9]`
	if [ -n "$IS_RUNNING" ]; then
		echo "Script already running with PID $IS_RUNNING, please check it with:"
		echo "ps aux | grep dump_sessions.sh"
		echo "Exiting"
		exit -1
	fi
}

function check_config() {
	#check RF
	if [ -n $RF ]; then
		if [ ! -f $RF ]; then
			yes_no
		fi
	else
		#default RF
		default_rf
	fi

	#check INTERVAL
	if [ -z $INTERVAL ]; then
		#set default interval to 10 minutes
		INTERVAL="600"
	fi
	
	#check MAX_SIZE
	if [ -z $MAX_SIZE ]; then
		#set max size to 256Mb
		MAX_SIZE="256"
	fi
	
	#check MODE
	if [ -z $MODE ]; then
		#set mode to SIGNALLING_MEDIA
		MODE="SIGNALLING_MEDIA"
	fi
	
	echo "Config: RF=$RF, INTERVAL=$INTERVAL, MAX_SIZE=$MAX_SIZE, MODE=$MODE"
}

function yes_no(){
	echo "Directory is not exist. Do you want to create it? (y/n)"
	read in_y_n
	if [ "$in_y_n" == "y" -o "$in_y_n" == "n" ]; then
		if [ "$in_y_n" == "y" ]; then
			mkdir -p $RF
		else
			#default RF
			default_rf
		fi
	else
		yes_no
	fi			
}

function default_rf() {
	RF="/usr/local/FlashphonerWebCallServer/logs/dumps"
	echo "Using default dump folder $RF"
	if [ ! -f $RF ]; then
		mkdir -p $RF
	fi
}

function get_signalling_and_media_ports() {
	PORT_FROM=`grep '^port_from' $FLASHPHONER_CONF_FILE | grep -o  '[0-9]*'`
	PORT_TO=`grep '^port_to' $FLASHPHONER_CONF_FILE | grep -o  '[0-9]*'`
	MEDIA_PORT_FROM=`grep '^media_port_from' $FLASHPHONER_CONF_FILE | grep -o  '[0-9]*'`
	MEDIA_PORT_TO=`grep '^media_port_to' $FLASHPHONER_CONF_FILE | grep -o  '[0-9]*'`
	RTMFP_PORT=`grep '^rtmfp.port' $FLASHPHONER_CONF_FILE | grep -o  '[0-9]*'`
	
	echo "Ports config: PORT_FROM=$PORT_FROM, PORT_TO=$PORT_TO, MEDIA_PORT_FROM=$MEDIA_PORT_FROM, MEDIA_PORT_TO=$MEDIA_PORT_TO"
	echo "RTMFP_PORT=$RTMFP_PORT"
}

function tcpdump_args() {
	if [ $MODE="SIGNALLING_MEDIA" ]; then
		TCPDUMP_ARGS="and portrange $PORT_FROM-$PORT_TO and portrange $MEDIA_PORT_FROM-$MEDIA_PORT_TO and port $RTMFP_PORT"
	elif [ $MODE="MEDIA" ]; then
		TCPDUMP_ARGS="and portrange $MEDIA_PORT_FROM-$MEDIA_PORT_TO"
	else
		#default SIGNALLING
		TCPDUMP_ARGS="and portrange $PORT_FROM-$PORT_TO and port $RTMFP_PORT"
	fi
}

function actual_size {
    ACTUAL_SIZE=`du -sb $RF | awk '/^[0-9]*.*$/{print $1}'`
    let "SIZE_IN_MB = $ACTUAL_SIZE / 1024 / 1024"
    echo "SIZE of $RF $SIZE_IN_MB MB"
}

function dump_size {
    actual_size
    if [[ $ACTUAL_SIZE -ge $MAX_SIZE_BYTES ]]; then
	#we have reached maximum size of our dump directory, need to delete oldest dump file
	echo "remove oldest dumps due to space limitation"
	while [[ $ACTUAL_SIZE -ge $MAX_SIZE_BYTES ]]; do
		#OLDEST_DUMP will contain oldest file from RF directory that starts with "session"
	    OLDEST_DUMP=`ls -tr $RF | grep session | head -1`
	    echo "Removing $OLDEST_DUMP from system"
	    rm -f $RF/$OLDEST_DUMP
	    actual_size
	done
    fi
}

function shutdown_dump {
    echo ""
    echo "kill tcpdump and exit"
    kill $TCPDUMP_PID
    sleep 2
    exit 0
}

function main {
    while true
    do
		get_signalling_and_media_ports
		tcpdump_args
		DATE=`date +%Y-%m-%d-%H_%M_%S_%N`
		tcpdump udp $TCPDUMP_ARGS -i any -n -s 0 -vvv -w $RF/session-$DATE.pcap > /dev/null 2>&1 &
		TCPDUMP_PID=`echo $!`
		sleep $INTERVAL
		kill $TCPDUMP_PID
		sleep 2
		dump_size
    done
}

is_running
check_deps
check_config

let "MAX_SIZE_BYTES = $MAX_SIZE * 1024 * 1024"
echo "MAX size in bytes $MAX_SIZE_BYTES"

main
