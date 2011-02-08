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



	/** Include database based cache. */
include_once t3lib_extMgm::extPath('mm_forum').'includes/cache/class.tx_mmforum_cache_database.php';

	/** Include file based cache. */
include_once t3lib_extMgm::extPath('mm_forum').'includes/cache/class.tx_mmforum_cache_file.php';

	/** Include APC based cache */
include_once t3lib_extMgm::extPath('mm_forum').'includes/cache/class.tx_mmforum_cache_apc.php';

	/** Include dummy cache. */
include_once t3lib_extMgm::extPath('mm_forum').'includes/cache/class.tx_mmforum_cache_none.php';



	/**
	 * This class handles data caching for the mm_forum extension.
	 * The tx_mmforum_cache class is a wrapper class for various caching
	 * mechanisms. At the moment, the tx_mmforum_cache class supports the
	 * following caching mechanisms:
	 *
	 *   - APC
	 *     If the APC extension is enabled in PHP, you can use this extension
	 *     for mm_forum data caching. Data is stored persistently in the server's
	 *     RAM. This caching method is very quick.
	 *   - Database
	 *     Data is stored in serialized form in the cache_hash table.
	 *   - File
	 *     Data is stored in the filesystem in the typo3temp/mm_forum directory.
	 *   - None
	 *     No caching will be done.
	 *
	 * As mentioned above, this class is only a wrapper. The actual caching
	 * is done in an instance of one of the tx_mmforum_cache_* classes.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @version    2008-10-11
	 * @copyright  2008 Martin Helmich, Mittwald CM Service GmbH & Co. KG
	 * @package    mm_forum
	 * @subpackage Cache
	 */

class tx_mmforum_cache {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The caching object.
		 * This is an instance of one of the tx_mmforum_cache_* classes.
		 *
		 * @var tx_mmforum_cache_* OR t3lib_cache_frontend_VariableFrontend
		 */

	var $cacheObj;



		/**
		 * The "direct cache" object.
		 * Data stored in the cache will be stored in this array also, allowing
		 * very quick access to this value in case it is requested several times
		 * during the same request.
		 * Note that the "direct cache" is not persistent.
		 *
		 * @var array
		 */

	var $directCache;



		/**
		 * Defines whether the TYPO3 internal cache is used. This flag will be set for
		 * TYPO3 versions from 4.3 upwards.
		 * @var boolean
		 */

	var $useTYPO3Cache = false;

		/**
		 * Initializes the cache and determines caching method.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @param   string $mode The caching mode. This may either be 'auto',
		 *                       'apc','database','file' or 'none' (see above).
		 * @return  void
		 */
	function init($mode = 'auto', $configuration=Array()) {

			/* If mode is set to 'auto' or 'apc', first try to set mode
			 * to APC (if enabled) or otherwise to database. */
		if($mode == 'auto' || $mode == 'apc') {
			if($this->getAPCEnabled())
				$useMode = 'apc';
			else $useMode = 'database';

			/* If mode is set to 'file',... */
		} elseif($mode == 'file')
			$useMode = 'file';

			/* If mode is set to 'none',... */
		elseif($mode == 'none')
			$useMode = 'none';

			/* In all other cases, set mode to 'database'. */
		else $useMode = 'database';

			/* Compose class name and instantiate */
		if(isset($GLOBALS['typo3CacheManager'])) {
			$this->useTYPO3Cache = TRUE;

			if($useMode == 'database' && TYPO3_UseCachingFramework)
				$this->cacheObj =& $GLOBALS['typo3CacheManager']->getCache('cache_hash');
			else {
				if($GLOBALS['typo3CacheManager']->hasCache('mm_forum'))
					$this->cacheObj =& $GLOBALS['typo3CacheManager']->getCache('mm_forum');
				else {
					switch($useMode) {
						case 'database': $className = 't3lib_cache_backend_DbBackend'; break;
						case 'apc':	     $className = 't3lib_cache_backend_ApcBackend'; break;
						case 'file':     $className = 't3lib_cache_backend_FileBackend'; break;
						case 'none':     $className = 't3lib_cache_backend_NullBackend'; break;
						case 'globals':  $className = 't3lib_cache_backend_GlobalsBackend'; break;
						default:         Throw New Exception("Unknown caching mode: $useMode", 1296594227);
					}

					if(!class_exists($className) && file_exists(PATH_t3lib.'cache/backend/class.'.strtolower($className).'.php'))
						include_once PATH_t3lib.'cache/backend/class.'.strtolower($className).'.php';
					elseif(!class_exists($className))
						$this->cacheObj =& $GLOBALS['typo3CacheManager']->getCache('cache_hash');

					if(class_exists($className)) {
						$cacheBackend		= t3lib_div::makeInstance($className, $configuration);
						$cacheObject		= t3lib_div::makeInstance('t3lib_cache_frontend_VariableFrontend', 'mm_forum', $cacheBackend);

						$GLOBALS['typo3CacheManager']->registerCache( $cacheObject );
						$this->cacheObj =& $GLOBALS['typo3CacheManager']->getCache('mm_forum');
					} else throw new Exception("Cache backend does not exist: $className", 1296594228);
				}
			}
		} else {
			$className = 'tx_mmforum_cache_'.$useMode;
			$this->cacheObj =& t3lib_div::makeInstance($className);
		}

	}

		/**
		 * Determines if the APC extension is enabled.
		 * This function determines if the APC extension is enabled
		 * in PHP. This is done by simply testing whether the needed
		 * functions exists.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @return  bool TRUE, if APC is enabled, otherwise FALSE.
		 */
	function getAPCEnabled() {

			/* Just check if the function apc_store and apc_fetch exist */
		return function_exists('apc_store') && function_exists('apc_fetch');

	}

		/**
		 * Saves an object into the cache.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @param   string $key      The key of the object. This key will be used to
		 *                           retrieve the object from the cache.
		 * @param   mixed  $object   The object that is to be stored in the cache.
		 *                           Depending on the cacheing method, this object should
		 *                           be serializable.
		 * @param   bool   $override Determines whether to override the variable in case
		 *	                         it is already stored in cache.
		 * @return  bool             TRUE on success, otherwise FALSE. VOID, if the TYPO3
		 *                           internal cache is used.
		 */
	function save($key, $object, $override=false) {

			/* Insert object into direct cache */
		if(!$this->directCache[$key] || $override)
			$this->directCache[$key] = $object;

		if($this->useTYPO3Cache) {
			return $this->cacheObj->set(str_replace(',','&',$key), $object);
		} else {
			if($object === false) $object = 'boolean:false';

				/* Insert object into real cache and return result */
			return $this->cacheObj->save($key, $object, $override);
		}

	}

		/**
		 * Wrapper for save function.
		 */
	function store($key, $object, $override=false) {
		return $this->save($key, $object, $override);
	}

		/**
		 * Restores an object from cache.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @param   string $key The key of the object.
		 * @return  mixed       The object
		 */
	function restore($key) {

			/* If key is found in direct cache, return object from
			 * direct cache, otherwise load from real cache. */
		if($this->useTYPO3Cache) {
			if(!$this->cacheObj->has(str_replace(',','&',$key))) return null;
			$restore = $this->directCache[$key] ? $this->directCache[$key] : $this->cacheObj->get(str_replace(',','&',$key));
		}
		else $restore = $this->directCache[$key] ? $this->directCache[$key] : $this->cacheObj->restore($key);

			/* If key is not in direct cache, store it there now. */
		if(!$this->directCache[$key]) $this->directCache[$key] = $restore;

			/* Return. */
		return $restore === 'boolean:false' ? false : $restore;

	}

		/**
		 * Deletes an object from cache.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @param   string $key The key of the object.
		 * @return  bool        TRUE on success, otherwise FALSE.
		 */
	function delete($key) {

			/* Unset direct cache value */
		unset($this->directCache[$key]);

			/* Delete from real cache and return result */
		if($this->useTYPO3Cache)
			return $this->cacheObj->remove(str_replace(',','&',$key));
		else return $this->cacheObj->delete($key);

	}

		/**
		 * Clears all caches.
		 * This function clears all mm_forum caches. In detail, this means
		 * clearing the APC user cache (if the APC extension is enabled) and
		 * removing all files from the mm_forum cache directory (usually
		 * typo3temp/mm_forum).
		 * This function is called by the hook in the t3lib_tcemain class.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @return  void
		 */
	function clearAllCaches() {

			/* Clear APC cache */
		if(function_exists('apc_clear_cache')) {
			apc_clear_cache('user');
		}

			/* Instantiate file cache and delete everything */
		$fileCache = t3lib_div::makeInstance('tx_mmforum_cache_file');
		$fileCache->deleteAll();

	}

		/**
		 * Returns the global cache object.
		 * This function returns the global cache object. If this object
		 * does not exist, it is created and stored in $GLOBALS['mm_forum']['cacheObj].
		 * This function is meant to be called statically.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2008-10-11
		 * @return  tx_mmforum_cache An tx_mmforum_cache object.
		 */
	function getGlobalCacheObject($mode='database', $configuration=Array()) {

			/* Check if object already exists and if so, just return this
			 * object. */
		if(isset($GLOBALS['mm_forum']['cacheObj']))
			return $GLOBALS['mm_forum']['cacheObj'];

			/* Otherwise create a new cache object */
		else {
			$cacheObj = t3lib_div::makeInstance('tx_mmforum_cache');
			$cacheObj->init($mode, $configuration);

			$GLOBALS['mm_forum']['cacheObj'] =& $cacheObj;

			return $GLOBALS['mm_forum']['cacheObj'];
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/cache/class.tx_mmforum_cache.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/includes/cache/class.tx_mmforum_cache.php']);
}
?>