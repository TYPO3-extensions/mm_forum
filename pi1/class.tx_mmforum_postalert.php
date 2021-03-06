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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   56: class tx_mmforum_postalert extends tslib_pibase
 *   67:     function list_alerts($conf)
 *  233:     function post_alert($conf)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


/**
 * The class 'tx_mmforum_postalert' is a subclass for the 'Forum'
 * plugin (tx_mmforum_pi1) of the 'mm_forum' extension.
 * It handles the post alerts submitted by registered users.
 * This class is not meant for instanciation, but only for static
 * function calls from the pi1 plugin, since it depends on the
 * LOCAL_LANG array of the main plugin.
 *
 * @author     Holger Trapp <h.trapp@mittwald.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Björn Detert <b.detert@mittwald.de>
 * @copyright  2007 Mittwald CM Service
 * @version    11. 10. 2006
 * @package    mm_forum
 * @subpackage Forum
 */
class tx_mmforum_postalert extends tx_mmforum_base {

	/**
	 * This is here to access the piVars
	 * @var string
	 */
	public $prefixId = 'tx_mmforum_pi1';

	/**
	 * This is needed to load locallang
	 * @var string
	 */
	public $scriptRelPath = 'pi1/class.tx_mmforum_postalert.php';
	
	/**
	 * @var tx_mmforum_postparser
	 */
	protected $tx_mmforum_postparser;

	/**
	 * @var tx_mmforum_postqueue
	 */
	protected $tx_mmforum_postqueue;
	
	public function __construct() {
		$this->tx_mmforum_postparser = GeneralUtility::makeInstance('tx_mmforum_postparser');
		$this->tx_mmforum_postqueue = GeneralUtility::makeInstance('tx_mmforum_postqueue');
		$this->pi_loadLL();
		parent::__construct();
	}

	/**
	 * Outputs a list of all posts alerted by users.
	 *
	 * This function output a list of all post alerts submitted by users.
	 * It allows a filtering by post alert status and different ordering options.
	 * @param  array  $conf The calling plugin's configuration vars
	 * @return string       The post alert list
	 */
	function list_alerts($conf) {
		$param = GeneralUtility::_GP('tx_mmforum_pi1');
		if ($param['update'] == 'update_status') {
			foreach($param as $key => $value) {
				$key = str_replace('status_','',$key);
				if (is_numeric($key)){
					$key = $this->databaseHandle->fullQuoteStr($key, 'tx_mmforum_post_alert');
					$value = $this->databaseHandle->fullQuoteStr($value, 'tx_mmforum_post_alert');
					$updateArray = array('status'=>$value,'tstamp'=>$GLOBALS['EXEC_TIME']);
					$this->databaseHandle->exec_UPDATEquery('tx_mmforum_post_alert', 'uid='.$key.' AND status <> '.$value, $updateArray);
				}
			}
		}

		$template		= $this->cObj->fileResource($conf['template.']['post_alert']);
		$template		= $this->cObj->getSubpart($template, "###ALERT_LIST###");
		$template_sub	= $this->cObj->getSubpart($template, "###ALERT_LIST_SUB###");

		// Language dependent markers
		$marker = array(
			'###LABEL_POSTALERTS###'		=> $this->pi_getLL('postalert.title'),
			'###LABEL_ORDERBY###'			=> $this->pi_getLL('postalert.orderby'),
			'###LABEL_ORDERBY_STATUS###'	=> $this->pi_getLL('postalert.orderby.status'),
			'###LABEL_ORDERBY_DATE###'		=> $this->pi_getLL('postalert.orderby.date'),
			'###LABEL_ORDERBY_USER###'		=> $this->pi_getLL('postalert.orderby.user'),
			'###LABEL_ORDERASC###'			=> $this->pi_getLL('postalert.ordermode.asc'),
			'###LABEL_ORDERDESC###'			=> $this->pi_getLL('postalert.ordermode.desc'),
			'###LABEL_DISPLAY###'			=> $this->pi_getLL('postalert.display'),
			'###LABEL_OPEN###'				=> $this->pi_getLL('postalert.status.open'),
			'###LABEL_INPROGRESS###'		=> $this->pi_getLL('postalert.status.progress'),
			'###LABEL_DONE###'				=> $this->pi_getLL('postalert.status.done'),
			'###LABEL_NR###'				=> $this->pi_getLL('postalert.number'),
			'###LABEL_DATE###'				=> $this->pi_getLL('postalert.date'),
			'###LABEL_PROBLEM###'			=> $this->pi_getLL('postalert.problem'),
			'###LABEL_POST###'				=> $this->pi_getLL('postalert.post'),
			'###LABEL_USER###'				=> $this->pi_getLL('postalert.user'),
			'###LABEL_STATUS###'			=> $this->pi_getLL('postalert.status'),
			'###LABEL_POSTTEXT###'			=> $this->pi_getLL('postalert.posttext'),
		);

		$marker['###ORDERBY_USER###']	= '';
		$marker['###ORDERBY_DATE###']	= '';
		$marker['###ORDERBY_STAT###']	= '';
		$marker['###ORDERASC###']		= '';
		$marker['###ORDERDESC###']		= '';
		$marker['###VIEW_OPEN###']		= '';
		$marker['###VIEW_WORK###']		= '';
		$marker['###VIEW_CLOSE###']		= '';

		// Determine ordering mode
		switch($param['order_by']) {
			case "user":
				$order_by = 'cruser_id';
				$marker['###ORDERBY_USER###'] = 'selected';
			break;
			case "date":
				$order_by = 'crdate';
				$marker['###ORDERBY_DATE###'] = 'selected';
			break;
			case "status":
				$order_by = 'status';
				$marker['###ORDERBY_STAT###'] = 'selected';
			break;
			default:
				$order_by = 'status';
				$marker['###ORDERBY_STAT###'] = 'selected';
			break;
		}
		switch($param['order']) {
			case "up":
				$order = 'ASC';
				$marker['###ORDERASC###'] = 'selected';
			break;
			case "down":
				$order = 'DESC';
				$marker['###ORDERDESC###'] = 'selected';
			break;
			default:
				$order = 'ASC';
				$marker['###ORDERASC###'] = 'selected';
			break;
		}
		$allowedStatus = array();
		// Determine filtering mode
		if ($param['view_open'] == 1) {
			$marker['###VIEW_OPEN###']		= 'checked';
			#$where .= 'OR status = -1 ';
			$allowedStatus[] = -1;
		}
		if ($param['view_work'] == 1) {
			$marker['###VIEW_WORK###']		= 'checked';
			#$where .= 'OR status = 0 ';= -1 ';
			$allowedStatus[] = 0;
		}
		if ($param['view_close'] == 1) {
			$marker['###VIEW_CLOSE###']		= 'checked';
			#$where .= 'OR status = 1 ';= -1 ';
			$allowedStatus[] = 1;
		}

		if ($param['view_close'] == '' AND $param['view_work'] == '' AND $param['view_open'] == '' AND empty($param) ) {
			$marker['###VIEW_OPEN###']		= 'checked';
			$marker['###VIEW_WORK###']		= 'checked';
			$marker['###VIEW_CLOSE###']		= '';
			#$where = ' OR status = -1 OR status = 0';
			$allowedStatus = array(0,-1);
		}

		$where = count($allowedStatus) ? ' AND status IN ('.implode(',',$allowedStatus).') ' : '';

		$boards = tx_mmforum_postalert::getModeratorBoards();

		if ($boards === false)
			$accessWhere = ' AND 0=1 ';
		elseif ($boards === true)
			$accessWhere = ' AND 1=1 ';
		else {
			$accessWhere = ' AND t.forum_id IN ('.implode(',',$boards).') ';
		}

			// Load post alert records from database
		$res = $this->databaseHandle->exec_SELECTquery(
			'a.*',
			'tx_mmforum_post_alert a LEFT JOIN tx_mmforum_topics t ON a.topic_id = t.uid',
			"1 = 1 $where $accessWhere",
			'',
			$order_by.' '.$order);

		$content_sub = '';
		while ($row = $this->databaseHandle->sql_fetch_assoc($res)) {
			$marker['###STATUS###'] = '';

			switch ($row['status']) {
				case('-1') :
                    $marker['###STATCOLOR###']  = $this->conf['postalerts.']['statusColors.']['open'];
					$marker['###STATUS###']    .= '<option value="-1" selected>'.$this->pi_getLL('postalert.status.open').'</option>';
					$marker['###STATUS###']    .= '<option value="0">'.$this->pi_getLL('postalert.status.progress').'</option>';
					$marker['###STATUS###']    .= '<option value="1">'.$this->pi_getLL('postalert.status.done').'</option>';
				break;
				case('0') :
					$marker['###STATCOLOR###']  = $this->conf['postalerts.']['statusColors.']['work'];
					$marker['###STATUS###']    .= '<option value="-1">'.$this->pi_getLL('postalert.status.open').'</option>';
					$marker['###STATUS###']    .= '<option value="0" selected>'.$this->pi_getLL('postalert.status.progress').'</option>';
					$marker['###STATUS###']    .= '<option value="1">'.$this->pi_getLL('postalert.status.done').'</option>';
				break;
				case('1') :
					$marker['###STATCOLOR###']  = $this->conf['postalerts.']['statusColors.']['done'];
					$marker['###STATUS###']    .= '<option value="-1">'.$this->pi_getLL('postalert.status.open').'</option>';
					$marker['###STATUS###']    .= '<option value="0">'.$this->pi_getLL('postalert.status.progress').'</option>';
					$marker['###STATUS###']    .= '<option value="1" selected>'.$this->pi_getLL('postalert.status.done').'</option>';

				break;
			}

			$linkparams[$this->prefixId] = array(
				'action'	=> 'list_post',
				'tid'		=> $row['topic_id'],
				'pid'		=> $row['post_id']
			);

			list($posttext) = $this->databaseHandle->sql_fetch_row($this->databaseHandle->exec_SELECTquery('post_text','tx_mmforum_posts_text','deleted="0" AND hidden="0" AND post_id="'.$row['post_id'].'"'));

			$marker['###UID###']		= $row['uid'];
			$marker['###TOPIC###']		= $this->pi_linkToPage($this->escape($this->get_topic_name($row['topic_id'])),$conf['pid_forum'],$target='_self',$linkparams);
			$marker['###DATE###']		= $this->formatDate($row['crdate']);
			$marker['###POST_TEXT###']	= nl2br($this->escape($posttext));
			$marker['###TEXT_SHORT###']	= $this->escape(tx_mmforum_tools::textCut($row['alert_text'],15,''));
			$marker['###TEXT###']		= nl2br($this->escape($row['alert_text']));

			$mod_data					= tx_mmforum_tools::get_userdata($row['cruser_id']);
			$marker['###MOD###']		= $mod_data[$this->getUserNameField()];

			$marker['###POST_USER###']	= $this->pi_linkToPage($mod_data[$this->getUserNameField()],$conf['pm_id'],'',array('tx_mmforum_pi3[action]'=>'message_write','userid'=>$row['cruser_id']));

			$content_sub .= $this->cObj->substituteMarkerArrayCached($template_sub, $marker);
		}
		$content = $this->cObj->substituteSubpart($template,'###ALERT_LIST_SUB###',$content_sub);
		$marker['###FORMACTION###'] = $this->escapeURL($this->pi_getPageLink($GLOBALS["TSFE"]->id,'',$linkparams));
		$content = $this->cObj->substituteMarkerArrayCached($content, $marker);

		return $content;
	}

	/**
	 * Outputs the form for users to create a post alert.
	 * @param  array  $conf The calling plugin's configuration vars
	 * @return string       The form
	 */
	function post_alert($conf) {
		// Check login
		if ($GLOBALS['TSFE']->fe_user->user['uid']) {
			$template		= $this->cObj->fileResource($conf['template.']['post_alert']);
			$template		= $this->cObj->getSubpart($template, "###POST_ALERT###");

			$param		= GeneralUtility::_GP('mm_forum');
			$post_id 	= intval($this->piVars['pid']);

			// Language dependent markers
			$marker = array(
				'###LABEL_HEADING###'		=> $this->pi_getLL('postalert.heading'),
				'###LABEL_POSTTEXT###'		=> $this->pi_getLL('postalert.posttext'),
				'###LABEL_REASON###'		=> $this->pi_getLL('postalert.reason'),
				'###LABEL_ALERT###'			=> $this->pi_getLL('postalert.alert')
			);

			$marker['###ERRORMESSAGE###'] = '';

			// Create alert record
			if (isset($param) && $param['submit'] == $this->pi_getLL('postalert.alert')) {
				if (empty($param['alert_text'])) {
					$marker['###ERRORMESSAGE###'] = '<div class="tx-mmforum-pi1-postalert-error">'.$this->pi_getLL('postalert.errorNoReason').'</div>';
				} else {
					$insertArray = array(
						'tstamp'		=> $GLOBALS['EXEC_TIME'],
						'crdate'		=> $GLOBALS['EXEC_TIME'],
						'cruser_id'		=> $GLOBALS['TSFE']->fe_user->user['uid'],
						'alert_text'	=> $param['alert_text'],
						'post_id'		=> $post_id,
						'topic_id'		=> $this->get_topic_id($post_id),
						'mod_id'		=> '',
						'status'		=> '-1'
					);
					$this->databaseHandle->exec_INSERTquery('tx_mmforum_post_alert', $insertArray);

					$linkto	= $this->get_pid_link($post_id, GeneralUtility::_GP('sword'),$conf);
					HttpUtility::redirect($linkto);
				}
			}

			list($posttext) = $this->databaseHandle->sql_fetch_row($this->databaseHandle->exec_SELECTquery('post_text','tx_mmforum_posts_text',"deleted='0' AND hidden='0' AND post_id='$post_id'"));

			$linkParams[$this->prefixId] = array(
				'action'		=> 'list_post',
				'tid'			=> $this->get_topic_id($post_id),
				'pid'			=> 'last'
			);

			$marker['###ACTIONLINK###'] = $this->escapeURL($this->pi_linkTP_keepPIvars_url());
			$marker['###POSTTEXT###']   = $this->tx_mmforum_postparser->main($this, $this->conf, $posttext, 'textparser');

			$marker['###FORMOPTIONS###'] .= '<input type="hidden" name="tx_mmforum_pi1[action]" value="'.$this->escape($this->piVars['action']).'" />';
			$marker['###FORMOPTIONS###'] .= '<input type="hidden" name="tx_mmforum_pi1[pid]" value="'.$post_id.'" />';

			$content = $this->cObj->substituteMarkerArrayCached($template, $marker);
		} else {
			$content = $this->errorMessage($conf,$this->pi_getLL('postalert.errorNoLogin'));
		}

		return $content;
	}

	/**
	 * @return array|bool
	 */
	function getModeratorBoards() {
		$this->parent = $this;
		$result = $this->tx_mmforum_postqueue->getModeratorBoards();
		unset($this->parent);
		return $result;
    }

}

if (defined("TYPO3_MODE") && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_postalert.php"])    {
    include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_postalert.php"]);
}
