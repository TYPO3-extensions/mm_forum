<?php
/*
 *  Copyright notice
 *
 *  (c) 2007 Nepa Design <extensions@nepa-design.de>
 *  (c) 2007 Mittwald CM Service
 *  All rights reserved
 *
 *  This script is part of the Typo3 project. The Typo3 project is
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
 *   70: class tx_mmforum_chcimport
 *   82:     function main($content)
 *  116:     function select_import()
 *  157:     function import_chc()
 *  187:     function chc_updateRels()
 *  233:     function chc_importCategories()
 *  283:     function chc_importConferences()
 *  333:     function chc_importTopics()
 *  385:     function chc_importPosts()
 *  448:     function convertGroups($groups)
 *  483:     function convertUser($user)
 *  494:     function clearDB()
 *  516:     function import_chc_deprecated()
 *  700:     function import_cwt()
 *  769:     function chc_getConferenceLastPost($conf_uid)
 *  785:     function chc_getTopicLastPost($topic_id)
 *  801:     function chc_getTopicFirstPost($topic_id)
 *  817:     function chc_getTopicReplyCount($topic_id)
 *  832:     function chc_getConferencePostCount($conf_uid)
 *  848:     function chc_getConferenceTopicCount($conf_uid)
 *
 * TOTAL FUNCTIONS: 19
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * This class manages the data import from the chc_forum and
 * cwt_community extension.
 * The CHC import was rewritten for version 0.1.3 due to unreliability
 * of previous version.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Nepa Design <extensions@nepa-design.de>
 * @copyright  2007 Mittwald CM Service
 * @copyright  2007 Nepa Design
 * @version    2007-10-08
 * @package    mm_forum
 * @subpackage Backend
 */
class tx_mmforum_chcimport {

    var $chc_clearTables = 'forums,posts,postread,posts_text,topics,favorites,post_alert,searchresults,wordlist,wordmatch,attachments,polls,polls_answers,polls_votes,postqueue';

    /**
     * Main function.
     * @author Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-03
     */
    function main($content) {
        $this->importVars = t3lib_div::_GP('tx_mmforum_chc');

        $this->ext_db = $this->dbObj->link;
        $this->loc_db = $GLOBALS['TYPO3_DB']->link;

        if(is_array($this->importVars['import'])) {

            foreach($this->importVars['import'] as $import) {

                $content .= '<fieldset><legend>'.$GLOBALS['LANG']->getLL('chc.step4').'</legend>';

                $conf        = $this->p->confArr;
                $this->pid   = $conf['forumPID'];

                if($import == 'chc')        $content .= $this->import_chc();
                if($import == 'cwt')        $content .= $this->import_cwt();

                $content .= '</fieldset>';
            }

        }
        else $content .= $this->select_import();

        return $content;
    }

    /**
     * Displays a form for the user to select which data is to be imported.
     * @author Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-03
     */
    function select_import() {
        global $LANG;

        $chc_enabled = t3lib_extMgm::isLoaded('chc_forum')?'checked="checked"':'disabled="disabled"';
        $cwt_enabled = t3lib_extMgm::isLoaded('cwt_community')?'checked="checked"':'disabled="disabled"';

        $content .= '<fieldset><legend>'.$LANG->getLL('chc.step3').'</legend>';
        $content .= '<table cellspacing="0" cellpadding="2" border="0">
    <tr>
        <td style="width:32px"><input type="checkbox" name="tx_mmforum_chc[import][]" value="chc" '.$chc_enabled.' /></td>
        <td '.(t3lib_extMgm::isLoaded('chc_forum')?'':'style="color:#808080;"').'><strong>'.$LANG->getLL('chc.import.chc').'</strong><br />'.$LANG->getLL('chc.import.chc.desc').'</td>
    </tr>
    <tr>
        <td style="width:32px"><input type="checkbox" name="tx_mmforum_chc[import][]" value="cwt" '.$cwt_enabled.' /></td>
        <td '.(t3lib_extMgm::isLoaded('cwt_community')?'':'style="color:#808080;"').'><strong>'.$LANG->getLL('chc.import.cwt').'</strong><br />'.$LANG->getLL('chc.import.cwt.desc').'</td>
    </tr>
</table>
<br /><input type="submit" value="'.$LANG->getLL('chc.import.continue').'" />
</fieldset>';

        return $content;
    }

    /**
     * Imports data from the CHC Forum extension.
     * This function was written as substitute for the CHC import
     * function provided by Nepa Design that proved to be insufficient
     * for some CHC Forum versions. The new CHC import procedure is
     * conform to TYPO3 coding guidelines and should provide a better
     * performance.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-10-08
     * @return  void
     * @uses    clearDB
     * @uses	chc_importCategories
     * @uses	chc_importConferences
     * @uses	chc_importTopics
     * @uses	chc_importPosts
     * @uses	chc_updateRels
     */
    function import_chc() {
    	// Clear mm_forum database
    		$this->clearDB();

    	// Import CHC categories
    		$this->chc_importCategories();
    	// Import CHC conferences
    		$this->chc_importConferences();
    	// Import CHC topics
    		$this->chc_importTopics();
    	// Import CHC posts
    		$this->chc_importPosts();

    	// Update database relations
    		$this->chc_updateRels();
    }

    /**
     * Updates relations between imported mm_forum records.
     * This function updates database relations between imported mm_forum
     * records. This for example means the forum records pointing to
     * the post that was last written in this forum. Since the record's
     * indices will have changed during the import procedure these relations
     * have to be updated.
     * This function also resets all users' post counter.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-10-08
     * @return  void
     */
    function chc_updateRels() {

    	// Update database relations
	    	foreach($this->updateRel as $updateRel) {
	    		list($table,$uid,$field,$mapping,$value) = explode(':',$updateRel);

	    		switch($mapping) {
	    			case 'post': $mappingArr = &$this->postMapping; break;
	    		}

	    		$updateArray = array(
	    			$field			=> $mappingArr[$value]
	    		);
	    		$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
	    			'tx_mmforum_'.$table,
	    			'uid='.$uid,
	    			$updateArray
	    		);
	    	}

    	// Reset users' post counter
	    	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
	    		'u.uid, COUNT(p.uid) AS posts',
	    		'fe_users u, tx_mmforum_posts p',
	    		'p.poster_id = u.uid AND p.deleted = 0',
	    		'p.poster_id'
	    	);
	    	list($user, $posts) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
	    	$updateArray = array(
	    		'tstamp'			=> time(),
	    		'tx_mmforum_posts'	=> $posts
	    	);
	    	$GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users','uid='.$user.' AND pid='.$this->p->confArr['userPID'],$updateArray);
    }

    /**
     * Imports CHC Categories.
     * This function imports categories from the CHC Forum extension
     * into the tx_mmforum_forum table of the mm_forum extension.
     * There is no data loss during the import procedure.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-10-08
     * @return  void
     * @uses	convertGroups
     */
    function chc_importCategories() {

    	$res		= $this->dbObj->exec_SELECTquery(
    		'*',
    		'tx_chcforum_category',
    		'deleted=0'
    	);
    	while($ctg = $this->dbObj->sql_fetch_assoc($res)) {

    		$insertArray = array(
    			'pid'				=> $this->pid,
    			'hidden'			=> $ctg['hidden'],
    			'tstamp'			=> time(),
    			'crdate'			=> time(),
    			'forum_name'		=> $ctg['cat_title'],
    			'forum_desc'		=> $ctg['cat_description'],
    			'sorting'			=> $ctg['sorting'],
    			'grouprights_read'	=> $this->convertGroups($ctg['auth_forumgroup_r']),
    			'grouprights_write'	=> $this->convertGroups($ctg['auth_forumgroup_w']),
    			'parentID'			=> 0
    		);
    		$GLOBALS['TYPO3_DB']->exec_INSERTquery(
    			'tx_mmforum_forums',
				$insertArray
    		);
    		$ctgUid		= $GLOBALS['TYPO3_DB']->sql_insert_id();

    		$this->categoryMapping[$ctg['uid']] = $ctgUid;

    	}
    }

    /**
     * Imports CHC Conferences.
     * This function imports conferences from the CHC Forum extension
     * into the tx_mmforum_forum table of the mm_forum extension.
     * CHC conferences are equivalent to mm_forum forum records contained
     * in the second level of the forum tree.
     * During the import procedure, there is a slight data loss regarding
     * user rights management that is more differentiated in the CHC Forum
     * extension.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-10-08
     * @return  void
     * @uses	convertGroups
     * @uses	chc_getConferencePostCount
     * @uses	chc_getConferenceTopicCount
     * @uses	chc_getConferenceLastPost
     */
    function chc_importConferences() {

    	$res		= $this->dbObj->exec_SELECTquery(
    		'*',
    		'tx_chcforum_conference',
    		'deleted=0'
    	);
    	while($conf = $this->dbObj->sql_fetch_assoc($res)) {

    		$insertArray = array(
    			'pid'				=> $this->pid,
    			'hidden'			=> $conf['hidden'],
    			'sorting'			=> $conf['sorting'],
    			'tstamp'			=> time(),
    			'crdate'			=> time(),
    			'forum_name'		=> $conf['conference_name'],
    			'forum_desc'		=> $conf['desc'],
    			'grouprights_read'	=> $conf['conference_public_r']?'':$this->convertGroups($conf['auth_forumgroup_r']),
    			'grouprights_write'	=> $conf['conference_public_w']?'':$this->convertGroups($conf['auth_forumgroup_w']),
    			'parentID'			=> $this->categoryMapping[$conf['cat_id']],
    			'forum_posts'		=> $this->chc_getConferencePostCount($conf['uid']),
    			'forum_topics'		=> $this->chc_getConferenceTopicCount($conf['uid']),
    		);
    		$GLOBALS['TYPO3_DB']->exec_INSERTquery(
    			'tx_mmforum_forums',
    			$insertArray
    		);
    		$forumUid		= $GLOBALS['TYPO3_DB']->sql_insert_id();

    		$this->forumMapping[$conf['uid']] = $forumUid;

    		$this->updateRel[] = 'forums:'.$forumUid.':forum_last_post_id:post:'.$this->chc_getConferenceLastPost($conf['uid']);

    	}

    }

    /**
     * Imports CHC topics.
     * This function imports topics into the tx_mmforum_topics table.
     * There is no data loss during the import procedure.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-10-08
     * @return  void
     * @uses	convertUser
     * @uses	chc_getTopicReplyCount
     * @uses	chc_getTopicLastPost
     * @uses	chc_getTopicFirstPost
     */
    function chc_importTopics() {

    	$res		= $this->dbObj->exec_SELECTquery(
    		'*',
    		'tx_chcforum_thread',
    		'deleted=0'
    	);
    	while($topic = $this->dbObj->sql_fetch_assoc($res)) {

    		$insertArray = array(
    			'pid'				=> $this->pid,
    			'hidden'			=> $topic['hidden'],
    			'tstamp'			=> time(),
    			'crdate'			=> time(),
    			'topic_title'		=> $topic['thread_subject'],
    			'topic_poster'		=> $this->convertUser($topic['thread_author']),
    			'topic_time'		=> $topic['thread_datetime'],
    			'topic_views'		=> $topic['thread_views'],
    			'topic_replies'		=> $this->chc_getTopicReplyCount($topic['uid']),
    			'forum_id'			=> $this->forumMapping[$topic['conference_id']],
    			'closed_flag'		=> $topic['thread_closed']
    		);
    		$GLOBALS['TYPO3_DB']->exec_INSERTquery(
    			'tx_mmforum_topics',
    			$insertArray
    		);
    		$topicUid		= $GLOBALS['TYPO3_DB']->sql_insert_id();

    		$this->topicMapping[$topic['uid']] = $topicUid;

    		$this->updateRel[] = 'topics:'.$topicUid.':topic_last_post_id:post:'.$this->chc_getTopicLastPost($topic['uid']);
    		$this->updateRel[] = 'topics:'.$topicUid.':topic_first_post_id:post:'.$this->chc_getTopicFirstPost($topic['uid']);

    	}

    }

    /**
     * Imports CHC posts.
     * This function imports posts into the tx_mmforum_posts table.
     * During the import procedure, there is a data loss regarding file
     * attachments (are not yet imported at all) and posts written by
     * anonymous authors.
     *
     * TODO: Import file attachments
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-10-08
     * @return  void
     * @todo    Import file attachments
     * @usesconvertUser
     */
    function chc_importPosts() {

    	$res		= $this->dbObj->exec_SELECTquery(
    		'*',
    		'tx_chcforum_post',
    		'deleted=0'
    	);
    	while($post = $this->dbObj->sql_fetch_assoc($res)) {

    		$insertArray = array(
    			'pid'				=> $this->pid,
    			'tstamp'			=> time(),
    			'crdate'			=> time(),
    			'hidden'			=> $post['hidden'],
    			'topic_id'			=> $this->topicMapping[$post['thread_id']],
    			'forum_id'			=> $this->forumMapping[$post['conference_id']],
    			'poster_id'			=> $this->convertUser($post['post_author']),
    			'post_time'			=> $post['crdate'],
    			'edit_time'			=> $post['post_edit_tstamp'],
    			'edit_count'		=> $post['post_edit_count']
    		);
    		$GLOBALS['TYPO3_DB']->exec_INSERTquery(
    			'tx_mmforum_posts',
    			$insertArray
    		);
    		$postUid		= $GLOBALS['TYPO3_DB']->sql_insert_id();

    		$insertArray = array(
    			'pid'				=> $this->pid,
    			'tstamp'			=> time(),
    			'crdate'			=> time(),
    			'post_id'			=> $postUid,
    			'post_text'			=> $post['post_text']
    		);
    		$GLOBALS['TYPO3_DB']->exec_INSERTquery(
    			'tx_mmforum_posts_text',
    			$insertArray
    		);

    		$this->postMapping[$post['uid']] = $postUid;

    	}

    }

    /**
     * Converts CHC Forum groups into ordinary fe_groups.
     * This function converts a list of CHC forum groups into a list of the
     * fe_groups contained in this forum group. This is necessary since the
     * mm_forum extension - unlike the CHC Forum - works with the fe_groups
     * table directly.
     * There will be a slight data loss regarding specific users that are
     * members of a CHC group. These users will not be included in the result,
     * since in the mm_forum access rights are handled using fe_groups ONLY.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-10-08
     * @param   string $groups A commaseperated list of CHC forum group UIDs.
     * @return  string         The UIDs of the fe_groups contained in the CHC
     *                         forum groups submitted as parameter as commaseperated
     *                         list.
     */
    function convertGroups($groups) {
    	$groupArray = t3lib_div::intExplode(',',$groups);

    	$resultFeGroups = array();

    	foreach($groupArray as $group) {
    		$res = $this->dbObj->exec_SELECTquery(
    			'forumgroup_groups',
    			'tx_chcforum_forumgroup',
    			'uid='.$groups.' AND deleted=0'
    		);
    		if(!$res || !$this->dbObj->sql_num_rows($res)) continue;

    		list($feGroups) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

    		$feGroupArray = t3lib_div::intExplode(',',$feGroups);
    		foreach($feGroupArray as $feGroup) $resultFeGroups[] = $feGroup;
    	}
    	return implode(',',$resultFeGroups);
    }

    /**
     * Converts user UIDs.
     * This function is intended for handling the import of users
     * between different TYPO3 databases. Currently, this is only a
     * dummy function that returns the same UID that is submitted to it.
     * However, this function will be necessary for importing CHC Forum
     * data from one database to another where user UIDs are not constant
     * any more.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-10-08
     * @param   int $user The fe_user UID
     * @return  int       The fe_user UID
     */
    function convertUser($user) {
    	return $user;
    }

    /**
     * Clears the database before commiting the import procedure.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-10-08
     * @return  void
     */
    function clearDB() {
    	$clearTables_array = t3lib_div::trimExplode(',',$this->chc_clearTables);

    	foreach($clearTables_array as $clearTable) {
    		$GLOBALS['TYPO3_DB']->sql_query("TRUNCATE TABLE tx_mmforum_$clearTable");
    	}

    	$updateArray = array(
    		'tx_mmforum_posts'  => 0,
    		'tstamp'			=> 0
    	);
    	$GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users','pid='.$this->p->confArr['userPID'],$updateArray);
    }

    /**
     * Conducts the data import from the CHC Forum extension.
     *
     * @author  Nepa Design <extensions@nepa-design.de>
     * @version 2007-05-03
     * @deprecated Was replaced by a better import procedure in version 0.1.4
     */
    function import_chc_deprecated() {
        mysql_query('TRUNCATE TABLE tx_mmforum_forums');
	    mysql_query('TRUNCATE TABLE tx_mmforum_posts');
	    mysql_query('TRUNCATE TABLE tx_mmforum_postread');
	    mysql_query('TRUNCATE TABLE tx_mmforum_posts_text');
	    mysql_query('TRUNCATE TABLE tx_mmforum_topics');

	    $anz_cat = $anz_forum = $anz_threads = $anz_posts = 0;

        $pid = $this->pid;

	    //FORUM KATEGORIE
	    $sql = 'SELECT * FROM tx_chcforum_category';
	    $query=mysql_query($sql,$this->ext_db);
	    while($res=mysql_fetch_array($query)) {

		    $sql_insert = 'INSERT INTO tx_mmforum_forums SET
				    uid = '.$res['uid'].',
				    pid = '.$pid.',
				    deleted = '.$res['deleted'].',
				    hidden = '.$res['hidden'].',
				    tstamp = '.$res['tstamp'].',
				    crdate = '.$res['crdate'].',

				    forum_name = "'.$res['cat_title'].'"
		    ';
		    mysql_query($sql_insert,$this->loc_db);

		    $anz_cat++;
	    }

	    //FORUM KONFERENZ
	    $sql = 'SELECT
                    *,
                    w.forumgroup_groups as group_write,
                    r.forumgroup_groups as group_read
                FROM
                    tx_chcforum_conference,
                    tx_chcforum_forumgroup w,
                    tx_chcforum_forumgroup r
                WHERE
                    w.uid = tx_chcforum_conference.auth_forumgroup_w AND
                    r.uid = tx_chcforum_conference.auth_forumgroup_r';
	    $query=mysql_query($sql,$this->ext_db);
	    while($res=mysql_fetch_array($query)) {

		    $anz_forum++;

		    $sql_insert = 'INSERT INTO tx_mmforum_forums SET
				    pid = '.$pid.',
				    tstamp = '.$res['tstamp'].',
				    crdate = '.$res['crdate'].',
				    hidden = '.$res['hidden'].',
				    parentID = '.$res['cat_id'].',
				    deleted = '.$res['deleted'].',
				    grouprights_read = "'.$res['group_read'].'",
			  	    grouprights_write = "'.$res['group_write'].'",
				    forum_name = "'.$res['conference_name'].'",
				    forum_desc = "'.$res['conference_desc'].'"
		    ';

		    mysql_query($sql_insert,$this->loc_db);

		    $conference_id=$res['uid'];
		    $forum_uid = mysql_insert_id($this->loc_db);

		    $topics = 0;
		    $posts_ges = 0;

		    //FORUM THREADS ERZEUGEN
		    $sql_topic = 'SELECT * FROM tx_chcforum_thread WHERE conference_id='.$conference_id;
		    $query_topic=mysql_query($sql_topic,$this->ext_db);
		    while($res_topic=mysql_fetch_array($query_topic)) {

			    $sql_insert = 'INSERT INTO tx_mmforum_topics SET
				    uid = '.$res_topic['uid'].',
				    pid = '.$pid.',
				    tstamp = '.$res_topic['tstamp'].',
				    crdate = '.$res_topic['crdate'].',
				    deleted = '.$res_topic['deleted'].',
				    hidden = '.$res_topic['hidden'].',
				    forum_id = '.$forum_uid.',
				    closed_flag = '.$res_topic['thread_closed'].',
				    topic_title  = "'.$res_topic['thread_subject'].'",
				    topic_poster = '.$res_topic['thread_author'].',
				    cruser_id = '.$res_topic['thread_author'].',
				    topic_time  = '.$res_topic['thread_datetime'].',
				    topic_last_post_id = '.$res_topic['thread_lastpostid'].',
				    topic_first_post_id = '.$res_topic['thread_firstpostid'].'
			    ';

			    mysql_query($sql_insert,$this->loc_db);
			    $topics++;

			    $anz_threads++;

			    $posts = -1;

			    //Forum Posts erzeugen
			    $sql_post = 'SELECT * FROM tx_chcforum_post WHERE thread_id='.$res_topic['uid'];
			    $query_post=mysql_query($sql_post,$this->ext_db);
			    while($res_post=mysql_fetch_array($query_post)) {
				    $sql_insert = 'INSERT INTO tx_mmforum_posts SET
					    uid = '.$res_post['uid'].',
					    pid = '.$pid.',
					    tstamp = '.$res_post['tstamp'].',
					    crdate = '.$res_post['crdate'].',
					    deleted = '.$res_post['deleted'].',
					    hidden = '.$res_post['hidden'].',
					    forum_id = '.$forum_uid.',
					    topic_id  = '.$res_topic['uid'].',
					    cruser_id = '.$res_post['post_author'].',
					    poster_id = '.$res_post['post_author'].',
					    poster_ip = "'.$res_post['post_author_ip'].'",
					    post_time  = '.$res_post['crdate'].',
					    edit_time  = '.$res_post['post_edit_tstamp'].',
					    edit_count  = '.$res_post['post_edit_count'].'
				    ';
				    mysql_query($sql_insert,$this->loc_db);

				    $sql_insert = 'INSERT INTO tx_mmforum_posts_text SET
					    uid = '.$res_post['uid'].',
					    pid = '.$pid.',
					    tstamp = '.$res_post['tstamp'].',
					    crdate = '.$res_post['crdate'].',
					    deleted = '.$res_post['deleted'].',
					    hidden = '.$res_post['hidden'].',
					    post_id  = '.$res_post['uid'].',
					    post_text  = "'.mysql_real_escape_string($res_post['post_text']).'"
				    ';
				    mysql_query($sql_insert,$this->loc_db);
				    $posts++;

				    $anz_posts++;
			    }

			    $posts_ges = $posts_ges + $posts + 1;

			    $sql_update = 'UPDATE tx_mmforum_topics SET topic_replies = '.$posts.', topic_views = '.$posts.' WHERE uid='.$res_topic['uid'];
			    mysql_query($sql_update,$this->loc_db);

		    }

		    $sql_last = 'SELECT topic_last_post_id FROM tx_mmforum_topics WHERE forum_id='.$forum_uid.' ORDER BY crdate DESC LIMIT 1';
		    $query_last=mysql_query($sql_last,$this->loc_db);
		    $res_last=mysql_fetch_array($query_last);


		    $sql_update = 'UPDATE tx_mmforum_forums SET forum_posts = '.$posts_ges.', forum_last_post_id='.$res_last['topic_last_post_id'].' , forum_topics = '.$topics.' WHERE uid='.$forum_uid;
		    mysql_query($sql_update,$this->loc_db);
	    }

	    //Benutzer die im Forum gepostet haben auslesen
	    $sql = 'SELECT poster_id FROM  tx_mmforum_posts WHERE deleted=0 AND hidden = 0 GROUP BY poster_id';
	    $query=mysql_query($sql,$this->loc_db);
	    while($res=mysql_fetch_array($query)) {

		    //Anzahl der Postings pro Benutzer auslesen
		    $sql_count = 'SELECT COUNT(uid) AS anzahl FROM  tx_mmforum_posts WHERE deleted=0 AND hidden=0 AND poster_id='.$res['poster_id'];
		    $query_count=mysql_query($sql_count,$this->loc_db);
		    $res_count=mysql_fetch_array($query_count);

		    //Benutzer mit der Anzahl der Postings Updaten
		    $sql = 'UPDATE fe_users SET tx_mmforum_posts='.$res_count['anzahl'].' WHERE uid='.$res['poster_id'];
		    mysql_query($sql,$this->loc_db);
	    }

	    $content.= '<strong>'.$GLOBALS['LANG']->getLL('chc.success').'</strong><br/>';
	    $content.= '<br />'.$GLOBALS['LANG']->getLL('chc.categories').': '.$anz_cat;
	    $content.= '<br />'.$GLOBALS['LANG']->getLL('chc.boards').': '.$anz_forum;
	    $content.= '<br />'.$GLOBALS['LANG']->getLL('chc.topics').': '.$anz_threads;
	    $content.= '<br />'.$GLOBALS['LANG']->getLL('chc.posts').': '.$anz_posts;
        $content.= '<br />';

        return $content;
    }

    /**
     * Conducts the data import from the CWT community extension.
     *
     * @author  Nepa Design <extensions@nepa-design.de>
     * @version 2007-05-03
     */
    function import_cwt() {
        mysql_query('TRUNCATE TABLE tx_mmforum_pminbox');

	    $anz_pm = 0;

        $pid = $this->pid;

	    //PM Importieren
	    $sql = 'SELECT *,(SELECT username FROM fe_users WHERE uid= tx_cwtcommunity_message.cruser_id) AS from_user, (SELECT username FROM fe_users WHERE uid= tx_cwtcommunity_message.fe_users_uid) AS to_user FROM tx_cwtcommunity_message';
	    $query=mysql_query($sql,$this->ext_db);
	    while($res=mysql_fetch_array($query)) {

		    if($res['status']>0)
			    $read = 'read_flg = 1,';
		    else
			    $read = '';

		    $sql_insert = 'INSERT INTO tx_mmforum_pminbox SET
				    pid = '.$pid.',
				    deleted = '.$res['deleted'].',
				    hidden = '.$res['hidden'].',
				    tstamp = '.$res['tstamp'].',
				    crdate = '.$res['crdate'].',
				    sendtime = '.$res['crdate'].',
				    cruser_id  = '.$res['cruser_id'].',
				    from_uid  = '.$res['cruser_id'].',
				    from_name = "'.mysql_real_escape_string($res['from_user']).'",
				    to_uid  = '.$res['fe_users_uid'].',
				    to_name = "'.mysql_real_escape_string($res['to_user']).'",
				    subject = "'.mysql_real_escape_string($res['subject']).'",
				    message = "'.mysql_real_escape_string($res['body']).'",
				    '.$read.'
				    mess_type = 0
		    ';
		    mysql_query($sql_insert,$this->loc_db);

		    $sql_insert = 'INSERT INTO tx_mmforum_pminbox SET
				    pid = '.$pid.',
				    deleted = '.$res['deleted'].',
				    hidden = '.$res['hidden'].',
				    tstamp = '.$res['tstamp'].',
				    crdate = '.$res['crdate'].',
				    sendtime = '.$res['crdate'].',
				    cruser_id  = '.$res['cruser_id'].',
				    from_uid  = '.$res['cruser_id'].',
				    from_name = "'.mysql_real_escape_string($res['to_user']).'",
				    to_uid  = '.$res['cruser_id'].',
				    to_name = "'.mysql_real_escape_string($res['from_user']).'",
				    subject = "'.mysql_real_escape_string($res['subject']).'",
				    message = "'.mysql_real_escape_string($res['body']).'",
				    mess_type = 1
		    ';
		    mysql_query($sql_insert,$this->loc_db);

		    $anz_pm++;
	    }

	    $content.= '<strong>'.$GLOBALS['LANG']->getLL('cwt.success').'</strong><br/>';
	    $content.= '<br />'.$GLOBALS['LANG']->getLL('cwt.pms').': '.$anz_pm.'<br />';

        return $content;
    }

    function chc_getConferenceLastPost($conf_uid) {

    	$res		= $this->dbObj->exec_SELECTquery(
    		'uid','tx_chcforum_post','conference_id='.$conf_uid.' AND deleted=0','','crdate DESC','1'
    	);
    	list($uid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
    	return $uid;

    }

    function chc_getTopicLastPost($topic_id) {

    	$res		= $this->dbObj->exec_SELECTquery(
    		'uid','tx_chcforum_post','thread_id='.$topic_id.' AND deleted=0','','crdate DESC','1'
    	);
    	list($uid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
    	return $uid;

    }

    function chc_getTopicFirstPost($topic_id) {

    	$res		= $this->dbObj->exec_SELECTquery(
    		'uid','tx_chcforum_post','thread_id='.$topic_id.' AND deleted=0','','crdate ASC','1'
    	);
    	list($uid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
    	return $uid;

    }

    function chc_getTopicReplyCount($topic_id) {

    	$res		= $this->dbObj->exec_SELECTquery(
    		'COUNT(*)','tx_chcforum_post','thread_id='.$topic_id.' AND deleted=0'
    	);
    	list($count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
    	return $count-1;
    }

    function chc_getConferencePostCount($conf_uid) {

    	$res		= $this->dbObj->exec_SELECTquery(
    		'COUNT(*)','tx_chcforum_post','conference_id='.$conf_uid.' AND deleted=0'
    	);
    	list($count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
    	return $count;

    }

    function chc_getConferenceTopicCount($conf_uid) {

    	$res		= $this->dbObj->exec_SELECTquery(
    		'COUNT(*)','tx_chcforum_thread','conference_id='.$conf_uid.' AND deleted=0'
    	);
    	list($count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
    	return $count;

    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_chcimport.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_chcimport.php']);
}
?>