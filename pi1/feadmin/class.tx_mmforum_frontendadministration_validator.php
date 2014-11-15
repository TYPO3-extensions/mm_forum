<?php

class tx_mmforum_FrontendAdministration_Validator {

	/**
	 * @var array
	 */
	var $errors;

	/**
	 * @var Boolean
	 */
	var $errorStatus = FALSE;

	var $conf = array();

	function validateEditObject($uid, $forum) {
		$this->validateForumName($forum['name']);
		$this->validateForumDescription($forum['description'], $forum['parent']);
		$this->validateParentUid($uid, $forum['parent']);

		return array ( 'error' => $this->errorStatus, 'errors' => $this->errors );
	}

	function validateParentUid($uid, $parentUid) {
		global $TYPO3_DB;

		# Always validate for new forums.
		if ($uid == -1) return;

		$res = $TYPO3_DB->exec_SELECTquery('*', 'tx_mmforum_forums', 'parentID='.intval($uid).' AND deleted=0 '.$this->parent->getStoragePIDQuery());
		if ($TYPO3_DB->sql_num_rows($res) > 0 && $parentUid != 0)
			$this->addErrorForField('parent', 'no-nested-forums', array($TYPO3_DB->sql_num_rows($res)));
	}

	function validateForumDescription($forumDescription, $parentId) {
		# Categories do not need a description. Subforums do.
		if ($parentId == 0) return;

		if (!isset($forumDescription) || strlen($forumDescription) === 0)
			$this->addErrorForField('description', 'empty');
		elseif (strlen($forumDescription) < $this->conf['validation.']['description.']['minLength'])
			$this->addErrorForField('description', 'tooshort', array($this->conf['validation.']['description.']['minLength']));
		elseif (strlen($forumDescription) > $this->conf['validation.']['description.']['maxLength'])
			$this->addErrorForField('description', 'toolong', array($this->conf['validation.']['description.']['maxLength']));
	}

	function validateForumName($forumName) {
		if (!isset($forumName) || strlen($forumName) === 0)
			$this->addErrorForField('name', 'empty');
		elseif (strlen($forumName) < $this->conf['validation.']['name.']['minLength'])
			$this->addErrorForField('name', 'tooshort', array($this->conf['validation.']['name.']['minLength']));
		elseif (strlen($forumName) > $this->conf['validation.']['name.']['maxLength'])
			$this->addErrorForField('name', 'toolong', array($this->conf['validation.']['name.']['maxLength']));
	}

	function addErrorForField($fieldName, $errorCode, $arguments=array()) {
		$this->errors[$fieldName][] = array ('type' => $errorCode, 'args' => $arguments);
		$this->errorStatus = TRUE;
	}

}