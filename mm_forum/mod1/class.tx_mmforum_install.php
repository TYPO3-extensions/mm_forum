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
	     * An associative array storing all field data and data types.
	     * Configurations variables are organized in sets. 
	     */
	var $fields = array(
		'general'		=> array(
			'storagePID'			=> 'group:pages',
			'userPID'				=> 'group:pages',
			'userGroup'				=> 'select:fe_groups:1',
			'adminGroup'			=> 'select:fe_groups:1',
			'informal'				=> 'checkbox',
			'realUrl_specialLinks'	=> 'checkbox',
			'disableRootline'		=> 'checkbox',
			'dateFormat'			=> 'string',
			'userNameField'			=> 'string',
		),
		'user'			=> array(
			'useCaptcha'			=> 'checkbox',
			'requiredFields'		=> 'special:userRequired'
		),
		'forum'			=> array(
			'boardPID'				=> 'group:pages',
			'moderatedBoard'		=> 'checkbox',
			'threadsPerPage'		=> 'int/unit{topics}',
			'postsPerPage'			=> 'int/unit{posts}',
			'displayRealName'		=> 'checkbox',
			'topic_hotPosts'		=> 'int/unit{posts}',
			'spamblock_interval'	=> 'int/unit{seconds}',
			'signatureLimit'		=> 'int/unit{lines}',
            'signatureBBCodes'      => 'checkbox',
            'enableRanks'           => 'checkbox',
            'enableShadows'         => 'checkbox',
			'prefixes'				=> 'string',
            'polls_enable'          => 'checkbox',
            'polls_restrict'        => 'select:fe_groups',
            'rssPID'                => 'group:pages',
            'topicIconMode'         => 'lselect:classic|modern',
            'attachments'           => 'div',
            'attachment_enable'     => 'checkbox',
            'attachment_allow'      => 'string',
            'attachment_deny'       => 'string',
            'attachment_filesize'   => 'int/unit{bytes}',
            'attachment_preview'    => 'checkbox',
            'attachment_count'		=> 'int'
		),
		'pm'			=> array(
			'pmPID'					=> 'group:pages',
			'pmBlocktime'			=> 'int/unit{seconds}',
		),
		'search'		=> array(
			'sword_minLength'		=> 'int/unit{chars}',
			'resultsPerPage'		=> 'int/unit{results}',
			'indexCount'			=> 'int/unit{topics}',
			'indexingPassword'		=> 'md5'
		),
		'filepaths'		=> array(
			'path_img'				=> 'string',
			'path_smilie'			=> 'string',
			'path_template'			=> 'string',
			'path_altTemplate'		=> 'string'
		),
		'contact'		=> array(
			'boardName'				=> 'string',
            'site_name'             => 'string',
			'support_mail'			=> 'string',
			'mailer_mail'			=> 'string',
			'notifyMail_sender'		=> 'string',
			'team_name'				=> 'string',
		),
		'cron'			=> array(
			'cron_verbose'				=> 'lselect:all|errors|quiet',
			'cron_htmlemail'			=> 'checkbox',
			'cron_notifyPublish_group'	=> 'select:fe_groups:1',
			'cron_lang'					=> 'string',
			'cron_sitetitle'			=> 'string',
			'cron_postqueue_link'		=> 'string',
			'cron_notifyPublishSender'	=> 'string',
			'cron_pathTmpl'				=> 'string',
		),
	);
	
    /**
     * General module functions
     */
    
    /**
     * The main function
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  string The module content
     */
	function main() {
		$this->init();
		
		$this->save();
		
		if($this->getIsConfigured()) 	$content = $this->display_allConfigForm();
		else							$content = $this->display_installation();
		
		return $content;
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
		return $GLOBALS['LANG']->getLL('install.'.$key);
	}
	
    /**
     * Initializes the installation module.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  void
     */
	function init() {
		$this->instVars = t3lib_div::_GP('tx_mmforum_install');
		$this->conf     = $this->p->config['plugin.']['tx_mmforum.'];
		
		$GLOBALS['LANG']->includeLLFile('EXT:mm_forum/mod1/locallang_install.xml');
	}
	
    /**
     * Loads the default configuration from the ext_typoscript_constants.txt file.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  array A configuration array
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
     * Displays links to all field categories.
     * The mm_forum configuration variables are grouped into sets. This function
     * generates links pointing to forms representing each of these sets.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  string  A set of links leading to the regarding field categories
     */
	function display_categoryLinks() {
		foreach($this->fields as $category => $data) {
			$label = $this->getLL('cat.'.$category);
			if($this->instVars['ctg'] == $category) {
				$set = $this->p->MOD_SETTINGS['function'];
				$items[] = '<div class="mm_forum-button-hover"><img src="img/install-'.$category.'.png" />'.$label.'</div>';
			} else {
                $set = $this->p->MOD_SETTINGS['function'];
				$items[] = '<div class="mm_forum-button" onmouseover="this.className=\'mm_forum-button-hover\';" onmouseout="this.className=\'mm_forum-button\';" onclick="document.location.href=\'index.php?SET[function]='.$set.'&tx_mmforum_install[ctg]='.$category.'\';"><img src="img/install-'.$category.'.png" />'.$label.'</div>';
            }
		}
		
		return '<div class="mm_forum-buttons">'.implode('',$items).'<div style="clear:both;"></div></div>';
	}

    /**
     * Displays the mm_forum help text. This text is displayed of no category
     * is selected.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  string  The mm_forum help text
     */
    function display_helpForm() {
        $template = file_get_contents(t3lib_div::getFileAbsFileName('EXT:mm_forum/res/tmpl/mod1/install.html'));
		$template = t3lib_parsehtml::getSubpart($template, '###INSTALL_HELP###');
		
		$marker = array(
			'###INST_HELP_TITLE###'		=> $this->getLL('help.title'),
			'###INST_HELP_TEXT###'		=> $this->getLL('help.content')
		);
        
        return t3lib_parsehtml::substituteMarkerArray($template, $marker);
    }
    
    /**
     * Main configuration form
     */
    
    /**
     * Displays the configuration form.
     * This function dynamically generates a configuration form allowing
     * the user to edit the fields of the selected configuration category.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  string  The configuration form.
     */
	function display_allConfigForm() {
		
		$defaultVars = $this->loadDefaultConfiguration();
		$content = $this->display_categoryLinks();

		if(!$this->instVars['ctg']) return $content.$this->display_helpForm();
		
		$fieldData = $this->fields[$this->instVars['ctg']];
		if(count($fieldData)==0) return $content;
	
		$content .= '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td class="mm_forum-listrow_header" valign="top" style="width:1px;"><img src="img/install-'.$this->instVars['ctg'].'.png" style="vertical-align: middle; margin-right:8px;" /></td>
        <td class="mm_forum-listrow_header" colspan="2" valign="top">'.$this->getLL('title.'.$this->instVars['ctg']).'</td>
    </tr>';

		foreach($fieldData as $field => $config) {
		
            $bigField = false;
            if($config == 'div') {

				$content .= '</table>
<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td class="mm_forum-listrow_header" valign="top" style="width:1px;"><img src="img/install-'.$field.'.png" style="vertical-align: middle; margin-right:8px;" /></td>
        <td class="mm_forum-listrow_header" colspan="2" valign="top">'.$this->getLL('field.'.$field.'.title').'</td>
    </tr>';

                continue;
            }
            
			if(preg_match('/\/unit\{(.*?)\}$/i',$config,$matches)) {
				$config = preg_replace('/\/unit\{(.*?)\}$/i','',$config);
				$unit = ' '.$this->getLL('unit.'.$matches[1]);
			} else $unit = '';
				
				if($config == 'string')				$input = $this->getTextField($field,$this->conf[$field]);
			elseif($config == 'int')				$input = $this->getTextField($field,$this->conf[$field],12);
            elseif($config == 'md5')                $input = $this->getMD5Field($field,$this->conf[$field]);
			elseif($config == 'checkbox')			$input = $this->getCheckField($field,$this->conf[$field]=='1');
			elseif(substr($config,0,6)=='group:') {
				$data = explode(':',$config);
				$input = $this->getGroupField($data[1],$this->conf[$field],$field);
			} elseif(substr($config,0,7)=='select:') {
				$data = explode(':',$config);
					if($data[1] == 'fe_groups') $pid = $this->conf['userPID'];
				elseif($data[1] == 'fe_users')  $pid = $this->conf['userPID'];
				else							$pid = $this->conf['storagePID'];
                
                $limit = $data[2]?$data[2]:100;
                $bigField = ($limit>1);
				$input = $this->getSelectField($field,$this->conf[$field],$data[1],$pid,$limit);
			} elseif(substr($config,0,8)=='lselect:') {
                $data = explode(':',$config);
                $data = explode('|', $data[1]);
                
                $input = $this->getLSelectField($field,$this->conf[$field],$data);
            } elseif(substr($config,0,8)=='special:') {
                $data = explode(':',$config);
				
				switch($data[1]) {
					case 'userRequired': $input = $this->getUserRequiredField($this->conf[$field],$field); $bigField=true; break;
				}
            }
			
			$input .= $unit;
			
			if(isset($defaultVars[$field]) && strlen($defaultVars[$field])>0) {
				$defValue = $defaultVars[$field];
				
				if($config == 'checkbox') $defValue=($defValue=='1')?$this->getLL('yes'):$this->getLL('no');
				elseif(preg_match('/^lselect:/',$config)) $defValue=$this->getLL('field.'.$field.'.options.'.$defValue);
				
				$default = '<br />'.$this->getLL('default').': '.htmlentities($defValue).$unit;
			} else $default = '';
			
            if(!$bigField)
			    $content .= '<tr class="mm_forum-listrow">
	    <td valign="top"><span style="color: #1555a0;">&#8718;</span></td>
	    <td valign="top" style="width:50%;">
		    <strong>'.$this->getLL('field.'.$field.'.title').'</strong><br />
		    '.$this->getLL('field.'.$field.'.desc').'
	    </td>
	    <td valign="top" style="width:50%;">
		    '.$input.$default.'
	    </td>
    </tr>';
            else
                $content .= '<tr class="mm_forum-listrow">
        <td valign="top"><span style="color: #1555a0;">&#8718;</span></td>
        <td valign="top">
		    <strong>'.$this->getLL('field.'.$field.'.title').'</strong><br />
		    '.$this->getLL('field.'.$field.'.desc').'
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

<input type="hidden" name="tx_mmforum_install[ctg]" value="'.$this->instVars['ctg'].'" />
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
     * Generates a text field.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @param   string $fieldname The text field name
     * @param   string $value     The text field value
     * @param   int    $size      The text field size
     * @return  string            The text field
     */
	function getTextField($fieldname,$value,$size=null) {
		return '<input type="text" name="tx_mmforum_install[conf][0]['.$fieldname.']" value="'.htmlspecialchars($value).'" '.($size?'size="'.$size.'"':'style="width:100%"').' />';
	}
    
    /**
     * Generates an encrypted password field.
     * Expects a MD5 hash.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @param   string $fieldname The password field name
     * @param   string $value     The current value.
     * @return  string            The password field
     */
    function getMD5Field($fieldname,$value) {
        return $this->getLL('currentValue').': '.$value.'<br /><input size="64" type="password" name="tx_mmforum_install[conf][0]['.$fieldname.']" />';
    }
	
    /**
     * Generates a checkbox.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @param   string  $fieldname The checkbox name
     * @param   boolean $checked   TRUE, if the checkbox is to be checked by default,
     *                             otherwise FALSE.
     * @return  string             The checkbox
     */
	function getCheckField($fieldname,$checked) {
		return '<input type="hidden" name="tx_mmforum_install[conf][0]['.$fieldname.']" value="0" /><input type="checkbox" value="1" name="tx_mmforum_install[conf][0]['.$fieldname.']" '.($checked?'checked="checked"':'').' />';
	}
	
    /**
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
    
    function getLSelectField($fieldname, $value, $options) {
        $optionStr = '';
        foreach($options as $option) {
            $checked = ($option == $value)?'selected="selected"':'';
            $optionStr .= '<option value="'.$option.'" '.$checked.'>'.$this->getLL('field.'.$fieldname.'.options.'.$option).'</option>';
        }
        
        return '<select name="tx_mmforum_install[conf][0]['.$fieldname.']">'.$optionStr.'</select>';
    }
	
    /**
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
     * Displays the initial installation form.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  string The installation form.
     */
	function display_installation() {
		if(!$this->p->config['plugin.']['tx_mmforum.']['storagePID'])
			$content .= $this->display_install_storagePid();
		elseif(!$this->p->config['plugin.']['tx_mmforum.']['userPID'])
			$content .= $this->display_install_userPid();
		elseif(!$this->getUserGroupsConfigured())
			$content .= $this->display_install_userGroups();
		
		return $content;
	}
	
    /**
     * Displays the user groups form.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  string The user groups form.
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
     * Displays the storage PID form.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  string The storage PID form
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
     * Displays the user PID form.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  string The user PID form
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
     * Saves submitted configuration variables into the configuration file.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  void
     */
	function save() {
        $conf = $this->instVars['conf'][0];
        $ctg = $this->instVars['ctg']?$this->instVars['ctg']:'general';
		if(count($conf)==0) return;
        
		foreach($conf as $var=>$value) {
			switch($var) {
				case 'storagePID'		: $value = str_replace('pages_','',$value); $value = str_replace(',','',$value); break;
				case 'userPID'			: $value = str_replace('pages_','',$value); $value = str_replace(',','',$value); break;
				case 'boardPID'			: $value = str_replace('pages_','',$value); $value = str_replace(',','',$value); break;
				case 'pmPID'			: $value = str_replace('pages_','',$value); $value = str_replace(',','',$value); break;
				case 'rssPID'			: $value = str_replace('pages_','',$value); $value = str_replace(',','',$value); break;
				case 'userGroup'		: $value = str_replace('fe_groups_','',$value); $value = str_replace(',','',$value); break;
				case 'adminGroup'		: $value = str_replace('fe_groups_','',$value); $value = str_replace(',','',$value); break;
			}
            
            $type = $this->fields[$ctg][$var]; if(!$type) continue;
            
            if(preg_match('/^int/',$type)) $value = intval($value);
            if(preg_match('/^md5/',$type)) {
                if(strlen(trim($value))==0) continue;
                else $value = md5($value);
            }
			
			if($value != $this->p->config['plugin.']['tx_mmforum.'][$var])
				$this->p->setConfVar($var,$value);
		}
        
        $TCE = t3lib_div::makeInstance('t3lib_tcemain');
        $TCE->admin = TRUE;
        $TCE->clear_cacheCmd('all');
		
		$this->conf = $this->p->config['plugin.']['tx_mmforum.'];
	}
	
    /**
     * Status detection
     */
    
    /**
     * Determines if the user groups are properly configured.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  boolean TRUE, if all user groups are properly configured, otherwise
     *                  FALSE
     */
	function getUserGroupsConfigured() {
		$c = $this->p->config['plugin.']['tx_mmforum.'];
		
		if(!$c['userGroup']) return false;
		if(!$c['adminGroup']) return false;
		
		return true;
	}
	
    /**
     * Determines if the mm_forum extension is properly configured.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-14
     * @return  boolean TRUE, if the extension is properly configured, otherwise
     *                  FALSE
     */
	function getIsConfigured() {
		$c = $this->p->config['plugin.']['tx_mmforum.'];
		
		foreach($this->required as $required)
			if(!$c[$required]) return false;
			
		return true;
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_install.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_install.php']);
}
?>