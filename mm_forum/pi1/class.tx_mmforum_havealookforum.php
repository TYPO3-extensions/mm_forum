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
 *   59: class tx_mmforum_havealookforum extends tslib_pibase
 *   69:     function set_havealookforum ($content,$conf)
 *  104:     function del_havealookforum ($content,$conf)
 *  125:     function edit_havealookforum ($content,$conf)
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
 
require_once(PATH_tslib."class.tslib_pibase.php");

/**
 * The class 'tx_mmforum_havealookforum' is a subclass for the 'Forum'
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
 * @author	   Cyrill Helg <cyrill.h@solnet.ch>
 * @copyright  2007 Mittwald CM Service
 * @version    11. 01. 2007
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_havealookforum extends tslib_pibase {

    /**
     * Adds a topic to a user's list of email subscriptions.
     * @param  string $content The plugin content
     * @param  array  $conf    The configuration vars of the plugin
     * @return string          An error message in case the redirect attempt to
     *                         the previous page fails.
     */
    function set_havealookforum ($content,$conf) {
        // Executing database operations
        $forumid = $this->piVars['fid'];
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            "uid",
            "tx_mmforum_forummail",
            "user_id ='".$GLOBALS['TSFE']->fe_user->user['uid']."' AND forum_id='$forumid'".$this->getPidQuery()
        );

        IF ($GLOBALS['TYPO3_DB']->sql_num_rows($res) < 1) {
            $insertArray = array(
            	'pid'		=> $this->getFirstPid(),
                'tstamp'    => time(),
                'crdate'    => time(), 
                'forum_id'  => $forumid,
                'user_id'   => $GLOBALS['TSFE']->fe_user->user['uid']
            );
            $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_forummail', $insertArray);
        }

        // Redirecting visitor back to previous page
        $ref= getenv("HTTP_REFERER");
        $content = $this->pi_getLL('subscr.addSuccess').'<br />'.$this->pi_getLL('redirect.error').'<br />';
        if ($ref) header('Location: '.$ref);

        return $content;
    }

    /**
     * Removes a topic from a user's list of email subscriptions.
     * @param  string $content The plugin content
     * @param  array  $conf    The configuration vars of the plugin
     * @return string          An error message in case the redirect attempt to
     *                         the previous page fails.
     */
    function del_havealookforum ($content,$conf) {
        // Executing database operations 
        $forumid = $this->piVars['fid'];
        $res = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_forummail', "user_id = ".$GLOBALS['TSFE']->fe_user->user['uid']." AND forum_id = ".$forumid.$this->getPidQuery()); 

        // Redirecting visitor back to previous page
        $ref= getenv("HTTP_REFERER");
        $content = $this->pi_getLL('subscr.delSuccess').'<br />'.$this->pi_getLL('redirect.error').'<br />';
        if ($ref) header('Location: '.$ref);

        return $content;
    }

    /**
     * Displays a list of a user's email subscriptions.
     * Performs also actions like editing or deleting subscriptions.
	* !TODO: This is not yet implemented for forumcategory subscriptions
     * @param  string $content The plugin content
     * @param  array  $conf    The configuration vars of the plugin
     */
//     function edit_havealook ($content,$conf)
//     {
//         $imgInfo  = array('border' => $conf['img_border'], 'alt' => '', 'src' => '', 'style' => '');
//         if(isset($GLOBALS['TSFE']->fe_user->user['uid'])) {
// 
//             // Delete a subscription
//             if($this->piVars['deltid']) {
//                 $res = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_forummail', "user_id = ".$GLOBALS['TSFE']->fe_user->user['uid']." AND forum_id = ".mysql_escape_string($this->piVars['deltid'])); 
//             }
// 
//             // Delete several subscriptions
//             if($this->piVars['havealook_action'] == "delete") {
//                 foreach($this->piVars['fav_delete'] as $del_tid) {
//                     $res = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_forummail', "user_id = ".$GLOBALS['TSFE']->fe_user->user['uid']." AND forum_id = ".intval($del_tid)); 
//                 }
//             }
// 
//             // Determination of sorting mode
//             #$orderBy = $this->piVars['order']?$this->piVars['order']:"added";
//             $orderBy = t3lib_div::GPvar('order')?t3lib_div::GPvar('order'):"added";
//                 if( $orderBy == "lpdate")   $order = "t.topic_last_post_id DESC";
//             elseif( $orderBy == "cat")      $order = "c.sorting ASC, f.sorting ASC, t.topic_last_post_id DESC";
//             elseif( $orderBy == "added")    $order = "m.uid DESC";
//             elseif( $orderBy == "alphab")   $order = "t.topic_title ASC";
// 
//             // Starting output
//             $template = $this->cObj->fileResource($conf['template.']['favorites']);
//             $template = $this->cObj->getSubpart($template, "###FAVORITES_SETTINGS###");
//             $marker = Array(
//                 "###ACTION###"              => "",
//                 "###ORDER_LPDATE###"        => ($orderBy == "lpdate")?'selected="selected"':'',
//                 "###ORDER_CAT###"           => ($orderBy == "cat"   )?'selected="selected"':'',
//                 "###ORDER_ADDED###"         => ($orderBy == "added" )?'selected="selected"':'',
//                 "###ORDER_ALPHAB###"        => ($orderBy == "alphab")?'selected="selected"':'',
//                 
//                 '###LABEL_ORDERBY###'       => $this->pi_getLL('favorites.orderBy'),
//                 '###LABEL_ORDER_LPDATE###'  => $this->pi_getLL('favorites.orderBy.lpdate'),
//                 '###LABEL_ORDER_CAT###'     => $this->pi_getLL('favorites.orderBy.cat'),
//                 '###LABEL_ORDER_ADDED###'   => $this->pi_getLL('favorites.orderBy.added'),
//                 '###LABEL_ORDER_ALPHAB###'  => $this->pi_getLL('favorites.orderBy.alphab')
//             );
//             $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
// 
// 
//             $template = $this->cObj->fileResource($conf['template.']['havealook']);
//             $template = $this->cObj->getSubpart($template, "###HAVEALOOK_BEGIN###");
//             $marker = Array(
//                 '###ACTION###'					=> '',
//                 '###LABEL_HAVEALOOK###'			=> $this->pi_getLL('havealook.title'),
//                 '###LABEL_OPTIONS###'			=> $this->pi_getLL('favorites.options'),
//                 '###LABEL_TOPICNAME###'			=> $this->pi_getLL('topic.title'),
//                 '###LABEL_CONFIRMMULTIPLE###'	=> $this->pi_getLL('havealook.confirmMultiple')
//             ); 
//             $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
// 
//             $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
//                 "t.*,t.solved,t.topic_is,m.*,f.sorting as forum_order,c.sorting as cat_order,t.topic_last_post_id",
//                 "tx_mmforum_forummail m, tx_mmforum_topics t, tx_mmforum_forums f, tx_mmforum_forums c",
//                 "t.uid = m.forum_id AND
//                  m.user_id = ".$GLOBALS['TSFE']->fe_user->user['uid']." AND
//                  f.uid = t.forum_id AND
//                  c.uid = f.parentID AND
//                  m.deleted = 0 ".
//                  $this->getMayRead_forum_query('f').
//                  $this->getMayRead_forum_query('c'),
//                 "",
//                 $order
//             );
//             
//             $template = $this->cObj->fileResource($conf['template.']['havealook']);
// 
//             if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) {
//                 $template = $this->cObj->getSubpart($template, '###LIST_HAVEALOOK_EMPTY###');
//                 $marker = array(
//                     '###LLL_HAVEALOOK_EMPTY###'     => $this->pi_getLL('havealook.empty')
//                 );
//                 $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
//             }
//             else {
//                 $template = $this->cObj->getSubpart($template, "###LIST_HAVEALOOK###");
//                 while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
//                     $res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
//                         't.topic_title, f.forum_name, c.forum_name AS cat_title',
//                         'tx_mmforum_topics t, tx_mmforum_forums f, tx_mmforum_forums c',
//                         't.uid="'.$row['forum_id'].'" AND
//                          f.uid=t.forum_id AND
//                          c.uid=f.parentID AND
//                          c.parentID=0 AND
//                          t.deleted=0 AND
//                          t.hidden=0 '.
//                          $this->getMayRead_forum_query('f').
//                          $this->getMayRead_forum_query('c')
//                     );
// 
//                     if($GLOBALS['TYPO3_DB']->sql_num_rows($res2)>0) {
//                         $data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res2);
//                         $imgInfo['src'] = $conf['path_img'].$conf['images.']['solved'];
//                         $imgInfo['alt'] = $this->pi_getLL('topic.isSolved');
//                         $solved = ($row['solved']==1)? $this->imgtag($imgInfo) : '';
//                         $prefix = ($row['topic_is'])?$this->cObj->wrap($row['topic_is'],$this->conf['list_topics.']['prefix_wrap']):'';
// 
//                         $marker['###TOPIC_CHECKBOX###'] = '<input type="checkbox" name="tx_mmforum_pi1[fav_delete][]" value="'.$row['forum_id'].'" />';
//                         $linkParams[$this->prefixId] = array(
//                             'action'  => 'list_post',
//                             'tid'     => $row['forum_id']
//                         );
//                         $marker['###TOPICNAME###'] = $prefix.$this->pi_linkToPage($data['topic_title'],$conf['pid_forum'],'',$linkParams).$solved;
//                         $marker['###TOPICSUB###'] = $data['cat_title'].' / '.$data['forum_name'];
//                         $marker['###TOPICICON###'] = $this->getTopicIcon($row);
//                         
//                         $linkParams[$this->prefixId] = array(
//                             'action'  => 'havealook',
//                             'deltid'  => $row['forum_id']
//                         );
//                         $marker['###TOPICDELLINK###'] = $this->pi_linkTP($this->pi_getLL('havealook.delete'),$linkParams);
// 
//                         $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
//                     }
//                 }
//             }
//             $template = $this->cObj->fileResource($conf['template.']['havealook']);
//             $template = $this->cObj->getSubpart($template, "###HAVEALOOK_END###");
//             
//             $marker = array(
//                 '###LABEL_MARKEDTOPICS###'      => $this->pi_getLL('havealook.markedTopics'),
//                 '###LABEL_DELETE###'            => $this->pi_getLL('havealook.delete'),
//                 '###LABEL_GO###'                => $this->pi_getLL('havealook.go')
//             );
//             $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
// 
// 
//         } else {
//             $template = $this->cObj->fileResource($conf['template.']['login_error']);
//             $template = $this->cObj->getSubpart($template, "###LOGINERROR###");
//             $marker = array();
//             $marker['###LOGINERROR_MESSAGE###'] = $this->pi_getLL('subscr.noLogin');
//             $content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
//         }
// 
//         return $content;
//     }
}


if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_havealookforum.php"])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_havealookforum.php"]);
}

?>
