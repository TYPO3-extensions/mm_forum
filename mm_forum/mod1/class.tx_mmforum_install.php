<?php
/*
 *  Copyright notice
 *
 *  (c) 2007-2009 Mittwald CM Service
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   81: class tx_mmforum_install
 *
 *              SECTION: General module functions
 *  181:     function main()
 *  202:     function getLL($key)
 *  213:     function init()
 *  227:     function loadDefaultConfiguration()
 *
 *              SECTION: Content helper functions
 *  248:     function display_categoryLinks()
 *  270:     function display_helpForm()
 *
 *              SECTION: Main configuration form
 *  289:     function display_allConfigForm()
 *  408:     function getTextField($fieldname,$value,$size=null)
 *  422:     function getMD5Field($fieldname,$value)
 *  437:     function getCheckField($fieldname,$checked)
 *  454:     function getSelectField($fieldname,$value,$table,$pid,$limit)
 *  491:     function getLSelectField($fieldname, $value, $options)
 *  515:     function getGroupField($table,$value,$fieldname,$add_button=false,$add_pid=0)
 *
 *              SECTION: Installation
 *  559:     function display_installation()
 *  577:     function display_install_userGroups()
 *  608:     function display_install_storagePid()
 *  641:     function display_install_userPid()
 *
 *              SECTION: Saving
 *  678:     function save()
 *
 *              SECTION: Status detection
 *  725:     function getUserGroupsConfigured()
 *  742:     function getIsConfigured()
 *
 * TOTAL FUNCTIONS: 20
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(PATH_t3lib.'class.t3lib_tcemain.php');

/**
 * This class handles the backend mm_forum configuration. If offers
 * dynamically generated forms to allow the user to edit the mm_forum
 * configuration vars in a very easy way.
 *
 * @author     Martin Helmich
 * @version    2009-02-12
 * @copyright  2007-2009 Mittwald CM Service
 * @package    mm_forum
 * @subpackage Backend
 */
class tx_mmforum_install {

	    /**
	     * Required configuration variables
	     */
	var $required = array(
		'storagePID', 'userPID', 'userGroup', 'adminGroup'
	);





		/**
		 * General module functions
		 */






		/**
		 *
		 * The main function
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  string The module content
		 *
		 */

	function main() {
		$this->init();

		$this->save();

		if($this->getIsConfigured()) 	$content = $this->display_allConfigForm();
		else							$content = $this->display_installation();

		return $content;
	}



		/**
		 *
		 * Gets a language variable from the locallang_install.xml file.
		 * Wrapper function to simplify retrieval of language dependent
		 * strings.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @param   string $key The language string key
		 * @return  string      The language string
		 *
		 */

	function getLL($key) {
		return $GLOBALS['LANG']->getLL('install.'.$key);
	}



		/**
		 *
		 * Initializes the installation module.
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  void
		 *
		 */

	function init() {
		$this->instVars    = t3lib_div::_GP('tx_mmforum_install');
		$this->conf        = $this->p->config['plugin.']['tx_mmforum.'];
		$this->fieldConfig = $this->p->modTSconfig['properties']['submodules.']['installation.']['categories.'];

		$GLOBALS['LANG']->includeLLFile('EXT:mm_forum/mod1/locallang_install.xml');
	}



		/**
		 *
		 * Loads the default configuration from the ext_typoscript_constants.txt file.
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  array A configuration array
		 *
		 */

	function loadDefaultConfiguration() {
        $conf    = file_get_contents('../ext_typoscript_constants.txt');
        $parser  = t3lib_div::makeInstance('t3lib_TSparser');
        $parser->parse($conf);

        return $parser->setup['plugin.']['tx_mmforum.'];
	}





		/**
		 * Content helper functions
		 */





		/**
		 *
		 * Displays links to all field categories.
		 * The mm_forum configuration variables are grouped into sets. This function
		 * generates links pointing to forms representing each of these sets.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-12-20
		 * @return  string  A set of links leading to the regarding field categories
		 *
		 */

	function display_categoryLinks() {
		foreach($this->fieldConfig as $category => $v) {
			if($v === 'MMFORUM_CONF_CATEGORY') {
				$conf = $this->fieldConfig["$category."];
				$label = $conf['name'] ? $GLOBALS['LANG']->sL($conf['name'],1) : $this->getLL('cat.'.$category);
				$icon = $GLOBALS['BACK_PATH'].preg_replace_callback("/^EXT:([a-z0-9_-]+)\//",array('tx_mmforum_install','replaceRelativeExtReference'), $conf['icon']);
				if($this->instVars['ctg'] == $category) {
					$items[] = '<div class="mm_forum-button-hover"><img src="'.$icon.'" />'.$label.'</div>';
				} else {
					$set = $this->p->MOD_SETTINGS['function'];
					$items[] = '<div class="mm_forum-button" onmouseover="this.className=\'mm_forum-button-hover\';" onmouseout="this.className=\'mm_forum-button\';" onclick="document.location.href=\'index.php?SET[function]='.$set.'&tx_mmforum_install[ctg]='.$category.'\';"><img src="'.$icon.'" />'.$label.'</div>';
				}
			}
		}

		return '<div class="mm_forum-buttons">'.implode('',$items).'<div style="clear:both;"></div></div>';
	}



		/**
		 *
		 * Callback function to replace extension file path references (EXT:ext_key) with
		 * the relative extension path.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2009-12-20
		 * @param   array $matches The matches of a regular expression
		 * @return  string         The replacement for the matches substring
		 *
		 */

	function replaceRelativeExtReference($matches) { return t3lib_extMgm::extRelPath($matches[1]); }



		/**
		 *
		 * Displays the mm_forum help text. This text is displayed of no category
		 * is selected.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  string  The mm_forum help text
		 *
		 */

    function display_helpForm() {
        $template = file_get_contents(t3lib_div::getFileAbsFileName('EXT:mm_forum/res/tmpl/mod1/install.html'));
		$template = tx_mmforum_BeTools::getSubpart($template, '###INSTALL_HELP###');

		$marker = array(
			'###INST_HELP_TITLE###'		=> $this->getLL('help.title'),
			'###INST_HELP_TEXT###'		=> $this->getLL('help.content')
		);

        return tx_mmforum_BeTools::substituteMarkerArray($template, $marker);
    }





		/**
		 * Main configuration form
		 */





		/**
		 *
		 * Displays the configuration form.
		 * This function dynamically generates a configuration form allowing
		 * the user to edit the fields of the selected configuration category.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  string  The configuration form.
		 *
		 */

	function display_allConfigForm() {

		$defaultVars = $this->loadDefaultConfiguration();

		if($this->getIsConfigured()) {
			$content = $this->display_categoryLinks();
			if(!$this->instVars['ctg']) return $content.$this->display_helpForm();
			$categoryData = $this->fieldConfig[$this->instVars['ctg'].'.'];
			$fieldData = $categoryData['items.'];
		} else {
			$content = '';
			$fieldData = $this->getRemainingPropertiesConfig();
			array_shift($fieldData);
			$categoryData = array_shift($fieldData);
			$categoryData = $this->fieldConfig[$categoryData['category'].'.'];
		}

		if(count($fieldData)==0) return $content;

		$label = $categoryData['name'] ? $GLOBALS['LANG']->sL($categoryData['title'],1) : $this->getLL('title.'.$this->instVars['ctg']);
		$icon = $GLOBALS['BACK_PATH'].preg_replace_callback("/^EXT:([a-z0-9_-]+)\//",array('tx_mmforum_install','replaceRelativeExtReference'), $categoryData['icon']);

		$content .= '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td class="mm_forum-listrow_header" valign="top" style="width:1px;"><img src="'.$icon.'" style="vertical-align: middle; margin-right:8px;" /></td>
        <td class="mm_forum-listrow_header" colspan="2" valign="top">'.$label.'</td>
    </tr>';

		foreach($fieldData as $field => $config) {

			if($config !== 'MMFORUM_CONF_ITEM') continue;

			$config = $fieldData["$field."];

			$label = $config['label'] ? $GLOBALS['LANG']->sL($config['label']) : $this->getLL('field.'.$field.'.title');
			$description = $config['description'] ? $GLOBALS['LANG']->sL($config['description']) : $this->getLL('field.'.$field.'.desc');

            $bigField = false;
            if($config['type'] == 'div') {
				$icon = $config['type.']['icon']
					? $GLOBALS['BACK_PATH'].preg_replace_callback("/^EXT:([a-z0-9_-]+)\//",array('tx_mmforum_install','replaceRelativeExtReference'), $config['type.']['icon'])
					: 'img/install-'.$field.'.png';
				$content .= '</table>
<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td class="mm_forum-listrow_header" valign="top" style="width:1px;"><img src="'.$icon.'" style="vertical-align: middle; margin-right:8px;" /></td>
        <td class="mm_forum-listrow_header" colspan="2" valign="top">'.$label.'</td>
    </tr>';

                continue;
            }

			$unit = $config['type.']['unit'] ? $GLOBALS['LANG']->sL($config['type.']['unit']) : '';

				if($config['type'] == 'string')	            $input = $this->getTextField($field,$this->conf[$field]);
			elseif($config['type'] == 'int')                $input = $this->getTextField($field,$this->conf[$field],12);
            elseif($config['type'] == 'md5')                $input = $this->getMD5Field($field,$this->conf[$field]);
			elseif($config['type'] == 'checkbox')           $input = $this->getCheckField($field,$this->conf[$field]=='1');
			elseif($config['type'] == 'group')              $input = $this->getGroupField($config['type.']['table'],$this->conf[$field],$field);
			elseif($config['type'] == 'select') {
				if($config['type.']['handler']) {
					if(strpos($config['type.']['handler'],'->') !== false) {
						list($className, $methodName) = explode('->', $config['type.']['handler']);
						$obj = t3lib_div::getUserObj($className);
						$options = $obj->$methodName($this->conf[$field], $field, $config);
					} else $options = $this->$config['type.']['handler']($this->conf[$field], $field, $config);
					$input = $this->getLSelectField($field,$this->conf[$field],$options);
				} elseif($config['type.']['table']) {
						if($config['type.']['table'] == 'fe_groups') $pid = $this->conf['userPID'];
					elseif($config['type.']['table'] == 'fe_users')  $pid = $this->conf['userPID'];
					else                                             $pid = $this->conf['storagePID'];

					$limit = $config['type.']['limit']?$config['type.']['limit']:100;
					$bigField = ($limit>1);
					$input = $this->getSelectField($field,$this->conf[$field],$config['type.']['table'],$pid,$limit);
				} elseif($config['type.']['options.']) {
					$options = array();
					foreach($config['type.']['options.'] as $k => $v)
						$options[$k] = $GLOBALS['LANG']->sL($v);
					$input = $this->getLSelectField($field,$this->conf[$field],$options);
				}
			} elseif($config['type'] == 'special') {
				if(strpos($config['type.']['handler'],'->') !== false) {
					list($className, $methodName) = explode('->', $config['type.']['handler']);
					$obj = t3lib_div::getUserObj($className);
					$obj->p = $this;
					$input = $obj->$methodName($this->conf[$field], $field, $config);
				} else {
					$input = $this->$config['type.']['handler']($this->conf[$field], $field, $config);
				}
            }
			$bigField = $config['type.']['big'] ? true : false;

			$input .= ' ' . $unit;

			if(isset($defaultVars[$field]) && strlen($defaultVars[$field])>0) {
				$defValue = $defaultVars[$field];

				if($config['type'] == 'checkbox') $defValue=($defValue=='1')?$this->getLL('yes'):$this->getLL('no');
				elseif($config['type.']['options.']) $defValue = $GLOBALS['LANG']->sL($config['type.']['options.'][$defValue]);

				$default = '<br />'.$this->getLL('default').': '.htmlentities($defValue).' '.$unit;
			} else $default = '';

            if(!$bigField)
			    $content .= '<tr class="mm_forum-listrow">
	    <td valign="top"><span style="color: #1555a0;">&#8718;</span></td>
	    <td valign="top" style="width:50%;">
		    <strong>'.$label.'</strong><br />
		    '.$description.'
	    </td>
	    <td valign="top" style="width:50%;">
		    '.$input.$default.'
	    </td>
    </tr>';
            else
                $content .= '<tr class="mm_forum-listrow">
        <td valign="top"><span style="color: #1555a0;">&#8718;</span></td>
        <td valign="top">
		    <strong>'.$label.'</strong><br />
		    '.$description.'
        </td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" align="right">'.$input.$default.'</td>
    </tr>';
		}

		$content .= '
	</table>

<input type="hidden" name="tx_mmforum_install[ctg]" value="'.($this->instVars['ctg']?$this->instVars['ctg']:'required').'" />
<div class="mm_forum-buttons">
	<div class="mm_forum-button" onmouseover="this.className=\'mm_forum-button-hover\';" onmouseout="this.className=\'mm_forum-button\';" onclick="document.forms[\'editform\'].submit();">
		<img src="img/save.png">
		'.$this->getLL('save').'
	</div>
	<div style="clear:both;"></div>
</div>
';

		return $content;
	}



		/**
		 *
		 * Generates a text field.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @param   string $fieldname The text field name
		 * @param   string $value     The text field value
		 * @param   int    $size      The text field size
		 * @return  string            The text field
		 *
		 */

	function getTextField($fieldname,$value,$size=null) {
		return '<input type="text" name="tx_mmforum_install[conf][0]['.$fieldname.']" value="'.htmlspecialchars($value).'" '.($size?'size="'.$size.'"':'style="width:100%"').' />';
	}



		/**
		 *
		 * Generates an encrypted password field.
		 * Expects a MD5 hash.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @param   string $fieldname The password field name
		 * @param   string $value     The current value.
		 * @return  string            The password field
		 *
		 */

    function getMD5Field($fieldname,$value) {
        return $this->getLL('currentValue').': '.$value.'<br /><input size="64" type="password" name="tx_mmforum_install[conf][0]['.$fieldname.']" />';
    }



		/**
		 *
		 * Generates a checkbox.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @param   string  $fieldname The checkbox name
		 * @param   boolean $checked   TRUE, if the checkbox is to be checked by default,
		 *                             otherwise FALSE.
		 * @return  string             The checkbox
		 *
		 */

	function getCheckField($fieldname,$checked) {
		return '<input type="hidden" name="tx_mmforum_install[conf][0]['.$fieldname.']" value="0" /><input type="checkbox" value="1" name="tx_mmforum_install[conf][0]['.$fieldname.']" '.($checked?'checked="checked"':'').' />';
	}



		/**
		 *
		 * Generates a dynamic selector field. The selector options are loaded
		 * from the database.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @param   string $fieldname The selector box name
		 * @param   string $value     The current value
		 * @param   string $table     The table the options are to be loaded from
		 * @param   int    $pid       The page ID the options are to be loaded from
		 * @return  string            The selector field.
		 *
		 */

	function getSelectField($fieldname,$value,$table,$pid,$limit) {
		$size = ($limit<=5 && $limit > 0)?$limit:5;

        switch($table) {
            case 'fe_groups':   $titlefield = 'title';
            default:            $titlefield = 'title';
        }

        if($limit > 1)
            $value = $this->p->convertToTCEList($value,$table,$titlefield);
        $conf = array(
			'itemFormElName' => 'tx_mmforum_install[conf][0]['.$fieldname.']',
			'itemFormElValue' => $value?$value:'',
			'fieldChangeFunc' => array(''),
			'fieldConf' => array(
				'config' => array(
					'type' => 'select',
					'foreign_table' => $table,
					'foreign_table_where' => 'AND '.$table.'.hidden=0 AND '.$table.'.pid='.$pid.'',
					'size' => $size,
					#'autoSizeMax' => 10,
					'minitems' => 0,
					'maxitems' => $limit
				)
			)
		);
		return $this->p->tceforms->getSingleField_typeSelect('','tx_mmforum_install[conf][0]['.$fieldname.']',array(),$conf);
	}



		/**
		 *
		 * Generates a dynamic selector field for required user fields.
		 * This function generates a dynamic selector field for user fields
		 * that are required to be filled in upon registration. The fields of
		 * the fe_user table are loaded directly from the TCA.
		 *
		 * @param  string $value     The value of this form element. This is
		 *                           a commaseperated list of fe_user fields.
		 * @param  string $fieldname The name of this form element.
		 * @return string            The HTML code for this input field.
		 *
		 * @author Martin Helmich <m.helmich@mittwald.de>
		 *
		 */

	function getUserRequiredField($value,$fieldname) {

			/* A list of fields that cannot be selected */
		$excludeFields = array(
			'username','password','lockToDomain','disable','starttime','endtime',
			'felogin_redirectPid','usergroup','image','tx_mmforum_avatar',
			'tx_mmforum_md5','tx_mmforum_reg_hash','tx_mmforum_pmnotifymode',
			'lastlogin','TSconfig','lastlogin'
		);

			/* Get TCA of fe_user table */
		global $TCA;
		t3lib_div::loadTCA('fe_users');

			/* Get list of selected fields */
		$selFields = t3lib_div::trimExplode(',',$value);
		$selFieldsLabels = array();

			/* Iterate through all fields and retrieve labels. */
		foreach($TCA['fe_users']['columns'] as $field => $fConfig) {
			if(in_array($field, $excludeFields)) continue;

			$label = $GLOBALS['LANG']->sL($fConfig['label'],$fConfig['label']);
			$label = preg_replace('/:$/','',$label);
			$arr[] = array($label,$field);

			if(in_array($field, $selFields))
				$selFieldsLabels[] = $field.'|'.$label;
		}

			/* Compose select field's configuration array */
		$conf = array(
			'itemFormElName' => 'tx_mmforum_install[conf][0]['.$fieldname.']',
			'itemFormElValue' => implode(',',$selFieldsLabels),
			'fieldChangeFunc' => array(''),
			'fieldConf' => array(
				'config' => array(
					'type' => 'select',
					'items' => $arr,
					'size' => 5,
					'minitems' => 0,
					'maxitems' => 99
				)
			)
		);

			/* Create select field using the TCEForms class and return */
		return $this->p->tceforms->getSingleField_typeSelect('','tx_mmforum_install[conf][0]['.$fieldname.']',array(),$conf);
	}



		/**
		 *
		 * Generates a selector box with static option values.
		 *
		 * @param  string $fieldname The name of the selector box
		 * @param  string $value     The current value
		 * @param  array  $options   All possible values as associative array (value => label)
		 * @return string            The selector boxe's HTML code.
		 *
		 */

    function getLSelectField($fieldname, $value, $options) {
        $optionStr = '';
        foreach($options as $option => $label) {
            $checked = ($option == $value)?'selected="selected"':'';
            $optionStr .= '<option value="'.$option.'" '.$checked.'>'.$label.'</option>';
        }

        return '<select name="tx_mmforum_install[conf][0]['.$fieldname.']">'.$optionStr.'</select>';
    }



		/**
		 *
		 * Gets all columns of the fe_user table.
		 *
		 * @param  string $value     n/a
		 * @param  string $fieldname n/a
		 * @param  array  $config    n/a
		 * @return array             All columns of the fe_users table as numeric array
		 *
		 */

	function getFeUserFields($value, $fieldname, $config) {
		global $TCA;
		t3lib_div::loadTCA('fe_users');
		foreach($TCA['fe_users']['columns'] as $k => $v)
			$result[$k] = preg_replace('/:$/','',$GLOBALS['LANG']->sL($v['label']));
		return $result;
	}



		/**
		 *
		 * Generates a dynamic group field.
		 * This function uses TYPO3 internal functions to present
		 * a record selector using a popup window and a page tree selector.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @param   string  $table      The table from which the records are to be selected
		 * @param   string  $value      A commaseperated lists of record UIDs that are to be selected by default
		 * @param   string  $fieldname  The group field's name.
		 * @param   boolean $add_button TRUE, if a button to add new records is to be displayed
		 * @param   int     $add_pid    The PID for newly generated records.
		 * @return  string              The group field.
		 *
		 */

	function getGroupField($table,$value,$fieldname,$add_button=false,$add_pid=0) {
		$conf = array(
			'itemFormElName' => 'tx_mmforum_install[conf][0]['.$fieldname.']',
			'itemFormElValue' => $value?$table.'_'.$value:'',
			'fieldConf' => array(
				'config' => array(
					"type" => "group",
					"internal_type" => "db",
					"allowed" => $table,
					"prepend_tname" => FALSE,
					"size" => 1,
					"minitems" => 0,
					"maxitems" => 1,
					'wizards' => $add_button?array(
						'_PADDING' => 0,
						'_VERTICAL' => 1,
						'add' => array(
							'type' => 'script',
							'title' => 'Add new record',
							'icon' => 'add.gif',
							'params' => Array(
								'table'=>$table,
								'pid' => $add_pid,
								'setValue' => 'prepend'
							),
							'script' => 'wizard_add.php',
						)
					):false
				)
			)
		);
		return $this->p->tceforms->getSingleField_typeGroup($table,'tx_mmforum_install[conf][0]['.$fieldname.']',array(),$conf);
	}





		/**
		 * Installation
		 */





		/**
		 *
		 * Displays the initial installation form.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  string The installation form.
		 *
		 */

	function display_installation() {
		if(!$this->p->config['plugin.']['tx_mmforum.']['storagePID'])
			$content .= $this->display_install_storagePid();
		elseif(!$this->p->config['plugin.']['tx_mmforum.']['userPID'])
			$content .= $this->display_install_userPid();
		elseif(!$this->getUserGroupsConfigured())
			$content .= $this->display_install_userGroups();
		elseif(!$this->getIsConfigured())
			$content .= $this->display_allConfigForm();

		return $content;
	}



		/**
		 *
		 * Displays the user groups form.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  string The user groups form.
		 *
		 */

	function display_install_userGroups() {
		$input_userGroup = $this->getGroupField('fe_groups',$this->conf['userGroup'],'userGroup',true,$this->conf['userPID']);
		$input_adminGroup = $this->getGroupField('fe_groups',$this->conf['adminGroup'],'adminGroup',true,$this->conf['userPID']);

		$content .= '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td colspan="3" class="mm_forum-listrow_header" valign="top"><img src="img/install-general.png" style="vertical-align: middle; margin-right:8px;" />'.$this->getLL('start.title').'</td>
    </tr>
	<tr class="mm_forum-listrow">
		<td colspan="3" style="padding-top: 16px; padding-bottom:16px;">'.$this->getLL('start').'</td>
	</tr>
	<tr>
		<td valign="top"><span style="color: #1555a0;">&#8718;</span></td>
		<td valign="top">
			<div style="font-weight:bold;">'.$this->getLL('field.userGroup.title').'</div>
			<div>'.$this->getLL('field.userGroup.desc').'</div>
		</td>
		<td valign="top">'.$input_userGroup.'</td>
	</tr>
	<tr>
		<td valign="top"><span style="color: #1555a0;">&#8718;</span></td>
		<td valign="top">
			<div style="font-weight:bold;">'.$this->getLL('field.adminGroup.title').'</div>
			<div>'.$this->getLL('field.adminGroup.desc').'</div>
		</td>
		<td valign="top">'.$input_adminGroup.'</td>
	</tr>
</table>
<div class="mm_forum-buttons">
	<div class="mm_forum-button" onmouseover="this.className=\'mm_forum-button-hover\';" onmouseout="this.className=\'mm_forum-button\';" onclick="document.forms[\'editform\'].submit();">
		<img src="img/save.png">
		'.$this->getLL('save').'
	</div>
	<div style="clear:both;"></div>
</div>
';
		return $content;
	}



		/**
		 *
		 * Displays the storage PID form.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  string The storage PID form
		 *
		 */

	function display_install_storagePid() {
		$conf = array(
			'itemFormElName' => 'tx_mmforum_install[conf][0][storagePID]',
			'fieldConf' => array(
				'config' => array(
					"type" => "group",
					"internal_type" => "db",
					"allowed" => "pages",
					"size" => 1,
					"minitems" => 0,
					"maxitems" => 1,
				)
			)
		);
		$input = $this->p->tceforms->getSingleField_typeGroup('pages','tx_mmforum_install[conf][0][storagePID]',array(),$conf);
		$content .= '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td colspan="3" class="mm_forum-listrow_header" valign="top"><img src="img/install-general.png" style="vertical-align: middle; margin-right:8px;" />'.$this->getLL('start.title').'</td>
    </tr>
	<tr class="mm_forum-listrow">
		<td colspan="3" style="padding-top: 16px; padding-bottom:16px;">'.$this->getLL('start').'</td>
	</tr>
	<tr>
		<td valign="top"><span style="color: #1555a0;">&#8718;</span></td>
		<td>
			<div style="font-weight:bold;">'.$this->getLL('field.storagePID.title').'</div>
			<div>'.$this->getLL('field.storagePID.desc').'</div>
		</td>
		<td>'.$input.'</td>
	</tr>
</table>
<div class="mm_forum-buttons">
	<div class="mm_forum-button" onmouseover="this.className=\'mm_forum-button-hover\';" onmouseout="this.className=\'mm_forum-button\';" onclick="document.forms[\'editform\'].submit();">
		<img src="img/save.png">
		'.$this->getLL('save').'
	</div>
	<div style="clear:both;"></div>
</div>
';
		return $content;
	}



		/**
		 *
		 * Displays the user PID form.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  string The user PID form
		 *
		 */

	function display_install_userPid() {
		$conf = array(
			'itemFormElName' => 'tx_mmforum_install[conf][0][userPID]',
			'fieldConf' => array(
				'config' => array(
					"type" => "group",
					"internal_type" => "db",
					"allowed" => "pages",
					"size" => 1,
					"minitems" => 0,
					"maxitems" => 1,
				)
			)
		);
		$input = $this->p->tceforms->getSingleField_typeGroup('pages','tx_mmforum_install[conf][0][userPID]',array(),$conf);
		$content .= '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td colspan="3" class="mm_forum-listrow_header" valign="top"><img src="img/install-general.png" style="vertical-align: middle; margin-right:8px;" />'.$this->getLL('start.title').'</td>
    </tr>
	<tr class="mm_forum-listrow">
		<td colspan="3" style="padding-top: 16px; padding-bottom:16px;">'.$this->getLL('start').'</td>
	</tr>
	<tr>
		<td valign="top"><span style="color: #1555a0;">&#8718;</span></td>
		<td>
			<div style="font-weight:bold;">'.$this->getLL('field.userPID.title').'</div>
			<div>'.$this->getLL('field.userPID.desc').'</div>
		</td>
		<td>'.$input.'</td>
	</tr>
</table>
<div class="mm_forum-buttons">
	<div class="mm_forum-button" onmouseover="this.className=\'mm_forum-button-hover\';" onmouseout="this.className=\'mm_forum-button\';" onclick="document.forms[\'editform\'].submit();">
		<img src="img/save.png">
		'.$this->getLL('save').'
	</div>
	<div style="clear:both;"></div>
</div>
';
		return $content;
	}





		/**
		 * Saving
		 */





		/**
		 *
		 * Saves submitted configuration variables into the configuration file.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  void
		 *
		 */

	function save() {
        $conf = $this->instVars['conf'][0];
        $ctg = $this->instVars['ctg']?$this->instVars['ctg']:'general';
		if(count($conf)==0) return;

		foreach($conf as $var=>$value) {
			$config = $ctg=='required' ? $this->getFieldConfigByIdentifier($var) : $this->fieldConfig["$ctg."]['items.']["$var."];
			$type = $config['type'];

			if($type == 'group' || $type == 'select')
				$value = preg_replace('/^'.$config['type.']['table'].'_/','',preg_replace('/,$/','',$value));
            if($type == 'int') $value = intval($value);
            if($type == 'md5') {
                if(strlen(trim($value))==0) continue;
                else $value = md5($value);
            }

			if($value != $this->p->config['plugin.']['tx_mmforum.'][$var])
				$this->p->setConfVar($var,$value);
		}

        $TCE = t3lib_div::makeInstance('t3lib_tcemain');
        $TCE->admin = TRUE;
		$TCE->BE_USER = $GLOBALS['BE_USER'];
        $TCE->clear_cacheCmd('all');

		$this->conf = $this->p->config['plugin.']['tx_mmforum.'];
	}





		/**
		 * Status detection
		 */





		/**
		 *
		 * Determines if the user groups are properly configured.
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  boolean TRUE, if all user groups are properly configured, otherwise
		 *                  FALSE
		 *
		 */

	function getUserGroupsConfigured() {
		$c = $this->p->config['plugin.']['tx_mmforum.'];

		if(!$c['userGroup']) return false;
		if(!$c['adminGroup']) return false;

		return true;
	}



		/**
		 *
		 * Determines if the mm_forum extension is properly configured.
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2007-05-14
		 * @return  boolean TRUE, if the extension is properly configured, otherwise
		 *                  FALSE
		 *
		 */

	function getIsConfigured() {
		$c = $this->p->config['plugin.']['tx_mmforum.'];

		foreach($this->p->modTSconfig['properties']['essentialConfiguration.'] as $prop => $e)
			if(!$c[$prop]) return false;

		return true;
	}

	function getRemainingEssentialProperties() {
		$result = array();
		foreach($this->p->modTSconfig['properties']['essentialConfiguration.'] as $prop => $e)
			if(!$this->p->config['plugin.']['tx_mmforum.'][$prop]) array_push($result, $prop);
		return $result;
	}

	function getFieldConfigByIdentifier($fieldId) {

			// Most important rule againt clear code: Avoid ALL necessary
			// brackets!
		foreach($this->fieldConfig as $category => $ctgType)
			if($ctgType === 'MMFORUM_CONF_CATEGORY')
				foreach($this->fieldConfig["$category."]["items."] as $prop => $config)
					if($config == 'MMFORUM_CONF_ITEM' && $prop == $fieldId)
						return $this->fieldConfig["$category."]["items."]["$prop."];
	}

	function getRemainingPropertiesConfig() {
		$props = $this->getRemainingEssentialProperties();
		$propConfig = Array();
		foreach($this->fieldConfig as $category => $ctgType) {
			$ctgProps = array();
			if($ctgType === 'MMFORUM_CONF_CATEGORY') {
				$ctgConf = $this->fieldConfig["$category."];
				foreach($this->fieldConfig["$category."]["items."] as $prop => $config) {
					if($config == 'MMFORUM_CONF_ITEM' && in_array($prop, $props)) {
						$ctgProps["$prop"]  = 'MMFORUM_CONF_ITEM';
						$ctgProps["$prop."] = $this->fieldConfig["$category."]["items."]["$prop."];
					}
				}
				if(count($ctgProps)>0) {
					$propConfig["{$category}_div"]  = 'MMFORUM_CONF_ITEM';
					$propConfig["{$category}_div."] = array(
						'type' => 'div',
						'type.' => array('icon' => $ctgConf['icon']),
						'category' => $category,
						'label' => $ctgConf['title'] ? $ctgConf['title'] : $this->getLL('title.'.$category)
					);
					foreach($ctgProps as $k => $v) $propConfig[$k] = $v;
				}
			}
		}
		return $propConfig;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_install.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_install.php']);
}
?>