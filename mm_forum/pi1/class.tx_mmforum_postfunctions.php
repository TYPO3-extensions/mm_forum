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

require_once(PATH_tslib."class.tslib_pibase.php");

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
class tx_mmforum_postfunctions extends tslib_pibase {
    
    
    /**
     * Lists all posts in a certain topic.
     * @param  string $content The plugin content
     * @param  array  $conf    The plugin's configuration vars
     * @return string          The content
     */
    function list_post($content, $conf, $order) {
        $language = $this->getLanguageFolder();
        $imgInfo = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');  

        
        
        // Check authorization  START     Martin Helmich, 18. 4. 06
        
        if(!$this->getMayRead_topic($this->piVars['tid'])) {						// Added 2007-04-24
            return $this->errorMessage($conf, $this->pi_getLL('topic.noAccess'));
        }
        
        $this->piVars['tid'] = intval($this->piVars['tid']);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_topics',
            'uid='.intval($this->piVars['tid'])
        );
        $topicData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        
        tx_mmforum_rss::setHTMLHeadData('forum', $topicData['forum_id']);
        tx_mmforum_rss::setHTMLHeadData('topic', $this->piVars['tid']);
        
        // Check authorization  END
        
        
        $this->local_cObj->data = $topicData;
        
        
        // Save admin panel changes  START
        if ($this->piVars['saveAdmin']==1 && ($this->getIsAdmin() || $this->getIsMod($topicData['forum_id'])))
        {
        	
            if ($this->piVars['change_forum_id'] <> 0 AND !empty($this->piVars['change_forum_id'])) {
                // Get old board UID
                $res_old_forum_id = $GLOBALS["TYPO3_DB"]->exec_SELECTquery(
                    "forum_id",            
                    "tx_mmforum_topics",     
                    "uid = ".intval($this->piVars['tid']).$this->getPidQuery() 
                );
                list($old_forum_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res_old_forum_id);

                // Generate shadow record
                if($this->conf['enableShadows'] || true) {
                    $shadow_insertArray = array(
                        'pid'                   => $topicData['pid'],
                        'tstamp'                => time(),
                        'crdate'                => time(),
                        'topic_title'           => $topicData['topic_title'],
                        'topic_poster'          => $topicData['topic_poster'],
                        'topic_time'            => $topicData['topic_time'],
                        'topic_views'           => $topicData['topic_views'],
                        'topic_replies'         => $topicData['topic_replies'],
                        'topic_last_post_id'    => $topicData['topic_last_post_id'],
                        'forum_id'              => $topicData['forum_id'],
                        'topic_first_post_id'   => $topicData['topic_first_post_id'],
                        'shadow_tid'            => $topicData['uid'],
                        'shadow_fid'            => $this->piVars['change_forum_id']
                    );
                    $GLOBALS['TYPO3_DB']->exec_INSERTquery(
                        'tx_mmforum_topics',
                        $shadow_insertArray
                    );
                }
                
                // Update new board UID
                $updateArray = array(
                    "forum_id" => $this->piVars['change_forum_id']
                );
                $res = $GLOBALS["TYPO3_DB"]->exec_UPDATEquery("tx_mmforum_topics", "uid = ".intval($this->piVars['tid']), $updateArray);

                $updateArray = array(
                    "forum_id" => $this->piVars['change_forum_id']
                );
                $res = $GLOBALS["TYPO3_DB"]->exec_UPDATEquery("tx_mmforum_posts", "topic_id = ".intval($this->piVars['tid']), $updateArray);
                
                $this->update_lastpost_forum($this->piVars['change_forum_id']);    // Updaten aus dem das Topic genommen wurde
                $this->update_lastpost_forum($old_forum_id);                        // Updaten in dem das Topic gepackt wurde
                $this->update_lastpost_topic($this->piVars['tid']);

                tx_mmforum_postfunctions::update_forum_posts_n_topics($old_forum_id);
                tx_mmforum_postfunctions::update_forum_posts_n_topics($this->piVars['change_forum_id']);

                  
                // Clearance for new indexing
                tx_mmforum_indexing::delete_topic_ind_date($this->piVars['tid']);
            }

            $at_top         = 0;
            $read_flag      = 0;
            $closed_flag    = 0;
            $delete_flag    = 0;
            
            if($this->piVars['at_top'])        $at_top         = 1;
            if($this->piVars['read_flag'])     $read_flag      = 1;
            if($this->piVars['closed_flag'])   $closed_flag    = 1;
            if($this->piVars['delete_flag'])   $delete_flag    = 1;
            $threadtitel = $this->piVars['threadtitel'];

            // UPDATE:
            $prefix = ($this->piVars['prefix_user'] <> $this->piVars['prefix_selected'])?$this->piVars['prefix_selected']:$this->piVars['prefix_user'];
             
            $updateArray = array(
                'at_top_flag'   => $at_top,
                'deleted'       => $delete_flag,
                'read_flag'     => $read_flag,
                'closed_flag'   => $closed_flag,
                'topic_title'   => $threadtitel, 
                'topic_is'      => $prefix,
                'tx_mmforumsearch_index_write' => 0,
            );

            $query_topic    = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_mmforum_topics', 'uid = '.intval($this->piVars['tid']), $updateArray);
            $res            = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, $query_topic);
            
            if($delete_flag == 1)
            {
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    '*',
                    'tx_mmforum_topics',
                    'uid="'.intval($this->piVars['tid']).'"'.$this->getPidQuery()
                );
                $threaddata = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                                                         
                $uRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'poster_id',
                    'tx_mmforum_posts',
                    'topic_id='.$threaddata['uid'].' AND deleted=0 AND hidden=0',
                    'poster_id'
                );
                
                $updateArray = array(
                    'deleted'   => '1',
                    'tstamp'    => time(),
                    'tx_mmforumsearch_index_write' => 0,
                );
                $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_posts', 'topic_id='.$threaddata['uid'], $updateArray);
                
                while($arr=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($uRes))
                    tx_mmforum_postfunctions::update_user_posts($arr['poster_id']);
                tx_mmforum_postfunctions::update_forum_posts_n_topics($threaddata['forum_id']);
                
                $headerlinkparams[$this->prefixId] = Array(
                    'action'     => "list_topic",
                    'fid'        => $threaddata['forum_id']
                );
                $link = $this->pi_getPageLink($this->getForumPID(),'',$headerlinkparams);
                $link = $this->getAbsURL($link);
                header('Location: '.$link); die();
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
        // Save admin panel changes  END

         
        // Determine sorting mode START
        IF ($order OR empty($GLOBALS['TSFE']->fe_user->user['uid'])){
            $userconfig['post_sort'] = $order;
        } else {
            // Load and evaluate user config
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                "*",
                "tx_mmforum_userconfig",
                "userid = ".$GLOBALS['TSFE']->fe_user->user['uid'].$this->getPidQuery()
            );

            if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
                $userconfig = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
            } else {
                $userconfig['post_sort']    = 'ASC';
            }
        }
        // Determine sorting mode END



        // Retrieve topic data from database  START
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "*",
            "tx_mmforum_topics",
            "deleted='0' AND uid = ".intval($this->piVars['tid']).$this->getPidQuery()
        );

        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "COUNT(*)-1",
            "tx_mmforum_posts",
            "topic_id='".$row['uid']."' AND deleted=0".$this->getPidQuery() 
        );
        list($replies) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        $updateArray = array(
            'topic_replies' => $replies
        );
        $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics', "uid = '".$row['uid']."'", $updateArray);

        $cruser             = $row['topic_poster'];
        $titel              = stripslashes($row['topic_title']);
        $topicprefix        = $row['topic_is'];
        $at_top_flag        = $row['at_top_flag'];
        $read_flag          = $row['read_flag'];
        $closed_flag        = $row['closed_flag'];
        // Retrieve topic data from database  END

        // Set or unset solved flag
        if ($this->piVars['solved'] === "0" OR $this->piVars['solved'] === "1")
        {
            $grouprights = t3lib_div::intExplode(",",$GLOBALS['TSFE']->fe_user->user['usergroup']);
            if (($cruser == $GLOBALS['TSFE']->fe_user->user['uid']) OR (in_array($conf['grp_admin'],$grouprights)) OR (in_array($conf['grp_mod'],$grouprights))) {
                $this->set_solved($this->piVars['tid'],$this->piVars['solved']);
                $_GET['pid'] = "last";
            } else {
                $content .= '<script type="text/javascript">alert(\''.$this->pi_getLL('topic.noSolveRights').'\')</script>';
            }
            unset($this->piVars['solved']);
        }

        // Redirect to a specific post   START
        if($this->piVars['pid'])
        {
            $post_id     = $this->piVars['pid'];

            if ($post_id == "last") {
                $topic_id     = intval($this->piVars['tid']);

                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    "uid",
                    "tx_mmforum_posts",
                    "topic_id = ".$topic_id." AND deleted=0".$this->getPidQuery(),
                    "",
                    "post_time DESC",
                    '',
                    '1'
                );

                list($pid)    = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                $linkto        = $this->get_pid_link($pid,$this->piVars['sword'],$conf);
                $linkto = $this->getAbsUrl($linkto);
                header('Location: '.$linkto); die();
            }
            elseif (preg_match ("/^[0-9]+$/",$post_id)) {
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    "topic_id",
                    "tx_mmforum_posts",
                    "deleted = 0 AND hidden = 0 AND uid = ".$post_id.$this->getPidQuery()
                );

                list($topic_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    "uid",         
                    "tx_mmforum_posts",     
                    "deleted = 0 AND hidden = 0 AND topic_id = ".$topic_id.$this->getPidQuery()
                );
                $i        = 0;

                while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                    $i++;
                    IF($row['uid'] == $post_id) {
                        $seite = $i / $conf['post_limit'];
                    }
                }
                $seite ++;
                $seite     = intval($seite);

                $linkto        = $this->get_pid_link($post_id,$this->piVars['sword'],$conf);
                $linkto = $this->getAbsUrl($linkto);
                header('Location: '.$linkto); die();
            }
        }
        // Redirect to a specific post   END

        
        // Check authentification
        if($this->getIsMod($topicData['forum_id']) || $this->getIsAdmin()) {
            $template = $this->cObj->fileResource($conf['template.']['list_post']);
            $template = $this->cObj->getSubpart($template, "###ADMIN_PANEL###");

            // Language dependent markers
            $marker = array(
                '###LABEL_ADMINOPTIONS###'          => $this->pi_getLL('topic.admin.adminoptions'),
                '###LABEL_EXPAND###'                => $this->pi_getLL('topic.admin.expand'),
                '###LABEL_COLLAPSE###'              => $this->pi_getLL('topic.admin.collapse'),
                '###LABEL_TOPICTITLE###'            => $this->pi_getLL('topic.admin.topictitle'),
                '###LABEL_TOPICPREFIX###'           => $this->pi_getLL('topic.admin.topicprefix'),
                '###LABEL_FIRST###'                 => $this->pi_getLL('topic.admin.first'),
                '###LABEL_CLOSED###'                => $this->pi_getLL('topic.admin.closed'),
                '###LABEL_DELETE###'                => $this->pi_getLL('topic.admin.delete'),
                '###LABEL_MOVE###'                  => $this->pi_getLL('topic.admin.move'),
                '###LABEL_SAVE###'                  => $this->pi_getLL('topic.admin.save'),
                '###LABEL_ADMINSONLY###'            => $this->pi_getLL('topic.adminsOnly'),
                '###IMG_EXPAND###'                  => $conf['path_img'].$conf['images.']['plus'],
                '###IMG_COLLAPSE###'                => $conf['path_img'].$conf['images.']['minus'],
            );
            
            // Create action link for form
                $marker['###ACTIONLINK###'] = $this->shieldURL($this->getAbsUrl($this->pi_linkTP_keepPIvars_url()));

            // Generate prefix list
            $prefixes = t3lib_div::trimExplode(',', $conf['prefixes']);
            $marker['###PREFIXES###'] .= '<option value="0"></option>';

            foreach($prefixes as $prefix) {
                $selected = ($topicprefix==$prefix)?'selected="selected"':'';
                $marker['###PREFIXES###'] .= '<option value="'.$prefix.'" '.$selected.'>'.$prefix.'</option>'; 

                if($selected!="") $has_sprefix = TRUE;
            }

            if($topicprefix == '0') $topicprefix = '';
            $marker['###TOPICPREFIX###'] = $topicprefix;

            // Set "at top", "admins only" and "closed" flags
            if($at_top_flag  == 1)    $at_top         = 'checked="checked"';    else $at_top        = '';
            if($read_flag    == 1)    $read_flag      = 'checked="checked"';    else $read_flag     = '';
            if($closed_flag  == 1)    $closed_flag    = 'checked="checked"';    else $closed_flag   = '';

            $marker['###AT_TOP###']     = $at_top;
            $marker['###READ_FLAG###']  = $read_flag;
            $marker['###CLOSED_FLAG###']= $closed_flag;
            $marker['###DELETE_FLAG###']= '';
            $marker['###TOPICTITEL###'] = $this->shield($titel);

            // Generate "move topic" select box
            $marker['###FORUM_BOX###']  = $this->get_forumbox($topic_id);
            
            $marker['###OPTIONS###']   .= '<input type="hidden"  name="'.$this->prefixId.'[topic_id]" value="'.$topic_id.'" />';
            $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        }
        // Output admin options  END


        // Output post listing START
        $template = $this->cObj->fileResource($conf['template.']['list_post']);
        $template = $this->cObj->getSubpart($template, "###LIST_POSTS_BEGIN###");
        $marker = array(
            '###LABEL_AUTHOR###'        => $this->pi_getLL('post.author'),
            '###LABEL_MESSAGE###'       => $this->pi_getLL('post.message')
        );

        // Log if topic has been read since last visit
        if ($GLOBALS['TSFE']->fe_user->user['usergroup']) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                "COUNT(uid) AS read_flg",         
                "tx_mmforum_postsread",     
                "topic_id = '".intval($this->piVars['tid'])."' AND user = '".$GLOBALS['TSFE']->fe_user->user['uid']."'".$this->getPidQuery()
            );

            list($userread) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            if(!$userread) {
                $insertArray = array(
                	'pid'		=> $this->getFirstPid(),
                    'topic_id'  => $this->piVars['tid'],
                    'user'      => $GLOBALS['TSFE']->fe_user->user['uid'],
                    'tstamp'    => time(),
                    'crdate'    => time(),
                );
                $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_postsread', $insertArray);
            }
        }

        // Increase hit counter
        $GLOBALS['TYPO3_DB']->sql_query("UPDATE tx_mmforum_topics SET topic_views = topic_views+1 WHERE uid = '".intval($this->piVars['tid'])."'");

        // Generate page navigation
        $limitcount = $conf['post_limit'];
        if($order!="DESC")
            $marker['###PAGES###'] = $this->pagecount ('tx_mmforum_posts','topic_id',$this->piVars['tid'],$limitcount); // Anzeigen der Seiten durch die man Bl?ttern kann
        else $marker['###PAGES###'] = '';

        // Generate breadcrumb menu
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "topic_id, forum_id",    
            "tx_mmforum_posts",    
            "deleted = 0 AND hidden = 0 AND topic_id = '".intval($this->piVars['tid'])."'".$this->getPidQuery()
        );

        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        if($this->conf['disableRootline'])
        	$template = $this->cObj->substituteSubpart($template, "###ROOTLINE_CONTAINER###", '');
        else
        	$marker['###FORUMPATH###'] = $this->get_forum_path($row['forum_id'],$row['topic_id']);

        $marker['###PAGETITLE###'] = $this->cObj->data['header'];
        $marker['###TOPICICON###'] = $this->getTopicIcon($this->piVars['tid']);
        
        // Retrieve topic data
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "*",
            "tx_mmforum_topics",   
            "uid = '".$row['topic_id']."'".$this->getPidQuery()
        );
        $row    = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $topic_data = $row;

		$this->local_cObj->data = $topic_data;

        $forumpath_topic    =  $this->shield(stripslashes($row['topic_title']));
        $topic_is           =  $row['topic_is'];
        $solved             =  $row['solved'];
        $read_flag          =  $row['read_flag'];
        $closed_flag        =  $row['closed_flag'];
        $cruser             =  $row['topic_poster'];
        $replies            =  $row['topic_replies'];
        
        $closed = $this->local_cObj->cObjGetSingle($this->conf['list_posts.']['closed'],$this->conf['list_posts.']['closed.']);

        // Determine page number
        if      ($this->piVars['page']) $seite = $this->piVars['page'];
        elseif  ($this->piVars['search_pid']) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                "uid",
                "tx_mmforum_posts",
                "deleted = 0 AND hidden = 0 AND topic_id = ".intval($this->piVars['tid']).$this->getPidQuery()
            );

            while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $i++;
                IF($row['uid'] == $this->piVars['search_pid']) {
                    $seite = $i / $limitcount;
                }
            }
            $seite++;
            $seite = intval($seite);

        }
        else $seite = 1;
        
		$prefix = $this->local_cObj->cObjGetSingle($this->conf['list_posts.']['prefix'],$this->conf['list_posts.']['prefix.']);        

        // Check if solved flag is set
        IF($solved == 1) {
            $imgInfo['src']     = $conf['path_img'].$conf['images.']['solved'];
            $imgInfo['alt']     = $this->pi_getLL('topic.isSolved');
            $imgInfo['style']   = 'vertical-align: middle;';
            $solvedIcon         = $this->imgtag($imgInfo);
        }
        else
            $solvedIcon = '';

        // Output topic name
        $marker['###TOPICNAME###'] = $closed.$prefix.$forumpath_topic;
        
        // Display poll
        if($topic_data['poll_id'] > 0 && $seite==1 && $this->conf['polls.']['enable'])
            $marker['###POLL###'] = tx_mmforum_polls::display($topic_data['poll_id']);
        else $marker['###POLL###'] = '';
        
        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

        // Determine last answering date to allow a user to edit his entry
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "MAX(post_time)",
            "tx_mmforum_posts",
            "deleted = 0 AND hidden = 0 AND topic_id = '".intval($this->piVars['tid'])."'".$this->getPidQuery()
        );

        list ($lastpostdate) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        
        $topic_data['_v_last_post_date'] = $lastpostdate;

        

        $limit = $limitcount*($seite-1).','.$limitcount;

        $postlist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "*",
            "tx_mmforum_posts",
            "deleted = 0 AND hidden = 0 AND topic_id = '".intval($this->piVars['tid'])."'".$this->getPidQuery(),
            "",
            "post_time ".$userconfig['post_sort'],
            $limit
        );

        if(($GLOBALS['TYPO3_DB']->sql_num_rows($postlist)==0) && ($seite>1)) {

            $linkParams[$this->prefixId] = array(
                'action'        => 'list_post',
                'tid'           => $this->piVars['tid'],
                'page'          => $seite-1
            );
            $link = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams);
            $link = $this->getAbsUrl($link);
            header("Location: $link"); die();
        }
            
        $template = $this->cObj->fileResource($conf['template.']['list_post']);
        $template = $this->cObj->getSubpart($template, "###LIST_POSTS###");

        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($postlist)) {
            $postmarker = tx_mmforum_postfunctions::getPostListMarkers($row,$topic_data);
			$content .= $this->cObj->substituteMarkerArrayCached($template, $postmarker);
        }
        
        // Output post listing END

        $template         = $this->cObj->fileResource($conf['template.']['list_post']);
        $template_option  = $this->cObj->getSubpart($template, "###LIST_POSTS_OPTIONEN###");
        $template         = $this->cObj->getSubpart($template, "###LIST_POSTS_END###");


        if((($read_flag == 0) AND ($closed_flag == 0)) OR $this->getIsMod($topicData['forum_id']) OR $this->getIsAdmin()) {
        	if($this->getMayWrite_topic($this->piVars['tid'])) {
	            $linkParams[$this->prefixId] = array(
	                'action'  => 'new_post',
	                'tid'     => $this->piVars['tid']
	            );
                if($this->getIsRealURL()) $linkParams[$this->prefixId]['fid'] = $topic_data['forum_id'];
	            $marker['###POSTBOTTOM###'] = $this->createButton('reply',$linkParams);
			}
			else $marker['###POSTBOTTOM###'] = '';
        }
        else $marker['###POSTBOTTOM###'] = $this->pi_getLL('topic.adminsOnly');


        IF (isset($GLOBALS['TSFE']->fe_user->user['uid'])) {

            $marker['###POSTMAILLINK###'] = tx_mmforum_postfunctions::getSubscriptionButton($topic_data['uid'],$topic_data);
            $marker['###FAVORITELINK###'] = tx_mmforum_postfunctions::getFavoriteButton($topic_data['uid'],$topic_data);
            $marker['###SOLVEDLINK###']   = tx_mmforum_postfunctions::getSolvedButton($topic_data['uid'],$topic_data);
            
            // Read which user UID created the topic
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                "topic_poster",
                "tx_mmforum_topics",
                "uid = ".intval($this->piVars['tid']).$this->getPidQuery()
            );
            list($cruser) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

			IF (($cruser == $GLOBALS['TSFE']->fe_user->user['uid']) OR $this->getIsAdmin() OR $this->getIsMod($topic_data['forum_id'])) {				
				$linkParams[$this->prefixId] = array(
					'action'		=> 'list_post',
					'tid'			=> $this->piVars['tid']
				);
				
				$marker['###SOLVED_ACTION###']      = '';
				$marker['###LABEL_THISTOPICIS###']	= $this->pi_getLL('topic.thisTopicIs');
				$marker['###LABEL_NOTSOLVED###']	= $this->pi_getLL('topic.notSolved');
				$marker['###LABEL_SOLVED###']		= $this->pi_getLL('topic.solved');
				$marker['###LABEL_SAVE###']			= $this->pi_getLL('save');
				$marker['###SOLVED_FALSE###']		= (!$solved)?'selected="selected"':'';
				$marker['###SOLVED_TRUE###']		= $solved?'selected="selected"':'';
				$marker['###SOLVED_TOPICUID###']	= $this->piVars['tid'];
				$marker['###ACTION###']				= $this->piVars['action'];
                $marker['###FORMACTION###']         = $this->shieldURL($this->getAbsUrl($this->pi_getPageLink($GLOBALS['TSFE']->id)));
			} else {
				$template_option = $this->cObj->substituteSubpart($template_option,"###SOLVEDOPTION###","");
			}
			$marker['###LABEL_OPTIONS###']     = $this->pi_getLL('options');
			
			
			$marker['###POST_OPTIONEN###']     = $this->cObj->substituteMarkerArrayCached($template_option, $marker);

        } else {
            $marker['###POST_OPTIONEN###'] = '';
        }
        
        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

        return $content;

    }
    
    function getSolvedButton($topic_id,$topic_data) {
    	
    	$imgInfo = array(
    		'src'		=> $topic_data['solved']?($this->conf['path_img'].$this->conf['images.']['solved_on']):($this->conf['path_img'].$this->conf['images.']['solved_off']),
    		'alt'		=> $topic_data['solved']?$this->pi_getLL('topic-solved-on'):$this->pi_getLL('topic-solved-off'),
    		'title'		=> $topic_data['solved']?$this->pi_getLL('topic-solved-on'):$this->pi_getLL('topic-solved-off'),
    	);
    	
    	if($topic_data['topic_poster'] == $GLOBALS['TSFE']->fe_user->user['uid'] || $this->getIsModOrAdmin($topic_data['forum_id'])) {
    		if($topic_data['solved']) {
    			$linkParams[$this->prefixId] = array(
    				'unsolve'			=> $topic_id
    			);
    			$link = $this->pi_linkTP($this->pi_getLL('topic-solvedshort-off'),$linkParams).' / <strong>'.$this->pi_getLL('topic-solvedshort-on').'</strong>';
    		} else {
    			$linkParams[$this->prefixId] = array(
    				'solve'				=> $topic_id
    			);
    			$link = '<strong>'.$this->pi_getLL('topic-solvedshort-off').'</strong> / '.$this->pi_linkTP($this->pi_getLL('topic-solvedshort-on'),$linkParams);
    		}
    	} else {
    		$link = $topic_data['solved']?$this->pi_getLL('topic-solvedshort-on'):$this->pi_getLL('topic-solvedshort-off');
    	}
        
        $image = $this->imgtag($imgInfo);
        $image = $this->cObj->stdWrap($image,$this->conf['list_posts.']['optImgWrap.']);
        $link  = $this->cObj->stdWrap($link,$this->conf['list_posts.']['optLinkWrap.']);
        
        $result = $this->cObj->stdWrap($image.$link,$this->conf['list_posts.']['optItemWrap.']);
        
        return $result;
    }
    
    function getFavoriteButton($topic_id,$topic_data) {
    	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "uid",
            "tx_mmforum_favorites",
            "user_id = ".$GLOBALS['TSFE']->fe_user->user['uid']." AND topic_id = ".$this->piVars['tid'].$this->getPidQuery()
        );
         
        if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) < 1) {
            $imgInfo['alt'] = $this->pi_getLL('topic.favorite.off');
            $imgInfo['title'] = $this->pi_getLL('topic.favorite.off');
            $imgInfo['src'] = $this->conf['path_img'].$this->conf['images.']['favorite_off'];
            $favlinkParams[$this->prefixId] = array(
            	'action'		=> 'set_favorite',
            	'tid'			=> $this->piVars['tid']
            );
            if($this->getIsRealURL()) $favlinkParams[$this->prefixId]['fid'] = $topic_data['forum_id'];
            
            $link = $this->pi_linkTP($this->pi_getLL('on'),$favlinkParams).' / <strong>'.$this->pi_getLL('off').'</strong>';
        } else {
            $imgInfo['alt'] = $this->pi_getLL('topic.favorite.on');
            $imgInfo['title'] = $this->pi_getLL('topic.favorite.on');
            $imgInfo['src'] = $this->conf['path_img'].$this->conf['images.']['favorite_on'];
            $favlinkParams[$this->prefixId] = array(
            	'action'		=> 'del_favorite',
            	'tid'			=> $this->piVars['tid']
            );
            if($this->getIsRealURL()) $favlinkParams[$this->prefixId]['fid'] = $topic_data['forum_id'];
            
            $link = '<strong>'.$this->pi_getLL('on').'</strong> / '.$this->pi_linkTP($this->pi_getLL('off'),$favlinkParams);
        }
        
        $image = $this->imgtag($imgInfo);
        $image = $this->cObj->stdWrap($image,$this->conf['list_posts.']['optImgWrap.']);
        $link  = $this->cObj->stdWrap($link,$this->conf['list_posts.']['optLinkWrap.']);
        
        $result = $this->cObj->stdWrap($image.$link,$this->conf['list_posts.']['optItemWrap.']);
        
        return $result;
    }
        
    function getSubscriptionButton($topic_id,$topic_data) {
    	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "uid",
            "tx_mmforum_topicmail",
            "user_id = ".$GLOBALS['TSFE']->fe_user->user['uid']." AND topic_id = ".intval($this->piVars['tid']).$this->getPidQuery()
        );
        IF ($GLOBALS['TYPO3_DB']->sql_num_rows($res) < 1) {
            $imgInfo['alt'] = $this->pi_getLL('topic.emailSubscr.off');
            $imgInfo['title'] = $this->pi_getLL('topic.emailSubscr.off');
            $imgInfo['src'] = $this->conf['path_img'].$this->conf['images.']['info_mail_off'];
            $linkParams[$this->prefixId] = array(
                'action'        => 'set_havealook',
                'tid'           => $this->piVars['tid']
            );
            if($this->getIsRealURL()) $linkParams[$this->prefixId]['fid'] = $topic_data['forum_id'];
            $link = $this->pi_linkTP($this->pi_getLL('on'),$linkParams).' / <strong>'.$this->pi_getLL('off').'</strong>';
        } else {
            $imgInfo['alt'] = $this->pi_getLL('topic.emailSubscr.on');
            $imgInfo['title'] = $this->pi_getLL('topic.emailSubscr.on');
            $imgInfo['src'] = $this->conf['path_img'].$this->conf['images.']['info_mail_on'];
            $linkParams[$this->prefixId] = array(
                'action'        => 'del_havealook',
                'tid'           => $this->piVars['tid']
            );
            if($this->getIsRealURL()) $linkParams[$this->prefixId]['fid'] = $topic_data['forum_id'];
            $link = '<strong>'.$this->pi_getLL('on').'</strong> / '.$this->pi_linkTP($this->pi_getLL('off'),$linkParams);
        }
        
        $image = $this->imgtag($imgInfo);
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
     * @param   string $row   The post record
     * @param   string $topic The record of the topic the post is located in.
     * @return  array         The marker array.
     */
    function getPostListMarkers($row,$topic, $extra='') {
    
    	$mAp = tx_mmforum_postfunctions::marker_getPostmenuMarker($row,$topic);
        $marker = array(
            '###LABEL_AUTHOR###'        => $extra['###LABEL_AUTHOR###'],
            '###LABEL_MESSAGE###'       => $extra['###LABEL_MESSAGE###'],  
    		'###ATTACHMENTS###'			=> tx_mmforum_postfunctions::marker_getAttachmentMarker($row,$topic),
            '###POSTOPTIONS###'			=> tx_mmforum_postfunctions::marker_getPostoptionsMarker($row,$topic),
    
            '###POSTMENU###'			=> implode('',$mAp),
            '###PROFILEMENU###'         => $mAp['profilebuttons'],
            '###MESSAGEMENU###'         => $mAp['msgbuttons'], 
	        '###POSTUSER###'    		=> $this->ident_user($row['poster_id'],$this->conf,($topic['topic_replies']>0)?($topic['topic_poster']):FALSE),
	        '###POSTTEXT###'    		=> tx_mmforum_postfunctions::marker_getPosttextMarker($row,$topic),
	        '###ANKER###'       		=> '<a name="pid'.$row['uid'].'"></a>',
	        '###POSTDATE###' 			=> $this->pi_getLL('post.writtenOn').': '.$this->formatDate($row['post_time']),
        );

		// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['postListMarkerArray'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['postListMarkerArray'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->processPostListMarkerArray($marker,$row,$topic,$this);
				}
			}
        
        return $marker;
        
    }
    
    function marker_getPosttextMarker($row,$topic) {
        list($text_uid,$posttext,$tstamp,$cache_tstamp,$cache_text) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,post_text,tstamp,cache_tstamp,cache_text','tx_mmforum_posts_text','deleted="0" AND post_id="'.$row['uid'].'"'));
        $postold = $posttext;
        
        if($tstamp > $cache_tstamp || $cache_tstamp == 0) {
        	$posttext = $this->shield($posttext);
        	$posttext = $this->bb2text($posttext,$this->conf);
        	$updateArray = array(
        		'cache_tstamp'			=> time(),
        		'cache_text'			=> $posttext
        	);
        	$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_posts_text','uid='.$text_uid,$updateArray);
        }
        else
        	$posttext = $cache_text;
        $posttext = $this->highlight_text($posttext,$this->piVars['sword']);
        
        $user_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_users',"uid='".$row['poster_id']."'");
        if($GLOBALS['TYPO3_DB']->sql_num_rows($user_res))
            $user = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($user_res);
        
        if($row['edit_count']>0) {
			$editMarker = array(
                '###COUNT###'    => intval($row['edit_count']),
                '###DATE###'     => date(" d.m.Y ",$row['edit_time']),
                '###TIME###'     => date("H:i",$row['edit_time'])
            );        	
			$posttext .= '<br /><br />'.$this->cObj->substituteMarkerArray($this->pi_getLL('post.edited'),$editMarker);
        }
        
        if($user['tx_mmforum_user_sig']) {
            $parseSignature = $this->shield($user['tx_mmforum_user_sig']);
            
            if($this->conf['signatureBBCodes'])
                $signatur4output = tx_mmforum_postparser::main($this,$this->conf,$parseSignature,'textparser');
            else $signatur4output = nl2br($parseSignature);
            
            if(intval($this->conf['signatureLimit'])>0) {
                $sigLines = explode("\n", $signatur4output);
                if(count($sigLines) > $this->conf['signatureLimit']) $sigLines = array_slice($sigLines,0,$this->conf['signatureLimit']);

                $signatur4output = implode("\n",$sigLines);
            }

            $posttext .= $this->cObj->stdWrap($signatur4output,$this->conf['list_posts.']['signature_stdWrap.']);
        }
        
        return $posttext;
    }
    
    function marker_getPostmenuMarker($row,$topic) {
    	
        $read_flag          =  $topic['read_flag'];
        $closed_flag        =  $topic['closed_flag'];
    	
    	$poster = $row['poster_id'];
        $user_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_users',"uid='$poster'");
        if($GLOBALS['TYPO3_DB']->sql_num_rows($user_res))
            $user = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($user_res);
        else $user = false;

        $menu = $profile = '';
        #if($GLOBALS['TSFE']->fe_user->user['username']){
        if($this->getMayWrite_topic($this->piVars['tid'])) {
            if ((($read_flag == 0) AND ($closed_flag == 0)) OR $this->getIsAdmin() OR $this->getIsMod($topic['forum_id'])) {
                $quoteParams[$this->prefixId] = array(
                    'action'        => 'new_post',
                    'tid'           => $this->piVars['tid'],
                    'quote'         => $row['uid']
                );
	            if($this->getIsRealURL()) {
	            	$quoteParams[$this->prefixId]['fid'] = $row['forum_id'];
                    $quoteParams[$this->prefixId]['pid'] = $this->pi_getLL('realurl.quote');
	            }
                $menu .=' '. $this->createButton('quote',$quoteParams,0,true);
            }
        }

        if($user && $user['deleted']=='0') {
            $profile .= $this->createButton('profile','profileView:'.$user['uid'],0,true);
        }
        
        if ($user['www']) {
            $url = parse_url($user['www']);
            IF (!$url['scheme']) $user['www'] = 'http://'.$user['www'];
            $profile .=' '.  $this->createButton('www',false,false,true,$user['www']);
        }
        if ($user['tx_mmforum_icq'])
            $profile .=' '.  $this->createButton('icq',false,false,true,'http://www.icq.com/scripts/search.dll?to='.htmlspecialchars($user['tx_mmforum_icq']));
        if ($user['tx_mmforum_aim'])
            $profile .=' '.  $this->createButton('aim',false,false,true,'aim:goim?screenname='.htmlspecialchars($user['tx_mmforum_aim']).'&message=Hello+Are+you+there?');
        if ($user['tx_mmforum_yim'])
            $profile .=' '.  $this->createButton('yim',false,false,true,'http://edit.yahoo.com/config/send_webmesg?.target='.htmlspecialchars($user['tx_mmforum_yim']).'&.src=pg');
        if ($user['tx_mmforum_skype'])
            $profile .=' '.  $this->createButton('skype',false,false,true,'skype:'.htmlspecialchars($user['tx_mmforum_skype']).'?call');
        
        if ($GLOBALS['TSFE']->fe_user->user['username'] && $user['uid']!=$GLOBALS['TSFE']->fe_user->user['uid']){
        	if(intval($this->conf['pm_id']) > 0 && $user && $user['deleted']=='0') {
                $pmParams = array(
                    'tx_mmforum_pi3[action]'        => 'message_write',
                    'tx_mmforum_pi3[userid]'        => $user['uid']
                );
                if($this->getIsRealUrl()) {
                    $pmParams['tx_mmforum_pi3']['folder'] = 'inbox';
                    $pmParams['tx_mmforum_pi3']['messid'] = $this->pi_getLL('realurl.pmnew');
                }
                $profile .= ' '. $this->createButton( 'pm',$pmParams,$this->conf['pm_id'],true);
			}
            
            $alertParams[$this->prefixId] = array(
                'action'        => 'post_alert',
                'pid'     		=> $row['uid'],
            );
            if($this->getIsRealUrl()) {
                $alertParams[$this->prefixId]['tid'] = $this->piVars['tid'];
                $alertParams[$this->prefixId]['fid'] = $row['forum_id'];
            }
            $menu .=' '.  $this->createButton('post-alert',$alertParams,0,true);

        }

        return array('msgbuttons'=>$menu, 'profilebuttons'=>$profile);
    }

    function marker_getPostoptionsMarker($row,$topic) {
    	$lastpostdate = $topic['_v_last_post_date'];
    	
    	IF ((($row['poster_id'] == $GLOBALS['TSFE']->fe_user->user['uid']) AND ($lastpostdate == $row['post_time'])) OR $this->getIsAdmin() OR $this->getIsMod($topic['forum_id'])) {
            
            $linkParams[$this->prefixId] = array(
                'action'        => 'post_edit',
                'pid'           => $row['uid']
            );
            if($this->getIsRealURL()) {
            	$linkParams[$this->prefixId]['fid'] = $row['forum_id'];
            	$linkParams[$this->prefixId]['tid'] = $row['topic_id'];
            }
            $editLink = $this->createButton('edit',$linkParams,0,true);

            $linkParams[$this->prefixId] = array(
                'action'        => 'post_del',
                'pid'           => $row['uid']
            );
            if($this->getIsRealURL()) {
            	$linkParams[$this->prefixId]['fid'] = $row['forum_id'];
            	$linkParams[$this->prefixId]['tid'] = $row['topic_id'];
            }
			$delLink = $this->createButton('delete',$linkParams,0,true);

            return $editLink.$delLink;
        } else {
            return '';
        }
    }
    
    function marker_getAttachmentMarker($row,$topic) {
    	if($row['attachment'] != 0) {
            $attachments = $this->cObj->stdWrap($this->pi_getLL('attachments.title'),$this->conf['attachments.']['attachmentLabel_stdWrap.']);
            
            $attachment_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'tx_mmforum_attachments',
                'uid IN ('.$row['attachment'].') AND deleted=0',
                '',
                'uid ASC'
            );
            
            while($attachment = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($attachment_res)) {
	            if(!@file_exists($attachment['file_path']))
	            	continue;

	            $linkParams[$this->prefixId] = array(
	                'action'            => 'get_attachment',
	                'attachment'        => $attachment['uid']
	            );
	            if($this->getIsRealURL()) {
	                unset($linkParams[$this->prefixId]['attachment']);
	                $linkParams[$this->prefixId]['fid'] = $this->pi_getLL('realurl.attachment').$attachment['uid'];
	            }
            
	            $size = $attachment['file_size'].' '.$this->pi_getLL('attachment.bytes');
	            if($attachment['file_size'] > 1024) $size = round($attachment['file_size']/1024,2).' '.$this->pi_getLL('attachment.kilobytes');
	            if($attachment['file_size'] > 1048576) $size = round($attachment['file_size']/1048576,2).' '.$this->pi_getLL('attachment.megabytes');
	            
	            $aLink = $this->pi_linkTP($attachment['file_name'],$linkParams);
	            if($this->conf['attachments.']['imagePreview']=='1') {
	                $imgConf = $this->conf['attachments.']['imagePreviewObj.'];
	                $imgConf['file'] = $attachment['file_path'];
	                
	                $aPreview = $this->cObj->cObjGetSingle($this->conf['attachments.']['imagePreviewObj'],$imgConf);
	                $aPreview = $this->pi_linkTP($aPreview,$linkParams);
	            } else $aPreview = '';
	            
	            $aString = $aPreview.$aLink.' ('.$this->pi_getLL('attachment.type').': '.$attachment['file_type'].', '.$this->pi_getLL('attachment.size').': '.$size.') &mdash; '.$attachment['downloads'].' '.$this->pi_getLL('attachment.downloads');
	            $sAttachment .= $this->cObj->stdWrap($aString, $this->conf['attachments.']['attachmentLink_stdWrap.']);
            }	            
	        $attachments .= $this->cObj->stdWrap($sAttachment, $this->conf['attachments.']['attachment_stdWrap.']);
            
            return $attachments;
        }
        return '';
    }
    
    /**
     * Updates the number of Posts in a topic topics in the forum table 
     *
     * @param  integer $forum_id    ID of the forum to update
     */
    function update_forum_posts_n_topics ($forum_id) {
        $forum_id = intval($forum_id);
        $mysql_count_posts = $GLOBALS["TYPO3_DB"]->exec_SELECTquery(
            "uid",            
            "tx_mmforum_posts",     
            "forum_id = ".$forum_id." AND deleted = 0 AND hidden = 0",
            '',
            'post_time DESC'
        );
        $count_posts = $GLOBALS['TYPO3_DB']->sql_num_rows($mysql_count_posts);
        list($last_post_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($mysql_count_posts);
        
        $mysql_count_topics = $GLOBALS["TYPO3_DB"]->exec_SELECTquery(
            "uid",
            "tx_mmforum_topics",
            "forum_id=".$forum_id." AND deleted=0 AND hidden=0 AND shadow_tid=0"
        );
        $count_topics = $GLOBALS['TYPO3_DB']->sql_num_rows($mysql_count_topics);
        
        $updateArray = array(
                "forum_posts"           => $count_posts,
                "forum_topics"          => $count_topics,
                'forum_last_post_id'    => $last_post_id,
                'tstamp'                => time()

        );
        $res = $GLOBALS["TYPO3_DB"]->exec_UPDATEquery("tx_mmforum_forums", "uid = ".$forum_id, $updateArray);
    }
    
    function update_user_posts($user_id) {
        $user_id = intval($user_id);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'COUNT(*)',
            'tx_mmforum_posts',
            'poster_id='.$user_id.' AND deleted=0 AND hidden=0'
        );
        list($posts) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        
        $updateArray = array(
            'tx_mmforum_posts'      => $posts
        );
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users','uid='.$user_id,$updateArray);
    }
    
    function update_post_attachment($post_id) {
        $post_id;
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'attachment',
            'tx_mmforum_posts',
            'uid='.$post_id
        );
        if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) return;
        list($a_uid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tx_mmforum_attachments',
            'uid='.$a_uid,
            array('post_id' => $post_id)
        );
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
        $postlist = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_posts',
            'deleted = 0 AND hidden = 0 AND uid = "'.$postId.'"'.$this->getPidQuery()
        );
        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($postlist);

        $topic_id = $row['topic_id'];

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('MAX(post_time)','tx_mmforum_posts','deleted="0" AND hidden="0" AND topic_id="'.$row['topic_id'].'"'.$this->getPidQuery());
        list ($lastpostdate) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        $grouprights = explode(",",$GLOBALS['TSFE']->fe_user->user['usergroup']);
        IF ((($row['poster_id'] == $GLOBALS['TSFE']->fe_user->user['uid']) AND ($lastpostdate == $row['post_time'])) OR $this->getIsAdmin() OR $this->getIsMod($row['forum_id'])) {
            // Retrieve post data
                $res        = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_posts',"uid = '".intval($this->piVars['pid'])."'".$this->getPidQuery());
                $row        = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                $topic_id    = $row['topic_id'];
                $forum_id    = $row['forum_id'];
                $cr_user    = $row['poster_id'];

            // Mark post as deleted
                $updArray = array(
					"deleted" => 1,
					'tx_mmforumsearch_index_write' => 0,
				);
                $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_posts','uid = "'.intval($this->piVars['pid']).'"',$updArray);
                $updArray = array("deleted"=>1);
                $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_posts_text','post_id = "'.intval($this->piVars['pid']).'"',$updArray);
                
            // Delete file attachment
                if($row['attachment'] > 0)
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_attachments','post_id='.$row['uid'],$updArray);

            // Decrease user's post coutner
                $GLOBALS['TYPO3_DB']->sql_query("UPDATE fe_users SET  tx_mmforum_posts = tx_mmforum_posts - 1 WHERE uid = '".$cr_user."'");

            // Get last active post in topic
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','tx_mmforum_posts','deleted="0" AND hidden="0" AND topic_id="'.$topic_id.'"'.$this->getPidQuery(),'','post_time ASC');
                list($lastpostid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

            // Decrease topic reply counter
                $mysql = "UPDATE tx_mmforum_topics SET tx_mmforumsearch_index_write=0, topic_replies = topic_replies-1,topic_last_post_id = '".$lastpostid."' WHERE uid = '".$topic_id."'";
                $GLOBALS['TYPO3_DB']->sql_query($mysql);


            // Refresh last post in board view

            // Get last active post in topic
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','tx_mmforum_posts','deleted="0" AND hidden="0" AND topic_id="'.$row['topic_id'].'"'.$this->getPidQuery(),'','post_time DESC');
                list($lastpostid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

            // Get last active post in board
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','tx_mmforum_posts','deleted="0" AND hidden="0" AND forum_id="'.$forum_id.'"'.$this->getPidQuery(),'','post_time DESC','1');
                $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                $last_forum_post_id = $row['uid'];

            // Decrease board post counter.
                $mysql = "UPDATE tx_mmforum_forums SET forum_posts = forum_posts-1 WHERE uid = '".$forum_id."'";
                $GLOBALS['TYPO3_DB']->sql_query($mysql);

            // Determine, if deleted post was last remaining post in topic. If so, topic is deleted, too
                $postmenge = $GLOBALS['TYPO3_DB']->sql_num_rows($GLOBALS['TYPO3_DB']->exec_SELECTquery('poster_id,topic_id,post_time','tx_mmforum_posts',"deleted = 0 AND hidden = 0 AND topic_id = '$topic_id'".$this->getPidQuery()));
                IF ($postmenge == 0) {
                    // Mark topic as deleted
                    $updArray = array("deleted"=>1);
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics',"uid='$topic_id'",$updArray);

                    // Delete poll
                    $pollObj = t3lib_div::makeInstance('tx_mmforum_polls');
                    $pollObj->deletePoll(0,$topic_id);
                    
                    // Determine last active post in board
                    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','tx_mmforum_posts',"deleted = 0 AND hidden = 0 AND forum_id = '$forum_id'".$this->getPidQuery(),'','post_time DESC','1');
                    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                    $last_forum_post_id = $row['uid'];

                    // Decrease board topic counter
                    $GLOBALS['TYPO3_DB']->sql_query("UPDATE tx_mmforum_forums SET forum_topics = forum_topics-1 WHERE uid = '".$forum_id."'");
                    
                    // Remove shadow topics pointing to this topic
                    $updateArray = array(
                    	'tstamp'			=> time(),
                    	'deleted'			=> 1
                    );
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics',"shadow_tid='$topic_id'",$updateArray);

                    $this->update_lastpost_forum($forum_id);
                    $this->update_lastpost_topic($topic_id);

                    $linkParams[$this->prefixId] = array(
                        'action'    => 'list_topic',
                        'fid'       => $forum_id
                    );
                    $link = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams);
                    $link = $this->getAbsUrl($link);
                    header("Location: $link"); die();
                } else {

                    $this->update_lastpost_forum($forum_id);
                    $this->update_lastpost_topic($topic_id);
                    
                    $linkParams[$this->prefixId] = array(
                        'action'    => 'list_post',
                        'tid'       => $topic_id,
                        'pid'       => 'last'
                    );
                    $link = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams);
                    $link = $this->getAbsUrl($link);
                    header("Location: $link"); die();
                }

        } else {
            $template = $this->cObj->fileResource($conf['template.']['error']);
            $marker = array();
            $marker['###ERROR###'] = $this->pi_getLL('deletePost.noAccess');
        }

        $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        return $content;
    }  

}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_postfunctions.php"])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_postfunctions.php"]);
}

?>