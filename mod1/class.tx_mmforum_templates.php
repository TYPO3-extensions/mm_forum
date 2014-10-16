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
 *   59: class tx_mmforum_templates
 *   67:     function main($content)
 *   86:     function updateVars()
 *  119:     function getTemplates($path=FALSE)
 *  153:     function createTheme($themename)
 *  169:     function displayCreateTheme()
 *  198:     function displayTemplateSelector()
 *  278:     function displayTemplateEditor()
 *  291:     function setSelectionRange(input, selectionStart, selectionEnd)
 *  311:     function replaceSelection (input, replaceString)
 *  340:     function catchTab(item,e)
 *  394:     function copy_recursive($srcdir,$dstdir)
 *  426:     function generateAltTemplatePath()
 *
 * TOTAL FUNCTIONS: 12
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * This class handles the dynamic editing and management of themes
 * and templates in the backend module. The user can create new themes
 * (i.e. sets of templates), by copying the default template into a
 * new directory and edit these templates.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    2007-05-14
 * @package    mm_forum
 * @subpackage Backend
 */
class tx_mmforum_templates {

		/**
		 * Theme names that cannot be selected using the theme selector.
		 * @var array
		 */
	var $metaThemes = array('mod1','cron','.svn');

    /**
     * The main function.
     * @param  string $content The content
     * @return string          The module content
     */
    function main($content) {

        $this->tmpVars = t3lib_div::_GP('tx_mmforum_template');

        $this->updateVars();

        if($this->tmpVars['newtheme']) $content .= $this->displayCreateTheme();
        else $content .= $this->displayTemplateSelector();

        if($this->tmpVars['template'] && $this->tmpVars['theme']) $content .= $this->displayTemplateEditor();

        return $content;
    }

    /**
     * Processes the configuration variables that are necessary for execution of this module.
     */
    function updateVars() {
        $this->conf = $this->p->config['plugin.']['tx_mmforum.'];

        if(strlen($this->conf['path_template'])==0) $this->conf['path_template'] = 'EXT:mm_forum/res/tmpl/';
        $path = str_replace('EXT:mm_forum/',t3lib_extMgm::extPath('mm_forum'),$this->conf['path_template']);
        if(substr($path,-1,1)!='/') $path = $path.'/';

        $this->templatePath = $path;

        if(strlen($this->conf['path_altTemplate'])==0) $this->conf['path_altTemplate'] = 'fileadmin/ext/mm_forum/tmpl/';
        if(preg_match('/^EXT:/',$this->conf['path_altTemplate']))
            $altPath = str_replace('EXT:mm_forum/',t3lib_extMgm::siteRelPath('mm_forum'),$this->conf['path_altTemplate']);
        else $altPath = PATH_site.$this->conf['path_altTemplate'];
        if(substr($altPath,-1,1)!='/') $altPath = $altPath.'/';

        $this->altTemplatePath = $altPath;
        if(!is_dir($this->altTemplatePath)) $this->generateAltTemplatePath();
    }

    /**
     * Loads all themes and templates from the file system.
     * This function recursively loads all themes and templates from the file system
     * and stores them into an associative array.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-07
     * @param   string $path The path from which the files are to be loaded. This parameter
     *                       can be left empty in order to load ALL themes and templates. This
     *                       parameter is needed only because of the recursive character of
     *                       this function.
     * @return  array        An array containing information on all themes and templates.
     */
    function getTemplates($path=FALSE) {
        if($path === FALSE) $path = $this->templatePath;
        if(!is_dir($path)) return array();

        if(substr($path,-1,1)=='/') $path = substr($path,0,strlen($path)-1);

        $dirs = t3lib_div::get_dirs($path);

        if(count($dirs)>0) {
            foreach($dirs as $dir) {
				if(in_array($dir,$this->metaThemes)) continue;
                $result[$dir] = $this->getTemplates($path.'/'.$dir);
            }
        }

        $files = t3lib_div::getFilesInDir($path,'',0,$order='1');

        if(count($files)==0) return $result;
        foreach($files as $file) {
			if($file{0}==='.') continue; # Don't display hidden files
            $result[$file] = $file;
        }

        return $result;
    }

    /**
     * Creates a new theme.
     * This function creates a new theme by copying all files and
     * directories from the default theme into a new directory.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-07
     * @param   string  $themename The new theme's name.
     * @return  boolean            TRUE, if the theme creation was successfull, otherwise false.
     */
    function createTheme($themename) {
        if(file_exists($this->altTemplatePath.'/'.$themename)) return false;

        $this->copy_recursive($this->templatePath.'default',$this->altTemplatePath.''.$themename);
        $this->tmpVars['theme'] = $themename;
        unset($this->tmpVars['template']);
        return true;
    }

    /**
     * Displays the form for creating a new theme.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-07
     * @return  string The content
     */
    function displayCreateTheme() {
        if(is_array($this->tmpVars['newtheme'])) {
            $newtheme = $this->createTheme($this->tmpVars['newtheme']['name']);
            if(!$newtheme) $newtheme_error = '<div class="mm_forum-fatalerror">'.$GLOBALS['LANG']->getLL('tmpl_createTheme_error').'</div>';
            else return $this->displayTemplateSelector();
        }

        $content = '
<fieldset>
    <legend>'.$GLOBALS['LANG']->getLL('tmpl_createTheme').'</legend>
    '.$GLOBALS['LANG']->getLL('tmpl_createTheme_desc').'<br /><br />
    '.$GLOBALS['LANG']->getLL('tmpl_createTheme_name').':
    <input type="text" name="tx_mmforum_template[newtheme][name]" /> <input type="submit" value="'.$GLOBALS['LANG']->getLL('tmpl_createTheme_submit').'" />
    '.$newtheme_error.'
</fieldset>
';
        return $content;
    }

    /**
     * Generates a form for selecting the template that is to be edited.
     * This function generates a form that allows the user to select the theme
     * and template he/she wants to edit. Furthermore, the user has the
     * possibility to set one theme as active.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-07
     * @return  string The form content.
     */
    function displayTemplateSelector() {

        $themeOptions = '<option value=""></option>';

        if($this->tmpVars['activate']) {
            $this->p->setConfVar('style',$this->tmpVars['theme']);

            if($this->tmpVars['theme'] == 'default')
                $this->p->setConfVar('style_path',$this->conf['path_template'].$this->tmpVars['theme']);
            else {
                $path = PATH_site.$this->conf['path_altTemplate'].$this->tmpVars['theme'];
                if(is_dir($path))
                    $this->p->setConfVar('style_path',$this->conf['path_altTemplate'].$this->tmpVars['theme']);
                else
                    $this->p->setConfVar('style_path',$this->conf['path_template'].$this->tmpVars['theme']);
            }
        }

        $vars = array_merge((array)$this->getTemplates(),(array)$this->getTemplates($this->altTemplatePath));

        foreach($vars as $theme=>$themeData) {
            $sel = ($this->tmpVars['theme'] == $theme)?'selected="selected"':'';
            $themeOptions .= '<option value="'.$theme.'" '.$sel.'>'.$theme.'</option>';
        }

        if($this->tmpVars['theme']) {
            $templateOptions .= '<option value=""></option>';
            $themeData = $vars[$this->tmpVars['theme']];

            foreach($themeData as $key => $template) {

                if(is_array($template)) {
                    $templateOptions .= '<optgroup label="'.$key.'">';

                    foreach($template as $sTemplate) {
                        $sel = ($this->tmpVars['template']==$key.'/'.$sTemplate)?'selected="selected"':'';
                        $templateOptions .= '<option value="'.$key.'/'.$sTemplate.'" '.$sel.'>'.$sTemplate.'</option>';
                    }

                    $templateOptions .= '</optgroup>';
                }
                else {
                    $sel = ($this->tmpVars['template']==$template)?'selected="selected"':'';
                    $templateOptions .= '<option value="'.$template.'" '.$sel.'>'.$template.'</option>';
                }

            }
        }
        else $templateOptions = '<option value="">'.$GLOBALS['LANG']->getLL('tmpl_selectThemeFirst').'</option>';

        $newThemeLink = '<a href="index.php?SET[function]='.$this->p->MOD_SETTINGS['function'].'&tx_mmforum_template[newtheme]=1" title="'.$GLOBALS['LANG']->getLL('tmpl_createTheme').'"><img src="img/forum-new.png" style="vertical-align:middle;" border="0" /></a>';
        
        if($this->tmpVars['theme']) {
            if($this->p->config['plugin.']['tx_mmforum.']['style']==$this->tmpVars['theme'])
                $activeLink = $GLOBALS['LANG']->getLL('tmpl_themeActive');
            else $activeLink = $GLOBALS['LANG']->getLL('tmpl_themeInactive').' <input type="submit" name="tx_mmforum_template[activate]" value="'.$GLOBALS['LANG']->getLL('tmpl_themeActivate').'" />';
        }

        $content .= '
<fieldset><legend>'.$GLOBALS['LANG']->getLL('tmpl_select_title').'</legend>
<table cellspacing="0" cellpadding="3" border="0">
    <tr>
        <td valign="top">'.$GLOBALS['LANG']->getLL('tmpl_selectTheme').':</td>
        <td valign="top"><select name="tx_mmforum_template[theme]" onchange="document.forms[0].submit();">'.$themeOptions.'</select> '.$newThemeLink.'</td>
        <td>'.$activeLink.'</td>
    </tr>
    <tr>
        <td valign="top">'.$GLOBALS['LANG']->getLL('tmpl_selectTemplate').':</td>
        <td valign="top"><select name="tx_mmforum_template[template]" onchange="document.forms[0].submit();">'.$templateOptions.'</select></td>
    </tr>
</table></fieldset><br />';

        return $content;

    }

    /**
     * Generates a form for editing a template.
     * This function generates a form for editing a template that has
     * been selected before. This function also stores the changes made by
     * the user into the file.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-07
     * @return  string The form content
     */
    function displayTemplateEditor() {
        global $LANG;

        $template = $this->tmpVars['template'];
        $theme    = $this->tmpVars['theme'];

        if($theme == 'default')
            $filename = $this->templatePath.$theme.'/'.$template;
        else $filename = $this->altTemplatePath.$theme.'/'.$template;
        $tContent = file_get_contents($filename);

        $content .= '
<script type="text/javascript">
function setSelectionRange(input, selectionStart, selectionEnd) {
  if (input.setSelectionRange) {
    input.focus();
    input.setSelectionRange(selectionStart, selectionEnd);
  }
  else if (input.createTextRange) {
    var range = input.createTextRange();
    range.collapse(true);
    range.moveEnd(\'character\', selectionEnd);
    range.moveStart(\'character\', selectionStart);
    range.select();
  }
}

function replaceSelection (input, replaceString) {
	if (input.setSelectionRange) {
		var selectionStart = input.selectionStart;
		var selectionEnd = input.selectionEnd;
		input.value = input.value.substring(0, selectionStart)+ replaceString + input.value.substring(selectionEnd);

		if (selectionStart != selectionEnd){
			setSelectionRange(input, selectionStart, selectionStart + 	replaceString.length);
		}else{
			setSelectionRange(input, selectionStart + replaceString.length, selectionStart + replaceString.length);
		}

	}else if (document.selection) {
		var range = document.selection.createRange();

		if (range.parentElement() == input) {
			var isCollapsed = range.text == \'\';
			range.text = replaceString;

			 if (!isCollapsed)  {
				range.moveStart(\'character\', -replaceString.length);
				range.select();
			}
		}
	}
}


// We are going to catch the TAB key so that we can use it, Hooray!
function catchTab(item,e){
	if(navigator.userAgent.match("Gecko")){
		c=e.which;
	}else{
		c=e.keyCode;
	}
	if(c==9){
		replaceSelection(item,String.fromCharCode(9));
		setTimeout("document.getElementById(\'"+item.id+"\').focus();",0);
		return false;
	}

}
</script>';

        if($this->tmpVars['template_edit'][$template]['submit']) {
            if($this->tmpVars['template_edit'][$template]['submit'] == $GLOBALS['LANG']->getLL('tmpl_editTemplate_toDefault'))
                $this->tmpVars['template_edit'][$template]['text'] = file_get_contents($this->templatePath.'default/'.$template);

            $file = fopen($filename,'w');
            fwrite($file,$this->tmpVars['template_edit'][$template]['text']);
            fclose($file);

            $tContent = $this->tmpVars['template_edit'][$template]['text'];
        }

        $tContent = htmlentities($tContent);

        $content .= '<fieldset><legend>'.sprintf($LANG->getLL('tmpl_editTemplate_title'),$template).'</legend>';
        $content .= '<textarea '.(($theme=='default')?'readonly="readonly"':'').' name="tx_mmforum_template[template_edit]['.$template.'][text]" style="width:100%; height:350px; font-family:Courier New; font-size:11px;" wrap="off" onkeydown="return catchTab(this,event);">'.$tContent.'</textarea>';

        if($theme != 'default')
            $content .= '<br /><br />
                            <input name="tx_mmforum_template[template_edit]['.$template.'][submit]" type="submit" value="'.$GLOBALS['LANG']->getLL('tmpl_editTemplate_submit').'" />
                            <input type="reset" value="'.$GLOBALS['LANG']->getLL('tmpl_editTemplate_reset').'" />
                            <input name="tx_mmforum_template[template_edit]['.$template.'][submit]" type="submit" value="'.$GLOBALS['LANG']->getLL('tmpl_editTemplate_toDefault').'" />';
        else $content .= '<br /><br />'.$GLOBALS['LANG']->getLL('tmpl_editTemplate_noedit');

        $content .= '</fieldset>';

        return $content;
    }

    /**
     * Copies directories and subdirectories.
     * This function recursively copies directories and subdirectories to
     * a new destination.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-07
     * @param   string $srcdir The source directory.
     * @param   string $dstdir The destination directory.
     * @return  void
     */
    function copy_recursive($srcdir,$dstdir) {
        if(substr($dstdir,-1,1)=='/') $dstdir = substr($dstdir,0,strlen($dstdir)-1);
        if(substr($srcdir,-1,1)=='/') $srcdir = substr($srcdir,0,strlen($srcdir)-1);

        $dirs = t3lib_div::get_dirs($srcdir);

        if(!is_dir($dstdir)) {
			mkdir($dstdir);
			t3lib_div::fixPermissions($dstdir);
		}

        if(count($dirs)>0) {
            foreach($dirs as $dir) {
                if(!is_dir($dstdir.'/'.$dir)) mkdir($dstdir.'/'.$dir);
                $this->copy_recursive($srcdir.'/'.$dir,$dstdir.'/'.$dir);
            }
        }

        $files = t3lib_div::getFilesInDir($srcdir);
        if(count($files)>0) {
            foreach($files as $file) {
                copy($srcdir.'/'.$file,$dstdir.'/'.$file);
				t3lib_div::fixPermissions($dstdir.'/'.$file);
            }
        }
    }

    /**
     * Creates the alternative template directory.
     * The alternative template directory is the directory where design
     * sets specified by the user are stored in. This directory has to be located
     * outside the mm_forum extension directory, because otherwise it would be
     * overwritten on update.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @return  void
     */
    function generateAltTemplatePath() {
        $segments = explode('/',$this->altTemplatePath);

        $path = '/';

        foreach($segments as $seg) {

            if(!is_dir($path)) mkdir($path);
            chdir($path);

            if(strlen(trim($seg))==0) continue;

            if($path == '/') $path = '/'.$seg;
            else $path .= '/'.$seg;
        }
    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_templates.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_templates.php']);
}
?>