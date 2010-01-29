#!/usr/bin/php -q
<?php
/*
 *  Copyright notice
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
 */





/**
 *
 *  This script handles the mm_forum cronjob handling. It is NOT intented to
 * be called from browser, but rather used on command line level. This script
 * has to be called with its absolute path (meaning
 * /srv/www/typo3conf/ext/mm_forum/cron/cli.tx_mmforum_cron.php instead of just
 * ./cli.tx_mmforumcron.php). The operation that is to be performed (meaning e.g.
 * search indexing or message reminders) has to be submitted as first parameter.
 * At the moment, available parameters are:
 *
 * indexing  - Starts the mm_forum search indexing. This cronjob should be called
 *             at least hourly in order to keep the search index up to date.
 * messaging - Sends notification emails about private messages that have been
 *             recieved during the last execution of this cronjob. This cronjob should
 *             be called once a day.
 * publish   - Notifies moderators about posts that are waiting to be published. This
 *             only makes sense if the mm_forum is run in the moderated mode. This cron
 *             should be called at least once a day.
 * reported  - Notifies moderators about posts that have been reported by forum users.
 *             This should be called at least once per day.
 *
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2009 Martin Helmich, Mittwald CM Service
 * @version    $Id$
 * @package    mm_forum
 * @subpackage Cronjobs
 *
 */





	// Set content type to plain text and disable HTML errors
header('Content-Type: text/plain');
ini_set('html_errors','0');

	// Define path constants
define('TYPO3_cliMode', true);
define('PATH_thisScript', $_SERVER['SCRIPT_FILENAME']);
if(!defined("STDERR")) define("STDERR", fopen("php://stderr","w"));

	// Load configuration file
require(dirname(PATH_thisScript).'/conf.php');

	// Load includes
require(dirname(PATH_thisScript).'/'.$BACK_PATH.'init.php');

	// Die with error if no parameter was submitted
if($_SERVER['argc'] < 2) die("FATAL ERROR - No parameter submitted. Don't know what to do.\n");

	// Get cronjob mode
$cronMode	= $_SERVER['argv'][1];

	// Determine which cronjob to execute
$cronClassName		= 'tx_mmforum_cron_'.$cronMode;
$cronFileName		= dirname(PATH_thisScript).'/classes/class.'.$cronClassName.'.php';

if(!file_exists($cronFileName)) die("FATAL ERROR - Cronjob mode $cronMode is not known.\n");
else require_once($cronFileName);

	// Instantiate cronjob object and execute
$cronObj = t3lib_div::makeInstance($cronClassName);
$cronObj->initialize();
$cronObj->main();
?>