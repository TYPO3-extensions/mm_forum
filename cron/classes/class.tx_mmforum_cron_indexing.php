<?php
/*
 *  Copyright notice
 *
 *  (c) 2008 Mittwald CM Service
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   52: class tx_mmforum_cron_indexing extends tx_mmforum_cronbase
 *   68:     function main()
 *  104:     function getIndexCount()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require(dirname(PATH_thisScript).'/classes/class.tx_mmforum_cronbase.php');
require(dirname(PATH_thisScript).'/../pi4/class.tx_mmforum_indexing.php');

/**
 * This cronjob script handles the automatic indexing of the mm_forum
 * search. To keep the search index up-to-date, it should be called at
 * least once an hour, preferably several times an hour.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    2008-06-22
 * @copyright  2008 Martin Helmich, Mittwald CM Service
 * @package    mm_forum
 * @subpackage Cronjobs
 */
class tx_mmforum_cron_indexing extends tx_mmforum_cronbase {

	/**
	 * This cronjob's name
	 */
	var $cron_name = 'tx_mmforum_cron_indexing';

	/**
	 * The main function.
	 * This function delegates the indexing class to the tx_mmforum_indexing
	 * class which performs the actual indexing.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @return  void
	 */
	function main() {

			// Report beginning
		$this->debug('Starting indexing');

			// Initialize indexing class
		$indexing = t3lib_div::makeInstance('tx_mmforum_indexing');
		$indexing->objectMode = true;
		$indexing->conf = $this->conf;

			// Load topics that are to be indexed
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tx_mmforum_topics',
			'pid='.$this->getPid(),		// Deleted posts have to be selected too in order to remove deleted posts from index
			'',
			'tx_mmforumsearch_index_write ASC',
			$this->getIndexCount()
		);

		while($topic = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$this->debug($indexing->ind_topic($topic['uid'],$this->conf));
		}

			// Report success
		$this->debug('Finished indexing');
	}

	/**
	 * Determines how much topics are to be indexed at a time. This value can be configured
	 * in the mm_forum configuration file.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @return  int The number of topics to be indexed at a time.
	 */
	function getIndexCount() {
		return $this->conf['cron_index_count']?intval($this->conf['cron_index_count']):'';
	}

}

	// XClass inclusion
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/cron/classes/class.tx_mmforum_cron_indexing.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/cron/classes/class.tx_mmforum_cron_indexing.php']);
}
?>
