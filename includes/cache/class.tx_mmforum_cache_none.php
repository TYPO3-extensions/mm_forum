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
	 * A dummy class for disabled caching.
	 * This class is just a dummy class without any actual functionality.
	 * It is used when the mm_forum cache is to be disabled.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @version    2008-10-11
	 * @copyright  2008 Martin Helmich, Mittwald CM Service GmbH & Co. KG
	 * @package    mm_forum
	 * @subpackage Cache
	 */
class tx_mmforum_cache_none {

		/**
		 * Supposed to store some data, but actually does nothing.
		 */
	function save($key, $object, $override=false) {
		/* Do nothing */
	}

		/**
		 * Just return false.
		 * @return bool FALSE
		 */
	function restore($key) {
		return null;
	}

		/**
		 * Just return false.
		 * @return bool FALSE
		 */
	function delete($key) {
		return false;
	}

}

?>