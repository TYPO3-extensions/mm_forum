<?php

class tx_mmforum_data {

	var $table;

	var $data;
	var $uid;
	var $loaded = false;

		/*
		 * INITIALISATION METHODS
		 */

	function loadFromDB($pid=-1) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			$this->getTableName(),
			'uid='.$this->getUID().' AND deleted=0 '.(($pid+1)?" AND pid={$pid}":"")
		);
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) $this->data = null;
		else $this->data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

		$this->loaded = true;
	}

	function initFromDB($uid) {
		$this->setUID($uid);
	}

	function initFromArray($arr) {
		if(is_array($arr)) {
			$this->data = $arr;
			$this->setUid($arr['uid']);
			$this->loaded = true;
		} else $this->data = null;
	}

	function getTableName() {
		return $this->table;
	}

		/*
		 * GETTER FUNCTIONS
		 */

	function gD($key) {
		if(!$this->loaded) $this->loadFromDB();
		return $this->data[$key];
	}

	function getCrdate() {
		return $this->gD('crdate');
	}

	function getUID() {
		return $this->uid;
	}

	function isNull() {
		return $this->data == null;
	}

		/*
		 * SETTER FUNCTIONS
		 */

	function setUID($uid) {
		$this->uid = intval($uid);
	}

}

?>