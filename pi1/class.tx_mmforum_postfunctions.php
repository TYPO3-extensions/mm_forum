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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   57: class tx_mmforum_postfunctions extends tslib_pibase
 *   68:     function list_post($content, $conf, $order)
 *  897:     function update_forum_posts_n_topics ($forum_id)
 *  931:     function update_user_posts($user_id)
 *  952:     function update_post_attachment($post_id)
 *  980:     function post_del($content, $conf)
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * The class 'tx_mmforum_postfunctions' is a subclass for the 'Forum'
 * plugin (tx_mmforum_pi1) of the 'mm_forum' extension.
 * It handles the output of post listing and post deletion.
 *
 * @author     Holger Trapp <h.trapp@mittwaldmedien.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Björn Detert <b.detert@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @version    24. 04. 2007
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_postfunctions extends tx_mmforum_base {

	/**
	 * This is here to access the piVars
	 * @var string
	 */
	public $prefixId = 'tx_mmforum_pi1';

	/**
	 * This is needed to load locallang
	 * @var string
	 */
	public $scriptRelPath = 'pi1/class.tx_mmforum_pi1.php';


	/**
	 * @var tx_mmforum_postparser
	 */
	protected $tx_mmforum_postparser;
	
	/**
	 * @var tx_mmforum_rss
	 */
	protected $tx_mmforum_rss;

	/**
	 * @param array $conf
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
	 */
	public function __construct($conf, $cObj) {
		$this->injectCObj($cObj);
		$this->injectConf($conf);
		
		$this->tx_mmforum_rss = GeneralUtility::makeInstance('tx_mmforum_rss');
		$this->tx_mmforum_postparser = GeneralUtility::makeInstance('tx_mmforum_postparser');
		parent::__construct();
	}

	/**
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
	 */
	public function injectCObj($cObj) {
		$this->cObj = $cObj;
	}

	/**
	 * @param $conf
	 */
	public function injectConf($conf) {
		$this->init($conf);
	}
	
	/**
	 * Lists all posts in a certain topic.
	 * @param  string $content The plugin content
	 * @param  array $conf The plugin's configuration vars
	 * @param $order
	 * @return string          The content
	 */
	function list_post($content, $conf, $order) {
		$templateFile = $this->local_cObj->fileResource($conf['template.']['list_post']);
		$topicId = intval($this->piVars['tid']);
		$feUserId = intval($GLOBALS['TSFE']->fe_user->user['uid']);

		// Check authorization
		if (!$this->getMayRead_topic($topicId)) {
			return $this->errorMessage($conf, $this->pi_getLL('topic.noAccess'));
		}

		$topicData = $this->getTopicData($topicId);
		$this->tx_mmforum_rss->setHTMLHeadData('forum', $topicData['forum_id']);
		$this->tx_mmforum_rss->setHTMLHeadData('topic', $topicId);
		$this->local_cObj->data = $topicData;

		// Save admin panel changes
		$this->saveAdminChanges($topicData);

		// Determine sorting mode
		$orderingMode = $conf['list_posts.']['postOrdering'] ? strtoupper($conf['list_posts.']['postOrdering']) : 'ASC';
		$order = strtoupper($order);
		if (in_array($order, array('ASC','DESC'))) $orderingMode = $order;
		if (!in_array($orderingMode, array('ASC','DESC'))) $orderingMode = 'ASC';

		// load the topic data again, to make sure we get the latest additions from the admin changes
		$topicData = $this->getTopicData($topicId);

		// check and set the topic replies
		$res = $this->databaseHandle->exec_SELECTquery(
			'COUNT(*)-1',
			'tx_mmforum_posts',
			'deleted = 0 AND topic_id = ' . $topicId . $this->getStoragePIDQuery()
		);
		list($replies) = $this->databaseHandle->sql_fetch_row($res);
		$updateArray = array('topic_replies' => $replies);
		$this->databaseHandle->exec_UPDATEquery('tx_mmforum_topics', 'uid = ' . $topicId, $updateArray);


		// Set or unset solved flag
		if (isset($this->piVars['solved'])) {
			if ($topicData['topic_poster'] == $feUserId || $this->getIsAdmin() || $this->getIsMod($topicData['forum_id'])) {
				$this->set_solved($topicId, $this->piVars['solved']);
				$this->piVars['pid'] = 'last';
			} else {
				$content .= '<script type="text/javascript">alert(\''.$this->pi_getLL('topic.noSolveRights').'\')</script>';
			}
			unset($this->piVars['solved']);
		}


		// redirect to a specific post
		if ($this->piVars['pid']) {
			$this->redirectToReply($topicId, $this->piVars['pid']);
		}

		// generate the marker for the admin panel
		if (!$conf['slimPostList']) {
			$adminPanel = $this->getAdminPanel($topicData);
		}
		else {
			$adminPanel = '';
		}

		// Output post listing START
		$template = $this->local_cObj->getSubpart($templateFile, empty($conf['LIST_POSTS_BEGIN']) ? '###LIST_POSTS_BEGIN###' : $conf['LIST_POSTS_BEGIN']);
		$marker = array(
			'###LABEL_AUTHOR###'  => $this->pi_getLL('post.author'),
			'###LABEL_MESSAGE###' => $this->pi_getLL('post.message'),
			'###ADMIN_PANEL###'   => $adminPanel,
		);
		if ($conf['slimPostList']) {
			$template = $this->local_cObj->substituteSubpart($template, '###HEADER_SUBPART###', '');
			$template = $this->local_cObj->substituteSubpart($template, '###ROOTLINE_CONTAINER###', '');
		}	

		// Log if topic has been read since last visit
		if ($feUserId) {
			$res = $this->databaseHandle->exec_SELECTquery(
				'COUNT(uid) AS read_flg',
				'tx_mmforum_postsread',
				'topic_id = ' . $topicId . ' AND user = ' . $feUserId . $this->getStoragePIDQuery()
			);
			list($isRead) = $this->databaseHandle->sql_fetch_row($res);
			if (!$isRead) {
				$insertArray = array(
					'pid'		=> $this->getStoragePID(),
					'topic_id'  => $topicId,
					'user'      => $feUserId,
					'tstamp'    => $GLOBALS['EXEC_TIME'],
					'crdate'    => $GLOBALS['EXEC_TIME'],
				);
				$this->databaseHandle->exec_INSERTquery('tx_mmforum_postsread', $insertArray);
			}
		}

		// Increase hit counter
		$this->databaseHandle->exec_UPDATEquery('tx_mmforum_topics', 'uid = ' . $topicId, array('topic_views' => 'topic_views+1'), 'topic_views');

		// Generate page navigation
		$limitCount = $conf['post_limit'];
		$marker['###PAGES###'] = $this->pagecount('tx_mmforum_posts', 'topic_id', $topicId, $limitCount);

		// Generate breadcrumb menu
		if ($conf['disableRootline']) {
			$template = $this->local_cObj->substituteSubpart($template, '###ROOTLINE_CONTAINER###', '');
		} else {
			$marker['###FORUMPATH###'] = $this->get_forum_path($topicData['forum_id'], $topicId);
		}

		$marker['###PAGETITLE###'] = $this->local_cObj->data['header'];
		$marker['###TOPICICON###'] = $this->getTopicIcon($topicId);

		// Retrieve topic data again
		$topicData = $this->getTopicData($topicId);
		$this->local_cObj->data = $topicData;

		// Determine page number
		if ($this->piVars['page']) {
			$pageNum = $this->piVars['page'];
			if ($conf['doNotUsePageBrowseExtension']) $pageNum ++;
		} elseif ($this->piVars['search_pid']) {
			$res = $this->databaseHandle->exec_SELECTquery(
				'uid',
				'tx_mmforum_posts',
				'deleted = 0 AND hidden = 0 AND topic_id = ' . $topicId . $this->getStoragePIDQuery(),
				'',
				'post_time ' . $orderingMode
			);
			$i = 0;
			$pageNum = 0;
			while ($row = $this->databaseHandle->sql_fetch_assoc($res)) {
				$i++;
				if ($row['uid'] == $this->piVars['search_pid']) {
					$pageNum = $i / $limitCount;
				}
			}
			$pageNum = intval($pageNum);
		} else {
			$pageNum = 0;
		}
		$forumpath_topic = $this->escape(stripslashes($topicData['topic_title']));
		$topic_is        = $topicData['topic_is'];
		$closed = $this->local_cObj->cObjGetSingle($conf['list_posts.']['closed'], $conf['list_posts.']['closed.']);
		$prefix = $this->local_cObj->cObjGetSingle($conf['list_posts.']['prefix'], $conf['list_posts.']['prefix.']);

		// Check if solved flag is set
		// TODO: add marker to template
		if ($topicData['solved'] == 1) {
			$imgInfo = array(
				'src'   => $conf['path_img'] . $conf['images.']['solved'],
				'alt'   => $this->pi_getLL('topic.isSolved'),
				'style' => 'vertical-align: middle;'
			);
			$solvedIcon = $this->buildImageTag($imgInfo);
		} else {
			$solvedIcon = '';
		}

		// Output topic name
		$marker['###TOPICNAME###']   = $closed . $forumpath_topic;
		$marker['###TOPICPREFIX###'] = strtolower($prefix);
		$marker['###PREFIX###']      = $prefix;

		/* Display the topic rating if the 'ratings' extension is installed
		 * and topic rating is enabled. */
		$isTopicRating = $this->isTopicRating();
		if ($isTopicRating)
			$marker['###TOPIC_RATING###'] = $isTopicRating ? $this->getRatingDisplay('tx_mmforum_topic', $topicData['uid']) : '';
		else 
			$template = $this->local_cObj->substituteSubpart($template, '###SUBP_TOPIC_RATING###', '');

		// Display poll
		if ($topicData['poll_id'] > 0 && $pageNum == 0 && $conf['polls.']['enable']) {
			$tx_mmforum_polls = GeneralUtility::makeInstance('tx_mmforum_polls');
			$marker['###POLL###'] = $tx_mmforum_polls->display($topicData['poll_id']);
		} else {
			$marker['###POLL###'] = '';
		}

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['listPosts_topic'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['listPosts_topic'] as $_classRef) {
				$_procObj = &GeneralUtility::getUserObj($_classRef);
				$marker   = $_procObj->listPosts_topic($marker, $topicData, $this);
			}
		}

		$content .= $this->local_cObj->substituteMarkerArray($template, $marker);

		// Determine last answering date to allow a user to edit his entry
		$res = $this->databaseHandle->exec_SELECTquery(
			'MAX(post_time)',
			'tx_mmforum_posts',
			'deleted = 0 AND hidden = 0 AND topic_id = ' . $topicId . $this->getStoragePIDQuery()
		);
		list ($lastpostdate) = $this->databaseHandle->sql_fetch_row($res);
		$topicData['_v_last_post_date'] = $lastpostdate;
		$DoNotSelectFirstPost = '';

		if (intval($this->firstPostID) > 0) {
			$DoNotSelectFirstPost = ' AND uid <> ' . intval($this->firstPostID);
		}

		$postList = $this->databaseHandle->exec_SELECTquery(
			'*',
			'tx_mmforum_posts',
			'deleted = 0 AND hidden = 0 AND topic_id = ' . $topicId .
			$this->getStoragePIDQuery() . $DoNotSelectFirstPost,
			'',
			'post_time ' . $orderingMode,
			$limitCount * ($pageNum) . ', ' . $limitCount
		);

		if (($this->databaseHandle->sql_num_rows($postList) == 0) && ($pageNum > 0)) {
			$linkParams[$this->prefixId] = array(
				'action' => 'list_post',
				'tid'    => $topicId,
				'page'   => $pageNum
			);
			$link = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams);
			HttpUtility::redirect($link);
		}

		$templateItem  = trim($this->local_cObj->getSubpart($templateFile, '###LIST_POSTS###'));
		$templateFirst = trim($this->local_cObj->getSubpart($templateFile, '###LIST_POSTS_FIRST###'));

		$i = 1;
		while ($row = $this->databaseHandle->sql_fetch_assoc($postList)) {
			$postMarker = $this->getPostListMarkers($row, $topicData, array('even' => ($i++%2)==0));
			$postMarker['###ADMIN_PANEL###'] = $adminPanel;
			if ($row['uid'] == $topicData['topic_first_post_id'] && $templateFirst) {
				$content .= $this->local_cObj->substituteMarkerArrayCached($templateFirst, $postMarker);
			} else {
				$content .= $this->local_cObj->substituteMarkerArrayCached($templateItem, $postMarker);
			}
		}

		// Output post listing END
		$templateOptions = $this->local_cObj->getSubpart($templateFile, '###LIST_POSTS_OPTIONEN###');
		$template        = $this->local_cObj->getSubpart($templateFile, empty($conf['LIST_POSTS_END']) ? '###LIST_POSTS_END###' : $conf['LIST_POSTS_END']);

		if ((!$topicData['read_flag'] && !$topicData['closed_flag']) || $this->getIsMod($topicData['forum_id']) || $this->getIsAdmin()) {
			if ($this->getMayWrite_topic($topicId)) {
				$linkParams[$this->prefixId] = array(
					'action' => 'new_post',
					'tid'    => $topicId
				);
				if ($this->useRealUrl()) {
					$linkParams[$this->prefixId]['fid'] = $topicData['forum_id'];
				}
				$marker['###POSTBOTTOM###'] = $this->createButton('reply', $linkParams);
			} else {
				$marker['###POSTBOTTOM###'] = '';
			}
		} else {
			$marker['###POSTBOTTOM###'] = $this->pi_getLL('topic.adminsOnly');
		}

		if ($feUserId) {
			$marker['###POSTMAILLINK###'] = $this->getSubscriptionButton($topicId, $topicData);
			$marker['###FAVORITELINK###'] = $this->getFavoriteButton($topicId, $topicData);
			$marker['###SOLVEDLINK###']   = $this->getSolvedButton($topicId, $topicData);

			if ($topicData['topic_poster'] == $feUserId || $this->getIsAdmin() || $this->getIsMod($topicData['forum_id'])) {
				$linkParams[$this->prefixId] = array(
					'action' => 'list_post',
					'tid'    => $topicId
				);

				$marker['###SOLVED_ACTION###']      = '';
				$marker['###LABEL_THISTOPICIS###']	= $this->pi_getLL('topic.thisTopicIs');
				$marker['###LABEL_NOTSOLVED###']	= $this->pi_getLL('topic.notSolved');
				$marker['###LABEL_SOLVED###']		= $this->pi_getLL('topic.solved');
				$marker['###LABEL_SAVE###']			= $this->pi_getLL('save');
				$marker['###SOLVED_FALSE###']		= (!$topicData['solved'] ? 'selected="selected"' : '');
				$marker['###SOLVED_TRUE###']		= ($topicData['solved']  ? 'selected="selected"' : '');
				$marker['###SOLVED_TOPICUID###']	= $topicId;
				$marker['###ACTION###']				= $this->piVars['action'];
				$marker['###FORMACTION###']         = $this->escapeURL($this->tools->getAbsoluteUrl($this->pi_getPageLink($GLOBALS['TSFE']->id)));
			} else {
				$template_option = $this->cObj->substituteSubpart($templateOptions, '###SOLVEDOPTION###', '');
			}
			$marker['###LABEL_OPTIONS###'] = $this->pi_getLL('options');
			$marker['###POST_OPTIONEN###'] = $this->cObj->substituteMarkerArray($templateOptions, $marker);
		} else {
			$marker['###POST_OPTIONEN###'] = '';
		}

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['listPosts_footer'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['listPosts_footer'] as $_classRef) {
				$_procObj = &GeneralUtility::getUserObj($_classRef);
				$marker   = $_procObj->listPosts_footer($marker, $topicData, $this);
			}
		}
		$content .= $this->local_cObj->substituteMarkerArray($template, $marker);
		return $content;
	}

	/**
	 * @param $topic_id
	 * @param $topic_data
	 * @return string
	 */
	function getSolvedButton($topic_id,$topic_data) {

		$imgInfo = array(
			'src'		=> $topic_data['solved']?($this->conf['path_img'].$this->conf['images.']['solved_on']):($this->conf['path_img'].$this->conf['images.']['solved_off']),
			'alt'		=> $topic_data['solved']?$this->pi_getLL('topic-solved-on'):$this->pi_getLL('topic-solved-off'),
			'title'		=> $topic_data['solved']?$this->pi_getLL('topic-solved-on'):$this->pi_getLL('topic-solved-off'),
		);

		if ($topic_data['topic_poster'] == $GLOBALS['TSFE']->fe_user->user['uid'] || $this->getIsModOrAdmin($topic_data['forum_id'])) {
			if ($topic_data['solved']) {
				$linkParams[$this->prefixId] = array(
					'unsolve'			=> $topic_id
				);
				$image = $this->pi_linkTP($this->buildImageTag($imgInfo),$linkParams);
				$link = $this->pi_linkTP($this->pi_getLL('topic-solvedshort-off'),$linkParams).' / <strong>'.$this->pi_getLL('topic-solvedshort-on').'</strong>';
			} else {
				$linkParams[$this->prefixId] = array(
					'solve'				=> $topic_id
				);
				$image = $this->pi_linkTP($this->buildImageTag($imgInfo),$linkParams);
				$link = '<strong>'.$this->pi_getLL('topic-solvedshort-off').'</strong> / '.$this->pi_linkTP($this->pi_getLL('topic-solvedshort-on'),$linkParams);
			}
		} else {
			$image = $this->buildImageTag($imgInfo);
			$link = $topic_data['solved'] ? $this->pi_getLL('topic-solvedshort-on') : $this->pi_getLL('topic-solvedshort-off');
		}

		$image = $this->cObj->stdWrap($image,$this->conf['list_posts.']['optImgWrap.']);
		$link  = $this->cObj->stdWrap($link,$this->conf['list_posts.']['optLinkWrap.']);

		$result = $this->cObj->stdWrap($image.$link,$this->conf['list_posts.']['optItemWrap.']);

		return $result;
	}

	/**
	 * @param $topic_id
	 * @param $topic_data
	 * @return string
	 */
	function getFavoriteButton($topic_id,$topic_data) {
		$res = $this->databaseHandle->exec_SELECTquery(
			"uid",
			"tx_mmforum_favorites",
			"user_id = ".$GLOBALS['TSFE']->fe_user->user['uid']." AND topic_id = ".$this->piVars['tid'].$this->getPidQuery()
		);

		if ($this->databaseHandle->sql_num_rows($res) < 1) {
			$imgInfo['alt'] = $this->pi_getLL('topic.favorite.off');
			$imgInfo['title'] = $this->pi_getLL('topic.favorite.off');
			$imgInfo['src'] = $this->conf['path_img'].$this->conf['images.']['favorite_off'];
			$favlinkParams[$this->prefixId] = array(
				'action'		=> 'set_favorite',
				'tid'			=> $this->piVars['tid']
			);
			if ($this->useRealUrl()) $favlinkParams[$this->prefixId]['fid'] = $topic_data['forum_id'];
			$image = $this->pi_linkTP($this->buildImageTag($imgInfo),$favlinkParams);
			$link = $this->pi_linkTP($this->pi_getLL('on'),$favlinkParams).' / <strong>'.$this->pi_getLL('off').'</strong>';
		} else {
			$imgInfo['alt'] = $this->pi_getLL('topic.favorite.on');
			$imgInfo['title'] = $this->pi_getLL('topic.favorite.on');
			$imgInfo['src'] = $this->conf['path_img'].$this->conf['images.']['favorite_on'];
			$favlinkParams[$this->prefixId] = array(
				'action'		=> 'del_favorite',
				'tid'			=> $this->piVars['tid']
			);
			if ($this->useRealUrl()) $favlinkParams[$this->prefixId]['fid'] = $topic_data['forum_id'];
			$image = $this->pi_linkTP($this->buildImageTag($imgInfo),$favlinkParams);
			$link = '<strong>'.$this->pi_getLL('on').'</strong> / '.$this->pi_linkTP($this->pi_getLL('off'),$favlinkParams);
		}

		// $image = $this->buildImageTag($imgInfo);
		$image = $this->cObj->stdWrap($image,$this->conf['list_posts.']['optImgWrap.']);
		$link  = $this->cObj->stdWrap($link,$this->conf['list_posts.']['optLinkWrap.']);

		$result = $this->cObj->stdWrap($image.$link,$this->conf['list_posts.']['optItemWrap.']);

		return $result;
	}

	function getSubscriptionButton($topic_id,$topic_data) {
		$res = $this->databaseHandle->exec_SELECTquery(
			"uid",
			"tx_mmforum_topicmail",
			"user_id = ".$GLOBALS['TSFE']->fe_user->user['uid']." AND topic_id = ".intval($this->piVars['tid']).$this->getPidQuery()
		);
		if ($this->databaseHandle->sql_num_rows($res) < 1) {
			$imgInfo['alt'] = $this->pi_getLL('topic.emailSubscr.off');
			$imgInfo['title'] = $this->pi_getLL('topic.emailSubscr.off');
			$imgInfo['src'] = $this->conf['path_img'].$this->conf['images.']['info_mail_off'];
			$linkParams[$this->prefixId] = array(
				'action'        => 'set_havealook',
				'tid'           => $this->piVars['tid']
			);
			if ($this->useRealUrl()) $linkParams[$this->prefixId]['fid'] = $topic_data['forum_id'];
			$image = $this->pi_linkTP($this->buildImageTag($imgInfo),$linkParams);
			$link = $this->pi_linkTP($this->pi_getLL('on'),$linkParams).' / <strong>'.$this->pi_getLL('off').'</strong>';
		} else {
			$imgInfo['alt'] = $this->pi_getLL('topic.emailSubscr.on');
			$imgInfo['title'] = $this->pi_getLL('topic.emailSubscr.on');
			$imgInfo['src'] = $this->conf['path_img'].$this->conf['images.']['info_mail_on'];
			$linkParams[$this->prefixId] = array(
				'action'        => 'del_havealook',
				'tid'           => $this->piVars['tid']
			);
			if ($this->useRealUrl()) $linkParams[$this->prefixId]['fid'] = $topic_data['forum_id'];
			$image = $this->pi_linkTP($this->buildImageTag($imgInfo),$linkParams);
			$link = '<strong>'.$this->pi_getLL('on').'</strong> / '.$this->pi_linkTP($this->pi_getLL('off'),$linkParams);
		}

		// $image = $this->buildImageTag($imgInfo);
		$image = $this->cObj->stdWrap($image,$this->conf['list_posts.']['optImgWrap.']);
		$link  = $this->cObj->stdWrap($link,$this->conf['list_posts.']['optLinkWrap.']);

		$result = $this->cObj->stdWrap($image.$link,$this->conf['list_posts.']['optItemWrap.']);

		return $result;
	}

	/**
	 * Generates markers for a post.
	 * This function generates markers for the post listing view.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-07-26
	 * @param   string $row The post record
	 * @param   string $topic The record of the topic the post is located in.
	 * @param array $extra
	 * @return array The marker array.
	 */
	function getPostListMarkers($row, $topic, $extra = array()) {
		list($userData) = $this->databaseHandle->exec_SELECTgetRows('*', 'fe_users', 'uid = ' . $row['poster_id']);
		$mAp = $this->marker_getPostmenuMarker($row, $topic);

		$userSignature = $this->marker_getUserSignature($userData);
		$marker = array(
			'###LABEL_AUTHOR###'	=> $extra['###LABEL_AUTHOR###'],
			'###LABEL_MESSAGE###'	=> $extra['###LABEL_MESSAGE###'],
			'###ATTACHMENTS###'		=> $this->marker_getAttachmentMarker($row,$topic),
			'###POSTOPTIONS###'		=> $this->marker_getPostoptionsMarker($row,$topic),
			'###POSTMENU###'		=> implode('', $mAp),
			'###PROFILEMENU###'		=> $mAp['profilebuttons'],
			'###MESSAGEMENU###'		=> $mAp['msgbuttons'],
			'###POSTUSER###'		=> $this->ident_user($row['poster_id'], $this->conf, ($topic['topic_replies'] > 0 ? $topic['topic_poster'] : false)),
			'###POSTTEXT###'		=> $this->marker_getPosttextMarker($row, $topic) . ($this->conf['list_posts.']['appendSignatureToPostText'] ? $userSignature : ''),
			'###ANKER###'			=> '<a name="pid' . $row['uid'] . '"></a>',	// deprecated, use "POSTANCHOR"
			'###POSTANCHOR###'		=> '<a name="pid' . $row['uid'] . '"></a>',
			'###POSTDATE###'		=> $this->pi_getLL('post.writtenOn').': '.$this->formatDate($row['post_time']),
			'###USERSIGNATURE###'	=> $userSignature,
			'###EVEN_ODD###'		=> $extra['even'] ? 'even' : 'odd',
			'###POSTRATING###'		=> $this->getRatingDisplay('tx_mmforum_posts', $row['uid'])
		);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['postListMarkerArray'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['postListMarkerArray'] as $_classRef) {
				$_procObj = &GeneralUtility::getUserObj($_classRef);
				$marker = $_procObj->processPostListMarkerArray($marker, $row, $topic, $this);
			}
		}
		return $marker;
	}

	/**
	 * generates a string that displays the posttext of the
	 *
	 * @param $row
	 * @param $topic
	 * @return string the string ready to output (only if there is a signature of course)
	 */
	function marker_getPosttextMarker($row, $topic) {
		$res = $this->databaseHandle->exec_SELECTquery(
			'uid, post_text, tstamp, cache_tstamp, cache_text',
			'tx_mmforum_posts_text',
			'deleted="0" AND post_id= ' . intval($row['uid'])
		);
		list($text_uid, $posttext, $tstamp, $cache_tstamp, $cache_text) = $this->databaseHandle->sql_fetch_row($res);
		$postold = $posttext;

		if ($tstamp > $cache_tstamp || $cache_tstamp == 0) {
			$posttext = $this->tx_mmforum_postparser->main($this, $this->conf, $posttext, 'textparser');
			$updateArray = array(
				'cache_tstamp' => $GLOBALS['EXEC_TIME'],
				'cache_text'   => $posttext
			);
			$this->databaseHandle->exec_UPDATEquery('tx_mmforum_posts_text','uid=' . $text_uid, $updateArray);
		} else {
			$posttext = $cache_text;
		}
		$posttext = $this->highlight_text($posttext, $this->piVars['sword']);
		if ($row['edit_count'] > 0) {
			$editMarker = array(
				'###COUNT###' => intval($row['edit_count']),
				'###DATE###'  => date(' d.m.Y ', $row['edit_time']),
				'###TIME###'  => date('H:i', $row['edit_time'])
			);

			$oldData = $this->cObj->data;
			$this->cObj->data = $editMarker;
			$posttext .= $this->cObj->stdWrap($this->cObj->substituteMarkerArray($this->pi_getLL('post.edited'), $editMarker), $this->conf['list_posts.']['postEdited_stdWrap.']);
			$this->cObj->data = $oldData;

		}
		return $posttext;
	}

	/**
	 * generates a string that fits perfectly to render the signature's user in a marker tempalte
	 * @param	array	$userData the user's data array
	 * @return	string	the string ready to output (only if there is a signature of course)
	 */
	function marker_getUserSignature($userData) {
		$signature = '';
		if ($userData['tx_mmforum_user_sig']) {
			if ($this->conf['signatureBBCodes']) {
				$signature = $this->tx_mmforum_postparser->main($this, $this->conf, $userData['tx_mmforum_user_sig'], 'textparser');
			} else {
				$signature = $this->escape($userData['tx_mmforum_user_sig']);
				$signature = nl2br($signature);
			}

			if (intval($this->conf['signatureLimit']) > 0) {
				$sigLines = explode("\n", $signature);
				if (count($sigLines) > $this->conf['signatureLimit']) {
					$sigLines = array_slice($sigLines, 0, $this->conf['signatureLimit']);
				}
				$signature = implode("\n", $sigLines);
			}
			$signature = $this->cObj->stdWrap($signature, $this->conf['list_posts.']['signature_stdWrap.']);
		}
		return $signature;
	}

	/**
	 * @param $row
	 * @param $topic
	 * @return array
	 */
	function marker_getPostmenuMarker($row,$topic) {

		$read_flag          =  $topic['read_flag'];
		$closed_flag        =  $topic['closed_flag'];

		$poster = $row['poster_id'];
		$user_res = $this->databaseHandle->exec_SELECTquery('*','fe_users',"uid='$poster'");
		if ($this->databaseHandle->sql_num_rows($user_res))
			$user = $this->databaseHandle->sql_fetch_assoc($user_res);
		else $user = false;

		$menu = $profile = '';
		if ($this->getMayWrite_topic($this->piVars['tid'])) {
			if ((($read_flag == 0) AND ($closed_flag == 0)) OR $this->getIsAdmin() OR $this->getIsMod($topic['forum_id'])) {
				$quoteParams[$this->prefixId] = array(
					'action'        => 'new_post',
					'tid'           => $this->piVars['tid'],
					'quote'         => $row['uid']
				);
				if ($this->useRealUrl()) {
					$quoteParams[$this->prefixId]['fid'] = $row['forum_id'];
					$quoteParams[$this->prefixId]['pid'] = $this->pi_getLL('realurl.quote');
				}
				$menu .= $this->createButton('quote',$quoteParams,0,true);
			}
		}

		if ($user && $user['deleted']=='0') {
			$profile .= $this->createButton('profile','profileView:'.$user['uid'],0,true);
			$profile .= $this->getUserButtons($user);
		}

		if ($GLOBALS['TSFE']->fe_user->user['username'] && $user['uid']!=$GLOBALS['TSFE']->fe_user->user['uid'] && !(isset($this->conf['pm_enabled']) && intval($this->conf['pm_enabled']) === 0)){
			if (intval($this->conf['pm_id']) > 0 && $user && $user['deleted']=='0' && !((isset($conf['pm_enabled']) && intval($conf['pm_enabled']) === 0))) {
				$pmParams = array(
					'tx_mmforum_pi3[action]'        => 'message_write',
					'tx_mmforum_pi3[userid]'        => $user['uid']
				);
				if ($this->useRealUrl()) {
					$pmParams['tx_mmforum_pi3']['folder'] = 'inbox';
					$pmParams['tx_mmforum_pi3']['messid'] = $this->pi_getLL('realurl.pmnew');
				}
				$profile .= $this->createButton('pm',$pmParams,$this->conf['pm_id'],true);
			}

			$alertParams[$this->prefixId] = array(
				'action'        => 'post_alert',
				'pid'     		=> $row['uid'],
			);
			if ($this->useRealUrl()) {
				$alertParams[$this->prefixId]['tid'] = $this->piVars['tid'];
				$alertParams[$this->prefixId]['fid'] = $row['forum_id'];
			}
			$menu .= $this->createButton('post-alert',$alertParams,0,true);
		}
		return array('msgbuttons'=>$menu, 'profilebuttons'=>$profile);
	}

	/**
	 * @param $row
	 * @param $topic
	 * @return string
	 */
	function marker_getPostoptionsMarker($row,$topic) {
		$lastpostdate = $topic['_v_last_post_date'];

		if ((($row['poster_id'] == $this->getUserID()) AND ($lastpostdate == $row['post_time']) AND $topic['closed_flag']!=1) 
		    OR $this->getIsAdmin() OR $this->getIsMod($topic['forum_id'])) {

			$linkParams[$this->prefixId] = array(
				'action'        => 'post_edit',
				'pid'           => $row['uid'],
				'token'         => $GLOBALS["TSFE"]->fe_user->getKey('ses', "token"),
			);
			if ($this->useRealUrl()) {
				$linkParams[$this->prefixId]['fid'] = $row['forum_id'];
				$linkParams[$this->prefixId]['tid'] = $row['topic_id'];
			}
			$editLink = $this->createButton('edit',$linkParams,0,true);

			$linkParams[$this->prefixId] = array(
				'action'        => 'post_del',
				'pid'           => $row['uid'],
				'token'         => $GLOBALS["TSFE"]->fe_user->getKey('ses', "token"),
			);
			if ($this->useRealUrl()) {
				$linkParams[$this->prefixId]['fid'] = $row['forum_id'];
				$linkParams[$this->prefixId]['tid'] = $row['topic_id'];
			}
			$delLink = $this->createButton('delete',$linkParams,0,true);

			return $editLink.$delLink;
		} else {
			return '';
		}
	}

	/**
	 * Fills the attachment part with the necessary values
	 *
	 * @param $row
	 * @param $topic
	 * @return string
	 */
	function marker_getAttachmentMarker($row, $topic) {
		$templateFile = $this->cObj->fileResource($this->conf['template.']['list_post']);
		$template = $this->cObj->getSubpart($templateFile, '###LIST_ATTACHMENTS###');
		// to keep backwards compatibility (in case this marker is not in the template file right now)
		// we add this here, however it will be deleted at some point, so please add the new
		// ###LIST_ATTACHMENTS### marker to your template
		if (strlen(trim($template)) == 0) {
			$template = '###IMAGE_PREVIEW### <!-- ###DOWNLOADLINK### -->###FILENAME###<!-- ###DOWNLOADLINK### --> (###LABEL_FILETYPE###: ###FILETYPE###, ###LABEL_FILESIZE###: ###FILESIZE###) &mdash; ###NUMDOWNLOADS### ###LABEL_NUMDOWNLOADS###';
		}

		$attachments = '';
		if ($row['attachment'] != 0) {
			$attachments = $this->cObj->stdWrap($this->pi_getLL('attachments.title'), $this->conf['attachments.']['attachmentLabel_stdWrap.']);
			$res = $this->databaseHandle->exec_SELECTquery(
				'*',
				'tx_mmforum_attachments',
				'post_id = '.$row['uid'].' AND deleted=0',
				'',
				'uid ASC'
			);
			$sAttachment = '';
			while ($attachment = $this->databaseHandle->sql_fetch_assoc($res)) {
				if (!@file_exists($attachment['file_path'])) {
					continue;
				}
				$linkParams[$this->prefixId] = array(
					'action'     => 'get_attachment',
					'attachment' => $attachment['uid']
				);
				if ($this->useRealUrl()) {
					unset($linkParams[$this->prefixId]['attachment']);
					$linkParams[$this->prefixId]['fid'] = $this->pi_getLL('realurl.attachment') . $attachment['uid'];
				}

				$size = $attachment['file_size'] . ' ' . $this->pi_getLL('attachment.bytes');
				if ($attachment['file_size'] > 1024) {
					$size = round($attachment['file_size'] / 1024, 2) . ' ' . $this->pi_getLL('attachment.kilobytes');
				}
				if ($attachment['file_size'] > 1048576) {
					$size = round($attachment['file_size'] / 1048576,2) . ' ' . $this->pi_getLL('attachment.megabytes');
				}

				$aLink = $this->pi_linkTP('|', $linkParams);
				$aPreview = '';
				if ($this->conf['attachments.']['imagePreview'] == '1') {
					$imgConf = $this->conf['attachments.']['imagePreviewObj.'];
					$imgConf['file'] = $attachment['file_path'];

					$aPreview = $this->cObj->cObjGetSingle($this->conf['attachments.']['imagePreviewObj'], $imgConf);
					$aPreview = $this->pi_linkTP($aPreview, $linkParams);
				}
				$markers = array(
					'###IMAGE_PREVIEW###'      => $aPreview,
					'###FILENAME###'           => $attachment['file_name'],
					'###FILETYPE###'           => $attachment['file_type'],
					'###FILESIZE###'           => $size,
					'###NUMDOWNLOADS###'       => $attachment['downloads'],
					'###LABEL_FILETYPE###'     => $this->pi_getLL('attachment.type'),
					'###LABEL_FILESIZE###'     => $this->pi_getLL('attachment.size'),
					'###LABEL_NUMDOWNLOADS###' => $this->pi_getLL('attachment.downloads')
				);
				$wrappedSubparts = array(
					'###DOWNLOADLINK###'  => explode('|', $aLink)
				);

				// Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['postAttachmentListMarkerArray'])) {
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['postAttachmentListMarkerArray'] as $_classRef) {
						$_procObj = &GeneralUtility::getUserObj($_classRef);
						$markers = $_procObj->processPostAttachmentListMarkerArray($markers, $wrappedSubparts, $row, $topic, $attachment, $this);
					}
				}
				$aString      = $this->cObj->substituteMarkerArrayCached($template, $markers, array(), $wrappedSubparts);
				$sAttachment .= $this->cObj->stdWrap($aString, $this->conf['attachments.']['attachmentLink_stdWrap.']);
			}
			$attachments .= $this->cObj->stdWrap($sAttachment, $this->conf['attachments.']['attachment_stdWrap.']);
		}
		return $attachments;
	}

	/**
	 * Updates the number of Posts in a topic topics in the forum table
	 *
	 * @param	int		$forumId    ID of the forum to update
	 * @return	void
	 */
	function update_forum_posts_n_topics($forumId) {
		$forumId = intval($forumId);
		$res = $this->databaseHandle->exec_SELECTquery(
			'uid',
			'tx_mmforum_posts',
			'deleted = 0 AND hidden = 0 AND forum_id = ' . $forumId,
			'',
			'post_time DESC'
		);
		$countPosts       = $this->databaseHandle->sql_num_rows($res);
		list($lastPostId) = $this->databaseHandle->sql_fetch_row($res);

		$res = $this->databaseHandle->exec_SELECTquery(
			'uid',
			'tx_mmforum_topics',
			'deleted = 0 AND hidden = 0 AND shadow_tid = 0 AND forum_id = ' . $forumId
		);
		$countTopics = $this->databaseHandle->sql_num_rows($res);

		$updateArray = array(
			'forum_posts'        => $countPosts,
			'forum_topics'       => $countTopics,
			'forum_last_post_id' => $lastPostId,
			'tstamp'             => $GLOBALS['EXEC_TIME']
		);
		$this->databaseHandle->exec_UPDATEquery('tx_mmforum_forums', 'uid = ' . $forumId, $updateArray);
	}

	/**
	 * updates the number of posts of a user
	 *
	 * @param	int $userId the user ID of which posts should be updated
	 * @return	void
	 */
	function update_user_posts($userId) {
		$userId = intval($userId);
		$res = $this->databaseHandle->exec_SELECTquery(
			'COUNT(*)',
			'tx_mmforum_posts',
			'deleted = 0 AND hidden = 0 AND poster_id = ' . $userId
		);
		list($posts) = $this->databaseHandle->sql_fetch_row($res);
		$updateArray = array('tx_mmforum_posts' => $posts);
		$this->databaseHandle->exec_UPDATEquery('fe_users', 'uid = ' . $userId, $updateArray);
	}

	/**
	 * updates the number of attachments for a post
	 *
	 * @param	int $postId the post ID of which attachments should be updated
	 * @return	void
	 */
	function update_post_attachment($postId) {
		$postId = intval($postId);
		$res = $this->databaseHandle->exec_SELECTquery(
			'attachment',
			'tx_mmforum_posts',
			'uid = ' . $postId
		);
		if ($this->databaseHandle->sql_num_rows($res) > 0) {
			list($attachmentId) = $this->databaseHandle->sql_fetch_row($res);
			$this->databaseHandle->exec_UPDATEquery(
				'tx_mmforum_attachments',
				'uid = ' . $attachmentId,
				array('post_id' => $postId)
			);
		}
	}

	/**
	 * Deletes a post. If the deleted post was the last one in a topic, the regarding topic
	 * is deleted, too.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The content
	 */
	function post_del($content, $conf) {
		$postId = $this->piVars['pid'];
		$postId = intval($postId);            // Parse to int for security reasons
		$postlist = $this->databaseHandle->exec_SELECTquery(
			'*',
			'tx_mmforum_posts',
			'deleted = 0 AND hidden = 0 AND uid = "'.$postId.'"'.$this->getPidQuery()
		);
		$row = $this->databaseHandle->sql_fetch_assoc($postlist);

		$topic_id = $row['topic_id'];

		$res = $this->databaseHandle->exec_SELECTquery('MAX(post_time)','tx_mmforum_posts','deleted="0" AND hidden="0" AND topic_id="'.$row['topic_id'].'"'.$this->getPidQuery());
		list ($lastpostdate) = $this->databaseHandle->sql_fetch_row($res);
		$grouprights = explode(",",$GLOBALS['TSFE']->fe_user->user['usergroup']);
		if (((($row['poster_id'] == $GLOBALS['TSFE']->fe_user->user['uid']) AND ($lastpostdate == $row['post_time'])) OR $this->getIsAdmin() OR $this->getIsMod($row['forum_id'])) && $GLOBALS["TSFE"]->fe_user->getKey('ses', "token") == $this->piVars['token'] && $this->piVars['token'] != false) {
			// Retrieve post data
			$res        = $this->databaseHandle->exec_SELECTquery('*','tx_mmforum_posts',"uid = '".intval($this->piVars['pid'])."'".$this->getPidQuery());
			$row        = $this->databaseHandle->sql_fetch_assoc($res);
			$topic_id    = $row['topic_id'];
			$forum_id    = $row['forum_id'];
			$cr_user    = $row['poster_id'];

			// Mark post as deleted
			$updArray = array(
				'deleted' => 1,
				'tx_mmforumsearch_index_write' => 0,
			);
			$this->databaseHandle->exec_UPDATEquery('tx_mmforum_posts','uid = "'.intval($this->piVars['pid']).'"',$updArray);
			$updArray = array("deleted"=>1);
			$this->databaseHandle->exec_UPDATEquery('tx_mmforum_posts_text','post_id = "'.intval($this->piVars['pid']).'"',$updArray);

			// Delete file attachment
			if ($row['attachment'] > 0)
				$this->databaseHandle->exec_UPDATEquery('tx_mmforum_attachments','post_id='.$row['uid'],$updArray);

			// Decrease user's post coutner
			$this->databaseHandle->exec_UPDATEquery('fe_users', 'uid = '.intval($cr_user), array('tx_mmforum_posts' => 'tx_mmforum_posts - 1'));

			// Get last active post in topic
			$res = $this->databaseHandle->exec_SELECTquery('uid','tx_mmforum_posts','deleted="0" AND hidden="0" AND topic_id="'.$topic_id.'"'.$this->getPidQuery(),'','post_time ASC');
			list($lastpostid) = $this->databaseHandle->sql_fetch_row($res);

			// Decrease topic reply counter
			$this->databaseHandle->exec_UPDATEquery('tx_mmforum_topics', 'WHERE uid = '.$topic_id, array(
				'tx_mmforumsearch_index_write' => 0,
				'topic_replies' => 'topic_replies-1',
				'topic_last_post_id' => $lastpostid
			));

			// Refresh last post in board view

			// Get last active post in topic
			$res = $this->databaseHandle->exec_SELECTquery('uid','tx_mmforum_posts','deleted="0" AND hidden="0" AND topic_id="'.$row['topic_id'].'"'.$this->getPidQuery(),'','post_time DESC');
			list($lastpostid) = $this->databaseHandle->sql_fetch_row($res);

			// Get last active post in board
			$res = $this->databaseHandle->exec_SELECTquery('uid','tx_mmforum_posts','deleted="0" AND hidden="0" AND forum_id="'.$forum_id.'"'.$this->getPidQuery(),'','post_time DESC','1');
			$row = $this->databaseHandle->sql_fetch_assoc($res);
			$last_forum_post_id = $row['uid'];

			// Decrease board post counter.
			$this->databaseHandle->exec_UPDATEquery('tx_mmforum_forums', 'uid = '.intval($forum_id), array('forum_posts' => 'forum_posts-1'));

			// Determine, if deleted post was last remaining post in topic. If so, topic is deleted, too
			$postmenge = $this->databaseHandle->sql_num_rows($this->databaseHandle->exec_SELECTquery('poster_id,topic_id,post_time','tx_mmforum_posts',"deleted = 0 AND hidden = 0 AND topic_id = '$topic_id'".$this->getPidQuery()));
			if ($postmenge == 0) {
				// Mark topic as deleted
				$updArray = array("deleted"=>1);
				$this->databaseHandle->exec_UPDATEquery('tx_mmforum_topics',"uid='$topic_id'",$updArray);

				// Delete poll
				$pollObj = GeneralUtility::makeInstance('tx_mmforum_polls');
				$pollObj->deletePoll(0,$topic_id);

				// Determine last active post in board
				$res = $this->databaseHandle->exec_SELECTquery('uid','tx_mmforum_posts',"deleted = 0 AND hidden = 0 AND forum_id = '$forum_id'".$this->getPidQuery(),'','post_time DESC','1');
				$row = $this->databaseHandle->sql_fetch_assoc($res);
				$last_forum_post_id = $row['uid'];

				// Decrease board topic counter
				$this->databaseHandle->exec_UPDATEquery('tx_mmforum_forums', 'uid = '.intval($forum_id), array('forum_topics' => 'forum_topics-1'));

				// Remove shadow topics pointing to this topic
				$updateArray = array(
					'tstamp'			=> $GLOBALS['EXEC_TIME'],
					'deleted'			=> 1
				);
				$this->databaseHandle->exec_UPDATEquery('tx_mmforum_topics',"shadow_tid='$topic_id'",$updateArray);

				$this->update_lastpost_forum($forum_id);
				$this->update_lastpost_topic($topic_id);

				$linkParams[$this->prefixId] = array(
					'action'    => 'list_topic',
					'fid'       => $forum_id
				);
				$link = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams);
				HttpUtility::redirect($link);
			} else {
				$this->update_lastpost_forum($forum_id);
				$this->update_lastpost_topic($topic_id);

				$linkParams[$this->prefixId] = array(
					'action'    => 'list_post',
					'tid'       => $topic_id,
					'pid'       => 'last'
				);
				$link = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams);
				HttpUtility::redirect($link);
			}
		} else {
			$template = $this->cObj->fileResource($conf['template.']['error']);
			$marker = array();
			$marker['###ERROR###'] = $this->pi_getLL('deletePost.noAccess');
		}

		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		return $content;
	}

	/**
	 * if an admin uses the "admin panel" options, this functions makes sure
	 * that all the values are properly saved
	 *
	 * @param	array	$topicData	the data of the topic
	 * @return	void
	 */
	function saveAdminChanges($topicData) {
		$topicId = intval($this->piVars['tid']);
		if ($this->piVars['saveAdmin'] == 1 && ($this->getIsAdmin() || $this->getIsMod($topicData['forum_id']))) {

			// move topic to another forum
			$changeForumId = intval($this->piVars['change_forum_id']);
			$oldForumId    = $topicData['forum_id'];
			if ($changeForumId > 0 && $changeForumId != $oldForumId) {
				// Generate shadow record
				if ($this->conf['enableShadows']) {
					$shadow_insertArray = array(
						'pid'                 => $topicData['pid'],
						'tstamp'              => $GLOBALS['EXEC_TIME'],
						'crdate'              => $GLOBALS['EXEC_TIME'],
						'topic_title'         => $topicData['topic_title'],
						'topic_poster'        => $topicData['topic_poster'],
						'topic_time'          => $topicData['topic_time'],
						'topic_views'         => $topicData['topic_views'],
						'topic_replies'       => $topicData['topic_replies'],
						'topic_last_post_id'  => $topicData['topic_last_post_id'],
						'forum_id'            => $topicData['forum_id'],
						'topic_first_post_id' => $topicData['topic_first_post_id'],
						'shadow_tid'          => $topicData['uid'],
						'shadow_fid'          => $changeForumId
					);
					$this->databaseHandle->exec_INSERTquery('tx_mmforum_topics', $shadow_insertArray);
				}

				// Update new board UID
				$updateArray = array('forum_id' => $changeForumId);
				$this->databaseHandle->exec_UPDATEquery('tx_mmforum_topics', 'uid = ' . $topicId, $updateArray);
				$this->databaseHandle->exec_UPDATEquery('tx_mmforum_posts',  'topic_id = ' . $topicId, $updateArray);

				// update the posts in the old and the new forum
				$this->update_lastpost_forum($changeForumId);
				$this->update_lastpost_forum($oldForumId);
				$this->update_lastpost_topic($topicId);
				$this->update_forum_posts_n_topics($oldForumId);
				$this->update_forum_posts_n_topics($changeForumId);

				// Clearance for new indexing
				tx_mmforum_indexing::delete_topic_ind_date($topicId);
			}

			$atTopFlag   = ($this->piVars['at_top']      ? 1 : 0);
			$readFlag    = ($this->piVars['read_flag']   ? 1 : 0);
			$closedFlag  = ($this->piVars['closed_flag'] ? 1 : 0);
			$deleteFlag  = ($this->piVars['delete_flag'] ? 1 : 0);
			$threadTitle =  $this->piVars['threadtitel'];

			// update all other values
			$updateArray = array(
				'at_top_flag' => $atTopFlag,
				'read_flag'   => $readFlag,
				'closed_flag' => $closedFlag,
				'deleted'     => $deleteFlag,
				'topic_title' => $threadTitle,
				'tx_mmforumsearch_index_write' => 0,
			);

			if ($this->piVars['prefix_selected']) {
				$updateArray['topic_is'] = $this->piVars['prefix_selected'];
			}

			$this->databaseHandle->exec_UPDATEquery('tx_mmforum_topics', 'uid = ' . $topicId, $updateArray);

			if ($this->conf['enableShadows'] == true) {
				$this->databaseHandle->exec_UPDATEquery('tx_mmforum_topics', 'shadow_tid = ' . $topicId, $updateArray);
			}

			if ($deleteFlag) {
				$res = $this->databaseHandle->exec_SELECTquery(
					'*',
					'tx_mmforum_topics',
					'uid = ' . $topicId . $this->getStoragePIDQuery()
				);
				$topicData = $this->databaseHandle->sql_fetch_assoc($res);

				// delete all posts
				$updateArray = array(
					'deleted'                      => 1,
					'tstamp'                       => $GLOBALS['EXEC_TIME'],
					'tx_mmforumsearch_index_write' => 0,
				);
				$this->databaseHandle->exec_UPDATEquery('tx_mmforum_posts', 'topic_id = ' . $topicData['uid'], $updateArray);

				//mark all posts_text as deleted
				$this->databaseHandle->exec_UPDATEquery(
					'tx_mmforum_posts_text',
					'post_id IN (SELECT uid FROM tx_mmforum_posts WHERE topic_id = ' .
					$topicData['uid'] . ')',
					array('deleted' => 1)
				);

				// get all posters of this thread, and update their posts
				$uRes = $this->databaseHandle->exec_SELECTquery(
					'poster_id',
					'tx_mmforum_posts',
					'topic_id = ' . $topicId . ' AND deleted = 0 AND hidden = 0' . $this->getStoragePIDQuery(),
					'poster_id'
				);
				while ($row = $this->databaseHandle->sql_fetch_assoc($uRes)) {
					$this->update_user_posts($row['poster_id']);
				}
				$this->update_forum_posts_n_topics($topicData['forum_id']);

				$linkParams[$this->prefixId] = array(
					'action' => 'list_topic',
					'fid'    => $topicData['forum_id']
				);
				$link = $this->pi_getPageLink($this->getForumPID(), '', $linkParams);
				HttpUtility::redirect($link);
			}

			unset($this->piVars['saveAdmin']);
			unset($this->piVars['change_forum_id']);
			unset($this->piVars['at_top']);
			unset($this->piVars['read_flag']);
			unset($this->piVars['closed_flag']);
			unset($this->piVars['delete_flag']);
			unset($this->piVars['threadtitel']);
			unset($this->piVars['prefix_user']);
			unset($this->piVars['prefix_selected']);
		}
	}

	/**
	 * generates the HTML for the Administration Panel
	 *
	 * @param	array	$topicData	the data of the topic
	 * @return	string	the HTML as a string
	 */
	function getAdminPanel($topicData) {
		$content = '';
		$topicId = intval($topicData['uid']);
		if ($this->getIsMod($topicData['forum_id']) || $this->getIsAdmin()) {
			$templateFile = $this->cObj->fileResource($this->conf['template.']['list_post']);
			$template = $this->cObj->getSubpart($templateFile, '###ADMIN_PANEL###');

			// Language dependent markers
			$marker = array(
				'###LABEL_ADMINOPTIONS###' => $this->pi_getLL('topic.admin.adminoptions'),
				'###LABEL_EXPAND###'       => $this->pi_getLL('topic.admin.expand'),
				'###LABEL_COLLAPSE###'     => $this->pi_getLL('topic.admin.collapse'),
				'###LABEL_TOPICTITLE###'   => $this->pi_getLL('topic.admin.topictitle'),
				'###LABEL_TOPICPREFIX###'  => $this->pi_getLL('topic.admin.topicprefix'),
				'###LABEL_FIRST###'        => $this->pi_getLL('topic.admin.first'),
				'###LABEL_CLOSED###'       => $this->pi_getLL('topic.admin.closed'),
				'###LABEL_DELETE###'       => $this->pi_getLL('topic.admin.delete'),
				'###LABEL_MOVE###'         => $this->pi_getLL('topic.admin.move'),
				'###LABEL_SAVE###'         => $this->pi_getLL('topic.admin.save'),
				'###LABEL_ADMINSONLY###'   => $this->pi_getLL('topic.adminsOnly'),
				'###IMG_EXPAND###'         => $this->conf['path_img'] . $this->conf['images.']['plus'],
				'###IMG_COLLAPSE###'       => $this->conf['path_img'] . $this->conf['images.']['minus'],
			);

			// Create action link for form
			$marker['###ACTIONLINK###'] = $this->escapeURL($this->tools->getAbsoluteUrl($this->pi_linkTP_keepPIvars_url()));

			// Generate prefix list
			$prefixes = GeneralUtility::trimExplode(',', $this->conf['prefixes']);
			$marker['###PREFIXES###'] .= '<option value="0"></option>';

			foreach ($prefixes as $prefix) {
				$selected = ($topicData['topic_is'] == $prefix ? ' selected="selected"' : '');
				$marker['###PREFIXES###'] .= '<option value="' . $prefix . '"' . $selected . '>' . $prefix . '</option>';
			}
			$marker['###TOPICPREFIX###'] = ($topicData['topic_is'] != 0 ? $topicData['topic_is'] : '');

			// Set "at top", "admins only" and "closed" flags
			$marker['###AT_TOP###']      = ($topicData['at_top_flag'] == 1 ? ' checked="checked"' : '');
			$marker['###READ_FLAG###']   = ($topicData['read_flag']   == 1 ? ' checked="checked"' : '');
			$marker['###CLOSED_FLAG###'] = ($topicData['closed_flag'] == 1 ? ' checked="checked"' : '');;
			$marker['###DELETE_FLAG###'] = '';
			$marker['###TOPICTITLE###'] = $marker['###TOPICTITEL###'] = $this->escape(stripslashes($topicData['topic_title']));

			// Generate "move topic" select box
			$marker['###FORUM_BOX###'] = $this->get_forumbox($topicId);

			$marker['###OPTIONS###']  .= '<input type="hidden"  name="' . $this->prefixId . '[topic_id]" value="' . $topicId . '" />';
			$content = $this->cObj->substituteMarkerArray($template, $marker);
		}
		return $content;
	}

	/**
	 * Returns a select box with a tree view of all categories and boards. The
	 * board of the topic specified in $topic_id is selected.
	 * @param  int    $topic_id The board containing the topic specified by this UID is
	 *                          selected.
	 * @return string           The HTML select box with all categories and boards.
	 */
	function get_forumbox($topic_id) {
		$forum_id = $this->get_forum_id($topic_id);

		$content = '<select class="tx-mmforum-select" name="'.$this->prefixId.'[change_forum_id]" size="12">';

		// Load categories
		$res = $this->databaseHandle->exec_SELECTquery(
			'*',
			'tx_mmforum_forums',
			'deleted="0"
			AND hidden="0" AND
			parentID="0" '.
			$this->getStoragePIDQuery().
			$this->getCategoryLimit_query().
			$this->getMayWrite_forum_query(),
			'',
			'sorting ASC'
		);
		while ($row = $this->databaseHandle->sql_fetch_assoc($res)) {
			$content .= '<optgroup label="'.$this->escape($row['forum_name']).'">';

			// Load boards
			$res2 = $this->databaseHandle->exec_SELECTquery(
				'*',
				'tx_mmforum_forums',
				'deleted="0" AND
				hidden="0" AND
				parentID="'.$row['uid'].'" '.
				$this->getStoragePIDQuery().
				$this->getMayWrite_forum_query(),
				'',
				'sorting ASC'
			);
			while ($row2 = $this->databaseHandle->sql_fetch_assoc($res2)) {
				if ($row2['uid'] == $forum_id) {
					$select = 'selected="selected"';
				} else {
					$select = '';
				}

				$content.= '<option value="'.$this->escape($row2['uid']).'" '.$select.'>'.$this->escape($row2['forum_name']).'</option>';
			}
			$content .= '</optgroup>';
		}
		$content .= '</select>';
		return $content;
	}

	/**
	 * redirects the page to a specific post
	 *
	 * @param int $topicId
	 * @param int $postId post number of the topic, should be a number or the keyword "last"
	 */
	function redirectToReply($topicId, $postId) {
		if ($postId == 'last') {
			$res = $this->databaseHandle->exec_SELECTquery(
				'uid',
				'tx_mmforum_posts',
				'deleted = 0  AND topic_id = ' . $topicId . $this->getStoragePIDQuery(),
				'',
				'post_time DESC',
				'',
				1
			);
			list($postId) = $this->databaseHandle->sql_fetch_row($res);
		} else {
			$postId = intval($postId);
		}
		if ($postId) {
			$link = $this->get_pid_link($postId, $this->piVars['sword'], $this->conf);
			HttpUtility::redirect($link);
		}
	}

	/**
	 * Dynamically generates user buttons.
	 * This function dynamically generates the set of buttons that is displayed
	 * below each post. The buttons can be configured by TypoScript in
	 * plugin.tx_mmforum_pi1.list_posts.userbuttons. See the existing configuration
	 * for examples.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2009-02-10
	 * @param   array  $user The user record
	 * @return  string       HTML code of the generated buttons
	 */
	function getUserButtons($user) {
		$oldData = $this->cObj->data;
		$this->cObj->data = $user;

		$profile = '';

		foreach($this->conf['list_posts.']['userbuttons.'] as $key => $obj) {

			if (!$this->conf['list_posts.']['userbuttons.'][$key.'.'] || !is_string($obj))
				continue;

			if ($obj === 'MMFORUM_BUTTON') {
				$buttonConf = $this->conf['list_posts.']['userbuttons.'][$key.'.'];

				if ($buttonConf['if.'] && !$this->cObj->checkif ($buttonConf['if.']))
					continue;

				if ($buttonConf['label.']) {
					$label = $this->cObj->cObjGetSingle('TEXT', $buttonConf['label.']);
				}

				if ($buttonConf['link.']) {
					$link = $this->cObj->cObjGetSingle('TEXT', $buttonConf['link.']);
				}

				if ($buttonConf['special']) {
					switch($buttonConf['special']) {
						case 'www':
							$res = @parse_url($link);
							if (count($res)==0 || strlen(trim($link))==0) continue 2;
							break;
					}
				}

				$profile .= $this->createButton(
					$label ? $label : $buttonConf['label'],
					$buttonConf['parameters'] ? $buttonConf['parameters'] : null,
					$buttonConf['id'] ? $buttonConf['id'] : null,
					$buttonConf['small'] ? true : false,
					$link,
					false,
					$buttonConf['ATagParams']
				);
			} else {
				$profile .= $this->cObj->cObjGetSingle($obj, $this->conf['list_posts.']['userbuttons.'][$key.'.']);
			}
		}

		$this->cObj->data = $oldData;

		return $profile;
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_postfunctions.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_postfunctions.php']);
}
