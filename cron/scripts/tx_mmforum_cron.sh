#!/bin/bash

#  
#  Copyright notice
#
#  (c) 2008 Martin Helmich, Mittwald CM Service
#  All rights reserved
#
#  This script is part of the TYPO3 project. The TYPO3 project is
#  free software; you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation; either version 2 of the License, or
#  (at your option) any later version.
#
#  The GNU General Public License can be found at
#  http://www.gnu.org/copyleft/gpl.html.
#
#  This script is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  This copyright notice MUST APPEAR in all copies of the script!
#  

#
#  This script is a starter script for all mm_forum cronjobs.
#  Basically, it calls the cli.tx_mmforum_cron.php file with the appropriate
#  parameter. The script determines the absolute path of the php file,
#  which is necessary for PHP to resolve all file paths correctly and finds
#  the appropriate PHP binary (php-cli if possible, otherwise php).
#
#  AUTHOR    Martin Helmich <m.helmich@mittwald.de>
#  COPYRIGHT 2009 Mittwald CM Service GmbH & Co. KG
#  VERSION   $Id$
#

function printUsageInfo() {
	echo "This script is the command line interface for the mm_forum cronjob module. It determines
the necessary environment variables to successfully execute the mm_forum cron script.

USAGE
	${0} ${1} [ARGUMENTS]

ARGUMENTS
	--php-path=...    - Path to PHP binary. If this parameter is not specified, the script
	                    automatically looks for binaries named \"php-cli\", \"php_cli\" and
	                    \"php\" in \$PATH.
	--script-path=... - Path to the PHP Cronjob script that is to be executed. If this parameter
	                    is not specified, this script will look in ../cron/cli.tx_mmforum_cron.php
	--silent, -q, -s  - The \"silent\" parameter suppresses all regular output. Only error
	                    will be displayed.
	--help            - Display this help screen

AUTHOR
	Martin Helmich <m.helmich@mittwald.de>
	Mittwald CM Service GmbH & Co. KG"
}

function getScriptPath() {
	local THIS_DIR=$(dirname $(readlink -f "${0}"))
	local MAIN_DIR=$(dirname "${THIS_DIR}")

	local SCRIPT_NAME="cli.tx_mmforum_cron.php"
	local SCRIPT_PATH="${MAIN_DIR}/${SCRIPT_NAME}"

	if [ ! -f $SCRIPT_PATH ] ; then echo -e "ERROR: Could not find cronjob script ${SCRIPT_PATH}. Consider using the --script-path parameter." >&2 ; return 1
	else echo "${SCRIPT_PATH}" ; return 0 ; fi
}

function getPHPPath() {
	local TRYOUT="php-cli php_cli php"
	local PHP_PATH=""
	
	for BIN in $TRYOUT ; do
		PHP_PATH=$(which "${BIN}")
		if [ $? -eq 0 ] ; then echo "${PHP_PATH}" ; return 0 ; fi
	done
	
	echo "ERROR: Could not determine path of PHP binary. Consider using the --php-path parameter." >&2 ; return 1
}

function say() {
	if [ $SILENT_MODE -eq 0 ] ; then echo "$@" ; fi
}

function error() {
		echo "ERROR:" >&2
		echo "	$1" >&2
		echo "	Try calling ${0} --help"
}

SCRIPT_PATH=""
PHP_PATH=""
SILENT_MODE=0
COLOR_MODE=1
CRON_TASK=""

	# Evaluate parameters
if [ -z "$1" ] ; then error "Cronjob task has not been specified." ; exit 1
else CRON_TASK="$1" ; fi

ARGS=("$@")
for((i=1;i<$#;i+=1)) ; do
	ARG="${ARGS[$i]}"
	case "${ARG%\=*}" in
		"--php-path" ) PHP_PATH="${ARG#--php-path=}" ;;
		"--script-path" ) SCRIPT_PATH="${ARG#--script-path=}" ;;
		"--silent" | "-s" | "-q" ) SILENT_MODE=1 ;;
		"--help" | "-h" ) printUsageInfo $@ ; exit 0 ;;
		* ) error "Unknown parameter: ${ARG%\=*}" ; exit 1 ;;
	esac
done

	# Determine path of the cronjob CLI script.
if [ -z "${SCRIPT_PATH}" ] ; then
	say "Trying to determine path of mm_forum cron script..."
	SCRIPT_PATH=$(getScriptPath)
	if [ $? -eq 0 ] ; then say "Found script at ${SCRIPT_PATH}." ; else exit 1 ; fi
else say "Using ${SCRIPT_PATH} as cron script..." ; fi
if [ ! -f "${SCRIPT_PATH}" ] ; then error "The file ${SCRIPT_PATH} does not exist." ; exit 1 ; fi

	# Determine path of PHP binary
if [ -z "${PHP_PATH}" ] ; then
	say "Trying to determine path of PHP binary..."
	PHP_PATH=$(getPHPPath)
	if [ $? -eq 0 ] ; then say "Found PHP at ${PHP_PATH}." ; else exit 1 ; fi
else say "Using ${PHP_PATH} as PHP executable..." ; fi
if [ ! -x "${PHP_PATH}" ] ; then echo "The PHP binary does not exist or is not executable." >&2 ; exit 1 ; fi

	# Execute PHP script
say "Executing PHP script..."
say "PHP script output start..."
say "============================================================"

if [ $SILENT_MODE -eq 1 ] ; then $PHP_PATH -q "${SCRIPT_PATH}" "${CRON_TASK}" > /dev/null
else $PHP_PATH -q "${SCRIPT_PATH}" "${CRON_TASK}" ; fi

say "============================================================"

if [ ! $? -eq 0 ] ; then error "Error during execution of PHP script" >&2 ; exit 1
else say -e "\n* Script was successfully executed" ; fi
