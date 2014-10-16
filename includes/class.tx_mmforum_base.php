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
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   80: class tx_mmforum_base extends tslib_pibase
 *   94:     function init($conf)
 *  144:     function pi_getLL($key)
 *  175:     function getForumPID()
 *  204:     function getUserProfilePID()
 *  216:     function isModeratedForum()
 *  227:     function getStoragePID()
 *  241:     function getStoragePIDQuery($tables = '')
 *  268:     function useRealUrl()
 *  280:     function formatDate($tstamp)
 *  296:     function escape($string)
 *  306:     function escapeURL($url)
 *  318:     function highlight_text($text, $words)
 *  358:     function buildImageTag($imgInfo)
 *  396:     function getFirstPid()
 *  405:     function randkey($length)
 *  415:     function hex2ip($hex)
 *  424:     function ip2hex($val)
 *  433:     function getAbsUrl($link)
 *  442:     function appendTrailingSlash($str)
 *  451:     function removeLeadingSlash($str)
 *  461:     function getIsModeratedBoard()
 *  471:     function getIsRealURL()
 *  482:     function shieldURL($url)
 *  493:     function shield($str)
 *  504:     function getPidQuery($tbl = '')
 *  533:     function imgtag($imgInfo, $debug = TRUE)
 *  544:     function getAdminGroup()
 *  559:     function getModeratorGroups()
 *  585:     function getUserID()
 *
 * TOTAL FUNCTIONS: 30
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(PATH_tslib . 'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_tools.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_validator.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/cache/class.tx_mmforum_cache.php');

	/**
	 * Provides basic functionalities for all mm_forum plugins.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @author     Benjamin Mack  <benni@typo3.org>
	 * @version    2008-10-12
	 * @package    mm_forum
	 * @subpackage Core
	 */
class tx_mmforum_base extends tslib_pibase {
	var $extKey = 'mm_forum';

	/**
	 * @var tx_mmforum_tools
	 */
	var $tools;
	/**
	 * @var tx_mmforum_validator
	 */
	var $validator;
	var $validatorObj;	// same as the above, is kept for backwards-compatibility, will be deleted at some point, use the one above


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

			/* Initialize validator */
		$this->validator = tx_mmforum_validator::getValidatorObject();
		$this->validatorObj = &$this->validator;

			/* Initialize tools */
		$this->tools = t3lib_div::makeInstance('tx_mmforum_tools');

			/* Initialize cache object */
		$this->cache = &tx_mmforum_cache::getGlobalCacheObject($this->conf['caching'], $this->conf['caching.']);

			/* Local cObj */
		$this->local_cObj = t3lib_div::makeInstance('tslib_cObj');

			/* get the PID List */
		if (!$this->conf['pidList']) {
			$this->conf['pidList'] = $this->pi_getPidList($this->cObj->data['pages'], $this->cObj->data['recursive']);
		}

		$this->conf['path_img']    = str_replace('EXT:mm_forum/', t3lib_extMgm::siteRelPath('mm_forum'), $this->conf['path_img']);
		$this->conf['path_smilie'] = str_replace('EXT:mm_forum/', t3lib_extMgm::siteRelPath('mm_forum'), $this->conf['path_smilie']);

		If(!class_exists('tx_pagebrowse_pi1')) {
			Include_Once t3lib_extMgm::extPath('pagebrowse').'pi1/class.tx_pagebrowse_pi1.php';
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
			if($cacheRes !== null) return $cacheRes;

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'pid',
				'tt_content',
				'list_type = "mm_forum_pi1" AND CType = "list" AND deleted = "0" AND hidden = "0" AND pi_flexform LIKE "%<value index=\"vDEF\">BOARD</value>%"'
			);
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
				list($pid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
			}

			$this->cache->save('forum_pid', $pid);
		}
		return $pid;
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
	* @return  boolean	true if the forum is moderated, otherwise false.
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
				$tables = t3lib_div::trimExplode(',', $tables);
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
	 * @param  int     the timestamp
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
	 * @param   string  the uncertain variable
	 * @return  string  a string ready to output
	 */
	function escape($string) {
		return $this->validator->specialChars(strval($string));
	}

	/**
	 * Wraps a URL to make it safe for outputting to the browser
	 *
	 * @param   string  the uncertain variable
	 * @return  string  a string ready to output
	 */
	function escapeURL($url) {
		return $this->validator->specialChars_URL(strval($url));
	}

	/**
	 * Highlights certain words in a text. Highlighting is done by applying a
	 * specific wrap defined in TypoScript (plugin.tx_mmforum_pi1.list_posts.highlight_wrap).
	 * @param  string $text  The text, in which the words are to be highlighted
	 * @param  array  $words An array of words that are to be highlighted
	 * @return string        The text with highlighted words.
	 */
	function highlight_text($text, $words) {
		$word_array = explode(' ', $words);
		foreach ($word_array as $needle) {
			if (trim($needle)) {
				$needle    = preg_quote($needle);
				$needle    = str_replace('/', '\\/', $needle);

				$check     = preg_match_all("/<(.*?)$needle(.*?)>/i", $text, $htmltags);
				$placemark = chr(1).chr(1).chr(1);
				$text      = preg_replace("/<(.*?)$needle(.*?)>/i", $placemark, $text);

				$replace   = $this->cObj->wrap('\\0', $this->conf['list_posts.']['highlight_wrap']);
				$text      = preg_replace("/$needle/i", $replace, $text);

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
					if ($imgInfo['width'] == '')
						$imgInfo['width'] = $imgSize[0];

					if ($imgInfo['height'] == '')
						$imgInfo['height'] = $imgSize[1];
				}
			}

			$imgTag = '<img src="' . $imgInfo['src'] . '" ';
			if (strlen($imgInfo['width'])) {
				$imgTag .= 'width="' . $imgInfo['width'] . '" ';
			}
			if (strlen($imgInfo['height'])) {
				$imgTag .= 'height="' . $imgInfo['height'] . '" ';
			}
			$imgTag .= 'style="border: ' . ((strlen($imgInfo['border']) > 0) ? $imgInfo['border'] : 0 ) . 'px;" ';
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

		if($cacheRes === null) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'GROUP_CONCAT(grouprights_mod)',
				'tx_mmforum_forums',
				'deleted=0 '.$this->getStoragePIDQuery()
			);
			list($groups) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

			$groupArr = t3lib_div::intExplode(',', $groups, true);
			$result   = array_unique($groupArr);

			$this->cache->save('moderator_groups', $result, true);

			return $result;
		} else return $cacheRes;
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
	 * @return boolean  TRUE, if the user that is currently logged in is an moderator.
	 */
	function getIsMod($forum=0) {
		if($GLOBALS['TSFE']->fe_user->user['username']=="") return false;

		$userId = $this->getUserID();
		$cacheRes = $this->cache->restore("userIsMod_{$userId}_{$forum}");

		if($cacheRes !== null) return $cacheRes;

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

		$this->cache->store("userIsMod_{$userId}_{$forum}", $isMod);

        return $isMod;
	}

	/**
	 * Determines if the user that is currently logged in is an administrator or a moderator.
	 *
	 * @return boolean  TRUE, if the user that is currently logged in is an
	 *                  administrator or a moderator.
	 */
	function getIsModOrAdmin($forum = 0) {
		return ($this->getIsMod($forum) || $this->getIsAdmin());
	}

	/**
	 * Jumps back to the previous page via an HTTP redirect
	 *
	 * @return	boolean	checks if the referrer
	 */
	function redirectToReferrer() {
		// Redirecting visitor back to previous page
		$ref = t3lib_div::getIndpEnv('HTTP_REFERER');
		if ($ref) {
			$ref = $this->tools->getAbsoluteUrl($ref);
			header('Location: ' . t3lib_div::locationHeaderUrl($ref));
			exit();
			return true;
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
		if(!t3lib_extMgm::isLoaded('ratings')) return null;

		if(isset($this->rating)) return $this->rating;
		else {
			$this->rating = t3lib_div::makeInstance('tx_ratings_api');
			$this->ratingConf = $this->rating->getDefaultConfig();
			$this->ratingConf['templateFile'] = $this->conf['stylePath'].'/rating/ratings.html';

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
		 * @param  int    $uid   The uid of the rated record.
		 * @return string        The HTML code of the ratings form
		 */
	function getRatingDisplay($table, $uid) {
		$rating =& $this->getRatingInstance();
		return $rating != null ? $rating->getRatingDisplay("{$table}_{$uid}", $this->ratingConf) : '';
	}



    function getListGetPageBrowser($numberOfPages, $additionalParameters=array()) {
    	// Get default configuration
    	$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_pagebrowse_pi1.'];

    	// Modify this configuration
		$conf['pageParameterName'] = $this->prefixId . '|page';
		$conf['numberOfPages'] = $numberOfPages;

		if(count($additionalParameters)>0) {
			$conf['extraQueryString'] = t3lib_div::implodeArrayForUrl(null, $additionalParameters);
		}

    	// Get page browser
    	$cObj = t3lib_div::makeInstance('tslib_cObj');

    	/* @var $cObj tslib_cObj */
    	$cObj->start(array(), '');
    	return $cObj->cObjGetSingle('USER_INT', $conf);
    }


	/**
	 * DEPRECATED METHODS
	 * (kept for compatability reasons
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

?>
