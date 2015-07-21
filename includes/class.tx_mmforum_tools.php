<?php
/**
 *  Copyright notice
 *
 *  (c) 2008 Martin Helmich, Mittwald CM Service GmbH & Co. KG
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

/**
 * The class 'tx_mmforum_tools' contains a collection of useful
 * functions for all plugins.
 *
 * @author     Holger Trapp <h.trapp@mittwaldmedien.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2008 Martin Helmich, Mittwald CM Service GmbH & Co. KG
 * @version    2008-10-11
 * @package    mm_forum
 * @subpackage Includes
 */
class tx_mmforum_tools extends \TYPO3\CMS\Frontend\Plugin\AbstractPlugin {

	/**
	 * The TYPO3 database object
	 *
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseHandle;

	/**
	 * Constructor. takes the database handle from $GLOBALS['TYPO3_DB']
	 */
	public function __construct() {
		parent::__construct();
		$this->databaseHandle = $GLOBALS['TYPO3_DB'];
	}

	/**
	 * Resizes an image.
	 * @param  string $image  The original image URL
	 * @param  int    $width  The desired maximum image width
	 * @param  int    $height The desired maximum image height
	 * @return string         An <img> tag pointing to the resized image
	 */
	function res_img($image, $width, $height) {
		$image_data = @getimagesize($image);
		$imgWidth  = $image_data[0];
		$imgHeight = $image_data[1];

		$conf = array(
			'file' => $image
		);
		if ($imgWidth > $imgHeight) {
			$conf['file.']['width'] = $width;
		} else {
			$conf['file.']['height'] = $height;
		}

		return $this->cObj->IMAGE($conf);
	}

	/**
	 * Loads an user record from database
	 * Returns the database record of a user as associative array.
	 * @param  int   $userId The user UID
	 * @return array          The user record as associative array.
	 */
	static function get_userdata($userId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'fe_users', 'uid = ' . intval($userId));
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
			return $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		} else {
			return false;
		}
	}

	/**
	 * Determines a username from the user UID.
	 * @param  int    $userId  The user UID
	 * @return string          The user name
	 */
	function get_username($userId) {
		$res = $this->databaseHandle->exec_SELECTquery('username', 'fe_users', 'uid = ' . intval($userId));
		if ($this->databaseHandle->sql_num_rows($res) > 0) {
			list($username) = $this->databaseHandle->sql_fetch_row($res);
			return $username;
		} else {
			return '';
		}
	}

	/**
	 * Determines a user UID from the user name
	 * @param string $username The user name
	 * @return int The user UID
	 */
	static function get_userid($username) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'fe_users',
			'username = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($username, 'fe_users').' AND deleted=0'
		);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
			list($userId) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
			return $userId;
		} else return 0;
	}

	/**
	 * Returns a link to a user's profile page.
	 * @param  int    $userid The user UID, to whose profile the link is to
	 *                        be created
	 * @return string         The link to the user's profile page
	 */
	function link_profil($userid) {
		$username = tx_mmforum_tools::get_username($userid);
		$params['tx_mmforum_pi1'] = array(
			'action'  => 'forum_view_profil',
			'user_id' => $userid
		);
		if ($this->getIsRealURL()) {
			unset($params['tx_mmforum_pi1']['user_id']);
			$params['tx_mmforum_pi1']['fid'] = tx_mmforum_tools::get_username($userid);
		}
		return $this->pi_linkToPage($username, $this->conf['pid_forum'], '', $params);
	}

	/**
	 * Cuts a string to a predefined length.
	 * This function cuts a string to a specific length. The new string's
	 * length can either be defined by a constant integer value or a
	 * key word, after which the string is cut.
	 * Words are not cut, the function searches for the next space character.
	 * @param  string $text   The text to be cut
	 * @param  int    $cut    The new length of the text
	 * @param  array  $needle An array of words after which the text shall be cut
	 * @return string         The cut text
	 */
	function textCut($text, $cut, $needle = '') {
		if (empty($needle)) {
			if (strlen($text) > $cut) {
				$textpos = substr($text, $cut);
				$find = strpos($textpos, ' ');
				if ($find !== false) {
					$text = substr($text, 0, $cut + $find) . ' ...';
				}
			}
		} else {
			// needle in ein Array umwandeln
			if (!is_array($needle)) {
				$needle = array($needle);
			}
			// nach dem ersten vorkommen einer needle suchen
			foreach ($needle as $wert) {
				$newPos = strpos($text,$wert);

				if (empty($pos) || $newPos < $pos) {
					$pos = $newPos;
				}
			}
			// Text beschneiden
			$text = substr($text, $pos, $cut);
		}
		return $text;
	}

	/**
	 * Determines the current session ID.
	 * @param  array  $conf The calling plugin's configuration array. Not actually used.
	 * @return string       The current session ID.
	 */
	function getsessid($conf) {
		$httpVars = explode(';', $_SERVER['HTTP_COOKIE']);
		foreach ($httpVars as $string) {
			if (strpos($string, 'PHPSESSID') > 0) {
				list($key, $sessionId) = explode('=', $string);
			}
		}
		if (empty($sessionId)) {
			session_start();
			$sessionId = session_id();
		}
		return $sessionId;
	}

	/**
	 * Removes empty values from an array.
	 *
	 * @param  array $arr The array to be processed
	 * @return array      The processed array
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 */
	static function processArray_numeric($arr) {
		$res = array();
		foreach($arr as $v) {
			if (strlen(trim($v)) > 0 && intval($v) > 0) {
				$res[] = $v;
			}
		}
		return $res;
	}

	/**
	 * Delivers the data record of a FE user group.
	 *
	 * @param   int   $uid The user group's UID
	 * @return  array      The user group's data record as associative
	 *                     array.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-11-24
	 */
	function getUserGroup($uid) {
		$res = $this->databaseHandle->exec_SELECTquery('*', 'fe_groups', 'uid = ' . intval($uid));
		return $this->databaseHandle->sql_fetch_assoc($res);
	}

		/**
		 * Gets all parent user groups of a user group.
		 * This function gets all parent groups of a user groups and returns
		 * the result as an array containing all group uids.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @param   int $group The UID of the group whose parent groups are to
		 *                     be determined.
		 * @return  array      An array of user group UIDs.
		 */
	static function getParentUserGroups($group) {
			/* Parse to int for security reasons */
		$group = implode(',', array_filter(GeneralUtility::intExplode(',', $group)));

			/* Try to load result from cache */
		$cache =& tx_mmforum_cache::getGlobalCacheObject();
		$cacheRes = $cache->restore('pgrpCache_'.$group);

			/* Is result was found in cache, return */
		if ($cacheRes !== null) {
			return $cacheRes;
		}

			/* Otherwise get groups */
		$groups = tx_mmforum_tools::getParentUserGroupsR($group);
		$groups = GeneralUtility::intExplode(',',$groups);
		$groups = tx_mmforum_tools::processArray_numeric($groups);
		$groups = array_unique($groups);

			/* Implode groups to string */
		$groupString = implode(',',$groups);

			/* Save groups to cache and return */
		$cache->save('pgrpCache_'.$group, $groupString);
		return $groupString;
	}

	/**
	 * Recursively called helper function for getParentUserGroups function.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @return  string        A list of group uids as commaseperated list
	 * @param   int    $group A group UID or a commaseperated list of group UIDs
	 */
	function getParentUserGroupsR($group) {
		$result = '';
		if (strpos($group, ',') !== false) {
			$groups = GeneralUtility::intExplode(',', $group);
			foreach ($groups as $sGroup) {
				$result .= tx_mmforum_tools::getParentUserGroupsR($sGroup);
			}
		} else {
			$group = intval($group);
			$res = $this->databaseHandle->exec_SELECTquery('*', 'fe_groups', 'FIND_IN_SET(' . $group . ', subgroup) AND deleted = 0');
			if ($this->databaseHandle->sql_num_rows($res)) {
				$row = $this->databaseHandle->sql_fetch_assoc($res);
				$result = $group . ',' . tx_mmforum_tools::getParentUserGroupsR($row['uid']);
			} else {
				$result = $group . ',';
			}
		}
		return $result;
	}

	/**
	 * Determines a user group's sub user groups.
	 * This functions delivers a comma separated list of all subordinate user
	 * groups of a frontend user group. Subgroups are determined recursively
	 * up to infinity.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-11-24
	 * @param   mixed  $group The user group UID whose subgroups are to be checked.
	 *                        This parameter may either be a single UID or a comma
	 *                        seperated list of UIDs.
	 * @return  string        A comma separated list of the groups and all of their
	 *                        subgroups.
	 * @deprecated Not in use
	 */
	function getSubUserGroups($group) {

			/* Parse to int for security reasons */
		$group = intval($group);

			/* Try to load value from cache */
		$cache =& tx_mmforum_cache::getGlobalCacheObject();
		$cacheRes = $cache->restore('sgrpCache_'.$group);

			/* If value was found in cache, return */
		if ($cacheRes !== null) {
			return $cacheRes;
		}

			/* Otherwise load all subgroups now */
		$groups = tx_mmforum_tools::getSubUserGroupsR($group);
		$groups = GeneralUtility::intExplode(',', $groups);
		$groups = tx_mmforum_tools::processArray_numeric($groups);
		$groups = array_unique($groups);

			/* Implode to string */
		$groupString = implode(',',$groups);

			/* Save to cache and return */
		$cache->save('sgrpCache_'.$group,$groupString);
		return $groupString;
	}

	/**
	 * Recursive helper function for sub user group determination.
	 * This function does the actual determination of the user subgroups.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-11-24
	 * @param   mixed  $group Same as the parameter of getSubUserGroups.
	 * @return  string        A part of the user group list.
	 * @deprecated Not in use
	 */
	function getSubUserGroupsR($group) {
		$result = '';
		if (strstr($group, ',') !== false) {
			$groups = GeneralUtility::intExplode(',', $group);
			foreach ($groups as $sGroup) {
				$result .= tx_mmforum_tools::getSubUserGroupsR($sGroup);
			}
		} else {
			$groupData = tx_mmforum_tools::getUserGroup($group);
			if (strlen($groupData['subgroup']) > 0) {
				$result = $group . ',' . tx_mmforum_tools::getSubUserGroupsR($groupData['subgroup']) . ',';
			} else {
				$result = $group . ',';
			}
		}

		return $result;
	}

	/**
	 * Translates a commaseperated list of group UIDs into a list of group names.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-24-11
	 * @param   string $content The commaseperated list of group UIDs
	 * @param   array  $conf    A configuration array that is not actually used.
	 * @return  string          A list of group names.
	 */
	function getUserGroupList($content, $conf = array()) {
		$groups = GeneralUtility::intExplode(',', $content);
		$groups = tx_mmforum_tools::processArray_numeric($groups);
		$sGroups = array();
		foreach ($groups as $group) {
			if ($GLOBALS['tx_mmforum_tools']['grpCache'][$group]) {
				$sGroups[] = $GLOBALS['tx_mmforum_tools']['grpCache'][$group];
			} else {
				$res = $this->databaseHandle->exec_SELECTquery(
					'title',
					'fe_groups',
					'uid = ' . intval($group)
				);
				list($grouptitle) = $this->databaseHandle->sql_fetch_row($res);
				$GLOBALS['tx_mmforum_tools']['grpCache'][$group] = $grouptitle;
				$sGroups[] = $grouptitle;
			}
		}
		return implode(', ',$sGroups);
	}

	/**
	 * Parses a file path containing a reference to an extension directory.
	 * This function replaces references to an extension directory in a file
	 * path (like e.g. EXT:mm_forum) with the actual path of the extension using
	 * the \TYPO3\CMS\Core\Utility\ExtensionManagementUtility class.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-01-06
	 * @param   string $path The path containing an extension reference.
	 * @return  string       The path with the extension reference replaced by
	 *                       the actual physical extension path.
	 */
	static function generateSiteRelExtPath($path) {
		return preg_replace_callback('/^EXT:([a-z0-9_]+)/', array('tx_mmforum_tools', 'replaceExtPath'), $path);
	}

	/**
	 * Callback function for the generateSiteRelExtPath method.
	 */
	function replaceExtPath($matches) {
		return ExtensionManagementUtility::siteRelPath($matches[1]);
	}

	/**
	 * Stores a variable into the mm_forum cache table.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @param   string $key The key this cache variable shall be referenced with
	 * @param   mixed $value The value that is to be stored in the cache table. Since
	 *                         this value is stored in a serialized form, this can a
	 *                         variable of any type.
	 * @param bool $forceOverwrite Defines if the variable should be overwritten in case
	 * it already exists.
	 *                         
	 * @return  void
	 */
	static function storeCacheVar($key, $value, $forceOverwrite = false) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'tx_mmforum_cache',
			'cache_key = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($key, 'tx_mmforum_cache')
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
			if ($forceOverwrite) {
				$updateArray = array(
					'cache_value' => serialize($value),
					'tstamp'      => $GLOBALS['EXEC_TIME']
				);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'tx_mmforum_cache',
					'cache_key = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($key, 'tx_mmforum_cache'),
					$updateArray
				);
			}
		} else {
			$insertArray = array(
				'cache_key'   => $key,
				'cache_value' => serialize($value),
				'tstamp'      => $GLOBALS['EXEC_TIME']
			);
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_cache', $insertArray);
		}
	}

	/**
	 * Gets a cache variable from the database
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @param   string $key The variable key
	 * @param   mixed $default The default value in case the variable is not found.
	 * @return mixed|null
	 */
	function getCacheVar($key, $default = null) {
		$cachedVal = null;
		$res = $this->databaseHandle->exec_SELECTquery(
			'value',
			'tx_mmforum_cache',
			'cache_key = ' . $this->databaseHandle->fullQuoteStr($key, 'tx_mmforum_cache')
		);
		if ($this->databaseHandle->sql_num_rows($res) > 0) {
			list($cachedVal) = $this->databaseHandle->sql_fetch_row($res);
		}
		return ($cachedVal !== null ? unserialize($cachedVal) : $default);
	}

	/**
	 * Deletes a cache variable.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @param   string $key The variable key
	 * @return  void
	 */
	function deleteCacheVar($key) {
		$this->databaseHandle->exec_DELETEquery(
			'tx_mmforum_cache',
			'cache_key = ' . $this->databaseHandle->fullQuoteStr($key, 'tx_mmforum_cache')
		);
	}

	/**
	 * Creates a random alphanumeric key of variable length.
	 * @param  int    $length The length of the key
	 * @return string         The key
	 */
	function generateRandomString($length) {
		$result = '';

		// random chars that are available
		$allChars = 'abcdefghijklmnopqrstuvwxyz0123456789';

		for ($i = 0; $i < $length; $i++) {
			$result .= substr($allChars, rand(1, strlen($allChars)), 1);
		}
		return $result;
	}


	/**
	 * Converts a hexadecimal string into an IP Address
	 * @param  string $hex The hexadecimal string
	 * @return string      The IP Address
	 */
	function hex2ip($hex) {
		$result = array();

		for ($i = 0; $i < 4; $i++) {
			$result[] = hexdec(substr($hex, 0, 2));
			$hex = substr($hex, 2);
		}
		return implode('.', $result);
	}

	/**
	 * Converts an IP Address into a hexadecimal string.
	 * @param string $ipAddress The IP Address
	 * @return string The hexadecimal string
	 */
	function ip2hex($ipAddress) {
		$result = '';
		$ipParts = explode('.', $ipAddress);
		for ($i = 0; $i < 4; $i++) {
			$result .= dechex($ipParts[$i]);
		}
		return $result;
	}

	/**
	 * Generates an absolute link.
	 * This function generates an absolute link from a relative link
	 * that is submitted as parameter.
	 * For this, the config.baseURL property is used. If this property
	 * is not set, the absolute URL will be determined using the
	 * $_ENV[HTTP_HOST] variable.
	 * This function was introduced due to problems with some realUrl
	 * configuration.
	 *
	 * @param  string $link A relative link
	 * @return string       The submitted string converted into an absolute link
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 */
	static function getAbsoluteUrl($link) {
		if (substr($link, 0, 7) == 'http://' || substr($link, 0, 8) == 'https://')  {
			return $link;
		}

		if (isset($GLOBALS['TSFE']->config['config']['baseURL'])) {
			$baseUrl = $GLOBALS['TSFE']->config['config']['baseURL'];
			if (substr($baseUrl, -1, 1) != '/') {
				$baseUrl .= '/';
			}
			$result = $baseUrl;
		} else {
			$useSSL = ( GeneralUtility::getIndpEnv('SERVER_PORT') == 443);

			$dirname = dirname( GeneralUtility::getIndpEnv('SCRIPT_NAME'));
			// on windows, dirname returns a backslash for the root directory, replace it with a forward slash
			$dirname = ($dirname == '\\') ? '/' : $dirname;
			$dirname = tx_mmforum_tools::appendTrailingSlash($dirname);
			$dirname = tx_mmforum_tools::removeLeadingSlash($dirname);
			if ($dirname == '/') {
				$dirname = '';
			}
			$host = GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST');
			$host = tx_mmforum_tools::appendTrailingSlash($host);

			if ((substr($host, 0, 8) != 'https://') && (substr($host, 0, 7) != 'http://')) {
				$host = ($useSSL ? 'https' : 'http') . '://' . $host;
			}
			$result = $host . $dirname;
		}
		$link = tx_mmforum_tools::removeLeadingSlash($link);
		return $result . $link;
	}

	/**
	 * Appends a trailing slash (/) to a string, but only if the last character is not already a slash.
	 * @param  string $str The string to which a / is to be appended
	 * @return string      The string with a / appended
	 */
	function appendTrailingSlash($str) {
		return (substr($str, -1, 1) != '/' ? $str . '/' : $str);
	}

	/**
	 * Removes a leading slash from a string.
	 * @param  string $str A string with a leading slash
	 * @return string      The string without the leading slash
	 */
	function removeLeadingSlash($str) {
		return (substr($str, 0, 1) == '/' ? substr($str, 1) : $str);
	}

		/**
		 * Escapes square brackets with hexadecimal notation.
		 * This is necessary for links sent in notification emails, since Mozilla
		 * Thunderbird crops links in mail bodys after the first closed square
		 * bracket.
		 *
		 * @param  string $url The URL to be encoded
		 * @return string      The encoded URL
		 */
	function escapeBrackets($url) {
		$replace = array('[' => '%5b', ']' => '%5d');
		return str_replace(array_keys($replace), array_values($replace), $url);
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/class.tx_mmforum_tools.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/class.tx_mmforum_tools.php']);
}
