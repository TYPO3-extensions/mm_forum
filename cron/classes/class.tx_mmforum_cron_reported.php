<?php
/***************************************************************
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
 ***************************************************************/

require_once ( dirname(PATH_thisScript).'/classes/class.tx_mmforum_cronbase.php' );
require_once ( PATH_t3lib.'class.t3lib_parsehtml.php' );

/**
 * Handles automatic reminders for posts that still have to be
 * published that are send as email.
 *
 * @package 	mm_forum
 * @subpackage 	Cronjobs
 * @author		Martin Helmich <m.helmich@mittwald.de>
 * @author 		Nathan Lenz <nathan.lenz@organicvalley.coop>
 * @version		2008-01-08
 * @copyright   2008 Mittwald CM Service
 */
class tx_mmforum_cron_reported extends tx_mmforum_cronbase {

	var $postalertItems;
	var $postalertMail;
	var $lang;
	var $cron_name = 'tx_mmforum_cron_reported';

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

		$this->loadPostAlertItems();

		if(count($this->postalertItems)>0) {
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
			$mailtext		= t3lib_parsehtml::substituteMarkerArray($this->postalertMail, $marker);
			$subject		= sprintf($this->getLL('subject'), count($this->postalertItems));
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
	 * be sent. The result of this function is stored into the attribute $this->postalertMail.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @return  void
	 */
	function generateOutput() {

		$template			= $this->loadTemplateFile('notifyReported');
		if($this->conf['cron_htmlemail'])
			$template		= t3lib_parsehtml::getSubpart($template, '###PNOTIFY_HTML###');
		else $template		= t3lib_parsehtml::getSubpart($template, '###PNOTIFY_PLAINTEXT###');

		$itemTemplate		= t3lib_parsehtml::getSubpart($template, '###PNOTIFY_LISTITEM###');

		$marker				= array(
			'###PNOTIFY_SUBJECT###'				=> sprintf($this->getLL('subject'), count($this->postalertItems)),
			'###PNOTIFY_TEXT###'				=> sprintf($this->getLL('text'), $this->conf['cron_sitetitle'], count($this->postalertItems)),
			'###PNOTIFY_LINK###'				=> $this->conf['cron_alertqueue_link'],
			'###LABEL_PNOTIFY_TOPICTITLE###'	=> $this->getLL('topictitle'),
			'###LABEL_PNOTIFY_REPORTDATE###'	=> $this->getLL('reportdate'),
			'###LABEL_PNOTIFY_TOPICAUTHOR###'	=> $this->getLL('topicauthor'),
			'###LABEL_PNOTIFY_TOPICFORUM###'	=> $this->getLL('topicforum'),

			'###LABEL_PNOTIFY_TOPICREPORTER###'	=> $this->getLL('postreporter'),

			'###LABEL_PNOTIFY_ALERTTEXT###'		=> $this->getLL('alerttext'),

			'###LABEL_PNOTIFY_POSTCONTENT###'	=> $this->getLL('reportedcontent'),
		);
		$template			= t3lib_parsehtml::substituteMarkerArray($template, $marker);

		foreach($this->postalertItems as $postalertItem) {
			$itemMarker = array(
				'###PNOTIFY_TOPICTITLE###'		=> $this->getTopicTitle($postalertItem['topic_id']),
				'###PNOTIFY_TOPICAUTHOR###'		=> $this->getPostAuthor($postalertItem['post_id']),

				'###PNOTIFY_TOPICREPORTER###'   => $this->getUsername($postalertItem['cruser_id']),
				'###PNOTIFY_REPORTDATE###'		=> date('d. m. Y, H:i',$postalertItem['crdate']),
				'###PNOTIFY_TOPICFORUM###'		=> $this->getTopicForum($postalertItem['topic_id']),

				'###PNOTIFY_POSTCONTENT###'     => $this->getPostContent($postalertItem['post_id']),

				'###PNOTIFY_ALERTTEXT###'		=> stripslashes($postalertItem['alert_text']),
				'###LABEL_PNOTIFY_ALERTTEXT###'		=> $this->getLL('alerttext'),

				'###LABEL_PNOTIFY_POSTCONTENT###'	=> $this->getLL('reportedcontent'),
			);

			$itemContent .= t3lib_parsehtml::substituteMarkerArray($itemTemplate, $itemMarker);
		}

		$template			= t3lib_parsehtml::substituteSubpart($template, '###PNOTIFY_LISTITEM###', $itemContent);
		$this->postalertMail = $template;
	}

	/**
	 * Return the name of a topic's board.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @author  Nathan Lenz <nathan.lenz@organicvalley.coop>
	 * @param   int $topic_id the topic's UID
	 * @return  string        The name of the forum this topic is contained in
	 */
	function getTopicForum($topic_id) {

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('forum_id','tx_mmforum_topics','uid='.intval($topic_id).' AND deleted=0');

		list($forum_uid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('forum_name', 'tx_mmforum_forums', 'uid='.intval($forum_uid).' AND deleted=0');

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
	function getUsername($user_uid) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($this->conf['userNameField'], 'fe_users', 'uid='.$user_uid.' AND deleted=0');

		if($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
			list($username) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res); return $username;
		} else return $this->getLL('deletedUser');
	}

	/**
	 * Gets the topic title from a topic_id.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @param   int		$topic_id The topic ID
	 * @return  string        The topic title of the postqueue item
	 */
	function getTopicTitle($topic_id) {

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title','tx_mmforum_topics','uid='.intval($topic_id));
		list($title) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $title;

	}



	/**
	 * Get the name of the author given a post id.
	 *
	 * @author Nathan Lenz <nathan.lenz@organicvalley.coop>
	 * @param  int 		$post_id 	The id of the post.
	 * @return string				The username of the author.
	 */
	function getPostAuthor($post_id) {

		if ($res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('u.'.$this->conf['userNameField'], 'fe_users as u, tx_mmforum_posts as p', 'p.uid='.intval($post_id).' AND p.poster_id = u.uid AND u.deleted=0 and p.deleted=0')) {
			list($username) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res); return $username;
		} else {
			return $this->getLL('deletedUser');
		}
	}

	/**
	 * Get the name of the author given a post id.
	 *
	 * @author Nathan Lenz <nathan.lenz@organicvalley.coop>
	 * @param  int 		$post_id 	The id of the post.
	 * @return string				The content of the post
	 */
	function getPostContent($post_id) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('post_text,cache_text','tx_mmforum_posts_text','uid='.intval($post_id));
		list($plain,$html) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $this->conf['cron_htmlemail'] ? $html : $plain;
	}

	/**
	 * Loads the postalert items thare moderators are to be informed of.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @return  void
	 */
	function loadPostAlertItems() {

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_post_alert','deleted=0 AND hidden=0 AND status <> 1');
		while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$this->postalertItems[] = $arr;
		}

	}

}

	// XClass inclusion
if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/cron/classes/class.tx_mmforum_cron_reported.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/cron/classes/class.tx_mmforum_cron_reported.php"]);
}
?>
