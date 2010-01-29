<?php
/***************************************************************
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
 ***************************************************************/

/**
 * This class handles the update process between different mm_forum
 * versions. For example, there was a change of table and field
 * names between versions 0.0.3 and 0.0.4. This script guarantees
 * backwards compatibility.
 *
 * @author Martin Helmich <m.helmich@mittwald.de>
 * @version 2007-04-25
 * @package mm_forum
 * @subpackage ExtCore
 */
class ext_update {

    /**
     * Obsolete table names and their new names
     */
    var $obsTableNames = array(
        'tx_mmpm_inbox'                 => 'tx_mmforum_pminbox',
        'tx_mmforumsearch_results'      => 'tx_mmforum_searchresults',
        'tx_mmforumsearch_wordlist'     => 'tx_mmforum_wordlist',
        'tx_mmforumsearch_wordmatch'    => 'tx_mmforum_wordmatch'
    );

    /**
     * Obsolete field names in the fe_user table and their new names
     */
    var $obsFieldNames_feuser = array(
        'tx_mmfeuserreg_interests'      => 'tx_mmforum_interests',
        'tx_mmfeuserreg_occ'            => 'tx_mmforum_occ',
        'tx_mmfeuserreg_reg_hash'       => 'tx_mmforum_reg_hash'
    );
    var $action;

    /**
     * The main function. Executes all updates.
     * @return string  The update process output.
     */
    function main() {
        $content = "";

        foreach($this->action as $action) {
            if($action == 'rename_tables') $content .= $this->renameTables();
        }

        return $content;
    }

    /**
     * Renames tables and field names to make them 0.0.4-conform.
     * @return string  The update process output.
     */
    function renameTables() {
        $sql = $this->renameTables_getQuery();
        $content .= 'Executing the following MySQL queries:<br /><br />'.implode('<br />',$sql);
        $content .= '<br /><br />';

        foreach($sql as $singleQuery)
            mysql_query($singleQuery);

        return $content;
    }

    /**
     * Generates the MySQL-queries for renaming all obsolete table and field names.
     * @return array  An array containing the MySQL-queries
     */
    function renameTables_getQuery() {
        $sql = array();

        foreach($this->obsTableNames as $oldTable => $newTable) {
            $sql[] = "DROP TABLE IF EXISTS $newTable";
            $sql[] = "ALTER TABLE ".$oldTable." RENAME ".$newTable."";
        }
        foreach($this->obsFieldNames_feuser as $oldField => $newField) {
            $sql[] = "ALTER TABLE fe_users DROP $newField";
            $sql[] = "ALTER TABLE fe_users CHANGE $oldField $newField tinytext NOT NULL";
        }

        return $sql;
    }

    /**
     * Determines if an update is required and if so, what has to be done.
     * This function determines if an update to a newer version is necessary.
     * If the function notices, that an update is necessary, it will tell the
     * main function what to do.
     * @return boolean TRUE, if an update is required, otherwise FALSE.
     */
    function access() {

        // Check for deprecated table names
        // Update check from 0.0.3 to 0.0.4
            $res = $GLOBALS['TYPO3_DB']->sql_query('SHOW TABLES');
            $tbl = array_keys($this->obsTableNames);
            while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
                if(in_array($arr[0],$tbl)) {
                    $this->action[] = 'rename_tables';
                    return true;
                }
            }

        return false;

    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/class.ext_update.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/class.ext_update.php']);
}
?>