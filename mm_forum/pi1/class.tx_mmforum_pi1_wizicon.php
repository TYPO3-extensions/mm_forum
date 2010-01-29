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
 * Class that adds the wizard icon.
 *
 * @author	   Holger Trapp <h.trapp@mittwaldmedien.de>
 * @copyright  2007 Mittwald CM Service
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_pi1_wizicon {
	function proc($wizardItems)	{
		global $LANG;

		$LL = $this->includeLocalLang();

		for($i=1; $i<=6; $i++) {
			$wizardItems["plugins_tx_mmforum_pi$i"] = array(
				"icon"=>t3lib_extMgm::extRelPath("mm_forum")."pi1/ce_wiz.gif",
				"title"=>$LANG->getLLL('pi'.$i.'_title',$LL),
				"description"=>$LANG->getLLL('pi'.$i.'_plus_wiz_description',$LL),
				"params"=>'&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=mm_forum_pi'.$i
			);
		}

		return $wizardItems;
	}
	function includeLocalLang()	{
		global $LANG;

        $LOCAL_LANG = $LANG->includeLLFile('EXT:mm_forum/locallang.xml',FALSE);
        return $LOCAL_LANG;
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_pi1_wizicon.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_pi1_wizicon.php"]);
}

?>