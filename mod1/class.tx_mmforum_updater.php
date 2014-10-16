<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008 Martin Helmich, Mittwald CM Service
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
 ***************************************************************/

/**
 * This class handles the update process of the mm_forum to the current
 * version from the SVN repository. The updater connects to the SVN
 * repository and first determines the current version that is available
 * in SVN. If the version is newer than the one installed, the updater
 * is able to download the tarball archive and unzip it automatically.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    2008-04-20
 * @copyright  2008 Martin Helmich, Mittwald CM Service
 * @package    mm_forum
 * @subpackage Backend
 */
class tx_mmforum_updater {

	/**
	 * The revision number of this version
	 */
	var $thisRevision = 212;

	/**
	 * The URL of the mm_forum repository.
	 * This can be any URL, the response just has to contain the string
	 * "Revision XYZ", with XYZ equalling the newest revision number.
	 */
	var $url_rep		= 'http://svn.typo3.net/listing.php?repname=mm_forum&path=%2F&sc=0';

	/**
	 * The URL supplying the newest revision as tarball archive.
	 */
	var $url_download	= 'http://svn.typo3.net/dl.php?repname=mm_forum&path=%2Fmm_forum%2Fmm_forum%2F&rev=0&isdir=1';

	/**
	 * Main function of the module.
	 * This function displays the updating form.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-04-20
	 * @return  string The HTML content of the module
	 */
	function main() {
		$this->init();

		$newestRevision		= $this->getNewestRevision();

		if($newestRevision === false) return $this->getLL('noURLfopen');

		$content  = '<table style="width:100%;" cellspacing="0" cellpadding="0">' .
				'<tr>' .
				'<td style="width:50%; padding-right:8px;">'.$this->getLL('intro').'</td>' .
				'<td><fieldset><legend>'.$this->getLL('title').'</legend>';

		$content .= sprintf($this->getLL('currentRevision'),$this->thisRevision).'<br />';
		$content .= sprintf($this->getLL('newestRevision'),$newestRevision).'<br /><br />';

		if(t3lib_div::_GP('update_result')) {
			unlink('../../tx_mmforum_update.php');
			unlink('../../mm_forum_update.tar.gz');

	        $TCE = t3lib_div::makeInstance('t3lib_tcemain');
	        $TCE->admin = TRUE;
	        $TCE->clear_cacheCmd('all');

			if(t3lib_div::_GP('update_result') == 'error')
				return '<strong>'.$this->getLL('error').'</strong>';
			if(t3lib_div::_GP('update_result') == 'success')
				return '<strong>'.$this->getLL('success').'</strong>';
		}

		if($this->thisRevision >= $newestRevision)
			$content .= '<strong>'.$this->getLL('noUpdate').'</strong>';
		else {
			if(t3lib_div::_GP('confirmUpdate')=='1') {
				$content .= $this->performUpdate();
			} else {
				$content .= '<strong>'.$this->getLL('updatePossible').'</strong>';
				$content .= '<br /><br /><input type="hidden" name="confirmUpdate" value="1" />' .
						'<input type="submit" value="'.$this->getLL('updateNow').'" />';
			}
		}

		$content .= '</fieldset></td></tr></table>';

		return $content;
	}

	/**
	 * Performs the actual update.
	 * This function performs the actual update to the current version.
	 * In order to do this, the following steps are executed:
	 *  1. The tarball archive is loaded from svn.typo3.net
	 *  2. The tarball archive is stored in the ext/ directory containing
	 *     the mm_forum extension directory. The archive has to be stored outside
	 *     of the extension directory since this is deleted in the course of
	 *     the update.
	 *  3. The tx_mmforum_update.php script is copied into the ext/ directory.
	 *     This is necessary since the mm_forum directory is deleted in the course
	 *     of the update.
	 *  4. The browser is redirected to tx_mmforum_update.php which performs
	 *     the actual update, i.e. deleting the mm_forum directory and unzipping
	 *     the tarball archive.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-04-20
	 * @return  string Error messages in case of an error, otherwise void.
	 */
	function performUpdate() {
		$c		= $this->getLL('progress.loadTarball');

		if(file_exists('../../tx_mmforum_update.php'))
			unlink('../../tx_mmforum_update.php');

		// Load Tarball
			$tarball = @file_get_contents($this->url_download, false);
			if(!$tarball) return $c.$this->getLL('progress.failure');
			else $c .= $this->getLL('progress.success').'<br />';

		// Validate Tarball
			if(strlen($tarball) == 0 || stristr($tarball, 'Unable to open file mm_forum.tar.gz'))
				return $c.$this->getLL('progress.failure');

		$c		.= $this->getLL('progress.storeToHD');

		// Store Tarball to disc
			$tarballFile = fopen('../../mm_forum_update.tar.gz','w');
			if(fwrite($tarballFile, $tarball)===false)
				return $c.$this->getLL('progress.failure');
			else $c .= $this->getLL('progress.success').'<br />';

		$c		.= $this->getLL('progress.moveInstaller');

		// Move install script into ext/ directory
			if(system('cp tx_mmforum_update.php ../../tx_mmforum_update.php')===false)
				return $c.$this->getLL('progress.failure');
			else $c .= $this->getLL('progress.success');

		$c		.= $this->getLL('progress.performInstallation');

		// Perform installation
			header('Location: ../../tx_mmforum_update.php');

		return $c;
	}

    /**
     * Gets a language variable from the locallang_install.xml file.
     * Wrapper function to simplify retrieval of language dependent
     * strings.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @param   string $key The language string key
     * @return  string      The language string
     */
	function getLL($key) {
		return $GLOBALS['LANG']->getLL('updater.'.$key);
	}

    /**
     * Initializes the installation module.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  void
     */
	function init() {
		$GLOBALS['LANG']->includeLLFile('EXT:mm_forum/mod1/locallang_updater.xml');
	}

	/**
	 * Loads the newest revision number from svn.typo3.net
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-04-20
	 * @return  int The newest revision number
	 */
	function getNewestRevision() {
		$repPage = @file_get_contents($this->url_rep,false);
		if(!$repPage) return false;
		else {
			preg_match('/Revision ([0-9]+)/',$repPage,$matches);
			return $matches[1];
		}
	}
}

	// XClass inclusion
if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/mod1/class.tx_mmforum_updater.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/mod1/class.tx_mmforum_updater.php"]);
}
?>