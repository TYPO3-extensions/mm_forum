<?php
/*
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
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   71: class tx_mmforum_forumAdmin
 *   81:     function main($content)
 *  107:     function display_newForum()
 *  195:     function display_newCategory()
 *  274:     function display_editForum()
 *  368:     function display_editCategory()
 *  466:     function globalIncSorting($start,$amount=1,$parentId=0)
 *  480:     function getMaxSorting($parentId = 0)
 *  496:     function delete_category()
 *  524:     function delete_forum()
 *  549:     function delete_forumContents($fid)
 *  576:     function delete_forumIndex($fid)
 *  596:     function updateUserPosts()
 *  626:     function save_newForum()
 *  669:     function save_newCategory()
 *  710:     function save_editCategory()
 *  767:     function save_editForum()
 *  819:     function display_tree()
 *  907:     function getUserGroupAccess_field($fieldname,$value)
 *  952:     function getForumOrderField($row,$pid,$new=false,$sec='ctg')
 * 1007:     function getLL($key)
 * 1018:     function init()
 *
 * TOTAL FUNCTIONS: 21
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * This class handles the backend administration of categories and
 * message board. This class replaces the old administration module integrated
 * directly into the backend module.
 * The new administration class ensures a higher useability by displaying
 * the categories and boards in a tree structure and allowing to insert new
 * boards and categories at any place.
 *
 * @author Martin Helmich <m.helmich@mittwald.de>
 * @version 2008-05-16
 * @copyright 2008 Mittwald CM Service
 * @package mm_forum
 * @subpackage Backend
 */
class tx_mmforum_forumAdmin {

    /**
     * The main function.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-24
     * @param   string $content The content
     * @return  string          The administration module content.
     */
    function main($content) {

        $this->init();

            if($this->param['fid']=='new')  $rcontent = $this->display_newForum();
        elseif($this->param['cid']=='new')  $rcontent = $this->display_newCategory();
        elseif($this->param['fid'])         $rcontent = $this->display_editForum();
        elseif($this->param['cid'])         $rcontent = $this->display_editCategory();

        $content = '<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td style="width:33%" valign="top">'.$this->display_tree().'</td>
		<td style="width:8px;"><img src="'.$GLOBALS['BACK_PATH'].'/clear.gif" style="width:8px;" /></td>
        <td style="width:67%" valign="top">'.$rcontent.'</td>
</table>';

        return $content;
    }

    /**
     * Displays the form for creating a new message board.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-05-16
     * @return  string The form content
     *
     * @todo    Combine all form creation functions into a single method?
     */
    function display_newForum() {

        if($this->param['forum']) {
            if($this->param['forum']['save'] || $this->param['forum']['saveReturn']) {
                $errors = $this->save_newForum();
                if($this->param['forum']['saveReturn']) {
                    $this->param['cid'] = $this->param['forum']['parentID'];
                    unset($this->param['forum']);
                }
                elseif(!$errors)
                    return;
            }
            elseif($this->param['forum']['back']) {
                unset($this->param);
                return;
            }
        }

        $orderOptions = $this->getForumOrderField(array(),$this->param['cid'],true);
        $input_authRead = $this->getUserGroupAccess_field('[forum][authRead]',$this->param['forum']['authRead']);
        $input_authWrite = $this->getUserGroupAccess_field('[forum][authWrite]',$this->param['forum']['authWrite']);
        $input_authMod = $this->getUserGroupAccess_field('[forum][authMod]',$this->param['forum']['authMod']);

        /*$content = '<fieldset>
    <legend>'.$this->getLL('forum.new').'</legend>
    <table cellspacing="0" cellpadding="2" style="width:100%">*/

		$content = '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
	    <tr>
	        <td class="mm_forum-listrow_header" colspan="2" valign="top"><img src="img/forum-edit.png" style="vertical-align: middle; margin-right:8px;" />'.$this->getLL('forum.new').'</td>
	    </tr>
        <tr>
            <td>'.$this->getLL('forum.title').'</td>
            <td><input type="text" name="tx_mmforum_fadm[forum][title]" value="'.htmlspecialchars($this->param['forum']['title']).'" style="width:100%;" />'.$errors['title'].'</td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.desc').'</td>
            <td><input type="text" name="tx_mmforum_fadm[forum][desc]" value="'.htmlspecialchars($this->param['forum']['desc']).'" style="width:100%;" /></td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.hidden').'</td>
            <td>
                <input type="hidden" name="tx_mmforum_fadm[forum][hidden]" value="0" />
                <input type="checkbox" name="tx_mmforum_fadm[forum][hidden]" value="1" '.($this->param['forum']['hidden']?'checked="checked"':'').' />
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.order').'</td>
            <td>
                <select name="tx_mmforum_fadm[forum][order]">'.$orderOptions.'</select>
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.authRead').'</td>
            <td>
                '.$input_authRead.'
                '.$this->getLL('category.readAuth_note').'<br /><br />
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.authWrite').'</td>
            <td>
                '.$input_authWrite.'
                '.$this->getLL('category.writeAuth_note').'<br /><br />
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.authMod').'</td>
            <td>
                '.$input_authMod.'
                '.$this->getLL('category.modAuth_note').'<br /><br />
            </td>
        </tr>
    </table>
    <input type="hidden" value="new" name="tx_mmforum_fadm[fid]" />
    <input type="hidden" value="'.$this->param['cid'].'" name="tx_mmforum_fadm[forum][parentID]" />
    <input type="submit" value="'.$this->getLL('save').'" name="tx_mmforum_fadm[forum][save]" />
    <input type="submit" value="'.$this->getLL('saveAndReturn').'" name="tx_mmforum_fadm[forum][saveReturn]" />
    <input type="submit" value="'.$this->getLL('back').'" name="tx_mmforum_fadm[forum][back]" />
</fieldset>
';

        return $content;
    }

    /**
     * Displays the form for creating a new category.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-05-16
     * @return  string The form content
     *
     * @todo    Combine all form creation functions into a single method?
     */
    function display_newCategory() {

        if($this->param['ctg']) {
            if($this->param['ctg']['save']) {
                $errors = $this->save_newCategory();
                if(!$errors)
                    return;
            }
            elseif($this->param['ctg']['back']) {
                unset($this->param);
                return;
            }
        }

        $orderOptions = $this->getForumOrderField(array(),0,true);
        $input_authRead = $this->getUserGroupAccess_field('[ctg][authRead]',$this->param['ctg']['authRead']);
        $input_authWrite = $this->getUserGroupAccess_field('[ctg][authWrite]',$this->param['ctg']['authWrite']);
        $input_authMod = $this->getUserGroupAccess_field('[ctg][authMod]',$this->param['ctg']['authMod']);

    /*    $content = '<fieldset>
    <legend>'.$this->getLL('category.new').'</legend>
    <table cellspacing="0" cellpadding="2" style="width:100%">*/

		$content = '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
	    <tr>
	        <td class="mm_forum-listrow_header" colspan="2" valign="top"><img src="img/forum-edit.png" style="vertical-align: middle; margin-right:8px;" />'.$this->getLL('category.new').'</td>
	    </tr>
        <tr>
            <td>'.$this->getLL('category.title').'</td>
            <td><input type="text" name="tx_mmforum_fadm[ctg][title]" value="'.htmlspecialchars($this->param['ctg']['title']).'" style="width:100%;" />'.$errors['title'].'</td>
        </tr>
        <tr>
            <td>'.$this->getLL('category.hidden').'</td>
            <td>
                <input type="hidden" name="tx_mmforum_fadm[ctg][hidden]" value="0" />
                <input type="checkbox" name="tx_mmforum_fadm[ctg][hidden]" value="1" '.($this->param['ctg']['hidden']?'checked="checked"':'').' />
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('category.order').'</td>
            <td>
                <select name="tx_mmforum_fadm[ctg][order]">'.$orderOptions.'</select>
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('category.authRead').'</td>
            <td>
                '.$input_authRead.'
                '.$this->getLL('category.readAuth_note').'<br /><br />
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('category.authWrite').'</td>
            <td>
                '.$input_authWrite.'
                '.$this->getLL('category.writeAuth_note').'<br /><br />
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('category.authMod').'</td>
            <td>
                '.$input_authMod.'
                '.$this->getLL('category.modAuth_note').'<br /><br />
            </td>
        </tr>
    </table>
    <input type="hidden" value="new" name="tx_mmforum_fadm[cid]" />
    <input type="hidden" value="'.$this->param['ctg']['parentID'].'" name="tx_mmforum_fadm[ctg][parentID]" />
    <input type="submit" value="'.$this->getLL('save').'" name="tx_mmforum_fadm[ctg][save]" />
    <input type="submit" value="'.$this->getLL('back').'" name="tx_mmforum_fadm[ctg][back]" />
</fieldset>
';

        return $content;
    }

    /**
     * Displays the form for editing an already existing forum.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-05-16
     * @return  string The form content
     *
     * @todo    Combine all form creation functions into a single method?
     */
    function display_editForum() {
        if($this->param['forum']) {
            if($this->param['forum']['save'])
                $errors = $this->save_editForum();
            elseif($this->param['forum']['back']) {
                unset($this->param);
                return;
            }
            elseif($this->param['forum']['delete']) {
                $this->delete_forum();
                return;
            }
        }

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_forums',
            'uid='.intval($this->param['fid']).' AND deleted=0'
        );
        if($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) return $this->display_tree();
        else $forum = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

        $orderOptions = $this->getForumOrderField($forum,$forum['parentID']);
        $input_authRead     = $this->getUserGroupAccess_field('[forum][authRead]',$forum['grouprights_read']);
        $input_authWrite    = $this->getUserGroupAccess_field('[forum][authWrite]',$forum['grouprights_write']);
        $input_authMod      = $this->getUserGroupAccess_field('[forum][authMod]',$forum['grouprights_mod']);

        /*$content = '<fieldset>
    <legend>'.$this->getLL('forum.edit').'</legend>
    <table cellspacing="0" cellpadding="2" style="width:100%">*/

		$content = '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
	    <tr>
	        <td class="mm_forum-listrow_header" colspan="2" valign="top"><img src="img/forum-edit.png" style="vertical-align: middle; margin-right:8px;" />'.$this->getLL('forum.edit').'</td>
	    </tr>
        <tr>
            <td>'.$this->getLL('forum.title').'</td>
            <td><input type="text" name="tx_mmforum_fadm[forum][title]" value="'.htmlspecialchars($forum['forum_name']).'" style="width:100%;" />'.$errors['title'].'</td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.desc').'</td>
            <td><input type="text" name="tx_mmforum_fadm[forum][desc]" value="'.htmlspecialchars($forum['forum_desc']).'" style="width:100%;" /></td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.hidden').'</td>
            <td>
                <input type="hidden" name="tx_mmforum_fadm[forum][hidden]" value="0" />
                <input type="checkbox" name="tx_mmforum_fadm[forum][hidden]" value="1" '.($forum['hidden']?'checked="checked"':'').' />
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.order').'</td>
            <td>
                <select name="tx_mmforum_fadm[forum][order]">'.$orderOptions.'</select>
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.authRead').'</td>
            <td>
                '.$input_authRead.'
                '.$this->getLL('category.readAuth_note').'<br /><br />
                '.$this->getLL('forum.updateIndex').'
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.authWrite').'</td>
            <td>
                '.$input_authWrite.'
                '.$this->getLL('category.writeAuth_note').'<br /><br />
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('forum.authMod').'</td>
            <td>
                '.$input_authMod.'
                '.$this->getLL('category.modAuth_note').'<br /><br />
            </td>
        </tr>
    </table>
    <div style="float:right;">'.$this->p->getItemFromRecord('tx_mmforum_forums',$forum).'</div>
    <input type="hidden" value="'.$forum['uid'].'" name="tx_mmforum_fadm[fid]" />
    <input type="hidden" value="'.$forum['parentID'].'" name="tx_mmforum_fadm[forum][parentID]" />
    <input type="submit" value="'.$this->getLL('save').'" name="tx_mmforum_fadm[forum][save]" />
    <input type="submit" value="'.$this->getLL('back').'" name="tx_mmforum_fadm[forum][back]" />
    <input type="submit" value="'.$this->getLL('delete').'" name="tx_mmforum_fadm[forum][delete]" onclick="return confirm(\''.$this->getLL('category.confirmDelete').'\');" />
</fieldset>
';

        return $content;
    }

    /**
     * Displays the form for editing an already existing category.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-05-16
     * @return  string The form content
     *
     * @todo    Combine all form creation functions into a single method?
     */
    function display_editCategory() {

        if($this->param['ctg']) {
            if($this->param['ctg']['save'])
                $errors = $this->save_editCategory();
            elseif($this->param['ctg']['back']) {
                unset($this->param);
                return;
            }
            elseif($this->param['ctg']['delete']) {
                $this->delete_category();
                return;
            }
        }

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_forums',
            'uid='.intval($this->param['cid']).' AND deleted=0'
        );
        if($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) return $this->display_tree();
        else $ctg = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

        $orderOptions = $this->getForumOrderField($ctg,0);
        $input_authRead = $this->getUserGroupAccess_field('[ctg][authRead]',$ctg['grouprights_read']);
        $input_authWrite = $this->getUserGroupAccess_field('[ctg][authWrite]',$ctg['grouprights_write']);
        $input_authMod = $this->getUserGroupAccess_field('[ctg][authMod]',$ctg['grouprights_mod']);

        /*$content = '<fieldset>
    <legend>'.$this->getLL('category.edit').'</legend>
    <table cellspacing="0" cellpadding="2" style="width:100%">*/

		$content = '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
	    <tr>
	        <td class="mm_forum-listrow_header" colspan="2" valign="top"><img src="img/forum-edit.png" style="vertical-align: middle; margin-right:8px;" />'.$this->getLL('category.edit').'</td>
	    </tr>
        <tr>
            <td>'.$this->getLL('category.title').'</td>
            <td><input type="text" name="tx_mmforum_fadm[ctg][title]" value="'.htmlspecialchars($ctg['forum_name']).'" style="width:100%;" />'.$errors['title'].'</td>
        </tr>
        <tr>
            <td>'.$this->getLL('category.hidden').'</td>
            <td>
                <input type="hidden" name="tx_mmforum_fadm[ctg][hidden]" value="0" />
                <input type="checkbox" name="tx_mmforum_fadm[ctg][hidden]" value="1" '.($ctg['hidden']?'checked="checked"':'').' />
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('category.order').'</td>
            <td>
                <select name="tx_mmforum_fadm[ctg][order]">'.$orderOptions.'</select>
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('category.authRead').'</td>
            <td>
                '.$input_authRead.'
                '.$this->getLL('category.readAuth_note').'<br /><br />
                '.$this->getLL('category.updateIndex').'
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('category.authWrite').'</td>
            <td>
                '.$input_authWrite.'
                '.$this->getLL('category.writeAuth_note').'
            </td>
        </tr>
        <tr>
            <td>'.$this->getLL('category.authMod').'</td>
            <td>
                '.$input_authMod.'
                '.$this->getLL('category.modAuth_note').'
            </td>
        </tr>
    </table>
    <div style="float:right;">'.$this->p->getItemFromRecord('tx_mmforum_forums',$ctg).'</div>
    <input type="hidden" value="'.$ctg['uid'].'" name="tx_mmforum_fadm[cid]" />
    <input type="hidden" value="'.$ctg['parentID'].'" name="tx_mmforum_fadm[ctg][parentID]" />
    <input type="submit" value="'.$this->getLL('save').'" name="tx_mmforum_fadm[ctg][save]" />
    <input type="submit" value="'.$this->getLL('back').'" name="tx_mmforum_fadm[ctg][back]" />
    <input type="submit" value="'.$this->getLL('delete').'" name="tx_mmforum_fadm[ctg][delete]" onclick="return confirm(\''.$this->getLL('category.confirmDelete').'\');" />
</fieldset>
';

        return $content;
    }

    /**
     * Increases the sorting value of other boards.
     * This function is needed for sorting entries. If a board is sorted
     * before some other boards, the sorting value of all these boards has
     * to be increased. This is done by this function.
     *
     * @param   int $start    The start sorting value. All sorting values above this
     *                        parameter will be increased.
     * @param   int $amount   The amount the sorting value is to be increased by.
     * @param   int $parentId The parent ID of the boards whose value is to be increased.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-24
     * @return  void
     */
    function globalIncSorting($start,$amount=1,$parentId=0) {
        $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_forums SET sorting = sorting + '.$amount.' WHERE sorting >= '.$start.' AND pid='.$this->pid.' AND deleted=0 AND parentID='.$parentId);
    }

    /**
     * Return the maximum sorting value.
     * This function is needed for sorting entries. If a record is inserted
     * as last entry in a list, it will get the maximum sorting value + 1.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-24
     * @param   int $parentId The parent ID of the records to be regarded in this process
     * @return  void
     */
    function getMaxSorting($parentId = 0) {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('MAX(sorting)','tx_mmforum_forums','pid='.$this->pid.' AND deleted=0 AND parentId='.$parentId);
        list($sorting) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        return $sorting;
    }

    /**
     * Deletes a category.
     * This function deletes a category, along with all subordinary boards and
     * all topics and posts contained in this category.
     * Furthermore, the post count of all users is updated.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-24
     * @return  void
     */
    function delete_category() {
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tx_mmforum_forums',
            'uid='.intval($this->param['cid']).' OR parentID='.intval($this->param['cid']),
            array('deleted' => 1)
        );
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid',
            'tx_mmforum_forums',
            'parentId='.intval($this->param['cid'])
        );
        while(list($fid)=$GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
            $this->delete_forumContents($fid);
            $this->delete_forumIndex($fid);
        }
        $this->updateUserPosts();
    }

    /**
     * Deletes a forum.
     * This function deletes a message board, along with all topics and posts
     * contained in this boards.
     * Furthermore, the post count of all users is updated.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-24
     * @return  void
     */
    function delete_forum() {
        $updateArray = array(
            'tstamp'        => time(),
            'deleted'       => 1
        );
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tx_mmforum_forums',
            'uid='.intval($this->param['fid']),
            $updateArray
        );
        $this->delete_forumContents(intval($this->param['fid']));
        $this->delete_forumIndex(intval($this->param['fid']));
        $this->updateUserPosts();
    }

    /**
     * Deletes all contents of a message board.
     * This function deletes all contents of a message board. This means
     * topics and posts.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-24
     * @param  int  $fid The id of the forum whose contents are to be deleted.
     * @return void
     */
    function delete_forumContents($fid) {
        $updateArray = array(
            'tstamp'        => time(),
            'deleted'       => 1
        );
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tx_mmforum_topics',
            'forum_id='.$fid,
            $updateArray
        );
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tx_mmforum_posts',
            'forum_id='.$fid,
            $updateArray
        );
    }

    /**
     * Deletes all search index entries regarding a certain forum.
     * This function deletes all entries of a certain forum from the
     * search index table.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-25
     * @param  int  $fid The id of the forum whose index is to be deleted.
     * @return void
     */
    function delete_forumIndex($fid) {
        $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_wordmatch', 'forum_id='.$fid);
        $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_searchresults', '1');
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tx_mmforum_topics',
            'forum_id='.$fid,
            array('tx_mmforumsearch_index_write' => 0)
        );
    }

    /**
     * Updates all users' post count.
     * This function updates the post count of all fe_users.
     * This is necessary in order to keep the post count of the fe_users
     * up-to-date after deleting message boards or even categories.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-24
     * @return  void
     */
    function updateUserPosts() {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid, tx_mmforum_posts',
            'fe_users',
            'deleted=0'
        );
        while(list($user_id,$posts) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
            $res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'COUNT(*)',
                'tx_mmforum_posts',
                'deleted=0 AND poster_id='.$user_id.' AND pid='.$this->pid
            );
            list($nPosts) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res2);

            if($posts == $nPosts) continue;
            $updateArray = array(
                'tstamp'            => time(),
                'tx_mmforum_posts'  => $nPosts
            );
            $GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users','uid='.$user_id,$updateArray);
        }
    }

    /**
     * Creates a new message board record ans stores it into the database.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-05-16
     * @return  void
     */
    function save_newForum() {
        $insertArray = array(
            'pid'               => $this->pid,
            'hidden'            => $this->param['forum']['hidden'],
            'tstamp'            => time(),
            'crdate'            => time(),
            'cruser_id'         => $GLOBALS['BE_USER']->user['uid'],
            'forum_name'        => trim($this->param['forum']['title']),
            'forum_desc'        => trim($this->param['forum']['desc']),
            'grouprights_read'  => $this->param['forum']['authRead'],
            'grouprights_write' => $this->param['forum']['authWrite'],
            'grouprights_mod'   => $this->param['forum']['authMod'],
            'parentID'          => $this->param['forum']['parentID'],
            'forum_posts'       => '0',
            'forum_topics'      => '0'
        );

        if(strlen($insertArray['forum_name'])==0) return array('title' => '<div class="mm_forum-fatalerror">'.$this->getLL('error.noTitle').'</div>');

        if($this->param['forum']['order'] == 'first') {
            $insertArray['sorting'] = 0;
            $this->globalIncSorting(0,1,$this->param['forum']['parentID']);
        }
        elseif($this->param['forum']['order'] == 'last')
            $insertArray['sorting'] = $this->getMaxSorting() + 1;
        else {
            $this->globalIncSorting($this->param['forum']['order'],2,$this->param['forum']['parentID']);
            $insertArray['sorting'] = $this->param['forum']['order']+1;
        }

        $GLOBALS['TYPO3_DB']->exec_INSERTquery(
            'tx_mmforum_forums',
            $insertArray
        );
    }

    /**
     * Creates a new category record and stores it into the database.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-05-16
     * @return  void
     */
    function save_newCategory() {
        $insertArray = array(
            'pid'               => $this->pid,
            'hidden'            => $this->param['ctg']['hidden'],
            'tstamp'            => time(),
            'crdate'            => time(),
            'cruser_id'         => $GLOBALS['BE_USER']->user['uid'],
            'forum_name'        => trim($this->param['ctg']['title']),
            'grouprights_read'  => $this->param['ctg']['authRead'],
            'grouprights_write' => $this->param['ctg']['authWrite'],
            'grouprights_mod'   => $this->param['ctg']['authMod'],
            'parentID'          => 0
        );

        if(strlen($insertArray['forum_name'])==0) return array('title' => '<div class="mm_forum-fatalerror">'.$this->getLL('error.noTitle').'</div>');

        if($this->param['ctg']['order'] == 'first') {
            $insertArray['sorting'] = 0;
            if($this->param['ctg']['sorting'] != 0)
                $this->globalIncSorting(0,1,$this->param['ctg']['parentID']);
        }
        elseif($this->param['ctg']['order'] == 'last')
            $insertArray['sorting'] = $this->getMaxSorting() + 1;
        else {
            $this->globalIncSorting($this->param['ctg']['order'],2,$this->param['ctg']['parentID']);
            $insertArray['sorting'] = $this->param['ctg']['order']+1;
        }

        $GLOBALS['TYPO3_DB']->exec_INSERTquery(
            'tx_mmforum_forums',
            $insertArray
        );
    }

    /**
     * Updates an already existing category record and stores it into the database.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-05-16
     * @return  void
     */
    function save_editCategory() {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_forums',
            'uid='.intval($this->param['cid'])
        );
        $ctg = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

        $updateArray = array(
            'tstamp'            => time(),
            'forum_name'        => trim($this->param['ctg']['title']),
            'grouprights_read'  => $this->param['ctg']['authRead'],
            'grouprights_write' => $this->param['ctg']['authWrite'],
            'grouprights_mod'   => $this->param['ctg']['authMod'],
            'hidden'            => $this->param['ctg']['hidden']
        );

        if(strlen($updateArray['forum_name'])==0) return array('title' => '<div class="mm_forum-fatalerror">'.$this->getLL('error.noTitle').'</div>');

        if($this->param['ctg']['order'] == 'first') {
            $updateArray['sorting'] = 0;
            if($ctg['sorting'] != 0)
                $this->globalIncSorting(0,1,$this->param['ctg']['parentID']);
        }
        elseif($this->param['ctg']['order'] == 'last')
            $updateArray['sorting'] = $this->getMaxSorting() + 1;
        else {
            if($this->param['ctg']['order'] != $ctg['sorting'])
                $this->globalIncSorting($this->param['ctg']['order'],2,$this->param['ctg']['parentID']);
            $updateArray['sorting'] = $this->param['ctg']['order'];
        }

        $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tx_mmforum_forums',
            'uid='.intval($this->param['cid']),
            $updateArray
        );

        if($updateArray['grouprights_read'] != $ctg['grouprights_read']) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid',
                'tx_mmforum_forums',
                'parentID='.$ctg['uid']
            );
            while(list($fid)=$GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
                $this->delete_forumIndex($fid);
            }
        }
    }

    /**
     * Updates an already existing board record and stores it into the database.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-05-16
     * @return  void
     */
    function save_editForum() {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_forums',
            'uid='.intval($this->param['fid'])
        );
        $ctg = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

        $updateArray = array(
            'tstamp'            => time(),
            'hidden'            => $this->param['forum']['hidden'],
            'forum_name'        => trim($this->param['forum']['title']),
            'forum_desc'        => trim($this->param['forum']['desc']),
            'grouprights_read'  => $this->param['forum']['authRead'],
            'grouprights_write' => $this->param['forum']['authWrite'],
            'grouprights_mod'   => $this->param['forum']['authMod'],
        );

        if(strlen($updateArray['forum_name'])==0) return array('title' => '<div class="mm_forum-fatalerror">'.$this->getLL('error.noTitle').'</div>');

        if($this->param['forum']['order'] == 'first') {
            $updateArray['sorting'] = 0;
            if($ctg['sorting'] != 0)
                $this->globalIncSorting(0,1,$this->param['forum']['parentID']);
        }
        elseif($this->param['forum']['order'] == 'last')
            $updateArray['sorting'] = $this->getMaxSorting() + 1;
        else {
            if($this->param['forum']['order'] != $ctg['sorting'])
                $this->globalIncSorting($this->param['forum']['order'],2,$this->param['forum']['parentID']);
            $updateArray['sorting'] = $this->param['forum']['order'];
        }

        $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tx_mmforum_forums',
            'uid='.intval($this->param['fid']),
            $updateArray
        );

        if($updateArray['grouprights_read'] != $ctg['grouprights_read']) $this->delete_forumIndex($ctg['uid']);
    }

    /**
     * Displays the forum tree.
     * This function displays the complete forum tree with all categories
     * and subordinate message boards. Furthermore, buttons allowing to
     * create new boards and categories are displayed.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-24
     * @return  string The forum tree content
     */
    function display_tree() {

        /*$content = '<fieldset>
    <legend>'.$this->getLL('tree').'</legend>
    <table width="100%" cellpadding="2" cellspacing="0">
';*/

		$content = '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td class="mm_forum-listrow_header" ><img src="img/forum.png" style="vertical-align: middle; margin-right:8px;" />'.$this->getLL('tree').'</td>
    </tr>';

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_forums',
            'parentID = 0 AND pid='.$this->pid.' AND deleted=0',
            '',
            'sorting ASC'
        );
        $i = 0;
        while($ctg = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $class_suffix = ($i++ % 2==0 ? '2' : '');

            if($this->param['cid'] == $ctg['uid'] && !$this->param['fid']) $class_suffix = '_active';

            $editOnClick = "location.href='index.php?SET[function]=".$this->func."&tx_mmforum_fadm[cid]=".$ctg['uid']."';";
			$js = 'onmouseover="this.className=\'mm_forum-listrow_active\'; this.style.cursor=\'pointer\';" onmouseout="this.className=\'mm_forum-listrow\'" onclick="'.htmlspecialchars($editOnClick).'"';

            $icon = '<img src="../icon_tx_mmforum_forums.gif" style="vertical-align: middle;" />';

            /*$content .= '<tr class="mm_forum-listrow'.$class_suffix.'" '.$js.'>
    <td>'.$icon.' '.htmlspecialchars($ctg['forum_name']).'</td>
</tr>';*/

			$content .= '<tr class="mm_forum-listrow" '.$js.'><td><img src="img/category.png" style="vertical-align: middle; margin-right:8px;" />'.htmlspecialchars($ctg['forum_name']).'</td></tr>';

            $res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'tx_mmforum_forums',
                'parentID = '.$ctg['uid'].' AND pid='.$this->pid.' AND deleted=0',
                '',
                'sorting ASC'
            );
            while($forum = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res2)) {
                $class_suffix = ($i++ % 2==0 ? '2' : '');

                if($this->param['fid'] == $forum['uid']) $class_suffix = '_active';

			    $editOnClick = "location.href='index.php?SET[function]=".$this->func."&tx_mmforum_fadm[fid]=".$forum['uid']."';";
			    $js = 'onmouseover="this.className=\'mm_forum-listrow_active\'; this.style.cursor=\'pointer\';" onmouseout="this.className=\'mm_forum-listrow\'" onclick="'.htmlspecialchars($editOnClick).'"';

                $icon = '<img src="img/forum.png" style="vertical-align: middle; margin-right:8px;" />';

                $content .= '<tr class="mm_forum-listrow" '.$js.'>
    <td style="padding-left:32px;">'.$icon.htmlspecialchars($forum['forum_name']).'</td>
</tr>';
            }
            $class_suffix = ($i++ % 2==0 ? '2' : '');

            if($this->param['cid'] == $ctg['uid'] && $this->param['fid']=='new') $class_suffix = '_active';

            $editOnClick = "location.href='index.php?SET[function]=".$this->func."&tx_mmforum_fadm[fid]=new&tx_mmforum_fadm[cid]=".$ctg['uid']."';";
			$js = 'onmouseover="this.className=\'mm_forum-listrow_active\'; this.style.cursor=\'pointer\';" onmouseout="this.className=\'mm_forum-listrow\'" onclick="'.htmlspecialchars($editOnClick).'"';

            $content .= '<tr class="mm_forum-listrow" '.$js.'>
    <td style="padding-left: 32px; font-style:italic;"><img src="img/forum-new.png" style="vertical-align:middle; margin-right:8px;" />['.$this->getLL('forum.new').']</td>
</tr>';
        }
        $class_suffix = ($i++ % 2==0 ? '2' : '');

        if($this->param['cid'] == 'new') $class_suffix = '_active';

        $editOnClick = "location.href='index.php?SET[function]=".$this->func."&tx_mmforum_fadm[cid]=new';";
		$js = 'onmouseover="this.className=\'mm_forum-listrow_active\'; this.style.cursor=\'pointer\';" onmouseout="this.className=\'mm_forum-listrow'.$class_suffix.'\'" onclick="'.htmlspecialchars($editOnClick).'"';

        $content .= '<tr class="mm_forum-listrow" '.$js.'>
        <td style="font-style:italic;"><img src="img/category-new.png" style="vertical-align:middle;" /> ['.$this->getLL('category.new').']</td>
</tr>';

        $content .= '</table></fieldset>';

        return $content;
    }

    /**
     * Generates a user group select field.
     * This function generates a user group selector field using the TYPO3-
     * internal form creation functions. See the documentation of t3lib/class.t3lib_tceforms.php
     * for more information.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-24
     * @param   string $fieldname The field name
     * @param   string $value     The field value
     * @return  string            The input field
     */
    function getUserGroupAccess_field($fieldname,$value) {
        $pa = array(
			'itemFormElName'		=> 'tx_mmforum_fadm'.$fieldname,
			'itemFormElValue'		=> $this->p->convertToTCEList($value,'fe_groups','title'),
			'fieldChangeFunc'		=> array(''),
			'fieldConf'				=> array(
				'config'				=> array(
					"type" => "select",
					"foreign_table" => "fe_groups",
					"foreign_table_where" => 'AND fe_groups.pid="'.$this->conf['userPID'].'"',
					"size" => 4,
					"minitems" => 0,
					"maxitems" => 100,
                    "itemListStyle" => 'width:150px;',
                    "selectedListStyle" => 'width:150px;'
				)
			)
		);

		$selectorBox = $this->p->tceforms->getSingleField_typeSelect('fe_groups','tx_mmforum_fadm'.$fieldname,array(),$pa);
        return $selectorBox;
    }

    /**
     * Generates a list of options for the sorting selector.
     * This function generates a list of HTML-option elements for the
     * sorting selector that is used in all category and message board
     * forms. The selector box consists of two general items ("as first" and
     * "as last", meaning that the new/edited item will either stand as
     * first or as last item in the list) and of some other items allowing
     * to sort this item in relation to other items.
     *
     * @param  array   $row The record of the board/category that is to be edited.
     * @param  int     $pid The record's parent ID. If case of a category this value is
     *                      0, otherwise it will be the UID of the category the record
     *                      belongs to.
     * @param  boolean $new TRUE, if this item is to be generated for a board/category
     *                      creation form, otherwise FALSE.
     * @param  string  $sec The parameter name to read. Has to be 'ctg' for categories and
     *                      'forum' for message boards.
     * @return string       A list of HTML-option objects.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-24
     */
    function getForumOrderField($row,$pid,$new=false,$sec='ctg') {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_forums',
            'parentID='.$pid.' AND pid='.$this->pid.' AND deleted=0 AND uid!='.intval($row['uid']),
            '',
            'sorting ASC'
        );
        $pos = 'beginning';

        while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $ctgs[] = $arr;
            if($row['sorting'] > $arr['sorting']) $pos = $arr['uid'];
        }

        $first_sel = ($row['sorting'] < $ctgs[0]['sorting'])?'selected="selected"':'';
        $last_sel = ($row['sorting'] >= $ctgs[count($ctgs)-1]['sorting'])?'selected="selected"':'';

        if($new) {
            $first_sel = '';
            $last_sel = 'selected="selected"';
        }

        if($this->param[$sec]['order'] == 'first') {
            $first_sel = 'selected="selected"';
            $last_sel = '';
        } elseif($this->param[$sec]['order'] == 'last') {
            $last_sel = 'selected="selected"';
            $first_sel = '';
        }

        $content = '<option value="first" '.$first_sel.'>'.$this->getLL('order.beginning').'</option>';
        $content .= '<option value="last" '.$last_sel.'>'.$this->getLL('order.ending').'</option>';

        if(count($ctgs)>0) {
            foreach($ctgs as $ctg) {
                $sel = ($pos == $ctg['uid'])?'selected="selected"':'';
                if($this->param[$sec]['order'])
                    $sel = ($this->param[$sec]['order']==($ctg['sorting']+1))?'selected="selected"':'';
                $content .= '<option value="'.($ctg['sorting']+1).'" '.$sel.'>'.$this->getLL('order.after').' '.$ctg['forum_name'].' '.$ctg['sorting'].'</option>';
            }
        }
        return $content;
    }

    /**
     * Gets a language variable from the locallang_forumadmin.xml file.
     * Wrapper function to simplify retrieval of language dependent
     * strings.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-23
     * @param   string $key The language string key
     * @return  string      The language string
     */
    function getLL($key) {
        return $GLOBALS['LANG']->getLL('forumAdmin.'.$key);
    }

    /**
     * Initializes the forum administration tool.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-23
     * @return  void
     */
    function init() {
        $this->param = t3lib_div::_GP('tx_mmforum_fadm');
        $this->conf = $this->p->config['plugin.']['tx_mmforum.'];
        $this->pid  = intval($this->conf['storagePID']);

        $this->func = $this->p->MOD_SETTINGS['function'];

        $GLOBALS['LANG']->includeLLFile('EXT:mm_forum/mod1/locallang_forumadmin.xml');
    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_forumadmin.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_forumadmin.php']);
}
?>