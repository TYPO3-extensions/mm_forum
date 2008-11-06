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
 *   63:     function list_user_post($conf,$user_id,$page)
 *  183:     function listpost_pagelink($label,$page,$userid)
 *  205:     function get_userdetails ($user_id)
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once (PATH_tslib."class.tslib_pibase.php");
require_once ( t3lib_extMgm::extPath('mm_forum').'includes/class.tx_mmforum_postparser.php' ); 

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
class tx_mmforum_user extends tslib_pibase {
   
	/**
	 * Lists all current user's posts in a HTML table, including page navigation and
	 * general information like total post/topic count and average posts per day.
	 * @param  array  $conf    The plugin's configuration vars.
	 * @param  int    $user_id The UID of the user, whose posts are to be listed.
	 * @param  int    $page    The current page
	 * @return string          The HTML table
	 */
	function list_user_post($conf,$user_id,$page) {
        $imgInfo  = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');
		if(!$user_id)   $user_id = $GLOBALS['TSFE']->fe_user->user['uid'];
		if(!$page)      $page = 0;
        
        if($this->getIsRealURL()) $page = str_replace($this->pi_getLL('realurl.page').'_','',$page);
		
		if(!$user_id) {
			return $this->errorMessage($conf, $this->pi_getLL('user.noLogin'));
		}
		
		$user_id = intval($user_id);

		$anzahl = 25;

		$template        = $this->cObj->fileResource($conf['template.']['userdetail']);
		$template        = $this->cObj->getSubpart($template, "###USERPOSTS###");
		$template_sub    = $this->cObj->getSubpart($template, "###LIST###");

		$marker = array(
			'###LABEL_POSTLIST###'	=> $this->pi_getLL('user.postList'),
            '###LABEL_TOPIC###'		=> $this->pi_getLL('board.topic'),
            '###LABEL_LASTPOST###'	=> $this->pi_getLL('board.lastPost')
		);

		// Determine post count
		list($count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('COUNT(uid)','tx_mmforum_posts','hidden="0" AND deleted="0" AND poster_id="'.$user_id.'"'));

		$marker['###POSTCOUNT###'] = $this->getauthor($user_id);

		// Determine topic count
		list($topics) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('COUNT(uid)','tx_mmforum_topics','hidden="0" AND deleted="0" AND topic_poster="'.$user_id.'"'));
        
		
		// Build page navigation
		$page_count = ceil($count / $anzahl);

		if ($page <= 0) $page = 1;
		if ($page > $page_count) $page = $page_count;

		$i = $page - 3;
		if ($i <= 0) $i = 1;

		for ($j = 1; $j <= 7; $j++) {
			$pagelink = tx_mmforum_user::listpost_pagelink($i,$i,$user_id);

			IF (($i >= 1) AND ($i<= $page_count)) {
					IF ($i == $page) {
						$pages .= "|<strong> $i </strong>|";
					} else {
						$pages .= $pagelink;
					}
			}
			$i++;
		}

		$min   = ($page>1)?tx_mmforum_user::listpost_pagelink(''.$this->pi_getLL('page.first'),1,$user_id):'';
        $left  = ($page>2)?tx_mmforum_user::listpost_pagelink('&laquo;'.$this->pi_getLL(''),$page-1,$user_id):'';
        $right = ($page<$page_count-1)?tx_mmforum_user::listpost_pagelink($this->pi_getLL('').'&raquo;',$page+1,$user_id):'';
        $max   = ($page<$page_count)?tx_mmforum_user::listpost_pagelink($this->pi_getLL('page.last').'',$page_count,$user_id):'';

		$marker['###PAGES###'] = $min.$left.$pages.$right.$max;
        $marker['###PAGES###'] = str_replace('||','|',$marker['###PAGES###']);
        
		$from = $anzahl * ($page-1);

		if($count > 0) {
			$template = $this->cObj->substituteSubpart($template, "###NOPOSTS###", "");
		
			// Read posts
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				't.topic_title,
				 t.topic_is,
				 t.solved,
				 f.forum_name,
				 c.forum_name AS category_name,
				 p.*',
				'tx_mmforum_posts p
				 LEFT JOIN tx_mmforum_topics t ON t.uid = p.topic_id
				 LEFT JOIN tx_mmforum_forums f ON t.forum_id = f.uid
				 LEFT JOIN tx_mmforum_forums c ON f.parentID = c.uid',
				'p.hidden         = "0" AND
				 p.deleted        = "0" AND
				 p.poster_id      = "'.$user_id.'"'.
				 $this->getMayRead_forum_query('f'),
				'',
				'p.crdate DESC',
				"$from,$anzahl"
			);
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$title = $this->shield($row['topic_title']);
				if($row['topic_is']) $title = "<span style=\"color:blue;\">[{$row['topic_is']}]</span> $title";
	            $imgInfo['src'] = $conf['path_img'].$conf['images.']['solved'];
	            $imgInfo['alt'] = $this->pi_getLL('topic.isSolved'); 
				$solved = $row['solved']?$this->imgtag($imgInfo):'';

				$linkparams[$this->prefixId] = array (
					'action'    => 'list_post',
					'tid'       => $row['topic_id']
				);
                if($this->getIsRealURL()) {
                    $linkparams[$this->prefixId]['fid'] = $row['forum_id'];
                }
				$postMarker = array(
					'###TOPICNAME###'		=> $this->pi_linkToPage($title,$conf['pid_forum'],$target='_self',$linkparams).$solved,
					'###TOPICDATE###'		=> date("d.m.Y [H:i]",$row[crdate]),
					'###CATEGORY###'		=> $this->shield($row['category_name']),
					'###FORUM###'			=> $this->shield($row['forum_name']),
					'###READIMAGE###'		=> $this->getTopicIcon($row['topic_id']),
				);
				$content .= $this->cObj->substituteMarkerArrayCached($template_sub, $postMarker);  
			}
		}
		else {
			$marker['###LABEL_NOPOSTS###'] = $this->pi_getLL('user.noposts');
		}

		list($ucrdate) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('crdate','fe_users',"uid='$user_id'"));

		$marker['###STAT###'] .= '<strong>'.$count.'</strong> '.$this->pi_getLL('user.totalPosts').', <strong>'.$topics.'</strong> '.$this->pi_getLL('user.topicsTotal').'<br />';
		$marker['###STAT###'] .= $this->cObj->substituteMarker($this->pi_getLL('user.postsPerDay'),'###POSTS###','<strong>'.round($count / ceil(((time() - $ucrdate) / 86400)),2).'</strong>');

		$template = $this->cObj->substituteMarkerArrayCached($template, $marker);  
		$content = $this->cObj->substituteSubpart($template,'###LIST###',$content);  
		return $content;
	}
    
    /**
     * Generates a link to a specific page of a user's post history.
     * @param  string $label  The label of the page link
     * @param  int    $page   The number of the page to be linked to
     * @param  int    $userid The UID of the user whose post history is to be displayed.
     * @return string         A link to the specified page.
     */
    function listpost_pagelink($label,$page,$userid) {
        $linkparams[$this->prefixId] = array (
			'action'      => 'post_history',
            'user_id'     => $userid,
			'page'        => $page
		);
        if($this->getIsRealURL()) {
            $linkparams[$this->prefixId] = array(
                'action'    => 'post_history',
                'fid'       => tx_mmforum_tools::get_username($userid),
                'tid'       => $this->pi_getLL('realurl.page').'_'.$page
            );
        }
        return '| '.$this->pi_linkToPage($label,$GLOBALS['TSFE']->id,$target='_self',$linkparams).' |';
    }

	/**
	 * Returns a user record specified by UID.
	 * @param  int   $user_id The UID of the user whose record is to be loaded
	 * @return array          The user record as associative array
	 */
	function get_userdetails ($user_id) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'fe_users',
			'uid = '.$user_id
		);
		return mysql_fetch_assoc($res);
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_user.php"])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_user.php"]);
}
?>
