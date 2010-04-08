<?php

Class tx_mmforum_FrontendAdministration_Validator {

		/**
		 * @var Array
		 */
	Var $errors;

		/**
		 * @var Boolean
		 */
	Var $errorStatus = FALSE;

	Var $conf = Array();

	Function validateEditObject($uid, $forum) {

		$this->validateForumName($forum['name']);
		$this->validateForumDescription($forum['description'], $forum['parent']);
		$this->validateParentUid($uid, $forum['parent']);

		Return Array ( 'error' => $this->errorStatus, 'errors' => $this->errors );

	}

	Function validateParentUid($uid, $parentUid) {

		Global $TYPO3_DB;

			# Always validate for new forums.
		If($uid == -1) Return;

		$res = $TYPO3_DB->exec_SELECTquery('*', 'tx_mmforum_forums', 'parentID='.intval($uid).' AND deleted=0 '.$this->parent->getStoragePIDQuery());
		If($TYPO3_DB->sql_num_rows($res) > 0 && $parentUid != 0)
			$this->addErrorForField('parent', 'no-nested-forums', Array($TYPO3_DB->sql_num_rows($res)));

	}
	
	Function validateForumDescription($forumDescription, $parentId) {

			# Categories do not need a description. Subforums do.
		If($parentId == 0) Return;

		If(!IsSet($forumDescription) || strlen($forumDescription) === 0)
			$this->addErrorForField('description', 'empty');
		ElseIf(strlen($forumDescription) < $this->conf['validation.']['description.']['minLength'])
			$this->addErrorForField('description', 'tooshort', Array($this->conf['validation.']['description.']['minLength']));
		ElseIf(strlen($forumDescription) > $this->conf['validation.']['description.']['maxLength'])
			$this->addErrorForField('description', 'toolong', Array($this->conf['validation.']['description.']['maxLength']));
		
	}

	Function validateForumName($forumName) {

		If(!IsSet($forumName) || strlen($forumName) === 0)
			$this->addErrorForField('name', 'empty');
		ElseIf(strlen($forumName) < $this->conf['validation.']['name.']['minLength'])
			$this->addErrorForField('name', 'tooshort', Array($this->conf['validation.']['name.']['minLength']));
		ElseIf(strlen($forumName) > $this->conf['validation.']['name.']['maxLength'])
			$this->addErrorForField('name', 'toolong', Array($this->conf['validation.']['name.']['maxLength']));

	}

	Function addErrorForField($fieldName, $errorCode, $arguments=Array()) {
		$this->errors[$fieldName][] = Array ('type' => $errorCode, 'args' => $arguments);
		$this->errorStatus = TRUE;
	}

}

?>
