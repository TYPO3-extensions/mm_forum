<?php
/**
 *  Copyright notice
 *
 *  (c) 2007-2009 Mittwald CM Service GmbH & Co. KG
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
 * A custom user field.
 * This class is the abstract representation of a custom user field. It
 * provides function for output generation, input validation and data
 * saving regarding a specific user defined field.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2009 Martin Helmich, Mittwald CM Service GmbH & Co. KG
 * @version    2009-02-16
 * @package    mm_forum
 * @subpackage Includes
 */
class tx_mmforum_userfield {

		/** An instance of the tx_mmforum_usermanagement class. */
	var $userLib = null;
		/** The data array of the user field */
	var $data = null;
		/** An instance of the tslib_cObj class */
	var $cObj = null;


		/** The prefixId for link generation */
	var $prefixId = 'tx_mmforum';

		/**
		 * Initializes the object.
		 * This function initializes the userfield object. It inherits the
		 * instance of the tx_mmforum_usermanagement instance and creates
		 * a new instance of the tslib_cObj class.
		 *
		 * @param &tx_mmforum_usermanagement $userLib An instance of the tx_mmforum_usermanagement
		 *                                            class.
		 * @param &tslib_cObj                $cObj    An instance of the tslib_cObj class. If this
		 *                                            parameter is omitted, a new instance will be
		 *                                            created.
		 * @return void
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function init(&$userLib, &$cObj=null) {
		$this->userLib =& $userLib;

		if($cObj === null)
			$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		else $this->cObj =& $cObj;
	}

		/**
		 * Saves a value for a user for this field.
		 * This function stores a value for this field for a specific user.
		 * Depending on the field type, the value is stored either into the
		 * fe_users table directly, or into the tx_mmforum_userfields_contents
		 * table.
		 *
		 * @param  int    $userId The UID of the user for whom the value is to be
		 * 	                      added.
		 * @param  string $value  The value that is to be stored into this field.
		 * @param  int    $pid    The page UID of the data storage page. Submitting
		 *                        this value as a parameter is just a dirty workaround.
		 * @return void
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function setForUser($userId, $value, $pid=0) {

			/* If the userfield uses an existing field from the fe_users
			 * table, generate an UPDATE query to edit the fe_user record. */
		if($this->isUsingExistingField()) {
			$updateArray = array(
				'tstamp'						=> time(),
				$this->getLinkedUserField()		=> trim($value)
			);
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users','uid='.intval($userId),$updateArray);

			/* If the userfield does NOT use a field from the fe_users table
			 * and there is already a value present in the tx_mmforum_userfields_contents
			 * table, overwrite this value now. */
		} elseif ( $this->isSetForUser($userId) ) {
			$updateArray = array(
				'tstamp'						=> time(),
				'field_value'					=> $value
			);
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_userfields_contents', 'user_id='.intval($userId).' AND field_id='.intval($this->getUID()).' AND deleted=0', $updateArray);

			/* If there is no value set for this user, then create a tx_mmforum_userfields_contents
			 * record now. */
		} else {
			$insertArray = array(
				'pid'				=> $pid,
				'tstamp'			=> time(),
				'crdate'			=> time(),
				'user_id'			=> $userId,
				'field_id'			=> $this->getUID(),
				'field_value'		=> $value
			);
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_userfields_contents', $insertArray);
		}
	}

		/**
		 * Determines if there is a value set for this field for a specific user.
		 *
		 * @param  int  $userId The UID of the user that is to be checked.
		 * @return bool         TRUE, if there is a value stored for this user, otherwise
		 *                      false.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function isSetForUser($userId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'COUNT(*)',
			'tx_mmforum_userfields_contents',
			'user_id='.intval($userId).' AND field_id='.intval($this->getUID()).' AND deleted=0'
		);
		list($count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

		return $count > 0;
	}

		/**
		 * Validates an input variable.
		 * This function validates an input variable against this field's
		 * settings. Validation depends on the "validate" and "required" property
		 * in the field's TypoScript setup.
		 * Note that if the "required" flag is NOT set, the input will not be
		 * validated against the regular expression saved in "validate" if the
		 * input is empty.
		 *
		 * @param  string $value The value that is to be checked.
		 * @return bool          TRUE, if the input is value, otherwise FALSE.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function isValid($value) {
		$value = trim($value);

		if($this->conf['required'] && strlen($value) == 0) {
			return false;
		} else if(strlen($value) > 0) {
			if(!$this->conf['validate']) {
				return true;
			} else {
				return preg_match($this->conf['validate'], $value);
			}
		} else {
			return true;
		}
	}

		/**
		 * Checks if an input variable already exists in the database
		 *
		 * @param  string $value The value that is to be checked.
		 * @param  string $field The vdatabase field where the value should be unique
		 * @return bool          TRUE, if the input is unique, otherwise FALSE.
		 *
		 * @author  Hauke Hain <hhpreuss@googlemail.com>
		 * @version 2009-09-09
		 */
	function isUnique($value, $field) {
		$value = $GLOBALS['TYPO3_DB']->fullQuoteStr(trim($value), 'fe_users');
		$uid = intval($GLOBALS['TSFE']->fe_user->user['uid']);

		//no user logged in
		if ($uid === 0) {
  		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			              $field, 'fe_users', $field . ' LIKE ' . $value
		        );
      return ($GLOBALS['TYPO3_DB']->sql_num_rows($res) < 1);
    }

    //user logged in
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			             'uid,' . $field, 'fe_users', $field . ' LIKE ' . $value
		      );
		$count = intval($GLOBALS['TYPO3_DB']->sql_num_rows($res));
		if ($count === 0) {
      return true;
    } elseif ($count === 1) {
      // if the found value is from the current user, return true
  		$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
  		if (intval($arr['uid']) === $uid) {
        return true;
      }
    }

    return false;
	}

		/**
		 * Gets the fe_users field that this field is linked to.
		 *
		 * @return string The field name of the fe_users field that this field
		 *                is linked to. Returns NULL if this field is not linked
		 *                to a fe_users field.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function getLinkedUserField() {
		return $this->conf['datasource'];
	}

		/**
		 * Determines if this field uses an existing field.
		 *
		 * @return  boolean TRUE, if this field is linked to an existing fe_users
		 *                  field, otherwise FALSE.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function isUsingExistingField() {
		return $this->data['config_parsed']['datasource'] ? true : false;
	}

		/**
		 * Determines if this field is mandatory or optional.
		 *
		 * @return boolean TRUE, if the field is required, otherwise FALSE.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function isRequired() {
		return $this->conf['required'] ? true : false;
	}

		/**
		 * Renders the input field.
		 * This function renders the userfield's input field based on the
		 * TypoScript configuration.
		 *
		 * @param  string $value The value for the input field.
		 * @return string        The HTML code for the input field.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function getRenderedInput($value) {

		if(!$this->conf['input']) return null;
		if($this->cObj === null) return null;

		$data = array(
			'fieldvalue' => $value
		);
		$this->cObj->data = $data;
		return $this->cObj->cObjGetSingle($this->conf['input'], $this->conf['input.']);

	}

		/**
		 * Renders the label.
		 * This function renders the userfield's label based on the TypoScript
		 * configuration.
		 *
		 * @return  string The label's HTML code.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function getRenderedLabel() {

		if($this->cObj === null) return null;

		if($this->data['config_parsed']['label']) {
			$content = $this->cObj->cObjGetSingle($this->data['config_parsed']['label'], $this->data['config_parsed']['label.']);
		} else {
			$content = $this->data['label'];
		}

		if($this->isUsingExistingField())
			$content .= '<input type="hidden" name="'.$this->prefixId.'[userfields_exist]['.$this->data['uid'].']" value="'.$this->conf['datasource'].'" />';

		return $content;

	}

		/**
		 * Gets the UID of this user field.
		 * @return The UID of this userfield.
		 */
	function getUID() { return $this->data['uid']; }

	function get($data) {

		if(is_int($data)) {
				/* Load record from database */
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*', 'tx_mmforum_userfields', 'uid='.intval($data)
			);
			if($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) return null;
			$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		} else {
			$arr = $data;
		}

			/* Unserialize array with meta information */
		$arr['meta'] = unserialize($arr['meta']);

			/* Parse configuration TypoScript */
        $parser =& $this->userLib->getTSParser();
		$parser->setup = null;
        $parser->parse($arr['config']);
        $arr['config_parsed'] = $parser->setup;

			/* Do some corrections for backwards compatibility */
		if(!$arr['meta']['label']['default'])
			$arr['meta']['label']['default'] = $arr['label'];
		if(!$arr['meta']['type'])
			$arr['meta']['type'] = 'custom';
		if(!$arr['meta']['link'] && $arr['config_parsed']['datasource'])
			$arr['meta']['link'] = $arr['config_parsed']['datasource'];
		if(!isset($arr['meta']['required']) && isset($arr['config_parsed']['required']))
			$arr['meta']['required'] = $arr['config_parsed']['required']?true:false;
		if(!$arr['meta']['text']['validate'])
			$arr['meta']['text']['validate'] = 'none';
		if(!$arr['meta']['text']['length'])
			$arr['meta']['text']['length'] = '-1';

		$this->data =  $arr;
		$this->meta =& $arr['meta'];
		$this->conf =& $arr['config_parsed'];

	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/user/class.tx_mmforum_userfield.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/user/class.tx_mmforum_userfield.php']);
}

?>