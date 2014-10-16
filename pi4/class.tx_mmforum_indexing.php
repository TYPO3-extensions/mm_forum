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
 *   65: class tx_mmforum_indexing extends tslib_pibase
 *   74:     function ind_topic($topic_id,$conf)
 *  108:     function ind_check()
 *  130:     function ind_topic_title($conf,$topic_id)
 *  193:     function ind_post($conf,$post_id)
 *  252:     function write_post_ind_date($post_id)
 *  266:     function write_topic_ind_date($topic_id)
 *  281:     function delete_topic_ind_date($topic_id)
 *  297:     function wordAdd($word)
 *  331:     function wortMatchAdd ($word_id,$matchparams,$debug=false)
 *  369:     function getPidQuery($conf,$tables="")
 *  388:     function getFirstPid($conf)
 *
 * TOTAL FUNCTIONS: 11
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * The class 'tx_mmforum_indexing' is a subclass for the search plugin
 * (tx_mmforum_pi4). It manages the indexing of topics and posts.
 * Post texts are split into single words, these words are written
 * into the index table. Then, in another table, it is stored, which
 * post contains which words.
 *
 * @author	   Holger Trapp <h.trapp@mittwald.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Björn Detert <b.detert@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @version    2006-10-11
 * @package    mm_forum
 * @subpackage Search
 */
class tx_mmforum_indexing {

	/**
	 * Indexes a specific topic.
	 * @param  int    $topic_id The topic UID
	 * @param  array  $conf     The calling plugin's configuration vars. Not actually used.
	 * @return string           If an error occurred, an error message is returned
	 */
	function ind_topic($topic_id,$conf)
	{
			// Delete old index records regarding this topic
		$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_wordmatch',"topic_id='$topic_id'");

			// Retrieve post data
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_posts',"topic_id='$topic_id' AND hidden='0'".$this->getPidQuery($conf));
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
			// Index each post in the topic
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$this->ind_post($conf,$row);
			}
            $this->ind_topic_title($conf,$topic_id);
		} else {
			$content = 'Could not find topic '.$topic_id;
		}

			// Update indexing date in topic record
		$this->write_topic_ind_date($topic_id);

		return $content;
	}

	/**
	 * Checks if an indexing is currently running.
	 * @return boolean TRUE, if an indexing is currently running, otherwise FALSE.
	 */
	function ind_check() {
		// Load date of last indexing process from database
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('tx_mmforumsearch_index_write','tx_mmforum_posts','','','tx_mmforumsearch_index_write DESC','1');
		list($date) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

		// If last indexing process happened less than 10 seconds ago, return TRUE
		IF($date < (time()-10)) {
			return 0;
		}
		// Otherwise, return FALSE.
		else {
			return 1;
		}
	}

    function ind_topic_title($conf,$topic_id) {
        $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_wordmatch',"topic_id='".$topic_id."' AND is_header=1");

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_topics',
            'uid='.intval($topic_id)
        );
        $topicData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

		if($topicData['deleted'] == 0) {
	        $words = $this->wordArray($conf, $topicData['topic_title']);

	        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'f.grouprights_read as f_read, c.grouprights_read as c_read',
				'tx_mmforum_forums f, tx_mmforum_forums c',
				'f.uid="'.$topicData['forum_id'].'" AND c.uid = f.parentID'
			);
			$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$f_groups = t3lib_div::intExplode(',',$arr['f_read']);
			$c_groups = t3lib_div::intExplode(',',$arr['c_read']);

			$rFGroups = array();
			foreach($f_groups as $group) {
				if($group > 0) $rFGroups[] = $group;
			}
			$sFGroups = implode(',',$rFGroups);

			$rCGroups = array();
			foreach($c_groups as $group) {
				if($group > 0) $rCGroups[] = $group;
			}
			$sCGroups = implode(',',$rCGroups);

	        $matchparams = array(
	            'post_id'           => $topicData['topic_first_post_id'],
	            'topic_id'          => $topicData['uid'],
	            'forum_id'          => $topicData['forum_id'],
	            'solved'            => $topicData['solved'],
	            'topic_title'       => $topicData['topic_title'],
	            'topic_views'       => $topicData['topic_views'],
	            'topic_replies'     => $topicData['topic_replies'],
	            'post_crdate'       => $topicData['crdate'],
	            'post_cruser'       => $topicData['topic_poster'],
	            'reqUserGroups_f'   => $sFGroups,
	            'reqUserGruops_c'   => $sCGroups,
	            'is_header'			=> 1,
	        );

	        foreach($words as $word) {
	            $word_id = $this->wordAdd($word);
	            $this->wortMatchAdd($word_id,$matchparams,false);
	        }
		}


    }

	/**
	 * Indexes a specific post.
	 * @param  array $conf    The calling plugin's configuration vars. Not actually used.
	 * @param  int   $post_id The UID of the post to be indexed.
	 * @return void
	 */
	function ind_post($conf,$post_array) {
			// Delete old records in the index table regarding this post.
		$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_wordmatch',"post_id='".$post_array['uid']."'");

			// If post is deleted, do not index again...
		if($post_array['deleted'] == 0) {
				// Get post content
			$content		= $this->get_posttext($post_array['uid']);
				// Retrieve all words in the post content as array
			$wordArray		= $this->wordArray($conf,$content);

				// Load topic information
			$topic_array	= $this->topic_information($post_array['topic_id']);

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'f.grouprights_read as f_read, c.grouprights_read as c_read',
				'tx_mmforum_forums f, tx_mmforum_forums c',
				'f.uid="'.$topic_array['forum_id'].'" AND c.uid = f.parentID'
			);
			$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$f_groups = t3lib_div::intExplode(',',$arr['f_read']);
			$c_groups = t3lib_div::intExplode(',',$arr['c_read']);

			#$groups = array_merge($f_groups,$c_groups);
			$rFGroups = array();
			foreach($f_groups as $group) {
				if($group > 0) $rFGroups[] = $group;
			}
			$sFGroups = implode(',',$rFGroups);

			$rCGroups = array();
			foreach($c_groups as $group) {
				if($group > 0) $rCGroups[] = $group;
			}
			$sCGroups = implode(',',$rCGroups);

			// Compose data for word matches
			$matchparams['post_id']			= $post_array['uid'];
			$matchparams['topic_id']		= $post_array['topic_id'];
			$matchparams['forum_id']		= $topic_array['forum_id'];
			$matchparams['solved']			= $topic_array['solved'];
			$matchparams['topic_title']		= $topic_array['topic_title'];
			$matchparams['topic_views']		= $topic_array['topic_views'];
			$matchparams['topic_replies']	= $topic_array['topic_replies'];
			$matchparams['post_crdate']		= $post_array['crdate'];
			$matchparams['post_cruser']		= $post_array['cruser_id'];
			$matchparams['reqUserGroups_f']	= $sFGroups;
			$matchparams['reqUserGroups_c']	= $sCGroups;

			// Insert words and word matches into data base. Very time consuming.
			foreach($wordArray as $value) {
				$word_id = $this->wordAdd($value);			// Insert word into database and get UID
				$this->wortMatchAdd($word_id,$matchparams,true);	// Write word match into database
			}
		}

		$this->write_post_ind_date($post_array['uid']);				// Update post indexing date
	}

	/**
	 * Updates the indexing timestamp in a post record.
	 * @param int $post_id The post UID
	 */
	function write_post_ind_date($post_id) {
		$updateArray = array(
			' tx_mmforumsearch_index_write' => time(),
		);
		$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_mmforum_posts', 'uid = '.$post_id, $updateArray);
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
	}

	/**
	 * Updates the indxing timestamp in a topic record.
	 * @param int $topic_id The topic UID
	 */
	function write_topic_ind_date($topic_id) {
		$updateArray = array(
			' tx_mmforumsearch_index_write' => time(),
		);
		$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_mmforum_topics', "uid = ".$topic_id, $updateArray);
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
	}

	/**
	 * Marks a topic as not indexed.
	 * @param int $topic_id The UID if the topic that is to be marked as not
	 *                      indexed.
	 */
	function delete_topic_ind_date($topic_id) {
		$updateArray = array(
			' tx_mmforumsearch_index_write' => 0,
		);
		$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_mmforum_topics', "uid = ".$topic_id, $updateArray);
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
	}

	/**
	 * Inserts a new word into the search index table and returns it's UID.
	 * If the word already exists in the search index, just the UID is returned.
	 * @param string $word The word to be inserted
	 * @param int          The word's UID
	 */
	function wordAdd($word) {
		// Attempt to load word from database
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','tx_mmforum_wordlist',"word=".$GLOBALS['TYPO3_DB']->fullQuoteStr($word, 'tx_mmforum_wordlist')." ".$this->getPidQuery($this->conf));
		IF(mysql_error()) echo mysql_error().'<hr>';

		// If words already exists, just return the UID
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
			list($uid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		}
		// Otherwise, create new record and return the UID
		else {
			// Compost insert query
			$insertArray = array(
				'pid'		=> $this->getFirstPid($this->conf),
				'word'		=> $word,
				'metaphone'	=> metaphone($word)
			);

			// Execute insert query
			$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_wordlist', $insertArray);
			$uid = mysql_insert_id();
		}
		return $uid;
	}

	/**
	 * Adds a word match into the word match table. A word match records describes
	 * which posts contains which words.
	 * @param  int   $word_id     The UID of the word for the word match
	 * @param  array $matchparams The other parameters for the word match
	 * @return void
	 */
	function wortMatchAdd ($word_id,$matchparams,$debug=false) {
		// Compost insert array
		$insertArray = array(
			'pid'				=> $this->getFirstPid($this->conf),
			'word_id'			=> $word_id,
			'post_id'			=> $matchparams['post_id'],
			'topic_id'			=> $matchparams['topic_id'],
			'forum_id'			=> $matchparams['forum_id'],
			'solved'			=> $matchparams['solved'],
			'topic_title'		=> $matchparams['topic_title'],
			'topic_views'		=> $matchparams['topic_views'],
			'topic_replies'		=> $matchparams['topic_replies'],
			'post_crdate'		=> $matchparams['post_crdate'],
			'post_cruser'		=> $matchparams['post_cruser'],
			'reqUserGroups_f'	=> $matchparams['reqUserGroups_f'],
            'reqUserGroups_c'   => $matchparams['reqUserGroups_c'],
			'crdate'            => time(),
			'tstamp'			=> time()
		);
		// Execute query
		$query = $GLOBALS['TYPO3_DB']->INSERTquery('tx_mmforum_wordmatch', $insertArray);
        $GLOBALS['TYPO3_DB']->sql_query($query);

        #if(!$res) echo "AAARGH!";
        #echo $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery.'<br />';
	}

	/**
	 * Delivers a MySQL-WHERE query checking the records' PID.
	 * This allows it to exclusively select records from a very specific list
	 * of pages.
	 * @param   array  $conf   The plugin configuration vars
	 * @param   string $tables The list of tables that are queried
	 * @return  string         The query, following the pattern " AND pid IN (...)"
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-02
	 */
	function getPidQuery($conf,$tables="") {

		if(strlen(trim($conf['pidList']))==0) return "";
		if($tables == "") {
			if($conf['storagePID'])
				return ' AND pid = '.$conf['storagePID'].' ';
			else return ' AND pid IN ('.$conf['pidList'].')';
		}

		$tables = t3lib_div::trimExplode(',',$tables);
		$query = "";

		foreach($tables as $table) {
			if($conf['storagePID'])
				$query .= " AND $table.pid = ".$conf['storagePID']." ";
			else $query .= " AND $table.pid IN (".$conf['pidList'].")";
		}
		return $query;
	}

	function getFirstPid($conf) {
			// If conf['storagePID'] is set, indexing is called in cronjob mode
		if($conf['storagePID']) return $conf['storagePID'];
			// Otherwise it is called from browser
		else {
			if(strlen(trim($conf['pidList']))==0) return 0;
			$pids = t3lib_div::trimExplode(',',$conf['pidList']);
			return $pids[0];
		}
	}

	/**
	 * Clears a string of all BBCodes
	 * @param  string $string The string to be cleared
	 * @return string         The cleared string
	 */
	function clear_phpBB($string) {
		$patterns = array(
		"/\[img\](.*?)\[\/img\]/isS",
		"/\[img:[a-z0-9]{10}\](.*?)\[\/img:[a-z0-9]{10}\]/isS",

		"/\[quote(.*)\](.*?)\[\/quote(.*)\]/isS",

		"/\[b\](.*?)\[\/b\]/isS",
		"/\[b:[a-z0-9]{10}\](.*?)\[\/b:[a-z0-9]{10}\]/isS",

		"/\[u\](.*?)\[\/u\]/isS",
		"/\[u:[a-z0-9]{10}\](.*?)\[\/u:[a-z0-9]{10}\]/isS",

		"/\[i\](.*?)\[\/i\]/isS",
		"/\[i:[a-z0-9]{10}\](.*?)\[\/i:[a-z0-9]{10}\]/isS",

		"/\[color=(.*?):[a-z0-9]{10}\](.*?)\[\/color:[a-z0-9]{10}\]/isS",
		"/\[color=(.*?)\](.*?)\[\/color\]/isS",

		"/\[list\](.*?)\[\/list\]/isS",
		"/\[list:[a-z0-9]{10}\](.*?)\[\/list:[a-z0-9]{10}\]/isS",
		"/\[list=[a-z0-9]{1}:[a-z0-9]{10}\](.*?)\[\/list:[a-z0-9]{1}:[a-z0-9]{10}\]/isS",

		"/\[link\]http:\/\/(.*?)\[\/link\]/isS",
		"/\[url\]http:\/\/(.*?)\[\/url\]/isS",
		"/\[url=\"http:\/\/(.*?)\"\](.*?)\[\/url\]/isS",
		"/\[url=http:\/\/(.*?)\](.*?)\[\/url\]/isS",

		"/\[link\](.*?)\[\/link\]/isS",
		"/\[url\](\S*?)\[\/url\]/isS",
		"/\[url=(.*?)\](.*?)\[\/url\]/isS",

		"/\[size=(.*?)\](.*?)\[\/size\]/isS",
		"/\[size=(.*?):[a-z0-9]{10}\](.*?)\[\/size:[a-z0-9]{10}\]/isS",

		"/\[\*:[a-z0-9]{10}\]/isS",
		"/\[\*\]/isS"
		);
		$string     = preg_replace( $patterns, "", $string) ;
		return $string;
	}

	/**
	 * Generates an array of search words out of a search string.
	 * Strips the search words of invalid special chars and checks if
	 * they are long enough.
	 * @param  array  $conf   The plugin's configuration vars
	 * @param  string $string The search string
	 * @return array          An array of search words
	 */
	function wordArray ($conf,$string) {
			// Remove special chars
		$string     = $this->clear_phpBB($string);
		$string     = strip_tags($string);
		$string		= preg_replace('/\W/',' ',$string);

		$wordArray  = explode(' ',$string);

		// Explodes the string into an array
		$clearWordArray = array();
		foreach($wordArray as $val) {
			$val = trim($val);

			$minLength = $conf['sword_minLength']?$conf['sword_minLength']:$conf['min_length'];

			if(strlen($val) >= $minLength) {
				$val = strtoupper($val);
				array_push($clearWordArray,$val);
			}
		}

		$clearWordArray = array_unique($clearWordArray);
		return $clearWordArray;
	}

	function get_posttext($post_id) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_posts_text',"post_id='$post_id' AND deleted='0' AND hidden='0'".$this->getPidQuery($this->conf),'','','1');
		$row    = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		return $row['post_text'];
	}

	/**
	 * Returns information about a certain topic.
	 * @param  int   $topic_id The topic UID
	 * @return array           The topic record as array
	 */
	function topic_information($topic_id) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_topics',"uid='$topic_id' AND hidden='0' AND deleted='0'".$this->getPidQuery($this->conf));
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		return  $row;
	}

	/**
	 * Returns information about a certain post.
	 * @param  int   $post_id The post UID
	 * @return array          The post record as array
	 */
	function post_information($post_id) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_mmforum_posts', "uid='$post_id' AND hidden='0' AND deleted='0'".$this->getPidQuery($this->conf));
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		return  $row;
	}

}


if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi4/class.tx_mmforum_indexing.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi4/class.tx_mmforum_indexing.php"]);
}

?>