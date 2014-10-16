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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_mmforum_cron_messaging extends tx_mmforum_cronbase
 *   62:     function main()
 *  155:     function getUsername($user_uid)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require(dirname(PATH_thisScript).'/classes/class.tx_mmforum_cronbase.php');
require_once(PATH_t3lib.'class.t3lib_parsehtml.php');

/**
 * This class handles the automatic notification of users about
 * new private messages.
 *
 * @author Martin Helmich <m.helmich@mittwald.de>
 * @version 2008-03-16
 * @package mm_forum
 * @subpackage Cronjobs
 */
class tx_mmforum_cron_messaging extends tx_mmforum_cronbase {

	var $cron_name = 'tx_mmforum_cron_messaging';

	/**
	 * Main function
	 * The main function of this class. Loads language variables and
	 * calls subsidiary functions.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-01-08
	 * @return  string  The output of this script.
	 */
	function main() {

		$this->debug('Starting message notification');

		$user_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'fe_users',
			'tx_mmforum_pmnotifymode = 1 AND deleted=0 AND disable=0 AND starttime < '.time().' AND (endtime = 0 OR endtime <= '.time().')'
		);

		$template			= $this->loadTemplateFile('notifyPM');
		if($this->conf['cron_htmlemail'])
			$template		= t3lib_parsehtml::getSubpart($template, '###NOTIFY_HTML###');
		else $template		= t3lib_parsehtml::getSubpart($template, '###NOTIFY_PLAINTEXT###');

		$itemTemplate		= t3lib_parsehtml::getSubpart($template, '###NOTIFY_LISTITEM###');

		while($user_arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($user_res)) {

			$pm_res		= $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',
				'tx_mmforum_pminbox',
				'to_uid='.$user_arr['uid'].' AND notified=0 AND mess_type=0'
			);
			$pm_content	= '';

			if($GLOBALS['TYPO3_DB']->sql_num_rows($pm_res) == 0) {
				$this->debug('No new messages for user '.$user_arr['username'].'. Continue with next user.');
				continue;
			} else $this->debug($GLOBALS['TYPO3_DB']->sql_num_rows($pm_res).' new messages for user '.$user_arr['username'].'. Creating email.');

			while($pm_arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($pm_res)) {
				$link = sprintf($this->conf['cron_pm_readlink'],$pm_arr['uid']);

				$cache_link = $this->getCacheValue_remove('pm.urlCache.'.$pm_arr['uid']);
				if($cache_link !== false)
					$link = $cache_link;

				$pm_marker = array(
					'###NOTIFY_SUBJECT###'			=> $pm_arr['subject'],
					'###NOTIFY_DATE###'				=> $this->formatDate($pm_arr['sendtime']),
					'###NOTIFY_SENDER###'			=> $this->getUsername($pm_arr['from_uid']),
					'###NOTIFY_LINK###'				=> $link,

					'###LLL_BY###'					=> $this->getLL('by'),
					'###LLL_ON###'					=> $this->getLL('on'),
				);
				$pm_content .= t3lib_parsehtml::substituteMarkerArray($itemTemplate, $pm_marker);
			}

			$user_content	= t3lib_parsehtml::substituteSubpart($template, '###NOTIFY_LISTITEM###', $pm_content);

			$user_marker	= array(
				'###NOTIFY_SUBJECT###'				=> sprintf($this->getLL('subject'),$GLOBALS['TYPO3_DB']->sql_num_rows($pm_res)),
				'###NOTIFY_TEXT###'					=> sprintf($this->getLL('text'),$this->conf['cron_sitetitle'],$GLOBALS['TYPO3_DB']->sql_num_rows($pm_res)),
				'###NOTIFY_ADDRESS###'				=> sprintf($this->getLL('address'),$user_arr[$this->conf['userNameField']?$this->conf['userNameField']:'username']),
				'###NOTIFY_LINK###'					=> $this->conf['cron_pm_link'],

				'###LABEL_NOTIFY_SUBJECT###'		=> $this->getLL('subject'),
				'###LABEL_NOTIFY_SENDER###'			=> $this->getLL('sender'),
				'###LABEL_NOTIFY_DATE###'			=> $this->getLL('date')
			);
			$user_content	= t3lib_parsehtml::substituteMarkerArray($user_content, $user_marker);

			$subject		= sprintf($this->getLL('mailSubject'),$this->conf['cron_sitetitle'],$GLOBALS['TYPO3_DB']->sql_num_rows($pm_res));
			$username		= $user_arr['name']?$user_arr['name']:$user_arr['username'];
			$recipient		= '"'.$username.'" <'.$user_arr['email'].'>';
			$contenttype	= $this->conf['cron_htmlemail']?'text/html':'text/plain';
			$header			= "Content-Type: $contenttype; charset=utf-8\n";
			$header		   .= "From: ".$this->conf['cron_notifyPublishSender']."\n";

			$content .= "Try to send mail($recipient, $subject, ...)\n";

			if(!@mail($recipient, $subject, $user_content, $header)) {
				$this->debug('Could not send email to '.$recipient,$this->DEBUG_ERROR);
			} else $this->debug('Email to user '.$user_arr['username'].' was successfully sent.');

		}

		$updateArray = array('notified' => 1, 'tstamp' => time());
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_pminbox', 'notified=0', $updateArray);

		$this->content = $content;
	}

	/**
	 * Determines a user name.
	 *
	 * @param  int    $user_uid The user UID
	 * @return string           The user name
	 */
	function getUsername($user_uid) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($this->conf['userNameField']?$this->conf['userNameField']:'username', 'fe_users', 'uid='.intval($user_uid).' AND deleted=0');

		if($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
			list($username) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res); return $username;
		} else return $this->getLL('deletedUser');
	}
}

	// XClass inclusion
if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/cron/classes/class.tx_mmforum_cron_messaging.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/cron/classes/class.tx_mmforum_cron_messaging.php"]);
}

?>
