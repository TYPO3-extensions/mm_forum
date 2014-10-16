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
	 * Cache class for file based caching.
	 * The tx_mmforum_cache_file class is a wrapper class for the
	 * mm_forum file caching mechanism. Data is stored in the
	 * typo3temp/mm_forum directory.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @version    2008-10-13
	 * @copyright  2008 Martin Helmich, Mittwald CM Service GmbH & Co. KG
	 * @package    mm_forum
	 * @subpackage Cache
	 */
class tx_mmforum_cache_file {

		/**
		 * The path in which to store the cache files.
		 */
	var $path = 'typo3temp/mm_forum';

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
		if(!is_dir($this->path)) mkdir($this->path);

		$filename = $this->getFilename($key);

		if($override === false && file_exists($filename)) return false;
		else {
			$file = fopen($filename,"w");
			$res = fwrite($file, serialize($object));
			fclose($file);

			return $res ? true : false;
		}
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
		$filename = $this->getFilename($key);

		if(!file_exists($filename)) return null;
		else return unserialize(file_get_contents($filename));
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
		$filename = $this->getFilename($key);

		if(!file_exists($filename)) return false;
		else return unlink($filename);
	}

		/**
		 * Gets a filename based on a cache key.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @access  private
		 * @param   string $key An object key
		 * @return  string      A filename
		 */
	function getFilename($key) {
		return $this->path.'/'.md5($key).'.mmforum_cache';
	}

		/**
		 * Delete the entire file cache.
		 * Thie function deletes the entire mm_forum file cache by
		 * just deleting all files from the directory specified in
		 * $this->path.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-13
		 * @access  public
		 * @return  void
		 */
	function deleteAll() {
		$fullPath = PATH_site . $this->path;

			/* NOTE: The following condition is NOT a mistake, but is actually intended
			 * not to match BOTH false (for "string not found") AND 0 (for "string found
			 * at index 0"). Btw, type safety is greatly overrated... ;) */
		while(strpos($fullPath,'../'))
			$fullPath = preg_replace('/\/([^\/]*?)\/\.\.\//','/',$fullPath);

		$files = glob($fullPath.'/*.mmforum_cache');

		foreach((array)$files as $file) if($file && file_exists($file)) unlink($file);
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/cache/class.tx_mmforum_cache_file.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/cache/class.tx_mmforum_cache_file.php']);
}
?>