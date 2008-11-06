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

require_once(PATH_tslib."class.tslib_pibase.php");

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
class tx_mmforum_pi2 extends tslib_pibase {
	var $prefixId = "tx_mmforum_pi2";		// Same as class name
	var $scriptRelPath = "pi2/class.tx_mmforum_pi2.php";	// Path to this script relative to the extension dir.
	var $extKey = "mm_forum";	// The extension key.

	/**
	 * The plugin's main function.
	 * Evaluates Get/Post-parameters and accordingly displays registrations
	 * forms and saves or activates users.
	 * @param  string $content The plugin content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The plugin content
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_USER_INT_obj=1;	// Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!

		$this->tmpl = $conf["templateFile"];
		$this->tmpl = $this->cObj->fileResource($this->tmpl);
		#$this->data = $_POST['mm_forum']['reg'];
        $this->data = $this->piVars['reg'];
		
        $this->conf['path_img'] = str_replace("EXT:mm_forum/",t3lib_extMgm::siteRelPath('mm_forum'),$this->conf['path_img']);
        $this->conf['path_smilie'] = str_replace("EXT:mm_forum/",t3lib_extMgm::siteRelPath('mm_forum'),$this->conf['path_smilie']);

        if ($_GET['user_hash']) $this->data['action'] = "hash_pruefen";

		if ($GLOBALS['TSFE']->fe_user->user['uid']) $content = $this->pi_getLL('error.alreadyLoggedIn');
		else {
			switch ($this->data['action']) {
				// Activate user
				case "hash_pruefen":
					$content = $this->check_hash($_GET['user_hash']);
					break;

				// Create user
				case "user_anlegen":
					$marker = $this->makeMarker();
					$marker = $this->validate($marker);

					if($marker["fehler"]) {
						// There should be any mistakes
							$content = $this->showRegForm($marker, $conf);
					} else {
						// Sava data
							$ok = $this->saveData();
							$ok = $this->sendEmail();
							$content = $this->showMailVersand();
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
	function sendEmail()
	{
		$header .= "From: ".$this->conf['supportMail']."\n";
		$header .= "X-Mailer: PHP/" . phpversion(). "\n";
		$header .= "X-Sender-IP: ".getenv("REMOTE_ADDR")."\n";
		$header .= "Content-type: text/plain;charset=".$GLOBALS['TSFE']->renderCharset."\n";

		$linkParams = array(
			'user_hash'		=> $this->data['reghash']
		);
		$link = $this->pi_getPageLink($GLOBALS['TSFE']->id,'',$linkParams);
        $link = tx_mmforum_pi1::getAbsUrl($link);
        
		$host = t3lib_div::getIndpEnv('HTTP_HOST');

		$marker = $this->makeMarker();
		#$marker['###EMAIL_URL###']				= (strlen($conf['email.']['activateUrlOverride'])>0)?$conf['email.']['activateUrlOverride'].'&user_hash='.$this->data['reghash']:"http://$host/$link";
        $marker['###EMAIL_URL###']              = $link;
		$marker['###LABEL_HELLO###']			= $this->cObj->substituteMarker($this->pi_getLL('msg.hello'),'###USERNAME###',$marker['###VALUE_username###']);
		$marker['###LABEL_PLEASEACTIVATE###']	= $this->cObj->substituteMarker($this->pi_getLL('msg.pleaseActivate'),'###SITENAME###',$this->conf['siteName']);
		$marker['###LABEL_YOURS###']			= $this->cObj->substituteMarker($this->pi_getLL('msg.yoursPlaintext'),'###TEAM###',$this->conf['teamName']);

		$vorlage = $this->cObj->getSubpart($this->tmpl, "###EMAIL_VALIDATION###");
		$vorlage = $this->cObj->substituteMarkerArrayCached($vorlage,$marker);

		$subject = $this->cObj->substituteMarker($this->pi_getLL('msg.subject'),'###SITENAME###',$this->conf['siteName']);

        t3lib_div::plainMailEncoded($this->data['email'],$subject,$vorlage,$header) ;
	}

	/**
	 * Shows a message indicationg the registration was successful and that a
	 * mail requesting an activation has been sent.
	 * @return string The message
	 */
	function showMailVersand()
	{
		$marker = $this->makeMarker();
		$vorlage = $this->cObj->getSubpart($this->tmpl, "###TEMPLATE_CREATE_LOGIN_OK###");
		
		$marker['###LABEL_THANKYOU###']		= $this->pi_getLL('msg.thankYou');
		$marker['###LABEL_EMAILISSENT###']	= $this->cObj->substituteMarker($this->pi_getLL('msg.emailIsSent'),'###EMAIL###','<strong>'.$marker['###VALUE_email###'].'</strong>');
		$marker['###LABEL_YOURS###']		= $this->cObj->substituteMarker($this->pi_getLL('msg.yours'),'###TEAM###',$this->conf['teamName']);
		
		$vorlage = $this->cObj->substituteMarkerArrayCached($vorlage,$marker);
		return $vorlage;
	}

	/**
	 * Activates a user.
	 * On registration, a so called regHash is generated, which is individual for
	 * each user, since it is the MD5-Hash of the date of registration and the username.
	 * This method removes the disabled flag from the user record, allowing him to
	 * log in on the page.
	 * @param  string $hash The regHash to be checked
	 * @return string       A message indicating the success of the activation
	 */
	function check_hash($hash)
	{
		// Check hash on validity
		$hash = mysql_escape_string($hash);
		
		if (preg_match("/[\"|'|~|\\$|\|]/i",$hash)) {
			$vorlage = $this->cObj->getSubpart($this->tmpl, "###FEHLER###");
			return $vorlage;
		}

		// Load user record from database
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_users','tx_mmforum_reg_hash="'.$hash.'"');

		// If user records exists exactly once, continue...
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)==1) {
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

			// Activate user
			$updateArray = array(
				'disable'					=> 0,
				'tx_mmforum_reg_hash'	=> ''
			);

			$res2 = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users',"tx_mmforum_reg_hash='$hash'",$updateArray);
			
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['activateUser'])) {
			    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['activateUser'] as $_classRef) {
			        $_procObj = & t3lib_div::getUserObj($_classRef);
			        $_procObj->activateUser($row,&$this);
			    }
			}
			
			// Output error message in case of failure
			if (mysql_error()) {
				$vorlage = $this->cObj->getSubpart($this->tmpl, "###FEHLER###");
					$marker = array(
					'###LABEL_ERRORGENERAL###'		=> $this->pi_getLL('error.generalError'),
					'###LABEL_PLEASECONTACT###'		=> $this->cObj->substituteMarker($this->pi_getLL('error.support'),'###SUPPORTMAIL###',str_replace('@',' [at] ',$this->conf['supportMail'])),
				);
				return $vorlage;
			}

			// Output message to user
			$marker = array();
			$marker["###VALUE_username###"] = $row["username"];
			$vorlage = $this->cObj->getSubpart($this->tmpl, "###TEMPLATE_VALIDATION_OK###");
			
			$marker['###LABEL_HELLO###']		= $this->cObj->substituteMarker($this->pi_getLL('msg.hello'),'###USERNAME###',$marker['###VALUE_username###']);
			$marker['###LABEL_ACTIVATED###']	= $this->cObj->substituteMarker($this->pi_getLL('msg.activated'),'###SITENAME###',$this->conf['siteName']);
			$marker['###LABEL_YOURS###']		= $this->cObj->substituteMarker($this->pi_getLL('msg.yours'),'###TEAM###',$this->conf['teamName']);
			
			$vorlage = $this->cObj->substituteMarkerArrayCached($vorlage,$marker);
			return $vorlage;
		}
		// If user records exists more than one time or not at all, abort.
		else {
			$vorlage = $this->cObj->getSubpart($this->tmpl, "###FEHLER###");
			
			$marker = array(
				'###LABEL_ERRORGENERAL###'		=> $this->pi_getLL('error.generalError'),
				'###LABEL_PLEASECONTACT###'		=> $this->cObj->substituteMarker($this->pi_getLL('error.support'),'###SUPPORTMAIL###',str_replace('@',' [at] ',$this->conf['supportMail'])),
			);
			$vorlage = $this->cObj->substituteMarkerArrayCached($vorlage,$marker);
			
			return $vorlage;
		}
	}

	/**
	 * Saves the data submitted during registration to database.
	 * @return int  1 in case of success, otherwise 0
	 */
	function saveData()
	{
		$usergroup	= $this->conf['userGroup'];
		$pid		= $this->conf['userPID'];

		// uid-auslesen, da in TYPO3 dieses Feld kein Autoincrement ist ;-)
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','fe_users','','','uid DESC','1');
		
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) $uid = 1;
		else {
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

			if (!intval($row["uid"])) return 0;
			$uid = $row["uid"]+1;
		}

		$tmp = time().$this->data['username'];
		$reghash = md5($tmp);
		$reghash = substr($reghash, 1, 15);
		$this->data['reghash'] = $reghash;

        // If the extension kb_md5fepw is installed, encrypt password
        if(t3lib_extMgm::isLoaded('kb_md5fepw')) $this->data['password'] = md5($this->data['password']);
        
		$insertArray = array(
			'uid'				    => $uid,
			'pid'				    => $pid,
			'tstamp'			    => time(),
			'crdate'			    => time(),
			'username'			    => $this->data['username'],
			'password'			    => $this->data['password'],
			'usergroup'			    => $usergroup,
			'disable'			    => 1,
			'name'				    => $this->data['name'],
			'city'				    => $this->data['address'],
			'email'				    => $this->data['email'],
			'www'				    => $this->data['www'],
			'tx_mmforum_icq'	    => $this->data['icq'],
			'tx_mmforum_aim'	    => $this->data['aim'],
			'tx_mmforum_yim'	    => $this->data['yim'],
            'tx_mmforum_skype'	    => $this->data['skype'],
            'tx_mmforum_occ'        => $this->data['beruf'],
			'tx_mmforum_msn'	    => $this->data['msn'],
			'tx_mmforum_interests'  => $this->data['interessen'],
			'tx_mmforum_reg_hash'   => $reghash
		);
		
			// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['saveData'])) {
		    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['saveData'] as $_classRef) {
		        $_procObj = & t3lib_div::getUserObj($_classRef);
		        $insertArray = $_procObj->saveRegistrationFormData($insertArray,$this->data,&$this);
		    }
		}
		
		$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('fe_users',$insertArray);
        
        $user_id = $GLOBALS['TYPO3_DB']->sql_insert_id();
        
        if(is_array($this->piVars['userfields'])) {
            foreach($this->piVars['userfields'] as $uid => $value) {
                if(strlen(trim($value))==0) continue;
                
                if($this->piVars['userfields_exist'][$uid]) {
                    if($this->getUserfieldUsesExistingField($uid)) {
                        $updateArray = array(
                            'tstamp'                                    => time(),
                            $this->piVars['userfields_exist'][$uid]    => $value
                        );
                        #t3lib_div::debug($updateArray);
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
        }
		
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
        $vorlage = $this->cObj->getSubpart($this->tmpl, "###TEMPLATE_CREATE###");
		$marker["###FORM_URL###"]= $this->pi_getPageLink($GLOBALS["TSFE"]->id);

		$marker["###FORM_NAME###"]=$this->extKey."[reg]";
		
		if(t3lib_extMgm::isLoaded('captcha') && $this->conf['useCaptcha']) {
			$marker['###CAPTCHA_IMAGE###'] = '<img src="'.t3lib_extMgm::siteRelPath('captcha').'captcha/captcha.php" alt="" />';
		} else {
			$vorlage = $this->cObj->substituteSubpart($vorlage, '###CAPTCHA###', '');
		}
		
		$llMarker = array(
			'###LABEL_REQUIRED###'			=> $this->pi_getLL('reg.requiredNote'),
			'###LABEL_USERNAME###'			=> $this->pi_getLL('reg.username'),
			'###LABEL_PASSWORD###'			=> $this->pi_getLL('reg.password'),
			'###LABEL_PASSWORDREPEAT###'	=> $this->pi_getLL('reg.passwordRepeat'),
			'###LABEL_NAME###'				=> $this->pi_getLL('reg.name'),
			'###LABEL_PROFESSION###'		=> $this->pi_getLL('reg.profession'),
			'###LABEL_LOCATION###'			=> $this->pi_getLL('reg.location'),
			'###LABEL_INTERESTS###'			=> $this->pi_getLL('reg.interests'),
			'###LABEL_WEBSITE###'			=> $this->pi_getLL('reg.website'),
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
		$marker = array_merge($marker,$llMarker);
        
        $userField_template = $this->cObj->getSubpart($vorlage, '###USERFIELDS###');
        $userField_content  = '';
        
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_userfields',
            'deleted=0',
            '','sorting DESC'
        );
        
        if($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
            $parser = t3lib_div::makeInstance('t3lib_TSparser');
            while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $parser->setup = array();
                if(strlen($arr['config'])>0) {
                    $parser->parse($arr['config']);
                    $config = $parser->setup;
                } else $config = array();
                
                if($config['label']) $label = $this->cObj->cObjGetSingle($config['label'],$config['label.']);
                else $label = $arr['label'].':';
                
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
                    '###USERFIELD_VALUE###'     => $this->piVars['userfields'][$arr['uid']]?$this->piVars['userfields'][$arr['uid']]:''
                );
                $userFields_content .= $this->cObj->substituteMarkerArrayCached($userField_thisTemplate, $userFields_marker);
            }
        }
        $vorlage = $this->cObj->substituteSubpart($vorlage, '###USERFIELDS###', $userFields_content);
		
		$vorlage = $this->cObj->substituteMarkerArrayCached($vorlage,$marker);
		return $vorlage;
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

		if(is_array($this->data)){
			while(list($k,$v)=each($this->data)) {
				if($k !="username" AND $k !="password"){
					$this->data[$k] = htmlspecialchars($v);
				}
			}
		}


		$marker["###VALUE_username###"]			= $this->data['username'];
		$marker["###VALUE_password###"]			= "";
		$marker["###VALUE_password_again###"]	= "";
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

		/*
		 * Check captcha
		 */
		if(t3lib_extMgm::isLoaded('captcha') && $this->conf['useCaptcha']) {
			session_start();
			if($this->data['captcha'] != $_SESSION['tx_captcha_string']) {
				$marker['###ERROR_captcha###'] = $this->pi_getLL('error.captcha');
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
			$marker["###ERROR_username###"] = $this->pi_getLL('error.usernameLength');
			$marker["fehler"] = 1;
		}
		
		if($this->conf['username_pattern']) {
			$username_pattern			= $this->conf['username_pattern'];
			$username_useMatchPattern	= true;
		} else {
			$username_pattern			= '/[^'.preg_quote($this->conf['username_allowed']).']/i';
			$username_useMatchPattern	= false;
		}
		
        if ((preg_match_all($username_pattern,$this->data['username'],$matches) && !$username_useMatchPattern) ||
		    (!preg_match_all($username_pattern,$this->data['username'],$matches) && $username_useMatchPattern)) {
			$matches[0] = array_unique($matches[0]);
            $marker["###ERROR_username###"] = $this->pi_getLL('error.usernameChars').($username_useMatchPattern?'':' ('.implode(', ',$matches[0]).')');
			$marker["fehler"] = 1;
			$marker["###VALUE_username###"] = str_replace('"','&quot;',$marker['###VALUE_username###']);
		}

		// Checks if username already exists in database
		if (!$marker["fehler"]) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',
				'fe_users',
				'username LIKE "'.$this->data['username'].'" AND deleted=0 AND pid='.$this->conf['userPID']
			);
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
				$marker["###ERROR_username###"] = $this->pi_getLL('error.usernameExists');
				$marker["fehler"] = 1;
			}
		}

		// Validate email address
		if (!tx_mmforum_pi2::validate_email($this->data["email"])) {
			$marker["###ERROR_email###"] = $this->pi_getLL('error.emailInvalid');
			$marker["fehler"] = 1;
		}
		// Check if email address already exists in database
		else
		{
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'email',
				'fe_users',
				'email="'.$this->data['email'].'" AND deleted=0 AND pid='.$this->conf['userPID']
			);
			IF ($GLOBALS['TYPO3_DB']->sql_num_rows($res) >= 1) {
				$marker["###ERROR_email###"] = $this->pi_getLL('error.emailExists');
				$marker["fehler"] = 1;
			}
		}

		// Check password
		if ($this->data["password"] != $this->data["password_again"]) {
			$marker["###ERROR_password###"] = $this->pi_getLL('error.passwordMismatch');
			$marker["fehler"] = 1;
		}

		if (strlen($this->data['password'])<6) {
			$marker["###ERROR_password###"] = $this->pi_getLL('error.passwordLength');
			$marker["fehler"] = 1;
		}

		if (strlen($this->data['name'])<1) {
			$marker["###ERROR_name###"] = $this->pi_getLL('error.nameNone');
			$marker["fehler"] = 1;
		}
		
		// Include hooks
		    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['validateFormData'])) {
		        foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['validateFormData'] as $_classRef) {
		            $_procObj = & t3lib_div::getUserObj($_classRef);
		            $marker = $_procObj->validateRegistrationFormData($marker,$this->data,&$this);
		        }
		    }

		return $marker;
	}
	
	/**
	 * Validates an email address.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  string $email The email address to be validated
	 * @return bool          TRUE, if the email address is valid, otherwise FALSE
	 */
	function validate_email($email) {
	    if(preg_match('/\.\./',$email)) return false;
		$pattern = "/^[a-z0-9!#\$%\*\/\?\|\^\{\}`~&'\+\-=_]([a-z0-9!#\$%\*\/\?\|\^\{\}`~&'\+\-=_\.]*?)[a-z0-9!#\$%\*\/\?\|\^\{\}`~&'\+\-=_]@([a-z0-9-\.]+?)[a-z0-9]\.([a-z\.])+$/i";
		return preg_match($pattern,$email);
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

			// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['makeMarker'])) {
		    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['registration']['makeMarker'] as $_classRef) {
		        $_procObj = & t3lib_div::getUserObj($_classRef);
		        $marker = $_procObj->processMakeMarker($marker,$this->data,&$this);
		    }
		}

		return $marker;
	}
	
	/**
	 * Delivers a MySQL-WHERE query checking the records' PID.
	 * This allows it to exclusively select records from a very specific list
	 * of pages.
	 * 
	 * NOTE: This function is currently partially disabled.
	 *       Instead of defining the PIDs to be checked via the plugin's Starting
	 *       Point, the PID is in this version defined in the TS constant
	 *       plugin.tx_mmforum.storagePID
	 * 
	 * @param   string $tables The list of tables that are queried
	 * @return  string         The query, following the pattern " AND pid IN (...)"
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-03
	 */
	function getPidQuery($tables="") {
		if($this->conf['storagePID']==-1) return "";
		if($this->conf['storagePID']=="") return "";
		else {
			if($tables == "")
				return " AND pid='".$this->conf['storagePID']."'";
			
			$tables = t3lib_div::trimExplode(',',$tables);
			$query = "";
			
			foreach($tables as $table) {
				$query .= " AND $table.pid='".$this->conf['storagePID']."'";
			}
			return $query;
		}
		
		/*
		if(strlen(trim($this->conf['pidList']))==0) return "";
		if($tables == "") return " AND pid IN (".$this->conf['pidList'].")";
		
		$tables = t3lib_div::trimExplode(',',$tables);
		$query = "";
		
		foreach($tables as $table) {
			$query .= " AND $table.pid IN (".$this->conf['pidList'].")";
		}
		return $query;
		*/
	}
	
	/**
	 * Delivers the PID of newly created records.
	 * @return  int The PID of a record that is to be created.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-04-02
	 */
	function getFirstPid() {
		if($this->conf['storagePID'] == -1) return 0;
		if(!$this->conf['storagePID']) return 0;
		return intval($this->conf['storagePID']);
	}
    
    /**
     * Wrapper function for retrieval of language dependent strings.
     * This function overrides the parent pi_getLL function. This was introduced
     * in order to allow language variables using TypoScript (which was until now
     * not possible due to the dots used in the language indices) by accessing
     * the same language label with dashes indead of dots. This function allows this
     * without changing all pi_getLL calls in this class.
     * 
     * Furthermore, as of version 0.1.4, the function controls the use of
     * formal or informal language (which is mainly characterized by the use of the
     * german "Sie" or "Du").
     * 
     * @param   string $key The language key
     * @return  string      The language dependent label
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-10-05
     */
    function pi_getLL($key) {
        $key1   = $key;
        $key2   = str_replace('.','-',$key);
        
        if(parent::pi_getLL($key2)) {
        	if($this->conf['informal']) return parent::pi_getLL($key2.'-inf')?parent::pi_getLL($key2.'-inf'):parent::pi_getLL($key2);
        	return parent::pi_getLL($key2);	
        }
        else {
        	if($this->conf['informal']) return parent::pi_getLL($key1.'-inf')?parent::pi_getLL($key1.'-inf'):parent::pi_getLL($key1);
        	return parent::pi_getLL($key1);
        }
    }
    
    function getUserfieldUsesExistingField($uid) {
		$uid = intval($uid);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tx_mmforum_userfields',
			'uid='.$uid.' AND deleted=0 AND hidden=0'
		);
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) return false;
		
		$arr		= $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$parser		= t3lib_div::makeInstance('t3lib_TSparser');
		$parser->parse($arr['config']);
		$config		= $parser->setup;
		
		return $config['datasource']?true:false;
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi2/class.tx_mmforum_pi2.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi2/class.tx_mmforum_pi2.php"]);
}

?>