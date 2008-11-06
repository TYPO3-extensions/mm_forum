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
 *   56: class tx_mmforum_userFields
 *   66:     function main($content)
 *   87:     function getLL($key)
 *   99:     function generateLink($param)
 *  111:     function init()
 *  123:     function getMaxSorting()
 *  140:     function saveData()
 *  237:     function displayExtForm()
 *  342:     function generateImageOptions($value,$path=false,$origPath='')
 *  372:     function displayFieldTable()
 *
 * TOTAL FUNCTIONS: 9
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
 
/**
 * This class handles the extension of the mm_forum user profile with
 * custom fields. These fields and their contents are stored in two
 * seperate database tables.
 * 
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    2007-05-16
 * @copyright  2007 Mittwald CM Service
 * @package    mm_forum
 * @subpackage Backend
 */
class tx_mmforum_userFields {

    /**
     * The main function.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-16
     * @return  string The module content
     */
    function main($content) {
        $this->init();
        
        if($this->ufVars['ext'])
            $content .= $this->displayExtForm();
        else
            $content .= $this->displayFieldTable();
        
        return $content;
    }
    
    /**
     * Gets a language variable from the locallang_userfields.xml file.
     * Wrapper function to simplify retrieval of language dependent
     * strings.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @param   string $key The language string key
     * @return  string      The language string
     */
    function getLL($key) {
        return $GLOBALS['LANG']->getLL('userFields.'.$key);
    }
    
    /**
     * Generates a link. Function was written to simplify link generation.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @param   string $param The link parameters
     * @return  string        The link
     */
    function generateLink($param) {
        $set = $this->p->MOD_SETTINGS['function'];
		return '<a href="index.php?SET[function]='.$set.$param.'">';
    }
    
    /**
     * Initializes the module
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @return  void
     */
    function init() {
		$this->ufVars = t3lib_div::_GP('tx_mmforum_userfields');
        $GLOBALS['LANG']->includeLLFile('EXT:mm_forum/mod1/locallang_userfields.xml');
    }
    
    /**
     * Retrieves a new sorting index.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @return int A new sorting index.
     */
    function getMaxSorting() {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'MAX(sorting)',
            'tx_mmforum_userfields',
            '1'
        );
        list($max) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        return $max+1;
    }
    
    /**
     * Saves data submitted from the user field listing view.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @return void
     */
    function saveData() {
        if($this->ufVars['moveUp']) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid,sorting',
                'tx_mmforum_userfields',
                'uid='.intval($this->ufVars['moveUp']).' AND deleted=0'
            );
            list($uid_1,$sorting_1) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid,sorting',
                'tx_mmforum_userfields',
                'sorting > '.$sorting_1.' AND deleted=0',
                '',
                'sorting ASC'
            );
            list($uid_2,$sorting_2) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            
            $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_userfields SET sorting='.$sorting_1.' WHERE uid='.$uid_2);
            $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_userfields SET sorting='.$sorting_2.' WHERE uid='.$uid_1);
        }
        if($this->ufVars['moveDown']) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid,sorting',
                'tx_mmforum_userfields',
                'uid='.intval($this->ufVars['moveDown']).' AND deleted=0'
            );
            list($uid_1,$sorting_1) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid,sorting',
                'tx_mmforum_userfields',
                'sorting < '.$sorting_1.' AND deleted=0',
                '',
                'sorting DESC'
            );
            list($uid_2,$sorting_2) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
            
            $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_userfields SET sorting='.$sorting_1.' WHERE uid='.$uid_2);
            $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_userfields SET sorting='.$sorting_2.' WHERE uid='.$uid_1);
        }
        
        if(!is_array($this->ufVars['field'])) return '';
        
        foreach($this->ufVars['field'] as $uid => $data) {
            
            if($uid == 'new' && strlen($data['save']) > 0) {
                $data['label'] = trim($data['label']);
                
                if(strlen($data['label'])==0) {
                    $content .= '<fieldset><legend>'.$this->getLL('error').'</legend>'.$this->getLL('error.noLabel').'</fieldset><br /><br />';
                }
                else {
                    $insertArr = array(
                        'pid'           => $this->p->config['plugin.']['tx_mmforum.']['storagePID'],
                        'tstamp'        => time(),
                        'crdate'        => time(),
                        'cruser_id'     => $GLOBALS['BE_USER']->user['uid'],
                        'sorting'       => $this->getMaxSorting(),
                        'deleted'       => 0,
                        'hidden'        => $data['hidden'],
                        'label'         => $data['label'],
                        'public'        => $data['public']
                    );
                    $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_userfields',$insertArr);
                    unset($this->ufVars['field']['new']);
                }
            }
            elseif(intval($uid)>0) {
                if(strlen($data['save']) > 0) {
                    $updateArr = array(
                        'tstamp'        => time(),
                        'hidden'        => $data['hidden'],
                        'label'         => $data['label'],
                        'public'        => $data['public']
                    );
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_userfields','uid='.$uid,$updateArr);
                }
                elseif(strlen($data['delete']) > 0) {
                    $updateArr = array(
                        'tstamp'        => time(),
                        'deleted'       => 1
                    );
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_userfields','uid='.$uid,$updateArr);
                }
            }
        }
        return $content;
    }
    
    /**
     * Displays an extended user field editing form.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-16
     * @return  string The content
     */
    function displayExtForm() {
        
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_userfields',
            'uid='.intval($this->ufVars['ext'])
        );
        $field = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        
        $parser  = t3lib_div::makeInstance('t3lib_TSparser');
        $parser->parse($field['config']);
        $config = $parser->setup;
        
        if($this->ufVars['extfield']['save'] == $this->getLL('save')) {
            if($this->ufVars['extfield']['label']=='1') {
                $config['label'] = 'TEXT';
                $config['label.']['value'] = $this->ufVars['extfield']['label_text'];
                
                $updateArr['config'] = $this->p->parseConf($config);
            }
            if($this->ufVars['extfield']['image']=='1') {
                $config['label'] = 'IMAGE';
                $config['label.'] = array(
                    'file'      => $this->ufVars['extfield']['imagesrc']
                );
                
                $updateArr['config'] = $this->p->parseConf($config);
            }
            if($this->ufVars['extfield']['useExisting']=='1') {
            	$config['datasource'] = $this->ufVars['extfield']['useExisting_field'];
                $updateArr['config'] = $this->p->parseConf($config);
            }
            if($this->ufVars['extfield']['config']) {
                $updateArr['config'] = stripslashes($_POST['data']['tx_mmforum_userfields'][$field['uid']]['config']);
                $parser->parse($updateArr['config']);
                $config = $parser->setup;
            }
            $updateArr['tstamp'] = time();
            $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_userfields','uid='.$field['uid'],$updateArr);
            
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'tx_mmforum_userfields',
                'uid='.intval($this->ufVars['ext'])
            );
            $field = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
            
            $parser  = t3lib_div::makeInstance('t3lib_TSparser');
            $parser->parse($field['config']);
            $config = $parser->setup;
        }
        elseif($this->ufVars['back'] == $this->getLL('back')) {
            return $this->displayFieldTable();
        }
        
        $path = $this->p->config['plugin.']['tx_mmforum.']['path_img'];
        if(substr($path,0,13)=='EXT:mm_forum/') $path = str_replace('EXT:mm_forum/',t3lib_extMgm::siteRelPath('mm_forum'),$path);
        $imageoptions = $this->generateImageOptions('',$path);
        
        $content .= '<table class="mm_forum-list" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td class="mm_forum-listrow_header" colspan="2">'.$this->getLL('ext.title').'</td>
    </tr>
    <tr class="mm_forum-listrow">
        <td style="width:25%;">'.$this->getLL('ext.regLabel').'</td>
        <td>
            <input type="checkbox" name="tx_mmforum_userfields[extfield][label]" value="1" />
            <input type="text" name="tx_mmforum_userfields[extfield][label_text]" />
        </td>
    </tr>
    <tr>
        <td style="width:25%;">'.$this->getLL('ext.image').'</td>
        <td>
            <input type="checkbox" name="tx_mmforum_userfields[extfield][image]" value="1" />
            <img src="../../../../typo3/clear.gif" id="previewImage" />
            <select name="tx_mmforum_userfields[extfield][imagesrc]" onchange="document.getElementById(\'previewImage\').src=\'../../../../\'+this[this.selectedIndex].value;">
                '.$imageoptions.'
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:25%;">'.$this->getLL('ext.useExisting').'</td>
        <td>
            <input type="checkbox" name="tx_mmforum_userfields[extfield][useExisting]" value="1" />
            <input type="text" name="tx_mmforum_userfields[extfield][useExisting_field]" />
        </td>
    </tr>
    <tr>
        <td>'.$this->getLL('ext.editConfig').'</td>
        <td valign="top">
            <br />
            <div id="config_span"><input type="checkbox" name="tx_mmforum_userfields[extfield][config]" value="1" /> '.$this->p->tceforms->getSoloField('tx_mmforum_userfields',$field,'config').'</div>'.$this->getLL('config.help').'
        </td>
    </tr>
</table>
<input type="hidden" name="tx_mmforum_userfields[ext]" value="'.$field['uid'].'" />
<input type="submit" name="tx_mmforum_userfields[extfield][save]" value="'.$this->getLL('save').'" />
<input type="submit" name="tx_mmforum_userfields[back]" value="'.$this->getLL('back').'" onclick="location.href=\'index.php?SET[function]=6\';" />
';

        return $content;
        
    }
    
    /**
     * Recursive function that generates an option menu of images files.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-16
     * @param   string $value    The default value
     * @param   string $path     The file path
     * @param   string $origPath The original file path. This parameter is needed
     *                           due to the function's recursive character.
     * @return  string           A list of <option>-objects.
     */
    function generateImageOptions($value,$path=false,$origPath='') {
        $path = $path?$path:$this->p->config['plugin.']['tx_mmforum.']['path_img'];
        if(substr($path,-1,1)=='/') $path = substr($path,0,strlen($path)-1);
        $origPath = $origPath?$origPath:$path;
        
        $dirs = t3lib_div::get_dirs('../../../../'.$path);
        if(count($dirs)>0) {
            foreach($dirs as $dir) {
                $options .= $this->generateImageOptions($value,$path.'/'.$dir,$origPath?$origPath:$path);
            }
        }
        
        $files=t3lib_div::getFilesInDir('../../../../'.$path,'gif,jpg,png');
		if(count($files)>0) {
			foreach($files as $k=>$f) {
				$name = ($noDel === FALSE)?  str_replace('.'.$fileExt,'',$f): $f;
                $dispPath = str_replace($origPath,'',$path);
				$options .= '<option value="'.$path.'/'.$name.'"'.($value==$path.'/'.$name?'selected="selected"':'').' onchange="'.$onSelect.'">'.$dispPath.'/'.$name.'</option>'; 
			}
		}
		return $options;
    }
    
    /**
     * Displays a list of all user defined fields.
     * 
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @return  string A list of all user defined fields.
     */
    function displayFieldTable() {
        
        $content .= $this->saveData();
        
        $arr = $this->ufVars['field']['new'];
        if(strlen($arr['public'])==0) $arr['public'] = 1;
        $content .= '<table cellpadding="2" cellspacing="0" class="mm_forum-list" width="100%">
    <tr>
        <td class="mm_forum-listrow_header">'.$this->getLL('field.enable').'</td>
        <td class="mm_forum-listrow_header">'.$this->getLL('field.public').'</td>
        <td class="mm_forum-listrow_header">'.$this->getLL('field.name').'</td>
        <td class="mm_forum-listrow_header">'.$this->getLL('field.options').'</td>
    </tr>
    <tr class="mm_forum-listrow2">
        <td style="width:1%">
            <input type="hidden" name="tx_mmforum_userfields[field][new][hidden]" value="1" />
            <input type="checkbox" name="tx_mmforum_userfields[field][new][hidden]" '.($arr['hidden']?'':'checked="checked"').' value="0" />
        </td>
        <td style="width:1%">
            <input type="hidden" name="tx_mmforum_userfields[field][new][public]" value="0" />
            <input type="checkbox" name="tx_mmforum_userfields[field][new][public]" '.($arr['public']?'checked="checked"':'').' value="1" />
        </td>
        <td>
            <input type="text" style="width:100%;" name="tx_mmforum_userfields[field][new][label]" value="'.htmlentities($arr['label']).'" />
        </td>
        <td style="width:1%">
            <input style="border:0px;" type="image" src="../../../../typo3/sysext/t3skin/icons/gfx/savedok.gif" name="tx_mmforum_userfields[field][new][save]" value="1" />&nbsp;<span style="color:red; font-weight:bold; vertical-align: top;">'.$this->getLL('new').'</span>
        </td>
    </tr>';
        
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_userfields',
            'deleted=0',
            '',
            'sorting DESC'
        );
        $i = 0;
        $max = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
        while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $upButton   = ($i>0)?$this->generateLink('&tx_mmforum_userfields[moveUp]='.$arr['uid']).'<img src="../../../../typo3/sysext/t3skin/icons/gfx/button_up.gif" /></a>':'<img src="../../../../typo3/clear.gif" width="16" height="16" />';
            $downButton = ($i<$max-1)?$this->generateLink('&tx_mmforum_userfields[moveDown]='.$arr['uid']).'<img src="../../../../typo3/sysext/t3skin/icons/gfx/button_down.gif" /></a>':'<img src="../../../../typo3/clear.gif" width="16" height="16" />';
            
            $extButton  = $this->generateLink('&tx_mmforum_userfields[ext]='.$arr['uid']).'<img src="../../../../typo3/sysext/t3skin/icons/gfx/options.gif" /></a>';
            
            if(strlen($arr['config'])>0) {
                $input = '<em>'.htmlentities($arr['label']).' ['.$this->getLL('field.extended').']</em><input type="hidden" name="tx_mmforum_userfields[field]['.$arr['uid'].'][label]" value="'.$arr['label'].'" />';
            } else $input = '<input type="text" style="width:100%;" name="tx_mmforum_userfields[field]['.$arr['uid'].'][label]" value="'.htmlentities($arr['label']).'" />';
            
            $content .= '<tr class="mm_forum-listrow'.($i++ % 2==0 ? '' : '2').'">
    <td>
        <input type="hidden" name="tx_mmforum_userfields[field]['.$arr['uid'].'][hidden]" value="1" />
        <input type="checkbox" name="tx_mmforum_userfields[field]['.$arr['uid'].'][hidden]" '.($arr['hidden']?'':'checked="checked"').' value="0" />
    </td>
    <td style="width:1%">
        <input type="hidden" name="tx_mmforum_userfields[field]['.$arr['uid'].'][public]" value="0" />
        <input type="checkbox" name="tx_mmforum_userfields[field]['.$arr['uid'].'][public]" '.($arr['public']?'checked="checked"':'').' value="1" />
    </td>
    <td>
        '.$input.'
    </td>
    <td style="white-space:nowrap;">
        <input style="border:0px;" type="image" src="../../../../typo3/sysext/t3skin/icons/gfx/savedok.gif" name="tx_mmforum_userfields[field]['.$arr['uid'].'][save]" value="1" />'.
        '<input style="border:0px;" type="image" src="../../../../typo3/sysext/t3skin/icons/gfx/garbage.gif" name="tx_mmforum_userfields[field]['.$arr['uid'].'][delete]" value="1" />'.
        $upButton.$downButton.$extButton.'
    </td>
</tr>';
        }
        
        $content .= '</table>';
        
        return $content;
    }
    
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_userfields.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_userfields.php']);
}
?>