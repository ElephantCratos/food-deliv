#!/usr/bin/env bash

PRODUCT="FlashphonerWebCallServer"

_EXECJAVA=java

WCS_APP_HOME="/usr/local/$PRODUCT"
WCS_APP_ABSOLUTE_HOME=`readlink -f $WCS_APP_HOME`

#if true WCS will store pid and lock files in WCS_APP_HOME directory
WCS_NON_ROOT=true

WCS_CORE_CONF="$WCS_APP_HOME/conf/wcs-core.properties"

LD_LIBRARY_PATH=$WCS_APP_HOME/lib/so

GC_SUFFIX=`date +%Y-%m-%d_%H-%M`".log"

while read -r line || [[ -n "$line" ]]; do
 if [[ "$line" == "### SERVER OPTIONS ###" ]]; then
  while read prop; do
   if [[ $prop == -* ]]; then
    WCS_SERVER_OPTS+="$prop "
   elif [[ "$prop" == "### JVM OPTIONS ###" ]]; then
    break 
   fi
  done
 else
  if [[ $line == -Xloggc* ]]; then
   WCS_JAVA_OPTS+=$line$GC_SUFFIX" "
  elif [[ $line == -Xlog:gc* ]]; then
   WCS_JAVA_OPTS+=$(echo "$line" | sed "s/gc-core-/gc-core-$GC_SUFFIX/")" "
  elif [[ $line == -* ]]; then
   WCS_JAVA_OPTS+="$line "
  fi
 fi
done < $WCS_CORE_CONF

WCS_JAVA_OPTS=${WCS_JAVA_OPTS%?}
WCS_SERVER_OPTS=${WCS_SERVER_OPTS%?}

WCS_STARTUP_LOG="$WCS_APP_HOME/logs/startup.log"

WCS_FD_LIMIT=20000

export _EXECJAVA WCS_JAVA_OPTS WCS_APP_HOME LD_LIBRARY_PATH WCS_APP_ABSOLUTE_HOME WCS_STARTUP_LOG WCS_FD_LIMIT

#Prevent huge RES memory utilization on multicore CPUs (20 and more cores)
export MALLOC_ARENA_MAX=4
