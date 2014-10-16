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
 *   67: class tx_mmforum_polls
 *   81:     function load($uid)
 *
 *              SECTION: Display functions
 *  106:     function display($poll_id)
 *  130:     function displayPreview($data)
 *  186:     function objVote()
 *  222:     function objDisplay()
 *
 *              SECTION: Data storage functions
 *  291:     function editPoll($poll_id, $data)
 *  373:     function createPoll($data)
 *  430:     function deletePoll($uid,$topic=0)
 *
 *              SECTION: Form creation functions
 *  479:     function display_editForm($uid, $override=array())
 *  574:     function display_createForm($piVars = array())
 *
 *              SECTION: Rights management
 *  640:     function getMayVote()
 *  661:     function getMayCreatePoll()
 *
 * TOTAL FUNCTIONS: 12
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * This class handles polls in the mm_forum.
 * The main purposes of this class are to display polls (and the
 * form allowing to create and edit them) and to store poll information
 * to the database.
 *
 * @author Martin Helmich <m.helmich@mittwald.de>
 * @version 2007-05-25
 * @package mm_forum
 * @subpackage Forum
 */
class tx_mmforum_polls {

    /**
     * Loads a poll record from database.
     * The submitted parameter may either be a poll's UID or a
     * poll record as associative array. In the latter case, the
     * record will not be loaded from database.
     *
     * @param   mixed $uid Either the UID of a poll record or the record
     *                     itself
     * @return  void
     * @version 2007-05-21
     */
    function load($uid) {
        if(!is_array($uid)) {
            $uid = intval($uid);
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'tx_mmforum_polls',
                'uid='.$uid
            );
            $this->data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        } else $this->data = $uid;
    }

    /**
     * Display functions
     */

    /**
     * Displays a poll.
     * This static function should be called in order to display
     * a poll.
     *
     * @param   int    $poll_id The UID of the poll that is to be displayed
     * @return  string          The poll content
     * @version 2007-05-22
     */
    function display($poll_id) {
        if($this->conf['polls.']['enable']) {
            $poll = t3lib_div::makeInstance('tx_mmforum_polls');
            $poll->load($poll_id);
            $poll->conf = $this->conf;
            $poll->piVars = $this->piVars;
            $poll->cObj = $this->cObj;
            $poll->p = $this;

            return $poll->objDisplay();
        } else return "";
    }

    /**
     * Displays a poll preview.
     * This static function should be called in order to display a poll
     * preview. This function is used from the topic creating function in the
     * mm_forum main plugin to display a preview of the topic that is to be
     * created.
     *
     * @param   array  $data An array containing data on the poll to be previewed.
     * @return  string       The poll content
     * @version 2007-05-25
     */
    function displayPreview($data, $pObj) {
        $template = $pObj->cObj->fileResource($pObj->conf['template.']['polls']);
        $template = $pObj->cObj->getSubpart($template, '###POLL_DISPLAY###');
        $template = $pObj->cObj->substituteSubpart($template, '###POLL_SUBMIT###', '');

        $row_template = $pObj->cObj->getSubpart($template, '###POLL_ANSWER_1###');

        $new        = is_array($data['answer']['new'])?$data['answer']['new']:array();
        $edit       = is_array($data['answer']['edit'])?$data['answer']['edit']:array();

        $answers    = array_merge(array_values($edit),array_values($new));

        if($data['expires']['act']) {
            $expDate = mktime($data['expires']['hour'],$data['expires']['minute'],0,$data['expires']['month'],$data['expires']['day'],$data['expires']['year']);
        } else $expDate = 0;

        $i = 0;
        foreach($answers as $answer) {
            if($pObj->conf['polls.']['pollBar_colorMap.'][$i]) {
                $color = $pObj->conf['polls.']['pollBar_colorMap.'][$i];
            }
            else $color = $pObj->conf['polls.']['pollBar_colorMap.']['default'];

            $aMarker = array(
                '###ANSWER_UID###'          => '',
                '###ANSWER_TEXT###'         => $pObj->escape($answer),
                '###ENABLE###'              => 'disabled="disabled"',
            );
            $aContent .= $pObj->cObj->substituteMarkerArray($row_template, $aMarker);
            $i ++;
        }

        $marker = array(
            '###LABEL_POLL###'          => $pObj->pi_getLL('poll.title'),
            '###LABEL_VOTE###'          => $pObj->pi_getLL('poll.vote'),
            '###LABEL_QUESTION###'      => $pObj->pi_getLL('poll.question'),
            '###QUESTION###'            => $pObj->escape($data['question']),
            '###EXPIRES###'             => $expDate?($pObj->pi_getLL('poll.expires').' '.date('d. m. Y, H:i',$expDate)):'',
            '###ICON###'				=> $pObj->cObj->cObjGetSingle($pObj->conf['polls.']['poll_icon'],$pObj->conf['polls.']['poll_icon.']),
        );
        $marker['###EXPIRES###'] = $pObj->cObj->stdWrap($marker['###EXPIRES###'], $pObj->conf['polls.']['expired_stdWrap.']);
        $template = $pObj->cObj->substituteMarkerArray($template, $marker);

        $template = $pObj->cObj->substituteSubpart($template, '###POLL_ANSWER_1###', $aContent);
        $template = $pObj->cObj->substituteSubpart($template, '###POLL_ANSWER_2###', '');

        return $template;
    }

    /**
     * Preforms a single voting process.
     * This function preforms a single voting process (i.e. when a user
     * selects an answer possibility in the post listing view and hits "Vote!").
     * The fact that this user voted on this poll is stored into the database.
     *
     * @version 2008-04-07
     * @return  void
     */
    function objVote() {
        $answer_id = $this->piVars['poll']['answer'];
        $poll_id = $this->data['uid'];
        $user_id = $GLOBALS['TSFE']->fe_user->user['uid'];

		if(!$this->getMayVote()) return;

        $answer_id  = intval($answer_id);
        $poll_id	= intval($poll_id);

        if(!$answer_id) return;

        $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_polls SET votes = votes + 1 WHERE uid='.$poll_id.' AND deleted=0');
        $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_polls_answers SET votes = votes + 1 WHERE uid='.$answer_id.' AND deleted=0');

        $insertArray = array(
            'pid'           => $this->p->getFirstPid(),
            'tstamp'        => time(),
            'crdate'        => time(),
            'poll_id'       => $poll_id,
            'answer_id'     => $answer_id,
            'user_id'       => $user_id
        );
        $GLOBALS['TYPO3_DB']->exec_INSERTquery(
            'tx_mmforum_polls_votes',
            $insertArray
        );
        $this->data['votes'] ++;
    }

    /**
     * Display a poll.
     * This function displays a poll. Depending on whether the user that is
     * currently logged in is allowed to vote on this poll (this will be the
     * case if the user has not already voted in this poll and if the poll is
     * not yet expired), the user will see a set of radio buttons allowing him/her
     * to choose an answering possibility, or the poll results.
     *
     * @return  string The poll content
     * @version 2007-05-22
     */
    function objDisplay() {
        if($this->piVars['poll']['vote'] == '1') $this->objVote();

        $template = $this->cObj->fileResource($this->conf['template.']['polls']);
        $template = $this->cObj->getSubpart($template, '###POLL_DISPLAY###');

        $vote = $this->getMayVote();

        if(!$vote) {
            $template = $this->cObj->substituteSubpart($template, '###POLL_SUBMIT###', '');
            $row_template = $this->cObj->getSubpart($template, '###POLL_ANSWER_2###');
        } else $row_template = $this->cObj->getSubpart($template, '###POLL_ANSWER_1###');

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_polls_answers',
            'poll_id='.intval($this->data['uid']).' AND deleted=0'
        );
        $i = 1;
        while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $pAnswers = ($arr['votes']>0)?round($arr['votes'] / $this->data['votes'] * 100):0;

            if($this->conf['polls.']['pollBar_colorMap.'][$i]) {
                $color = $this->conf['polls.']['pollBar_colorMap.'][$i];
            }
            else $color = $this->conf['polls.']['pollBar_colorMap.']['default'];

            $aMarker = array(
                '###ANSWER_UID###'          => $arr['uid'],
                '###ANSWER_TEXT###'         => $this->p->escape($arr['answer']),
                '###ANSWER_COUNT###'        => sprintf($this->p->pi_getLL('poll.replies'),$arr['votes'],$this->data['votes'],$pAnswers.'%'),
                '###ANSWER_ANSWERS###'      => '<div style="width: '.$pAnswers.'%; height:10px; background-color: '.$color.';">&nbsp;</div>',
                '###ENABLE###'              => '',
            );
            $aContent .= $this->cObj->substituteMarkerArray($row_template, $aMarker);
            $i ++;
        }

        $actionParams[$this->p->prefixId] = array(
            'tid'                       => $this->p->piVars['tid'],
            'fid'                       => $this->p->piVars['fid'],
            'action'					=> 'list_post'
        );
        $actionLink = $this->p->pi_getPageLink($GLOBALS['TSFE']->id,'',$actionParams);

        $marker = array(
            '###LABEL_POLL###'          => $this->p->pi_getLL('poll.title'),
            '###LABEL_VOTE###'          => $this->p->pi_getLL('poll.vote'),
            '###LABEL_QUESTION###'      => $this->p->pi_getLL('poll.question'),
            '###QUESTION###'            => $this->p->escape($this->data['question']),
            '###EXPIRES###'             => $this->data['endtime']?($this->p->pi_getLL('poll.expires').' '.date('d. m. Y, H:i',$this->data['endtime'])):'',
            '###ACTION###'              => $this->p->escapeURL($this->p->tools->getAbsoluteUrl($actionLink)),
            '###ICON###'				=> $this->cObj->cObjGetSingle($this->conf['polls.']['poll_icon'],$this->conf['polls.']['poll_icon.']),
        );
        $marker['###EXPIRES###'] = $this->cObj->stdWrap($marker['###EXPIRES###'], $this->conf['polls.']['expired_stdWrap.']);
        $template = $this->cObj->substituteMarkerArray($template, $marker);

        $template = $this->cObj->substituteSubpart($template, '###POLL_ANSWER_1###', $aContent);
        $template = $this->cObj->substituteSubpart($template, '###POLL_ANSWER_2###', '');

        return $template;
    }

    /**
     * Data storage functions
     */

    /**
     * Edits a poll.
     * This static function handles the editing of a post. This includes
     * updating the poll data itself (i.e. the question), and adding, deleting
     * and editing the answering possibilities.
     *
     * @param   int   $poll_id The UID of the poll that is to be edited
     * @param   array $data    The poll data array
     * @return  void
     * @version 2007-05-25
     */
    function editPoll($poll_id, $data, $pObj) {

        $poll_id = intval($poll_id);
		$mayEdit = $this->getMayEditPoll($poll_id, $pObj);

        if(!$pObj->conf['polls.']['enable']) return $pObj->pi_getLL('poll.disabled');
        if(!tx_mmforum_polls::getMayCreatePoll($pObj) || !$mayEdit) return $pObj->pi_getLL('poll.restricted');

        if(strlen(trim($data['question']))==0) return $pObj->pi_getLL('poll.noQuestion');

        $defACount = $pObj->conf['polls.']['minAnswers'];
        $answerCount = 0;

        if(is_array($data['answer']['new'])) {
	        foreach($data['answer']['new'] as $answer) {
	            if(strlen(trim($answer))>0) $answerCount ++;
	        }
        }
        if(is_array($data['answer']['edit'])) {
	        foreach($data['answer']['edit'] as $answer) {
	            if(strlen(trim($answer))>0) $answerCount ++;
	        }
        }
        if($answerCount < $defACount) return sprintf($pObj->pi_getLL('poll.noAnswers'),$defACount);

        if($data['expires']['act']) {
            $expDate = mktime($data['expires']['hour'],$data['expires']['minute'],0,$data['expires']['month'],$data['expires']['day'],$data['expires']['year']);
        } else $expDate = 0;
        $pollUpdateData = array(
            'tstamp'        => time(),
            'question'      => $data['question'],
            'endtime'       => $expDate
        );

        $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_polls', 'uid='.$poll_id, $pollUpdateData);

        // Edit answering possibilities
        if(is_array($data['answer']['edit'])) {
            foreach($data['answer']['edit'] as $uid => $value) {
                $answer = trim($value);
                if(strlen($value) == 0) {
                    $data['answer']['delete'][] = $uid;
                    continue;
                }
                $answerUpdateArray = array(
                    'tstamp'        => time(),
                    'answer'        => $value
                );
                $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_polls_answers', 'uid='.intval($uid), $answerUpdateArray);
            }
        }
        // Add answers
        if(is_array($data['answer']['new'])) {
            foreach($data['answer']['new'] as $answer) {
                $answerInsertData = array(
                    'pid'           => $pObj->getFirstPid(),
                    'tstamp'        => time(),
                    'crdate'        => time(),
                    'poll_id'       => $poll_id,
                    'votes'         => 0,
                    'answer'        => $answer
                );
                $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_polls_answers', $answerInsertData);
            }
        }
        // Remove answers
        if(is_array($data['answer']['delete'])) {
            foreach($data['answer']['delete'] as $delUid) {
                $delUid = intval($delUid);

                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('votes','tx_mmforum_polls_answers','uid='.$delUid);
                list($votes) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_polls SET votes = votes - '.$votes.' WHERE uid='.$poll_id);
                $answerUpdateArray = array(
                    'tstamp'        => time(),
                    'deleted'       => 1
                );
                $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_polls_answers', 'uid='.$delUid, $answerUpdateArray);
            }
        }
    }

    /**
     * Creates a poll.
     * This static function handles the saving of a newly created
     * poll into the database. This includes storing the poll record
     * itself as well as creating the regarding answering possibilities.
     *
     * @param   array $data The poll data array
     * @return  int         The newly created poll's UID
     * @version 2007-05-25
     */
    function createPoll($data,$pObj) {

        $defACount = $pObj->conf['polls.']['minAnswers'];

        if(!$pObj->conf['polls.']['enable']) return $pObj->pi_getLL('poll.disabled');
        if(!tx_mmforum_polls::getMayCreatePoll($pObj)) return $pObj->pi_getLL('poll.restricted');

        if(strlen(trim($data['question']))==0) return $pObj->pi_getLL('poll.noQuestion');

        $answerCount = 0;
        foreach($data['answer']['new'] as $answer) {
            if(strlen(trim($answer))>0) $answerCount ++;
        }
        if($answerCount < $defACount) return sprintf($pObj->pi_getLL('poll.noAnswers'),$defACount);

        if($data['expires']['act']) {
            $expDate = mktime($data['expires']['hour'],$data['expires']['minute'],0,$data['expires']['month'],$data['expires']['day'],$data['expires']['year']);
        } else $expDate = 0;
        $pollInsertData = array(
            'pid'           => $pObj->getFirstPid(),
            'tstamp'        => time(),
            'crdate'        => time(),
            'crfeuser_id'   => $GLOBALS['TSFE']->fe_user->user['uid'],
            'votes'         => 0,
            'question'      => trim($data['question']),
            'endtime'       => $expDate
        );
        $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_polls', $pollInsertData);
        $poll_id = $GLOBALS['TYPO3_DB']->sql_insert_id();

        foreach($data['answer']['new'] as $answer) {
            $answer = trim($answer);
            if(strlen($answer) == 0) continue;
            $answerInsertData = array(
                'pid'           => $pObj->getFirstPid(),
                'tstamp'        => time(),
                'crdate'        => time(),
                'poll_id'       => $poll_id,
                'votes'         => 0,
                'answer'        => $answer
            );
            $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_polls_answers', $answerInsertData);
        }

        return $poll_id;
    }

    /**
     * Deletes a poll.
     * This function deletes a poll completely from database. This includes
     * the reference to the poll in the tx_mmforum_topics table, the answering
     * possibilities to this poll and the votes that were already made.
     *
     * @version 2007-05-25
     * @param  int  $uid   The UID of the poll that is to be deleted
     * @param  int  $topic The UID of the topic whose poll is to be deleted
     * @return void
     */
    function deletePoll($uid,$topic=0) {
        if($topic == 0) {
            $poll_id   = intval($uid);
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid',
                'tx_mmforum_topics',
                'poll_id='.$poll_id
            );
            if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) $topic_id=0;
            else list($topic_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        }
        elseif($uid == 0) {
            $topic_id = intval($topic);
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'poll_id',
                'tx_mmforum_topics',
                'uid='.$topic
            );
            if($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0) $poll_id=0;
            else list($poll_id) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        }
        else {
            $poll_id    = intval($uid);
            $topic_id   = intval($topic);
        }

        if($poll_id > 0) {
            $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_polls'        ,    'uid='.$poll_id,array('deleted'=>1,'tstamp'=>time()));
            $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_polls_answers','poll_id='.$poll_id,array('deleted'=>1,'tstamp'=>time()));
            $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_polls_votes'  ,'poll_id='.$poll_id,array('deleted'=>1,'tstamp'=>time()));
        }
        if($topic_id > 0)
            $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_topics'       ,    'uid='.$topic_id,array('poll_id'=>0));
    }

    /**
     * Form creation functions
     */

    /**
     * Displays a poll editing form.
     * This function displays a form allowing the user to edit an
     * already existing poll.
     *
     * @param   int    $uid The UID of the poll that is to be edited.
     * @return  string      The form content
     * @version 2007-05-25
     */
    function display_editForm($uid, $override=array(),$pObj=NULL) {
		global $TYPO3_DB;

        if(!$pObj->conf['polls.']['enable']) return "";
        $defACount = $pObj->conf['polls.']['minAnswers'];

        $uid = intval($uid);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_polls',
            'uid='.$uid.' AND deleted=0'
        );
        $poll = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

		$mayEdit = $this->getMayEditPoll($uid, $pObj);

        $template = $pObj->cObj->fileResource($pObj->conf['template.']['polls']);
        $template = $pObj->cObj->getSubpart($template, '###POLL_FORM###');

        $answerTemplate = $pObj->cObj->getSubpart($template, '###ANSWERSECTION###');

        if(strlen($override['expires']['act'])>0) {
            if($override['expires']['act'])
                $expDate = mktime($override['expires']['hour'],$override['expires']['minute'],0,$override['expires']['month'],$override['expires']['day'],$override['expires']['year']);
            else $expDate = 0;
        } else $expDate = $poll['endtime'];

        $marker = array(
            '###LABEL_QUESTION###'      => $pObj->pi_getLL('poll.question'),
            '###LABEL_ANSWERS###'       => $pObj->pi_getLL('poll.answers'),
            '###QUESTION###'            => $pObj->escape($override['question']?$override['question']:$poll['question']),
            '###ANSWER###'              => '',
            '###DELETE###'              => $pObj->pi_getLL('poll.deleteAnswer'),
            '###ADD_ANSWER###'          => $pObj->pi_getLL('poll.addAnswer'),
            '###DELCONFIRM###'          => $pObj->pi_getLL('poll.deleteAnswerConf'),
            '###LABEL_EXPIRES###'       => $pObj->pi_getLL('poll.expires'),
            '###LABEL_NEWANSWER###'     => $pObj->pi_getLL('poll.addAnswer'),
            '###DAY###'                 => $expDate?date("d",$expDate):'DD',
            '###MONTH###'               => $expDate?date("m",$expDate):'MM',
            '###YEAR###'                => $expDate?date("Y",$expDate):'YYYY',
            '###HOUR###'                => $expDate?date("H",$expDate):'HH',
            '###MINUTE###'              => $expDate?date("i",$expDate):'MM',
            '###EXPIRES###'             => $expDate?'checked="checked"':'',
            '###ENB_EXP###'             => $expDate && $mayEdit ?'':'disabled="disabled"',
            '###DELETEFIELDS###'        => '',
            '###DISABLEDSTYLE###'       => $mayEdit ? '' : 'style="display:none;"',
			'###DISABLED###'			=> $mayEdit ? '' : 'disabled="disabled"',
			'###DISABLED_VAR###'		=> $mayEdit ? 0 : 1
        );

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_polls_answers',
            'poll_id='.$poll['uid'].' AND deleted=0'
        ); $i = 0;
        while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $answer = $override['answer']['edit'][$arr['uid']]?$override['answer']['edit'][$arr['uid']]:$arr['answer'];
            if(is_array($override['answer']['delete'])) {
                if(in_array($arr['uid'],$override['answer']['delete'])) {
                    $marker['###DELETEFIELDS###'] .= '<input type="hidden" name="tx_mmforum_pi1[poll][answer][delete][]" value="'.$arr['uid'].'" />';
                    continue;
                }
            }
            $aMarker = array(
                '###ANSWER###'      => $pObj->escape($answer),
                '###ANSWER_UID###'  => $arr['uid'],
                '###ANSWER_MODE###' => 'edit'
            );
            if($i < $defACount)
                $tAnswTmpl = $pObj->cObj->substituteSubpart($answerTemplate, '###DELLINK###', '');
            else $tAnswTmpl = $answerTemplate;
            $answers .= $pObj->cObj->substituteMarkerArrayCached($tAnswTmpl, $aMarker);
            $i ++;
        }
        if(is_array($override['answer']['new'])) {
            foreach($override['answer']['new'] as $nAnswer) {
                $aMarker = array(
                    '###ANSWER###'      => $pObj->escape($nAnswer),
                    '###ANSWER_UID###'  => '',
                    '###ANSWER_MODE###' => 'new'
                );
                if($i < $defACount)
                    $tAnswTmpl = $pObj->cObj->substituteSubpart($answerTemplate, '###DELLINK###', '');
                else $tAnswTmpl = $answerTemplate;
                $answers .= $pObj->cObj->substituteMarkerArrayCached($tAnswTmpl, $aMarker);
                $i ++;
            }
        }

        $template = $pObj->cObj->substituteSubpart($template, '###ANSWERSECTION###', $answers);
        $template = $pObj->cObj->substituteMarkerArrayCached($template, $marker);

        return $template;
    }

    /**
     * Displays a poll creation form.
     * This function displays a form allowing the user to create a new post.
     *
     * @return  string The form content
     * @version 2007-05-25
     */
    function display_createForm($piVars = array(),$pObj=NULL) {
        if(!$pObj->conf['polls.']['enable']) return "";

        $defACount = $pObj->conf['polls.']['minAnswers'];
        $rDefACount = $defACount;
        if($piVars) {
            $defACount = (count($piVars['answer']['new'])>$defACount)?count($piVars['answer']['new']):$defACount;
        }

        $template = $pObj->cObj->fileResource($pObj->conf['template.']['polls']);
        $template = $pObj->cObj->getSubpart($template, '###POLL_FORM###');

        $answerTemplate = $pObj->cObj->getSubpart($template, '###ANSWERSECTION###');

        $marker = array(
            '###LABEL_QUESTION###'      => $pObj->pi_getLL('poll.question'),
            '###LABEL_ANSWERS###'       => $pObj->pi_getLL('poll.answers'),
            '###QUESTION###'            => $pObj->escape($piVars?$piVars['question']:''),
            '###ANSWER###'              => '',
            '###DELETE###'              => $pObj->pi_getLL('poll.deleteAnswer'),
            '###ADD_ANSWER###'          => $pObj->pi_getLL('poll.addAnswer'),
            '###DELCONFIRM###'          => $pObj->pi_getLL('poll.deleteAnswerConf'),
            '###LABEL_EXPIRES###'       => $pObj->pi_getLL('poll.expires'),
            '###LABEL_NEWANSWER###'     => $pObj->pi_getLL('poll.addAnswer'),
            '###ENB_EXP###'             => $piVars['expires']['act']?'':'disabled="disabled"',
            '###DAY###'                 => $piVars['expires']['act']?$piVars['expires']['day']:'DD',
            '###MONTH###'               => $piVars['expires']['act']?$piVars['expires']['month']:'MM',
            '###YEAR###'                => $piVars['expires']['act']?$piVars['expires']['year']:'YYYY',
            '###HOUR###'                => $piVars['expires']['act']?$piVars['expires']['hour']:'HH',
            '###MINUTE###'              => $piVars['expires']['act']?$piVars['expires']['minute']:'MM',
            '###EXPIRES###'             => $piVars['expires']['act']?'checked="checked"':'',
            '###DELETEFIELDS###'        => '',
      			'###DISABLED###'			      => $this->getMayCreatePoll($pObj) ? '' : 'disabled="disabled"',
			      '###DISABLED_VAR###'		    => $this->getMayCreatePoll($pObj) ? 0 : 1
        );
        $template = $pObj->cObj->substituteMarkerArrayCached($template, $marker);

        for($i = 0; $i < $defACount; $i ++) {
            $marker = array(
                '###ANSWER###'      => $pObj->escape($piVars?$piVars['answer']['new'][$i]:''),
                '###ANSWER_UID###'  => '',
                '###ANSWER_MODE###' => 'new',
                '###DELETE###'      => $pObj->pi_getLL('poll.deleteAnswer'),
      			    '###DISABLED###'		=> '',
            );
            if($i < $rDefACount)
                $tAnswTmpl = $pObj->cObj->substituteSubpart($answerTemplate, '###DELLINK###', '');
            else $tAnswTmpl = $answerTemplate;
            $answers .= $pObj->cObj->substituteMarkerArrayCached($tAnswTmpl, $marker);
        }

        $template = $pObj->cObj->substituteSubpart($template, '###ANSWERSECTION###', $answers);

        return $template;
    }

    /**
     * Rights management
     */

    /**
     * Determines if the user that is currently logged in is allowed to vote in a poll.
     * This function determines if the user that is currently logged in
     * is allowed to participate in a poll. This will not be the case if the
     * poll is already expired, if there is no user logged in or if the user
     * has already participated in this poll.
     *
     * @return boolean TRUE, if the current user may vote, otherwise false.
     */
    function getMayVote() {
        if($this->data['endtime']>0 && $this->data['endtime']<time()) return false;
        if(!$GLOBALS['TSFE']->fe_user->user['uid']) return false;

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_polls_votes',
            'poll_id='.intval($this->data['uid']).' AND user_id='.$GLOBALS['TSFE']->fe_user->user['uid'].' AND deleted=0'
        );
        return ($GLOBALS['TYPO3_DB']->sql_num_rows($res)==0);
    }

    /**
     * Determines if the user that is currently logged in is allowed to create a poll.
     * This function determines if the user that is currently logged
     * in is allowed to create a poll. This checks if polls are enabled in
     * general and if poll creation is limited to certain user groups.
     *
     * @return  boolean TRUE, if the current user may create a post, otherwise false.
     * @version 2007-05-22
     */
    function getMayCreatePoll($pObj) {
        if(!$pObj->conf['polls.']['enable']) return false;
        if($pObj->conf['polls.']['restrictToGroups']) {
            $authPolls  = t3lib_div::intExplode(',',$pObj->conf['polls.']['restrictToGroups']);
		    $groups     = $GLOBALS['TSFE']->fe_user->groupData['uid'];

            $authPolls  = tx_mmforum_tools::processArray_numeric($authPolls);
            $groups     = tx_mmforum_tools::processArray_numeric($groups);

            if(count($authPolls)==0) return true;

            $i = array_intersect($authPolls, $groups);
            return (count($i)>0);
        }
        return true;
    }



		/**
		 *
		 * Determines whether the currently logged in user is allowed to edit an
		 * existing poll. Polls can only be edited if they have not been voted
		 * on yet. Or if you are an administrator. Administrators are allowed to
		 * do everythink. Just like root. Or god.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 2010-01-07
		 * @param   int            $pollId The UID of the poll that is to be
		 *                                 checked.
		 * @param   tx_mmforum_pi1 $pObj   The parent object. Usually, this is
		 *                                 instance of the tx_mmforum_pi1 class.
		 * @return  boolean                TRUE, if the poll may be edited,
		 *                                 otherwise FALSE.
		 *
		 */

	function getMayEditPoll($pollId, $pObj) {
		global $TYPO3_DB;
		if(!$this->getMayCreatePoll($pObj)) return false;
		list($voteCount) = $TYPO3_DB->sql_fetch_row($TYPO3_DB->exec_SELECTquery('COUNT(*)', 'tx_mmforum_polls_votes', 'poll_id='.intval($pollId).' AND deleted=0'));
		return $voteCount == 0 || $pObj->getIsAdmin();
	}

}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_polls.php"])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi1/class.tx_mmforum_polls.php"]);
}
?>