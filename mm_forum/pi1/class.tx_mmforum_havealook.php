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
 *   59: class tx_mmforum_havealook extends tslib_pibase
 *   69:     function set_havealook ($content,$conf)
 *  104:     function del_havealook ($content,$conf)
 *  125:     function edit_havealook ($content,$conf)
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * The class 'tx_mmforum_havealook' is a subclass for the 'Forum'
 * plugin (tx_mmforum_pi1) of the 'mm_forum' extension.
 * It handles subscriptions for email notifications on new posts
 * in certain topics.
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
	 * Adds a topic to a user's list of email subscriptions.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The configuration vars of the plugin
	 * @return string          An error message in case the redirect attempt to
	 *                         the previous page fails.
	 */
	function set($forumObj) {
		$feUserId = intval($GLOBALS['TSFE']->fe_user->user['uid']);
		$topicId  = intval($forumObj->piVars['tid']);
		if ($feUserId && $topicId) {
			// Executing database operations
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid',
				'tx_mmforum_topicmail',
				'user_id = ' . $feUserId . ' AND topic_id = ' . $topicId . $forumObj->getPidQuery()
			);

			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) < 1) {
				$insertArray = array(
					'pid'		=> $forumObj->getStoragePID(),
					'tstamp'    => time(),
					'crdate'    => time(), 
					'topic_id'  => $topicId,
					'user_id'   => $feUserId
				);
				$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_topicmail', $insertArray);
			}
		 }

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
			// Executing database operations
			$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery(
				'tx_mmforum_topicmail',
				'user_id = ' . $feUserId . ' AND topic_id = ' . $topicId . $forumObj->getPidQuery()
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

		if ($feUserId) {
			// Delete a subscription
			if ($forumObj->piVars['deltid']) {
				if ($forumObj->piVars['delmode'] == 'topic') {
					$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_topicmail', 'user_id = ' . $feUserId . ' AND topic_id = ' . intval($forumObj->piVars['deltid']));
				} else {
					$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_forummail', 'user_id = ' . $feUserId . ' AND topic_id = ' . intval($forumObj->piVars['deltid']));
				}
				unset($forumObj->piVars['deltid']);
			}

			// Delete several subscriptions
			if ($forumObj->piVars['havealook_action'] == 'delete') {
				foreach ((array) $forumObj->piVars['fav_delete']['topic'] as $del_tid) {
					$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_topicmail', 'user_id = ' . $feUserId . ' AND topic_id = ' . intval($del_tid));
				}
				foreach ((array) $forumObj->piVars['fav_delete']['forum'] as $del_tid) {
					$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_forummail', 'user_id = ' . $feUserId . ' AND forum_id = ' . intval($del_tid));
				}
				unset($forumObj->piVars['havealook_action']);
			}

			// Determination of sorting mode
			$orderBy = ($forumObj->piVars['order'] ? $forumObj->piVars['order'] : 'added');

			// Starting output
			$templateFile = $forumObj->cObj->fileResource($forumObj->conf['template.']['favorites']);
			$template     = $forumObj->cObj->getSubpart($templateFile, '###FAVORITES_SETTINGS###');
			$marker = array(
				'###ACTION###'             => $forumObj->shieldURL($forumObj->getAbsUrl($forumObj->pi_linkTP_keepPIvars_url())),
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
			$content .= $forumObj->cObj->substituteMarkerArray($template, $marker);


			$templateFile2 = $forumObj->cObj->fileResource($conf['template.']['havealook']);
			$template      = $forumObj->cObj->getSubpart($templateFile2, '###HAVEALOOK_BEGIN###');
			$marker = array(
				'###ACTION###'					=> $forumObj->shieldURL($forumObj->getAbsUrl($forumObj->pi_linkTP_keepPIvars_url())),
				'###LABEL_HAVEALOOK###'			=> $forumObj->pi_getLL('havealook.title'),
				'###LABEL_OPTIONS###'			=> $forumObj->pi_getLL('favorites.options'),
				'###LABEL_TOPICNAME###'			=> $forumObj->pi_getLL('topic.title'),
				'###LABEL_CONFIRMMULTIPLE###'	=> $forumObj->pi_getLL('havealook.confirmMultiple')
			); 
			$content .= $forumObj->cObj->substituteMarkerArrayCached($template, $marker);

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
					$order = 'item_title ASC';
					break;
				default:
					$order = '';
			}

			$sql = '(SELECT' .
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
					'	\'topic\' 				AS notify_mode ' .
					'FROM' .
					'	tx_mmforum_topicmail m' .
					'	LEFT JOIN tx_mmforum_topics t ON m.topic_id = t.uid ' .
					'	LEFT JOIN tx_mmforum_forums f ON t.forum_id = f.uid ' .
					'	LEFT JOIN tx_mmforum_forums c ON f.parentID = c.uid ' .
					'WHERE' .
					'	m.user_id = ' . $feUserId . ' AND ' .
					'	m.deleted = 0 AND ' .
					'	t.deleted = 0 AND' .
					'	f.deleted = 0 AND' .
					'	c.deleted = 0 ' .
						$forumObj->getMayRead_forum_query('f').
						$forumObj->getMayRead_forum_query('c').
					') ' .
					'UNION ' .
					'(SELECT' .
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
					'	LEFT JOIN tx_mmforum_forums c ON f.parentID = c.uid ' .
					'WHERE' .
					'	m.user_id = ' . $feUserId . ' AND ' .
					'	m.deleted = 0 AND ' .
					'	f.deleted = 0 AND ' .
					'	c.deleted = 0 ' .
						$forumObj->getMayRead_forum_query('f').
						$forumObj->getMayRead_forum_query('c').
					') ' .
					'ORDER BY ' .
						$order;

			$res = $GLOBALS['TYPO3_DB']->sql_query($sql);

			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) {
				$template = $forumObj->cObj->getSubpart($templateFile2, '###LIST_HAVEALOOK_EMPTY###');
				$content .= $forumObj->cObj->substituteMarker($template, '###LLL_HAVEALOOK_EMPTY###', $forumObj->pi_getLL('havealook.empty'));
			} else {
				$template = $forumObj->cObj->getSubpart($templateFile2, '###LIST_HAVEALOOK###');
				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

					$imgInfo = array(
						'src' => $forumObj->conf['path_img'] . $forumObj->conf['images.']['solved'],
						'alt' => $forumObj->pi_getLL('topic.isSolved')
					);

					$solved = ($row['item_solved'] == 1 ? $forumObj->buildImageTag($imgInfo) : '');
					$prefix = ($row['item_prefix'] ? $forumObj->cObj->wrap($row['item_prefix'], $forumObj->conf['list_topics.']['prefix_wrap']) : '');

					$marker['###TOPIC_CHECKBOX###'] = '<input type="checkbox" name="tx_mmforum_pi1[fav_delete]['.$row['notify_mode'].'][]" value="'.$row['item_uid'].'" />';
					if ($row['notify_mode'] == 'topic') {
						$linkParams[$forumObj->prefixId] = array(
							'action' => 'list_post',
							'tid'    => $row['item_uid']
						);                        
						$marker['###TOPICICON###'] = $forumObj->getTopicIcon($row['item_uid']);
					} else {
						$linkParams[$forumObj->prefixId] = array(
							'action' => 'list_topic',
							'fid'    => $row['item_uid']
						);
						$marker['###TOPICICON###'] = $forumObj->getForumIcon($row['item_uid']);
					}
					$marker['###TOPICNAME###'] = $prefix . $forumObj->pi_linkToPage($forumObj->shield($row['item_title']), $forumObj->conf['pid_forum'],'',$linkParams) . $solved;
					$marker['###TOPICSUB###'] = $forumObj->shield($row['cat_title']).' / '.$forumObj->shield($row['forum_title']) . ($row['notify_mode'] == 'topic' ? ' / ' . $forumObj->shield($row['item_title']) : '');

					$linkParams[$forumObj->prefixId] = array(
						'action'  	=> 'havealook',
						'deltid'  	=> $row['item_uid'],
						'delmode'	=> $row['notify_mode']
					);
					$marker['###TOPICDELLINK###'] = $forumObj->pi_linkTP($forumObj->pi_getLL('havealook.delete'), $linkParams);

					$content .= $forumObj->cObj->substituteMarkerArrayCached($template, $marker);
				}
			}
			$template = $forumObj->cObj->getSubpart($templateFile2, '###HAVEALOOK_END###');

			$marker = array(
				'###LABEL_MARKEDTOPICS###' => $forumObj->pi_getLL('havealook.markedTopics'),
				'###LABEL_DELETE###'       => $forumObj->pi_getLL('havealook.delete'),
				'###LABEL_GO###'           => $forumObj->pi_getLL('havealook.go')
			);
			$content .= $forumObj->cObj->substituteMarkerArray($template, $marker);
		} else {
			$templateFile = $forumObj->cObj->fileResource($conf['template.']['login_error']);
			$template = $forumObj->cObj->getSubpart($templateFile, '###LOGINERROR###');
			$content .= $forumObj->cObj->substituteMarker($template, '###LOGINERROR_MESSAGE###', $forumObj->pi_getLL('subscr.noLogin'));
		}

		return $content;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_havealook.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_havealook.php']);
}

?>
