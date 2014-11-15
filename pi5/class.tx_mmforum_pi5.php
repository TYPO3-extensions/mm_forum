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
 */


/**
 * The plugin 'Change Userdetails' for the 'mm_forum' extension
 * offers forms for fe_users to change their user settings. This
 * means fields like interests, homepage, email address etc., furthermore
 * the avatar, which is uploaded in an extra directory to be specified
 * in TypoScript and the password.
 *
 * @author     Holger Trapp <h.trapp@mittwaldmedien.de>
 * @author     Georg Ringer <typo3@ringerge.org>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Björn Detert <b.detert@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @version    2007-10-03
 * @package    mm_forum
 * @subpackage Settings
 */
class tx_mmforum_pi5 extends tx_mmforum_base {
	var $prefixId      = 'tx_mmforum_pi5';					// Same as class name
	var $scriptRelPath = 'pi5/class.tx_mmforum_pi5.php';	// Path to this script relative to the extension dir.

	/**
	 * @var tx_mmforum_usermanagement
	 */
	var $userLib = null;

	/**
	 *
	 * @var tx_mmforum_FeUser
	 */
	protected $user = null;

	/**
	 * Main method. Calls the function list_userdata.
	 *
	 * @author  Georg Ringer <typo3@ringerge.org>
	 * @version 2007-05-15
	 * @param   string $content The content
	 * @param   array  $conf    The plugin's configuration vars
	 * @return  string          The plugin content
	 */
	function main($content, $conf) {
		$this->init($conf);
		$this->pi_USER_INT_obj = 1;

			/* Instantiate user management library */
		$this->userLib = t3lib_div::makeInstance('tx_mmforum_usermanagement');


		if ($GLOBALS['TSFE']->loginUser) {
			$this->user = t3lib_div::makeInstance('tx_mmforum_FeUser');
			$this->user->initFromDB($GLOBALS['TSFE']->fe_user->user['uid']);
			$this->user->loadFromDB();
			$content = $this->listUserdata($content);
		} else {
			$template = $this->cObj->fileResource($this->conf['template']);
			$template = $this->cObj->getSubpart($template, '###ERROR###');
			$content .= $this->cObj->substituteMarker($template, '###ERROR_MSG###', $this->pi_getLL('nologin'));
		}
		
		return $this->pi_wrapInBaseClass($content);
	}

	/**
	 * Displays a form for editing the data of the current user.
	 *
	 * @author  Georg Ringer <typo3@ringerge.org>
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-05-15
	 * @param   string $content The plugin content
	 * @return  string          The content
	 */
	function listUserdata ($content) {
		switch (t3lib_div::_GP('action'))
		{
			case 'change_data':
				$this->writeUserdata($content);
				break;
			case 'avatar_upload':
				$content = $this->uploadAvatar($content);
				break;
			case 'change_pass':
				$content .= $this->changePassword($this->piVars['newpass1'], $this->piVars['newpass2'], $this->piVars['oldpass']);
				break;
		}

		$template = $this->cObj->fileResource($this->conf['template']);
		$template = $this->cObj->getSubpart($template, '###MAIN###');

		$marker = array(
			'###LABEL_UPLOADAVATAR###'   => $this->pi_getLL('avatar.uploadAvatar'),
			'###LABEL_AVATAR###'         => $this->pi_getLL('avatar.avatar'),
			'###LABEL_CHANGEPASSWORD###' => $this->pi_getLL('password.change'),
			'###LABEL_OLDPW###'          => $this->pi_getLL('password.old'),
			'###LABEL_NEWPW###'          => $this->pi_getLL('password.new'),
			'###LABEL_REPEAT###'         => $this->pi_getLL('password.repeat'),
			'###LABEL_CHANGE###'         => $this->pi_getLL('password.save'),

			'###LABEL_SETTINGS2###'      => $this->pi_getLL('settings2'),
			'###LABEL_PMNOTIFY###'       => $this->pi_getLL('pmnotifymode'),
			'###LABEL_PMNOTIFY_0###'     => $this->pi_getLL('pmnotifymode.0'),
			'###LABEL_PMNOTIFY_1###'     => $this->pi_getLL('pmnotifymode.1'),
			'###LABEL_PMNOTIFY_2###'     => $this->pi_getLL('pmnotifymode.2'),

			'###IMG_MAIL###'             => tx_mmforum_pi1::createButton('email',array(),0,true,'',true),
			'###IMG_ICQ###'              => tx_mmforum_pi1::createButton('icq',array(),0,true,'',true),
			'###IMG_AIM###'              => tx_mmforum_pi1::createButton('aim',array(),0,true,'',true),
			'###IMG_YIM###'              => tx_mmforum_pi1::createButton('yim',array(),0,true,'',true),
			'###IMG_MSN###'              => tx_mmforum_pi1::createButton('msn',array(),0,true,'',true),
			'###IMG_SKYPE###'            => tx_mmforum_pi1::createButton('skype',array(),0,true,'',true),

			'###PMNOTIFY_0###'           => $this->user->pmNotifyModeIs(0) ? 'checked="checked"' : '',
			'###PMNOTIFY_1###'           => $this->user->pmNotifyModeIs(1) ? 'checked="checked"' : '',
			'###PMNOTIFY_2###'           => $this->user->pmNotifyModeIs(2) ? 'checked="checked"' : '',

			'###SETTINGS_ICON###'        => $this->cObj->cObjGetSingle($this->conf['icons.']['settings'], $this->conf['icons.']['settings.']),
			'###SETTINGS2_ICON###'       => $this->cObj->cObjGetSingle($this->conf['icons.']['settings'], $this->conf['icons.']['settings2.']),
			'###AVATAR_ICON###'          => $this->cObj->cObjGetSingle($this->conf['icons.']['settings'], $this->conf['icons.']['avatar.']),
			'###PASSWORD_ICON###'        => $this->cObj->cObjGetSingle($this->conf['icons.']['settings'], $this->conf['icons.']['password.']),

			'###FORMACTION###'           => $this->pi_getPageLink($GLOBALS['TSFE']->id)
		);

		// Create marker array, field names are retrieved from TypoScript
		$extrafields = array('uid', 'username', 'crdate', 'tx_mmforum_posts', 'tx_mmforum_avatar', 'image');
		$fields = array_unique(array_merge($extrafields, t3lib_div::trimExplode(',', $this->conf['userFields'], true)));
		$required = t3lib_div::trimExplode(',', $this->conf['required.']['fields'], true);

		foreach ($fields as $fieldName) {
			$label = $this->pi_getLL($fieldName);
			if (in_array($fieldName, $required)) $label = $this->cObj->wrap($label, $this->conf['required.']['fieldWrap']);
			
			$marker['###DESCR_' . strtoupper($fieldName) . '###'] = $label;
			$marker['###' . strtoupper($fieldName) . '###'] = isset($this->piVars[$fieldName]) ? $this->piVars[$fieldName] : $this->user->gD($fieldName);
		}

		// Some special fields
		$marker['###CRDATE###']				= strftime($this->conf['date'], $this->user->gD('crdate'));
		$marker['###ACTIONLINK###']         = '';
		$marker['###SIGNATUR_PREVIEW###']	= tx_mmforum_postparser::main(
				$this, $this->conf, $this->user->gD('tx_mmforum_user_sig'), 'textparser');

		// Avatar
		$imgTSConfig = $this->conf['avatar.'];
		$imgTSConfig['file'] = $this->user->getAvatar($this->conf['path_avatar']);
		$marker['###AVATAR###'] =  $this->cObj->IMAGE($imgTSConfig);

		$marker['###AVATAR_DEL###'] = '';
		if ($this->user->hasAvatar()) {
			$marker['###AVATAR_DEL###'] = '<input type="checkbox"  name="' . $this->prefixId . '[del_avatar]" />' . $this->pi_getLL('delete_avatar');
		}

		// Language markers
		$dataLL = Array ('descr_signatur_preview', 'send', 'about', 'filename', 'descr_date');
		foreach ($dataLL as $llKey) {
			$marker['###' . strtoupper($llKey) . '###'] = $this->pi_getLL($llKey);
		}

		// User fields
		$userField_template = $this->cObj->getSubpart($template, '###USERFIELDS###');
		$userField_content  = '';

		$userFields = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'tx_mmforum_userfields',
			'deleted=0 AND hidden=0',	// where
			'',							// groupby
			'sorting DESC'				// orderby
		);

		$parser = t3lib_div::makeInstance('t3lib_TSparser');
		foreach ($userFields as $field) {
			if (isset($this->piVars['userfield'][$field['uid']])) {
				$value = $this->piVars['userfield'][$field['uid']];
			} else {
				$res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'field_value',
					'tx_mmforum_userfields_contents',
					'field_id=' . $field['uid'] . ' AND user_id=' . $this->user->getUid()
				);

				$value = '';
				if ($GLOBALS['TYPO3_DB']->sql_num_rows($res2) > 0) {
					list($value) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res2);
				}
			}

			$parser->setup = array();
			if (strlen($field['config']) > 0) {
				$parser->parse($field['config']);
			}
			$config = $parser->setup;

			$label = $field['label'];
			if ($config['label']) {
				$label = $this->cObj->cObjGetSingle($config['label'], $config['label.']);
			}

			if ($config['required']) {
				$label = $this->cObj->wrap($label, $this->conf['required.']['fieldWrap']);
			}

			if ($config['datasource']) {
				$value = isset($this->piVars['userfield'][$field['uid']]) ? $this->piVars['userfield'][$field['uid']] : $this->user->gD($config['datasource']);
				$label .= '<input type="hidden" name="tx_mmforum_pi5[userfield_exists][' . $field['uid'] . ']" value="' . $config['datasource'] . '" />';
			}

			if ($config['input']) {
				$tmpData = $this->cObj->data;
				$this->cObj->data = array('fieldvalue' => $value);

				$input = $this->cObj->cObjGetSingle($config['input'], $config['input.']);

				$this->cObj->data = $tmpData;
			} else {
				$input = $this->cObj->getSubpart($userField_template, '###DEFUSERFIELD###');
			}
			$userField_thisTemplate = $this->cObj->substituteSubpart($userField_template, '###DEFUSERFIELD###', $input);

			$userField_marker = array(
				'###USERFIELD_LABEL###' => $label,
				'###USERFIELD_VALUE###' => $value,
				'###USERFIELD_UID###'   => $field['uid'],
				'###USERFIELD_NAME###'  => $this->prefixId . '[userfield][' . $field['uid'] . ']',
				'###USERFIELD_ERROR###'	=> isset($this->userfield_error[$field['uid']]) ? $this->cObj->wrap($this->userfield_error[$field['uid']], $this->conf['userFields.']['wrap']) : ''
			);
			$userField_content .= $this->cObj->substituteMarkerArrayCached($userField_thisTemplate, $userField_marker);
		}
		$template = $this->cObj->substituteSubpart($template, '###USERFIELDS###', $userField_content);

			// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['editProfilMarkerArray'])) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['editProfilMarkerArray'] as $classRef) {
				$procObj = &t3lib_div::getUserObj($classRef);
				$marker = $procObj->processProfilMarkerArray($marker, $this->cObj);
			}
		}

		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		return $content;
	}

	/**
	 *
	 * @return t3lib_TSparser
	 */
	function &getTSParser() {
		if (!$this->parser) {
			$this->parser = t3lib_div::makeInstance('t3lib_TSparser');
		}

		return $this->parser;
	}

	/**
	 * Returns true, if user field is required
	 *
	 * @param integer $uid
	 * @return boolean
	 */
	function getUserFieldIsRequired($uid) {
		$arr = $this->getUserfieldConfig($uid);
		if (!is_array($arr) || empty($arr)) {
			return false;
		}
		
		$parser = $this->getTSParser();
		$parser->parse($arr['config']);

		return $parser->setup['required'] ? true : false;
	}

	/**
	 * Returns true, if user field uses existing field
	 *
	 * @param integer $uid
	 * @return boolean
	 */
	function getUserfieldUsesExistingField($uid) {
		$arr = $this->getUserfieldConfig($uid);
		if (!is_array($arr) || empty($arr)) {
			return false;
		}
		
		$parser = $this->getTSParser();
		$parser->parse($arr['config']);

		return $parser->setup['datasource'] ? true : false;
	}

	/**
	 * fetches user field configuration from database
	 *
	 * @param integer $uid
	 * @return array
	 */
	function getUserfieldConfig($uid) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'config',
			'tx_mmforum_userfields',
			'uid=' . intval($uid) . ' AND deleted=0 AND hidden=0'
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
			$arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			return $arr;

		} else {
			return array();
		}
	}

	/**
	 * Writes the changes made by the user to the database
	 * @author  Georg Ringer <typo3@ringerge.org>
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-05-15
	 * @param   string $content The plugin content
	 * @return  string          The content
	 */
	function writeUserdata($content) {

		$template = $this->cObj->fileResource($this->conf['template']);
		$template = $this->cObj->getSubpart($template, '###ERROR###');

		$data = t3lib_div::trimExplode(',', $this->conf['userFields'], true);

		foreach ($data as $v) {
			$updateArr[$v] = $this->piVars[$v];
		}

		$userField = t3lib_div::makeInstance('tx_mmforum_userfield');
		/* @var $userField tx_mmforum_userfield */
		$userField->init($this->userLib, $this->cObj);

		// Check required user fields
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_mmforum_userfields', 'deleted=0');
		
		foreach ($rows as $fieldData) {
			$userField->get($fieldData);

			$value  = $this->piVars['userfield'][$userField->getUid()];

			if (!$userField->isValid($value)) {
				$requiredMissing = true;
				$this->userfield_error[$fieldData['uid']] = $this->pi_getLL('error-userfieldEmpty');
			}

			if (   !$this->userfield_error[$fieldData['uid']]
			    && intval($userField->data['uniquefield']) === 1
				&& !($userField->isUnique($value, $userField->data['config_parsed']['datasource']))) {

				$this->userfield_error[$fieldData['uid']] = $this->pi_getLL('error-userfieldNotUnique');
				$error = 1;
			}

		}

		t3lib_div::debug($this->userfield_error);

		if ($requiredMissing) $error = 1;

		// If no error occurred...
		if ($error == 0) {

			// Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['editProfilUpdateArray'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['editProfilUpdateArray'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$updateArr = $_procObj->processProfilUpdateArray($updateArr, $this->cObj);
				}
			}

			$this->user->setDataArray($updateArr);
			$this->user->updateDatabase();

			// Save user fields
			if (is_array($this->piVars['userfield'])) {
				foreach($this->piVars['userfield'] as $uid => $value) {
					if (strlen(trim($value)) == 0) continue;

					$uid = intval($uid);

					if ($this->piVars['userfield_exists'][$uid]) {
						if ($this->getUserfieldUsesExistingField($uid)) {
							$updateArray = array(
								$this->piVars['userfield_exists'][$uid]		=> $value,
								'tstamp'									=> $GLOBALS['EXEC_TIME']
							);
							$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
								'fe_users',
								'uid=' . $this->user->getUid() . ' AND deleted=0',
								$updateArray
							);
						}
					} else {
						$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
							'*',
							'tx_mmforum_userfields_contents',
							'user_id=' . $this->user->getUid() . ' AND field_id=' . $uid . ' AND deleted=0'
						);
						if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) {
							$insertArray = array(
								'pid'           => $this->getStoragePID(),
								'tstamp'        => $GLOBALS['EXEC_TIME'],
								'crdate'        => $GLOBALS['EXEC_TIME'],
								'user_id'       => $this->user->getUid(),
								'field_id'      => $uid,
								'field_value'   => $value
							);
							$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_userfields_contents', $insertArray);
						}
						else {
							$updateArray = array(
								'tstamp'        => $GLOBALS['EXEC_TIME'],
								'field_value'   => $value
							);
							$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
								'tx_mmforum_userfields_contents',
								'field_id=' . $uid . ' AND user_id=' . $this->user->getUid(),
								$updateArray
							);
						}
					}
				}
			}

		// Otherwise...
		} else {
			$content .= $this->cObj->substituteMarkerArrayCached($template, $marker); //TODO: FIXME undefined variable $marker
		}

		return $content;
	}

	/**
	 * Uploads a new avatar to the server.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @author  Georg Ringer <typo3@ringerge.org>
	 * @version 2007-10-03
	 * @param   string $content The plugin content
	 * @return  string          The content
	 */
	function uploadAvatar($content) {
		$avatarFile = $_FILES[$this->prefixId];

		if (isset($this->piVars['del_avatar'])) {
			$this->user->removeAvatar($this->conf['path_avatar']);
			$this->user->updateDatabase();
			return $content;
		}

		$fI = t3lib_div::split_fileref($avatarFile['name']['file']);
		$fileExt = $fI['fileext'];

		if (!t3lib_div::verifyFilenameAgainstDenyPattern($avatarFile['name']['file'])
			|| !t3lib_div::inList($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'], $fileExt))
			return;

		if (isset($this->piVars['upload'])) {
			$uploaddir = $this->conf['path_avatar'];

			/*
			 * Load the allowed file size for avatar image from the TCA and
			 * check against the size of the uploaded image.
			 */
			global $TCA;
			$GLOBALS['TSFE']->includeTCA();
			t3lib_div::loadTCA('fe_users');
			if (filesize($avatarFile['tmp_name']['file']) > $GLOBALS['TCA']['fe_users']['columns']['tx_mmforum_avatar']['config']['max_size']*1024)
				return '';

			$file = $this->user->getUid() . '_' . $GLOBALS['EXEC_TIME'] . '.' . $fileExt;
			$uploadfile = $uploaddir . $file;

			if (move_uploaded_file($avatarFile['tmp_name']['file'], $uploadfile)) {
					/* Paranoid? Eh, you never know... */
				chmod($uploadfile, 0444);
				
				$this->user->setAvatar($file);
				$this->user->updateDatabase();
			}
		}

		return $content;
	}

	/**
	 * Changes the current user's password
	 * @version 12.03.2010
	 *
	 * @param string $password			the new entered password
	 * @param string $passwordRepeat	repeated password
	 * @param string $oldPassword		old password
	 * @return string HTML output
	 */
	function changePassword($password, $passwordRepeat, $oldPassword) {

		$messages = array();

		// Old password is not set
		if (empty($oldPassword)) {
			$messages[] = $this->pi_getLL('errorInsertOldPw');
		}
		// New password is not set
		if (empty($password)) {
			$messages[] = $this->pi_getLL('errorInsertNewPw');
		}
		// New password is not repeated
		if (empty($passwordRepeat)) {
			$messages[] = $this->pi_getLL('errorInsertNewPw2');
		}
		// New password is not repeated correctly
		if ((!empty($password) && !empty($passwordRepeat)) && ($password <> $passwordRepeat)) {
			$messages[] = $this->pi_getLL('errorNewPwRepeat');
		}
		// Old password is not correct
		else if (count($messages) == 0) {
			if (!$this->user->checkPassword($oldPassword)) {
				$messages[] = $this->pi_getLL('errorOldPw');
			}
		}
		// Password too short
		if (($password == $passwordRepeat) && (strlen($password) < $this->conf['minPasswordLength'])) {
			$messages[] = sprintf($this->pi_getLL('errorPwLength'),$this->conf['minPasswordLength']);
		}

		// Save new password to database
		if (count($messages) == 0) {
			$this->user->setPassword($password);
			$this->user->updateDatabase();

			$messages[] = $this->pi_getLL('pwChanged');
		}
		
		$template = $this->cObj->getSubpart(
			$this->cObj->fileResource($this->conf['template']),
			'###ERROR###'
		);
		
		$content = $this->cObj->substituteMarker(
			$template,
			'###ERROR_MSG###',
			implode('', (array)$messages)
		);

		return $content;
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mm_forum/pi5/class.tx_mmforum_pi5.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mm_forum/pi5/class.tx_mmforum_pi5.php']);
}
