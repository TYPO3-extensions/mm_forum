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
 * @version    11. 01. 2007
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_havealook {

	/**
	 * Adds a topic to a user's list of email subscriptions and then does a
	 * redirect to the previous page.
	 * this function is called from teh
	 * @param  string $forumObj The plugin object
	 * @return string           An error message in case the redirect attempt to
	 *                          the previous page fails.
	 */
	function set($forumObj) {
		tx_mmforum_havealook::addSubscription($forumObj, $forumObj->piVars['tid'], $GLOBALS['TSFE']->fe_user->user['uid']);

		// Redirecting visitor back to previous page
		$forumObj->redirectToReferrer();
		return $forumObj->pi_getLL('subscr.addSuccess') . '<br/>' . $forumObj->pi_getLL('redirect.error') . '<br />';
	}


	/**
	 * Removes a topic from a user's list of email subscriptions.
	 *
	 * @param  object  $forumObj  The plugin object
	 * @return string             An error message in case the redirect attempt to
	 *                            the previous page fails.
	 */
	function delete($forumObj) {
		$feUserId = intval($GLOBALS['TSFE']->fe_user->user['uid']);
		$topicId  = intval($forumObj->piVars['tid']);

		if ($feUserId && $topicId) {
			// Executing database operation
			$GLOBALS['TYPO3_DB']->exec_DELETEquery(
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
	 * @param  string $content The plugin content
	 * @param  array  $conf    The configuration vars of the plugin
	 */
	function edit($forumObj) {
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
					$GLOBALS['TYPO3_DB']->exec_DELETEquery(
						'tx_mmforum_topicmail',
						'user_id = ' . $feUserId . ' AND topic_id = ' . $deleleTopicId . $forumObj->getStoragePIDQuery()
					);
				} else {
					$GLOBALS['TYPO3_DB']->exec_DELETEquery(
						'tx_mmforum_forummail',
						'user_id = ' . $feUserId . ' AND forum_id = ' . $deleleTopicId . $forumObj->getStoragePIDQuery()
					);
				}
				unset($forumObj->piVars['deltid']);
			}

			// Delete several subscriptions (through the checkboxes)
			if ($forumObj->piVars['havealook_action'] == 'delete') {
				foreach ((array) $forumObj->piVars['fav_delete']['topic'] as $deleleTopicId) {
					$GLOBALS['TYPO3_DB']->exec_DELETEquery(
						'tx_mmforum_topicmail',
						'user_id = ' . $feUserId . ' AND topic_id = ' . intval($deleleTopicId) . $forumObj->getStoragePIDQuery()
					);
				}
				foreach ((array) $forumObj->piVars['fav_delete']['forum'] as $deleleTopicId) {
					$GLOBALS['TYPO3_DB']->exec_DELETEquery(
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
					$_procObj = &t3lib_div::getUserObj($_classRef);
					$marker   = $_procObj->havealook_listsettings($marker, $forumObj);
				}
			}
			$content .= $forumObj->cObj->substituteMarkerArray($template, $marker);


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
			$res = $GLOBALS['TYPO3_DB']->sql_query($sql);

			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) {
				$template = $forumObj->cObj->getSubpart($templateFile, '###LIST_HAVEALOOK_EMPTY###');
				$content .= $forumObj->cObj->substituteMarker($template, '###LLL_HAVEALOOK_EMPTY###', $forumObj->pi_getLL('havealook.empty'));
			} else {
				$template = $forumObj->cObj->getSubpart($templateFile, '###LIST_HAVEALOOK###');

				// go through every found subscription
				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

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
							$_procObj = &t3lib_div::getUserObj($_classRef);
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
				$_procObj = &t3lib_div::getUserObj($_classRef);
				$marker   = $_procObj->havealook_edit($marker, $forumObj);
			}
		}

		$content .= $forumObj->cObj->substituteMarkerArray($template, $marker);
		return $content;
	}


	/**
	 * Adds a topic to a (logged in) user's list of email subscriptions.
	 *
	 * @param  string $forumObj The plugin object
	 * @param  string $topicId  The topic identifier
	 * @return bool             Whether it worked or not
	 */
	function addSubscription($forumObj, $topicId, $feUserId) {
		$feUserId = intval($feUserId);
		$topicId  = intval($topicId);
		if ($feUserId && $topicId) {
			// Executing database operations
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid',
				'tx_mmforum_topicmail',
				'user_id = ' . $feUserId . ' AND topic_id = ' . $topicId . $forumObj->getStoragePIDQuery()
			);

			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) < 1) {
				$insertData = array(
					'pid'      => $forumObj->getStoragePID(),
					'tstamp'   => time(),
					'crdate'   => time(),
					'topic_id' => $topicId,
					'user_id'  => $feUserId
				);
				return $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_topicmail', $insertData);
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
	 * @param  tx_mmforum_pi1 $forumObj An instance of the tx_mmforum_pi1 class.
     * @return void
     */
	function notifyTopicSubscribers($topicId, $forumObj) {
		$topicId = intval($topicId);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title', 'tx_mmforum_topics', 'uid = ' . $topicId  . $forumObj->getStoragePIDQuery());
		list($topicName) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

		$sender = $forumObj->conf['notifyingMail.']['sender'];
		if (!preg_match('/<([^><@]*?)@([^><@]*?)>$/',$forumObj->conf['notifyingMail.']['sender'])) {
			$sender = $forumObj->conf['notifyingMail.']['sender'] . ' <' . $forumObj->conf['notifyingMail.']['sender_address'] . '>';
		}

		$mailHeaders = array(
			'From: ' . $sender,
			'Content-type: text/plain;charset=' . $GLOBALS['TSFE']->renderCharset,
		);

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
			'###BOARDNAME###' => $forumObj->conf['boardName']
		);


		// get all users on this topic
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'DISTINCT m.user_id, u.email, u.' . $forumObj->getUserNameField(),
			'tx_mmforum_topicmail m, fe_users u',
			'm.user_id = u.uid AND m.topic_id = ' . $topicId .
			' AND u.deleted = 0 AND u.email != "" AND u.disable = 0 AND m.user_id != ' . intval($GLOBALS['TSFE']->fe_user->user['uid']) . $forumObj->getStoragePIDQuery('m')
		);

		// loop through each user who subscribed
		while (list($toUserId, $toEmail, $toUsername) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
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
						$_procObj = &t3lib_div::getUserObj($_classRef);
						$llMarker = $_procObj->newPostMail_contentMarker($llMarker, $row, $forumObj);
					}
				}
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_subject'])) {
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_subject'] as $_classRef) {
						$_procObj = &t3lib_div::getUserObj($_classRef);
						$subject = $_procObj->newPostMail_subject($subject, $row, $forumObj);
					}
				}

				$subject = $forumObj->cObj->substituteMarkerArray($subject, $llMarker);

				$do_send = true;
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_beforeSend'])) {
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_beforeSend'] as $_classRef) {
						$_procObj = &t3lib_div::getUserObj($_classRef);
						$do_send = $_procObj->newPostMail_beforeSend($subject, $mailtext, $toUserId, $this);
					}
				}

				if($do_send) {
					t3lib_div::plainMailEncoded(
						$toEmail,
						$subject,
						$mailtext,
						implode("\n", $mailHeaders),
						'base64',
						$GLOBALS['TSFE']->renderCharset
					);
				}
			}
		}
	}


	/**
	 * Sends an e-mail to users who have subscribed to certain forumcategory
	 * @param  string $content The plugin content
	 * @param  array  $conf    The configuration vars
	 * @param  int    $topic_id   The UID of the new topic that was created
	 * @param  int    $forum_id   The UID of the forum about which the users are
	 *                        to be alerted.
	 * @return void
	 * @author Cyrill Helg
	 */
	function notifyForumSubscribers($topicId, $forumId, $forumObj) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title', 'tx_mmforum_topics', 'uid = ' . intval($topicId) . $forumObj->getStoragePIDQuery());
		list($topicName) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('forum_name, parentID', 'tx_mmforum_forums', 'uid = ' . intval($forumId) . $forumObj->getStoragePIDQuery());
		list($forumName, $categoryId) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

		$sender = $forumObj->conf['notifyingMail.']['sender'];
		if (!preg_match('/<([^><@]*?)@([^><@]*?)>$/',$forumObj->conf['notifyingMail.']['sender'])) {
			$sender = $forumObj->conf['notifyingMail.']['sender'] . ' <' . $forumObj->conf['notifyingMail.']['sender_address'] . '>';
		}

		$mailHeaders = array(
			'From: ' . $sender,
			'Content-type: text/plain;charset=' . $GLOBALS['TSFE']->renderCharset,
		);

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
			'###USERNAME###'  => $toUsername,
			'###FORUMNAME###' => $forumName,
		);

		$subjectMarker = array(
			'###TOPICNAME###' => $topicName,
			'###FORUMNAME###' => $forumName,
			'###BOARDNAME###' => $forumObj->conf['boardName']
		);

		// loop through each user who subscribed
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'DISTINCT m.user_id, u.email, u.' . $forumObj->getUserNameField(),
			'tx_mmforum_forummail m, fe_users u',
			'm.user_id = u.uid AND
			 (m.forum_id = ' . intval($forumId) . ($categoryId > 0 ? ' OR m.forum_id = ' . $categoryId : '') . ') AND
			 u.deleted = 0 AND
			 u.disable = 0 AND
			 u.email != "" AND
			 m.user_id != ' . intval($GLOBALS['TSFE']->fe_user->user['uid']) . $forumObj->getStoragePIDQuery('m')
		);

		while (list($toUserId, $toEmail, $toUsername) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
			$marker['###USERNAME###'] = $forumObj->escape($toUsername);
			$mailtext = $forumObj->cObj->substituteMarkerArrayCached($template, $marker);

			// Compose mail and send
			$subject = $forumObj->cObj->substituteMarkerArray($forumObj->pi_getLL('ntfMailForum.subject'), $subjectMarker);
			mail($toEmail, $subject, $mailtext, implode("\n", $mailHeaders));
		}
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_havealook.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_havealook.php']);
}

?>