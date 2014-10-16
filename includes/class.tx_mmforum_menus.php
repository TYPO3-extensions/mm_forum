<?php
/***************************************************************
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
 ***************************************************************/

require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_base.php');

class tx_mmforum_menus extends tx_mmforum_base {
	var $prefixId = 'tx_mmforum_pi1';

		/* VERY dirty hack, but has to be this way in order for the
		 * plugin to load the pi1 locallang file. */
    var $scriptRelPath = 'pi1/class.tx_mmforum_pi1.php';

	function menuInit($conf) {
		$this->prefixId = ($conf['prefixId'] ? $conf['prefixId'] : 'tx_mmforum_pi1');
		$this->piVars   = t3lib_div::_GPmerged($this->prefixId);
		$this->pi_loadLL();

		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
	}

	/**
	 * Generates a custom forum category menu.
	 * This function generates a custom navigation with a HMENU. This function can be included
	 * as special.userfunc in HMENUs in TypoScript in order to display the mm_forum category
	 * tree as a navigation line.
	 *
	 * @param   string $content The content variable
	 * @param   array  $conf    The configuration array
	 * @return  array           An array containing a set of HMENU items
	 */
	function catMenu($content, $conf) {
		$this->init($conf);
		$this->menuInit($conf);
		$result = array();

    	$forumPid       = ($conf['forumPID']       ? $conf['forumPID']       : $this->getForumPID());
    	$categoryAction = ($conf['categoryAction'] ? $conf['categoryAction'] : 'list_cat');
    	$boardAction    = ($conf['boardAction']    ? $conf['boardAction']    : 'list_topic');
		$activeCategory = intval($this->piVars['cid']);
		$activeBoard    = intval($this->piVars['fid']);
		$activeTopic    = intval($this->piVars['tid']);
		if ($activeTopic && !$activeBoard) {
			$activeBoard = tx_mmforum_pi1::get_forum_id($activeTopic);
		}

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid, forum_name AS name, parentID',
			'tx_mmforum_forums',
			'deleted = 0 AND hidden = 0' . $this->getStoragePIDQuery(),
			'',
			'parentID ASC, sorting ASC'
		);

		// compile every entry in the list
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

			// top level = categories
			if ($row['parentID'] == 0) {
				$linkParams = array(
					'action' => $categoryAction,
					'cid'    => $row['uid']
				);
				$menuItem = array(
					'id'             => $row['uid'],
					'title'          => $row['name'],
					'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url($linkParams, 1, 1, $forumPid),
					'ITEM_STATE'     => ($row['uid'] == $activeCategory ? 'ACT' : 'NO')
				);
				$result[] = $menuItem;
			} else {
				// 2nd level == forums
				$linkParams = array(
					'action' => $boardAction,
					'fid'    => $row['uid']
				);

				$menuItem = array(
					'id'             => $row['uid'],
					'title'          => $row['name'],
					'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url($linkParams, 1, 1, $forumPid),
					'ITEM_STATE'     => ($row['uid'] == $activeBoard ? 'ACT' : 'NO')
				);
				$forums[$row['parentID']][] = $menuItem;
			}
		}

		// if a "forum" (2nd level) is active, we need to find the parentID to highlight
		// the parentID as ACTIFSUB
		foreach ($forums as $parentID => $menuItems) {
			foreach ($menuItems as $menuItem) {
				if ($menuItem['ITEM_STATE'] == 'ACT') {
					$activeCategory = $parentID;
					break;
				}
			}
		}

		// unless we use special.expAll = 1, set the parentID to ACTIFSUB and add
		// the second level of the category menu
		foreach ($result as &$menuItem) {
			if (($menuItem['ITEM_STATE'] == 'ACT' || $menuItem['id'] == $activeCategory || $conf['expAll']) && is_array($forums[$menuItem['id']])) {
				$menuItem['_SUB_MENU']  = $forums[$menuItem['id']];
				$menuItem['ITEM_STATE'] = (($menuItem['ITEM_STATE'] == 'ACT' || $menuItem['id'] == $activeCategory) ? 'ACTIFSUB' : 'IFSUB');
			}
		}

		// we also need to have the option that the category automatically displays the first subcategory
		if ($conf['linkCatToFirstBoard']) {
			foreach ($result as &$menuItem) {
					if (is_array($forums[$menuItem['id']])) {
						$firstBoard = reset($forums[$menuItem['id']]);
						if (is_array($firstBoard)) {
							$menuItem['_OVERRIDE_HREF'] = $firstBoard['_OVERRIDE_HREF'];
						}
					}
			}
		}

		return $result;
	}


	/**
	 * Generates a custom rootline menu.
	 * This function generates a custom rootline menu. This function can be included
	 * as special.userfunc in HMENUs in TypoScript in order to merge the mm_forum
	 * internal rootline with a global page rootline. On the same time, the property
	 * tx_mmforum_pi1.disableRootline should be set to 1.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-07-23
	 * @param   string $content The content variable
	 * @param   array  $conf    The configuration array
	 * @return  array           An array containing a set of HMENU items
	 */
	function createRootline($content, $conf) {
		$this->menuInit($conf);
		$result = array();

		$action = $this->piVars['action'];
		switch ($action) {
			// List post view, new post form, post alert form
			// Displays a rootline like "mm_forum page -> Category -> Board -> Topic (-> New post/Report post)"
			case 'list_post':
			case 'new_post':
			case 'post_alert':
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					't.uid, t.forum_id, c.uid, topic_title, f.forum_name, c.forum_name',
					'tx_mmforum_topics t, tx_mmforum_forums f, tx_mmforum_forums c',
					't.uid="' . intval($this->piVars['tid']) . '" AND f.uid=t.forum_id AND c.uid=f.parentID'
				);
				list($topicId,$forumId,$catId,$topicTitle,$forumTitle,$catTitle) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

				$topicTitle = stripslashes($topicTitle);

				$topicTitle = str_replace('<','&lt;',$topicTitle);
				$topicTitle = str_replace('>','&gt;',$topicTitle);

				if ($action == 'new_post') {
					$linkParams[$this->prefixId] = array(
						'action' => 'new_post',
						'tid'    => $topicId,
						'fid'    => $forumId
					);
					$result[] = array(
						'title'          => $this->pi_getLL('rootline.reply'),
						'_OVERRIDE_HREF' => $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams)
					);
				}
				elseif ($action == 'post_alert') {
					$linkParams[$this->prefixId] = array(
						'action' => 'post_alert',
						'tid'    => $topicId,
						'fid'    => $forumId
					);
					$result[] = array(
						'title'          => $this->pi_getLL('rootline.post_alert'),
						'_OVERRIDE_HREF' => $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams)
					);
				}
			break;

			// New topic form, topic listing view
			// Displays a rootline like "mm_forum page -> Category -> Board (-> New topic)"
			case 'new_topic':
			case 'list_topic':
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'f.uid,f.forum_name,c.uid,c.forum_name',
					'tx_mmforum_forums f, tx_mmforum_forums c',
					'f.uid="'.intval($this->piVars['fid']).'" AND c.uid=f.parentID'
				);
				list($forumId, $forumTitle, $catId, $catTitle) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

				if ($action == 'new_topic') {
					$linkParams[$this->prefixId] = array(
						'action' => 'new_topic',
						'fid'    => $forumId
					);
					$result[] = array(
						'title'          => $this->pi_getLL('rootline.new_topic'),
						'_OVERRIDE_HREF' => $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams)
					);
				}
			break;

			// Post editing form
			// Displays a rootline like "mm_forum page -> Category -> Board -> Topic -> Edit post"
			case 'post_edit':
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					't.uid,t.forum_id,c.uid,topic_title,f.forum_name,c.forum_name',
					'tx_mmforum_posts p, tx_mmforum_topics t, tx_mmforum_forums f, tx_mmforum_forums c',
					'p.uid="' . intval($this->piVars['pid']) . '" AND t.uid=p.topic_id AND f.uid=p.forum_id AND c.uid=f.parentID'
				);
				list($topicId,$forumId,$catId,$topicTitle,$forumTitle,$catTitle) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

				$topicTitle = stripslashes($topicTitle);

				$topicTitle = str_replace('<','&lt;',$topicTitle);
				$topicTitle = str_replace('>','&gt;',$topicTitle);

				$linkParams[$this->prefixId] = array(
					'action' => 'post_edit',
					'fid'    => $forumId,
					'tid'    => $topicId,
					'pid'    => $this->piVars['pid']
				);
				$result[] = array(
					'title'          => $this->pi_getLL('rootline.edit_post'),
					'_OVERRIDE_HREF' => $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams)
				);
			break;

			// User profile
			// Displays a rootline like "mm_forum page -> User profile: Username"
			case 'forum_view_profil':

				if($this->piVars['fid']) {
		            $user_id = tx_mmforum_tools::get_userid($this->piVars['fid']);
		        } else $user_id = $this->piVars['user_id'];

				$conf['userNameField']?$conf['userNameField']:$conf['userNameField']='username';

				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					$conf['userNameField'],
					'fe_users',
					'uid="'.intval($user_id).'"'
				);
				list($username) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
				$linkParams[$this->prefixId] = array(
					'action'  => 'forum_view_profil',
					'user_id' => $this->piVars['user_id']
				);

				$result[] = array(
					'title'          => sprintf($this->pi_getLL('rootline.userprofile'), $username),
					'_OVERRIDE_HREF' => $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams)
				);
			break;

			// List unread or unanswered topics
			// Displays a rootline like "mm_forum page -> List unread/unanswered topics"
			case 'list_unread':
			case 'list_unans':
				$linkParams[$this->prefixId] = array(
					'action' => $action
				);
				$result[] = array(
					'title'          => $this->pi_getLL('rootline.' . $action),
					'_OVERRIDE_HREF' => $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams)
				);
			break;
		}

		if ($topicId) {
			$topicParams[$this->prefixId] = array(
				'action' => 'list_post',
				'tid'    => $topicId,
				'fid'    => $forumId
			);
			$result[] = array(
				'title'          => $topicTitle,
				'_OVERRIDE_HREF' => $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $topicParams)
			);
		}

		if ($forumId) {
			$boardParams[$this->prefixId] = array(
				'action' => 'list_topic',
				'fid'    => $forumId
			);
			$result[] = array(
				'title'          => $forumTitle,
				'_OVERRIDE_HREF' => $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $boardParams)
			);
		}

		if ($catId) {
			$catParams[$this->prefixId] = array(
				'action' => 'list_forum',
			);
			$result[] = array(
				'title'          => $catTitle,
				'_OVERRIDE_HREF' => $this->pi_getPageLink($GLOBALS['TSFE']->id) . '#cat' . $catId
			);
		}
		$result = array_reverse($result);

		if ($conf['entryLevel']) {
			$pageRootline = array_slice($GLOBALS['TSFE']->config['rootLine'], $conf['entryLevel']);
		} else {
			$pageRootline = $GLOBALS['TSFE']->config['rootLine'];
		}

		if(!$conf['includeNotInMenu']) {
			$pageRootline_final = array();
			foreach($pageRootline as $pageRootline_element) {
				if($pageRootline_element['nav_hide'] != 1)
					$pageRootline_final[] = $pageRootline_element;
			}
		} else $pageRootline_final = $pageRootline;

		$result = array_merge((array)$pageRootline_final, $result);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['rootlineArray'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['rootlineArray'] as $_classRef) {
				$_procObj = &t3lib_div::getUserObj($_classRef);
				$result   = $_procObj->processRootlineArray($result, $this);
			}
		}
		return $result;
	}

}
?>