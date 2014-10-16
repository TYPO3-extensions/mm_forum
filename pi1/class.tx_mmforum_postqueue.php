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
 */

/**
 * Require post factory class
 */
require_once ( t3lib_extMgm::extPath('mm_forum').'pi1/class.tx_mmforum_postfactory.php' );

/**
 * This class handles the administration of the moderated version of the
 * mm_forum extension. It displayes a list of all posts queued for being
 * published and uses the postfactory class to eventually create the posts
 * that are selected for publishing.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    2007-07-21
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_postqueue {

	/**
	 * Main function.
	 * This is the postqueue class' main function which mainly consists
	 * of inheriting the most important plugin variables from the parent
	 * plugin object.
	 * Furthermore it handles the calling of content generating functions.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-07-19
	 * @param   array  $conf   The calling plugin's configuration array
	 * @param   object $parent The calling plugin
	 * @return  string         The content
	 */
	function main($conf, $parent) {
		$this->conf 	= $conf;
		$this->parent 	= $parent;
		$this->cObj		= $parent->cObj;
		$this->piVars	= $parent->piVars['postqueue'];

		if($this->piVars['action'] == 'commit') {
			$this->commit_changes();
		}

		$content = $this->display_postQueue();

		return $content;
	}

	/**
	 * Wrapper function for language label retrieval.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-07-19
	 * @param   string $key     The language label's key
	 * @param   string $default The default label if no language label was found
	 * @return  string          The language label
	 */
	function pi_getLL($key, $default='') {
		return $this->parent->pi_getLL($key,$default);
	}

	/**
	 * Wrapper function for RealURL functionality.
	 *
	 * @author  Martin Helmich
	 * @version 2007-07-19
	 * @return  boolean  TRUE, if RealURL support is enabled, otherwise FALSE
	 */
	function getIsRealURL() {
		return $this->parent->getIsRealURL();
	}

	/**
	 * Wrapper function for link generation.
	 *
	 * @author  Martin Helmich
	 * @version 2007-07-19
	 * @param   string $str   The link string
	 * @param   array  $param The URL parameters
	 * @return  string        The link
	 */
	function pi_linkTP($str,$param) {
		return $this->parent->pi_linkTP($str,$param);
	}

	/**
	 * Wrapper function for link generation.
	 *
	 * @author  Martin Helmich
	 * @version 2007-07-19
	 * @param   string $str    The link string
	 * @param   int    $id     The UID of the page to be linked
	 * @param   string $target The link's target
	 * @param   array  $params The URL parameters
	 * @return  string         The link
	 */
	function pi_linkToPage($str, $id, $target, $params) {
		return $this->parent->pi_linkToPage($str,$id,$target,$params);
	}

	/**
	 * Generates a link to a forum.
	 * This function generates a link to a specific forum which is
	 * specified by it's UID that is submitted as parameter. Also displays
	 * the category name.
	 *
	 * @author  Martin Helmich
	 * @version 2007-07-19
	 * @param   int    $fid The forum UID
	 * @return  string      A link to the forum
	 */
	function getForumLink($fid) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'f.forum_name, c.forum_name as cat_name',
			'tx_mmforum_forums f, tx_mmforum_forums c',
			'f.uid='.$fid.' AND c.uid=f.parentID'
		);
		$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

		return $arr['cat_name'].' / '.$this->pi_linkToPage($arr['forum_name'],$fid,'',array('fid'=>$fid));
	}

	/**
	 * Saves changes.
	 * This function saves the options the moderator selected in the postqueue
	 * overview list to the database. For example, all postqueue elements marked
	 * with 'publish' are now written into the mm_forum post/topic table.
	 *
	 * @author  Martin Helmich
	 * @version 2007-07-21
	 * @return  void
	 */
	function commit_changes() {

		$postfactory = t3lib_div::makeInstance('tx_mmforum_postfactory');
		$postfactory->init($this->conf,$this->parent);

		foreach($this->piVars['items'] as $item_uid => $action) {
			if($action == 'ignore') {
				$updateArray = array(
					'tstamp'			=> time(),
					'hidden'			=> 1
				);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_postqueue','uid='.$item_uid,$updateArray);
			}
			elseif($action == 'delete') {
				$updateArray = array(
					'tstamp'			=> time(),
					'deleted'			=> 1
				);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_postqueue','uid='.$item_uid,$updateArray);
			} else {
				$this->commit_create($item_uid,$postfactory);
			}
		}

		$postfactory->updateQueue_process();

	}

	/**
	 * Creates a post/topic
	 * This function creates a single post/topic from a postqueue record. For
	 * this, the postfactory class is used.
	 *
	 * @author  Martin Helmich
	 * @version 2007-07-21
	 * @param   int    $item_uid     The UID of the postqueue record from which a post is
	 *                               to be generated.
	 * @param   object &$postfactory A reference to the postfactory object that is to be used
	 *                               for creating this post.
	 * @return  void
	 */
	function commit_create($item_uid,&$postfactory) {

		$item_uid = intval($item_uid);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tx_mmforum_postqueue',
			'uid='.$item_uid.' AND deleted=0'
		);
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) return false;

		$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

		if($arr['topic']) {
			$postfactory->create_topic(
				$arr['topic_forum'],
				$arr['post_user'],
				$arr['topic_title'],
				$arr['post_text'],
				$arr['post_time'],
				$arr['post_ip'],
				t3lib_div::trimExplode(',',$arr['post_attachment']),
				$arr['topic_poll'],
				$arr['topic_subscribe'],
				true
			);
		} else {
			$postfactory->create_post(
				$arr['post_parent'],
				$arr['post_user'],
				$arr['post_text'],
				$arr['post_time'],
				$arr['post_ip'],
				t3lib_div::trimExplode(',',$arr['post_attachment']),
				true
			);
		}

		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_postqueue','uid='.$item_uid,array('tstamp'=>time(),'deleted'=>1));

	}

	/**
	 * Displays the post queue.
	 * This function displays all elements of the postqueue in a list
	 * view.
	 *
	 * @author  Martin Helmich
	 * @version 2007-07-21
	 * @return  string The postqueue list content
	 */
	function display_postQueue() {

		$template		= $this->cObj->fileResource($this->conf['template.']['postqueue']);
		$template		= $this->cObj->getSubpart($template, '###POSTQUEUE_LIST###');
		$template_row	= $this->cObj->getSubpart($template, '###POSTQUEUE_ITEM###');

		$marker = array(
			'###LLL_PUBLISH###'			=> $this->pi_getLL('postqueue.publishtab'),
			'###LLL_DELETE###'			=> $this->pi_getLL('postqueue.deletetab'),
			'###LLL_IGNORE###'			=> $this->pi_getLL('postqueue.ignoretab'),
			'###LLL_POSTTEXT###'		=> $this->pi_getLL('postqueue.posttext'),
			'###LLL_POSTQUEUE###'		=> $this->pi_getLL('postqueue.title'),
			'###LLL_PUBLISHBUTTON###'	=> $this->pi_getLL('postqueue.publishbutton'),
			'###LLL_NOITEMS###'			=> $this->pi_getLL('postqueue.noitems'),
			'###ACTION###'				=> $this->parent->escapeURL($this->parent->tools->getAbsoluteUrl($this->parent->pi_getPageLink($GLOBALS['TSFE']->id)))
		);
		$template		= $this->cObj->substituteMarkerArray($template, $marker);
		$rContent		= '';

        $boards = $this->getModeratorBoards();

        if(is_array($boards)) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery (
                'q.*',
                'tx_mmforum_postqueue q LEFT JOIN tx_mmforum_topics t ON q.post_parent = t.uid',
                'q.deleted = 0 AND (t.deleted=0 OR t.uid IS NULL) AND (q.topic_forum IN ('.implode(',',$boards).') OR t.forum_id IN ('.implode(',',$boards).'))',
                '',
                'q.crdate DESC'
            );
        } elseif($boards === true) {
		    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			    '*',
			    'tx_mmforum_postqueue',
			    'deleted=0',
			    '',
			    'crdate DESC'
		    );
        }

		if($boards !== false) {
			if($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
				$template		= $this->cObj->substituteSubpart($template, '###POSTQUEUE_NOITEMS###', '');
			} else {
				$template		= $this->cObj->substituteSubpart($template, '###POSTQUEUE_ITEMLIST###', '');
			}

			while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$rMarker = array(
					'###LLL_WROTE###'		=> $this->pi_getLL('postqueue.wrote'),
					'###DATE###'			=> $this->parent->formatDate($arr['post_time']),
					'###POST_TEXT###'		=> $this->parent->bb2text($this->parent->escape($arr['post_text']),$this->conf),
					'###UID###'				=> $arr['uid'],
					'###POST_POSTER###'		=> $this->parent->linkToUserProfile($arr['post_user']),
					'###CHECK_DELETE###'	=> '',
					'###CHECK_IGNORE###'	=> $arr['hidden']?'checked="checked"':'',
					'###CHECK_PUBLISH###'	=> $arr['hidden']?'':'checked="checked"',
					'###FORUMPATH###'		=> $this->getForumLink($arr['topic_forum']),
				);
				if($arr['topic']) {
					$rMarker['###TOPIC_LINK###'] = $this->parent->escape($arr['topic_title']).' ['.$this->pi_getLL('postqueue.newTopic').']';
				} else {
					$tData = $this->parent->getTopicData($arr['post_parent']);
					$linkParams[$this->parent->prefixId] = array(
						'action'		=> 'list_post',
						'tid'			=> $tData['uid']
					);
					if($this->parent->getIsRealURL()) $linkParams[$this->parent->prefixId]['fid'] = $tData['forum_id'];

					$rMarker['###TOPIC_LINK###'] = $this->parent->pi_linkToPage($this->parent->escape($tData['topic_title']),$this->conf['pid_forum'],'',$linkParams);
				}

				$rContent .= $this->cObj->substituteMarkerArray($template_row, $rMarker);
			}

			$template = $this->cObj->substituteSubpart($template, '###POSTQUEUE_ITEM###', $rContent);
		} else {
			$template		= $this->cObj->substituteSubpart($template, '###POSTQUEUE_ITEMLIST###', '');
		}

		return $template;

	}

    function getModeratorBoards() {

		if($this->parent->getIsAdmin()) return true;

        $groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];

		if(count($groups) == 0) return false;

        foreach($groups as $group) {
            $queryParts[] = 'FIND_IN_SET('.$group.',c.grouprights_mod)';
            $queryParts[] = 'FIND_IN_SET('.$group.',f.grouprights_mod)';
        }
        $query = implode(' OR ', $queryParts);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery (
            'f.uid',
            'tx_mmforum_forums f LEFT JOIN tx_mmforum_forums c ON c.uid=f.parentID',
            'f.deleted=0 AND c.deleted=0 AND ('.$query.')'
        );
        while(list($uid)=$GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
            $result[] = $uid;
        }
        return count($result)?$result:false;

    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_postqueue.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_postqueue.php']);
}
?>