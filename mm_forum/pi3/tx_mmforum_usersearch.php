<?php
/*
 *  Copyright notice
 *
 *  (c) 2007 Mittwald CM Service
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
 * This script is used for the dynamic user search in the tx_mmforum_pi3
 * private messaging plugin.
 * The idea is to present the user a list of users correspoinding to
 * the input the user already made. If the user has e.g. already entered
 * the first two letters of a user name, this script presents a list of
 * all users whose usernames start with these two letters.
 * 
 * @version    2007-04-13
 * @author     Stefan Horenkamp <s.horenkamp@mittwald.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    mm_forum
 * @subpackage Messaging
 */

error_reporting(0);

header('Content-Type: text/plain');
 
include('../../../../typo3conf/localconf.php');       

$userSearch_searchstring	= $_POST['userSearch'];

echo get_content($userSearch_searchstring, $userSearch_userPID);

/**
 * Searches for users whose usernames start with a certain string
 * and returns a list with name proposals.
 * 
 * @param  string $figure The string the usernames to be found have to
 *                        start with.
 * @param  int    $pid    The page UID of the global fe_user storage page.
 * @return string         A HTML list containing a list of users whose usernames
 *                        start with the letter combination specified by the user's
 *                        input.
 * @version 2008-06-22
 */
function get_content($figure)
{   
    global $typo_db;
    global $typo_db_username;
    global $typo_db_password;
    global $typo_db_host;                
    
	session_start();
	
	$pid		= $_SESSION['tx_mmforum_pi3']['userPID'];
	
	$user = array();
    $link = mysql_connect($typo_db_host, $typo_db_username, $typo_db_password) or exit;
    @mysql_select_db($typo_db, $link) or exit;
    
    $figure = mysql_escape_string($figure);
    $pid = intval($pid);    
    
    $query = 'SELECT username FROM fe_users WHERE disable=0 AND deleted=0 AND pid='.$pid.' AND username LIKE \''.$figure.'%\' ORDER BY username DESC';
    $res   = mysql_query($query, $link) or exit;
    $count = mysql_num_rows($res);
	
    if($count == 0 || $count > 8)
        $content = '';
    else {
        while($row = mysql_fetch_assoc($res))
            array_push($user, $row['username']);
        
        $max = ($count<8)?$count:8;
        
        for($i=0;$i<$max;$i++) {
        	$userObjs[] = '"'.htmlspecialchars($user[$i]).'"';
        }
        
        $content = '['.implode(',',$userObjs).']';
    }
    
    mysql_close($link);
    return $content;
}

?>