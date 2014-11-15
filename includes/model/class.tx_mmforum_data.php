<?php

class tx_mmforum_data {

	protected $table;

	/**
	 * @var array
	 */
	public $data;
	protected $origData = array();
	protected $uid;
	protected $loaded = false;

	/*
	 * INITIALISATION METHODS
	 */

	function loadFromDB($pid = -1) {
		$andWhere = '';
		if ($pid+1) $andWhere = ' AND pid=' . $pid;

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			$this->getTableName(),
			'uid=' . $this->getUid() . ' AND deleted=0 ' . $andWhere
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) {
			$this->data = null;
			$this->origData = array();
		} else {
			$this->data = $this->origData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		}

		$this->loaded = true;
	}

	function initFromDB($uid) {
		$this->setUid($uid);
	}

	function initFromArray($arr) {
		if (is_array($arr)) {
			$this->data = $this->origData = $arr;
			$this->setUid($arr['uid']);
			$this->loaded = true;
		} else {
			$this->data = null;
		}
	}

	/**
	 * Writes changes into database
	 *
	 * @return void
	 */
	public function updateDatabase() {
		if (!is_array($this->data)) {
			return;
		}

		$diff = array_diff_assoc($this->data, $this->origData);
		foreach ($diff as $key => $value) {
			if (!isset($this->data[$key])) {
				unset($diff[$key]);
			}
		}

		if (!empty($diff)) {
			$this->data['tstamp'] = $diff['tstamp'] = $GLOBALS['EXEC_TIME'];

			if (intVal($this->data['uid']) > 0) {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery($this->getTableName(), 'uid=' . $this->getUid(), $diff);
			} else {
				$this->data['crdate'] = $diff['crdate'] = $GLOBALS['EXEC_TIME'];

				$GLOBALS['TYPO3_DB']->exec_INSERTquery($this->getTableName(), $diff);

				$this->setUid($GLOBALS['TYPO3_DB']->sql_insert_id());
				$this->data['uid'] = $this->getUid();
			}

			$this->origData = $this->data;
		}
	}

	public function setDataArray($data) {
		if (!is_array($data)) {
			return;
		}

		if (!is_array($this->data)) {
			$this->data = $data;
		} else {
			$this->setArrayRecursive($this->data, $data);
		}
	}

	protected function setArrayRecursive(&$arr, $newArr) {
		if (is_array($arr) && is_array($newArr)) {

			foreach ($newArr as $key => $value) {
				if (is_array($value) && is_array($arr[$key])) {
					$this->setArrayData($arr[$key], $value);
				} else {
					if (is_array($value)) {
						$arr[$key] = $value;
					} else {
						$arr[$key] = $value;
					}
				}
			}
		}
	}

	function getTableName() {
		return $this->table;
	}

	/*
	 * GETTER FUNCTIONS
	 */

	function gD($key) {
		if (!$this->loaded) {
			$this->loadFromDB();
		}
		return $this->data[$key];
	}

	function getCrdate() {
		return $this->gD('crdate');
	}

	function getUid() {
		return $this->uid;
	}

	function isNull() {
		return $this->data == null;
	}

	/*
	 * SETTER FUNCTIONS
	 */

	function setUid($uid) {
		$this->uid = intval($uid);
	}
}
