#!/bin/bash
#
# Run recorder in XVFB
#

# Todo (Igor): try to add handlers on SIGTERM SIGINT SIGKILL to properly close application and cleanup resources
function stop() {
  echo "Got signal SIGINT SIGTERM SIGHUP" >> /tmp/recorder.log
  ps ax | grep -e "[x]vfb"
  exit 0
}

function kill_handler() {
  echo "Got SIGKILL" >> /tmp/recorder.log
}

trap stop SIGTERM SIGINT
trap kill_handler SIGKILL

function run() {
  echo "Run: DISPLAY_NUM $1 ; USERNAME $2 ; PASSWORD $3 ; URL $4 ; LINK $5"
  export DISPLAY=:$1
  Xvfb $DISPLAY -screen 0 1920x1080x24 -ac -nolisten tcp -dpi 96 +extension RANDR -nocursor &
  xvfb_pid=$!
  echo "Started Xvfb: $xvfb_pid"
  local no_sandbox=""
  if [ "$EUID" -eq 0 ]; then
    no_sandbox="--no-sandbox"
  fi
  zapp $no_sandbox --disable-gesture-requirement-for-media-playback --autoplay-policy=no-user-gesture-required --ignore-certificate-errors --disable-gpu --enable-javascript -d --autoupdate=false --mode=meeting --username=$USERNAME --password=$PASSWORD --server-url=$SERVER_URL --user-data-dir=/tmp/$USERNAME-$DISPLAY_NUM $LINK
  echo "Started zapp: $!"
}

function main() {
 while getopts d:u:p:s:l: param; do
    case "${param}" in
      d) DISPLAY_NUM=${OPTARG};;
      u) USERNAME=${OPTARG};;
      p) PASSWORD=${OPTARG};;
      s) SERVER_URL=${OPTARG};; 
      l) LINK=${OPTARG};;
     \?) echo "Invalid argument"
         exit 1;;
      :) echo "Missing option argument for -$OPTARG" >&2; exit 1;;		 
    esac    
 done
 if [ -z ${DISPLAY_NUM} ]; then
   echo "Missed DISPLAY_NUM param -d"
   exit 1
 fi
 if [ -z ${USERNAME} ]; then
   echo "Missed username param -u"
   exit 1
 fi
 if [ -z ${PASSWORD} ]; then
   echo "Missed password param -p" 
   exit 1
 fi
 if [ -z ${SERVER_URL} ]; then
   echo "Missed server url param -s"
   exit 1
 fi
 if [ -z ${LINK} ]; then
   echo "Missed link param -l"
   exit 1
 fi 
 run $DISPLAY_NUM $USERNAME $PASSWORD $SERVER_URL $LINK
}


main "$@"
