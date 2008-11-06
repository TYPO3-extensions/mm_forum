<?php

class tx_mmforum_data {
	
	protected $table;
	
	private $data;
	private $uid;
	private $loaded = false;
	
	protected function getTableName() {
		return $table;
	}
	
	protected function gD($key) {
		if(!$this->loaded) $this->loadFromDB();
		return $this->data[$key];
	}
	
	public function getUID() {
		return $this->uid;
	}
	
	protected function setUID($uid) {
		$this->uid = intval($uid);
	}
	
	protected function initFromArray($arr) {
		if(is_array($arr))
			$this->data = $arr;
		else $this->data = null;
	}
	
	private function loadFromDB() {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			$this->getTableName(),
			'uid='.$this->getUID().' AND deleted=0'
		);
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) $this->data = null;
		else $this->data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		
		$this->loaded = true;
	}
	
	protected function initFromDB($uid) {
		$this->setUID($uid);
	}
		
}

?>