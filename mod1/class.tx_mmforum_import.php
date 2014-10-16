<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2007 Mittwald CM Service
 *  All rights reserved
 *
 *  This script is part of the Typo3 project. The Typo3 project is
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   55: class tx_mmforum_import
 *   72:     function main($content)
 *  108:     function display_step0()
 *  148:     function display_step1()
 *  228:     function display_step2()
 *  254:     function import_chc($dbObj)
 *  273:     function import_phpbb($dbObj)
 *  290:     function outputImportSettings()
 *
 * TOTAL FUNCTIONS: 7
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * This class manages data import into the mm_forum extension.
 * The import script allows the administrator to import data from
 * other applications, like the phpBB message board or the
 * "CHC Forum" TYPO3 extension.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @version    2007-05-02
 * @package    mm_forum
 * @subpackage Backend
 */
class tx_mmforum_import {

    var $importData;
    var $actions = array('phpbb','chc');

    /**
     * Main function. Generates all output.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-02
     *
     * @param   string $content The content that was generated until now
     * @return  string          The import script content
     *
     * @uses    display_step0
     * @uses    display_step1
     * @uses    display_step2
     * @uses    outputImportSettings
     */
    function main($content) {
        $this->importData = t3lib_div::_GP('tx_mmforum_import');

        if(!isset($this->importData['step']))
            $this->importData['step'] = 0;
        if(isset($this->importData['extdb']) && !is_array($this->importData['extdb'])) {
            $this->importData['extdb'] = stripslashes($this->importData['extdb']);
            $this->importData['extdb'] = unserialize($this->importData['extdb']);
        }

        if($this->importData['action'] == 'phpbb') $this->maxSteps = 6;
        else $this->maxSteps = 4;

        switch($this->importData['step']) {
            case 0:             $content .= $this->display_step0(); break;
            case 1:             $content .= $this->display_step1(); break;
            case 2:             $content .= $this->display_step2(); break;
        }

        $content .= $this->outputImportSettings();

        return $content;
    }

    /**
     * Displays the first step of the import procedure.
     * This functions displays the first step of the mm_forum data import
     * procedure. This step consists of selecting the import procedure
     * that is to be conducted. Currently (0.0.5) the user may choose
     * between importing data from a phpBB board and a CHC forum.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-02
     * @return  string The content of step 1
     * @uses    display_step1
     */
    function display_step0() {
        global $LANG;

        if(isset($this->importData['step0']['submit'])) {
            if(in_array($this->importData['action'],$this->actions)) {
                $this->importData['step'] = 1;
                return $this->display_step1();
            }
            else $error = '<div class="mm_forum-fatalerror">'.$LANG->getLL('import.step0.error').'</div>';
        }

        $content .= '<fieldset><legend>'.sprintf($LANG->getLL('import.stepX'),1).': '.$LANG->getLL('import.step0').'</legend>';

        $content .= '<table cellspacing="0" cellpadding="3" style="width:100%;">';
        $content .= '<tr><td valign="top" style="width:32px;"><input id="import_phpbb" type="radio" name="tx_mmforum_import[action]" value="phpbb" /></td>';
        $content .= '<td valign="top"><label for="import_phpbb" style="display:block; width:100%;"><strong>'.$LANG->getLL('import.step0.phpBB').'</strong><br />'.$LANG->getLL('import.step0.phpBB.desc').'</label></td></tr>';
        $content .= '<tr><td valign="top"><input id="import_chc" type="radio" name="tx_mmforum_import[action]" value="chc"></td>';
        $content .= '<td valign="top"><label for="import_chc" style="display:block; width:100%;"><strong>'.$LANG->getLL('import.step0.CHC').'</strong><br />'.$LANG->getLL('import.step0.CHC.desc').'</label></td></tr>';
        $content .= '</table>';
        $content .= $error?$error:'';

        $content .= '<br /><input type="submit" name="tx_mmforum_import[step0][submit]" value="'.$LANG->getLL('import.step0.continue').'" />';

        $content .= '</fieldset>';
        return $content;
    }

    /**
     * Displays the second step of the import procedure.
     * This function displays the second step of the mm_forum data import
     * procedure. This step consists of selecting the MySQL database from
     * which the data is to be imported. This may be the local TYPO3 database
     * or another external database. In the latter case, the user will have
     * to specify the connection data for this database.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-02
     * @return  string The content of step 2
     * @uses    display_step2
     */
    function display_step1() {
        global $LANG;

        if(isset($this->importData['step1']['submit'])) {
            if($this->importData['db'] == 'external') {
                if(strlen($this->importData['extdb']['server']) == 0 ) $error['server'] = '<div class="mm_forum-fatalerror">'.$LANG->getLL('import.step1.error_server').'</div>';
                if(strlen($this->importData['extdb']['user'])   == 0 ) $error['user']   = '<div class="mm_forum-fatalerror">'.$LANG->getLL('import.step1.error_user').'</div>';
                if(strlen($this->importData['extdb']['dbname']) == 0 ) $error['dbname'] = '<div class="mm_forum-fatalerror">'.$LANG->getLL('import.step1.error_dbname').'</div>';

                $link = @mysql_connect($this->importData['extdb']['server'],$this->importData['extdb']['user'],$this->importData['extdb']['password'],true);
                if(!$link) $error['connect'] = '<div class="mm_forum-fatalerror">'.$LANG->getLL('import.step1.error_connect').'</div>';
                else {
                    $res = mysql_select_db($this->importData['extdb']['dbname'],$link);
                    if(!$res) $error['selectdb'] = '<div class="mm_forum-fatalerror">'.$LANG->getLL('import.step1.error_selectdb').'</div>';
                }
            }

            if(count($error) == 0) {
                $this->importData['step'] = 2;
                return $this->display_step2();
            }
        }

        $content .= '<fieldset><legend>'.sprintf($LANG->getLL('import.stepXofY'),2,$this->maxSteps).': '.$LANG->getLL('import.step1').'</legend>';

        $db_ext = ( $this->importData['db']=='external')?'checked="checked"':'';
        $db_loc = (!$this->importData['db']=='external')?'checked="checked"':'';

        $content .= '<table cellspacing="0" cellpadding="3" border="0">
    <tr>
        <td style="width:32px" valign="top"><input type="radio" name="tx_mmforum_import[db]" value="local" '.$db_loc.' /></td>
        <td><strong>'.$LANG->getLL('import.step1.localDB').'</strong></td>
    </tr>
    <tr>
        <td style="width:32px" valign="top"><input type="radio" name="tx_mmforum_import[db]" value="external" '.$db_ext.' /></td>
        <td>
            <strong>'.$LANG->getLL('import.step1.extDB').'</strong>
            <table cellspacing="0" cellpadding="2" border="0" style="margin-top: 4px;">
                <tr>
                    <td style="padding-left:0px;">'.$LANG->getLL('import.step1.ext.server').'</td>
                    <td><input type="text" name="tx_mmforum_import[extdb][server]" value="'.$this->importData['extdb']['server'].'" />'.$error['server'].'</td>
                </tr>
                <tr>
                    <td style="padding-left:0px;">'.$LANG->getLL('import.step1.ext.user').'</td>
                    <td><input type="text" name="tx_mmforum_import[extdb][user]" value="'.$this->importData['extdb']['user'].'" />'.$error['user'].'</td>
                </tr>
                <tr>
                    <td style="padding-left:0px;">'.$LANG->getLL('import.step1.ext.password').'</td>
                    <td><input type="text" name="tx_mmforum_import[extdb][password]" value="'.$this->importData['extdb']['password'].'" /></td>
                </tr>
                <tr>
                    <td style="padding-left:0px;">'.$LANG->getLL('import.step1.ext.dbname').'</td>
                    <td><input type="text" name="tx_mmforum_import[extdb][dbname]" value="'.$this->importData['extdb']['dbname'].'" />'.$error['dbname'].'</td>
                </tr>
            </table>
        </td>
    </tr>
</table>'.$error['connect'].$error['selectdb'].'
<br /><input type="submit" name="tx_mmforum_import[step1][submit]" value="'.$LANG->getLL('import.step1.continue').'" />
    ';

        $content .= '</fieldset>';

        unset($this->importData['extdb']);

        return $content;
    }

    /**
     * Displays the second step of the import procedure.
     * This function displays the third step of the mm_forum data import
     * procedure. This step consists of starting the part of the import
     * process that is dependend on the selected method in step 1.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-02
     * @return  string The import procedure content
     * @uses    import_phpbb
     * @uses    import_chc
     */
    function display_step2() {
        if($this->importData['db'] == 'local') $dbObj = $GLOBALS['TYPO3_DB'];
        else {
            $dbObj = t3lib_div::makeInstance('t3lib_db');
            $dbObj->sql_pconnect($this->importData['extdb']['server'],$this->importData['extdb']['user'],$this->importData['extdb']['password']);
            $dbObj->sql_select_db($this->importData['extdb']['dbname']);
        }

        switch($this->importData['action']) {
            case 'phpbb':       $content = $this->import_phpbb($dbObj); break;
            case 'chc':         $content = $this->import_chc($dbObj); break;
        }
        return $content;
    }

    /**
     * Conducts the CHC Forum data import.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-02
     * @param   object dbObj A database object signifying the database the data
     *                       that is to be imported is stored in. This object is an
     *                       instance of the class "t3lib_db".
     * @return  string       The result of the CHC Forum import
     */
    function import_chc($dbObj) {
        require_once(t3lib_extMgm::extPath('mm_forum', 'mod1/class.tx_mmforum_chcimport.php'));
        $import_chc = t3lib_div::makeInstance('tx_mmforum_chcimport');
        $import_chc->dbObj = $dbObj;
        $import_chc->p     = $this->p;

        return $import_chc->main('');
    }

    /**
     * Conducts the phpBB data import.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-02
     * @param   object dbObj A database object signifying the database the data
     *                       that is to be imported is stored in. This object is an
     *                       instance of the class "t3lib_db".
     * @return  string       The result of the phpBB import
     */
    function import_phpbb($dbObj) {
        require_once(t3lib_extMgm::extPath('mm_forum', 'mod1/class.tx_mmforum_phpbbimport.php'));
        $import_phpbb = t3lib_div::makeInstance('tx_mmforum_phpbbimport');
        $import_phpbb->dbObj = $dbObj;
        $import_phpbb->p     = $this->p;

        return $import_phpbb->main('');
    }

    /**
     * Outputs imporant configuration variables.
     * This function outputs important configurations variables for
     * submission to the next step.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-02
     * @return  string The configuration vars in hidden HTML input fields
     */
    function outputImportSettings() {
        if(!empty($this->importData['action']))
            $content .= '<input type="hidden" name="tx_mmforum_import[action]" value="'.$this->importData['action'].'" />';
        $content .= '<input type="hidden" name="tx_mmforum_import[step]" value="'.$this->importData['step'].'" />';

        if(isset($this->importData['extdb']))
            $content .= '<input type="hidden" name="tx_mmforum_import[extdb]" value="'.htmlspecialchars(serialize($this->importData['extdb'])).'" />';
        if(!empty($this->importData['db']))
            $content .= '<input type="hidden" name="tx_mmforum_import[db]" value="'.$this->importData['db'].'" />';

        return $content;
    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_import.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_import.php']);
}
?>