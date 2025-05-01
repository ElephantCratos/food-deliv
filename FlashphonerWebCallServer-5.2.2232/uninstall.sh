#!/bin/bash
#
# Uninstall script for Flashphoner WebCallServer
#



# Global variables configuration

# Declare global variables
function declareGlobals() {
  declareCommonGlobals
  INSTALL_LOG="$WCS_HOME/bin/install.log"
  VERSION_WITH_HASH=$(cat $WCS_HOME/conf/WCS.version)
  VERSION=${VERSION_WITH_HASH%-*}
  WCS_HOME_FULL=$(readlink -e $WCS_HOME)
}

# Get install log file
function getInstallLogFile() {
  if [[ -f $INSTALL_LOG ]]; then
    echo "$INSTALL_LOG"
  else
    echo ""
  fi
}

# Get full installation path including version
function getFullHome() {
  if [[ -d $WCS_HOME_FULL ]]; then
    echo "$WCS_HOME_FULL"
  else
    echo ""
  fi
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



# Message displaying functions

# Display uninstall banner
function displayUninstallBanner() {
  if isSilent; then
    return 0
  fi

  informUser "
**************************************************
*                                                *
*             Uninstalling Flashphoner           *
*                                                *
* (c) Flashphoner.com 2010. All rights reserved  *
*                                                *
**************************************************
"
  return 0
}

# Ask user to confirm uninstallation
function askForConfirmation() {
  local answer=""
  local message="Are you sure you want to uninstall $(getProduct)? [yes/no]"

  if isSilent; then
    true; return
  fi
  until [[ "$answer" == "yes" || "$answer" == "no" ]]; do
    answer=$(askUser $message)
  done
  if [[ "$answer" == "yes" ]]; then
    true; return
  fi
  false; return
}

# Display uninstallation result
function displayUninstallResult() {
  if isSilent; then
    return 0
  fi
  informUser "
***************************************************************************
*                                                                         *
*                         Uninstallation complete!                        *
*                                                                         *
*  Thank you for trying Flashphoner!                                      *
*  Please restart server before further work                              *
*  Support - support@flashphoner.com, forum - www.flashphoner.com/forums  *
*  Press Enter to continue                                                *
*                                                                         *
***************************************************************************
"
  askUser
}

# Functions to check server state and stop it

# Check previous instance state and stop if running
function stopRunningInstance() {
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
    informUser "\nYou have to stop $(getProduct) before uninstall\n"
    ! isSilent && confirm="$(askUser 'Stop now [yes/no] ? ')"
    if [[ $confirm == y* ]]; then
      stopRunningServer $pidFile
      result=$?
    else
      informUser "\nAbort uninstallation\n "
      result=1
    fi
  fi
  return $result
}


# Functions to check and deactivate license

# Check if license is active and deactivate it
function deactivateLicense() {
  if [ -f $(getHome)/conf/flashphoner.license ]; then
	  informUser "Active license found, deactivating"
	  $(getHome)/bin/deactivation.sh > /dev/null 2>&1
  fi
}


# File removing functions

# Remove legacy version files (for backward compatibility reason)
function removeOldFiles() {
  local installLogFile=$(getInstallLogFile)
  local line=""

  [[ -z $installLogFile ]] && return 0
  if [[ -f $installLogFile ]]; then
    informUser "Removing $(getProduct) files according to install.log..."
    cat $installLogFile | while read line; do
      [ -f $line ] && rm -rf $line
    done
    informUser "- Removing old files completed."
  fi
}

# Remove product folder with symlink
function removeProductFolder() {
  cd /
  rm -f $(getHome)
  rm -rf $(getFullHome)
}

# Main files removing function
function removeFiles() {
  removeOldFiles
  removeProductFolder
}


# Functions to remove non-root user

# Remove user if exists
function removeUser() {
  local user=$(getUser)

  if isUser $user; then
    pkill -TERM -u $user
    userdel -f $user
  fi
}



# Functions to remove service

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

# Remove service file from systemd
function removeFromSystemd() {
  if [[ -f $(getSystemdFileName) ]]; then
    rm -rf $(getSystemdFileName)
  fi
}

# Remove startup script from initd
function removeFromInitd() {
  if [[ -f $(getInitdFileName) ]]; then
    rm -rf $(getInitdFileName)
  fi
}

# Disable service startup
function disableService() {
  local msgCantDetectDistro="Unable to determine your distribution"

  if [ ! -f /proc/version ]; then
    informUser "ERROR: $msgCantDetectDistro"
    logError "$msgCantDetectDistro"
    return 1
  elif grep -e "Red Hat\|centos" /proc/version > /dev/null; then
    chkconfig --del webcallserver > /dev/null 2>&1
  elif grep -e "[Dd]ebian\|[Uu]buntu" /proc/version > /dev/null; then
    update-rc.d -f webcallserver remove > /dev/null 2>&1
  else
    informUser "ERROR: $msgCantDetectDistro"
    logError "$msgCantDetectDistro"
    return 1
  fi
}

# Disable and remove service
function removeService() {
  declareService
  if [[ "$(getDaemonType)" == "systemd" ]]; then
    systemctl disable webcallserver > /dev/null 2>&1
    removeFromSystemd
    systemctl daemon-reload
  else
    removeFromInitd
    if ! disableService; then
      return 1
    fi
  fi
}


# Main module

# Usage displaying
function usage() {
  echo "Usage: $(basename $0)"
  echo -e "  -silent | --silent\tUninstall WCS in non interactive mode (do not ask anything)"
  return 1
}

# Main script function
function main() {
  local msgRestricted=""
  local msgNotAllowed=""

  declareGlobals

  # Parse command line arguments
  while [[ $# -gt 0 ]]; do
    case $1 in
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
    msgRestricted="Write to $(getLocal) restricted for user running uninstall.sh"
    informUser "ERROR: $msgRestricted"
    logError "$msgRestricted"
    return 1
  fi

  displayUninstallBanner
  if ! askForConfirmation; then
    return 1
  fi
  if ! stopRunningInstance; then
    return 1
  fi
  deactivateLicense
  informUser "UNINSTALLING $(getProduct)..."
  removeService
  removeFiles
  removeUser
  displayUninstallResult 
}

main "$@"

exit $?


