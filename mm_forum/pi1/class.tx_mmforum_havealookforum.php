<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2007 Mittwald CM Service
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
 ***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   59: class tx_mmforum_havealookforum extends tslib_pibase
 *   69:     function set_havealookforum ($content,$conf)
 *  104:     function del_havealookforum ($content,$conf)
 *  125:     function edit_havealookforum ($content,$conf)
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * This class handles subscriptions for email notifications on new posts
 * in certain topics.
 * This class is not meant for instanciation, but only for static
 * function calls from the pi1 plugin, since it depends on the
 * LOCAL_LANG array of the main plugin.
 *
 * @author     Holger Trapp <h.trapp@mittwald.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Björn Detert <b.detert@mittwald.de>
 * @author	   Cyrill Helg <cyrill.h@solnet.ch>
 * @copyright  2007 Mittwald CM Service
 * @version    11. 01. 2007
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_havealookforum {

	/**
	 * Adds a topic to a user's list of email subscriptions.
	 *
	 * @param  array  $forumObj The plugin object
	 * @return string           An error message in case the redirect attempt to
	 *                          the previous page fails.
	 */
	function set($forumObj) {
		$feUserId = intval($GLOBALS['TSFE']->fe_user->user['uid']);
		$forumId  = intval($forumObj->piVars['fid']);

		// Executing database operations
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'tx_mmforum_forummail',
			'user_id = ' . $feUserId . ' AND forum_id = ' . $forumId . $forumObj->getStoragePIDQuery()
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) < 1) {
			$insertArray = array(
				'pid'      => $forumObj->getStoragePID(),
				'tstamp'   => time(),
				'crdate'   => time(),
				'forum_id' => $forumId,
				'user_id'  => $feUserId
			);
			$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_forummail', $insertArray);
		}

		// Redirecting visitor back to previous page
		$forumObj->redirectToReferrer();
		return $forumObj->pi_getLL('subscr.addSuccess') . '<br />' . $forumObj->pi_getLL('redirect.error') . '<br />';
	}

	/**
	 * Removes a topic from a user's list of email subscriptions.
	 *
	 * @param  array  $forumObj The plugin object
	 * @return string           An error message in case the redirect attempt to
	 *                          the previous page fails.
	 */
	function delete($forumObj) {
		$feUserId = intval($GLOBALS['TSFE']->fe_user->user['uid']);
		$forumId  = intval($forumObj->piVars['fid']);

		// Executing database operations
		$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery(
			'tx_mmforum_forummail',
			'user_id = ' . $feUserId . ' AND forum_id = ' . $forumId . $forumObj->getStoragePIDQuery()
		);

		// Redirecting visitor back to previous page
		$forumObj->redirectToReferrer();
		return $forumObj->pi_getLL('subscr.delSuccess') . '<br />' . $forumObj->pi_getLL('redirect.error') . '<br />';
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_havealookforum.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_havealookforum.php']);
}

?>
