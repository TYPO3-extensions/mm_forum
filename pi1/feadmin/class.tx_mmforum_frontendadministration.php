<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General Public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General Public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General Public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */

Require_Once ( t3lib_extMgm::extPath('mm_forum').'pi1/feadmin/class.tx_mmforum_frontendadministration_validator.php' );

/**
 *
 * Controller class for the frontend administration plugin. This module offers
 * functionality for editing categories and forums from the TYPO3 frontend as
 * alternative to the backend module.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2010 Martin Helmich, Mittwald CM Service GmbH & Co KG
 * @package    mm_forum
 * @subpackage Frontend_Administration
 * @version    $Id$
 *
 */
class tx_mmforum_FrontendAdministration {

	/**
	 * A reference to the parent object, usually an instance of the mm_forum_pi1
	 * class.
	 * @var tx_mmforum_pi1
	 */
	var $p;

	
	/**
	 * A flashmessage that will be displayed over the list view, after a forum was
	 * saved, deleted, ...
	 * @var String
	 */
	var $flashmessage = NULL;

	
	/*
	 * ACTION METHODS
	 */

	
	/**
	 *
	 * The main dispatcher method. This method initializes the controller and
	 * evaluates the GET-/POST-Parameters in order to call the appropriate action
	 * method to handle the request.
	 *
	 * @access public
	 * @param  Array           $conf   The configuration array for this controller
	 * @param  tx_mmforum_base $parent The parent object
	 * @return String                  HTML content
	 *
	 */
	function main($conf, tx_mmforum_base $parent) {
		$this->initFromParent($conf, $parent);

		if ($this->v['flashmessage']) $this->flashmessage = base64_decode($this->v['flashmessage']);

		if ($this->v['editForum'])   $actionMethod = 'edit';
		elseif ($this->v['newForum'])    $actionMethod = 'edit';
		elseif ($this->v['setACLs'])     $actionMethod = 'acl';
		else                            $actionMethod = 'list';

		$actionMethod = "{$actionMethod}Action";
		$content = $this->$actionMethod();

		return $content;
	}



	/**
	 *
	 * Presents a list of all available categories and forums. Also handles simple
	 * operations like ordering or deleting forums.
	 *
	 * @access private
	 * @return String HTML content
	 *
	 */
	function listAction() {
		global $TYPO3_DB;

		# Handle some basic operations like deleting or sorting forums.
		if ( $this->v['moveUp']      ) $this->moveForumUp   ( $this->v['moveUp']      );
		if ( $this->v['moveDown']    ) $this->moveForumDown ( $this->v['moveDown']    );
		if ( $this->v['removeForum'] ) $this->deleteForum   ( $this->v['removeForum'] );

		# Load templates.
		$template         = $this->cObj->fileResource($this->conf['templates.']['list']);
		$categoryTemplate = $this->cObj->getSubpart($template, '###ROW_CATEGORY###');
		$forumTemplate    = $this->cObj->getSubpart($template, '###ROW_FORUM###');

		# Load all categories from the database.
		# NOTE: The "hidden" flag is NOT queried on purpose!
		$categoryHandle = $TYPO3_DB->exec_SELECTquery ( '*', 'tx_mmforum_forums',
			'parentID=0 AND deleted=0 '.$this->p->getStoragePIDQuery(),
			'', 'sorting ASC' );
		$categoryContent = '';
		$categoryCount = $TYPO3_DB->sql_num_rows($categoryHandle); $i = 1;
		While($categoryArray = $TYPO3_DB->sql_fetch_assoc($categoryHandle)) {

			$localCategoryTemplate = $categoryTemplate;
			$categoryMarkers = Array ( '###CATEGORY_ICON###'    => $this->p->getForumIcon($categoryArray, FALSE, FALSE),
				'###CATEGORY_NAME###'    => $this->validator->specialChars($categoryArray['forum_name']),
				'###CATEGORY_DESC###'    => $this->validator->specialChars($categoryArray['forum_desc']),
				'###CATEGORY_OPTIONS###' => $this->getForumOptions($categoryArray, $i == 1, $i == $categoryCount) );

			# Load all subforums for the current category from the database.
			# NOTE: The "hidden" flag is NOT queried on purpose!
			$forumHandle = $TYPO3_DB->exec_SELECTquery ( '*', 'tx_mmforum_forums',
				'parentID='.$categoryArray['uid'].' AND deleted=0 '.$this->p->getStoragePIDQuery(),
				'', 'sorting ASC' );
			$forumContent = '';
			$forumCount = $TYPO3_DB->sql_num_rows($forumHandle); $j = 1;
			While($forumArray = $TYPO3_DB->sql_fetch_assoc($forumHandle)) {

				$forumMarkers = Array ( '###FORUM_ICON###'    => $this->p->getForumIcon($forumArray, FALSE, FALSE),
					'###FORUM_NAME###'    => $this->validator->specialChars($forumArray['forum_name']),
					'###FORUM_DESC###'    => $this->validator->specialChars($forumArray['forum_desc']),
					'###FORUM_OPTIONS###' => $this->getForumOptions($forumArray, $j == 1, $j == $forumCount) );
				$forumContent .= $this->cObj->substituteMarkerArray($forumTemplate, $forumMarkers);
				$j ++;
			} # End forum loop

			$localCategoryTemplate = $this->cObj->substituteSubpart($localCategoryTemplate, '###ROW_FORUM###', $forumContent);
			$categoryContent .= $this->cObj->substituteMarkerArray($localCategoryTemplate, $categoryMarkers);

			$i ++;
		} # End category loop
		$template = $this->cObj->substituteSubpart($template, '###ROW_CATEGORY###', $categoryContent);

		$marker = array( '###NEW_CATEGORY_OPTIONS###' => $this->getOptionImage('newctg', !$this->checkActionAllowance('category','create')) );

		# Display a flashmessage if one was set.
		if ($this->flashmessage) $marker['###FLASHMESSAGE###'] = htmlspecialchars($this->flashmessage);
		else $template = $this->cObj->substituteSubpart($template, '###FLASHMESSAGE_BOX###', '');

		$template = $this->cObj->substituteMarkerArray($template, $marker);
		$template = preg_replace_callback('/###L:([A-Z_-]+)###/i', array($this,'replaceLL'), $template);

		return $template;
	}



	/**
	 *
	 * Displays a form for editing a forum's access control lists (ACLs).
	 * In this form, the administrator can grant or deny read, write and moderation
	 * access to every frontend group.
	 *
	 * @access private
	 * @return HTML content
	 *
	 */
	function aclAction() {
		$forumUid  = intval($this->v['setACLs']);
		$forumData = $this->p->getBoardData($forumUid);

		if (!$this->checkActionAllowance($forumData['parentID'] == 0 ? 'category' : 'forum', 'acl'))
			return $this->displayNoAccessError();

		if ( $this->v['acl_save'] )    $this->saveAclAction();
		elseif ( $this->v['acl_cancel'] )  $this->redirectToAction(array());

		$template = $this->cObj->fileResource($this->conf['templates.']['acl']);
		$groupTemplate = $this->cObj->getSubpart($template, '###GROUP_ROW###');

		# Very ugly. Why does "getParentUserGroups" have to return its result as
		# commaseperated list, and not as array? Well, can't change that now...
		$readGroups      = array_filter(explode(',',$this->tools->getParentUserGroups($forumData['grouprights_read'])),'intval');
		$writeGroups     = array_filter(explode(',',$this->tools->getParentUserGroups($forumData['grouprights_write'])),'intval');
		$moderatorGroups = array_filter(explode(',',$this->tools->getParentUserGroups($forumData['grouprights_mod'])),'intval');

		$marker = Array ( '###ANON_READ_CHECKED###' => (count($readGroups)  > 0) ? '' : 'checked="checked"',
			'###ALL_READ_CHECKED###'  => (count($readGroups)  > 0) ? '' : 'checked="checked"',
			'###ALL_WRITE_CHECKED###' => (count($writeGroups) > 0) ? '' : 'checked="checked"',
			'###FORM_ACTION###'       => $this->p->pi_getPageLink($GLOBALS['TSFE']->id),
			'###FORUM_UID###'         => $forumUid );
		$template = $this->cObj->substituteMarkerArray($template, $marker);
		$template = $this->cObj->substituteSubpart ( $template, '###GROUP_ROW###',
			$this->aclGetGroupRow ( $groupTemplate,
				array ( $readGroups, $writeGroups, $moderatorGroups ) ) );

		$template = preg_replace_callback('/###L:([A-Z_-]+)###/i', array($this,'replaceLL'), $template);
		return $template;
	}



	/**
	 *
	 * Saves all changes made to a specific forum's access control list.
	 *
	 * @access private
	 * @return void
	 *
	 */
	function saveAclAction() {
		$acls = $this->v['acl'];
		$forumUid = intval($this->v['setACLs']);

		$readString  = (in_array('all', $acls['read']) || in_array('anon', $acls['read']))
			? '' : implode(',',array_filter($acls['read'],'intval'));
		$writeString = in_array('all', $acls['write'])
			? '' : implode(',',array_filter($acls['write'],'intval'));
		$modString   = implode(',',array_filter($acls['moderate'],'intval'));

		$updateArray = array ( 'tstamp'            => $GLOBALS['EXEC_TIME'],
			'grouprights_read'  => $readString,
			'grouprights_write' => $writeString,
			'grouprights_mod'   => $modString );
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_forums', 'uid='.$forumUid, $updateArray);

		$this->redirectToAction(array('flashmessage' => base64_encode($this->l('acl-success'))));
	}



	/**
	 *
	 * Generates a single group row for the ACL editing form.
	 *
	 * @access private
	 * @param  String  $template       The row template
	 * @param  array   $selectedGroups The groups, that are granted read, write and
	 *                                 moderation access for the edited forum.
	 * @param  Integer $parent         The parent group. NULL for no parent group.
	 * @param  array   $parentList     A list of all parent groups.
	 * @return String                  HTML content
	 *
	 */
	function aclGetGroupRow($template, $selectedGroups, $parent=NULL, $parentList=array()) {
		global $TYPO3_DB;

		$where = ($parent == NULL) ? ' AND (subgroup="" OR subgroup IS NULL) ' : ' AND find_in_set('.intval($parent).',subgroup) ';
		$res = $TYPO3_DB->exec_SELECTquery('*', 'fe_groups', 'deleted=0 '.$this->p->getUserPidQuery('fe_groups').$where);

		$content = '';
		While($arr = $TYPO3_DB->sql_fetch_assoc($res)) {
			$groupMarker = array(
				'###GROUP_NAME###'            => $this->validator->specialChars($arr['title']),
				'###GROUP_UID###'             => $arr['uid'],
				'###GROUP_INDENT###'          => count($parentList) * 24,
				'###GROUP_ONCHECK_READ###'    => '',
				'###GROUP_ONUNCHECK_READ###'  => '',
				'###GROUP_ONCHECK_WRITE###'   => '',
				'###GROUP_ONUNCHECK_WRITE###' => '',
				'###GROUP_ONCHECK_MOD###'     => '',
				'###GROUP_ONUNCHECK_MOD###'   => ''
			);

			if ($this->p->getAdminGroup() == $arr['uid']) {
				$groupMarker['###GROUP_READ_CHECKED###']  = 'checked="checked" disabled="disabled"';
				$groupMarker['###GROUP_WRITE_CHECKED###'] = 'checked="checked" disabled="disabled"';
				$groupMarker['###GROUP_MOD_CHECKED###']   = 'checked="checked" disabled="disabled"';
			} else {
				$groupMarker['###GROUP_READ_CHECKED###']  = ( in_array($arr['uid'], $selectedGroups[0]) ) ? 'checked="checked"' : '';
				$groupMarker['###GROUP_WRITE_CHECKED###'] = ( in_array($arr['uid'], $selectedGroups[1]) ) ? 'checked="checked"' : '';
				$groupMarker['###GROUP_MOD_CHECKED###']   = ( in_array($arr['uid'], $selectedGroups[2]) ) ? 'checked="checked"' : '';
			}

			$children = array_filter(explode(',',$this->tools->getParentUserGroups($arr['uid'])),'intval');
			foreach($children As $child) {
				$groupMarker['###GROUP_ONCHECK_READ###']  .= 'checkACLFlag(\'read\', '.$child.');';
				$groupMarker['###GROUP_ONCHECK_WRITE###'] .= 'checkACLFlag(\'write\', '.$child.');';
				$groupMarker['###GROUP_ONCHECK_MOD###']   .= 'checkACLFlag(\'moderate\', '.$child.');';
			}
			foreach($parentList As $parent) {
				$groupMarker['###GROUP_ONUNCHECK_READ###']  .= 'uncheckACLFlag(\'read\', '.$parent.');';
				$groupMarker['###GROUP_ONUNCHECK_WRITE###'] .= 'uncheckACLFlag(\'write\', '.$parent.');';
				$groupMarker['###GROUP_ONUNCHECK_MOD###']   .= 'uncheckACLFlag(\'moderate\', '.$parent.');';
			}

			$content .= $this->cObj->substituteMarkerArray($template, $groupMarker);
			$content .= $this->aclGetGroupRow($template, $selectedGroups, $arr['uid'], array_merge($parentList, array($arr['uid'])));
		} return $content;
	}



	/**
	 *
	 * Displays a form for editing or creating a single forum.
	 *
	 * @access private
	 * @return String HTML content
	 *
	 */
	function editAction() {
		$forumUid = $this->v['newForum'] ? -1 : intval($this->v['editForum']);

		if ( $this->v['forum']['save'] ) {
			$result = $this->saveEditAction();
			if ($result['success'] === TRUE)
				$this->redirectToAction(array('flashmessage' => base64_encode(sprintf($this->l('edit-success'),$this->v['forum']['name']))));
			else $errors = $result['errors'];
		} elseif ($this->v['forum']['cancel']) $this->redirectToAction(array());
		else $errors = array();

		global $TYPO3_DB;

		$template = $this->cObj->fileResource($this->conf['templates.']['edit']);

		# Select the forum to be edited. The "hidden" flag is not queried on purpose!
		if ($forumUid > 0) {
			$res = $TYPO3_DB->exec_SELECTquery ( 'uid, forum_name AS name, forum_desc AS description, parentID AS parent',
				'tx_mmforum_forums', 'uid='.$forumUid.' AND deleted=0 '.$this->p->getStoragePIDQuery() );
			if ($TYPO3_DB->sql_num_rows($res) == 0) return $this->p->errorMessage($this->p->conf, $this->l('error-forumnotfound'));
			$forumArray = $TYPO3_DB->sql_fetch_assoc($res);
		} else $forumArray = array();
		if (is_array($this->v['forum'])) $forumArray = array_merge($forumArray, $this->v['forum']);

		if (!$forumArray['name']) $forumArray['name'] = $this->l('new-dummytitle');

		if (!$this->checkActionAllowance($forumArray['parent'] == 0 ? 'category' : 'forum', $forumUid == -1 ? 'create' : 'edit'))
			return $this->displayNoAccessError();

		$forumMarkers = array(
			'###FORUM_NAME###'			 => $this->validator->specialChars($forumArray['name']),
			'###FORUM_DESCRIPTION###'	 => $this->validator->specialChars($forumArray['description']),
			'###FORUM_PARENT_OPTIONS###' => $this->getForumSelectOptionList($forumArray['parent']),
			'###FORUM_HIDDEN_CHECKED###' => $forumArray['hidden'] ? 'checked="checked"' : '',
			'###FORUM_UID###'            => $forumUid,

			'###FORM_ACTION###'          => $this->p->pi_getPageLink($GLOBALS['TSFE']->id)
		);

		foreach(array('name','description','parent') As $field) {
			if ($errors[$field]) {
				$messages = array();
				foreach($errors[$field] As $error)
					$messages[] = vsprintf($this->l('error-'.$field.'-'.$error['type']), $error['args']);
				$forumMarkers['###ERRORS_'.strtoupper($field).'###'] = $this->cObj->stdWrap(implode(' ',$messages), $this->conf['format.']['errorMessage.']);
			} else $forumMarkers['###ERRORS_'.strtoupper($field).'###'] = '';
		}

		$template = $this->cObj->substituteMarkerArray($template, $forumMarkers);
		$template = preg_replace_callback('/###L:([A-Z_-]+)###/i', array($this,'replaceLL'), $template);

		return $template;
	}



	/**
	 *
	 * Saves changes made to either an existing or a newly created forum to the
	 * datbase.
	 *
	 * @access private
	 * @return Array An array that contains a status code and validation errors, if
	 *               some occured.
	 *
	 */
	function saveEditAction() {
		global $TYPO3_DB;

		$forum = $this->v['forum']; $forumUid = intval($this->v['editForum']);
		$validationResult = $this->forumValidator->validateEditObject($forumUid, $forum);

		if (!$this->checkActionAllowance($forum['parent'] == 0 ? 'category' : 'forum', $forumUid == -1 ? 'create' : 'edit'))
			return $this->displayNoAccessError();

		if ($validationResult['error']) return Array ( 'success'        => FALSE,
			'errors'         => $validationResult['errors'],
			'overrideValues' => $forum );
		$saveArray = Array ( 'tstamp'      => $GLOBALS['EXEC_TIME'],
			'forum_name'  => $forum['name'],
			'forum_desc'  => $forum['description'],
			'parentID'    => $forum['parent'],
			'hidden'      => $forum['hidden'] ? 1 : 0 );
		if ($forumUid == -1) {
			$saveArray['pid']     = $this->p->getStoragePID();
			$saveArray['crdate']  = $GLOBALS['EXEC_TIME'];
			$saveArray['sorting'] = $this->getSortingForNewForum($forum['parent']);
			$TYPO3_DB->exec_INSERTquery('tx_mmforum_forums', $saveArray);
		} else $TYPO3_DB->exec_UPDATEquery('tx_mmforum_forums', 'uid='.intval($forumUid), $saveArray);

		return Array ( 'success' => TRUE );

	}

	
	/**
	 *
	 * Generates a list with buttons offering several options for single forums.
	 *
	 * @access private
	 * @param  Array   $forum   The forum for which the buttons are to be generated
	 * @param  Boolean $isFirst TRUE, if the current forum is the first one in the
	 *                          list, otherwise FALSE.
	 * @param  Boolean $isLast  TRUE, if the current forum is the last one in the
	 *                          list, otherwise FALSE.
	 * @return String           HTML content
	 *
	 */
	function getForumOptions($forum, $isFirst=FALSE, $isLast=FALSE) {

		$content = '';

		$aclGroupName = $forum['parentID'] ? 'forum' : 'category';

		$oldData = $this->p->cObj->data;
		$this->p->cObj->data = $forum;

		$content .= $this->getOptionImage ( 'edit'  , !$this->checkActionAllowance($aclGroupName, 'edit'));
		$content .= $this->getOptionImage ( 'newsub', ($forum['parentID'] != 0 || !$this->checkActionAllowance('forum', 'create')));
		$content .= $this->getOptionImage ( 'remove', !$this->checkActionAllowance($aclGroupName, 'remove'));
		$content .= $this->getOptionImage ( 'access', !$this->checkActionAllowance($aclGroupName, 'acl'));
		$content .= $this->getOptionImage ( 'up'    , $isFirst || !$this->checkActionAllowance($aclGroupName, 'order'));
		$content .= $this->getOptionImage ( 'down'  , $isLast || !$this->checkActionAllowance($aclGroupName, 'order'));

		$this->p->cObj->data = $oldData;

		return $content;
	}



	/**
	 *
	 * Generates an option image. The configuration for these images is loaded from
	 * "plugin.tx_mmforum_pi1.feadmin.list.buttons".
	 *
	 * @param  String  $name    The identifier for the button that is to be generated.
	 * @param  Boolean $disable TRUE, to disable the button. In this case, the button
	 *                          is not linked, and the image is in grayscale and
	 *                          slightly blurred.
	 * @return String
	 *
	 */
	function getOptionImage($name, $disable=FALSE) {
		if (!$disable)
			return $this->p->cObj->cObjGetSingle ( $this->conf['list.']['buttons.'][$name],
				$this->conf['list.']['buttons.'][$name.'.'] );
		else {
			$oldConf = $this->conf['list.']['buttons.'][$name.'.'];
			$newConf = Array ( 'file' => 'GIFBUILDER',
				'file.' => Array ( 'XY'  => '24,24',
					'10'  => 'IMAGE',
					'10.' => Array ( 'file' => $oldConf['file'] ),
					'20'  => 'EFFECT',
					'20.' => Array ( 'value' => 'gamma=1.5 | gray | blur=5' ) ) );
			return $this->p->cObj->cObjGetSingle($this->conf['list.']['buttons.'][$name], $newConf);
		}
	}



	/**
	 *
	 * Generates an HTML <OPTION>-List that contains all toplevel boards.
	 *
	 * @param  Integer $selectedUid The UID of the forum that is to be marked as
	 *                              preselected
	 * @return String               HTML content
	 *
	 */
	function getForumSelectOptionList($selectedUid=NULL) {

		global $TYPO3_DB;

		$content = '';
		$res = $TYPO3_DB->exec_SELECTquery('*', 'tx_mmforum_forums', 'deleted=0 AND parentID=0 '.$this->p->getStoragePIDQuery(), '', 'sorting ASC');

		While($arr = $TYPO3_DB->sql_fetch_assoc($res)) {
			$selected = $arr['uid'] == $selectedUid;
			$content .= '<option value="'.$arr['uid'].'" '.($selected?'selected="selected"':'').'>'.$this->validator->specialChars($arr['forum_name']).'</option>';
		} return $content;

	}

	
	/*
	 * DATA MODEL METHODS
	 */
	

	/**
	 *
	 * Deletes a single forum.
	 *
	 * @access private
	 * @param  Integer $forumUid The UID of the forum that is to be deleted
	 * @return Void
	 *
	 */
	function deleteForum($forumUid) {
		global $TYPO3_DB;
		$forumUid = intval($forumUid);
		$forumData = $this->p->getBoardData($forumUid);
		if (!$this->checkActionAllowance($forumData['parentID']==0?'category':'forum', 'remove')){ 
			$this->flashmessage = $this->l('access-error'); 
			return FALSE; 
		}
		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_forums', "uid=$forumUid OR parentID=$forumUid", array('deleted'=>1, 'tstamp'=>$GLOBALS['EXEC_TIME']));
		$this->flashmessage = sprintf($this->l('delete-success'), $forumData['forum_name']);
	}



	/**
	 *
	 * Moves a forum upwards.
	 *
	 * @access private
	 * @param  Integer $forumUid The UID of the forum that is to be moved.
	 * @return Void
	 *
	 */
	function moveForumUp($forumUid) {
		$forumData = $this->p->getBoardData($forumUid);
		if (!$this->checkActionAllowance($forumData['parentID']==0?'category':'forum', 'order')){ 
			$this->flashmessage = $this->l('access-error'); 
			return FALSE;
		}
		global $TYPO3_DB;
		$res = $TYPO3_DB->exec_SELECTquery ( 'uid, sorting', 'tx_mmforum_forums',
			'deleted=0 AND parentID='.$forumData['parentID'].'
					                            AND sorting < '.$forumData['sorting'],
			'', 'sorting DESC', 1 );
		if ($TYPO3_DB->sql_num_rows($res) == 0) return;
		list($upperUid, $upperSorting) = $TYPO3_DB->sql_fetch_row($res);

		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_forums', 'uid='.$forumData['uid'], array('sorting' => $upperSorting, 'tstamp' => $GLOBALS['EXEC_TIME']));
		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_forums', 'uid='.$upperUid, array('sorting' => $forumData['sorting'], 'tstamp' => $GLOBALS['EXEC_TIME']));
	}



	/**
	 *
	 * Moves a forum downwards.
	 *
	 * @access private
	 * @param  Integer $forumUid The UID of the forum that is to be moved.
	 * @return Void
	 *
	 */
	function moveForumDown($forumUid) {
		$forumData = $this->p->getBoardData($forumUid);
		if (!$this->checkActionAllowance($forumData['parentID']==0?'category':'forum', 'order')) { 
			$this->flashmessage = $this->l('access-error'); 
			return FALSE; 
		}
		global $TYPO3_DB;
		$res = $TYPO3_DB->exec_SELECTquery ( 'uid, sorting', 'tx_mmforum_forums',
			'deleted=0 AND parentID='.$forumData['parentID'].'
					                            AND sorting > '.$forumData['sorting'],
			'', 'sorting ASC', 1 );
		if ($TYPO3_DB->sql_num_rows($res) == 0) return;
		list($lowerUid, $lowerSorting) = $TYPO3_DB->sql_fetch_row($res);

		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_forums', 'uid='.$forumData['uid'], array('sorting' => $lowerSorting, 'tstamp' => $GLOBALS['EXEC_TIME']));
		$TYPO3_DB->exec_UPDATEquery('tx_mmforum_forums', 'uid='.$lowerUid, array('sorting' => $forumData['sorting'], 'tstamp' => $GLOBALS['EXEC_TIME']));
	}



	/**
	 *
	 * Gets the sorting value for a newly created forum.
	 *
	 * @param  Integer $parentUid The parent UID of the new forum
	 * @return Integer            The new sorting value.
	 *
	 */
	function getSortingForNewForum($parentUid = 0) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('MAX(sorting)+1', 'tx_mmforum_forums', 'deleted=0 AND parentID='.intval($parentUid).' '.$this->p->getStoragePIDQuery());
		list($newSorting) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $newSorting;
	}

	
	/*
	 * ACCESS VALIDATION
	 */
	

	/**
	 *
	 * Checks if the user that is currently logged in has access to a specific area
	 * of the frontend administration module.
	 * These ACLs can be configured using the TS property
	 * "tx_mmforum_pi1.feAdmin.acl"
	 *
	 * @param  String $group  The type of element that is edited. This may be either
	 *                        "forum" or "category".
	 * @param  String $action The action that is to be performed (create, edit,
	 *                        remove, order, ...)
	 * @return Boolean        TRUE, if the action is allowed, otherwise FALSE.
	 *
	 */
	function checkActionAllowance($group, $action) {
		$aclList = $this->conf['acl.']["$group."][$action];
		if ($aclList == 'all') return TRUE;
		if ($aclList == '' || $aclList == 'none') return FALSE;

		$authGroups = array_filter(explode(',',tx_mmforum_tools::getParentUserGroups($aclList)),'intval');
		$groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];

		return count(array_intersect($authGroups,$groups))>0;
	}



	/**
	 *
	 * Displays an error message that denies access to a specific action.
	 * @return string HTML content
	 *
	 */
	function displayNoAccessError() {
		return $this->p->errorMessage($this->p->conf, $this->l('access-error'));
	}

	
	/*
	 * HELPER METHODS
	 */
	
	
	/**
	 *
	 * Generates a HTTP redirect to a specific action of this controller.
	 *
	 * @access private
	 * @param  Array $args Arguments for the redirect URL
	 * @return Void
	 *
	 */
	function redirectToAction($args) {
		header('Location: '.tx_mmforum_tools::getAbsoluteUrl($this->p->pi_getPageLink($GLOBALS['TSFE']->id, NULL, array($this->p->prefixId=>$args)))); die();
	}



	/**
	 *
	 * Wrapper for the pi_getLL method of the parent object. For convenience only.
	 *
	 * @param  String $key     The label key
	 * @param  String $default Default value
	 * @return String          The text
	 *
	 */
	function l($key, $default='') {
		$res = $this->p->pi_getLL('feadmin-'.$key, $key);
		return strlen($res) ? $res : ($default?$default:'feadmin-'.$key);
	}



	/**
	 *
	 * Callback function for dynamically replacing language markers.
	 *
	 * @param  Array $matches Matched text segment
	 * @return String         Translated text.
	 *
	 */
	function replaceLL($matches) {
		return $this->l($matches[1], $matches[1]);
	}



	/**
	 *
	 * Initializes this controller. Mainly, this method creates local references to
	 * some objects of the parent object, the tx_mmforum_pi1 class.
	 *
	 * @param  Array          $configuration The configuration array
	 * @param  tx_mmforum_pi1 $parentObject  The parent object
	 * @return Void
	 *
	 */
	function initFromParent($configuration, $parentObject) {
		$this->conf =  $configuration['feAdmin.'];
		$this->p    =  $parentObject;
		$this->cObj =& $parentObject->cObj;
		$this->validator =& $parentObject->validator;
		$this->v    = $parentObject->piVars;
		$this->tools = $parentObject->tools;

		$this->forumValidator = t3lib_div::makeInstance('tx_mmforum_FrontendAdministration_Validator');
		$this->forumValidator->conf = $this->conf;
		$this->forumValidator->parent = $this->p;
	}
}
