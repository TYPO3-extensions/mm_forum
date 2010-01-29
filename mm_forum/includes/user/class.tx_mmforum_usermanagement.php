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
 * Provides user management functions.
 * This class provides functions regarding user management. Is is e.g.
 * used in the user registration and user settings plugin (tx_mmforum_pi2
 * and tx_mmforum_pi5) and in the mm_forum backend module.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2009 Martin Helmich, Mittwald CM Service GmbH & Co. KG
 * @version    2009-02-16
 * @package    mm_forum
 * @subpackage Includes
 */
class tx_mmforum_usermanagement {

		/** An instance of the t3lib_TSparser class */
	var $parser;

		/**
		 * Gets an instance of the t3lib_TSparser class.
		 *
		 * @return &t3lib_TSparser A reference to an t3lib_TSparser object.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function &getTSParser() {
		if($this->parser) return $this->parser;
		else {
			$this->parser = t3lib_div::makeInstance('t3lib_TSparser');
			return $this->parser;
		}
	}

		/**
		 * Determines if a user field uses an existing fe_user field
		 *
		 * @param  int $uid The UID of the userfield
		 * @return boolean  TRUE, if the userfield uses an existing fe_user field,
		 * 	                otherwise FALSE.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function getUserfieldUsesExistingField($uid) {

		$userField = $this->getUserFieldData($uid);
		if ($userField !== null) {
			return ($userField['config_parsed']['datasource'] ? true : false);
		}
		return false;

	}

		/**
		 * Loads the record of an user field.
		 * This function load the entire record of a custom user field. The
		 * field's typoscript configuration is automatically parsed and the
		 * array of metadata that is stored in the database is automatically
		 * unserialized.
		 *
		 * @param  mixed $data Some data the record is to be initialized with.
		 *                     This may be either the record's UID or the entire
		 *                     record itself as array.
		 * @return array       The record of the user field.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-02-16
		 */
	function getUserFieldData($data) {

		if(is_int($data) || intval($data) != 0 ) {
				/* Load record from database */
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*', 'tx_mmforum_userfields', 'uid='.intval($data)
			);
			if($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) return null;
			$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		} elseif (is_array($arr)) {
			$arr = $data;
		}

			/* Unserialize array with meta information */
		$arr['meta'] = unserialize($arr['meta']);

			/* Parse configuration TypoScript */
        $parser = $this->getTSParser();
        $parser->parse($arr['config']);
        $arr['config_parsed'] = $parser->setup;
		$parser->setup = null;

			/* Do some corrections for backwards compatibility */
		if(!$arr['meta']['label']['default'])
			$arr['meta']['label']['default'] = $arr['label'];
		if(!$arr['meta']['type'])
			$arr['meta']['type'] = 'custom';
		if(!$arr['meta']['link'] && $arr['config_parsed']['datasource'])
			$arr['meta']['link'] = $arr['config_parsed']['datasource'];
		if(!isset($arr['meta']['required']) && isset($arr['config_parsed']['required']))
			$arr['meta']['required'] = $arr['config_parsed']['required']?true:false;
		if(!isset($arr['meta']['unique']))
			$arr['meta']['unique'] = intval($arr['uniquefield'])===1?true:false;
		if(!$arr['meta']['text']['validate'])
			$arr['meta']['text']['validate'] = 'none';
		if(!$arr['meta']['text']['length'])
			$arr['meta']['text']['length'] = '-1';

		return $arr;

	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/user/class.tx_mmforum_usermanagement.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/user/class.tx_mmforum_usermanagement.php']);
}


?>