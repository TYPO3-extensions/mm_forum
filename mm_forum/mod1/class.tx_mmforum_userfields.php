<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2007-2009 Mittwald CM Service GmbH & Co. KG
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
 *   56: class tx_mmforum_userFields
 *   66:     function main($content)
 *   87:     function getLL($key)
 *   99:     function generateLink($param)
 *  111:     function init()
 *  123:     function getMaxSorting()
 *  140:     function saveData()
 *  237:     function displayExtForm()
 *  342:     function generateImageOptions($value,$path=false,$origPath='')
 *  372:     function displayFieldTable()
 *
 * TOTAL FUNCTIONS: 9
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

include_once '../includes/user/class.tx_mmforum_usermanagement.php';

/**
 * This class handles the extension of the mm_forum user profile with
 * custom fields. These fields and their contents are stored in two
 * seperate database tables.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    2009-02-14
 * @copyright  2007-2009 Martin Helmich, Mittwald CM Service GmbH & Co. KG
 * @package    mm_forum
 * @subpackage Backend
 */
class tx_mmforum_userFields extends tx_mmforum_usermanagement {

    /**
     * The main function.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-16
     * @return  string The module content
     */
    function main($content='') {
        $this->init();

        if($this->ufVars['edit']) $content .= $this->displayExtForm($this->ufVars['edit']);
        else                      $content .= $this->displayFieldTable();

        return $content;
    }

    /**
     * Gets a language variable from the locallang_userfields.xml file.
     * Wrapper function to simplify retrieval of language dependent
     * strings.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @param   string $key The language string key
     * @return  string      The language string
     */
    function getLL($key) { return $GLOBALS['LANG']->getLL('userFields.'.$key); }

    /**
     * Generates a link. Function was written to simplify link generation.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @param   string $param The link parameters
     * @return  string        The link
     */
    function generateLink($param,$mode=0) {
        $set = $this->p->MOD_SETTINGS['function'];
		switch($mode) {
			default:
			case 0: return '<a href="index.php?SET[function]='.$set.$param.'">'; break;
			case 1: return 'index.php?SET[function]='.$set.$param; break;
		}
    }

    /**
     * Initializes the module
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @return  void
     */
    function init() {
		$this->ufVars = t3lib_div::_GP('tx_mmforum_userfields');
        $GLOBALS['LANG']->includeLLFile('EXT:mm_forum/mod1/locallang_userfields.xml');
    }

    /**
     * Retrieves a new sorting index.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @return int A new sorting index.
     */
    function getMaxSorting() {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'MAX(sorting)',
            'tx_mmforum_userfields',
            '1'
        );
        list($max) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        return $max+1;
    }

    /**
     * Saves data submitted from the user field listing view.
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-15
     * @return void
     */
    function saveData() {
        if($this->ufVars['moveUp']) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid,sorting',
                'tx_mmforum_userfields',
                'uid='.intval($this->ufVars['moveUp']).' AND deleted=0'
            );
            list($uid_1,$sorting_1) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid,sorting',
                'tx_mmforum_userfields',
                'sorting > '.$sorting_1.' AND deleted=0',
                '',
                'sorting ASC'
            );
            list($uid_2,$sorting_2) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

            $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_userfields SET sorting='.$sorting_1.' WHERE uid='.$uid_2);
            $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_userfields SET sorting='.$sorting_2.' WHERE uid='.$uid_1);
        }
        if($this->ufVars['moveDown']) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid,sorting',
                'tx_mmforum_userfields',
                'uid='.intval($this->ufVars['moveDown']).' AND deleted=0'
            );
            list($uid_1,$sorting_1) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid,sorting',
                'tx_mmforum_userfields',
                'sorting < '.$sorting_1.' AND deleted=0',
                '',
                'sorting DESC'
            );
            list($uid_2,$sorting_2) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

            $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_userfields SET sorting='.$sorting_1.' WHERE uid='.$uid_2);
            $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_mmforum_userfields SET sorting='.$sorting_2.' WHERE uid='.$uid_1);
        }
        foreach((array)$this->ufVars['field'] as $uid => $data) {

            if(intval($uid)>0) {
            	if(strlen($data['delete']) > 0) {
                    $updateArr = array(
                        'tstamp'        => time(),
                        'deleted'       => 1
                    );
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_userfields','uid='.$uid,$updateArr);
                }
            }
        }
        return $content;
    }

	function generateTSConfig($meta) {

		$config = array();

		if($meta['link']) {
			$config['datasource'] = $meta['link'];
		}

		if($meta['required']) {
			$config['required'] = '1';
		}

		$config['label'] = 'TEXT';
		$config['label.'] = array(
			'value'			=> $meta['label']['default']
		);

		foreach($meta['label'] as $key => $content) {
			if($key == 'default') continue;
			$config['label.']['lang.'][$key] = $content;
		}

		switch($meta['type']) {
			case 'text':
				switch($meta['text']['validate']) {
					case 'num':		$config['validate'] = '/^[-+]?[0-9]*\.?[0-9]+$/'; break;
					case 'alnum':	$config['validate'] = '/^\w*$/'; break;
					case 'email':	$config['validate'] = "/^[a-z0-9!#\$%\*\/\?\|\^\{\}`~&'\+\-=_]([a-z0-9!#\$%\*\/\?\|\^\{\}`~&'\+\-=_\.]*?)[a-z0-9!#\$%\*\/\?\|\^\{\}`~&'\+\-=_]@([a-z0-9]([a-z0-9-]*[a-z0-9])?\.)+[a-z]+$/i"; break;
					case 'url':		$config['validate'] = '/^https?:\/\/([a-zA-Z0-9_\-]+:[^\s@:]+@)?((([a-zA-Z][a-zA-Z0-9\-]+\.)+[a-zA-Z\-]+)|((2(5[0-5]|[0-4][0-9])|[01][0-9]{2}|[0-9]{1,2})\.(2(5[0-5]|[0-4][0-9])|[01][0-9]{2}|[0-9]{1,2})\.(2(5[0-5]|[0-4][0-9])|[01][0-9]{2}|[0-9]{1,2})\.(2(5[0-5]|[0-4][0-9])|[01][0-9]{2}|[0-9]{1,2})))(:[0-9]{1,5})?(\/[!~*\'\(\)a-zA-Z0-9;\/\\\?:\@&=\+\$,%#\._-]*)*$/'; break;
					case 'date':	$config['validate'] = '/^[0-9]{1,2}\. [0-9]{1,2}\. [0-9]{4}$/'; break;
				}

				$length = ($meta['text']['length']>0) ? 'maxlength="'.$meta['text']['length'].'"' : '';

				$config['input'] = 'HTML';
				$config['input.']['value'] = '<input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###" '.$length.' />';
				break;
			case 'radio':
				$config['input'] = 'COA';
				$i = 10;
				foreach($meta['radio']['value'] as $key=>$value) {
					$config['input.'][$i] = 'CASE';
					$config['input.'][$i.'.'] = array(
						'key.'					=> array(
							'field'					=> 'fieldvalue'
						),
						"$key"					=> 'HTML',
						"$key."					=> array(
							'value'					=> '<div><input type="radio" name="###USERFIELD_NAME###" checked="checked" value="'.$key.'" /> '.htmlspecialchars($value).'</div>',
						),
						"default"					=> 'HTML',
						"default."					=> array(
							'value'					=> '<div><input type="radio" name="###USERFIELD_NAME###" value="'.$key.'" /> '.htmlspecialchars($value).'</div>',
						)
					);
					$i += 10;
				}

				$config['output'] = 'CASE';
				$config['output.']['key.']['field'] = 'fieldvalue';
				foreach($meta['radio']['value'] as $key=>$value) {
					$config['output.'][$key] = 'TEXT';
					$config['output.'][$key.'.']['value'] = htmlspecialchars($value);
				}
			break;
			case 'select':
				$config['input'] = 'COA';
				$i = 20;

				$config['input.']['10'] = 'HTML';
				$config['input.']['10.']['value'] = '<select name="###USERFIELD_NAME###">';

				foreach($meta['select']['value'] as $key=>$value) {
					$config['input.'][$i] = 'CASE';
					$config['input.'][$i.'.'] = array(
						'key.'					=> array(
							'field'					=> 'fieldvalue'
						),
						"$key"					=> 'HTML',
						"$key."					=> array(
							'value'					=> '<option value="'.$key.'" selected="selected" /> '.htmlspecialchars($value).'</option>',
						),
						"default"					=> 'HTML',
						"default."					=> array(
							'value'					=> '<option value="'.$key.'" /> '.htmlspecialchars($value).'</option>',
						)
					);
					$i += 10;
				}

				$config['input.'][$i] = 'HTML';
				$config['input.'][$i.'.']['value'] = '</select>';

				$config['output'] = 'CASE';
				$config['output.']['key.']['field'] = 'fieldvalue';
				foreach($meta['select']['value'] as $key=>$value) {
					$config['output.'][$key] = 'TEXT';
					$config['output.'][$key.'.']['value'] = htmlspecialchars($value);
				}
			break;
			case 'checkbox':
				$config['input'] = 'HTML';
				$config['input.']['value'] = '<input type="hidden" name="###USERFIELD_NAME###" value="0" /><input type="checkbox" name="###USERFIELD_NAME###" value="1" />';

				$config['output'] = 'CASE';
				$config['output.'] = array(
					'key.'				=> array('field' => 'fieldvalue'),
					'0'					=> 'TEXT',
					'0.'				=> array(
						'value'				=> 'No',
						'lang.'				=> array(
							'de'				=> 'Nein'
						),
					),
					'1'					=> 'TEXT',
					'1.'				=> array(
						'value'				=> 'Yes',
						'lang.'				=> array(
							'de'				=> 'Ja'
						),
					),
				);
			break;
		}

		return $config;

	}

	function generateMetaArray($data) {

			/* Validate the link parameter. Check if the regarding
			 * link field is defined in the TCA. If this is not the
			 * case, replace this link with an empty value. */
		if($data['link']) {
			global $TCA;
			t3lib_div::loadTCA('fe_users');

			$fields = array_keys($TCA['fe_users']['columns']);
			$uf_link = in_array($data['link'],$fields) ? $data['link'] : null;
		} else $uf_link = null;

			/* Validate the type parameter. If the parameter is NOT one
			 * of the five allowed parameters, set parameter to 'custom'. */
		$params = array('text','radio','checkbox','custom','select');
		if(!in_array($data['type'],$params))
			$uf_type = 'custom';
		else $uf_type = $data['type'];

			/* Validate the text length parameter. If the parameter is no
			 * valid positive integer (or -1 for unlimited length), the
			 * parameter is set to -1. */
		if(intval($data['text']['length'] != 0))
			$uf_text_length = intval($data['text']['length']);
		else $uf_text_length = -1;

			/* Validate the validation parameter. If the parameter is NOT one
			 * of the six allowed parameters, set parameter to 'none'. */
		$params = array('none','num','alnum','email','url','date');
		if(!in_array($data['text']['validate'],$params))
			$uf_text_validate = 'none';
		else $uf_text_validate = $data['text']['validate'];

			/* Generate the label array. Kick out empty labels. */
		$uf_labels = array();
		foreach($data['label'] as $key => $label) {
			$label['content'] = trim($label['content']);
			$label['lang'] = trim($label['lang']);

			if(strlen($label['content']) * strlen($label['lang']) == 0) continue;

			$uf_labels[$label['lang']] = $label['content'];
		}

			/* Generate the radio value array. Kick out empty values. */
		$uf_radio_values = array();
		foreach($data['radio']['value'] as $key => $label) {
			$label['content'] = trim($label);

			if(strlen($label) == 0) continue;

			$uf_radio_values[] = $label;
		}

			/* Generate the select value array. Kick out empty values. */
		$uf_select_values = array();
		foreach($data['select']['value'] as $key => $label) {
			$label['content'] = trim($label);

			if(strlen($label) == 0) continue;

			$uf_select_values[] = $label;
		}

		$meta = array(
			'label'				=> $uf_labels,
			'required'			=> $data['required'] ? true : false,
			'private'			=> $data['private'] ? true : false,
			'unique'			=> $data['unique'] ? true : false,
			'link'				=> $uf_link,
			'type'				=> $uf_type,
			'text'				=> array(
				'length'			=> $uf_text_length,
				'validate'			=> $uf_text_validate
			),
			'radio'				=> array(
				'value'				=> $uf_radio_values,
			),
			'select'			=> array(
				'value'				=> $uf_select_values,
			)
		);

		return $meta;

	}

	function editField($uid,$data) {

		$meta = $this->generateMetaArray($data);

		if($meta['type'] == 'custom') {
			$config	= $data['custom']['config'];
		} else {
			$confArr = $this->generateTSConfig($meta);
			$config  = $this->p->parseConf($confArr);
		}

		$updateArray = array(
			'tstamp'            => time(),
			'public'            => $meta['private']?'0':'1',
			'uniquefield'       => $meta['unique']?'1':'0',
			'label'             => $meta['label']['default'],
			'meta'              => serialize($meta),
			'config'	        => $config
		);
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_mmforum_userfields', 'uid='.intval($uid), $updateArray);

	}

	function saveNewField($data) {

		$meta = $this->generateMetaArray($data);

		if($meta['type'] == 'custom') {
			$config	= $data['custom']['config'];
		} else {
			$confArr = $this->generateTSConfig($meta);
			$config  = $this->p->parseConf($confArr);
		}

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('MAX(sorting)+1','tx_mmforum_userfields','1');
		list($sorting) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

		$insertArray = array(
			'tstamp'			=> time(),
			'crdate'			=> time(),
			'cruser_id'			=> $GLOBALS['BE_USER']->user['uid'],
			'sorting'			=> $sorting,
			'public'			=> $meta['private']?'0':'1',
			'uniquefield'		=> $meta['unique']?'1':'0',
			'label'				=> $meta['label']['default'],
			'meta'				=> serialize($meta),
			'config'			=> $config
		);
		$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_userfields', $insertArray);
		return $GLOBALS['TYPO3_DB']->sql_insert_id();

	}

	function displayExtForm($uid=-1) {

		if($this->ufVars['action'] == 'save') {
			if($uid == -1)
				$uid = $this->saveNewField($this->ufVars);
			else $this->editField($uid,$this->ufVars);
		}

		if($uid != -1) $userfield = $this->getuserFieldData($uid);

		$template = file_get_contents(t3lib_div::getFileAbsFileName('EXT:mm_forum/res/tmpl/mod1/userfields.html'));
		$template = tx_mmforum_BeTools::getSubpart($template, '###USERFIELD_FORM###');

		$llMarker		= array(
			'###UF_IMG_LANG###'				=> '<img src="img/language.png" title="'.$this->getLL('form.language').'" />',
			'###UF_IMG_RADIOVALUE###'		=> '<img src="img/list-add.png" />',
			'###UF_IMG_DEL###'				=> 'img/edit-delete.png',

			'###UF_LLL_TITLE###'			=> $this->getLL( $uid == -1 ? 'new.title' : 'edit.title' ),
			'###UF_LLL_LABEL###'			=> $this->getLL('field.name'),
			'###UF_LLL_TYPE###'				=> $this->getLL('field.type'),
			'###UF_LLL_REQUIRED###'			=> $this->getLL('field.required'),
			'###UF_LLL_PRIVATE###'			=> $this->getLL('field.private'),
			'###UF_LLL_UNIQUE###'			=> $this->getLL('field.unique'),
			'###UF_LLL_TYPE_TEXT###'		=> $this->getLL('field.type.text'),
			'###UF_LLL_TYPE_RADIO###'		=> $this->getLL('field.type.radio'),
			'###UF_LLL_TYPE_CHECKBOX###'	=> $this->getLL('field.type.checkbox'),
			'###UF_LLL_TYPE_SELECT###'		=> $this->getLL('field.type.select'),
			'###UF_LLL_TYPE_CUSTOM###'		=> $this->getLL('field.type.custom'),
			'###UF_LLL_LINKTOFEUSER###'		=> $this->getLL('field.link'),
			'###UF_LLL_SAVE###'				=> $this->getLL('save'),
			'###UF_LLL_BACK###'				=> $this->getLL('back'),

			'###UF_LLL_TEXT_TITLE###'		=> $this->getLL('field.type.text'),
			'###UF_LLL_RADIO_TITLE###'		=> $this->getLL('field.type.radio'),
			'###UF_LLL_CHECKBOX_TITLE###'	=> $this->getLL('field.type.checkbox'),
			'###UF_LLL_SELECT_TITLE###'		=> $this->getLL('field.type.select'),
			'###UF_LLL_CUSTOM_TITLE###'		=> $this->getLL('field.type.custom'),

			'###UF_LLL_TEXT_LENGTH###'			=> $this->getLL('field.text.length'),
			'###UF_LLL_TEXT_LENGTH_UNLIMITED###'=> $this->getLL('field.text.length.unlim'),
			'###UF_LLL_TEXT_VALIDATE###'		=> $this->getLL('field.text.validate'),
			'###UF_LLL_TEXT_VALIDATE_NONE###'	=> $this->getLL('field.text.validate.none'),
			'###UF_LLL_TEXT_VALIDATE_NUM###'	=> $this->getLL('field.text.validate.num'),
			'###UF_LLL_TEXT_VALIDATE_ALNUM###'	=> $this->getLL('field.text.validate.alnum'),
			'###UF_LLL_TEXT_VALIDATE_EMAIL###'	=> $this->getLL('field.text.validate.email'),
			'###UF_LLL_TEXT_VALIDATE_URL###'	=> $this->getLL('field.text.validate.www'),
			'###UF_LLL_TEXT_VALIDATE_DATE###'	=> $this->getLL('field.text.validate.date'),

			'###UF_LLL_RADIO_VALUES###'			=> $this->getLL('field.radio.values'),
			'###UF_LLL_SELECT_VALUES###'		=> $this->getLL('field.select.values'),

			'###UF_BACKLINK###'					=> $this->generateLink('',1),
		);
		$template = tx_mmforum_BeTools::substituteMarkerArray($template, $llMarker);

		if($uid == -1) {
			$template = tx_mmforum_BeTools::substituteSubpart($template, '###USERFIELD_FORM_LABEL###', '');
			$template = tx_mmforum_BeTools::substituteSubpart($template, '###USERFIELD_FORM_RADIOVALUE###', '');
			$template = tx_mmforum_BeTools::substituteSubpart($template, '###USERFIELD_FORM_SELECTVALUE###', '');
			$marker = array(
				'###UF_TYPE###'				=> 'text',
				'###UF_REQUIRED###'			=> 'false',
				'###UF_PRIVATE###'			=> 'false',
				'###UF_UNIQUE###'			=> 'false',
				'###UF_LABEL###'			=> '',
				'###UF_TEXT_LENGTH###'		=> -1,
				'###UF_TEXT_VALIDATE###'	=> 'none',
				'###UF_FEUSER_FIELDS###'	=> $this->getFeUserFields(),
				'###UF_UID###'				=> -1,
				'###UF_CONFIG###'			=> ''
			);
		} else {
			$lTemplate	= tx_mmforum_BeTools::getSubpart($template, '###USERFIELD_FORM_LABEL###');
			$rTemplate	= tx_mmforum_BeTools::getSubpart($template, '###USERFIELD_FORM_RADIOVALUE###');
			$sTemplate	= tx_mmforum_BeTools::getSubpart($template, '###USERFIELD_FORM_SELECTVALUE###');

				/* Generate labels */
			foreach((array)$userfield['meta']['label'] as $key=>$content) {
				$lMarker = array(
					'###UF_LANG_CONTENT###'			=> addslashes($content),
					'###UF_LANG_LABEL###'			=> addslashes($key)
				);
				$lContent .= tx_mmforum_BeTools::substituteMarkerArray($lTemplate, $lMarker);
			}
			$template = tx_mmforum_BeTools::substituteSubpart($template, '###USERFIELD_FORM_LABEL###', $lContent);

				/* Generate radio values */
			foreach((array)$userfield['meta']['radio']['value'] as $key=>$content) {
				$rMarker = array(
					'###UF_RADIO_VALUE###'			=> addslashes($content),
				);
				$rContent .= tx_mmforum_BeTools::substituteMarkerArray($rTemplate, $rMarker);
			}
			$template = tx_mmforum_BeTools::substituteSubpart($template, '###USERFIELD_FORM_RADIOVALUE###', $rContent);

				/* Generate select values */
			foreach((array)$userfield['meta']['select']['value'] as $key=>$content) {
				$sMarker = array(
					'###UF_SELECT_VALUE###'			=> addslashes($content),
				);
				$sContent .= tx_mmforum_BeTools::substituteMarkerArray($sTemplate, $sMarker);
			}
			$template = tx_mmforum_BeTools::substituteSubpart($template, '###USERFIELD_FORM_SELECTVALUE###', $sContent);

			$marker = array(
				'###UF_TYPE###'				=> $userfield['meta']['type'],
				'###UF_REQUIRED###'			=> $userfield['meta']['required']?'true':false,
				'###UF_PRIVATE###'			=> $userfield['meta']['private']?'true':false,
				'###UF_UNIQUE###'			=> $userfield['meta']['unique'] ? true : false,
				'###UF_LABEL###'			=> '',
				'###UF_TEXT_LENGTH###'		=> $userfield['meta']['text']['length'],
				'###UF_TEXT_VALIDATE###'	=> $userfield['meta']['text']['validate'],
				'###UF_FEUSER_FIELDS###'	=> $this->getFeUserFields($userfield['meta']['link']),
				'###UF_UID###'				=> $userfield['uid'],
				'###UF_CONFIG###'			=> htmlspecialchars($userfield['config'])
			);
		}

		$template = tx_mmforum_BeTools::substituteMarkerArray($template, $marker);

		return $template;

	}

	function getFeUserFields($selected='') {

			/* Get TCA of fe_user table */
		global $TCA;
		t3lib_div::loadTCA('fe_users');

		$content = '<option value=""></option>';

			/* Iterate through all fields and retrieve labels. */
		foreach($TCA['fe_users']['columns'] as $field => $fConfig) {
			$label = $GLOBALS['LANG']->sL($fConfig['label'],$fConfig['label']);
			$label = preg_replace('/:$/','',$label);
			$arr[] = array($label,$field);

			$content .= '<option value="'.htmlspecialchars($field).'" '.($selected==$field?'selected="selected"':'').'>'.htmlspecialchars($label).'</option>';
		}
		return $content;
	}

	    /**
	     * Displays a list of all user defined fields.
	     *
	     * @author  Martin Helmich <m.helmich@mittwald.de>
	     * @version 2009-02-14
	     * @return  string A list of all user defined fields.
	     */
    function displayFieldTable() {

        $content .= $this->saveData();

		$template = file_get_contents(t3lib_div::getFileAbsFileName('EXT:mm_forum/res/tmpl/mod1/userfields.html'));
		$template = tx_mmforum_BeTools::getSubpart($template, '###USERFIELD_LIST###');

		$iTemplate = tx_mmforum_BeTools::getSubpart($template, '###USERFIELD_LIST_ITEM###');

		$marker = array(
			'###UF_LLL_TITLE###'				=> $this->getLL('list.title'),
			'###UF_LLL_HEAD_NAME###'			=> $this->getLL('field.name'),
			'###UF_LLL_HEAD_EDIT###'			=> $this->getLL('field.options'),
			'###UF_LLL_HEAD_TYPE###'			=> $this->getLL('field.type'),
			'###UF_LLL_NEW###'					=> $this->getLL('list.new'),
			'###UF_LLL_HELP###'					=> $this->getLL('list.help'),

			'###UF_LINK_NEW###'					=> $this->generateLink('&tx_mmforum_userfields[edit]=-1',1)
		);
		$template = tx_mmforum_BeTools::substituteMarkerArray($template, $marker);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_userfields',
            'deleted=0',
            '',
            'sorting DESC'
        );
        $i = 0;
        $max = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
        while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

			$meta = unserialize($arr['meta']);

			$upButton   = ($i>0)?$this->generateLink('&tx_mmforum_userfields[moveUp]='.$arr['uid']).'<img src="img/move-up.png" /></a>':'<img src="../../../../typo3/clear.gif" width="24" height="24" />';
            $downButton = ($i<$max-1)?$this->generateLink('&tx_mmforum_userfields[moveDown]='.$arr['uid']).'<img src="img/move-down.png" /></a>':'<img src="../../../../typo3/clear.gif" width="24" height="24" />';
            $delButton	= $this->generateLink('&tx_mmforum_userfields[field]['.$arr['uid'].'][delete]=1').'<img src="img/edit-delete.png" /></a>';
            $extButton  = $this->generateLink('&tx_mmforum_userfields[edit]='.$arr['uid']).'<img src="img/edit.png" /></a>';

			$iMarker	= array(
				'###UF_NAME###'				=> htmlspecialchars($arr['label']),
				'###UF_TYPE###'				=> $this->getLL('field.type.'.$meta['type']),
				'###UF_EDIT###'				=> $extButton.$delButton.$upButton.$downButton
			);
			$iContent .= tx_mmforum_BeTools::substituteMarkerArray($iTemplate, $iMarker);

			$i ++;
        }

		$template = tx_mmforum_BeTools::substituteSubpart($template, '###USERFIELD_LIST_ITEM###', $iContent);
		$content .= $template;

        return $content;
    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_userfields.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/mod1/class.tx_mmforum_userfields.php']);
}
?>