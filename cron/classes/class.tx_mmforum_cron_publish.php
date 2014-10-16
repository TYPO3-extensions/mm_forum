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
 *   56: class tx_mmforum_cron_publish extends tx_mmforum_cronbase
 *   72:     function main()
 *   88:     function validateConfig()
 *  106:     function sendEmailToModerators()
 *  145:     function generateOutput()
 *  187:     function getTopicForum($record)
 *  209:     function getTopicAuthor($user_uid)
 *  225:     function getTopicTitle($record)
 *  241:     function loadPostQueueItems()
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require(dirname(PATH_thisScript).'/classes/class.tx_mmforum_cronbase.php');
require_once(PATH_t3lib.'class.t3lib_parsehtml.php');

/**
 * Handles automatic reminders for posts that still have to be
 * published that are send as email.
 *
 * @package 	mm_forum
 * @subpackage 	Cronjobs
 * @author		Martin Helmich <m.helmich@mittwald.de>
 * @version		2008-01-08
 * @copyright   2008 Mittwald CM Service
 */
class tx_mmforum_cron_publish extends tx_mmforum_cronbase {

	var $postqueueItems;
	var $postqueueMail;
	var $lang;
	var $cron_name = 'tx_mmforum_cron_publish';

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
		$this->loadPostQueueItems();

		if(count($this->postqueueItems)>0) {
			$this->content .= $this->generateOutput();
			$this->content .= $this->sendEmailToModerators();
		}
	}

	/**
	 * Validates the cronjob's configuration and exits with an error if something is missing.
	 * @author  Martin Helmich <m.helmich@mittwald.de
	 * @version 2008-06-22
	 * @return  void
	 */
	function validateConfig() {

		parent::validateConfig();
		if(intval($this->conf['cron_notifyPublish_group'])==0) $this->debug('Constant "cron_notifyPublish_group" is not set.',$this->DEBUG_FATAL);

	}

	/**
	 * Sends the email text to members of the moderator group.
	 * This functions sends the email text that has already been created
	 * by the function "generateOutput()" to each moderator user from the
	 * moderator group. The functions return status reports and possibly
	 * error reports.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-01-08
	 * @return  string Status and error reports
	 */
	function sendEmailToModerators() {

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'fe_users',
			'FIND_IN_SET('.$this->conf['cron_notifyPublish_group'].',usergroup) AND deleted=0 AND disable=0 AND pid IN ('.$this->conf['userPID'].')'
		);

		while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$marker			= array(
				'###PNOTIFY_ADDRESS###'			=> sprintf($this->getLL('address'),$arr['username'])
			);
			$mailtext		= t3lib_parsehtml::substituteMarkerArray($this->postqueueMail, $marker);
			$subject		= sprintf($this->getLL('subject'), count($this->postqueueItems));
			$username		= $arr['name']?$arr['name']:$arr['username'];
			$recipient		= '"'.$username.'" <'.$arr['email'].'>';
			$contenttype	= $this->conf['cron_htmlemail']?'text/html':'text/plain';
			$header			= "Content-Type: $contenttype; charset=utf-8\n";
			$header		   .= "From: ".$this->conf['cron_notifyPublishSender']."\n";

			if(!@mail($recipient, $subject, $mailtext, $header)) {
				$this->debug('Could not send email to '.$recipient,$this->DEBUG_ERROR);
			} else $this->debug('Successfully sent email to '.$recipient);
		}
		return $content;

	}

	/**
	 * Generates the notification email content.
	 * This function generates the content of the notification email that is to
	 * be sent. The result of this function is stored into the attribute $this->postqueueMail.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @return  void
	 */
	function generateOutput() {

		$template			= $this->loadTemplateFile('notifyPublish');
		if($this->conf['cron_htmlemail'])
			$template		= t3lib_parsehtml::getSubpart($template, '###PNOTIFY_HTML###');
		else $template		= t3lib_parsehtml::getSubpart($template, '###PNOTIFY_PLAINTEXT###');

		$itemTemplate		= t3lib_parsehtml::getSubpart($template, '###PNOTIFY_LISTITEM###');

		$marker				= array(
			'###PNOTIFY_SUBJECT###'				=> sprintf($this->getLL('subject'), count($this->postqueueItems)),
			'###PNOTIFY_TEXT###'				=> sprintf($this->getLL('text'), $this->conf['cron_sitetitle'], count($this->postqueueItems)),
			'###PNOTIFY_LINK###'				=> $this->conf['cron_postqueue_link'],
			'###LABEL_PNOTIFY_TOPICTITLE###'	=> $this->getLL('topictitle'),
			'###LABEL_PNOTIFY_TOPICDATE###'		=> $this->getLL('topicdate'),
			'###LABEL_PNOTIFY_TOPICAUTHOR###'	=> $this->getLL('topicauthor'),
			'###LABEL_PNOTIFY_TOPICFORUM###'	=> $this->getLL('topicforum'),
		);
		$template			= t3lib_parsehtml::substituteMarkerArray($template, $marker);

		foreach($this->postqueueItems as $postqueueItem) {
			$itemMarker = array(
				'###PNOTIFY_TOPICTITLE###'		=> $this->getTopicTitle($postqueueItem),
				'###PNOTIFY_TOPICAUTHOR###'		=> $this->getTopicAuthor($postqueueItem['post_user']),
				'###PNOTIFY_TOPICDATE###'		=> date('d. m. Y, H:i',$postqueueItem['post_time']),
				'###PNOTIFY_TOPICFORUM###'		=> $this->getTopicForum($postqueueItem)
			);
			$itemContent .= t3lib_parsehtml::substituteMarkerArray($itemTemplate, $itemMarker);
		}

		$template			= t3lib_parsehtml::substituteSubpart($template, '###PNOTIFY_LISTITEM###', $itemContent);
		$this->postqueueMail = $template;
	}

	/**
	 * Return the name of a topic's board.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @param   mixed $record A topic identifier. This may either be the topic's UID or the
	 *                        topic as associative array.
	 * @return  string        The name of the forum this topic is contained in
	 */
	function getTopicForum($record) {
		if($record['topic_forum']) {
			$forum_uid = $record['topic_forum'];
		} else {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('forum_id','tx_mmforum_topics','uid='.$record['post_parent'].' AND deleted=0');
			list($forum_uid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		}

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('forum_name', 'tx_mmforum_forums', 'uid='.$forum_uid.' AND deleted=0');

		list($forum_name) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $forum_name;
	}

	/**
	 * Gets a user's user name.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @param   int $user_uid The user's UID
	 * @return  string        The user's user name
	 */
	function getTopicAuthor($user_uid) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($this->conf['userNameField'], 'fe_users', 'uid='.$user_uid.' AND deleted=0');

		if($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
			list($username) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res); return $username;
		} else return $this->getLL('deletedUser');
	}

	/**
	 * Gets the topic title from a postqueue record.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @param   array $record The postqueue item.
	 * @return  string        The topic title of the postqueue item
	 */
	function getTopicTitle($record) {
		if($record['topic_title']) return $record['topic_title'];
		else {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title','tx_mmforum_topics','uid='.$record['post_parent']);
			list($title) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
			return $title;
		}
	}

	/**
	 * Loads the postqueue items thare moderators are to be informed of.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @return  void
	 */
	function loadPostQueueItems() {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_postqueue','deleted=0 AND hidden=0');
		while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$this->postqueueItems[] = $arr;
		}
	}

}

	// XClass inclusion
if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/cron/classes/class.tx_mmforum_cron_publish.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/cron/classes/class.tx_mmforum_cron_publish.php"]);
}
?>
