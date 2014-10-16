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
 *   52: class tx_mmforum_ranksFE
 *   66:     function displayUserRanks($user_id=-1)
 *  112:     function getUserPostCount($user_id)
 *  140:     function getUserGroupList($user_id)
 *  174:     function getRankByGroup($user_groups)
 *  218:     function getRankByPostCount($post_count)
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * This class handles user ranks in the frontend. This includes for
 * example to determine which ranks apply to a single user and to display
 * these ranks.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @version    2008-04-20
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_ranksFE {

	var $cObj;

	/**
	 * Initializes the object.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-04-20
	 * @param   tslib_pibase $pObj The parent plugin
	 * @return  void
	 */
	function init($pObj) {
		$this->pObj = $pObj;
		$this->conf = $pObj->conf;
		$this->cObj = $pObj->cObj;
	}

    /**
     * Displays the user ranks for a single user.
     * This function displays all user ranks for a single user whose
     * UID is submitted as parameter. All user ranks are displayed in a
     * plain list, while the actual output may be configured using
     * TypoScript.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-06-06
     * @param   int    $user_id The UID of the user whose ranks are to be displayed
     * @return  string          The user ranks.
     */
    function displayUserRanks($user_id=-1) {

        #$userRanksObj = t3lib_div::makeInstance('tx_mmforum_ranksFE');
        #$userRanksObj->setContentObject($this->cObj);

        if($this->conf['ranks.']['enable']) {
            if($user_id == -1) $user_id = $GLOBALS['TSFE']->fe_user->user['uid'];

            $post_count         = $this->getUserPostCount($user_id);
            $post_count_rank    = $this->getRankByPostCount($post_count);
            if($post_count_rank != 'error') $ranks[] = $post_count_rank;
        }

        $usergroups         = $this->getUserGroupList($user_id);
        $usergroups_ranks   = $this->getRankByGroup($usergroups);

        if($usergroups_ranks[0]['excl']) $ranks = $usergroups_ranks;
        else $ranks = array_merge((array)$ranks, (array)$usergroups_ranks);

        if(count($ranks)==0) return '';

        foreach($ranks as $rank) {
            if($rank['color'])
                $title = '<span style="color: '.$rank['color'].'">'.$rank['title'].'</span>';
            else $title = $rank['title'];

            $title = $this->cObj->stdWrap($title,$this->conf['ranks.']['title_stdWrap.']);

            if($rank['icon']) {
                #$icon = '<img src="uploads/tx_mmforum/'.$rank['icon'].'" style="vertical-align:middle;" />';
				$icon = $this->cObj->IMAGE(array('file' => 'uploads/tx_mmforum/'.$rank['icon'], 'file.' => array('params' => '-verbose')));
                $icon = $this->cObj->stdWrap($icon,$this->conf['ranks.']['icon_stdWrap.']).' ';
            } else $icon = '';

            $sRank = $this->cObj->stdWrap($icon.$title,$this->conf['ranks.']['rank_stdWrap.']);
            $content .= $sRank;
        }
        $content = $this->cObj->stdWrap($content,$this->conf['ranks.']['all_stdWrap.']);

        return $content;
    }

    /**
     * Determines the post count of a user.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-06-06
     * @param   int $user_id The UID of the user whose post count is to be determined.
     * @return  int          The user's post count.
     */
    function getUserPostCount($user_id) {
        if($user_id == $GLOBALS['TSFE']->fe_user->user['uid'])
            return $GLOBALS['TSFE']->fe_user->user['tx_mmforum_posts'];
        else {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'tx_mmforum_posts',
                'fe_users',
                'uid='.intval($user_id)
            );
            if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) return 0;
            else {
                list($post_count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                return $post_count;
            }
        }
    }

    /**
     * Determines the groups a user is in.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-06-06
     * @param   int   $user_id The UID of the user whose groups are to be
     *                         determined.
     * @return  array          An array containing all groups the user is a
     *                         member of.
     */
    function getUserGroupList($user_id) {
        if($user_id == $GLOBALS['TSFE']->fe_user->user['uid'])
            $groups = $GLOBALS['TSFE']->fe_user->user['usergroup'];
        else {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'usergroup',
                'fe_users',
                'uid='.intval($user_id)
            );
            if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) return 0;
            else {
                list($groups) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            }
        }
        $aGroup = t3lib_div::intExplode(',',$groups);
        $aGroup = tx_mmforum_tools::processArray_numeric($aGroup);
        return $aGroup;
    }

    /**
     * Determines a user's ranks in dependence of his/her groups.
     * This function determines which user ranks of a user result from
     * groups ranks of groups the user is a member of. An example for this would
     * be the administrator rank, since all members of the administrator
     * groups should be ranked as administrator.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-06-06
     * @param   array $user_groups An array containing all user groups the user
     *                             is a member of.
     * @return  array              An array containing all user ranks resulting from
     *                             the user's groups.
     */
    function getRankByGroup($user_groups) {

        $ranks = array();
        if(!is_array($user_groups)) return array();

        foreach($user_groups as $group) {
            if($GLOBALS['tx_mmforum_ranksFE']['groupCache'][$group])
                $groupData = $GLOBALS['tx_mmforum_ranksFE']['groupCache'][$group];
            else {
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    '*',
                    'fe_groups',
                    'uid='.$group
                );
                $groupData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                $GLOBALS['tx_mmforum_ranksFE']['groupCache'][$group] = $groupData;
            }

            if($groupData['tx_mmforum_rank']) {
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    '*',
                    'tx_mmforum_ranks',
                    'uid='.$groupData['tx_mmforum_rank'].' AND deleted=0 AND hidden=0'
                );
                if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) continue;
                $rank = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                if($groupData['tx_mmforum_rank_excl']) {
                    $rank['excl'] = 1;
                    return array($rank);
                }

                $ranks[] = $rank;
            }
        }
        return $ranks;

    }

    /**
     * Determines the user's rank by his/her post count.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-06-06
     * @param   int   $post_count The user's post count.
     * @return  array             The regarding user rank as associative array.
     */
    function getRankByPostCount($post_count) {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_ranks',
            'minPosts <= '.$post_count.' AND deleted=0 AND hidden=0 AND special=0',
            '',
            'minPosts DESC'
        );
        if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) return 'error';
        else return $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
    }

    /**
     * Sets the content object the content is rendered for.
     * @param tslib_cObj $cObj The content object.
     */
    function setContentObject($cObj) {
    	$this->cObj = $cObj;
    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_ranksfe.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi1/class.tx_mmforum_ranksfe.php']);
}
?>