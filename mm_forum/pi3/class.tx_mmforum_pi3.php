<?php
/***************************************************************
 *  Copyright notice
 *  
 *  (c) 2008 Mittwald CM Service
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
 *   69: class tx_mmforum_pi3 extends tslib_pibase
 *   85:     function main($content,$conf)
 *  168:     function list_inbox ($content,$conf)
 *  349:     function message_del ($content,$conf)
 *  379:     function message_read ($content,$conf)
 *  462:     function message_write ($content,$conf)
 *  677:     function top_navi ($content,$conf)
 *  726:     function list_user($content,$conf)
 *  762:     function count_new_pm($uid)
 *  797:     function getLanguage()
 *  816:     function getStoragePIDQuery($tables="")
 *
 * TOTAL FUNCTIONS: 14
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_base.php');

/**
 * Plugin 'Private Messaging' for the 'mm_forum' extension.
 * The 'private messaging' plugin for the extension 'mm_forum' displays
 * the personal in- and outbox for each user's private messages and
 * allows writing new private messages and replying to read messages.
 *
 * @author     Holger Trapp <h.trapp@mittwaldmedien.de>
 * @author     Georg Ringer <typo3@ringerge.org>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Björn Detert <b.detert@mittwald.de>
 * @copyright  2008 Mittwald CM Service
 * @version    2008-01-11
 * @package    mm_forum
 * @subpackage Messaging
 */
class tx_mmforum_pi3 extends tx_mmforum_base {
	var $prefixId		= 'tx_mmforum_pi3';					// Same as class name
	var $prefixId_pi1	= 'tx_mmforum_pi1';					// Main plugin class name
	var $scriptRelPath	= 'pi3/class.tx_mmforum_pi3.php';	// Path to this script relative to the extension dir.
    var $mm1;
    
	/**
	* Main function. Delegates tasks to other functions
	* @author  Holger Trapp <h.trapp@mittwaldmedien.de>
	* @version 20.09.2006
	* @param   string $content The content
	* @param   array  $conf    The plugin's configuration vars
	* @return  string          The plugin content
	*/
	function main($content, $conf) {
        //add Javascript
        $GLOBALS['TSFE']->additionalHeaderData['mm_forum'] = '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath('mm_forum').'res/scripts/prototype-1.6.0.3.js"></script>';

		$this->init($conf);
		$this->pi_USER_INT_obj = 1;

		$this->config["code"] = $this->cObj->stdWrap($this->conf["code"],$this->conf["code."]);

		$this->templateFile = $conf["templateFile"];
		$codes=t3lib_div::trimExplode(",", $this->config["code"]?$this->config["code"]:$this->conf["defaultCode"],1);
		if (!count($codes))  $codes=array("");

        $conf = $this->conf;
		
		if(!$conf['pm_pid']) $conf['pm_pid'] = $GLOBALS['TSFE']->id;
		
		while(list(,$theCode)=each($codes)) {
			list($theCode,$cat,$aFlag) = explode("/",$theCode);
			$theCode = (string)strtoupper(trim($theCode));
			$this->theCode = $theCode;

			switch($theCode) {
				// Output a cObj telling whether there are new messages or not
				case "CHECKNEW":
					$new_messages = $this->count_new_pm($GLOBALS['TSFE']->fe_user->user['uid']);
					IF ($new_messages > 0){
						if($new_messages == 1){
							$content = $this->pi_linkToPage($new_messages.$this->pi_getLL('newmessage'),$conf['pm_pid'],$target='_self',array());    
						}
						elseif($new_messages > 1){
							$content = $this->pi_linkToPage($new_messages.$this->pi_getLL('newmessages'),$conf['pm_pid'],$target='_self',array());   
						}
					}
					else{
						$content = '';
					}
				break;

				// Output the default private messaging user interface
				default:
					if ($GLOBALS['TSFE']->fe_user->user['username']) {
						$action = $this->piVars['action']?$this->piVars['action']:'';
                        
                        IF ($action == "")               $content = $this->list_inbox($content,$conf);
						IF ($action == "message_read")   $content = $this->message_read($content,$conf);
						IF ($action == "message_write")  $content = $this->message_write($content,$conf);
						IF ($action == "message_del")    $content = $this->message_del($content,$conf);
						IF ($action == "import")   		 $content = $this->import($content,$conf);
					} else {
						$template = $this->cObj->fileResource($conf['temp']['error_message']);
						$marker['###ERROR###'] = $this->pi_getLL('msgError');
						$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
					}
				break;
			}
		}

		return $this->pi_wrapInBaseClass($content);
	}

	/**
	 * Lists the private messages of the current user.
	 * @author  Georg Ringer <typo3@ringerge.org>
	 * @version 20.09.2006
	 * @param   string $content The content
	 * @param   array  $conf    The plugin's configuration vars
	 * @return  string          The plugin content
	 */
	function list_inbox ($content,$conf) {

		$content = $this->top_navi($content, $conf);

		$templateFile = $this->cObj->fileResource($conf['template.']['main']);
		$feUserId = $GLOBALS['TSFE']->fe_user->user['uid'];

		// Move selected messages to archive
		if ($this->piVars['sel_action'] == 'archiv') {
			foreach ($this->piVars['messid'] as $value) {
				$value = intval($value);
				$where = 'uid = ' . $value . ' AND to_uid = ' . $feUserId;
				$val = array('mess_type' => 2);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_pminbox', $where, $val);
			}
		}

		// Delete selected messages
		if ($this->piVars['sel_action'] == 'del') {
			foreach ($this->piVars['messid'] as $value) {
				$value = intval($value);
				$where =  'uid = ' . $value . ' AND to_uid = ' . $feUserId;
				$val = array('deleted' => 1);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_pminbox', $where, $val);
			}
		}



		switch ($this->piVars['folder']) {
			// display outbox
			case 'send':
				$mess_type = 1;
				$tofrom = $this->pi_getLL('headerTofromOutbox');
				$marker['###LABEL_INBOX###'] = $this->pi_getLL('main.outbox');
				break;

			// display archive
			case 'archive':
				$mess_type = 2;
				$tofrom = $this->pi_getLL('headerTofromArchiv');
				$marker['###LABEL_INBOX###'] = $this->pi_getLL('main.archive');
				break;

			// display inbox
			case 'inbox':
			default:
				$mess_type = 0;
				$tofrom = $this->pi_getLL('headerTofromInbox');
				$marker['###LABEL_INBOX###'] = $this->pi_getLL('main.inbox');
				$this->piVars['folder'] = 'inbox';
		}

		session_start();
		unset($_SESSION['mm_forum']['pm']['message']);

		// Generate and execute SQL query
		$where = 'hidden = 0 AND deleted = 0 AND to_uid = ' . $feUserId . ' AND mess_type = ' . $mess_type . $this->getStoragePIDQuery();
		$orderBy = 'sendtime DESC';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_mmforum_pminbox', $where, '', $orderBy);

		// Load Template
		$template = $this->cObj->getSubpart($templateFile, '###MESSAGES_TOP###');

		$marker['###HEAD_TO_FROM###']	= $tofrom;
		$marker['###HEAD_SUBJECT###']	= $this->pi_getLL('headerSubject');
		$marker['###HEAD_DATE###']		= $this->pi_getLL('headerDate');
		$marker['###HEAD_REPLY###']		= $this->pi_getLL('reply');
		$marker['###HEAD_DELETE###']	= $this->pi_getLL('delete');

		$marker['###FORMLINK###']		= $this->getAbsUrl($this->pi_getPageLink($GLOBALS['TSFE']->id));
		$marker['###CONFIRM_ACTION###']	= $this->pi_getLL('confirmAction');

		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);


		$marker = array();
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0) {
			$template = $this->cObj->getSubpart($templateFile, '###NO_MESSAGES###');
			$marker['###LABEL_NOMESSAGES###'] = $this->pi_getLL('noMessages');
			$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

		} else {
			$template = $this->cObj->getSubpart($templateFile, '###MESSAGES###');

			// Output messages
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$marker['###MESSID###']  = $row['uid'];
				$marker['###SELECT###']  = '<input type="checkbox"  name="'.$this->prefixId.'[messid][]" value="'.$row['uid'].'" />';

				// Generate subject link
				$linkParams[$this->prefixId] = array(
					'action'		=> 'message_read',
					'messid'		=> $row['uid']
				);
				if ($this->getIsRealUrl()) {
					$linkParams[$this->prefixId]['folder'] = $this->piVars['folder'];
				}
				$marker['###SUBJECT###'] = $this->pi_linkToPage($this->shield($row['subject']), $GLOBALS["TSFE"]->id, '', $linkParams);

				// Generate author link
				unset($linkParams);
				$userdata = tx_mmforum_tools::get_userdata($row['from_uid']);
				if ($userdata === FALSE) {
					$marker['###FROM###'] = $this->pi_getLL('user.deleted');
				} else if ($userdata['deleted'] == '1') {
					$marker['###FROM###'] = $this->shield($userdata[tx_mmforum_pi1::getUserNameField()]);
				} else {
					$marker['###FROM###'] = tx_mmforum_pi1::linkToUserProfile($row['from_uid']);
				}

				// Generate date
				$marker['###DATE###'] = $this->formatDate($row['sendtime']);

				// Generate reply link
				unset($linkParams);
				if ($userdata && $userdata['deleted'] == '0') {
					$linkParams[$this->prefixId] = array(
						'action' => 'message_write',
						'messid' => $row['uid']
					);
					if ($this->getIsRealUrl()) {
						$linkParams[$this->prefixId]['folder'] = $this->piVars['folder'];
					}
					$marker['###PM###']	= tx_mmforum_pi1::createButton('pmreply', $linkParams, 0, true);
				} else {
					$marker['###PM###'] = tx_mmforum_pi1::createButton('pmreply', $linkParams, 0, true, '', true);
				}

				// Generate PM deletion link
				$linkParam[$this->prefixId] = array(
					'action' => 'message_del',
					'messid' => $row['uid'],
					'folder' => $this->piVars['folder']
				);
				if ($this->getIsRealUrl()) {
					$linkParam[$this->prefixId]['folder'] = $this->piVars['folder'];
				}

				$marker['###DEL###'] = tx_mmforum_pi1::createButton('pmdelete', $linkParam, 0, true, '', false, 'onclick="return confirm(\''.$this->pi_getLL('deleteConfirm').'\');"');

				// Unread messages are wrapped with a wrap defined in TypoScript
				if ($row['read_flg'] == 0) {
					if ($mess_type != 1) {
						$marker['###SUBJECT###'] = $this->cObj->wrap($marker['###SUBJECT###'], $conf['unreadWrap']);
						$marker['###FROM###']    = $this->cObj->wrap($marker['###FROM###'],    $conf['unreadWrap']);
						$marker['###DATE###']    = $this->cObj->wrap($marker['###DATE###'],    $conf['unreadWrap']);
						$marker['###PM###']      = $this->cObj->wrap($marker['###PM###'],      $conf['unreadWrap']);
						$marker['###DEL###']     = $this->cObj->wrap($marker['###DEL###'],     $conf['unreadWrap']);
						$marker['###ICON###']	 = $this->conf['path_img'] . $this->conf['images.']['pmicon_new'];
					} else {
						$marker['###ICON###']	 = $this->conf['path_img'] . $this->conf['images.']['pmicon'];
					}
				} else {
					$marker['###ICON###'] = $this->conf['path_img'] . $this->conf['images.']['pmicon'];
				}

				$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
			}
		}

		$template = $this->cObj->getSubpart($templateFile, '###MESSAGES_END###');


		$marker['###SELECTION###'] = $this->pi_getLL('selection');
		$marker['###OPTION###']    = $this->pi_getLL('selected').' <select class="tx-mmforum-select" name="'.$this->prefixId.'[sel_action]">';
		$marker['###OPTION###']   .= '	<option value="nothing">&nbsp;</option>';
		$marker['###OPTION###']   .= '	<option value="del">'.$this->pi_getLL('selectedDelete').'</option>';

		if ($this->piVars['folder'] <> "archiv") {
		$marker['###OPTION###'] .= '	<option value="archiv">'.$this->pi_getLL('selectedToArchive').'</option>';
		}
		$marker['###OPTION###'] .= '</select>';
		$marker['###OPTION###'] .= '<input type="hidden" name="'.$this->prefixId.'[folder]" value="'.$this->shield($this->piVars['folder']).'" />';

		$marker['###SELECT_ALL###']  = $this->pi_getLL('selectAll');
		$marker['###SELECT_NONE###'] = $this->pi_getLL('selectNone');

		$linkParams[$this->prefixId] = array('action'=>'message_write');
		if ($this->getIsRealUrl()) {
			$linkParams[$this->prefixId]['folder'] = 'inbox';
		}
		$marker['###NEWMESSAGE###']	   = tx_mmforum_pi1::createButton('newpm', $linkParams);
		$marker['###LABEL_EXECUTE###'] = $this->pi_getLL('main.execute');

		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		return $content;
	}

	/**
	 * Deletes a private message.
	 * @author  Georg Ringer <typo3@ringerge.org>
	 * @version 20.09.2006
	 * @param   string $content The content
	 * @param   array  $conf    The plugin's configuration vars
	 * @return  string          The plugin content
	 */
	function message_del ($content,$conf) {
		$where = 'hidden = 0 AND DELETED = 0 AND uid = \''.intval($this->piVars["messid"]).'\' AND to_uid = '.$GLOBALS['TSFE']->fe_user->user['uid'].$this->getStoragePIDQuery();
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','tx_mmforum_pminbox',$where,'','',$limit=1);
		
		if ($res) {
			$val = Array('deleted' => 1);
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_pminbox',$where,$val);
			
			$link = $this->pi_getPageLink($GLOBALS["TSFE"]->id,$target='',array($this->prefixId=>array('folder'=>$this->piVars['folder'])));
			$link = $this->getAbsUrl($link);
			
			header('Location: '.$link);
		}
		else {
			$template = $this->cObj->fileResource($conf['temp']['error_message']);
			$marker['###ERROR###'] = $this->pi_getLL('deleteError');
		}
		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		return $content;
	}

	/**
	* Displays a single private message.
	* @author  Georg Ringer <typo3@ringerge.org>
	* @version 2007-05-02
	* @param   string $content The content
	* @param   array  $conf    The plugin's configuration vars
	* @return  string          The plugin content
	*/
	function message_read ($content,$conf) {
		
		// Load message from database
		$field = 'uid, subject, from_name, sendtime, message, read_flg, from_uid';
		$where = 'hidden = 0 AND DELETED = 0 AND uid = '.intval($this->piVars["messid"]).' AND to_uid = '.$GLOBALS['TSFE']->fe_user->user['uid'].$this->getStoragePIDQuery();
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($field,'tx_mmforum_pminbox',$where,'','',$limit=1);

		if ($res) {
			// Set read flag
			$val = Array('read_flg' => 1); 
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_pminbox',$where,$val);

			// Display message
			$content = $this->top_navi($content,$conf);
			$template = $this->cObj->fileResource($conf['template.']['message_read']);
			$template = $this->cObj->getSubpart($template, "###MESSAGE_READ###");
			$marker = array(
				'###LABEL_FROM###'			=> $this->pi_getLL('headerTofromInbox'),
				'###LABEL_DATE###'			=> $this->pi_getLL('headerDate'),
				'###LABEL_SUBJECT###'		=> $this->pi_getLL('headerSubject'),
				'###LABEL_READMESSAGE###'	=> $this->pi_getLL('read.readMessage')
			);
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$marker['###MESSID###']		= $row['uid'];
			$marker['###SUBJECT###']	= $this->shield($row['subject']);
            
            $message_text = $row['message'];
            
            #$message_text    =   tx_mmforum_postparser::main($this,$this->conf,$message_text,'textparser');
            
            if($row['mess_type'] == 1) {
            	$userdata = tx_mmforum_tools::get_userdata($row['to_uid']);
            	$marker['###LABEL_FROM###'] = $this->pi_getLL('headerTofromOutbox');
            }
            else $userdata = tx_mmforum_tools::get_userdata($row['from_uid']);
            
            if($userdata === FALSE)             $marker['###FROM###'] = $this->pi_getLL('user.deleted');
            elseif($userdata['deleted'])        $marker['###FROM###'] = $this->shield($userdata[tx_mmforum_pi1::getUserNameField()]);
            else {
                $marker['###FROM###']	= tx_mmforum_pi1::linkToUserProfile($userdata);
            }
			$marker['###DATE###']		= $this->formatDate($row['sendtime']);
			$marker['###MESSAGE###']	= nl2br($this->shield($message_text));

			if($userdata === FALSE || $userdata['deleted'] == 1) {
				$linkParams = array();
				$linkParams[$this->prefixId] = array(
					'action' => 'message_write',
					'messid' => $row['uid']
				);
				if($this->useRealUrl()) $linkParams[$this->prefixId]['folder'] = $this->piVars['folder']?$this->piVars['folder']:'inbox';
				$marker['###REPLY###']		= tx_mmforum_pi1::createButton('pmreply',$linkParams);
			} else {
				$marker['###REPLY###'] = '';
			}
            
			$linkParams[$this->prefixId] = array(
                'action'=>'message_del',
                'messid'=>$row['uid']
            );
            if($this->useRealUrl()) $linkParams[$this->prefixId]['folder'] = $this->piVars['folder']?$this->piVars['folder']:'inbox';
            $marker['###DELETE###']		= tx_mmforum_pi1::createButton('pmdelete',$linkParams,0,false,'',false,'onclick="return confirm(\''.$this->pi_getLL('deleteConfirm').'\');"');
		}
		// Display error message if message not found
		else {
			$template = $this->cObj->fileResource($conf['template.']['error_message']);
			$marker['###ERROR###'] = $this->pi_getLL('errorNoAccess');
		}

		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
		return $content;
	}


	/**
	* Displays the form for writing a new private message or replying to an
	* existing one. Also saves the new private message to database.
	* @author  Georg Ringer <typo3@ringerge.org>
	* @author  Martin Helmich <m.helmich@mittwald.de>
	* @version 2008-01-11
	* @param   string $content The content
	* @param   array  $conf    The plugin's configuration vars
	* @return  string          The plugin content
	*/
	function message_write ($content,$conf) {
		
		// Load message to reply to from database
		$where = 'hidden = 0 AND DELETED = 0 AND uid = '.intval($this->piVars["messid"]).' AND to_uid = '.$GLOBALS['TSFE']->fe_user->user['uid'].$this->getStoragePIDQuery();
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','tx_mmforum_pminbox',$where,'','',$limit=1);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		
		// Commit user search
		if ($this->piVars["save"] == $this->pi_getLL('write.search')) {
				// If a messsage text has already been entered, store this text
				// in the session variables in order to have it available lateron.
			if(!empty($this->piVars['message'])) {
				session_start();
				$_SESSION['mm_forum']['pm']['message'] = $this->piVars['message'];
			}
			
			$content .= $this->list_user($content,$conf);
		}
		// Send new message
		elseif ($this->piVars["save"] == $this->pi_getLL('write.send')) {
			$subject        = $this->piVars["subject"];
			$message        = $this->piVars["message"];
			$to_username    = $this->piVars["user"];
			
			$error = 0;
			// Check subject
			if (!$subject) {
				$error = 1;
				$errormessage = $this->pi_getLL('errorNoSubject');
			}

			// Check message
			if (!$message) {
				$error = 1;
				$errormessage = $this->pi_getLL('errorNoMessage');
			}

			// Check recipient
			if (!$to_username) {
				$error = 1;
				$errormessage = $this->pi_getLL('errorNoRecipient');
			}

			// Spam protection: just one message per $conf['block_time']
				// Load last sent message from database
					$where = 'from_uid ='.$GLOBALS['TSFE']->fe_user->user['uid'].' AND mess_type=0 '.$this->getStoragePIDQuery();
					$orderBy = 'crdate DESC';
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('crdate','tx_mmforum_pminbox',$where,$groupBy='',$orderBy,$limit=1);
					$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

				// Compare with current time and spam block interval
					if((time()-$conf['block_time']) <= $row['crdate']) {
						$error = 1;
						$errormessage = sprintf($this->pi_getLL('errorBlockinTime'),$conf['block_time']);
					}

			// Check if an error has occurred so far. If so, abort.
			if ($error) {
				$template = $this->cObj->fileResource($conf['template.']['error_message']);
				$marker['###ERROR###'] = $errormessage;
			}
			else
			{
				// Retrieve userId from username
				$where = "deleted = 0 AND disable = 0 AND username=".$GLOBALS['TYPO3_DB']->fullQuoteStr($to_username,'fe_users')." AND pid=".$this->conf['userPID'];
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','fe_users',$where);
				$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

				// Save and send the private message
				if ($row) {
					// Save the private message for the recipient
					$val = Array(
						'pid'			=> $this->getStoragePID(),
						'tstamp'		=> time(), 
						'crdate'		=> time(), 
						'cruser_id'		=> $GLOBALS['TSFE']->fe_user->user['uid'], 
						'sendtime'		=> time(), 
						'from_uid'		=> $GLOBALS['TSFE']->fe_user->user['uid'], 
						'from_name'		=> $GLOBALS['TSFE']->fe_user->user['username'], 
						'to_uid'		=> $row['uid'], 
						'to_name'		=> $to_username, 
						'subject'		=> $subject, 
						'message'		=> $message
					);
					$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_pminbox',$val);
                    $mess_id = $GLOBALS['TYPO3_DB']->sql_insert_id();

					// Save as sent private message
					$val = Array(
						'pid'			=> $this->getStoragePID(),
						'tstamp'		=> time(), 
						'crdate'		=> time(), 
						'cruser_id'		=> $GLOBALS['TSFE']->fe_user->user['uid'], 
						'sendtime'		=> time(), 
						'to_uid'		=> $GLOBALS['TSFE']->fe_user->user['uid'], 
						'to_name'		=> $GLOBALS['TSFE']->fe_user->user['username'], 
						'from_uid'		=> $row['uid'], 
						'from_name'		=> $to_username, 
						'subject'		=> $subject, 
						'message'		=> $message,
						'mess_type'		=> 1
					);
					$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_pminbox',$val);
					
					session_start();
					unset($_SESSION['mm_forum']['pm']['message']);

					// Notification to the recipient via email
					$where = 'uid = '.$row['uid'];
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('email, '.tx_mmforum_pi1::getUserNameField().', tx_mmforum_pmnotifymode','fe_users',$where,'');
					$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
					$to = $row['email'];

					if($row['tx_mmforum_pmnotifymode'] == 0) {
						$llMarker = array(
							'###SITENAME###'		=> $conf['siteName'],
							'###EMAIL###'			=> $conf['mailerEmail']
						);
						$from = $this->cObj->substituteMarkerArray($this->pi_getLL('ntfmail.sender'),$llMarker);
						
						$header .= "From: ".$from."\n";
						$header .= "X-Mailer: PHP/" . phpversion(). "\n";
						$header .= "X-Sender-IP: ".getenv("REMOTE_ADDR")."\n";
			            $header .= "Content-type: text/plain;charset=".$GLOBALS['TSFE']->renderCharset."\n";
	
						$template = $this->pi_getLL('ntfmail.content');
	                    
	                    $linkParams[$this->prefixId] = array(
	                        'messid' => $mess_id,
	                        'action' => 'message_read'
	                    );
	                    if($this->useRealUrl()) $linkParams[$this->prefixId]['folder'] = 'inbox';

	                    $msgLink = $this->pi_getPageLink($this->conf['pm_pid'],'',$linkParams);
						$msgLink = $this->tools->escapeBrackets($msgLink);

						$marker = array(
							'###USERNAME###'		=> $row[tx_mmforum_pi1::getUserNameField()],
	                        '###PMLINK###'          => tx_mmforum_pi1::getAbsUrl($msgLink),
							'###SITENAME###'		=> $conf['siteName']
						);
						$mailtext = $this->cObj->substituteMarkerArrayCached($template, $marker);
	
						// Compose mail and send
						mail($to,$this->pi_getLL('ntfmail.subject'),$mailtext, $header) or die('Fehler beim Mailversand.');
						
						$updateArray = array(
							'notified'				=> 1,
							'tstamp'				=> time(),
						);
						$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_pminbox','uid='.$mess_id,$updateArray);
					} elseif($row['tx_mmforum_pmnotifymode'] == 1) {
						$linkParams[$this->prefixId]		= array(
							'action'			=> 'message_read',
							'messid'			=> $mess_id
						);
						$link = $this->pi_getPageLink($this->conf['pm_pid'],'',$linkParams);
						$link = $this->getAbsUrl($link);
						tx_mmforum_tools::storeCacheVar('pm.urlCache.'.$mess_id,$link);
					}

					// Redirect user to inbox
					$link = $this->pi_getPageLink($conf['pm_pid']);
					$link = $this->getAbsUrl($link);
					header('Location: '.$link);
				}
				// Display an error message in case the recipient does not exist
				else
				{
					$template = $this->cObj->fileResource($conf['template.']['error_message']);
					$marker['###ERROR###'] = $this->pi_getLL('errorRecipientNotExists');
				}
			}
		}
		
		// Display message form
		else {                    
			$content = $this->top_navi($content,$conf);

				// Load template
			$template = $this->cObj->fileResource($conf['template.']['message_write']);
			$template = $this->cObj->getSubpart($template, "###MESSAGE_WRITE###");

				// Include Javascript
			$GLOBALS['TSFE']->additionalHeaderData['mm_forum'] .= '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath('mm_forum').'res/scripts/usersearch.js"></script>'.chr(10);

				// Set language-dependent markers
			$marker = array(
				'###LABEL_WRITEMESSAGE###'		=> $this->pi_getLL('write.writeMessage'),
				'###LABEL_TO###'				=> $this->pi_getLL('headerTofromOutbox'),
				'###LABEL_SUBJECT###'			=> $this->pi_getLL('headerSubject'),
				'###LABEL_SEND###'				=> $this->pi_getLL('write.send'),
				'###LABEL_RESET###'				=> $this->pi_getLL('write.reset'),
				'###LABEL_SEARCH###'			=> $this->pi_getLL('write.search'),
                '###EXT_PATH###'                => t3lib_extMgm::siteRelPath("mm_forum"),
                '###PID###'                     => $conf['userPID'],
                '###LANG###'                    => $this->pi_getLL('write.search'),
                '###PM###'                      => $conf['pm_pid'],
				'###AJAX_URL###'				=> t3lib_extMgm::siteRelPath('mm_forum').'pi3/tx_mmforum_usersearch.php',
			);
			
			session_start();
			$_SESSION[$this->prefixId]['userPID'] = $this->conf['userPID'];
			
			// If PM is a reply to another PM, there is a prefix in subject/msg-text
			if($row) {
				$field = 'uid, from_name, message, subject';
				$where = 'hidden = 0 AND deleted = 0 AND  uid = \''.intval($this->piVars["messid"]).'\' AND to_uid = '.$GLOBALS['TSFE']->fe_user->user['uid'].$this->getStoragePIDQuery();
				$orderBy = 'sendtime ASC';
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($field,'tx_mmforum_pminbox',$where,$groupBy='',$orderBy,$limit='');
				$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
				
				$msgPrefix = $this->pi_getLL('messageReplyTextPrefix');
				$row['message'] = $msgPrefix.str_replace("\n","\n".$msgPrefix,$row['message']);

				$marker['###ACTION###']     = $this->getAbsUrl($this->pi_getPageLink($GLOBALS['TSFE']->id,'',array($this->prefixId=>array('action'=>'message_write'))));
				$marker['###SUBJECT###']    = $this->pi_getLL('messageReplySubjectPrefix').$row['subject'];
				$marker['###TO_USER###']    = $this->shield($row['from_name']);
				$marker['###MESSAGE###']    = $this->shield($row['message']);
			// Create entirely new PM
			} else {
				$to_userid = $this->piVars['userid']?intval($this->piVars['userid']): intval(t3lib_div::GPvar("userid"));
				if($to_userid != 0) {
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('username','fe_users',"uid='$to_userid'");
					list($username)=$GLOBALS['TYPO3_DB']->sql_fetch_row($res);
				} else $username = "";
				
				$marker['###ACTION###']     = $this->getAbsUrl($this->pi_getPageLink($GLOBALS['TSFE']->id,'',array($this->prefixId=>array('action'=>'message_write'))));
                $marker['###SUBJECT###']    = '';
				$marker['###TO_USER###']    = $this->shield($username);
				$marker['###DATE###']       = '';
				$marker['###MESSAGE###']    = '';
				
				if($_SESSION['mm_forum']['pm']['message'])
					$marker['###MESSAGE###'] = $_SESSION['mm_forum']['pm']['message'];
			}
		}

		$content .= $this->cObj->substituteMarkerArrayCached($template, (array)$marker);
		return $content;
	}

	/**
	* Generates the top navigation of private message system.
	* @author  Georg Ringer <typo3@ringerge.org>
	* @version 20.09.2006
	* @param   string $content The content
	* @param   array  $conf    The plugin's configuration vars
	* @return  string          The plugin content
	*/
	function top_navi ($content,$conf)
	{	
        $imgInfo  = array('border' => intval($conf['img_border']), 'alt' => '', 'src' => '' );
        $template = $this->cObj->fileResource($conf['template.']['navi_top']);
		$count    = $this->count_new_pm($GLOBALS['TSFE']->fe_user->user['uid']);
		
		if ($count==0) $marker['###NEWMESSAGES###'] = '';
		elseif ($count==1) $marker['###NEWMESSAGES###'] = sprintf($this->pi_getLL('newmessage2'),$count);
		else $marker['###NEWMESSAGES###'] = sprintf($this->pi_getLL('newmessages2'),$count);
		
		// Display inbox button
        $marker['###INBOX###']		= tx_mmforum_pi1::createButton('inbox',array($this->prefixId.'[folder]'=>'inbox'));
        
        // Display outbox button
        $marker['###OUTBOX###']		= tx_mmforum_pi1::createButton('outbox',array($this->prefixId.'[folder]'=>'send'));
        
        // Display archive button
        $marker['###ARCHIV###']		= tx_mmforum_pi1::createButton('archive',array($this->prefixId.'[folder]'=>'archive'));
        
        // Display message creation button
        $linkParams[$this->prefixId] = array(
        	'action'		=> 'message_write'
        );
		if($this->useRealUrl())
			$linkParams[$this->prefixId]['folder'] = 'inbox';
		$marker['###NEWPM###']		= tx_mmforum_pi1::createButton('newpm',$linkParams);
           
		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);
        return $content;
	}

	/**
	* Searches for a specific username and lists the results.
	* @author  Georg Ringer <typo3@ringerge.org>
	* @version 20.09.2006
	* @param   string $content The content
	* @param   array  $conf    The plugin's configuration vars
	* @return  string          The plugin content
	*/
	function list_user($content,$conf) {
		$template = $this->cObj->fileResource($conf['template.']['user_list']);
		$template = $this->cObj->getSubpart($template, "###USERLIST_BEGIN###");

		$marker = array(
			// Empty
		);
		
			// Include hooks
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['pm']['listUser_beginMarkers'])) {
            foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['pm']['listUser_beginMarkers'] as $_classRef) {
                $_procObj = & t3lib_div::getUserObj($_classRef);
                $marker = $_procObj->listUser_beginMarkers($marker, $this);
            }
        }
		
		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

		$usersearch = $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['user'],'fe_users');

		$where = 'disable = 0 AND deleted = 0 AND username LIKE \'%'.$usersearch.'%\' AND pid='.$this->conf['userPID'].'';
		$orderBy = 'username ASC';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','fe_users',$where,$groupBy='',$orderBy,'100');

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$template = $this->cObj->fileResource($conf['template.']['user_list']);
			$template = $this->cObj->getSubpart($template, "###USERLIST###");

			$userMarker = array(
				'###USERNAME###' => $this->pi_linkTP($this->shield($row[tx_mmforum_pi1::getUserNameField()]),array('tx_mmforum_pi3[action]'=>'message_write','userid'=>$row['uid']))
			);
			
				// Include hooks
	        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['pm']['listUser_userMarkers'])) {
	            foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['pm']['listUser_userMarkers'] as $_classRef) {
	                $_procObj = & t3lib_div::getUserObj($_classRef);
	                $marker = $_procObj->listUser_userMarkers($userMarker, $row, $this);
	            }
	        }
			
            $content .= $this->cObj->substituteMarkerArrayCached($template, $userMarker);
		}

		$template = $this->cObj->fileResource($conf['template.']['user_list']);
		$template = $this->cObj->getSubpart($template, "###USERLIST_END###");
		
		$marker = array(
			// Empty
		);
		
			// Include hooks
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['pm']['listUser_endMarkers'])) {
            foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['pm']['listUser_endMarkers'] as $_classRef) {
                $_procObj = & t3lib_div::getUserObj($_classRef);
                $marker = $_procObj->listUser_endMarkers($marker, $this);
            }
        }
		
		$content .= $this->cObj->substituteMarkerArrayCached($template, $marker);

		return $content;
	}
	
	/**
	* Determines the amount of unread private messages.
	* @author  Georg Ringer <typo3@ringerge.org>
	* @version 11.10.2006
	* @param   int $uid The UID of the user whose messages are to be checked.
	*                   If empty, the UID of the current user is taken instead.
	* @return  int      The amount of unread private messages for the user specified
	*                   by $uid.
	*/
	function count_new_pm($uid) {
		if (empty($uid)) $uid = $GLOBALS['TSFE']->fe_user->user['uid'];
		$where = 'hidden = 0 AND deleted = 0 AND read_flg = 0 AND mess_type = 0 AND to_uid = '.$GLOBALS['TSFE']->fe_user->user['uid'].$this->getStoragePIDQuery();
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','tx_mmforum_pminbox',$where,'','',$limit='');
		return $GLOBALS['TYPO3_DB']->sql_num_rows($res);
	}
    
    
   /**
    * Check which language is set up in the TypoScript
    * @return string        The folder where language dependend images are stored
    */
    
    function getLanguage(){
        return tx_mmforum_pi1::getLanguageFolder();
    }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi3/class.tx_mmforum_pi3.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/pi3/class.tx_mmforum_pi3.php']);
}

?>
