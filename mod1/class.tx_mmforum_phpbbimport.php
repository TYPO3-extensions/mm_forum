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
 *   89: class tx_mmforum_phpbbimport
 *
 *              SECTION: Main functions
 *  141:     function main($content)
 *
 *              SECTION: Step 1: Database checking
 *  196:     function step1($content)
 *
 *              SECTION: Step 2: Select data
 *  282:     function step2($content)
 *  358:     function step2_getDependencyList($field)
 *  387:     function step2_checkDependencies()
 *
 *              SECTION: Step 3: Import settings
 *  420:     function step3($content)
 *
 *              SECTION: Step 4: Conduct import
 *  680:     function step4($content)
 *  878:     function step4_outputReport($report)
 *  895:     function step4_outputErrors($errors)
 *  916:     function step4_importBoards()
 * 1079:     function step4_importUsers()
 * 1323:     function step4_importPosts()
 * 1453:     function step4_importPosts_convBB($text)
 * 1480:     function step4_importPMs()
 * 1552:     function step4_importSearch()
 * 1684:     function step4_importSmilies()
 * 1738:     function step4_importSubscriptions()
 * 1775:     function step4_importPolls()
 *
 *              SECTION: Data storage functions
 * 1865:     function retrieveMappingArray(&$array, $filename)
 * 1884:     function saveMappingArray($array, $filename)
 * 1903:     function deleteMappingArray($filename)
 * 1914:     function getImported($field)
 * 1925:     function setImported($field)
 * 1937:     function unsetImported($field)
 *
 *              SECTION: Miscellaneous functions
 * 1962:     function checkTable($tablename)
 * 1976:     function getAllTables()
 * 1995:     function outputData()
 * 2013:     function outputData_recursiveArray($arr,$path)
 *
 * TOTAL FUNCTIONS: 28
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(PATH_t3lib.'class.t3lib_tceforms.php');

/**
 * This class manages data import from existing phpBB boards.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @version    2007-05-02
 * @package    mm_forum
 * @subpackage Backend
 */
class tx_mmforum_phpbbimport {

	var $table_prefix = "phpbb";
	var $steps = 4;
    var $stepOffset = 2;

	/**
	 * An array containing a list of all tables used by the phpBB board in version 2.0.22
	 */
	var $phpbbTables = array(
		'auth_access','banlist','categories','config','confirm','disallow','forum_prune',
		'forums','groups','posts','posts_text','privmsgs','privmsgs_text','ranks','search_results',
		'search_wordlist','search_wordmatch','sessions','sessions_keys','smilies','themes',
		'themes_name','topics','topics_watch','user_group','users','vote_desc','vote_results',
		'vote_voters','words'
	);
	/**
	 * An array defining which tables are needed for which import operation
	 */
	var $reqTables = array(
		'boards/categories'	=> array('categories','forums','auth_access'),
		'users/usergroups' => array('users','user_group'),
		'topics/posts' => array('posts','posts_text','topics','forums','users'),
		'pms' => array('privmsgs','privmsgs_text','users'),
        'polls' => array('vote_desc','vote_results','vote_voters'),
		'search' => array('search_results','search_wordlist','search_wordmatch'),
		'smilies' => array('smilies'),
		'subscr' => array('topics_watch','users')
	);

	/**
	 * Defines the interdependencies between different data fields.
	 */
	var $dependencies = array(
		'0:2','2:0','2:1','3:1','5:2','5:1','7:1','0:1','4:2','4:1'
	);

	var $userID_mapping, $usergroupID_mapping, $topicID_mapping, $postID_mapping, $categoryID_mapping, $boardID_mapping, $searchword_mapping;
	var $updateMapping = array();

    /**
     * Main functions
     */

	/**
	 * Main function. Reads GP-Parameters and displays general options.
	 *
	 * @param   string $content The content variable
	 * @return  string          The plugin content.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-15
	 */
	function main($content)
	{
		$this->data['step'] = 1;
		$this->data = array_merge(
			$this->data,
			is_array(t3lib_div::_GET('tx_mmforum_phpbb'))?t3lib_div::_GET('tx_mmforum_phpbb'):array(),
			is_array(t3lib_div::_POST('tx_mmforum_phpbb'))?t3lib_div::_POST('tx_mmforum_phpbb'):array()
		);

        if(!$this->dbObj) $this->dbObj = $GLOBALS['TYPO3_DB'];

        $content  = '<form action="" method="post">';

        #$this->confArr= unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mm_forum']);
        $this->confArr = $this->p->confArr;

		switch($this->data['step']) {
			case 1: $content = $this->step1($content); break;
			case 2: $content = $this->step2($content); break;
			case 3: $content = $this->step3($content); break;
			case 4: $content = $this->step4($content); break;
		}

		$content .= $this->outputData();
		$content .= '</form>';

		return $content;
	}

    /**
     * Step 1: Database checking
     */

	/**
	 * Displays and conducts the first step of phpBB data import.
	 * The first step consists of defining the database table prefix, since all
	 * phpBB tables are prefixed by a custom prefix.
	 * After this prefix was specified by user input, this is verified by checking
	 * if all the necessary tables exist. If none of the required tables exist,
	 * it will not be possible to go on to the next step. Of some of the required
	 * tables exists, a warning will be displayed saying that there might occur
	 * some problems during further data import procedure. The missing of some
	 * tables will cause the regarding operations in the following step to be
	 * not available for selection.
	 * If all tables are found, of course no warning will be displayed and the
	 * user may proceed with the next step.
	 *
	 * @param   string $content The content variable
	 * @return  string          The content of step 1
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-08
	 */
	function step1($content) {
		global $LANG;

		// Continue to next step, if everything is alright
			if($this->data['step1.']['submit'] == $LANG->getLL('phpbb.general.continue') && $this->data['step1.']['ignore'] != $LANG->getLL('phpbb.general.no')) {
				if(($this->data['step1.']['found'] == count($this->phpbbTables)) || ($this->data['step1.']['ignore']==$LANG->getLL('phpbb.general.yes'))) {
					$content = $this->step2($content);
					return $content;
				}
			}

		$content .= '<fieldset>';
		$content .= '<legend>'.sprintf($LANG->getLL('phpbb.general.stepXofY'),1+$this->stepOffset,$this->steps+$this->stepOffset).': '.$LANG->getLL('phpbb.step1').'</legend>';

		// Display warning message
			if($this->data['step1.']['submit'] == $LANG->getLL('phpbb.general.continue') && $this->data['step1.']['ignore'] != $LANG->getLL('phpbb.general.no')) {
				unset($this->data['step1.']['submit']);

				if(($this->data['step1.']['found'] < count($this->phpbbTables)) && ($this->data['step1.']['ignore']!=$LANG->getLL('phpbb.general.yes'))) {
					$content .= $LANG->getLL('phpbb.step1.warning').'<br /><br />';
					$content .= '<input type="submit" value="'.$LANG->getLL('phpbb.general.yes').'" name="tx_mmforum_phpbb[step1.][ignore]" /> ';
					$content .= '<input type="submit" value="'.$LANG->getLL('phpbb.general.no').'" name="tx_mmforum_phpbb[step1.][ignore]" /> ';
					$content .= '<input type="hidden" name="tx_mmforum_phpbb[step1.][submit]" value="'.$LANG->getLL('phpbb.general.continue').'" />';

					$content .= '</fieldset>';
					return $content;
				}
			}

			unset($this->data['step1.']['submit']);
			unset($this->data['step1.']['ignore']);

		// Output table prefix definition field
			$content .= $LANG->getLL('phpbb.general.prefix').': <input type="text" value="'.$this->data['prefix'].'" name="tx_mmforum_phpbb[prefix]" /> ';
			$content .= '<input type="submit" value="'.$LANG->getLL('phpbb.general.update').'" name="tx_mmforum_phpbb[step1.][submit]" />';

		// Display MySQL table test
			if(strlen($this->data['prefix'])>0) {
				$content .= '<br /><br /><strong>'.$LANG->getLL('phpbb.step1.checking').'</strong><br />';
				$found = 0;
				foreach($this->phpbbTables as $table) {
					$tablename = $this->data['prefix'].'_'.$table;
					if(!$this->checkTable($tablename)) $content .= sprintf($LANG->getLL('phpbb.step1.notfound'),$tablename).'<br />';
					else $found++;
				}

				if($found == 0) 							$content .= '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step1.fatalError').'</div>';
				elseif($found < count($this->phpbbTables))	$content .= '<div class="mm_forum-warning">'.$LANG->getLL('phpbb.step1.error').'</div>';
				else 										$content .= '<div class="mm_forum-ok">'.$LANG->getLL('phpbb.step1.ok').'</div>';

				if($found > 0)
					$content .= '<br /><input type="submit" value="'.$LANG->getLL('phpbb.general.continue').'" name="tx_mmforum_phpbb[step1.][submit]" />';

				$content .= '<input type="hidden" name="tx_mmforum_phpbb[step1.][found]" value="'.$found.'" />';

				unset($this->data['prefix']);
			}

		$content .= '</fieldset>';

		return $content;
	}

    /**
     * Step 2: Select data
     */

	/**
	 * Displays and conducts the second step of phpBB data import.
	 * The second step of phpBB data import consists of selecting which data
	 * from the phpBB board are to be imported. At the moment, the user can decide
	 * between importing board categories and boards, usergroups and users,
	 * threads and posts, private messages, search indices, smilies and email
	 * subscriptions. Some data are necessary to import in several of these data
	 * fields, such as the user and user group data. If the user decides to import
	 * a certain data field for which another data field is also needed to be imported,
	 * the user will be forced to select this data field either to prevent errors
	 * during data import.
	 * If the user tries to go on to the next step without selecting any data fields,
	 * an error message will be displayed.
	 *
	 * @param   string $content The content variable
	 * @return  string          The content of step 2
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-08
	 */
	function step2($content) {
		global $LANG;

		$this->data['step'] = 2;
		$noSelectNote = "";

		if($this->data['step2.']['submit'] == $LANG->getLL('phpbb.general.continue')) {
			if(count($this->data['step2.']['importdata'])==0) $noSelectNote = '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step2.noSelectNote').'</div>';
			elseif(!$this->step2_checkDependencies()) $noSelectNote = '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step2.dependencyError').'</div>';
			else {
				$content = $this->step3($content);
				return $content;
			}

			unset($this->data['step2.']['submit']);
		}

		$content .= '<fieldset>';
		$content .= '<legend>'.sprintf($LANG->getLL('phpbb.general.stepXofY'),2+$this->stepOffset,$this->steps+$this->stepOffset).': '.$LANG->getLL('phpbb.step2').'</legend>';

		$content .= $LANG->getLL('phpbb.step2.instruction').$noSelectNote.'<br />';

		$content .= '<table border="0" cellspacing="0" cellpadding="3" width="100%">';

		$errorGlobal = false;
		$i = 0;
		foreach($this->reqTables as $data=>$tables) {
			$dTables = $tables;
			$error = false;
			foreach($dTables as $key=>$table) {
				$table = $this->data['prefix'].'_'.$table;
				if(!$this->checkTable($table)) {
					$dTables[$key] = '<span style="color:red;">'.$table.'</span>';
					$error = true;
					$errorGlobal = true;
				}
				else $dTables[$key] = $table;
			}

			$checkboxChecked = 'checked="checked"';
			if($this->getImported($data)) $checkboxChecked = '';
			if($error) {
				$checkboxChecked = 'disabled="disabled"';
			}

			if(isset($this->data['step2.']['importdata'])) {
				$checkboxChecked = in_array($data,$this->data['step2.']['importdata'])?'checked="checked"':'';
			}

			$checkbox = '<input type="checkbox" name="tx_mmforum_phpbb[step2.][importdata][]" value="'.$data.'" '.$checkboxChecked.' />';
			$caption  = ($error?'<span style="color:#808080;">':'').'<strong>'.$LANG->getLL('phpbb.step2.'.$data).'</strong>'.($this->getImported($data)?' <span style="color:008000;">('.$LANG->getLL('phpbb.step2.alreadyImported').')</span>':'').'<br />'.$LANG->getLL('phpbb.step2.tablesrequired').': '.implode(', ',$dTables).''.($error?'</span>':'');
			$caption .= '<br />'.$LANG->getLL('phpbb.step2.dependsOn').': '.$this->step2_getDependencyList($i);

			$content .= '<tr><td>'.$checkbox.'</td><td>'.$caption.'</td></tr>';
			$i ++;
		}

		$content .= '</table>';

		if($errorGlobal) $content .= '<br />'.$LANG->getLL('phpbb.step2.note');
		$content .= '<br /><input type="submit" value="'.$LANG->getLL('phpbb.general.continue').'" name="tx_mmforum_phpbb[step2.][submit]" />';

		$content .= '</fieldset>';

		unset($this->data['step2.']);

		return $content;
	}

	/**
	 * Generates a commalist of interdependencies between data to be imported.
	 *
	 * @param   string $field The field whose dependencies are to be listed.
	 * @return  string        A commalist of depencencies
	 * @version 2007-04-03
	 */
	function step2_getDependencyList($field) {
		global $LANG;
		$dep = array();
		foreach($this->dependencies as $dependency) {
			$data = explode(':',$dependency);
			$names = array_keys($this->reqTables);

			if($data[0] == $field) {
				if(isset($this->data['step2.']['importdata'])) {
					if(!in_array($names[$data[1]],$this->data['step2.']['importdata'])) {
						if(!$this->getImported($names[$data[1]]))
							$dep[] = '<span class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step2.'.$names[$data[1]]).'</span>';
					}
					else $dep[] = $LANG->getLL('phpbb.step2.'.$names[$data[1]]);
				}
				else $dep[] = $LANG->getLL('phpbb.step2.'.$names[$data[1]]);
			}
		}
		if(count($dep)==0) return "&mdash;";
		return implode(', ',$dep);
	}

	/**
	 * Checks if all dependencies between different data fields are
	 * fulfilled.
	 *
	 * @return boolean TRUE, if all depencencies are fulfilled, otherwise FALSE.
	 * @version 2007-04-03
	 */
	function step2_checkDependencies() {
		$selected = $this->data['step2.']['importdata'];

		$names = array_keys($this->reqTables);

		foreach($this->dependencies as $dependency) {
			$data = explode(':',$dependency);

			if(in_array($names[$data[0]],$selected) && !in_array($names[$data[1]],$selected)) {
				if(!$this->getImported($names[$data[1]]))
					return false;
			}
		}
		return true;
	}

    /**
     * Step 3: Import settings
     */

	/**
	 * Displays and conducts the third step of phpBB data import.
	 * In this step, the user will specify several other setting needed
	 * during data import. For example, one of these settings is the page UID
	 * of the page where the newly created records are to be stored on.
	 * In dependence of which data fields the user chose to be imported in
	 * step 2, there will be displayed different options.
	 *
	 * @param   string $content The content variable
	 * @return  string          The content of step3
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-15
	 */
	function step3($content) {
		global $LANG;

		$this->data['step'] = 3;
		$dataFields = $this->data['step2.']['importdata'];

		$warning = 0;
		$error = 0;

		// Define default data
		if(!isset($this->data['step3.'])) {
			$this->data['step3.']['import0.']['pid'] = $this->confArr['forumPID'];
			$this->data['step3.']['import1.']['pid'] = $this->confArr['userPID'];
			$this->data['step3.']['import2.']['pid'] = $this->confArr['forumPID'];
			$this->data['step3.']['import3.']['pid'] = $this->confArr['forumPID'];
			$this->data['step3.']['polls.']['pid'] = $this->confArr['forumPID'];
			$this->data['step3.']['import4.']['pid'] = $this->confArr['forumPID'];
			$this->data['step3.']['import5.']['pid'] = $this->confArr['forumPID'];
			$this->data['step3.']['import6.']['pid'] = $this->confArr['forumPID'];
			$this->data['step3.']['import1.']['mods'] = 'import';
			$this->data['step3.']['import1.']['admins'] = 'import';
			$this->data['step3.']['import1.']['users'] = 'import';
			$this->data['step3.']['import1.']['modgroup'] = $this->confArr['modGroup'];
			$this->data['step3.']['import1.']['admingroup'] = $this->confArr['adminGroup'];
			$this->data['step3.']['import1.']['usergroup'] = $this->confArr['userGroup'];
			$this->data['step3.']['import1.']['avatarpath'] = 'uploads/tx_mmforum/';
			$this->data['step3.']['import1.']['importSingleUserGroups'] = 0;
			$this->data['step3.']['import5.']['phpbb_smilie_url'] = 'images/smilies/';
			$this->data['step3.']['import5.']['mmforum_smilie_url'] = t3lib_extMgm::siteRelPath('mm_forum').'res/smilies/';
		}

		if($this->data['step3.']['submit'] == $LANG->getLL('phpbb.general.continue')) {
			if(in_array('boards/categories',$dataFields)) {
				// Check if board and category PID was specified
				if(strlen($this->data['step3.']['import0.']['pid'])==0 || !is_numeric($this->data['step3.']['import0.']['pid'])) {
					$warning ++;
					$warnings['import0.pid'] = '<div class="mm_forum-warning">'.$LANG->getLL('phpbb.step3.import0.pid.warning').'</div>';
					$this->data['step3.']['import0.']['pid'] = 0;
				}
			}
			if(in_array('users/usergroups',$dataFields)) {
				// Check if user and user group PID was specified
				if(strlen($this->data['step3.']['import1.']['pid'])==0 || !is_numeric($this->data['step3.']['import1.']['pid'])) {
					$warning ++;
					$warnings['import1.pid'] = '<div class="mm_forum-warning">'.$LANG->getLL('phpbb.step3.import0.pid.warning').'</div>';
					$this->data['step3.']['import1.']['pid'] = 0;
				}
				// Check if user group was correctly specified
				if(($this->data['step3.']['import1.']['users']=='import') && (intval($this->data['step3.']['import1.']['usergroup'])==0)) {
					$error ++;
					$warnings['import1.users'] = '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step3.import1.mods.emptyError').'</div>';
				}
				elseif($this->data['step3.']['import1.']['users']=='import') {
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_groups','uid="'.intval($this->data['step3.']['import1.']['usergroup']).'"');
					if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) {
						$error ++;
						$warnings['import1.users'] = '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step3.import1.mods.neError').'</div>';
					}
				}
				// Check if moderator group was correctly specified
				if(($this->data['step3.']['import1.']['mods']=='import') && (intval($this->data['step3.']['import1.']['modgroup'])==0)) {
					$error ++;
					$warnings['import1.mods'] = '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step3.import1.mods.emptyError').'</div>';
				}
				elseif($this->data['step3.']['import1.']['mods']=='import') {
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_groups','uid="'.intval($this->data['step3.']['import1.']['modgroup']).'"');
					if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) {
						$error ++;
						$warnings['import1.mods'] = '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step3.import1.mods.neError').'</div>';
					}
				}
				// Check if admin group was correctly specified
				if($this->data['step3.']['import1.']['admins']=='import' && intval($this->data['step3.']['import1.']['admingroup'])==0) {
					$error ++;
					$warnings['import1.admins'] = '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step3.import1.mods.emptyError').'</div>';
				}
				elseif($this->data['step3.']['import1.']['admins']=='import') {
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_groups','uid="'.intval($this->data['step3.']['import1.']['admingroup']).'"');
					if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) {
						$error ++;
						$warnings['import1.admins'] = '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step3.import1.mods.neError').'</div>';
					}
				}
				// Check if avatar path was correctly specified
				if(strlen($this->data['step3.']['import1.']['avatarpath'])==0) {
					$warning ++;
					$warnings['import1.avatarpath'] = '<div class="mm_forum-warning">'.$LANG->getLL('phpbb.step3.import1.avatarpath.emptyWarning').'</div>';

					$this->data['step3.']['import1.']['avatarpath'] = 'uploads/tx_mmforum/';
				}
			}
			if(in_array('topics/posts',$dataFields)) {
				if(strlen($this->data['step3.']['import2.']['pid'])==0 || !is_numeric($this->data['step3.']['import2.']['pid'])) {
					$warning ++;
					$warnings['import2.pid'] = '<div class="mm_forum-warning">'.$LANG->getLL('phpbb.step3.import0.pid.warning').'</div>';
					$this->data['step3.']['import2.']['pid'] = 0;
				}
			}
			if(in_array('pms',$dataFields)) {
				if(strlen($this->data['step3.']['import3.']['pid'])==0 || !is_numeric($this->data['step3.']['import3.']['pid'])) {
					$warning ++;
					$warnings['import3.pid'] = '<div class="mm_forum-warning">'.$LANG->getLL('phpbb.step3.import0.pid.warning').'</div>';
					$this->data['step3.']['import3.']['pid'] = 0;
				}
			}
			if(in_array('search',$dataFields)) {
				if(strlen($this->data['step3.']['import4.']['pid'])==0 || !is_numeric($this->data['step3.']['import4.']['pid'])) {
					$warning ++;
					$warnings['import4.pid'] = '<div class="mm_forum-warning">'.$LANG->getLL('phpbb.step3.import0.pid.warning').'</div>';
					$this->data['step3.']['import4.']['pid'] = 0;
				}
			}
			if(in_array('smilies',$dataFields)) {
				if(strlen($this->data['step3.']['import5.']['pid'])==0 || !is_numeric($this->data['step3.']['import5.']['pid'])) {
					$warning ++;
					$warnings['import5.pid'] = '<div class="mm_forum-warning">'.$LANG->getLL('phpbb.step3.import0.pid.warning').'</div>';
					$this->data['step3.']['import5.']['pid'] = 0;
				}
				if(strlen($this->data['step3.']['import5.']['phpbb_smilie_url']) == 0) {
					$error ++;
					$warnings['import5.phpbb_smilie_url'] .= '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step3.import5.phpbbSmilie_url.emptyError').'</div>';
				}
				if(strlen($this->data['step3.']['import5.']['mmforum_smilie_url']) == 0) {
					$error ++;
					$warnings['import5.mmforum_smilie_url'] .= '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step3.import5.mmforumSmilie_url.emptyError').'</div>';
				}
			}
			if(in_array('subscr',$dataFields)) {
				if(strlen($this->data['step3.']['import6.']['pid'])==0 || !is_numeric($this->data['step3.']['import6.']['pid'])) {
					$warning ++;
					$warnings['import6.pid'] = '<div class="mm_forum-warning">'.$LANG->getLL('phpbb.step3.import0.pid.warning').'</div>';
					$this->data['step3.']['import6.']['pid'] = 0;
				}
			}

			if($error+$warning == 0) {
				$content = $this->step4($content);
				return $content;
			}
		}

		$content .= '<fieldset>';
		$content .= '<legend>'.sprintf($LANG->getLL('phpbb.general.stepXofY'),3+$this->stepOffset,$this->steps+$this->stepOffset).': '.$LANG->getLL('phpbb.step3').'</legend>';

		$content .= '<table cellspacing="0" cellpadding="3" width="100%">';

		$checked = ($this->data['step3.']['clearAll'])?'checked="checked"':'';
		$content .= '<tr><td style="width:1px; color:#ff8000;">&raquo;</td><td width="33%">'.$LANG->getLL('phpbb.step3.clearAll').'</td><td><input type="checkbox" name="tx_mmforum_phpbb[step3.][clearAll] value="1" '.$checked.' /></td></tr>';

		$checked = ($this->data['step3.']['clearReallyAll'])?'checked="checked"':'';
		$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.clearReallyAll').'</td><td><input type="checkbox" name="tx_mmforum_phpbb[step3.][clearReallyAll] value="1" '.$checked.' /></td></tr>';

		if(in_array('boards/categories',$dataFields)) {
			$content .= '<tr><td style="width:1px; color:#ff8000;">&raquo;</td><td colspan="2"><strong>'.$LANG->getLL('phpbb.step2.boards/categories').'</strong></td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import0.pid').'</td><td width="67%"><input type="text" name="tx_mmforum_phpbb[step3.][import0.][pid]" size="4" value="'.$this->data['step3.']['import0.']['pid'].'" />'.$warnings['import0.pid'].'</td></tr>';
		}
		if(in_array('users/usergroups',$dataFields)) {
			$content .= '<tr><td style="width:1px; color:#ff8000;">&raquo;</td><td colspan="2"><strong>'.$LANG->getLL('phpbb.step2.users/usergroups').'</strong></td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import1.pid').'</td><td width="67%"><input type="text" name="tx_mmforum_phpbb[step3.][import1.][pid]" size="4" value="'.$this->data['step3.']['import1.']['pid'].'" />'.$warnings['import1.pid'].'</td></tr>';

			$iChecked = ($this->data['step3.']['import1.']['users']=='import' )?'checked="checked"':'';
			$nChecked = ($this->data['step3.']['import1.']['users']=='nothing')?'checked="checked"':'';
			$cChecked = ($this->data['step3.']['import1.']['users']=='create' || !$this->data['step3.']['import1.']['users'])?'checked="checked"':'';

			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import1.users').'</td><td width="67%">
				<input type="radio" name="tx_mmforum_phpbb[step3.][import1.][users]" value="create"  '.$cChecked.'> '.$LANG->getLL('phpbb.step3.import1.users.create').'<br />
				<input type="radio" name="tx_mmforum_phpbb[step3.][import1.][users]" value="import"  '.$iChecked.'> '.$LANG->getLL('phpbb.step3.import1.users.import').': <input type="text" name="tx_mmforum_phpbb[step3.][import1.][usergroup]" value="'.$this->data['step3.']['import1.']['usergroup'].'" /><br />
				<input type="radio" name="tx_mmforum_phpbb[step3.][import1.][users]" value="nothing" '.$nChecked.'> '.$LANG->getLL('phpbb.step3.import1.users.nothing').'
				'.$warnings['import1.users'].'
			</td></tr>';

			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import1.moderators').'</td><td width="67%">
				<input type="radio" name="tx_mmforum_phpbb[step3.][import1.][mods]" value="create"  '.$cChecked.'> '.$LANG->getLL('phpbb.step3.import1.mods.create').'<br />
				<input type="radio" name="tx_mmforum_phpbb[step3.][import1.][mods]" value="import"  '.$iChecked.'> '.$LANG->getLL('phpbb.step3.import1.mods.import').': <input type="text" name="tx_mmforum_phpbb[step3.][import1.][modgroup]" value="'.$this->data['step3.']['import1.']['modgroup'].'" /><br />
				<input type="radio" name="tx_mmforum_phpbb[step3.][import1.][mods]" value="nothing" '.$nChecked.'> '.$LANG->getLL('phpbb.step3.import1.mods.nothing').'
				'.$warnings['import1.mods'].'
			</td></tr>';

			$iChecked = ($this->data['step3.']['import1.']['admins']=='import' )?'checked="checked"':'';
			$nChecked = ($this->data['step3.']['import1.']['admins']=='nothing')?'checked="checked"':'';
			$cChecked = ($this->data['step3.']['import1.']['admins']=='create' || !$this->data['step3.']['import1.']['mods'])?'checked="checked"':'';

			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import1.admins').'</td><td width="67%">
				<input type="radio" name="tx_mmforum_phpbb[step3.][import1.][admins]" value="create"  '.$cChecked.'> '.$LANG->getLL('phpbb.step3.import1.admins.create').'<br />
				<input type="radio" name="tx_mmforum_phpbb[step3.][import1.][admins]" value="import"  '.$iChecked.'> '.$LANG->getLL('phpbb.step3.import1.admins.import').': <input type="text" name="tx_mmforum_phpbb[step3.][import1.][admingroup]" value="'.$this->data['step3.']['import1.']['admingroup'].'" /><br />
				<input type="radio" name="tx_mmforum_phpbb[step3.][import1.][admins]" value="nothing" '.$nChecked.'> '.$LANG->getLL('phpbb.step3.import1.admins.nothing').'
				'.$warnings['import1.admins'].'
			</td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import1.avatarpath').'</td><td width="67%"><input type="text" name="tx_mmforum_phpbb[step3.][import1.][avatarpath]" value="'.$this->data['step3.']['import1.']['avatarpath'].'" />'.$warnings['import1.avatarpath'].'</td></tr>';

			$isugChecked = ($this->data['step3.']['import1.']['importSingleUserGroups'])?'checked="checked"':'';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import1.createSingleUserGroups').'</td><td width="67%"><input type="checkbox" name="tx_mmforum_phpbb[step3.][import1.][importSingleUserGroups]" value="1" '.$isugChecked.' /></td></tr>';
		}
		if(in_array('topics/posts',$dataFields)) {
			$content .= '<tr><td style="width:1px; color:#ff8000;">&raquo;</td><td colspan="2"><strong>'.$LANG->getLL('phpbb.step2.topics/posts').'</strong></td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import2.pid').'</td><td width="67%"><input type="text" name="tx_mmforum_phpbb[step3.][import2.][pid]" size="4" value="'.$this->data['step3.']['import2.']['pid'].'" />'.$warnings['import2.pid'].'</td></tr>';
		}
		if(in_array('pms',$dataFields)) {
			$content .= '<tr><td style="width:1px; color:#ff8000;">&raquo;</td><td colspan="2"><strong>'.$LANG->getLL('phpbb.step2.pms').'</strong></td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import3.pid').'</td><td width="67%"><input type="text" name="tx_mmforum_phpbb[step3.][import3.][pid]" size="4" value="'.$this->data['step3.']['import3.']['pid'].'" />'.$warnings['import3.pid'].'</td></tr>';
		}
        if(in_array('polls',$dataFields)) {
            $content .= '<tr><td style="width:1px; color:#ff8000;">&raquo;</td><td colspan="2"><strong>'.$LANG->getLL('phpbb.step2.polls').'</strong></td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.polls.pid').'</td><td width="67%"><input type="text" name="tx_mmforum_phpbb[step3.][polls.][pid]" size="4" value="'.$this->data['step3.']['polls.']['pid'].'" />'.$warnings['polls.pid'].'</td></tr>';
        }
		if(in_array('search',$dataFields)) {
			$checked = $this->data['step3.']['import4.']['group_post']?'checked="checked"':'';

			$content .= '<tr><td style="width:1px; color:#ff8000;">&raquo;</td><td colspan="2"><strong>'.$LANG->getLL('phpbb.step2.search').'</strong></td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import4.pid').'</td><td width="67%"><input type="text" name="tx_mmforum_phpbb[step3.][import4.][pid]" size="4" value="'.$this->data['step3.']['import4.']['pid'].'" />'.$warnings['import4.pid'].'</td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import4.group_post').'</td><td width="67%"><input type="hidden" name="tx_mmforum_phpbb[step3.][import4.][group_post]" value="0" /><input type="checkbox" name="tx_mmforum_phpbb[step3.][import4.][group_post]" value="1" '.$checked.' /></td></tr>';
		}
		if(in_array('smilies',$dataFields)) {
			$content .= '<tr><td style="width:1px; color:#ff8000;">&raquo;</td><td colspan="2"><strong>'.$LANG->getLL('phpbb.step2.smilies').'</strong></td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import5.pid').'</td><td width="67%"><input type="text" name="tx_mmforum_phpbb[step3.][import5.][pid]" size="4" value="'.$this->data['step3.']['import5.']['pid'].'" />'.$warnings['import5.pid'].'</td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import5.phpbb_smilie_url').'</td><td width="67%"><input type="text" name="tx_mmforum_phpbb[step3.][import5.][phpbb_smilie_url]" value="'.$this->data['step3.']['import5.']['phpbb_smilie_url'].'" />'.$warnings['import5.phpbb_smilie_url'].'</td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import5.mmforum_smilie_url').'</td><td width="67%"><input type="text" name="tx_mmforum_phpbb[step3.][import5.][mmforum_smilie_url]" value="'.$this->data['step3.']['import5.']['mmforum_smilie_url'].'" />'.$warnings['import5.mmforum_smilie_url'].'</td></tr>';
		}
		if(in_array('subscr',$dataFields)) {
			$content .= '<tr><td style="width:1px; color:#ff8000;">&raquo;</td><td colspan="2"><strong>'.$LANG->getLL('phpbb.step2.subscr').'</strong></td></tr>';
			$content .= '<tr><td></td><td width="33%">'.$LANG->getLL('phpbb.step3.import6.pid').'</td><td width="67%"><input type="text" name="tx_mmforum_phpbb[step3.][import6.][pid]" size="4" value="'.$this->data['step3.']['import6.']['pid'].'" />'.$warnings['import6.pid'].'</td></tr>';
		}

		unset($this->data['step3.']);

		$content .= '</table>';

		if($warning > 0) $content .= '<div class="mm_forum-warning">'.$LANG->getLL('phpbb.step3.warning').'</div><input type="hidden" name="tx_mmforum_phpbb[step3.][ignore]" value="1" />';
		if($error == 1) $content .= '<div class="mm_forum-fatalerror">'.$LANG->getLL('phpbb.step3.error1').'</div>';
		elseif($error > 1) $content .= '<div class="mm_forum-fatalerror">'.sprintf($LANG->getLL('phpbb.step3.errors'),$error).'</div>';

		$content .= '<br /><input type="submit" value="'.$LANG->getLL('phpbb.general.continue').'" name="tx_mmforum_phpbb[step3.][submit]" />';

		$content .= '</fieldset>';

		return $content;
	}

    /**
     * Step 4: Conduct import
     */

	/**
	 * Displays and conducts the fourth step of phpBB data import.
	 * In the fourth step, the data import is finally really conducted,
	 * using the parameters specified in steps one, two and three.
	 * The phpBB tables will be imported in an order that prevents conflicts
	 * resulting from data that not yet exists, since it is not yet imported,
	 * being referenced by already imported records.
	 * Problematic is that all entries will recieve a new primary key while
	 * being imported, so that references to the old primary key that exist
	 * in other records will be outdated when these records will be imported.
	 * To solve this problem, global mapping arrays will be created allowing
	 * to match old and new primary keys.
	 *
	 * @param   string $content The content variable
	 * @return  string          The content of step4
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-03
	 */
	function step4($content) {
		global $LANG;

		$this->data['step'] = 4;

		$content .= '<fieldset>';
		$content .= '<legend>'.sprintf($LANG->getLL('phpbb.general.stepXofY'),4+$this->stepOffset,$this->steps+$this->stepOffset).': '.$LANG->getLL('phpbb.step4').'</legend>';

		if($this->data['step4.']['submit']==$LANG->getLL('phpbb.step4.startButton')) {
			$dataFields = $this->data['step2.']['importdata'];

			if($this->data['step3.']['clearAll']) {
				$sql = "";
				if(in_array('users/usergroups',$dataFields)) {
					$userWhere = $this->data['step4.']['clearAll']['userWHERE']?$this->data['step4.']['clearAll']['userWHERE']:($this->data['step4.']['clearAll']['userUID']?"WHERE uid NOT IN (".$this->data['step4.']['clearAll']['userUID'].")":'');
					$usergroupWhere = $this->data['step4.']['clearAll']['usergroupWHERE']?$this->data['step4.']['clearAll']['usergroupWHERE']:($this->data['step4.']['clearAll']['usergroupUID']?"WHERE uid NOT IN (".$this->data['step4.']['clearAll']['usergroupUID'].")":'');

					mysql_query("DELETE FROM fe_users $userWhere;");
                    #mysql_query("DELETE FROM fe_groups $usergroupWhere;");
					$this->unsetImported('users/usergroups');
					$this->deleteMappingArray('usergroupID_mapping');
					$this->deleteMappingArray('userID_mapping');
				}
				if(in_array('boards/categories',$dataFields)) {
					mysql_query("DELETE FROM tx_mmforum_forums");
					$this->unsetImported('boards/categories');
					$this->deleteMappingArray('boardID_mapping');
					$this->deleteMappingArray('categoryID_mapping');
				}
				if(in_array('topics/posts',$dataFields)) {
					mysql_query("DELETE FROM tx_mmforum_posts;");
                    mysql_query("DELETE FROM tx_mmforum_topics;");
                    mysql_query("DELETE FROM tx_mmforum_posts_text;");

					$this->unsetImported('topics/posts');
					$this->deleteMappingArray('postID_mapping');
					$this->deleteMappingArray('topicID_mapping');
				}
				if(in_array('pms',$dataFields)) {
					mysql_query("DELETE FROM tx_mmforum_pminbox");
					$this->unsetImported('pms');
				}
				if(in_array('polls',$dataFields)) {
					mysql_query("DELETE FROM tx_mmforum_polls;");
                    mysql_query("DELETE FROM tx_mmforum_polls_answers;");
                    mysql_query("DELETE FROM tx_mmforum_polls_votes;");
					$this->unsetImported('polls');
				}
				if(in_array('search',$dataFields)) {
					mysql_query("DELETE FROM tx_mmforum_searchresults;");
                    mysql_query("DELETE FROM tx_mmforum_wordlist;");
                    mysql_query("DELETE FROM tx_mmforum_wordmatch;");
					$this->unsetImported('search');
				}
				if(in_array('smilies',$dataFields)) {
					mysql_query("DELETE FROM tx_mmforum_smilies");
					$this->unsetImported('smilies');
				}
				if(in_array('substr',$dataFields)) {
					mysql_query("DELETE FROM tx_mmforum_topicmail");
					$this->unsetImported('subscr');
				}
			}
			elseif($this->data['step3.']['clearReallyAll']) {
				$userWhere = $this->data['step4.']['clearAll']['userWHERE']?$this->data['step4.']['clearAll']['userWHERE']:($this->data['step4.']['clearAll']['userUID']?"WHERE uid NOT IN (".$this->data['step4.']['clearAll']['userUID'].")":'');
				$usergroupWhere = $this->data['step4.']['clearAll']['usergroupWHERE']?$this->data['step4.']['clearAll']['usergroupWHERE']:($this->data['step4.']['clearAll']['usergroupUID']?"WHERE uid NOT IN (".$this->data['step4.']['clearAll']['usergroupUID'].")":'');

				mysql_query("DELETE FROM fe_users $userWhere");
				#mysql_query("DELETE FROM fe_groups $usergroupWhere");
				mysql_query("DELETE FROM tx_mmforum_forums");
				mysql_query("DELETE FROM tx_mmforum_posts");
				mysql_query("DELETE FROM tx_mmforum_topics");
				mysql_query("DELETE FROM tx_mmforum_posts_text");
				mysql_query("DELETE FROM tx_mmforum_pminbox");
				mysql_query("DELETE FROM tx_mmforum_searchresults");
				mysql_query("DELETE FROM tx_mmforum_wordlist");
				mysql_query("DELETE FROM tx_mmforum_wordmatch");
				mysql_query("DELETE FROM tx_mmforum_smilies");
				mysql_query("DELETE FROM tx_mmforum_topicmail;");
				mysql_query("DELETE FROM tx_mmforum_polls;");
				mysql_query("DELETE FROM tx_mmforum_polls_votes;");
				mysql_query("DELETE FROM tx_mmforum_polls_answers;");

				$this->unsetImported('users/usergroups');
				$this->unsetImported('boards/categories');
				$this->unsetImported('topics/posts');
				$this->unsetImported('pms');
				$this->unsetImported('polls');
				$this->unsetImported('search');
				$this->unsetImported('smilies');
				$this->unsetImported('subscr');
				$this->unsetImported('polls');

				$this->deleteMappingArray('postID_mapping');
				$this->deleteMappingArray('boardID_mapping');
				$this->deleteMappingArray('categoryID_mapping');
				$this->deleteMappingArray('topicID_mapping');
				$this->deleteMappingArray('usergroupID_mapping');
				$this->deleteMappingArray('userID_mapping');
			}

			$this->retrieveMappingArray($this->postID_mapping,'postID_mapping');
			$this->retrieveMappingArray($this->boardID_mapping,'boardID_mapping');
			$this->retrieveMappingArray($this->categoryID_mapping,'categoryID_mapping');
			$this->retrieveMappingArray($this->topicID_mapping,'topicID_mapping');
			$this->retrieveMappingArray($this->usergroupID_mapping,'usergroupID_mapping');
			$this->retrieveMappingArray($this->userID_mapping,'userID_mapping');

			if(in_array('users/usergroups',$dataFields)) {
				$content .= '<div style="font-weight: bold;">'.$LANG->getLL('phpbb.step2.users/usergroups').'</div>';

				$res = $this->step4_importUsers();
				$content .= $this->step4_outputReport($res['rep']);
				$content .= $this->step4_outputErrors($res['err']);
			}

			if(in_array('boards/categories',$dataFields)) {
				$content .= '<div style="font-weight: bold;">'.$LANG->getLL('phpbb.step2.boards/categories').'</div>';

				$res = $this->step4_importBoards();
				$content .= $this->step4_outputReport($res['rep']);
				$content .= $this->step4_outputErrors($res['err']);
			}

			if(in_array('topics/posts',$dataFields)) {
				$content .= '<div style="font-weight: bold;">'.$LANG->getLL('phpbb.step2.topics/posts').'</div>';

				$res = $this->step4_importPosts();
				$content .= $this->step4_outputReport($res['rep']);
				$content .= $this->step4_outputErrors($res['err']);
			}

			if(in_array('pms',$dataFields)) {
				$content .= '<div style="font-weight: bold;">'.$LANG->getLL('phpbb.step2.pms').'</div>';

				$res = $this->step4_importPMs();
				$content .= $this->step4_outputReport($res['rep']);
				$content .= $this->step4_outputErrors($res['err']);
			}

            if(in_array('polls',$dataFields)) {
				$content .= '<div style="font-weight: bold;">'.$LANG->getLL('phpbb.step2.polls').'</div>';

				$res = $this->step4_importPolls();
				$content .= $this->step4_outputReport($res['rep']);
				$content .= $this->step4_outputErrors($res['err']);
            }

			if(in_array('search',$dataFields)) {
				$content .= '<div style="font-weight: bold;">'.$LANG->getLL('phpbb.step2.search').'</div>';

				$res = $this->step4_importSearch();
				$content .= $this->step4_outputReport($res['rep']);
				$content .= $this->step4_outputErrors($res['err']);
			}

			if(in_array('smilies',$dataFields)) {
				$content .= '<div style="font-weight: bold;">'.$LANG->getLL('phpbb.step2.smilies').'</div>';

				$res = $this->step4_importSmilies();
				$content .= $this->step4_outputReport($res['rep']);
				$content .= $this->step4_outputErrors($res['err']);
			}

			if(in_array('subscr',$dataFields)) {
				$content .= '<div style="font-weight: bold;">'.$LANG->getLL('phpbb.step2.subscr').'</div>';

				$res = $this->step4_importSubscriptions();
				$content .= $this->step4_outputReport($res['rep']);
				$content .= $this->step4_outputErrors($res['err']);
			}
		}
		else {

			if($this->data['step3.']['clearReallyAll'] || ($this->data['step3.']['clearReallyAll'] && in_array('users/usergroups',$this->data['step2.']['importdata']))) {
				$content .= '<div>'.$LANG->getLL('phpbb.step4.clearAllNote').'</div>';
				$content .= '<table><tr><td>'.$LANG->getLL('phpbb.step4.clear.userUID').'</td><td><input type="text" name="tx_mmforum_phpbb[step4.][clearAll][userUID]" value="" /> '.$LANG->getLL('phpbb.step4.clear.uidEx').'</td></tr>';
				$content .= '<tr><td>'.$LANG->getLL('phpbb.step4.clear.userWHERE').'</td><td><input type="text" name="tx_mmforum_phpbb[step4.][clearAll][userWHERE]" /> '.$LANG->getLL('phpbb.step4.clear.whereEx').'</td></tr>';
				$content .= '<tr><td>'.$LANG->getLL('phpbb.step4.clear.usergroupUID').'</td><td><input type="text" name="tx_mmforum_phpbb[step4.][clearAll][usergroupUID]" value="" /></td></tr>';
				$content .= '<tr><td>'.$LANG->getLL('phpbb.step4.clear.usergroupWHERE').'</td><td><input type="text" name="tx_mmforum_phpbb[step4.][clearAll][usergroupWHERE]" /></td></tr></table>';
			}

			$content .= $LANG->getLL('phpbb.step4.startNote');
			$content .= '<br /><br /><input type="submit" value="'.$LANG->getLL('phpbb.step4.startButton').'" name="tx_mmforum_phpbb[step4.][submit]" />';
		}

		$content .= '</fieldset>';

		return $content;
	}

	/**
	 * Displays a set of reports that is generated during a data import
	 * procedure.
	 *
	 * @param  array  $report An array of reports
	 * @return string         A string representation of the reports in $report
	 */
	function step4_outputReport($report) {
		$content = "";
		if(!is_array($report)) return "";
		foreach($report as $sReport) {
		 	$content .= '<div>'.$sReport.'</div>';
		}

		return $content;
	}

	/**
	 * Displays a set of error messages that is generated during a data
	 * import procedure.
	 *
	 * @param  array  $errors An array of errors
	 * @return string         A string representration of the errors in $errors
	 */
	function step4_outputErrors($errors) {
		$content = "";
		if(!is_array($errors)) return "";
		foreach($errors as $error) {
		 	$content .= '<div class="mm_forum-fatalerror">'.$error.'</div>';
		}
		return $content;
	}

	/**
	 * Imports boards and categories from phpBB tables.
	 * This function imports the category and board data stored in the phpBB tables
	 * prefix_categories and prefix_forums.
	 *
	 * @return  array  An array containing information on errors that occured during
	 *                 data import (key 'err') and casual reports made during data
	 *                 import (key 'rep').
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-24
	 */
	function step4_importBoards() {
		global $LANG;

		$err = array();
		$rep = array();

		$successCategories = 0;
		$successBoards     = 0;

		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_categories',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {
			$insertArray = array(
				'pid'			=> $this->data['step3.']['import0.']['pid'],
				'tstamp'		=> time(),
				'crdate'		=> time(),
				'forum_name'	=> $arr['cat_title'],
				'forum_order'	=> $arr['cat_order'],
                'sorting'       => $arr['cat_order'],
			);
			$iRes = @$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_forums',$insertArray);
			if(!$iRes) {
				$err[] = sprintf($LANG->getLL('phpbb.step4.errorCategoryImport'),$arr['cat_title']);
				continue;
			}

			$this->categoryID_mapping[$arr['cat_id']] = $GLOBALS['TYPO3_DB']->sql_insert_id();
			$successCategories ++;
		}
		$rep[] = sprintf($LANG->getLL('phpbb.step4.categoryimport_report'),$successCategories);

		$this->saveMappingArray($this->categoryID_mapping,'categoryID_mapping');

		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_forums',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {

			$r_read = array();
            $r_write = array();

            // Determine read rights
                if($arr['auth_read'] == 0) $r_read[] = '';
            elseif($arr['auth_read'] == 1) {
                $r_read[] = '{$usergroup}';
                $this->updateMapping['groups'][] = 'forum:{$usergroup}:grouprights_read/list';
            }
            elseif($arr['auth_read'] == 3) {
                $r_read[] = '{$modgroup}';
                $this->updateMapping['groups'][] = 'forum:{$modgroup}:grouprights_read/list';
            }
            elseif($arr['auth_read'] == 4) {
                $r_read[] = '{$admingroup}';
                $this->updateMapping['groups'][] = 'forum:{$admingroup}:grouprights_read/list';
            }
            elseif($arr['auth_read'] == 3) {
                $res = $this->dbObj->exec_SELECTquery(
                    '*',
                    $this->data['prefix'].'_auth_access',
                    'forum_id="'.$arr['forum_id'].'" AND auth_read=1'
                );
                while($arr = $this->dbObj->sql_fetch_assoc($res)) {
                    $r_read[] = $arr['group_id'];
                    $this->updateMapping['groups'][] = 'forum:'.$arr['group_id'].':grouprights_read/list';
                }
            }

            // Determine write rights
                if($arr['auth_reply'] == 0) $r_write[] = '';
            elseif($arr['auth_reply'] == 1) {
                $r_write[] = '{$usergroup}';
                $this->updateMapping['groups'][] = 'forum:{$usergroup}:grouprights_write/list';
            }
            elseif($arr['auth_read'] == 3) {
                $r_write[] = '{$modgroup}';
                $this->updateMapping['groups'][] = 'forum:{$modgroup}:grouprights_write/list';
            }
            elseif($arr['auth_read'] == 4) {
                $r_write[] = '{$admingroup}';
                $this->updateMapping['groups'][] = 'forum:{$admingroup}:grouprights_write/list';
            }
            elseif($arr['auth_read'] == 3) {
                $res = $this->dbObj->exec_SELECTquery(
                    '*',
                    $this->data['prefix'].'_auth_access',
                    'forum_id="'.$arr['forum_id'].'" AND auth_reply=1'
                );
                while($arr = $this->dbObj->sql_fetch_assoc($res)) {
                    $r_write[] = $arr['group_id'];
                    $this->updateMapping['groups'][] = 'forum:'.$arr['group_id'].':grouprights_write/list';
                }
            }

            foreach($r_write as $wGroup)
                if(intval($wGroup)>0) $rWGroup[] = $wGroup;
            foreach($r_read as $rGroup)
                if(intval($rGroup)>0) $rRGroup[] = $rGroup;

			$insertArray = array(
				'pid'			        => $this->data['step3.']['import0.']['pid'],
				'tstamp'		        => time(),
				'crdate'		        => time(),
				'deleted'		        => 0,
				'hidden'		        => 0,
				'forum_name'	        => $arr['forum_name'],
				'forum_desc'	        => $arr['forum_desc'],
				'forum_posts'	        => $arr['forum_posts'],
				'forum_last_post_id'    => $arr['forum_last_post_id'],
				'cat_id'		        => '',
				'forum_order'	        => $arr['forum_order'],
				'forum_topics'	        => $arr['forum_topics'],
				'sorting'		        => $arr['forum_order'],
				'parentID'		        => $this->categoryID_mapping[$arr['cat_id']],
                'grouprights_read'      => count($rRGroup)?implode(',',$rRGroup):'',
                'grouprights_write'     => count($rWGroup)?implode(',',$rWGroup):''
			);
			$iRes = @$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_forums',$insertArray);
			if(!$iRes) {
				$err[] = sprintf($LANG->getLL('phpbb.step4.errorBoardImport'),$arr['forum_name']);
				continue;
			}

			$newId = $GLOBALS['TYPO3_DB']->sql_insert_id();
			$this->boardID_mapping[$arr['forum_id']] = $newId;

			$this->updateMapping['posts'][] = "forum:".$arr['forum_last_post_id'].":forum_last_post_id";

			$successBoards ++;
		}
		$rep[] = sprintf($LANG->getLL('phpbb.step4.boardimport_report'),$successBoards);

		$this->saveMappingArray($this->boardID_mapping,'boardID_mapping');
		$this->setImported("boards_categories");

		return array('err'=>$err,'rep'=>$rep);
	}

	/**
	 * Imports users and user groups from phpBB tables.
	 * This function imports the user and usergroup data stored in the phpBB tables
	 * prefix_users, prefix_groups and prefix_user_group.
	 * Problematic is that there are differences in the way the phpBB and the mm_forum
	 * boards handle moderator and administrator authentifications. While in the phpBB
	 * board, the administrator/moderator status is defined by an extra field in the
	 * prefix_users table, in the mm_forum board this status is defined by the regarding
	 * user's membership in a special user group.
	 * To counter this difficulty, the user may choose in what way moderator and administrator
	 * rights are to be handled before data import. The import script can either create
	 * new groups in which the regarding users will be put or put these users into an
	 * already existing admin/mod group. Admin/mod rights can also be ignored totally.
	 *
	 * @return  array  An array containing information on errors that occured during
	 *                 data import (key 'err') and casual reports made during data
	 *                 import (key 'rep').
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-24
	 */
	function step4_importUsers() {
		global $LANG;

		$err = array();
		$rep = array();

		$successGroups = 0;
		$successUsers = 0;

		/*
		 * IMPORT USER GROUPS
		 */
		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_groups',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {
			if(!$this->data['step3.']['import1.']['importSingleUserGroups'] && $arr['group_single_user']) continue;

			$insertArray = array(
				'pid'			=> $this->data['step3.']['import1.']['pid'],
				'tstamp'		=> time(),
				'title'			=> $arr['group_name'],
				'hidden'		=> 0,
				'description'	=> $arr['group_description']
			);
			$iRes = @$GLOBALS['TYPO3_DB']->exec_INSERTquery('fe_groups',$insertArray);
			if(!$iRes) {
				$err[] = sprintf($LANG->getLL('phpbb.step4.errorUsergroupImport'),$arr['group_name']);
				continue;
			}

			$this->usergroupID_mapping[$arr['group_id']] = $GLOBALS['TYPO3_DB']->sql_insert_id();
			$successGroups ++;
		}
		$rep[] = sprintf($LANG->getLL('phpbb.step4.groupimport_report'),$successGroups);

		$this->saveMappingArray($this->usergroupID_mapping,'usergroupID_mapping');

		/*
		 * IMPORT USERS
		 */

		// Determine user, admin and moderator group UID
			$admingroup_uid     = 0;
			$moderatorgroup_uid = 0;

			if($this->data['step3.']['import1.']['admins'] == 'create') {
				$insertArray = array(
					'pid'			=> $this->data['step3.']['import1.']['pid'],
					'tstamp'		=> time(),
					'title'			=> $LANG->getLL('phpbb.step4.new_adminGroup')
				);
				$iRes = @$GLOBALS['TYPO3_DB']->exec_INSERTquery('fe_groups',$insertArray);
				if(!$iRes) {
					$err[] = $LANG->getLL('phpbb.step4.errorAdmingroupCreate');
					return array('err'=>$err,'rep'=>$rep);
				}

				$admingroup_uid = $GLOBALS['TYPO3_DB']->sql_insert_id();
                $this->usergroupID_mapping['{$admingroup}'] = $admingroup_uid;
			}
			elseif($this->data['step3.']['import1.']['admins'] == 'import') {
				$admingroup_uid = $this->data['step3.']['import1.']['admingroup'];
                $this->usergroupID_mapping['{$admingroup}'] = $admingroup_uid;
			}
            else $this->usergroupID_mapping['{$admingroup}'] = '';

			if($this->data['step3.']['import1.']['mods'] == 'create') {
				$insertArray = array(
					'pid'			=> $this->data['step3.']['import1.']['pid'],
					'tstamp'		=> time(),
					'title'			=> $LANG->getLL('phpbb.step4.new_modGroup')
				);
				$iRes = @$GLOBALS['TYPO3_DB']->exec_INSERTquery('fe_groups',$insertArray);
				if(!$iRes) {
					$err[] = $LANG->getLL('phpbb.step4.errorModgroupCreate');
					return array('err'=>$err,'rep'=>$rep);
				}

				$moderatorgroup_uid = $GLOBALS['TYPO3_DB']->sql_insert_id();
                $this->usergroupID_mapping['{$modgroup}'] = $moderatorgroup_uid;
			}
			elseif($this->data['step3.']['import1.']['mods'] == 'import') {
				$moderatorgroup_uid = $this->data['step3.']['import1.']['modgroup'];
                $this->usergroupID_mapping['{$modgroup}'] = $moderatorgroup_uid;
			}
            else $this->usergroupID_mapping['{$modgroup}'] = '';

			if($this->data['step3.']['import1.']['users'] == 'create') {
				$insertArray = array(
					'pid'			=> $this->data['step3.']['import1.']['pid'],
					'tstamp'		=> time(),
					'title'			=> $LANG->getLL('phpbb.step4.new_userGroup')
				);
				$iRes = @$GLOBALS['TYPO3_DB']->exec_INSERTquery('fe_groups',$insertArray);
				if(!$iRes) {
					$err[] = $LANG->getLL('phpbb.step4.errorUsergroupCreate');
					return array('err'=>$err,'rep'=>$rep);
				}

				$usergroup_uid = $GLOBALS['TYPO3_DB']->sql_insert_id();
                $this->usergroupID_mapping['{$usergroup}'] = $usergroup_uid;
			}
			elseif($this->data['step3.']['import1.']['users'] == 'import') {
				$usergroup_uid = $this->data['step3.']['import1.']['usergroup'];
                $this->usergroupID_mapping['{$usergroup}'] = $usergroup_uid;
			}
            else $this->usergroupID_mapping['{$usergroup}'] = '';

		// Load users
			$res = $this->dbObj->exec_SELECTquery(
				'*',
				$this->data['prefix'].'_users',
				'1'
			);
			while($arr = $this->dbObj->sql_fetch_assoc($res)) {
				$groups = array();

				// Determine user groups
					if(($arr['user_level'] == 2) && ($this->data['step3.']['import1.']['mods'] != 'nothing'))
						$groups[] = $moderatorgroup_uid;
					if(($arr['user_level'] == 1) && ($this->data['step3.']['import1.']['mods'] != 'nothing'))
						$groups[] = $admingroup_uid;
					if($this->data['step3.']['import1.']['mods'] != 'nothing')
						$groups[] = $usergroup_uid;

					$res2 = $this->dbObj->exec_SELECTquery(
						'*',
						$this->data['prefix'].'_user_group',
						'user_id="'.$arr['user_id'].'" AND user_pending=0'
					);
					while($user_group = $this->dbObj->sql_fetch_assoc($res2)) {
						if($this->usergroupID_mapping[$user_group['group_id']])
							$groups[] = $this->usergroupID_mapping[$user_group['group_id']];
					}
					$usergroup_string = implode(',',$groups);

				// Determine avatar
					if($arr['user_avatar_type']=='1') {
						$filename_total = $arr['user_avatar'];
						$filename_base  = basename($filename_total);
						$filename_new	= $this->data['step3.']['import1.']['avatarpath'].$filename_base;

						if(file_exists($filename_total)) {
							copy($filename_total,$filename_new);
							$avatar = $filename_base;
						}
					}

				$insertArray = array(
					'pid'				=> $this->data['step3.']['import1.']['pid'],
					'tstamp'			=> time(),
					'username'			=> $arr['username'],
					'password'			=> $arr['user_password'],
					'disable'			=> $arr['user_active']?'0':'1',
					'email'				=> $arr['user_email'],
					'usergroup'			=> $usergroup_string,
					'starttime'			=> 0,
					'endtime'			=> 0,
					'crdate'			=> $arr['user_regdate'],
					'cruser_id'			=> '',						// Insert current BE user UID?
					'deleted'			=> 0,
					'www'				=> $arr['user_website'],
					'lastlogin'			=> $arr['user_last_login_try'],
					'tx_mmforum_avatar'	=> $avatar,
					'tx_mmforum_icq'	=> $arr['user_icq'],
					'tx_mmforum_aim'	=> $arr['user_aim'],
					'tx_mmforum_yim'	=> $arr['user_yim'],
					'tx_mmforum_md5'	=> $arr['user_password'],
					'tx_mmforum_posts'	=> $arr['user_posts'],
					'tx_mmforum_msn'	=> $arr['user_msnm'],
					'tx_mmforum_user_sig' => $arr['user_sig'],
					'tx_mmforum_prelogin' => '',
					'tx_mmforum_interests' => $arr['user_interests'],
					'tx_mmforum_occ'    => $arr['user_occ'],
					'tx_mmforum_skype'	=> ''
				);
				$iRes = $GLOBALS['TYPO3_DB']->exec_INSERTquery('fe_users',$insertArray);
				if(!$iRes) {
					$err[] = sprintf($LANG->getLL('phpbb.step4.errorUserImport'),$arr['username']);
					continue;
				}

				$this->userID_mapping[$arr['user_id']] = $GLOBALS['TYPO3_DB']->sql_insert_id();
				$successUsers ++;
			}
			$rep[] = sprintf($LANG->getLL('phpbb.step4.userimport_report'),$successUsers);

        if(is_array($this->updateMapping['groups'])) {
            foreach($this->updateMapping['groups'] as $updateMapping_groups) {
			    $data = t3lib_div::trimExplode(':',$updateMapping_groups);

			    if($data[0] == 'forum') {
                    if(substr($data[2],-5,5)=='/list') {
                        $data[2] = substr($data[2],0,strlen($data[2])-5);
                        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                            $data[2].', uid',
                            'tx_mmforum_forums',
                            'FIND_IN_SET('.$data[1].','.$data[2].')'
                        );
                        while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                            $sList = $arr[$data[1]];
                            $uid = $arr['uid'];
                            $list = t3lib_div::trimExplode($sList);

                            foreach($list as $listItem) {
                                if($listItem == $data[1]) {
                                    $updatedList[] = $this->usergroupID_mapping[$data[1]]; continue;
                                }
                                $updatedList[] = $listItem;
                            }
                            $sUpdatedList = implode(',',$updatedList);
                            $updateArray = array($data[2] => $sUpdatedList);
                            $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_forums','uid="'.$uid.'"',$updateArray);
                        }
                    }
                    else {
				        $updateArray = array($data[2] => $this->usergroupID_mapping[$data[1]]);
				        $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_forums',$data[2].'="'.$data[1].'"',$updateArray);
                    }
			    }
		    }
        }

		$this->saveMappingArray($this->userID_mapping,'userID_mapping');
		$this->setImported("users_usergroups");

		return array('err'=>$err,'rep'=>$rep);
	}

	/**
	 * Imports posts and topics from phpBB tables.
	 * This function imports the post and topic data stored in the phpBB tables
	 * prefix_topics, prefix_posts and prefix_posts_text.
	 *
	 * @return  array  An array containing information on errors that occured during
	 *                 data import (key 'err') and casual reports made during data
	 *                 import (key 'rep').
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-15
	 */
	function step4_importPosts() {
		global $LANG;

		$err = array();
		$rep = array();

		$successTopics = 0;
		$successPosts = 0;

		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_topics',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {
			if($arr['topic_status']==2) continue;
			$insertArray = array(
				'pid'					=> $this->data['step3.']['import2.']['pid'],
				'cruser_id'				=> $this->userID_mapping[$arr['topic_poster']],
				'tstamp'				=> time(),
				'crdate'				=> time(),
				'deleted'				=> 0,
				'hidden'				=> 0,
				'topic_title'			=> $arr['topic_title'],
				'topic_poster'			=> $this->userID_mapping[$arr['topic_poster']],
				'topic_time'			=> $arr['topic_time'],
				'topic_views'			=> $arr['topic_views'],
				'topic_replies'			=> $arr['topic_replies'],
				'topic_last_post_id'	=> $arr['topic_last_post_id'],
				'forum_id'				=> $this->boardID_mapping[$arr['forum_id']],
				'topic_first_post_id'	=> $arr['topic_first_post_id'],
				'topic_is'				=> '',
				'solved'				=> '',
				'at_top_flag'			=> ($arr['topic_type']>0)?1:0,
				'closed_flag'			=> ($arr['topic_status']>0)?1:0
			);
			$iRes = @$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_topics',$insertArray);
			if(!$iRes) {
				$err[] = sprintf($LANG->getLL('phpbb.step4.errorTopicImport'),$arr['topic_title']);
				continue;
			}

			$newId = $GLOBALS['TYPO3_DB']->sql_insert_id();
			$this->topicID_mapping[$arr['topic_id']] = $newId;
			$this->updateMapping['posts'][] = "topic:".$arr['topic_last_post_id'].":topic_last_post_id";
			$this->updateMapping['posts'][] = "topic:".$arr['topic_first_post_id'].":topic_first_post_id";
			$successTopics ++;
		}
		$rep[] = sprintf($LANG->getLL('phpbb.step4.topicimport_report'),$successTopics);



		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_posts',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {
			$insertArray = array(
				'pid'					=> $this->data['step3.']['import2.']['pid'],
				'tstamp'				=> time(),
				'crdate'				=> time(),
				'cruser_id'				=> $this->userID_mapping[$arr['poster_id']],
				'topic_id'				=> $this->topicID_mapping[$arr['topic_id']],
				'forum_id'				=> $this->boardID_mapping[$arr['forum_id']],
				'poster_id'				=> $this->userID_mapping[$arr['poster_id']],
				'post_time'				=> $arr['post_time'],
				'poster_ip'				=> $arr['poster_ip'],
				'edit_time'				=> $arr['post_edit_time'],
				'edit_count'			=> $arr['post_edit_count'],
			);
			$iRes = @$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_posts',$insertArray);
            $post_id = $GLOBALS['TYPO3_DB']->sql_insert_id();
			if(!$iRes) {
				$err[] = sprintf($LANG->getLL('phpbb.step4.errorPostImport'),$arr['post_id']);
				continue;
			}

			$this->postID_mapping[$arr['post_id']] = $post_id;
			$successPosts ++;
		}
		$rep[] = sprintf($LANG->getLL('phpbb.step4.postimport_report'),$successPosts);

		$this->saveMappingArray($this->postID_mapping,'postID_mapping');

		foreach($this->updateMapping['posts'] as $updateMapping_post) {
			$data = t3lib_div::trimExplode(':',$updateMapping_post);

			if($data[0] == 'forum') {
				$updateArray = array($data[2] => $this->postID_mapping[$data[1]]);
				$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_forums',$data[2].'="'.$data[1].'"',$updateArray);
			}
			elseif($data[0] == 'topic') {
				$updateArray = array($data[2] => $this->postID_mapping[$data[1]]);
				$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics',$data[2].'="'.$data[1].'"',$updateArray);
			}
		}

		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_posts_text',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {
			$insertArray = array(
				'pid'					=> $this->data['step3.']['import2.']['pid'],
				'tstamp'				=> time(),
				'crdate'				=> time(),
				'post_id'				=> $this->postID_mapping[$arr['post_id']],
				'post_text'				=> $this->step4_importPosts_convBB($arr['post_text']),
			);
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_posts_text',$insertArray);
		}

		$this->setImported("topics_posts");

		return array('err'=>$err,'rep'=>$rep);
	}

	/**
	 * Converts phpBB BBcodes into mm_forum BBcodes.
	 * phpBB BBcodes use a ten-digit hexcode identification for each used
	 * BBcode. This code is usually not parsed by the mm_forum extension,
	 * so this hexcode has to be removed.
	 * @param   string $text The original phpBB post text
	 * @return  string       The updated post text
	 * @version 2007-04-03
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 */
	function step4_importPosts_convBB($text) {
		$text = preg_replace("/\[b:[a-z0-9]{10}\]/","[b]",$text);
		$text = preg_replace("/\[i:[a-z0-9]{10}\]/","[i]",$text);
		$text = preg_replace("/\[u:[a-z0-9]{10}\]/","[u]",$text);
		$text = preg_replace("/\[color=([a-z]*?):[a-z0-9]{10}\]/","[color=\\1]",$text);
		$text = preg_replace("/\[size=([0-9]*?):[a-z0-9]{10}\]/","[size=\\1]",$text);
		$text = preg_replace("/\[quote:[a-z0-9]{10}\]/","[quote]",$text);
		$text = preg_replace("/\[quote=(.*?):[a-z0-9]{10}\]/","[quote=\\1]",$text);
		$text = preg_replace("/\[code:[a-z0-9]{10}\]/","[code]",$text);
		$text = preg_replace("/\[list:[a-z0-9]{10}\]/","[list]",$text);
		$text = preg_replace("/\[img:[a-z0-9]{10}\]/","[img]",$text);

		return $text;
	}

	/**
	 * Imports private messages from phpBB tables.
	 * This function imports private messages from the phpBB tables
	 * prefix_privmsgs and prefix_privmsgs_text.
	 *
	 * @return  array  An array containing information on errors that occured during
	 *                 data import (key 'err') and casual reports made during data
	 *                 import (key 'rep').
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-22
	 */
	function step4_importPMs() {
		global $LANG;

		$err = array();
		$rep = array();

		$successPMs = 0;

		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_privmsgs',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {

			$res2 = $this->dbObj->exec_SELECTquery(
				'*',
				$this->data['prefix'].'_privmsgs_text',
				'privmsgs_text_id = "'.$arr['privmsgs_id'].'"'
			);
			$tdata = $this->dbObj->sql_fetch_assoc($res2);

			switch($arr['privmsgs_type']) {
				case 0: $read = '1'; $messType = '0'; break;
				case 1: $read = '';  $messType = '1'; break;
				case 3: $read = '1'; $messType = '2'; break;
				case 4: $read = '1'; $messType = '2'; break;
				case 5: $read = '0'; $messType = '0'; break;
			}
			$insertArray = array(
				'pid'					=> $this->data['step3.']['import3.']['pid'],
				'tstamp'				=> time(),
				'crdate'				=> time(),
				'deleted'				=> 0,
				'hidden'				=> 0,
				'cruser_id'				=> $this->userID_mapping[$arr['privmsgs_from_userid']],
				'sendtime'				=> $arr['privmsgs_date'],
				'from_uid'				=> $this->userID_mapping[$arr['privmsgs_from_userid']],
				'from_name'				=> '',
				'to_uid'				=> $this->userID_mapping[$arr['privmsgs_to_userid']],
				'to_name'				=> '',
				'subject'				=> $arr['privmsgs_subject'],
				'message'				=> $tdata['privmsgs_text'],
				'read_flg'				=> $read,
				'mess_type'				=> $messType
			);
			$iRes = @$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_pminbox',$insertArray);
			if(!$iRes) {
				$err[] = sprintf($LANG->getLL('phpbb.step4.errorPMImport'),$arr['privmsgs_id']);
				continue;
			}
			$successPMs ++;
		}
		$rep[] = sprintf($LANG->getLL('phpbb.step4.pmimport_report'),$successPMs);
		$this->setImported("pms");

		return array('err'=>$err,'rep'=>$rep);
	}

	/**
	 * Imports the search index from phpBB tables.
	 * This function imports the entire index of the phpBB search from
	 * the phpBB tables prefix_search_results, prefix_wordlist and
	 * prefix_wordmatch.
	 *
	 * @return  array  An array containing information on errors that occured during
	 *                 data import (key 'err') and casual reports made during data
	 *                 import (key 'rep').
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-22
	 */
	function step4_importSearch() {
		global $LANG;

		$wordsSuccess = 0;
		$resultsSuccess = 0;
		$matchesSuccess = 0;

		$err = array();
		$rep = array();

		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_search_results',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {
			$searchData = unserialize($arr['search_array']);

			if($arr['sort_dir'] == 'DESC') $searchOrder = 2;
			else $searchOrder = 1;

			if(!is_array($searchData['split_search'])) $searchData['split_search'] = array($searchData['split_search']);
			if(!is_array($searchData['search_results'])) $searchData['search_results'] = t3lib_div::trimExplode(',',$searchData['search_results']);

			$insertArray = array(
				'pid'					=> $this->data['step3.']['import4.']['pid'],
				'tstamp'				=> time(),
				'crdate'				=> time(),
				'cruser_id'				=> '',
				'deleted'				=> 0,
				'hidden'				=> 0,
				'search_string'			=> implode(' ',$searchData['split_search']),
				'search_place'			=> 0,
				'solved'				=> 0,
				'search_order'			=> $searchOrder,
				'array_string'			=> serialize(array_flip($searchData['search_results'])),
				'groupPost'				=> $this->data['step3.']['import4.']['group_post']
			);
			$iRes = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_searchresults',$insertArray);
			if($iRes) $resultsSuccess ++;
		}
		$rep[] = sprintf($LANG->getLL('phpbb.step4.searchresultsimport_report'),$resultsSuccess);

		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_search_wordlist',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {
			$insertArray = array(
				'pid'					=> $this->data['step3.']['import4.']['pid'],
				'tstamp'				=> time(),
				'crdate'				=> time(),
				'cruser_id'				=> '',
				'deleted'				=> 0,
				'hidden'				=> 0,
				'word'					=> $arr['word_text'],
				'metaphone'				=> metaphone($arr['word_text'])
			);
			$iRes = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_wordlist',$insertArray);
			if($iRes) $wordsSuccess ++;
			$newId = $GLOBALS['TYPO3_DB']->sql_insert_id();

			$this->searchword_mapping[$arr['word_id']] = $newId;
		}
		$rep[] = sprintf($LANG->getLL('phpbb.step4.wordlistimport_report'),$wordsSuccess);

		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_search_wordmatch',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {
			$res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',
				'tx_mmforum_posts',
				'uid="'.$this->postID_mapping[$arr['post_id']].'"'
			);
			$postData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res2);

			$res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',
				'tx_mmforum_topics',
				'uid="'.$postData['topic_id'].'"'
			);
			$topicData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res2);

			$insertArray = array(
				'pid'					=> $this->data['step3.']['import4.']['pid'],
				'tstamp'				=> time(),
				'crdate'				=> time(),
				'cruser_id'				=> '',
				'deleted'				=> 0,
				'hidden'				=> 0,
				'word_id'				=> $this->searchword_mapping[$arr['word_id']],
				'post_id'				=> $this->postID_mapping[$arr['post_id']],
				'is_header'				=> $arr['title_match'],
				'topic_id'				=> $topicData['uid'],
				'forum_id'				=> $topicData['forum_id'],
				'solved'				=> $topicData['solved'],
				'topic_title'			=> $topicData['topic_title'],
				'topic_views'			=> $topicData['topic_views'],
				'topic_replies'			=> $topicData['topic_replies'],
				'post_crdate'			=> $postData['post_time'],
				'post_cruser'			=> $postData['poster_id']
			);
			$iRes = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_wordmatch',$insertArray);
			if($iRes) $matchesSuccess ++;
		}
		$rep[] = sprintf($LANG->getLL('phpbb.step4.matchesimport_report'),$matchesSuccess);
		$this->setImported("search");

		return array('err'=>$err,'rep'=>$rep);
	}

	/**
	 * Imports smilies from phpBB tables.
	 * This function imports all smilies from the phpBB table
	 * prefix_smilies.
	 * Since the absolute paths to the smilie files are neither stored
	 * in the phpBB table nor in the mm_forum table, these paths have to
	 * be specified before the import procedure in step 3.
	 * This function also copies the regarding smilie files into the
	 * respective mm_forum directory.
	 *
	 * @return  array  An array containing information on errors that occured during
	 *                 data import (key 'err') and casual reports made during data
	 *                 import (key 'rep').
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-22
	 */
	function step4_importSmilies() {
		global $LANG;

		$err = array();
		$rep = array();

		$phpbb_smiliedir   = $this->data['step3.']['import5.']['phpbb_smilie_url'];
		$mmforum_smiliedir = $this->data['step3.']['import5.']['mmforum_smilie_url'];

		$smilieImport_count = 0;

		if($phpbb_smiliedir{strlen($phpbb_smiliedir)-1} != "/") $phpbb_smiliedir = "$phpbb_smiliedir/";
		if($mmforum_smiliedir{strlen($mmforum_smiliedir)-1} != "/") $mmforum_smiliedir = "$mmforum_smiliedir/";

		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_smilies',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {
			$insertArray = array(
				'pid'					=> $this->data['step3.']['import5.']['pid'],
				'tstamp'				=> time(),
				'crdate'				=> time(),
				'cruser_id'				=> '',
				'deleted'				=> 0,
				'hidden'				=> 0,
				'code'					=> $arr['code'],
				'smile_url'				=> $arr['smile_url'],
				'emoticon'				=> $arr['emoticon']
			);
			copy($phpbb_smiliedir.$arr['smile_url'],$mmforum_smiliedir.$arr['smile_url']);
			$iRes = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_smilies',$insertArray);

			if($iRes) $smilieImport_count ++;
		}
		$rep[] = sprintf($LANG->getLL('phpbb.step4.smilieimport_report'),$smilieImport_count);
		$this->setImported("smilies");

		return array('err'=>$err,'rep'=>$rep);
	}

	/**
	 * Import email subscriptions from phpBB tables.
	 * This function imports email subscriptions informing users about new
	 * replies in certain topics from the phpBB table prefix_topics_watch.
	 *
	 * @return  array  An array containing information on errors that occured during
	 *                 data import (key 'err') and casual reports made during data
	 *                 import (key 'rep').
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-22
	 */
	function step4_importSubscriptions() {
		global $LANG;
		$subscrImport_count = 0;

		$err = array();
		$rep = array();

		$res = $this->dbObj->exec_SELECTquery(
			'*',
			$this->data['prefix'].'_topics_watch',
			'1'
		);
		while($arr = $this->dbObj->sql_fetch_assoc($res)) {
			$insertArray = array(
				'pid'					=> $this->data['step3.']['import6.']['pid'],
				'tstamp'				=> time(),
				'crdate'				=> time(),
				'cruser_id'				=> '',
				'deleted'				=> 0,
				'hidden'				=> 0,
				'user_id'				=> $this->userID_mapping[$arr['user_id']],
				'topic_id'				=> $this->topicID_mapping[$arr['topic_id']]
			);
			$iRes = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_topicmail',$insertArray);
			if($iRes) $subscrImport_count ++;
		}
		$rep[] = sprintf($LANG->getLL('phpbb.step4.subscrimport_report'),$subscrImport_count);
		$this->setImported("subscr");

		return array('err'=>$err,'rep'=>$rep);
	}

    /**
     * Import polls from phpBB tables.
     * This function imports polls from the phpBB tables vote_desc, vote_results
     * and vote_voters.
	 *
	 * @return  array  An array containing information on errors that occured during
	 *                 data import (key 'err') and casual reports made during data
	 *                 import (key 'rep').
	 * @author  Martin Helmich <m.helmich@mittwald.de>
     */
    function step4_importPolls() {
        global $LANG;

		$err = array();
		$rep = array();

        $pollImport_count = 0;
        $res = $this->dbObj->exec_SELECTquery(
            '*',
            $this->data['prefix'].'_vote_desc',
            '1'
        );
        while($poll = $this->dbObj->sql_fetch_assoc($res)) {
            $insertArray = array(
                'pid'                   => $this->data['step3.']['polls.']['pid'],
                'tstamp'                => time(),
                'crdate'                => time(),
                'cruser_id'             => $GLOBALS['BE_USER']->user['uid'],
                'deleted'               => 0,
                'hidden'                => 0,
                'question'              => $poll['vote_text'],
                'endtime'               => $poll['vote_start'] + $poll['vote_length']
            );
            $topic_id = $this->topicID_mapping[$poll['topic_id']];
            $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_polls',$insertArray);
            $poll_id  = $GLOBALS['TYPO3_DB']->sql_insert_id();
            $poll_votes = 0;
            $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics','uid='.$topic_id,array('poll_id'=>$poll_id));

            $res2 = $this->dbObj->exec_SELECTquery(
                '*',
                $this->data['prefix'].'_vote_results',
                'vote_id='.$poll['vote_id']
            );
            while($answer = $this->dbObj->sql_fetch_assoc($res2)) {
                $insertArray = array(
                    'pid'               => $this->data['step3.']['polls.']['pid'],
                    'tstamp'            => time(),
                    'crdate'            => time(),
                    'deleted'           => 0,
                    'hidden'            => 0,
                    'answer'            => $answer['vote_option_text'],
                    'votes'             => $answer['vote_result'],
                    'poll_id'           => $poll_id
                );
                $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_polls_answers', $insertArray);
                $poll_votes += $answer['vote_result'];
                $answer_id = $GLOBALS['TYPO3_DB']->sql_insert_id();
            }

            $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_polls','uid='.$poll_id,array('votes'=>$poll_votes));

            $res3 = $this->dbObj->exec_SELECTquery(
                '*',
                $this->data['prefix'].'_vote_voters',
                'vote_id='.$poll['vote_id']
            );
            while($voter = $this->dbObj->sql_fetch_assoc($res3)) {
                $insertArray = array(
                    'pid'               => $this->data['step3.']['polls.']['pid'],
                    'tstamp'            => time(),
                    'crdate'            => time(),
                    'deleted'           => 0,
                    'hidden'            => 0,
                    'poll_id'           => $poll_id,
                    'user_id'           => $this->userID_mapping[$voter['vote_user_id']],
                );
                $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_polls_votes', $insertArray);
            }
            $pollImport_count ++;
        }
		$rep[] = sprintf($LANG->getLL('phpbb.step4.pollimport_report'),$pollImport_count);
		$this->setImported("polls");

		return array('err'=>$err,'rep'=>$rep);
    }

    /**
     * Data storage functions
     */

	/**
	 * Loads a stored mapping array from a temporary file.
	 *
	 * Mapping arrays are necessary in order to keep references between
	 * different records valid, since all records get new primary keys
	 * during the import procedure while there remain references to the
	 * old primary keys.
	 *
	 * @param array  &$array   The array, into which the data is to be loaded.
	 * @param string $filename The filename from which the array is to be loaded.
	 */
	function retrieveMappingArray(&$array, $filename) {
		if(!file_exists("tmp.$filename")) return;

		$string = file_get_contents("tmp.$filename");
		$array = unserialize($string);
	}

	/**
	 * Stores a mapping array to a temporary file.
	 *
	 * Mapping arrays are necessary in order to keep references between
	 * different records valid, since all records get new primary keys
	 * during the import procedure while there remain references to the
	 * old primary keys.
	 *
	 * @param array  &$array   The array, in which the data is stored
	 * @param string $filename The filename into which the data is to be stored
	 */
	function saveMappingArray($array, $filename) {
		$arrayS = serialize($array);

		$file = fopen("tmp.$filename","w");
		fwrite($file,$arrayS);
		fclose($file);
	}

	/**
	 * Deletes a temporary file storing a mapping array.
	 *
	 * Mapping arrays are necessary in order to keep references between
	 * different records valid, since all records get new primary keys
	 * during the import procedure while there remain references to the
	 * old primary keys.
	 *
	 * @param string $filename The filename in which the array is stored.
	 */
	function deleteMappingArray($filename) {
		if(file_exists("tmp.$filename"))
			unlink("tmp.$filename");
	}

	/**
	 * Determines if a data field has already been imported.
	 *
	 * @param  string  $field The data field that is to be checked.
	 * @return boolean        TRUE, if the data field has already been imported, otherwise FALSE
	 */
	function getImported($field) {
		$field = str_replace("/","_",$field);
		return file_exists("tmp.$field");
	}

	/**
	 * Sets a data field to "imported".
	 *
	 * @param string $field The data field that is to be set to "imported".
	 */
	function setImported($field) {
		$field = str_replace("/","_",$field);
		$file = fopen("tmp.$field","w");
		fclose($file);
	}

	/**
	 * Sets a data field to "not imported"
	 * @param string $field The data field that is to be set to "not imported"
	 */
	function unsetImported($field) {
		$field = str_replace("/","_",$field);
		if(file_exists("tmp.$field"))
			unlink("tmp.$field");
	}

    /**
     * Miscellaneous functions
     */

	/**
	 * Checks if a table exists in the default database.
	 * This function checks if a certain table specified by a parameter already
	 * exists in the default TYPO3 database.
	 * To speed up this process, the list of tables in the database will be
	 * retrieved only once (see getAllTables).
	 *
	 * @param   string  $tablename The table that is to be checked
	 * @return  boolean            TRUE, if the table specified by the parameter $tablename
	 *                             exists in the default TYPO3 database, otherwise FALSE.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-08
	 * @uses    getAllTables
	 */
	function checkTable($tablename) {
		if(!isset($this->allTables)) $this->getAllTables();
		return in_array($tablename,$this->allTables);
	}

	/**
	 * Retrieves a list of all tables in the default TYPO3 database.
	 * This function retrieves a full list of all tables that currently exist
	 * in the default TYPO3 database.
	 *
	 * @return  void
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-08
	 */
	function getAllTables() {
		$res = $this->dbObj->sql_query("SHOW TABLES");
		while(list($tablename)=$this->dbObj->sql_fetch_row($res)) {
			$this->allTables[] = $tablename;
		}
	}


	/**
	 * Outputs the current internal data array for GP-parameter transmission.
	 * To transfer all internal data used in one step of data import to the
	 * next step, this function will output the complete internal data array
	 * as a set of hidden <INPUT> fields.
	 *
	 * @return  string The set of hidden fields containing the internal data array.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-08
	 * @uses    outputData_recursiveArray
	 */
	function outputData() {
		$content = $this->outputData_recursiveArray($this->data,'');

		return $content;
	}

	/**
	 * Helper function for internal data array output.
	 * This function is a helper function for the output of the internal data array
	 * for GP-parameter transmission. Since this function works recursively,
	 * it can handle arrays of any dimension.
	 *
	 * @param   array  $arr  The array that is to be output
	 * @param   string $path The path to the current level of output. Used by recursive calls.
	 * @return  string       The array submitted via $arr as a set of <INPUT> fields
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-03-08
	 */
	function outputData_recursiveArray($arr,$path) {
		if(count($arr)==0) return "";
		foreach($arr as $key=>$value) {
			if(!is_array($value)) {
				$content .= '<input type="hidden" value="'.$value.'" name="tx_mmforum_phpbb'.$path.'['.$key.']" />'."\r\n";
			}
			else $content .= $this->outputData_recursiveArray($value,$path.'['.$key.']');
		}
		return $content;
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/mod1/class.tx_mmforum_phpbbimport.php"])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/includes/class.tx_mmforum_phpbbimport.php"]);
}
?>