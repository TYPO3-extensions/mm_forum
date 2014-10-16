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
	 * @version    2010-07-23
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @author     Peter Schuster <typo3@peschuster.de>
	 * @package    mm_forum
	 * @subpackage Messaging
	 */

class tx_mmforum_userSearch {
	/**
	 * Page id of user records
	 *
	 * @var int
	 */
	protected $pid;
	/**
	 * Allowed fe_groups id for users.
	 *
	 * @var int
	 */
	protected $group_id;
	protected $field;

	/**
	 * True, if object is initialized.
	 *
	 * @var boolean
	 */
	protected $is_init = false;

	/**
	 * Initialize internal variables and db connection.
	 *
	 * @return void
	 */
	protected function init() {
		error_reporting(0);
		session_start();

		tslib_eidtools::connectDB();

		$this->pid = $_SESSION['tx_mmforum_pi3']['userPID'];
		$this->group_id = $_SESSION['tx_mmforum_pi3']['userGID'];
		$this->field = $_SESSION['tx_mmforum_pi3']['usernameField'];

		$this->is_init = true;
	}

	/**
	 * Check whether field name is valid.
	 * -> prevent sql injection
	 *
	 * @param string $field field name
	 * @return boolean true, if column name is valid
	 */
	protected function validateName($field) {
		if ($field === 'username')
			return true;
		
		$res = $GLOBALS['TYPO3_DB']->sql_query('SHOW COLUMNS FROM fe_users;');
		if ($res) {
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				if ($row['Field'] === $field) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Searche for users and returns usernames as result
	 *
	 * @param string $sword search string
	 * @return array Array of usernames
	 */
	public function search($sword) {
		$result = array();

		if (!$this->is_init) {
			$this->init();
		}

		if (!$this->validateName($this->field))
			return $result;

		$search = $GLOBALS['TYPO3_DB']->quoteStr($sword, 'fe_users');

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				$this->field, 'fe_users',
                'disable=0 AND deleted=0 AND ' . $this->field . ' LIKE \'' . $search . '%\'' .
					' AND pid=' . $this->pid . ' AND FIND_IN_SET(' . $this->group_id . ', usergroup)',
                '',						// group by
				$this->field . ' ASC',	// order by
				'8'						// limit
			);

		while(list($item) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
			array_push($result, $item);
		}

		return $result;
	}

	/**
	 * Renders search result data to json array.
	 *
	 * @param array $data raw data
	 * @return string compiled json array
	 */
	public function render(array $data) {
		$content = '';

		if(function_exists('json_encode')) {
			$content = json_encode($data);
		} else {
			$content = '{"' . implode('","', $data) . '"}';
		}

		return $content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi3/ajax.tx_mmforum_usersearch.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi3/ajax.tx_mmforum_usersearch.php']);
}


if (!defined('TYPO3_MODE'))
	die();

$SOBE = t3lib_div::makeInstance('tx_mmforum_userSearch');
/* @var $SOBE tx_mmforum_userSearch */

$sword = t3lib_div::_GP('userSearch');
$data = $SOBE->search($sword);

header('Content-Type: text/plain');
echo $SOBE->render($data);

?>
