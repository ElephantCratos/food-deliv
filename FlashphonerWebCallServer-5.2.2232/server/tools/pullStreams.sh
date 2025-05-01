#!/bin/bash

WS_URL=$1
REST_URL=$2
LOCAL_STREAM=$3
QTY=$4
STOP=false
SHOW=false
LOCAL_CONFIG="$HOME/.config/pullStreams"
LOCAL_DATA_FILE="playingStreams"
LOCAL_DATA_CONTENT=""

function usage() {
    echo -e ""
    echo -e "Pull all the streams from a server to test by a number of subscribers per each stream"
    echo -e "Usage:"
    echo -e ""
    echo -e "Pull all the streams from a server:"
    echo -e "pullStreams.sh <server-ws-url> <server-rest-url> <local-stream-name-prefix> [streams-quantity]"
    echo -e ""
    echo -e "Stop pulling the streams from the server by the local stream name prefix:"
    echo -e "pushStreams.sh <server-ws-url> <server-rest-url> <local-stream-name-prefix> stop"
    echo -e ""
    echo -e "Stop all the streams pulling from the server:"
    echo -e "pullStreams.sh <server-ws-url> stop"
    echo -e ""
    echo -e "Show all the pulled streams:"
    echo -e "pullStreams.sh show"
    echo -e ""
    echo -e "Parameters:"
    echo -e "server-ws-url\t\t\tA remote server websocket URL, for example \033[3mws://test.flashphoner.com:8080\033[m (required)"
    echo -e "server-rest-url\t\t\tA remote server REST URL, for example \033[3mhttp://test.flashphoner.com:8081\033[m (required)"
    echo -e "local-stream-name-prefix\tA local stream name prefix, for example \033[3mlocalStream\033[m (required)"
    echo -e "streams-quantity\t\tSubscribers quantity to play, for example \033[3m10\033[m (optional, default is \033[3m1\033[m)"
    echo -e ""
    echo -e "Dependencies: curl, jq"
    echo -e ""
    echo -e "Note: \033[1mwcs_agent_ssl=true\033[m MUST be enabled on both servers to use secure websocket URL (\033[3mwss://test.flashphoner.com:8443\033[m)"
}

function validateParameters() {
    [[ -z $WS_URL ]] && usage && exit 0

    case "$WS_URL" in
        help|h|-h|--h|--help)
            usage
            exit 0
            ;;
        show)
            SHOW=true
            WS_URL=""
            return 0
            ;;
    esac

    [[ -z $REST_URL ]] && echo "Please set a remote server REST URL" && usage && exit 1

    if [[ "$REST_URL" == "stop" ]]; then
        REST_URL=""
        STOP=true
    else
        [[ -z $LOCAL_STREAM ]] && echo "Please set a local stream name prefix" && usage && exit 1
        [[ -z $QTY ]] && echo "Default quantity 1 is used" && QTY=1
        if [[ "$QTY" == "stop" ]]; then
            STOP=true
        fi
    fi
}

function createLocalDatabase() {
    mkdir -p $LOCAL_CONFIG

    if [[ ! -f $LOCAL_CONFIG/$LOCAL_DATA_FILE ]]; then
        touch $LOCAL_CONFIG/$LOCAL_DATA_FILE
        LOCAL_DATA_CONTENT=""
    fi
}

function addToLocalDatabase() {
    local record=$1
    local isInBase=""
    
    if [[ ! -z $LOCAL_DATA_CONTENT ]]; then
        isInBase=`echo $LOCAL_DATA_CONTENT | grep "$record" 2> /dev/nul`
        if [[ -z $isInBase ]]; then
            LOCAL_DATA_CONTENT=`echo -e "$LOCAL_DATA_CONTENT\n$record"`
        fi
    else
        LOCAL_DATA_CONTENT="$record"
    fi
}

function readLocalDatabase() {
    createLocalDatabase
    LOCAL_DATA_CONTENT=`cat $LOCAL_CONFIG/$LOCAL_DATA_FILE`
}

function getLocalDBContent() {
    echo "$LOCAL_DATA_CONTENT"
}

function flushLocalDatabase() {
    echo "$LOCAL_DATA_CONTENT" > $LOCAL_CONFIG/$LOCAL_DATA_FILE
}

function removeRecord() {
    local record=$1

    LOCAL_DATA_CONTENT=`echo $LOCAL_DATA_CONTENT | sed "s@$record@@g"`
}

function removeAllRecords() {
    LOCAL_DATA_CONTENT=""
    flushLocalDatabase
}

function play() {
    local remoteStreamsList=`curl --data "{\"published\": true}" -X POST -H 'Content-type: application/json' -s -k $REST_URL/rest-api/stream/find | jq '.[] | .name' 2>/dev/nul`
    local localStream=""
    local pullReport=""

    [[ -z $remoteStreamsList ]] && echo "No streams found on $REST_URL, nothing to play" && exit 1
    for stream in $remoteStreamsList
    do
        stream=`echo $stream | tr -d '"'`
        for ((i = 1; i <= $QTY; i++))
        do
            localStream=$LOCAL_STREAM$i-$stream
            echo "Playing stream $stream from $WS_URL as $localStream"
            pullReport=`curl --data "{\"uri\": \"$WS_URL\", \"localStreamName\": \"$localStream\", \"remoteStreamName\": \"$stream\"}" -X POST -H 'Content-type: application/json' -s http://localhost:8081/rest-api/pull/pull`
            if [[ ! -z $pullReport ]]; then
                echo $pullReport | jq
            else
                addToLocalDatabase "$WS_URL,$REST_URL,$localStream,$stream"
            fi
        done
    done
}

function filterStream() {
    local operation=$1
    local uri=$2
    local restUrl=$3
    local localName=$4
    local remoteName=$5
    local localStreamsList=`curl --data "{\"published\": true}" -X POST -H 'Content-type: application/json' -s -k http://localhost:8081/rest-api/stream/find | jq '.[] | .name' 2>/dev/nul`

    if [[ -z $localStreamsList ]] || [[ ! "$localStreamsList" =~ "$localName" ]]; then
        # Remote stream is not published locally
        false; return
    fi
    if [[ "$operation" == "stop" ]]; then
        if [[ ! -z $WS_URL ]] && [[ ! "$uri" == "$WS_URL" ]]; then
            # Server URI filter is not passed
            false; return
        fi
        if [[ ! -z $LOCAL_STREAM ]] && [[ ! "$localName" =~ ^$LOCAL_STREAM.* ]]; then
            # Local stream name prefix filter is not passed
            false; return
        fi
    fi
    true; return
}

function validateStream() {
    local uri=$1
    local restUrl=$2
    local localName=$3
    local remoteName=$4

    if filterStream play $uri $restUrl $localName $remoteName; then
        echo "Stream $remoteName is playing successfully as $localName from $uri"
        true; return
    fi
    echo "Stream $remoteName playback as $localName from $uri failed"
    false; return
}

function stopStream() {
    local uri=$1
    local restUrl=$2
    local localName=$3
    local remoteName=$4

    if filterStream stop $uri $restUrl $localName $remoteName; then
        echo "Stream $remoteName is playing from $uri as $localName, stopping"
        curl --data "{\"uri\": \"$uri\", \"localStreamName\": \"$localName\", \"remoteStreamName\": \"$remoteName\"}" -X POST -H 'Content-type: application/json' -s http://localhost:8081/rest-api/pull/terminate | jq
        true; return
    fi
    false; return
}

function doAction() {
    local action=$1
    local pullList=`curl -X POST -H 'Content-type: application/json' -s http://localhost:8081/rest-api/pull/find_all | jq '.[] | .uri + "," + .localStreamName + "," + .remoteStreamName' 2>/dev/nul`
    local playingStreams="$(getLocalDBContent)"
    local uri=""
    local localName=""
    local remoteName=""

    [[ -z $pullList ]] && echo "No pulled streams found, nothing to do" && exit 1
    [[ -z $playingStreams ]] && echo "No streams found in local database, nothing to do" && exit 1
    for stream in $playingStreams
    do
        uri=`echo $stream | tr -d '"' | cut -d',' -f1`
        restUrl=`echo $stream | tr -d '"' | cut -d',' -f2`
        localName=`echo $stream | tr -d '"' | cut -d',' -f3`
        remoteName=`echo $stream | tr -d '"' | cut -d',' -f4`
        if [[ "$pullList" =~ "$uri/websocket,$localName,$remoteName" ]] || [[ "$pullList" =~ "$uri,$localName,$remoteName" ]]; then
            if [[ "$action" == "stop" ]]; then
                if stopStream $uri $restUrl $localName $remoteName; then
                    removeRecord "$uri,$restUrl,$localName,$remoteName"
                fi
            elif [[ "$action" == "validate" ]]; then
                if ! validateStream $uri $restUrl $localName $remoteName; then
                    removeRecord "$uri,$restUrl,$localName,$remoteName"
                fi
            elif [[ "$action" == "show" ]]; then
                validateStream $uri $restUrl $localName $remoteName
            fi
        elif [[ "$action" == "validate" ]] || [[ "$action" == "stop" ]]; then
            echo "Stream $localName is not pulled as $remoteName from $uri, removing from local database"
            removeRecord "$uri,$restUrl,$localName,$remoteName"
        fi
    done
}

function main() {
    validateParameters
    readLocalDatabase
    if $STOP; then
        doAction "stop"
        flushLocalDatabase
    elif $SHOW; then
        doAction "show"
    else
        play
        sleep 1
        doAction "validate"
        flushLocalDatabase
    fi
}

main @
