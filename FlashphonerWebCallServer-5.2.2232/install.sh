#!/bin/bash
#
# Install script for Flashphoner WebCallServer
#



# Global variables configuration

# Declare global variables
function declareGlobals() {
  declareCommonGlobals
  INSTALLER_DIR=${INSTALLER_DIR:-$(pwd)}
  INSTALL_LOG=${INSTALL_LOG:-$INSTALLER_DIR/install.log}
  UPDATE_LOG=${UPDATE_LOG:-$WCS_HOME/logs/update.log}
  VERSION_WITH_HASH=${VERSION_WITH_HASH:-$(cat $INSTALLER_DIR/WCS.version)}
  VERSION=${VERSION:-${VERSION_WITH_HASH%-*}}
  WCS_HOME_FULL="$WCS_HOME-$VERSION"
}

# Get installer dir
function getInstallerDir() {
  echo "$INSTALLER_DIR"
}

# Get install log file
function getInstallLogFile() {
  echo "$INSTALL_LOG"
}

# Get update log file
function getUpdateLogFile() {
  echo "$UPDATE_LOG"
}

# Get version to install
function getVersion() {
  echo "$VERSION"
}

# Get version with hash to install
function getVersionWithHash() {
  echo "$VERSION_WITH_HASH"
}

# Get major version to install
function getMajorVersion() {
  local majorVersion=$(echo $VERSION | cut -d. -f1)

  echo "$majorVersion"
}

# Get minor version to install
function getMinorVersion() {
  local minorVersion=$(echo $VERSION | cut -d. -f2)

  echo "$minorVersion"
}

# Get branch version
function getBranchVersion() {
  local branchVersion=${VERSION%.*}

  echo "$branchVersion"
}


# Get full installation path including version
function getFullHome() {
  echo "$WCS_HOME_FULL"
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


# Common user interface functions

# Display a message to console if allowed
function informUser() {
  local message=$1

  if ! isSilent; then
    echo -e "$message"
  fi
}

# Read user input
function askUser() {
  local message=$@
  local prompt=""
  local input=""

  if [[ ! -z $message ]]; then
    read -p "$message " input < /dev/tty
  else
    read input < /dev/tty
  fi
  echo "$input"
}

# Read user input with predefined answers
function askUserToChoose() {
  local message=$@
  local answers=""
  local input=""

  if [[ ! -z $message ]]; then
    answers=$(echo "$message" | sed 's/.*\[\(.*\)\].*/\1/' | tr '/' ' ')
    while true
    do
      input=$(askUser $message)
      if [[ "$answers" =~ $input ]]; then
        break
      fi
    done
  else
    input=$(askUser)
  fi
  echo "$input"
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


# Functions to manage WCS process

# Try to stop service
function tryStopService() {
  local pidFile=$1

  [[ -z $pidFile ]] && return 1
  if [[ -f $pidFile && $(pgrep -F $pidFile) ]]; then
    if [[ "$(getDaemonType)" == "systemd" ]]; then
      systemctl stop webcallserver > /dev/null 2>&1
    else
      service webcallserver stop > /dev/null 2>&1
    fi
  fi
}

# Try to stop application
function tryStopApp() {
  local pidFile=$1

  [[ -z $pidFile ]] && return 1
  if [[ -f $pidFile && $(pgrep -F $pidFile) ]]; then
    informUser "Service did not stop, stopping application"
    $(getHome)/bin/webcallserver stop > /dev/null 2>&1
  fi
}

# Try to stop process
function tryStopProcess() {
  local pidFile=$1
  local pid=""
  local msgForceStop=""

  pid=$(pgrep -fn com.flashphoner.server.Server)
  if [[ ! -z $pid ]]; then
    msgForceStop="Server process found (pid $pid), stopping forcefully"
    informUser "$msgForceStop"
    logWarn "$msgForceStop"
    kill -9 $pid
    [[ ! -z $pidFile && -f $pidFile ]] && rm -rf $pidFile
  fi
}


# Stop running instance
function stopRunningServer() {
  local pidFile=$1
  local msgCantStop=""

  [[ -z $pidFile ]] && return 1
  tryStopService $pidFile
  sleep 1
  tryStopApp $pidFile
  sleep 1
  if [[ -f $pidFile && $(pgrep -F $pidFile) ]]; then
    msgCantStop="Cannot stop Java process $(pgrep -F $pidFile), please kill it forcefully"
    informUser "ERROR: $msgCantStop"
    logError "$msgCantStop"
    return 1
  fi
  # if still there is a process, try to kill it
  tryStopProcess
  return 0
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


# File operations

# Copy files with verification
function verifiedCopy() {
  local src=$1
  local dst=$2
  local result=0

  if [[ -z $src || -z $dst ]]; then
    logError "No source or destination defined, can't copy"
    return 1
  fi
  logDebug "Copy $src to $dst with diff verification"
  cp -Rf $src $dst
  result=$?
  if [[ $result -ne 0 ]]; then
    logError "$src to $dst copying failed: error $result"
    return $result
  fi
  if [[ -f $src && -f $dst ]]; then
    diff -q $src $dst > /dev/null 2>&1
  elif [[ -d $src ]]; then
    find $src -type f -exec diff -q {} $dst/$(basename {}) > /dev/null 2>&1 \;
  elif [[ -d $dst ]]; then
    diff -q $src $dst/$(basename $src) > /dev/null 2>&1
  fi
  result=$?
  if [[ $result -ne 0 ]]; then
    logError "$src to $dst copy verification failed: error $result"
  fi
  logDebug "Verified copy result: $result"
  return $result
}


# Message displaying functions

# Display EULA
function displayLicense() {
  local pageFile=""
  local page=""
  local answer=""

  if isSilent || isInstalled; then
    return 0
  fi

  for pageFile in `ls EULA_PAGE* | sort`; do
    page=`cat $pageFile`
    echo "$page"
    echo ""
    echo "Press ENTER if you agree to the license terms. Press Ctrl+C to stop installation."
    answer="$(askUser)"
  done

  until [[ "$answer" == "yes" || "$answer" == "no" ]]; do
    answer="$(askUser 'Do you agree to the license terms? [yes/no]')"
  done
  clear
  if [[ "$answer" == "no" ]]; then
    false; return
  fi
  true; return
}

# Display installation result
function displayResult() {
  local input=""
  local msgIns="Installation complete!"
  local msgUpd="Update complete!      "
  local msgFinish="$msgIns"
  local msgUpdated=""

  if isInstalled; then
    msgFinish="$msgUpd"
    msgUpdated="Updated from $(getPreviousVersion) to $(getVersion)"
    logInfo "$msgUpdated"
    logInfo "$msgUpdated" $(getUpdateLogFile)
  else
    logInfo "$msgFinish"
  fi
  if isSilent; then
    return 0
  fi
  informUser "
************************************************************************************************
*                                                                                              *
*                                   $msgFinish                                     *
*                             Thank you for trying Flashphoner!                                *
*                                                                                              *
*               Please restart $(getProduct) before start the work.                 *
*                  Write us with any questions to support@flashphoner.com                      *
*                                                                                              *
*                                                                                              *
*                                                                                              *
************************************************************************************************
"
  askUser
}

# Prerequisites checking functions

# Check if Java installed
function checkJava() {
  local javaVersion=$(getJavaVersion)
  local msgOldJava=""
  local msgJava="Java detected successfully, version"

  informUser "DETECTING Java version..."
  if [[ $javaVersion -eq 0 ]]; then
    informUser "
ERROR: The Java command (java) could not be found.
Search path: $PATH
In most cases this problem can be fixed by adding a symbolic 
link to the Java command in the /usr/bin directory. 
To do this first execute the command \"which java\" to identify 
the full path to the Java executable. Next, create a symbolic
link to this file with the command
\"ln -sf [path-to-java] /usr/bin/java\" where [path-to-java] is
the path returned by the \"which\" command.
"
    logError "No Java found!"
    false; return
  elif [[ $javaVersion -lt 8 ]]; then
    msgOldJava="Java version is $javaVersion, must be at least 8!"
    informUser "ERROR: $msgOldJava"
    logError "$msgOldJava"
    false; return
  else
    msgJava="$msgJava $javaVersion"
    informUser "- $msgJava"
    logInfo "$msgJava"
    true; return
  fi
}

# Get Java architecture id (32 or 64)
function getJavaArchId() {
  local javaCmd=`command -v java 2>/dev/null`
  local jdkVersion=""
  local javaArchId=0

  if [[ -z $javaCmd ]]; then
    echo "$javaArchId"
    return 1
  fi
  jdkVersion=`$javaCmd -version 2>&1`
  javaArchId=`echo $jdkVersion | grep 64 | sed 's/\(.*\)\(64\)\(.*\)/\2/'`
  if [ "$javaArchId" != "64" ]; then
    javaArchId="32"
  fi
  echo "$javaArchId"
}

# Get hardware platform full id
function getHwPlatform() {
  local hwPlatform=`uname -m`

  echo "$hwPlatform"
}

# Get hardware platform id (32 or 64)
function getHwPlatformId() {
  local hwPlatformId=`echo $(getHwPlatform) | sed 's/\(.*\)\(64\)\(.*\)/\2/'`

  if [ "$hwPlatformId" != "64" ]; then
    hwPlatformId="32"
  fi
  echo "$hwPlatformId"
}

# Check free space available
function checkFreeSpace() {
  local minSpaceThreshold=$(getMinSpaceThreshold)
  local spaceAvailable=`df -k $(getLocal) | awk 'NR==2 { print $4 }'`
  local msgNoSpace="Not enough space to install properly: less than $minSpaceThreshold kbytes"

  if [ $spaceAvailable -le $minSpaceThreshold ]; then
    informUser "ERROR: $msgNoSpace"
    logError "$msgNoSpace"    
    false; return
  fi
  true; return
}

# Check a command
function checkCommand() {
  local cmd=$1
  local msgNoCommand="'$cmd' not found, please install before installing WCS"

  if ! isCommandExists $cmd; then
    informUser "ERROR: $msgNoCommand"
    logError "$msgNoCommand"    
    false; return
  fi
  true; return
}

# Check a commands needed for installation or server functioning
function checkCommands() {
  local commands=(
    curl
    wget
    diff
    sed
    grep
    awk
    df
    useradd
    cp
  )
  local cmd=""

  for cmd in ${commands[@]}; do
    ! checkCommand $cmd && return 1
  done
  return 0
}

# Check prerequisites main function
function checkPrerequisites() {
  local javaArchId=0
  local hwPlatform="$(getHwPlatform)"
  local hwPlatformId=0
  local msgNotCompatible=""

  informUser "
******************************************************
*                                                    *
*              Checking the system                   * 
*                                                    *                                                     
******************************************************
"
  if ! checkCommands; then
    false; return
  fi
  if ! checkFreeSpace; then
    false; return
  fi
  if ! checkJava; then
    false; return
  fi
  informUser "DETECTING JVM architecture..."
  javaArchId="$(getJavaArchId)"
  informUser "- $javaArchId bit architecture detected"
  informUser "DETECTING hardware platform..."
  hwPlatformId="$(getHwPlatformId)"
  informUser "- $hwPlatformId bit hardware platform detected"
  if [[ "$hwPlatformId" != "$javaArchId" ]]; then
    msgNotCompatible="JVM architecture $javaArchId is not compatible with hardware architecture $hwPlatformId bit($hwPlatform)!"
    informUser "
ERROR. $msgNotCompatible
Please, uninstall current JVM/JDK and download and install JDK $hwPlatform.
Support - support@flashphoner.com, forum - www.flashphoner.com/forums
"
    logError "$msgNotCompatible"
    false; return
  fi
  informUser "
******************************************************
*                                                    *
*       The system is ready for installation.        *
*                                                    *
*                                        Press ENTER *
******************************************************
"
  ! isSilent && askUser
  true; return
}


# Functions to check previous version

# Declare version globals
function declareVersion() {
  ALREADY_INSTALLED=${ALREADY_INSTALLED:-false}
  PRODUCT_VERSION_FILE=${PRODUCT_VERSION_FILE:-$(getHome)/conf/WCS.version}
  PREVIOUS_VERSION=${PREVIOUS_VERSION:-}
  PREVIOUS_VERSION_WITH_HASH=${PREVIOUS_VERSION_WITH_HASH:-}
  PREVIOUS_VERSION_DIR=${PREVIOUS_VERSION_DIR:-}
}

# Is server already installed
function isInstalled() {
  $ALREADY_INSTALLED; return
}

# Set server installed flag
function setInstalled() {
  ALREADY_INSTALLED=true
}

# Get installed version file location
function getInstalledVersionFile() {
  echo "$PRODUCT_VERSION_FILE"
}

# Get previous version
function getPreviousVersion() {
  echo "$PREVIOUS_VERSION"
}

# Get previous version with hash
function getPreviousVersionWithHash() {
  echo "$PREVIOUS_VERSION_WITH_HASH"
}

# Get previous major version
function getPreviousMajorVersion() {
  local previousMajorVersion=$(echo $PREVIOUS_VERSION | cut -d. -f1)

  echo "$previousMajorVersion"
}

# Get previous minor version
function getPreviousMinorVersion() {
  local previousMinorVersion=$(echo $PREVIOUS_VERSION | cut -d. -f2)

  echo "$previousMinorVersion"
}

# Get previous branch version
function getPreviousBranchVersion() {
  local previousBranchVersion=${PREVIOUS_VERSION%.*}

  echo "$previousBranchVersion"
}

# Set previuos version
function setPreviousVersion() {
  local version=$1

  [[ -z $version ]] && return 1
  PREVIOUS_VERSION_WITH_HASH=$version
  PREVIOUS_VERSION=${PREVIOUS_VERSION_WITH_HASH%-*}
}

# Get previous version full home dir
function getPreviousFullHome() {
  echo "$PREVIOUS_VERSION_DIR"
}

# Set previuos version full home dir
function setPreviousFullHome() {
  local dir=$1

  [[ -z $dir ]] && return 1
  PREVIOUS_VERSION_DIR=$dir
}

# Check previous instance state and stop if running
function stopPreviousInstance() {
  local pidFilePath="/var/run"
  local pidFileName="FlashphonerMainWebCallServer.pid"
  local pidFile=""
  local envFile="$(getHome)/bin/setenv.sh"
  local confirm="yes"
  local result=0

  if [[ -f $envFile && $(grep "WCS_NON_ROOT=true" $envFile) ]]; then
    pidFilePath="$(getHome)/bin"
  fi
  pidFile="$pidFilePath/$pidFileName"
  logDebug "Get pid from file: $pidFile"
  if [[ -f $pidFile && $(pgrep -F $pidFile) ]]; then
    informUser "\nYou have to stop $(getProduct) before update\n"
    ! isSilent && confirm="$(askUser 'Stop now [yes/no] ? ')"
    if [[ $confirm == y* ]]; then
      stopRunningServer $pidFile
      result=$?
    else
      informUser "\nAbort updating\n "
      result=1
    fi
  fi
  return $result
}

# Main previous version check function
function checkPreviousVersion() {
  local previousVersionFile=""
  local previousVersion=""
  local previousFullHome=""
  local msgAlreadyInstalled=""
  local homeDir=$(getHome)
  local msgBanner=""
  local installed=false

  declareVersion
  previousVersionFile=$(getInstalledVersionFile)
  if [[ -f $previousVersionFile ]]; then
    previousVersion=$(cat $previousVersionFile)
    previousFullHome=$(readlink -e $homeDir)
    logDebug "Previous version found: $previousVersion"
    setPreviousVersion $previousVersion
    setPreviousFullHome $previousFullHome
    setInstalled
  fi
  if [[ "$(getPreviousVersionWithHash)" == "$(getVersionWithHash)" ]]; then
    msgAlreadyInstalled="Flashphoner v.$(getVersion) already installed"
    informUser "
******************************************************
*                                                    *
*    $msgAlreadyInstalled     *
*                                                    *
******************************************************
"
    logInfo "$msgAlreadyInstalled"
    ! isSilent && askUser
    false; return
  fi
  if isInstalled; then
    ! stopPreviousInstance && return 1
    msgBanner="Update Flashphoner from $(getPreviousVersion) to $(getVersion)"
  else
    msgBanner="     Installing Flashphoner v.$(getVersion)"
  fi
  informUser "
******************************************************
*                                                    *
*  $msgBanner  
*                                                    *
*  (C) Flashphoner.com 2010. All rights reserved.    *
*  To install press ENTER, to abort press CTRL+C.    *
*                                                    *
*                                        Press ENTER *
******************************************************
"
  ! isSilent && askUser
  true; return
}

# Check if updates allowed
function updatesAllowed() {
  local addOptions=""
  local javaVersion=$(getJavaVersion)

  if ! isInstalled; then
    true; return
  fi
  [[ $javaVersion -eq 16 ]] && addOptions="--illegal-access=permit"
  [[ $javaVersion -ge 17 ]] && addOptions="--add-exports java.base/sun.security.provider=ALL-UNNAMED --add-opens java.base/java.lang=ALL-UNNAMED"
  java -Dcom.flashphoner.fms.AppHome="$(getHome)" $addOptions -cp "./server/lib/*" com.flashphoner.server.license.ExpiredUpdates
  if [ $? -eq 1 ]; then
    false; return
  fi
  true; return
}


# Installation functions

# Copy Web SDK with examples
function copySdk() {
  local fullHome=$(getFullHome)
  local xmlFile=$fullHome/client/examples/flashphoner.xml
  local result=0

  cd $(getInstallerDir)
  for folder in client client2; do
    if ! verifiedCopy $folder $fullHome; then
      result=$?
      informUser "Failed to copy SDK"
      return $result
    fi
  done
  sed -i s/87\.226\.225\.[0-9][0-9]/192.168.1.5/g $xmlFile
  sed -i s/192.168.1.[0-9]/127.0.0.1/g $xmlFile
}

# Update bin folder content
function updateBin() {
  local fullHome=$(getFullHome)
  local installerDir=$(getInstallerDir)
  local filesToKeep=(
    "on_multiple_record_hook.sh"
    "on_record_hook.sh"
    "setenv.sh"
  )
  local file=""
  local result=0

  cd $installerDir/server
  if [[ "$(getBranchVersion)" == "$(getPreviousBranchVersion)" ]]; then
    # Preserve possible scripts tweaks while updating the same product branch #WCS-2615
    for file in bin/*; do
      if [[ " ${filesToKeep[@]} " =~ " $(basename ${file}) " ]] && [[ -f "$fullHome/$file" ]]; then
        continue
      fi
      if ! verifiedCopy $file $fullHome/$file; then
        result=$?
        break
      fi
    done    
  else
    verifiedCopy bin $fullHome
    result=$?
  fi
  if [[ $result -ne 0 ]]; then
    informUser "Failed to update bin folder"
  fi
  return $result
}

# Copy bin folder
function copyBin() {
  local fullHome=$(getFullHome)
  local installerDir=$(getInstallerDir)
  local result=0

  cd $installerDir/server
  mkdir -p $fullHome/bin
  if ! isInstalled; then
    verifiedCopy bin $fullHome
  else
    updateBin
  fi
  result=$?
  if [[ $result -ne 0 ]]; then
    informUser "Failed to copy or update bin folder"
    return $result
  fi
  if ! verifiedCopy $installerDir/uninstall.sh $fullHome/bin; then
    result=$?
    informUser "Failed to copy uninstall script to bin folder"
    return $result
  fi
  chmod +x $fullHome/bin/*.sh
  chmod +x $fullHome/bin/webcallserver
}

# Copy web folder
function copyWeb() {
  local result=0

  cd $(getInstallerDir)/server
  if ! verifiedCopy web $(getFullHome); then
    result=$?
    informUser "Failed to copy web folder"
    return $result
  fi
}

# Copy lib folder
function copyLib() {
  local fullHome=$(getFullHome)
  local result=0

  cd $(getInstallerDir)/server
  for file in $fullHome/lib/*; do
    if [[ "custom" != "$(basename ${file})" ]]; then
      rm -rf $file
    fi
  done
  if ! verifiedCopy lib $fullHome; then
    result=$?
    informUser "Failed to copy lib folder"
    return $result
  fi
  mkdir -p $fullHome/lib/custom
}

# Copy tools folder
function copyTools() {
  local fullHome=$(getFullHome)
  local result=0

  cd $(getInstallerDir)/server
  if ! verifiedCopy tools $fullHome; then
    result=$?
    informUser "Failed to copy tools folder"
    return $result
  fi
  chmod +x $fullHome/tools/*.sh
  chmod +x $fullHome/tools/certbot-auto
}

# Update conf folder
function updateConf() {
  local configs=(
    "log4j.properties"
    "accounts.xml"
    "database.yml"
    "account.xml"
    "callee.xml"
    "crossdomain.xml"
    "token_keys.properties"
    "dtls-main-cert.pem"
    "dtls-main-key.pem"
    "watchdog.properties"
    "wcs-core.properties"
    "wss.jks.backup"
    "cli-hostkey.pem"
    "dtls0_ua"
    "offline-mixer.json"
    "hls-subscribers-emulator.json"
    "wcs_sfu_bridge_profiles.yml"
    "mail.properties"
  )
  local subdirs=(
    "qa"
    "apps/click-to-call"
    "zclient/conf"
  )
  local fullHome=$(getFullHome)
  local installerDir=$(getInstallerDir)
  local item=""
  local result=0

  for item in ${configs[@]}; do
    if [[ ! -f $fullHome/conf/$item && -f $installerDir/server/conf/$item ]]; then
      if ! verifiedCopy $installerDir/server/conf/$item $fullHome/conf; then
        result=$?
        informUser "Failed to update configuration files"
        return $result
      fi
    fi
    if [[ $item == "database.yml" ]]; then
      sed -i 's/9091/8081\/apps/g' $fullHome/conf/database.yml
    fi
  done
  for item in ${subdirs[@]}; do
    if [[ ! -d "$fullHome/conf/$item" ]]; then
      if ! verifiedCopy $installerDir/server/conf/$item $fullHome/conf; then
        result=$?
        informUser "Failed to update configuration folders"
        return $result
      fi
    fi
  done
}

# Copy conf folder
function copyConf() {
  local fullHome=$(getFullHome)
  local installerDir=$(getInstallerDir)
  local result=0

  cd $installerDir/server
  mkdir -p $fullHome/conf
  if ! isInstalled; then
    verifiedCopy conf $fullHome
  else
    updateConf
  fi
  result=$?
  if [[ $result -ne 0 ]]; then
    informUser "Failed to copy or update server configuration"
    return $result
  fi
}

# Make symlink to product folder
function makeSymlink() {
  local linkHome=$(getHome)
  local fullHome=$(getFullHome)

  if [ -L $linkHome ]; then
    rm -f $linkHome
  fi
  ln -sf $fullHome $linkHome
}

# Update version file
function updateVersion() {
  echo "$(getVersionWithHash)" > $(getHome)/conf/WCS.version
}

# Copying product files
function copyFiles() {
  local folders=(
    Sdk
    Bin
    Web
    Lib
    Tools
    Conf
  )
  local folder=""
  local result=0

  informUser "COPYING files..."
  for folder in ${folders[@]}; do
    if ! copy$folder; then
      result=$?
      informUser "- Copying failed"
      return $result
    fi
  done
  makeSymlink
  updateVersion
  informUser "- Copying completed."
}

# Main installation function
function doInstall() {
  local fullHome="$(getFullHome)"

  if isInstalled; then
    informUser "Starting $(getProduct) update..."
    mv $(getPreviousFullHome) $fullHome
  else
    informUser "
*************************************************************
*                                                           *
*      Starting $(getProduct) installation      *  
*                                                           *
*************************************************************
"
    [ ! -d $fullHome ] && mkdir $fullHome
  fi

  copyFiles
  return $?
}


# Functions to prepare non-root user

# Create user if not exists
function prepareUser() {
  local user=$(getUser)

  if ! isUser $user; then
    useradd --system --no-create-home --user-group $user
  fi
}


# Functions to install or update service

# Declere service globals
function declareService() {
  SYSTEMD_PATH=${SYSTEMD_PATH:-/etc/systemd/system}
  INITD_PATH=${INITD_PATH:-/etc/init.d}
  SYSTEMD_FILE=${SYSTEMD_FILE:-$SYSTEMD_PATH/webcallserver.service}
  INITD_FILE=${INITD_FILE:-$INITD_PATH/webcallserver}
}

# Get systemd path
function getSystemdPath() {
  echo "$SYSTEMD_PATH"
}

# Get initd path
function getInitdPath() {
  echo "$SYSTEMD_PATH"
}

# Get systemd full file name
function getSystemdFileName() {
  echo "$SYSTEMD_FILE"
}

# Get initd full file name
function getInitdFileName() {
  echo "$INITD_FILE"
}

# Copy service setup file to systemd
function copyToSystemd() {
  cp $(getInstallerDir)/scripts/systemd/webcallserver.service $(getSystemdPath)
}

# Copy startup script to initd
function copyToInitd() {
  cp -f $(getFullHome)/bin/webcallserver $(getInitdFileName)
}

# Helper function tro ask user for run WCS on startup
function askToRunOnStartup() {
  local answer=""

  until [[ "$answer" == "yes" || "$answer" == "no" ]]; do
    answer=$(askUser 'Do you want to start WebCallServer at server startup? [yes/no]')
  done
  echo "$answer"
}

# Enable WCS running on startup
function startupEnable() {
  local msgCantDetectDistro="Unable to determine your distribution"

  if [[ "$(getDaemonType)" == "systemd" ]]; then
    systemctl enable webcallserver > /dev/null 2>&1
  elif [ ! -f /proc/version ]; then
    informUser "ERROR: $msgCantDetectDistro"
    logError "$msgCantDetectDistro"
    return 1
  elif grep -e "Red Hat\|centos" /proc/version > /dev/null; then
    chkconfig --add webcallserver > /dev/null 2>&1
    chkconfig --level 345 webcallserver on > /dev/null 2>&1
  elif grep -e "[Dd]ebian\|[Uu]buntu" /proc/version > /dev/null; then
    update-rc.d -f webcallserver remove > /dev/null 2>&1
    update-rc.d webcallserver defaults > /dev/null 2>&1
  else
    informUser "ERROR: $msgCantDetectDistro"
    logError "$msgCantDetectDistro"
    return 1
  fi
}

# Install service
function installService() {
  local runOnStartup="no"
  local startupEnabled=false

  if [[ "$(getDaemonType)" == "systemd" ]]; then
    copyToSystemd
    systemctl daemon-reload
  else
    copyToInitd
  fi
  ! isSilent && runOnStartup=$(askToRunOnStartup)
  if [[ "$runOnStartup" == "yes" ]]; then
    startupEnable && startupEnabled=true
  fi
  if ! $startupEnabled; then
    informUser "Use '$(getHome)/bin/webcallserver start' to start server"
  fi
}

# Update systemd service
function updateSystemd() {
  local systemdFile=$(getSystemdFileName)

  if [[ -f $systemdFile ]]; then
    # Add 143 exit code as success #WCS-3034
    if ! grep -e "^SuccessExitStatus" $systemdFile > /dev/null; then
      sed -i 's/\[Service\]/\[Service\]\nSuccessExitStatus=143/g' $systemdFile
    fi
    # Revert user back to root if there is such settings,
    # service should always be started from root with sudoing to flashphoner #WCS-3112
    sed -i -e "s/^\(User[ \t]*=\).*\$/\1root/" $systemdFile
    sed -i -e "s/^\(Group[ \t]*=\).*\$/\1root/" $systemdFile
    # Set service type to forking #WCS-3682
    sed -i -e "s/^\(Type[ \t]*=\).*\$/\1forking/" $systemdFile
    # Replace network.target to network-online.target #WCS-3716
    sed -i -e "s/network.target/network-online.target/" $systemdFile
    # Add Wants=network-online.target #WCS-3716
    if ! grep -e "^Wants[ \t]*=[ \t]*network-online.target" $systemdFile > /dev/null; then
      sed -i 's/\[Unit\]/\[Unit\]\nWants=network-online.target/g' $systemdFile
    fi
    # Add restart constraints #WCS-3716
    if ! grep -e "^StartLimitIntervalSec" $systemdFile > /dev/null; then
      sed -i 's/\[Unit\]/\[Unit\]\nStartLimitIntervalSec=120\nStartLimitBurst=5/g' $systemdFile
    fi
    # Add restart conditions #WCS-3716
    if ! grep -e "^Restart" $systemdFile > /dev/null; then
      sed -i 's/\[Service\]/\[Service\]\nRestart=on-failure\nRestartSec=5s/g' $systemdFile
    fi
    # Switch RemainAfterExit to no #WCS-3716
    sed -i -e "s/^\(RemainAfterExit[ \t]*=\).*\$/\1no/" $systemdFile
  else
    copyToSystemd
  fi
  systemctl daemon-reload
}

# Update service
function updateService() {
  if [[ "$(getDaemonType)" == "systemd" ]]; then
    updateSystemd
  elif [[ -f $(getInitdFileName) ]]; then
    copyToInitd
  fi
}

# Main service setup function
function serviceSetup() {
  declareService
  if ! isInstalled; then
    installService
  else
    updateService
  fi
}



# Main module

# Usage displaying
function usage() {
  echo "Usage: $(basename $0) [OPTIONS]"
  echo -e "  -silent | --silent\tInstall WCS in non interactive mode (do not ask anything)"
  return 1
}

# Main script function
function main() {
  local msgRestricted=""
  local msgNotAllowed=""

  declareGlobals
  declareLog $(getInstallLogFile)

  # Parse command line arguments
  while [[ $# -gt 0 ]]; do
    case $1 in
      --debug)
        enableDebug
        shift
        ;;
      -silent|--silent)
        setSilent
        shift
        ;;
      *)
        usage
        return 1
        ;;
    esac
  done

  # Check write permissions
  if [ ! -w "$(getLocal)" ]; then
    msgRestricted="Write to $(getLocal) restricted for user running install.sh"
    informUser "ERROR: $msgRestricted"
    logError "$msgRestricted"
    return 1
  fi

  if ! checkPrerequisites; then
    return 1
  fi
  if ! checkPreviousVersion; then
    return 1
  fi
  if isInstalled && [[ ! updatesAllowed ]]; then
    msgNotAllowed="Updates are not allowed for version $(getPreviousVersion)"
    informUser "ERROR: $msgNotAllowed"
    logError "$msgNotAllowed"
    return 1    
  fi
  if ! displayLicense; then
    return 1
  fi
  if ! doInstall; then
    informUser "ERROR: Installation failed, see install.log"
    return 1
  fi
  prepareUser
  serviceSetup
  displayResult 
  if isUser $(getUser); then
    setOwner $(getUser) $(getFullHome) false
  fi
}

main "$@"

exit $?


