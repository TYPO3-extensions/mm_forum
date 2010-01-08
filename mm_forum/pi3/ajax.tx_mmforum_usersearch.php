<?php

/*                                                                     *
 *  COPYRIGHT NOTICE                                                   *
 *                                                                     *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                    *
 *      All rights reserved                                            *
 *                                                                     *
 *  This script is part of the TYPO3 project. The TYPO3 project is     *
 *  free software; you can redistribute it and/or modify               *
 *  it under the terms of the GNU General Public License as published  *
 *  by the Free Software Foundation; either version 2 of the License,  *
 *  or (at your option) any later version.                             *
 *                                                                     *
 *  The GNU General Public License can be found at                     *
 *  http://www.gnu.org/copyleft/gpl.html.                              *
 *                                                                     *
 *  This script is distributed in the hope that it will be useful,     *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of     *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      *
 *  GNU General Public License for more details.                       *
 *                                                                     *
 *  This copyright notice MUST APPEAR in all copies of the script!     *
 *                                                                     */



	/**
	 * This script is used for the dynamic user search in the tx_mmforum_pi3
	 * private messaging plugin.
	 * The idea is to present the user a list of users correspoinding to
	 * the input the user already made. If the user has e.g. already entered
	 * the first two letters of a user name, this script presents a list of
	 * all users whose usernames start with these two letters.
	 *
	 * @version    2010-01-08
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    mm_forum
	 * @subpackage Messaging
	 */

error_reporting(0);
header('Content-Type: text/plain');

$feUserObj = tslib_eidtools::initFeUser();
session_start();

global $TYPO3_DB;
$pid = $_SESSION['tx_mmforum_pi3']['userPID'];
$gid = $_SESSION['tx_mmforum_pi3']['userGID'];

$search = $TYPO3_DB->quoteStr(t3lib_div::_GP('userSearch'), 'fe_users');

$arr = array();
$res = $TYPO3_DB->exec_SELECTquery( 'username', 'fe_users',
                                    "username LIKE '$search%' AND pid=$pid AND FIND_IN_SET($gid,usergroup)",
                                    '', 'username ASC', '8' );
while(list($username) = $TYPO3_DB->sql_fetch_row($res)) array_push($arr, $username);

if(function_exists('json_encode')) echo json_encode($arr);
else echo '{"' . implode('","',$arr) . '"}';

?>
