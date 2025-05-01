#!/bin/bash
#
# Script to collect a report about Flashphoner WebCallServer work
#


# Global variables configuration

# Declare global variables
function declareGlobals() {
  declareCommonGlobals
  REPORT_LOG_FILE="$WCS_HOME/logs/report.log"
  LAST_HOURS=3
  REPORTS_HOME="$WCS_HOME/report"
  REPORT_DATE=`date +%Y-%m-%d-%H-%M-%S`
  REPORT_DIR="$REPORTS_HOME/report-$REPORT_DATE"
  WCS_PID=""
}

# Get report log file name
function getReportLogFile() {
  echo "$REPORT_LOG_FILE"
}

# Get last hours to collect logs
function getLastHours() {
  echo "$LAST_HOURS"
}

# Set last hours to collect logs
function setLastHours() {
  local lastHours=$1

  [[ ! -z $lastHours ]] && LAST_HOURS=$lastHours
}

# Get report date
function getReportDate() {
  echo "$REPORT_DATE"
}

# Set report date
function setReportDate() {
  local reportDate=${1:-$(date +%Y-%m-%d-%H-%M-%S)}

  [[ ! -z $reportDate ]] && REPORT_DATE=$reportDate
}

# Get report directory
function getReportDir() {
  echo "$REPORT_DIR"
}

# Set report directory
function setReportDir() {
  local reportDir=${1:-$(getReportsHome)/report-$(getReportDate)}

  [[ ! -z $reportDir ]] && REPORT_DIR=$reportDir
}

# Get WCS pid
function getWcsPid() {
  echo "$WCS_PID"
}

# Set WCS pid
function setWcsPid() {
  local pid=$1

  [[ ! -z $pid ]] && WCS_PID=$pid
}

# Get reports home dir
function getReportsHome() {
  echo "$REPORTS_HOME"
}


# Standard error codes 

# Declare error codes constans
function declareErrors() {
  EOK=0
  EPERM=1
  ENOENT=2
  ESRCH=3
  EINTR=4
  EIO=5
  ENXIO=6
  E2BIG=7
  ENOEXEC=8
  EBADF=9
  ECHILD=10
  EAGAIN=11
  ENOMEM=12
  EACCES=13
  EFAULT=14
  ENOTBLK=15
  EBUSY=16
  EEXIST=17
  EXDEV=18
  ENODEV=19
  ENOTDIR=20
  EISDIR=21
  EINVAL=22
  ENFILE=23
  EMFILE=24
  ENOTTY=25
  ETXTBSY=26
  EFBIG=27
  ENOSPC=28
  ESPIPE=29
  EROFS=30
  EMLINK=31
  EPIPE=32
  EDOM=33
  ERANGE=34

  ERRORS_STRINGS=()
  ERRORS_STRINGS[$EOK]="OK"
  ERRORS_STRINGS[$EPERM]="Operation not permitted"
  ERRORS_STRINGS[$ENOENT]="No such file or directory"
  ERRORS_STRINGS[$ESRCH]="No such process"
  ERRORS_STRINGS[$EINTR]="Interrupted system call"
  ERRORS_STRINGS[$EIO]="I/O error"
  ERRORS_STRINGS[$ENXIO]="No such device or address"
  ERRORS_STRINGS[$E2BIG]="Argument list too long"
  ERRORS_STRINGS[$ENOEXEC]="Exec format error"
  ERRORS_STRINGS[$EBADF]="Bad file number"
  ERRORS_STRINGS[$ECHILD]="No child processes"
  ERRORS_STRINGS[$EAGAIN]="Try again"
  ERRORS_STRINGS[$ENOMEM]="Out of memory"
  ERRORS_STRINGS[$EACCES]="Permission denied"
  ERRORS_STRINGS[$EFAULT]="Bad address"
  ERRORS_STRINGS[$ENOTBLK]="Block device required"
  ERRORS_STRINGS[$EBUSY]="Device or resource busy"
  ERRORS_STRINGS[$EEXIST]="File exists"
  ERRORS_STRINGS[$EXDEV]="Cross-device link"
  ERRORS_STRINGS[$ENODEV]="No such device"
  ERRORS_STRINGS[$ENOTDIR]="Not a directory"
  ERRORS_STRINGS[$EISDIR]="Is a directory"
  ERRORS_STRINGS[$EINVAL]="Invalid argument"
  ERRORS_STRINGS[$ENFILE]="File table overflow"
  ERRORS_STRINGS[$EMFILE]="Too many open files"
  ERRORS_STRINGS[$ENOTTY]="Not a typewriter"
  ERRORS_STRINGS[$ETXTBSY]="Text file busy"
  ERRORS_STRINGS[$EFBIG]="File too large"
  ERRORS_STRINGS[$ENOSPC]="No space left on device"
  ERRORS_STRINGS[$ESPIPE]="Illegal seek"
  ERRORS_STRINGS[$EROFS]="Read-only file system"
  ERRORS_STRINGS[$EMLINK]="Too many links"
  ERRORS_STRINGS[$EPIPE]="Broken pipe"
  ERRORS_STRINGS[$EDOM]="Math argument out of domain of func"
  ERRORS_STRINGS[$ERANGE]="Math result not representable"
}

# Get an error string by code
function strError() {
  local errorCode=$1
  local errorString="No description"

  if [ $errorCode -ge $EOK ] && [ $errorCode -le $ERANGE ]; then
    errorString="${ERRORS_STRINGS[$errorCode]}"
  fi
  echo "$errorString"
}


# Common global variables configuration

# Declare common global variables
function declareCommonGlobals() {
  declareErrors
  PRODUCT=FlashphonerWebCallServer
  LOCAL_DIR=/usr/local
  WCS_HOME="$LOCAL_DIR/$PRODUCT"
  FLASHPHONER_USER="flashphoner"
  SILENT_MODE=${SILENT_MODE:-false}
  SITE=${SITE:-flashphoner.com}
  MIN_SPACE_THRESHOLD=${MIN_SPACE_THRESHOLD:-1048576}
  MAIN_PID_FILE=${MAIN_PID_FILE:-FlashphonerMainWebCallServer.pid}
  MAIN_BRANCH=5.2
}

# Get product name
function getProduct() {
  echo "$PRODUCT"
}

# Get local directory to install
function getLocal() {
  echo "$LOCAL_DIR"
}

# Get WCS home directory
function getHome() {
  echo "$WCS_HOME"
}

# Get WCS user name
function getUser() {
  echo "$FLASHPHONER_USER"
}

# Is installation non-interactive
function isSilent() {
  $SILENT_MODE; return
}

# Set install to be non-interactive
function setSilent() {
  SILENT_MODE=true
}

# Get site domain name
function getSite() {
  echo "$SITE"
}

# Get site URL
function getSiteUrl() {
  echo "https://$SITE"
}

# Get minimal free space to install/update/work
function getMinSpaceThreshold() {
  echo "$MIN_SPACE_THRESHOLD"
}

# Get main process pid file name
function getMainPidFile() {
  echo "$MAIN_PID_FILE"
}

# Get main product branch
function getMainBranch() {
  echo "$MAIN_BRANCH"
}


# Logging functions

# Declare logging variables
function declareLog() {
  local logFile=$1

  DEBUG=${DEBUG:-false}
  DEFAULT_LOG=${DEFAULT_LOG:-}

  setDefaultLogFile $logFile
}

# Check if debug log enabled
function isDebugEnabled() {
  if [[ ! -z $DEBUG ]]; then
    $DEBUG; return
  else
    false; return
  fi
}

# Enable debug logging
function enableDebug() {
  DEBUG=true
}

# Disable debug logging
function disableDebug() {
  DEBUG=false
}

# Get default log file
function getDefaultLogFile() {
  echo "$DEFAULT_LOG"
}

# Set default log file
function setDefaultLogFile() {
  local logFile=$1

  [[ -z $logFile ]] && return 1
  if checkLogPath $logFile; then
    DEFAULT_LOG=$logFile
  fi
}

# # Check if log file path exists and make the dir if needed
function checkLogPath() {
  local logFile=$1
  local result=0

  [[ -z $logFile ]] && return 1
  logPath=$(dirname "$logFile")
  if [[ ! -d $logPath ]]; then
    mkdir -p $logPath
    result=$?
  fi
  return $result
}

# Form log timestamp
function logDate() {
  local dateToLog=`echo "[$(date '+%Y-%m-%d %H:%M:%S')]"`
 
  echo "$dateToLog"
}

# Write to a log file
function writeLog() {
  local level=$1
  local message=$2
  local logFile=$3
  local owner=$(getCurrentUser)
  local logPath=""
  local callerFuncName=""

  [[ -z $logFile ]] && logFile=$(getDefaultLogFile)
  if [[ "$level" == "" || "$message" == "" || "$logFile" == "" ]]; then
    return 1
  fi
  if [[ -f $logFile ]]; then
    owner=$(stat -c "%U" $logFile)
  else
    checkLogPath $logFile
    [[ $? -ne 0 ]] && return 1
  fi
  # Determine caller function name
  for callerFuncName in ${FUNCNAME[@]}; do
    if [[ "$callerFuncName" == "${FUNCNAME[0]}" || "$callerFuncName" =~ ^log  || "$callerFuncName" =~ ^display  ]]; then
      continue
    else
      break
    fi
  done
  if [[ "$owner" != "$(getCurrentUser)" && "$(command -v sudo 2>/dev/null)" != "" ]]; then
    # The log file is owned by someone else, try to sudo to it if sudo available
    sudo -u $owner echo -e "$(logDate) $level $callerFuncName - $message" >> $logFile
  else
    echo -e "$(logDate) $level $callerFuncName - $message" >> $logFile
  fi
 }

# Write INFO level message
function logInfo() {
  local message=$1
  local logFile=$2

  writeLog "INFO" "$message" "$logFile"
}

# Write WARN level message
function logWarn() {
  local message=$1
  local logFile=$2

  writeLog "WARN" "$message" "$logFile"
}

# Write ERROR level message
function logError() {
  local message=$1
  local logFile=$2

  writeLog "ERROR" "$message" "$logFile"
}

# Write DEBUG level message
function logDebug() {
  local message=$1
  local logFile=$2

  if isDebugEnabled; then
    writeLog "DEBUG" "$message" "$logFile"
  fi
}

# Display INFO level message to user
function displayInfo() {
  local message=$1
  local logFile=$2

  echo -e "$message"
  writeLog "INFO" "$message" "$logFile"
}

# Display WARN level message to user
function displayWarn() {
  local message=$1
  local logFile=$2

  echo -e "WARN: $message"
  writeLog "WARN" "$message" "$logFile"
}

# Display ERROR level message to user
function displayError() {
  local message=$1
  local logFile=$2

  echo -e "ERROR: $message"
  writeLog "ERROR" "$message" "$logFile"
}


# Various helper functions

# Check if user exists in system
function isUser() {
  local user=$1
  
  if [[ -z $user ]]; then
    user=$(getUser)
  fi
  if [[ "$(getent passwd $user)" =~ ^$user ]]; then
    true; return
  else
    false; return
  fi
}

# Get current user running script
function getCurrentUser() {
  echo "$(whoami)"
}

# Get daemon type
function getDaemonType() {
  local daemonType=`ps --no-headers -o comm 1`
  echo "$daemonType"
} 

# Check if service is running
function isService() {
  # Check service only if systemd used #WCS-3034
  if [[ $(getDaemonType) != 'systemd' ]]; then
    false; return
  fi
  if systemctl is-active --quiet webcallserver.service; then
    true; return
  else
    false; return
  fi
}

# Check if the script is running from root
function isScriptRunningFromRoot() {
  if [[ "$(getCurrentUser)" == "root" ]]; then
    true; return
  else
    false; return
  fi
}

# Set file system item owner to user
function setOwner() {
  local user=$1
  local object=$2
  local rootMode=$3
  local group=""
  local changeUser=false

  if [[ "$user" == "" || "$object" == "" ]]; then
    return 1
  fi
  if [[ -z $rootMode ]]; then
    changeUser=false
  elif [[ "$rootMode" == "false" || "$rootMode" == "1" ]]; then
    changeUser=true
  fi
  [[ ! isScriptRunningFromRoot ]] && return 1
  [[ ! -e $object ]] && return 1
  group=$(id -gn $user)
  # Set owner to root if the main process starts from flashphoner user to prevent systemd errors #WCS-3682
  if $changeUser; then
    user="root"
  fi
  logDebug "Set $object permissions to $user:$group (rootMode: $rootMode)"
  setOwnerAndGroup $user $group $object
  # Make an object readable and writable by group #WCS-3682 #WCS-3718
  chmod -R g+rw $object > /dev/null 2>&1
}

# Set file system item owner to user and group
function setOwnerAndGroup() {
  local user=$1
  local group=$2
  local object=$3

  if [[ "$user" == "" || "$object" == "" ]]; then
    return 1
  fi
  [[ ! isScriptRunningFromRoot ]] && return 1
  [[ ! -e $object ]] && return 1
  chown -RHL $user:$group $object > /dev/null 2>&1
}

# Get Java version
function getJavaVersion() {
  local javaCmd=`command -v java 2>/dev/null`
  local jdkVersion=""
  local javaFullVersion=""
  local javaMajor=0
  local javaMinor=0
  local javaVersion=0

  if [[ -z $javaCmd ]]; then
    echo "$javaVersion"
    return 1
  fi
  jdkVersion=`$javaCmd -version 2>&1`
  javaFullVersion=`echo $jdkVersion | head -1 | cut -d" " -f 3 | tr -d \"`
  javaMajor=`echo $javaFullVersion | cut -d \. -f 1`
  if [[ $javaMajor -eq 1 ]]; then
    javaMinor=`echo $javaFullVersion | cut -d \. -f 2`
    if [[ $javaMinor -ge 8  ]]; then
      javaVersion=$javaMinor
    fi
  elif [[ $javaMajor -ge 8 ]]; then
    javaVersion=$javaMajor
  fi
  logDebug "Java version $javaVersion"
  echo "$javaVersion"
}

# Get user running the process by pid
function getUserByPid() {
  local pid=$1
  local user=`ps -o uname= -p "$pid"`
 
  logDebug "Process $pid running as user $user"
  echo "$user"
}

# Check if user is owner of the object
function isOwned() {
  local object=$1
  local user=$2
  local owner=""
  
  if [[ -z $user ]]; then
    user=$(getUser)
  fi
  owner=$(stat -c "%U" "$object")
  if [[ $owner == $user ]]; then
    true; return
  else
    false; return
  fi
}

# Get next file copy name as file.0, file.1 etc
function getNextCopyName() {
  local pattern=$1
  local lastFile=""
  local lastSuffix=""
  local newSuffix=0
  local nextCopyName=""
  
  if [[ -z $pattern ]]; then
    echo ""
    return 1
  fi
  lastFile=`ls $pattern.[0-9]* | sort -Vr | head -1`
  [[ -z $lastFile ]] && lastFile=`ls $pattern | sort -Vr | head -1`
  [[ -z $lastFile ]] && lastFile=$pattern
  lastSuffix=`echo $lastFile | grep -o '[^.]*$'`
  logDebug "lastSuffix: $lastSuffix"
  if [[ $lastSuffix =~ ^[0-9]+$ ]]; then
    newSuffix=$((lastSuffix+1))
  else
    lastSuffix=""
  fi
  logDebug "lastSuffix: $lastSuffix, newSuffix: $newSuffix"
  if [[ ! -z $lastSuffix ]]; then
    nextCopyName=`echo $lastFile | sed 's/\(.*\)'$lastSuffix'/\1'$newSuffix'/'`
  else
    nextCopyName=$lastFile.$newSuffix
  fi
  
  echo "$nextCopyName"
}

# Check if user can write the object
function isWritable() {
  local object=$1
  local user=$2
  local asUser=""
  
  if [[ -z $user ]]; then
    user=$(getUser)
  fi
  if [[ "$(getCurrentUser)" != $user ]]; then
    asUser="sudo -u $user"
  fi
  if $asUser test -w "$object"; then
    true; return
  else
    false; return
  fi
}

# Check if site available
function isSiteUp() {
  ping -c1 -W1 -q $(getSite) &>/dev/null
  if [[ $? -eq 0 ]]; then
    true; return
  else
    false; return
  fi
}

# Check free space available
function checkFreeSpace() {
  local spaceEstimated=$1
  local fileToCheck=$2
  local spaceEstimatedKbytes=0
  local factor=0
  local dirToCheck=$(getLocal)
  
  [ -z $spaceEstimated ] && return 1

  [ ! -z $fileToCheck ] && dirToCheck=$(dirname $fileToCheck)

  case "${spaceEstimated: -1}" in
    k|K)
      factor=1;;
    m|M)
      factor=1024;;
    g|G)
      factor=1048576;;
  esac

  if [ $factor -gt 0 ]; then
    spaceEstimated=${spaceEstimated:0:-1}
    spaceEstimatedKbytes=$((spaceEstimated*factor))
  else
    spaceEstimatedKbytes=$spaceEstimated
  fi

  spaceAvailable=`df -k $dirToCheck | awk 'NR==2 { print $4 }'`

  if [ $spaceAvailable -le $spaceEstimatedKbytes ]; then
    false; return
  fi
  true; return
}

# Get server pid from file or directly from the process
function getServerPid() {
  local pidFileRoot=/var/run/$(getMainPidFile)
  local pidFileNonRoot=$(getHome)/bin/$(getMainPidFile)
  local pid=""

  if [ -f $pidFileRoot ]; then
    pid=$(cat $pidFileRoot)
  elif [ -f $pidFileNonRoot ]; then
    pid=$(cat $pidFileNonRoot)
  else
    logWarn "Server is probably not running, looking for the process"
    pid=$(pgrep -fn com.flashphoner.server.Server)
  fi

  echo "$pid"
}


# Command detection functions

# Return full command by name or emty string if not exist
function getCommand() {
  local shortCmd=$1
  local fullCmd=""
  
  if [[ ! -z $shortCmd ]]; then
    fullCmd=`command -v $shortCmd 2>/dev/null`
  fi
  echo "$fullCmd"
}

# Check if command exists
function isCommandExists() {
  local shortCmd=$1
  local fullCmd=$(getCommand $shortCmd)

  if [[ -z $fullCmd ]]; then
    logError "Command '$shortCmd' not found"
    false; return
  fi
  true; return
}


# Various Java functions functions

# Run jcmd to start GC as user depending on WCS running from
function runGcAsUser() {
  local user=$1
  local cmd=$2
  local pid=$3
  local javaVersion=$(getJavaVersion)

  if [[ "$user" == "root" ]]; then
    $cmd $pid GC.run >> $(getDefaultLogFile) 2>&1
    return $?
  fi
  if [[ $javaVersion -gt 8 && isService ]]; then
    # Run jcmd from root for service
    runGcAsUser root $cmd $pid
  else
    sudo -u $user $cmd $pid GC.run >> $(getDefaultLogFile) 2>&1
  fi
  return $?
}

# Run jcmd to get heap dump as user depending on WCS running from
function runDumpAsUser() {
  local user=$1
  local cmd=$2
  local pid=$3
  local dumpFile=$4
  local javaVersion=$(getJavaVersion)
  local result=$EOK

  [ -z $dumpFile ] && return $EINVAL
  if [[ "$user" == "root" ]]; then
    $cmd $pid GC.heap_dump $dumpFile >> $(getDefaultLogFile) 2>&1
    result=$?
  elif [[ $javaVersion -gt 8 && isService ]]; then
    # Run jcmd from root for service
    runDumpAsUser root $cmd $pid $dumpFile
    result=$?
  else
    if isWritable $(dirname $dumpFile) $user ; then 
      sudo -u $user $cmd $pid GC.heap_dump $dumpFile >> $(getDefaultLogFile) 2>&1
      result=$?
    else
      result=$EACCESS
    fi
  fi
  if [[ ! -f $dumpFile ]] && [[ $result -eq $EOK ]]; then
    result=$ENOENT
  fi
  return $result
}

# Run GC using jcmd
function runGc() {
  local cmd="jcmd"
  local fullCmd=""
  local pid=$1
  local user=$(getUserByPid $pid)

  ! isCommandExists $cmd && return $ENOENT
  fullCmd=$(getCommand $cmd)
  runGcAsUser $user $fullCmd $pid
  return $?
}

# Collect heap dump using jmap
function getHeapDump() {
  local cmd="jcmd"
  local fullCmd=""
  local pid=$1
  local dumpFile=$2
  local user=$(getUserByPid $pid)
  local dumpFileDir=$(dirname $dumpFile)
  local dumpSize=$(getDumpSize)

  ! isCommandExists $cmd && return $ENOENT
  fullCmd=$(getCommand $cmd)
  ! test -d $dumpFileDir && mkdir -p $dumpFileDir
  if ! test -z $dumpSize && ! checkFreeSpace $dumpSize $dumpFileDir; then
    return $ENOSPC
  fi
  if test -f $dumpFile; then
    return $EEXIST
  fi
  runDumpAsUser $user $fullCmd $pid $dumpFile
  return $?
}

# Get estimated dump size
function getDumpSize() {
  local dumpSize=""
  local coreConfig="$(getHome)/conf/wcs-core.properties"

  dumpSize=$(grep -e "^-Xmx" $coreConfig | cut -f2 -d"x")
  if [[ -z $dumpSize ]]; then
    dumpSize=$(grep -Eo "[[:space:]]-Xmx[a-zA-Z0-9\/]*" $coreConfig | cut -d" " -f 2 | cut -f2 -d"x")
  fi

  echo "$dumpSize"
}

# Run jstack as user depending on WCS running from
function getJstackAsUser() {
  local user=$1
  local cmd=$2
  local pid=$3
  local jstackFile=$4
  local javaVersion=$(getJavaVersion)
  local result=$EOK

  if [[ "$user" == "root" ]]; then
    $cmd $pid > $jstackFile
    result=$?
  elif [[ $javaVersion -gt 8 && isService ]]; then
    # Run jstack from root for service
    getJstackAsUser root $fullCmd $pid $jstackFile
    result=$?
  else
    if isWritable $(dirname $jstackFile) $user; then
      sudo -u $user $cmd $pid > $jstackFile
      result=$?
    else
      result=$EACCESS
    fi
  fi
  if [[ ! -f $jstackFile ]] && [[ $result -eq $EOK ]]; then
    result=$ENOENT
  fi
  return $result
}

# Collect jstack
function getJstack() {
  local cmd="jstack"
  local fullCmd=""
  local pid=$1
  local jstackFile=$2
  local user=$(getUserByPid $pid)
  local jstackFileDir=$(dirname $jstackFile)

  ! isCommandExists $cmd && return $ENOENT
  fullCmd=$(getCommand $cmd)
  ! test -d $jstackFileDir && mkdir -p $jstackFileDir
  getJstackAsUser $user $fullCmd $pid $jstackFile
  return $?
}

# Run jcmd to start flight recording as user depending on WCS running from
function runJfrAsUser() {
  local user=$1
  local cmd=$2
  local pid=$3
  local duration=$4
  local jfrFile=$5
  local javaVersion=$(getJavaVersion)
  local result=$EOK

  if [[ "$user" == "root" ]]; then
    $cmd $pid JFR.start duration=$duration filename=$jfrFile >> $(getDefaultLogFile) 2>&1
    result=$?
  elif [[ $javaVersion -gt 8 && isService ]]; then
    # Run jcmd from root for service
    runJfrAsUser root $cmd $pid $duration $jfrFile
    result=$?
  else
    if isWritable $(dirname $jfrFile) $user; then
      sudo -u $user $cmd $pid JFR.start duration=$duration filename=$jfrFile >> $(getDefaultLogFile) 2>&1
      result=$?
    else
      result=$EACCESS
    fi
  fi
  if [[ ! -f $jfrFile ]] && [[ $result -eq $EOK ]]; then
    result=$ENOENT
  fi
  return $result
}

# Collect flight recording
function getJfr() {
  local cmd="jcmd"
  local fullCmd=""
  local pid=$1
  local duration=$2
  local jfrFile=$3
  local user=$(getUserByPid $pid)
  local jfrFileDir=$(dirname $jfrFile)

  ! isCommandExists $cmd && return $ENOENT
  fullCmd=$(getCommand $cmd)
  ! test -d $jfrFileDir && mkdir -p $jfrFileDir
  runJfrAsUser $user $fullCmd $pid $duration $jfrFile
  return $?
}


# Server logs gathering functions

# Collect logs
function get_logs() {
  local dateFrom=`date "+%Y-%m-%d %H" -d "$(getLastHours) hour ago"`
  local dateTo=`date "+%Y-%m-%d %H" -d "1 hour"`

  mkdir -p $(getReportDir)/logs/server_logs
  mkdir -p $(getReportDir)/logs/client_logs
  cd $(getHome)/logs
  find . -newermt "$dateFrom" ! -newermt "$dateTo" -exec cp --parents -t $(getReportDir)/logs {} + > /dev/null 2>&1
  return 0
}



# Server network interface statistics gathering functions

# Collect netstat output
function get_netstat() {
  local cmd="netstat"
  local fullCmd=""

  ! isCommandExists $cmd && return 1
  fullCmd=$(getCommand $cmd)
  $fullCmd -antpul > "$(getReportDir)/$cmd.log"
  return $?
}



# Opened objects statistics gathering functions

# Collect lsof output
function get_lsof() {
  local cmd="lsof"
  local fullCmd=""

  ! isCommandExists $cmd && return 1
  fullCmd=$(getCommand $cmd)
  $fullCmd -P -p $(getWcsPid) > "$(getReportDir)/lsof.log"
  return $?
}



# Memory statistics gathering functions

# Detect pmap keys
function detectPmapKeys() {
  local cmd=$1

  if $cmd -h | grep -e "^[[:space:]]\-X" > /dev/null; then
    cmd="$cmd -X"
  else
    cmd="$cmd -x"
  fi

  echo "$cmd"
}

# Collect pmap output
function get_pmap() {
  local cmd="pmap"
  local fullCmd=""

  ! isCommandExists $cmd && return 1
  fullCmd=$(detectPmapKeys $(getCommand $cmd))
  $fullCmd $(getWcsPid) > "$(getReportDir)/pmap.log"
  return $?
}



# Jstack gathering functions

# Collect jstack output
function get_jstack() {
  local pid=$(getWcsPid)
  local jstackFile=$(getReportDir)/jstack.log

  getJstack $pid $jstackFile
  return $?
}



# Heap dump collection functions

# Run GC then collect heap dump
function get_dump() {
  local pid=$(getWcsPid)
  local dumpFileDir=$(getReportDir)/dump
  local dumpFile=$dumpFileDir/$pid.hprof

  ! test -d $dumpFileDir && mkdir -p $dumpFileDir
  isUser && setOwner $(getUser) $dumpFileDir
  runGc $pid && getHeapDump $pid $dumpFile
  return $?
}



# Server configs gathering functions

# Collect configs
function get_conf() {
  local confDir=$(getHome)/conf
  local targetDir=$(getReportDir)/conf

  mkdir -p $targetDir
  cp $confDir/flashphoner.properties $targetDir
  cp $confDir/wcs-core.properties $targetDir
  cp $confDir/log4j.properties $targetDir
  cp $confDir/WCS.version $targetDir
  cp $confDir/*.yml $targetDir
  if [[ "$(echo $confDir/*.sdp)" != "$confDir/*.sdp" ]]; then
    cp $confDir/*.sdp $targetDir
  fi
  return 0
}



# Server system information gathering functions

# Collect system info
function get_sysinfo() {
  local targetDir=$(getReportDir)/sysinfo

  mkdir -p $targetDir
  cat /proc/cpuinfo > "$targetDir/cpuinfo.log"
  cat /proc/meminfo > "$targetDir/meminfo.log"
  df -h > "$targetDir/df.log"
  if isCommandExists ifconfig; then
    ifconfig > "$targetDir/ifcfg.log"
  elif isCommandExists ip; then
    ip a > "$targetDir/ifcfg.log"
  fi
  iptables -nvL > "$targetDir/iptables.log"
  return 0
}



# WCS statistics information gathering functions

# Collect stats info
function get_stats() {
  local config="$(getHome)/conf/flashphoner.properties"
  local httpPort=""
  local statsQuery=""
  local res=0

  httpPort=`sed -n -e "s/^http.port[ \t]*=[ \t]*\(.*\)/\1/p" $config`
  [[ -z $httpPort ]] && httpPort="8081"
  statsQuery="http://localhost:$httpPort/?action=stat&format=json"
  logDebug "$statsQuery"
  if isCommandExists jq; then
    curl -s "$statsQuery" | jq . > $(getReportDir)/stats.json
  else
    curl -s "$statsQuery" > $(getReportDir)/stats.json
  fi
  return $?
}



# Report archiving functions

# Pack report to archive
function get_tar() {
  local tarFile="report_$(getReportDate).tar.gz"

  ! isCommandExists tar && return 1
  logInfo "Packing report to archive $tarFile"
  cd $(getReportsHome)
  tar -zcf $tarFile $(basename $(getReportDir)) > /dev/null 2>&1
  return $?
}


# Main module

# Usage displaying
function usage() {
  echo "Usage: $(basename $0) [OPTIONS]"
  echo "Default report will include netstat, lsof, logs between now and 3 hours ago, pmap, jstack"
  echo -e "  --conf\t\t copy configuration"
  echo -e "  --dump\t\t make heap dump"
  echo -e "  --sysinfo\t\t gather system info"
  echo -e "  --stats\t\t gather WCS statistics"
  echo -e "  --tar\t\t\t tar report"
  echo -e "  --hours <hours>\t hours count to collect lates logs (3 by default)"
  echo -e "  --help\t\t help"
  return 1
}

# Check the process and prepare report dir
function prepareToReport() {
  local pid=""
  local msgMustBeRoot="The script must be run as root"

  if [[ "$(getCurrentUser)" != "root" ]]; then
    logError "$msgMustBeRoot"
    echo -e "$msgMustBeRoot:\n sudo report.sh"
    false; return
  fi

  pid=$(getServerPid)
  if [[ -z $pid ]]; then
    displayError "Server is not running"
    false; return
  fi

  setWcsPid $pid
  setReportDate `date +%Y-%m-%d-%H-%M-%S`
  setReportDir
  mkdir -p "$(getReportDir)"
  if isUser $(getUser); then
    setOwner $(getUser) $(getReportDir)/
  fi
  logInfo "Collecting report $(getReportDir)"
}

# Display scheduled actions
function showSchedule() {
  local report=""
  local dumpSize=""

  logInfo "Scheduled report:"
  echo -e "Scheduled report:\n"
  for report in $@; do
    if [[ "$report" == "dump" ]]; then
      dumpSize=$(getDumpSize)
      echo "* $report [estimated $dumpSize]"
      logInfo "Task: dump, estimated dump size: $dumpSize"
    else
      logInfo "Task: $report"
      echo "* $report"
    fi
  done
}

# Pack report to tar.gz
function packReport() {
  local tarFile="report_$(getReportDate).tar.gz"

  logInfo "Packing report to archive $tarFile"
  cd $(getReportsHome)
  tar -zcf $tarFile $(basename $(getReportDir)) > /dev/null 2>&1
  return $?
}

# Main script function
function main() {
  local startTime=$(date +%s)
  local scheduledReport=(
    logs
    netstat
    lsof
    pmap
    jstack
  )
  local tarReport=false
  local taskResult=0
  local dumpSize

  declareGlobals
  declareLog $(getReportLogFile)
  # Set log file owner
  if isUser $(getUser) && isScriptRunningFromRoot; then
    touch $(getReportLogFile)
    setOwner $(getUser) $(getReportLogFile)
  fi

  # Parse command line
  while [[ $# -gt 0 ]]; do
    case "$1" in
      --help)
        usage
        return 1
        ;;
      --dump)
        dumpSize=$(getDumpSize)
        if checkFreeSpace $dumpSize; then
          scheduledReport+=(dump)
        else
          displayWarn "No free space available to collect heap dump of $dumpSize"
        fi
        ;;
      --sysinfo)
        scheduledReport+=(sysinfo);;
      --conf)
        scheduledReport+=(conf);;
      --stats)
        scheduledReport+=(stats);;
      --tar)
        tarReport=true;;
      --hours)
        shift
        setLastHours $1;;
      --debug)
        enableDebug;;
      *)
        usage
        return 1
        ;;
    esac
    shift 
  done
  if ! prepareToReport; then
    return 1
  fi
  if $tarReport; then
    scheduledReport+=(tar)
  fi
  showSchedule ${scheduledReport[@]}
  echo -e "\nProgress: \n"
  for task in ${scheduledReport[@]}; do
    echo "[PROGRESS] $task"
    tput cuu1
    if get_$task; then
      tput el
      echo "[DONE] $task"
    else
      taskResult=$?
      tput el
      echo "[FAILED] $task"
      logError "Task $task failed, result $taskResult"
    fi
  done
  endTime=$(date +%s)
  echo "Report complete in $((endTime - startTime)) seconds. Check $(getReportDir)"
  logInfo "Report $(getReportDir) collected"
  if isUser $(getUser); then
    setOwner $(getUser) $(getReportsHome)/
    setOwner $(getUser) $(getReportLogFile)
  fi
}

main "$@"

exit $?


