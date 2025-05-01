PID=$1
EVENT=$2
APPHOME=$3

echo "Params: PID: $PID EVENT: $EVENT APPHOME: $APPHOME"

WDDIR="$APPHOME/logs/watchdog"

DATE=`date +"%y-%m-%d-%H_%M_%S"`

REPORT_DIR=$WDDIR/report-$DATE

echo "REPORT_DIR: $REPORT_DIR"

mkdir $REPORT_DIR

cd $REPORT_DIR

#readme
echo $EVENT > README
#top
top -n 1 -b > top
#netstat
netstat -nlp | grep java > netstat
#java processes
ps aux | grep java > ps
#copy logs
mkdir logs_snapshot
cp $APPHOME/logs/flashphoner_manager.log logs_snapshot
cp $APPHOME/logs/gc-core.log logs_snapshot
cp $APPHOME/logs/gc-manager.log logs_snapshot
cp $APPHOME/logs/server_logs/flashphoner.log logs_snapshot
cp $APPHOME/logs/error[0-9]*.log logs_snapshot
cp $APPHOME/logs/*.netstat logs_snapshot
#copy configs
mkdir conf
cp $APPHOME/conf/flashphoner.properties conf
cp $APPHOME/conf/watchdog.properties conf
cp $APPHOME/conf/WCS.version conf
#copy bin
mkdir bin
cp $APPHOME/bin/setenv.sh bin
cp $APPHOME/bin/startup.sh bin

if [ $EVENT = "EventScannerDown" ] || [ $EVENT = "SIPRegDoesNotWork" ]; then

    #jstack
    jstack $PID > jstack.$PID
    
    #jmap, heap
    jmap -histo $PID > jmap.$PID
    
    #pmap
    pmap $PID > pmap.$PID
    
    #porcess status
    cat /proc/$PID/status > status.$PID

fi

cd $WDDIR
tar -czf report-$DATE.tar.gz report-$DATE

rm -Rf report-$DATE





