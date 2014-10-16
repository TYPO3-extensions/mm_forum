<?php

require_once(t3lib_extMgm::extPath('mm_forum').'includes/model/class.tx_mmforum_data.php');

class tx_mmforum_FeUser extends tx_mmforum_data {

	var $table = 'fe_users';

		/*
		 * FINDER METHODS
		 */

	function GetByUID($uid, $pid=-1) {
		$user = t3lib_div::makeInstance('tx_mmforum_FeUser');
		$user->setUID($uid);
		$user->loadFromDB();

		return $user->isNull() ? null : $user;
	}

	function GetByUsername($username, $pid=-1) {
		$username = $GLOBALS['TYPO3_DB']->fullQuoteStr($username, 'fe_users');
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'fe_users',
			"username={$username} AND deleted=0 ".(($pid+1)?" AND pid={$pid}":"")
		);

		if($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
			$user = t3lib_div::makeInstance('tx_mmforum_FeUser');
			$user->initFromArray($GLOBALS['TYPO3_DB']->sql_fetch_assoc($res));
			return $user;
		} else return null;
	}

		/*
		 * GETTER FUNCTIONS
		 */
	function getPostCount() { return intval($this->gD('tx_mmforum_posts')); }
	function getCity() { return $this->gD('city'); }
	function getUsername() { return $this->gD('username'); }
	function getAvatarFilename() { return $this->gD('tx_mmforum_avatar'); }
	function hasAvatar() { return strlen($this->getAvatarFilename()) > 0; }

}

?>