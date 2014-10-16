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
 * This script performs the actual update of the mm_forum extension
 * to the newest version.
 *
 * @author    Martin Helmich <m.helmich@mittwald.de
 * @version   2008-04-20
 * @copyright 2008 Martin Helmich, Mittwald CM Service
 */

/**
 * Redirects back to updater in case of an error.
 * This function redirects the browser back to the mm_forum updater
 * module in case an error occurs during update. Also restores the
 * backup copy of the extension directory.
 *
 * @author  Martin Helmich <m.helmich@mittwald.de
 * @version 2008-04-20
 * @param   boolean $revertBackup Defines whether the extension directory
 *                                should be restored using the backup copy.
 * @return  void
 */
function dieError($revertBackup) {
	if($revertBackup) {
		system('rm -rf ./mm_forum');
		system('mv ./mm_forum-backup ./mm_forum');
	}
	header('Location: mm_forum/mod1/index.php?update_result=error'); die();
}

/**
 * Redirects back to updater in case of success.
 * This function redirects the browser back to the mm_forum updater
 * module after the update has been completed successfully. Also removes
 * the mm_forum backup copy.
 *
 * @author  Martin Helmich <m.helmich@mittwald.de
 * @version 2008-04-20
 * @return  void
 */
function dieSuccess() {
	system('rm -rf ./mm_forum-backup/');
	header('Location: mm_forum/mod1/index.php?update_result=success'); die();
}

// Make a backup copy of the extension directory
	if(system('cp -r ./mm_forum ./mm_forum-backup') === false)
		dieError(false);

// Remove the mm_forum directory
	if(system('rm -rf ./mm_forum/') === false)
		dieError(true);

// Unzip the tarball archive
	if(system('tar -xzf mm_forum_update.tar.gz') === false)
		dieError(true);

// Set access rights
	system('chmod -R 755 ./mm_forum');

// Remove the tarball archive
	system('rm mm_forum_update.tar.gz');

// Return user to updater form
	dieSuccess();
?>
