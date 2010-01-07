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
 *
 * This class provides a set of functions allowing it to create posts
 * and topics very easily (i.e. with a single function call). All other
 * necessary procedures are automatically done by the functions of this
 * class.
 * 
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    $Id$
 * @package    mm_forum
 * @subpackage Forum
 *
 */

class tx_mmforum_postfactory {





	/*
	 * INITIALIZATION
	 */





	/**
	 *
	 * Initializes the post factory.
	 * 
	 * @author  Martin Helmich
	 * @version 2007-07-21
	 * @param   array $conf The configuration array of the calling object.
	 * @return  void
	 *
	 */

	function init($conf, $parent = null) {
		require_once(t3lib_extMgm::extPath('mm_forum').'pi1/class.tx_mmforum_havealook.php');

		$this->conf = $conf;
		$this->parent = $parent;
	}





		/*
		 * TOPIC CREATION
		 */





		/**
		 *
		 * Stores a new topic into the postqueue.
		 * This function creates a new topic. The topic is not created directly but
		 * is inserted into the post queue instead.
		 *
		 * @author  Martin Helmich
		 * @version 2007-07-24
		 * @param   int     $forum_uid  The UID of the forum the new topic is to be created in
		 * @param   int     $author     The UID of the fe_user creating this topic
		 * @param   string  $subject    The topic's subject
		 * @param   string  $text       The topic's first post's text
		 * @param   int     $date       The date of topic creation as unix timestamp
		 * @param   string  $ip         The topic author's IP address
		 * @param   int     $attachment The UID of the attachment that is to be attached
		 *                              to the topic's first post. Set 0 for no attachment.
		 * @param   int     $poll       The UID of the poll that is to be attached to
		 *                              the topic. Set 0 for no poll.
		 * @param   boolean $subscribe  If TRUE, the new topic will be added to the
		 *                              author's topic subscriptions after saving.
		 * @param   boolean $noUpdate   Set to TRUE in order to prevent the database
		 *                              counters from being updated directly after
		 *                              creating this topic. Instead, the elements to be
		 *                              updated will be stored in an "update queue" and
		 *                              will be updated after all posts/topics have been
		 *                              created. This minimizes database load.
		 * @return  int/boolean         If topic creation was successfull, the topic's
		 *                              UID is returned, otherwise FALSE.
		 *
		 */

	function create_topic_queue($forum_uid, $author, $subject, $text, $date, $ip, $attachments = array(), $poll = 0, $subscribe = false, $noUpdate = false) {
		$insertArray = array(
			'pid'				=> $this->getFirstPid(),
			'tstamp'			=> time(),
			'crdate'			=> time(),
			'topic'				=> 1,
			'topic_forum'		=> $forum_uid,
			'topic_poll'		=> $poll,
			'topic_subscribe'	=> $subscribe?1:0,
			'topic_title'		=> $subject,
			'post_parent'		=> 0,
			'post_text'			=> $text,
			'post_user'			=> $author,
			'post_time'			=> $date,
			'post_ip'			=> $ip,
			'post_attachment'	=> (is_array($attachments) ? implode(',', $attachments) : ''),
		);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['postfactory']['insertPostqueue'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['postfactory']['insertPostqueue'] as $_classRef) {
				$_procObj = &t3lib_div::getUserObj($_classRef);
				$insertArray = $_procObj->processPostqueueInsertArray($insertArray, $this);
			}
		}

		// Insert data
		$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_postqueue', $insertArray);
	}



		/**
		 *
		 * Creates a new topic.
		 * This function creates a new topic. Also automatically creates the first posts
		 * of this topic and updates all database counters.
		 *
		 * @author  Martin Helmich
		 * @version 2009-02-05
		 * @param   int     $forum_uid  The UID of the forum the new topic is to be created in
		 * @param   int     $author     The UID of the fe_user creating this topic
		 * @param   string  $subject    The topic's subject
		 * @param   string  $text       The topic's first post's text
		 * @param   int     $date       The date of topic creation as unix timestamp
		 * @param   string  $ip         The topic author's IP address
		 * @param   int     $attachment The UID of the attachment that is to be attached to the topic's
		 *                              first post. Set 0 for no attachment.
		 * @param   int     $poll       The UID of the poll that is to be attached to the topic.
		 *                              Set 0 for no poll.
		 * @param   boolean $noUpdate   Set to TRUE in order to prevent the database counters from
		 *                              being updated directly after creating this topic. Instead,
		 *                              the elements to be updated will be stored in an "update queue"
		 *                              and will be updated after all posts/topics have been created.
		 *                              This minimizes database load.
		 * @return  int/boolean         If topic creation was successfull, the topic's UID is returned,
		 *                              otherwise FALSE.
		 *
		 */

	function create_topic($forumId, $author, $subject, $text, $date, $ip, $attachments = array(), $poll = 0, $subscribe = false, $noUpdate = false, $notifyForumSubscribers = true) {

		// Generate topic record
		$insertArray = array(
			'pid'          => $this->getFirstPid(),
			'tstamp'       => time(),
			'crdate'       => time(),
			'topic_title'  => $subject,
			'topic_poster' => $author,
			'topic_time'   => $date,
			'forum_id'     => $forumId,
			'poll_id'      => $poll
		);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['postfactory']['insertTopic'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['postfactory']['insertTopic'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$insertArray = $_procObj->processTopicInsertArray($insertArray, $this);
			}
		}

		// Insert topic record
		if (!$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_topics', $insertArray)) {
			return false;
		}

		// Retrieve topic uid
		$topicId = $GLOBALS['TYPO3_DB']->sql_insert_id();

		// Generate post record
		$postId = $this->create_post($topicId, $author, $text, $date, $ip, $attachments, $noUpdate);
		if ($postId === false) {
			$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_topics', 'uid = ' . $topicId);
			return false;
		}

		// Update first post record
		$updateData = array('topic_first_post_id' => $postId);
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics', 'uid = ' . $topicId, $updateData);



		// Subscribe the author to the topic
		if ($subscribe) {
			tx_mmforum_havealook::addSubscription($this->parent, $topicId, $author);
		}

		//added by Cyrill Helg
		// Send notification email to users who have subscribed the forum where this topic is created
		if ($notifyForumSubscribers)
			tx_mmforum_havealook::notifyForumSubscribers($topicId, $forumId, $this->parent);

		return $topicId;
	}





		/*
		 * POST CREATION
		 */





		/**
		 *
		 * Stores a new post into the postqueue.
		 * This function creates a new post. The post is not created directly but
		 * stored in the post queue instead.
		 *
		 * @author  Martin Helmich
		 * @version 2007-07-23
		 * @param   int     $topic_uid  The UID of the topic the new post is to be created in
		 * @param   int     $author     The UID of the fe_user creating this post
		 * @param   string  $text       The post's text
		 * @param   int     $date       The date of post creation as unix timestamp
		 * @param   string  $ip         The post author's IP address
		 * @param   int     $attachment The UID of the attachment that is to be attached to this post.
		 *                              Set 0 for no attachment.
		 * @param   boolean $noUpdate   Set to TRUE in order to prevent the database counters from
		 *                              being updated directly after creating this post. Instead,
		 *                              the elements to be updated will be stored in an "update queue"
		 *                              and will be updated after all posts/topics have been created.
		 *                              This minimizes database load.
		 * @return  int/boolean         If post creation was successfull, the post's UID is returned,
		 *                              otherwise FALSE.
		 *
		 */
	function create_post_queue($topicId, $author, $text, $date, $ip, $attachments = array()) {

		// Retrieve forum uid
		$forumId = $this->getForumUIDByTopic($topicId);
		if ($forumId === false) {
			return false;
		}
		
		// Insert post into post queue
		$insertArray = array(
			'pid'             => $this->getFirstPid(),
			'tstamp'          => time(),
			'crdate'          => time(),
			'topic'           => 0,
			'topic_forum'     => $forumId,
			'post_parent'     => $topicId,
			'post_text'       => $text,
			'post_user'       => $author,
			'post_time'       => $date,
			'post_ip'         => $ip,
			'post_attachment' => (is_array($attachments) ? implode(',', $attachments) : ''),
		);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['postfactory']['insertPostqueue'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['postfactory']['insertPostqueue'] as $_classRef) {
				$_procObj    = &t3lib_div::getUserObj($_classRef);
				$insertArray = $_procObj->processPostqueueInsertArray($insertArray);
			}
		}

		// Insert data
		$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_postqueue', $insertArray);	
	}



		/**
		 *
		 * Creates a new post.
		 * This function creates a new post. Also automatically updates all database counters.
		 *
		 * @author  Martin Helmich
		 * @version 2007-07-23
		 * @param   int     $topicId    The UID of the topic the new post is to be created in
		 * @param   int     $author     The UID of the fe_user creating this post
		 * @param   string  $text       The post's text
		 * @param   int     $date       The date of post creation as unix timestamp
		 * @param   string  $ip         The post author's IP address
		 * @param   int     $attachment The UID of the attachment that is to be attached to this post.
		 *                              Set 0 for no attachment.
		 * @param   boolean $noUpdate   Set to TRUE in order to prevent the database counters from
		 *                              being updated directly after creating this post. Instead,
		 *                              the elements to be updated will be stored in an "update queue"
		 *                              and will be updated after all posts/topics have been created.
		 *                              This minimizes database load.
		 * @return  int/boolean         If post creation was successfull, the post's UID is returned,
		 *                              otherwise FALSE.
		 *
		 */

	function create_post($topicId, $author, $text, $date, $ip, $attachments = array(), $noUpdate = false) {
		$author = intval($author);

		// Retrieve forum uid
		$forumId = $this->getForumUIDByTopic($topicId);
		if ($forumId === false) {
			return false;
		}

		// Generate post record
		$insertArray = array(
			'pid'        => $this->getFirstPid(),
			'tstamp'     => time(),
			'crdate'     => time(),
			'topic_id'   => $topicId,
			'forum_id'   => $forumId,
			'poster_id'  => $author,
			'post_time'  => $date,
			'poster_ip'  => $ip,
			'attachment' => (is_array($attachments) ? implode(',', $attachments) : ''),
		);
	
		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['postfactory']['insertPost'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['postfactory']['insertPost'] as $_classRef) {
				$_procObj    = &t3lib_div::getUserObj($_classRef);
				$insertArray = $_procObj->processPostInsertArray($insertArray, $this);
			}
		}
			
		// Insert post record
		if (!$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_posts', $insertArray)) {
			return false;
		}
			
		// Retrieve post uid
		$postId = $GLOBALS['TYPO3_DB']->sql_insert_id();

		// Update attachment record
		if (is_array($attachments) && count($attachments)) {
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_attachments', 'uid IN (' . implode(',', $attachments) . ')', array('post_id' => $postId));
		}
			
		// Generate post text record
		$insertArray = array(
			'pid'       => $this->getFirstPid(),
			'tstamp'    => time(),
			'crdate'    => time(),
			'post_id'   => $postId,
			'post_text' => $text
		);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['postfactory']['insertPostText'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['postfactory']['insertPostText'] as $_classRef) {
				$_procObj    = &t3lib_div::getUserObj($_classRef);
				$insertArray = $_procObj->processPostTextInsertArray($insertArray, $this);
			}
		}
			
		// Insert post text record
		if (!$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_posts_text', $insertArray)) {
			$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_posts', 'uid = ', $postId);
			return false;
		}
			
		// Clear topic for indexing
		if (class_exists('tx_mmforum_indexing')) {
			tx_mmforum_indexing::delete_topic_ind_date($topicId);
		}

        // Send notification email to users who have subscribed this topic
		if ($this->parent != null) {
			// Subscribe to the topic
			tx_mmforum_havealook::addSubscription($this->parent, $topicId, $author);
			tx_mmforum_havealook::notifyTopicSubscribers($topicId, $this->parent);
		}
            
        // Set topic for all users to "not read"
		$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_postsread', 'topic_id = ' . $topicId);

		// Update topic and forum post counters
		if (!$noUpdate) {
			$this->updateTopicPostCount($topicId);
			$this->updateForumPostCount($forumId);
			$this->updateUserPostCount($author);
		} else {
			$this->updateQueue_addTopic($topicId);
			$this->updateQueue_addForum($forumId);
			$this->updateQueue_addUser($author);
		}

		return $postId;
	}





		/*
		 * DELETION
		 */





		/**
		 *
		 * Deletes a topic from the database.
		 * This function completely deletes a topic and all associated objects (like
		 * ratings, subscriptions, etc.). Before deleting the topic itself, all posts
		 * are deleted using the "delete_post" method.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-12-20
		 * @param   int  $topicId  The UID of the topic that is to be deleted
		 * @return  void
		 *
		 */

	function delete_topic ( $topicId ) {


			/*
			 * Get the global database interface, because I am lazy of writing... :P
			 */

		global $TYPO3_DB;


			/*
			 * Load the topic from the database.
			 */

		$arr = $TYPO3_DB->sql_fetch_assoc($TYPO3_DB->exec_SELECTquery('*','tx_mmforum_topics','uid='.intval($topicId)));
		$uA = array('deleted'=>1, 'tstamp'=>time());


			/*
			 * Load all posts of this topic and delete them all.
			 */

		$res = $TYPO3_DB->exec_SELECTquery('uid','tx_mmforum_posts','topic_id='.intval($topicId).' AND deleted=0');
		while(list($postId) = $TYPO3_DB->sql_fetch_row($res))
			$this->delete_post($postId, true);


			/*
			 * Now delete all favorites, subscriptions, ratings and search index entries.
			 */

		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_favorites', $uA, 'topic_id='.intval($postId));
		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_havealook', $uA, 'topic_id='.intval($postId));
		$TYPO3_DB->exec_DELETEquery('tx_mmforum_wordmatch', 'topic_id='.intval($topicId).'');
		if(t3lib_extMgm::extLoaded('ratings'))
			$TYPO3_DB->exec_DELETEquery('tx_ratings_data', $uA, 'reference="tx_mmforum_topics_'.intval($postId).'"');


			/*
			 * Congratulations. Now delete the topic itself.
			 */

		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_topics', $uA, 'uid='.intval($postId));


			/*
			 * Now update all the internal counters.
			 */

		$this->updateQueue_addForum($arr['forum_id']);
		$this->updateQueue_addUser($arr['topic_poster']);
		$this->updateQueue_process();
	}



		/**
		 *
		 * Deletes a single posts.
		 * This method deletes a single post and all associated objects (alerts, ratings,
		 * search index entries etc.).
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-12-20
		 * @param   int     $postId   The UID of the post that is to be deleted.
		 * @param   boolean $noUpdate Set to TRUE, in order to suspend updating of
		 *                            internal counters.
		 * @return  void
		 *
		 */

	function delete_post($postId, $noUpdate = false) {
		global $TYPO3_DB;

		$arr = $TYPO3_DB->sql_fetch_assoc($TYPO3_DB->exec_SELECTquery('*','tx_mmforum_posts','uid='.intval($postId)));
		$uA = array('deleted'=>1, 'tstamp'=>time());

		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_posts', $uA, 'uid='.intval($postId));
		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_posts_text', $uA, 'post_id='.intval($postId));
		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_post_alert', $uA, 'post_id='.intval($postId));
		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_attachments', $uA, 'post_id='.intval($postId));
		$TYPO3_DB->exec_DELETEquery('tx_mmforum_wordmatch', 'post_id='.intval($postId).'');

		if(t3lib_extMgm::extLoaded('ratings'))
			$TYPO3_DB->exec_DELETEquery('tx_ratings_data', $uA, 'reference="tx_mmforum_posts_'.intval($postId).'"');

		if(!$noUpdate) {
			$this->updateTopicPostCount($arr['topic_id']);
			$this->updateForumPostCount($arr['forum_id']);
			$this->updateUserPostCount($arr['poster_id']);
		} else {
			$this->updateQueue_addTopic($arr['topic_id']);
			$this->updateQueue_addForum($arr['forum_id']);
			$this->updateQueue_addUser($arr['poster_id']);
		}

	}





		/*
		 * COUNTER UPDATING METHODS
		 */





		/**
		 *
		 * Adds a topic to the list of topics whose post counters have to be updated.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-07-21
		 * @param   int  $topic_uid The UID of the topic to be updated
		 * @return  void
		 *
		 */

	function updateQueue_addTopic($topic_uid) { $this->updateQueue['topics'][$topic_uid] = true; }



		/**
		 * Adds a forum to the list of forum whose post and topic counters have to be updated.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-07-21
		 * @param   int  $forum_uid The UID of the forum to be updated
		 * @return  void
		 */

	function updateQueue_addForum($forum_uid) { $this->updateQueue['forums'][$forum_uid] = true; }



		/**
		 *
		 * Adds a user to the list of users whose post counters have to be updated.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-07-21
		 * @param   int  $user_uid The UID of the user to be updated
		 * @return  void
		 *
		 */

	function updateQueue_addUser($user_uid) { $this->updateQueue['users'][$user_uid] = true; }



		/**
		 *
		 * Updates post and topic counters of topics, forums and users.
		 * This function updates the post and topic counters of topics, forums
		 * and users that were affected during the prior creation of topics
		 * and posts.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-07-21
		 * @return  void
		 *
		 */

	function updateQueue_process() {

			/*
			 * First rule against clear code: Use trinity operators as often as possible,
			 * try to nest them as deeply as you can.
			 */
		$topicQueue = (is_array($this->updateQueue['topics']) ? array_keys($this->updateQueue['topics']) : array());
		$forumQueue = (is_array($this->updateQueue['forums']) ? array_keys($this->updateQueue['forums']) : array());
		$userQueue  = (is_array($this->updateQueue['users'])  ? array_keys($this->updateQueue['users'])  : array());

			/*
			 * Second rule against clear code: Avoid all unneccesary brackets!
			 */
		foreach ($topicQueue as $topicId) $this->updateTopicPostCount($topicId);
		foreach ($forumQueue as $forumId) $this->updateForumPostCount($forumId);
		foreach ($userQueue as $userId)   $this->updateUserPostCount($userId);
	}



		/**
		 * Updates a user's post count.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-07-21
		 * @param   int  $user_uid The UID of the user whose post count is to be updated
		 * @return  void
		 */

	function updateUserPostCount($user_uid) {
		$user_uid = intval($user_uid);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'COUNT(*)',
			'tx_mmforum_posts',
			'poster_id='.$user_uid.' AND deleted=0'.$this->getPidQuery()
		);
		list($postcount) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		
		$updateArray = array(
			'tstamp'			=> time(),
			'tx_mmforum_posts'	=> $postcount
		);
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users','uid='.$user_uid,$updateArray);
	}



		/**
		 *
		 * Updates a forum's post and topic count.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-06-12
		 * @param   int    $forum_uid The UID of the forum whose post and topic
		 *                            count is to be updated
		 * @param   string $orderBy   The column name of the tx_mmforum_posts table
		 *                            used for ordering posts (see
		 *                            http://forge.typo3.org/issues/show/3520 for
		 *                            for more information).
		 * @return  void
		 *
		 */

	function updateForumPostCount($forum_uid, $orderBy='post_time') {
		$forum_uid = intval($forum_uid);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'tx_mmforum_posts',
			'forum_id='.$forum_uid.' AND deleted=0'.$this->getPidQuery(),
			'',
			$orderBy.' DESC'
		);
		list($last_post_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		$postcount = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
		
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'COUNT(*)',
			'tx_mmforum_topics',
			'forum_id='.$forum_uid.' AND deleted=0'.$this->getPidQuery()
		);
		list($topiccount) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		
		$updateArray = array(
			'tstamp'				=> time(),
			'forum_last_post_id'	=> $last_post_id,
			'forum_posts'			=> $postcount,
			'forum_topics'			=> $topiccount
		);
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_forums','uid='.$forum_uid,$updateArray);
	}



		/**
		 *
		 * Updates a topics's post count.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-07-21
		 * @param   int   $topic_uid  The UID of the topic whose post count is to be
		 *                            updated
		 * @param   string $orderBy   The column name of the tx_mmforum_posts table
		 *                            used for ordering posts (see
		 *                            http://forge.typo3.org/issues/show/3520 for
		 *                            for more information).
		 * @return  void
		 *
		 */

	function updateTopicPostCount($topic_uid, $orderBy='post_time') {
		$topic_uid = intval($topic_uid);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'tx_mmforum_posts',
			'topic_id='.$topic_uid.' AND deleted=0'.$this->getPidQuery(),
			'',
			$orderBy.' DESC'
		);
		list($last_post_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		$postcount = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
		
		$updateArray = array(
			'tstamp'				=> time(),
			'topic_last_post_id'	=> $last_post_id,
			'topic_replies'			=> $postcount - 1
		);
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics','uid='.$topic_uid,$updateArray);
	}





		/*
		 * VARIOUS HELPER FUNCTIONS
		 */





		/**
		 *
		 * Retrievs a topic's forum UID.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-07-21
		 * @param   int $topic_uid The topic's UID
		 * @return  int            The forum's UID
		 *
		 */

	function getForumUIDByTopic($topic_uid) {
		$topic_uid = intval($topic_uid);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'forum_id',
			'tx_mmforum_topics',
			'uid='.$topic_uid.' AND deleted=0'
		);
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
			list($forum_uid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
			return $forum_uid;
		} else return false;
	}



	/**
	 *
	 * Delivers the PID of newly created records.
	 * @return  int The PID of a record that is to be created.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-02
	 *
	 */

	function getFirstPid() {
		if($this->conf['storagePID'] == -1) return 0;
		if(!$this->conf['storagePID']) return 0;
		return intval($this->conf['storagePID']);
	}



	/**
	 *
	 * Delivers a MySQL-WHERE query checking the records' PID.
	 * This allows it to exclusively select records from a very specific list
	 * of pages.
	 * 
	 * @param   string $tables The list of tables that are queried
	 * @return  string         The query, following the pattern " AND pid IN (...)"
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-03
	 * 
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
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_postfactory.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_postfactory.php']);
}
?>