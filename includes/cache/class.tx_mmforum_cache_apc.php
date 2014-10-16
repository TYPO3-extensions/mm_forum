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
	 * Cache class for APC based caching.
	 * The tx_mmforum_cache_apc class is a wrapper class for the caching
	 * functions provided by the APC extension. Cached data is stored
	 * in the server's RAM persistently between requests.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @version    2008-10-11
	 * @copyright  2008 Martin Helmich, Mittwald CM Service GmbH & Co. KG
	 * @package    mm_forum
	 * @subpackage Cache
	 */
class tx_mmforum_cache_apc {

		/**
		 * The prefix string for all cache keys.
		 * This attribute may be of importance, if on the server there is more
		 * than one instance of the mm_forum running (even inside two entirely
		 * different virtual hosts), because these two instances can then override
		 * the other one's cache variables. For this reason, it is important to
		 * give the cache keys globally unique names.
		 * If you experience any odd behaviour, change this value to a random
		 * value of your choice.
		 */
	var $prefix = '1';

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
		if($override)
			return apc_store($this->getCacheKey($key),$object);
		else return apc_add($this->getCacheKey($key),$object);
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
		$res = apc_fetch($this->getCacheKey($key));
		return ($res === false) ? null : $res;
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
		return apc_delete($this->getCacheKey($key));
	}

		/**
		 * Generates a cache key used for the APC key.
		 * By default, the key is just prepended with 'tx_mmforum_1_'.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @access  private
		 * @param   string $key An object key
		 * @return  string      A formatted object key.
		 */
	function getCacheKey($key) {
		return 'tx_mmforum_'.$this->prefix.'_'.$key;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/cache/class.tx_mmforum_cache_apc.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/cache/class.tx_mmforum_cache_apc.php']);
}
?>