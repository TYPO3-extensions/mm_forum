<?php
/*
 *
 * Copyright notice
 *
 *  (c) 2008 Martin Helmich, Mittwald CM Service
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 *
 */
/**
 *
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   60: class tx_mmforum_cronbase
 *  123:     function initialize()
 *  151:     function validateConfig()
 *  167:     function loadLanguageFile($langFile='')
 *  188:     function getLL($lKey)
 *  201:     function getPid()
 *  216:     function loadTemplateFile($filename)
 *  235:     function formatDate($tstamp)
 *  251:     function removeCacheValue($key)
 *  270:     function getCacheValue($key)
 *  292:     function getCacheValue_remove($key)
 *  311:     function debug($message, $mode=0)
 *
 * TOTAL FUNCTIONS: 11
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(PATH_t3lib.'class.t3lib_tsparser.php');




/**
 *
 * This class is the base object for all cronjob classes of the mm_forum.
 * Is provides basic functions for debugging and logging, loading of template
 * files, language control functions, cache control and so on.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2008 Martin Helmich, Mittwald CM Service
 * @version    $Id$
 * @package    mm_forum
 * @subpackage Cronjobs
 *
 */

class tx_mmforum_cronbase {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * This cronjob's name. This attribute is meant to be overwritten by the
		 * actual cronjob classes.
		 * @var string
		 */

	var $cron_name = 'tx_mmforum_cronbase';



		/**
		 * Debugging constant. Used for regular loggin messages.
		 * @var int
		 */

	var $DEBUG_LOG			=  0;



		/**
		 * Debugging constant. Used for notices.
		 * @var int
		 */

	var $DEBUG_NOTICE		= 10;



		/**
		 * Debugging constant. Used for warnings.
		 * @var int
		 */

	var $DEBUG_WARNING		= 20;



		/**
		 * Debugging constant. Used for non-fatal errors.
		 * @var int
		 */

	var $DEBUG_ERROR		= 30;



		/**
		 * Debugging constant. Used for fatal errors.
		 * @var int
		 */

	var $DEBUG_FATAL		= 40;



		/**
		 * Debug mode constant. All messages will be displayed.
		 * @var string
		 */

	var $DEBUGMODE_ALL		= 'all';



		/**
		 * Debug mode constant. No messages will be displayed.
		 * @var string
		 */

	var $DEBUGMODE_QUIET	= 'quiet';



		/**
		 * Debug mode constant. Only errors and warnings will be displayed.
		 * @var string
		 */

	var $DEBUGMODE_ERROR	= 'errors';





		/*
		 * INITIALIZATION
		 */





		/**
		 *
		 * Initializes the cronjob.
		 * This function initializes the cronjob object. The initialization of a
		 * cronjob consists of:
		 *
		 *   1. Load the default settings from static constants
		 *   2. Load user settings from tx_mmforum_config.ts
		 *   3. Join default and user settings and parse into an array
		 *   4. Load language file for the cronjob
		 *   5. Validate settings and exit with error if something is missing
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-06-22
		 * @return  void
		 *
		 */

	function initialize() {

			// Load default constants file
		$conf    = file_get_contents(dirname(PATH_thisScript).'/../ext_typoscript_constants.txt');

			// Load user settings file
		$localSettings_filename = dirname(PATH_thisScript).'/../../../tx_mmforum_config.ts';
		if(file_exists($localSettings_filename))
		    $conf   .= "\n#LOCAL SETTINGS\n".file_get_contents( $localSettings_filename );

		    // Parse setup
		$parser  = new t3lib_TSparser();
		$parser->parse($conf);
		$this->conf = $parser->setup['plugin.']['tx_mmforum.'];

			// Load language files
		$this->loadLanguageFile();

			// Validate configuration
		$this->validateConfig();

	}



		/**
		 *
		 * Validates the configuration file and exits with an error if something is
		 * missing
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-06-22
		 * @return  void
		 *
		 */

	function validateConfig() {
		if(!intval($this->conf['userPID'])) 	$this->debug("Constant \"userPID\" is not set.",$this->DEBUG_FATAL);
		if(!intval($this->conf['storagePID'])) 	$this->debug("Constant \"storagePID\" is not set.",$this->DEBUG_FATAL);
		if(!$this->conf['cron_pathTmpl']) 		$this->debug("Constant \"cron_pathTmpl\" is not set.",$this->DEBUG_FATAL);
	}



		/**
		 *
		 * Loads a language file.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-06-22
		 * @param   string $langFile The name of the language file. If left empty,
		 *                           the default language file is loaded.
		 * @return  void
		 *
		 */

	function loadLanguageFile($langFile='') {
		if(empty($langFile)) $langFile = 'lang.'.$this->cron_name.'.php';

		$this->langFile = dirname(PATH_thisScript).'/lang/'.$langFile;
		if(file_exists($this->langFile)) require_once($this->langFile);
		else $this->debug("Language file not found.", $this->DEBUG_FATAL);

		if(isset($LANG)) $this->lang = $LANG;
		else $this->debug("Language file could not be read.", $this->DEBUG_FATAL);
	}





		/*
		 * GETTER METHODS
		 */





		/**
		 *
		 * Gets a language variable.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-06-22
		 * @param   string $lKey The key of the language dependent string
		 * @return  string       The language string
		 *
		 */

	function getLL($lKey) {
		if($this->lang[$this->conf['cron_lang']][$lKey])
			return $this->lang[$this->conf['cron_lang']][$lKey];
		else return $this->debug('Language label not found: '.$lKey,$this->DEBUG_NOTICE);
	}



		/**
		 *
		 * Gets the mm_forum data storage PID.
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-06-22
		 * @return  int The mm_forum data storage PID
		 *
		 */

	function getPid() { return $this->conf['storagePID']; }



		/**
		 *
		 * Loads a template file.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-06-22
		 * @param   string $filename The filename of the template file. This
		 *                           parameter must contain only the filename.
		 *                           The template directory is specified in the
		 *                           configuration file.
		 * @return  string           The content of the template file.
		 *
		 */

	function loadTemplateFile($filename) {
		$absFileName = dirname(PATH_thisScript).'/'.dirname($GLOBALS['BACK_PATH']).'/'.$this->conf['cron_pathTmpl'].$filename.'.html';
		$absFileName = realpath($absFileName);
		if(file_exists($absFileName)) return file_get_contents($absFileName);
		else return $this->debug('Template file not found: '.$absFileName,$this->DEBUG_ERROR);
	}



		/**
		 *
		 * Formats a timestamp with a specific date format.
		 * The date format is specified in the mm_forum configuration file. This
		 * function is capable of working with both the 'date' and the 'strftime'
		 * function and automatically recognizes which of the two functions to use.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-06-22
		 * @param   int $tstamp The timestamp
		 * @return  string      The formatted date
		 *
		 */

	function formatDate($tstamp) {
    	$df = $this->conf['dateFormat'];

    	if(strpos($df,'%')===false) return date($df,$tstamp);
    	else return strftime($df,$tstamp);
    }



		/**
		 *
		 * Removes a cache variable.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-06-22
		 * @param   string $key The key of the cache variable.
		 * @return  boolean     TRUE on success, otherwise FALSE
		 *
		 */

    function removeCacheValue($key) {
    	$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery(
			'tx_mmforum_cache', 'cache_key='.$GLOBALS['TYPO3_DB']->fullQuoteStr($key,'tx_mmforum_cache')
    	);
    	return $res?true:false;
    }



		/**
		 *
		 * Loads a cache variable from database.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-06-22
		 * @param   string $key The key of the cache variable.
		 * @return  mixed       The cache variable. Since the cache value is
		 *                      stored in serialized form, this value can be of
		 *                      any type. If the value was not found in the
		 *                      cache table, this function will return NULL.
		 *
		 */

    function getCacheValue($key) {
    	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
    		'cache_value', 'tx_mmforum_cache', 'cache_key='.$GLOBALS['TYPO3_DB']->fullQuoteStr($key,'tx_mmforum_cache')
    	);
    	if($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
	    	list($value) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
	    	return unserialize($value);
    	} return null;
    }



		/**
		 *
		 * Loads a cache variable from database and removes if afterwards.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-06-22
		 * @param   string $key The key of the cache variable.
		 * @return  mixed       The cache variable. Since the cache value is
		 *                      stored in serialized form, this value can be of
		 *                      any type. If the value was not found in the
		 *                      cache table, this function will return NULL.
		 *
		 */

    function getCacheValue_remove($key) {
    	$value = $this->getCacheValue($key);
    	$this->removeCacheValue($key);
    	return $value;
    }



		/**
		 *
		 * Prints a debug message.
		 * This function prints a debug message. The message will be treated
		 * differently depending on the message type specified by $mode. A fatal
		 * error will cause the entire script to stop at once, while other messages
		 * will just be displayed or not in accordance to the settings specified in
		 * the configuration file.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-06-22
		 * @param   string $message The message to be displayed
		 * @param   int    $mode    The message mode. Determines what is done with
		 *                          the message.
		 * @return  void
		 *
		 */

	function debug($message, $mode=0) {
		if($this->conf['cron_verbose'] == $this->DEBUGMODE_QUIET || strlen($message)==0) return;
		switch($mode) {
			case $this->DEBUG_FATAL: fwrite(STDERR,'FATAL ERROR - '.$message.chr(10)); die(); break;
			case $this->DEBUG_ERROR: fwrite(STDERR,'ERROR - '.$message.chr(10)); break;
			case $this->DEBUG_WARNING: $this->debug('Warning - '.$message); break;
			case $this->DEBUG_NOTICE: $this->debug('Note - '.$message); break;
			default: if($this->conf['cron_verbose'] == $this->DEBUGMODE_ALL) echo $message.chr(10); break;
		}
	}
}

	// XClass inclusion
if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/cron/classes/class.tx_mmforum_cronbase.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/cron/classes/class.tx_mmforum_cronbase.php"]);
}
?>
