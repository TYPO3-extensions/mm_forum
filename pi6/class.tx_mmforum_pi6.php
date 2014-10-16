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
 *   76: class tx_mmforum_pi6 extends tslib_pibase
 *   92:     function main($content,$conf)
 *  123:     function microtime_float()
 *  145:     function display($content)
 *  227:     function getExpandCollapseLink($expand)
 *  242:     function getSgPl($key,$number)
 *  257:     function getUserList($userData, $seperate=TRUE, $sepChar='<br />')
 *  290:     function getPartUserList($users, $class='')
 *  311:     function getAveragePosts()
 *  332:     function getTotalPosts()
 *  351:     function getTotalReplies()
 *  361:     function getTotalTopics()
 *  377:     function getTotalUsers()
 *  397:     function getOnlineTotal()
 *  408:     function getOnlineGuests()
 *  419:     function getTodayGuests()
 *  430:     function getTotalFromTime($time)
 *  447:     function getGuestsFromTime($time)
 *  468:     function getTodayTotal()
 *  480:     function getTodayUsers()
 *  490:     function getTodayDate()
 *  504:     function getOnlineUsers()
 *  518:     function getUsersFromTime($time, $sesBackcheck=FALSE, $postCount=TRUE)
 *  568:     function getConfig()
 *  585:     function getUserPidQuery($table="fe_users")
 *  606:     function getStoragePIDQuery($tables="")
 *  643:     function getStoragePID()
 *  666:     function pi_getLL($key)
 *
 * TOTAL FUNCTIONS: 27
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_base.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_pi1.php');

/**
 * The plugin "Portal information" for the extension "mm_forum" displays
 * information about the message board brought by the extension.
 * This includes a listing of all users currently logged in, grouped by
 * user status and some board statistics.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    2006-10-10
 * @package    mm_forum
 * @subpackage Portalinformation
 */
class tx_mmforum_pi6 extends tx_mmforum_base {
	var $prefixId      = 'tx_mmforum_pi6';		// Same as class name
	var $prefixId_pi1  = 'tx_mmforum_pi1';
	var $scriptRelPath = 'pi6/class.tx_mmforum_pi6.php';	// Path to this script relative to the extension dir.

	/**
	 * The plugin's main function
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 *
	 * @param  string $content The content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The plugin content
	 *
	 * @uses getConfig
	 * @uses setHeaders
	 * @uses display
	 */
	function main($content, $conf) {
		$time = $this->microtime_float();
		$this->init($conf);
		// Configuring so caching is not expected. This value means that no cHash params are ever set.
		// We do this, because it's a USER_INT object!
		$this->pi_USER_INT_obj = 1;
		$this->getConfig();

		$this->small = $this->piVars['ext']?!$this->piVars['ext']:TRUE;

		$content = $this->display($content);
		$time = $this->microtime_float() - $time;

		if($this->conf['debug']) $content .= $this->cObj->substituteMarker($this->pi_getLL('debug'),'###EXECUTIONTIME###',round($time,4));

		return $this->pi_wrapInBaseClass($content);
	}

	/**
	 * Returns the current unix timestamp in milliseconds. Used only
	 * for optimization.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return float The current unix timestamp in milliseconds.
	 */
	function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	/**
	 * Displays the plugin
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  string $content The plugin content
	 * @return string          The plugin content
	 * @uses getOnlineGuests
	 * @uses getTodayGuests
	 * @uses getOnlineUsers
	 * @uses getTodayUsers
	 * @uses getTotalUsers
	 * @uses getTotalTopics
	 * @uses getTotalReplies
	 * @uses getAveragePosts
	 * @uses getUserList
	 */
	function display($content) {
		$small = $this->small;

		$template = $this->cObj->fileResource($this->conf['templateFile']);
		$subpart = !$small?"###PORTALINFO###":"###PORTALINFO_SMALL###";
		$template = $this->cObj->getSubpart($template, $subpart);

		$marker = Array();

		// Determine amount of registered users
		$onlineUsers = $this->getOnlineUsers();
		$todayUsers = $this->getTodayUsers();

		// Determine amount of guests
		if(t3lib_extMgm::isLoaded('sys_stat')) {
			$onlineGuests = $this->getOnlineGuests();
			$todayGuests = $this->getTodayGuests();
		} else {
            $onlineGuests = 0;
            $todayGuests = 0;
        }

		// Determine total amount of visitors
		$onlineTotal = $onlineUsers['count'] + $onlineGuests;
		$todayTotal = $todayUsers['count'] + $todayGuests;

		// Current users
		$llMarker = array(
			'###PRD###'                 => $this->getSgPl('prd.pr',$onlineTotal?$onlineTotal:$onlineUsers['count']),
			'###BESUCHER_TOTAL###'      => $this->cObj->wrap($onlineTotal?$onlineTotal:0,$this->conf['importantInformation_wrap']),
			'###BESUCHER_TOTAL_LABEL###'=> $this->getSgPl('total_user',$onlineTotal?$onlineTotal:$onlineUsers['count']),
			'###BESUCHER_REG###'        => $this->cObj->wrap($onlineUsers['count'],$this->conf['importantInformation_wrap']),
			'###BESUCHER_REG_LABEL###'  => $this->getSgPl('reg_user',$onlineUsers['count']),
			'###BESUCHER_GAST###'       => $this->cObj->wrap($onlineGuests,$this->conf['importantInformation_wrap']),
			'###BESUCHER_GAST_LABEL###' => $this->getSgPl('guest',$onlineGuests),
		);
		$currentTotal = $this->cObj->substituteMarkerArray($this->pi_getLL('info.currentTotal'),$llMarker);

		// Today's users
		$llMarker = array(
			'###PRD###'					=> $this->getSgPl('prd.vg',$todayTotal?$todayTotal:$todayUsers['count']),
			'###BESUCHER_REG_HEUTE###'  => $this->cObj->wrap($todayUsers['count'],$this->conf['importantInformation_wrap']),
			'###BESUCHER_REG_HEUTE_LABEL###' => $this->getSgPl('reg_user',$todayUsers['count']),
			'###BESUCHER_GAST_HEUTE###' => $this->cObj->wrap($todayGuests,$this->conf['importantInformation_wrap']),
			'###BESUCHER_GAST_HEUTE_LABEL###' => $this->getSgPl('guest',$todayGuests),
		);
		$todayTotal = $this->cObj->substituteMarkerArray($this->pi_getLL('info.todayTotal'),$llMarker);

		// Statistics
		$llMarker = array(
			'###BENUTZER_TOTAL###'      => $this->cObj->wrap($this->getTotalUsers(),$this->conf['importantInformation_wrap']),
			'###THEMEN_TOTAL###'        => $this->cObj->wrap($this->getTotalTopics(),$this->conf['importantInformation_wrap']),
			'###ANTWORTEN_TOTAL###'     => $this->cObj->wrap($this->getTotalReplies(),$this->conf['importantInformation_wrap']),
			'###BEITRAEGE_SCHNITT###'   => $this->cObj->wrap(round($this->getAveragePosts(),2),$this->conf['importantInformation_wrap']),
			'###SITENAME###'			=> $this->cObj->wrap($this->conf['siteName'],$this->conf['importantInformation_wrap']),
		);
		$statistics = $this->cObj->substituteMarkerArray($this->pi_getLL('info.stats'),$llMarker);

		$marker = Array(
			'###LABEL_INFO###'			=> $this->pi_getLL('info.title'),
			'###LABEL_CURRENT_TOTAL###'	=> $currentTotal,
			'###LABEL_TODAY_TOTAL###'	=> $todayTotal,
			'###LABEL_CURRENTLYONLINE###' => $this->pi_getLL('info.currentlyOnline'),
			'###LABEL_TODAYONLINE###'	=> $this->pi_getLL('info.todayOnline'),
			'###LABEL_STATS###'			=> $statistics,
			'###BESUCHER_LISTE###'      => $this->getUserList($onlineUsers,TRUE),
			'###BESUCHER_LISTE_HEUTE###'=> !$small?$this->getUserList($todayUsers,TRUE):'',
			'###EXPAND###'              => $this->getExpandCollapseLink(TRUE),
			'###COLLAPSE###'            => $this->getExpandCollapseLink(FALSE),
		);

		$template = $this->cObj->substituteMarkerArray($template, $marker);

		return $content.$template;
	}

	/**
	 * Generates a link to show or hide the extended information for the Portal information.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  boolean $expand TRUE, if a link to show the content is to be displayed, otherwise FALSE.
	 * @return string          The link's HTML code.
	 */
	function getExpandCollapseLink($expand) {
		$params = $_GET;
		$params[$this->prefixId]['ext'] = intval($expand);
		return $this->pi_linkTP("&raquo; ".($expand?$this->pi_getLL('ext.expand'):$this->pi_getLL('ext.collapse')),$params);
	}

	/**
	 * Loads a singular or plural version of a certain word from the locallang.php in dependence
	 * of the amount of a certain value.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  string  $key    The word's key in locallang.php. This key has to exist twice, once with the suffix ".sg" (singular) and once ".pl" (plural).
	 * @param  integer $number The integer to be checked. If $number = 1, the singular version is accessed, otherwise the plural version.
	 * @return string          The regarding word, either in plural or singular version.
	 */
	function getSgPl($key,$number) {
		if($number == 1) return $this->pi_getLL($key.'.sg');
		else return $this->pi_getLL($key.'.pl');
	}

	/**
	 * Generates a list from an array with data on users logged in.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  array   $userData The user data, ordered by the pattern array('admins'=>array(array('uid'=>[...],'username'=>[...],'usergroup'=>[...]),[...]),'mods'=>array([...]),'users'=>array([...])).
	 * @param  boolean $seperate Determines, if the users listed are to be grouped by user status (user, moderator, administrator).
	 * @param  string  $sepChar  The seperator string that is to be used to seperate the different user groups (see above) from each other.
	 * @return string            The user list's HTML code.
	 * @uses getPartUserList
	 */
	function getUserList($userData, $seperate=TRUE, $sepChar='<br />') {
		$result = "";

		// Output regular user list
		if(count($userData['users'])>0) {
			if($seperate)   $users = $this->cObj->wrap($this->pi_getLL('list.users').':',$this->conf['userList.']['groupTitle_wrap']).' '.$this->getPartUserList($userData['users'],'tx-mmforum-pi6-user');
			else            $users = $this->getPartUserList($userData['users'],'tx-mmforum-pi6-user');
			$result .= ((strlen($result)>0)?$seperate?$sepChar:', ':'').$users;
		}
		// Output moderator list
		if(count($userData['mods'])>0) {
			if($seperate)   $mods = $this->cObj->wrap($this->pi_getLL('list.moderators').':',$this->conf['userList.']['groupTitle_wrap']).' '.$this->getPartUserList($userData['mods'],'tx-mmforum-pi6-mod');
			else            $mods = $this->getPartUserList($userData['mods'],'tx-mmforum-pi6-mod');
			$result .= ((strlen($result)>0)?$seperate?$sepChar:', ':'').$mods;
		}
		// Output administrator list
		if(count($userData['admins'])>0) {
			if($seperate)   $admins = $this->cObj->wrap($this->pi_getLL('list.administrators').':',$this->conf['userList.']['groupTitle_wrap']).' '.$this->getPartUserList($userData['admins'],'tx-mmforum-pi6-admin');
			else            $admins = $this->getPartUserList($userData['admins'],'tx-mmforum-pi6-admin');
			$result .= ((strlen($result)>0)?$seperate?$sepChar:', ':'').$admins;
		}

		return $result;
	}

	/**
	 * Displays a single user group of the users to be listed.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  array  $users An array containing the users to be displayed.
	 * @param  string $class The css class, the generated links are to be wrapped with.
	 * @return string        The part-user list's HTML code
	 */
	function getPartUserList($users, $class='') {
		$links = Array();
		foreach($users as $user) {
			$pageLink = tx_mmforum_pi1::getUserProfileLink($user['uid']);
			$postCount = $user['postCount']?' ('.$user['postCount'].')':'';
			$links[] = '<a href="'.$pageLink.'" class="'.$class.'">'.$user[tx_mmforum_pi1::getUserNameField()].$postCount.'</a>';
		}
		return implode(', ',$links);
	}

	/**
	 * Computes the average post count per day.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return integer The average post count
	 * @uses getTotalPosts
	 */
	function getAveragePosts() {
        $res=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '(UNIX_TIMESTAMP()-min(post_time))',
            'tx_mmforum_posts',
            '1=1'.$this->cObj->enableFields('tx_mmforum_posts').$this->getStoragePIDQuery()
        );
        $arr = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        $daysTotal = floor($arr[0]/(3600*24))+1;

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'COUNT(*)/'.$daysTotal,
            'tx_mmforum_posts',
            '1=1'.$this->cObj->enableFields('tx_mmforum_posts').$this->getStoragePIDQuery()
        );
        $arr = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        return $arr[0];
    }

	/**
	 * Determines the total amount of posts in the board.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return integer The total amount of posts in the board.
	 */
	function getTotalPosts() {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'COUNT(*)',
			'tx_mmforum_posts',
			'1=1'.$this->cObj->enableFields('tx_mmforum_posts').$this->getStoragePIDQuery()
		);
		$arr = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $arr[0];
	}

	/**
	 * Determines the total amount of replies in the board. This number
	 * results from the total amount of posts minus the amount of topics.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return integer The total amount of replies
	 * @uses getTotalPosts
	 * @uses getTotalTopics
	 */
	function getTotalReplies() {
		return $this->getTotalPosts() - $this->getTotalTopics();
	}

	/**
	 * Determines the total amount of topics in the board.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return integer The total amount of topics in the board.
	 */
	function getTotalTopics() {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'COUNT(*)',
			'tx_mmforum_topics',
			'1=1'.$this->cObj->enableFields('tx_mmforum_topics').$this->getStoragePIDQuery()
		);
		$arr = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $arr[0];
	}

	/**
	 * Determines the total amount of registered users.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return integer The total amount of registered users.
	 */
	function getTotalUsers() {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'COUNT(*)',
			'fe_users',
			'1=1'.$this->cObj->enableFields('fe_users').$this->getUserPidQuery()
		);
		$arr = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $arr[0];
	}

	/**
	 * Determines the total amount of users currently visiting the page.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return integer The total amount of visitors.
	 * @uses getTotalFromTime
	 * @deprecated The total amount of visitors is now determined through addtion of getOnlineGuests and getOnlineUsers.
	 * @see getOnlineGuests
	 * @see getOnlineUsers
	 */
	function getOnlineTotal() {
		return $this->getTotalFromTime(time()-$this->conf['onlineTime']);
	}

	/**
	 * Determines the amount of guests currently online.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return integer The amount of guests currently online.
	 * @uses getGuestsFromTime
	 */
	function getOnlineGuests() {
		return $this->getGuestsFromTime(time()-$this->conf['onlineTime']);
	}

	/**
	 * Determines the amount of guests, who were online today.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return integer The amount of guests, who were online today.
	 * @uses getGuestsFromTime
	 */
	function getTodayGuests() {
		return $this->getGuestsFromTime($this->getTodayDate());
	}

	/**
	 * Determines the amount of all visitors who were online after a certain point of time.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  integer $time The point of time, since when the visitors are to be counted.
	 * @return integer       The amount of visitors since $time
	 */
	function getTotalFromTime($time) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'sys_stat',
			'tstamp >= "'.$time.'"',
			'surecookie'
		);
		return $GLOBALS['TYPO3_DB']->sql_num_rows($res);
	}

	/**
	 * Determines the amount of guests (=non-logged-in users) since a certain point of time.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  integer $time The point of time, since when the guests are to be counted.
	 * @return integer       The total amount of guests since $time
	 */
	function getGuestsFromTime($time) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',
			'sys_stat',
			'tstamp >= "'.$time.'" AND feuser_id = "0"',
			'surecookie'
		);
		return $GLOBALS['TYPO3_DB']->sql_num_rows($res);
	}

	/**
	 * Determines the amount of visitors who were online today.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return integer The amount of visitors who were online today.
	 * @uses getTotalFromTime
	 * @uses getTodayDate
	 * @deprecated The total amount of visitors is now determined through additon of getTodayGuests and getTodayUsers.
	 * @see getTodayGuests
	 * @see getTodayUsers
	 */
	function getTodayTotal() {
		return $this->getTotalFromTime($this->getTodayDate());
	}

	/**
	 * Determines the amount of registered users who were online today.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return integer The amount of registered users who were online today.
	 * @uses getUsersFromTime
	 * @uses getTodayDate
	 */
	function getTodayUsers() {
		return $this->getUsersFromTime($this->getTodayDate(),FALSE,$this->conf['showPostCount'] && !$this->small);
	}

	/**
	 * Determines the unix timestamp of the current day at 0:00.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return integer The unix timestamp of the current day at 0:00.
	 */
	function getTodayDate() {
		$today = date('dmy');
		$today = mktime(0,0,1,substr($today,2,2),substr($today,0,2),substr($today,4,4));

		return $today;
	}

	/**
	 * Determines all registered users currently online
	 * @author Martin Helmich <m.helmich@mittwald.de>.
	 * @return array An array containing information on users logged in.
	 * @uses getUsersFromTime
	 */
	function getOnlineUsers() {
		return $this->getUsersFromTime(time()-$this->conf['onlineTime'],TRUE,FALSE);
	}

	/**
	 * Determines all registered users who were online since a certain point of time.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  integer $time         The point of time, since when the users are to be counted.
	 * @param  boolean $sesBackcheck Determines, of the user status is to be checked by session data.
	 * @param  boolean $postCount    Determines, if the amount of posts created since $time is to be determined (can be
	 *                               quite time-expensive)
	 * @return array                 An array containing information on users who were online today.
	 */
	function getUsersFromTime($time, $sesBackcheck=FALSE, $postCount=TRUE) {
		$result = Array(
			'users','mods',"admins",'count'
		);

		if(!t3lib_extMgm::isLoaded('sys_stat')) $sesBackcheck = TRUE;

		$grp_admin		= array($this->getAdministratorGroup());
		$grp_mod		= $this->getModeratorGroups();

		if($sesBackcheck) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'u.usergroup, u.'.tx_mmforum_pi1::getUserNameField().', u.uid',
				'fe_users u, fe_sessions s',
				's.ses_tstamp >= "'.$time.'" AND u.deleted=0 AND u.disable=0 AND u.uid=s.ses_userid '.$this->getUserPidQuery('u'),
				'ses_userid',
				'username ASC'
			);
		}
		else {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'u.usergroup, u.'.tx_mmforum_pi1::getUserNameField().', u.uid',
				'fe_users u, sys_stat s',
				's.feuser_id != "0" AND u.uid = s.feuser_id AND s.tstamp >= "'.$time.'" '.$this->getUserPidQuery('u'),
				'feuser_id'
			);
		}
		while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			if($postCount) {
				$res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'COUNT(*)',
					'tx_mmforum_posts',
					'poster_id="'.$arr['uid'].'" AND post_time >= "'.$time.'"'.$this->cObj->enableFields('tx_mmforum_posts').' '.$this->getStoragePIDQuery()
				);
				$arr2 = $GLOBALS['TYPO3_DB']->sql_fetch_row($res2);
				$arr['postCount'] = $arr2[0];
			}

			$user_groups = t3lib_div::intExplode(',',$arr['usergroup']);

			    if(count(array_intersect($user_groups, $grp_mod))   > 0) $result['mods'][]   = $arr;
			elseif(count(array_intersect($user_groups, $grp_admin)) > 0) $result['admins'][] = $arr;
			else $result['users'][] = $arr;
		}
		$result['count'] = $GLOBALS['TYPO3_DB']->sql_num_rows($res);

		return $result;
	}

	/**
	 * Updates the configuration array.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @return void
	 */
	function getConfig() {
		$this->conf['templateFile'] = $this->conf['templateFile']?$this->conf['templateFile']:'EXT:mm_forum/res/tmpl/default/portalinfo/mm_forum_pi6.tmpl';
		$this->conf['cssFile'] = $this->conf['cssFile']?$this->conf['cssFile']:'typo3conf/ext/mm_forum_portalinfo/stylesheet.css';
		$this->conf['onlineTime'] = $this->conf['onlineTime']?intval($this->conf['onlineTime']):600;
		$this->conf['showGuests'] = ($this->conf['showGuests']=='1')?TRUE:FALSE;
		$this->conf['showPostCount'] = ($this->conf['showPostCount']=='1')?TRUE:FALSE;
		$this->conf['debug'] = (trim($this->conf['debug'])=='1')?TRUE:FALSE;
	}

	/**
	 * Delivers a MySQL-WHERE query checking a fe_user record's PID.
	 * @param   string $table The table name from which to select. Default 'fe_user'
	 * @return  string        The query, following the pattern " AND fe_users.pid=..."
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-16
	 */
	function getUserPidQuery($table="fe_users") {
		if($this->conf['userPID']==-1) return "";
		if($this->conf['userPID']=="") return "";
		else return " AND $table.pid='".$this->conf['userPID']."'";
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi6/class.tx_mmforum_pi6.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi6/class.tx_mmforum_pi6.php']);
}

?>
