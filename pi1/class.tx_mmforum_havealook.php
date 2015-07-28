<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2007-2008 Mittwald CM Service
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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   59: class tx_mmforum_havealook
 *   69:     function set($forumObj)
 *  104:     function delete($forumObj)
 *  125:     function edit($forumObj)
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * The class 'tx_mmforum_havealook'  handles subscriptions for email
 * notifications on new posts in certain topics.
 * This class is not meant for instanciation, but only for static
 * function calls from the pi1 plugin, since it depends on the
 * LOCAL_LANG array of the main plugin.
 *
 * @author     Holger Trapp <h.trapp@mittwald.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Björn Detert <b.detert@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_havealook {

	/**
	 * The TYPO3 database object
	 *
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseHandle;

	/**
	 * Constructor. takes the database handle from $GLOBALS['TYPO3_DB']
	 */
	public function __construct() {
		$this->databaseHandle = $GLOBALS['TYPO3_DB'];
	}

	/**
	 * Adds a topic to a user's list of email subscriptions and then does a
	 * redirect to the previous page.
	 * this function is called from teh
	 * @param \tx_mmforum_base $forumObj The plugin object
	 * @return string           An error message in case the redirect attempt to
	 *                          the previous page fails.
	 */
	static function set(tx_mmforum_base $forumObj) {
		tx_mmforum_havealook::addSubscription($forumObj, $forumObj->piVars['tid'], $GLOBALS['TSFE']->fe_user->user['uid']);

		// Redirecting visitor back to previous page
		$forumObj->redirectToReferrer();
		return $forumObj->pi_getLL('subscr.addSuccess') . '<br/>' . $forumObj->pi_getLL('redirect.error') . '<br />';
	}

	/**
	 * Removes a topic from a user's list of email subscriptions.
	 *
	 * @param \tx_mmforum_base $forumObj The plugin object
	 * @return string             An error message in case the redirect attempt to
	 *                            the previous page fails.
	 */
	static function delete(tx_mmforum_base $forumObj) {
		$feUserId = intval($GLOBALS['TSFE']->fe_user->user['uid']);
		$topicId  = intval($forumObj->piVars['tid']);

		if ($feUserId && $topicId) {
			// Executing database operation
			$this->databaseHandle->exec_DELETEquery(
				'tx_mmforum_topicmail',
				'user_id = ' . $feUserId . ' AND topic_id = ' . $topicId . $forumObj->getStoragePIDQuery()
			);
		}

		// Redirecting visitor back to previous page
		$forumObj->redirectToReferrer();
		return $forumObj->pi_getLL('subscr.delSuccess') . '<br />' . $forumObj->pi_getLL('redirect.error') . '<br />';
	}

	/**
	 * Displays a list of a user's email subscriptions.
	 * Performs also actions like editing or deleting subscriptions.
	 * @param \tx_mmforum_base $forumObj
	 * @return string
	 */
	static function edit(\tx_mmforum_base $forumObj) {
		$content = '';
		$feUserId = intval($GLOBALS['TSFE']->fe_user->user['uid']);

		// can be
		//    "topic" - only topic subscriptions
		//    "forum" - only forum subscriptions
		//    "all"    - both of them (default)
		$displayMode = 'all';
		if ($forumObj->conf['havealook.']['displayOnlyTopics']) {
			$displayMode = 'topic';
		}
		if (isset($forumObj->piVars['displayMode'])) {
			$displayMode = $forumObj->piVars['displayMode'];
		}

		if ($feUserId) {

			// Delete a single subscription (through the link at every subscription)
			if ($forumObj->piVars['deltid']) {
				$deleleTopicId = intval($forumObj->piVars['deltid']);
				if ($forumObj->piVars['delmode'] == 'topic') {
					$this->databaseHandle->exec_DELETEquery(
						'tx_mmforum_topicmail',
						'user_id = ' . $feUserId . ' AND topic_id = ' . $deleleTopicId . $forumObj->getStoragePIDQuery()
					);
				} else {
					$this->databaseHandle->exec_DELETEquery(
						'tx_mmforum_forummail',
						'user_id = ' . $feUserId . ' AND forum_id = ' . $deleleTopicId . $forumObj->getStoragePIDQuery()
					);
				}
				unset($forumObj->piVars['deltid']);
			}

			// Delete several subscriptions (through the checkboxes)
			if ($forumObj->piVars['havealook_action'] == 'delete') {
				foreach ((array) $forumObj->piVars['fav_delete']['topic'] as $deleleTopicId) {
					$this->databaseHandle->exec_DELETEquery(
						'tx_mmforum_topicmail',
						'user_id = ' . $feUserId . ' AND topic_id = ' . intval($deleleTopicId) . $forumObj->getStoragePIDQuery()
					);
				}
				foreach ((array) $forumObj->piVars['fav_delete']['forum'] as $deleleTopicId) {
					$this->databaseHandle->exec_DELETEquery(
						'tx_mmforum_forummail',
						'user_id = ' . $feUserId . ' AND forum_id = ' . intval($deleleTopicId) . $forumObj->getStoragePIDQuery()
					);
				}
				unset($forumObj->piVars['havealook_action']);
			}

			// Determination of sorting mode
			$orderBy = ($forumObj->piVars['order'] ? $forumObj->piVars['order'] : 'added');

			// rendering the settings
			$templateFile = $forumObj->cObj->fileResource($forumObj->conf['template.']['havealook']);
			$template     = $forumObj->cObj->getSubpart($templateFile, '###HAVEALOOK_SETTINGS###');
			$marker = array(
				'###ACTION###'             => $forumObj->escapeURL($forumObj->tools->getAbsoluteUrl($forumObj->pi_linkTP_keepPIvars_url())),
				'###ORDER_LPDATE###'       => ($orderBy == 'lpdate') ? 'selected="selected"' : '',
				'###ORDER_CAT###'          => ($orderBy == 'cat'   ) ? 'selected="selected"' : '',
				'###ORDER_ADDED###'        => ($orderBy == 'added' ) ? 'selected="selected"' : '',
				'###ORDER_ALPHAB###'       => ($orderBy == 'alphab') ? 'selected="selected"' : '',

				'###LABEL_ORDERBY###'      => $forumObj->pi_getLL('favorites.orderBy'),
				'###LABEL_ORDER_LPDATE###' => $forumObj->pi_getLL('favorites.orderBy.lpdate'),
				'###LABEL_ORDER_CAT###'    => $forumObj->pi_getLL('favorites.orderBy.cat'),
				'###LABEL_ORDER_ADDED###'  => $forumObj->pi_getLL('favorites.orderBy.added'),
				'###LABEL_ORDER_ALPHAB###' => $forumObj->pi_getLL('favorites.orderBy.alphab')
			);

			// Include hook to modify the output of the settings
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['havealook']['listsettings'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['havealook']['listsettings'] as $_classRef) {
					$_procObj = &GeneralUtility::getUserObj($_classRef);
					$marker   = $_procObj->havealook_listsettings($marker, $forumObj);
				}
			}
			$content = $forumObj->cObj->substituteMarkerArray($template, $marker);

			// rendering the head part
			$template      = $forumObj->cObj->getSubpart($templateFile, '###HAVEALOOK_BEGIN###');
			$marker = array(
				'###ACTION###'                => $forumObj->escapeURL($forumObj->tools->getAbsoluteUrl($forumObj->pi_linkTP_keepPIvars_url())),
				'###LABEL_HAVEALOOK###'       => $forumObj->pi_getLL('havealook.title'),
				'###LABEL_OPTIONS###'         => $forumObj->pi_getLL('favorites.options'),
				'###LABEL_TOPICNAME###'       => $forumObj->pi_getLL('topic.title'),
				'###LABEL_CONFIRMMULTIPLE###' => $forumObj->pi_getLL('havealook.confirmMultiple')
			);
			$content .= $forumObj->cObj->substituteMarkerArray($template, $marker);

			switch($orderBy) {
				case 'lpdate':
					$order = 'item_lastpost_uid DESC';
					break;
				case 'cat':
					$order = 'cat_order ASC, forum_order ASC, item_lastpost_uid DESC';
					break;
				case 'added':
					$order = 'mail_uid DESC';
					break;
				case 'alphab':
				default:
					$order = 'item_title ASC';
					break;
			}

			$sqlTopic = 'SELECT' .
					'	t.topic_title			AS item_title,' .
					'	t.uid					AS item_uid,' .
					'	t.topic_last_post_id	AS item_lastpost_uid,' .
					'	t.solved				AS item_solved,' .
					'	t.topic_is				AS item_prefix,' .
					'	m.uid 					AS mail_uid,' .
					'	f.forum_name			AS forum_title,' .
					'	f.uid					AS forum_uid,' .
					'	f.forum_last_post_id	AS forum_lastpost_id,' .
					'	f.sorting 				AS forum_order,' .
					'	c.forum_name			AS cat_title,' .
					'	c.uid					AS cat_uid,' .
					'	c.sorting 				AS cat_order,' .
					'	"topic" 				AS notify_mode ' .
					'FROM' .
					'	tx_mmforum_topicmail m' .
					'	LEFT JOIN tx_mmforum_topics t ON m.topic_id = t.uid ' .
					'	LEFT JOIN tx_mmforum_forums f ON t.forum_id = f.uid ' .
					'	LEFT JOIN tx_mmforum_forums c ON f.parentID = c.uid ' .
					'WHERE' .
					'	m.user_id = ' . $feUserId . ' AND ' .
					'	m.deleted = 0 AND' .
					'	t.deleted = 0 AND' .
					'	f.deleted = 0 AND' .
					'	c.deleted = 0 ' .
						$forumObj->getMayRead_forum_query('f').
						$forumObj->getMayRead_forum_query('c');

				$sqlForum = 'SELECT' .
					'	f.forum_name			AS item_title,' .
					'	f.uid					AS item_uid,' .
					'	f.forum_last_post_id	AS item_lastpost_uid,' .
					'	0						AS item_solved,' .
					'	""						AS item_prefix,' .
					'	m.uid					AS mail_uid,' .
					'	f.forum_name			AS forum_title,' .
					'	f.uid					AS forum_uid,' .
					'	f.forum_last_post_id	AS forum_lastpost_uid,' .
					'	f.sorting				AS forum_order,' .
					'	c.forum_name			AS cat_title,' .
					'	c.uid					AS cat_uid,' .
					'	c.sorting				AS cat_order,' .
					'	"forum"					AS notify_mode ' .
					'FROM' .
					'	tx_mmforum_forummail m' .
					'	LEFT JOIN tx_mmforum_forums f ON m.forum_id = f.uid ' .
					'	LEFT JOIN tx_mmforum_forums c ON (f.parentID = c.uid OR (f.parentID = 0 AND f.uid = c.uid)) ' .
					'WHERE' .
					'	m.user_id = ' . $feUserId . ' AND ' .
					'	m.deleted = 0 AND ' .
					'	f.deleted = 0 AND ' .
					'	c.deleted = 0 ' .
						$forumObj->getMayRead_forum_query('f').
						$forumObj->getMayRead_forum_query('c');

			if ($displayMode == 'topic') {
				$sql = $sqlTopic;
			} else if ($displayMode == 'forum') {
				$sql = $sqlForum;
			} else {
				$sql = '(' . $sqlTopic . ') UNION (' . $sqlForum . ')';
			}

			$sql .= 'ORDER BY ' . $order;
			$res = $this->databaseHandle->sql_query($sql);

			if ($this->databaseHandle->sql_num_rows($res) == 0) {
				$template = $forumObj->cObj->getSubpart($templateFile, '###LIST_HAVEALOOK_EMPTY###');
				$content .= $forumObj->cObj->substituteMarker($template, '###LLL_HAVEALOOK_EMPTY###', $forumObj->pi_getLL('havealook.empty'));
			} else {
				$template = $forumObj->cObj->getSubpart($templateFile, '###LIST_HAVEALOOK###');

				// go through every found subscription
				while ($row = $this->databaseHandle->sql_fetch_assoc($res)) {
					if ($row['notify_mode'] == 'topic') {
						$linkParams[$forumObj->prefixId] = array(
							'action' => 'list_post',
							'tid'    => $row['item_uid']
						);
						$marker['###TOPICICON###'] = $forumObj->getTopicIcon($row['item_uid']);
					} else {
						$linkParams[$forumObj->prefixId] = array(
							'action' => 'list_prefix',
							'fid'    => $row['item_uid']
						);
						$marker['###TOPICICON###'] = $forumObj->getForumIcon($row['item_uid']);
					}

					$imgInfo = array(
						'src' => $forumObj->conf['path_img'] . $forumObj->conf['images.']['solved'],
						'alt' => $forumObj->pi_getLL('topic.isSolved')
					);
					$marker['###SOLVED###']         = ($row['item_solved'] == 1 ? $forumObj->buildImageTag($imgInfo) : '');
					$marker['###PREFIX###']         = ($row['item_prefix']      ? $forumObj->cObj->wrap($row['item_prefix'], $forumObj->conf['list_topics.']['prefix_wrap']) : '');
					$marker['###NAME###']           = $forumObj->pi_linkToPage($forumObj->escape($row['item_title']), $forumObj->conf['pid_forum'], '', $linkParams);
					$marker['###FORUMNAME###']      = $forumObj->escape($row['forum_title']);
					$marker['###CATEGORY###']       = $forumObj->escape($row['cat_title']);
					$marker['###TOPICNAME###']      = $marker['###PREFIX###'] . $marker['###NAME###'] . $marker['###SOLVED###'];
					$marker['###TOPICSUB###']       = $marker['###CATEGORY###'] . ' / ' . $marker['###FORUMNAME###'] . ($row['notify_mode'] == 'topic' ? ' / ' . $forumObj->escape($row['item_title']) : '');
					$marker['###TOPIC_CHECKBOX###'] = '<input type="checkbox" name="tx_mmforum_pi1[fav_delete]['.$row['notify_mode'].'][]" value="' . $row['item_uid'] . '" />';

					$linkParams[$forumObj->prefixId] = array(
						'action'  => 'havealook',
						'deltid'  => $row['item_uid'],
						'delmode' => $row['notify_mode']
					);
					$marker['###DELETELINK###'] = $marker['###TOPICDELLINK###'] = $forumObj->pi_linkTP($forumObj->pi_getLL('havealook.delete'), $linkParams);

					// Include hook to modify the output of each item
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['havealook']['listitem'])) {
						foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['havealook']['listitem'] as $_classRef) {
							$_procObj = &GeneralUtility::getUserObj($_classRef);
							$marker = $_procObj->havealook_listitem($marker, $row, $forumObj);
						}
					}
					$content .= $forumObj->cObj->substituteMarkerArrayCached($template, $marker);
				}
			}

			$template = $forumObj->cObj->getSubpart($templateFile, '###HAVEALOOK_END###');
			$marker = array(
				'###LABEL_MARKEDTOPICS###' => $forumObj->pi_getLL('havealook.markedTopics'),
				'###LABEL_DELETE###'       => $forumObj->pi_getLL('havealook.delete'),
				'###LABEL_GO###'           => $forumObj->pi_getLL('havealook.go')
			);

		} else {
			$templateFile = $forumObj->cObj->fileResource($forumObj->conf['template.']['login_error']);
			$template     = $forumObj->cObj->getSubpart($templateFile, '###LOGINERROR###');
			$marker = array(
				'###LOGINERROR_MESSAGE###' => $forumObj->pi_getLL('subscr.noLogin'),
			);
		}

		// Include hook to modify the output of the whole thing
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['havealook']['edit'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['havealook']['edit'] as $_classRef) {
				$_procObj = &GeneralUtility::getUserObj($_classRef);
				$marker   = $_procObj->havealook_edit($marker, $forumObj);
			}
		}

		$content .= $forumObj->cObj->substituteMarkerArray($template, $marker);
		return $content;
	}

	/**
	 * Adds a topic to a (logged in) user's list of email subscriptions.
	 *
	 * @param  \tx_mmforum_base $forumObj The plugin object
	 * @param  string $topicId The topic identifier
	 * @param $feUserId
	 * @return bool             Whether it worked or not
	 */
	function addSubscription(\tx_mmforum_base $forumObj, $topicId, $feUserId) {
		$feUserId = intval($feUserId);
		$topicId  = intval($topicId);
		if ($feUserId && $topicId) {
			// Executing database operations
			$res = $this->databaseHandle->exec_SELECTquery(
				'uid',
				'tx_mmforum_topicmail',
				'user_id = ' . $feUserId . ' AND topic_id = ' . $topicId . $forumObj->getStoragePIDQuery()
			);

			if ($this->databaseHandle->sql_num_rows($res) < 1) {
				$insertData = array(
					'pid'      => $forumObj->getStoragePID(),
					'tstamp'   => $GLOBALS['EXEC_TIME'],
					'crdate'   => $GLOBALS['EXEC_TIME'],
					'topic_id' => $topicId,
					'user_id'  => $feUserId
				);
				return $this->databaseHandle->exec_INSERTquery('tx_mmforum_topicmail', $insertData);
			} else {
				// it's already added, so "it worked"
				return true;
			}
		}
		// invalid parameters
		return false;
	}


	/**
	 * Sends an e-mail to users who have subscribed a certain topic.
	 *
	 * @param  int            $topicId  The UID of the topic about which the users are
	 *                                  to be alerted.
	 * @param  \tx_mmforum_base $forumObj An instance of the tx_mmforum_pi1 class.
	 * @return void
	 */
	function notifyTopicSubscribers($topicId, \tx_mmforum_base $forumObj) {
		$topicId = intval($topicId);
		$res = $this->databaseHandle->exec_SELECTquery('topic_title', 'tx_mmforum_topics', 'uid = ' . $topicId  . $forumObj->getStoragePIDQuery());
		list($topicName) = $this->databaseHandle->sql_fetch_row($res);

		$template = $forumObj->pi_getLL('ntfMail.text');

		$linkParams[$forumObj->prefixId] = array(
			'action' => 'open_topic',
			'id'     => $topicId
		);

		$link = $forumObj->pi_getPageLink($forumObj->conf['pid_forum'], '', $linkParams);
		$link = $forumObj->tools->escapeBrackets($link);

		if (strlen($forumObj->conf['notifyingMail.']['topicLinkPrefix_override']) > 0) {
			$link = $forumObj->conf['notifyingMail.']['topicLinkPrefix_override'] . $link;
		} else {
			$link = $forumObj->tools->getAbsoluteUrl($link);
		}

		$marker = array(
			'###LINK###'      => $link,
			'###TOPICNAME###' => $topicName,
			'###BOARDNAME###' => $forumObj->conf['boardName'],
			'###TEAM###'      => $forumObj->conf['teamName']
		);
                
                if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['notifyTopicSubscribers_marker'])) {
                    foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['notifyTopicSubscribers_marker'] as $_classRef) {
                        $_procObj = &GeneralUtility::getUserObj($_classRef);
                        $marker = $_procObj->notifyTopicSubscribers_marker($topicId, $marker, $forumObj);
                    }
                }

		// get all users on this topic
		$res = $this->databaseHandle->exec_SELECTquery(
			'DISTINCT tx_mmforum_topicmail.user_id, fe_users.email, fe_users.' . $forumObj->getUserNameField(),
			'tx_mmforum_topicmail, fe_users',
			'tx_mmforum_topicmail.user_id = fe_users.uid AND tx_mmforum_topicmail.topic_id = ' . $topicId .
			' AND fe_users.deleted = 0 AND fe_users.email != "" AND fe_users.disable = 0 AND tx_mmforum_topicmail.user_id != ' . intval($GLOBALS['TSFE']->fe_user->user['uid']) . $forumObj->getStoragePIDQuery('tx_mmforum_topicmail')
		);

		// loop through each user who subscribed
		while (list($toUserId, $toEmail, $toUsername) = $this->databaseHandle->sql_fetch_row($res)) {
			$marker['###USERNAME###'] = $forumObj->escape($toUsername);
			$mailtext = $forumObj->cObj->substituteMarkerArrayCached($template, $marker);

			// Compose mail and send
			if (!empty($toEmail)) {
				$llMarker = array(
					'###TOPICNAME###' => $forumObj->escape($topicName),
					'###BOARDNAME###' => $forumObj->escape($forumObj->conf['boardName'])
				);
				$subject = $forumObj->pi_getLL('ntfMail.subject');

				// Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_contentMarker'])) {
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_contentMarker'] as $_classRef) {
						$_procObj = &GeneralUtility::getUserObj($_classRef);
						//TODO: FIXME undefined variable $row
						$llMarker = $_procObj->newPostMail_contentMarker($llMarker, $row, $forumObj);
					}
				}
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_subject'])) {
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_subject'] as $_classRef) {
						$_procObj = &GeneralUtility::getUserObj($_classRef);
						//TODO: FIXME undefined variable $row
						$subject = $_procObj->newPostMail_subject($subject, $row, $forumObj);
					}
				}

				$subject = $forumObj->cObj->substituteMarkerArray($subject, $llMarker);

				$do_send = true;
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_beforeSend'])) {
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_beforeSend'] as $_classRef) {
						$_procObj = &GeneralUtility::getUserObj($_classRef);
						$do_send = $_procObj->newPostMail_beforeSend($subject, $mailtext, $toUserId, $this);
					}
				}

				if ($do_send) {
					$mail = GeneralUtility::makeInstance('t3lib_mail_Message');
					$mail->setFrom(array($forumObj->conf['notifyingMail.']['sender_address'] => $forumObj->conf['notifyingMail.']['sender']));
					$mail->setTo(array($toEmail => $toUsername));
					$mail->setSubject($subject);
					$mail->setBody($mailtext, 'text/plain');
					$mail->send();
				}
			}
		}
	}

	/**
	 * Sends an e-mail to users who have subscribed to certain forumcategory
	 * @param $topicId int The UID of the new topic that was created
	 * @param $forumId int The UID of the forum about which the users are to be alerted.
	 * @param \tx_mmforum_base $forumObj
	 * @return void
	 * @author Cyrill Helg
	 */
	static function notifyForumSubscribers($topicId, $forumId, \tx_mmforum_base $forumObj) {
		$res = $this->databaseHandle->exec_SELECTquery('topic_title', 'tx_mmforum_topics', 'uid = ' . intval($topicId) . $forumObj->getStoragePIDQuery());
		list($topicName) = $this->databaseHandle->sql_fetch_row($res);

		$res = $this->databaseHandle->exec_SELECTquery('forum_name, parentID', 'tx_mmforum_forums', 'uid = ' . intval($forumId) . $forumObj->getStoragePIDQuery());
		list($forumName, $categoryId) = $this->databaseHandle->sql_fetch_row($res);

		// prepare the template (the variables that don't change all the time need only to be set once)
		$linkParams[$forumObj->prefixId] = array(
			'action' => 'open_topic',
			'id'     => $topicId
		);

		$link = $forumObj->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams);
		$link = $forumObj->tools->escapeBrackets($link);

		if (strlen($forumObj->conf['notifyingMail.']['topicLinkPrefix_override']) > 0)
			$link = $forumObj->conf['notifyingMail.']['topicLinkPrefix_override'] . $link;
		else $link = $forumObj->tools->getAbsoluteUrl($link);

		$template = $forumObj->pi_getLL('ntfMailForum.text');

		$marker = array(
			'###LINK###'      => $link,
			'###USERNAME###'  => $toUsername, //TODO: FIXME undefined variable $toUsername
			'###FORUMNAME###' => $forumName,
			'###TEAM###'      => $forumObj->conf['teamName'],
		);

		$subjectMarker = array(
			'###TOPICNAME###' => $topicName,
			'###FORUMNAME###' => $forumName,
			'###BOARDNAME###' => $forumObj->conf['boardName']
		);

		// loop through each user who subscribed
		$res = $this->databaseHandle->exec_SELECTquery(
			'DISTINCT tx_mmforum_forummail.user_id, fe_users.email, fe_users.' . $forumObj->getUserNameField(),
			'tx_mmforum_forummail, fe_users',
			'tx_mmforum_forummail.user_id = fe_users.uid AND
			 (tx_mmforum_forummail.forum_id = ' . intval($forumId) . ($categoryId > 0 ? ' OR tx_mmforum_forummail.forum_id = ' . $categoryId : '') . ') AND
			 fe_users.deleted = 0 AND
			 fe_users.disable = 0 AND
			 fe_users.email != "" AND
			 tx_mmforum_forummail.user_id != ' . intval($GLOBALS['TSFE']->fe_user->user['uid']) . $forumObj->getStoragePIDQuery('tx_mmforum_forummail')
		);

		while (list($toUserId, $toEmail, $toUsername) = $this->databaseHandle->sql_fetch_row($res)) {
			$marker['###USERNAME###'] = $forumObj->escape($toUsername);
			$mailtext = $forumObj->cObj->substituteMarkerArrayCached($template, $marker);

			// Compose mail and send
			$subject = $forumObj->cObj->substituteMarkerArray($forumObj->pi_getLL('ntfMailForum.subject'), $subjectMarker);
			$mail = GeneralUtility::makeInstance('t3lib_mail_Message');
			$mail->setFrom(array($forumObj->conf['notifyingMail.']['sender_address'] => $forumObj->conf['notifyingMail.']['sender']));
			$mail->setTo(array($toEmail => $toUsername));
			$mail->setSubject($subject);
			$mail->setBody($mailtext, 'text/plain');
			$mail->send();
		}
	}
}


if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_havealook.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_havealook.php']);
}
