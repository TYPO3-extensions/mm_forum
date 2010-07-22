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
 *   52: class tx_mmforum_user extends tslib_pibase
 *   63:     function list_user_post($conf,$userId,$page)
 *  183:     function listpost_pagelink($label,$page,$userid)
 *  205:     function get_userdetails ($userId)
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_base.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_postparser.php');

/**
 * Plugin 'class.tx_mmforum_user.php' for the 'mm_forum' extension.
 *
 * @author     Holger Trapp <h.trapp@mittwaldmedien.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Björn Detert <b.detert@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_user extends tx_mmforum_base {

	/**
	 * Lists all current user's posts in a HTML table, including page navigation and
	 * general information like total post/topic count and average posts per day.
	 * @param  array  $conf    The plugin's configuration vars.
	 * @param  int    $userId  The UID of the user, whose posts are to be listed.
	 * @param  int    $page    The current page
	 * @return string          The HTML table
	 */
	function list_user_post($conf, $userId = 0, $page = 0) {
		$this->conf = $conf;
		$userId = intval($userId ? $userId : $GLOBALS['TSFE']->fe_user->user['uid']);

		if (!$userId) {
			return $this->errorMessage($conf, $this->pi_getLL('user.noLogin'));
		}
		$page = intval($this->useRealUrl() ? str_replace($this->pi_getLL('realurl.page') . '_', '', $page) : $page);


		$itemsPerPage = 10;
		$templateFile = $this->cObj->fileResource($conf['template.']['userdetail']);
		$template     = $this->cObj->getSubpart($templateFile, '###USERPOSTS###');
		$template_sub = $this->cObj->getSubpart($template, '###LIST###');

		$marker = array(
			'###LABEL_POSTLIST###' => $this->pi_getLL('user.postList'),
			'###LABEL_TOPIC###'    => $this->pi_getLL('board.topic'),
			'###LABEL_LASTPOST###' => $this->pi_getLL('board.lastPost')
		);

		// Determine post count
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'COUNT(uid)',
			'tx_mmforum_posts',
			'hidden = 0 AND deleted = 0 AND poster_id = ' .  $userId
		);
		list($count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

		$marker['###POSTCOUNT###'] = $this->getauthor($userId);

		// Determine topic count
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'COUNT(uid)',
			'tx_mmforum_topics',
			'hidden = 0 AND deleted = 0 AND topic_poster = "' . $userId . '"'
		);
		list($topics) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);


		// Build page navigation
		$pageCount = ceil($count / $itemsPerPage);

		if (!intval($this->conf['doNotUsePageBrowseExtension'])===0) {
			$page --;

			// find the page right now, but if ($page -3) is less than 1, set it to at least "1"
			$page = max($page, 1);
			// also, $page is not allowed to be bigger than the $pageCount
			$page = min($page, $pageCount);

			// find the page links, but if ($page -3) is less than 1, set it to at least "1"
			$i = max(($page - 3), 1);

			for ($j = 1; $j <= 7; $j++) {
				$pagelink = tx_mmforum_user::listpost_pagelink($i, $i, $userId);

				if (($i >= 1 && $i <= $pageCount)) {
					if ($i == $page) {
						$pages .= '|<strong> ' . $i . ' </strong>|';
					} else {
						$pages .= $pagelink;
					}
				}
				$i++;
			}

			$min   = ($page > 1) ? tx_mmforum_user::listpost_pagelink('' . $this->pi_getLL('page.first'), 1, $userId)      : '';
			$left  = ($page > 2) ? tx_mmforum_user::listpost_pagelink('&laquo;' . $this->pi_getLL(''), $page - 1, $userId) : '';
			$right = ($page < $pageCount-1) ? tx_mmforum_user::listpost_pagelink($this->pi_getLL('') . '&raquo;', $page + 1, $userId)    : '';
			$max   = ($page < $pageCount)   ? tx_mmforum_user::listpost_pagelink($this->pi_getLL('page.last') . '', $pageCount, $userId) : '';

			$marker['###PAGES###'] = $min . $left . $pages . $right . $max;
			$marker['###PAGES###'] = str_replace('||', '|', $marker['###PAGES###']);
		} else {
			$marker['###PAGES###'] = $this->getListGetPageBrowser($pageCount);
		}

		$from = $itemsPerPage * ($page);

		if ($count > 0) {
			$template = $this->cObj->substituteSubpart($template, '###NOPOSTS###', '');

			// Read posts
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				't.topic_title, t.topic_is, t.solved,
					f.forum_name,
					c.forum_name AS category_name,
					p.*',
				'tx_mmforum_posts p
					LEFT JOIN tx_mmforum_topics t ON t.uid = p.topic_id
					LEFT JOIN tx_mmforum_forums f ON t.forum_id = f.uid
					LEFT JOIN tx_mmforum_forums c ON f.parentID = c.uid',
				'p.hidden = 0 AND p.deleted = 0 AND p.poster_id = ' . $userId . ' ' .
					$this->getMayRead_forum_query('f'),
				'',
				'p.crdate DESC',
				$from . ', ' . $itemsPerPage
			);
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$title = $this->escape($row['topic_title']);
				if ($row['topic_is']) {
					$title = '<span class="tx-mmforum-prefix">[{'.$row['topic_is'].'}] </span>' . $title;
				}
				$imgInfo = array(
					'src' => $conf['path_img'] . $conf['images.']['solved'],
					'alt' => $this->pi_getLL('topic.isSolved')
				);
				$solved = ($row['solved'] ? $this->buildImageTag($imgInfo) : '');

				$linkParams[$this->prefixId] = array(
					'action' => 'list_post',
					'tid'    => $row['topic_id']
				);
				if ($this->useRealUrl()) {
					$linkParams[$this->prefixId]['fid'] = $row['forum_id'];
				}
				$postMarker = array(
					'###TOPICNAME###' => $this->pi_linkToPage($title, $conf['pid_forum'], '', $linkParams) . $solved,
					'###TOPICDATE###' => $this->formatDate($row['post_time']),
					'###PREFIX###'    => $this->escape($row['topic_is']),
					'###CATEGORY###'  => $this->escape($row['category_name']),
					'###FORUM###'     => $this->escape($row['forum_name']),
					'###READIMAGE###' => $this->getTopicIcon($row['topic_id']),
				);

				// Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUserPosts_item'])) {
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUserPosts_item'] as $_classRef) {
						$_procObj = &t3lib_div::getUserObj($_classRef);
						$postMarker = $_procObj->listUserPosts_item($postMarker, $row, $this);
					}
				}
				$content .= $this->cObj->substituteMarkerArrayCached($template_sub, $postMarker);
			}
		} else {
			$marker['###LABEL_NOPOSTS###'] = $this->pi_getLL('user.noposts');
		}

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('crdate', 'fe_users', 'uid = ' . $userId);
		list($ucrdate) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

		$marker['###STAT###'] .= '<strong>' . $count . '</strong> ' . $this->pi_getLL('user.totalPosts') . ', <strong>' . $topics . '</strong> ' . $this->pi_getLL('user.topicsTotal') . '<br />';
		$marker['###STAT###'] .= $this->cObj->substituteMarker($this->pi_getLL('user.postsPerDay'), '###POSTS###', '<strong>' . round($count / ceil(((time() - $ucrdate) / 86400)), 2) . '</strong>');

		$template = $this->cObj->substituteMarkerArray($template, $marker);
		$content = $this->cObj->substituteSubpart($template, '###LIST###', $content);
		return $content;
	}

	/**
	 * Generates a link to a specific page of a user's post history.
	 * @param  string $label  The label of the page link
	 * @param  int    $page   The number of the page to be linked to
	 * @param  int    $userId The UID of the user whose post history is to be displayed.
	 * @return string         A link to the specified page.
	 */
	function listpost_pagelink($label, $page, $userId) {
		$linkParams[$this->prefixId] = array(
			'action'  => 'post_history',
			'user_id' => $userId,
			'page'    => $page
		);
		if ($this->useRealUrl()) {
			$linkParams[$this->prefixId] = array(
				'action' => 'post_history',
				'fid'    => tx_mmforum_tools::get_username($userId),
				'tid'    => $this->pi_getLL('realurl.page') . '_' . $page
			);
		}
		return '| ' . $this->pi_linkToPage($label, $GLOBALS['TSFE']->id, '', $linkParams) . ' |';
	}

	/**
	 * Returns a user record specified by UID.
	 * @param  int   $userId  The UID of the user whose record is to be loaded
	 * @return array          The user record as associative array
	 */
	function get_userdetails($userId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'fe_users', 'uid = ' . $userId);
		return $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_user.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_user.php']);
}
?>