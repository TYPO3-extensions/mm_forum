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
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  176: class tx_mmforum_pi1 extends tx_mmforum_base
 *
 *              SECTION: General plugin methods
 *  191:     function main($content, $conf)
 *  377:     function evalConfigValues()
 *  441:     function page_footer($conf)
 *  474:     function page_header($conf)
 *
 *              SECTION: Main content functions
 *  503:     function list_unread($content, $conf)
 *  703:     function list_unanswered($content, $conf)
 *  842:     function list_category($content, $conf)
 *  948:     function list_forum($content, $conf)
 * 1094:     function list_topic($content, $conf)
 * 1409:     function list_prefix($content, $conf, $prefix)
 * 1774:     function list_latest($conf)
 * 1852:     function list_users()
 * 2009:     function userdef_cmp($a,$b)
 * 2117:     function list_postqueue()
 * 2127:     function list_rss()
 *
 *              SECTION: Forum content management functions
 * 2143:     function new_topic($content, $conf)
 * 2383:     function new_post($content, $conf)
 * 2660:     function performAttachmentUpload()
 * 2737:     function post_edit($content, $conf)
 *
 *              SECTION: Favorites
 * 3084:     function set_favorite()
 * 3133:     function del_favorite()
 * 3161:     function favorites($content, $conf)
 *
 *              SECTION: Forum content management helper functions
 * 3358:     function generateBBCodeButtons($template)
 * 3432:     function show_smilie_db($conf)
 * 3476:     function update_lastpost_topic($topicId)
 * 3490:     function update_lastpost_forum($forumId)
 * 3516:     function set_solved($topicId, $solved)
 * 3533:     function send_newpost_mail ($content,$conf,$topic_id)
 * 3606:     function send_newpost_mail_forum ($content,$conf,$topic_id,$forum_id)
 *
 *              SECTION: Subordinary content functions
 * 3663:     function post_history($conf)
 * 3680:     function view_profil ($content,$conf)
 * 3906:     function view_last_10_topics($uid)
 * 3952:     function view_last_10_posts($uid)
 * 3979:     function send_mail($content, $conf)
 *
 *              SECTION: Page navigation
 * 4089:     function dynamicPageNav($maxPage,$linkVar_name='page',$def_linkParams=array(),$maxOffset=4)
 * 4154:     function pagecount ($table,$column,$id,$limitcount,$count=FALSE)
 * 4229:     function pagecount2 ($lastlogin, $limitcount)
 *
 *              SECTION: Forum content helper functions
 * 4284:     function getlastpost($postid,$conf,$topicTitle=false)
 * 4341:     function getauthor($userId)
 * 4369:     function get_topic_name($topicId)
 * 4385:     function ident_user($uid, $conf, $threadauthor = FALSE)
 * 4454:     function getUserAvatar($userData)
 * 4488:     function get_forum_path ($forumid,$topicid)
 * 4531:     function bb2text($text, $conf)
 * 4544:     function getunreadposts ($content, $conf, $lastlogin)
 * 4617:     function reset_unreadpost($content, $conf)
 * 4645:     function highlight_text ($text,$words)
 * 4680:     function get_pid_link ($post_id,$sword,$conf)
 * 4718:     function get_topic_is($topicId)
 * 4734:     function get_userranking($userId, $conf)
 * 4750:     function user_config($conf,$param)
 * 4816:     function open_topic($content, $conf)
 * 4836:     function get_user_fav($userId = 0)
 * 4853:     function get_topic_id($postId)
 * 4866:     function get_forum_id($topicId)
 * 4881:     function get_forumbox($topic_id)
 * 4937:     function get_last_post($topicId)
 * 4956:     function get_userid($username)
 * 4970:     function errorMessage($conf, $msg)
 * 4986:     function successMessage($conf, $msg)
 * 5004:     function getLanguageFolder()
 * 5021:     function getTopicData($topicId)
 * 5041:     function getTopicIcon($topic)
 * 5130:     function getForumIcon($forum=null, $isClosed=false, $isNew=false)
 * 5200:     function getMarkAllRead_link()
 * 5224:     function getAttachment()
 *
 *              SECTION: User rights management
 * 5265:     function getIsAdmin()
 * 5279:     function getIsMod($forum=0)
 * 5315:     function getIsModOrAdmin($forum=0)
 * 5327:     function getMayRead_forum_query($prefix="")
 * 5369:     function getMayRead_forum_query($prefix="")
 * 5423:     function getMayRead_forum($forum)
 * 5490:     function getMayWrite_forum_query()
 * 5530:     function getMayWrite_forum($forum)
 * 5604:     function getMayWrite_topic($topic)
 * 5654:     function getMayRead_topic($topic)
 * 5679:     function getMayRead_post($post)
 *
 *              SECTION: Various helper functions
 * 5707:     function getUserPidQuery($table="fe_users")
 * 5719:     function getBoardData($uid)
 * 5735:     function getCategoryLimit_query($tablename="")
 * 5756:     function createRootline($content,$conf)
 * 5941:     function createButton($label,$params,$id=0,$small=false,$href='',$nolink=false,$atagparams='')
 * 5981:     function topic_setSolveStatus($status)
 * 6018:     function topic_solve()
 * 6031:     function topic_unsolve()
 * 6049:     function getUserProfileLink($userData)
 * 6090:     function linkToUserProfile($userData, $text = '')
 * 6108:     function getUserNameField()
 * 6118:     function getTopicIconMode()
 *
 * TOTAL FUNCTIONS: 89
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_base.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_postalert.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_havealook.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_havealookforum.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_postfunctions.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_user.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_polls.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_ranksfe.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_postqueue.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_rss.php');

require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/user/class.tx_mmforum_usermanagement.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/user/class.tx_mmforum_userfield.php');

require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/model/class.tx_mmforum_user.php');

if(t3lib_extMgm::isLoaded('ratings'))
	require_once(t3lib_extMgm::extPath('ratings') . 'class.tx_ratings_api.php');

/**
 * Plugin 'mm_forum' for the 'mm_forum' extension.
 * This is the main plugin of the 'mm_forum' extension. Offers functions
 * for displaying all boards, grouped by category, and listing topics in
 * a specifig board or by certain conditions and then listing posts in a
 * specific topic.
 * Furthermore, there are function for creating new posts and editing
 * existing ones and displaying user information and editing personal
 * preferences like listing and editing topics for email notification
 * or marking as favorite.
 *
 * @author     Holger Trapp   <h.trapp@mittwald.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Björn Detert   <b.detert@mittwald.de>
 * @author     Benjamin Mack  <benni@typo3.org>
 * @copyright  2008 Mittwald CM Service
 * @version    2008-03-17
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_pi1 extends tx_mmforum_base {
	var $prefixId      = 'tx_mmforum_pi1';
	var $scriptRelPath = 'pi1/class.tx_mmforum_pi1.php';

	/**
	 * General plugin methods
	 */

	/**
	 * The plugin main function. Generates all content.
	 * @param  string $content The content
	 * @param  array  $conf    The plugin configuration vars
	 * @return string          The plugin content
	 */
	function main($content, $conf) {

			/* Initialize base object */
		$this->init($conf);
		$this->pi_USER_INT_obj = $this->conf['cache'] ? 0 : 1;
		$this->config['code'] = $this->cObj->stdWrap($this->conf['code'], $this->conf['code.']);

			/* Evaluate flexform values */
		$this->evalConfigValues();

			/* Store a reference to the global configuration array */
		$conf =& $this->conf;

			/* Load template file */
		$this->templateFile = $conf['templateFile'];

			/* Load plugin code */
		$codes = t3lib_div::trimExplode(',', ($this->config['code'] ? $this->config['code'] : $this->conf['defaultCode']), 1);
		if (!count($codes)) {
			$codes = array('');
		}

			/* Include RSS feed to header */
		tx_mmforum_rss::setHTMLHeadData('all');

			/* Change page title */
		if ($this->conf['substitutePagetitle']) {
			$this->createPageTitle();
		}

		foreach ($codes as $theCode) {
			list($theCode, $cat, $aFlag) = explode('/', $theCode);
			$theCode = (string)strtoupper(trim($theCode));
			$this->theCode = $theCode;
			switch ($theCode) {
				case 'HAVEALOOK':
					$content = tx_mmforum_havealook::edit($this);
					break;
				case 'FAVORITES':
					$content = $this->favorites($content, $conf);
					break;
				case 'POSTALERTLIST':
					$content = tx_mmforum_postalert::list_alerts($conf);
					break;
				case 'LIST_POSTS':
					$content =  $this->post_history($conf);
					break;
				case 'LATEST':
					$content = $this->list_latest($conf);
					break;
				case 'USERLIST':
					$content = $this->list_users();
					break;
				case 'POSTQUEUE':
					$content = $this->list_postqueue();
					break;
				case 'RSS':
					$content = $this->list_rss();
					break;
				Case 'FEADMIN':
					$content = $this->frontendAdministration();
					Break;
				default:

					// Include hook to add own content and do change the action to direct it differently
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['preDispatchHook'])) {
						foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['preDispatchHook'] as $_classRef) {
							$_params = array(
								'content' => &$content,
								'conf'    => &$conf
							);
							$_procObj = &t3lib_div::getUserObj($_classRef);
							$_procObj->preDispatchHook($_params, $this);
						}
					}

					if ($this->redirectTo) {
						header('Location: ' . t3lib_div::locationHeaderUrl($this->redirectTo));
						exit();
					}

					switch ($this->piVars['action']) {
						case 'list_unread':
							$content = $this->list_unread($content, $conf);
							break;
						case 'list_unans':
							$content = $this->list_unanswered($content, $conf);
							break;
						case 'list_cat':
							$content = $this->list_category($content, $conf);
							break;
						case 'list_topic':
							$content = $this->list_topic($content, $conf);
							break;
						case 'list_post':
							$content = tx_mmforum_postfunctions::list_post($content, $conf, '');
							break;
						case 'new_topic':
							$content = $this->new_topic($content, $conf);
							break;
						case 'new_post':
							$content = $this->new_post($content, $conf);
							break;
						case 'send_mail':
							$content = $this->send_mail($content, $conf);
							break;
						case 'forum_view_profil':
						case 'view_profile':
							$content = $this->view_profil($content, $conf);
							break;
						case 'post_del':
							$content = tx_mmforum_postfunctions::post_del($content, $conf);
							break;
						case 'post_edit':
							$content = $this->post_edit($content, $conf);
							break;
						case 'change_userdata':
							$content = $this->change_userdata($content, $conf);
							break;
						case 'reset_read':
							$content = $this->reset_unreadpost($content, $conf);
							break;
						case 'set_havealook':
							$content = tx_mmforum_havealook::set($this);
							break;
						case 'del_havealook':
							$content = tx_mmforum_havealook::delete($this);
							break;
						case 'set_havealookforum':
							$content = tx_mmforum_havealookforum::set($this);
							break;
						case 'del_havealookforum':
							$content = tx_mmforum_havealookforum::delete($this);
							break;
						case 'set_favorite':
							$content = $this->set_favorite();
							break;
						case 'del_favorite':
							$content = $this->del_favorite();
							break;
						case 'open_topic':
							$content = $this->open_topic($content, $conf);
							break;
						case 'post_alert':
							$content = tx_mmforum_postalert::post_alert($conf);
							break;
						case 'list_prefix':
							$content = $this->list_prefix($content,$conf,$this->piVars['list_prefix']['prfx']);
							break;
						case 'post_history':
							$content = $this->post_history($conf);
							break;
						case 'get_attachment':
							$content = $this->getAttachment($conf);
							break;
						default:
							if (empty($content)) {
								$content = $this->list_forum($content, $conf);
							}
							break;
					}
					if ($this->piVars['solve']) {
						$content = $this->topic_solve();
					}
					if ($this->piVars['unsolve']) {
						$content = $this->topic_unsolve();
					}
					$content = $this->page_header($conf) . $content;
				break;
			}
		}

        // Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['mainContentHook'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['mainContentHook'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$content = $_procObj->mainContentHook($content, $this);
			}
		}
		$content .= $this->page_footer($conf);

		return $this->pi_wrapInBaseClass($content);
	}

	/**
	 * Evaluates configuration parameters submitted via FlexForms.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-27
	 * @return void
	 */
	function evalConfigValues() {
		$this->pi_initPIflexform();
		$ff = $this->cObj->data['pi_flexform'];

		// Get the code, either from the flexform, or TypoScript
		$code = $this->pi_getFFvalue($ff, 'code', 'general');
		if (!$code) {
			$code = $this->config['code'] = $this->conf['code'];
		} else if ($code == 'BOARD') {
			$this->config['code'] = '';
		} else {
			$this->config['code'] = $code;
		}

		switch ($code) {
			case 'LATEST':
				$exclCategories_latest = trim($this->pi_getFFvalue($ff, 'exclCategories_latest', 'general'));
				$limitCat = $exclCategories_latest ? $exclCategories_latest : $this->cObj->stdWrap($this->conf['exclCategories_latest'],$this->conf['exclCategories_latest.']);

				$this->limitCat = ((strlen($limitCat) > 0)  ? $limitCat : false);

				$limitTopic = trim($this->pi_getFFvalue($ff, 'latest_limit', 'general'));
				$this->conf['listLatest.']['limit'] = (intval($limitTopic) > 0) ? intval($limitTopic) : $this->conf['listLatest.']['limit'];
				break;

			case 'USERLIST':
				$this->userlist_fields  = $this->pi_getFFvalue($ff, 'userlist_fields', 'general');
				if (!$this->userlist_fields) {
					$this->userlist_fields = $this->conf['userlist_fields'];
				}

				$this->userlist_limit = $this->pi_getFFvalue($ff, 'userlist_limit', 'general');
				if (!$this->userlist_limit) {
					$this->userlist_limit = $this->conf['userlist_limit'];
				}
				break;

			case 'BOARD':
			case '':
				$exclCategories = trim($this->pi_getFFvalue($ff, 'exclCategories', 'general'));
				$limitCat = $exclCategories ? $exclCategories : $this->cObj->stdWrap($this->conf['exclCategories'],$this->conf['exclCategories.']);
				$this->limitCat = ((strlen($limitCat) > 0) ? $limitCat : false);

				$redirect = $this->pi_getFFvalue($ff, 'redirectSpecial', 'general');
				if ($redirect == 'list_unans' || $redirect == 'list_unread' || $redirect == 'list_prefix') {
					$linkParams[$this->prefixId] = array(
						'action' => $redirect
					);
					if ($redirect == 'list_prefix') {
						$linkParams[$this->prefixId]['list_prefix'] = array(
							'prfx' => strtolower($this->pi_getFFvalue($ff, 'prefix', 'general'))
						);
					}
					$link = $this->pi_getPageLink($this->getForumPID(), '', $linkParams);
					$this->redirectTo = $this->tools->getAbsoluteUrl($link);
				}
				break;
		}
	}


	/**
	 * Renders the page footer
	 * @return	string	Returns the Footerstring
	 */
	function page_footer($conf) {
		$template = $this->cObj->fileResource($conf['template.']['footer']);
		$template = $this->cObj->getSubpart($template, '###FOOTER###');

		$_EXTKEY = 'mm_forum';
		@include(t3lib_extMgm::extPath('mm_forum') . 'ext_emconf.php');

		if (isset($EM_CONF)) {
			$marker['###FOOTER_MESSAGE###'] = sprintf($this->pi_getLL('footermessage'), $EM_CONF['mm_forum']['version']);
		} else {
			$marker['###FOOTER_MESSAGE###'] = '';
		}

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['pageFooter'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['pageFooter'] as $_classRef) {
				$_procObj = &t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->pageFooter($marker, $this);
			}
		}

		$content  = $this->cObj->substituteMarkerArrayCached($template, $marker);
		$content .= '<!-- mm_forum Version '.$EM_CONF['mm_forum']['version'].' //-->';

		return $content;
	}

	/**
	 * Renders the page header, containing links to e.g. the user control center
	 * @param	array	The plugin's configuration vars
	 * @return	string	The header string
	 */
	function page_header($conf) {
		$template = $this->cObj->fileResource($conf['template.']['header']);
		$template = $this->cObj->getSubpart($template, '###HEADER###');
		$marker['###FORUM_HEAD###'] = $this->pi_getLL('forum_head');
		$marker['###FORUM_DESC###'] = $this->pi_getLL('forum_desc');

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['pageHeader'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['pageHeader'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->pageHeader($marker, $this);
			}
		}

		return $this->cObj->substituteMarkerArrayCached($template, $marker);
	}


	/**
	 * Main content functions
	 */

	/**
	 * Lists unread topics in a HTML table.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The content
	 */
    function list_unread($content, $conf)
    {
        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
        $template = $this->cObj->getSubpart($template, "###TOPICBEGIN3###");
        $marker = array();

        if ($GLOBALS['TSFE']->fe_user->user['uid']) {
            $resunread = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'tx_mmforum_prelogin as lastlogin',
                'fe_users',
                'uid = "'.$GLOBALS['TSFE']->fe_user->user['uid'].'"'
            );
            $rowunread = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resunread);
            $lastlogin = $rowunread['lastlogin'];
        }
        else {
            $template = $this->cObj->fileResource($conf['template.']['login_error']);
            $template = $this->cObj->getSubpart($template, "###LOGINERROR###");
            $marker = array();
            $marker['###LOGINERROR_MESSAGE###'] = $this->pi_getLL('login_error');
            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
            return $content;
        }

        $marker['###FORUMPATH###']          = '';
        $marker['###FORUMNAME###']          = $this->pi_getLL('board.unreadEntries');
        $marker['###NEWTOPICLINK###']       = '';
        $marker['###LABEL_TOPIC###']        = $this->pi_getLL('board.topic');
        $marker['###LABEL_REPLIES_HITS###'] = $this->pi_getLL('board.replies');
        $marker['###LABEL_AUTHOR###']       = $this->pi_getLL('board.author');
        $marker['###LABEL_LASTPOST###']     = $this->pi_getLL('board.lastPost');
        $marker['###LABEL_RATING###']		= $this->pi_getLL('board.rating');
        $marker['###PAGES###']              = $this->pagecount2 ($lastlogin, $conf['topic_count']); // Anzeigen der Seiten, durch die man blÃ¯Â¿Â½ttern kann

		if(!$this->isTopicRating()) {
			$template = $this->cObj->substituteSubpart($template, '###SUBP_RATING_LABEL###', '');
		}

		// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnread_header'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnread_header'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listUnread_header($marker, $this);
				}
			}

        $content .= $this->getMarkAllRead_link().'<br />';
        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);                   // Markers write back
        $page = intval($this->piVars['page']);

        // If page not set, then set page = 1
            if(empty($page))
                $page = 1;

        $limit = ($conf['topic_count']-1)*($page-1).','.$conf['topic_count'];                       // Make MYSQL LIMIT value
        $topiclist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "distinct tx_mmforum_topics.topic_title,
                      tx_mmforum_topics.closed_flag,
                      tx_mmforum_topics.solved,
                      tx_mmforum_topics.forum_id,
                      tx_mmforum_topics.uid,
                      tx_mmforum_topics.topic_poster,
                      tx_mmforum_topics.topic_last_post_id,
                      tx_mmforum_topics.topic_replies,
                      tx_mmforum_topics.topic_views",
            "tx_mmforum_topics inner join tx_mmforum_posts on tx_mmforum_topics.uid = tx_mmforum_posts.topic_id,
             tx_mmforum_forums f, tx_mmforum_forums c",
            "tx_mmforum_topics.deleted = 0 AND
                tx_mmforum_posts.deleted = 0 AND
                post_time >= ".$lastlogin." AND
                tx_mmforum_topics.forum_id = f.uid AND
                f.parentID = c.uid ".
                $this->getStoragePIDQuery('f,tx_mmforum_topics').
                $this->getMayRead_forum_query('f').
                $this->getMayRead_forum_query('c').
                $this->getCategoryLimit_query('c'),
            '',
            'tx_mmforum_topics.topic_last_post_id desc',
            $limit
        );

        if($GLOBALS['TYPO3_DB']->sql_num_rows($topiclist)>0) {
	        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
	        $template = $this->cObj->getSubpart($template, "###LIST_TOPIC3###");
		}
		else {
	        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
	        $template = $this->cObj->getSubpart($template, "###LIST_NOTOPIC###");

            $content .= $this->cObj->substituteMarker($template, "###LABEL_NOTOPICS###", $this->pi_getLL('topic.noTopicsFound'));
		}

		if(!$this->isTopicRating()) {
			$template = $this->cObj->substituteSubpart($template, '###SUBP_RATING###', '');
		}

        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($topiclist)) {
            $forum = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'forum_name,parentID',
                'tx_mmforum_forums',
                'uid="'.intval($row['forum_id']).'"'
            );
            $rowf = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forum);

            $cat = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'forum_name as cat_title',
                'tx_mmforum_forums',
                'uid="'.intval($rowf['parentID']).'"'
            );
            $rowc = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($cat);

            if($row['solved'] == 1) {
                $imgInfo['src'] = $conf['path_img'].$conf['images.']['solved'];
                $imgInfo['alt'] = $this->pi_getLL('topic.isSolved');
                $imgInfo['title'] = $this->pi_getLL('topic.isSolved');
                $solved         = $this->buildImageTag($imgInfo);
            } else {
                $solved = '';
            }
            $linkparams[$this->prefixId] = array (
                'action'  => 'list_post',
                'tid'     => $row['uid'],
                'pid'     => 'last'
            );
            if($this->useRealUrl()) {
                $linkparams[$this->prefixId]['fid'] = $row['forum_id'];
            }
            $imgInfo['src'] = $conf['path_img'].$conf['images.']['jump_to'];
            $imgInfo['alt'] = $this->pi_getLL('topic.gotoLastPost');
            $imgInfo['title'] = $this->pi_getLL('topic.gotoLastPost');
            $last_post_link = $this->pi_linkToPage($this->buildImageTag($imgInfo), $GLOBALS['TSFE']->id, '', $linkparams);

            if($row['topic_is'])
                $topic_is = $this->cObj->wrap($row['topic_is'],$this->conf['list_topics.']['prefix_wrap']);
            else
                $topic_is = '';

            $row['topic_title'] = stripslashes($row['topic_title']);

            $row['topic_title'] = str_replace('<','&lt;',$row['topic_title']);
            $row['topic_title'] = str_replace('>','&gt;',$row['topic_title']);

            $linkParams[$this->prefixId] = array(
                'action'    => 'list_post',
                'tid'       => $row['uid']
            );
            $marker['###TOPICNAME###']  = $topic_is.$this->pi_linkTP($this->escape($row['topic_title']),$linkParams).$solved;
            $marker['###UNDERLINE###']  = $this->escape($rowc['cat_title']).' &raquo; '.$this->escape($rowf['forum_name']);
            $marker['###POSTS###']      = intval($row['topic_replies']).' ('.intval($row['topic_views']).')';
            $marker['###AUTHOR###']		= $this->linkToUserProfile($row['topic_poster']);
            $marker['###LAST###']       = $this->getlastpost($row['topic_last_post_id'],$conf).' '.$last_post_link;
            $marker['###READIMAGE###'] = $this->getTopicIcon($row);
            $marker['###RATING###']		= $this->getRatingDisplay('tx_mmforum_topic', $row['uid']);

            IF (($row['topic_replies'] + 1) > $conf['post_limit'])
            {
                $page_link = $this->pi_getLL('page.goto').':';
                $menge = $row['topic_replies'];
                $i = 0;
                while($menge >= 0) {
                    $i++;
                    $linkparams[$this->prefixId] = array (
                        'action' => 'list_post',
                        'tid'    => $row['uid'],
                        'page'   => $i
                    );
          					if ($linkparams[$this->prefixId]['page'] < 1) {
                      $linkparams[$this->prefixId]['page'] = '';
                    }
                    $page_link  .= ' '.$this->pi_linkToPage($i,$GLOBALS['TSFE']->id,'',$linkparams);
                    $menge = $menge - $conf['post_limit'];
                }
                $marker['###TOPICNAME###'] .= ' '.$this->cObj->wrap($page_link,$this->conf['list_topics.']['listunread_pagenav_wrap']);
            }

            // Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnread_listitem'])) {
					foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnread_listitem'] as $_classRef) {
						$_procObj = & t3lib_div::getUserObj($_classRef);
						$marker = $_procObj->listUnread_listitem($marker, $row, $this);
					}
				}

            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        }

        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
        $template = $this->cObj->getSubpart($template, "###TOPICEND###");

        $marker['###PAGES###'] = $this->pagecount2 ($lastlogin, $conf['topic_count']); // Anzeigen der Seiten durch die man Blättern kann

        // Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnread_footer'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnread_footer'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listUnread_footer($marker, $this);
				}
			}

        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

        return $content;
    }


	/**
	 * Lists unanswered topics in a HTML table.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The content
	 */
	function list_unanswered($content, $conf) {
		$feUser       = intval(isset($GLOBALS['TSFE']->fe_user->user['uid']) ? $GLOBALS['TSFE']->fe_user->user['uid'] : 0);
		$templateFile = $this->cObj->fileResource($conf['template.']['list_topic']);
		$template     = $this->cObj->getSubpart($templateFile, '###TOPICBEGIN_UNANSW###');

		// find out the unread posts since the last login
		if ($feUser) {
			list($rowUnread) = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'tx_mmforum_prelogin as lastLogin',
				'fe_users',
				'uid = ' . $feUser);
			$readarray = $this->getunreadposts($content, $conf, $rowUnread['lastLogin']);
		}

		$marker = array(
			'###FORUMNAME###'    => $this->pi_getLL('board.unansweredEntries'),
			'###LABEL_TOPIC###'  => $this->pi_getLL('board.topic'),
			'###LABEL_AUTHOR###' => $this->pi_getLL('board.author'),
		);

		$limitCount = $conf['topic_count'];
		$pagebrowser = $this->pagecount('tx_mmforum_topics', 'topic_replies', 0, $limitCount);
		$marker['###PAGES###'] = $pagebrowser;

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_header'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_header'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->listUnanswered_header($marker, $this);
			}
		}

		// add "TOPICBEGIN_UNANSW" to the content
		$content .= $this->cObj->substituteMarkerArray($template, $marker);

		$currentPage = max(intval($this->piVars['page']), 1);
		$limit = ($limitCount - 1) * ($currentPage - 1) . ', ' . $limitCount;

		$topiclist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			't.*,
				c.grouprights_read as cat_read,
				f.grouprights_read as f_read',
			'tx_mmforum_topics t, tx_mmforum_forums f, tx_mmforum_forums c',
			't.topic_replies = 0 AND
				t.deleted=0 AND t.hidden=0 AND
				f.deleted=0 AND f.hidden=0 AND
				t.forum_id = f.uid AND
				c.uid = f.parentID '.
				$this->getStoragePIDQuery('t,f') .
				$this->getMayRead_forum_query('f') .
				$this->getMayRead_forum_query('c') .
				$this->getCategoryLimit_query('c'),
			'',
			'topic_last_post_id DESC',
			$limit
		);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($topiclist)) {
			$forum = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'forum_name, parentID',
				'tx_mmforum_forums',
				'uid = "'.$row['forum_id'].'"'
			);
			$rowf = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forum);

			$cat = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'forum_name as cat_title',
				'tx_mmforum_forums',
				'uid="'.$rowf['parentID'].'"'
			);
			$rowc = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($cat);

			$template = $this->cObj->getSubpart($templateFile, '###LIST_TOPIC_UNANSW###');
			$row['topic_title'] = str_replace('<', '&lt;', $row['topic_title']);
			$row['topic_title'] = str_replace('>', '&gt;', $row['topic_title']);

			$prefix = ($row['topic_is'] ? $this->cObj->wrap($row['topic_is'], $this->conf['list_topics.']['prefix_wrap']) : '');

			// Check if solved flag is set
			if ($row['solved'] == 1) {
				$imgInfo = array(
					'src'	=> $conf['path_img'] . $conf['images.']['solved'],
					'alt'	=> $this->pi_getLL('topic.isSolved'),
					'title'	=> $this->pi_getLL('topic.isSolved')
				);
				$solved = $this->buildImageTag($imgInfo);
			} else {
				$solved = '';
			}

			$row['topic_title'] = stripslashes($row['topic_title']);

			$linkParams[$this->prefixId] = array(
				'action' => 'list_post',
				'tid'    => $row['uid']
			);

			if ($this->useRealUrl()) {
				$linkParams[$this->prefixId]['fid'] = $row['forum_id'];
			}
			$marker['###TOPICNAME###'] = $prefix . $this->pi_linkTP($this->escape($row['topic_title']), $linkParams);
			$marker['###UNDERLINE###'] = $this->escape($rowc['cat_title']) . ' &raquo; ' . $this->escape($rowf['forum_name']);
			$marker['###AUTHOR###']    = $this->getauthor($row['topic_poster']);
			$linkParams[$this->prefixId]['pid'] = 'last';
			$imgInfo['src']		= $conf['path_img'] . $conf['images.']['jump_to'];
			$imgInfo['alt']		= $this->pi_getLL('topic.gotoLastPost');
			$imgInfo['title']	= $this->pi_getLL('topic.gotoLastPost');
			$last_post_link = $this->pi_linkToPage($this->buildImageTag($imgInfo), $GLOBALS['TSFE']->id, '', $linkParams);
			$marker['###LAST###']      = $this->getlastpost($row['topic_last_post_id'], $conf).' '.$last_post_link;
			$marker['###READIMAGE###'] = $this->getTopicIcon($row);
			$marker['###PREFIX###']    = $prefix;

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_listitem'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_listitem'] as $_classRef) {
					$_procObj = &t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listUnanswered_listitem($marker, $row, $this);
				}
			}

			$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		}

		$template = $this->cObj->getSubpart($templateFile, '###TOPICEND_UNANSW###');
		$marker['###PAGES###'] = $pagebrowser;

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_footer'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_footer'] as $_classRef) {
				$_procObj = &t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->listUnanswered_footer($marker, $this);
			}
		}

		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		return $content;
	}

	/**
	 * Lists all boards in a specific category.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The content
	 */
	function list_category($content, $conf) {
		$templateFile = $this->cObj->fileResource($conf['template.']['main']);
		$template     = $this->cObj->getSubpart($templateFile, "###LIST_FORUM_BEGIN###");
		$marker = array(
			'###MARKREAD###'       => $this->getMarkAllRead_link(),
			'###LABEL_FORUM###'    => $this->pi_getLL('board.board'),
			'###LABEL_TOPICS###'   => $this->pi_getLL('board.topics'),
			'###LABEL_POSTS###'    => $this->pi_getLL('board.posts'),
			'###LABEL_LASTPOST###' => $this->pi_getLL('board.lastPost')
		);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_header'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_header'] as $_classRef) {
				$_procObj = &t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->listCategories_header($marker, $this);
			}
		}

		// substitute the header part
		$content .= $this->cObj->substituteMarkerArray($template, $marker);

		// load template subparts only once before the loops
		$templateCatList   = $this->cObj->getSubpart($templateFile, '###LIST_CAT###');
		$templateForumList = $this->cObj->getSubpart($templateFile, '###LIST_FORUM###');

		$catList = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid, forum_name',
			'tx_mmforum_forums f',
			'f.deleted = 0 AND f.hidden = 0 AND f.parentID=0 AND f.uid = ' . intval($this->piVars['cid']) .
			$this->getStoragePIDQuery().
			$this->getMayRead_forum_query('f')
		);

		// loop through every parent forum (= category)
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($catList)) {
			$marker['###CATNAME###'] = $this->escape($row['forum_name']);

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_listitem'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_listitem'] as $_classRef) {
					$_procObj = &t3lib_div::getUserObj($_classRef);
					$marker   = $_procObj->listCategories_listitem($marker, $row, $this);
				}
			}
			// add the category list
			$content .= $this->cObj->substituteMarkerArrayCached($templateCatList, $marker);

			$forumList = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid, forum_name, forum_desc, forum_topics, forum_posts, forum_last_post_id',
				'tx_mmforum_forums f',
				'f.deleted = 0 AND f.hidden = 0 AND f.parentID = ' . $row['uid'] .
					$this->getStoragePIDQuery('f').
					$this->getMayRead_forum_query(),
				'',
				'sorting ASC'
			);

			// loop through every forum
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forumList)) {
				$linkParams[$this->prefixId] = array(
					'action' => 'list_topic',
					'fid'     => $row['uid']
				);
				$imgInfo = array(
					'src' => $conf['path_img'] . $conf['images.']['read']
				);
				$marker['###FORUMNAME###']  = $this->pi_linkToPage($this->escape($row['forum_name']), $GLOBALS['TSFE']->id, '', $linkParams);
				$marker['###FORUMDESC###']  = $this->escape($row['forum_desc']);
				$marker['###THEMES###']     = $this->escape($row['forum_topics']);
				$marker['###POSTS###']      = $this->escape($row['forum_posts']);
				$marker['###LASTPOSTS###']  = $this->getlastpost($row['forum_last_post_id'], $conf, true);
				$marker['###READIMAGE###']  = $this->buildImageTag($imgInfo);

				// Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_sublistitem'])) {
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_sublistitem'] as $_classRef) {
						$_procObj = &t3lib_div::getUserObj($_classRef);
						$marker = $_procObj->listCategories_sublistitem($marker, $row, $this);
					}
				}
				$content .= $this->cObj->substituteMarkerArrayCached($templateForumList, $marker);
			}
		}

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_footer'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_footer'] as $_classRef) {
				$_procObj = &t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->listCategories_footer($marker, $this);
			}
		}

		// substitute the footer part
		$template = $this->cObj->getSubpart($templateFile, '###LIST_FORUM_END###');
		$content .= $this->cObj->substituteMarkerArray($template, $marker);
		return $content;
	}

	/**
	 * Lists all categories and all boards.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The content
	 */
	function list_forum($content, $conf) {
		$templateFile = $this->cObj->fileResource($conf['template.']['main']);
		$template     = $this->cObj->getSubpart($templateFile, '###LIST_FORUM_BEGIN###');
		$marker = array(
			'###MARKREAD###'       => $this->getMarkAllRead_link(),
			'###LABEL_FORUM###'    => $this->pi_getLL('board.board'),
			'###LABEL_TOPICS###'   => $this->pi_getLL('board.topics'),
			'###LABEL_POSTS###'    => $this->pi_getLL('board.posts'),
			'###LABEL_LASTPOST###' => $this->pi_getLL('board.lastPost'),
			'###PAGETITLE###'      => $this->cObj->data['header'],
			'###LABEL_OPTIONS###'  => $this->pi_getLL('options')
		);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_header'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_header'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->listForums_header($marker, $this);
			}
		}

		$content .= $this->cObj->substituteMarkerArray($template, $marker);
		$feUserId = intval(isset($GLOBALS['TSFE']->fe_user->user['uid']) ? $GLOBALS['TSFE']->fe_user->user['uid'] : 0);

		if ($feUserId) {
			//$resunread = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			//	'tx_mmforum_prelogin as lastlogin',
			//	'fe_users',
			//	'uid = ' . $feUserId
			//);
			//$rowunread = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resunread);
			//$lastlogin = $rowunread['lastlogin'];#-1814400;
			//$readarray = $this->getunreadposts($content, $conf, $lastlogin);
			$lastlogin = $GLOBALS['TSFE']->fe_user->user['tx_mmforum_prelogin'];
		}

		$catlist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid, forum_name',
			'tx_mmforum_forums f',
			'deleted = 0 AND hidden = 0 AND parentID = 0 ' .
				 $this->getStoragePIDQuery().
				 $this->getMayRead_forum_query('f').
				 $this->getCategoryLimit_query(),
			'',
			'sorting ASC'
		);

        //Remember the read forum data na dextract only the needed subforums
        $parents = array();
        $parentIds = array();
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($catlist)) {
        	$parents[] = $row;
        	$parentIds[] = $row['uid'];
        }
        $catIdWhere = count($parentIds) ? '(parentID='. implode(' OR parentID=', $parentIds).')' : '0=1';
        $where ='deleted = 0 AND
                 hidden = 0 AND
                 '.$catIdWhere.
                 $this->getStoragePIDQuery().
                 $this->getMayRead_forum_query();
        $forumlist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'tx_mmforum_forums',
        		$where,
                '',
                'sorting ASC'
            );
        $parentForums = array();
        $visibleForumKeys = array();
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forumlist)) {
        	$parentForums[$row['parentID']][] = $row;
        	$visibleForumKeys[] = $row['uid'];
        }

        $filter = array(
        	'forum_id' => $visibleForumKeys,
			'onlyCategories' => 1
        );

		//get ids of unread posts in these forums
        $unreadarray  = $this->getunreadposts($content, $conf, $lastlogin, $filter);

		$x = 0;
		$i = 1;
		//while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($catlist)) {
		foreach ($parents as $row) {
			$x++;
			$template = $this->cObj->getSubpart($templateFile, '###LIST_CAT###');

			$marker['###CATNAME###'] = '<a name="cat' . $row['uid'] . '"></a>' . $this->escape($row['forum_name']);
			$marker['###CATID###']	= 'c' . $row['uid'];

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_categoryItem'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_categoryItem'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listForums_categoryItem($marker, $row, $this);
				}
			}

			$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

//			$forumlist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
//				'*',
//				'tx_mmforum_forums f',
//				'deleted = 0 AND hidden = 0 AND parentID = ' . $row['uid'] .
//					 $this->getStoragePIDQuery() .
//					 $this->getMayRead_forum_query('f'),
//				'',
//				'sorting ASC'
//			);
//
//			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forumlist)) {
			$parentID = $row['uid'];
			if (is_array($parentForums[$parentID])) {
				foreach ($parentForums[$parentID] as $row) {
					$forumId = intval($row['uid']);
					$template = $this->cObj->getSubpart($templateFile, '###LIST_FORUM###');

					$linkparams[$this->prefixId] = array(
						'action' => 'list_topic',
						'fid'    => $row['uid']
					);
					$marker['###FORUMNAME###'] = $this->pi_linkToPage($this->escape($row['forum_name']), $GLOBALS['TSFE']->id, '', $linkparams);
					$marker['###FORUMDESC###'] = $this->escape($row['forum_desc']);
					$marker['###THEMES###']    = ($row['forum_topics'] ? intval($row['forum_topics']) : '');
					$marker['###POSTS###']     = ($row['forum_posts']  ? intval($row['forum_posts'])  : '');
					$marker['###LASTPOSTS###'] = $this->getlastpost($row['forum_last_post_id'], $conf, true);
					$marker['###FORUMID###']   = 'f' . $forumId;
					$marker['###LIST_FORUM_EVENODD###'] = $this->conf['display.']['listItem.'][($i % 2 ? 'odd' : 'even' ) . 'Class'];
					$i++;
					$closed = (!$this->getMayWrite_forum($row));

					// If there is a user logged in, it is checked if
					//there are new posts since the last login.
	//				if ($feUserId) {
	//					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
	//						'*',
	//						'tx_mmforum_topics',
	//						'forum_id = ' . $forumId . $this->getStoragePIDQuery());
	//					$blnnew = false;
	//
	//					while ($row_topic = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
	//						if (in_array($row_topic['uid'], $readarray)) {
	//							$blnnew = true;
	//							break;
	//						}
	//					}
					if ($feUserId && in_array($row['uid'], $unreadarray)) {
						$marker['###READIMAGE###'] = $this->getForumIcon(null, $closed, true);
					} else {
						$marker['###READIMAGE###'] = $this->getForumIcon(null, $closed, false);
					}

					// Include hooks
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_forumItem'])) {
						foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_forumItem'] as $_classRef) {
							$_procObj = & t3lib_div::getUserObj($_classRef);
							$marker = $_procObj->listForums_forumItem($marker, $row, $this);
						}
					}
					$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
				}
			}

			$template = $this->cObj->getSubpart($templateFile, '###LIST_CAT_END###');
			$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		}

		$template  = $this->cObj->getSubpart($templateFile, '###LIST_FORUM_END###');
		$content  .= $this->cObj->substituteMarkerArray($template, $marker);

		if ($feUserId) {
			$template = $this->cObj->getSubpart($templateFile, '###LIST_FORUM_OPTIONS###');
			$marker['###LABEL_OPTIONS###'] = $this->pi_getLL('options');
			$content .= $this->cObj->substituteMarkerArray($template, $marker);
		}

		return $content;
	}

	/**
	 * Lists all topics in a certain board.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The content
	 */
	function list_topic($content, $conf) {
		$feUserId = intval(isset($GLOBALS['TSFE']->fe_user->user['uid']) ? $GLOBALS['TSFE']->fe_user->user['uid'] : 0);
		$forumId  = intval($this->piVars['fid']);
		$imgInfo = array(
			'border' => $conf['img_border'],
			'alt'    => '',
			'src'    => '',
			'style'  => ''
		);

		// Checking the forum for read access
		if (!$this->getMayRead_forum($forumId)) {
			$content .= $this->errorMessage($conf, $this->pi_getLL('board.noAccess'));
			return $content;
		}

		$templateFile = $this->cObj->fileResource($conf['template.']['list_topic']);
		$template     = $this->cObj->getSubpart($templateFile, '###TOPICBEGIN###');
		$marker       = array();

		// find out the unread posts since the last login

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'tx_mmforum_forums',
			'uid = ' . $forumId . $this->getStoragePIDQuery()
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) {
			$content .= $this->errorMessage($conf, $this->pi_getLL('board.noAccess'));
			return $content;
		}

		// load the forum details for the header
		list($rowForum) = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'tx_mmforum_forums',
			'uid = ' . $forumId . $this->getStoragePIDQuery()
		);

		if (empty($rowForum['grouprights_read'])) {
			tx_mmforum_rss::setHTMLHeadData('forum', $forumId);
		}

		if ($this->conf['disableRootline']) {
			$template = $this->cObj->substituteSubpart($template, '###ROOTLINE_CONTAINER###', '');
		} else {
			$marker['###FORUMPATH###'] = $this->get_forum_path($forumId, '');
		}

		$marker['###PAGETITLE###'] = $this->cObj->data['header'];
		$marker['###FORUMNAME###'] = $this->escape($rowForum['forum_name']);
		$marker['###FORUMDESC###'] = $this->escape($rowForum['forum_desc']);
		$marker['###FORUMICON###'] = $this->getForumIcon($rowForum, $this->getMayRead_forum($rowForum), FALSE);

		$linkParams[$this->prefixId] = array(
			'action'    => 'new_topic',
			'fid'       => $forumId
		);
		$imgInfo['src'] = $conf['path_img'] . $this->getLanguageFolder() . $conf['images.']['new_topic'];

		if ($this->getMayWrite_forum($forumId)) {
			$marker['###NEWTOPICLINK###'] = $this->createButton('newtopic', $linkParams);
		} else {
			$marker['###NEWTOPICLINK###'] = '';
		}

		$isTopicRating = $this->isTopicRating();
		if(!$isTopicRating) {
			$template = $this->cObj->substituteSubpart($template, '###SUBP_RATING_LABEL###', '');
		}

		$marker['###LABEL_TOPIC###']		= $this->pi_getLL('board.topic');
		$marker['###LABEL_REPLIES_HITS###']	= $this->pi_getLL('board.replies');
		$marker['###LABEL_AUTHOR###']		= $this->pi_getLL('board.author');
		$marker['###LABEL_LASTPOST###']		= $this->pi_getLL('board.lastPost');
		$marker['###LABEL_HIDESOLVED###']	= $this->pi_getLL('board.hideSolved');
		$marker['###LABEL_RATING###']		= $this->pi_getLL('board.rating');

		$limitcount = $conf['topic_count'];

		$marker['###MARKREAD###'] = $this->getMarkAllRead_link();
		// List of the pages as a page browser
		$marker['###PAGES###'] = $this->pagecount('tx_mmforum_topics', 'forum_id', $forumId, $limitcount);

		$marker['###HIDESOLVED_CHECKED###'] = ($this->piVars['hide_solved'] ? 'checked="checked"' : '');
		$marker['###SETTINGS_ACTION###'] = htmlspecialchars($this->tools->getAbsoluteUrl($this->pi_linkTP_keepPIvars_url(array('hide_solved'=>0))));

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_header'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_header'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->listTopics_header($marker, $this, $rowForum);
			}
		}

		$content .= $this->cObj->substituteMarkerArray($template, $marker);

		// load the posts that the user set as "favorites"
		if ($feUserId) {
			$userFav = $this->get_user_fav();
		}

		$currentPage = (intval($this->piVars['page']) > 0 ? intval($this->piVars['page']) : 0);
		if($this->conf['doNotUsePageBrowseExtension']) $currentPage ++;

		$limit = ($limitcount-1)*($currentPage) . ',' . $limitcount;

		$solvedCon = ($this->piVars['hide_solved'] ? ' AND t.solved=0 ' : '');
		$shadowCon = ($this->conf['enableShadows'] ? '' : ' AND t.shadow_tid=0 ');


		// load all the posts
		$topiclist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			't.*',
			'tx_mmforum_topics t, tx_mmforum_posts p',
			'p.uid = t.topic_last_post_id AND ' .
      't.deleted = 0 AND t.hidden = 0 AND t.forum_id = ' . $forumId .
				$solvedCon . $shadowCon . $this->getStoragePIDQuery('t'),
			'',
			't.at_top_flag DESC, p.post_time DESC',
			$limit
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($topiclist) > 0) {
			$template = $this->cObj->getSubpart($templateFile, '###LIST_TOPIC###');
		} else {
			$template = $this->cObj->getSubpart($templateFile, '###LIST_NOTOPIC###');
			$content .= $this->cObj->substituteMarker($template, '###LABEL_NOTOPICS###', $this->pi_getLL('topic.noTopicsFound'));
		}

		if(!$isTopicRating) $template = $this->cObj->substituteSubpart($template, '###SUBP_RATING###', '');

		$j = 1;
		$topics = array();
		$topicIds = array();

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($topiclist)) {
			$topics[] = $row;
			$topicIds[] = $row['uid'];
		}

		//get tuhe unread state _of only the 30 (or so) topics per page and not that _of all posts_
		$conf['topic_id'] = $topicIds;
		if($GLOBALS['TSFE']->fe_user->user['uid']) {
			$lastlogin = $GLOBALS['TSFE']->fe_user->user['tx_mmforum_prelogin'];
			$readarray = $this->getunreadposts($content, $conf, $lastlogin);
		}



			/*
			 * MAIN LOOP begin
			 */

		foreach ($topics as $row) {
			// Check if solved flag is set.
			$solved = '';
			$favorit = '';
			if ($row['solved'] == 1 && $this->conf['topicIconMode'] == 'classic') {
				$imgInfo['src']		= $conf['path_img'] . $conf['images.']['solved'];
				$imgInfo['alt']		= $this->pi_getLL('topic.isSolved');
				$imgInfo['title']	= $this->pi_getLL('topic.isSolved');
				$solved				= $this->buildImageTag($imgInfo);
			}

			// Check if the topic is favorite
			if (is_array($userFav) && in_array ($row['uid'], $userFav)) {
				$imgInfo['src']		= $conf['path_img'] . $conf['images.']['favorite'];
				$imgInfo['alt']		= $this->pi_getLL('topic.isFavorite');
				$imgInfo['title']	= $this->pi_getLL('topic.isFavorite');
				$favorit			= $this->buildImageTag($imgInfo);
			}

			$topic_is = ($row['topic_is'] ? $this->cObj->wrap($row['topic_is'], $this->conf['list_topics.']['prefix_wrap']) : '');

			if ($row['shadow_tid'] == 0) {
				$linkParams[$this->prefixId] = array(
					'action' => 'list_post',
					'tid'    => $row['uid']
				);
				if ($this->useRealUrl()) {
					$linkParams[$this->prefixId]['fid'] = $row['forum_id'];
				}
			} else {
				$linkParams[$this->prefixId] = array(
					'action' => 'list_post',
					'tid'    => $row['shadow_tid']
				);
				if ($this->useRealUrl()) {
					$linkParams[$this->prefixId]['fid'] = $row['shadow_fid'];
				}
				$topic_is = $this->cObj->wrap($this->pi_getLL('topic.shadow'), $this->conf['list_topics.']['prefix_wrap']);
			}

			$marker['###TOPICNAME###'] = $favorit . $topic_is .
				'<a href="' . htmlspecialchars($this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams)) . '" title="' . $this->escape($row['topic_title']) . '">'
				. $this->escape($this->cObj->stdWrap($row['topic_title'], $this->conf['list_topics.']['topicTitle_stdWrap.']))
				. '</a> ' . $solved;

			$page_link = '';
			$last_post_link = '';

			// Display page navigation below topic name, making it possible to jump to a page directly
			if (($row['topic_replies'] + 1) > $conf['post_limit']) {
				$page_link    = '( ' . $this->pi_getLL('page.goto') . ':';
				$menge        = $row['topic_replies'] + 1;
				$i            = 1;
				$pages = ceil($menge / $conf['post_limit']);

				$interval = ceil($pages / 10);

				for ($i = 0; $i < $pages; $i += $interval) {
					$linkParams[$this->prefixId] = array(
						'action'    => 'list_post',
						'tid'       => $row['uid'],
						'page'      => $i
					);
					if ($linkParams[$this->prefixId]['page'] < 1) {
            $linkParams[$this->prefixId]['page'] = '';
          }
					$page_link .= ' ' . $this->pi_linkToPage($i+1, $GLOBALS['TSFE']->id, '', $linkParams);

					if ($interval > 1) {
						if ($i == $interval+1) {
							$i--;
						}
					}

					if ($i == $pages) {
						break;
					}
					if ($i + $interval > $pages) {
						$i = $pages - $interval;
					}
				}
				$page_link  .= ' ) ';
				$marker['###TOPICNAME###'] .= $this->cObj->wrap($page_link, $this->conf['list_topics.']['pagenav_wrap']);
			}

			$linkParams[$this->prefixId]['pid'] = 'last';
			$imgInfo['src']		= $conf['path_img'] . $conf['images.']['jump_to'];
			$imgInfo['alt']		= $this->pi_getLL('topic.gotoLastPost');
			$imgInfo['title']	= $this->pi_getLL('topic.gotoLastPost');
			$last_post_link = $this->pi_linkToPage($this->buildImageTag($imgInfo), $GLOBALS['TSFE']->id, '', $linkParams);

			$replies = intval($row['topic_replies']);
			$marker['###HITS###']       		= ($row['shadow_tid'] == 0) ? $row['topic_views'] : '-';
			$marker['###POSTS###']      		= ($row['shadow_tid'] == 0) ? $replies : '';
			$marker['###POSTS_HITS###'] 		= ($row['shadow_tid'] == 0) ? $replies . ' ('.$row['topic_views'].')' : '';
			$marker['###AUTHOR###']     		= $this->getauthor($row['topic_poster']);
			$marker['###LAST###']               = $this->getlastpost($row['topic_last_post_id'], $conf) . ' ' . $last_post_link;
			$marker['###LIST_TOPIC_EVENODD###']	= $this->conf['display.']['listItem.'][($j++ % 2 ? 'odd' : 'even') . 'Class'];
			$marker['###RATING###']				= $isTopicRating ? $this->getRatingDisplay('tx_mmforum_topic', $row['uid']) : '';

			// display last answer (and a no-replies message if there is only the initial thread post)
			if ($replies > 0) {
				$marker['###LASTREPLY###'] = $this->getlastpost($row['topic_last_post_id'], $conf) . ' ' . $last_post_link;
			} else {
				$marker['###LASTREPLY###'] = $this->pi_getLL('topic.noreplies');
			}

			// Get topic icon
			$marker['###READIMAGE###'] = $this->getTopicIcon($row, $readarray);

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_topicItem'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_topicItem'] as $_classRef) {
					$_procObj = &t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listTopics_topicItem($marker, $row, $this);
				}
			}

			$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		}

			/*
			 * MAIN LOOP end
			 */



		$template = $this->cObj->getSubpart($templateFile, '###TOPICEND###');
		$marker['###PAGES###'] = $this->pagecount('tx_mmforum_topics', 'forum_id', $forumId, $limitcount);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_footer'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_footer'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->listTopics_footer($marker, $this, $rowForum);
			}
		}

		$content .= $this->cObj->substituteMarkerArray($template, $marker);

		// Added by Cyrill Helg
		if ($feUserId) {
			$template = $this->cObj->getSubpart($templateFile, '###LIST_POSTS_OPTIONEN###');

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid',
				'tx_mmforum_forummail',
				'user_id = ' . $feUserId . ' AND forum_id = ' . $forumId . $this->getStoragePIDQuery()
			);

			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) < 1) {
				$imgInfo['alt']		= $this->pi_getLL('topic.emailSubscr.off');
				$imgInfo['title']	= $this->pi_getLL('topic.emailSubscr.off');
				$imgInfo['src']		= $conf['path_img'].$conf['images.']['info_mail_off'];
				$linkParams[$this->prefixId] = array(
					'action' => 'set_havealookforum',
					'fid'    => $forumId
				);
				// if($this->useRealUrl()) $linkParams[$this->prefixId]['fid'] = $postforum;
				//TODO: This does not work yet
				$link = $this->pi_linkTP($this->pi_getLL('on'), $linkParams).' / <strong>' . $this->pi_getLL('off') . '</strong>';
			} else {
				$imgInfo['alt']		= $this->pi_getLL('topic.emailSubscr.on');
				$imgInfo['title']	= $this->pi_getLL('topic.emailSubscr.on');
				$imgInfo['src']		= $conf['path_img'].$conf['images.']['info_mail_on'];
				$linkParams[$this->prefixId] = array(
					'action' => 'del_havealookforum',
					'fid'    => $forumId
				);
				// if($this->useRealUrl()) $linkParams[$this->prefixId]['fid'] = $postforum;
				//TODO: This does not work yet
				$link = '<strong>' . $this->pi_getLL('on') . '</strong> / ' . $this->pi_linkTP($this->pi_getLL('off'), $linkParams);
			}

			$image = $this->buildImageTag($imgInfo);

			$image = $this->cObj->stdWrap($image, $this->conf['list_posts.']['optImgWrap.']);
			$link  = $this->cObj->stdWrap($link, $this->conf['list_posts.']['optLinkWrap.']);

			$marker['###POSTMAILLINK###']  = $this->cObj->stdWrap($image . $link, $this->conf['list_posts.']['optItemWrap.']);
			$marker['###LABEL_OPTIONS###'] = $this->pi_getLL('options_mail_forum');
			$content .= $this->cObj->substituteMarkerArray($template, $marker);
		}
		return $content;
	}

	/**
	 * Lists topics with a specific prefix.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  string $content The plugin content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The HTML content
	 */
	function list_prefix($content, $conf, $prefix) {

		// determine the selected prefix based on the allowed list and the disallowed list
		$prefixes = t3lib_div::trimExplode(',', $conf['prefixes']);
		$noListPrefixes = t3lib_div::trimExplode(',', $conf['noListPrefixes']);

		foreach ($prefixes as $sPrefix) {
			if (in_array($sPrefix, $noListPrefixes)) {
				continue;
			}
			if (strcasecmp($sPrefix, $prefix) == 0) {
				$realPrefix = $sPrefix;
				break;
			}
		}

		if (!isset($realPrefix)) {
			list($realPrefix) = array_diff($prefixes, $noListPrefixes);
			$prefix           = $realPrefix;
		}
		$prefix = $GLOBALS['TYPO3_DB']->quoteStr($prefix, '');
		$templateFile = $this->cObj->fileResource($conf['template.']['list_topic']);
		$template     = $this->cObj->getSubpart($templateFile, '###PREFIX_SETTINGS###');
		$marker = array(
			'###ACTION###'                  => $this->tools->getAbsoluteUrl($this->pi_linkTP_keepPIvars_url()),
			'###CATEGORIES###'              => '<option value="all">' . $this->pi_getLL('prefix.all') . '</option>',
			'###ORDER_LASTPOST###'          => '',
			'###ORDER_CATEGORY###'          => '',
			'###ORDER_CRDATE###'            => '',

			'###LABEL_DISPLAYSETTINGS###'   => $this->pi_getLL('prefix.displaySettings'),
			'###LABEL_DISPLAY###'           => $this->pi_getLL('prefix.display'),
			'###LABEL_ORDERBY###'           => $this->pi_getLL('prefix.orderby'),
			'###LABEL_ORDERBY_LASTPOST###'  => $this->pi_getLL('prefix.orderby.lastPost'),
			'###LABEL_ORDERBY_CATEGORY###'  => $this->pi_getLL('prefix.orderby.category'),
			'###LABEL_ORDERBY_CRDATE###'    => $this->pi_getLL('prefix.orderby.crdate'),
			'###LABEL_LIMIT###'             => $this->pi_getLL('prefix.limit'),
			'###LABEL_LIMIT_10###'          => $this->pi_getLL('prefix.limit.10'),
			'###LABEL_LIMIT_20###'          => $this->pi_getLL('prefix.limit.20'),
			'###LABEL_LIMIT_50###'          => $this->pi_getLL('prefix.limit.50'),
			'###LABEL_LIMIT_100###'         => $this->pi_getLL('prefix.limit.100'),
			'###LABEL_LIMIT_ALL###'         => $this->pi_getLL('prefix.limit.all'),
			'###LABEL_OUTOFCATEGORY###'		=> $this->pi_getLL('prefix.outOfCategory'),

			'###LIMIT_10###'                => '',
			'###LIMIT_20###'                => '',
			'###LIMIT_50###'                => '',
			'###LIMIT_100###'               => '',
			'###LIMIT_ALL###'               => '',
		);

		$page = ($this->piVars['page'] ? $this->piVars['page'] : 0);

		// Evaluate settings
		$settings = (is_array($this->piVars['list_prefix']) ? $this->piVars['list_prefix'] : array('order' => 'lastpost', 'show' => 'all'));
		if (!isset($settings['order'])) {
			$settings['order'] = 'lastpost';
		}
		if (!isset($settings['show'])) {
			$settings['show']  = 'all';
		}
		switch ($settings['order']) {
			case 'lastpost':
				$order = 'topic_last_post_id DESC';
			break;
			case 'category':
				$order = 'c.sorting ASC, f.sorting ASC, topic_last_post_id DESC';
			break;
			case 'crdate':
				$order = 'topic_time DESC';
			break;
			case 'author':
				$order = 'u.name ASC, topic_time DESC';
			break;
			default:
				if ($settings['order']) {
					$order = $GLOBALS['TYPO3_DB']->quoteStr($settings['order'], 'tx_mmforum_topics') . ' DESC';
				} else {
					$order = 'topic_last_post_id DESC';
				}
			break;
		}
		$marker['###ORDER_' . strtoupper($settings['order']) . '###'] = 'selected="selected"';

		$addWhere = '';
		if ($settings['show'] != 'all') {
			$limitToBoardOrCat = explode('_', $settings['show']);
			if (count($limitToBoardOrCat) == 2
				&& ($limitToBoardOrCat[0] == 'f' || $limitToBoardOrCat[0] == 'c')
				&& (intval($limitToBoardOrCat[1]) > 0)) {
				$addWhere = 'AND ' . $limitToBoardOrCat[0] . '.uid = ' . intval($limitToBoardOrCat[1]);
			}
		}


		$selectedCategory = intval($this->piVars['cid']);
		$selectedBoard    = intval($this->piVars['fid']);
		if ($selectedCategory) {
			$addWhere .= ' AND c.uid = ' . $selectedCategory;
		}
		if ($selectedBoard) {
			$addWhere .= ' AND f.uid = ' . $selectedBoard;
		}

		if ($prefix && $this->piVars['list_prefix']['prfx']) {
			$addWhere .= ' AND t.topic_is LIKE "' . $prefix . '"';
		}

		$settings['limit'] = intval($settings['limit'] ? $settings['limit'] : 20);
		if ($settings['limit'] == 0) {
			$settings['limit'] = 20;
		}

		if ($settings['limit'] == 'all') {
			$limit = '';
		} else {
			$limit = (($page)*$settings['limit']) . ', ' . $settings['limit'];
		}

		// Set limitation markers for settings form
		$marker['###LIMIT_' . strtoupper($settings['limit']) . '###'] = 'selected="selected"';


		// Detect the number of topics with a certain prefix from the DB
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			't.uid',
			'tx_mmforum_topics t, tx_mmforum_forums f, tx_mmforum_forums c, fe_users u',
			't.deleted = 0 AND t.hidden = 0 AND
				f.uid = t.forum_id AND c.uid = f.parentID AND t.topic_poster = u.uid' .
				$addWhere . $this->getStoragePIDQuery('t,f,c') .
				$this->getMayRead_forum_query('c') .
				$this->getMayRead_forum_query('f') .
				$this->getCategoryLimit_query('c'),
			'',
			$order
		);
		$numTopicsTotal = $GLOBALS['TYPO3_DB']->sql_num_rows($res);

		// Load topics with a certain prefix from the DB
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			't.*, f.forum_name, c.forum_name as cat_title',
			'tx_mmforum_topics t, tx_mmforum_forums f, tx_mmforum_forums c, fe_users u',
			't.deleted = 0 AND t.hidden = 0 AND
				f.uid = t.forum_id AND c.uid = f.parentID AND t.topic_poster = u.uid ' .
				$addWhere . $this->getStoragePIDQuery('t,c,f') .
				$this->getMayRead_forum_query('c') .
				$this->getMayRead_forum_query('f') .
				$this->getCategoryLimit_query('c'),
			'',
			$order,
			$limit
		);

		// Fill prefix select field in settings form
		foreach ($prefixes as $sPrefix) {
			if (in_array($sPrefix, $noListPrefixes)) {
				continue;
			}
			$selected = ((strcasecmp($sPrefix, $prefix) == 0) ? 'selected="selected"' : '');
			$marker['###PREFIXES###'] .= '<option value="'.strtolower($sPrefix).'" '.$selected.'>'.$sPrefix.'</option>';
		}

		// Fill category/board select field in settings form
		$cres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tx_mmforum_forums f',
			'deleted = 0 AND hidden = 0 ' .
				$this->getStoragePIDQuery().
				$this->getMayRead_forum_query('f').
				$this->getCategoryLimit_query(),
			'',
			'parentID ASC, sorting ASC'
		);

		// load all data from the categories and boards in a multi-dimensional array
		$forumList = array();
		while ($forum = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($cres)) {
			if ($forum['parentID'] == 0) {
				// we need to have an associative array to preserve sorting
				$forumList['c_' . $forum['uid']] = $forum;
			} else {
				$forumList['c_' . $forum['parentID']]['boards']['f_' . $forum['uid']] = $forum;
			}
		}

		// generate the select HTML tag
		foreach ($forumList as $catValue => $cat) {
			$sel = ($catValue == $settings['show']) ? 'selected="selected"' : '';
			$marker['###CATEGORIES###'] .= '
				<optgroup label="'.$this->escape($cat['forum_name']).'">
				<option value="' . $catValue . '" ' . $sel . '>' . $this->pi_getLL('prefix.categories.all') . '</option>
			';

			if (is_array($cat['boards'])) {
				foreach ($cat['boards'] as $forumValue => $forum) {
					$title = t3lib_div::fixed_lgd_cs($forum['forum_name'], 50);
					$sel = ($forumValue == $settings['show'] ? 'selected="selected"' : '');
					$marker['###CATEGORIES###'] .= '<option value="' . $forumValue . '" ' . $sel . '>' . $this->escape($title) . '</option>';
				}
			}
			$marker['###CATEGORIES###'] .= '</optgroup>';
		}

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_header'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_header'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->listPrefix_header($marker, $this, $forumList);
			}
		}

		// add the header part to the content
		$content .= $this->cObj->substituteMarkerArray($template, $marker);


		// Start output of main table
		$template       = $this->cObj->getSubpart($templateFile, '###TOPICBEGIN###');
		$pagecount      = ($settings['limit'] != 'all') ? $this->pagecount('tx_mmforum_topics', 'topic_is', 'HowTo', $settings['limit'], $numTopicsTotal) : '';

		$forumLink      = $this->pi_linkToPage($this->pi_getLL('board.rootline'),  $this->getForumPID());
		$prefixParams   = array('tx_mmforum_pi1[action]' => 'list_prefix');
		$prefixRootline = $this->cObj->substituteMarker($this->pi_getLL('prefix.rootline'), '###PREFIX###', $realPrefix);
		$prefixLink     = $this->pi_linkTP($prefixRootline, $prefixParams);

		$marker = array(
			'###FORUMICON###'          => $this->getForumIcon(), 
			'###FORUMPATH###'          => $forumLink . ' &raquo; ' . $prefixLink,
			'###NEWTOPICLINK###'       => '',
			'###FORUMNAME###'          => $this->cObj->substituteMarker($this->pi_getLL('prefix.title'), '###PREFIX###', $realPrefix),
			'###PAGES###'              => $pagecount,
			'###LABEL_TOPIC###'        => $this->pi_getLL('board.topic'),
			'###LABEL_REPLIES_HITS###' => $this->pi_getLL('board.replies'),
			'###LABEL_AUTHOR###'       => $this->pi_getLL('board.author'),
			'###LABEL_LASTPOST###'     => $this->pi_getLL('board.lastPost'),
			'###LABEL_RATING###'       => $this->pi_getLL('board.rating')
		);

		$template = $this->cObj->substituteSubpart($template, '###SETTINGS###', '');

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_settings'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_settings'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->listPrefix_settings($marker, $this);
			}
		}
		// add the settings part to the template
		$content .= $this->cObj->substituteMarkerArray($template, $marker);


		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
			$template = $this->cObj->getSubpart($templateFile, '###LIST_TOPIC###');
		} else {
			$template = $this->cObj->getSubpart($templateFile, '###LIST_NOTOPIC###');
			$content .= $this->cObj->substituteMarker($template, '###LABEL_NOTOPICS###', $this->pi_getLL('topic.noTopicsFound'));
		}

		// Load already read topics from database
		if ($GLOBALS['TSFE']->fe_user->user['uid']) {
			list($rowUnread) = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'tx_mmforum_prelogin as lastlogin',
				'fe_users',
				'uid = ' . intval($GLOBALS['TSFE']->fe_user->user['uid'])
			);
			$readarray = $this->getunreadposts($content, $conf, $rowUnread['lastlogin']);
		}

		// Output topics
		while ($topicRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$topicTitle = stripslashes(htmlspecialchars($topicRow['topic_title']));

			$linkParams[$this->prefixId] = array(
				'action'  => 'list_post',
				'tid'     => $topicRow['uid'],
				'prefix'  => $topicRow['topic_is']
			);

			$imgInfo = array(
				'src' => $conf['path_img'] . $conf['images.']['jump_to'],
				'alt' => $this->pi_getLL('topic.lastarticle'),
				'title' => $this->pi_getLL('topic.lastarticle'),
			);

			$lastPostParams[$this->prefixId] = array(
				'action' => 'list_post',
				'tid'    => $topicRow['uid'],
				'prefix' => $topicRow['topic_is'],
				'pid'    => 'last'
			);

			$lastPostURL = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $lastPostParams);
			$lastPostLink = '<a href="'.$lastPostURL.'" title="'.$topicTitle.'">' . $this->buildImageTag($imgInfo) . '</a>';

			$marker = array(
				'###TOPICNAME###'   => $this->pi_linkToPage($this->escape($topicTitle), $GLOBALS['TSFE']->id, '', $linkParams),
				'###POSTS###'       => intval($topicRow['topic_replies']),
				'###HITS###'        => intval($topicRow['topic_views']),
				'###AUTHOR###'      => $this->getauthor($topicRow['topic_poster']),
				'###DATE###'        => $this->formatDate($topicRow['topic_time']),
				'###LAST###'		=> $this->getlastpost($topicRow['topic_last_post_id'], $conf).' '.$lastPostLink,
				'###POSTS_HITS###'  => intval($topicRow['topic_replies']) .' ('. intval($topicRow['topic_views']) . ')',
				'###RATING###'      => $this->getRatingDisplay('tx_mmforum_topic', $topicRow['uid'])
			);
			$location = $topicRow['cat_title'] . ' / ' . $topicRow['forum_name'];
			$postLimit = intval($conf['post_limit']);
			if ($topicRow['topic_replies'] >= $postLimit) {
				$page_link = '( ' . $this->pi_getLL('page.goto') . ':';
				$topicPostsLeft = $topicRow['topic_replies'];
				$i = 0;

				$linkParams[$this->prefixId] = array(
					'action'  => 'list_post',
					'tid'     => $topicRow['uid']
				);

				while ($topicPostsLeft >= 0) {
					$i++;
					$linkParams[$this->prefixId]['page'] = $i;
					if ($linkParams[$this->prefixId]['page'] < 1) {
            $linkParams[$this->prefixId]['page'] = '';
          }
					$page_link .= ' '.$this->pi_linkToPage($i, $GLOBALS['TSFE']->id, '', $linkParams) . ' ';
					$topicPostsLeft -= $postLimit;
				}
				$page_link  .= ' ) ';
				$marker['###TOPICNAME###']  .= '<div class="tx-mmforum-pi1-listtopic-pages">' . $page_link . ' <span style="font-weight: normal;">' . $this->escape($location) . '</span></div>';
			} else {
				$marker['###TOPICNAME###']  .= '<div class="tx-mmforum-pi1-listtopic-location">' . $this->escape($location) . '</div>';
			}

			// Get topic icon
			$marker['###READIMAGE###'] = $this->getTopicIcon($topicRow);

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_topicItem'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_topicItem'] as $_classRef) {
					$_procObj = &t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listPrefix_topicItem($marker, $topicRow, $this);
				}
			}

			$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		}


		// render the footer part
		$template = $this->cObj->getSubpart($templateFile, '###TOPICEND###');
		$marker = array('###PAGES###' => $pagecount);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_footer'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_footer'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->listPrefix_footer($marker, $this);
			}
		}

		$content .= $this->cObj->substituteMarkerArray($template, $marker);
		return $content;
	}

	/**
	 * Displays a list containing a list of the latest posts, meaning the
	 * topics that was last written in.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-05-23
	 * @return  string	The latest topic list
	 */
	function list_latest($conf) {
		$templateFile = $this->cObj->fileResource($this->conf['template.']['latest']);
		$template     = $this->cObj->getSubpart($templateFile, '###LATEST###');
		$templateRow  = $this->cObj->getSubpart($template, '###LATEST_POST###');

		$limit = $this->conf['listLatest.']['limit'];

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			't.topic_last_post_id as post_id, t.uid as topic_id, t.*,
				f.uid as forum_id, f.forum_name as forum_name,
				c.forum_name as category_name,
				p.poster_id as author, p.post_time',
			'tx_mmforum_posts p, tx_mmforum_forums f, tx_mmforum_forums c, tx_mmforum_topics t',
			't.uid = p.topic_id AND
			  p.uid = t.topic_last_post_id AND
				f.uid = p.forum_id AND
				c.uid = f.parentID AND
				p.deleted = 0 AND t.deleted = 0 AND f.deleted = 0 AND c.deleted = 0 AND
				p.hidden  = 0 AND t.hidden  = 0 AND f.hidden  = 0 AND c.hidden  = 0 '.
				$this->getMayRead_forum_query('f').
				$this->getMayRead_forum_query('c').
				$this->getCategoryLimit_query('c'),
			'p.topic_id',
			'p.post_time DESC',
			$limit
		);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$linkParams[$this->prefixId] = array(
				'action' => 'list_post',
				'tid'    => $row['topic_id']
			);
			if ($this->useRealUrl()) {
				$linkParams[$this->prefixId]['fid'] = $row['forum_id'];
			}

			If($this->conf['listLatest.']['linkToLatestPost'])
				$linkParams[$this->prefixId]['pid'] = 'last';

			$row['topic_title'] = stripslashes($row['topic_title']);

			$row['topic_title'] = str_replace('<','&lt;',$row['topic_title']);
			$row['topic_title'] = str_replace('>','&gt;',$row['topic_title']);

			$rMarker = array(
				'###TOPICNAME###'   => $this->pi_linkToPage($this->escape($row['topic_title']), $this->getForumPID(), '', $linkParams),
				'###TOPICSUB###'    => $this->escape($row['category_name'] . ' / '.  $row['forum_name']),
				'###TOPICICON###'   => $this->getTopicIcon($row),
				'###TOPICAUTHOR###' => $this->getauthor($row['author']),
				'###NUMPOSTS###'    => $row['topic_replies'],
				'###TOPICFORUM###'  => $this->escape($row['category_name']),
				'###TOPICDATE###'   => date('d. m. Y, H:i', $row['post_time'])
			);
			$linkParams[$this->prefixId]['pid'] = 'last';
			$imgInfo['src']		= $conf['path_img'] . $conf['images.']['jump_to'];
			$imgInfo['alt']		= $this->pi_getLL('topic.gotoLastPost');
			$imgInfo['title']	= $this->pi_getLL('topic.gotoLastPost');
			$last_post_link = $this->pi_linkToPage($this->buildImageTag($imgInfo), $this->getForumPID(), '', $linkParams);
			$rMarker['###LASTPOST###'] = $this->getlastpost($row['post_id'], $this->conf).' '.$last_post_link;

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_topicItem'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_topicItem'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$rMarker  = $_procObj->listLatest_topicItem($rMarker, $row, $this);
				}
			}
			$rowContent .= $this->cObj->substituteMarkerArrayCached($templateRow, $rMarker);
		}
		$template = $this->cObj->substituteSubpart($template, '###LATEST_POST###', $rowContent);

		$marker = array(
			'###FORUMNAME###'      => $this->pi_getLL('latest.title'),
			'###LABEL_TOPIC###'    => $this->pi_getLL('board.topic'),
			'###LABEL_LASTPOST###' => $this->pi_getLL('board.lastPost')
		);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_allMarkers'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_allMarkers'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$marker   = $_procObj->listLatest_allMarkers($marker, $this);
			}
		}

		return $this->cObj->substituteMarkerArray($template, $marker);
	}

    /**
     * Displays a list of all users registered in the forum.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-09-29
     * @return  string The user list
     */
    function list_users() {
        $list_fields    = $this->userlist_fields;
        $list_count     = $this->userlist_limit;

        $list_fields    = t3lib_div::trimExplode(',',$list_fields);

        $template       = $this->cObj->fileResource($this->conf['template.']['userlist']);
        $template       = $this->cObj->getSubpart($template, '###USERLIST###');

        $template_th    = $this->cObj->getSubpart($template, '###USERLIST_TH###');
        $template_row   = $this->cObj->getSubpart($template, '###USERLIST_ROW###');
        $template_cell  = $this->cObj->getSubpart($template, '###USERLIST_CELL###');

        $userfields     = array();

        $sorting        = $this->piVars['sorting'];
        if(!$sorting)
            $sorting    = $sorting = $this->getUserNameField();

        if(!in_array($sorting,$list_fields)) $sorting = $this->getUserNameField();
        if(!in_array($sorting,$list_fields)) $sorting = $list_fields[0];

        $sorting_mode   = $this->piVars['sorting_mode'];
        if(!$sorting_mode)
            $sorting_mode = $sorting_mode = 'ASC';

        $sorting_mode   = strtoupper($sorting_mode);

        if(!in_array($sorting_mode,array('ASC','DESC'))) $sorting_mode = 'ASC';

			/* Instantiate user management library */
		$this->userLib = t3lib_div::makeInstance('tx_mmforum_usermanagement');

		$userField = t3lib_div::makeInstance('tx_mmforum_userfield');
		$userField->init($this->userLib, $this->cObj);

        foreach($list_fields as $field) {

            if(intval($field)>0) {
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    '*',
                    'tx_mmforum_userfields',
                    'uid='.$field.' AND deleted=0'
                );
				if($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) continue;

                $arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
				$userField->get($arr);

                /*if(strlen($arr['config'])>0) {
                    $parser->parse($arr['config']);
                    $config = $parser->setup;
                } else $config = array();

                if($config['label']) $label = $this->cObj->cObjGetSingle($config['label'],$config['label.']);
                else $label = $arr['label'];

                if($config['output']) {
                    $userfields[$arr['uid']]    = $arr;
                }*/

				$label = $userField->getRenderedLabel();

				$userfields_config[$arr['uid']] = $userField->conf;
            }
            else {
                $label = $this->pi_getLL('userlist.fields.'.$field);
                if(!$label) $label = $field;
            }

            if($sorting == $field) {
                if($sorting_mode == 'DESC') {
                    $label = '&#x25BC;&nbsp;'.$label;
                    $linkParams[$this->prefixId] = array(
                        'sorting'       => $field,
                        'sorting_mode'  => 'ASC'
                    );
                }
                if($sorting_mode == 'ASC') {
                    $label = '&#x25B2;&nbsp;'.$label;
                    $linkParams[$this->prefixId] = array(
                        'sorting'       => $field,
                        'sorting_mode'  => 'DESC'
                    );
                }
            } else {
                $linkParams[$this->prefixId] = array(
                    'sorting'       => $field,
                    'sorting_mode'  => 'ASC'
                );
            }
            if(substr($field,0,2)!='__')
                $label = $this->pi_linkTP($label,$linkParams);

            $thMarker = array(
                '###COLUMN_TITLE###'        => $label
            );
            $content_th     .= $this->cObj->substituteMarkerArray($template_th,$thMarker);
        }
        $template       = $this->cObj->substituteSubpart($template, '###USERLIST_TH###', $content_th);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'COUNT(*)',
            'fe_users',
            '1=1'.$this->cObj->enableFields('fe_users').$this->getUserPidQuery()
        );
        list($user_num) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        $page_max   = ceil($user_num / $list_count);
        $page_cur   = intval($this->piVars['page'])>0?$this->piVars['page']:0;

		if (intval($this->conf['doNotUsePageBrowseExtension'])===0) {
			$page_menu = $this->getListGetPageBrowser($page_max);
		} else {
			$page_menu = $this->dynamicPageNav($page_max,'page',array('sorting'=>$sorting,'sorting_mode'=>$sorting_mode));
		}

        $marker		= array(
			'###PAGES###'	=> $page_menu,
			'###LLL_USERLIST_TITLE###'		=> $this->pi_getLL('userlist-title'),
			'###USERLIST_COLUMNCOUNT###'	=> count($list_fields)
		);

        $offset     = (($page_cur)*$list_count);
        $limit      = $offset.','.$list_count;

		if(is_numeric($sorting)) {
			if($userfields_config[$sorting]['datasource'])
				$sorting = $userfields_config[$sorting]['datasource'];
		}

        if(!is_numeric($sorting)) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'fe_users',
                '1=1'.$this->cObj->enableFields('fe_users').$this->getUserPidQuery(),
                '',
                $sorting.' '.$sorting_mode,
                $limit
            );
            while($user = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
                $userResult[] = $user;
        } else {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'fe_users',
                '1=1'.$this->cObj->enableFields('fe_users').$this->getUserPidQuery()
            );
            while($user = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'field_value',
                    'tx_mmforum_userfields_contents',
                    'field_id='.$sorting.' AND deleted=0 AND user_id='.$user['uid']
                );
                if($GLOBALS['TYPO3_DB']->sql_num_rows($res2)==0) $user['__userdef'] = '';
                else {
                    list($userdef) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res2);
                    $user['__userdef'] = $userdef;
                }

                $users[] = $user;
            }

            function userdef_cmp($a,$b) {
                return strcmp($a['__userdef'],$b['__userdef']);
            }

            usort($users,'userdef_cmp');
            if($sorting_mode == 'DESC') $users = array_reverse($users);

            $userResult = array_slice($users,$offset,$list_count);
        }

        $parser  = t3lib_div::makeInstance('t3lib_TSparser');

        $i = 0;

        foreach($userResult as $user) {
            $user_row = $template_row;
            $user_fields = '';

            $user_row_class = ($i%2)?$this->conf['display.']['listItem.']['oddClass']:$this->conf['display.']['listItem.']['evenClass'];
            $user_row = $this->cObj->substituteMarker($user_row, '###USERLIST_ROW_EVENODD###', $user_row_class);

			$i ++;

            foreach($list_fields as $field) {
                if(intval($field)>0) {
                    $res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                        'f.config,c.field_value',
                        'tx_mmforum_userfields f LEFT JOIN tx_mmforum_userfields_contents c ON c.field_id = f.uid',
                        'f.hidden=0 AND f.deleted=0 AND (c.deleted=0 OR c.deleted IS NULL) AND f.uid='.$field.' AND (c.user_id IS NULL OR c.user_id='.$user['uid'].')',
                        '',
                        'f.sorting DESC'
                    );
                    $arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res2);

					unset($parser->setup);

                    if(strlen($arr['config'])>0) {
                        $parser->parse($arr['config']);
                        $config = $parser->setup;
                    } else $config = array();

                    if($config['datasource']) {
						$arr['field_value'] = $user[$config['datasource']];
                    }

                    if($config['output']) {
                        $tmpArr = $this->cObj->data;
                        $this->cObj->data = array(
                            'fieldvalue'    => $arr['field_value']
                        );
                        $fieldcontent = $this->cObj->cObjGetSingle($config['output'],$config['output.']);
                        $this->cObj->data = $tmpArr;
                    }
                    else $fieldcontent = $arr['field_value'];
                }
                else {
                    $user['fieldname']  = $field;
                    $user['fieldvalue'] = $user[$field];
                    $tmpData = $this->cObj->data;
                    $this->cObj->data = $user;
                    $fieldcontent = $this->cObj->cObjGetSingle($this->conf['userlist_item'],$this->conf['userlist_item.']);
                    $this->cObj->data = $tmpData;
                }

                $cellMarker = array(
                    '###CONTENT###'     => $fieldcontent
                );
                // Include hooks
                    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_userCell'])) {
                        foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_userCell'] as $_classRef) {
                            $_procObj = & t3lib_div::getUserObj($_classRef);
                            $cellMarker = $_procObj->listLatest_userCell($cellMarker, $user, $field, $this);
                        }
                    }
                $user_fields .= $this->cObj->substituteMarkerArray($template_cell,$cellMarker);
            }

            // Include hooks
                if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_preUserRow'])) {
                    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_preUserRow'] as $_classRef) {
                        $_procObj = & t3lib_div::getUserObj($_classRef);
                        $user_row = $_procObj->listLatest_preUserRow($user_row, $user, $this);
                    }
                }

            $user_row = $this->cObj->substituteSubpart($user_row, '###USERLIST_CELL###', $user_fields);

            // Include hooks
                if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_postUserRow'])) {
                    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_postUserRow'] as $_classRef) {
                        $_procObj = & t3lib_div::getUserObj($_classRef);
                        $user_row .= $_procObj->listLatest_postUserRow($user_row, $user, $this);
                    }
                }

            $user_rows .= $user_row;
        }
        $template = $this->cObj->substituteSubpart($template, '###USERLIST_ROW###', $user_rows);
        $template = $this->cObj->substituteMarkerArray($template, $marker);

        return $template;
    }

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function list_postqueue() {
		$postqueue = t3lib_div::makeInstance('tx_mmforum_postqueue');
		return $postqueue->main($this->conf, $this);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function list_rss() {
		$rss = t3lib_div::makeInstance('tx_mmforum_rss');
		return $rss->main($this->conf, $this);
	}

		/**
		 *
		 * Displays a frontend administration form.
		 *
		 * @author Martin Helmich <m.helmich@mittwald.de>
		 * @since  2010-04-06
		 * @return string HTML content
		 *
		 */

	Function frontendAdministration() {
		Require_Once(t3lib_extMgm::extPath('mm_forum').'pi1/feadmin/class.tx_mmforum_frontendadministration.php');
		$frontendAdministration = t3lib_div::makeInstance('tx_mmforum_FrontendAdministration');
		Return $frontendAdministration->main($this->conf, $this);
	}





		/*
		 * Forum content management functions
		 */





		/**
		 * Displays the form for creating a new topic.
		 * @param  string $content The plugin content
		 * @param  array  $conf    The plugin's configuration vars
		 * @return string          The content
		 */
	function new_topic($content, $conf) {

		// Check if there is a user logged in
		$feUserId = intval(isset($GLOBALS['TSFE']->fe_user->user['uid']) ? $GLOBALS['TSFE']->fe_user->user['uid'] : 0);
		$forumId  = intval($this->piVars['fid']);

		if ($feUserId) {
			// Check write permissions for the forum
			if (!$this->getMayWrite_forum($forumId)) {
				return $content . $this->errorMessage($conf, $this->pi_getLL('newTopic.noAccess'));
			}

			if ($this->piVars['button'] == $this->pi_getLL('newTopic.save')) {
				$errorFound = false;
				if (!$this->piVars['topicname']) {
					$errorFound = true;
					$errorMessage .= '<div>' . $this->pi_getLL('newTopic.noTitle') . '</div>';
				}
				if (!$this->piVars['message']) {
					$errorFound = true;
					$errorMessage .= '<div>' . $this->pi_getLL('newTopic.noText') . '</div>';
				}

				if ($errorFound) {
					$content .= $this->errorMessage($conf, $errorMessage);
					unset($this->piVars['button']);
					return $this->new_topic($content, $conf);
				}

				// Create postfactory object
				$postfactory = t3lib_div::makeInstance('tx_mmforum_postfactory');
				$postfactory->init($this->conf,$this);

				// Create poll
				if ($this->piVars['enable_poll'] == '1') {
					$pollObj = t3lib_div::makeInstance('tx_mmforum_polls');
					$poll_id = $pollObj->createPoll($this->piVars['poll'], $this);
					if (!is_numeric($poll_id)) {
						$content .= $this->errorMessage($this->conf, $poll_id);
						unset($this->piVars['button']);
						return $this->new_topic($content, $conf);
					}
				} else {
					$poll_id = 0;
				}

				// Check file upload
				if ($_FILES['tx_mmforum_pi1_attachment_1']['size'] > 0) {
					$res = $this->performAttachmentUpload();
					if (!is_array($res)) {
						$content .= $res;
						unset($this->piVars['button']);
						return $this->new_topic($content, $conf);
					}
					else {
						$attachment_ids = $res;
					}
				} else {
					$attachment_ids = array();
				}

				if ($this->isModeratedForum() && !$this->getIsAdmin() && !$this->getIsMod($forumId)) {

					// Create topic using postfactory
					$postfactory->create_topic_queue(
						$forumId,
						$GLOBALS['TSFE']->fe_user->user['uid'],
						$this->piVars['topicname'],
						$this->piVars['message'],
						time(),
						$this->tools->ip2hex(t3lib_div::getIndpEnv('REMOTE_ADDR')),
						$attachment_ids,
						$poll_id
					);

					return $this->successMessage($conf, $this->pi_getLL('postqueue-success'));
				} else {

					// Create topic using postfactory
					$topic_uid = $postfactory->create_topic(
						$forumId,
						$GLOBALS['TSFE']->fe_user->user['uid'],
						$this->piVars['topicname'],
						$this->piVars['message'],
						time(),
						$this->tools->ip2hex(t3lib_div::getIndpEnv('REMOTE_ADDR')),
						$attachment_ids,
						$poll_id,
						$this->piVars['havealook'] == 'havealook'
					);

					// Redirect to new topic
					$linkParams[$this->prefixId] = array(
						'action'  => 'list_post',
						'tid'     => $topic_uid,
					);
					if ($this->useRealUrl()) {
						$linkParams[$this->prefixId]['fid'] = $forumId;
					}
					$link = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams);
					$link = $this->tools->getAbsoluteUrl($link);
					header('Location: ' . t3lib_div::locationHeaderUrl($link . '#pid' . $postid));
					exit();
				}
			} else {
				if ($this->piVars['button'] == $this->pi_getLL('newTopic.preview')) {
					if ($this->piVars['enable_poll'] == '1') {
						$pollObj = t3lib_div::makeInstance('tx_mmforum_polls');
						$content .= $pollObj->displayPreview($this->piVars['poll'], $this);
					}

					$template = $this->cObj->fileResource($conf['template.']['list_post']);
					$template = $this->cObj->getSubpart($template, '###LIST_POSTS###');
					$template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_SECTION###', '');

                    $userSignature = tx_mmforum_postfunctions::marker_getUserSignature($GLOBALS['TSFE']->fe_user->user);

					$posttext = $this->piVars['message'];
					$postold  = $posttext;
					$posttext = $this->bb2text($posttext, $conf) . ($this->conf['list_posts.']['appendSignatureToPostText'] ? $userSignature : '');

					$marker['###POSTOPTIONS###'] = '';
					$marker['###POSTMENU###']    = '';
					$marker['###MESSAGEMENU###'] = '';
					$marker['###PROFILEMENU###'] = '';
					$marker['###POSTUSER###']    = $this->ident_user($GLOBALS['TSFE']->fe_user->user['uid'], $conf);
					$marker['###POSTTEXT###']    = $posttext;
					$marker['###ANKER###']       = '';
					$marker['###POSTANCHOR###']	 = '';
					$marker['###POSTDATE###']    = $this->pi_getLL('post.writtenOn') . ': ' . $this->formatDate(time());
					$marker['###POSTRATING###']  = '';

					// Include hooks
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newTopic_INpreview'])) {
						foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newTopic_INpreview'] as $_classRef) {
							$_procObj = & t3lib_div::getUserObj($_classRef);
							$marker = $_procObj->newTopic_INpreview($marker, $this);
						}
					}

					$previewTemplate = $this->cObj->fileResource($conf['template.']['new_topic']);
					$previewTemplate = $this->cObj->getSubpart($previewTemplate, '###PREVIEW###');
					$previewTemplate = $this->cObj->substituteSubpart($previewTemplate, '###HEADER_SUBPART###', '');
					$previewMarker = array(
						'###TOPIC_TITLE###'   => $this->escape($this->piVars['topicname']),
						'###LABEL_PREVIEW###' => $this->pi_getLL('newTopic.preview'),
						'###PREVIEW_POST###'  => $this->cObj->substituteMarkerArrayCached($template, $marker)
					);

					// Include hooks
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newTopic_preview'])) {
						foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newTopic_preview'] as $_classRef) {
							$_procObj = & t3lib_div::getUserObj($_classRef);
							$previewMarker = $_procObj->newTopic_preview($previewMarker, $this);
						}
					}

					$previewContent = $this->cObj->substituteMarkerArrayCached($previewTemplate, $previewMarker);
				}

				$template = $this->cObj->fileResource($conf['template.']['new_topic']);
				$template = $this->cObj->getSubpart($template, '###NEWTOPIC###');

				$actionParams[$this->prefixId] = array(
					'action' => 'new_topic',
					'fid'    => $forumId
				);
				$actionURL = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $actionParams);

				$bbCodeButtons_template = $this->cObj->getSubpart($template, '###BBCODEBUTTONS###');

				if (empty($conf['jQueryEditorJavaScript'])) {
				  $bbCodeButtons = $this->generateBBCodeButtons($bbCodeButtons_template);
				} else {
					$bbCodeButtons = stristr($bbCodeButtons_template, '<td>') ? '<td></td>' : '';
				}

				$template = $this->cObj->substituteSubpart($template, '###BBCODEBUTTONS###', $bbCodeButtons);
				$template = str_replace('###POLLJAVASCRIPT###',$this->conf['polljavascript'],$template);

				$pollObj = t3lib_div::makeInstance('tx_mmforum_polls');

				$marker = array(
					'###LABEL_CREATETOPIC###'   => $this->pi_getLL('newTopic.create'),
					'###LABEL_TITLE###'         => $this->pi_getLL('newTopic.title'),
					'###LABEL_SETHAVEALOOK###'  => $this->pi_getLL('newTopic.setHaveALook'),
					'###LABEL_SEND###'          => $this->pi_getLL('newTopic.save'),
					'###LABEL_PREVIEW###'       => $this->pi_getLL('newTopic.preview'),
					'###LABEL_RESET###'         => $this->pi_getLL('newTopic.reset'),
					'###POSTTEXT###'            => $this->piVars['message'],
					'###TOPICNAME###'           => $this->escape($this->piVars['topicname']),
					'###HAVEALOOK###'           => ($this->piVars['havealook'] ? 'checked="checked"' : ''),
					'###OLDPOSTTEXT###'         => '',
					'###ACTION###'              => htmlspecialchars($this->tools->getAbsoluteUrl($actionURL)),
					'###SMILIES###'             => $this->show_smilie_db($conf),
					'###LABEL_ATTACHMENT###'    => $this->pi_getLL('newPost.attachment'),
					'###LABEL_POLL###'          => $this->pi_getLL('poll.postattach'),
					'###POLL###'                => $pollObj->display_createForm($this->piVars['poll'] ? $this->piVars['poll'] : array(),$this),
					'###POLLDIV_STYLE###'       => ($this->piVars['enable_poll'] ? '' : 'style="display:none;"'),
					'###ENABLE_POLL###'         => ($this->piVars['enable_poll'] ? 'checked="checked"' : ''),
					'###DISABLE_POLL###'        => '',
					'###DISABLE_POLL_VAR###'    => 0,
					'###CALLPOLLJS###'          => $this->conf['callpolljs'],
					'###POST_PREVIEW###'        => $previewContent ? $previewContent : '',
					'###TOPICICON###'           => $this->getTopicIcon(Array()),
					'###TOPICTITLE###'          => $this->pi_getLL('newTopic.create')
				);

				// Remove file attachment section if file attachments are disabled
				if (!$this->conf['attachments.']['enable']) {
					$template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_SECTION###', '');
				}

				// Add attachment input fields according to TypoScript setting
				$fieldCount = ($this->conf['attachments.']['maxCount'] ? $this->conf['attachments.']['maxCount'] : 1);
				$aTemplate  = $this->cObj->getSubpart($template, '###ATTACHMENT_FIELD###');

				for ($i = 1; $i <= $fieldCount; $i ++) {
					$aMarker = array(
						'###ATTACHMENT_NO###' => $i
					);
					$aContent .= $this->cObj->substituteMarkerArray($aTemplate, $aMarker);
				}
				$template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_FIELD###', $aContent);

				// Remove poll section if polls are disabled
				if (!$pollObj->getMayCreatePoll($this)) {
					$template = $this->cObj->substituteSubpart($template, '###POLL_SECTION###', '');
				}

				// Maximum file size
				$mFileSize = t3lib_div::formatSize($this->conf['attachments.']['maxFileSize']);

				$marker['###MAXFILESIZE_TEXT###'] = sprintf($this->pi_getLL('newPost.maxFileSize'), $mFileSize);
				$marker['###MAXFILESIZE_TEXT###'] = $this->cObj->stdWrap($marker['###MAXFILESIZE_TEXT###'], $this->conf['attachments.']['maxFileSize_stdWrap.']);
				$marker['###MAXFILESIZE###'] = $this->conf['attachments.']['maxFileSize'];

				if($this->conf['disableRootline'])
					$template = $this->cObj->substituteSubpart($template, "###ROOTLINE_CONTAINER###", '');
				else
					$marker['###FORUMPATH###'] = $this->get_forum_path(intval($this->piVars['fid']),'');
			}

		} else {
			// No user logged in, error message
			$template = $this->cObj->fileResource($conf['template.']['login_error']);
			$template = $this->cObj->getSubpart($template, '###LOGINERROR###');
			$marker   = array();
			$marker['###LOGINERROR_MESSAGE###'] = $this->pi_getLL('newTopic.noLogin');
		}

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newTopic_formMarker'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newTopic_formMarker'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$marker = $_procObj->newTopic_formMarker($marker, $this);
			}
		}

		$marker['###STARTJAVASCRIPT###'] = $this->includeEditorJavaScript();

		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		return $content;
	}

	/**
     * Displays the form for creating a new post an answer to an existing topic.
     * @param  string $content The plugin content
     * @param  array  $conf    The plugin's configuration vars
     * @return string          The content
     */
	function new_post($content, $conf) {
		$loginUser = $GLOBALS['TSFE']->loginUser;

		$topicId = intval($this->piVars['tid']);
		$topicData = $this->getTopicData($topicId);

		$forumId = $topicData['forum_id'];

		if (($loginUser && ($this->get_topic_is($topicId) == 0))
			|| ($loginUser && $this->getIsModOrAdmin($forumId))) {
			
			if (!$this->getMayWrite_topic($topicId)) {
				return $content.$this->errorMessage($conf, $this->pi_getLL('newTopic.noAccess'));
			}

			if ($this->piVars['button'] == $this->pi_getLL('newPost.save')) {
				if (!$this->piVars['message']) {
					$content .= $this->errorMessage($this->conf,$this->pi_getLL('newTopic.noText'));
					unset($this->piVars['button']);

					return $this->new_post($content, $conf);
				}

				// Checks if the current user has already written a post in a certain interval
				// from now on. If so, the write attempt is blocked for security reasons.
				$interval = $conf['spamblock_interval'];

				$time = time() - $interval;
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'*',
					'tx_mmforum_posts',
					'poster_id=' . $this->getUserID() . ' AND post_time>=' . $time . $this->cObj->enableFields('tx_mmforum_posts')
				);

				if (($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0)) {

					$template = $this->cObj->fileResource($conf['template.']['login_error']);
					$template = $this->cObj->getSubpart($template, "###LOGINERROR###");

					$marker = array();
					$llMarker = array('###SPAMBLOCK###' => $interval);
					$marker['###LOGINERROR_MESSAGE###'] = $this->cObj->substituteMarkerArray($this->pi_getLL('newPost.spamBlock'),$llMarker);

					$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
					return $content;
				}

				// Create a topic subscription if the user checked the regarding checkbox.
				if ($this->piVars['havealook']) {
					tx_mmforum_havealook::addSubscription($this, $topicId, $this->getUserID());
				}

				// Check file upload
				if ($_FILES['tx_mmforum_pi1_attachment_1']['size']>0) {

					$res = $this->performAttachmentUpload();

					if(!is_array($res)) {
						$content .= $res;
						unset($this->piVars['button']);

						return $this->new_post($content,$conf);
					} else {
						$attachment_ids = $res;
					}
				} else {
					$attachment_ids = 0;
				}

				// Instantiate postfactory class
				$postfactory = t3lib_div::makeInstance('tx_mmforum_postfactory');
				$postfactory->init($this->conf, $this);

				if($this->isModeratedForum() && !$this->getIsAdmin() && !$this->getIsMod($this->piVars['fid'])) {

					// Create post using postfactory
					$postfactory->create_post_queue(
						$topicId,
						$this->getUserID(),
						$this->piVars['message'],
						time(),
						$this->tools->ip2hex(t3lib_div::getIndpEnv("REMOTE_ADDR")),
						$attachment_ids
					);

					return $this->successMessage($conf,$this->pi_getLL('postqueue-success'));

				} else {

					// Create post using postfactory
					$postId = $postfactory->create_post(
						$topicId,
						$this->getUserID(),
						$this->piVars['message'],
						time(),
						$this->tools->ip2hex(t3lib_div::getIndpEnv("REMOTE_ADDR")),
						$attachment_ids,
						false,
						($this->piVars['havealook'] == 'havealook')
					);

					// Redirect user to new post
					$linkParams = array(
						'tx_mmforum_pi1[action]'  => 'list_post',
						'tx_mmforum_pi1[tid]'     => $topicId,
						'tx_mmforum_pi1[pid]'     => $postId
					);

					$link = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams);
					$link = $this->tools->getAbsoluteUrl($link);

					header('Location: ' . t3lib_div::locationHeaderUrl($link . '#pid' . $postId));
					exit();
				}
			} else {

				// Show post preview
				if ($this->piVars['button'] == $this->pi_getLL('newPost.preview')) {
					$template = $this->cObj->fileResource($conf['template.']['list_post']);
					$template = $this->cObj->getSubpart($template, '###LIST_POSTS###');

					$template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_SECTION###', '');

					$userSignature = tx_mmforum_postfunctions::marker_getUserSignature($GLOBALS['TSFE']->fe_user->user);

					$posttext = $this->piVars['message'];
					$postold = $posttext;
					$posttext = $this->bb2text($posttext,$conf) . ($this->conf['list_posts.']['appendSignatureToPostText'] ? $userSignature : '');

					$marker['###POSTOPTIONS###']= '';
					$marker['###MESSAGEMENU###']= '';
					$marker['###PROFILEMENU###']= '';
					$marker['###POSTMENU###']   = '';
					$marker['###POSTUSER###']   = $this->ident_user($this->getUserID(), $conf);
					$marker['###POSTTEXT###']   = $posttext;
					$marker['###ANKER###']      = '';
					$marker['###POSTANCHOR###']	= '';
					$marker['###POSTDATE###']   = $this->pi_getLL('post.writtenOn') . ': ' . $this->formatDate(time());
					$marker['###POSTRATING###'] = '';

					// Include hooks
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPost_INpreview'])) {
						foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPost_INpreview'] as $classRef) {
							$procObj = & t3lib_div::getUserObj($classRef);
							$marker = $procObj->newPost_INpreview($marker, $this);
						}
					}

					$previewTemplate = $this->cObj->fileResource($conf['template.']['new_post']);
					$previewTemplate = $this->cObj->getSubpart($previewTemplate,"###PREVIEW###");
					$previewMarker = array(
						'###LABEL_PREVIEW###'    => $this->pi_getLL('newPost.preview'),
						'###PREVIEW_POST###'     => $this->cObj->substituteMarkerArrayCached($template, $marker)
					);

					// Include hooks
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPost_preview'])) {
						foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPost_preview'] as $classRef) {
							$procObj = & t3lib_div::getUserObj($classRef);
							$previewMarker = $procObj->newPost_preview($previewMarker, $this);
						}
					}

					$previewContent = $this->cObj->substituteMarkerArrayCached($previewTemplate, $previewMarker);
				}

				$template = $this->cObj->fileResource($conf['template.']['new_post']);
				$template = $this->cObj->getSubpart($template,
					stristr($template, '###NEWTOPIC###') === false ? '###NEWPOST###' : '###NEWTOPIC###');	// compatibility: typo in template file fixed. was 'NEWTOPIC'

				$marker = array(
					'###LABEL_SEND###'              => $this->pi_getLL('newPost.save'),
					'###LABEL_PREVIEW###'           => $this->pi_getLL('newPost.preview'),
					'###LABEL_RESET###'             => $this->pi_getLL('newPost.reset'),
					'###LABEL_ATTENTION###'         => $this->pi_getLL('newPost.attention'),
					'###LABEL_NOTECODESAMPLES###'   => $this->pi_getLL('newPost.codeSamples'),
					'###LABEL_ATTACHMENT###'        => $this->pi_getLL('newPost.attachment'),
					'###LABEL_SETHAVEALOOK###'		=> $this->pi_getLL('newTopic.setHaveALook')
				);
				
				$marker['###POSTTITLE###'] = $this->escape($topicData['topic_title']);

				$marker['###POST_PREVIEW###'] = (string)$previewContent;

				// Remove file attachment section if file attachments are disabled
				if(!$this->conf['attachments.']['enable']) {
					$template = $this->cObj->substituteSubpart($template, "###ATTACHMENT_SECTION###", '');
				}

				// Remove file attachment edit section
				$template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_EDITSECTION###', '');

				// Add attachment input fields according to TypoScript setting
				$fieldCount = $this->conf['attachments.']['maxCount']?$this->conf['attachments.']['maxCount']:1;
				$aTemplate = $this->cObj->getSubpart($template, '###ATTACHMENT_FIELD###');

				for($i=1; $i <= $fieldCount; $i ++) {
					$aMarker = array(
						'###ATTACHMENT_NO###' => $i
					);
					$aContent .= $this->cObj->substituteMarkerArray($aTemplate, $aMarker);
				}
				$template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_FIELD###', $aContent);

				// Remove poll section
				$template = $this->cObj->substituteSubpart($template,'###POLL_SECTION###', '');

				// Maximum file size
				$mFileSize = $this->conf['attachments.']['maxFileSize'] . ' B';
				if($this->conf['attachments.']['maxFileSize'] >= 1024) {
					$mFileSize = round($this->conf['attachments.']['maxFileSize'] / 1024, 2) . ' KB';
				}
				if($this->conf['attachments.']['maxFileSize'] >= (1024 * 1024)) {
					$mFileSize = round($this->conf['attachments.']['maxFileSize'] / (1024 * 1024), 2) . ' MB';
				}

				$marker['###MAXFILESIZE_TEXT###'] = sprintf($this->pi_getLL('newPost.maxFileSize'), $mFileSize);
				$marker['###MAXFILESIZE_TEXT###'] = $this->cObj->stdWrap($marker['###MAXFILESIZE_TEXT###'], $this->conf['attachments.']['maxFileSize_stdWrap.']);
				$marker['###MAXFILESIZE###'] = $this->conf['attachments.']['maxFileSize'];

				// Inserting predefined message
				iF ($this->piVars['message']) {
					$marker['###POSTTEXT###'] = $this->escape($this->piVars['message']);
				} else {
					// Load post to be quoted
					if ($this->piVars['quote']) {
						if(!$this->getMayRead_post($this->piVars['quote'])) {
							return $content.$this->errorMessage($conf,$this->pi_getLL('newPost.quote.error'));
						}

						// Get user UID of quoted user
						$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
							'poster_id',
							'tx_mmforum_posts',
							'uid=' . intval($this->piVars['quote'])
						);
						list($quoteuserid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

						// Get user name of quoted user
						$quoteuser_array = tx_mmforum_tools::get_userdata($quoteuserid);
						$quoteuser = $quoteuser_array[$this->getUserNameField()];

						// Get text to be quoted
						$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
							'post_text',
							'tx_mmforum_posts_text',
							'post_id=' . intval($this->piVars['quote'])
						);
						list($posttext) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

						// Insert quote into message text.
						$marker['###POSTTEXT###'] = '[quote="' . $quoteuser . '"]' . "\r\n" . $posttext . "\r\n" . '[/quote]';
					} else {
						$marker['###POSTTEXT###'] = '';
					}
				}

				$actionParams[$this->prefixId] = array(
					'action' => 'new_post',
					'tid' => $this->piVars['tid']
				);
				
				if($this->useRealUrl()) {
					$actionParams[$this->prefixId]['fid'] = $forumId;
				}

				$actionLink = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $actionParams);

				$bbCodeButtons_template = $this->cObj->getSubpart($template, '###BBCODEBUTTONS###');

				if (empty($conf['jQueryEditorJavaScript'])) {
					$bbCodeButtons = $this->generateBBCodeButtons($bbCodeButtons_template);
				} else {
					$bbCodeButtons = stristr($bbCodeButtons_template, '<td>') ? '<td></td>' : '';
				}

				$template = $this->cObj->substituteSubpart($template,'###BBCODEBUTTONS###',$bbCodeButtons);

				$marker['###SMILIES###']			= $this->show_smilie_db($conf);
				$marker['###ACTION###']				= htmlspecialchars($this->tools->getAbsoluteUrl($actionLink));
				$marker['###LABEL_CREATETOPIC###']	= $this->pi_getLL('newPost.title');

				$conf['slimPostList'] = 1;
				$marker['###OLDPOSTTEXT###'] = '<hr />' . tx_mmforum_postfunctions::list_post('', $conf, 'DESC');

				if($this->conf['disableRootline']) {
					$template = $this->cObj->substituteSubpart($template, '###ROOTLINE_CONTAINER###', '');
				} else {
					$marker['###FORUMPATH###'] = $this->get_forum_path($forumId, '');
				}
			}
		} else {
			$template = $this->cObj->fileResource($conf['template.']['login_error']);
			$template = $this->cObj->getSubpart($template, "###LOGINERROR###");
			$marker = array(
				'###LOGINERROR_MESSAGE###' => $this->pi_getLL('newPost.noLogin'),
			);
		}

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPost_formMarker'])) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPost_formMarker'] as $classRef) {
				$procObj = & t3lib_div::getUserObj($classRef);
				$marker = $procObj->newPost_formMarker($marker, $this);
			}
		}

		$marker['###HAVEALOOK###'] = ($this->piVars['havealook'] ? 'checked="checked"' : '');
		$marker['###STARTJAVASCRIPT###'] = $this->includeEditorJavaScript();

		$content .= $this->cObj->substituteMarkerArray($template, $marker);
		return $content;
	}
	
	function includeEditorJavaScript() {
		$js = '';
		if (!empty($this->conf['jQueryEditorJavaScript'])) {
			$jsmarker = array(
					'###BBCOLOR###'   => $this->pi_getLL('markItUp-bbcolor'),
					'###YELLOW###'    => $this->pi_getLL('markItUp-bbcolor-Yellow'),
					'###ORANGE###'    => $this->pi_getLL('markItUp-bbcolor-Orange'),
					'###RED###'       => $this->pi_getLL('markItUp-bbcolor-Red'),
					'###BLUE###'      => $this->pi_getLL('markItUp-bbcolor-Blue'),
					'###PURPLE###'    => $this->pi_getLL('markItUp-bbcolor-Purple'),
					'###GREEN###'     => $this->pi_getLL('markItUp-bbcolor-Green'),
					'###WHITE###'     => $this->pi_getLL('markItUp-bbcolor-White'),
					'###GRAY###'      => $this->pi_getLL('markItUp-bbcolor-Gray'),
					'###BLACK###'     => $this->pi_getLL('markItUp-bbcolor-Black'),
					'###BBIMAGEURL###'=> $this->pi_getLL('markItUp-bbimage'),
					'###BBLINKURL###' => $this->pi_getLL('markItUp-bblink'),
					'###BBLISTNR###'  => $this->pi_getLL('markItUp-bblistnr'),
					'###BBSIZE###'    => $this->pi_getLL('markItUp-bbsize'),
					'###BOLD###'      => $this->pi_getLL('markItUp-Bold'),
					'###ITALIC###'    => $this->pi_getLL('markItUp-Italic'),
					'###UNDERLINE###' => $this->pi_getLL('markItUp-Underline'),
					'###PICTURE###'   => $this->pi_getLL('markItUp-Picture'),
					'###LINK###'      => $this->pi_getLL('markItUp-Link'),
					'###COLOR###'     => $this->pi_getLL('markItUp-Colors'),
					'###SIZE###'      => $this->pi_getLL('markItUp-Size'),
					'###SIZEBIG###'   => $this->pi_getLL('markItUp-Size-Big'),
					'###SIZENORMAL###'=> $this->pi_getLL('markItUp-Size-Normal'),
					'###SIZESMALL###' => $this->pi_getLL('markItUp-Size-Small'),
					'###BLIST###'     => $this->pi_getLL('markItUp-blist'),
					'###NLIST###'     => $this->pi_getLL('markItUp-nlist'),
					'###LITEM###'     => $this->pi_getLL('markItUp-litem'),
					'###QUOTES###'    => $this->pi_getLL('markItUp-Quotes'),
					'###CODE###'      => $this->pi_getLL('markItUp-Code'),
					'###CLEAN###'     => $this->pi_getLL('markItUp-Clean'),
					'###PREVIEW###'   => $this->pi_getLL('markItUp-Preview')
			);
			$js = "\n".$this->cObj->substituteMarkerArray($this->conf['jQueryEditorJavaScript'], $jsmarker);
		}
		if (!empty($this->conf['editorJavaScript'])) {
			$js .= "\n".$this->conf['editorJavaScript']."\n";
		}
		if (!empty($js)) {
			$js = '<script type="text/javascript">' . $js . '</script>';
		}
		return $js;
	}

	/**
	 * Performs a file upload.
	 * This function handles the storing of file attachments into the
	 * database and the file system.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-05-21
	 * @return  mixed The attachment UID(s) if the process was successfull, otherwise an
	 *                error message.
	 */
	function performAttachmentUpload() {
		$deny   = t3lib_div::trimExplode(',', $this->conf['attachments.']['deny']);
		$allow  = t3lib_div::trimExplode(',', $this->conf['attachments.']['allow']);
		$maxAttachments = intval($this->conf['attachments.']['maxCount'] ? $this->conf['attachments.']['maxCount'] : 1);

		if (!$this->conf['attachments.']['enable']) {
			return $this->errorMessage($this->conf, $this->pi_getLL('attachment.disabled'));
		}

		$attachments = array();
		for ($i = 1; $i <= $maxAttachments; $i++) {
			$file = $_FILES['tx_mmforum_pi1_attachment_' . $i];
			if (!$file['size']) {
				continue;
			}
			if ($file['size'] > $this->conf['attachments.']['maxFileSize']) {
				$fileSize = t3lib_div::formatSize($file['size']) . 'B';
				return $this->errorMessage($this->conf, sprintf($this->pi_getLL('attachment.toobig'), $fileSize));
			}
			if ($allow[0] == '*' || strlen($allow) == 0) {
				if (count($deny) > 0) {
					foreach ($deny as $denyItem) {
						if (preg_match('/\.' . $denyItem . '$/i', $file['name'])) {
							return $this->errorMessage($this->conf, $this->pi_getLL('attachment.denyed'));
						}
					}
				}
			} else {
				$valid = false;
				if (count($allow) > 0) {
					foreach ($allow as $allowItem) {
						if (preg_match('/\.' . $allowItem . '$/i', $file['name'])) {
							$valid = true;
						}
					}
					if (!$valid) {
						return $this->errorMessage($this->conf, $this->pi_getLL('attachment.denyed'));
					}
				}
			}

			$dirname = $this->conf['attachments.']['attachmentDir'];
			if (substr($newpath, -1, 1) != '/') {
				$dirname .= '/';
			}

			$newpath = $dirname.'attachment_' . md5_file($file['tmp_name']);

			$ext = array_pop(explode('.', $file['name']));
			$newpath .= '.' . $ext;

			move_uploaded_file($file['tmp_name'], $newpath);
			chmod($newpath, 0444);

				/* Fix wrong mime-type for pdf when uploading through Firefox
				 * Mime-type for PDF should be: 'application/pdf'
				 * FF 3.0.9 Linux gives 'binary/octet-stream'
				 * FF 3.0.9 Windows gives 'application/octet-stream'
				 *
				 * Credits go to Loek Hilgersom
				 */
			if(in_array($file['type'],array('binary/octet-stream','application/octet-stream','x-application/octet-stream')) && substr_compare($file['name'],'.pdf',-4,4,true)==0)
				$file['type'] = 'application/pdf';

			$insertArray = array(
				'pid'       => $this->getStoragePID(),
				'tstamp'    => time(),
				'crdate'    => time(),
				'file_type' => $file['type'],
				'file_name' => $file['name'],
				'file_size' => $file['size'],
				'file_path' => $newpath,
			);

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['attachment_dataRecord'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['attachment_dataRecord'] as $_classRef) {
					$_procObj = &t3lib_div::getUserObj($_classRef);
					$insertArray = $_procObj->attachment_dataRecord($insertArray, $this);
				}
			}

			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_attachments', $insertArray);
			$attachments[] = $GLOBALS['TYPO3_DB']->sql_insert_id();
		}

		return $attachments;
	}


    /**
     * Displays the form for editing an existing post. Regular users can only edit their own
     * posts if they have not been answered yet. Moderators and administrators can edit all
     * posts, regardless if they have been answered or not.
     * @param  string $content The plugin content
     * @param  array  $conf    The plugin's configuration vars
     * @return string          The content
     */
	function post_edit($content, $conf) {
		$postId = intval($this->piVars['pid']);

		// Get topic UID
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tx_mmforum_posts',
			'deleted=0 AND hidden=0 AND uid=' . $postId . $this->getStoragePIDQuery()
		);
		
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

		$topicId = $row['topic_id'];
		$forumId = $row['forum_id'];

		// Determine, if edited post is the last post in topic
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'MAX(post_time)',
			'tx_mmforum_posts',
			'deleted=0 AND hidden=0 AND topic_id='. $topicId . $this->getStoragePIDQuery()
		);
		list($lastpostdate) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

		// Determine if edited post is the first post in topic
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'tx_mmforum_posts',
			'deleted=0 AND hidden=0 AND topic_id='.$topicId.' '.$this->getStoragePIDQuery(),
			'',
			'post_time ASC'
		);
		list($firstPostId) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		$firstPost = ($postId === intval($firstPostId));

		// Load topic data
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tx_mmforum_topics',
			'deleted=0 AND hidden=0 AND uid=' . $topicId . $this->getStoragePIDQuery()
		);
		$topicData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

		if ((
				($row['poster_id'] == $GLOBALS['TSFE']->fe_user->user['uid'])
				&& ($lastpostdate == $row['post_time'])
				&& $topicData['closed_flag'] != 1)
				OR $this->getIsAdmin()
				OR $this->getIsMod($row['forum_id']))
				{

			if($this->piVars['button'] == $this->pi_getLL('newPost.save')) {

				// Write changes to database
				$updateArray = array(
					'post_text' => $this->piVars['message'],
					'tstamp'    => time()
				);

				$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
						'tx_mmforum_posts_text',
						'post_id=' . $postId,
						$updateArray
				);

				// check for attachments that should be deleted
				if ($this->piVars['attachment_delete']) {
					foreach ($this->piVars['attachment_delete'] as $attachementId => $delete) {
						$attachementId = intval($attachementId);
						$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_attachments', 'uid=' . $attachementId, array('deleted' => 1, 'tstamp' => time()));
						$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_posts', 'uid=' . $attachementId, array('attachment' => 0, 'tstamp' => time()));
						$attachments = t3lib_div::intExplode(',', $row['attachment']);
						unset($attachments[array_search($attachementId, $attachments)]);
						$row['attachment'] = implode(',', $attachments);
					}
					$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
							'tx_mmforum_posts',
							'uid=' . $postId,
							array('attachment' => $row['attachment'])
					);
				}

				// Check for new file uploads / attachments
				if ($_FILES['tx_mmforum_pi1_attachment_1']['size'] > 0) {
					$res = $this->performAttachmentUpload();

					if (!is_array($res)) {
						$content .= $res;
						unset($this->piVars['button']);
						return $this->post_edit($content);

					} else {
						$attachmentIds = $res;
						$attachments = t3lib_div::intExplode(',', $row['attachment']);
						$attachments = tx_mmforum_tools::processArray_numeric($attachments);

						$updateData = array(
							'attachment' => implode(',', array_merge($attachments, $attachmentIds))
						);
						$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_posts', 'uid = ' . $postId, $updateData);

						// Update attachment records with the post ID (as this is not set within the performAttachmentUpload)
						if (count($attachmentIds)) {
							$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
									'tx_mmforum_attachments',
									'uid IN (' . implode(',', $attachmentIds) . ')',
									array('post_id' => $postId)
							);
						}
					}
				} else {
					$attachmentIds = null;
				}

				if ($this->conf['polls.']['enable']) {

					if($this->piVars['enable_poll'] == '1' && $firstPost) {
						$pollObj = t3lib_div::makeInstance('tx_mmforum_polls');

						if($topicData['poll_id'] > 0) {

							$res = $pollObj->editPoll($topicData['poll_id'],$this->piVars['poll'],$this);

							if($res) {
								$content .= $this->errorMessage($this->conf, $res);
								unset($this->piVars['button']);

								return $this->post_edit($content,$conf);
							}
						} else {
							
							$pollId = $pollObj->createPoll($this->piVars['poll'],$this);

							if(!is_numeric($pollId)) {
								$content .= $this->errorMessage($this->conf, $pollId);
								unset($this->piVars['button']);

								return $this->post_edit($content,$conf);
							}

							$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
									'tx_mmforum_topics',
									'uid=' . $topicId,
									array('poll_id' => $pollId, 'tstamp'=>time())
							);
						}
					} else if ($firstPost && $topicData['poll_id'] > 0) {

						$pollObj = t3lib_div::makeInstance('tx_mmforum_polls');
						$pollObj->deletePoll($topicData['poll_id'],$topicData['uid']);
					}
				}


				if ($this->piVars['title']
						AND (
								($this->getIsMod($row['forum_id']) || $this->getIsAdmin())
								|| ($firstPost && $row['poster_id'] == $GLOBALS['TSFE']->fe_user->user['uid'])
							)
					) {

					$updateArray = array(
						'topic_title'   => $this->piVars['title'],
						'tstamp'        => time()
					);
					$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
							'tx_mmforum_topics',
							'uid=' . $topicId,
							$updateArray
					);
				}

				// If the editing user is no admin or mod, the change is logged in the database
				if (!$this->getIsMod($row['forum_id']) && !$this->getIsAdmin()) {
					$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
							'tx_mmforum_posts',
							'uid=' . $postId,
							array(
								'edit_count' => intval($row['edit_count']) + 1,
								'edit_time' => time()
							)
					);

				}

				// Clearing for new indexing
				require_once(t3lib_extMgm::extPath('mm_forum').'pi4/class.tx_mmforum_pi4.php');
				tx_mmforum_indexing::delete_topic_ind_date($topicId);

				$linkParams[$this->prefixId] = array(
					'action'  => 'list_post',
					'tid'     => $topicId,
					'pid'     => $this->piVars['pid'],
				);

				$link = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams);
				$link = $this->tools->getAbsoluteUrl($link);

				header('Location: ' . t3lib_div::locationHeaderUrl($link . '#pid' . $postId));
				exit();
			
			} else {
				// Display post preview
				if ($this->piVars['button'] == $this->pi_getLL('newPost.preview')) {
					if ($this->piVars['enable_poll'] == '1' && $this->conf['polls.']['enable']) {
						$content .= tx_mmforum_polls::displayPreview($this->piVars['poll'], $this);
					}
					
					$template = $this->cObj->fileResource($conf['template.']['list_post']);
					$template = $this->cObj->getSubpart($template, "###LIST_POSTS###");

					$template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_SECTION###', '');

					$posttext = $this->piVars['message'];
					$postold = $posttext;
					$posttext = $this->bb2text($posttext, $conf);

					$marker['###POSTOPTIONS###']  = '';
					$marker['###SOLVEDOPTION###'] = '';
					$marker['###POSTMENU###']     = '';
					$marker['###POSTUSER###']     = $this->ident_user($row['poster_id'],$conf);
					$marker['###POSTTEXT###']     = $posttext;
					$marker['###ANKER###']        = '';
					$marker['###POSTANCHOR###']	  = '';
					$marker['###POSTDATE###']     = $this->pi_getLL('post.writtenOn') . ': ' . $this->formatDate($topicData['topic_time']);
					$marker['###POSTRATING###']   = '';

					// Include hooks
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['editPost_INpreviewMarker'])) {
						foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['editPost_INpreviewMarker'] as $classRef) {
							$procObj = & t3lib_div::getUserObj($classRef);
							$marker = $procObj->editPost_INpreviewMarker($marker, $this);
						}
					}

					$previewTemplate = $this->cObj->fileResource($conf['template.']['new_post']);
					$previewTemplate = $this->cObj->getSubpart($previewTemplate, '###PREVIEW###');
					$previewMarker = array(
						"###TOPIC_TITLE###"        => $this->escape($this->piVars['topicname']),
						"###LABEL_PREVIEW###"      => $this->pi_getLL('newTopic.preview'),
						"###PREVIEW_POST###"       => $this->cObj->substituteMarkerArrayCached($template, $marker)
					);

					// Include hooks
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['editPost_previewMarker'])) {
						foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['editPost_previewMarker'] as $classRef) {
							$procObj = & t3lib_div::getUserObj($classRef);
							$previewMarker = $procObj->editPost_previewMarker($previewMarker, $this);
						}
					}

					$previewContent = $this->cObj->substituteMarkerArrayCached($previewTemplate, $previewMarker);
				}

				$template = $this->cObj->fileResource($conf['template.']['new_post']);
				$template = $this->cObj->getSubpart($template,
					stristr($template, '###NEWTOPIC###') === false ? '###NEWPOST###' : '###NEWTOPIC###');	// compatibility: typo in template file fixed. was 'NEWTOPIC'

				$attachments = t3lib_div::intExplode(',', $row['attachment']);
				$attachments = tx_mmforum_tools::processArray_numeric($attachments);
				$attachCount = count($attachments);

				if($attachCount == $this->conf['attachments.']['maxCount'] || !$this->conf['attachments.']['enable']) {
					$template = $this->cObj->substituteSubpart($template, "###ATTACHMENT_SECTION###", '');
				} else {
					$attachDiff = $this->conf['attachments.']['maxCount'] - $attachCount;
					$aTemplate	= $this->cObj->getSubpart($template, '###ATTACHMENT_FIELD###');
					$aContent = '';

					for($i=1; $i <= $attachDiff; $i++) {
						$aMarker = array(
							'###ATTACHMENT_NO###' => $i
						);
						$aContent .= $this->cObj->substituteMarkerArray($aTemplate, $aMarker);
					}

					$marker = array(
						'###LABEL_ATTACHMENT###' => $this->pi_getLL('newPost.attachment'),
						'###MAXFILESIZE###' => $this->conf['attachments.']['maxFileSize']
					);

					// Maximum file size
					$mFileSize = $this->conf['attachments.']['maxFileSize'].' B';
					if ($this->conf['attachments.']['maxFileSize'] >= 1024) {
						$mFileSize = round($this->conf['attachments.']['maxFileSize'] / 1024, 2) . ' KB';
					}
					if ($this->conf['attachments.']['maxFileSize'] >= 1024*1024) {
						$mFileSize = round($this->conf['attachments.']['maxFileSize'] / (1024 * 1024), 2) . ' MB';
					}

					$marker['###MAXFILESIZE_TEXT###'] = sprintf($this->pi_getLL('newPost.maxFileSize'), $mFileSize);
					$marker['###MAXFILESIZE_TEXT###'] = $this->cObj->stdWrap($marker['###MAXFILESIZE_TEXT###'], $this->conf['attachments.']['maxFileSize_stdWrap.']);

					$template = $this->cObj->substituteMarkerArray($template, $marker);
					$template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_FIELD###', $aContent);
				}

				$marker = array();

				if(strlen($row['attachment']) == 0) {
					$template = $this->cObj->substituteSubpart($template,'###ATTACHMENT_EDITSECTION###', '');
				} else {
					$aRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'*',
						'tx_mmforum_attachments',
						'uid IN (' .$row['attachment'] . ') AND deleted=0',
						'',
						'uid ASC'
					);

					$marker['###LABEL_ATTACHMENT###'] = $this->pi_getLL('newPost.attachment');
					$aTemplate = $this->cObj->getSubpart($template, '###ATTACHMENT_EDITFIELD###');
					$aContent = '';

					while($attachment = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($aRes)) {
						$size = $attachment['file_size'].' '.$this->pi_getLL('attachment.bytes');
						if ($attachment['file_size'] > 1024) $size = round($attachment['file_size']/1024,2).' '.$this->pi_getLL('attachment.kilobytes');
						if ($attachment['file_size'] > 1048576) $size = round($attachment['file_size']/1048576,2).' '.$this->pi_getLL('attachment.megabytes');

						$aMarker['###LABEL_DELETEATTACHMENT###'] = $this->pi_getLL('attachment.delete');

						$sAttachment = $attachment['file_name'] . ' (' . $this->pi_getLL('attachment.type') . ': ' . $attachment['file_type'] . ', ' . $this->pi_getLL('attachment.size') . ': ' . $size . '), ' . $attachment['downloads'] . ' ' . $this->pi_getLL('attachment.downloads');
						$sAttachment = $this->escape($sAttachment);
						$sAttachment = $this->cObj->stdWrap($sAttachment, $this->conf['attachments.']['attachmentEditLabel_stdWrap.']);

						$aMarker['###ATTACHMENT_DATA###'] = $sAttachment;
						$aMarker['###ATTACHMENT_UID###']  = $attachment['uid'];
						$aContent .= $this->cObj->substituteMarkerArray($aTemplate, $aMarker);
					}
					$template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_EDITFIELD###', $aContent);
				}

				if ($firstPost && $this->conf['polls.']['enable']) {
					$pollObj = t3lib_div::makeInstance('tx_mmforum_polls');
					if ($topicData['poll_id'] == 0) {
						$marker['###POLL###']           = $pollObj->display_createForm($this->piVars['poll'] ? $this->piVars['poll'] : array(), $this);
						$marker['###ENABLE_POLL###']    = $this->piVars['enable_poll'] ? 'checked="checked"' : '';
						$marker['###POLLDIV_STYLE###']  = $this->piVars['enable_poll'] ? '' : 'style="display:none;"';
						$marker['###LABEL_POLL_CE###']  = $this->pi_getLL('poll.postattach.new');
						$marker['###DISABLE_POLL###']   = '';
						$marker['###DISABLE_POLL_VAR###'] = 0;
						$marker['###CALLPOLLJS###'] = $this->conf['callpolljs'];
					} else {
						$pollEnabled = $pollObj->getMayEditPoll($topicData['poll_id'],$this);
						$marker['###POLL###']           = $pollObj->display_editForm($topicData['poll_id'],$this->piVars['poll']?$this->piVars['poll']:array(),$this);
						$marker['###ENABLE_POLL###']    = 'checked="checked"';
						$marker['###POLLDIV_STYLE###']  = '';
						$marker['###LABEL_POLL_CE###']  = $this->pi_getLL('poll.postattach.edit');
						$marker['###DISABLE_POLL###']   = $pollEnabled ? '' : 'disabled="disabled"';
						$marker['###DISABLE_POLL_VAR###'] = $pollEnabled ? 0 : 1;
						$marker['###CALLPOLLJS###'] = $this->conf['callpolljs'];
					}
					$marker['###LABEL_POLL###']     = $this->pi_getLL('poll.postattach');
				} else {
					$template = $this->cObj->substituteSubpart($template, '###POLL_SECTION###', '');
				}

				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('post_text', 'tx_mmforum_posts_text', 'post_id=' . $postId);
				list($posttext) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title', 'tx_mmforum_topics', 'uid=' . $topicId);
				list($title) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

				$marker['###POSTTEXT###'] = $this->piVars['message'] ? $this->escape($this->piVars['message']) : $this->escape($posttext);

				if ($this->getIsMod($row['forum_id']) || $this->getIsAdmin()) {
					$marker['###POSTTITLE###'] = '<input type="text"  name="tx_mmforum_pi1[title]" size="50" value="' . $this->escape($title) . '" style="width:80%;"></div>';
				} else if($firstPost && $row['poster_id'] == $GLOBALS['TSFE']->fe_user->user['uid']) {
					$marker['###POSTTITLE###'] = '<input type="text"  name="tx_mmforum_pi1[title]" size="50" value="' . $this->escape($title) . '" style="width:80%;"></div>';
				} else {
					$marker['###POSTTITLE###'] = $this->escape($title);
				}

				$marker['###OLDPOSTTEXT###'] = '';
				$marker['###SMILIES###'] = $this->show_smilie_db($conf);
				$marker['###SOLVEDOPTION###'] = '';
				$marker['###ACTION###'] = htmlspecialchars(
						$this->tools->getAbsoluteUrl(
								$this->pi_getPageLink(
										$GLOBALS['TSFE']->id,
										'',
										array($this->prefixId => array(
											'action' => 'post_edit',
											'pid' => $postId
										))
								)
						)
				);

				$marker['###LABEL_SEND###']            = $this->pi_getLL('newPost.save');
				$marker['###LABEL_PREVIEW###']         = $this->pi_getLL('newPost.preview');
				$marker['###LABEL_RESET###']           = $this->pi_getLL('newPost.reset');
				$marker['###LABEL_ATTENTION###']       = $this->pi_getLL('newPost.attention');
				$marker['###LABEL_NOTECODESAMPLES###'] = $this->pi_getLL('newPost.codeSamples');
				$marker['###TOPICICON###'] = $this->getTopicIcon($topicData);
				$marker['###TOPICTITLE###'] = $this->escape($topicData['topic_title']);

				// no have-a-look on post edit
				$template = $this->cObj->substituteSubpart($template, '###HAVEALOOK_SECTION###', '');

				$bbCodeButtons_template = $this->cObj->getSubpart($template, '###BBCODEBUTTONS###');

				if (empty($conf['jQueryEditorJavaScript'])) {
					$bbCodeButtons = $this->generateBBCodeButtons($bbCodeButtons_template);
				} else {
					$bbCodeButtons = stristr($bbCodeButtons_template, '<td>') ? '<td></td>' : '';
				}

				$template = $this->cObj->substituteSubpart($template, '###BBCODEBUTTONS###', $bbCodeButtons);
				$template = str_replace('###POLLJAVASCRIPT###', $this->conf['polljavascript'], $template);

				if($this->conf['disableRootline']) {
					$template = $this->cObj->substituteSubpart($template, '###ROOTLINE_CONTAINER###', '');
				} else {
					$marker['###FORUMPATH###'] = $this->get_forum_path($forumId, $topicId);
				}
			}
		} else {
			$template = $this->cObj->fileResource($conf['template.']['error']);
			$marker = array(
				'###ERROR###' => $this->pi_getLL('editPost.noAccess'),
			);
		}

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['editPost_formMarker'])) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['editPost_formMarker'] as $classRef) {
				$procObj = & t3lib_div::getUserObj($classRef);
				$marker = $procObj->editPost_formMarker($marker, $this);
			}
		}

		$marker['###STARTJAVASCRIPT###'] = $this->includeEditorJavaScript();
		$marker['###POST_PREVIEW###']    = (string)$previewContent;
		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

		return $content;
	}

	/**
	 * Favorites
	 */

	/**
	 * Adds a topic to the current user's favorites.
	 * @return string          An error message in case the redirect attempt to the previous
	 *                         page fails.
	 */
	function set_favorite() {
		$topicId = intval($this->piVars['tid']);
		$userId  = intval($GLOBALS['TSFE']->fe_user->user['uid']);

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'tx_mmforum_favorites',
			'user_id = ' . $userId . ' AND topic_id = ' . $topicId . $this->getStoragePIDQuery()
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) < 1 && $userId > 0) {
			$insertArray = array(
				'pid'       => $this->getStoragePID(),
				'tstamp'    => time(),
				'crdate'    => time(),
				'topic_id'  => $topicId,
				'user_id'   => $userId
			);

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['setFavorite_dataRecord'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['setFavorite_dataRecord'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$insertArray = $_procObj->setFavorite_dataRecord($insertArray, $this);
				}
			}
			$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_favorites', $insertArray);
		}

		// Redirect back to previous page
		$this->redirectToReferrer();
		return $this->pi_getLL('favorites.addSuccess') . '<br />' . $this->pi_getLL('redirect.error') . '<br />';
	}

	/**
	 * Deletes a topic from the current user's favorites
	 * @return string          An error message in case the redirect attempt to the previous
	 *                         page fails
	 */
	function del_favorite() {
		$topicId = intval($this->piVars['tid']);
		$userId  = intval($GLOBALS['TSFE']->fe_user->user['uid']);

		$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery(
			'tx_mmforum_favorites',
			'user_id = ' . $userId . ' AND topic_id = ' . $topicId);

		// Redirect back to previous page
		$this->redirectToReferrer();
		return $this->pi_getLL('favorites.delSuccess') . '<br />' . $this->pi_getLL('redirect.error') . '<br />';
	}

	/**
	 * Displays the current user's favorite topics. Performs also operations like
	 * editing or deleting favorites.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The configuration vars
	 * @return string          The content
	 */
	function favorites($content, $conf) {
		$userId = intval($GLOBALS['TSFE']->fe_user->user['uid']);

		if ($userId > 0) {
			// Delete favorite
			if ($this->piVars['fav']['deltid']) {
				$del_tid = intval($this->piVars['fav']['deltid']);
				$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_favorites', 'user_id = ' . $userId .' AND topic_id = '.$del_tid);
				unset($this->piVars['fav']);
			}

			// Delete multiple favorites
			if ($this->piVars['fav']['action'] == 'delete') {
				foreach ((array)$this->piVars['fav']['delete'] as $del_tid) {
					$del_tid = intval($del_tid);        // Parse to int for security reasons
					$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_favorites', 'user_id = ' . $userId . ' AND topic_id = ' . $del_tid);
				}
				unset($this->piVars['fav']);
			}

			// Determine sorting mode
			$orderBy = ($this->piVars['order'] ? $this->piVars['order'] : 'added');
			switch ($orderBy) {
				case 'lpdate':
					$order = 't.topic_last_post_id DESC';
					break;
				case 'cat':
					$order = 'c.sorting ASC, f.sorting ASC, t.topic_last_post_id DESC';
					break;
				case 'added':
					$order = 'fa.uid DESC';
					break;
				case 'alphab':
					$order = 't.topic_title ASC';
					break;
				default:
					$order = 'fa.uid DESC';
					break;
			}

			// Output sorting options form
			$template = $this->cObj->fileResource($conf['template.']['favorites']);
			$template = $this->cObj->getSubpart($template, '###FAVORITES_SETTINGS###');
			$settingsMarker = array(
				'###ACTION###'             => htmlspecialchars($this->tools->getAbsoluteUrl($this->pi_linkTP_keepPIvars_url())),
				'###ORDER_LPDATE###'       => ($orderBy == 'lpdate' ? 'selected="selected"' : ''),
				'###ORDER_CAT###'          => ($orderBy == 'cat'    ? 'selected="selected"' : ''),
				'###ORDER_ADDED###'        => ($orderBy == 'added'  ? 'selected="selected"' : ''),
				'###ORDER_ALPHAB###'       => ($orderBy == 'alphab' ? 'selected="selected"' : ''),

				'###LABEL_ORDERBY###'      => $this->pi_getLL('favorites.orderBy'),
				'###LABEL_ORDER_LPDATE###' => $this->pi_getLL('favorites.orderBy.lpdate'),
				'###LABEL_ORDER_CAT###'    => $this->pi_getLL('favorites.orderBy.cat'),
				'###LABEL_ORDER_ADDED###'  => $this->pi_getLL('favorites.orderBy.added'),
				'###LABEL_ORDER_ALPHAB###' => $this->pi_getLL('favorites.orderBy.alphab'),
			);

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_header'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_header'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$settingsMarker = $_procObj->listFavorites_header($settingsMarker, $this);
				}
			}

			$content .= $this->cObj->substituteMarkerArrayCached($template, $settingsMarker);

			$template = $this->cObj->fileResource($conf['template.']['favorites']);
			$template = $this->cObj->getSubpart($template, '###FAVORITES_BEGIN###');
			$marker = array(
				'###ACTION###'                => $this->escapeURL($this->tools->getAbsoluteUrl($this->pi_linkTP_keepPIvars_url())),
				'###LABEL_OPTIONS###'         => $this->pi_getLL('favorites.options'),
				'###LABEL_FAVORITES###'       => $this->pi_getLL('favorites.title'),
				'###LABEL_TOPICNAME###'       => $this->pi_getLL('topic.title'),
				'###LABEL_CONFIRMMULTIPLE###' => $this->pi_getLL('havealook.confirmMultiple')
			);
			$marker = array_merge($marker, $settingsMarker);

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_options'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_options'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listFavorites_options($marker, $this);
				}
			}

			$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

			// Load favorites and start output
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'fa.topic_id,
					t.*,
					c.forum_name as cat_title,
					f.forum_name',
				'tx_mmforum_favorites fa,
					tx_mmforum_topics t,
					tx_mmforum_forums f,
					tx_mmforum_forums c',
				'fa.user_id       = '.$userId.' AND
					t.uid         = fa.topic_id AND
					f.uid         = t.forum_id AND
					c.uid         = f.parentID AND
					t.deleted     = 0 AND
					fa.deleted    = 0 '.
					$this->getStoragePIDQuery('fa,t,f,c').
					$this->getMayRead_forum_query('c').
					$this->getMayRead_forum_query('f'),
				'',
				$order
			);

			$template = $this->cObj->fileResource($conf['template.']['favorites']);

			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) {
				$template = $this->cObj->getSubpart($template, '###LIST_FAVORITES_EMPTY###');
				$marker = array(
					'###LLL_FAVORITES_EMPTY###' => $this->pi_getLL('favorites.empty')
				);
				$content .= $this->cObj->substituteMarkerArray($template, $marker);
			} else {
				$template = $this->cObj->getSubpart($template, "###LIST_FAVORITES###");

				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

					$topicParams[$this->prefixId] = array(
						'action' => 'list_post',
						'tid'    => $row['topic_id']
					);

					$row['topic_title'] = stripslashes($row['topic_title']);

					$row['topic_title'] = str_replace('<','&lt;',$row['topic_title']);
					$row['topic_title'] = str_replace('>','&gt;',$row['topic_title']);

					$topicLink = $this->pi_linkToPage($this->escape($row['topic_title']), $this->getForumPID(), '', $topicParams);
					$delParams[$this->prefixId]['fav']['deltid'] = $row['topic_id'];
					$delLink = $this->pi_linkTP($this->pi_getLL('favorites.delete'), $delParams);

					$marker['###TOPIC_CHECKBOX###'] = '<input type="checkbox" name="'.$this->prefixId.'[fav][delete][]" value="'.$row['topic_id'].'" />';
					$marker['###TOPICNAME###']      = $topicLink;
					$marker['###TOPICSUB###']       = $this->escape($row['cat_title'].' / '.$row['forum_name']);
					$marker['###TOPICDELLINK###']   = $delLink;
					$marker['###TOPICICON###']      = $this->getTopicIcon($row);

					// Include hooks
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_listItem'])) {
						foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_listItem'] as $_classRef) {
							$_procObj = & t3lib_div::getUserObj($_classRef);
							$marker = $_procObj->listFavorites_listItem($marker, $row, $this);
						}
					}

					$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
				}
			}

			$template = $this->cObj->fileResource($conf['template.']['favorites']);
			$template = $this->cObj->getSubpart($template, '###FAVORITES_END###');

			$marker = array(
				'###LABEL_MARKEDTOPICS###' => $this->pi_getLL('havealook.markedTopics'),
				'###LABEL_DELETE###'       => $this->pi_getLL('havealook.delete'),
				'###LABEL_GO###'           => $this->pi_getLL('havealook.go'),
			);

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_footer'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_footer'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listFavorites_footer($marker, $this);
				}
			}

			$content .= $this->cObj->substituteMarkerArray($template, $marker);

		} else {
			$template = $this->cObj->fileResource($conf['template.']['login_error']);
			$template = $this->cObj->getSubpart($template, '###LOGINERROR###');
			$marker = array();
			$marker['###LOGINERROR_MESSAGE###'] = $this->pi_getLL('favorites.noLogin');
			$content .= $this->cObj->substituteMarkerArray($template, $marker);
		}
		return $content;
	}


    /**
     * Forum content management helper functions
     */

    /**
     * Generates BBCode buttons.
     * This function generates a set of BBCode buttons that insert new BBcodes
     * into a post text input field using javascript. The BBCodes are loaded
     * dynamically from database.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2009-04-09
     * @param   string $template The template that is to be used for the set of
     *                           BBCode buttons
     * @return  string           The BBCode buttons
     */
    function generateBBCodeButtons($template) {

        // Load regular BBCodes
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_postparser',
            'deleted=0 AND hidden=0'
        );
        $i = 0;
        while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            if(substr($arr['title'],0,4)=='LLL:') $title = $this->pi_getLL(substr($arr['title'],4));
            else $title = $arr['title'];

            $imgpath = $this->conf['postparser.']['buttonPath'].$arr['fe_inserticon'];
            $imgpath = str_replace('EXT:mm_forum/',t3lib_extMgm::siteRelPath('mm_forum'),$imgpath);

            preg_match('/\[(.*?)\]\|\[\/(.*?)\]/',$arr['bbcode'],$items);

            $items[1] = str_replace('|','',$items[1]);
            $items[2] = str_replace('|','',$items[2]);

            $marker = array(
                '###CODE_IMAGE###'          => $imgpath,
                '###CODE_LABEL###'          => $this->escape($title),
                '###CODE_NUMBER###'         => $i,
				'###CODE_OPEN###'			=> '['.strtolower($items[1]).']',
				'###CODE_CLOSE###'			=> '[/'.strtolower($items[2]).']',
            );

            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        }
        // Load syntax highlighting data
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_syntaxhl',
            'deleted=0 AND hidden=0'
        );
        if($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
            while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                if(substr($arr['lang_title'],0,4)=='LLL:') $title = $this->pi_getLL(substr($arr['lang_title'],4));
                else $title = $arr['lang_title'];

                $imgpath = $this->conf['postparser.']['buttonPath'].$arr['fe_inserticon'];
                $imgpath = str_replace('EXT:mm_forum/',t3lib_extMgm::siteRelPath('mm_forum'),$imgpath);

                $marker = array(
                    '###CODE_IMAGE###'          => $imgpath,
                    '###CODE_LABEL###'          => $this->escape($title),
                    '###CODE_NUMBER###'         => $i,
					'###CODE_OPEN###'			=> '['.strtolower($arr['lang_code']).']',
					'###CODE_CLOSE###'			=> '[/'.strtolower($arr['lang_code']).']',
                );

                $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
            }
        }

        return $content;
    }

    /**
     * Displays a table of all available smilies loaded from database.
     * @param  array  $conf The plugin's configuration vars
     * @return string       The HTML smily table.
     */
    function show_smilie_db($conf) {
        $imgInfo = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');
        // Load smilies from database
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'smile_url,uid,code',
            'tx_mmforum_smilies',
            'deleted=0 AND hidden=0',
            'smile_url',
            'uid ASC'
        );

        $i = 0;

        // Display smilies in table, 4 smilies a row.
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){

			$smiliePath = 'uploads/tx_mmforum/'.$row['smile_url'];
			If(!file_exists($smiliePath)) $smiliePath = $conf['path_smilie'].$row['smile_url'];

            $imgInfo['src'] = $smiliePath;
            $imgInfo['alt'] = $row['code'];
            $imgInfo['title'] = $row['code'];
            if (empty($conf['jQueryEditorJavaScript'])) {
              $href= 'javascript:editor.insertSmilie(\''.$row['code'].'\')';
            } else {
              $href= '#';
            }
            if($this->conf['postForm.']['smiliesAsDiv']) {
            	$content .= $this->cObj->wrap('<a href="'.$href.'" title="'.$row['code'].'">'.$this->buildImageTag($imgInfo).'</a>',$this->conf['postForm.']['smiliesAsDiv.']['itemWrap']);
            } else {
	            if($i >= 4){
	                $content .= "\r\n</tr><tr>\r\n";
	                $i = 0;
	            }
	            $i++;
	            $content .='<td><a href="'.$href.'" title="'.$row['code'].'">'.$this->buildImageTag($imgInfo)."</a></td>\n";
            }
        }

        if($this->conf['postForm.']['smiliesAsDiv']) {
        	$content = $this->cObj->wrap($content, $this->conf['postForm.']['smiliesAsDiv.']['allWrap']);
        } else {
        	$content = '<table style="width:100%; border:0px;"><tr>'.$content.'</tr></table>';
        }

        return $content;
    }

	/**
	 * Determines the last post in a topic and updates the topic record.
	 * @param  int  $topicId The topic's UID
	 * @return void
	 */
	function update_lastpost_topic($topicId) {
		$updateArray = array(
			'topic_last_post_id' => $this->get_last_post($topicId),
			'tstamp'             => time()
		);
		$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics', 'uid = ' . intval($topicId), $updateArray);
	}

	/**
	 * Determines the last post in a board and updates the board record.
	 * @param  int  $forumId The boards's UID
	 * @return void
	 */
	function update_lastpost_forum($forumId) {
		$forumId = intval($forumId);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'tx_mmforum_posts',
			'forum_id = ' . $forumId . ' AND deleted = 0 AND hidden = 0 ' . $this->getStoragePIDQuery(),
			'',
			'crdate DESC',
			'1'
		);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

		$updateArray = array(
			'forum_last_post_id' => $row['uid'],
			'tstamp'             => time()
		);
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_forums', 'uid = ' . $forumId, $updateArray);
	}

	/**
	 * Sets the solved status of a topic.
	 * @param  int  $topicId  The UID of the topic
	 * @param  bool $solved   The desired solved status of the topic
	 * @return void
	 */
	function set_solved($topicId, $solved) {
		$updateArray = array(
			'solved'    => intval($solved),
			'tstamp'    => time()
		);
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics', 'uid = ' . intval($topicId), $updateArray);
	}

	/**
	 * Sends an e-mail to users who have subscribed a certain topic.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The configuration vars
	 * @param  int    $topic   The UID of the topic about which the users are
	 *                        to be alerted.
	 * @return void
	 * @deprecated since 0.1.8, please use the direct call to the static function
	 */
	function send_newpost_mail($content, $conf, $topicId) {
		tx_mmforum_havealook::notifyTopicSubscribers($topicId, $this);
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
	 * @deprecated since 0.1.8, please use the direct call to the static function
	 */
	function send_newpost_mail_forum($content, $conf, $topicId, $forumId) {
		tx_mmforum_havealook::notifyForumSubscribers($topicId, $forumId, $this);
	}

	/**
	 * Subordinary content functions
	 */

	/**
	 * Returns a list with the 10 last posts from user_id
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The content
	 */
	function post_history($conf) {
		if ($this->useRealUrl() && $this->piVars['fid'])
			$this->piVars['user_id'] = tx_mmforum_tools::get_userid($this->piVars['fid']);
		elseif (isset($this->piVars['user_id']) && !is_numeric($this->piVars['user_id']))
			$this->piVars['user_id'] = tx_mmforum_tools::get_userid($this->piVars['user_id']);
		elseif ( !isset($this->piVars['user_id']) )
			$this->piVars['user_id'] = $GLOBALS['TSFE']->fe_user->user['uid'];

		return tx_mmforum_user::list_user_post($conf, $this->piVars['user_id'], $this->useRealUrl() ? $this->piVars['tid'] : $this->piVars['page']);
	}

    /**
     * Displays information about a certain user, whose UID is submitted via GP-Vars.
     * @param  string $content The content of the plugin
     * @param  array  $conf    The configuration vars for the plugin
     * @return string          The new content of the plugin
     */
    function view_profil ($content,$conf){
        $imgInfo = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');

		#$user_id = intval($this->piVars['user_id']);

        if($this->useRealUrl() && $this->piVars['fid'])
			$user = tx_mmforum_FeUser::GetByUsername($this->piVars['fid']);
        else $user = tx_mmforum_FeUser::GetByUID($this->piVars['user_id']);

        $template = $this->cObj->fileResource($conf['template.']['userdetail']);
        $template = $this->cObj->getSubpart($template, "###USERDETAIL###");

		if($user === null)
            return $this->errorMessage($conf, $this->pi_getLL('user.error_notExist'));

        $marker = array();

        // Language-dependent field labels
            $marker = array(
                '###LABEL_ABOUT###'         => $this->pi_getLL('user.allAbout'),
                '###LABEL_REGDATE###'       => $this->pi_getLL('user.regDate'),
                '###LABEL_TOTALPOSTS###'    => $this->pi_getLL('user.totalPosts'),
                '###LABEL_LOCATION###'      => $this->pi_getLL('user.location'),
                '###LABEL_WEBSITE###'       => $this->pi_getLL('user.website'),
                '###LABEL_PROFESSION###'    => $this->pi_getLL('user.profession'),
                '###LABEL_INTERESTS###'     => $this->pi_getLL('user.interests'),
                '###LABEL_CONTACT###'       => $this->pi_getLL('user.contact'),
                '###LABEL_POSTHISTORY###'   => $this->pi_getLL('user.posthistory'),
                '###LABEL_10TOPICS###'      => $this->pi_getLL('user.10topics'),
                '###LABEL_AVATAR###'        => $this->pi_getLL('user.avatar'),
				'###LABEL_USERNAME###'		=> $this->pi_getLL('user.username'),
				'###LABEL_FIELD###'			=> $this->pi_getLL('user.field'),
				'###LABEL_VALUE###'			=> $this->pi_getLL('user.value'),
				'###LABEL_USERPROFILE###'	=> $this->pi_getLL('user.profile'),
				'###LABEL_RATING###'		=> $this->pi_getLL('user.rating')
            );

        // Username
            $marker['###USER###']                	= $this->escape($user->gD($this->getUserNameField()));
        // Date of registration
            $marker['###REGDATE###']				= $this->cObj->stdWrap($user->getCrdate(),$this->conf['user_profile.']['crdate_stdWrap.']);
        // Number of posts
            $marker['###TOTALPOSTS###']             = $user->getPostCount();
            if($user->getPostCount() >= $this->conf['user_hotposts']) {
                // Special icon for users with more than a certain number posts defined in TypoScript
                $llMarker = array('###HOTPOSTS###' => $this->conf['user_hotposts']);
                $str = $this->cObj->substituteMarkerArray($this->pi_getLL('user.hot'),$llMarker);
                $imgInfo['src']                     = $this->conf['path_img'].$this->conf['images.']['5kstar'];
                $imgInfo['alt']                     = $str;
                $imgInfo['title']                   = $str;
                $marker['###TOTALPOSTS###']        .= $this->buildImageTag($imgInfo);
            }

		// Rating
			if($this->isUserRating())
				$marker['###RATING###']					= $this->getRatingDisplay('fe_users', $user->getUid());
			else $template = $this->cObj->substituteSubpart($template, '###SUBP_RATING###', '');

        // Avatar
			$marker['###AVATAR###'] = "";
	   		if ($conf['path_avatar'] && $user->hasAvatar())
				$marker['###AVATAR###'] = tx_mmforum_tools::res_img($conf['path_avatar'].$user->getAvatarFilename(),$conf['avatar_width'],$conf['avatar_height']);
	   		else {
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('image','fe_users','uid = "'.$user->getUid().'"');
				$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

				if( $row['image'] ) {
		    		if( strstr($row['image'],',' ) !== false ) {
						$avatarArray = t3lib_div::trimExplode(',',$row['image']);
						$row['image'] = $avatarArray[0];
		    		}
		    
		    		if( file_exists('uploads/pics/'.$row['image']) )
						$marker['###AVATAR###'] = tx_mmforum_tools::res_img('uploads/pics/'.$row['image'],$conf['avatar_width'],$conf['avatar_height']);
		    		elseif( file_exists('uploads/tx_srfeuserregister/'.$row['image']) )
						$marker['###AVATAR###'] = tx_mmforum_tools::res_img('uploads/tx_srfeuserregister/'.$row['image'],$conf['avatar_width'],$conf['avatar_height']);

				}
			}
        // E-Mail (currently deactivated)
            $marker['###MAIL###']                   = '';  #'<a href="index.php?id='.$GLOBALS["TSFE"]->id.'&tx_mmforum_pi1[action]=send_mail&tx_mmforum_pi1[uid]='.$row['uid'].'"><img src="'.$conf['path_img'].'mail.gif" border="0"></a>';

        // Private Messaging
        if ($GLOBALS['TSFE']->fe_user->user['username'] && !((isset($conf['pm_enabled']) && intval($conf['pm_enabled']) === 0))) {
            $linkParams['tx_mmforum_pi3'] = array (
                'action'    => 'message_write',
                'folder'    => 'inbox',
                'messid'    => $this->pi_getLL('realurl.pmnew'),
                'userid'    => $user->getUID(),
            );
            $marker['###PM###']                     = $this->createButton('pm',$linkParams,$this->conf['pm_id'],true);
        } else {
          $marker['###PM###'] = '';
        }

        // A link to a page presenting the last 10 posts by this user
            $linkparams = array();
            $linkparams[$this->prefixId] = array (
                'action'    => 'post_history',
                'user_id'   => $user->getUID()
            );
            if($this->useRealUrl()) {
                unset($linkparams[$this->prefixId]['user_id']);
                $linkparams[$this->prefixId]['fid'] = $user->getUsername();
            }
            $marker['###10POSTS###']        = $this->pi_linkToPage($this->pi_getLL('user.lastPostsLink'),$GLOBALS['TSFE']->id,'',$linkparams).'<br />';

        // A list of the last 10 topic created by this user
            $marker['###10TOPICS###']        = $this->view_last_10_topics($user->getUID());

        // The number of topics created by this user (currently not used?)
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('COUNT(uid)','tx_mmforum_topics',"topic_poster='{$user->getUID()}'".$this->getStoragePIDQuery());
            list($topic_num) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            $marker['###THEMEN###']         = "<strong>".$topic_num."</strong>";

        // The last post made by this user (currently not used?)
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,topic_id','tx_mmforum_posts',"deleted='0' AND hidden='0' AND poster_id='{$user->getUID()}'".$this->getStoragePIDQuery(),'','crdate DESC','1');
            list($lastpost_id,$lastpost_topic_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title','tx_mmforum_topics',"uid='$lastpost_topic_id'".$this->getStoragePIDQuery());
            list($lastpost_topic_name) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

            $lastpost_topic_name = str_replace('<','&lt;',$lastpost_topic_name);
            $lastpost_topic_name = str_replace('>','&gt;',$lastpost_topic_name);

        // User defined fields
            $userField_template = $this->cObj->getSubpart($template, '###USERFIELDS###');
            $userField_content = '';

            $userField_private = ($this->getIsAdmin() || $this->getIsMod())?'':' AND f.public=1';

            /*$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'f.*,c.field_value',
                'tx_mmforum_userfields f, tx_mmforum_userfields_contents c',
                'f.hidden=0 AND f.deleted=0 AND c.deleted=0 AND c.field_id=f.uid AND c.user_id='.$user_id.$userField_private,
                '',
                'f.sorting DESC'
            );*/
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'tx_mmforum_userfields f',
                'f.hidden=0 AND f.deleted=0'.$userField_private,
				'',
				'sorting DESC'
            );
            $parser  = t3lib_div::makeInstance('t3lib_TSparser');
            while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $cRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'field_value',
                    'tx_mmforum_userfields_contents c',
                    'c.deleted=0 AND c.field_id='.$arr['uid'].' AND c.user_id='.$user->getUid()
                );
                if($GLOBALS['TYPO3_DB']->sql_num_rows($cRes)) {
                    list($fieldContent) = $GLOBALS['TYPO3_DB']->sql_fetch_row($cRes);
                    $arr['field_value'] = $fieldContent;
                } else $fieldContent = '';

                $parser->setup = array();
                if(strlen($arr['config'])>0) {
                    $parser->parse($arr['config']);
                    $config = $parser->setup;
                } else $config = array();

                if($config['label']) $label = $this->cObj->cObjGetSingle($config['label'],$config['label.']);
                else $label = $arr['label'].':';

                if($config['datasource']) {
                    if($config['datasource'] != 'password')
                        $fieldContent = $user->gD($config['datasource']);
                    else $fieldContent = $arr['field_value'];
                }
                else $fieldContent = $arr['field_value'];

                if($config['output']) {
                    $tmpArr = $this->cObj->data;
                    $this->cObj->data = array(
                        'fieldvalue'    => $fieldContent
                    );
                    $output = $this->cObj->cObjGetSingle($config['output'],$config['output.']);
                    $this->cObj->data = $tmpArr;
                }
                else $output = $fieldContent;

                $userField_marker = array(
                    '###LABEL_USERFIELD###'     => $label,
                    '###USERFIELD###'           => $output,
                    '###USERFIELD_VALUE###'     => $fieldContent,
                    '###USERFIELD_UID###'       => $this->escape($arr['uid']),
                    '###USERFIELD_LABEL###'     => $this->escape($arr['label']),
                    '###USERFIELD_NAME###'      => ''
                );
                $userField_content .= $this->cObj->substituteMarkerArrayCached($userField_template,$userField_marker);
            }
            $template = $this->cObj->substituteSubpart($template, '###USERFIELDS###', $userField_content);

            $marker['###ROWSPAN###'] = 15 + $GLOBALS['TYPO3_DB']->sql_num_rows($res);

        // Include hooks
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['userProfile_marker'])) {
                foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['userProfile_marker'] as $_classRef) {
                    $_procObj = & t3lib_div::getUserObj($_classRef);
                    $marker = $_procObj->userProfile_marker($marker, $user->data, $this);
                }
            }

        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        return $content;
    }

    /**
     * Lists the last 10 topics created by a specific user.
     * @param  int    $uid The UID of the user whose topics are to be listed
     * @return string      A HTML list of the last 10 topics created by the user
     */
    function view_last_10_topics($uid)
    {
        $uid = intval($uid);
        $imgInfo = array('border' => $this->conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            't.*',
            'tx_mmforum_topics t,tx_mmforum_forums f',
            't.topic_poster="'.$uid.'" AND
            t.deleted="0" AND
            t.hidden="0" AND
            f.uid=t.forum_id '.
            $this->getStoragePIDQuery('t,f').
            $this->getMayRead_forum_query('f'),
            '',
            't.crdate DESC',
            '10'
        );
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $row['topic_title'] = stripslashes($row['topic_title']);

            $row['topic_title'] = str_replace('<','&lt;',$row['topic_title']);
            $row['topic_title'] = str_replace('>','&gt;',$row['topic_title']);

            $topic_is       = $row['topic_is']?"<span class=\"tx-mmforum-pi1-listtopic-prefix\">[{$row['topic_is']}]</span> ":'';
            $imgInfo['src'] = $this->conf['path_img'].$this->conf['images.']['solved'];
            $imgInfo['alt'] = $this->pi_getLL('topic.isSolved');
            $imgInfo['title'] = $this->pi_getLL('topic.isSolved');
            $solved         = $row['solved'] ? $this->buildImageTag($imgInfo) : '';

            $linkParams[$this->prefixId] = array(
                'action'    => 'list_post',
                'tid'       => $row['uid']
            );
            if($this->useRealUrl()) {
                $linkParams[$this->prefixId]['fid'] = $row['forum_id'];
            }
            $content .= $this->formatDate($row['topic_time']).' - '.$this->pi_linkTP($topic_is.$row['topic_title'],$linkParams).$solved.'<br />';
        }
        return $content;
    }

    /**
     * Lists the last 10 posts and their topics created by a specific user.
     * @param  int    $uid The UID of the user whose posts are to be listed
     * @return string      A HTML list of the last 10 posts created by the user
     */
    function view_last_10_posts($uid) {
        $uid = intval($uid);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_posts',"poster_id='$uid' AND deleted='0' AND hidden='0'".$this->getStoragePIDQuery(),'','crdate DESC','10');
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $topic_name = $this->get_topic_name($row['topic_id']);
            $topic_name = str_replace('<','&lt;',$topic_name);
            $topic_name = str_replace('>','&gt;',$topic_name);

            $linkParams[$this->prefixId] = array(
                'action'      => 'list_post',
                'tid'         => $row['topic_id'],
                'search_pid'  => $row['uid']
            );
            $content .= $this->formatDate($row['post_time']).' - '.$this->pi_linkTP($topic_name,$linkParams).'<br />';
        }
        return $content;
    }


    /**
     * Sends an email to a specific user.
     * @param  string $content The plugin content
     * @param  array  $conf    The plugin's configuration vars
     * @return string          The content
     *
     * @deprecated Currently not used
     */
    function send_mail($content, $conf) {
        $benutzer = $GLOBALS['TSFE']->fe_user->user['username'];
        $mailtimeout    = 30;                   // Mail time Out

        if (!empty($benutzer)) {
            if ($this->piVars['send'] <> 'ok') {

                $template = $this->cObj->fileResource($conf['template.']['send_email']);
                $template = $this->cObj->getSubpart($template, "###SENDMAIL###");
                $marker = array();
                // Generate authencification code and insert into database
                    $mailcode = md5(getenv("REMOTE_ADDR").time().$this->tools->generateRandomString(10));
                    $insertArray = array(
                        'code'          => $mailcode,
                        'tstamp'        => time(),
                        'crdate'        => time(),
                        'cruser_id'     => $GLOBALS['TSFE']->fe_user->user['uid']
                    );
                    $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_mailkey',$insertArray);
                    $marker['###MAILCODE###'] =  $mailcode;

                // Retrieve user name from database
                    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($this->getUserNameField(),'fe_users',"uid = '".intval($this->piVars['uid'])."'");
                    list($usermailname) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                    $marker['###USERMAILNAME###'] =  $usermailname;
            }
            else {
                // Check if user is authorized to send emails.
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_mailkey',"code = '".$this->piVars['authcode']."' AND cruser_id = '".$GLOBALS['TSFE']->fe_user->user['uid']."'");
                $mailok = $GLOBALS['TYPO3_DB']->sql_num_rows($res);

                if ($mailok) {
                    // Load template
                    $template = $this->cObj->fileResource($conf['template.']['send_email']);
                    $template = $this->cObj->getSubpart($template, "###MAILSTATUS###");
                    $marker = array();

                    // Get recipient user data
                    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($this->getUserNameField().',email','fe_users',"uid = '".intval($this->piVars['uid'])."'");
                    list($usermailname,$to) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

                    $header .= "From: <".$GLOBALS['TSFE']->fe_user->user[$this->getUserNameField()].">".$GLOBALS['TSFE']->fe_user->user['email']."\n";
                    $header .= "Reply-To:" . $GLOBALS['TSFE']->fe_user->user['email'] . "\n";
                    $header .= "Content-type: text/plain;charset=".$GLOBALS['TSFE']->renderCharset."\n";

                    $mailtext = $this->piVars['text'];
                    $mailtext = nl2br($mailtext);

                    // Compose and send mail
                    mail($to,$this->piVars['subject'],$mailtext, $header);

                    // If "tome" is active, the sender gets a copy of the mail.
                    if ($this->piVars['uid'])  {
                        mail($GLOBALS['TSFE']->fe_user->user['email'],$this->piVars['subject'],$mailtext, $header);
                    }

                    // Remove authentification code from databse
                    $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_mailkey',"cruser_id = '".$GLOBALS['TSFE']->fe_user->user['uid']."'");

                    // Output information
                    $llMarker = array("###RECIPIENT###" => $usermailname);
                    $marker['###MAIL_MESSAGE###'] = $this->cObj->substituteMarkerArray($this->pi_getLL('mail.success'),$llMarker);
                } else {
                    // Load template
                    $template = $this->cObj->fileResource($conf["templateFile"]);
                    $template = $this->cObj->getSubpart($template, "###LOGINERROR###");

                    $marker = array();

                    // Error message
                    $llMarker = array("###TIMEOUT###" => $mailtimeout);
                    $marker['###LOGINERROR_MESSAGE###'] = $this->cObj->substituteMarkerArray($this->pi_getLL('mail.timeout'),$llMarker);
                }
            }
        }
        else {
            $template = $this->cObj->fileResource($conf['template.']['login_error']);
            $template = $this->cObj->getSubpart($template, "###LOGINERROR###");
            $marker = array();
            $marker['###LOGINERROR_MESSAGE###'] = $this->pi_getLL('mail.noLogin');
        }

        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        return $content;

    }

    /**
     * Page navigation
     */

    /**
     * Generates a dynamic page navigator suitable for many applications.
     * This function can be used to generate a general page generator that can
     * be used nearly everywhere.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-06-05
     * @param   int    $maxPage        The maximum page number
     * @param   string $linkVar_name   The name of the piVar in which the page number
     *                                 is stored.
     * @param   array  $def_linkParams An array of default link parameters. These are set
     *                                 in every link generated for the page navigator.
     * @param   int    $maxOffset      Defines over how much links the page navigator shall
     *                                 extend itself in both directions.
     * @return  string                 The page navigator
     */
    function dynamicPageNav($maxPage,$linkVar_name='page',$def_linkParams=array(),$maxOffset=4) {

        $content = $this->pi_getLL('page-goto').': ';

        if(!$this->piVars[$linkVar_name]) $this->piVars[$linkVar_name]=1;
        $curPage = $this->piVars[$linkVar_name];

        if($curPage > 1) {
            $linkParams[$this->prefixId] = $def_linkParams;
            $linkParams[$this->prefixId][$linkVar_name] = 1;

            $content .= $this->pi_linkTP(''.$this->pi_getLL('page.first').'|',$linkParams);
        }
        if($curPage > 2) {
            $linkParams[$this->prefixId] = $def_linkParams;
            $linkParams[$this->prefixId][$linkVar_name] = $curPage - 1;

            $content .= $this->pi_linkTP(' &laquo; '.$this->pi_getLL('').'|',$linkParams);
        }

        for($i = $curPage-$maxOffset; $i <= $curPage+$maxOffset; $i ++) {
            if($i < 1) continue;
            if($i > $maxPage) break;

            if($curPage == $i) {
                $content .= "|<strong> $i </strong>|";
            } else {
                $linkParams[$this->prefixId] = $def_linkParams;
                $linkParams[$this->prefixId][$linkVar_name] = $i;

                $content .= $this->pi_linkTP(' '.$i.' ',$linkParams);
            }
        }


        if($curPage < $maxPage-1) {
            $linkParams[$this->prefixId] = $def_linkParams;
            $linkParams[$this->prefixId][$linkVar_name] = $curPage + 1;

            $content .= $this->pi_linkTP(' &raquo; '.$this->pi_getLL('').' ',$linkParams);
        }
        if($curPage < $maxPage) {
            $linkParams[$this->prefixId] = $def_linkParams;
            $linkParams[$this->prefixId][$linkVar_name] = $maxPage;

            $content .= '|'.$this->pi_linkTP(''.$this->pi_getLL('page.last').' ',$linkParams);
        }

        return $content;

    }

    /**
     * Generates a page navigation menu. The number of pages is determined by the amount
     * of records in the database meeting a certain condition and the maximum number of
     * records on one page.
     * @param  string table      The table name, from which records are displayed
     * @param  string column     The table column, that has to meet a certain condition
     * @param  mixed  id         The value the table column $column has to meet
     * @param  int    limitcount The maximum number of records on one page
     * @param  int    count      Optional parameter. Allows to override the record count
     *                           determined by parameters $column and $id
     * @return string            The page navigation menu
     */
    function pagecount ($table,$column,$id,$limitcount,$count=FALSE) {
        $id = intval($id);
        $column = preg_replace("/[^A-Za-z0-9\._]/","",$column);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "COUNT($column)",
            $table,
            "deleted='0' AND hidden='0' AND $column='$id'".$this->getStoragePIDQuery()
        );
        list($postcount) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        if(! ($count === FALSE)) $postcount = intval($count);

        $maxpage = ceil($postcount / $limitcount);

        // should Dmitry's pagebrowse extension be used?
        if (intval($this->conf['doNotUsePageBrowseExtension'])===0) {
			$content = $this->getListGetPageBrowser($maxpage);
			return $content;
        }

        if ($this->piVars['page'] == 0) $page = 1;
        else $page = $this->piVars['page'];

        $linkParams = array();

        if($table == "tx_mmforum_topics") {
            if($column == 'topic_is') {
                $linkParams[$this->prefixId]['action']='list_prefix';
                if($this->piVars['list_prefix']) {
                    $settings = $this->piVars['list_prefix'];
                    $linkParams[$this->prefixId]['list_prefix']['show'] = $settings['show'];
                    $linkParams[$this->prefixId]['list_prefix']['order'] = $settings['order'];
                    $linkParams[$this->prefixId]['list_prefix']['prfx'] = $settings['prfx'];
                }
            } elseif($column != 'topic_replies') {
                $linkParams[$this->prefixId]['action'] = 'list_topic';
                $linkParams[$this->prefixId]['fid'] = $this->piVars['fid'];

				if($this->useRealUrl()) {
					$linkParams[$this->prefixId]['tid'] = 'pages';
					$linkParams[$this->prefixId]['pid'] = 'page';
				}
            } else {
                $linkParams[$this->prefixId]['action'] = 'list_unans';
            }
        }
        IF($table == "tx_mmforum_posts"){
            $linkParams[$this->prefixId]['action'] = 'list_post';
            $linkParams[$this->prefixId]['tid'] = $this->piVars['tid'];
        }

        IF ($maxpage > 1) {
            if($this->piVars['hide_solved']) $linkParams[$this->prefixId]['hide_solved']='1';

            // First page
                if (($page - 1) >= 1)           $content .= $this->pi_linkTP(''.$this->pi_getLL('page.first').' ',array_merge($linkParams,array($this->prefixId.'[page]'=>1))).'|';
            // Previous page
                if (($page - 1) > 1)            $content .= $this->pi_linkTP(' '.$this->pi_getLL('page.previous').' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page-1))).'|';
            // List pages from 2 pages before current page to 2 pages after current page
                if (($page - 2) >= 1)           $content .= '|'.$this->pi_linkTP(' '.($page-2).' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page-2))).'|';
                if (($page - 1) >= 1)           $content .= '|'.$this->pi_linkTP(' '.($page-1).' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page-1))).'|';
                $content .= '|<strong> '.$page.' </strong>|';
                if (($page + 1) <= $maxpage)    $content .= '|'.$this->pi_linkTP(' '.($page+1).' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page+1))).'|';
                if (($page + 2) <= $maxpage)    $content .= '|'.$this->pi_linkTP(' '.($page+2).' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page+2))).'|';
            // Next page
                if (($page + 1) < $maxpage)     $content .= '|'.$this->pi_linkTP(' '.$this->pi_getLL('page.next').' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page+1)));
            // Last page
                if (($page + 1) <= $maxpage)    $content .= '|'.$this->pi_linkTP(' '.$this->pi_getLL('page.last').' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$maxpage)));
        }

        #$content = preg_replace('/\|$/','',$content);
        $content = str_replace('||', '|', $content);

        return $content;

    }

    /**
     * Displays a special page navigation menu for the listing of unread topics.
     * @param  int    $lastlogin  The time of the current user's last login
     * @param  int    $limitcount The maximum number of entries to be displayed on
     *                            one page.
     * @return string             The page navigation menu
     */
    function pagecount2 ($lastlogin, $limitcount) {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'distinct tx_mmforum_topics.topic_title,
             tx_mmforum_topics.uid,
             tx_mmforum_topics.topic_poster,
             tx_mmforum_topics.topic_last_post_id,
             tx_mmforum_topics.topic_replies',
            'tx_mmforum_topics inner join tx_mmforum_posts on tx_mmforum_topics.uid = tx_mmforum_posts.topic_id',
            "tx_mmforum_topics.deleted = 0 and
             tx_mmforum_posts.deleted = 0 and
             post_time >= '$lastlogin'".$this->getStoragePIDQuery('tx_mmforum_topics,tx_mmforum_posts')
        );
        $postcount = $GLOBALS['TYPO3_DB']->sql_num_rows($res);

        $maxpage = intval($postcount / $limitcount)+1;
        if ($this->piVars['page'] == 0) $page = 1;
        else $page = $this->piVars['page'];

        $linkParams[$this->prefixId] = array("action"=>"list_unread");
        // should Dmitry's pagebrowse extension be used?
        if (intval($this->conf['doNotUsePageBrowseExtension'])===0) {
          $content = $this->getListGetPageBrowser($maxpage);
          return $content;
        }

        IF ($maxpage > 1) {
            // First page
                if (($page - 1) >= 1)           $content .= $this->pi_linkTP(' '.$this->pi_getLL('page.first').' ',array_merge($linkParams,array($this->prefixId.'[page]'=>1))).'|';
            // Previous page
                if (($page - 1) > 1)            $content .= $this->pi_linkTP(' &laquo; '.$this->pi_getLL('').' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page-1))).'|';
            // List pages from 2 pages before current page to 2 pages after current page
                if (($page - 2) >= 1)           $content .= '|'. $this->pi_linkTP(' '.($page-2).' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page-2))).'|';
                if (($page - 1) >= 1)           $content .= '|'.$this->pi_linkTP(' '.($page-1).' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page-1))).'|';
                $content .= '|<strong> '.$page.' </strong>|';
                if (($page + 1) <= $maxpage)    $content .= '|'.$this->pi_linkTP(' '.($page+1).' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page+1))).'|';
                if (($page + 2) <= $maxpage)    $content .= '|'.$this->pi_linkTP(' '.($page+2).' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page+2))).'|';
            // Next page
                if (($page + 1) < $maxpage)     $content .= '|'.$this->pi_linkTP(' '.$this->pi_getLL('').' &raquo; ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page+1)));
            // Last page
                if (($page + 1) <= $maxpage)    $content .= '|'.$this->pi_linkTP(' '.$this->pi_getLL('page.last').' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$maxpage)));
        }

        #$content = preg_replace('/\|$/','',$content);
        $content = str_replace('||', '|', $content);

        return $content;
    }

    /**
     * Forum content helper functions
     */

    /**
     * Returns a string with information on a certain post. Displays author and date of creation.
     * @param  int    $postid The UID of the post
     * @param  array  $conf   The plugin's configuration vars
     * @return string         A string containing information about the post
     */
    function getlastpost($postid,$conf,$topicTitle=false) {
        $postid = intval($postid);
        $postdata = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_posts',"uid='$postid'".$this->getStoragePIDQuery());

        if ($GLOBALS['TYPO3_DB']->sql_num_rows($postdata) == 0) $content = $this->pi_getLL('no_info');
        else {
            $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($postdata);

            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($this->getUserNameField().',deleted','fe_users','uid="'.$row['poster_id'].'"');
            if($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0)
                list($username,$deleted) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

            $link = $this->get_pid_link($postid,'',$conf);
            $link = $this->escapeURL($link);

            $posttime = $this->cObj->stdWrap($row['post_time'],$this->conf['list_topics.']['lastPostDate_stdWrap.']);
            $posttime = '<a href="'.$link.'">'.$posttime.'</a>';

            if(!$username)
                $usrlink = $this->pi_getLL('user.deleted');
            elseif(!$deleted)
                $usrlink = $this->linkToUserProfile($row['poster_id']);
            else $usrlink = $this->escape($username);

            if($topicTitle && $this->conf['list_topics.']['lastPostTopicTitle']) {
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title','tx_mmforum_topics','uid='.$row['topic_id'].' AND deleted=0');
                if($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
                    list($topicname) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                    $topicname = $this->escape($topicname);
                    $topicname = $this->cObj->stdWrap($topicname,$this->conf['list_topics.']['lastPostTopicTitle_innerStdWrap.']);
                    $title = '<a href="'.$link.'">'.$topicname.'</a>';
                }
                $title = $this->cObj->stdWrap($title,$this->conf['list_topics.']['lastPostTopicTitle_outerStdWrap.']);
            } else $title = '';

            $usrlink = $this->cObj->stdWrap($usrlink,$this->conf['list_topics.']['lastPostUserName_stdWrap.']);

            $content = $title.$posttime.$usrlink;
        }

        // Include hooks
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['viewLastPost_postContentHook'])) {
                foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['viewLastPost_postContentHook'] as $_classRef) {
                    $_procObj = & t3lib_div::getUserObj($_classRef);
                    $content = $_procObj->viewLastPost_postContentHook($content, $row, $this);
                }
            }

        return $content;
    }

	/**
	 * Returns a link to a user's profile page
	 * @param  int    $userId The UID of the user, to whose profile page is to be linked
	 * @return string         The HTML-Link tag
	 */
	function getauthor($userId) {
		$userData = tx_mmforum_tools::get_userdata($userId);
		if ($userData === false) {
			$content = $this->pi_getLL('user.deleted');
		} else {
			if (!$userData['deleted']) {
				$content = $this->linkToUserProfile($userData);
			} else {
				$content = $this->escape($userData[$this->getUserNameField()]);
			}
		}

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['profileLink_postContentHook'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['profileLink_postContentHook'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$content = $_procObj->profileLink_postContentHook($content, $userData, $this);
			}
		}
		return $content;
	}

	/**
	 * Returns the title of a specific topic determined by UID.
	 * @param  int    $topicId The UID of the topic
	 * @return string          The title of the topic
	 */
	function get_topic_name($topicId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title', 'tx_mmforum_topics', 'uid=' . intval($topicId) . $this->getStoragePIDQuery());
		list($name) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $name;
	}

	/**
	 * Outputs information about a specific user for the post listing.
	 * @param  int    $uid          The UID of the user whose information are to be displayed
	 * @param  array  $conf         The configuration vars of the plugin
	 * @param  bool   $threadauthor TRUE, if the user is the author of the thread displayed. In
	 *                              this case, a special string telling that this user is the author
	 *                              of the thread is displayed.
	 * @return string               The user information
	 */
	function ident_user($uid, $conf, $threadauthor = FALSE) {
		$userData = (!is_array($uid) ? tx_mmforum_tools::get_userdata($uid) : $uid);

		$template = $this->cObj->fileResource($this->conf['template.']['list_post']);
		$template = $this->cObj->getSubpart($template, '###USERINFO###');

		if ($template) {
			$avatar = $this->getUserAvatar($userData);

			$marker = array(
				'###LLL_DELETED###'		=> $this->pi_getLL('user-deleted'),
				'###USERNAME###'		=> $this->cObj->stdWrap($userData[$this->getUserNameField()], $this->conf['list_posts.']['userinfo.']['username_stdWrap.']),
				'###USERREALNAME###'	=> $this->cObj->stdWrap($userData['name'], $this->conf['list_posts.']['userinfo.']['realname_stdWrap.']),
				'###USERRANKS###'		=> $this->get_userranking($uid, $conf),
				'###TOPICCREATOR###'	=> ($uid == $threadauthor ? $this->cObj->stdWrap($this->pi_getLL('topic-topicauthor'),$this->conf['list_posts.']['userinfo.']['creator_stdWrap.']) : ''),
				'###AVATAR###'			=> $avatar,
				'###LLL_REGSINCE###'	=> $this->pi_getLL('user-regSince'),
				'###LLL_POSTCOUNT###'	=> $this->pi_getLL('user-posts'),
				'###REGSINCE###'		=> $this->cObj->stdWrap($userData['crdate'], $this->conf['list_posts.']['userinfo.']['crdate_stdWrap.']),
				'###POSTCOUNT###'		=> intval($userData['tx_mmforum_posts']),
				'###USER_RATING###'		=> $this->isUserRating() ? $this->getRatingDisplay('fe_users', $userData['uid']) : ''
			);
			if ($userData === false) {
				$template = $this->cObj->substituteSubpart($template, '###USERINFO_REGULAR###', '');
			} else {
				$template = $this->cObj->substituteSubpart($template, '###USERINFO_DELETED###', '');
			}

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['userInformation_marker'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['userInformation_marker'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->userInformation_marker($marker, $userData, $this);
				}
			}

			$content .= $this->cObj->substituteMarkerArray($template, $marker);
		} else {
			if ($userData === false) {
				return '<strong>' . $this->pi_getLL('user.deleted') . '</strong>';
			}

			$content = '<strong>' . $userData[$this->getUserNameField()] . '</strong><br />';

			if ($this->conf['list_posts.']['userinfo_realName'] && $userData['name']) {
				$content .= $this->cObj->wrap($userData['name'], $this->conf['list_posts.']['userinfo_realName_wrap']);
			}

			$userranking = $this->get_userranking($uid, $conf) . '<br />';
			if ($uid == $threadauthor) {
				$userranking .= $this->cObj->wrap($this->pi_getLL('topic.topicauthor'),$this->conf['list_posts.']['userinfo_topicauthor_wrap']);
			}
			$content .= $userranking;

			if ($userData['tx_mmforum_avatar']) {
				$content .= tx_mmforum_tools::res_img($conf['path_avatar'] . $userData['tx_mmforum_avatar'], $conf['avatar_height'], $conf['avatar_width']);
			}

			$content .= $this->cObj->wrap($this->pi_getLL('user.regSince') . ': ' . date('d.m.Y', $userData['crdate']) . '<br />' . $this->pi_getLL('user.posts') . ': ' . $userData['tx_mmforum_posts'], $this->conf['list_posts.']['userinfo_content_wrap']);
		}

		return $content;
	}

	/**
	 * Returns the avatar of the user as a <img...> HTML tag
	 *
	 * @param  array	the user data array
	 * @return string   the HTML tag
	 */
	function getUserAvatar($userData) {
		$avatarImg = trim($userData['tx_mmforum_avatar']);
		$feuserImg = trim($userData['image']);
		$imgConf = $this->conf['list_posts.']['userinfo.']['avatar_cObj.'];

		$img = '';
		if ($avatarImg || $feuserImg) {
			if ($avatarImg) {
				$imgConf['file'] = $this->conf['path_avatar'] . $avatarImg;
			} else {
				// only take the first image, if there are multiple ones
				if (strpos($feuserImg, ',') !== false) {
					list($feuserImg) = t3lib_div::trimExplode(',', $feuserImg);
				}
				if (file_exists('uploads/pics/' . $feuserImg)) {
					$imgConf['file'] = 'uploads/pics/' . $feuserImg;
				} else if (file_exists('uploads/tx_srfeuserregister/' . $feuserImg)) {
					$imgConf['file'] = 'uploads/tx_srfeuserregister/' . $feuserImg;

				}
			}
			$img = $this->cObj->cObjGetSingle($this->conf['list_posts.']['userinfo.']['avatar_cObj'], $imgConf);
		}
		return $img;
	}

    /**
     * Creates a rootline menu for navigating over board and board category when in
     * topic view.
     * @param  int    $forumid The UID of the current board.
     * @param  int    $topicid The UID of the current topic.
     * @return string          The rootline menu
     */
    function get_forum_path ($forumid,$topicid) {
        $forumpath_index        = $this->pi_linkTP($this->pi_getLL('board.rootline'));
        $forum_id                = intval($forumid);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "parentID, forum_name",
            "tx_mmforum_forums",
            "uid = '".$forumid."'".$this->getStoragePIDQuery()
        );
        list($catid, $forumpath_forum)    = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "forum_name",
            "tx_mmforum_forums",
            "uid = '".$catid."'".$this->getStoragePIDQuery()
        );
        list($forumpath_category)        = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "topic_title",
            "tx_mmforum_topics",
            " uid = '".$topicid."'".$this->getStoragePIDQuery()
        );
        list($forumpath_topic)            = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        if ( $forumpath_category)    $forumpath_category   = $this->conf['display.']['rootline.']['separator'].'<a href="'.$this->pi_getPageLink($GLOBALS['TSFE']->id).'#cat'.$catid.'">'.$this->escape($forumpath_category).'</a>';
        if ( $forumpath_forum)       $forumpath_forum      = $this->conf['display.']['rootline.']['separator'].$this->pi_linkTP($this->escape($forumpath_forum),array('tx_mmforum_pi1[action]'=>'list_topic','tx_mmforum_pi1[fid]'=>$forumid));
        if ( $forumpath_topic)       $forumpath_topic      = $this->conf['display.']['rootline.']['separator'].$this->escape($forumpath_topic);

        $pathcontent = $forumpath_index.$forumpath_category.$forumpath_forum;
        return $pathcontent;

    }

	/**
	 * Substitutes BBCode in a text to conventional HTML.
	 * @param  string $text The text in which the BBCode is to be translated.
	 * @param  array  $conf The configuration vars of the plugin.
	 * @return string       The text with translated BBCodes
	 * @deprecated This method actually is not necessary anymore, since it just
	 *             delegates the task to class postparser.
	 */
	function bb2text($text, $conf) {
		return tx_mmforum_postparser::main($this, $conf, $text, 'textparser');
	}

    /**
     * Displays a list of topics not yet read by the current user.
     * Returns the tx_mmforum_posts.topic_id or tx_mmforum_posts.forum_id of all unread posts/forums
     * in a non-assoc array.
     *
     * To limit the amount of retrieved IDs, the $filter param has to be used.
     * It supports the following settings:
     * 	$filter['forum_id'] == array of forum IDs which are of interest.
     * 						This is e.g. used in list_forum where the viewed at posts are
     * 						limited to those which are actually displayed in that overview.
     *  $filter['topic_id'] == array of topic ids to check. Useful for _all_ views which are displaying only a subset of topics
     *  					E.g. list_topic, list_unanswered, etc. This will effectively look only at those posts in question.
     *  $filter['onlyCategories'] == if set to 1, the result will be the forum_ids instead of topic_ids.
     *  					Useful again for the forum list where we are not interested in unread posts but in unread forums.
     *
     *
     * @param $content - unused, kept for backwards compatibility
     * @param $conf - unused, kept for backwards compatibility
     * @param $lastlogin - $GLOBALS['TSFE']->fe_user->user['tx_mmforum_prelogin'] == The time when a user last pressed "Mark all read"
     * @param $filter - array used to limit the returned posts to those of interest. See method description
     * @return array non-assoc
     */
    function getunreadposts ($content, $conf, $lastlogin, $filter = array()) {
//    	$starttime = microtime(true);
		$uid        = $GLOBALS['TSFE']->fe_user->user['uid'];

		if(!$uid) return array();

		if (is_array($filter['forum_id'])) {
			$where = '(forum_id=' . implode(' OR forum_id=', $filter['forum_id']). ') AND ';
		} else if (is_array($filter['topic_id'])) {
			$where = '(topic_id=' . implode(' OR topic_id=', $filter['topic_id']). ') AND ';
		} else {
			$where = '';
		}

    	$where .= 'deleted = 0 AND crdate > '.intval($lastlogin).' AND a.topic_id NOT IN (SELECT topic_id FROM tx_mmforum_postsread WHERE user='.intval($uid).$this->getStoragePIDQuery(). ')';
    	//debug ($where, 'where');
    	if ($filter['onlyCategories']) {
    		$select = 'distinct(forum_id)';
    	} else {
    		$select = 'distinct(topic_id)';
    	}
		$unread    = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		    $select ,
            'tx_mmforum_posts a',
            $where
         );

        $res = array();
        while ($row = mysql_fetch_assoc($unread)) {
        	if ($filter['onlyCategories']) {
        		$res[] = $row['forum_id'];
        	} else {
        		$res[] = $row['topic_id'];
        	}
         }
    	//t3lib_div::debug (count($res), 'number of unread posts : took ms: ' . (microtime(true) - $starttime));
    	return $res;
	}

	/**
	 * Marks all unread posts as read.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The configuration vars of the plugin
	 * @return string          An error message in case the redirect attempt to
	 *                         the previous page fails.
	 */
	function reset_unreadpost($content, $conf) {
		// Executing database operations
		$updateArray = array(
			'lastlogin'           => time(),
			'tx_mmforum_prelogin' => time(),
			'tstamp'              => time()
		);
		$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users', 'uid = ' . $GLOBALS['TSFE']->fe_user->user['uid'], $updateArray);

		// Redirecting visitor back to previous page
		$ref = t3lib_div::getIndpEnv('HTTP_REFERER');
		if ($ref) {
			$ref = $this->tools->getAbsoluteUrl($ref);
			header('Location: ' . t3lib_div::locationHeaderUrl($ref));
			exit();
		}
		$content = $this->pi_getLL('board.markedAllRead') . '<br />' . $this->pi_getLL('redirect.error') . '<br />';
		return $content;
	}

    /**
     * Highlights certain words in a text. Highlighting is done by applying a
     * specific wrap defined in TypoScript (plugin.tx_mmforum_pi1.list_posts.highlight_wrap).
     * @param  string $text  The text, in which the words are to be highlighted
     * @param  array  $words An array of words that are to be highlighted
     * @return string        The text with highlighted words.
     */
    function highlight_text ($text,$words)
    {
        $word_array = explode(" ",$words);
        foreach ($word_array as $needle) {
            if(trim($needle) != "")
            {
                $needle      = preg_quote($needle);
                $needle      = str_replace('/','\\/',$needle);

                $check       = preg_match_all("/<(.*?)$needle(.*?)>/i", $text, $htmltags);
                $placemark   = chr(1).chr(1).chr(1);
                $text        = preg_replace("/<(.*?)$needle(.*?)>/i", $placemark, $text);

                $replace     = $this->cObj->wrap('\\0',$this->conf['list_posts.']['highlight_wrap']);
                $text        = preg_replace("/$needle/i", $replace, $text);

                if(count($htmltags[0])>0) {
                    foreach($htmltags[0] as $htmltag) {
                        $text = preg_replace('/'.$placemark.'/',"$htmltag", $text, 1);
                    }
                }
            }
        }
        return $text;
    }

	/**
     * Builds a link to a specific post by determining the topic and the post's
     * page in this topic by post UID.
     * @param  int    $post_id The post UID
     * @param  string $sword   The search word
     * @param  array  $conf    The plugin's configuration vars
     * @return string          The URL
     */
	function get_pid_link ($post_id, $sword, $conf) {
		$post_id = intval($post_id);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'topic_id,forum_id',
				'tx_mmforum_posts',
				'deleted=0 AND hidden=0 AND uid=\'' . $post_id . '\'' . $this->getStoragePIDQuery()
			);
		list($topic_id, $forum_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'uid',
				'tx_mmforum_posts',
				'deleted=0 AND hidden=0 AND topic_id=\'' . $topic_id . '\'' . $this->getStoragePIDQuery(),
				'',
				'post_time'
			);

		$pos = array_search(array('uid' => (string)$post_id), $rows);
		$pos = ($pos === false ? 0 : ($pos + 1));	// pos is id -> increase by one
		$page = (int)ceil($pos / $conf['post_limit']);
		
		$linkparams[$this->prefixId] = array (
			'action'    => 'list_post',
			'tid'       => $topic_id,
			'page'      => $page - 1
		);
		if($linkparams[$this->prefixId]['page'] < 1) {
			unset($linkparams[$this->prefixId]['page']);
		}
		if($this->useRealUrl()) {
			$linkparams[$this->prefixId]['fid'] = $forum_id;
			$linkparams[$this->prefixId]['pid'] = $this->pi_getLL('realurl.page');
		}

		if($sword) $linkparams[$this->prefixId]['sword'] = $sword;

		$link = $this->pi_getPageLink($this->getForumPID(), '', $linkparams);
		return $link . '#pid' . $post_id;
	}

	/**
	 * Returns the prefix of a certain topic.
	 * @param  int    $topicId  The UID of the topic
	 * @return string           The prefix of the topic
	 */
	function get_topic_is($topicId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_is', 'tx_mmforum_topics', 'uid=' . intval($topicId) . $this->getStoragePIDQuery());
		list($row) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $row;
	}


	/**
	 * Returns the userranking of the user determined by the user's
	 * usergroup.
	 * @param  int    $userId The user's UID
	 * @param  array  $conf   The plugin's configuration vars
	 * @return string         The user's ranking.
	 **/
	function get_userranking($userId, $conf) {
		$userRanksObj = t3lib_div::makeInstance('tx_mmforum_ranksFE');
		$userRanksObj->init($this);
		$userRanksObj->setContentObject($this->cObj);
		return $userRanksObj->displayUserRanks($userId);
	}

    /**
     * Displays a configuration form for users.
     * NOTE: This function is not used yet, since there are too few settings to
     *       be set.
     * @param  array  $conf  The plugin's configuration vars
     * @param  array  $param The parameters for this function
     * @return string        The configuration form
     */
    function user_config($conf,$param)
    {
        // Save changes
            IF ($param['save']) {
                if($param['postorder'] == 2) $post_sort = 'DESC';
                else $post_sort = 'ASC';

                $updateArray = array(
                    'tstamp'    => time(),
                    'post_sort' => $post_sort,
                    'ip'        => getenv("REMOTE_ADDR")
                );
                $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_userconfig', 'userid='.$GLOBALS['TSFE']->fe_user->user['uid'] , $updateArray);
            }

        $template = $this->cObj->fileResource($conf['template.']['userconf']);
        $template = $this->cObj->getSubpart($template, "###USERCONF###");

        $marker = array(
            '###ACTIONLINK###'                => $this->pi_getPageLink($GLOBALS["TSFE"]->id),
            '###LABEL_USERCONF###'            => $this->pi_getLL('user.userConf'),
            '###LABEL_POSTORDER###'           => $this->pi_getLL('user.postOrder'),
            '###LABEL_POSTFIRST###'           => $this->pi_getLL('user.postOrder.postFirst'),
            '###LABEL_SAVE###'                => $this->pi_getLL('user.save')
        );

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_userconfig','userid="'.$GLOBALS['TSFE']->fe_user->user['uid'].'"');

        // If there is no config record for current user, create one.
            if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) {
                $insertArray = array(
                    'tstamp'        => time(),
                    'crdate'        => time(),
                    'userid'        => $GLOBALS['TSFE']->fe_user->user['uid'],
                    'ip'            => t3lib_div::getIndpEnv("REMOTE_ADDR")
                );
                $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_userconfig',$insertArray);
            } else {
                $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
            }

        // Sorting order of posts
            IF($row['post_sort'] == "DESC") {
                $select1 = '';
                $select2 = 'selected';
            } else {
                $select1 = 'selected';
                $select2 = '';
            }
            $marker['###POSTORDER###'] .= '<select class="tx-mmforum-select" name="tx_mmforum_pi1[postorder]">';
            $marker['###POSTORDER###'] .= '<option value="1" '.$select1.'>'.$this->pi_getLL('user.postOrder.first').'</option>';
            $marker['###POSTORDER###'] .= '<option value="2" '.$select2.'>'.$this->pi_getLL('user.postOrder.last').'</option>';
            $marker['###POSTORDER###'] .= '</select>';


        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        return $content;
    }

	/**
	 * Redirects to the last page of a certain topic
	 * @param  string $content The plugin content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return void
	 */
	function open_topic($content, $conf) {
		$topicId = intval($this->piVars['id']);
		$linkParams[$this->prefixId] = array(
			'action' => 'list_post',
			'tid'    => $topicId,
			'pid'    => 'last'
		);
		$linkto = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams);
		$linkto = $this->tools->getAbsoluteUrl($linkto);
		header('Location: ' . t3lib_div::locationHeaderUrl($linkto));
		exit();
	}

	/**
	 * Read all favorites of a specific user into an array.
	 * @param  int   $userId  The user UID. If no user-ID is submitted, the current user's
	 *                        UID is used instead.
	 * @return array          The user's favorites
	 */
	function get_user_fav($userId = 0) {
		$userId = intval($userId ? $userId : $GLOBALS['TSFE']->fe_user->user['uid']);
		$userFavorites = array();

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_id', 'tx_mmforum_favorites', 'user_id = ' . $userId . $this->getStoragePIDQuery());
		while (list($favoritesId) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
			$userFavorites[] = $favoritesId;
		}
		return $userFavorites;
	}

	/**
	 * Determines the topic UID of a specific post.
	 * @param  int $postId  The post UID
	 * @return int          The topic UID
	 */
	function get_topic_id($postId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_id', 'tx_mmforum_posts', 'uid = ' . intval($postId) . $this->getStoragePIDQuery());
		list($topicId) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $topicId;
	}

	/**
	 * Returns the board UID of a topic
	 * @param  int $topicId The topic UID
	 * @return int          The board UID
	 **/
	function get_forum_id($topicId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('forum_id', 'tx_mmforum_topics', 'uid = ' . intval($topicId) . $this->getStoragePIDQuery());
		list($forumId) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $forumId;
	}


    /**
     * Returns a select box with a tree view of all categories and boards. The
     * board of the topic specified in $topic_id is selected.
     * @param  int    $topic_id The board containing the topic specified by this UID is
     *                          selected.
     * @return string           The HTML select box with all categories and boards.
     */
    function get_forumbox($topic_id)
    {
        $forum_id    = $this->get_forum_id($topic_id);

        $content    = '<select class="tx-mmforum-select" name="'.$this->prefixId.'[change_forum_id]" size="12">';

        // Load categories
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
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
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $content .= '<optgroup label="'.$this->escape($row['forum_name']).'">';

            // Load boards
            $res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
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
            while ($row2 = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res2)) {
                IF ($row2['uid'] == $forum_id) {
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
	 * Returns the UID of the last post in a topic.
	 * @param  int $topic_id The topic UID
	 * @return int           The UID of the last post
	 */
	function get_last_post($topicId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'tx_mmforum_posts',
			'topic_id = ' . intval($topicId) . ' AND deleted = 0 AND hidden = 0' . $this->getStoragePIDQuery(),
			'',
			'crdate DESC',
			'1'
		);
		list($lastPostId) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $lastPostId;
	}

	/**
	 * Returns the user UID of a specific username.
	 *
	 * @deprecated Use tx_mmforum_tools::get_userid instead
	 * @param  string $username The username, whose user UID is to be determined.
	 * @return int              The user UID of $username.
	 */
	function get_userid($username) { return tx_mmforum_tools::get_userid($username); }

	/**
	 * Generates an error message.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  array  $conf The plugin's configuration vars
	 * @param  string $msg  The error message
	 * @return string       The HTML error message
	 */
	function errorMessage($conf, $message) {
		$templateFile = $this->cObj->fileResource($conf['template.']['login_error']);
		$template     = $this->cObj->getSubpart($templateFile, '###LOGINERROR###');
		$marker       = '';
		if (is_array($message)) {
			foreach ($message as $singleMessage) {
				$marker .= $this->cObj->stdWrap($singleMessage, $conf['errorMessage.']);
			}
		} else {
			$marker = $this->cObj->stdWrap($message, $conf['errorMessage.']);
		}
		return $this->cObj->substituteMarker($template, '###LOGINERROR_MESSAGE###', $marker);
	}

	/**
	 * Generates a success message.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  array  $conf The plugin's configuration vars
	 * @param  string $msg  The success message
	 * @return string       The HTML success message
	 */
	function successMessage($conf, $msg) {
		$template = $this->cObj->fileResource($conf['template.']['login_error']);
		$template = $this->cObj->getSubpart($template, '###SUCCESSNOTICE###');
		$marker = array();
		$marker['###LOGINERROR_MESSAGE###'] = $msg;
		return $this->cObj->substituteMarkerArrayCached($template, $marker);
	}

	/**
	 * Returns a directory name dependent of the website language defined in
	 * config.language
	 * The directory name corresponds with the official 2-byte abbreviation
	 * of a country (e.g. 'de' for Germany or 'dk' for Denmark).
	 * If the website language is English or not set, 'default/' is returned.
	 * @return string  A directory name corresponding to the website language.
	 *                 If the language is English or not set, 'default/' is returned.
	 */
	function getLanguageFolder() {
		$folder = 'default';
		if (isset($GLOBALS['TSFE']->config['config']['language'])
			&& $GLOBALS['TSFE']->config['config']['language'] != 'en') {
			$folder = $GLOBALS['TSFE']->config['config']['language'];
		}
		return $folder . '/';
	}

	/**
	 * Loads a topic record from database.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 11. 01. 2007
	 * @param   int   $topicId The topic UID
	 * @return  array          The topic record as associative array.
	 */
	function getTopicData($topicId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tx_mmforum_topics',
			'uid = ' . intval($topicId) . ' AND deleted = "0" AND hidden = "0"' . $this->getStoragePIDQuery()
		);
		return (($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) ? false : $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res));
	}

	/**
	 * Generates a topic icon.
	 * The icon generated depends on various topic attributes such as
	 * read/closed status etc.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 11. 01. 2007
	 * @param   mixed  $topic The topic data. May either be a topic UID or a topic record
	 *                        as associative array.
	 * @return  string        The topic icon as HTML img tag
	 */
	function getTopicIcon($topic, $readarray=-1) {
		if (!is_array($topic)) {
			$topic = $this->getTopicData(intval($topic));
		}
		$userId = intval(isset($GLOBALS['TSFE']->fe_user->user['uid']) ? $GLOBALS['TSFE']->fe_user->user['uid'] : 0);

		if ($userId && $readarray==-1) {
			if(!isset($GLOBALS['tx_mmforum_pi1']['readarray'])) {
				$lastlogin = $GLOBALS['TSFE']->fe_user->user['tx_mmforum_prelogin'];
				$readarray = $this->getunreadposts('', $this->conf, $lastlogin);

				$GLOBALS['tx_mmforum_pi1']['readarray'] = $readarray;
			} else {
				$readarray = $GLOBALS['tx_mmforum_pi1']['readarray'];
			}
		} else if (!is_array($readarray)) $readarray = array();

		$isNew    = in_array($topic['uid'], $readarray);
		$isHot    = ($this->conf['hotposts'] > 0) ? ($topic['topic_replies'] >= $this->conf['hotposts']) : false;
		$isClosed = ($topic['closed_flag'] == '1');
		$isUnanw  = ($topic['topic_replies'] == 0);
		$isPinned = ($topic['at_top_flag'] == '1');
		$isSolved = ($topic['solved'] == '1');

		$topicIconMode = $this->getTopicIconMode();

		if ($topicIconMode == 'modern') {
			$dataArray = array(
				'unread'        => $isNew,
				'hot'           => $isHot,
				'closed'        => $isClosed,
				'unanswered'    => $isUnanw,
				'solved'        => $isSolved,
				'pinned'        => $isPinned
			);
			$oldData = $this->cObj->data;
			$this->cObj->data = $dataArray;
			$image = $this->cObj->cObjGetSingle($this->conf['topicIcon'], $this->conf['topicIcon.']);
			$this->cObj->data = $oldData;

			return $image;
		} elseif($topicIconMode == 'classic') {
			$imgname    = 'topicicon';
			if ($isPinned) {
				$imgname .= '_pinned';
			}
			if ($isClosed) {
				$imgname .= '_closed';
			} elseif ($isHot) {
				$imgname .= '_hot';
			} elseif ($isUnanw) {
				$imgname .= '_unanswered';
			}
			if ($isNew) {
				$imgname .= '_new';
			}

			if ($topic['shadow_tid'] > 0) {
				$imgname = 'topicicon_shadow';
			}

			$imgInfo = array(
				'src'        => $this->conf['path_img'] . $this->conf['images.'][$imgname],
				'alt'        => $this->pi_getLL('topic.' . $imgname),
				'title'      => $this->pi_getLL('topic.' . $imgname)
			);
			return $this->buildImageTag($imgInfo);
		}
	}

    /**
     * Generates a forum icon.
     * This function dynamically generates a forum icon.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-03-17
     * @param   boolean $isClosed Defines if the forum is not writeable
     * @param   boolean $isNew    Defines if the forum contains unread posts
     * @return  string            The HTML code of the image
     */
    function getForumIcon($forum=null, $isClosed=false, $isNew=false) {

        $topicIconMode = $this->getTopicIconMode();

		if($forum !== null) {
			if(!is_array($forum))
				$forum = $this->getBoardData($forum);

			if ($GLOBALS['TSFE']->fe_user->user['uid']) {
	            if(!isset($GLOBALS['tx_mmforum_pi1']['readarray'])) {
	                $resunread = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
	                    'tx_mmforum_prelogin as lastlogin',
	                    'fe_users',
	                    'uid="'.$GLOBALS['TSFE']->fe_user->user['uid'].'"'
	                );
	                $rowunread = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resunread);
	                $lastlogin = $rowunread['lastlogin'];
	                $readarray = $this->getunreadposts('', $this->conf, $lastlogin);

	                $GLOBALS['tx_mmforum_pi1']['readarray'] = $readarray;
	            }
	            else $readarray = $GLOBALS['tx_mmforum_pi1']['readarray'];
	        }
	        else $readarray = array();

	        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_topics','forum_id="'.$forum['uid'].'"'.$this->getStoragePIDQuery());
            $blnnew = false;

            while($row_topic = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                if(in_array($row_topic['uid'], $readarray)) {
                    $blnnew = true;
                    break;
                }
            }

            $isNew = $blnnew;
            $isClosed = !$this->getMayWrite_forum($forum);
		}

        if($topicIconMode == 'modern') {
            $dataArray = array(
                'unread'        => $isNew,
                'hot'           => 0,
                'closed'        => $isClosed,
                'unanswered'    => 0,
                'solved'        => 0
            );
            $oldData = $this->cObj->data;
            $this->cObj->data = $dataArray;

			If($this->conf['forumIcon'])
            	$image = $this->cObj->cObjGetSingle($this->conf['forumIcon'],$this->conf['forumIcon.']);
            Else $image = $this->cObj->cObjGetSingle($this->conf['topicIcon'],$this->conf['topicIcon.']);

            $this->cObj->data = $oldData;

            return $image;
        } elseif($topicIconMode == 'classic') {
            $filename = ($isClosed?'closed_':'').'forum'.($isNew?'_new':'');

            $imgInfo = array(
                'src'		=> $this->conf['path_img'].$this->conf['images.'][$filename],
                'alt'		=> $this->pi_getLL('board.'.$filename),
                'title'		=> $this->pi_getLL('board.'.$filename)
            );
            return $this->buildImageTag($imgInfo);
        }
    }

	/**
	 * Generates a link to mark all topics in a board as read.
	 * Is only displayed when a fe_user is logged in.
	 *
	 * @return	string		The link to mark all topics as read
	 */
	function getMarkAllRead_link() {
		if ($GLOBALS['TSFE']->fe_user->user) {
			$linkParams[$this->prefixId] = array(
				'action' => 'reset_read'
			);
			return $this->pi_linkToPage($this->pi_getLL('board.markAllRead'), $GLOBALS['TSFE']->id, '', $linkParams) . '<br />';
		} else {
			return '';
		}
	}

    /**
     * Outputs a file attachment.
     * This function outputs a file attachment whose UID is submitted
     * via parameter. For security reasons, attachment links may not point
     * to the attachment files directly. Instead, this function checks if the
     * user that is currently logged in is allowed to open this attachment.
     * Only if this check is passed, the attachment file will be output
     * directly to the browser.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-21
     * @return  void
     */
    function getAttachment() {
        if($this->useRealUrl()) {
            $aUID = $this->piVars['fid'];
            $aUID = str_replace($this->pi_getLL('realurl.attachment'),'',$aUID);
        } else
            $aUID = $this->piVars['attachment'];
        $aUID = intval($aUID);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_attachments',
            'uid='.$aUID.' AND deleted=0'
        );
        if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0)
            return $this->errorMessage($this->conf,$this->pi_getLL('attachment.doesNotExist'));

        $a = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

        if(@file_exists($a['file_path']) /*&& $this->getMayRead_post($a['post_id'])*/) {
            $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_attachments SET downloads = downloads + 1 WHERE uid='.$aUID);

            header('Content-Type: '.$a['file_type']);
            header('Content-Length: '.filesize($a['file_path']));
            header('Content-Disposition: attachment; filename="'.$a['file_name'].'"');

            readfile($a['file_path']);
            die();
        } else {
            return $this->errorMessage($this->conf,$this->pi_getLL('attachment.doesNotExist'));
        }
    }

    /**
     * User rights management
     */

	/**
     * Generates a MySQL-query to determine in which boards the current user may read.
     * @return string  A MySQL-WHERE-query, beginning with "AND", checking which boards the
     *                 user that is currently logged in may read in.
     * @author Martin Helmich <m.helmich@mittwald.de>
     */
	function getMayRead_forum_query($prefix = '') {

		$userId = $this->getUserID();

		// First search for query in cache. In case of a hit, just return the result.
		$cacheRes = $this->cache->restore('getMayRead_forum_query_' . $userId . '_' . $prefix);
		if($cacheRes !== null) {
			return $cacheRes;
		}

		// If the user is an administrator, just return a dummy query.
		if($this->getIsAdmin()) return ' AND 1 ';

		// If no user is logged in, select only boards where no read access is specified. */
		$dprefix = (strlen($prefix) > 0) ? $prefix . '.' : '';
		if(!$GLOBALS['TSFE']->fe_user->user) {
			$this->cache->save('getMayRead_forum_query_' . $userId . '_' . $prefix, $query = ' AND (' . $dprefix . 'grouprights_read=\'\')');
			return $query;
		}

		// Get all groups the current user is a member of.
		$groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
		$groups = tx_mmforum_tools::processArray_numeric($groups);

		// If the user is not in any group, build a subquery that always returns FALSE.
		if(!is_array($groups) || count($groups) == 0) {
			$queryParts = '1=2';
		}

		// Otherwise check the intersection between the user's groups and the groups with read access.
		else {
			foreach($groups as $group) {
				$queryParts[] = 'FIND_IN_SET(' . $group . ', ' . $dprefix . 'grouprights_read)';
			}
		}

		$query = is_array($queryParts) ? implode(' OR ', $queryParts) : $queryParts;
		$query = ' AND ((' . $query . ') OR ' . $dprefix . 'grouprights_read=\'\') ';

		// Store query to cache and return.
		$this->cache->save('getMayRead_forum_query_'.$userId.'_'.$prefix,$query);
		return $query;
	}

	/**
     * Determines if the current user may read in a certain board.
     * @param  mixed   $forum The board identifier. This may either be a board UID pointing to
     *                        a record in the tx_mmforum_forums table or an associative array
     *                        already containing this records.
     * @return boolean        TRUE, if the user that is currently logged in may read in the
     *                        specified board, otherwise FALSE.
     * @author Martin Helmich <m.helmich@mittwald.de>
     */
	function getMayRead_forum($forum) {

		$userId = $this->getUserID();

		// If the $forum parameter is not an array, treat it as a forum UID
		if(!is_array($forum)) {

			$forum = intval($forum);

			/* Try to load read access from cache. If the regarding property is
			 * stored in the cache, return the result now, otherwise load the board
			 * record from the database. */
			$cacheRes = $this->cache->restore('getMayRead_forum_' . $userId . '_' . $forum);
			if($cacheRes !== null) {
				return $cacheRes;
			} else {
				$forum = $this->getBoardData($forum);
			}

		} else {
			$cacheRes = $this->cache->restore('getMayRead_forum_' . $userId . '_' . $forum['uid']);
			if($cacheRes !== null) {
				return $cacheRes;
			}
		}

		// If the current user has moderation or even administration access to this board, just return TRUE in any case.
		if ($this->getIsModOrAdmin($forum['uid'])) {
			return true;
		}

		// If this forum has a parent category, check the access rights for this parent category, too.
		if($forum['parentID']) {
			if (!$this->getMayRead_forum(intval($forum['parentID']))) {
				$this->cache->save('getMayRead_forum_' . $userId . '_' . $forum['uid'], false);
				return false;
			}
		}

		// Get all groups that are allowed to read in this board.
		$authRead = tx_mmforum_tools::getParentUserGroups($forum['grouprights_read']);

		// If no groups are specified for read access, everyone can read.
		if (strlen($authRead) == 0) {
			$this->cache->save('getMayRead_forum_' . $userId . '_' . $forum['uid'], true);
			return true;
		}

		// Parse allowed groups into an array
		$authRead = t3lib_div::trimExplode(',',$authRead);

		// Load the current user's groups
		$groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
		$groups = tx_mmforum_tools::processArray_numeric($groups);

		// Determine the intersection between the allowed groups and the current user's groups.
		$intersect = array_intersect($authRead, $groups);

		// If the current user is in at least one group that is in the groups with read access, then the result is TRUE.
		$result = count($intersect)>0;

		// Store result to cache.
		$this->cache->save('getMayRead_forum_' . $userId . '_' . $forum['uid'], $result);

		return $result;
	}

	/**
     * Generates a MySQL-query to determine in which boards the current user may write.
     * @return string  A MySQL-WHERE-query, beginning with "AND", checking in which boards the
     *                 user that is currently logged in may write.
     * @author Martin Helmich <m.helmich@mittwald.de>
     */
	function getMayWrite_forum_query() {

		$userId = $this->getUserID();

		// Search for query in cache. In case of a hit, return the result now.
		$cacheRes = $this->cache->restore('getMayWrite_forum_query_'.$userId);
		if($cacheRes !== null) {
			return $cacheRes;
		}

		// If the user is an administrator, just return a dummy query.
		if ($this->getIsAdmin()) {
			return ' AND 1 ';
		}

		// Get the current user's groups
		$groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
		$groups = tx_mmforum_tools::processArray_numeric($groups);

		/* Check if the user is in the base user group. If this is not the
		 * case, the user is not allowed to write anywhere. */
		if(!in_array($this->getBaseUserGroup(), $groups)) {
			$query = " AND 1=0";
			$this->cache->save('getMayWrite_forum_query_' . $userId, $query);
			return $query;
		}

		// Iterate through all the user's groups and compose an array of SQL conditions.
		$queryParts = array();
		foreach($groups as $group) {
			$queryParts[] = sprintf('FIND_IN_SET(%s,grouprights_write)', $group);
		}

		// Compose SQL query
		$query = implode(' OR ', $queryParts);
		$query = sprintf(' AND (($s) OR grouprights_write=\'\') ', $query);

		// Save generated query to cache and return
		$this->cache->save('getMayWrite_forum_query_' . $userId, $query);
		return $query;

	}

	/**
     * Determines if the current user may write in a certain board.
     * @param  mixed   $forum The board identifier. This may either be a board UID pointing to
     *                        a record in the tx_mmforum_forums table or an associative array
     *                        already containing this record.
     * @return boolean        TRUE, if the user that is currently logged in may write in the
     *                        specified board, otherwise FALSE.
     * @author Martin Helmich <m.helmich@mittwald.de>
     */
	function getMayWrite_forum($forum) {

		$userId = $this->getUserID();

		// If no user is logged in, return FALSE at once.
		if(!$userId) {
			return false;
		}

		// If the $forum parameter is no array, treat the parameter as forum UID instead
		if(!is_array($forum)) {

			// Parse to int for security reasons
			$forum = intval($forum);

			// Search for result in cache. In case of a hit, return the result at once.
			$cacheRes = $this->cache->restore('getMayWrite_forum_'.$userId.'_'.$forum);
			if($cacheRes !== null) return $cacheRes;

			// Otherwise load the complete board record.
			$forum = $this->getBoardData($forum);
		}

		/* If this has not been done already, look into the cache now
		 * and return the result in the case of a hit. */
		if(!isset($cacheRes)) {
			$cacheRes = $this->cache->restore('getMayWrite_forum_'.$userId.'_'.$forum['uid']);
			if($cacheRes !== null) return $cacheRes;
		}

		/* If the current user has moderation or even administration
		 * access to this board, just return TRUE in any case. */
		if($this->getIsModOrAdmin($forum['uid'])) return true;

		// If the forum has got a parent category, check the access rights for this category, too.
		if($forum['parentID'])
			if(!$this->getMayWrite_forum($forum['parentID'])) return false;

		// Load all groups that have write access to this forum
		$authWrite = tx_mmforum_tools::getParentUserGroups($forum['grouprights_write']);

		/* If no groups with write access have been specified, everyone
		 * can write, so just return true. */
		$authWrite = t3lib_div::intExplode(',',$authWrite);
		$authWrite = $this->tools->processArray_numeric($authWrite);
		if(count($authWrite)==0) {
			$this->cache->save('getMayWrite_forum_'.$userId.'_'.$forum['uid'],true);
			return true;
		}

		// Load current user's groups
		$groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
		$groups = tx_mmforum_tools::processArray_numeric($groups);

		/* Check if the user is in the base user group. If this is not the
		 * case, the user is not allowed to write anywhere. */
		if(!in_array($this->getBaseUserGroup(), $groups)) {
			$this->cache->save("getMayWrite_forum_{$userId}_{$forum[uid]}", false);
			return false;
		}

		/* Determine the intersection between the user's groups and the groups
		 * with write access. If the intersect count is bigger than 0, this means
		 * that the user is in at least one group that has write access, so
		 * return TRUE in this case. */
		$intersect	= array_intersect($authWrite,$groups);
		$result		= count($intersect)>0;

		// Write result to cache and return
		$this->cache->save('getMayWrite_forum_'.$userId.'_'.$forum['uid'],$result);
		return $result;
	}

	/**
     * Determines if the current user may write in a certain topic.
     * @param  mixed   $topic The topic identifier. This may either be a topic UID pointing to
     *                        a record in the tx_mmforum_topics table or an associative array
     *                        already containing this record.
     * @return boolean        TRUE, if the user that is currently logged in may write in the
     *                        specified topic, otherwise FALSE.
     * @author Martin Helmich <m.helmich@mittwald.de>
     */
	function getMayWrite_topic($topic) {

		$userId = $this->getUserID();

		// If the $topic parameter is not an array, treat this parameter as a topic UID.
		if(!is_array($topic)) {

			$topic = intval($topic);

			// Look in the cache. In case of a hit, just return the result
			$cacheRes = $this->cache->restore('getMayWrite_topic_' . $topic . '_' . $userId);
			if($cacheRes !== null) {
				return $cacheRes;
			}

			// Load the topic's forum UID
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'f.*',
				'tx_mmforum_forums f, tx_mmforum_topics t',
				't.uid="'.$topic.'" AND f.uid = t.forum_id'
			);
			$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$result = $this->getMayWrite_forum($arr);

			// Save the result to cache and return
			$this->cache->save('getMayWrite_topic_' . $topic . '_' . $userId, $result);
			return $result;

		} else {

			/* If the topic's forum UID is already known, just delegate to the
			 * getMayWrite_forum function. Since the result of that function is
			 * already being cached, there is no need to cache the result at this
			 * place again. */
			return $this->getMayWrite_forum($topic['forum_id']);

		}
	}

	/**
     * Determines if the current user may read a certain topic.
     * @param  mixed   $topic The topic identifier. This may either be a topic UID pointing to
     *                        a record in the tx_mmforum_topics table or an associative array
     *                        already containing this record.
     * @return boolean        TRUE, if the user that is currently logged in may read the
     *                        specified topic, otherwise FALSE.
     * @author Martin Helmich <m.helmich@mittwald.de>
     */
	function getMayRead_topic($topic) {
		if (!is_array($topic)) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'f.*',
				'tx_mmforum_forums f, tx_mmforum_topics t',
				't.uid=' . intval($topic) . ' AND f.uid=t.forum_id'
			);
			$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

			return $this->getMayRead_forum($arr);
		
		} else {
			return $this->getMayRead_forum($topic['forum_id']);
		}
	}

	/**
     * Determines if the current user may read a certain post.
     * @param  mixed   $topic The post identifier. This may either be a post UID pointing to
     *                        a record in the tx_mmforum_posts table or an associative array
     *                        already containing this record.
     * @return boolean        TRUE, if the user that is currently logged in may read the
     *                        specified post, otherwise FALSE.
     * @author Martin Helmich <m.helmich@mittwald.de>
     */
	function getMayRead_post($post) {
		if($post == 0) {
			return false;
		}

		if(!is_array($post)) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'f.*',
				'tx_mmforum_forums f, tx_mmforum_posts p',
				'p.uid=' . intval($post) . ' AND f.uid=p.forum_id'
			);
			$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

			return $this->getMayRead_forum($arr);
		
		} else {
			return $this->getMayRead_forum($post['forum_id']);
		}
	}

	/**
	 * Various helper functions
	 */

	/**
	 * Generates an SQL condition for checking the user PID.
	 *
	 * @param  string $table The table name (default 'fe_users')
	 * @return string        A condition checking the PID of fe_user
	 *                       records.
	 */
	function getUserPidQuery($table = 'fe_users') {
		return ' AND ' . $table . '.pid = ' . intval($this->conf['userPID']) . ' ';
	}

    /**
     * Retrieces a board records from database.
     * This function retrieves a board record from the tx_mmforum_forums table in
     * the database as an associative array.
     *
     * @param  int   $uid The board's uid
     * @return array      The board record as associative array
     */
    function getBoardData($uid) {
        $uid = intval($uid);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_forums',
            "uid='$uid'"
        );
        return $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
    }

    /**
     * Generates a MySQL-query part to select only a set of predefined categories.
     * @param  string $tablename The table name
     * @return string            The MySQL-query part
     */
    function getCategoryLimit_query($tablename="") {
        if(!$this->limitCat) return "";

        $prefix = $tablename?"$tablename.":'';
        $query = " AND $prefix"."uid IN (".$this->limitCat.")";
        return $query;
    }
    
  /**
	 * Creates the mm_forum page title.
	 * This function generates the title of the mm_forum using the rootline
	 * locallang names.
	 */
	function createPageTitle() {
	  if ($this->conf['removeOriginalPagetitle']) {
	    $GLOBALS['TSFE']->page['title'] = '';
    }
    
		switch ($this->piVars['action']) {
			// List post view, new post form, post alert form
			// Sets a title like "mm_forum page -> Category -> Board -> Topic (-> New post/Report post)""
			case 'list_post':
			case 'new_post':
			case 'post_alert':
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'topic_title, f.forum_name, c.forum_name',
					'tx_mmforum_topics t, tx_mmforum_forums f, tx_mmforum_forums c',
					't.uid="' . intval($this->piVars['tid']) . '" AND f.uid=t.forum_id AND c.uid=f.parentID'
				);
				list($topicTitle,$forumTitle,$catTitle) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

				$topicTitle = stripslashes($topicTitle);

				$topicTitle = str_replace('<','&lt;',$topicTitle);
				$topicTitle = str_replace('>','&gt;',$topicTitle);

				if ($this->piVars['action'] == 'new_post') {
					$pageTitle = $this->pi_getLL('rootline.reply');
				} elseif ($this->piVars['action'] == 'post_alert') {
					$pageTitle = $this->pi_getLL('rootline.post_alert');
				}
			break;

			// New topic form, topic listing view
			// Sets a title like "mm_forum page -> Category -> Board (-> New topic)"
			case 'new_topic':
			case 'list_topic':
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'f.forum_name, c.forum_name',
					'tx_mmforum_forums f, tx_mmforum_forums c',
					'f.uid="'.intval($this->piVars['fid']).'" AND c.uid=f.parentID'
				);
				list($forumTitle,$catTitle) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

				if ($this->piVars['action'] == 'new_topic') {
					$pageTitle = $this->pi_getLL('rootline.new_topic');
				}
			break;

			// Post editing form
			// Sets a title like "mm_forum page -> Category -> Board -> Topic -> Edit post"
			case 'post_edit':
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'topic_title,f.forum_name,c.forum_name',
					'tx_mmforum_posts p, tx_mmforum_topics t, tx_mmforum_forums f, tx_mmforum_forums c',
					'p.uid="' . intval($this->piVars['pid']) . '" AND t.uid=p.topic_id AND f.uid=p.forum_id AND c.uid=f.parentID'
				);
				list($topicTitle,$forumTitle,$catTitle) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

				$topicTitle = stripslashes($topicTitle);

				$topicTitle = str_replace('<','&lt;',$topicTitle);
				$topicTitle = str_replace('>','&gt;',$topicTitle);

				$pageTitle = $this->pi_getLL('rootline.edit_post');
			break;

			// User profile
			// Sets a title like "mm_forum page -> User profile: Username"
			case 'forum_view_profil':
				if($this->useRealUrl() && $this->piVars['fid']) {
          $user = tx_mmforum_FeUser::GetByUsername($this->piVars['fid']);
        } else {
          $user = tx_mmforum_FeUser::GetByUID($this->piVars['user_id']);
        }

				$pageTitle = sprintf($this->pi_getLL('rootline.userprofile'), $this->escape($user->gD($this->getUserNameField())));
			break;

			// List unread or unanswered topics
			// Sets a title like "mm_forum page -> List unread/unanswered topics"
			case 'list_unread':
			case 'list_unans':
			  $pageTitle = $this->pi_getLL('rootline.' . $this->piVars['action']);
			break;
		}

		if ($this->conf['pagetitleLastForumPageTitleOnly']) {

  		if (isset($topicTitle)) {
  		  if ($GLOBALS['TSFE']->page['title'] == '') {
          $GLOBALS['TSFE']->page['title'] = $topicTitle;
        } else {
          $GLOBALS['TSFE']->page['title'] .= $this->conf['display.']['pageTitle.']['separator'].$topicTitle;
        }
  		} elseif (isset($forumTitle)) {
  		  if ($GLOBALS['TSFE']->page['title'] == '') {
          $GLOBALS['TSFE']->page['title'] = $forumTitle;
        } else {
          $GLOBALS['TSFE']->page['title'] .= $this->conf['display.']['pageTitle.']['separator'].$forumTitle;
        }
      } elseif(isset($catTitle)) {
  		  if ($GLOBALS['TSFE']->page['title'] == '') {
          $GLOBALS['TSFE']->page['title'] = $catTitle;
        } else {
          $GLOBALS['TSFE']->page['title'] .= $this->conf['display.']['pageTitle.']['separator'].$catTitle;
        }
  		}
      
    } else {

  		if (isset($catTitle)) {
  		  if ($GLOBALS['TSFE']->page['title'] == '') {
          $GLOBALS['TSFE']->page['title'] = $catTitle;
        } else {
          $GLOBALS['TSFE']->page['title'] .= $this->conf['display.']['pageTitle.']['separator'].$catTitle;
        }
  		}
  
  		if (isset($forumTitle)) {
  		  if ($GLOBALS['TSFE']->page['title'] == '') {
          $GLOBALS['TSFE']->page['title'] = $forumTitle;
        } else {
          $GLOBALS['TSFE']->page['title'] .= $this->conf['display.']['pageTitle.']['separator'].$forumTitle;
        }
  		}
  
  		if (isset($topicTitle)) {
  		  if ($GLOBALS['TSFE']->page['title'] == '') {
          $GLOBALS['TSFE']->page['title'] = $topicTitle;
        } else {
          $GLOBALS['TSFE']->page['title'] .= $this->conf['display.']['pageTitle.']['separator'].$topicTitle;
        }
  		}
    
    }

		if (isset($pageTitle)) {
			$GLOBALS['TSFE']->page['title'] .= $this->conf['display.']['pageTitle.']['separator'].$pageTitle;
		}

    //wrap the page title
		$GLOBALS['TSFE']->page['title'] = $this->cObj->wrap($GLOBALS['TSFE']->page['title'], $this->conf['pagetitleWrap']);
		// set page title for indexed search
		$GLOBALS['TSFE']->indexedDocTitle = $GLOBALS['TSFE']->page['title'];
		
		//get to know if it is a USER_INT extension
		if ($this->pi_USER_INT_obj == true) {
		  //$GLOBALS['TSFE']->page['title'] is already written, so the change does
		  //not have any effect
		  $GLOBALS['TSFE']->content = preg_replace('/<title>.+<\/title>/',
                                  '<title>'.$GLOBALS['TSFE']->page['title'].'</title>',
                                  $GLOBALS['TSFE']->content, 1); 
    }
		
	}

	/**
	 * Generates a custom rootline menu.
	 * This function generates a custom rootline menu. This function can be included
	 * as special.userfunc in HMENUs in TypoScript in order to merge the mm_forum
	 * internal rootline with a global page rootline. On the same time, the property
	 * tx_mmforum_pi1.disableRootline should be set to 1.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-11-02
	 * @param   string $content The content variable
	 * @param   array  $conf    The configuration array
	 * @return  array           An array containing a set of HMENU items
	 * @deprecated This function is now only a wrapper function for the regarding
	 *             method in the tx_mmforum_menu class.
	 */
	function createRootline($content, $conf) {
		include_once(t3lib_extMgm::extPath('mm_forum') . '/includes/class.tx_mmforum_menus.php');

		$menuObj = t3lib_div::makeInstance('tx_mmforum_menus');
		return $menuObj->createRootline($content,$conf);
	}

    /**
     * Creates a button.
     * This function creates a button. The button can be configured using
     * TypoScript.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-02-11
     * @param   string  $label  The label. This will either be an image file name
     *                          or a key for a locallang entry.
     * @param   array   $params Link parameters
     * @param   int     $id     The page ID to be linked to. If 0, the button will
     *                          link to the current page.
     * @param   boolean $small  Defines if the button is to be declared as small. Will
     *                          only affect text buttons.
     * @param   string  $href   A hard-coded link where this button shall link to.
     * @return  string          The button.
     */
    function createButton($label, $params, $id=0, $small=false, $href='', $nolink=false, $atagparams='') {
		if ($id == 0)
			$id = intval($this->fid) == 0 ? $GLOBALS['TSFE']->id : intval($this->fid);

		$prefixId = $this->prefixId_pi1 ? $this->prefixId_pi1 : $this->prefixId;

		$buttonObj = $this->conf['buttons.'][$small ? 'small' : 'normal'];
		$buttonConf = $this->conf['buttons.'][$small ? 'small.' : 'normal.'];

		if (!is_array($params)) {
			if (preg_match('/^profileView:([0-9]+?)$/', $params, $matches)) {
				$href = tx_mmforum_pi1::getUserProfileLink($matches[1]);
			}
		}

		$data = array(
			'button_label' => $this->pi_getLL('button.' . $label, $label),
			'button_link' => $nolink ? '' : ($href ? $href : $this->pi_getPageLink($id, '', $params)),
			'button_iconname' => file_exists($this->conf['path_img'] . 'buttons/icons/' . $label . '.png') ? $label . '.png' : '',
			'button_atagparams' => $atagparams
		);
		if ($data['button_link']{0} === '?') $data['button_link'] = '/' . $data['button_link'];
		$oldData = $this->cObj->data;
		$this->cObj->data = $data;

		$button = $this->cObj->cObjGetSingle($buttonObj, $buttonConf);
		$this->cObj->data = $oldData;

		return $button;
	}

	/**
	 * Sets the solved status of a topic.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-02-11
	 * @param   int   $status Determines to which solved status the topic is to be set.
	 *                        Set to '0' to "unsolve" the topic and to '1' to solve it.
	 * @return  mixed         Returns either VOID on success or a content string in case
	 *                        an error ocurred.
	 */
	function topic_setSolveStatus($status) {
		$topicId   = intval($this->piVars[$status ? 'solve' : 'unsolve']);
		$res       = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_mmforum_topics', 'uid = ' . $topicId);
		$topicData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

		if($topicData['topic_poster'] == $GLOBALS['TSFE']->fe_user->user['uid'] || $this->getIsModOrAdmin($topicData['forum_id'])) {
			$updateArray = array(
				'solved' => $status,
				'tstamp' => time()
			);
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics', 'uid = ' . $topicId, $updateArray);

			$linkParams[$this->prefixId] = array(
				'action' => 'list_post',
				'tid'    => $topicId
			);
			if ($this->useRealUrl()) {
				$linkParams[$this->prefixId]['fid'] = $topicData['forum_id'];
			}

			$link = $this->tools->getAbsoluteUrl($this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams));
			header('Location: ' . t3lib_div::locationHeaderUrl($link));
			exit();
		} else {
			return $this->errorMessage($this->conf, $this->pi_getLL('topic-noSolveRights'));
		}
	}

	/**
	 * Solves a topic.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-02-11
	 * @return  mixed Returns either VOID on success or a content string in case
	 *                an error ocurred.
	 */
	function topic_solve() {
		return $this->topic_setSolveStatus(1);
	}

	/**
	 * "Unsolves" a topic.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-02-11
	 * @return  mixed Returns either VOID on success or a content string in case
	 *                an error ocurred.
	 */
	function topic_unsolve() {
		return $this->topic_setSolveStatus(0);
	}

	/**
	 * Generates a link to a user profile.
	 * This function generates a link to a user's profile. This function
	 * is used everywhere where a link to a user profile is needed. This means
	 * that it suffices to hook into this function to modify the whole
	 * profile linking mechanism of the mm_forum.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-02-11
	 * @param   mixed  $userData Information on the user that is to be linked. This may
	 *                           either be the user record as associative array or the user UID.
	 * @return  string           The user link
	 *
	 */
	function getUserProfileLink($userData) {
		if (!is_array($userData)) {
			$userData = tx_mmforum_tools::get_userdata($userData);
		}
		$prefixId = ($this->prefixId_pi1 ? $this->prefixId_pi1 : $this->prefixId);
		$linkParams[$prefixId] = array(
			'action'  => 'forum_view_profil',
			'user_id' => $userData['uid']
		);

		if (tx_mmforum_pi1::useRealUrl()) {
			unset($linkParams[$prefixId]['user_id']);
			$linkParams[$prefixId]['fid'] = $userData['username'];
		}
		$link = $this->pi_getPageLink(tx_mmforum_pi1::getUserProfilePID(), '', $linkParams);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['profileLink_postLinkGen'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['profileLink_postLinkGen'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$link = $_procObj->userProfileLink($userData, $link, $this);
			}
		}
		return $link;
	}

	/**
	 * Generates a text linked to a user profile.
	 * This function generates a text that is linked to a user profile.
	 * The content of the link is typically the user name, but may be overwritten
	 * by a parameter.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-02-11
	 * @param   mixed  $userData Information on the user that is to be linked. This may
	 *                           either be the user record as associative array or the user UID.
	 * @param   string $text     The link text. If this parameter is not specified,
	 *                           the user name will be used as link text.
	 * @return	string           The link to the user Profile
	 */
	function linkToUserProfile($userData, $text = '') {
		if (!is_array($userData)) {
			$userData = tx_mmforum_tools::get_userdata($userData);
		}
		if (empty($text)) {
			$text = $userData[tx_mmforum_pi1::getUserNameField()];
		}
		return '<a href="' . $this->escapeURL(tx_mmforum_pi1::getUserProfileLink($userData)) . '">' . $text . '</a>';
	}

	/**
	 * Returns the database field name that is used for the username.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-03-16
	 * @since   0.1.6
	 * @return  string The database field used for the username
	 */
	function getUserNameField() {
		return ($this->conf['userNameField'] ? $this->conf['userNameField'] : 'username');
	}

	/**
	 * Returns the topic icon mode. This will either be 'classic' or 'modern'
	 *
	 * @return	string		The topic icon mode (either 'classic' or 'modern')
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 */
	function getTopicIconMode() {
		return ($this->conf['topicIconMode'] ? $this->conf['topicIconMode'] : 'modern');
	}

		/**
		 * Determines if rating is enabled for a specific data type.
		 * This can be configured using the plugin.tx_mmforum_pi1.enableRating
		 * array. Furthermore, the 'ratings' extension is required to be installed.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 0.1.8-090410
		 * @param   string $table The data type that is to be checked. At the moment,
		 *                        this may either be 'topics', 'posts' or 'users'.
		 * @return  boolean       TRUE, if rating is enabled, FALSE if rating disabled
		 *                        or the 'ratings' extension is not installed.
		 */
	function isRating($table) {
		if(!t3lib_extMgm::isLoaded('ratings')) return false;
		return $this->conf['enableRating.'][$table] ? true : false;
	}

		/**
		 * Determines if topic rating is enabled.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 0.1.8-090410
		 * @return  boolean TRUE if topic rating is enabled, FALSE is topic rating
		 *                  is disabled or the 'ratings' extension is not installed.
		 */
	function isTopicRating() { return $this->isRating('topics'); }

		/**
		 * Determines if post rating is enabled.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 0.1.8-090410
		 * @return  boolean TRUE if post rating is enabled, FALSE is post rating
		 *                  is disabled or the 'ratings' extension is not installed.
		 */
	function isPostRating() { return $this->isRating('posts'); }

		/**
		 * Determines if user rating is enabled.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 0.1.8-090410
		 * @return  boolean TRUE if user rating is enabled, FALSE is user rating
		 *                  is disabled or the 'ratings' extension is not installed.
		 */
	function isUserRating() { return $this->isRating('users'); }



		/**
		 * Formats a timestamp in human readable form. If the timestamp is from the
		 * current day or the day before, there is no date displayed, but rather a string
		 * saying "today" or "yesterday".
		 *
		 * @param  string $content The timestamp that is to be formatted
		 * @param  array  $conf    Configuration options
		 * @return string          The formatted timestamp
		 */

	function formatLastPostDate($content, $conf) {

		$this->pi_loadLL();

		$todayStart = mktime(0, 0, 0, date("m"), date('d'), date('Y'));
		$yesterdayStart = mktime(0, 0, 0, date("m"), date('d')-1, date('Y'));

		$content	= intval($content);

		    if($content >= $todayStart)		$dateFormat = $this->pi_getLL('date-today').'&nbsp;[%H:%M]';
		elseif($content >= $yesterdayStart)	$dateFormat = $this->pi_getLL('date-yesterday').'&nbsp;[%H:%M]';
		else								$dateFormat = $conf['defaultDateFormat'];

		return strftime($dateFormat, $content);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_pi1.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_pi1.php']);
}
?>