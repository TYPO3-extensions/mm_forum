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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   50: class tx_mmforum_ranksBE
 *   60:     function main($content)
 *  128:     function getLL($key)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * This class handles user ranks in the backend. This includes for
 * example to display a sorted list of all user ranks. The actual
 * creation and editing of user ranks is taken over by TYPO3 internal
 * methods.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @version    2007-06-06
 * @package    mm_forum
 * @subpackage Backend
 */
class tx_mmforum_ranksBE {

    /**
     * Main function. Displays a list of all user ranks.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-06-06
     * @param   string $content The content
     * @return  string          The content
     */
    function main($content) {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_ranks',
            'deleted=0',
            '',
            'special DESC, CAST(minPosts AS UNSIGNED)'
        );

        $returnUrl  = t3lib_extMgm::extRelPath('mm_forum').'mod1/index.php';
        $returnUrl  = urlencode($returnUrl);
        $createlink = $GLOBALS['BACK_PATH'].'alt_doc.php?returnUrl='.$returnUrl.'&edit[tx_mmforum_ranks]['.$this->p->confArr['forumPID'].']=new';

		$content .= '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td class="mm_forum-listrow_header" colspan="4" valign="top"><img src="img/ranks.png" style="vertical-align: middle; margin-right:8px;" />'.$GLOBALS['LANG']->getLL('menu.ranks').'</td>
    </tr>
	<tr>
		<td class="mm_forum-listrow_label">'.$this->getLL('title').'</td>
		<td class="mm_forum-listrow_label">'.$this->getLL('icon').'</td>
		<td class="mm_forum-listrow_label">'.$this->getLL('posts').'</td>
		<td class="mm_forum-listrow_label" style="width:24px;">&nbsp;</td>
	</tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td><a href="'.$createlink.'"><img src="img/rank-new.png" alt="Create" /></a></td>
    </tr>
	';

        while($rank = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

            if($rank['color'])
                $title = '<span style="color: '.$rank['color'].'">'.htmlentities($rank['title']).'</span>';
            else $title = htmlentities($rank['title']);

            if($rank['icon'])
                $icon = '<img src="'.$GLOBALS['BACK_PATH'].'../uploads/tx_mmforum/'.$rank['icon'].'" />';
            else $icon = $this->getLL('noicon');

            if($rank['special'])
                $posts = $this->getLL('special');
            else
                $posts = $rank['minPosts'];
            $editlink = $GLOBALS['BACK_PATH'].'alt_doc.php?returnUrl='.$returnUrl.'&edit[tx_mmforum_ranks]['.$rank['uid'].']=edit';

            $i = 0;
            $content .= '<tr class="mm_forum-listrow">
                <td>'.$title.'</td>
                <td>'.$icon.'</td>
                <td>'.$posts.'</td>
                <td><a href="'.$editlink.'"><img src="img/edit.png" alt="Edit" /></a></td>
            </tr>
            ';

        }

        $content .= '</table>';

        return $content;
    }

    /**
     * Gets a language variable from the locallang_forumadmin.xml file.
     * Wrapper function to simplify retrieval of language dependent
     * strings.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-23
     * @param   string $key The language string key
     * @return  string      The language string
     */
    function getLL($key) {
        return $GLOBALS['LANG']->getLL('ranks.'.$key);
    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_ranksbe.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_ranksbe.php']);
}
?>