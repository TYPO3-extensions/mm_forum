<?php
/**
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
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   65: class tx_mmforum_pi2 extends tslib_pibase
 *   79:     function main($content,$conf)
 *  136:     function sendEmail()
 *  172:     function showMailVersand()
 *  195:     function check_hash($hash)
 *  259:     function saveData()
 *  337:     function showRegForm($marker, $conf)
 *  422:     function validate($marker)
 *  527:     function validate_email($email)
 *  538:     function makeMarker()
 *  581:     function getPidQuery($tables="")
 *  618:     function getFirstPid()
 *  641:     function pi_getLL($key)
 *
 * TOTAL FUNCTIONS: 12
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_base.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/user/class.tx_mmforum_usermanagement.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/user/class.tx_mmforum_userfield.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_pi1.php');

/**
 * The plugin 'User registration' for the 'mm_forum' extension
 * handles the registration, creation and activation of new users.
 * When a new user registrates, the regarding user record is created
 * as disabled, and the user is required to activate his account by
 * visiting a certain link unique for each user.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Björn Detert <b.detert@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @version    2007-05-15
 * @package    mm_forum
 * @subpackage Registration
 */
class tx_mmforum_pi2 extends tx_mmforum_base {
	var $prefixId      = 'tx_mmforum_pi2';
	var $scriptRelPath = 'pi2/class.tx_mmforum_pi2.php';

	var $userlib		= null;


	/**
	 * The plugin's main function.
	 * Evaluates Get/Post-parameters and accordingly displays registrations
	 * forms and saves or activates users.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The plugin content
	 */
	function main($content, $conf) {
		$this->data = $this->piVars['reg'];
		$this->init($conf);
		$this->pi_USER_INT_obj = 1;

			/* Load template file */
		$this->tmpl = $this->cObj->fileResource($conf['templateFile']);

		$userHash = $this->piVars['user_hash'];

			/* Instantiate user management library */
		$this->userLib = t3lib_div::makeInstance('tx_mmforum_usermanagement');

		if ($userHash) {
			$this->data['action'] = 'checkHash';
		}

		if ($GLOBALS['TSFE']->fe_user->user['uid']) {
			$content = $this->pi_getLL('error.alreadyLoggedIn');
		} else {
			switch ($this->data['action']) {
				// activate the user
				case 'checkHash':
					$content = $this->check_hash($userHash);
					break;

				// create a user
				case 'createUser':
					$marker = $this->makeMarker();
					$marker = $this->validate($marker);

					// If there are any mistakes, show the form again
					if ($marker['fehler']) {
						$content = $this->showRegForm($marker, $conf);
					} else {
						// Sava data
						$ok = $this->saveData();
						$ok = $this->sendEmail();
						$content = $this->showEmailSent();
					}
					break;

				// Show registration form
				default:
					$content = $this->showRegForm($this->makeMarker(), $conf);
					break;
			}
		}
		return $content;
	}

	/**
	 * Sends the email requesting the user to activate his/her account by
	 * visiting the activation page. The link is sent via email.
	 * The activation link is created from the current host address and the current
	 * page UID. This can be overwritten by plugin.tx_mmforum_pi2.email.activateUrlOverride
	 * @return void
	 */
	function sendEmail() {
		$header = array(
			'From: '.$this->conf['supportMail'],
			'Content-type: text/plain; charset=' . $GLOBALS['TSFE']->renderCharset
		);

		$linkParams[$this->prefixId] = array(
			'user_hash' => $this->data['reghash']
		);
		$link = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $linkParams);
		$link = tx_mmforum_pi1::getAbsUrl($link);
		$link = $this->tools->escapeBrackets($link);

		$marker = $this->makeMarker();
		$marker['###EMAIL_URL###']            = $link;
		$marker['###LABEL_HELLO###']          = $this->cObj->substituteMarker($this->pi_getLL('msg.hello'), '###USERNAME###', $this->data[$this->conf['userNameField']]);
		$marker['###LABEL_PLEASEACTIVATE###'] = $this->cObj->substituteMarker($this->pi_getLL('msg.pleaseActivate'), '###SITENAME###', $this->conf['siteName']);
		$marker['###LABEL_YOURS###']          = $this->cObj->substituteMarker($this->pi_getLL('msg.yoursPlaintext'), '###TEAM###', $this->conf['teamName']);

		$template = $this->cObj->getSubpart($this->tmpl, '###EMAIL_VALIDATION###');
		$template = $this->cObj->substituteMarkerArrayCached($template, $marker);

		$subject = $this->cObj->substituteMarker($this->pi_getLL('msg.subject'), '###SITENAME###', $this->conf['siteName']);

		t3lib_div::plainMailEncoded (

			$this->data['email'],               /* Address                                  */
			$subject,                           /* Subject                                  */
			$template,                          /* Mail body                                */
			implode("\n", $header),             /* Headers, seperated by \n                 */
			'base64',                           /* Encoding that is to be used for headers. *
			                                     * Allowed is "Quoted-printable" and        *
			                                     * "base64"                                 */

			$GLOBALS['TSFE']->renderCharset     /* The charset the headers are to be        *
			                                     * encoded in.                              */

		);
	}



		/**
		 * Shows a message indicationg the registration was successful and that a
		 * mail requesting an activation has been sent.
		 * @return string The message
		 */

	function showEmailSent() {
		$marker = $this->makeMarker();
		$template = $this->cObj->getSubpart($this->tmpl, '###TEMPLATE_CREATE_LOGIN_OK###');

		$marker['###LABEL_THANKYOU###']    = $this->pi_getLL('msg.thankYou');
		$marker['###LABEL_EMAILISSENT###'] = $this->cObj->substituteMarker($this->pi_getLL('msg.emailIsSent'), '###EMAIL###', '<strong>' . $marker['###VALUE_email###'] . '</strong>');
		$marker['###LABEL_YOURS###']       = $this->cObj->substituteMarker($this->pi_getLL('msg.yours'), '###TEAM###', $this->conf['teamName']);

		return $this->cObj->substituteMarkerArray($template, $marker);
	}



		/**
		 * Activates a user.
		 * On registration, a so called regHash is generated, which is individual for
		 * each user, since it is the MD5-Hash of the date of registration and the username.
		 * This method removes the disabled flag from the user record, allowing him to
		 * log in on the page.
		 *
		 * @param  string $hash The regHash to be checked
		 * @return string       A message indicating the success of the activation
		 */

	function check_hash($hash) {

			/* Check hash on validity */
		if(!preg_match('/^[a-f0-9]{15}$/', $hash)) {
			$template = $this->cObj->getSubpart($this->tmpl, "###FEHLER###");
			return $template;
		}

			/* Load user record from database */
		$hash = mysql_escape_string($hash);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_users','tx_mmforum_reg_hash="'.$hash.'"');

			/* If user records exists exactly once, continue... */
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)==1) {
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

				/* Activate user */
			$updateArray = array(
				'disable'               => 0,
				'tx_mmforum_reg_hash'   => ''
			);

			$res2 = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users',"tx_mmforum_reg_hash='$hash'",$updateArray);

			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['activateUser'])) {
			    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['activateUser'] as $_classRef) {
			        $_procObj = & t3lib_div::getUserObj($_classRef);
			        $_procObj->activateUser($row,$this);
			    }
			}

			// Output error message in case of failure
			if (mysql_error()) {
				$template = $this->cObj->getSubpart($this->tmpl, "###FEHLER###");
					$marker = array(
					'###LABEL_ERRORGENERAL###'		=> $this->pi_getLL('error.generalError'),
					'###LABEL_PLEASECONTACT###'		=> $this->cObj->substituteMarker($this->pi_getLL('error.support'),'###SUPPORTMAIL###',str_replace('@',' [at] ',$this->conf['supportMail'])),
				);
				return $template;
			}

			// Output message to user
			$marker = array();
			$marker['###VALUE_username###'] = $row['username'];
			$template = $this->cObj->getSubpart($this->tmpl, "###TEMPLATE_VALIDATION_OK###");

			$marker['###LABEL_HELLO###']     = $this->cObj->substituteMarker($this->pi_getLL('msg.hello'), '###USERNAME###', $marker['###VALUE_username###']);
			$marker['###LABEL_ACTIVATED###'] = $this->cObj->substituteMarker($this->pi_getLL('msg.activated'), '###SITENAME###', $this->conf['siteName']);
			$marker['###LABEL_YOURS###']     = $this->cObj->substituteMarker($this->pi_getLL('msg.yours'), '###TEAM###', $this->conf['teamName']);

			$content = $this->cObj->substituteMarkerArrayCached($template,$marker);
		}
		// If user records exists more than one time or not at all, abort.
		else {
			$template = $this->cObj->getSubpart($this->tmpl, "###FEHLER###");

			$marker = array(
				'###LABEL_ERRORGENERAL###'  => $this->pi_getLL('error.generalError'),
				'###LABEL_PLEASECONTACT###' => $this->cObj->substituteMarker($this->pi_getLL('error.support'),'###SUPPORTMAIL###',str_replace('@',' [at] ',$this->conf['supportMail'])),
			);
			$content = $this->cObj->substituteMarkerArrayCached($template, $marker);
		}
		return $content;
	}

	/**
	 * Saves the data submitted during registration to database.
	 * @return int  1 in case of success, otherwise 0
	 */
	function saveData()
	{
		$usergroup	= $this->conf['userGroup'];
		$pid		= $this->conf['userPID'];

		$this->data['reghash'] = substr(md5(time().$this->data['username']), 1, 15);

		$objPHPass = null;
		if (t3lib_extMgm::isLoaded('t3sec_saltedpw')) {
			require_once(t3lib_extMgm::extPath('t3sec_saltedpw').'res/staticlib/class.tx_t3secsaltedpw_div.php');
			if (tx_t3secsaltedpw_div::isUsageEnabled()) {
				require_once(t3lib_extMgm::extPath('t3sec_saltedpw').'res/lib/class.tx_t3secsaltedpw_phpass.php');
				$objPHPass = t3lib_div::makeInstance('tx_t3secsaltedpw_phpass');
			}
		}
		if (!$objPHPass && t3lib_extMgm::isLoaded('saltedpasswords')) {
			if (tx_saltedpasswords_div::isUsageEnabled()) {
				$objPHPass = t3lib_div::makeInstance(tx_saltedpasswords_div::getDefaultSaltingHashingMethod());
			}
		}

		if ($objPHPass) {
			$this->data['password'] = $objPHPass->getHashedPassword($this->data['password']);

		} else if(t3lib_extMgm::isLoaded('kb_md5fepw')) {	//if kb_md5fepw is installed, crypt password
			$this->data['password']=md5($this->data['password']);
		}

		$insertArray = array(
			'pid'				    => $pid,
			'tstamp'			    => time(),
			'crdate'			    => time(),
			'username'			    => $this->data['username'],
			'password'			    => $this->data['password'],
			'usergroup'			    => $usergroup,
			'disable'			    => 1,
			'tx_mmforum_reg_hash'   => $this->data['reghash']
		);

			# Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['saveData'])) {
		    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['saveData'] as $_classRef) {
		        $_procObj = & t3lib_div::getUserObj($_classRef);
		        $insertArray = $_procObj->saveRegistrationFormData($insertArray,$this->data,$this);
		    }
		}

		$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('fe_users',$insertArray);
        $user_id = $GLOBALS['TYPO3_DB']->sql_insert_id();

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_mmforum_userfields', 'deleted=0');

        if($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {

			$userField = t3lib_div::makeInstance('tx_mmforum_userfield');
			$userField->init($this->userLib, $this->cObj);

			while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$userField->get($arr);

				$value = trim($this->piVars['userfields'][$userField->getUID()]);
				$userField->setForUser($user_id, $value, $this->getStoragePID());

				if($userField->isUsingExistingField()) $this->data[$userField->getLinkedUserField()] = $value;
			}
		}

		/*
        if(is_array($this->piVars['userfields'])) {
            foreach($this->piVars['userfields'] as $uid => $value) {
                if(strlen(trim($value))==0) continue;

                if($this->piVars['userfields_exist'][$uid]) {
                    if($this->userLib->getUserfieldUsesExistingField($uid)) {
                        $updateArray = array(
                            'tstamp'                                    => time(),
                            $this->piVars['userfields_exist'][$uid]    => $value
                        );
                        $GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users','uid='.$user_id,$updateArray);
                    }
                } else {
                    $insertArray = array(
                        'user_id'       => $user_id,
                        'field_id'      => $uid,
                        'field_value'   => $value,
                        'pid'           => $this->conf['storagePID'],
                        'tstamp'        => time(),
                        'crdate'        => time(),
                    );
                    $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_userfields_contents',$insertArray);
                }
            }
        }*/

		if (mysql_error()) return 0;
		return 1;
	}

	/**
	 * Shows the registration form.
	 * @param  array  $marker An array of markers, with which the template is to
	 *                        be filled.
	 * @return string         The registration form.
	 */
	function showRegForm($marker, $conf) {
        $template = $this->cObj->getSubpart($this->tmpl, "###TEMPLATE_CREATE###");
		$marker["###FORM_URL###"]= $this->pi_getPageLink($GLOBALS["TSFE"]->id);

		$marker["###FORM_NAME###"]=$this->extKey."[reg]";

		if(t3lib_extMgm::isLoaded('captcha') && $this->conf['useCaptcha']) {
			$marker['###CAPTCHA_IMAGE###'] = '<img src="'.t3lib_extMgm::siteRelPath('captcha').'captcha/captcha.php" alt="" />';
		} else {
			$template = $this->cObj->substituteSubpart($template, '###CAPTCHA###', '');
		}

		$fields = 'username,name,tx_mmforum_occ,city,tx_mmforum_interests,www,email';
		$fields = explode(',',$fields);

		$requiredFields = t3lib_div::trimExplode(',',$this->conf['required.']['fields']);
		$requiredFields[] = 'username';
		$requiredFields[] = 'password';
		$requiredFields[] = 'passwordrepeat';

		$llMarker = array(
			'###LABEL_REQUIRED###'			=> $this->pi_getLL('reg.requiredNote'),
			'###LABEL_USERNAME###'			=> $this->pi_getLL('reg.username'),
			'###LABEL_PASSWORD###'			=> $this->pi_getLL('reg.password'),
			'###LABEL_PASSWORDREPEAT###'	=> $this->pi_getLL('reg.passwordRepeat'),
			'###LABEL_NAME###'				=> $this->pi_getLL('reg.name'),
			'###LABEL_TX_MMFORUM_OCC###'	=> $this->pi_getLL('reg.profession'),
			'###LABEL_CITY###'				=> $this->pi_getLL('reg.location'),
			'###LABEL_TX_MMFORUM_INTERESTS###'			=> $this->pi_getLL('reg.interests'),
			'###LABEL_WWW###'				=> $this->pi_getLL('reg.website'),
			'###LABEL_CREATE###'			=> $this->pi_getLL('reg.create'),
			'###LABEL_EMAIL###'				=> $this->pi_getLL('reg.email'),
			'###LABEL_CAPTCHA###'			=> $this->pi_getLL('reg.captcha'),
			'###LABEL_REGISTRATION###'		=> $this->pi_getLL('reg.title'),

            '###IMG_MAIL###'				=> tx_mmforum_pi1::createButton('email',array(),0,true,'',true),
			'###IMG_ICQ###'					=> tx_mmforum_pi1::createButton('icq',array(),0,true,'',true),
			'###IMG_AIM###'					=> tx_mmforum_pi1::createButton('aim',array(),0,true,'',true),
			'###IMG_YIM###'					=> tx_mmforum_pi1::createButton('yim',array(),0,true,'',true),
			'###IMG_MSN###'					=> tx_mmforum_pi1::createButton('msn',array(),0,true,'',true),
            '###IMG_SKYPE###'			   	=> tx_mmforum_pi1::createButton('skype',array(),0,true,'',true),
		);

			/* Highlight required fields */
		foreach($requiredFields as $field) {
			$llMarker['###LABEL_'.strtoupper($field).'###'] = $this->cObj->wrap($llMarker['###LABEL_'.strtoupper($field).'###'], $this->conf['required.']['fieldWrap']);
		}

			/* Restore old markers for backwards compatibility */
		$llMarker['###LABEL_PROFESSION###']		= $llMarker['###LABEL_TX_MMFORUM_OCC###'];
		$llMarker['###LABEL_LOCATION###']		= $llMarker['###LABEL_CITY###'];
		$llMarker['###LABEL_INTERESTS###']		= $llMarker['###LABEL_TX_MMFORUM_INTERESTS###'];
		$llMarker['###LABEL_WEBSITE###']		= $llMarker['###LABEL_WWW###'];

		$marker = array_merge($marker,$llMarker);

        $userField_template = $this->cObj->getSubpart($template, '###USERFIELDS###');
        $userField_content  = '';

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_userfields',
            'deleted=0',
            '','sorting DESC'
        );

        if($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {

			$userField = t3lib_div::makeInstance('tx_mmforum_userfield');
			$userField->init($this->userLib, $this->cObj);

			while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$userField->get($arr);

				if (($this->conf['showOnlyRequiredUserfields'] == 1 && $userField->isRequired()) || ($this->conf['showOnlyRequiredUserfields'] != 1)) {
  				$label = $userField->getRenderedLabel();

                  if($userField->isRequired())
                      $label = $this->cObj->wrap($label, $this->conf['required.']['fieldWrap']);

  				$input = $userField->getRenderedInput($this->piVars['userfields'][$userField->getUID()]);
  				if($input === null) $input = $this->cObj->getSubpart($userField_template, '###DEFUSERFIELD###');
  				$userField_thisTemplate = $this->cObj->substituteSubpart($userField_template, '###DEFUSERFIELD###', $input);

  				$userFields_marker = array(
                      '###USERFIELD_LABEL###'     => $label,
                      '###USERFIELD_UID###'       => $userField->getUID(),
                      '###USERFIELD_NAME###'      => 'tx_mmforum_pi2[userfields]['.$userField->getUID().']',
                      '###USERFIELD_VALUE###'     => $this->piVars['userfields'][$userField->getUID()]?$this->piVars['userfields'][$arr['uid']]:'',
  					          '###USERFIELD_ERROR###'		=> $marker['userfield_error'][$userField->getUID()]
                  );
          if ($userFields_marker['###USERFIELD_ERROR###']) {
            $userFields_marker['###USERFIELD_ERROR###'] = $this->cObj->wrap($userFields_marker['###USERFIELD_ERROR###'], $this->conf['errorwrap']);
          }
          $userFields_content .= $this->cObj->substituteMarkerArrayCached($userField_thisTemplate, $userFields_marker);
			  }
			}

			/*
			$parser = t3lib_div::makeInstance('t3lib_TSparser');
            while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $parser->setup = array();
                if(strlen($arr['config'])>0) {
                    $parser->parse($arr['config']);
                    $config = $parser->setup;
                } else $config = array();

                if($config['label']) $label = $this->cObj->cObjGetSingle($config['label'],$config['label.']);
                else $label = $arr['label'];

				if($config['required'])
					$label = $this->cObj->wrap($label, $this->conf['required.']['fieldWrap']);

                if($config['input']) {
                    $data = array(
                        'fieldvalue'        => $value
                    );
                    $tmpData = $this->cObj->data;
                    $this->cObj->data = $data;

                    $input = $this->cObj->cObjGetSingle($config['input'],$config['input.']);

                    $this->cObj->data = $tmpData;
                } else $input = $this->cObj->getSubpart($userField_template, '###DEFUSERFIELD###');
                $userField_thisTemplate = $this->cObj->substituteSubpart($userField_template, '###DEFUSERFIELD###', $input);

                if($config['datasource']) {
                    $label .= '<input type="hidden" name="tx_mmforum_pi2[userfields_exist]['.$arr['uid'].']" value="'.$config['datasource'].'" />';
                }

                $userFields_marker = array(
                    '###USERFIELD_LABEL###'     => $label,
                    '###USERFIELD_UID###'       => $arr['uid'],
                    '###USERFIELD_NAME###'      => 'tx_mmforum_pi2[userfields]['.$arr['uid'].']',
                    '###USERFIELD_VALUE###'     => $this->piVars['userfields'][$arr['uid']]?$this->piVars['userfields'][$arr['uid']]:'',
					'###USERFIELD_ERROR###'		=> $marker['userfield_error'][$arr['uid']]
                );
                $userFields_content .= $this->cObj->substituteMarkerArrayCached($userField_thisTemplate, $userFields_marker);
            }

            */

        }
        $template = $this->cObj->substituteSubpart($template, '###USERFIELDS###', $userFields_content);

		$template = $this->cObj->substituteMarkerArrayCached($template,$marker);
		return $template;
	}

	/**
	 * Validates the user input and outputs an error message in case some of the
	 * input data is invalid.
	 * @param  array  $marker The markers for the general registration form. If something
	 *                        is invalid, it is written into this array, and the error message
	 *                        is displayed at the proper position in the registration form.
	 * @return array          The marker array already submitted as parameter, if necessary filled
	 *                        with adequate error messages.
	 */
	function validate($marker)
	{
		$marker["fehler"] = 0;

		$this->data['username'] = trim($this->data['username']);
		$this->data['password'] = trim($this->data['password']);

			/* Check some deprecated field names for reasons of backwards
			 * compatibility. */
		if($this->data['beruf']) {
			$this->data['tx_mmforum_occ'] = $this->data['beruf'];
			unset($this->data['beruf']);
		} if($this->data['address']) {
			$this->data['city'] = $this->data['address'];
			unset($this->data['address']);
		} if($this->data['interessen']) {
			$this->data['tx_mmforum_interests'] = $this->data['interessen'];
			unset($this->data['interessen']);
		}

		$marker["###VALUE_username###"]			= $this->data['username'];
		$marker["###VALUE_password###"]			= "";
		$marker["###VALUE_password_again###"]	= "";
		$marker["###VALUE_name###"]				= $this->data['name'];
		$marker["###VALUE_beruf###"]			= $this->data['tx_mmforum_occ']?$this->data['tx_mmforum_occ']:$this->data['beruf'];
		$marker["###VALUE_tx_mmforum_occ###"]	= $this->data['tx_mmforum_occ']?$this->data['tx_mmforum_occ']:$this->data['beruf'];
		$marker["###VALUE_address###"]			= $this->data['city']?$this->data['city']:$this->data['address'];
		$marker["###VALUE_city###"]				= $this->data['city']?$this->data['city']:$this->data['address'];
		$marker["###VALUE_interessen###"]		= $this->data['tx_mmforum_interests']?$this->data['tx_mmforum_interests']:$this->data['interessen'];
		$marker["###VALUE_tx_mmforum_interests###"]		= $this->data['tx_mmforum_interests']?$this->data['tx_mmforum_interests']:$this->data['interessen'];
		$marker["###VALUE_www###"]				= $this->data['www'];
		$marker["###VALUE_email###"]			= $this->data['email'];
		$marker["###VALUE_msn###"]				= $this->data['msn'];
		$marker["###VALUE_yim###"]				= $this->data['yim'];
		$marker["###VALUE_aim###"]				= $this->data['aim'];
		$marker["###VALUE_icq###"]				= $this->data['icq'];
        $marker["###VALUE_skype###"]			= $this->data['skype'];

			/*
			 * Check captcha
			 */
		if(t3lib_extMgm::isLoaded('captcha') && $this->conf['useCaptcha']) {
			session_start();
			if($this->data['captcha'] != $_SESSION['tx_captcha_string']) {
				$marker['###ERROR_captcha###'] = $this->cObj->wrap($this->pi_getLL('error.captcha'), $this->conf['errorwrap']);
				$marker['fehler'] = 1;
			}
			$_SESSION['tx_captcha_string'] = '';
		}

			/*
			 * Check username
			 * A username must be at least 3 characters long and has a maximum length of 30 chars.
			 * There must be no invalid chars.
			 */
		if ((strlen($this->data['username']) < $this->conf['username_minLength']) || (strlen($this->data['username']) > $this->conf['username_maxLength'])) {
			$marker["###ERROR_username###"] = $this->cObj->wrap($this->pi_getLL('error.usernameLength'), $this->conf['errorwrap']);
			$marker["fehler"] = 1;
		}

		if($this->conf['username_pattern']) {
			$username_pattern			= $this->conf['username_pattern'];
			$username_useMatchPattern	= true;
		} else {
			$username_pattern			= '/[^'.$this->conf['username_allowed'].']/i';
			$username_useMatchPattern	= false;
		}

        if ((preg_match_all($username_pattern,$this->data['username'],$matches) && !$username_useMatchPattern) ||
		    (!preg_match_all($username_pattern,$this->data['username'],$matches) && $username_useMatchPattern)) {
			$matches[0] = array_unique($matches[0]);
            $marker["###ERROR_username###"] = $this->cObj->wrap($this->pi_getLL('error.usernameChars').($username_useMatchPattern?'':' ('.implode(', ',$matches[0]).')'), $this->conf['errorwrap']);
			$marker["fehler"] = 1;
			$marker["###VALUE_username###"] = str_replace('"','&quot;',$marker['###VALUE_username###']);
		}

			/* Checks if username already exists in database */
		if (!$marker["fehler"]) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',
				'fe_users',
				'username LIKE "'.$this->data['username'].'" AND deleted=0 AND pid='.$this->conf['userPID']
			);
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
				$marker["###ERROR_username###"] = $this->cObj->wrap($this->pi_getLL('error.usernameExists'), $this->conf['errorwrap']);
				$marker["fehler"] = 1;
			}
		}

			/* Check password, first if the two entered password match, then for
			 * length. */
		if ($this->data["password"] != $this->data["password_again"]) {
			$marker["###ERROR_password###"] = $this->cObj->wrap($this->pi_getLL('error.passwordMismatch'), $this->conf['errorwrap']);
			$marker["fehler"] = 1;
		} if (strlen($this->data['password'])<6) {
			$marker["###ERROR_password###"] = $this->cObj->wrap($this->pi_getLL('error.passwordLength'), $this->conf['errorwrap']);
			$marker["fehler"] = 1;
		}

			/* Validate user defined fields */
			/* Check required user fields */
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*', 'tx_mmforum_userfields', 'deleted=0'
		);

		$userField = t3lib_div::makeInstance('tx_mmforum_userfield');
		$userField->init($this->userLib, $this->cObj);

		while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

			$userField->get($arr);

			$value		= $this->piVars['userfields'][$userField->getUID()];
			$validate	= $userField->isValid($value);

			if(!$validate) {
				$marker['fehler'] = 1;
				$marker['userfield_error'][$arr['uid']] = $this->pi_getLL('error-userfieldEmpty');
			}

  		if ((intval($userField->data['uniquefield']) === 1) &&
            !($userField->isUnique($value,$userField->data['config_parsed']['datasource']))) {
        $marker['fehler'] = 1;
  			$marker['userfield_error'][$arr['uid']] = $this->pi_getLL('error-userfieldNotUnique');
      }

		}

			/* Include hooks */
	    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['validateFormData'])) {
	        foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['validateFormData'] as $_classRef) {
	            $_procObj = & t3lib_div::getUserObj($_classRef);
	            $marker = $_procObj->validateRegistrationFormData($marker,$this->data,$this);
	        }
	    }

		return $marker;
	}

	/**
	 * Creates the marker array for the general registration form from the internal
	 * array of already made user input data.
	 * @return array  The marker array for the general registration form.
	 */
	function makeMarker()
	{
		$marker = array();

		$marker["###ERROR_username###"]			= "";
		$marker["###ERROR_password###"]			= "";
		$marker["###ERROR_name###"]				= "";
		$marker["###ERROR_email###"]			= "";
		$marker['###ERROR_captcha###']			= "";
		$marker['###ERROR_tx_mmforum_occ###']	= "";
		$marker['###ERROR_tx_mmforum_interests###']			= "";
		$marker['###ERROR_www###']				= "";
		$marker['###ERROR_city###']				= "";
		$marker["###HIDDENFIELDS###"]			= "";

		$marker["###VALUE_username###"]			= $this->data['username'];
		$marker["###VALUE_password###"]			= $this->data['password'];
		$marker["###VALUE_password_again###"]	= $this->data['password_again'];
		$marker["###VALUE_name###"]				= $this->data['name'];
		$marker["###VALUE_beruf###"]			= $this->data['beruf'];
		$marker["###VALUE_address###"]			= $this->data['address'];
		$marker["###VALUE_interessen###"]		= $this->data['interessen'];
		$marker["###VALUE_www###"]				= $this->data['www'];
		$marker["###VALUE_email###"]			= $this->data['email'];
		$marker["###VALUE_msn###"]				= $this->data['msn'];
		$marker["###VALUE_yim###"]				= $this->data['yim'];
		$marker["###VALUE_aim###"]				= $this->data['aim'];
		$marker["###VALUE_icq###"]				= $this->data['icq'];
        $marker["###VALUE_skype###"]			= $this->data['skype'];
        $marker["###VALUE_city###"]				= $this->data['city'];
        $marker["###VALUE_tx_mmforum_occ###"]			= $this->data['tx_mmforum_occ'];
        $marker["###VALUE_tx_mmforum_interests###"]			= $this->data['tx_mmforum_interests'];

			// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['makeMarker'])) {
		    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['makeMarker'] as $_classRef) {
		        $_procObj = & t3lib_div::getUserObj($_classRef);
		        $marker = $_procObj->processMakeMarker($marker,$this->data,$this);
		    }
		}

		return $marker;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi2/class.tx_mmforum_pi2.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi2/class.tx_mmforum_pi2.php']);
}

?>
