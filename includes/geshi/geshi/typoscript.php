<?php
/*************************************************************************************
 * typoscript.php
 * ---------------
 * Author: Oliver Thiele (typo3@oliver-thiele.de)
 * Copyright: (c) 2005 Oliver Thiele
 * Release Version: 1.0.6
 * CVS Revision Version: $Revision: 0.0.1 $
 * Date Started: 2005/04/12
 * Last Modified: $Date: 2007/04/13 $
 *
 * TypoScript 3.7 language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2004/07/14 (1.0.0)
 *   -  First Release
 *
 * TODO (updated 2004/11/27)
 * -------------------------
 *
 *************************************************************************************
 *
 *     This file is part of GeSHi.
 *
 *   GeSHi is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   GeSHi is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with GeSHi; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ************************************************************************************/

if(!defined('GESHI_TS_REF')) define('GESHI_TS_REF',$_SERVER['SERVER_NAME']);
 
$language_data = array (
	'LANG_NAME' => 'TypoScript',
	'COMMENT_SINGLE' => array(1 => '//', 2 => '#'),
	'COMMENT_MULTI' => array('/*' => '*/'),
	'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
	'QUOTEMARKS' => array("'", '"'),
	'ESCAPE_CHAR' => '',
	'KEYWORDS' => array(

# Conditions
		1 => array(
			'browser', 'version', 'system', 'device', 'useragent', 'IP', 'hostname',
			'hour', 'minute', 'dayofweek', 'dayofmonth', 'month',
			'usergroup', 'loginUser', 'treelevel', 'PIDinRootline', 'PIDupinRootline',
			'globalVar', 'globalString', 'userFunc'
			),
# Functions
		2 => array(
			'stdWrap', 'imgResource','imageLinkWrap', 'numRows', 'select', 'split', 'if', 'typolink',
			'textStyle', 'encapsLines', 'tableStyle', 'addParams', 'filelink', 'parseFunc', 'makeLinks',
			'tags', 'HTMLparser_tags', 'HTMLparser'
			),
# Setup
		3 => array(
			'CONFIG', 'CONSTANTS', 'PAGE', 'FE_DATA', 'FE_TABLE', 'FRAMESET', 'FRAME', 'META', 'CARRAY'
			),
# cObjects
		4 => array(
			'HTML', 'TEXT', 'COA_INT', 'COA', 'FILE', 'IMAGE', 'IMG_RESSOURCE', 'CLEARGIF',
			'CONTENT', 'HMENU', 'RECORDS', 'CTABLE', 'OTABLE', 'COLUMNS', 'HRULER', 'IMGTEXT',
			'CASE', 'LOAD_REGISTER', 'RESTORE_REGISTER', 'FORM', 'SEARCHRESULT', 'USER_INT', 'USER',
			'PHP_SCRIPT_INT', 'PHP_SCRIPT_EXT', 'PHP_SCRIPT', 'TEMPLATE', 'MULTIMEDIA', 'EDITPANEL'
			),
# MENU Objects
		5 => array(
			'GMENU_LAYERS', 'GMENU_FOLDOUT', 'GMENU', 'TMENU_LAYERS', 'TMENUITEM', 'TMENU',
			'IMGMENUITEM', 'IMGMENU', 'JSMENUITEM', 'JSMENU'
			),
#Menuzustände
		6 => array(                                                                                
			'NO', 'RO', 'ACT', 'CUR', 'IFSUB', 'ACTIFSUB', 'USR', 'SPC', 'USERDEF1', 'USERDEF2', 'ACTRO', 'ACTIFSUBRO', 'IFSUBRO','CURRO'
			),
		7 => array(
# Eigenschaften Config
			'simulateStaticDocuments', 'baseURL', 'sys_language_uid',  'language', 'locale_all',
			'linkVars', 'doctype', 'tx_realurl_enable', 'xhtml_cleaning', 'htmlTag_langKey',
			'spamProtectEmailAddresses', 'spamProtectEmailAddresses_atSubst', 'admPanel',
			'index_enable', 'index_externals', 'xmlprologue'
			),
		8 => array(
# Eigenschaften Page
			'typeNum', 'bodyTagCObject', 'bodyTagMargins', 'bodyTagAdd', 'bodyTag', 'bgImg', 'frameset',
			'meta', 'shortcutIcon', 'headerData', 'includeLibs', 'stylesheet', 'includeCSS',
			'CSS_inlineStyle', 'insertClassesFromRTE', 'noLinkUnderline', 'hoverStyle', 'hover',
			'smallFormFields', 'admPanelStyles'
			),
		9 => array(
			# DB Tabellen
			'tt_content', 'fe_users', 'be_users', 'pages', 'tt_news',
			# DB Felder
			'select_key', 'title', 'subtitle', 'nav_title', 'sorting', 'colPos', 'deleted', 'hidden',
			'crdate', 'tstamp', 'uid', 'pid', 'header_layout', 'header',
			),
		10 => array(
# Eigenschaften
			# Template
			'template', 'marks', 'workOnSubpart',
			# HMENU
			'special', 'entryLevel', 'expAll',
			# CONTENT
			'orderBy', 'where', 'languageField',
			# Wrap
			'wrap', 'linkWrap', 'allWrap', 'case',
            # IMAGE
            'offset', 'mask',
			# getData
			'setCopntentToCurrent', 'setCurrent', 'lang', 'data', 'field', 'current', 'cObject',
			'numRows', 'filelist', 'preUserFunc',
			'ATagParams',
			 'table', 'value', 'dataWrap', 'dataArray', 'file',
# TLO
			'temp', 'plugin', 'seite', 'lib', 'resources', 'sitetitle', 'types',

			),
	 ),
	'SYMBOLS' => array(
			'|*|', '||', '|', '//', '(', ')', '{', '}', '/', '=',
			 '!', '@', '%', '&', '*', '/', '<', '>'
		),
	'CASE_SENSITIVE' => array(
		GESHI_COMMENTS => false,
		1 => true,
		2 => true,
		3 => true,
		4 => true,
		5 => true,
		6 => true,
		7 => true,
		8 => true,
		9 => true,
		10 => true,
		),
	'STYLES' => array(
		'KEYWORDS' => array(
			1 => 'color: #BF2236; font-weight: bold;',
			2 => 'color: #000099; font-weight: bold;',
			3 => 'color: #000066; font-weight: bold;',
			4 => 'color: #BF2236; font-weight: bold;',
			5 => 'color: #009900; font-weight: bold;',
			6 => 'color: #009900; font-weight: bold;',
			7 => 'color: #009900;',
			8 => 'color: #334ECF;',
			9 => 'color: #CF8C19;',
			10 => 'color: #334ECF;'
			),
		'COMMENTS' => array(
			'MULTI' => 'color: #808080; font-style: italic;'
			),
		'ESCAPE_CHAR' => array(
			0 => 'color: #000099; font-weight: bold;'
			),
		'BRACKETS' => array(
			0 => 'color: #BF2236; font-weight: bold;'
			),
		'STRINGS' => array(
			0 => 'color: #808080;'
			),
		'NUMBERS' => array(
			0 => 'color: #cc66cc;'
			),
		'METHODS' => array(
			0 => 'color: #334ECF;'
			),
		'SYMBOLS' => array(
			0 => 'color: #BF2236;'
			),
		'SCRIPT' => array(
			0 => 'color: #00bbdd;',
			1 => 'color: #ddbb00;',
			2 => 'color: #009900;',
			3 => 'color: #00bbdd;',
			4 => 'color: #ddbb00;',
			5 => 'color: #009900;',
			6 => 'color: #009900;',
			7 => 'color: #006600;',
			8 => 'color: #006600;',
			9 => 'color: #009900;',
			10 => 'color: #009900;'
			),
		'REGEXPS' => array(
			0 => 'color: #BF2236;'
			)
		),
	'URLS' => array(
		1 => 'http://'.GESHI_TS_REF.'/tsref/conditions/{FNAME}/',
		2 => 'http://'.GESHI_TS_REF.'/tsref/functions/{FNAME}/',
		3 => 'http://'.GESHI_TS_REF.'/tsref/setup/{FNAME}/',
		4 => 'http://'.GESHI_TS_REF.'/tsref/cobject/{FNAME}/',
		5 => 'http://'.GESHI_TS_REF.'/tsref/menu_objects/{FNAME}/',
		6 => 'http://'.GESHI_TS_REF.'/tsref/menu_objects/menu_zustaende/index.html#{FNAME}',
		7 => 'http://'.GESHI_TS_REF.'/tsref/setup/config/index.html#{FNAME}',
		8 => 'http://'.GESHI_TS_REF.'/tsref/setup/page/index.html#{FNAME}',
		9 => '',
		10 => ''
		),
	'OOLANG' => false,
	'OBJECT_SPLITTERS' => array(
		0 => '.',
		),
	'REGEXPS' => array(
			// 0 => "(\\[)([a-zA-Z0-9]*)",
	/*
			2 => array(
			GESHI_SEARCH => '(^\[)([a-z])(\])',
			GESHI_REPLACE => '\\2',
			GESHI_MODIFIERS => '',
			GESHI_BEFORE => '\\1',
			GESHI_AFTER => '\\3'
			),
	*/
		),
	'STRICT_MODE_APPLIES' => GESHI_NEVER,
	'SCRIPT_DELIMITERS' => array(),
	'HIGHLIGHT_STRICT_BLOCK' => array()
);

?>