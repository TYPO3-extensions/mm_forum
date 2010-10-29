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
 *   68: class tx_mmforum_postparser
 *   86:     function main($parent,$conf,$text,$job='textparser')
 *  117:     function parse_text($text,$parent,$conf)
 *  143:     function linkgenerator($content,$conf,$switch)
 *  199:     function getMailTo($mailAddress,$linktxt,$initP='?')
 *  228:     function bbcode2html($text,$parent,$conf)
 *  295:     function processList($matches)
 *  322:     function generate_smilies($text,$parent,$conf)
 *  352:     function validate_email($email)
 *  370:     function syntaxhighlighting($content, $parent, $conf)
 *  421:     function zitat($text,$parent, $conf)
 *  462:     function decode_entities($text)
 *  479:     function links($text, $conf)
 *
 * TOTAL FUNCTIONS: 12
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
if (t3lib_extMgm::isLoaded('geshilib')) {
	include_once ( t3lib_extMgm::siteRelPath('geshilib') . 'res/geshi.php' );
} elseif (!class_exists("GeSHi")) { // Checks if there is an instance of this class already in use!
	include_once ( t3lib_extMgm::extPath('mm_forum') . 'includes/geshi/geshi.php' );
}

/**
 * The class 'tx_mmforum_postparser' is a tool class for parsing the
 * posts in the message board. It generates code blocks, quotes, etc.
 * Also manages some security issues, e.g. preventing code insertion
 * by posting JavaScript.
 *
 * @author     Björn Detert <b.detert@mittwald.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    07.05. 2007
 * @package    mm_forum
 * @subpackage Includes
 * @copyright  2007, Mittwald CM Service
 */
class tx_mmforum_postparser {

	/**
	 * Initialization function. A text is submitted as parameter and the parsed text
	 * is returned.
	 *
	 * @author  Björn Detert <b.detert@mittwald.de>
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 20. 9. 2006
	 * @param   object $parent The calling object (regulary of type tx_mmforum_pi1), so this
	 *                         object inherits all configuration and language options from the
	 *                         calling object.
	 * @param   array  $conf   The calling plugin's configuration vars
	 * @param   string $text   The text that is to be parsed
	 * @param   strong $job    The task to fulfil. At the moment, the only possible job is "textparser"
	 * @return  string         The parsed text.
	 */
	function main($parent, $conf, $text, $job='textparser') {

		$postParserObj = t3lib_div::makeInstance('tx_mmforum_postparser');

		switch ($job) {
			case 'textparser':
				$content = $postParserObj->parse_text($text, $parent, $conf);
				break;
			default:
				$content = $parent->pi_getLL('postparser_noJob');
				break;
		}
		return $content;
	}

	/**
	 * Parses the text.
	 * This function parses the text submitted as parameter. Delegates the
	 * different parsing tasks to the regarding functions.
	 * The different parsing options can be configured via TypoScript
	 *
	 * @author  Björn Detert <b.detert@mittwald.de>
	 * @version 20. 9. 2006
	 * @param   string $text The text to be parsed
	 * @param   object $parent The calling object (regulary of type tx_mmforum_pi1), so this
	 *                         object inherits all configuration and language options from the
	 *                         calling object.
	 * @param   array  $conf   The calling plugin's configuration vars
	 * @return  string         The parsed text
	 */
	function parse_text($text, $parent, $conf) {
		$content = $text;
		#if($conf['config.']['plugin.']['tx_mmforum_pi1.']['postparser.']['links'] ==1)				$content = postparser::links($text, $myConf);
		if ($conf['postparser.']['bb_code_parser'] == 1)
			$content = $this->bbcode2html($content, $parent, $conf);
		if ($conf['postparser.']['smilie_generator'] == 1)
			$content = $this->generate_smilies($content, $parent, $conf);
		if ($conf['postparser.']['zitat_div'] == 1)
			$content = $this->zitat($content, $parent, $conf);
		$content = nl2br(str_replace('\r\n', "\r\n", $content));
		if ($conf['postparser.']['syntaxHighlighter'] == 1)
			$content = $this->syntaxhighlighting($content, $parent, $conf);
		/* Need for no double parsing, for example that the codeboxes are not double parsed. That really would not good look :-) */
		$content = str_replace("###DONT_PARSE_AGAIN_START###", "", $content);
		$content = str_replace("###DONT_PARSE_AGAIN_ENDE###", "", $content);
		return $content;
	}

	/**
	 * Generates crypted email links.
	 *
	 * @author  Björn Detert <b.detert@mittwald.de>
	 * @version 20. 9. 2006
	 * @param   string $content The string in which the email links are to be generated
	 * @param   array  $conf    The calling plugin's configuration vars
	 * @param   string $switch  Determines the parsing option. At the moment, the only accepted
	 *                          value is 'cryptmail'.
	 * @return  string          The text with email links
	 */
	function linkgenerator($content, $conf, $switch) {
		switch ($switch) {
			case 'cryptmail':
				$patterns = array(
					'1' => array(
						'pattern' => "/\[url\]mailto\:(.*?)\[\/url\]/isS",
						'front' => "[url]mailto:",
						'end' => "[/url]"
					),
					'2' => array(
						'pattern' => "/\[url\]http\:\/\/mailto\:(.*?)\[\/url\]/isS",
						'front' => "[url]http://mailto:",
						'end' => "[/url]"
					),
					'3' => array(
						'pattern' => "/\[link\]mailto\:(.*?)\[\/link\]/isS",
						'front' => "[link]mailto:",
						'end' => "[/link]"
					),
					'4' => array(
						'pattern' => "/\[link\]http\:\/\/mailto\:(.*?)\[\/link\]/isS",
						'front' => "[link]http://mailto:",
						'end' => "[/link]"
					),
				);
				while (list($k, $v) = each($patterns)) {
					$bool = preg_match_all($v['pattern'], $content, $res_arr);
					if ($bool == TRUE && is_array($res_arr)) {
						while (list($key, $value) = each($res_arr[1])) {
							$value = str_replace('mailto:', '', $value);
							$link = $this->getMailTo($value, $value, '');
							$content = str_replace($v['front'] . $value . $v['end'], '<a href="' . $link[0] . '">' . $link[1] . '</a>', $content);
						}
					}
				}
				return $content;
				break;
			default:
				// Nothing at the moment :-)
				break;
		}
	}

	/**
	 * Generates data for a single crypted email link from the mail address.
	 *
	 * @author  Björn Detert <b.detert@mittwald.de>
	 * @version 20. 9. 2006
	 * @param   string $mailAddress The email address to be linked
	 * @param   string $linktxt     The text of the link
	 * @param   string $initP       ?
	 * @return  array               A numeric array containing the data for the email
	 *                              link. Index 0 contains the URL, index 1 contains the link
	 *                              text.
	 */
	function getMailTo($mailAddress, $linktxt, $initP='?') {
		if (!strcmp($linktxt, '')) {
			$linktxt = $mailAddress;
		}
		$mailToUrl = 'mailto:' . $mailAddress;
		if ($GLOBALS['TSFE']->spamProtectEmailAddresses) {
			$email_substr = $parent->config['email_subst'] = $GLOBALS['TSFE']->tmpl->setup['config.']['spamProtectEmailAddresses_atSubst'];
			$mailToUrl = "javascript:linkTo_UnCryptMailto('" . $GLOBALS['TSFE']->encryptEmail($mailToUrl) . "');";
			$mailToUrl = str_replace('<', '&lt;', $mailToUrl);
			$linktxt = str_replace('@', $email_substr, $linktxt);
			$lastDotLabel = $lastDotLabel ? $lastDotLabel : '[dot]';
			$linktxt = preg_replace('/\.([^\.]+)$/', $lastDotLabel . '$1', $linktxt);
		}
		return array($mailToUrl, $linktxt);
	}

	/**
	 * Parses BBCode in a string.
	 * This function parses all BBCodes in a string. Most of the BBCodes and
	 * their substitution patterns are loaded from database.
	 *
	 * @author  Björn Detert <b.detert@mittwald.de>
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-12-08
	 * @param   string $text   The text to be parsed
	 * @param   object $parent The calling object (regulary of type tx_mmforum_pi1), so this
	 *                         object inherits all configuration and language options from the
	 *                         calling object.
	 * @param   array  $conf   The calling plugin's configuration vars
	 * @return  string         The parsed string
	 */
	function bbcode2html($text, $parent, $conf) {

		/* Parse special characters */
		$text = $parent->validatorObj->specialChars($text);

		/* SET Buffer markers for Syntax Highlithing Boxes */
		/* ======================== */
		/* getting all activated syntaxhl languages out of the database ... */
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'lang_title,lang_pattern,lang_code',
						'tx_mmforum_syntaxhl',
						'deleted=0 AND hidden=0'
		);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 0)
			$buffer = array();
		else {
			while ($data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$check = preg_match_all($data['lang_pattern'], $text, $buffer[$data['lang_title']]);
				$text = preg_replace($data['lang_pattern'], '###' . $data['lang_title'] . '_BUFFER_MARKER_MMCMS###', $text);
			}
		}
		$text = $this->links($text, $conf);
		$text = $this->linkgenerator($text, $conf, 'cryptmail');
		$text = $this->typolinks($text, $parent);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'pattern,replacement',
						'tx_mmforum_postparser',
						'deleted=0'
		);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
			while ($data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$text = preg_replace($data['pattern'], $data['replacement'], $text);
			}
		}
		/* FILLING the Markers by TYPOScript Vars */
		$text = str_replace('###TARGET###', $conf['postparser.']['bb_code_linktarget'], $text);
		/* Different CSS classes for internal and external links */
		if ($conf['postparser.']['bb_code_parser_differlinkclass'] == 1) {
			$hostname = str_replace('www.', '', $_SERVER['SERVER_NAME']);
			$temptext = '';
			foreach (explode('<a ', $text) as $value) {
				if (strpos($value, 'href=') > -1) {
					$temptext .= '<a ';
				}
				if (strpos($value, $hostname) === FALSE) {
					$temptext .= str_replace('###CSS_CLASS###', $conf['postparser.']['bb_code_linkclass'], $value);
				} else {
					$temptext .= str_replace('###CSS_CLASS###', $conf['postparser.']['bb_code_linkclassinternal'], $value);
				}
			}
			$text = $temptext;
			unset($temptext);
			unset($value);
		} else {
			$text = str_replace('###CSS_CLASS###', $conf['postparser.']['bb_code_linkclass'], $text);
		}
		/* remove unneeded HTML-code */
		$text = str_replace('target=""', '', $text);
		$text = str_replace('class=""', '', $text);
		/* the list bb code */
		$patterns = array(
			"/\[list\](.*?)\[\/list\]/isS",
			"/\[list:[a-z0-9]{10}\](.*?)\[\/list:[a-z0-9]{10}\]/isS",
			"/\[list=[a-z0-9]{1}:[a-z0-9]{10}\](.*?)\[\/list:[a-z0-9]{1}:[a-z0-9]{10}\]/isS"
		);
		while (list($k, $v) = each($patterns)) {
			#$text  = preg_replace_callback($v,create_function('$treffer',' return "<ul>".str_replace("[*]","</li><li>",$treffer[1])."</ul>";'),$text);
			$text = preg_replace_callback($v, array('tx_mmforum_postparser', 'processList'), $text);
		}
		$text = str_replace('<li></li>', '', $text);
		while (list($k, $v) = each($buffer)) {
			foreach ($v[0] as $code_item) {
				$text = preg_replace('/###' . $k . '_BUFFER_MARKER_MMCMS###/', "$code_item", $text, 1);
			}
		}
		return $text;
	}

	/**
	 * Parses a list.
	 * This function generates a XHTML compatible list of items that is
	 * generated from a string where the respective items are marked by
	 * [*] in the beginning.
	 * This function is used as callback function for a preg_replace_callback
	 * expression.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2007-07-17
	 * @param   array  $matches An array containing the matches of the regular
	 *                          expression.
	 * @return  string          The XHTML list.
	 */
	function processList($matches) {
		$listContent = $matches[1];

		$itemString = '';
		$itemsRaw = explode('[*]', $listContent);
		foreach ($itemsRaw as $itemRaw) {
			$itemRaw = trim($itemRaw);
			if (strlen($itemRaw) > 0)
				$itemString .= '<li class="tx-mmforum-listitem">' . $itemRaw . '</li>';
		}

		if (strlen($itemString) > 0)
			return '<ul class="tx-mmforum-list">' . $itemString . '</ul>';
	}

	/**
	 * Substitutes smilie tags like :) or ;) with corresponding <img> tags.
	 * The smilie tags and there image equivalents are loaded from database.
	 *
	 * @author  Björn Detert <b.detert@mittwald.de>
	 * @version 20. 9. 2006
	 * @param   string $text   The text to be parsed
	 * @param   object $parent The calling object (regulary of type tx_mmforum_pi1), so this
	 *                         object inherits all configuration and language options from the
	 *                         calling object.
	 * @param   array  $conf   The calling plugin's configuration vars
	 * @return  string         The parsed string
	 */
	function generate_smilies($text, $parent, $conf) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'code,smile_url',
						'tx_mmforum_smilies',
						'deleted=0',
						'',
						'LENGTH(code) DESC'
		);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

			$uploadPath = 'uploads/tx_mmforum/' . $row['smile_url'];

			If (!file_exists($uploadPath)) {
				if (substr($conf['postparser.']['bb_code_path_smilie'], 0, 4) == 'EXT:')
					$smiliepath = tx_mmforum_tools::generateSiteRelExtPath($conf['postparser.']['bb_code_path_smilie'] . $row['smile_url']);
				else
					$smiliepath = $conf['postparser.']['bb_code_path_smilie'] . $row['smile_url'];
			} Else
				$smiliepath = $uploadPath;

			$smilieimage = '<img src="' . $smiliepath . '" alt="' . $row['smile_url'] . '" />';
			#$text 			= 	str_replace(' '.$row['code'].' ',$smilieimage,$text);
			$text = str_replace($row['code'], $smilieimage, $text);
		}
		return $text;
	}

	/**
	 * Validates an email address.
	 * @param  string  $email The email address to be validated
	 * @return boolean        TRUE, if the email address is valid, otherwise FALSE
	 * @deprecated This function is not used anymore
	 */
	function validate_email($email) {
		(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) ? $bool = TRUE : $bool = FALSE;
		return $bool;
	}

	/**
	 * Highlights the content parts marked as source code using the GeSHi
	 * class.
	 * @author  Björn Detert <b.detert@mittwald.de>
	 * @version 20. 9. 2006
	 * @param   string $content The text to be parsed
	 * @param   object $parent  The calling object (regulary of type tx_mmforum_pi1), so this
	 *                          object inherits all configuration and language options from the
	 *                          calling object.
	 * @param   array  $conf    The calling plugin's configuration vars
	 * @return  string          The parsed string
	 */
	function syntaxhighlighting($content, $parent, $conf) {
		/* Path to Geshi Syntax-Highlighting files. */
		$path = t3lib_div::getFileAbsFileName('EXT:mm_forum/includes/geshi/geshi/', $onlyRelative = 1, $relToTYPO3_mainDir = 0);
		($conf['postparser.']['tsrefUrl']) ? define('GESHI_TS_REF', $conf['postparser.']['tsrefUrl']) : define('GESHI_TS_REF', 'www.typo3.net');

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'lang_title,lang_pattern,lang_code',
						'tx_mmforum_syntaxhl',
						'deleted=0'
		);
		while ($data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			preg_match_all($data['lang_pattern'], $content, $source_arr);
			while (list($key, $value) = each($source_arr[1])) {
				$value = trim($this->decode_entities($value));
				if ($data['lang_title'] == 'php') {
					if (!preg_match("/<\?/", trim(substr($value, 0, 6))))
						$value = "<?\n" . $value . "\n?>";
				}
				$geshi = new GeSHi($value, $data['lang_code'], $path);
				$geshi->set_header_type(GESHI_HEADER_PRE);
				#$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
				$geshi->set_line_style('background: ' . $conf['postparser.']['sh_linestyle_bg'] . ';', 'background: ' . $conf['postparser.']['sh_linestyle_bg2'] . ';', true);
				$geshi->set_overall_style('margin:0px;', true);
				$geshi->enable_classes();
				$style = '<style type="text/css"><!--';
				$style .= $geshi->get_stylesheet();
				$style .= '--></style>';
				$geshi->enable_strict_mode('FALSE');
				$replace = $geshi->parse_code();
				$time = $geshi->get_time();
				$CodeHead = '<div class="tx-mmforum-pi1-codeheader">' . strtoupper($data['lang_title']) . '</div>'; // $code_header , check this out?? I get confused ^^
				$replace = '###DONT_PARSE_AGAIN_START###' . $CodeHead . '<div class="tx-mmforum-pi1-codeblock">' . $style . $replace . '</div>###DONT_PARSE_AGAIN_ENDE###';
				$content = str_replace($source_arr[0][$key], $replace, $content);
			}
		}
		return $content;
	}

	/**
	 * Parses quotes in the text.
	 *
	 * @author  Björn Detert <b.detert@mittwald.de>
	 * @version 20. 9. 2006
	 * @param   string $text   The text to be parsed
	 * @param   object $parent The calling object (regulary of type tx_mmforum_pi1), so this
	 *                         object inherits all configuration and language options from the
	 *                         calling object.
	 * @param   array  $conf   The calling plugin's configuration vars
	 * @return  string         The parsed string
	 */
	function zitat($text, $parent, $conf) {
		preg_match_all('/\[quote:[a-zA-Z0-9]{10}="[^\"]{1,30}"]/isS', $text, $quote_data);
		while (list ($k, $v) = each($quote_data)) {
			$pattern = '/"[^\"]{1,30}"/';
			while (list ($k1, $v1) = each($v)) {
				preg_match($pattern, $v1, $arr_with_names);
				while (list ($k2, $v2) = each($arr_with_names)) {
					$text = preg_replace('/\[quote:[a-zA-Z0-9]{10}="[^\"]{1,30"]/isS',
									'<div class="' . $conf['postparser.']['quoteClass'] . '">' . str_replace("\"", "", $v2) . ' ' . $parent->pi_getLL('quote_prefix') . '<br />', $text, 1);
				}
			}
		}
		$text = preg_replace('/\[quote:[a-zA-Z0-9]{10}]/isS', '<div class="' . $conf['postparser.']['quoteClass'] . '">', $text);
		$text = preg_replace('/\[\/quote:[a-zA-Z0-9]{10}]/isS', '</div>', $text);
		$text = preg_replace("/\[quote\](.*?)\[\/quote\]/isS", "<div class=\"" . $conf['postparser.']['quoteClass'] . "\">\\1</div>", $text);
		$text = preg_replace('/\[\/quote]/isS', '</div>', $text);

		preg_match_all('/\[quote=(?:&quot;|")[^\"]{1,30}(?:&quot;|")]/isS', $text, $quote_data);
		while (list ($k, $v) = each($quote_data)) {
			$pattern = '/(?:&quot;|")[^\"]{1,30}(?:&quot;|")/';
			while (list ($k1, $v1) = each($v)) {
				preg_match($pattern, $v1, $arr_with_names);
				while (list ($k2, $v2) = each($arr_with_names)) {
					$text = preg_replace('/\[quote=(?:&quot;|")[^\"]{1,30}(?:&quot;|")]/isS', '<div class="' . $conf['postparser.']['quoteClass'] . '">' . str_replace("\"", "", $v2) . ' ' . $parent->pi_getLL('quote_prefix') . '<br />', $text, 1);
				}
			}
		}
		$text = preg_replace('/\[quote:[a-zA-Z0-9]{10}]/isS', '<div class="' . $conf['postparser.']['quoteClass'] . '">', $text);
		$text = preg_replace('/\[\/quote:[a-zA-Z0-9]{10}]/isS', '</div>', $text);
		$text = preg_replace("/\[quote\](.*?)\[\/quote\]/isS", "<div class=\"" . $conf['postparser.']['quoteClass'] . "\">\\1</div", $text);
		return $text;
	}

	/**
	 * Decodes HTML entities.
	 * @author  Björn Detert <b.detert@mittwald.de>
	 * @version 20. 9. 2006
	 * @param   string $text The text to be parsed
	 * @return  string       The parsed string
	 */
	function decode_entities($text) {
		#$text= html_entity_decode($text,ENT_QUOTES,"ISO-8859-1"); #NOTE: UTF-8 does not work!
		$text = html_entity_decode($text, ENT_QUOTES);
		$text = preg_replace('/&#(\d+);/me', "chr(\\1)", $text); #decimal notation
		$text = preg_replace('/&#x([a-f0-9]+);/mei', "chr(0x\\1)", $text);  #hex notation
		$text = str_replace("<br />", "", $text);
		return $text;
	}

	/**
	 * Generates links.
	 *
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 * @param  string $text The text, in which the links are to be generated.
	 * @param  array  $conf The calling plugin's configuration vars
	 * @return string       The parsed string
	 */
	function links($text, $conf) {
		$text = preg_replace("/(\s)http:\/\/([\S]+?)(\s)/i", "$1[url=\"http://$2\"]http://$2[/url]$3", " $text ");
		$text = preg_replace("/(\s)([\.a-z0-9-]+@[\.a-z0-9-]+\.[a-z]+)(\s)/i", "$1[url]mailto:$2[/url]$3", "$text");
		return $text;
	}

	/**
	 * Generates TYPO3 links.
	 *
	 * @author Hauke Hain <hhpreuss@googlemail.com>
	 * @param  string $text The text, in which the links are to be generated.
	 * @param  object $parent The calling object (regulary of type tx_mmforum_pi1), so this
	 *                        object inherits all configuration and language options from the
	 *                        calling object.
	 * @return string       The parsed string
	 */
	function typolinks($text, &$parent) {
		$ausgabe = array();
		preg_match_all('(record\:[\w_]+\:\d+)', $text, $ausgabe);
		if (is_array($ausgabe) && is_array($ausgabe[0])) {
			for ($i = 0; $i < sizeof($ausgabe[0]); ++$i) {
				$text = str_replace($ausgabe[0][$i], $this->typoLinkURL($ausgabe[0][$i], $parent), $text);
			}
		}
		return $text;
	}

	/**
	 * Returns TYPO3 URL with the help of a linkhandler.
	 * uses the TypoScript of the linkhandler of the extension tinymcr_rte
	 *
	 * @author Hauke Hain <hhpreuss@googlemail.com>
	 * @param  string $id The link id or record parameter
	 * @param  object $parent The calling object (regulary of type tx_mmforum_pi1), so this
	 *                        object inherits all configuration and language options from the
	 *                        calling object.
	 * @return string       The parsed string
	 */
	function typoLinkURL($id, &$parent) {
		$localcObj = t3lib_div::makeInstance('tslib_cObj');
		$SERVER_NAME = $_SERVER['SERVER_NAME'];

		if (strpos($SERVER_NAME, 'http://') === false) {
			$SERVER_NAME = 'http://' . $SERVER_NAME;
		}

		if (strpos($id, 'record:page:') === false) {
			$PagesTSconfig = $GLOBALS['TSFE']->getPagesTSconfig();
			$linkhandler = $PagesTSconfig['RTE.']['default.']['linkhandler.'];
			$linkHandlerData = t3lib_div::trimExplode(':', $id);

			if (is_array($GLOBALS['TCA'][$linkHandlerData[1]]['ctrl'])) {
				$row = $this->getRecordRow($linkHandlerData[1], $linkHandlerData[2], $localcObj);
				$localcObj->start($row, ''); // make data available in TypoScript
				$lconf = array();
				if (is_array($linkhandler[$linkHandlerData[1] . '.'][$row['pid'] . '.'])) {
					$lconf = $linkhandler[$linkHandlerData[1] . '.'][$row['pid'] . '.'];
				} else {
					$lconf = $linkhandler[$linkHandlerData[1] . '.']['default.'];
				}
				// remove the tinymce_rte specific attributes
				unset($lconf['select'], $lconf['sorting']);
				return $SERVER_NAME . '/' . $localcObj->typoLink_URL($lconf);
			}

			return $id;
		} else {
			return $SERVER_NAME . '/' . $localcObj->typoLink_URL(array('parameter' => str_replace('record:page:', '', $id)));
		}
	}

	/**
	 * ('EXT:tinymce_rte/hooks/class.tx_tinymce_rte_handler.php:&tx_tinymce_rte_handler')
	 * allow to use links as "record:tt_news:3"
	 * original by Daniel Poetzinger (AOE media GmbH) in extension linkhandler
	 *
	 * @author Thomas Allmer <thomas.allmer@webteam.at>
	 *
	 */
	function getRecordRow($table, $uid, &$localcObj) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, 'uid=' . intval($uid) . $localcObj->enableFields($table), '', '');
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		return $row;
	}

}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/includes/class.tx_mmforum_postparser.php"]) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/includes/class.tx_mmforum_postparser.php"]);
}
?>
