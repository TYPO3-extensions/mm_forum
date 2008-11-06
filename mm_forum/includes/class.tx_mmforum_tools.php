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
 *   58: class tx_mmforum_tools extends tslib_pibase
 *   68:     function res_img($image,$width,$height)
 *   93:     function get_userdata($user_id)
 *  106:     function get_username($user_id)
 *  118:     function get_userid($user_name)
 *  131:     function link_profil($userid)
 *  157:     function textCut($text,$cut,$needle = '')
 *  185:     function getsessid($conf)
 *  206:     function processArray_numeric($arr)
 *  221:     function getUserGroupList($content, $conf=array())
 *
 * TOTAL FUNCTIONS: 9
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(PATH_tslib."class.tslib_pibase.php");

/**
 * The class 'tx_mmforum_tools' contains a collection of useful
 * functions for all plugins.
 *
 * @author     Holger Trapp <h.trapp@mittwaldmedien.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @version    11. 10. 2006
 * @package    mm_forum
 * @subpackage Includes
 */
class tx_mmforum_tools extends tslib_pibase {

	/**
	 * Resizes an image.
	 * @param  string $image  The original image URL
	 * @param  int    $width  The desired maximum image width
	 * @param  int    $height The desired maximum image height
	 * @return string         An <img> tag pointing to the resized image
	 */
	function res_img($image,$width,$height) {
		$image_data = @getimagesize($image);
		$breite = $image_data[0];
		$hoehe  = $image_data[1];
		
		IF ($breite > $hoehe)
			$conf["procductpic_smal."]["file."]["width"] = $width;
		else
			$conf["procductpic_smal."]["file."]["height"] = $height;    

		$conf["procductpic_smal"] = "IMAGE";
		$conf["procductpic_smal."]["file"] = $image;

		$image = $this->cObj->cObjGetSingle($conf["procductpic_smal"],$conf["procductpic_smal."]);

		return $image;
	}

	/**
	 * Loads an user record from database
	 * Returns the database record of a user as associative array.
	 * @param  int   $user_id The user UID
	 * @return array          The user record as associative array.
	 */
	function get_userdata($user_id){
		$user_id = intval($user_id);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_users',"uid='$user_id'");
        if($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0)
		    return $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		else return false;
	}

	/**
	 * Determines a username from the user UID.
	 * @param  int    $user_id The user UID
	 * @return string          The user name
	 */
	function get_username($user_id){
		$user_id = intval($user_id);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('username','fe_users',"uid='$user_id'");
		list($username)   = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $username;
	}
    
    /**
     * Determines a user UID from the user name
     * @param  string $user_name The user name
     * @return int               The user UID
     */
    function get_userid($user_name) {
		$user_name = mysql_escape_string($user_name);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','fe_users',"username='$user_name'");
		list($userid)   = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $userid;
    }

	/**
	 * Returns a link to a user's profile page.
	 * @param  int    $userid The user UID, to whose profile the link is to
	 *                        be created
	 * @return string         The link to the user's profile page
	 */
	function link_profil($userid) {
		$username = tx_mmforum_tools::get_username($userid);
		$params['tx_mmforum_pi1'] = Array(
			'action'		=> 'forum_view_profil',
			'user_id'		=> $userid
		);
        if($this->getIsRealURL()) {
            unset($params['tx_mmforum_pi1']['user_id']);
            $params['tx_mmforum_pi1']['fid'] = tx_mmforum_tools::get_username($userid);
        }
		#$usrlink = $this->pi_linkTP($username, $params);
		$usrlink = $this->pi_linkToPage($username, $this->conf['pid_forum'], '', $params);
		return $usrlink;
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
	function textCut($text,$cut,$needle = '') {
		if (empty($needle)) {
			IF (strlen($text) > $cut) {
				$textpos = substr($text,$cut);
				$find = strpos($textpos," ");
				if ($find >= "0") $text = substr($text,0,$cut+$find)." ...";
			}
		} else {
			IF (!is_array($needle)) {           // needle in ein Array umwandeln
				$needle = array($needle);
			}
			foreach($needle as $wert) {         // nach dem ersten vorkommen einer needle suchen                    
				$newPos = strpos($text,$wert);
				
				if( empty($pos) or $newPos < $pos  ) {
					$pos = $newPos;
				}
			}
			$text = substr($text,$pos,$cut);    // Text beschneiden 
		}
		return $text;
	}

	/**
	 * Determines the current session ID.
	 * @param  array  $conf The calling plugin's configuration array. Not actually used.
	 * @return string       The current session ID.
	 */
	function getsessid($conf) {
		$httpVars = explode (';',$_SERVER['HTTP_COOKIE']);
		foreach ($httpVars as $string) {
			if(strpos($string,'PHPSESSID') > 0) {  
				list($key,$sessid) = explode("=",$string);
			}
		}
		if(empty($sessid)) {
			session_start();
			$sessid = session_id(); 
		}
		return $sessid;
	}
	
	/**
	 * Removes empty values from an array.
	 * 
	 * @param  array $arr The array to be processed
	 * @return array      The processed array
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 */
	function processArray_numeric($arr) {
		$res = array();
		foreach($arr as $v) {
			if(strlen(trim($v))>0 && intval($v)>0) $res[] = $v;
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
		$uid = intval($uid);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_groups','uid="'.$uid.'"');
		$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		return $arr;
	}
	
	/**
	 * Gets all parent user groups of a user group.
	 * This function gets all parent groups of a user groups and returns
	 * the result as an array containing all group uids.
	 * 
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @param   int $group The UID of the group whose parent groups are to 
	 *                     be determined.
	 * @return  array      An array of user group UIDs.
	 */
	function getParentUserGroups($group) {
		if($GLOBALS['tx_mmforum_tools']['pgrpCache'][$group])
			return $GLOBALS['tx_mmforum_tools']['pgrpCache'][$group];
		else {
			$GLOBALS['TYPO3_DB']->debugOutput = true;
			$groups = tx_mmforum_tools::getParentUserGroupsR($group);
			$groups = t3lib_div::intExplode(',',$groups);
			$groups = tx_mmforum_tools::processArray_numeric($groups);
			$groups = array_unique($groups);
			$GLOBALS['tx_mmforum_tools']['pgrpCache'][$group] = implode(',',$groups);
			return $GLOBALS['tx_mmforum_tools']['pgrpCache'][$group];
		}
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
		if(strstr($group,',')!== false) {
			$groups = t3lib_div::intExplode(',',$group);
			foreach($groups as $sGroup)
				$result .= tx_mmforum_tools::getParentUserGroupsR($sGroup);
		} else {
			$group = intval($group);
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_groups','FIND_IN_SET('.$group.',subgroup) AND deleted=0');
			if($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
				$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
				$result = $group.','.tx_mmforum_tools::getParentUserGroupsR($arr['uid']);
			} else $result = $group.',';
		}
		return $result;
	}
	
	/**
	 * Determines a user group's sub user groups.
	 * This functions delivers a commaseperated list of all subordinate user
	 * groups of a frontend user group. Subgroups are determined resursively
	 * up to infinity.
	 * 
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-11-24
	 * @param   mixed  $group The user group UID whose subgroups are to be checked.
	 *                        This parameter may either be a single UID or a comma
	 *                        seperated list of UIDs.
	 * @return  string        A commaseperated list of the groups and all of their
	 *                        subgroups.
	 */
	function getSubUserGroups($group) {
		if($GLOBALS['tx_mmforum_tools']['sgrpCache'][$group])
			return $GLOBALS['tx_mmforum_tools']['sgrpCache'][$group];
		else {
			$groups = tx_mmforum_tools::getSubUserGroupsR($group);
			$groups = t3lib_div::intExplode(',',$groups);
			$groups = tx_mmforum_tools::processArray_numeric($groups);
			$groups = array_unique($groups);
			$GLOBALS['tx_mmforum_tools']['sgrpCache'][$group] = implode(',',$groups);
			return $GLOBALS['tx_mmforum_tools']['sgrpCache'][$group];
		}
	}
	
	/**
	 * Recursive helper function for sub user group determination.
	 * This function does the actual determination of the user subgroups.
	 * 
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-11-24
	 * @param   mixed  $group Same as the parameter of getSubUserGroups.
	 * @return  string        A part of the user group list.
	 */
	function getSubUserGroupsR($group) {
		if(strstr($group,',')!==false) {
			$groups = t3lib_div::intExplode(',',$group);
			foreach($groups as $sGroup) {
				$result .= tx_mmforum_tools::getSubUserGroupsR($sGroup);
			}
		} else {
			$groupData = tx_mmforum_tools::getUserGroup($group);
			if(strlen($groupData['subgroup'])>0)
				$result = $group.','.tx_mmforum_tools::getSubUserGroupsR($groupData['subgroup']).',';
			else $result = $group.',';
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
    function getUserGroupList($content, $conf=array()) {
        $groups = t3lib_div::intExplode(',',$content);
        $groups = tx_mmforum_tools::processArray_numeric($groups);
        
        foreach($groups as $group) {
            if($GLOBALS['tx_mmforum_tools']['grpCache'][$group])
                $sGroups[] = $GLOBALS['tx_mmforum_tools']['grpCache'][$group];
            else {
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'title',
                    'fe_groups',
                    'uid='.intval($group)
                );
                list($grouptitle) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
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
     * the t3lib_extMgm class.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2008-01-06
     * @param   string $path The path containing an extension reference.
     * @return  string       The path with the extension reference replaced by
     *                       the actual physical extension path.
     */
    function generateSiteRelExtPath($path) {
    	$path = preg_replace_callback('/^EXT:([a-z0-9_]+)/',array('tx_mmforum_tools','replaceExtPath'),$path);
    	return $path;
    }
    
    /**
     * Callback function for the generateSiteRelExtPath method.
     */
    function replaceExtPath($matches) {
    	return t3lib_extMgm::siteRelPath($matches[1]);
    }
    
	/**
	 * Stores a variable into the mm_forum cache table.
	 * 
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @param   string  $key   The key this cache variable shall be referenced with
	 * @param   mixed   $value The value that is to be stored in the cache table. Since
	 *                         this value is stored in a serialized form, this can a
	 *                         variable of any type.
	 * @param   boolean $ovr   Defines if the variable should be overwritten in case it
	 *                         already exists.
	 * @return  void
	 */
    function storeCacheVar($key, $value, $ovr=false) {
    	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
    		'uid','tx_mmforum_cache','cache_key='.$GLOBALS['TYPO3_DB']->fullQuoteStr($key, 'tx_mmforum_cache')
    	);
    	
    	if($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
    		if($ovr) {
    			$updateArray = array(
    				'cache_value'			=> serialize($value),
    				'tstamp'				=> time()
    			);
    			$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'tx_mmforum_cache',
					'cache_key='.$GLOBALS['TYPO3_DB']->fullQuoteStr($key, 'tx_mmforum_cache'),
					$updateArray
				);
    		} else return;
    	} else {
    		$insertArray = array(
    			'cache_key'			=> $key,
    			'cache_value'		=> serialize($value),
    			'tstamp'			=> time()
    		);
    		
    		$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_cache', $insertArray);
    	}
    }
	
	/**
	 * Gets a cache variable from the database
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @param   string $key     The variable key
	 * @param   mixed  $default The default value in case the variable is not found.
	 */
	function getCacheVar($key, $default=null) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'value', 'tx_mmforum_cache','cache_key='.$GLOBALS['TYPO3_DB']->fullQuoteStr($key, 'tx_mmforum_cache')
		);
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) return $default;
		else {
			list($value) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
			return unserialize($value);
		}
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
    	$GLOBALS['TYPO3_DB']->exec_DELETEquery(
			'tx_mmforum_cache',
			'cache_key='.$GLOBALS['TYPO3_DB']->fullQuoteStr($key, 'tx_mmforum_cache')
		);
    }

}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/includes/class.tx_mmforum_tools.php"])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/includes/class.tx_mmforum_tools.php"]);
}



?>
