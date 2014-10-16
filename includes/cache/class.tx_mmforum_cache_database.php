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

	/**
	 * Cache class for database based caching.
	 * The tx_mmforum_cache_database class is a wrapper class for the
	 * mm_forum database caching mechanism. Data is stored in the
	 * cache_hash table provided by the TYPO3 caching mechanism.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @version    2008-10-11
	 * @copyright  2008 Martin Helmich, Mittwald CM Service GmbH & Co. KG
	 * @package    mm_forum
	 * @subpackage Cache
	 */
class tx_mmforum_cache_database {

		/**
		 * Saves an object into the cache.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @access  public
		 * @param   string $key      The key of the object. This key will be
		 *                           used to retrieve the object from the cache.
		 * @param   mixed  $object   The object that is to be stored in the
		 *                           cache. Depending on the cacheing method, this
		 *                           object should be serializable.
		 * @param   bool   $override Determines whether to override the variable
		 *                           in case it is already stored in cache.
		 * @return  bool             TRUE on success, otherwise FALSE.
		 */
	function save($key, $object, $override=false) {

			/* Prepare INSERT statement */
		$sql = "INSERT INTO cache_hash SET hash=MD5('".$key."'), content=".$GLOBALS['TYPO3_DB']->fullQuoteStr(serialize($object), 'cache_hash').", ident='tx_mmforum_cache'";

			/* If $override flag is set, append an 'ON DUPLICATE KEY UPDATE' statement
			 * to the INSERT statement. */
		if($override)
			$sql .= " ON DUPLICATE KEY UPDATE content = '".serialize($object)."'";

			/* Execute and return result. */
		return $GLOBALS['TYPO3_DB']->sql_query($sql) ? true : false;

	}

		/**
		 * Restores an object from cache.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @access  public
		 * @param   string $key The key of the object.
		 * @return  mixed       The object
		 */
	function restore($key) {

			/* Try to select from database */
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('content','cache_hash','hash=MD5("'.$key.'") AND ident="tx_mmforum_cache"');

			/* If no results were found, return FALSE */
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) return null;

			/* Otherwise, load result, unserialize and return */
		else {
			list($content) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
			return unserialize($content);
		}

	}

		/**
		 * Deletes an object from cache.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @access  public
		 * @param   string $key The key of the object.
		 * @return  bool        TRUE on success, otherwise FALSE.
		 */
	function delete($key) {
		$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery('cache_hash', 'hash=MD5("'.$key.'") AND ident="tx_mmforum_cache"');
		return $res ? true : false;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/cache/class.tx_mmforum_cache_database.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/cache/class.tx_mmforum_cache_database.php']);
}
?>