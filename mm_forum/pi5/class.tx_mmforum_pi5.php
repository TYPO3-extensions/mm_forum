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
 *   64: class tx_mmforum_pi5 extends tslib_pibase
 *   78:     function main($content,$conf)
 *  107:     function list_userdata ($content,$conf)
 *  232:     function write_userdata($content,$conf)
 *  324:     function avatar_upload($content , $conf)
 *  371:     function validate_email($email)
 *  389:     function change_pass($content,$conf)
 *  476:     function getStoragePIDQuery($tables="")
 *
 * TOTAL FUNCTIONS: 9
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_base.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_postparser.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/user/class.tx_mmforum_usermanagement.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_pi1.php');

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

	var $userLib		= null;

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
        $conf = $this->conf;

			/* Instantiate user management library */
		$this->userLib = t3lib_div::makeInstance('tx_mmforum_usermanagement');

        if($GLOBALS['TSFE']->fe_user->user['uid'])
		    $content = $this->list_userdata($content,$conf);
        else {
		    $template = $this->cObj->fileResource($conf['template']);
		    $template = $this->cObj->getSubpart($template, "###ERROR###");
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
	 * @param   array  $conf    The plugin's configuration vars
	 * @return  string          The content
	 */
	function list_userdata ($content,$conf) {
		if (t3lib_div::_GP("action") == "change_data")			   $this->write_userdata($content,$conf);
		if (t3lib_div::_GP("action") == "avatar_upload")	$content = $this->avatar_upload($content,$conf);
		if (t3lib_div::_GP("action") == "change_pass")	$content = $this->change_pass($content,$conf);

		$template = $this->cObj->fileResource($conf['template']);
		$template = $this->cObj->getSubpart($template, "###MAIN###");
		$extrafields = 'uid,username,crdate,tx_mmforum_posts,tx_mmforum_avatar,image,';
		$fields = $extrafields.str_replace(' ', '', $conf['userFields']);
		$where = 'uid = "'.$GLOBALS['TSFE']->fe_user->user['uid'].'"';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_users',$where,$groupBy='');
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

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

			'###PMNOTIFY_0###'           => ($row['tx_mmforum_pmnotifymode']==0)?'checked="checked"':'',
			'###PMNOTIFY_1###'           => ($row['tx_mmforum_pmnotifymode']==1)?'checked="checked"':'',
			'###PMNOTIFY_2###'           => ($row['tx_mmforum_pmnotifymode']==2)?'checked="checked"':'',

			'###SETTINGS_ICON###'        => $this->cObj->cObjGetSingle($this->conf['icons.']['settings'],$this->conf['icons.']['settings.']),
			'###SETTINGS2_ICON###'       => $this->cObj->cObjGetSingle($this->conf['icons.']['settings'],$this->conf['icons.']['settings2.']),
			'###AVATAR_ICON###'          => $this->cObj->cObjGetSingle($this->conf['icons.']['settings'],$this->conf['icons.']['avatar.']),
			'###PASSWORD_ICON###'        => $this->cObj->cObjGetSingle($this->conf['icons.']['settings'],$this->conf['icons.']['password.']),

			'###FORMACTION###'           => $this->pi_getPageLink($GLOBALS['TSFE']->id)
		);

		// Create marker array, field names are retrieved from TypoScript
		$data = explode(',', $fields);
		$required = t3lib_div::trimExplode(',',$this->conf['required.']['fields']);

		foreach ($data as $k=>$v) {
			$marker['###'.strtoupper($v).'###'] = isset($this->piVars[$v])?$this->piVars[$v]:$row[$v];
			$marker['###DESCR_'.strtoupper($v).'###'] = $this->pi_getLL($v);
			if(in_array($v, $required)) {
				$marker['###DESCR_'.strtoupper($v).'###'] = $this->cObj->wrap($marker['###DESCR_'.strtoupper($v).'###'], $this->conf['required.']['fieldWrap']);
			}
		}

		// Some special fields
		$marker['###CRDATE###']				= strftime($conf['date'], $row['crdate']);
        $marker['###ACTIONLINK###']         = '';
		$marker['###SIGNATUR_PREVIEW###']	= tx_mmforum_postparser::main($this,$conf,$row['tx_mmforum_user_sig'],'textparser');

		// Avatar
		$imgTSConfig = $conf['avatar.'];

        if($row['tx_mmforum_avatar'])
            $imgTSConfig['file'] = $conf['path_avatar'].$row['tx_mmforum_avatar'];
        elseif($row['image']) {
            if(strstr($row['image'],',') !== false) {
            	$avatarArray = t3lib_div::trimExplode(',',$row['image']);
            	$row['image'] = $avatarArray[0];
            }

            if(file_exists('uploads/pics/'.$row['image']))
            	$imgTSConfig['file'] = 'uploads/pics/'.$row['image'];
            elseif(file_exists('uploads/tx_srfeuserregister/'.$row['image']))
            	$imgTSConfig['file'] = 'uploads/tx_srfeuserregister/'.$row['image'];
        }
		$marker['###AVATAR###'] =  $this->cObj->IMAGE($imgTSConfig);

		if ($row['tx_mmforum_avatar'] || $row['image']) {
			$marker['###AVATAR_DEL###'] = '<input type="checkbox"  name="'.$this->prefixId.'[del_avatar]" />'.$this->pi_getLL('delete_avatar');
		} else $marker['###AVATAR_DEL###'] = '';

		// Language markers
		$dataLL = Array ('descr_signatur_preview', 'send', 'about','filename', 'descr_date');
		foreach ($dataLL as $k=>$v) {
			$marker['###'.strtoupper($v).'###'] = $this->pi_getLL($v);
		}

        // User fields
        $userField_template = $this->cObj->getSubpart($template, "###USERFIELDS###");
        $userField_content  = '';

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_userfields',
            'deleted=0 AND hidden=0',
            '',
            'sorting DESC'
        );

        $parser  = t3lib_div::makeInstance('t3lib_TSparser');
        while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            if(isset($this->piVars['userfield'][$arr['uid']])) {
            	$value = $this->piVars['userfield'][$arr['uid']];
            } else {
				$res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
	                'field_value',
	                'tx_mmforum_userfields_contents',
	                'field_id='.$arr['uid'].' AND user_id='.$row['uid']
	            );
	            if($GLOBALS['TYPO3_DB']->sql_num_rows($res2)>0) list($value) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res2);
	            else $value = '';
			}

            $parser->setup = array();
            if(strlen($arr['config'])>0) {
                $parser->parse($arr['config']);
                $config = $parser->setup;
            } else $config = array();

            if($config['label']) $label = $this->cObj->cObjGetSingle($config['label'],$config['label.']);
            else $label = $arr['label'];

			if($config['required']) $label = $this->cObj->wrap($label, $this->conf['required.']['fieldWrap']);

            if($config['datasource']) {
            	$value = isset($this->piVars['userfield'][$arr['uid']])?$this->piVars['userfield'][$arr['uid']]:$row[$config['datasource']];
            	$label .= '<input type="hidden" name="tx_mmforum_pi5[userfield_exists]['.$arr['uid'].']" value="'.$config['datasource'].'" />';
            }

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

            $userField_marker = array(
				'###USERFIELD_LABEL###' => $label,
				'###USERFIELD_VALUE###' => $value,
				'###USERFIELD_UID###'   => $arr['uid'],
				'###USERFIELD_NAME###'  => 'tx_mmforum_pi5[userfield]['.$arr['uid'].']',
				'###USERFIELD_ERROR###'	=> isset($this->userfield_error[$arr['uid']]) ? $this->cObj->wrap($this->userfield_error[$arr['uid']], $conf['userFields.']['wrap']) : ''
            );
            $userField_content .= $this->cObj->substituteMarkerArrayCached($userField_thisTemplate, $userField_marker);
        }
        $template = $this->cObj->substituteSubpart($template, "###USERFIELDS###", $userField_content);

			// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['editProfilMarkerArray'])) {
		    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['editProfilMarkerArray'] as $_classRef) {
		        $_procObj = & t3lib_div::getUserObj($_classRef);
		        $marker = $_procObj->processProfilMarkerArray($marker,$this->cObj);
		    }
		}

		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		return $content;
	}

	function &getTSParser() {
		if($this->parser) return $this->parser;
		else {
			$this->parser = t3lib_div::makeInstance('t3lib_TSparser');
			return $this->parser;
		}
	}

	function getUserFieldIsRequired($uid) {
		$uid = intval($uid);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tx_mmforum_userfields',
			'uid='.$uid.' AND deleted=0 AND hidden=0'
		);
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) return false;

		$arr		= $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$parser		= $this->getTSParser();
		$parser->parse($arr['config']);
		$config		= $parser->setup;

		return $config['required']?true:false;
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
		$parser		= $this->getTSParser();
		$parser->parse($arr['config']);
		$config		= $parser->setup;

		return $config['datasource']?true:false;
	}

	/**
	 * Writes the changes made by the user to the database
	 * @author  Georg Ringer <typo3@ringerge.org>
     * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-05-15
	 * @param   string $content The plugin content
	*  @param   array  $conf    The plugin's configuration vars
	 * @return  string          The content
	 */
	function write_userdata($content,$conf) {

    $template = $this->cObj->fileResource($conf['template']);
		$template = $this->cObj->getSubpart($template, "###ERROR###");

		$fields = str_replace(' ', '', $conf['userFields']);
		$data = explode(',', $fields);

		foreach ($data as $k=>$v) {
			$updateArr[$v] = $this->piVars[$v];
		}

			/* Check required user fields */
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*', 'tx_mmforum_userfields', 'deleted=0'
		);

		$userField = t3lib_div::makeInstance('tx_mmforum_userfield');
		$userField->init($this->userLib, $this->cObj);

		while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

			$userField->get($arr);

			$value		= $this->piVars['userfield'][$userField->getUID()];
			$validate	= $userField->isValid($value);

			if(!$validate) {
				$requiredMissing = true;
				$this->userfield_error[$arr['uid']] = $this->pi_getLL('error-userfieldEmpty');
			}

			if (   !($this->userfield_error[$arr['uid']])
				&&  (intval($userField->data['uniquefield']) === 1)
			    && !($userField->isUnique($value,$userField->data['config_parsed']['datasource']))) {
				$this->userfield_error[$arr['uid']] = $this->pi_getLL('error-userfieldNotUnique');
				$error = 1;
			}

		}

		if($requiredMissing)
			$error = 1;

		// If no error occurred...
		if ($error == 0) {

			$where = 'uid = '.$GLOBALS['TSFE']->fe_user->user['uid'];

        	     // Include hooks
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['editProfilUpdateArray'])) {
			    foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['display']['editProfilUpdateArray'] as $_classRef) {
			        $_procObj = & t3lib_div::getUserObj($_classRef);
			        $updateArr = $_procObj->processProfilUpdateArray($updateArr, $this->cObj);
			    }
			}
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users',$where,$updateArr);

			// Save user fields
	        if(is_array($this->piVars['userfield'])) {
	            foreach($this->piVars['userfield'] as $uid => $value) {
	                if(strlen(trim($value))==0) continue;

	                $uid		= intval($uid);
	                #$value		= mysql_escape_string($value);		// Escaping the string is not necessary since this is done by the TYPO3_DB class

	                if($this->piVars['userfield_exists'][$uid]) {
	                	if($this->getUserfieldUsesExistingField($uid)) {
	                		$updateArray = array(
	                			$this->piVars['userfield_exists'][$uid]		=> $value,
	                			'tstamp'									=> time()
	                		);
	                		$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
	                			'fe_users',
	                			'uid='.$GLOBALS['TSFE']->fe_user->user['uid'].' AND deleted=0',
	                			$updateArray
	                		);
	                	}
	                } else {
		                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		                    '*',
		                    'tx_mmforum_userfields_contents',
		                    'user_id='.$GLOBALS['TSFE']->fe_user->user['uid'].' AND field_id='.$uid.' AND deleted=0'
		                );
		                if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) {
		                    $insertArray = array(
		                        'pid'           => $this->getStoragePID(),
		                        'tstamp'        => time(),
		                        'crdate'        => time(),
		                        'user_id'       => $GLOBALS['TSFE']->fe_user->user['uid'],
		                        'field_id'      => $uid,
		                        'field_value'   => $value
		                    );
		                    $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_userfields_contents',$insertArray);
		                }
		                else {
		                    $updateArray = array(
		                        'tstamp'        => time(),
		                        'field_value'   => $value
		                    );
		                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_userfields_contents','field_id='.$uid.' AND user_id='.$GLOBALS['TSFE']->fe_user->user['uid'],$updateArray);
		                }
	                }
	            }
	        }

		// Otherwise...
		} else {
			$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		}

		return $content;
	}

	/**
	 * Completely removes an user avatar.
	 * This function completely removes an user avatar by removing
	 * the avatar from the user record and by deleting the avatar file
	 * in the file system.
	 *
	 * @author  Martin Helmich
	 * @version 2007-10-03
	 * @param   int  $user_uid The user's UID whose avatar is to be deleted
	 * @return  void
	 */
	function remove_avatar($user_uid) {

		// Retrieve avatar filename
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('tx_mmforum_avatar,image','fe_users','uid='.intval($user_uid));
			list($avatar,$image) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

			if(!$avatar && !$image) return;

            if($avatar) {
		        // Delete avatar file
                    $avatar_fullPath = $this->conf['path_avatar'].$avatar;
			        @unlink($avatar_fullPath);
            }
            if($image) {
                // Delete user image file
                    $image_fullPath = 'uploads/pics/'.$image;
                    @unlink($image_fullPath);
            }

		// Remove avatar from user record
			$updateArr = array(
				'tstamp'			=> time(),
				'tx_mmforum_avatar'	=> '',
                'image'             => ''
			);
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users','uid='.intval($user_uid),$updateArr);

	}

	/**
	 * Uploads a new avatar to the server.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @author  Georg Ringer <typo3@ringerge.org>
	 * @version 2007-10-03
	 * @param   string $content The plugin content
	 * @param   array  $conf    The plugin's configuration vars
	 * @return  string          The content
	 */
	function avatar_upload($content , $conf) {

        $userId = $GLOBALS['TSFE']->fe_user->user['uid'];

		// Remove avatar. This does not remove the actual image file, but sets the
		// avatar field in the user record to empty.
		if (isset($this->piVars['del_avatar'])) {
			$this->remove_avatar($userId);
			return $content;
		}

			/*
			 * Get the file extension and do some crazy security stuff.
			 */
		$extension = array_pop(explode('.',$_FILES[$this->prefixId]['name']['file']));
		if(in_array($extension,array('php','php3','php4','php5','phpsh','pl','sh','rb','py'))) die ("Hacking attempt.");

		if (isset($this->piVars['upload'])) {
            $uploaddir = $this->conf['path_avatar'];

				/*
				 * Load the allowed file size for avatar image from the TCA and
				 * check against the size of the uploaded image.
				 */
			global $TCA;
			$GLOBALS['TSFE']->includeTCA();
			t3lib_div::loadTCA('fe_users');
			if(filesize($_FILES[$this->prefixId]['tmp_name']['file']) > $TCA['fe_users']['columns']['tx_mmforum_avatar']['config']['max_size']*1024)
				return;

			$file = $userId.'_'.time().'.'.$extension;
			$uploadfile = $uploaddir.$file;

			if (move_uploaded_file($_FILES[$this->prefixId]['tmp_name']['file'], $uploadfile)) {
					/* Paranoid? Eh, you never know... */
				chmod($uploadfile, 0444);
				$updateArray['tx_mmforum_avatar'] = $file;
				$upload_ok = true;

				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users','uid='.$userId,$updateArray);
			} else {
				$upload_ok = false;
			}
		}

		return $content;
	}

	/**
	 * Changes the current user's password
	 * @author  Georg Ringer <typo3@ringerge.org>
	 * @version 22.09.2006
	 * @param   string $content The plugin content
	 * @param   array  $conf    The plugin's configuration vars
	 * @return  string          The content
	 */
	function change_pass($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		#$param = t3lib_div::_POST('mmf-pw');

		// Check parameters
		if (isset($this->piVars['newpass1']) OR isset($this->piVars['newpass2']) OR isset($this->piVars['oldpass'])) {
			$error = 0;

			// Old password is not set
			if (empty($this->piVars['oldpass'])) {
				$error = 1;
				$errormessage .= $this->pi_getLL('errorInsertOldPw');
			}
			// New password is not set
			if (empty($this->piVars['newpass1'])) {
				$error = 1;
				$errormessage .= $this->pi_getLL('errorInsertNewPw');
			}
			// New password is not repeated
			if (empty($this->piVars['newpass2'])) {
				$error = 1;
				$errormessage .= $this->pi_getLL('errorInsertNewPw2');
			}
			// New password is not repeated correctly
			if (($this->piVars['newpass1'] AND $this->piVars['newpass2']) AND ($this->piVars['newpass1'] <> $this->piVars['newpass2'])) {
				$error = 1;
				$errormessage .= $this->pi_getLL('errorNewPwRepeat');
			}
			// Old password is not correct
			else {
				$saltedSv = null;
 				if (t3lib_extMgm::isLoaded('t3sec_saltedpw')) {
					require_once(t3lib_extMgm::extPath('t3sec_saltedpw', 'sv1/class.tx_t3secsaltedpw_sv1.php'));
					if (tx_t3secsaltedpw_div::isUsageEnabled()) {
						$saltedSv = t3lib_div::makeInstance('tx_t3secsaltedpw_sv1');
					}
				}
				if (!$saltedSv && t3lib_extMgm::isLoaded('saltedpasswords')) {
					if (tx_saltedpasswords_div::isUsageEnabled()) {
						$saltedSv = t3lib_div::makeInstance('tx_saltedpasswords_sv1');
					}
				}
				if ($saltedSv) {
					$saltedSv->init();
					if (!$saltedSv->compareUident($GLOBALS['TSFE']->fe_user->user, array('uident_text' => $this->piVars['oldpass']))) {
						$error = 1;
						$errormessage .= $this->pi_getLL('errorOldPw');
					}
				} else {
					if(t3lib_extMgm::isLoaded('kb_md5fepw')) {
						$this->piVars['oldpass'] = md5($this->piVars['oldpass']);
					}

					if ($this->piVars['oldpass'] <> $GLOBALS['TSFE']->fe_user->user['password']) {
						if (md5($this->piVars['oldpass']) <> $GLOBALS['TSFE']->fe_user->user['tx_mmforum_md5']) {
							$error = 1;
							$errormessage .= $this->pi_getLL('errorOldPw');
						}
					}
				}
			}
			// Password too short
			if (($this->piVars['newpass1'] == $this->piVars['newpass2']) AND (strlen($this->piVars['newpass1']) < $conf['minPasswordLength']) AND (strlen($this->piVars['newpass2']) < $conf['minPasswordLength'])) {
				$error = 1;
				$errormessage .= sprintf($this->pi_getLL('errorPwLength'),$conf['minPasswordLength']);
			}

			// Save new password to database
			if ($error == 0) {
				$where = 'uid = '.$GLOBALS['TSFE']->fe_user->user['uid'];
				$val = Array(
					'password' => $this->piVars['newpass1'],
					'tx_mmforum_md5' => md5($this->piVars['newpass1'])
				);

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
					$val['password'] = $objPHPass->getHashedPassword($val['password']);

				} else if(t3lib_extMgm::isLoaded('kb_md5fepw')) {	//if kb_md5fepw is installed, crypt password
					$val['password']=md5($val['password']);
				}

				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users',$where,$val);

				$errormessage .= $this->pi_getLL('pwChanged');
			}
		}

		$marker['###ERROR_MSG###'] = $errormessage;
		$template = $this->cObj->fileResource($conf['template']);
		$template = $this->cObj->getSubpart($template, "###ERROR###");

		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		return $content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi5/class.tx_mmforum_pi5.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi5/class.tx_mmforum_pi5.php']);
}

?>