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
 *  172: class tx_mmforum_pi1 extends tslib_pibase
 *
 *              SECTION: General plugin methods
 *  188:     function main($content,$conf)
 *  281:     function evalConfigValues()
 *  350:     function page_footer ($conf)
 *  371:     function page_header ($conf)
 *
 *              SECTION: Main content functions
 *  392:     function list_unread($content, $conf)
 *  566:     function list_unanswered($content, $conf)
 *  689:     function list_category($content, $conf)
 *  775:     function list_forum($content, $conf)
 *  891:     function list_topic($content, $conf)
 * 1115:     function list_prefix($content, $conf, $prefix)
 * 1436:     function list_latest()
 * 1496:     function list_users()
 * 1640:     function userdef_cmp($a,$b)
 *
 *              SECTION: Forum content management functions
 * 1715:     function new_topic($content, $conf)
 * 1965:     function new_post($content, $conf)
 * 2232:     function performAttachmentUpload()
 * 2294:     function post_edit($content, $conf)
 *
 *              SECTION: Favorites
 * 2542:     function set_favorite ($content,$conf)
 * 2574:     function del_favorite ($content,$conf)
 * 2595:     function favorites ($content,$conf)
 *
 *              SECTION: Forum content management helper functions
 * 2742:     function generateBBCodeButtons($template)
 * 2816:     function show_smilie_db($conf)
 * 2852:     function update_lastpost_topic($topic_id)
 * 2865:     function update_lastpost_forum($forum_id)
 * 2889:     function set_solved($topic_id,$solved)
 * 2904:     function send_newpost_mail ($content,$conf,$topic_id)
 *
 *              SECTION: Subordinary content functions
 * 2960:     function post_history($conf)
 * 2975:     function view_profil ($content,$conf)
 * 3167:     function view_last_10_topics($uid)
 * 3211:     function view_last_10_posts($uid)
 * 3237:     function send_mail($content, $conf)
 *
 *              SECTION: Page navigation
 * 3346:     function dynamicPageNav($maxPage,$linkVar_name='page',$def_linkParams=array(),$maxOffset=4)
 * 3410:     function pagecount ($table,$column,$id,$limitcount,$count=FALSE)
 * 3480:     function pagecount2 ($lastlogin, $limitcount)
 *
 *              SECTION: Forum content helper functions
 * 3530:     function getlastpost($postid,$conf)
 * 3579:     function getauthor($userid)
 * 3603:     function get_topic_name($tid)
 * 3619:     function ident_user($uid,$conf,$threadauthor=FALSE)
 * 3693:     function get_forum_path ($forumid,$topicid)
 * 3735:     function bb2text($text,$conf)
 * 3749:     function getunreadposts ($content, $conf, $lastlogin)
 * 3822:     function reset_unreadpost ($content, $conf)
 * 3846:     function highlight_text ($text,$words)
 * 3881:     function get_pid_link ($post_id,$sword,$conf)
 * 3919:     function encode_html($content)
 * 3931:     function get_topic_is ($topic_id)
 * 3947:     function get_userranking($user_id,$conf)
 * 3972:     function user_config($conf,$param)
 * 4038:     function open_topic($content,$conf)
 * 4058:     function get_user_fav($user_id='')
 * 4075:     function get_topic_id($post_id)
 * 4088:     function get_forum_id($topic_id)
 * 4102:     function get_forumbox($topic_id)
 * 4158:     function get_last_post($topic_id)
 * 4180:     function text_protect($text)
 * 4196:     function text_cut($text,$cut,$word_cut = 0)
 * 4219:     function get_userid($username)
 * 4232:     function errorMessage($conf, $msg)
 * 4257:     function imgtag($imgInfo,$debug=TRUE)
 * 4279:     function getLanguageFolder()
 * 4298:     function getTopicData($tid)
 * 4318:     function getTopicIcon($topic)
 * 4366:     function getMarkAllRead_link()
 * 4389:     function getAttachment()
 *
 *              SECTION: User rights management
 * 4430:     function getIsAdmin()
 * 4442:     function getIsMod()
 * 4457:     function getMayRead_forum_query($prefix="")
 * 4482:     function getMayRead_forum($forum)
 * 4507:     function getMayWrite_forum_query()
 * 4529:     function getMayWrite_forum($forum)
 * 4560:     function getMayWrite_topic($topic)
 * 4585:     function getMayRead_topic($topic)
 * 4610:     function getMayRead_post($post)
 *
 *              SECTION: Various helper functions
 * 4636:     function randkey($length)
 * 4652:     function hex2ip($hex)
 * 4667:     function ip2hex($val)
 * 4690:     function getAbsUrl($link)
 * 4719:     function appendTrailingSlash($str)
 * 4729:     function removeLeadingSlash($str)
 * 4744:     function getIsRealURL()
 * 4765:     function getPidQuery($tables="")
 * 4802:     function getFirstPid()
 * 4823:     function getBoardData($uid)
 * 4839:     function getCategoryLimit_query($tablename="")
 * 4857:     function createRootline()
 * 5022:     function pi_getLL($key)
 *
 * TOTAL FUNCTIONS: 86
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
 
require_once(PATH_tslib."class.tslib_pibase.php");

require_once ( t3lib_extMgm::extPath('mm_forum').'includes/class.tx_mmforum_tools.php' );
require_once ( t3lib_extMgm::extPath('mm_forum').'includes/class.tx_mmforum_validator.php' );
require_once ( t3lib_extMgm::extPath('mm_forum').'pi1/class.tx_mmforum_postalert.php' );
require_once ( t3lib_extMgm::extPath('mm_forum').'pi1/class.tx_mmforum_havealook.php' );
require_once ( t3lib_extMgm::extPath('mm_forum').'pi1/class.tx_mmforum_havealookforum.php' );
require_once ( t3lib_extMgm::extPath('mm_forum').'pi1/class.tx_mmforum_postfunctions.php' );
require_once ( t3lib_extMgm::extPath('mm_forum').'pi1/class.tx_mmforum_user.php' );
require_once ( t3lib_extMgm::extPath('mm_forum').'pi1/class.tx_mmforum_polls.php' );
require_once ( t3lib_extMgm::extPath('mm_forum').'pi1/class.tx_mmforum_ranksfe.php' );
require_once ( t3lib_extMgm::extPath('mm_forum').'pi1/class.tx_mmforum_postqueue.php' );
require_once ( t3lib_extMgm::extPath('mm_forum').'pi1/class.tx_mmforum_rss.php' );
        
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
 * @copyright  2008 Mittwald CM Service
 * @version    2008-03-17
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_pi1 extends tslib_pibase {
    var $prefixId       = "tx_mmforum_pi1";                    // Same as class name
    var $scriptRelPath  = "pi1/class.tx_mmforum_pi1.php";    // Path to this script relative to the extension dir.
    var $extKey         = "mm_forum";                        // The extension key.

    /**
     * General plugin methods
     */
    
    /**
     * The plugin main function. Generates all content.
     * @param  string $content The content
     * @param  array  $conf    The plugin configuration vars
     * @return string          The plugin content
     */
    function main($content,$conf)
    {
        //add Javascript
        $GLOBALS['TSFE']->additionalHeaderData['mm_forum'] = '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath('mm_forum').'mm_forum.js"></script>';
        
        $this->conf=$conf;
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();
        $this->pi_USER_INT_obj=1;
        $this->config["code"] = $this->cObj->stdWrap($this->conf["code"],$this->conf["code."]);
        
        $this->evalConfigValues();
        
        $this->conf['path_img'] = str_replace("EXT:mm_forum/",t3lib_extMgm::siteRelPath('mm_forum'),$this->conf['path_img']);
        $this->conf['path_smilie'] = str_replace("EXT:mm_forum/",t3lib_extMgm::siteRelPath('mm_forum'),$this->conf['path_smilie']);
        
        $conf = $this->conf;
        
        if($conf['debug']) $GLOBALS['TYPO3_DB']->debugOutput = TRUE;

        $this->local_cObj =t3lib_div::makeInstance("tslib_cObj");                // Local cObj.
        $this->templateFile = $conf["templateFile"];
        $this->validatorObj = tx_mmforum_validator::getValidatorObject();
        
        $codes=t3lib_div::trimExplode(",", $this->config["code"]?$this->config["code"]:$this->conf["defaultCode"],1);
        
        // get the PID List
        if(!$this->conf['pidList'])
        	$this->conf['pidList'] = $this->pi_getPidList($this->cObj->data['pages'],$this->cObj->data['recursive']);
            
        if (!count($codes)) $codes=array("");
        
        tx_mmforum_rss::setHTMLHeadData('all');
        
        while(list(,$theCode)=each($codes))
        {
            list($theCode,$cat,$aFlag) = explode("/",$theCode);
            $theCode = (string)strtoupper(trim($theCode));
            $this->theCode = $theCode;
            switch($theCode) {
                case "HAVEALOOK":
                    $content = tx_mmforum_havealook::edit_havealook($content, $conf); break;
                case "FAVORITES":
                    $content = $this->favorites($content, $conf); break;
                case "POSTALERTLIST":
                    $content = tx_mmforum_postalert::list_alerts($conf); break;
                case "LIST_POSTS":
                    $content =  $this->post_history($conf); break;
                case "LATEST":
                    $content = $this->list_latest(); break;
                case "USERLIST":
                    $content = $this->list_users(); break;
                case "POSTQUEUE":
                	$content = $this->list_postqueue(); break;
				case "RSS":
					$content = $this->list_rss(); break;
                default:      
                    if ($this->redirectTo) {
                        header('Location: '.$this->redirectTo); die();
                    }

                    if ($this->piVars['action'] == "")                   $content = $this->list_forum($content,$conf);
                    if ($this->piVars['action'] == "list_unread")        $content = $this->list_unread($content,$conf);
                    if ($this->piVars['action'] == "list_unans")         $content = $this->list_unanswered($content,$conf);
                    if ($this->piVars['action'] == "list_cat")           $content = $this->list_category($content,$conf);
                    if ($this->piVars['action'] == "list_topic")         $content = $this->list_topic($content,$conf);
                    if ($this->piVars['action'] == "list_post")          $content = tx_mmforum_postfunctions::list_post ($content,$conf,''); 
                    if ($this->piVars['action'] == "new_topic")          $content = $this->new_topic ($content,$conf);
                    if ($this->piVars['action'] == "new_post")           $content = $this->new_post  ($content,$conf);
                    if ($this->piVars['action'] == "send_mail")          $content = $this->send_mail ($content,$conf);
                    if ($this->piVars['action'] == "forum_view_profil")  $content = $this->view_profil ($content,$conf);
                    if ($this->piVars['action'] == "post_del")           $content = tx_mmforum_postfunctions::post_del($content,$conf);
                    if ($this->piVars['action'] == "post_edit")          $content = $this->post_edit ($content,$conf);
                    if ($this->piVars['action'] == "change_userdata")    $content = $this->change_userdata($content, $conf);
                    if ($this->piVars['action'] == "reset_read")         $content = $this->reset_unreadpost($content, $conf);
                    if ($this->piVars['action'] == "set_havealook")      $content = tx_mmforum_havealook::set_havealook($content, $conf);
                    if ($this->piVars['action'] == "del_havealook")      $content = tx_mmforum_havealook::del_havealook($content, $conf);
                    if ($this->piVars['action'] == "set_havealookforum") $content = tx_mmforum_havealookforum::set_havealookforum($content, $conf);
                    if ($this->piVars['action'] == "del_havealookforum") $content = tx_mmforum_havealookforum::del_havealookforum($content, $conf);
                    if ($this->piVars['action'] == "set_favorite")       $content = $this->set_favorite($content, $conf);
                    if ($this->piVars['action'] == "del_favorite")       $content = $this->del_favorite($content, $conf);
                    if ($this->piVars['action'] == "open_topic")         $content = $this->open_topic($content,$conf);
                    if ($this->piVars['action'] == "post_alert")         $content = tx_mmforum_postalert::post_alert($conf);
                    if ($this->piVars['action'] == "list_prefix")        $content = $this->list_prefix($content,$conf,$this->piVars['list_prefix']['prfx']);
                    if ($this->piVars['action'] == "post_history")       $content = $this->post_history($conf);
                    if ($this->piVars['action'] == 'get_attachment')     $content = $this->getAttachment();
                    if ($this->piVars['solve'])							 $content = $this->topic_solve();
                    if ($this->piVars['unsolve'])						 $content = $this->topic_unsolve();
                    $content = $this->page_header($conf).$content;
                break;
            }

        }
        
        // Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['mainContentHook'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['mainContentHook'] as $_classRef) {
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
        
        // Code
        $code = $this->pi_getFFvalue($ff, 'code', 'general');
        
        if(!$code) $code = $this->config['code'] = $this->conf['code'];
        elseif($code == 'BOARD') $this->config['code'] = '';
        else $this->config['code'] = $code;
        
        if($code == 'LATEST') {
            $limitCat = $this->pi_getFFvalue($ff, 'exclCategories_latest', 'general');
            if(strlen(trim($limitCat))>0) {
                $this->limitCat = $limitCat;
            } else $this->limitCat = FALSE;
            
            $limitTopic = $this->pi_getFFvalue($ff, 'latest_limit', 'general');
            if(strlen($limitTopic)>0) $this->latest_limitTopic = $limitTopic;
            else $this->latest_limitTopic = 10;
        }
        
        if($code == 'USERLIST') {
            $this->userlist_fields  = $this->pi_getFFvalue($ff, 'userlist_fields', 'general');
            if(!$this->userlist_fields) $this->userlist_fields = $this->conf['userlist_fields'];
            
            $this->userlist_limit   = $this->pi_getFFvalue($ff, 'userlist_limit', 'general');
            if(!$this->userlist_limit) $this->userlist_limit = $this->conf['userlist_limit'];
        }
        
        if($code == "" || $code == 'BOARD') {
            $limitCat = $this->pi_getFFvalue($ff, 'exclCategories', 'general');
            if(strlen(trim($limitCat))>0) {
                $this->limitCat = $limitCat;
            } else $this->limitCat = FALSE;
            
            $redirect = $this->pi_getFFvalue($ff, 'redirectSpecial', 'general');
            if($redirect == 'list_unans' || $redirect == 'list_unread' || $redirect == 'list_prefix') {
                if($redirect == 'list_unans') {
                    $linkParams[$this->prefixId] = array(
                        'action'        => 'list_unans'
                    );
                }
                elseif($redirect == 'list_unread') {
                    $linkParams[$this->prefixId] = array(
                        'action'        => 'list_unread'
                    );
                }
                elseif($redirect == 'list_prefix') {
                    $linkParams[$this->prefixId] = array(
                        'action'        => 'list_prefix',
                        'list_prefix'   => array(
                            'prfx'          => strtolower($this->pi_getFFvalue($ff, 'prefix', 'general'))
                        )
                    );
                }
                $link = $this->pi_getPageLink($this->getForumPID(),'',$linkParams);
				
				$this->redirectTo = $this->getAbsUrl($link);
            }
        }
    }


    /**
     * Renders the page footer
     * @return string        Returns the Footerstring
     */
    
    function page_footer ($conf) {
        $template = $this->cObj->fileResource($conf['template.']['footer']);
        $template = $this->cObj->getSubpart($template, "###FOOTER###");
        
        $_EXTKEY = 'mm_forum';
        @include t3lib_extMgm::extPath('mm_forum').'ext_emconf.php';
        
        if(isset($EM_CONF))
        	$marker['###FOOTER_MESSAGE###'] = sprintf($this->pi_getLL('footermessage'),$EM_CONF['mm_forum']['version']);
        else $marker['###FOOTER_MESSAGE###'] = '';

		// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['pageFooter'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['pageFooter'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->mainContentHook($marker, $this);
				}
			}

        $content  = $this->cObj->substituteMarkerArrayCached($template, $marker);
        $content .= '<!-- mm_forum Version '.$EM_CONF['mm_forum']['version'].' //-->';
        
        return $content;
    }
    
    
    
    
    /**
     * Renders the page header, containing links to e.g. the user control center
     * @param  array  The plugin's configuration vars
     * @return string The header string
     */
    function page_header ($conf) {
        $template = $this->cObj->fileResource($conf['template.']['header']);
        $template = $this->cObj->getSubpart($template, "###HEADER###");
        $marker['###FORUM_HEAD###'] = $this->pi_getLL('forum_head');
        $marker['###FORUM_DESC###'] = $this->pi_getLL('forum_desc');
        
		// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['pageHeader'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['pageHeader'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->mainContentHook($marker, $this);
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
        $marker['###PAGES###']              = $this->pagecount2 ($lastlogin, $conf['topic_count']); // Anzeigen der Seiten, durch die man blÃ¯Â¿Â½ttern kann

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
                $this->getPidQuery('f,tx_mmforum_topics').
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
        
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($topiclist))
        {
            #$row['topic_title'] = str_replace('<','&lt;',$row['topic_title']);
            #$row['topic_title'] = str_replace('>','&gt;',$row['topic_title']);

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
                $solved         = $this->imgtag($imgInfo);
            } else {
                $solved = '';
            }
            $linkparams[$this->prefixId] = array (
                'action'  => 'list_post',
                'tid'     => $row['uid'],
                'pid'     => 'last'
            );
            if($this->getIsRealURL()) {
                $linkparams[$this->prefixId]['fid'] = $row['forum_id'];
            }
            $imgInfo['src'] = $conf['path_img'].$conf['images.']['jump_to'];
            $imgInfo['alt'] = $this->pi_getLL('topic.gotoLastPost');
            $last_post_link = $this->pi_linkToPage($this->imgtag($imgInfo), $GLOBALS['TSFE']->id,'',$linkparams);

            if($row['topic_is'])
                $topic_is = $this->cObj->wrap($row['topic_is'],$this->conf['list_topics.']['prefix_wrap']);
            else
                $topic_is = '';

            $row['topic_title'] = stripslashes($row['topic_title']);

            $linkParams[$this->prefixId] = array(
                'action'    => 'list_post',
                'tid'       => $row['uid']
            );
            $marker['###TOPICNAME###']  = $topic_is.$this->pi_linkTP($this->shield($row['topic_title']),$linkParams).$solved;
            $marker['###UNDERLINE###']  = $this->shield($rowc['cat_title']).' &raquo; '.$this->shield($rowf['forum_name']);
            $marker['###POSTS###']      = intval($row['topic_replies']).' ('.intval($row['topic_views']).')';
            #$marker['###AUTHOR###']     = tx_mmforum_tools::link_profil($row['topic_poster']);
            $marker['###AUTHOR###']		= $this->linkToUserProfile($row['topic_poster']);
            $marker['###LAST###']       = $this->getlastpost($row['topic_last_post_id'],$conf).$last_post_link;
            
            $marker['###READIMAGE###'] = $this->getTopicIcon($row);

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
        $imgInfo  = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');
        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
        $template = $this->cObj->getSubpart($template, "###TOPICBEGIN_UNANSW###");
        $marker = array();

        if ($GLOBALS['TSFE']->fe_user->user['uid']) {
            $qunread   = 'select tx_mmforum_prelogin as lastlogin from fe_users where uid ='.$GLOBALS['TSFE']->fe_user->user['uid'];
            $resunread = mysql(TYPO3_db, $qunread) or die(mysql_error());
            $rowunread = mysql_fetch_array($resunread);
            $lastlogin = $rowunread['lastlogin'];
            $readarray  = $this->getunreadposts($content, $conf, $lastlogin);
        }

        $marker['###FORUMNAME###'] 			= $this->pi_getLL('board.unansweredEntries');
        $marker['###LABEL_TOPIC###']        = $this->pi_getLL('board.topic');
        $marker['###LABEL_AUTHOR###']       = $this->pi_getLL('board.author');
        
        $limitcount = $conf['topic_count'];
        
        $pagebrowser = $this->pagecount ('tx_mmforum_topics','topic_replies',0,$limitcount); // Anzeigen der Seiten durch die man BlÃ¯Â¿Â½ttern kann
        $marker['###PAGES###'] = $pagebrowser;
        
        // Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_header'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_header'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listUnanswered_header($marker, $this);
				}
			}

        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker); // ZurÃ¯Â¿Â½ckschrteiben der Marker

        $seite = intval($this->piVars['page']);
        if(empty($seite)) $seite = 1;
        $limit = ($limitcount-1)*($seite-1).','.$limitcount;

        $topiclist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            't.*,
             c.grouprights_read as cat_read,
             f.grouprights_read as f_read',
            'tx_mmforum_topics t,
             tx_mmforum_forums f,
             tx_mmforum_forums c',
            't.topic_replies=0 AND
             t.deleted=0 AND
             t.hidden=0 AND
             f.deleted=0 AND
             f.hidden=0 AND
             t.forum_id=f.uid AND
             c.uid=f.parentID '.
             $this->getPidQuery('t,f').
             $this->getMayRead_forum_query('f').
             $this->getMayRead_forum_query('c').
             $this->getCategoryLimit_query('c'),
            '',
            'topic_last_post_id DESC',
            $limit
        );

        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($topiclist)) {
            $forum = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'forum_name, parentID',
                'tx_mmforum_forums',
                'uid="'.$row['forum_id'].'"'
            );
            $rowf = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forum);
            
            $cat = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'forum_name as cat_title',
                'tx_mmforum_forums',
                'uid="'.$rowf['parentID'].'"'
            );
            $rowc = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($cat);

            $template = $this->cObj->fileResource($conf['template.']['list_topic']);
            $template = $this->cObj->getSubpart($template, "###LIST_TOPIC_UNANSW###");
            $row['topic_title'] = str_replace('<','&lt;',$row['topic_title']);
                $row['topic_title'] = str_replace('>','&gt;',$row['topic_title']);

            if($row['topic_is'])
                $topic_is = $this->cObj->wrap($row['topic_is'],$this->conf['list_topics.']['prefix_wrap']); 
            else
                $topic_is = '';

            // Check if solved flag is set
            IF($row['solved'] == 1){
                $imgInfo['src'] = $conf['path_img'].$conf['images.']['solved'];
                $imgInfo['alt'] = $this->pi_getLL('topic.isSolved');
                $solved         = $this->imgtag($imgInfo);
            }
            else{
                $solved = '';
            }

            $row['topic_title'] = stripslashes($row['topic_title']);

            $linkParams[$this->prefixId] = array(
            	'action'		=> 'list_post',
            	'tid'			=> $row['uid']
            );
            if($this->getIsRealURL()) {
                $linkParams[$this->prefixId]['fid'] = $row['forum_id'];
            }
            $marker['###TOPICNAME###']	= $topic_is.$this->pi_linkTP($this->shield($row['topic_title']),$linkParams);
            $marker['###UNDERLINE###']  = $this->shield($rowc['cat_title']).' &raquo; '.$this->shield($rowf['forum_name']);
            $marker['###AUTHOR###']     = $this->getauthor($row['topic_poster']);
            $marker['###LAST###']       = $this->getlastpost($row['topic_last_post_id'],$conf);
            
            $marker['###READIMAGE###']  = $this->getTopicIcon($row);

			// Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_listitem'])) {
					foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_listitem'] as $_classRef) {
						$_procObj = & t3lib_div::getUserObj($_classRef);
						$marker = $_procObj->listUnanswered_listitem($marker, $row, $this);
					}
				}

            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        }

        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
        $template = $this->cObj->getSubpart($template, "###TOPICEND_UNANSW###");

        $marker['###PAGES###'] = $pagebrowser;
        
        // Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_footer'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listUnanswered_footer'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
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
    function list_category($content, $conf)
    {
        $imgInfo  = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');
        $template = $this->cObj->fileResource($conf['template.']['main']);
        $template = $this->cObj->getSubpart($template, "###LIST_FORUM_BEGIN###");

        $marker = array(
            '###MARKREAD###'       => $this->getMarkAllRead_link(),
            '###LABEL_FORUM###'    => $this->pi_getLL('board.board'),
            '###LABEL_TOPICS###'   => $this->pi_getLL('board.topics'),
            '###LABEL_POSTS###'    => $this->pi_getLL('board.posts'),
            '###LABEL_LASTPOST###' => $this->pi_getLL('board.lastPost')
        );

		// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_header'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_header'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listCategories_header($marker, $this);
				}
			}

        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

        $cat = intval($this->piVars['cid']);

        $catlist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid, forum_name',
            'tx_mmforum_forums',
            'deleted = 0 AND
             hidden = 0 AND
             parentID=0 AND
             uid = '.$cat.' '.
             $this->getPidQuery().
             $this->getMayRead_forum_query()
        );

		$x = 0;
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($catlist))
        {
            $x++;
            $template = $this->cObj->fileResource($conf['template.']['main']);
            $template = $this->cObj->getSubpart($template, "###LIST_CAT###");

            $marker['###CATNAME###'] = $this->shield($row['forum_name']);
            
            // Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_listitem'])) {
					foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_listitem'] as $_classRef) {
						$_procObj = & t3lib_div::getUserObj($_classRef);
						$marker = $_procObj->listCategories_listitem($marker, $row, $this);
					}
				}
            
            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

            $forumlist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                "uid, forum_name, forum_desc, forum_topics, forum_posts, forum_last_post_id",
                "tx_mmforum_forums",
                "deleted = 0 AND
                 hidden = 0 AND
                 parentID = ".$row['uid'].' '.
                 $this->getPidQuery().
                 $this->getMayRead_forum_query().
                 $this->getMayRead_forum_query(),
                "",
                "sorting ASC"
            );

            while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forumlist)) {
                $template = $this->cObj->fileResource($conf['template.']['main']);
                $template = $this->cObj->getSubpart($template, "###LIST_FORUM###");

                $linkparams[$this->prefixId] = array (
                    'action' => 'list_topic',
                    'fid'     => $row['uid']
                );
                $marker['###FORUMNAME###']  = $this->pi_linkToPage($this->shield($row['forum_name']),$GLOBALS['TSFE']->id,'',$linkparams);
                $marker['###FORUMDESC###']  = $this->shield($row['forum_desc']);
                $marker['###THEMES###']     = $this->shield($row['forum_topics']);
                $marker['###POSTS###']      = $this->shield($row['forum_posts']);
                $marker['###LASTPOSTS###']  = $this->getlastpost($row['forum_last_post_id'],$conf,true);
                $imgInfo['src']             = $conf['path_img'].$conf['images.']['read'];
                $marker['###READIMAGE###']  = $this->imgtag($imgInfo);

				// Include hooks
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_sublistitem'])) {
						foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_sublistitem'] as $_classRef) {
							$_procObj = & t3lib_div::getUserObj($_classRef);
							$marker = $_procObj->listCategories_sublistitem($marker, $row, $this);
						}
					}

                $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
            }
        }

        $template = $this->cObj->fileResource($conf['template.']['main']);
        $template = $this->cObj->getSubpart($template, "###LIST_FORUM_END###");
        
        // Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_footer'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listCategories_footer'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listCategories_footer($marker, $this);
				}
			}
        
        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        return $content;

    }

    /**
     * Lists all categories and all boards.
     * @param  string $content The plugin content
     * @param  array  $conf    The plugin's configuration vars
     * @return string          The content
     */
    function list_forum($content, $conf)
    {
        $imgInfo    = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');
        $template   = $this->cObj->fileResource($conf['template.']['main']);
        $template   = $this->cObj->getSubpart($template, "###LIST_FORUM_BEGIN###");
        $marker = array(
            '###MARKREAD###'        	=> $this->getMarkAllRead_link(),
            '###LABEL_FORUM###'     	=> $this->pi_getLL('board.board'),
            '###LABEL_TOPICS###'    	=> $this->pi_getLL('board.topics'),
            '###LABEL_POSTS###'     	=> $this->pi_getLL('board.posts'),
            '###LABEL_LASTPOST###'  	=> $this->pi_getLL('board.lastPost'),
			'###PAGETITLE###'			=> $this->cObj->data['header'],
			'###LABEL_OPTIONS###'		=> $this->pi_getLL('options')
        );

		// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_header'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_header'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listForums_header($marker, $this);
				}
			}

        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

        if ($GLOBALS['TSFE']->fe_user->user['uid']) {
            $resunread = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'tx_mmforum_prelogin as lastlogin',
                'fe_users',
                'uid="'.$GLOBALS['TSFE']->fe_user->user['uid'].'"'
            );
            $rowunread = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resunread);
            $lastlogin = $rowunread['lastlogin'];#-1814400;
            $readarray  = $this->getunreadposts($content, $conf, $lastlogin);
        }
        
        $catlist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid, forum_name',
            'tx_mmforum_forums',
            'deleted = 0 AND
             hidden = 0 AND
             parentID=0 '.
             $this->getPidQuery().
             $this->getMayRead_forum_query().
             $this->getCategoryLimit_query(),
            '',
            'sorting ASC'
        );
        
        $x = 0;
		$i = 1;
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($catlist)) {
            $x++;
            $template = $this->cObj->fileResource($conf['template.']['main']);
            $template = $this->cObj->getSubpart($template, "###LIST_CAT###");

            $marker['###CATNAME###'] = '<a name="cat'.intval($row['uid']).'"></a>'.$this->shield($row['forum_name']);
            $marker['###CATID###']	= 'c'.intval($row['uid']);
            
            // Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_categoryItem'])) {
					foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_categoryItem'] as $_classRef) {
						$_procObj = & t3lib_div::getUserObj($_classRef);
						$marker = $_procObj->listForums_categoryItem($marker, $row, $this);
					}
				}
            
            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

            $forumlist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'tx_mmforum_forums',
                'deleted = 0 AND
                 hidden = 0 AND
                 parentID = '.intval($row['uid']).' '.
                 $this->getPidQuery().
                 $this->getMayRead_forum_query(),
                '',
                'sorting ASC'   
            );

            while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forumlist)) {
                $template = $this->cObj->fileResource($conf['template.']['main']);
                $template = $this->cObj->getSubpart($template, "###LIST_FORUM###");

                $linkparams[$this->prefixId] = array (
                    'action'    => 'list_topic',
                    'fid'       => $row['uid']
                );
                $marker['###FORUMNAME###']  = $this->pi_linkToPage($this->shield($row['forum_name']),$GLOBALS['TSFE']->id,'',$linkparams);
                $marker['###FORUMDESC###']  = $this->shield($row['forum_desc']);
                $marker['###THEMES###']     = $row['forum_topics']?intval($row['forum_topics']):'';
                $marker['###POSTS###']      = $row['forum_posts']?intval($row['forum_posts']):'';
                $marker['###LASTPOSTS###']  = $this->getlastpost($row['forum_last_post_id'],$conf,true);
            	$marker['###FORUMID###']	= 'f'.$row['uid'];
                
            	$marker['###LIST_FORUM_EVENODD###'] = ($i%2)?$this->conf['display.']['listItem.']['oddClass']:$this->conf['display.']['listItem.']['evenClass'];

				$i ++;

                $closed = (!$this->getMayWrite_forum($row));

                // If there is a user logged in, it is checked if there are new posts since the last login.
                if ($GLOBALS['TSFE']->fe_user->user['uid']) {
                    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_topics','forum_id="'.$row['uid'].'"'.$this->getPidQuery());
                    $blnnew = false;

                    while($row_topic = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                        if(in_array($row_topic['uid'], $readarray)) {
                            $blnnew = true;
                            break;
                        }
                    }
                    /*if($blnnew) {
                        $imgInfo['src']             = $conf['path_img'].$conf['images.'][$closed.'forum_new'];
                        $marker['###READIMAGE###']  = $this->imgtag($imgInfo);
                    }
                    else {
                        $imgInfo['src']             = $conf['path_img'].$conf['images.'][$closed.'forum'];
                        $marker['###READIMAGE###']  = $this->imgtag($imgInfo);
                    } */
                    
                    $marker['###READIMAGE###']  = $this->getForumIcon(null,$closed,$blnnew);
                } else {
                    #$imgInfo['src']             = $conf['path_img'].$conf['images.'][$closed.'forum'];
                    #$marker['###READIMAGE###']  = $this->imgtag($imgInfo);
                    $marker['###READIMAGE###']  = $this->getForumIcon(null,$closed,false);
                }
                
                // Include hooks
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_forumItem'])) {
						foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listForums_forumItem'] as $_classRef) {
							$_procObj = & t3lib_div::getUserObj($_classRef);
							$marker = $_procObj->listForums_forumItem($marker, $row, $this);
						}
					}

                $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
            }
            
            $template = $this->cObj->fileResource($conf['template.']['main']);
            $template = $this->cObj->getSubpart($template, "###LIST_CAT_END###");
            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);             
        }
        
        $template = $this->cObj->fileResource($conf['template.']['main']);
        $template1 = $this->cObj->getSubpart($template, "###LIST_FORUM_END###");
        $content .= $this->cObj->substituteMarkerArrayCached($template1, $marker);  

        if ($GLOBALS['TSFE']->fe_user->user['uid']) 
        {
            $template1   = $this->cObj->getSubpart($template, "###LIST_FORUM_OPTIONS###"); 
            $marker['###LABEL_OPTIONS###']        = $this->pi_getLL('options'); 
            $content .= $this->cObj->substituteMarkerArrayCached($template1, $marker); 
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
        $imgInfo = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');
        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
        $template = $this->cObj->getSubpart($template, "###TOPICBEGIN###");
        $marker = array();

        if ($GLOBALS['TSFE']->fe_user->user['uid']) {
            $resunread = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'tx_mmforum_prelogin as lastlogin',
                'fe_users',
                'uid="'.$GLOBALS['TSFE']->fe_user->user['uid'].'"'
            );
            $rowunread = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resunread);
            $lastlogin = $rowunread['lastlogin'];
            $readarray = $this->getunreadposts($content, $conf, $lastlogin);
        }

        /**
         * Check authorization       Martin Helmich, 18. 4. 06
         */
         	if(!$this->getMayRead_forum($this->piVars['fid'])) {
                $content .= $this->errorMessage($conf, $this->pi_getLL('board.noAccess'));
                return $content;
         	}
         
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid',
                'tx_mmforum_forums',    
                'uid="'.intval($this->piVars['fid']).'" '.$this->getPidQuery()
            ); 

            if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) {
                $content .= $this->errorMessage($conf, $this->pi_getLL('board.noAccess'));
                return $content;   
            }
        /**
         * Check authorization end
         */

        $forum_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'forum_name,uid,grouprights_read',
            'tx_mmforum_forums',
            "uid = '".intval($this->piVars['fid'])."'".$this->getPidQuery()
        );
        list($forumname,$forumid,$readgroups) = $GLOBALS['TYPO3_DB']->sql_fetch_row($forum_res);

        if($readgroups == '')
            tx_mmforum_rss::setHTMLHeadData('forum', $this->piVars['fid']);
        
		if($this->conf['disableRootline'])
			$template = $this->cObj->substituteSubpart($template, "###ROOTLINE_CONTAINER###", '');
		else
        	$marker['###FORUMPATH###'] = $this->get_forum_path($forumid,'');
             
        $marker['###PAGETITLE###'] = $this->cObj->data['header'];    
        $marker['###FORUMNAME###'] = $this->shield($forumname);
        
        $linkParams[$this->prefixId] = array(
            'action'    => 'new_topic',
            'fid'       => $forumid
        );
        $imgInfo['src'] = $conf['path_img'].$this->getLanguageFolder().$conf['images.']['new_topic'];
        
        #if($this->getMayWrite_forum($this->piVars['fid']))
        #	$marker['###NEWTOPICLINK###'] = $this->pi_linkTP($this->imgtag($imgInfo), $linkParams);
        #else $marker['###NEWTOPICLINK###'] = '';
        
        if($this->getMayWrite_forum($this->piVars['fid']))
        	$marker['###NEWTOPICLINK###'] = $this->createButton('newtopic',$linkParams);
        else $marker['###NEWTOPICLINK###'] = '';
        
        #function createButton($label,$params,$id=0,$small=false,$href='') {
        
        $marker['###LABEL_TOPIC###']         = $this->pi_getLL('board.topic');
        $marker['###LABEL_REPLIES_HITS###']  = $this->pi_getLL('board.replies');
        $marker['###LABEL_AUTHOR###']        = $this->pi_getLL('board.author');
        $marker['###LABEL_LASTPOST###']      = $this->pi_getLL('board.lastPost');
        $marker['###LABEL_HIDESOLVED###']    = $this->pi_getLL('board.hideSolved');

        $limitcount = $conf['topic_count'];

        $marker['###MARKREAD###'] = $this->getMarkAllRead_link();
        $marker['###PAGES###'] = $this->pagecount ('tx_mmforum_topics','forum_id',$this->piVars['fid'],$limitcount); // Anzeigen der Seiten durch die man BlÃ¯Â¿Â½ttern kann

        $marker['###HIDESOLVED_CHECKED###'] = ($this->piVars['hide_solved'])?'checked="checked"':'';
        $marker['###SETTINGS_ACTION###'] = htmlspecialchars($this->getAbsUrl($this->pi_linkTP_keepPIvars_url(array('hide_solved'=>0))));
        
        $marker['###FORUMICON###'] = $this->getForumIcon($this->getMayWrite_forum($this->piVars['fid']),count($readarray));

		// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_header'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_header'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listTopics_header($marker, $this);
				}
			}

        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);


        if ($GLOBALS['TSFE']->fe_user->user['uid'])
            $user_fav = $this -> get_user_fav();

        $seite = intval($this->piVars['page']);
        if(empty($seite)) $seite = 1;
        $limit = ($limitcount-1)*($seite-1).','.$limitcount;

        $solvedcon = ($this->piVars['hide_solved'])?"AND solved='0'":"";
        $shadowCon = ($this->conf['enableShadows'])?'':' AND shadow_tid=0 ';

        $topiclist = $GLOBALS["TYPO3_DB"]->exec_SELECTquery(
            "*",
            "tx_mmforum_topics",
            "deleted = 0 AND hidden = 0 AND forum_id = '".intval($this->piVars['fid'])."' $solvedcon".$shadowCon.$this->getPidQuery(),
            "",
            "at_top_flag DESC, topic_last_post_id DESC",
            $limit
        );
        
        if($GLOBALS['TYPO3_DB']->sql_num_rows($topiclist)>0) {
	        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
	        $template = $this->cObj->getSubpart($template, "###LIST_TOPIC###");
		}
		else {
	        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
	        $template = $this->cObj->getSubpart($template, "###LIST_NOTOPIC###");
	        
            $content .= $this->cObj->substituteMarker($template, "###LABEL_NOTOPICS###", $this->pi_getLL('topic.noTopicsFound'));
		}
        
        $j = 1;
        
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($topiclist)) {
            // Check if solved flag is set.
                if($row['solved'] == 1 && $this->conf['topicIconMode']=='classic'){
                    $imgInfo['src'] = $conf['path_img'].$conf['images.']['solved'];
                    $imgInfo['alt'] = $this->pi_getLL('topic.isSolved');
                    $solved         = $this->imgtag($imgInfo);             
                }
                else{
                    $solved = '';
                }
            // Check if topic is favorite
                if (is_array($user_fav) AND in_array ($row['uid'], $user_fav)){
                    $imgInfo['src'] = $conf['path_img'].$conf['images.']['favorite'];
                    $imgInfo['alt'] = $this->pi_getLL('topic.isFavorite');
                    $favorit        = $this->imgtag($imgInfo);
                }
                else{
                    $favorit = '';
                }
            $row['topic_title'] = str_replace('<','&lt;',$row['topic_title']);
            $row['topic_title'] = str_replace('>','&gt;',$row['topic_title']);
            $row['topic_title'] = stripslashes($row['topic_title']);

            if($row['topic_is'])
                $topic_is = $this->cObj->wrap($row['topic_is'],$this->conf['list_topics.']['prefix_wrap']);
            else
                $topic_is = '';
                
            if($row['shadow_tid'] == 0) {
                $linkparams[$this->prefixId] = array (
                    'action' => 'list_post',
                    'tid'    => $row['uid']
                );
                if($this->getIsRealURL()) $linkparams[$this->prefixId]['fid'] = $row['forum_id'];
            } else {
                $linkparams[$this->prefixId] = array (
                    'action' => 'list_post',
                    'tid'    => $row['shadow_tid']
                );
                if($this->getIsRealURL()) $linkparams[$this->prefixId]['fid'] = $row['shadow_fid'];
                $topic_is = $this->cObj->wrap($this->pi_getLL('topic.shadow'),$this->conf['list_topics.']['prefix_wrap']);
            }

            $marker['###TOPICNAME###']  = $favorit.$topic_is.'<a href="'.htmlspecialchars($this->pi_getPageLink($GLOBALS["TSFE"]->id,'',$linkparams)).'" title="'.$this->shield($row['topic_title']).'">'.$this->shield($this->text_cut($row['topic_title'],50,0)).'</a> '.$solved;

            $page_link = '';
            $last_post_link = '';

            // Display page navigation below topic name, making it possible to jump to a page directly
            IF (($row['topic_replies'] + 1) > $conf['post_limit']) {
                $page_link    = '( '.$this->pi_getLL('page.goto').':';
                $menge        = $row['topic_replies']+1;
                $i            = 1;
                $pages = ceil($menge/$conf['post_limit']);
                
                $interval = ceil($pages / 10);
                
                for($i=1; $i <= $pages; $i += $interval) {
                    $linkparams[$this->prefixId] = array (
                        'action'    => 'list_post',
                        'tid'       => $row['uid'],
                        'page'      => $i
                    );
                    $page_link    .= ' '.$this->pi_linkToPage($i,$GLOBALS['TSFE']->id,'',$linkparams);
                    
                    if($interval > 1) {
                    	if($i==$interval+1) $i --;
					}
					
					if($i == $pages) break;
					if($i + $interval > $pages) {
						$i = $pages - $interval;	
					}
                }
                $page_link  .= ' ) ';
                $marker['###TOPICNAME###'] .= $this->cObj->wrap($page_link,$this->conf['list_topics.']['pagenav_wrap']);
            }

            $linkparams[$this->prefixId]['pid'] = 'last';
            $imgInfo['src']       = $conf['path_img'].$conf['images.']['jump_to'];
            $imgInfo['alt']       = $this->pi_getLL('topic.gotoLastPost');    
            $last_post_link       = $this->pi_linkToPage($this->imgtag($imgInfo), $GLOBALS['TSFE']->id,'',$linkparams);

            $replies = ($row['topic_replies']>0)?$row['topic_replies']:0;
            
            $marker['###HITS###']       		= ($row['shadow_tid']==0)?$row['topic_views']:'-';
            $marker['###POSTS###']      		= ($row['shadow_tid']==0)?$replies:'';
            $marker['###POSTS_HITS###'] 		= ($row['shadow_tid']==0)?$replies.' ('.$row['topic_views'].')':'';
            $marker['###AUTHOR###']     		= $this->getauthor($row['topic_poster']);
            $marker['###LAST###']       		= $this->getlastpost($row['topic_last_post_id'],$conf).' '.$last_post_link;
            $marker['###LIST_TOPIC_EVENODD###']	= ($j%2)?$this->conf['display.']['listItem.']['oddClass']:$this->conf['display.']['listItem.']['evenClass'];

			$j ++;
            
            // Get topic icon
            $marker['###READIMAGE###'] = $this->getTopicIcon($row);
            
            // Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_topicItem'])) {
					foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_topicItem'] as $_classRef) {
						$_procObj = & t3lib_div::getUserObj($_classRef);
						$marker = $_procObj->listTopics_topicItem($marker, $row, $this);
					}
				}
            
            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        }

        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
        $template = $this->cObj->getSubpart($template, "###TOPICEND###");

        $marker['###PAGES###'] = $this->pagecount ('tx_mmforum_topics','forum_id',$this->piVars['fid'],$limitcount); // Anzeigen der Seiten durch die man BlÃ¯Â¿Â½ttern kann
        
        // Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_footer'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listTopics_footer'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->listTopics_footer($marker, $this);
				}
			}
        
        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        
		/**
		* Added by Cyrill Helg
		**/
		
		IF (isset($GLOBALS['TSFE']->fe_user->user['uid'])) {
		    $template         = $this->cObj->fileResource($conf['template.']['list_topic']);
		    $template         = $this->cObj->getSubpart($template, "###LIST_POSTS_OPTIONEN###");
        
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		        "uid",
		        "tx_mmforum_forummail",
		        "user_id = ".$GLOBALS['TSFE']->fe_user->user['uid']." AND forum_id = ".intval($this->piVars['fid']).$this->getPidQuery()
		    );
		    
		    IF ($GLOBALS['TYPO3_DB']->sql_num_rows($res) < 1) {
		        $imgInfo['alt'] = $this->pi_getLL('topic.emailSubscr.off');
		        $imgInfo['src'] = $conf['path_img'].$conf['images.']['info_mail_off'];
		        $linkParams[$this->prefixId] = array(
		            'action'        => 'set_havealookforum',
		            'fid'           => $this->piVars['fid']
		        );
		//                 if($this->getIsRealURL()) $linkParams[$this->prefixId]['fid'] = $postforum; 
		//TODO: This does not work yet
		        $link = $this->pi_linkTP($this->pi_getLL('on'),$linkParams).' / <strong>'.$this->pi_getLL('off').'</strong>';
		    } else {
		        $imgInfo['alt'] = $this->pi_getLL('topic.emailSubscr.on');
		        $imgInfo['src'] = $conf['path_img'].$conf['images.']['info_mail_on'];
		        $linkParams[$this->prefixId] = array(
		            'action'        => 'del_havealookforum',
		            'fid'           => $this->piVars['fid']
		        );
		//                 if($this->getIsRealURL()) $linkParams[$this->prefixId]['fid'] = $postforum;
		//TODO: This does not work yet
		        $link = '<strong>'.$this->pi_getLL('on').'</strong> / '.$this->pi_linkTP($this->pi_getLL('off'),$linkParams);
		    }
		
		    $image = $this->imgtag($imgInfo);
		    
            $image = $this->cObj->stdWrap($image,$this->conf['list_posts.']['optImgWrap.']);
            $link  = $this->cObj->stdWrap($link,$this->conf['list_posts.']['optLinkWrap.']);
            
            $marker['###POSTMAILLINK###'] = $this->cObj->stdWrap($image.$link,$this->conf['list_posts.']['optItemWrap.']);
		    
		    $marker['###LABEL_OPTIONS###']     = $this->pi_getLL('options_mail_forum');
		    $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
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
    function list_prefix($content, $conf, $prefix)
    {
    	$prefixes = t3lib_div::trimExplode(',',$conf['prefixes']);
    	$noListPrefixes = t3lib_div::trimExplode(',',$conf['noListPrefixes']);
    	
    	foreach($prefixes as $sPrefix) {
    		if(in_array($sPrefix,$noListPrefixes)) continue;
    		if(strtolower($sPrefix)==strtolower($prefix)) {
    			$realPrefix = $sPrefix; break;
			}	
		}
		if(!isset($realPrefix)) {
			$tPrefixes = array_diff($prefixes,$noListPrefixes);
			$realPrefix = $tPrefixes[0];
			$prefix = $realPrefix;
		}
		
		$prefix = $GLOBALS['TYPO3_DB']->quoteStr($prefix,'');
    	
        $imgInfo = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');
        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
        $template = $this->cObj->getSubpart($template, "###PREFIX_SETTINGS###");
        $marker = Array(
            "###ACTION###"                  => $this->getAbsUrl($this->pi_linkTP_keepPIvars_url()),
            "###CATEGORIES###"              => '<option value="all">'.$this->pi_getLL('prefix.all').'</option>',
            "###ORDER_LASTPOST###"          => '',
            "###ORDER_CATEGORY###"          => '',
            "###ORDER_CRDATE###"            => '',
            
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
        );

        $page = $this->piVars['page']?$this->piVars['page']:1;
        $pagecount = 15;

        // Evaluate settings
            $settings = $this->piVars['list_prefix']?$this->piVars['list_prefix']:Array("order"=>"lastpost","show"=>"all");

            if(!isset($settings['order'])) $settings['order'] = 'lastpost';
            if(!isset($settings['show']))  $settings['show']  = 'all';

            switch($settings['order']) {
                case 'lastpost': 
                    $order = "topic_last_post_id DESC";  
                    $marker['###ORDER_LASTPOST###'] = 'selected="selected"';
                    break;
                case 'category':
                    $order = "c.sorting ASC, f.sorting ASC, topic_last_post_id DESC";
                    $marker['###ORDER_CATEGORY###'] = 'selected="selected"';
                    break;
                case 'crdate':
                    $order = 'topic_time DESC';
                    $marker['###ORDER_CRDATE###'] = 'selected="selected"';
                    break; 
                default: 
                    $order = "topic_last_post_id DESC";  
                    $marker['###ORDER_LASTPOST###'] = 'selected="selected"';
                    break;
            }

            if($settings['show'] == "all") $show = "";
            else {
                $arr = explode("_",$settings['show']);
                
                    if(count($arr)!=2)               $show = "";
                elseif($arr[0]!='f' && $arr[0]!='c') $show = "";
                elseif(intval($arr[1])==0)           $show = "";
                else
                	$show = 'AND '.$arr[0].'.uid = "'.intval($arr[1]).'"';
            }
            
            $limit = " ";
            $settings['limit'] = $settings['limit']?intval($settings['limit']):"20";
            if($settings['limit'] == 0) $settings['limit'] = 20;
            
            if($settings['limit'] == 'all') $limit = "";
            else {
                $limit = (($page-1)*$settings['limit']).",".$settings['limit']."";      
            }

        // Set limitation markers for settings form
            $marker['###LIMIT_10###']   = '';
            $marker['###LIMIT_20###']   = '';
            $marker['###LIMIT_50###']   = '';
            $marker['###LIMIT_100###']  = '';
            $marker['###LIMIT_ALL###']  = '';
            $marker['###LIMIT_'.strtoupper($settings['limit']).'###'] = 'selected="selected"';

        // Load howtos from database
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                "t.*,
                 f.forum_name,
                 c.forum_name as cat_title",
                "tx_mmforum_topics t,
                 tx_mmforum_forums f,
                 tx_mmforum_forums c",
                "t.topic_is LIKE '$prefix' AND
                 t.deleted=0 AND
                 t.hidden=0 AND
                 f.uid = t.forum_id AND
                 c.uid = f.parentID ".
                 $show.$this->getPidQuery('t,c,f').
                 $this->getMayRead_forum_query('c').
                 $this->getMayRead_forum_query('f').
                 $this->getCategoryLimit_query('c'),
                "",
                $order,
                $limit
            );

        // Determine howto count
            $nres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                "t.uid",
                "tx_mmforum_topics t,
                 tx_mmforum_forums f,
                 tx_mmforum_forums c",
                "t.topic_is LIKE '$prefix' AND
                 t.deleted=0 AND
                 t.hidden=0 AND
                 f.uid = t.forum_id AND
                 c.uid = f.parentID ".
                 $show.$this->getPidQuery('t,f,c').
                 $this->getMayRead_forum_query('c').
                 $this->getMayRead_forum_query('f').
                 $this->getCategoryLimit_query('c'),
                "",
                $order
            );
            $num_result = $GLOBALS['TYPO3_DB']->sql_num_rows($nres);

        // Fill prefix select field in settings form
        	foreach($prefixes as $sPrefix) {
    			if(in_array($sPrefix,$noListPrefixes)) continue;
        		$selected = (strtolower($prefix)==strtolower($sPrefix))?'selected="selected"':'';
    			$marker['###PREFIXES###'] .= '<option value="'.strtolower($sPrefix).'" '.$selected.'>'.$sPrefix.'</option>';
			}
            
        // Fill category/board select field in settings form
            $cres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*,forum_name as cat_title',
                'tx_mmforum_forums',
                'deleted="0" AND
                 hidden="0" AND
                 parentID="0" '.
                 $this->getPidQuery().
                 $this->getMayRead_forum_query().
                 $this->getCategoryLimit_query(),
                '',
                'sorting ASC'
            );
            
            while($cat = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($cres)) {
                $value = 'c_'.$cat['uid'];
                $sel = ($value == $settings['show'])?'selected="selected"':'';
                
                $marker['###CATEGORIES###'] .= '<optgroup label="'.$this->shield($cat['cat_title']).'">';
                $marker['###CATEGORIES###'] .= '<option value="'.$value.'" '.$sel.'>'.$this->pi_getLL('prefix.categories.all').'</option>';

                $fres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    '*',
                    'tx_mmforum_forums',
                    'parentID="'.$cat['uid'].'" AND
                     deleted="0" AND
                     hidden="0" '.
                     $this->getPidQuery().
                     $this->getMayRead_forum_query(),
                    '',
                    'sorting ASC'
                );

                while($forum = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($fres)) {
                    $title = $forum['forum_name'];
                    $mlength = 50;
                    if(strlen($title)>$mlength) $title = substr($title,0,$mlength).'...';

                    $value = 'f_'.$forum['uid'];
                    $sel = ($value == $settings['show'])?'selected="selected"':'';

                    $marker['###CATEGORIES###'] .= '<option value="'.$this->shield($value).'" '.$sel.'>'.$this->shield($title).'</option>';
                }
                
                $marker['###CATEGORIES###'] .= '</optgroup>';
            }
            
            // Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_header'])) {
					foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_header'] as $_classRef) {
						$_procObj = & t3lib_div::getUserObj($_classRef);
						$marker = $_procObj->listPrefix_header($marker, $this);
					}
				}

            $content .= $this->cObj->substituteMarkerArray($template, $marker);

        // Start output of main table
            $template       = $this->cObj->fileResource($conf['template.']['list_topic']);
            $template       = $this->cObj->getSubpart($template, "###TOPICBEGIN###");
            $pagecount      = ($settings['limit']!='all')?$this->pagecount('tx_mmforum_topics','topic_is','HowTo',$settings['limit'],$num_result):'';
            
            $forumLink      = $this->pi_linkToPage($this->pi_getLL('board.rootline'),$this->getForumPID());
            $prefixParams   = array('tx_mmforum_pi1[action]'=>'list_prefix');
            $prefixRootline = $this->cObj->substituteMarker($this->pi_getLL('prefix.rootline'),"###PREFIX###",$realPrefix);
            $prefixLink     = $this->pi_linkTP($prefixRootline,$prefixParams);
            
            $marker = Array(
                "###FORUMPATH###"           => $forumLink.' &raquo; '.$prefixLink,
                "###NEWTOPICLINK###"        => "",
                "###FORUMNAME###"			=> $this->cObj->substituteMarker($this->pi_getLL('prefix.title'),"###PREFIX###",$realPrefix),
                "###PAGES###"               => $pagecount,
                '###LABEL_TOPIC###'         => $this->pi_getLL('board.topic'),
                '###LABEL_REPLIES_HITS###'  => $this->pi_getLL('board.replies'),
                '###LABEL_AUTHOR###'        => $this->pi_getLL('board.author'),
                '###LABEL_LASTPOST###'      => $this->pi_getLL('board.lastPost')
            );

            $template = $this->cObj->substituteSubpart($template, "###SETTINGS###",'');
            
            // Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_settings'])) {
					foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_settings'] as $_classRef) {
						$_procObj = & t3lib_div::getUserObj($_classRef);
						$marker = $_procObj->listPrefix_settings($marker, $this);
					}
				}
            
            $content .= $this->cObj->substituteMarkerArray($template, $marker);

            if($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
	            $template = $this->cObj->fileResource($conf['template.']['list_topic']);
	            $template = $this->cObj->getSubpart($template, "###LIST_TOPIC###");
			}
			else {
	            $template = $this->cObj->fileResource($conf['template.']['list_topic']);
	            $template = $this->cObj->getSubpart($template, "###LIST_NOTOPIC###");
	            
            	$content .= $this->cObj->substituteMarker($template, "###LABEL_NOTOPICS###", $this->pi_getLL('topic.noTopicsFound'));
			}

        // Load topics already read from database
            if ($GLOBALS['TSFE']->fe_user->user['uid']) {
                $resunread  = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'tx_mmforum_prelogin as lastlogin',
                    'fe_users',
                    'uid="'.$GLOBALS['TSFE']->fe_user->user['uid'].'"'
                );
                $rowunread  = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resunread);
                
                $lastlogin  = $rowunread['lastlogin'];
                $readarray  = $this->getunreadposts($content, $conf, $lastlogin);
            }

        // Output topics
        while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $arr['topic_title'] = htmlspecialchars($arr['topic_title']);
            $arr['topic_title'] = stripslashes($arr['topic_title']);

            $linkParams[$this->prefixId] = Array(
                'action'  => 'list_post',
                'tid'     => $arr['uid'],
                'howto'   => 1,
            );
            $imgInfo['src']             = $conf['path_img'].$conf['images.']['jump_to'];
            $imgInfo['alt']             = $this->pi_getLL('topic.lastarticle');
            
            $lastPostParams[$this->prefixId] = array(
            	'action'		=> 'list_post',
            	'tid'			=> $arr['uid'],
            	'pid'			=> 'last'
            );
            
            $lastPostURL = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$lastPostParams);
            $lastPostLink = '<a href="'.$lastPostURL.'" title="'.$arr['topic_title'].'">'.$this->imgtag($imgInfo).'</a>';
            
            $marker = Array(
                "###TOPICNAME###"   => $this->pi_linkToPage($this->shield($arr['topic_title']),$GLOBALS['TSFE']->id,'_self',$linkParams),
                "###POSTS###"       => intval($arr['topic_replies']),
                "###HITS###"        => intval($arr['topic_views']),
                "###AUTHOR###"      => $this->getauthor($arr['topic_poster']),
                "###LAST###"		=> $this->getlastpost($arr['topic_last_post_id'],$conf).' '.$lastPostLink
            );
            $location = $arr['cat_title'].' / '.$arr['forum_name'];
            IF (($arr['topic_replies'] + 1) > $conf['post_limit']) {
                $page_link = '( '.$this->pi_getLL('page.goto').':';
                $menge = $arr['topic_replies'];
                $i = 0;

                $linkParams[$this->prefixId] = Array(
                    'action'  => 'list_post',
                    'tid'     => $arr['uid'],
                    'howto'   => 1, 
                );

                while($menge >= 0) {
                    $i++; 
                    $linkParams['page'] = $i;
                    $page_link .= ' '.$this->pi_linkToPage($i,$GLOBALS['TSFE']->id,'_self',$linkParams).' ';
                    $menge = $menge - $conf['post_limit'];
                }
                $page_link  .= ' ) ';
                $marker['###TOPICNAME###']  .= '<div class="tx-mmforum-pi1-listtopic-pages">'.$page_link.' <span style="font-weight: normal;">'.$this->shield($location).'</span></div>';
            }
            else $marker['###TOPICNAME###']  .= '<div class="tx-mmforum-pi1-listtopic-location">'.$this->shield($location).'</div>';
            
            // Get topic icon
            $marker['###READIMAGE###'] = $this->getTopicIcon($arr);
            
            // Include hooks
                if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_topicItem'])) {
                    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_topicItem'] as $_classRef) {
                        $_procObj = & t3lib_div::getUserObj($_classRef);
                        $marker = $_procObj->listPrefix_topicItem($marker, $arr, $this);
                    }
                }

            $content .= $this->cObj->substituteMarkerArray($template, $marker);
        }

        $template = $this->cObj->fileResource($conf['template.']['list_topic']);
        $template = $this->cObj->getSubpart($template, "###TOPICEND###");
        
        $marker = Array("###PAGES###" => $pagecount);
        
        // Include hooks
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_footer'])) {
                foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listPrefix_footer'] as $_classRef) {
                    $_procObj = & t3lib_div::getUserObj($_classRef);
                    $marker = $_procObj->listPrefix_footer($marker, $this);
                }
            }
            
        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        return $content;
    }
    
    /**
     * Displays a list containing a list of the latest posts, meaning the
     * topics that was last written in.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-23
     * @return  string The latest topic list
     */
    function list_latest() {
        $template = $this->cObj->fileResource($this->conf['template.']['latest']);
        $template = $this->cObj->getSubpart($template, '###LATEST###');
        $templateRow = $this->cObj->getSubpart($template, '###LATEST_POST###');
        
        $limit = $this->latest_limitTopic;
        
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            't.topic_last_post_id as post_id, t.uid as topic_id, t.*, f.uid as forum_id, f.forum_name as forum_name, c.forum_name as category_name, p.poster_id as author',
            'tx_mmforum_posts p, tx_mmforum_forums f, tx_mmforum_forums c, tx_mmforum_topics t',
            't.uid = p.topic_id AND
             f.uid = p.forum_id AND
             c.uid = f.parentID AND
             p.deleted = 0 AND
             t.deleted = 0 AND
             f.deleted = 0 AND
             c.deleted = 0 AND
             p.hidden = 0 AND
             t.hidden = 0 AND
             f.hidden = 0 AND
             c.hidden = 0 '.
             $this->getMayRead_forum_query('f').
             $this->getMayRead_forum_query('c').
             $this->getCategoryLimit_query('c'),
            'p.topic_id',
            't.topic_last_post_id DESC',
            $limit
        );
        while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $linkParams[$this->prefixId] = array(
                'action'        => 'list_post',
                'tid'           => $arr['topic_id']
            );
            if($this->getIsRealURL()) $linkParams[$this->prefixId]['fid'] = $arr['forum_id'];
            $rMarker = array(
                '###TOPICNAME###'       => $this->pi_linkToPage($this->shield($arr['topic_title']),$this->getForumPID(),'',$linkParams),
                '###TOPICSUB###'        => $this->shield($arr['category_name'].' / '.$arr['forum_name']),
                '###TOPICICON###'       => $this->getTopicIcon($arr),
                '###LASTPOST###'        => $this->getlastpost($arr['post_id'],$this->conf),
            );
            // Include hooks
                if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_topicItem'])) {
                    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_topicItem'] as $_classRef) {
                        $_procObj = & t3lib_div::getUserObj($_classRef);
                        $rMarker = $_procObj->listLatest_topicItem($rMarker, $arr, $this);
                    }
                }
            $rowContent .= $this->cObj->substituteMarkerArray($templateRow, $rMarker);
        }
        $template = $this->cObj->substituteSubpart($template, '###LATEST_POST###', $rowContent);
        
        $marker = array(
            '###FORUMNAME###'       => $this->pi_getLL('latest.title'),
            '###LABEL_TOPIC###'     => $this->pi_getLL('board.topic'),
            '###LABEL_LASTPOST###'  => $this->pi_getLL('board.lastPost')
        );
        // Include hooks
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_allMarkers'])) {
                foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listLatest_allMarkers'] as $_classRef) {
                    $_procObj = & t3lib_div::getUserObj($_classRef);
                    $marker = $_procObj->listLatest_allMarkers($marker, $this);
                }
            }
        $template = $this->cObj->substituteMarkerArrayCached($template, $marker);
        
        return $template;
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
        
        $parser = t3lib_div::makeInstance('t3lib_TSparser');
        
        foreach($list_fields as $field) {
            
            if(intval($field)>0) {
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    '*',
                    'tx_mmforum_userfields',
                    'uid='.$field.' AND deleted=0 '.$this->getPidQuery()
                );
                $arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                
                if(strlen($arr['config'])>0) {
                    $parser->parse($arr['config']);
                    $config = $parser->setup;
                } else $config = array();
                
                if($config['label']) $label = $this->cObj->cObjGetSingle($config['label'],$config['label.']);
                else $label = $arr['label'];
                
                if($config['output']) {
                    $userfields[$arr['uid']]    = $arr;
                }
				
				$userfields_config[$arr['uid']] = $config;
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
        $page_cur   = intval($this->piVars['page'])?$this->piVars['page']:1;
        
        $page_menu  = $this->dynamicPageNav($page_max,'page',array('sorting'=>$sorting,'sorting_mode'=>$sorting_mode));
        
        $marker		= array(
			'###PAGES###'	=> $page_menu,
			'###LLL_USERLIST_TITLE###'		=> $this->pi_getLL('userlist-title'),
			'###USERLIST_COLUMNCOUNT###'	=> count($list_fields)
		);
        
        $offset     = (($page_cur-1)*$list_count);
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
    
    function list_postqueue() {
        $postqueue = t3lib_div::makeInstance('tx_mmforum_postqueue');
        $content = $postqueue->main($this->conf, $this);
        
        return $content;
    }
	
	function list_rss() {
		$rss = t3lib_div::makeInstance('tx_mmforum_rss');
		$content = $rss->main($this->conf, $this);
		
		return $content;
	}

    /**
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
        $benutzer = $GLOBALS['TSFE']->fe_user->user['username'];

        if (!empty($benutzer)) {

                             
            
            /**
             * Authorization check       Martin Helmich, 18. 4. 06
             */
            if(! $this->getMayWrite_forum($this->piVars['fid']))                    // Added 2007-04-24
                return $content.$this->errorMessage($conf,$this->pi_getLL('newTopic.noAccess'));
            /**
             * Authorization check end
             */


            if($this->piVars['button'] == $this->pi_getLL('newTopic.save')) {
                
                
                $error_ok = 0;
                IF (!$this->piVars['topicname']) {
                    $error_ok = 1;
                    $error_message .= '<div>'.$this->pi_getLL('newTopic.noTitle').'</div>';
                }
                IF (!$this->piVars['message']) {
                    $error_ok = 1;
                    $error_message .= '<div>'.$this->pi_getLL('newTopic.noText').'</div>';
                }

                IF ($error_ok == 1) {
                    #$template = $this->cObj->fileResource($conf['template.']['error']);
                    #$marker = array();
                    #$marker['###ERROR###'] =  $error_message;
                    
                    $content .= $this->errorMessage($conf, $error_message);
                    unset($this->piVars['button']);
                    return $this->new_topic($content,$conf);
                } else {
                    // Create postfactory object
                        $postfactory = t3lib_div::makeInstance('tx_mmforum_postfactory');
                        $postfactory->init($this->conf,$this);
                    
                    // Create poll
                        if($this->piVars['enable_poll'] == '1') {
                            $pollObj = t3lib_div::makeInstance('tx_mmforum_polls');
                            $poll_id = $pollObj->createPoll($this->piVars['poll'],$this);
                            if(!is_numeric($poll_id)) {
                                $content .= $this->errorMessage($this->conf, $poll_id);
                                unset($this->piVars['button']);
                                return $this->new_topic($content,$conf);
                            }
                        } else $poll_id = 0;
                          
                    // Check file upload
                        if($_FILES['tx_mmforum_pi1_attachment_1']['size']>0) {
                            $res = $this->performAttachmentUpload();
                            if(!is_array($res)) {
                                $content .= $res;
                                unset($this->piVars['button']);
                                return $this->new_topic($content,$conf);
                            }
                            else $attachment_ids = $res;
                        } else $attachment_ids = array();
                    
                    if($this->getIsModeratedBoard() && !$this->getIsAdmin() && !$this->getIsMod($this->piVars['fid'])) {

                        // Create topic using postfactory
                            $postfactory->create_topic_queue(
                                intval($this->piVars['fid']),
                                $GLOBALS['TSFE']->fe_user->user['uid'],
                                $this->piVars['topicname'],
                                $this->piVars['message'],
                                time(),
                                  $this->ip2hex(t3lib_div::getIndpEnv("REMOTE_ADDR")),
                                  $attachment_ids,
                                  $poll_id
                            );

                        return $this->successMessage($conf,$this->pi_getLL('postqueue-success'));
                    }
                    else {
    
                        // Create topic using postfactory
                            $topic_uid = $postfactory->create_topic(
                                intval($this->piVars['fid']),
                                $GLOBALS['TSFE']->fe_user->user['uid'],
                                $this->piVars['topicname'],
                                $this->piVars['message'],
                                time(),
                                $this->ip2hex(getenv("REMOTE_ADDR")),
                                $attachment_ids,
                                $poll_id,
                                $this->piVars['havealook'] == 'havealook'
                            );
    
                        // Redirect to new topic
                            $linkParams[$this->prefixId] = array(
                                'action'  => 'list_post',
                                'tid'     => $topic_uid,
                            );
                            if($this->getIsRealURL()) $linkParams[$this->prefixId]['fid'] = $this->piVars['fid'];
                            $link = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams);
                            
                            $link = $this->getAbsUrl($link);
                            header("Location: $link#pid$postid"); die();
                    }
                }
            } else {
                if($this->piVars['button'] == $this->pi_getLL('newTopic.preview')) {
                    if($this->piVars['enable_poll'] == '1') {
                    	$pollObj = t3lib_div::makeInstance('tx_mmforum_polls');
                    	$content .= $pollObj->displayPreview($this->piVars['poll'],$this);
                    }
                    
                    $template   = $this->cObj->fileResource($conf['template.']['list_post']);
                    $template   = $this->cObj->getSubpart($template, "###LIST_POSTS###");
                    
                    $template   = $this->cObj->substituteSubpart($template, '###ATTACHMENT_SECTION###', '');
                    
                    $posttext   = $this->piVars['message'];
                    $postold    = $posttext;
                    $posttext   = $this->bb2text($posttext,$conf);

                    $marker['###POSTOPTIONS###']    = '';
                    $marker['###POSTMENU###']       = '';
                    $marker['###POSTUSER###']       = $this->ident_user($GLOBALS['TSFE']->fe_user->user['uid'],$conf);
                    $marker['###POSTTEXT###']       = $posttext;
                    $marker['###ANKER###']          = '';
                    $marker['###POSTDATE###']       = $this->pi_getLL('post.writtenOn').': '.$this->formatDate(time());

                    $previewTemplate    = $this->cObj->fileResource($conf['template.']['new_topic']);
                    $previewTemplate    = $this->cObj->getSubpart($previewTemplate,"###PREVIEW###");
                    $previewMarker = array(
                        "###TOPIC_TITLE###"        => $this->shield($this->piVars['topicname']),
                        "###LABEL_PREVIEW###"    => $this->pi_getLL('newTopic.preview'),
                        "###PREVIEW_POST###"    => $this->cObj->substituteMarkerArrayCached($template, $marker)
                    );
                    
                    // Include hooks
                        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newTopic_preview'])) {
                            foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newTopic_preview'] as $_classRef) {
                                $_procObj = & t3lib_div::getUserObj($_classRef);
                                $previewMarker = $_procObj->newTopic_preview($previewMarker, $this);
                            }
                        }
                    
                    $content .= $this->cObj->substituteMarkerArrayCached($previewTemplate, $previewMarker);
                }

                // Include editor Javascript
                $content   .=   $this->cObj->fileResource($conf['scripts.']['editor']);

                $template = $this->cObj->fileResource($conf['template.']['new_topic']);
                $template = $this->cObj->getSubpart($template, "###NEWTOPIC###");
                
                $actionParams[$this->prefixId] = array(
                    'action'        => 'new_topic',
                    'fid'            => $this->piVars['fid']
                );
                $actionURL = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$actionParams);
                
                $bbCodeButtons_template = $this->cObj->getSubpart($template, '###BBCODEBUTTONS###');
                $bbCodeButtons = $this->generateBBCodeButtons($bbCodeButtons_template);
                $template = $this->cObj->substituteSubpart($template,'###BBCODEBUTTONS###',$bbCodeButtons);
                
                $pollObj = t3lib_div::makeInstance('tx_mmforum_polls');
                
                $marker = array(
                    '###LABEL_CREATETOPIC###'       => $this->pi_getLL('newTopic.create'),
                    '###LABEL_TITLE###'             => $this->pi_getLL('newTopic.title'),
                    '###LABEL_SETHAVEALOOK###'      => $this->pi_getLL('newTopic.setHaveALook'),
                    '###LABEL_SEND###'              => $this->pi_getLL('newTopic.save'),
                    '###LABEL_PREVIEW###'           => $this->pi_getLL('newTopic.preview'),
                    '###LABEL_RESET###'             => $this->pi_getLL('newTopic.reset'),
                    '###POSTTEXT###'                => $this->piVars['message'],
                    '###TOPICNAME###'               => $this->shield($this->piVars['topicname']),
                    '###HAVEALOOK###'               => ($this->piVars['havealook'])?'checked="checked"':'',
                    '###OLDPOSTTEXT###'             => '',
                    '###ACTION###'                  => htmlspecialchars($this->getAbsUrl($actionURL)),
                    '###SMILIES###'                 => $this->show_smilie_db($conf),
                    '###LABEL_ATTACHMENT###'        => $this->pi_getLL('newPost.attachment'),
                    '###LABEL_POLL###'              => $this->pi_getLL('poll.postattach'),
                    '###POLL###'                    => $pollObj->display_createForm($this->piVars['poll']?$this->piVars['poll']:array(),$this),
                    '###POLLDIV_STYLE###'           => $this->piVars['enable_poll']?'':'style="display:none;"',
                    '###ENABLE_POLL###'             => $this->piVars['enable_poll']?'checked="checked':''
                );

                	// Remove file attachment section if file attachments are disabled
                if(!$this->conf['attachments.']['enable']) $template = $this->cObj->substituteSubpart($template, "###ATTACHMENT_SECTION###", '');
                
                	// Add attachment input fields according to TypoScript setting
                $fieldCount = $this->conf['attachments.']['maxCount']?$this->conf['attachments.']['maxCount']:1;
                $aTemplate = $this->cObj->getSubpart($template, '###ATTACHMENT_FIELD###');
                
                for($i=1; $i <= $fieldCount; $i ++) {
                	$aMarker = array(
                		'###ATTACHMENT_NO###'		=> $i
                	);
                	$aContent .= $this->cObj->substituteMarkerArray($aTemplate, $aMarker);
                }
                $template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_FIELD###', $aContent);
                
                	// Remove poll section if polls are disabled
                if(!$pollObj->getMayCreatePoll($this)) $template = $this->cObj->substituteSubpart($template, "###POLL_SECTION###", '');
                
               		// Maximum file size
                $mFileSize = $this->conf['attachments.']['maxFileSize'].' B';
                if($this->conf['attachments.']['maxFileSize'] >= 1024     ) $mFileSize = round($this->conf['attachments.']['maxFileSize'] / 1024,2).' KB';
                if($this->conf['attachments.']['maxFileSize'] >= 1024*1024) $mFileSize = round($this->conf['attachments.']['maxFileSize'] / (1024*1024),2).' MB';
                
                $marker['###MAXFILESIZE_TEXT###'] = sprintf($this->pi_getLL('newPost.maxFileSize'),$mFileSize);
                $marker['###MAXFILESIZE_TEXT###'] = $this->cObj->stdWrap($marker['###MAXFILESIZE_TEXT###'],$this->conf['attachments.']['maxFileSize_stdWrap.']);
                $marker['###MAXFILESIZE###'] = $this->conf['attachments.']['maxFileSize'];
            }
        }
        else {

            // No user logged in, error message
            $template     = $this->cObj->fileResource($conf['template.']['login_error']);
            $template     = $this->cObj->getSubpart($template, "###LOGINERROR###");
            $marker     = array();
            $marker['###LOGINERROR_MESSAGE###'] = $this->pi_getLL('newTopic.noLogin');

        }
        
        // Include hooks
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newTopic_formMarker'])) {
                foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newTopic_formMarker'] as $_classRef) {
                    $_procObj = & t3lib_div::getUserObj($_classRef);
                    $marker = $_procObj->newTopic_formMarker($marker, $this);
                }
            }

        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        return $content;
    }

    /**
     * Displays the form for creating a new post an answer to an existing topic.
     * @param  string $content The plugin content
     * @param  array  $conf    The plugin's configuration vars
     * @return string          The content
     */
    function new_post($content, $conf)
    {
        $benutzer       =   $GLOBALS['TSFE']->fe_user->user['username'];
        $gruppe         =   $GLOBALS['TSFE']->fe_user->user['usergroup'];
        $grouprights    =   explode(",",$GLOBALS['TSFE']->fe_user->user['usergroup']);

        if (
            ((!empty($benutzer)) AND ($this->get_topic_is($this->piVars['tid']) == 0)) 
            OR ((!empty($benutzer)) AND (in_array($conf['grp_admin'],$grouprights))) 
            OR ((!empty($benutzer)) AND (in_array($conf['grp_mod'],$grouprights)))
            )
        {
            if(!$this->getMayWrite_topic($this->piVars['tid'])) {
                return $content.$this->errorMessage($conf, $this->pi_getLL('newTopic.noAccess'));
            }
            
            if($this->piVars['button'] == $this->pi_getLL('newPost.save')) {
                IF (!$this->piVars['message']) {
                    $content .= $this->errorMessage($this->conf,$this->pi_getLL('newTopic.noText'));
                    unset($this->piVars['button']);
                    return $this->new_post($content, $conf);
                }
                
                // Checks if the current user has already written a post in a certain interval
                // from now on. If so, the write attempt is blocked for security reasons.
                    $interval = $conf['spamblock_interval'];

                    $time   = time() - $interval;
                    $res    = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                        "*",
                        "tx_mmforum_posts",
                        "poster_id='".$GLOBALS['TSFE']->fe_user->user['uid']."' AND post_time >= '".$time."'"
                    );

                    $abort = ($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0)?TRUE:FALSE;

                    if($abort) {
                        $template = $this->cObj->fileResource($conf['template.']['login_error']);
                        $template = $this->cObj->getSubpart($template, "###LOGINERROR###");
                        $marker = array();
                        $llMarker = array('###SPAMBLOCK###' => $interval);
                        $marker['###LOGINERROR_MESSAGE###'] = $this->cObj->substituteMarkerArray($this->pi_getLL('newPost.spamBlock'),$llMarker);
                        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

                        return $content;
                    }

                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    "forum_id",       
                    "tx_mmforum_topics",    
                    "uid = '".intval($this->piVars['tid'])."'"
                );
                list($forum_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                
                // Check file upload
                    if($_FILES['tx_mmforum_pi1_attachment_1']['size']>0) {
                        $res = $this->performAttachmentUpload();
                        if(!is_array($res)) {
                            $content .= $res;
                            unset($this->piVars['button']);
                            return $this->new_post($content,$conf);
                        }
                        else $attachment_ids = $res;
                    } else $attachment_ids = 0;
                        
                // Instantiate postfactory class
                      $postfactory = t3lib_div::makeInstance('tx_mmforum_postfactory');
                      $postfactory->init($this->conf,$this);
                
                if($this->getIsModeratedBoard() && !$this->getIsAdmin() && !$this->getIsMod($this->piVars['fid'])) {
                          
                    // Create post using postfactory
                        $postfactory->create_post_queue(
                            intval($this->piVars['tid']),
                            $GLOBALS['TSFE']->fe_user->user['uid'],
                            $this->piVars['message'],
                            time(),
                              $this->ip2hex(t3lib_div::getIndpEnv("REMOTE_ADDR")),
                              $attachment_ids
                        );

                        return $this->successMessage($conf,$this->pi_getLL('postqueue-success'));
                }
                else {
                      
                    // Create post using postfactory
                          $post_uid = $postfactory->create_post(
                              intval($this->piVars['tid']),
                              $GLOBALS['TSFE']->fe_user->user['uid'],
                              $this->piVars['message'],
                              time(),
                              $this->ip2hex(t3lib_div::getIndpEnv("REMOTE_ADDR")),
                              $attachment_ids
                          );
    
                    // Redirect user to new post
                        $linkParams = array(
                            'tx_mmforum_pi1[action]'  => 'list_post',
                            'tx_mmforum_pi1[tid]'     => intval($this->piVars['tid']),
                            'tx_mmforum_pi1[pid]'     => $post_uid
                        );
                        $link = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams);
                        $link = $this->getAbsUrl($link);
                    
                        header("Location: $link#pid$post_uid"); die();
                }
            }
            else {
                // Show post preview
                if($this->piVars['button'] == $this->pi_getLL('newPost.preview')) {
                    $template = $this->cObj->fileResource($conf['template.']['list_post']);
                    $template = $this->cObj->getSubpart($template, "###LIST_POSTS###");

                    $template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_SECTION###', '');
                    
                    $posttext = $this->piVars['message'];
                    $postold = $posttext;
                    $posttext = $this->bb2text($posttext,$conf);

                    $marker['###POSTOPTIONS###']= '';
                    $marker['###POSTMENU###']   = '';
                    $marker['###POSTUSER###']   = $this->ident_user($GLOBALS['TSFE']->fe_user->user['uid'],$conf);
                    $marker['###POSTTEXT###']   = $posttext;
                    $marker['###ANKER###']      = '';
                    $marker['###POSTDATE###']   = $this->pi_getLL('post.writtenOn').': '.$this->formatDate(time());

                    $previewTemplate    = $this->cObj->fileResource($conf['template.']['new_post']);
                    $previewTemplate    = $this->cObj->getSubpart($previewTemplate,"###PREVIEW###");
                    $previewMarker = array(
                        "###TOPIC_TITLE###"      => $this->shield($this->piVars['topicname']),
                        "###LABEL_PREVIEW###"    => $this->pi_getLL('newTopic.preview'),
                        "###PREVIEW_POST###"     => $this->cObj->substituteMarkerArrayCached($template, $marker)
                    );
                    
                    // Include hooks
                        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPost_preview'])) {
                            foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPost_preview'] as $_classRef) {
                                $_procObj = & t3lib_div::getUserObj($_classRef);
                                $previewMarker = $_procObj->newPost_preview($previewMarker, $this);
                            }
                        }
                    
                    $content .= $this->cObj->substituteMarkerArrayCached($previewTemplate, $previewMarker);
                }

                // Include editor Javascript
                $content   .=   $this->cObj->fileResource($conf['scripts.']['editor']); 

                $template = $this->cObj->fileResource($conf['template.']['new_post']);
                $template = $this->cObj->getSubpart($template, "###NEWTOPIC###");
                $marker = array(
                    '###LABEL_SEND###'              => $this->pi_getLL('newPost.save'),
                    '###LABEL_PREVIEW###'           => $this->pi_getLL('newPost.preview'),
                    '###LABEL_RESET###'             => $this->pi_getLL('newPost.reset'),
                    '###LABEL_ATTENTION###'         => $this->pi_getLL('newPost.attention'),
                    '###LABEL_NOTECODESAMPLES###'   => $this->pi_getLL('newPost.codeSamples'),
                    '###LABEL_ATTACHMENT###'        => $this->pi_getLL('newPost.attachment')
                );
                
                // Remove file attachment section if file attachments are disabled
                if(!$this->conf['attachments.']['enable']) $template = $this->cObj->substituteSubpart($template, "###ATTACHMENT_SECTION###", '');
                
                // Remove file attachment edit section
                $template = $this->cObj->substituteSubpart($template,'###ATTACHMENT_EDITSECTION###', '');
                
                	// Add attachment input fields according to TypoScript setting
                $fieldCount = $this->conf['attachments.']['maxCount']?$this->conf['attachments.']['maxCount']:1;
                $aTemplate = $this->cObj->getSubpart($template, '###ATTACHMENT_FIELD###');
                
                for($i=1; $i <= $fieldCount; $i ++) {
                	$aMarker = array(
                		'###ATTACHMENT_NO###'		=> $i
                	);
                	$aContent .= $this->cObj->substituteMarkerArray($aTemplate, $aMarker);
                }
                $template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_FIELD###', $aContent);
                
                // Remove poll section
                $template = $this->cObj->substituteSubpart($template,'###POLL_SECTION###', '');
                
                // Maximum file size
                $mFileSize = $this->conf['attachments.']['maxFileSize'].' B';
                if($this->conf['attachments.']['maxFileSize'] >= 1024     ) $mFileSize = round($this->conf['attachments.']['maxFileSize'] / 1024,2).' KB';
                if($this->conf['attachments.']['maxFileSize'] >= 1024*1024) $mFileSize = round($this->conf['attachments.']['maxFileSize'] / (1024*1024),2).' MB';
                
                $marker['###MAXFILESIZE_TEXT###'] = sprintf($this->pi_getLL('newPost.maxFileSize'),$mFileSize);
                $marker['###MAXFILESIZE_TEXT###'] = $this->cObj->stdWrap($marker['###MAXFILESIZE_TEXT###'],$this->conf['attachments.']['maxFileSize_stdWrap.']);
                $marker['###MAXFILESIZE###'] = $this->conf['attachments.']['maxFileSize'];

                // Inserting predefined message
                IF ($this->piVars['message']) {
                    $marker['###POSTTEXT###'] = $this->piVars['message'];
                }
                else {
                    // Load post to be quoted
                    IF ($this->piVars['quote']) {
                        if(!$this->getMayRead_post($this->piVars['quote'])) {
                            return $content.$this->errorMessage($conf,$this->pi_getLL('newPost.quote.error'));   
                        }

                        // Get user UID of quoted user
                        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                            "poster_id",
                            "tx_mmforum_posts",
                            "uid = '".intval($this->piVars['quote'])."'"
                        );
                        list($quoteuserid)  = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

                        // Get user name of quoted user
                        $quoteuser_array    = tx_mmforum_tools::get_userdata($quoteuserid);
                        $quoteuser          = $quoteuser_array[$this->getUserNameField()];

                        // Get text to be quoted
                        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                            "post_text",
                            "tx_mmforum_posts_text",
                            "post_id = '".intval($this->piVars['quote'])."'"
                        );
                        list($posttext)  = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                        #$posttext = htmlentities($posttext);

                        // Insert quote into message text.
                        $marker['###POSTTEXT###'] = "[quote=\"$quoteuser\"]\r\n$posttext\r\n[/quote]";
                    }
                    else {
                        $marker['###POSTTEXT###'] = '';
                    }
                }

                $actionParams[$this->prefixId] = array(
                    'action'            => 'new_post',
                    'tid'                => $this->piVars['tid']
                );
                $actionLink = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$actionParams);
                
                $bbCodeButtons_template = $this->cObj->getSubpart($template, '###BBCODEBUTTONS###');
                $bbCodeButtons = $this->generateBBCodeButtons($bbCodeButtons_template);
                $template = $this->cObj->substituteSubpart($template,'###BBCODEBUTTONS###',$bbCodeButtons);

                $marker['###SMILIES###']        = $this->show_smilie_db($conf);
                $marker['###ACTION###']         = htmlspecialchars($this->getAbsUrl($actionLink));
                $marker['###POSTTITLE###']      = $this->pi_getLL('newPost.title');

                $marker['###OLDPOSTTEXT###'] = '<hr />'.tx_mmforum_postfunctions::list_post('',$conf,'DESC');
            }

        }
        else {
            $template = $this->cObj->fileResource($conf['template.']['login_error']);
            $template = $this->cObj->getSubpart($template, "###LOGINERROR###");
            $marker = array();
            $marker['###LOGINERROR_MESSAGE###'] = $this->pi_getLL('newPost.noLogin');
        }
        
        // Include hooks
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPost_formMarker'])) {
                foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPost_formMarker'] as $_classRef) {
                    $_procObj = & t3lib_div::getUserObj($_classRef);
                    $marker = $_procObj->newPost_formMarker($marker, $this);
                }
            }
            
        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        
        return $content;
    }
    
    /**
     * Performs a file upload.
     * This function handles the storing of file attachments into the
     * database and the file system.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-21
     * @return  mixed The attachment UID if the process was successfull, otherwise an
     *                error message.
     */
    function performAttachmentUpload() {
        $deny   = t3lib_div::trimExplode(',',$this->conf['attachments.']['deny']);
        $allow  = t3lib_div::trimExplode(',',$this->conf['attachments.']['allow']);
        
        $fieldCount = $this->conf['attachments.']['maxCount']?$this->conf['attachments.']['maxCount']:1;
        
        for($i=1; $i <= $fieldCount; $i ++) {
	        $file = $_FILES['tx_mmforum_pi1_attachment_'.$i];
	        if(!$file['size']) continue;
	        
	        if(!$this->conf['attachments.']['enable']) return $this->errorMessage($this->conf,$this->pi_getLL('attachment.disabled'));
	        if($file['size'] > $this->conf['attachments.']['maxFileSize']) {
	            $fileSize = $file['size'].' B';
	            if($file['size'] >= 1024     ) $fileSize = round($file['size'] / 1024,2).' KB';
	            if($file['size'] >= 1024*1024) $fileSize = round($file['size'] / (1024*1024),2).' MB';
	            return $this->errorMessage($this->conf,sprintf($this->pi_getLL('attachment.toobig'),$fileSize));
	        }
	        if($allow[0] == '*' || (strlen($allow)==0)) {
	            if(count($deny)>0) {
	                foreach($deny as $denyItem)
	                    if(preg_match('/\.'.$denyItem.'$/i',$file['name'])) return $this->errorMessage($this->conf,$this->pi_getLL('attachment.denyed'));
	            }
	        }
	        else {
	            $valid = false;
	            if(count($allow)>0) {
	                foreach($allow as $allowItem)
	                    if(preg_match('/\.'.$allowItem.'$/i',$file['name'])) $valid = true;
	                if(!$valid) return $this->errorMessage($this->conf,$this->pi_getLL('attachment.denyed'));
	            }
	        }
	        
	        $newpath = $this->conf['attachments.']['attachmentDir'];
	        if(substr($newpath,-1,1)!='/') $newpath = $newpath.'/';
	        $newpath .= 'attachment_'.md5_file($file['tmp_name']);
	        
	        preg_match('/\.(.*?)$/',$file['name'],$ext);
	        $newpath .= '.'.$ext[1];
	        
	        move_uploaded_file($file['tmp_name'],$newpath);
	        chmod($newpath,intval($GLOBALS['TYPO3_CONF_VARS']['BE']['fileCreateMask'],8));
	        
	        $insertArray = array(
	            'pid'           => $this->getFirstPid(),
	            'tstamp'        => time(),
	            'crdate'        => time(),
	            'file_type'     => $file['type'],
	            'file_name'     => $file['name'],
	            'file_size'     => $file['size'],
	            'file_path'     => $newpath,
	        );
	        
	        // Include hooks
	            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['attachment_dataRecord'])) {
	                foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['attachment_dataRecord'] as $_classRef) {
	                    $_procObj = & t3lib_div::getUserObj($_classRef);
	                    $insertArray = $_procObj->attachment_dataRecord($insertArray, $this);
	                }
	            }
	        
	        $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_attachments',$insertArray);
	        $attachment_ids[] = $GLOBALS['TYPO3_DB']->sql_insert_id();
        }
        
        return $attachment_ids;
    }


    /**
     * Displays the form for editing an existing post. Regular users can only edit their own
     * posts if they have not been answered yet. Moderators and administrators can edit all
     * posts, regardless if they have been answered or not.
     * @param  string $content The plugin content
     * @param  array  $conf    The plugin's configuration vars
     * @return string          The content
     */
    function post_edit($content, $conf)
    {
        // Get topic UID
        $postlist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "*",
            "tx_mmforum_posts",
            "deleted = 0 AND hidden = 0 AND uid = '".intval($this->piVars['pid'])."'".$this->getPidQuery()
        );
        $row    = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($postlist);
        $topic_id = $row['topic_id'];

        // Determine, if edited post is the last post in topic
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "MAX(post_time)",
            "tx_mmforum_posts",
            "deleted = 0 AND hidden = 0 AND topic_id = '".$row['topic_id']."'".$this->getPidQuery()
        );
        list ($lastpostdate) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        // Determine if edited post is the first post in topic
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid',
            'tx_mmforum_posts',
            'deleted=0 AND hidden=0 AND topic_id='.$topic_id.' '.$this->getPidQuery(),
            '',
            'post_time ASC'
        );
        list($firstUID) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        $firstPost = (intval($this->piVars['pid']) == intval($firstUID));
        
        // Load topic data
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_topics',
            'deleted=0 AND hidden=0 AND uid='.$topic_id.' '.$this->getPidQuery()
        );
        $topicData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        
        IF ((($row['poster_id'] == $GLOBALS['TSFE']->fe_user->user['uid']) AND ($lastpostdate == $row['post_time'])) OR $this->getIsAdmin() OR $this->getIsMod($row['forum_id'])) {
            if($this->piVars['button'] == $this->pi_getLL('newPost.save')) {

                // Write changes to database
                $updateArray = array(
                    'post_text' => $this->piVars['message'],
                    'tstamp'    => time()
                );
                $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_posts_text', "post_id = '".intval($this->piVars['pid'])."'", $updateArray);
                
				// Check file upload
                    if($_FILES['tx_mmforum_pi1_attachment_1']['size']>0) {
                        $res = $this->performAttachmentUpload();
                        if(!is_array($res)) {
                            $content .= $res;
                            unset($this->piVars['button']);
                            return $this->new_post($content,$conf);
                        }
                        else {
                        	$attachment_ids = $res;
							$attachments = t3lib_div::intExplode(',',$row['attachment']);
							$attachments = tx_mmforum_tools::processArray_numeric($attachments);
							
							$updateArray = array(
								'attachment'	=> implode(',',array_merge($attachments,$attachment_ids))
							);
							$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
								'tx_mmforum_posts',
								'uid='.$row['uid'],
								$updateArray
		                    );
						}
                    } else $attachment_ids = null;
				
                if($this->piVars['attachment_delete']) {
                    foreach($this->piVars['attachment_delete'] as $uid=>$delete) {
	                    $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_attachments', 'uid='.intval($uid), array('deleted'=>1,'tstamp'=>time()));
	                    $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_posts', 'uid='.intval($uid), array('attachment'=>0,'tstamp'=>time()));
	                    $attachments = t3lib_div::intExplode(',',$row['attachment']);
	                    unset($attachments[array_search($uid,$attachments)]);
	                    $row['attachment'] = implode(',',$attachments);
                    }
                    $updateArray = array(
                    	'attachment' => $row['attachment']
                    );
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
						'tx_mmforum_posts',
						'uid='.$row['uid'],
						$updateArray
                    );
                }
                
                if($this->conf['polls.']['enable']) {
	                if($this->piVars['enable_poll'] == '1' && $firstPost) {
	                    $pollObj = t3lib_div::makeInstance('tx_mmforum_polls');
	                    if($topicData['poll_id'] > 0) {
	                        $res = $pollObj->editPoll($topicData['poll_id'],$this->piVars['poll'],$this);
	                        if($res) {
	                            $content .= $this->errorMessage($this->conf, $res);
	                            unset($this->piVars['button']);
	                            return $this->post_edit($content,$conf);
	                        }
	                    }
	                    else {
	                        $poll_id = $pollObj->createPoll($this->piVars['poll'],$this);
	                        if(!is_numeric($poll_id)) {
	                            $content .= $this->errorMessage($this->conf, $poll_id);
	                            unset($this->piVars['button']);
	                            return $this->post_edit($content,$conf);
	                        }
	                        $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics', 'uid='.$topic_id, array('poll_id' => $poll_id,'tstamp'=>time()));
	                    }
	                } elseif($firstPost) {
	                    if($topicData['poll_id'] > 0)
	                        $pollObj->deletePoll($topicData['poll_id'],$topicData['uid']);
	                }
                }


                if ($this->piVars['title'] AND (($this->getIsMod($row['forum_id']) || $this->getIsAdmin()) || ($firstPost && $row['poster_id']==$GLOBALS['TSFE']->fe_user->user['uid']))) {
                    $updateArray = array(
                        'topic_title'   => $this->piVars['title'],
                        'tstamp'        => time()
                    );
                    $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics', "uid = '".$this->get_topic_id($this->piVars['pid'])."'", $updateArray);
                }

                // If the editing user is no admin or mod, the change is logged in the database
                if (!$this->getIsMod($row['forum_id']) && !$this->getIsAdmin()) {
                    $mysql = "UPDATE tx_mmforum_posts SET edit_count = edit_count+1,edit_time='".time()."' WHERE uid = '".intval($this->piVars['pid'])."' LIMIT 1" ;
                    $GLOBALS['TYPO3_DB']->sql_query($mysql) or die(mysql_error());
                }

                // Clearing for new indexing
                tx_mmforum_indexing::delete_topic_ind_date($topic_id); 

                $linkParams[$this->prefixId] = array(
                    'action'  => 'list_post',
                    'tid'     => $topic_id,
                    'pid'     => $this->piVars['pid'],
                );
                $link = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams);
                $link = $this->getAbsUrl($link);
                header("Location: $link#pid".$this->piVars['pid']); die();
            }
            else {
                // Display post preview
                if($this->piVars['button'] == $this->pi_getLL('newPost.preview')) {
                    if($this->piVars['enable_poll'] == '1' && $this->conf['polls.']['enable']) $content .= tx_mmforum_polls::displayPreview($this->piVars['poll']);
                    
                    $template   = $this->cObj->fileResource($conf['template.']['list_post']);
                    $template   = $this->cObj->getSubpart($template, "###LIST_POSTS###");
                    
                    $template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_SECTION###', '');
                    
                    $posttext   = $this->piVars['message'];
                    $postold    = $posttext;
                    $posttext   = $this->bb2text($posttext,$conf);

                    $marker['###POSTOPTIONS###']    = '';
                    $marker['###SOLVEDOPTION###']   = '';
                    $marker['###POSTMENU###']       = '';
                    $marker['###POSTUSER###']       = $this->ident_user($row['poster_id'],$conf);
                    $marker['###POSTTEXT###']       = $posttext;
                    $marker['###ANKER###']          = '';
                    $marker['###POSTDATE###']       = $this->pi_getLL('post.writtenOn').': '.$this->formatDate(time());
                    
                    $previewTemplate    = $this->cObj->fileResource($conf['template.']['new_post']);
                    $previewTemplate    = $this->cObj->getSubpart($previewTemplate,"###PREVIEW###");
                    $previewMarker = array(
                        "###TOPIC_TITLE###"        => $this->shield($this->piVars['topicname']),
                        "###LABEL_PREVIEW###"      => $this->pi_getLL('newTopic.preview'),
                        "###PREVIEW_POST###"       => $this->cObj->substituteMarkerArrayCached($template, $marker)
                    );
                    
                    // Include hooks
                        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['editPost_previewMarker'])) {
                            foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['editPost_previewMarker'] as $_classRef) {
                                $_procObj = & t3lib_div::getUserObj($_classRef);
                                $previewMarker = $_procObj->editPost_previewMarker($previewMarker, $this);
                            }
                        }
                    
                    $content .= $this->cObj->substituteMarkerArrayCached($previewTemplate, $previewMarker);
                }

                $template    = $this->cObj->fileResource($conf['template.']['new_post']);
                $template    = $this->cObj->getSubpart($template, "###NEWTOPIC###");

				$attachments = t3lib_div::intExplode(',',$row['attachment']);
				$attachments = tx_mmforum_tools::processArray_numeric($attachments);
				$attachCount = count($attachments);

				if($attachCount == $this->conf['attachments.']['maxCount'])
	                $template   = $this->cObj->substituteSubpart($template, "###ATTACHMENT_SECTION###", '');
				else {
					$attachDiff = $this->conf['attachments.']['maxCount'] - $attachCount;
					$aTemplate	= $this->cObj->getSubpart($template, '###ATTACHMENT_FIELD###');
					$aContent = '';
					
					for($i=1; $i <= $attachDiff; $i ++) {
						$aMarker		= array(
							'###ATTACHMENT_NO###'		=> $i
						);
						$aContent	   .= $this->cObj->substituteMarkerArray($aTemplate, $aMarker);
					}
					
					$marker		= array(
						'###LABEL_ATTACHMENT###'		=> $this->pi_getLL('newPost.attachment'),
						'###MAXFILESIZE###'				=> $this->conf['attachments.']['maxFileSize']
					);
					
						// Maximum file size
	                $mFileSize = $this->conf['attachments.']['maxFileSize'].' B';
	                if($this->conf['attachments.']['maxFileSize'] >= 1024     ) $mFileSize = round($this->conf['attachments.']['maxFileSize'] / 1024,2).' KB';
	                if($this->conf['attachments.']['maxFileSize'] >= 1024*1024) $mFileSize = round($this->conf['attachments.']['maxFileSize'] / (1024*1024),2).' MB';
	                
	                $marker['###MAXFILESIZE_TEXT###'] = sprintf($this->pi_getLL('newPost.maxFileSize'),$mFileSize);
	                $marker['###MAXFILESIZE_TEXT###'] = $this->cObj->stdWrap($marker['###MAXFILESIZE_TEXT###'],$this->conf['attachments.']['maxFileSize_stdWrap.']);
					
					$template	= $this->cObj->substituteMarkerArray($template, $marker);
					$template	= $this->cObj->substituteSubpart($template, '###ATTACHMENT_FIELD###', $aContent);
				}
                
                $marker        = array();
                
                if(strlen($row['attachment'])==0) 
                    $template = $this->cObj->substituteSubpart($template,'###ATTACHMENT_EDITSECTION###', '');
                else {
                    $aRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                        '*',
                        'tx_mmforum_attachments',
                        'uid IN ('.$row['attachment'].') AND deleted=0',
                        '',
                        'uid ASC'
                    );
	                    
	                $marker['###LABEL_ATTACHMENT###'] = $this->pi_getLL('newPost.attachment');                    
                    $aTemplate = $this->cObj->getSubpart($template, '###ATTACHMENT_EDITFIELD###');
                    $aContent = '';
					
                    while($attachment = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($aRes)) {
	                    $size = $attachment['file_size'].' '.$this->pi_getLL('attachment.bytes');
	                    if($attachment['file_size'] > 1024) $size = round($attachment['file_size']/1024,2).' '.$this->pi_getLL('attachment.kilobytes');
	                    if($attachment['file_size'] > 1048576) $size = round($attachment['file_size']/1048576,2).' '.$this->pi_getLL('attachment.megabytes');

	                    $aMarker['###LABEL_DELETEATTACHMENT###'] = $this->pi_getLL('attachment.delete');
	                    
	                    $sAttachment = $attachment['file_name'].' ('.$this->pi_getLL('attachment.type').': '.$attachment['file_type'].', '.$this->pi_getLL('attachment.size').': '.$size.') &mdash; '.$attachment['downloads'].' '.$this->pi_getLL('attachment.downloads');
	                    $sAttachment = $this->shield($sAttachment);
	                    $sAttachment = $this->cObj->stdWrap($sAttachment, $this->conf['attachments.']['attachmentEditLabel_stdWrap.']);
	                    
	                    $aMarker['###ATTACHMENT_DATA###'] = $sAttachment;
	                    $aMarker['###ATTACHMENT_UID###']  = $attachment['uid'];
	                    $aContent .= $this->cObj->substituteMarkerArray($aTemplate, $aMarker);
                    }
                    $template = $this->cObj->substituteSubpart($template, '###ATTACHMENT_EDITFIELD###', $aContent);
                }
                
                if($firstPost && $this->conf['polls.']['enable']) {
                    $pollObj = t3lib_div::makeInstance('tx_mmforum_polls');
                    if($topicData['poll_id'] == 0) {
                        $marker['###POLL###']           = $pollObj->display_createForm($this->piVars['poll']?$this->piVars['poll']:array(),$this);
                        $marker['###ENABLE_POLL###']    = $this->piVars['enable_poll']?'checked="checked"':'';
                        $marker['###POLLDIV_STYLE###']  = $this->piVars['enable_poll']?'':'style="display:none;"';
                        $marker['###LABEL_POLL_CE###']  = $this->pi_getLL('poll.postattach.new');
                    }
                    else {
                        $marker['###POLL###']           = $pollObj->display_editForm($topicData['poll_id'],$this->piVars['poll']?$this->piVars['poll']:array(),$this);
                        $marker['###ENABLE_POLL###']    = 'checked="checked"';
                        $marker['###POLLDIV_STYLE###']  = '';
                        $marker['###LABEL_POLL_CE###']  = $this->pi_getLL('poll.postattach.edit');
                    }
                    $marker['###LABEL_POLL###']     = $this->pi_getLL('poll.postattach');
                } else $template = $this->cObj->substituteSubpart($template, '###POLL_SECTION###', '');

                $pid = $this->piVars['pid'];

                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('post_text','tx_mmforum_posts_text','post_id="'.$pid.'"');
                list($posttext) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title','tx_mmforum_topics','uid="'.$this->get_topic_id($pid).'"');
                list($title) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

                // Include editor Javascript
                $content   .=   $this->cObj->fileResource($conf['scripts.']['editor']); 
                #$posttext = htmlentities($posttext);

                IF ($this->piVars['message']) {
                    $marker['###POSTTEXT###']          = $this->shield($this->piVars['message']);
                }
                else {
                    $marker['###POSTTEXT###']          = $this->shield($posttext);
                }

                if($this->getIsMod($row['forum_id']) || $this->getIsAdmin()) {
                    $marker['###POSTTITLE###']         = '<div style="padding-left:130px;">'.$this->pi_getLL('newPost.titleField').' : <input type="text"  name="tx_mmforum_pi1[title]" size="50" value="'.$this->shield($title).'"></div>';
                }
                elseif($firstPost && $row['poster_id'] == $GLOBALS['TSFE']->fe_user->user['uid'])
                    $marker['###POSTTITLE###']         = '<div style="padding-left:130px;">'.$this->pi_getLL('newPost.titleField').' : <input type="text"  name="tx_mmforum_pi1[title]" size="50" value="'.$this->shield($title).'"></div>';
                else {
                    $marker['###POSTTITLE###']         = $this->pi_getLL('newPost.title');
                }

                $marker['###OLDPOSTTEXT###']           = '';
                $marker['###SMILIES###']               = $this->show_smilie_db($conf);
                $marker['###SOLVEDOPTION###']          = '';
                $marker['###ACTION###']                = htmlspecialchars($this->getAbsUrl($this->pi_getPageLink($GLOBALS['TSFE']->id,'',array($this->prefixId=>array('action'=>'post_edit','pid'=>$this->piVars['pid'])))));
                
                $marker['###LABEL_SEND###']            = $this->pi_getLL('newPost.save');
                $marker['###LABEL_PREVIEW###']         = $this->pi_getLL('newPost.preview');
                $marker['###LABEL_RESET###']           = $this->pi_getLL('newPost.reset');
                $marker['###LABEL_ATTENTION###']       = $this->pi_getLL('newPost.attention');
                $marker['###LABEL_NOTECODESAMPLES###'] = $this->pi_getLL('newPost.codeSamples');
                
                $bbCodeButtons_template = $this->cObj->getSubpart($template, '###BBCODEBUTTONS###');
                $bbCodeButtons = $this->generateBBCodeButtons($bbCodeButtons_template);
                $template = $this->cObj->substituteSubpart($template,'###BBCODEBUTTONS###',$bbCodeButtons);

            }
        } else {
            $template = $this->cObj->fileResource($conf['template.']['error']);
            $marker = array();
            $marker['###ERROR###'] = $this->pi_getLL('editPost.noAccess');
        }

        // Include hooks
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['editPost_formMarker'])) {
                foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['editPost_formMarker'] as $_classRef) {
                    $_procObj = & t3lib_div::getUserObj($_classRef);
                    $marker = $_procObj->editPost_formMarker($marker, $this);
                }
            }

        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        return $content;
    }
    
    /**
     * Favorites
     */

    /**
     * Adds a topic to the current user's favorites.
     * @param  string $content The plugin content
     * @param  array  $conf    The configuration vars
     * @return string          An error message in case the redirect attempt to the previous
     *                         page fails.
     */
    function set_favorite ($content,$conf) {
        $topicid = $this->piVars['tid'];
        $topicid = intval($topicid);            // Parse to int for security reasons
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "uid",
            "tx_mmforum_favorites",
            "user_id = ".$GLOBALS['TSFE']->fe_user->user['uid']." AND topic_id='$topicid'".$this->getPidQuery()
        );
        IF ($GLOBALS['TYPO3_DB']->sql_num_rows($res) < 1) {
            $insertArray = array(
                'pid'         => $this->getFirstPid(),
                'tstamp'     => time(),
                'crdate'     => time(),
                'topic_id'   => $topicid,
                'user_id'    => $GLOBALS['TSFE']->fe_user->user['uid']
            );
            
            // Include hooks
                if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['setFavorite_dataRecord'])) {
                    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['setFavorite_dataRecord'] as $_classRef) {
                        $_procObj = & t3lib_div::getUserObj($_classRef);
                        $insertArray = $_procObj->setFavorite_dataRecord($insertArray, $this);
                    }
                }
            
            $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_favorites', $insertArray);
        }

        // Redirect back to previous page
        $ref= getenv("HTTP_REFERER");
        $content = $this->pi_getLL('favorites.addSuccess').'<br />'.$this->pi_getLL('redirect.error').'<br />';
        if ($ref) header('Location: '.$this->getAbsUrl($ref));
        return $content;
    }

    /**
     * Deletes a topic from the current user's favorites
     * @param  string $content The plugin content
     * @param  array  $conf    The configuration vars
     * @return string          An error message in case the redirect attempt to the previous
     *                         page fails
     */
    function del_favorite ($content,$conf) {
        $topicid = $this->piVars['tid'];
        $topicid = intval($topicid);            // Parse to int for security reasons

        $res = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_favorites', $GLOBALS['TSFE']->fe_user->user['uid']." AND topic_id = ".$topicid); 

        // Redirect back to previous page
        $ref= getenv("HTTP_REFERER");
        $content = $this->pi_getLL('favorites.delSuccess').'<br />'.$this->pi_getLL('redirect.error').'<br />';
        if ($ref) header('Location: '.$this->getAbsUrl($ref));
        return $content;
    }

    /**
     * Displays the current user's favorite topics. Performs also operations like
     * editing or deleting favorites.
     * @param  string $content The plugin content
     * @param  array  $conf    The configuration vars
     * @return string          The content
     */
    function favorites ($content,$conf) {
        if(isset($GLOBALS['TSFE']->fe_user->user['uid'])) {
            
            // Delete favorite
            if($this->piVars['fav']['deltid']) {
                $del_tid = $this->piVars['fav']['deltid'];
                $del_tid = intval($del_tid);        // Parse to int for security reasons
                $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_favorites',"user_id='".$GLOBALS['TSFE']->fe_user->user['uid']."' AND topic_id='$del_tid'");
                
                unset($this->piVars['fav']);
            }

            // Delete multiple favorites
            if($this->piVars['fav']['action'] == "delete") {
                foreach($this->piVars['fav']['delete'] as $del_tid) {
                    $del_tid = intval($del_tid);        // Parse to int for security reasons
                    $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_favorites',"user_id='".$GLOBALS['TSFE']->fe_user->user['uid']."' AND topic_id='$del_tid'");;
                }
                unset($this->piVars['fav']);
            }

            // Determine sorting mode
            #$orderBy = t3lib_div::GPvar('order')?t3lib_div::GPvar('order'):"added";
            $orderBy = $this->piVars['order']?$this->piVars['order']:"added";

                if( $orderBy == "lpdate")   $order = "t.topic_last_post_id DESC";
            elseif( $orderBy == "cat")      $order = "c.sorting ASC, f.sorting ASC, t.topic_last_post_id DESC";
            elseif( $orderBy == "added")    $order = "fa.uid DESC";           
            elseif( $orderBy == "alphab")   $order = "t.topic_title ASC";
            else                            $order = "fa.uid DESC";

            // Output sorting options form
            $template = $this->cObj->fileResource($conf['template.']['favorites']);
            $template = $this->cObj->getSubpart($template, "###FAVORITES_SETTINGS###");
            $settingsMarker = Array(
                "###ACTION###"              => htmlspecialchars($this->getAbsUrl($this->pi_linkTP_keepPIvars_url())),
                "###ORDER_LPDATE###"        => ($orderBy == "lpdate")?'selected="selected"':'',
                "###ORDER_CAT###"           => ($orderBy == "cat"   )?'selected="selected"':'',
                "###ORDER_ADDED###"         => ($orderBy == "added" )?'selected="selected"':'',
                "###ORDER_ALPHAB###"        => ($orderBy == "alphab")?'selected="selected"':'',
                
                '###LABEL_ORDERBY###'       => $this->pi_getLL('favorites.orderBy'),
                '###LABEL_ORDER_LPDATE###'  => $this->pi_getLL('favorites.orderBy.lpdate'),
                '###LABEL_ORDER_CAT###'     => $this->pi_getLL('favorites.orderBy.cat'),
                '###LABEL_ORDER_ADDED###'   => $this->pi_getLL('favorites.orderBy.added'),
                '###LABEL_ORDER_ALPHAB###'  => $this->pi_getLL('favorites.orderBy.alphab'),
            );
            
            // Include hooks
                if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_header'])) {
                    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_header'] as $_classRef) {
                        $_procObj = & t3lib_div::getUserObj($_classRef);
                        $settingsMarker = $_procObj->listFavorites_header($settingsMarker, $this);
                    }
                }       
            
            $content .= $this->cObj->substituteMarkerArrayCached($template, $settingsMarker);

            $template = $this->cObj->fileResource($conf['template.']['favorites']);
            $template = $this->cObj->getSubpart($template, "###FAVORITES_BEGIN###");
            $marker = Array(
                "###ACTION###"                  => $this->shieldURL($this->getAbsUrl($this->pi_linkTP_keepPIvars_url())),
                '###LABEL_OPTIONS###'           => $this->pi_getLL('favorites.options'),
                '###LABEL_FAVORITES###'         => $this->pi_getLL('favorites.title'),
                '###LABEL_TOPICNAME###'         => $this->pi_getLL('topic.title'),
                '###LABEL_CONFIRMMULTIPLE###'   => $this->pi_getLL('havealook.confirmMultiple')
            );
            $marker = array_merge($marker, $settingsMarker);
            
            // Include hooks
                if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_options'])) {
                    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_options'] as $_classRef) {
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
                'fa.user_id       = "'.$GLOBALS['TSFE']->fe_user->user['uid'].'" AND
                 t.uid            = fa.topic_id AND
                 f.uid            = t.forum_id AND
                 c.uid            = f.parentID AND
                 t.deleted        = 0 AND
                 fa.deleted       = 0 '.
                 $this->getPidQuery('fa,t,f,c').
                 $this->getMayRead_forum_query('c').
                 $this->getMayRead_forum_query('f'),
                '',
                $order
            );
            
            $template = $this->cObj->fileResource($conf['template.']['favorites']);
            
            if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) {
                $template = $this->cObj->getSubpart($template, '###LIST_FAVORITES_EMPTY###');
                $marker = array(
                    '###LLL_FAVORITES_EMPTY###'     => $this->pi_getLL('favorites.empty')
                );
                $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
            } else {
                $template = $this->cObj->getSubpart($template, "###LIST_FAVORITES###");
            
                while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

                    $topicParams[$this->prefixId] = array(
                        'action'        => 'list_post',
                        'tid'            => $row['topic_id']
                    );
                    $topicLink = $this->pi_linkToPage($this->shield($row['topic_title']),$this->getForumPID(),'',$topicParams);
                    $delParams[$this->prefixId]['fav']['deltid'] = $row['topic_id'];
                    $delLink = $this->pi_linkTP($this->pi_getLL('favorites.delete'),$delParams);
                    
                    $marker['###TOPIC_CHECKBOX###'] = '<input type="checkbox" name="'.$this->prefixId.'[fav][delete][]" value="'.$row['topic_id'].'" />';
                    $marker['###TOPICNAME###']      = $topicLink;
                    $marker['###TOPICSUB###']       = $this->shield($row['cat_title'].' / '.$row['forum_name']);
                    $marker['###TOPICDELLINK###']   = $delLink;
                    $marker['###TOPICICON###']      = $this->getTopicIcon($row);
                    
                    // Include hooks
                        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_listItem'])) {
                            foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_listItem'] as $_classRef) {
                                $_procObj = & t3lib_div::getUserObj($_classRef);
                                $marker = $_procObj->listFavorites_listItem($marker, $row, $this);
                            }
                        }
                    
                    $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
                }
            }

            $template = $this->cObj->fileResource($conf['template.']['favorites']);
            $template = $this->cObj->getSubpart($template, "###FAVORITES_END###");
            
            $marker = array(
                '###LABEL_MARKEDTOPICS###'      => $this->pi_getLL('havealook.markedTopics'),
                '###LABEL_DELETE###'            => $this->pi_getLL('havealook.delete'),
                '###LABEL_GO###'                => $this->pi_getLL('havealook.go'),
            );
            
            // Include hooks
                if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_footer'])) {
                    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['listFavorites_footer'] as $_classRef) {
                        $_procObj = & t3lib_div::getUserObj($_classRef);
                        $marker = $_procObj->listFavorites_footer($marker, $this);
                    }
                }
            
            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        }
        else {
            $template = $this->cObj->fileResource($conf['template.']['login_error']);
            $template = $this->cObj->getSubpart($template, "###LOGINERROR###");
            $marker = array();
            $marker['###LOGINERROR_MESSAGE###'] = $this->pi_getLL('favorites.noLogin');
            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
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
     * @version 2007-05-03
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
            
            $marker = array(
                '###CODE_IMAGE###'          => $imgpath,
                '###CODE_LABEL###'          => $this->shield($title),
                '###CODE_NUMBER###'         => $i,
            );
            
            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
            preg_match('/\[(.*?)\]\|\[\/(.*?)\]/',$arr['bbcode'],$items);
            
            $items[1] = str_replace('|','',$items[1]);
            $items[2] = str_replace('|','',$items[2]);
            
            $bbItems[$i] = '\'['.strtolower($items[1]).']\'';
            $bbItems[$i+1] = '\'[/'.strtolower($items[2]).']\'';
            $i += 2;
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
                    '###CODE_LABEL###'          => $this->shield($title),
                    '###CODE_NUMBER###'         => $i
                );
                
                $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
                
                $bbItems[$i] = '\'['.strtolower($arr['lang_code']).']\'';
                $bbItems[$i+1] = '\'[/'.strtolower($arr['lang_code']).']\'';
                $i += 2;
            }
        }
        
        $GLOBALS['TSFE']->additionalHeaderData['mm_forum'] .= '<script type="text/javascript">
        var bbtags = new Array('.implode(',',(array)$bbItems).');
        </script>';
        
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
            $imgInfo['src'] = $conf['path_smilie'].$row['smile_url'];
            $imgInfo['alt'] = $row['emoticon'];
            if($this->conf['postForm.']['smiliesAsDiv']) {
            	$content .= $this->cObj->wrap("<a href=\"javascript:emoticon('".$row['code']."')\">".$this->imgtag($imgInfo)."</a>",$this->conf['postForm.']['smiliesAsDiv.']['allWrap']);
            } else {
	            if($i >= 4){
	                $content .= "\r\n</tr><tr>\r\n";
	                $i = 0;
	            }
	            $i++;
	            $content .="<td><a href=\"javascript:emoticon('".$row['code']."')\">".$this->imgtag($imgInfo)."</a></td>\n";
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
     * @param  int  $topic_id The topic's UID
     * @return void
     */
    function update_lastpost_topic($topic_id) {
        $updateArray = array(
            'topic_last_post_id'    => $this->get_last_post($topic_id),
            'tstamp'                => time()
        );
        $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics', "uid='$topic_id'", $updateArray);
    }

    /**
     * Determines the last post in a board and updates the board record.
     * @param  int  $forum_id The boards's UID
     * @return void
     */
    function update_lastpost_forum($forum_id) {
        $forum_id = intval($forum_id);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "*",
            "tx_mmforum_posts",
            "forum_id = ".$forum_id." AND deleted = 0 AND hidden = 0 ".$this->getPidQuery(),
            "",
            "crdate DESC",
            "1"
        );
        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        
        $updateArray = array(
            'forum_last_post_id'    => $row['uid'],
            'tstamp'                => time()
        );
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_forums',"uid='$forum_id'",$updateArray);
    }

    /**
     * Sets the solved status of a topic.
     * @param  int  $topic_id The UID of the topic
     * @param  bool $solved   The desired solved status of the topic
     * @return void
     */
    function set_solved($topic_id,$solved) {
        $solved = intval($solved);
        $updArray = array(
            'solved'    => $solved,
            'tstamp'    => time()
        );
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics',"uid='$topic_id'",$updArray);
    }

    /**
     * Sends an e-mail to users who have subscribed a certain topic.
     * @param  string $content The plugin content
     * @param  array  $conf    The configuration vars
     * @param  int    $topic   The UID of the topic about which the users are
     *                        to be alerted.
     * @return void
     */
    function send_newpost_mail ($content,$conf,$topic_id)
    {
        $topic_id = intval($topic_id);
        list ($topic_name) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title','tx_mmforum_topics',"uid='$topic_id'".$this->getPidQuery()));

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('user_id','tx_mmforum_topicmail',"topic_id='$topic_id'".$this->getPidQuery());
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

            list($to_username, $to_usermail) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery($this->getUserNameField().',email','fe_users','uid="'.$row['user_id'].'"'));

            $header .= "From: ".$conf['notifyingMail.']['sender']."\n";
            $header .= "X-Mailer: PHP/" . phpversion(). "\n";
            $header .= "X-Sender-IP: ".getenv("REMOTE_ADDR")."\n";
            $header .= "Content-type: text/plain;charset=".$GLOBALS['TSFE']->renderCharset."\n";


            $template = $this->pi_getLL('ntfMail.text');
            $marker['###USERNAME###'] = $this->shield($to_username);
            
            $linkParams[$this->prefixId] = array(
                'action' => 'open_topic',
                'id'     => $topic_id
            );
            $link = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams);
            if(strlen($conf['notifyingMail.']['topicLinkPrefix_override'])>0) {
                $link = $conf['notifyingMail.']['topicLinkPrefix_override'].$link;
            } else $link = $this->getAbsUrl($link);
            
            $marker['###LINK###'] = $this->shieldURL($link);
            
            $mailtext = $this->cObj->substituteMarkerArrayCached($template, $marker);

            // Compose mail and send
            #if (!empty($to_usermail) && $row['user_id'] != $GLOBALS['TSFE']->fe_user->user['uid']) {
			if (!empty($to_usermail)) {
                $llMarker = array(
                    '###TOPICNAME###'        => $this->shield($topic_name),
                    '###BOARDNAME###'        => $this->shield($conf['boardName'])
                );
                
                $subject = $this->pi_getLL('ntfMail.subject');
                
                // Include hooks
                    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_contentMarker'])) {
                        foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_contentMarker'] as $_classRef) {
                            $_procObj = & t3lib_div::getUserObj($_classRef);
                            $llMarker = $_procObj->newPostMail_contentMarker($llMarker, $row, $this);
                        }
                    }
                    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_subject'])) {
                        foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['newPostMail_subject'] as $_classRef) {
                            $_procObj = & t3lib_div::getUserObj($_classRef);
                            $subject = $_procObj->newPostMail_subject($subject, $row, $this);
                        }
                    }
                
                $subject = $this->cObj->substituteMarkerArray($subject,$llMarker);
				mail($to_usermail,$subject,$mailtext, $header);
            }
        }
    }
	
    /**
     * Sends an e-mail to users who have subscribed to certain forumcategory
     * @param  string $content The plugin content
     * @param  array  $conf    The configuration vars
     * @param  int    $topic_id   The UID of the new topic that was created
	 *	@param  int    $forum_id   The UID of the forum about which the users are
     *                        to be alerted.
     * @return void
	 * @author Cyrill Helg
     */
    function send_newpost_mail_forum ($content,$conf,$topic_id,$forum_id)
    {
        list ($topic_name) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title','tx_mmforum_topics',"uid='$topic_id'".$this->getPidQuery()));

		list ($forum_name) =
		$GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery(' forum_name','tx_mmforum_forums',"uid='$forum_id'".$this->getPidQuery()));
				
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('user_id','tx_mmforum_forummail',"forum_id='$forum_id'".$this->getPidQuery());
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

            list($to_username, $to_usermail) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery($this->getUserNameField().',email','fe_users','uid="'.$row['user_id'].'"'));

            $header .= "From: ".$conf['notifyingMail.']['sender']."\n";
            $header .= "X-Mailer: PHP/" . phpversion(). "\n";
            $header .= "X-Sender-IP: ".getenv("REMOTE_ADDR")."\n";
		    $header .= "Content-type: text/plain;charset=".$GLOBALS['TSFE']->renderCharset."\n";


            $template = $this->pi_getLL('ntfMailForum.text');
            $marker['###USERNAME###']  = $this->shield($to_username);
            $marker['###FORUMNAME###'] = $this->shield($forum_name);
            $linkParams[$this->prefixId] = array(
                'action' => 'open_topic',
                'id'     => $topic_id
            );
            $link = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams);
            if(strlen($conf['notifyingMail.']['topicLinkPrefix_override'])>0) {
                $link = $conf['notifyingMail.']['topicLinkPrefix_override'].$link;
            } else $link = $this->getAbsUrl($link);
            
            $marker['###LINK###'] = $this->shieldURL($link);
            
            $mailtext = $this->cObj->substituteMarkerArrayCached($template, $marker);

            // Compose mail and send
            if ($row['user_id'] <> $GLOBALS['TSFE']->fe_user->user['uid']) {
                $llMarker = array(
                    '###TOPICNAME###'        => $this->shield($topic_name),
					'###FORUMNAME###'	     => $this->shield($forum_name),
                    '###BOARDNAME###'        => $this->shield($conf['boardName'])
                );
                $subject = $this->cObj->substituteMarkerArray($this->pi_getLL('ntfMailForum.subject'),$llMarker);
                mail($to_usermail,$subject,$mailtext, $header);
            }
        }
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
        if($this->getIsRealURL() && $this->piVars['fid']) $this->piVars['user_id'] = tx_mmforum_tools::get_userid($this->piVars['fid']);
        if(!is_numeric($this->piVars['user_id'])) {
            $this->piVars['user_id'] = $this->get_userid($this->piVars['user_id']);
        }
        return tx_mmforum_user::list_user_post($conf,$this->piVars['user_id'],$this->piVars['page']);
    }
    
    /**
     * Displays information about a certain user, whose UID is submitted via GP-Vars.
     * @param  string $content The content of the plugin
     * @param  array  $conf    The configuration vars for the plugin
     * @return string          The new content of the plugin
     */
    function view_profil ($content,$conf){
        $imgInfo = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');
        $user_id = intval($this->piVars['user_id']);
        
        if($this->getIsRealURL() && $this->piVars['fid']) {
            $user_id = tx_mmforum_tools::get_userid($this->piVars['fid']);
        }
        
        $template = $this->cObj->fileResource($conf['template.']['userdetail']);
        $template = $this->cObj->getSubpart($template, "###USERDETAIL###");
        
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_users',"uid='$user_id'");
        
        if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0)
            return $this->errorMessage($conf, $this->pi_getLL('user.error_notExist'));
        
        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

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
            );
        
        // Username
            $marker['###USER###']                	= $this->shield($row[$this->getUserNameField()]);
        // Date of registration
            $marker['###REGDATE###']				= $this->cObj->stdWrap($row['crdate'],$this->conf['user_profile.']['crdate_stdWrap.']);
        // Number of posts
            $marker['###TOTALPOSTS###']             = intval($row['tx_mmforum_posts']);
            if($row['tx_mmforum_posts'] >= $conf['user_hotposts']) {
                // Special icon for users with more than a certain number posts defined in TypoScript
                $llMarker = array('###HOTPOSTS###' => $conf['user_hotposts']);
                $str = $this->cObj->substituteMarkerArray($this->pi_getLL('user.hot'),$llMarker);
                $imgInfo['src']                     = $conf['path_img'].$conf['images.']['5kstar'];
                $imgInfo['alt']                     = $str;
                $marker['###TOTALPOSTS###']        .= $this->imgtag($imgInfo);
            }

        // Location
            $marker['###LOCATION###']               = $this->shield($row['city']);

        // Internet address
                if(strlen($row['www']) == 0)                $marker['###WEBSITE###'] = "";
            elseif(substr($row['www'],0,7) != "http://")    $marker['###WEBSITE###'] = '<a href="http://'.$row['www'].'">http://'.$row['www'].'</a>';
            else                                            $marker['###WEBSITE###'] = '<a href="'.$row['www'].'">'.$row['www'].'</a>';

        // Profession
            $marker['###PROFESSION###']             = $this->shield($row['tx_mmforum_occ']);
        // Interests
            $marker['###INTERESTS###']              = $this->shield($row['tx_mmforum_interests']);

        // Avatar
            if ($conf['path_avatar'] && $row['tx_mmforum_avatar'])  {
                
                $marker['###AVATAR###']             = tx_mmforum_tools::res_img($conf['path_avatar'].$row['tx_mmforum_avatar'],$conf['avatar_width'],$conf['avatar_height']);
            } else {
                $marker['###AVATAR###']             = "";
            }
        // E-Mail (currently deactivated)
            $marker['###MAIL###']                   = '';  #'<a href="index.php?id='.$GLOBALS["TSFE"]->id.'&tx_mmforum_pi1[action]=send_mail&tx_mmforum_pi1[uid]='.$row['uid'].'"><img src="'.$conf['path_img'].'mail.gif" border="0"></a>';
        
        // Private Messaging
            $linkParams['tx_mmforum_pi3'] = array (
                'action'    => 'message_write',
                'folder'    => 'inbox',
                'messid'    => $this->pi_getLL('realurl.pmnew'),
                'userid'    => $row['uid']
            );
            $marker['###PM###']                     = $this->createButton('pm',$linkParams,$this->conf['pm_id'],true);
    
        // YIM-Button
            $marker['###LABEL_YIM###']              = $this->createButton('yim',array(),0,true,'',true);
            $marker['###YIM###']                    = $this->shield($row['tx_mmforum_yim']);

        // MSM-Button
            $marker['###LABEL_MSN###']              = $this->createButton('msn',array(),0,true,'',true);
            $marker['###MSN###']                    = $this->shield($row['tx_mmforum_msn']);

        // AIM-Button
            $marker['###LABEL_AIM###']              = $this->createButton('aim',array(),0,true,'',true);
            $marker['###AIM###']                    = $this->shield($row['tx_mmforum_aim']);

        // ICQ-Button
            $marker['###LABEL_ICQ###']              = $this->createButton('icq',array(),0,true,'',true);
            $marker['###ICQ###']                    = $this->shield($row['tx_mmforum_icq']);
            
         // SKYPE-Button
            $marker['###LABEL_SKYPE###']            = $this->createButton('skype',array(),0,true,'',true);
            $marker['###SKYPE###']                  = $this->shield($row['tx_mmforum_skype']);

        // A link to a page presenting the last 10 posts by this user
            $linkparams = array();
            $linkparams[$this->prefixId] = array (
                'action'    => 'post_history',
                'user_id'   => $user_id
            );
            if($this->getIsRealURL()) {
                unset($linkparams[$this->prefixId]['user_id']);
                $linkparams[$this->prefixId]['fid'] = $row['username'];
            }
            $marker['###10POSTS###']        = $this->pi_linkToPage($this->pi_getLL('user.lastPostsLink'),$GLOBALS['TSFE']->id,'',$linkparams).'<br />';

        // A list of the last 10 topic created by this user
            $marker['###10TOPICS###']        = $this->view_last_10_topics($user_id);

        // The number of topics created by this user (currently not used?)
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('COUNT(uid)','tx_mmforum_topics',"topic_poster='$user_id'".$this->getPidQuery());
            list($topic_num) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            $marker['###THEMEN###']         = "<strong>".$topic_num."</strong>";

        // The last post made by this user (currently not used?)
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,topic_id','tx_mmforum_posts',"deleted='0' AND hidden='0' AND poster_id='$user_id'".$this->getPidQuery(),'','crdate DESC','1');
            list($lastpost_id,$lastpost_topic_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title','tx_mmforum_topics',"uid='$lastpost_topic_id'".$this->getPidQuery());
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
                'f.hidden=0 AND f.deleted=0'.$userField_private
            );
            $parser  = t3lib_div::makeInstance('t3lib_TSparser');
            while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $cRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'field_value',
                    'tx_mmforum_userfields_contents c',
                    'c.deleted=0 AND c.field_id='.$arr['uid'].' AND c.user_id='.$user_id
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
                        $fieldContent = $row[$config['datasource']];
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
                    '###USERFIELD_UID###'       => $this->shield($arr['uid']),
                    '###USERFIELD_LABEL###'     => $this->shield($arr['label']),
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
                    $marker = $_procObj->userProfile_marker($marker, $row, $this);
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
            $this->getPidQuery('t,f').
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
            $solved         = $row['solved'] ? $this->imgtag($imgInfo) : '';
            
            $linkParams[$this->prefixId] = array(
                'action'    => 'list_post',
                'tid'       => $row['uid']
            );
            if($this->getIsRealURL()) {
                $linkParams[$this->prefixId]['fid'] = $row['forum_id'];
            }
            $content .= $this->formatDate($row['tstamp']).' - '.$this->pi_linkTP($topic_is.$row['topic_title'],$linkParams).$solved.'<br />';
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
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_posts',"poster_id='$uid' AND deleted='0' AND hidden='0'".$this->getPidQuery(),'','crdate DESC','10');
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $topic_name = $this->get_topic_name($row['topic_id']);
            $topic_name = str_replace('<','&lt;',$topic_name);
            $topic_name = str_replace('>','&gt;',$topic_name);

            $linkParams[$this->prefixId] = array(
                'action'      => 'list_post',
                'tid'         => $row['topic_id'],
                'search_pid'  => $row['uid']
            );
            $content .= $this->formatDate($row['tstamp']).' - '.$this->pi_linkTP($topic_name,$linkParams).'<br />';
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
                    $mailcode = md5(getenv("REMOTE_ADDR").time().$this->randkey(10));
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
                    $header .= "X-Mailer: PHP/" . phpversion(). "\n";
                    $header .= "X-Sender-IP: ".getenv("REMOTE_ADDR")."\n";
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
            
            $content .= $this->pi_linkTP('&laquo; '.$this->pi_getLL('').'|',$linkParams);
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
            "deleted='0' AND hidden='0' AND $column='$id'".$this->getPidQuery()
        );
        list($postcount) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        if(! ($count === FALSE)) $postcount = intval($count);

        $maxpage = ceil($postcount / $limitcount);
        if ($this->piVars['page'] == 0) $page = 1;
        else $page = $this->piVars['page'];

        $linkParams = array();
        
        IF($table == "tx_mmforum_topics") {
            if($column == 'topic_is') {
                $linkParams[$this->prefixId]['action']='list_prefix';
                if($this->piVars['list_prefix']) {
                    $settings = $this->piVars['list_prefix'];
                    $linkParams[$this->prefixId]['list_prefix']['show'] = $settings['show'];
                    $linkParams[$this->prefixId]['list_prefix']['order'] = $settings['order'];
                    $linkParams[$this->prefixId]['list_prefix']['prfx'] = $settings['prfx'];
                }
            }
            elseif($column != 'topic_replies') {
                $linkParams[$this->prefixId]['action'] = 'list_topic';
                $linkParams[$this->prefixId]['fid'] = $this->piVars['fid'];
            }
            else {
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
                if (($page - 1) > 1)            $content .= $this->pi_linkTP('&laquo; '.$this->pi_getLL('').' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page-1))).'|';
            // List pages from 2 pages before current page to 2 pages after current page
                if (($page - 2) >= 1)           $content .= '|'.$this->pi_linkTP(' '.($page-2).' ',array_merge($linkParams,array($this->prefixId.'[page]'=>$page-2))).'|';
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
             post_time >= '$lastlogin'".$this->getPidQuery('tx_mmforum_topics,tx_mmforum_posts')
        );
        $postcount = $GLOBALS['TYPO3_DB']->sql_num_rows($res);

        $maxpage = intval($postcount / $limitcount)+1;
        if ($this->piVars['page'] == 0) $page = 1;
        else $page = $this->piVars['page'];

        $linkParams[$this->prefixId] = array("action"=>"list_unread");

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
        $postdata = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_posts',"uid='$postid'".$this->getPidQuery());
        
        if ($GLOBALS['TYPO3_DB']->sql_num_rows($postdata) == 0) $content = $this->pi_getLL('no_info');
        else {
            $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($postdata);

            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($this->getUserNameField().',deleted','fe_users','uid="'.$row['poster_id'].'"');
            if($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0)
                list($username,$deleted) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

            $link = $this->get_pid_link($postid,'',$conf);
            $link = $this->shieldURL($link);
            
            $posttime = $this->cObj->stdWrap($row['post_time'],$this->conf['list_topics.']['lastPostDate_stdWrap.']);
            $posttime = '<a href="'.$link.'">'.$posttime.'</a>';

            if(!$username)
                $usrlink = $this->pi_getLL('user.deleted');
            elseif(!$deleted)
                $usrlink = $this->linkToUserProfile($row['poster_id']);
            else $usrlink = $this->shield($username);

            if($topicTitle && $this->conf['list_topics.']['lastPostTopicTitle']) {
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title','tx_mmforum_topics','uid='.$row['topic_id'].' AND deleted=0');
                if($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
                    list($topicname) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                    $topicname = $this->shield($topicname);
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
     * @param  int    $userid The UID of the user, to whose profile page is to be linked
     * @return string         The HTML-Link tag
     */
    function getauthor($userid) {
        $userdata = tx_mmforum_tools::get_userdata($userid);
        if($userdata === FALSE) $content = $this->pi_getLL('user.deleted');
        else {
	        if(!$userdata['deleted'])
	            $content = $this->linkToUserProfile($userdata);
	        else $content = $this->shield($userdata[$this->getUserNameField()]);
        }
        
        // Include hooks
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['profileLink_postContentHook'])) {
                foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['profileLink_postContentHook'] as $_classRef) {
                    $_procObj = & t3lib_div::getUserObj($_classRef);
                    $content = $_procObj->profileLink_postContentHook($content, $userdata, $this);
                }
            }
            
        return $content;
    }

    /**
     * Returns the title of a specific topic determined by UID.
     * @param  int    $tid The UID of the topic
     * @return string      The title of the topic
     */
    function get_topic_name($tid) {
        $tid = intval($tid);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title','tx_mmforum_topics',"uid='$tid'".$this->getPidQuery());
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
    function ident_user($uid,$conf,$threadauthor=FALSE) {
        $imgInfo           = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');
        $user_array        = tx_mmforum_tools::get_userdata($uid);
        
        $template = $this->cObj->fileResource($this->conf['template.']['list_post']);
        $template = $this->cObj->getSubpart($template, '###USERINFO###');
        
        if($template) {
            
            if($user_array['tx_mmforum_avatar']) {
                $avatarConf = $this->conf['list_posts.']['userinfo.']['avatar_cObj.'];
                $avatarConf['file'] = $this->conf['path_avatar'].$user_array['tx_mmforum_avatar'];
                $avatar = $this->cObj->cObjGetSingle($this->conf['list_posts.']['userinfo.']['avatar_cObj'],$avatarConf);
            } elseif($user_array['image']) {
                $avatarConf = $this->conf['list_posts.']['userinfo.']['avatar_cObj.'];
                
                if(strstr($user_array['image'],',') !== false) {
                	$avatarList = t3lib_div::trimExplode(',',$user_array['image']);
                	$user_array['image'] = $avatarList[0];
                }
                
                if(file_exists('uploads/pics/'.$user_array['image']))
                	$avatarConf['file'] = 'uploads/pics/'.$user_array['image'];
                elseif(file_exists('uploads/tx_srfeuserregister/'.$user_array['image']))
                	$avatarConf['file'] = 'uploads/tx_srfeuserregister/'.$user_array['image'];
                $avatar = $this->cObj->cObjGetSingle($this->conf['list_posts.']['userinfo.']['avatar_cObj'],$avatarConf);
            } else $avatar = '';
            
            $marker = array(
                '###LLL_DELETED###'         => $this->pi_getLL('user-deleted'),
                '###USERNAME###'            => $this->cObj->stdWrap($user_array[$this->getUserNameField()],$this->conf['list_posts.']['userinfo.']['username_stdWrap.']),
                '###USERREALNAME###'        => $this->cObj->stdWrap($user_array['name'],$this->conf['list_posts.']['userinfo.']['realname_stdWrap.']),
                '###USERRANKS###'           => $this->get_userranking($uid,$conf),
                '###TOPICCREATOR###'        => ($uid==$threadauthor)?$this->cObj->stdWrap($this->pi_getLL('topic-topicauthor'),$this->conf['list_posts.']['userinfo.']['creator_stdWrap.']):'',
                '###AVATAR###'              => $avatar,
                '###LLL_REGSINCE###'        => $this->pi_getLL('user-regSince'),
                '###LLL_POSTCOUNT###'       => $this->pi_getLL('user-posts'),
                '###REGSINCE###'            => $this->cObj->stdWrap($user_array['crdate'],$this->conf['list_posts.']['userinfo.']['crdate_stdWrap.']),
                '###POSTCOUNT###'           => intval($user_array['tx_mmforum_posts'])
            );
            if($user_array === FALSE)
                $template = $this->cObj->substituteSubpart($template, '###USERINFO_REGULAR###', '');
            else $template = $this->cObj->substituteSubpart($template, '###USERINFO_DELETED###', '');
            
            // Include hooks
                if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['userInformation_marker'])) {
                    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['userInformation_marker'] as $_classRef) {
                        $_procObj = & t3lib_div::getUserObj($_classRef);
                        $marker = $_procObj->userInformation_marker($marker, $user_array, $this);
                    }
                }
            
            $content .= $this->cObj->substituteMarkerArray($template, $marker);
        } else {
            if($user_array === FALSE)
                return '<strong>'.$this->pi_getLL('user.deleted').'</strong>';
            
            $content        = '<strong>'.$user_array[$this->getUserNameField()].'</strong><br />';
            
            if($this->conf['list_posts.']['userinfo_realName'] && $user_array['name'])
                $content .= $this->cObj->wrap($user_array['name'], $this->conf['list_posts.']['userinfo_realName_wrap']);

            $userranking = $this->get_userranking($uid,$conf).'<br />';
            if($uid == $threadauthor) $userranking .= $this->cObj->wrap($this->pi_getLL('topic.topicauthor'),$this->conf['list_posts.']['userinfo_topicauthor_wrap']);
            $content .= $userranking;
            
            if ($user_array['tx_mmforum_avatar']) {
                 $content   .=   tx_mmforum_tools::res_img($conf['path_avatar'].$user_array['tx_mmforum_avatar'],$conf['avatar_height'],$conf['avatar_width']);
            }
            
            $content .= $this->cObj->wrap($this->pi_getLL('user.regSince').': '.date("d.m.Y",$user_array['crdate']).'<br />'.$this->pi_getLL('user.posts').': '.$user_array['tx_mmforum_posts'],$this->conf['list_posts.']['userinfo_content_wrap']);
            
            /*  # Deactivated due to introduction of user ranking system
            IF($user_array['tx_mmforum_posts'] >= $conf['user_hotposts']) {
                // Special icon for users with more than a certain number posts defined in TypoScript
                $llMarker = array('###HOTPOSTS###' => $conf['user_hotposts']);
                $str = $this->cObj->substituteMarkerArray($this->pi_getLL('user.hot'),$llMarker);
                $imgInfo['src'] = $conf['path_img'].$conf['images.']['5kstar'];
                $imgInfo['alt'] = $str;
                $content       .= $this->imgtag($imgInfo);
            }
            */
        }
        
        return $content;
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
            "uid = '".$forumid."'".$this->getPidQuery()
        );
        list($catid, $forumpath_forum)    = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "forum_name",
            "tx_mmforum_forums",
            "uid = '".$catid."'".$this->getPidQuery()
        );
        list($forumpath_category)        = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "topic_title",
            "tx_mmforum_topics",
            " uid = '".$topicid."'".$this->getPidQuery()
        );
        list($forumpath_topic)            = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        if ( $forumpath_category)    $forumpath_category   = $this->conf['display.']['rootline.']['separator'].'<a href="'.$this->pi_getPageLink($GLOBALS['TSFE']->id).'#cat'.$catid.'">'.$this->shield($forumpath_category).'</a>';
        if ( $forumpath_forum)       $forumpath_forum      = $this->conf['display.']['rootline.']['separator'].$this->pi_linkTP($this->shield($forumpath_forum),array('tx_mmforum_pi1[action]'=>'list_topic','tx_mmforum_pi1[fid]'=>$forumid));
        if ( $forumpath_topic)       $forumpath_topic      = $this->conf['display.']['rootline.']['separator'].$this->shield($forumpath_topic);
        
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
    function bb2text($text,$conf) {
        $output_text    =   tx_mmforum_postparser::main($this,$conf,$text,'textparser');
        return $output_text;
    }
    
    /**
     * Displays a list of topics not yet read by the current user.
     * @param  string $content   The plugin content
     * @param  array  $conf      The configuration vars of the plugin
     * @param  int    $lastlogin A unix timestamp specifying the last login date
     *                           of the current user.
     * @return string            The list of unread topics
     */
    function getunreadposts ($content, $conf, $lastlogin)
    {
        /*
         * Retrieve read posts from database (tx_mmforum_postsread)
         */
        $uid        = $GLOBALS['TSFE']->fe_user->user['uid'];
        $resread    = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "*",
            "tx_mmforum_postsread",
            "user='$uid'".$this->getPidQuery()
        );

        $numread    = $GLOBALS['TYPO3_DB']->sql_num_rows($resread);
        $arread        = array();
        if($numread > 0) {
            $countread = 0;
            while($rowread = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resread))
            {
                $arread[$countread]['topic_id'] = $rowread['topic_id'];
                $countread += 1;
            }
        }
        else {
            $arread[0]['topic_id'] = 0;
        }

        /*
         * Determine posts since last login (tx_mmforum_posts).
         * (Posts are compared to read ones and if necessary inserted into
         * output array)
         */
        $resposts    = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "distinct topic_id",
            "tx_mmforum_posts p ",
            "deleted = 0 and p.post_time >= ".$lastlogin.$this->getPidQuery()
        );

        $numposts  = $GLOBALS['TYPO3_DB']->sql_num_rows($resposts);
        $content   = $numposts;
        $arposts   = array();
        if($numposts > 0) {
            $count = 0;
            while($row = mysql_fetch_array($resposts)) {
                if($arread[0]['topic_id'] != 0) {
                    $read = false;
                    for($i = 0; $i <= count($arread); $i++) {
                        if($arread[$i]['topic_id'] == $row['topic_id']) {
                            $read = true;
                            break;
                        }
                    }
                }
                else
                {
                    $read = false;
                }
                if(!$read) {
                    $arposts[$count]  = $row['topic_id'];
                    $count += 1;
                }
            }
        }
        return $arposts;
    }

    /**
     * Marks all unread posts as read.
     * @param  string $content The plugin content
     * @param  array  $conf    The configuration vars of the plugin
     * @return string          An error message in case the redirect attempt to
     *                         the previous page fails.
     */
    function reset_unreadpost ($content, $conf)
    {
        // Executing database operations
        $updateArray = array(
            'lastlogin'              => time(),
            'tx_mmforum_prelogin'   => time(),
            'tstamp'                => time()
        );
        $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users', "uid = ".$GLOBALS['TSFE']->fe_user->user['uid'], $updateArray);

        // Redirecting visitor back to previous page
        $ref= getenv("HTTP_REFERER");
        $content = $this->pi_getLL('board.markedAllRead').'<br />'.$this->pi_getLL('redirect.error').'<br />';
        if ($ref) header('Location: '.$this->getAbsUrl($ref)); die();
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
    function get_pid_link ($post_id,$sword,$conf) {
        $post_id = intval($post_id);
        list($topic_id,$forum_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_id,forum_id','tx_mmforum_posts',"deleted=0 AND hidden=0 AND uid='$post_id'".$this->getPidQuery()));
        $res            = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','tx_mmforum_posts',"deleted=0 AND hidden=0 AND topic_id='$topic_id'".$this->getPidQuery());
        $i              = 1;

        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $i++;
            
            IF($row['uid'] == $post_id) {
                $i--;
                $seite = ceil($i / $conf['post_limit']);
            }
        }
        $seite     = intval($seite);

        $linkparams[$this->prefixId] = array (
            'action'    => 'list_post',
            'tid'       => $topic_id,
            'page'      => $seite
        );
        if($this->getIsRealURL()) {
            $linkparams[$this->prefixId]['fid'] = $forum_id;
            $linkparams[$this->prefixId]['pid'] = $this->pi_getLL('realurl.page');
        }

        if($sword) $linkparams[$this->prefixId]['sword'] = $sword;

        $linkto     = $this->pi_getPageLink($this->getForumPID(),'',$linkparams).'#pid'.$post_id;
        return $linkto;
    }

    /**
     * Returns the prefix of a certain topic.
     * @param  int    $topic_id The UID of the topic
     * @return string           The prefix of the topic
     */
    function get_topic_is ($topic_id) {
        $topic_id = intval($topic_id);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_is','tx_mmforum_topics',"uid='$topic_id'".$this->getPidQuery());
        list($num) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        return $num;
    }


    /**
     * Returns the userranking of the user determined by the user's
     * usergroup.
     * @param  int    $user_id The user's UID
     * @param  array  $conf    The plugin's configuration vars
     * @return string          The user's ranking.
     **/
    function get_userranking($user_id,$conf)
    {
     	$userRanksObj = t3lib_div::makeInstance('tx_mmforum_ranksFE');
     	$userRanksObj->init($this);
     	$userRanksObj->setContentObject($this->cObj);
     	return $userRanksObj->displayUserRanks($user_id);
        //return tx_mmforum_ranksFE::displayUserRanks($user_id);
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
    function open_topic($content,$conf) {
        $topic_id = $this->piVars['id'];
        $topic_id = intval($topic_id);            // Parse to int for security reasons
        $linkparams[$this->prefixId] = array (
            'action'  => 'list_post',
            'tid'     => $topic_id,
            'pid'     => 'last'
        );
        $linkto = $this->pi_getPageLink($GLOBALS["TSFE"]->id,'',$linkparams);
        $linkto = $this->getAbsUrl($linkto); 
        header('Location: '.$linkto); die();
    }

    /**
     * Read all favorites of a specific user into an array.
     * @param  int   $user_id The user UID. If no user-ID is submitted, the current user's
     *                        UID is used instead.
     * @return array          The user's favorites
     */
    function get_user_fav($user_id='') {
        if(empty($user_id))    $user_id = $GLOBALS['TSFE']->fe_user->user['uid'];
        $user_fav = array();
        
        $user_id = intval($user_id);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_id','tx_mmforum_favorites',"user_id='$user_id'".$this->getPidQuery());
        while (list($fav_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
            array_push($user_fav, $fav_id);
        }
        return $user_fav;
    }

    /**
     * Determines the topic UID of a specific post.
     * @param  int $post_id The post UID
     * @return int          The topic UID
     */
    function get_topic_id($post_id) {
        $post_id = intval($post_id);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_id','tx_mmforum_posts',"uid='$post_id'".$this->getPidQuery());
        list($topic_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        return $topic_id;
    }

    /**
     * Returns the board UID of a topic
     * @param  int $topic_id The topic UID
     * @return int           The board UID
     **/
    function get_forum_id($topic_id) {
        $topic_id = intval($topic_id);
        list($forum_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('forum_id','tx_mmforum_topics',"uid='$topic_id'".$this->getPidQuery()));
        return $forum_id;
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
             $this->getPidQuery().
             $this->getCategoryLimit_query().
             $this->getMayWrite_forum_query(),
            '',
            'sorting ASC'
        );
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $content .= '<optgroup label="'.$this->shield($row['forum_name']).'">';

            // Load boards
            $res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'tx_mmforum_forums',
                'deleted="0" AND
                 hidden="0" AND
                 parentID="'.$row['uid'].'" '.
                 $this->getPidQuery().
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

                $content.= '<option value="'.$this->shield($row2['uid']).'" '.$select.'>'.$this->shield($row2['forum_name']).'</option>';
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
    function get_last_post($topic_id) {
        $topic_id = intval($topic_id);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "*",
            "tx_mmforum_posts",
            "topic_id = '$topic_id' AND deleted = 0 AND hidden = 0".$this->getPidQuery(),
            "",
            "crdate DESC",
            "1"
        );
        
        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        return $row['uid'];
    }

    /**
     * Shortens a text to a specified length.
     * @param  string $text     The text to be cut
     * @param  int    $cut      The length, the text is to be cut to
     * @param  bool   $word_cut Determines, if words are to be cut by this function or
     *                          to be preserved.
     * @return string           The cut text.
     */
    function text_cut($text,$cut,$word_cut = 0) {
        IF (strlen($text) > $cut) {
            $firsttext  = substr($text,0,$cut);
            $lasttext   = substr($text,$cut);
            if($word_cut==0) {
                $find   = strpos($lasttext," ");
                if ($find > 0) {
                    $text = $firsttext.substr($lasttext,0,$find).' ...';
                } else {
                    $text = $firsttext.$lasttext;
                }
            }
            else $text = $firsttext;
        }
        return $text;
    }

    /**
     * Returns the user UID of a specific username.
     * @param  string $username The username, whose user UID is to be determined.
     * @return int              The user UID of $username.
     */
    function get_userid($username) {
        $username = $GLOBALS['TYPO3_DB']->fullQuoteStr($username, 'fe_users');
        list($user_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','fe_users',"username=$username"));
        return $user_id;
    }

    /**
     * Generates an error message.
     * @author Martin Helmich <m.helmich@mittwald.de>
     * @param  array  $conf The plugin's configuration vars
     * @param  string $msg  The error message
     * @return string       The HTML error message
     */
    function errorMessage($conf, $msg) {
        $template = $this->cObj->fileResource($conf['template.']['login_error']);
        $template = $this->cObj->getSubpart($template, "###LOGINERROR###");
        $marker = array();
        $marker['###LOGINERROR_MESSAGE###'] = $msg;
        return $this->cObj->substituteMarkerArrayCached($template, $marker);
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
        $template = $this->cObj->getSubpart($template, "###SUCCESSNOTICE###");
        $marker = array();
        $marker['###LOGINERROR_MESSAGE###'] = $msg;
        return $this->cObj->substituteMarkerArrayCached($template, $marker);
    }
    
    /**
     * Returns a complete Image HTML String
     * Generates a complete XHTML img-tag from parameters submitted in an
     * associative array.
     * 
     * @param array  $imgInfo The parameters for image creation as associative array.
     *                        The array is to be generated according to the following pattern:
     *                             'src'           => Image source file
     *                          'width'         => Image width in pixels
     *                          'height'        => Image height in pixels
     *                          'border'        => Image border width in pixels
     *                          'alt'           => Substitute text in case image is not found
     *                          'title'         => Description of the image at MouseOver
     * @return string         The XHTML img-tag
     */
    function imgtag($imgInfo,$debug=TRUE){
        $imgTag = '<img src="'.$imgInfo['src'].'" ';
        if($imgInfo['width'])  $imgTag .= 'width="'.$imgInfo['width'].'" ';
        if($imgInfo['height']) $imgTag .= 'height="'.$imgInfo['height'].'" ';
        if(strlen($imgInfo['border'])>0) $imgTag .= 'style="border: '.$imgInfo['border'].'px;" '; else $imgTag .= 'style="border: 0px;" ';
        $imgTag .= 'alt="'.$imgInfo['alt'].'" ';
        if($imgInfo['title'])  $imgTag .= 'title="'.$imgInfo['title'].'" ';
        $imgTag .= '/>';
        
        return $imgTag;
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
        if(!$GLOBALS['TSFE']->config['config']['language'])
            return 'default/';
        if($GLOBALS['TSFE']->config['config']['language'] == 'en'){
            return 'default/';
        }
        else{
            return $GLOBALS['TSFE']->config['config']['language'].'/';
        }
    }
    
    /**
     * Loads a topic record from database.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 11. 01. 2007
     * @param   int   $tid The topic UID
     * @return  array      The topic record as associative array.
     */
    function getTopicData($tid) {
        $tid = intval($tid);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_topics',
            'uid="'.$tid.'" AND deleted="0" AND hidden="0"'.$this->getPidQuery()
        );
        return ($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0)?FALSE:$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
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
    function getTopicIcon($topic) {
        if(!is_array($topic)) $topic = $this->getTopicData(intval($topic));
        
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
        
        $isNew      = in_array($topic['uid'],$readarray);
        $isHot      = ($topic['topic_replies'] >= $this->conf['hotposts']);
        $isClosed   = ($topic['closed_flag'] == '1');
        $isUnanw    = ($topic['topic_replies'] == 0);
        $isPinned   = ($topic['at_top_flag'] == '1');
        $isSolved   = ($topic['solved'] == '1');
        
        $topicIconMode = $this->getTopicIconMode();
        
        if($topicIconMode == 'modern') {
            $dataArray = array(
                'unread'        => $isNew,
                'hot'           => $isHot,
                'closed'        => $isClosed,
                'unanswered'    => $isUnanw,
                'solved'        => $isSolved
            );
            $oldData = $this->cObj->data;
            $this->cObj->data = $dataArray;
            $image = $this->cObj->cObjGetSingle($this->conf['topicIcon'],$this->conf['topicIcon.']);
            $this->cObj->data = $oldData;
            
            return $image;
        } elseif($topicIconMode == 'classic') {
            $imgname    = 'topicicon';
            if($isPinned)       $imgname .= '_pinned';
            if($isClosed)       $imgname .= '_closed';
            elseif($isHot)      $imgname .= '_hot';
            elseif($isUnanw)    $imgname .= '_unanswered';
            if($isNew)          $imgname .= '_new';
            
            if($topic['shadow_tid']>0) $imgname = 'topicicon_shadow';
            
            $imgInfo = array(
                'src'        => $this->conf['path_img'].$this->conf['images.'][$imgname],
                'alt'        => $this->pi_getLL('topic.'.$imgname),
                'title'      => $this->pi_getLL('topic.'.$imgname)
            );
            
            return $this->imgtag($imgInfo);
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
	        
	        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_topics','forum_id="'.$forum['uid'].'"'.$this->getPidQuery());
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
            $image = $this->cObj->cObjGetSingle($this->conf['topicIcon'],$this->conf['topicIcon.']);
            $this->cObj->data = $oldData;
            
            return $image;
        } elseif($topicIconMode = 'classic') {
            $filename = ($isClosed?'closed_':'').'forum'.($isNew?'_new':'');
            
            $imgInfo = array(
                'src'       => $this->conf['path_img'].$this->conf['images.'][$filename],
                'alt'       => $this->pi_getLL('board.'.$filename)
            );
            return $this->imgtag($imgInfo);
        }
    }
    
    /**
     * Generates a link to mark all topics in a board as read.
     * Is only displayed when a fe_user is logged in.
     * 
     * @return string The link to mark all topics as read
     */
    function getMarkAllRead_link() {
        if($GLOBALS['TSFE']->fe_user->user) {
            $linkparams[$this->prefixId] = array (
                'action' => 'reset_read'
            );
            return $this->pi_linkToPage($this->pi_getLL('board.markAllRead'),$GLOBALS['TSFE']->id,'',$linkparams).'<br />';
        }
        else return '';
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
        if($this->getIsRealURL()) {
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
        
        if(@file_exists($a['file_path']) && $this->getMayRead_post($a['post_id'])) {
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
	 * Determines if the user that is currently logged in is an administrator.
	 * @return boolean  TRUE, if the user that is currently logged in is an administrator.
	 */
	function getIsAdmin() {
		if($GLOBALS['TSFE']->fe_user->user['username']=="") return false;
		
		//$grouprights = explode(",",$GLOBALS['TSFE']->fe_user->user['usergroup']);
        return in_array($this->conf['grp_admin'],$GLOBALS['TSFE']->fe_user->groupData['uid']);
	}
	
	/**
	 * Determines if the user that is currently logged in is an moderator.
	 * @return boolean  TRUE, if the user that is currently logged in is an moderator.
	 */
	function getIsMod($forum=0) {
		if($GLOBALS['TSFE']->fe_user->user['username']=="") return false;
		
        if($GLOBALS['tx_mmforum_pi1']['userIsMod'][$forum] !== null)
			return $GLOBALS['tx_mmforum_pi1']['userIsMod'][$forum];
            
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'c.grouprights_mod as category_auth, f.grouprights_mod as forum_auth',
            'tx_mmforum_forums f LEFT JOIN tx_mmforum_forums c ON f.parentID=c.uid',
            'f.uid='.intval($forum).' AND f.deleted=0'
        );
		if(!$res || $GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) return false;
        
        list($category_auth, $forum_auth) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        
        $category_auth      = t3lib_div::intExplode(',', $category_auth);
        $forum_auth         = t3lib_div::intExplode(',', $forum_auth);
        
        $auth               = array_merge($category_auth, $forum_auth);
        $auth               = array_unique($auth);
		
        $intersect = array_intersect($GLOBALS['TSFE']->fe_user->groupData['uid'], $auth);
		
        $isMod = count($intersect) > 0;
        $GLOBALS['tx_mmforum_pi1']['userIsMod'][$forum] = $isMod;
		
        return $isMod;
	}
	
	/**
	 * Determines if the user that is currently logged in is an administrator or a moderator.
	 * @return boolean  TRUE, if the user that is currently logged in is an
	 *                  administrator or a moderator.
	 */
	function getIsModOrAdmin($forum=0) {
		return ($this->getIsMod($forum) || $this->getIsAdmin());
	}
     
    /**
     * Generates a MySQL-query to determine in which boards the current user may read.
     * @return string  A MySQL-WHERE-query, beginning with "AND", checking which boards the
     *                 user that is currently logged in may read in.
     * @author Martin Helmich <m.helmich@mittwald.de>
     */
    function getMayRead_forum_query($prefix="") {
        if($GLOBALS['tx_mmforum_pi1']['getMayRead_forum_query'][$prefix])
            return $GLOBALS['tx_mmforum_pi1']['getMayRead_forum_query'][$prefix];
        else {
            if($this->getIsAdmin()) return ' AND 1 ';
			
			if(strlen($prefix)>0) $prefix = "$prefix.";
            if(!$GLOBALS['TSFE']->fe_user->user) return " AND (".$prefix."grouprights_read='')";
            
            $groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
            $groups = tx_mmforum_tools::processArray_numeric($groups);
            if(!is_array($groups) || count($groups)==0)
				$queryParts = '1';
			else {
				foreach($groups as $group) {
	                $queryParts[] = "FIND_IN_SET($group,".$prefix."grouprights_read)";
	            }
			}
            $query = is_array($queryParts)?implode(' OR ',$queryParts):$queryParts;
            $query = " AND (($query) OR ".$prefix."grouprights_read='') ";
            
            $GLOBALS['tx_mmforum_pi1']['getMayRead_forum_query'][$prefix] = $query;
            
            return $query;
        }
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
        if(!is_array($forum)) {
            $forum = intval($forum);
            if($GLOBALS['tx_mmforum_pi1']['getMayRead_forum'][$forum])
                return $GLOBALS['tx_mmforum_pi1']['getMayRead_forum'][$forum];
            else
                $forum = $this->getBoardData($forum);
        }
        
        if($GLOBALS['tx_mmforum_pi1']['getMayRead_forum'][$forum['uid']])
            return $GLOBALS['tx_mmforum_pi1']['getMayRead_forum'][$forum['uid']];
        else {
            if($forum['parentID'])
                if(!$this->getMayRead_forum($forum['parentID'])) return false; 
            
            $authRead = tx_mmforum_tools::getParentUserGroups($forum['grouprights_read']);
            if(strlen($authRead)==0) return true;
            $authRead = t3lib_div::trimExplode(',',$authRead);
            
            $groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
            $groups = tx_mmforum_tools::processArray_numeric($groups);
            
            $intersect    = array_intersect($authRead,$groups);
            $result        = count($intersect)>0;
            
            $GLOBALS['tx_mmforum_pi1']['getMayRead_forum'][$forum['uid']] = $result;
            
            return $result;
        }
    }
    
    /**
     * Generates a MySQL-query to determine in which boards the current user may write.
     * @return string  A MySQL-WHERE-query, beginning with "AND", checking in which boards the
     *                 user that is currently logged in may write.
     * @author Martin Helmich <m.helmich@mittwald.de>
     */
    function getMayWrite_forum_query() {
        if($GLOBALS['tx_mmforum_pi1']['getMayWrite_forum_query'])
            return $GLOBALS['tx_mmforum_pi1']['getMayWrite_forum_query'];
        else {
            $groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
            $groups = tx_mmforum_tools::processArray_numeric($groups);
            foreach($groups as $group) {
                $queryParts[] = "FIND_IN_SET($group,grouprights_write)";
            }
            $query = implode(' OR ',$queryParts);
            $query = " AND (($query) OR grouprights_write='') ";
            
            $GLOBALS['tx_mmforum_pi1']['getMayWrite_forum_query'] = $query;
            
            return $query;
        }
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
        if(!$GLOBALS['TSFE']->fe_user->user['uid']) return false;
        
        if(!is_array($forum)) { 
            $forum = intval($forum);
            if($GLOBALS['tx_mmforum_pi1']['getMayWrite_forum'][$forum])
                return $GLOBALS['tx_mmforum_pi1']['getMayWrite_forum'][$forum];
            else {
                $forum = $this->getBoardData($forum);
            }
        }
        
        if($GLOBALS['tx_mmforum_pi1']['getMayWrite_forum'][$forum['uid']])
            return $GLOBALS['tx_mmforum_pi1']['getMayWrite_forum'][$forum['uid']];
        else {
            if($this->getIsModOrAdmin($forum['uid'])) return true;
            
            if($forum['parentID']) {
                if(!$this->getMayWrite_forum($forum['parentID'])) return false;
            }
            
            $authWrite = tx_mmforum_tools::getParentUserGroups($forum['grouprights_write']);
            if(strlen($authWrite)==0) return true;
            $authWrite = t3lib_div::intExplode(',',$authWrite);
            
            $groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
            $groups = tx_mmforum_tools::processArray_numeric($groups);
            
            if(count($authWrite)==0) return true;
            
            $intersect    = array_intersect($authWrite,$groups);
            $result     = count($intersect)>0;
            
            $GLOBALS['tx_mmforum_pi1']['getMayWrite_forum'][$forum['uid']] = $result;
            
            return $result;
        }
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
        if(!is_array($topic)) {
            $topic = intval($topic);
            if($GLOBALS['tx_mmforum_pi1']['getMayWrite_topic'][$topic])
                return $GLOBALS['tx_mmforum_pi1']['getMayWrite_topic'][$topic];
            else {
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'f.*',
                    'tx_mmforum_forums f, tx_mmforum_topics t',
                    't.uid="'.$topic.'" AND f.uid = t.forum_id'
                );
                $arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                $result = $this->getMayWrite_forum($arr);
                $GLOBALS['tx_mmforum_pi1']['getMayWrite_topic'][$topic] = $result;
                return $result;
            }
        }
        else {
            if($GLOBALS['tx_mmforum_pi1']['getMayWrite_topic'][$topic['uid']])
                return $GLOBALS['tx_mmforum_pi1']['getMayWrite_topic'][$topic['uid']];
            else {
                $result = $this->getMayWrite_forum($topic['forum_id']);
                $GLOBALS['tx_mmforum_pi1']['getMayWrite_topic'][$topic['uid']] = $result;
                return $result;
            }
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
        if(!is_array($topic)) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'f.*',
                'tx_mmforum_forums f, tx_mmforum_topics t',
                't.uid="'.intval($topic).'" AND f.uid = t.forum_id'
            );
            $arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
            return $this->getMayRead_forum($arr);
        }
        else {
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
        if($post == 0) return false;
        if(!is_array($post)) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'f.*',
                'tx_mmforum_forums f, tx_mmforum_posts p',
                'p.uid="'.intval($post).'" AND f.uid = p.forum_id'
            );
            $arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
            return $this->getMayRead_forum($arr);
        }
        else {
            return $this->getMayRead_forum($post['forum_id']);
        }
    }
    
    /**
     * Various helper functions
     */

    /**
     * Creates a random alphanumeric key of variable length.
     * @param  int    $length The length of the key
     * @return string         The key
     */
    function randkey($length) {
        // RANDOM KEY PARAMETERS
        $keychars = "abcdefghijklmnopqrstuvwxyz0123456789";
        // RANDOM KEY GENERATOR
        $randkey = "";
        for ($i=0;$i<$length;$i++)
            $randkey .= substr($keychars, rand(1, strlen($keychars) ), 1);
        return $randkey;
    }

    /**
     * Converts a hexadecimal string into an IP Address
     * @param  string $hex The hexadecimal string
     * @return string      The IP Address
     */
    function hex2ip($hex) {
        for($i=0;$i<4;$i++) {
            $str.=hexdec(substr($hex,0,2)).'.';
            $hex=substr($hex,2);
        }
        $str = substr($str,0,-1); // Remove last dot
        return $str;
    }

    /**
     * Converts an IP Address into a hexadecimal string.
     * @param  string $val The IP Address
     * @return string      The hexadecimal string
     */
    function ip2hex($val) {
        $tetr=explode(".",$val);
        $hexstr = '';
        for($i=0;$i<4;$i++) {
            $hexstr.=dechex($tetr[$i]);
        }
        return $hexstr;
    }
    
    /**
     * Generates an absolute link.
     * This function generates an absolute link from a relative link
     * that is submitted as parameter.
     * For this, the config.baseURL property is used. If this property
     * is not set, the absolute URL will be determined using the
     * $_ENV[HTTP_HOST] variable.
     * This function was introduced due to problems with some realUrl
     * configuration.
     * 
     * @param  string $link A relative link
     * @return string       The submitted string converted into an absolute link
     * @author Martin Helmich <m.helmich@mittwald.de>
     */
    function getAbsUrl($link) {
    	if(substr($link,0,7)=='http://') return $link;
    	if(substr($link,0,8)=='https://') return $link;
    	
    	$ssl = (t3lib_div::getIndpEnv('SERVER_PORT') == 443);
    	
    	if($GLOBALS['TSFE']->config['config']['baseURL']) {
			$baseUrl = $GLOBALS['TSFE']->config['config']['baseURL'];
			if(substr($baseUrl,-1,1)!='/') $baseUrl = $baseUrl.'/';
			if(substr($link,0,1)=='/') $link = substr($link,1);
			$result = $baseUrl.$link;
    	}
		else {
	        $dirname = dirname(t3lib_div::getIndpEnv('SCRIPT_NAME'));
            $dirname = tx_mmforum_pi1::appendTrailingSlash($dirname);
            $dirname = tx_mmforum_pi1::removeLeadingSlash($dirname);
            if($dirname == '/') $dirname = '';
            $host    = t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST');
            $host    = tx_mmforum_pi1::appendTrailingSlash($host);
            
            if((substr($host,0,8)!='https://') && (substr($host,0,7)!='http://'))
            	$host = ($ssl)?'https://'.$host:'http://'.$host;
            
            $link = tx_mmforum_pi1::removeLeadingSlash($link);
            $result = $host.$dirname.$link;
        }
        return $result;
    }
    
    /**
     * Appends a trailing slash (/) to a string, but only if the last character is not already a slash.
     * @param  string $str The string to which a / is to be appended
     * @return string      The string with a / appended
     */
    function appendTrailingSlash($str) {
        if(substr($str,-1,1)!='/') return $str.'/'; else return $str;
    }
    
    /**
     * Removes a leading slash from a string.
     * @param  string $str A string with a leading slash
     * @return string      The string without the leading slash
     */
    function removeLeadingSlash($str) {
        if(substr($str,0,1)=='/') return substr($str,1); else return $str;
    }
    
    /**
     * Determines if the RealURL extension is enabled.
     * If the RealURL extension (and the plugin.tx_mmforum.realUrl_specialLinks constant),
     * the mm_forum extension will create links that will allow readUrl to create
     * nicer URLs (like for example "mm_forum/board_a/my_topic/reply" instead of
     * "mm_forum//my_topic/reply").
     * 
     * @return boolean TRUE, if RealURL and realUrl_specialLinks is enabled, otherwise
     *                 FALSE.
     */
    function getIsRealURL() {
        if($this->conf['realUrl_specialLinks']=='1')
            return ($GLOBALS['TSFE']->config['config']['tx_realurl_enable']=='1');
        else return false;
    }
    
    /**
     * Delivers a MySQL-WHERE query checking the records' PID.
     * This allows it to exclusively select records from a very specific list
     * of pages.
     * 
     * NOTE: This function is currently partially disabled.
     *       Instead of defining the PIDs to be checked via the plugin's Starting
     *       Point, the PID is in this version defined in the TS constant
     *       plugin.tx_mmforum.storagePID
     * 
     * @param   string $tables The list of tables that are queried
     * @return  string         The query, following the pattern " AND pid IN (...)"
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-04-03
     */
    function getPidQuery($tables="") {
        if($this->conf['storagePID']==-1) return "";
        if($this->conf['storagePID']=="") return "";
        else {
            if($tables == "")
                return " AND pid='".$this->conf['storagePID']."'";
            
            $tables = t3lib_div::trimExplode(',',$tables);
            $query = "";
            
            foreach($tables as $table) {
                $query .= " AND $table.pid='".$this->conf['storagePID']."'";
            }
            return $query;
        }
        
        /*
        if(strlen(trim($this->conf['pidList']))==0) return "";
        if($tables == "") return " AND pid IN (".$this->conf['pidList'].")";
        
        $tables = t3lib_div::trimExplode(',',$tables);
        $query = "";
        
        foreach($tables as $table) {
            $query .= " AND $table.pid IN (".$this->conf['pidList'].")";
        }
        return $query;
        */
    }
    
    function getUserPidQuery($table="fe_users") {
        return " AND $table.pid = ".$this->conf['userPID']." ";
    }
    
    /**
     * Delivers the PID of newly created records.
     * @return  int The PID of a record that is to be created.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-04-02
     */
    function getFirstPid() {
        if($this->conf['storagePID'] == -1) return 0;
        if(!$this->conf['storagePID']) return 0;
        return intval($this->conf['storagePID']);
        
        /*
        if(strlen(trim($this->conf['pidList']))==0) return 0;
        
        $pids = t3lib_div::trimExplode(',',$this->conf['pidList']);
        return $pids[0];
        */
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
    function createRootline($content,$conf) {
        $result = array();
        $this->pi_loadLL();
        
        // List post view, new post form, post alert form
        // Displays a rootline like "mm_forum page -> Category -> Board -> Topic (-> New post/Report post)"
        if($this->piVars['action'] == 'list_post' ||
           $this->piVars['action'] == 'new_post' ||
           $this->piVars['action'] == 'post_alert') {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                't.uid,t.forum_id,c.uid,topic_title,f.forum_name,c.forum_name',
                'tx_mmforum_topics t, tx_mmforum_forums f, tx_mmforum_forums c',
                't.uid="'.intval($this->piVars['tid']).'" AND f.uid=t.forum_id AND c.uid=f.parentID'
            );
            list($topic_id,$forum_id,$cat_id,$topic_title,$forum_title,$cat_title) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            
            if($this->piVars['action'] == 'new_post') {
                $linkParams[$this->prefixId] = array(
                    'action'         => 'new_post',
                    'tid'            => $topic_id,
                    'fid'            => $forum_id
                );
                $result[] = array(
                    'title'             => $this->pi_getLL('rootline.reply'),
                    '_OVERRIDE_HREF'    => $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams)
                );
            }
            elseif($this->piVars['action'] == 'post_alert') {
                $linkParams[$this->prefixId] = array(
                    'action'         => 'post_alert',
                    'tid'            => $topic_id,
                    'fid'            => $forum_id
                );
                $result[] = array(
                    'title'             => $this->pi_getLL('rootline.post_alert'),
                    '_OVERRIDE_HREF'    => $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams)
                );
            }
        }
        // New topic form, topic listing view
        // Displays a rootline like "mm_forum page -> Category -> Board (-> New topic)"
        elseif($this->piVars['action'] == 'new_topic' ||
               $this->piVars['action'] == 'list_topic') {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'f.uid,f.forum_name,c.uid,c.forum_name',
                'tx_mmforum_forums f, tx_mmforum_forums c',
                'f.uid="'.intval($this->piVars['fid']).'" AND c.uid=f.parentID'
            );
            list($forum_id,$forum_title,$cat_id,$cat_title) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            
            if($this->piVars['action'] == 'new_topic') {
                $linkParams[$this->prefixId] = array(
                    'action'            => 'new_topic',
                    'fid'               => $forum_id
                );
                $result[] = array(
                    'title'             => $this->pi_getLL('rootline.new_topic'),
                    '_OVERRIDE_HREF'    => $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams)
                );
            }
        }
        // Post editing form
        // Displays a rootline like "mm_forum page -> Category -> Board -> Topic -> Edit post"
        elseif($this->piVars['action'] == 'post_edit') {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                't.uid,t.forum_id,c.uid,topic_title,f.forum_name,c.forum_name',
                'tx_mmforum_posts p, tx_mmforum_topics t, tx_mmforum_forums f, tx_mmforum_forums c',
                'p.uid="'.intval($this->piVars['pid']).'" AND t.uid=p.topic_id AND f.uid=p.forum_id AND c.uid=f.parentID'
            );
            list($topic_id,$forum_id,$cat_id,$topic_title,$forum_title,$cat_title) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            
            $linkParams[$this->prefixId] = array(
                'action'             => 'post_edit',
                'fid'                => $forum_id,
                'tid'                => $topic_id,
                'pid'                => $this->piVars['pid']
            );
            $result[] = array(
                'title'              => $this->pi_getLL('rootline.edit_post'),
                '_OVERRIDE_HREF'     => $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams)
            );
        }
        // User profile
        // Displays a rootline like "mm_forum page -> User profile: Username"
        elseif($this->piVars['action'] == 'forum_view_profil') {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'username',
                'fe_users',
                'uid="'.intval($this->piVars['user_id']).'"'
            );
            list($username) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            $linkParams[$this->prefixId] = array(
                'action'             => 'forum_view_profil',
                'user_id'            => $this->piVars['user_id']
            );
            $result[] = array(
                'title'              => sprintf($this->pi_getLL('rootline.userprofile'),$username),
                '_OVERRIDE_HREF'     => $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams)
            );
        }
        // List unread or unanswered topics
        // Displays a rootline like "mm_forum page -> List unread/unanswered topics"
        elseif($this->piVars['action'] == 'list_unread' ||
               $this->piVars['action'] == 'list_unans') {
            $linkParams[$this->prefixId] = array(
                'action'            => $this->piVars['action']
            );
            $result[] = array(
                'title'             => $this->pi_getLL('rootline.'.$this->piVars['action']),
                '_OVERRIDE_HREF'    => $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams)
            );
        }
        
        
        if($topic_id) {
            $topicParams[$this->prefixId] = array(
                'action'            => 'list_post',
                'tid'               => $topic_id,
                'fid'               => $forum_id
            );
            $result[] = array(
                'title'             => $topic_title,
                '_OVERRIDE_HREF'    => $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$topicParams)
            );
        }
        
        if($forum_id) {    
            $boardParams[$this->prefixId] = array(
                'action'            => 'list_topic',
                'fid'                => $forum_id
            );
            $result[] = array(
                'title'                => $forum_title,
                '_OVERRIDE_HREF'    => $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$boardParams)
            );
        }
        
        if($cat_id) {    
            $catParams[$this->prefixId] = array(
                'action'            => 'list_forum',
            );
            $result[] = array(
                'title'                => $cat_title,
                '_OVERRIDE_HREF'    => $this->pi_getPageLink($GLOBALS['TSFE']->id.'#cat'.$cat_id)
            );
        }
        $result = array_reverse($result);
        
        if($conf['entryLevel'])
            $pageRootline = array_slice($GLOBALS['TSFE']->config['rootLine'],$conf['entryLevel']);
        else $pageRootline = $GLOBALS['TSFE']->config['rootLine'];
        
        $result = array_merge($pageRootline, $result);
        
			// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['rootlineArray'])) {
		    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['rootlineArray'] as $_classRef) {
		        $_procObj = & t3lib_div::getUserObj($_classRef);
		        $result = $_procObj->processRootlineArray($result,&$this);
		    }
		}
		
        return $result;
    }
    
    /**
     * Wrapper function for retrieval of language dependent strings.
     * This function overrides the parent pi_getLL function. This was introduced
     * in order to allow language variables using TypoScript (which was until now
     * not possible due to the dots used in the language indices) by accessing
     * the same language label with dashes indead of dots. This function allows this
     * without changing all pi_getLL calls in this class.
     * 
     * Furthermore, as of version 0.1.4, the function controls the use of
     * formal or informal language (which is mainly characterized by the use of the
     * german "Sie" or "Du").
     * 
     * @param   string $key The language key
     * @return  string      The language dependent label
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-10-05
     */
    function pi_getLL($key) {
        $key1   = $key;
        $key2   = str_replace('.','-',$key);
        
        if(parent::pi_getLL($key2)) {
            if($this->conf['informal']) return parent::pi_getLL($key2.'-inf')?parent::pi_getLL($key2.'-inf'):parent::pi_getLL($key2);
            return parent::pi_getLL($key2);    
        }
        else {
            if($this->conf['informal']) return parent::pi_getLL($key1.'-inf')?parent::pi_getLL($key1.'-inf'):parent::pi_getLL($key1);
            return parent::pi_getLL($key1);
        }
    }
    
    /**
     * Determines if this instance of the mm_forum is a moderated forum.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-07-19
     * @return  boolean TRUE, if the forum is moderated, otherwise FALSE.
     */
    function getIsModeratedBoard() {
        return $this->conf['moderated']?true:false;
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
    function createButton($label,$params,$id=0,$small=false,$href='',$nolink=false,$atagparams='') {
        if($id==0) $id = $GLOBALS['TSFE']->id;
    	
    	$prefixId = $this->prefixId_pi1?$this->prefixId_pi1:$this->prefixId;
    	
    	$buttonObj  = $this->conf['buttons.'][$small?'small':'normal'];
    	$buttonConf = $this->conf['buttons.'][$small?'small.':'normal.'];
    	
    	if(!is_array($params)) {
	    	if(preg_match('/^profileView:([0-9]+?)$/',$params,$matches)) {
	    		$href = tx_mmforum_pi1::getUserProfileLink($matches[1]);
	    	}
    	}
    	
    	$data		= array(
    		'button_label'		 => $this->pi_getLL('button.'.$label),
            'button_link'        => $nolink?'':($href?$href:$this->pi_getPageLink($id,'',$params)),
            'button_iconname'    => file_exists($this->conf['path_img'].'buttons/icons/'.$label.'.png')?$label.'.png':'',
            'button_atagparams'  => $atagparams
        );
        $oldData    = $this->cObj->data;
        $this->cObj->data = $data;
        
        $button     = $this->cObj->cObjGetSingle($buttonObj,$buttonConf);
        $this->cObj->data = $oldData;
        
        return $button;
    }  
    
    /**
     * Returns the page UID of the main plugin.
     * This function returns the page UID where the main mm_forum plugin
     * is placed on. Typically, this variable should be stored in the
     * TypoScript configuration. If this should not be the case, this function
     * will try to determine the page UID by searching the tt_content table
     * for a regarding content element.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-02-11
     * @return  int The page UID where the mm_forum plugin is placed on
     */
    function getForumPID() {
        if($this->conf['pid_forum']) return $this->conf['pid_forum'];
        
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'pid',
            'tt_content',
            'list_type="mm_forum_pi1" AND CType="list" AND deleted="0" AND hidden="0" AND pi_flexform LIKE "%<value index=\"vDEF\">BOARD</value>%"'
        );
        if($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
            list($pid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res); return $pid;
        } else return 0;
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
        $topic_id    = intval($this->piVars[$status?'solve':'unsolve']);
        $res        = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*','tx_mmforum_topics','uid='.$topic_id
        );
        $topic_data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        
        if($topic_data['topic_poster'] == $GLOBALS['TSFE']->fe_user->user['uid'] || $this->getIsModOrAdmin($topic_data['forum_id'])) {
            $updateArray = array(
                'solved'        => $status,
                'tstamp'        => time()
            );
            $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics','uid='.$topic_id,$updateArray);
            
            $linkParams[$this->prefixId] = array(
                'action'        => 'list_post',
                'tid'            => $topic_id
            );
            if($this->getIsRealURL()) {
                $linkParams[$this->prefixId]['fid'] = $topic_data['forum_id'];
            }
            
            $link = $this->getAbsUrl($this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams));
            header('Location: '.$link);
        } else {
            return $this->errorMessage($this->conf,$this->pi_getLL('topic-noSolveRights'));
        }
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
     * @param   mixed  $user Information on the user that is to be linked. This may
     *                       either be the user record as associative array or the user UID.
     * @return  string       The user link
     * 
     */
    function getUserProfileLink($user) {
    	if(!is_array($user)) $user = tx_mmforum_tools::get_userdata($user);
    	$prefixId = $this->prefixId_pi1?$this->prefixId_pi1:$this->prefixId;
    	$linkParams[$prefixId] = array(
    		'action'		=> 'forum_view_profil',
    		'user_id'		=> $user['uid']
    	);
    	if(tx_mmforum_pi1::getIsRealURL()) {
            unset($linkParams[$prefixId]['user_id']);
            $linkParams[$prefixId]['fid'] = $user['username'];
        }
        
        $link = $this->pi_getPageLink(tx_mmforum_pi1::getForumPID(), '', $linkParams);
        
        // Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['profileLink_postLinkGen'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['profileLink_postLinkGen'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$link = $_procObj->userProfileLink($user, $link, $this);
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
     * @param   mixed  $user Information on the user that is to be linked. This may
     *                       either be the user record as associative array or the user UID.
     * @return  string       The user link
     */
    function linkToUserProfile($user, $text='') {
    	if(!is_array($user)) $user = tx_mmforum_tools::get_userdata($user);
    	if($text === '') $text = $user[tx_mmforum_pi1::getUserNameField()];
        
        return '<a href="'.$this->shieldURL(tx_mmforum_pi1::getUserProfileLink($user)).'">'.$text.'</a>';
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
    	return $this->conf['userNameField']?$this->conf['userNameField']:'username';
    }
    
    function getTopicIconMode() {
        return $this->conf['topicIconMode']?$this->conf['topicIconMode']:'modern';
    }
    
    function formatDate($tstamp) {
    	$df = $this->conf['dateFormat'];
    	
    	if(strpos($df,'%')===false)
    		return date($df,$tstamp);
    	else return strftime($df,$tstamp);
    }
    
    function shield($text) {
    	return $this->validatorObj->specialChars($text);
    }
    
    function shieldURL($url) {
    	return $this->validatorObj->specialChars_URL($url);
    }
}


if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_pi1.php"])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_pi1.php"]);
}
?>