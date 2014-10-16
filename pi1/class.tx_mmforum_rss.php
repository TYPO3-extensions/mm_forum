<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Mittwald CM Service GmbH & Co. KG
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
 * The tx_mmforum_rss class handles the display and generation of
 * RSS feeds informing about latest posts in the forum.
 * The mm_forum RSS generator supports three kinds of feeds:
 *
 *  1. A global feed for the entire forum. This contains posts from
 *     all PUBLIC boards (public meaning no restrictions regarding the
 *     reading rights whatsoever).
 *  2. A feed for a single forum. This contains only posts from this
 *     specific forum and only if this forum is public (see above).
 *  3. A feed for a single thread. This contains only posts from this
 *     specific thread and only if this thread is in a public forum.
 *
 * @copyright  2008 Martin Helmich, Mittwald CM Service
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    2008-07-17
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_rss {



		/**
		 * Global objects
		 */

	var $pObj, $main, $piVars, $cObj, $mode, $param;



		/**
		 * Fields that are to be selected into the post array
		 */

	var $selectFields = 'p.uid AS post_uid, p.post_time, x.post_text, t.topic_title, f.forum_name, u.';



		/**
		 * Main function. Handles all output.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @param   array          $conf   The configuration array
		 * @param   tx_mmforum_pi1 $parent The parent object
		 * @return  void
		 */

	function main($conf, $parent) {

			// Initialize variables
		$this->initialize($conf, $parent);

		// load userfield
		$this->selectFields .= $this->conf['userNameField'] ? $this->conf['userNameField'] : 'username';

			// Load post array
		if($this->piVars['tid']) {
			$posts = $this->getPosts_topic($this->piVars['tid']);
			$this->mode = 'topic';
			$this->param = $this->piVars['tid'];
		} elseif($this->piVars['fid']) {
			$posts = $this->getPosts_forum($this->piVars['fid']);
			$this->mode = 'forum';
			$this->param = $this->piVars['fid'];
		} else {
			$posts = $this->getPosts_all();
			$this->mode = 'all';
			$this->param = null;
		}

			// Set headers
		$this->setHeaders();

			// Output rss data and die
		die( $this->printPosts($posts) );

	}



		/**
		 * Writes HTML header data to include the rss file.
		 * This function creates HTML header data to include the rss file into the
		 * generated output. Using the parameters, it can be specified whether to link
		 * to an RSS feed of the entire forum, just a single forum or even a single
		 * topic.
		 * It is possible to include multiple RSS feeds onto a single page. If the
		 * forum is e.g. in post listing mode, an RSS feed containing the latest
		 * posts of this specific topic will be included, but there will also RSS
		 * feeds for the forum this thread is contained in and for the entire forum
		 * be included.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-12-27
		 * @param   string $mode  The RSS display mode. Can be 'all', 'topic' or 'forum'
		 * @param   int    $param If $mode is 'topic' or 'forum', this parameter has
		 *                        to contain the topic of forum UID.
		 * @return  void
		 */

	function setHTMLHeadData($mode, $param=null) {
			// If method is called statically, instantiate class
		#if(!$this instanceof tx_mmforum_rss) {
			// Workaround. "instanceof" does not work with old PHP versions.
		if(isset($this->extKey)) {
			$rssObj = t3lib_div::makeInstance('tx_mmforum_rss');
			$rssObj->initialize($this->conf, $this);
			$rssObj->setHTMLHeadData($mode, $param);
		} else {
			switch($mode) {
				default:
				case 'all':     $linkParams[$this->pObj->prefixId] = array(); break;
				case 'forum':   $linkParams[$this->pObj->prefixId] = array('fid' => $param); break;
				case 'topic':   $linkParams[$this->pObj->prefixId] = array('tid' => $param); break;
			}

				/* Set HTML head data only if a RSS page is specified */
			if($this->conf['rssPID']) {
					// Compose RSS URL
				$rssLink = $this->pObj->pi_getPageLink($this->conf['rssPID'], null, $linkParams);
				$rssLink = $this->pObj->tools->getAbsoluteUrl($rssLink);

					// Include RSS URL in HTML header data
				$GLOBALS['TSFE']->additionalHeaderData['mm_forum_rss_'.$mode] = '<link rel="alternate" type="application/rss+xml" title="'.$this->pObj->escape($this->getFeedTitle($mode, $param)).'" href="'.$rssLink.'" />';
			}
		}
	}



		/**
		 * Sets the output's content type to application/xml.
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @return  void
		 */

	function setHeaders() { header('Content-Type: application/xml'); }



		/**
		 * Prints an array of posts.
		 * This function prints an array of posts that are to be listed in the RSS
		 * feed.
		 * @param  array  A numeric array of all posts that shall be listed in the
		 *                RSS
		 * @return string The RSS feed as XML string
		 */

	function printPosts($posts) {
		$template		= $this->cObj->fileResource($this->conf['template.']['rss']);
		$template		= $this->cObj->getSubpart($template, '###RSS_FEED###');
		$rowTemplate	= $this->cObj->getSubpart($template, '###RSS_POST_ITEM###');

		// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['rss_posts'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['rss_posts'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->rss_posts($posts, $this);
				}
			}

		$marker			= array(
			'###RSS_ENCODING###'        => $GLOBALS['TSFE']->renderCharset,
			'###RSS_TITLE###'           => $this->getFeedTitle($mode, $param),
			'###RSS_DESCRIPTION###'     => $this->getFeedDescription(),
			'###RSS_URL###'             => $this->pObj->escapeURL($this->getFeedURL()),
			'###RSS_GENERATOR###'       => 'mm_forum powered by TYPO3',
			'###RSS_LASTBUILT###'       => date('r'),
			'###RSS_LANGUAGE###'		=> $this->pObj->LLkey == 'default' ? 'en' : $this->pObj->LLkey
		);

		// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['rss_globalMarkers'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['rss_globalMarkers'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$marker = $_procObj->rss_globalMarkers($marker, $posts, $this);
				}
			}

		$template		= $this->cObj->substituteMarkerArray($template, $marker);

		foreach($posts as $post) {
			$rowMarker	= array(
				'###RSS_TOPIC_NAME###'      => $this->pObj->escape($post['topic_title']),
				'###RSS_POST_LINK###'       => $this->getPostLink($post['post_uid']),
				'###RSS_POST_DATE###'       => date('r',$post['post_time']),
				'###RSS_POST_TEXT_SHORT###' => $this->getPostTextShort($post['post_text']),
				'###RSS_POST_TEXT###'       => $this->getPostTextComplete($post['post_text']),
				'###RSS_FORUM_NAME###'      => $this->pObj->escape($post['forum_name']),
				'###RSS_POST_AUTHOR###'     => $this->pObj->escape($post[$this->conf['userNameField'] ? $this->conf['userNameField'] : 'username'])
			);
			$rowContent .= $this->cObj->substituteMarkerArray($rowTemplate, $rowMarker);

			// Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['rss_itemMarkers'])) {
					foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['rss_itemMarkers'] as $_classRef) {
						$_procObj = & t3lib_div::getUserObj($_classRef);
						$rowMarker = $_procObj->rss_itemMarkers($marker, $post, $this);
					}
				}
		}
		$template		= $this->cObj->substituteSubpart($template, '###RSS_POST_ITEM###', $rowContent);

		return $template;
	}



		/**
		 * Gets the description text of a posts.
		 * This function parses the posts text for being displayed as post summary
		 * in the RSS feed. It removes all linebreaks and BBCodes and replaces
		 * special characters.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @param   string $text The post text
		 * @return  string       The parsed post text
		 */

	function getPostTextShort($text) {
		$text = str_replace("\r\n"," ",$text);
		$text = str_replace("\n", " ",$text);
		$text = str_replace("\r", " ",$text);

		$text = preg_replace('/\[.*?\]/i','',$text);
		$text = htmlspecialchars($text);

		return $text;
	}



		/**
		 * Gets the complete post text.
		 * This function returns the complete text of a posts. It uses the postparser
		 * class to generate the content.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @param   <type> $text The post text
		 * @return  <type>       The parsed post text
		 */

	function getPostTextComplete($text) {
		return tx_mmforum_postparser::main($this->pObj,$this->conf,$text,'textparser');
	}



		/**
		 * Generates a link to a post.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @param   integer $post_id The post UID
		 * @return  string           The absolute post URL
		 */

    function getPostLink($post_id) {
        $linkParams[$this->pObj->prefixId]		= array(
            'action'	=> 'list_post',
            'pid'		=> $post_id
        );
        return $this->pObj->escapeURL($this->pObj->tools->getAbsoluteUrl($this->pObj->pi_getPageLink($this->conf['pid_forum'], '', $linkParams)));
    }



		/**
		 * Gets the RSS feed title.
		 * This function generates the title of the RSS feed. This consists of the
		 * page's title and - if a forum or topic UID is specified - the forum and/or
		 * topic title.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @param   string  $mode  The display mode of the feed. Can be 'all', 'topic'
		 *                         or 'forum'.
		 * @param   integer $param If $mode is 'topic' or 'forum', this parameter has
		 *                         to contain the topic or forum UID.
		 * @return  string         The feed title
		 */

	function getFeedTitle($mode='all', $param=null) {
		$mode = $mode?$mode:$this->mode;
		$param = $param?$param:$this->param;

		switch($mode) {
			default:
			case 'all':     return $GLOBALS['TSFE']->page['title']; break;
			case 'topic':
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('topic_title, forum_name', 'tx_mmforum_topics t LEFT JOIN tx_mmforum_forums f ON t.forum_id = f.uid', 't.uid='.intval($param));
				list($topic_title, $forum_title) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
				return $GLOBALS['TSFE']->page['title'].' : '.$forum_title.' : '.$topic_title; break;
			case 'forum':
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('forum_name', 'tx_mmforum_forums', 'uid='.intval($param));
				list($forum_title) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
				return $GLOBALS['TSFE']->page['title'].' : '.$forum_title; break;
		}

		return $GLOBALS['TSFE']->page['title'];
	}



		/**
		 * Gets the RSS feed's description
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @return  string The RSS feed's description.
		 */

    function getFeedDescription() {
        if($this->piVars['tid']) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'topic_title',
				'tx_mmforum_topics t
				 LEFT JOIN tx_mmforum_forums f ON f.uid = t.forum_id
				 LEFT JOIN tx_mmforum_forums c ON c.uid = f.parentID',
				'uid='.intval($this->piVars['tid']).
				 $this->pObj->getMayRead_forum_query('f').
				 $this->pObj->getMayRead_forum_query('c')
			);
        } elseif($this->piVars['fid']) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'f.forum_name',
				'tx_mmforum_forums f
				 LEFT JOIN tx_mmforum_forums c ON c.uid = f.parentID',
				'f.uid='.intval($this->piVars['fid']).
				$this->pObj->getMayRead_forum_query('f').
				$this->pObj->getMayRead_forum_query('c')
			);
        } else {
            return "";
        }

        list($result) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        return $result;
    }



		/**
		 * Gets the URL the feed is linked to.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @return  string The URL the feed is linked to.
		 */

	function getFeedURL() {
		if($this->piVars['tid']) {
			$linkParams[$this->pObj->prefixId]		= array(
				'action'		=> 'list_post',
				'tid'			=> $this->piVars['tid']
			);
		} elseif($this->piVars['fid']) {
			$linkParams[$this->pObj->prefixId]		= array(
				'action'		=> 'list_topic',
				'fid'			=> $this->piVars['fid']
			);
		}

		return $this->pObj->tools->getAbsoluteUrl($this->pObj->pi_getPageLink($this->conf['pid_forum'], '', $linkParams));
	}



    /**
     * Returns the amount of posts to be listed in the feed.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-07-17
     * @return  integer Returns 30
     */

	function getPostNum() { return 30; }



		/**
		 * Gets the latest posts of a specific topic.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @param   integer $topic_id The topic UID
		 * @return  array             An array containing all matching posts
		 */

	function getPosts_topic($topic_id) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			$this->selectFields,
			'tx_mmforum_posts p
			 LEFT JOIN tx_mmforum_posts_text x ON x.post_id = p.uid
			 LEFT JOIN fe_users u ON u.uid = p.poster_id
			 LEFT JOIN tx_mmforum_topics t ON t.uid = p.topic_id
			 LEFT JOIN tx_mmforum_forums f ON t.forum_id = f.uid',
			'p.deleted=0 AND
			 t.deleted=0 AND
			 f.deleted=0 AND
			 p.topic_id='.intval($topic_id).
			 $this->pObj->getMayRead_forum_query('f').
			 $this->pObj->getMayRead_forum_query('c'),
			/*'p.uid',*/ '',
			'p.post_time DESC',
			$this->getPostNum()
		);
		$results = array();
		while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
			array_push($results, $arr);
		return $results;
	}



		/**
		 * Gets the latest posts of a specific forum.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @param   integer $topic_id The forum UID
		 * @return  array             An array containing all matching posts
		 */

	function getPosts_forum($forum_id) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			$this->selectFields,
			'tx_mmforum_posts p
			 LEFT JOIN tx_mmforum_posts_text x ON x.post_id = p.uid
			 LEFT JOIN fe_users u ON u.uid = p.poster_id
			 LEFT JOIN tx_mmforum_topics t ON t.uid = p.topic_id
			 LEFT JOIN tx_mmforum_forums f ON t.forum_id = f.uid
			 LEFT JOIN tx_mmforum_forums c ON f.parentID = c.uid',
			'p.deleted=0 AND
			 t.deleted=0 AND
			 f.deleted=0 AND
			 f.uid='.intval($forum_id).
			 $this->pObj->getMayRead_forum_query('f').
			 $this->pObj->getMayRead_forum_query('c'),
			/*'p.uid',*/ '',
			'p.post_time DESC',
			$this->getPostNum()
		);
		$results = array();
		while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			array_push($results, $arr);
		}
		return $results;
	}



		/**
		 * Gets the latest posts from all public forums.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @return  array             An array containing all matching posts
		 */

	function getPosts_all() {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			$this->selectFields,
			'tx_mmforum_posts p
			 LEFT JOIN tx_mmforum_posts_text x ON x.post_id = p.uid
			 LEFT JOIN fe_users u ON u.uid = p.poster_id
			 LEFT JOIN tx_mmforum_topics t ON t.uid = p.topic_id
			 LEFT JOIN tx_mmforum_forums f ON t.forum_id = f.uid
			 LEFT JOIN tx_mmforum_forums c ON f.parentID = c.uid',
			'p.deleted=0 AND
			 t.deleted=0 AND
			 f.deleted=0 '.
			 $this->pObj->getMayRead_forum_query('f').
			 $this->pObj->getMayRead_forum_query('c'),
			/*'p.uid',*/ '',
			'p.post_time DESC',
			$this->getPostNum()
		);
		$results = array();
		while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			array_push($results, $arr);
		}
		return $results;
	}



		/**
		 * Initializes the class by getting all vital attributes from parent object.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-07-17
		 * @param   array          $conf   The configuration array
		 * @param   tx_mmforum_pi1 $parent The parent object
		 */

	function initialize($conf, $parent) {
		$this->pObj = $parent;
		$this->cObj = $parent->cObj;
		$this->conf = $conf;
		$this->piVars = $parent->piVars;
	}



	function pi_getLL($key) {
		return $this->pObj->pi_getLL($key);
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_rss.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_rss.php']);
}
?>
