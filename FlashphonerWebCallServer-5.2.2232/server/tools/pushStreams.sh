#!/bin/bash

WS_URL=$1
REST_URL=$2
LOCAL_STREAM=$3
REMOTE_STREAM=$4
QTY=$5
STOP=false
SHOW=false
LOCAL_CONFIG="$HOME/.config/pushStreams"
LOCAL_DATA_FILE="publishingStreams"
LOCAL_DATA_CONTENT=""

function usage() {
    echo -e ""
    echo -e "Push a stream from the load server to a server to test"
    echo -e "Usage:"
    echo -e ""
    echo -e "Push a local stream to a server:"
    echo -e "pushStreams.sh <server-ws-url> <server-rest-url> <local-stream-name> <remote-stream-name-prefix> [streams-quantity]"
    echo -e ""
    echo -e "Stop the local stream publishing to the server:"
    echo -e "pushStreams.sh <server-ws-url> <server-rest-url> <local-stream-name> <remote-stream-name-prefix> stop"
    echo -e ""
    echo -e "Stop all the streams publishing to the server:"
    echo -e "pushStreams.sh <server-ws-url> stop"
    echo -e ""
    echo -e "Show all the pushed streams:"
    echo -e "pushStreams.sh show"
    echo -e ""
    echo -e "Parameters:"
    echo -e "server-ws-url\t\t\tA remote server websocket URL, for example \033[3mws://test.flashphoner.com:8080\033[m (required)"
    echo -e "server-rest-url\t\t\tA remote server REST URL, for example \033[3mhttp://test.flashphoner.com:8081\033[m (required)"
    echo -e "local-stream-name\t\tA local published stream name, for example \033[3mlocalStream\033[m (required)"
    echo -e "remote-stream-name-prefix\tA stream name prefix to publish to the remote server, for example \033[3mremoteStream\033[m (required)"
    echo -e "streams-quantity\t\tStreams quantity to publish, for example \033[3m100\033[m (optional, default is \033[3m1\033[m)"
    echo -e ""
    echo -e "Dependencies: curl, jq"
    echo -e ""
    echo -e "Note: \033[1mwcs_agent_ssl=true\033[m MUST be enabled on both servers to use secure websocket URL (\033[3mwss://test.flashphoner.com:8443\033[m)"
}

function validateParameters() {
    local isLocalStream="404"

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
        [[ -z $LOCAL_STREAM ]] && echo "Please set a local stream name" && usage && exit 1
        isLocalStream=`curl --data "{\"published\": true, \"name\": \"$LOCAL_STREAM\"}" -X POST -H 'Content-type: application/json' -s http://localhost:8081/rest-api/stream/find -i -o /dev/null -w '%{http_code}' | grep 200`
        [[ $isLocalStream != "200" ]] && echo "Stream $LOCAL_STREAM is not published locally" && exit 1
        [[ -z $REMOTE_STREAM ]] && echo "Please set a remote stream name prefix" && usage && exit 1
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

function publish() {
    local remoteStream=""
    local pushReport=""

    for ((i = 1; i <= $QTY; i++))
    do
        remoteStream=$REMOTE_STREAM$i
        echo "Publishing stream $LOCAL_STREAM to $WS_URL as $remoteStream"
        pushReport=`curl --data "{\"uri\": \"$WS_URL\", \"localStreamName\": \"$LOCAL_STREAM\", \"remoteStreamName\": \"$remoteStream\"}" -X POST -H 'Content-type: application/json' -s http://localhost:8081/rest-api/pull/push`
        if [[ ! -z $pushReport ]]; then
            echo $pushReport | jq
        else
            addToLocalDatabase "$WS_URL,$REST_URL,$LOCAL_STREAM,$remoteStream"
        fi
    done
}

function filterStream() {
    local operation=$1
    local uri=$2
    local restUrl=$3
    local localName=$4
    local remoteName=$5
    local remoteStreamsList=`curl --data "{\"published\": true}" -X POST -H 'Content-type: application/json' -s -k $restUrl/rest-api/stream/find | jq '.[] | .name' 2>/dev/nul`

    if [[ -z $remoteStreamsList ]] || [[ ! "$remoteStreamsList" =~ "$remoteName" ]]; then
        # Remote stream is not published
        false; return
    fi
    if [[ "$operation" == "stop" ]]; then
        if [[ ! -z $WS_URL ]] && [[ ! "$uri" == "$WS_URL" ]]; then
            # Server URI filter is not passed
            false; return
        fi
        if [[ ! -z $LOCAL_STREAM ]] && [[ ! "$localName" == "$LOCAL_STREAM" ]]; then
            # Local stream name filter is not passed
            false; return
        fi
        if [[ ! -z $REMOTE_STREAM ]] && [[ ! "$remoteName" =~ ^$REMOTE_STREAM.* ]]; then
            # Remote stream name prefix filter is not passed
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

    if filterStream publish $uri $restUrl $localName $remoteName; then
        echo "Stream $localName is publishing successfully as $remoteName to $uri"
        true; return
    fi
    echo "Stream $localName publishing as $remoteName to $uri failed"
    false; return
}

function stopStream() {
    local uri=$1
    local restUrl=$2
    local localName=$3
    local remoteName=$4

    if filterStream stop $uri $restUrl $localName $remoteName; then
        echo "Stream $localName is publishing as $remoteName to $uri, stopping"
        curl --data "{\"uri\": \"$uri\", \"localStreamName\": \"$localName\", \"remoteStreamName\": \"$remoteName\"}" -X POST -H 'Content-type: application/json' -s http://localhost:8081/rest-api/pull/terminate | jq
        true; return
    fi
    false; return
}

function doAction() {
    local action=$1
    local pushList=`curl -X POST -H 'Content-type: application/json' -s http://localhost:8081/rest-api/pull/find_all | jq '.[] | .uri + "," + .localStreamName + "," + .remoteStreamName' 2>/dev/nul`
    local publishingStreams="$(getLocalDBContent)"
    local uri=""
    local restUrl=""
    local localName=""
    local remoteName=""
    
    if [[ -z $pushList ]]; then
        echo "No pushed streams found, nothing to do"
        removeAllRecords
        exit 1
    fi
    if [[ -z $publishingStreams ]]; then
        echo "No streams found in local database, nothing to do"
        exit 1
    fi
    for stream in $publishingStreams
    do
        uri=`echo $stream | tr -d '"' | cut -d',' -f1`
        restUrl=`echo $stream | tr -d '"' | cut -d',' -f2`
        localName=`echo $stream | tr -d '"' | cut -d',' -f3`
        remoteName=`echo $stream | tr -d '"' | cut -d',' -f4`
        if [[ "$pushList" =~ "$uri/websocket,$localName,$remoteName" ]] || [[ "$pushList" =~ "$uri,$localName,$remoteName" ]]; then
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
            echo "Stream $localName is not pushed as $remoteName to $uri, removing from local database"
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
        publish
        sleep 1
        doAction "validate"
        flushLocalDatabase
    fi
}

main @
