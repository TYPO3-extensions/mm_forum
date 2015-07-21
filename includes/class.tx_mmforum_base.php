<?php
/**
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
 */
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

/**
 * Provides basic functionalities for all mm_forum plugins.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Benjamin Mack  <benni@typo3.org>
 * @version    2008-10-12
 * @package    mm_forum
 * @subpackage Core
 */
class tx_mmforum_base extends \TYPO3\CMS\Frontend\Plugin\AbstractPlugin {

	var $extKey = 'mm_forum';

	/**
	 * @var tx_mmforum_tools
	 */
	var $tools;

	/**
	 * @var tx_mmforum_validator
	 */
	var $validator;
	var $validatorObj; // same as the above, is kept for backwards-compatibility, will be deleted at some point, use the one above

	/**
	 * The TYPO3 database object
	 *
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseHandle;

	/**
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
	 */
	protected $local_cObj;

	/**
	 * @var tx_mmforum_cache
	 */
	protected $cache;

	/**
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
	 */
	public $cObj;

	/**
	 * @var mixed
	 */
	protected $limitCat;

	
	public function __construct() {
		$this->databaseHandle = $GLOBALS['TYPO3_DB'];
		$this->tools = GeneralUtility::makeInstance('tx_mmforum_tools');
		$this->validator = GeneralUtility::makeInstance('tx_mmforum_validator');
		$this->cache = GeneralUtility::makeInstance('tx_mmforum_cache');
		$this->local_cObj = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
		if ($this->cObj === NULL) {
			$this->cObj = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
		}
		parent::__construct();
	}

	/**
	 * Initializes all the things for a mm_forum plugin that is needed
	 * Should be run as the first thing when a plugin is called
	 *
	 * @param   string $conf the configuration array for the plugin
	 * @return  void
	 */
	function init($conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		/* Initialize cache object */
		$this->cache = tx_mmforum_cache::getGlobalCacheObject($this->conf['caching'], $this->conf['caching.']);

		/* get the PID List */
		if (!$this->conf['pidList']) {
			$this->conf['pidList'] = $this->pi_getPidList($this->cObj->data['pages'], $this->cObj->data['recursive']);
		}

		$this->conf['path_img'] = str_replace('EXT:mm_forum/', ExtensionManagementUtility::siteRelPath('mm_forum'), $this->conf['path_img']);
		$this->conf['path_smilie'] = str_replace('EXT:mm_forum/', ExtensionManagementUtility::siteRelPath('mm_forum'), $this->conf['path_smilie']);

		if (!class_exists('tx_pagebrowse_pi1')) {
			include_once ExtensionManagementUtility::extPath('pagebrowse') . 'pi1/class.tx_pagebrowse_pi1.php';
		}

		if ($this->conf['debug']) {
			$GLOBALS['TYPO3_DB']->debugOutput = true;
		}
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
		$altKey = str_replace('.', '-', $key);

		// first check the alternative syntax with the "-"
		$llKey = parent::pi_getLL($altKey);
		if ($llKey) {
			// additionally check for the informal option (once we know there is a
			if ($this->conf['informal'] && (parent::pi_getLL($altKey . '-inf'))) {
				$llKey = parent::pi_getLL($altKey . '-inf');
			}
		} else {
			$llKey = parent::pi_getLL($key);
			if ($this->conf['informal'] && (parent::pi_getLL($key . '-inf'))) {
				$llKey = parent::pi_getLL($key . '-inf');
			}
		}
		return $llKey;
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
			$text = $userData[$this->getUserNameField()];
		}
		return ((bool) $userData['deleted'] === true) ? $text : '<a href="' . $this->escapeURL($this->getUserProfileLink($userData)) . '">' . $text . '</a>';
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
		$pid = 0;
		if ($this->conf['pid_forum']) {
			$pid = intval($this->conf['pid_forum']);
		}

		if (!$pid) {
			$cacheRes = $this->cache->restore('forum_pid');
			if ($cacheRes !== null) return $cacheRes;

			$res = $this->databaseHandle->exec_SELECTquery(
				'pid',
				'tt_content',
				'list_type = "mm_forum_pi1" AND CType = "list" AND deleted = "0" AND hidden = "0" AND pi_flexform LIKE "%<value index=\"vDEF\">BOARD</value>%"'
			);
			if ($this->databaseHandle->sql_num_rows($res)) {
				list($pid) = $this->databaseHandle->sql_fetch_row($res);
			}

			$this->cache->save('forum_pid', $pid);
		}
		return $pid;
	}

	/**
	 * Determines the topic UID of a specific post.
	 * @param  int $postId  The post UID
	 * @return int          The topic UID
	 */
	function get_topic_id($postId) {
		$res = $this->databaseHandle->exec_SELECTquery('topic_id', 'tx_mmforum_posts', 'uid = ' . intval($postId) . $this->getStoragePIDQuery());
		list($topicId) = $this->databaseHandle->sql_fetch_row($res);
		return $topicId;
	}

	/**
	 * Returns the title of a specific topic determined by UID.
	 * @param  int    $topicId The UID of the topic
	 * @return string          The title of the topic
	 */
	function get_topic_name($topicId) {
		$res = $this->databaseHandle->exec_SELECTquery('topic_title', 'tx_mmforum_topics', 'uid=' . intval($topicId) . $this->getStoragePIDQuery());
		list($name) = $this->databaseHandle->sql_fetch_row($res);
		return $name;
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
	 * Builds a link to a specific post by determining the topic and the post's
	 * page in this topic by post UID.
	 * @param  int    $post_id The post UID
	 * @param  string $sword   The search word
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The URL
	 */
	function get_pid_link ($post_id, $sword, $conf) {
		$post_id = intval($post_id);
		$res = $this->databaseHandle->exec_SELECTquery(
			'topic_id,forum_id',
			'tx_mmforum_posts',
			'deleted=0 AND hidden=0 AND uid=\'' . $post_id . '\'' . $this->getStoragePIDQuery()
		);
		list($topic_id, $forum_id) = $this->databaseHandle->sql_fetch_row($res);

		$rows = $this->databaseHandle->exec_SELECTgetRows(
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
		if ($linkparams[$this->prefixId]['page'] < 1) {
			unset($linkparams[$this->prefixId]['page']);
		}
		if ($this->useRealUrl()) {
			$linkparams[$this->prefixId]['fid'] = $forum_id;
			$linkparams[$this->prefixId]['pid'] = $this->pi_getLL('realurl.page');
		}

		if ($sword) $linkparams[$this->prefixId]['sword'] = $sword;

		$link = $this->pi_getPageLink($this->getForumPID(), '', $linkparams);
		return $link . '#pid' . $post_id;
	}

	/**
	 * Generates an error message.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  array $conf The plugin's configuration vars
	 * @param  string $message The error message
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
	 * Returns the page UID of the plugin where you want the user profile to display.
	 *
	 * @return  int The page UID where the mm_forum plugin is placed on to display the user profiles
	 */
	function getUserProfilePID() {
		$profilePID = intval($this->conf['userProfilePID']);
		return ($profilePID > 0) ? $profilePID : $this->getForumPID();
	}

	/**
	 * Determines if this instance of the mm_forum is a moderated forum.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-07-19
	 * @return  boolean    true if the forum is moderated, otherwise false.
	 */
	function isModeratedForum() {
		return ($this->conf['moderated'] ? true : false);
	}

	/**
	 * Delivers the PID of newly created records.
	 * @return  int The PID of a record that is to be created.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-02
	 */
	function getStoragePID() {
		return (intval($this->conf['storagePID']) > 0 ? intval($this->conf['storagePID']) : 0);
	}

	/**
	 * Delivers a MySQL-WHERE query checking the records' PID.
	 * This allows it to exclusively select records from a very specific list
	 * of pages.
	 *
	 * @param   string $tables The list of tables that are queried
	 * @return  string         The query, following the pattern " AND pid IN (...)"
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-03
	 */
	function getStoragePIDQuery($tables = '') {
		$query = '';
		$storagePID = $this->getStoragePID();
		if ($storagePID > 0) {
			if (empty($tables)) {
				$query = ' AND pid = ' . $storagePID;
			} else {
				$tables = GeneralUtility::trimExplode(',', $tables);
				foreach ($tables as $table) {
					$query .= ' AND ' . $table . '.pid = ' . $storagePID;
				}
			}
		}
		return $query;
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
	function useRealUrl() {
		return ($this->conf['realUrl_specialLinks'] == '1') ? true : false;
	}

	/**
	 * Formats a date, and checks if there is a "%" in the dateFormat. If so, then
	 * the php function strftime() function is used, otherwise date() is used
	 *
	 * @param  int $tstamp the timestamp
	 * @return boolean The formatted date
	 */
	function formatDate($tstamp) {
		$format = $this->conf['dateFormat'];

		if (strpos($format, '%') === false) {
			return date($format, $tstamp);
		} else {
			return strftime($format, $tstamp);
		}
	}

	/**
	 * Wraps a string to make it safe for outputting to the browser
	 *
	 * @param   string $string the uncertain variable
	 * @return  string  a string ready to output
	 */
	function escape($string) {
		return $this->validator->specialChars(strval($string));
	}

	/**
	 * Wraps a URL to make it safe for outputting to the browser
	 *
	 * @param   string $url the uncertain variable
	 * @return  string  a string ready to output
	 */
	function escapeURL($url) {
		return $this->validator->specialChars_URL(strval($url));
	}

	/**
	 * Highlights certain words in a text. Highlighting is done by applying a
	 * specific wrap defined in TypoScript (plugin.tx_mmforum_pi1.list_posts.highlight_wrap).
	 * @param  string $text The text, in which the words are to be highlighted
	 * @param  array $words An array of words that are to be highlighted
	 * @return string        The text with highlighted words.
	 */
	function highlight_text($text, $words) {
		$word_array = explode(' ', $words);
		foreach ($word_array as $needle) {
			if (trim($needle)) {
				$needle = preg_quote($needle);
				$needle = str_replace('/', '\\/', $needle);

				preg_match_all("/<(.*?)$needle(.*?)>/i", $text, $htmltags);
				$placemark = chr(1) . chr(1) . chr(1);
				$text = preg_replace("/<(.*?)$needle(.*?)>/i", $placemark, $text);

				$replace = $this->cObj->wrap('\\0', $this->conf['list_posts.']['highlight_wrap']);
				$text = preg_replace("/$needle/i", $replace, $text);

				if (count($htmltags[0])) {
					foreach ($htmltags[0] as $htmltag) {
						$text = preg_replace('/' . $placemark . '/', "$htmltag", $text, 1);
					}
				}
			}
		}
		return $text;
	}

	/**
	 * Returns a complete Image HTML String
	 * Generates a complete XHTML img-tag from parameters submitted in an
	 * associative array.
	 *
	 * @param array $imgInfo The parameters for image creation as associative array.
	 *                        The array is to be generated according to the following pattern:
	 *                             'src'           => Image source file
	 *                          'width'         => Image width in pixels
	 *                          'height'        => Image height in pixels
	 *                          'border'        => Image border width in pixels
	 *                          'alt'           => Substitute text in case image is not found
	 *                          'title'         => Description of the image at MouseOver
	 * @return string         The XHTML img-tag
	 */
	function buildImageTag($imgInfo) {
		$defaultInfo = array(
			'border' => $this->conf['img_border'],
			'alt'    => '',
			'src'    => '',
			'style'  => '',
			'width'  => '',
			'height' => '',
		);
		if (is_array($imgInfo)) {
			$imgInfo = array_merge($defaultInfo, $imgInfo);
		} else {
			$imgInfo = $defaultInfo;
		}

		if ($imgInfo['src'] == '') {
			$imgTag = '';
		} else {

			# Get width/height from image if not provided
			# This fixes an Internet Explorer error with transparent PNGs.
			#
			# See http://forge.typo3.org/issues/12120
			# Credits to Urs Weiss
			if ($imgInfo['width'] == '' || $imgInfo['height'] == '') {
				$imgSize = getimagesize($imgInfo['src']);
				if ($imgSize !== false) {
					if ($imgInfo['width'] == '') {
						$imgInfo['width'] = $imgSize[0];
					}

					if ($imgInfo['height'] == '') {
						$imgInfo['height'] = $imgSize[1];
					}
				}
			}

			$imgTag = '<img src="' . $imgInfo['src'] . '" ';
			if (strlen($imgInfo['width'])) {
				$imgTag .= 'width="' . $imgInfo['width'] . '" ';
			}
			if (strlen($imgInfo['height'])) {
				$imgTag .= 'height="' . $imgInfo['height'] . '" ';
			}
			$imgTag .= 'style="border: ' . ((strlen($imgInfo['border']) > 0) ? $imgInfo['border'] : 0) . 'px;" ';
			$imgTag .= 'alt="' . $imgInfo['alt'] . '" ';
			if (strlen($imgInfo['title'])) {
				$imgTag .= 'title="' . $imgInfo['title'] . '" ';
			}
			$imgTag .= '/>';
		}
		return $imgTag;
	}

	/**
	 * Gets the UID of the administrator group.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-10-07
	 * @return  int The UID of the administrator group
	 */
	function getAdminGroup() {
		return $this->conf['grp_admin'];
	}

	function getAdministratorGroup() {
		return $this->getAdminGroup();
	}

	/**
	 * Gets the UID of all moderator groups.
	 * This function gets an array of the UIDs of all moderator groups,
	 * meaning all groups that have moderation access to at least one
	 * forum.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-10-07
	 * @return  array An array of moderator group UIDs
	 */
	function getModeratorGroups() {
		$cacheRes = $this->cache->restore('moderator_groups');

		if ($cacheRes === null) {
			$res = $this->databaseHandle->exec_SELECTquery(
				'GROUP_CONCAT(grouprights_mod)',
				'tx_mmforum_forums',
				'deleted=0 '.$this->getStoragePIDQuery()
			);
			list($groups) = $this->databaseHandle->sql_fetch_row($res);

			$groupArr = GeneralUtility::intExplode(',', $groups, true);
			$result = array_unique($groupArr);

			$this->cache->save('moderator_groups', $result, true);

			return $result;
		} else {
			return $cacheRes;
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
			$res = $this->databaseHandle->exec_SELECTquery(
				'f.*',
				'tx_mmforum_forums f, tx_mmforum_topics t',
				't.uid=' . intval($topic) . ' AND f.uid=t.forum_id'
			);
			$arr = $this->databaseHandle->sql_fetch_assoc($res);

			return $this->getMayRead_forum($arr);

		} else {
			return $this->getMayRead_forum($topic['forum_id']);
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

		$userId = $this->getUserID();

		// If the $forum parameter is not an array, treat it as a forum UID
		if (!is_array($forum)) {

			$forum = intval($forum);

			/* Try to load read access from cache. If the regarding property is
			 * stored in the cache, return the result now, otherwise load the board
			 * record from the database. */
			$cacheRes = $this->cache->restore('getMayRead_forum_' . $userId . '_' . $forum);
			if ($cacheRes !== null) {
				return $cacheRes;
			} else {
				$forum = $this->getBoardData($forum);
			}

		} else {
			$cacheRes = $this->cache->restore('getMayRead_forum_' . $userId . '_' . $forum['uid']);
			if ($cacheRes !== null) {
				return $cacheRes;
			}
		}

		// If the current user has moderation or even administration access to this board, just return TRUE in any case.
		if ($this->getIsModOrAdmin($forum['uid'])) {
			return true;
		}

		// If this forum has a parent category, check the access rights for this parent category, too.
		if ($forum['parentID']) {
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
		$authRead = GeneralUtility::trimExplode(',',$authRead);

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
	 * Retrieces a board records from database.
	 * This function retrieves a board record from the tx_mmforum_forums table in
	 * the database as an associative array.
	 *
	 * @param  int   $uid The board's uid
	 * @return array      The board record as associative array
	 */
	function getBoardData($uid) {
		$uid = intval($uid);
		$res = $this->databaseHandle->exec_SELECTquery(
			'*',
			'tx_mmforum_forums',
			"uid='$uid'"
		);
		return $this->databaseHandle->sql_fetch_assoc($res);
	}

	/**
	 * Loads a topic record from database.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 11. 01. 2007
	 * @param   int   $topicId The topic UID
	 * @return  array          The topic record as associative array.
	 */
	function getTopicData($topicId) {
		$res = $this->databaseHandle->exec_SELECTquery(
			'*',
			'tx_mmforum_topics',
			'uid = ' . intval($topicId) . ' AND deleted = "0" AND hidden = "0"' . $this->getStoragePIDQuery()
		);
		return (($this->databaseHandle->sql_num_rows($res) == 0) ? false : $this->databaseHandle->sql_fetch_assoc($res));
	}

	/**
	 * Generates a page navigation menu. The number of pages is determined by the amount
	 * of records in the database meeting a certain condition and the maximum number of
	 * records on one page.
	 * @param  string $table      The table name, from which records are displayed
	 * @param  string $column     The table column, that has to meet a certain condition
	 * @param  mixed  $id         The value the table column $column has to meet
	 * @param  int    $limitcount The maximum number of records on one page
	 * @param  mixed    $count      Optional parameter. Allows to override the record count
	 *                           determined by parameters $column and $id
	 * @return string            The page navigation menu
	 */
	function pagecount ($table,$column,$id,$limitcount = 10,$count=FALSE) {
		$id = intval($id);
		$column = preg_replace("/[^A-Za-z0-9\._]/",'',$column);
		$res = $this->databaseHandle->exec_SELECTquery(
			"COUNT($column)",
			$table,
			"deleted='0' AND hidden='0' AND $column='$id'".$this->getStoragePIDQuery()
		);
		list($postcount) = $this->databaseHandle->sql_fetch_row($res);

		if (! ($count === FALSE)) $postcount = intval($count);

		$maxpage = ceil($postcount / $limitcount);

		// should Dmitry's pagebrowse extension be used?
		if (intval($this->conf['doNotUsePageBrowseExtension'])===0) {
			$content = $this->getListGetPageBrowser($maxpage);
			return $content;
		}

		if ($this->piVars['page'] == 0) $page = 1;
		else $page = $this->piVars['page'];

		$linkParams = array();

		if ($table == "tx_mmforum_topics") {
			if ($column == 'topic_is') {
				$linkParams[$this->prefixId]['action']='list_prefix';
				if ($this->piVars['list_prefix']) {
					$settings = $this->piVars['list_prefix'];
					$linkParams[$this->prefixId]['list_prefix']['show'] = $settings['show'];
					$linkParams[$this->prefixId]['list_prefix']['order'] = $settings['order'];
					$linkParams[$this->prefixId]['list_prefix']['prfx'] = $settings['prfx'];
				}
			} elseif ($column != 'topic_replies') {
				$linkParams[$this->prefixId]['action'] = 'list_topic';
				$linkParams[$this->prefixId]['fid'] = $this->piVars['fid'];

				if ($this->useRealUrl()) {
					$linkParams[$this->prefixId]['tid'] = 'pages';
					$linkParams[$this->prefixId]['pid'] = 'page';
				}
			} else {
				$linkParams[$this->prefixId]['action'] = 'list_unans';
			}
		}
		if ($table == "tx_mmforum_posts"){
			$linkParams[$this->prefixId]['action'] = 'list_post';
			$linkParams[$this->prefixId]['tid'] = $this->piVars['tid'];
		}

		$content = '';
		if ($maxpage > 1) {
			if ($this->piVars['hide_solved']) $linkParams[$this->prefixId]['hide_solved']='1';

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
	 * Sets the solved status of a topic.
	 * @param  int  $topicId  The UID of the topic
	 * @param  bool $solved   The desired solved status of the topic
	 * @return void
	 */
	function set_solved($topicId, $solved) {
		$updateArray = array(
			'solved'    => intval($solved),
			'tstamp'    => $GLOBALS['EXEC_TIME']
		);
		$this->databaseHandle->exec_UPDATEquery('tx_mmforum_topics', 'uid = ' . intval($topicId), $updateArray);
	}

	/**
	 * Creates a rootline menu for navigating over board and board category when in
	 * topic view.
	 * @param  int    $forumid The UID of the current board.
	 * @param  int    $topicid The UID of the current topic.
	 * @return string          The rootline menu
	 */
	function get_forum_path ($forumid,$topicid) {
		$forumpath_index = $this->pi_linkTP($this->pi_getLL('board.rootline'));

		$res = $this->databaseHandle->exec_SELECTquery(
			"parentID, forum_name",
			"tx_mmforum_forums",
			"uid = '".$forumid."'".$this->getStoragePIDQuery()
		);
		list($catid, $forumpath_forum) = $this->databaseHandle->sql_fetch_row($res);

		$res = $this->databaseHandle->exec_SELECTquery(
			"forum_name",
			"tx_mmforum_forums",
			"uid = '".$catid."'".$this->getStoragePIDQuery()
		);
		list($forumpath_category) = $this->databaseHandle->sql_fetch_row($res);

		$res = $this->databaseHandle->exec_SELECTquery(
			"topic_title",
			"tx_mmforum_topics",
			" uid = '".$topicid."'".$this->getStoragePIDQuery()
		);
		list($forumpath_topic) = $this->databaseHandle->sql_fetch_row($res);

		if ( $forumpath_category)    $forumpath_category   = $this->conf['display.']['rootline.']['separator'].'<a href="'.$this->pi_getPageLink($GLOBALS['TSFE']->id).'#cat'.$catid.'">'.$this->escape($forumpath_category).'</a>';
		if ( $forumpath_forum)       $forumpath_forum      = $this->conf['display.']['rootline.']['separator'].$this->pi_linkTP($this->escape($forumpath_forum),array('tx_mmforum_pi1[action]'=>'list_topic','tx_mmforum_pi1[fid]'=>$forumid));
		if ( $forumpath_topic)       $forumpath_topic      = $this->conf['display.']['rootline.']['separator'].$this->escape($forumpath_topic);

		$pathcontent = $forumpath_index.$forumpath_category.$forumpath_forum;
		return $pathcontent;
	}

	/**
	 * Generates a topic icon.
	 * The icon generated depends on various topic attributes such as
	 * read/closed status etc.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 11. 01. 2007
	 * @param   mixed $topic The topic data. May either be a topic UID or a topic record
	 *                        as associative array.
	 * @param $readarray
	 * @return  string        The topic icon as HTML img tag
	 */
	function getTopicIcon($topic, $readarray=-1) {
		if (!is_array($topic)) {
			$topic = $this->getTopicData(intval($topic));
		}
		$userId = intval(isset($GLOBALS['TSFE']->fe_user->user['uid']) ? $GLOBALS['TSFE']->fe_user->user['uid'] : 0);

		if ($userId && $readarray==-1) {
			if (!isset($GLOBALS['tx_mmforum_pi1']['readarray'])) {
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
		} elseif ($topicIconMode == 'classic') {
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
	 * Returns the topic icon mode. This will either be 'classic' or 'modern'
	 *
	 * @return	string		The topic icon mode (either 'classic' or 'modern')
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 */
	function getTopicIconMode() {
		return ($this->conf['topicIconMode'] ? $this->conf['topicIconMode'] : 'modern');
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

		if (!$uid) return array();

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
		$unread    = $this->databaseHandle->exec_SELECTquery(
			$select ,
			'tx_mmforum_posts a',
			$where
		);

		$res = array();
		while ($row = $this->databaseHandle->sql_fetch_assoc($unread)) {
			if ($filter['onlyCategories']) {
				$res[] = $row['forum_id'];
			} else {
				$res[] = $row['topic_id'];
			}
		}
		//GeneralUtility::debug (count($res), 'number of unread posts : took ms: ' . (microtime(true) - $starttime));
		return $res;
	}

	/**
	 * Returns the UID of the currently logged in fe_user.
	 *
	 * @return int The UID of the current fe_user
	 */
	function getUserID() {
		return intval($GLOBALS['TSFE']->fe_user->user['uid']);
	}

	/**
	 * Determines if the user that is currently logged in is an administrator.
	 *
	 * @return boolean  TRUE, if the user that is currently logged in is an administrator.
	 */
	function getIsAdmin() {
		if ($GLOBALS['TSFE']->fe_user->user['username'] == '') {
			return false;
		} else {
			return in_array($this->conf['grp_admin'], $GLOBALS['TSFE']->fe_user->groupData['uid']);
		}
	}

	/**
	 * Determines if the user that is currently logged in is an moderator.
	 *
	 * @param int $forum
	 * @return boolean  TRUE, if the user that is currently logged in is an moderator.
	 */
	function getIsMod($forum = 0) {
		if ($GLOBALS['TSFE']->fe_user->user['username'] == '') {
			return false;
		}

		$userId = $this->getUserID();
		$cacheRes = $this->cache->restore("userIsMod_{$userId}_{$forum}");

		if ($cacheRes !== null) {
			return $cacheRes;
		}

		$res = $this->databaseHandle->exec_SELECTquery(
			'c.grouprights_mod as category_auth, f.grouprights_mod as forum_auth',
			'tx_mmforum_forums f LEFT JOIN tx_mmforum_forums c ON f.parentID=c.uid',
			'f.uid=' . intval($forum) . ' AND f.deleted=0'
		);
		if (!$res || $this->databaseHandle->sql_num_rows($res) == 0) {
			return false;
		}

		list($category_auth, $forum_auth) = $this->databaseHandle->sql_fetch_row($res);

		$category_auth = GeneralUtility::intExplode(',', $category_auth);
		$forum_auth = GeneralUtility::intExplode(',', $forum_auth);

		$auth = array_merge($category_auth, $forum_auth);
		$auth = array_unique($auth);

		$intersect = array_intersect($GLOBALS['TSFE']->fe_user->groupData['uid'], $auth);

		$isMod = count($intersect) > 0;

		$this->cache->store("userIsMod_{$userId}_{$forum}", $isMod);

		return $isMod;
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
		if ($cacheRes !== null) {
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
		if (!in_array($this->getBaseUserGroup(), $groups)) {
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
		$query = sprintf(' AND ((%s) OR grouprights_write=\'\') ', $query);

		// Save generated query to cache and return
		$this->cache->save('getMayWrite_forum_query_' . $userId, $query);
		return $query;
	}

	/**
	 * Generates a MySQL-query part to select only a set of predefined categories.
	 * @param  string $tablename The table name
	 * @return string            The MySQL-query part
	 */
	function getCategoryLimit_query($tablename='') {
		if (!$this->limitCat) return '';

		$prefix = $tablename ? "$tablename." : '';
		$query = " AND $prefix"."uid IN (".$this->limitCat.")";
		return $query;
	}

	/**
	 * Returns the board UID of a topic
	 * @param  int $topicId The topic UID
	 * @return int          The board UID
	 **/
	function get_forum_id($topicId) {
		$res = $this->databaseHandle->exec_SELECTquery('forum_id', 'tx_mmforum_topics', 'uid = ' . intval($topicId) . $this->getStoragePIDQuery());
		list($forumId) = $this->databaseHandle->sql_fetch_row($res);
		return $forumId;
	}

	/**
	 * Determines if the user that is currently logged in is an administrator or a moderator.
	 *
	 * @param int $forum
	 * @return boolean  TRUE, if the user that is currently logged in is an
	 *                  administrator or a moderator.
	 */
	function getIsModOrAdmin($forum = 0) {
		return ($this->getIsMod($forum) || $this->getIsAdmin());
	}

	/**
	 * Jumps back to the previous page via an HTTP redirect
	 *
	 * @return    boolean    checks if the referrer
	 */
	function redirectToReferrer() {
		// Redirecting visitor back to previous page
		$ref = GeneralUtility::getIndpEnv('HTTP_REFERER');
		if ($ref) {
			HttpUtility::redirect($ref);
		} else {
			return false;
		}
	}

	/**
	 * Gets the UID of the main mm_forum user group.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 0.1.8-090410
	 * @return  int The UID of the main mm_forum user group.
	 */
	function getBaseUserGroup() {
		return $this->conf['userGroup'];
	}

	/**
	 * Gets an instance of the tx_ratings_api class.
	 * This function returns an instance of the tx_ratings_api class. The
	 * class is only instantiated once. If the 'ratings' extension is not
	 * installed, this function returns NULL.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 0.1.8-090410
	 * @return  tx_ratings_api An instance of the tx_ratings_api class or
	 *                         NULL if the ratings extension is not installed.
	 */
	function getRatingInstance() {
		if (! ExtensionManagementUtility::isLoaded('ratings')) {
			return null;
		}

		if (isset($this->rating)) {
			return $this->rating;
		} else {
			$this->rating = GeneralUtility::makeInstance('tx_ratings_api');
			$this->ratingConf = $this->rating->getDefaultConfig();
			$this->ratingConf['templateFile'] = $this->conf['stylePath'] . '/rating/ratings.html';

			return $this->rating;
		}
	}

	/**
	 * Displays a rating form using the API of the 'ratings' extension.
	 * Rated records are identified by a combination of the table name and
	 * the record's uid.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 0.1.8-090410
	 * @param  string $table The table name of the rated record (e.g. fe_users
	 *                       or tx_mmforum_topics)
	 * @param  int $uid The uid of the rated record.
	 * @return string        The HTML code of the ratings form
	 */
	function getRatingDisplay($table, $uid) {
		$rating =& $this->getRatingInstance();
		return $rating != null ? $rating->getRatingDisplay("{$table}_{$uid}", $this->ratingConf) : '';
	}

	/**
	 * @param $numberOfPages
	 * @param array $additionalParameters
	 * @return string
	 */
	function getListGetPageBrowser($numberOfPages, $additionalParameters = array()) {
		// Get default configuration
		$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_pagebrowse_pi1.'];

		// Modify this configuration
		$conf['pageParameterName'] = $this->prefixId . '|page';
		$conf['numberOfPages'] = $numberOfPages;

		if (count($additionalParameters) > 0) {
			$conf['extraQueryString'] = GeneralUtility::implodeArrayForUrl(null, $additionalParameters);
		}

		// Get page browser
		$cObj = GeneralUtility::makeInstance('tslib_cObj');

		/* @var $cObj tslib_cObj */
		$cObj->start(array(), '');
		return $cObj->cObjGetSingle('USER_INT', $conf);
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
		if (! ExtensionManagementUtility::isLoaded('ratings')) return false;
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
	function isTopicRating() {
		return $this->isRating('topics');
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
		if (!is_array($topic)) {

			$topic = intval($topic);

			// Look in the cache. In case of a hit, just return the result
			$cacheRes = $this->cache->restore('getMayWrite_topic_' . $topic . '_' . $userId);
			if ($cacheRes !== null) {
				return $cacheRes;
			}

			// Load the topic's forum UID
			$res = $this->databaseHandle->exec_SELECTquery(
				'f.*',
				'tx_mmforum_forums f, tx_mmforum_topics t',
				't.uid="'.$topic.'" AND f.uid = t.forum_id'
			);
			$arr = $this->databaseHandle->sql_fetch_assoc($res);
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
		if ($id==0) $id = intval($this->fid) == 0 ? $GLOBALS['TSFE']->id : intval($this->fid);

		$buttonObj  = $this->conf['buttons.'][$small?'small':'normal'];
		$buttonConf = $this->conf['buttons.'][$small?'small.':'normal.'];

		if (!is_array($params)) {
			if (preg_match('/^profileView:([0-9]+?)$/',$params,$matches)) {
				$href = $this->getUserProfileLink($matches[1]);
			}
		}

		$data = array(
			'button_label'		 => $this->pi_getLL('button.'.$label,$label),
			'button_link'        => $nolink?'':($href?$href:$this->pi_getPageLink($id,'',$params)),
			'button_iconname'    => file_exists($this->conf['path_img'].'buttons/icons/'.$label.'.png') ? $label.'.png' : '',
			'button_atagparams'  => $atagparams
		);
		if ($data['button_link']{0} === '?') $data['button_link'] = '/'.$data['button_link'];
		$oldData = $this->cObj->data;
		$this->cObj->data = $data;

		$button = $this->cObj->cObjGetSingle($buttonObj,$buttonConf);
		$this->cObj->data = $oldData;

		return $button;
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
		$prefixId = $this->prefixId;
		$linkParams[$prefixId] = array(
			'action'  => 'forum_view_profil',
			'user_id' => $userData['uid']
		);

		if ($this->useRealUrl()) {
			unset($linkParams[$prefixId]['user_id']);
			$linkParams[$prefixId]['fid'] = $userData['username'];
		}
		$link = $this->pi_getPageLink($this->getUserProfilePID(), '', $linkParams);

		// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['profileLink_postLinkGen'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['forum']['profileLink_postLinkGen'] as $_classRef) {
				$_procObj = & GeneralUtility::getUserObj($_classRef);
				$link = $_procObj->userProfileLink($userData, $link, $this);
			}
		}
		return $link;
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
		if (!$userId) {
			return false;
		}

		// If the $forum parameter is no array, treat the parameter as forum UID instead
		if (!is_array($forum)) {

			// Parse to int for security reasons
			$forum = intval($forum);

			// Search for result in cache. In case of a hit, return the result at once.
			$cacheRes = $this->cache->restore('getMayWrite_forum_'.$userId.'_'.$forum);
			if ($cacheRes !== null) return $cacheRes;

			// Otherwise load the complete board record.
			$forum = $this->getBoardData($forum);
		}

		/* If this has not been done already, look into the cache now
		 * and return the result in the case of a hit. */
		if (!isset($cacheRes)) {
			$cacheRes = $this->cache->restore('getMayWrite_forum_'.$userId.'_'.$forum['uid']);
			if ($cacheRes !== null) return $cacheRes;
		}

		/* If the current user has moderation or even administration
		 * access to this board, just return TRUE in any case. */
		if ($this->getIsModOrAdmin($forum['uid'])) return true;

		// If the forum has got a parent category, check the access rights for this category, too.
		if ($forum['parentID'])
			if (!$this->getMayWrite_forum($forum['parentID'])) return false;

		// Load all groups that have write access to this forum
		$authWrite = tx_mmforum_tools::getParentUserGroups($forum['grouprights_write']);

		/* If no groups with write access have been specified, everyone
		 * can write, so just return true. */
		$authWrite = GeneralUtility::intExplode(',',$authWrite);
		$authWrite = $this->tools->processArray_numeric($authWrite);
		if (count($authWrite)==0) {
			$this->cache->save('getMayWrite_forum_'.$userId.'_'.$forum['uid'],true);
			return true;
		}

		// Load current user's groups
		$groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
		$groups = tx_mmforum_tools::processArray_numeric($groups);

		/* Check if the user is in the base user group. If this is not the
		 * case, the user is not allowed to write anywhere. */
		if (!in_array($this->getBaseUserGroup(), $groups)) {
			$this->cache->save("getMayWrite_forum_{$userId}_{$forum['uid']}", false);
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
					$_procObj = & GeneralUtility::getUserObj($_classRef);
					$marker = $_procObj->userInformation_marker($marker, $userData, $this);
				}
			}

			$content = $this->cObj->substituteMarkerArray($template, $marker);
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
				$content .= $this->tools->res_img($conf['path_avatar'] . $userData['tx_mmforum_avatar'], $conf['avatar_height'], $conf['avatar_width']);
			}

			$content .= $this->cObj->wrap($this->pi_getLL('user.regSince') . ': ' . date('d.m.Y', $userData['crdate']) . '<br />' . $this->pi_getLL('user.posts') . ': ' . $userData['tx_mmforum_posts'], $this->conf['list_posts.']['userinfo_content_wrap']);
		}

		return $content;
	}

	/**
	 * Determines if user rating is enabled.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 0.1.8-090410
	 * @return  boolean TRUE if user rating is enabled, FALSE is user rating
	 *                  is disabled or the 'ratings' extension is not installed.
	 */
	function isUserRating() {
		return $this->isRating('users');
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
					list($feuserImg) = GeneralUtility::trimExplode(',', $feuserImg);
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
	 * Returns the userranking of the user determined by the user's
	 * usergroup.
	 * @param  int    $userId The user's UID
	 * @param  array  $conf   The plugin's configuration vars
	 * @return string         The user's ranking.
	 **/
	function get_userranking($userId, $conf) {
		$userRanksObj = GeneralUtility::makeInstance('tx_mmforum_ranksFE');
		$userRanksObj->init($this);
		$userRanksObj->setContentObject($this->cObj);
		return $userRanksObj->displayUserRanks($userId);
	}


	/**
	 * DEPRECATED METHODS
	 * (kept for compatibility reasons
	 */

	/**
	 * @deprecated: use the $this->getStoragePID() method
	 */
	function getFirstPid() {
		return $this->getStoragePID();
	}

	/**
	 * @deprecated: use the direct call to the tools method
	 */
	function randkey($length) {
		return $this->tools->generateRandomString($length);
	}

	/**
	 * Converts a hexadecimal string into an IP Address
	 * @deprecated: use the direct call to the tools method
	 * @param  string $hex The hexadecimal string
	 * @return string      The IP Address
	 */
	function hex2ip($hex) {
		return $this->tools->hex2ip($hex);
	}

	/**
	 * @deprecated: use the direct call to the tools method
	 */
	function ip2hex($val) {
		return $this->tools->ip2hex($val);
	}

	/**
	 * @deprecated: use the direct call to the tools method
	 */
	function getAbsUrl($link) {
		return $this->tools->getAbsoluteUrl($link);
	}

	/**
	 * @deprecated: use the direct call to the tools method
	 */
	function appendTrailingSlash($str) {
		return $this->tools->appendTrailingSlash($str);
	}

	/**
	 * @deprecated: use the direct call to the tools method
	 */
	function removeLeadingSlash($str) {
		return $this->tools->removeLeadingSlash($str);
	}

	/**
	 * @deprectated, now it's only a wrapper, will be removed in future versions
	 * use $this->isModeratedForum();
	 */
	function getIsModeratedBoard() {
		return $this->isModeratedForum();
	}

	/**
	 * @deprectated, now it's only a wrapper, will be removed in future versions
	 * use $this->useRealUrl();
	 */
	function getIsRealURL() {
		return $this->useRealUrl();
	}

	/**
	 * @deprectated, now it's only a wrapper, will be removed in future versions
	 * use $this->escapeURL();
	 */
	function shieldURL($url) {
		return $this->escapeURL($url);
	}

	/**
	 * @deprectated, now it's only a wrapper, will be removed in future versions
	 * use $this->escape();
	 */
	function shield($str) {
		return $this->escape($str);
	}

	/**
	 * @deprectated, now it's only a wrapper, will be removed in future versions
	 * use $this->getStoragePIDQuery();
	 */
	function getPidQuery($tbl = '') {
		return $this->getStoragePIDQuery($tbl);
	}

	/**
	 * @deprecated: use the buildImageTag() function now
	 */
	function imgtag($imgInfo, $debug = TRUE) {
		return $this->buildImageTag($imgInfo);
	}
}

// does not make sense to XCLASS here
