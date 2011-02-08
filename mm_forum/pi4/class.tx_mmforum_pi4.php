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
 *   78: class tx_mmforum_pi4 extends tslib_pibase
 *   91:     function main($content,$conf)
 *  138:     function index($content,$param,$conf)
 *  203:     function microtime_float()
 *  221:     function searchform($conf,$param)
 *  333:     function searchfind($conf,$searchstring,$param)
 *  521:     function pagebar($items, $diff, $param)
 *  581:     function find_posts($word_id_list,$param,$words = 0,$username = '')
 *  683:     function get_posttext($post_id)
 *  698:     function wordArray ($conf,$string)
 *  727:     function word_id($word)
 *  747:     function clear_phpBB($string)
 *  797:     function search_stat ()
 *  814:     function list_forum($cat_id)
 *  838:     function list_cat()
 *  865:     function list_cat_tree()
 *  884:     function get_search_results($searchstring,$param)
 *  946:     function topic_information($topic_id)
 *  958:     function post_information($post_id)
 *  970:     function get_username($user_id)
 *  984:     function getLanguage()
 * 1065:     function getMayRead_forum_query($prefix="")
 *
 * TOTAL FUNCTIONS: 24
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(t3lib_extMgm::extPath('mm_forum') . 'includes/class.tx_mmforum_base.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi4/class.tx_mmforum_indexing.php');
require_once(t3lib_extMgm::extPath('mm_forum') . 'pi1/class.tx_mmforum_pi1.php');

/**
 * Plugin 'Forum Search' for the 'mm_forum_search' extension.
 * The plugin 'Search' for the 'mm_forum' extension displays the
 * search form to the user and conducts searches and presents the
 * results to the user.
 * Search queries are stored in the database for caching reasons.
 *
 * @author     Holger Trapp <h.trapp@mittwald.de>
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Björn Detert <b.detert@mittwald.de>
 * @copyright  2008 Mittwald CM Service
 * @version    2008-10-07
 * @package    mm_forum
 * @subpackage Search
 */
class tx_mmforum_pi4 extends tx_mmforum_base {
	var $prefixId 		= 'tx_mmforum_pi4';					// Same as class name
	var $scriptRelPath	= 'pi4/class.tx_mmforum_pi4.php';	// Path to this script relative to the extension dir.

	/**
	 * The plugin's main functions. Handles indexing and delegates
	 * output and search tasks to the regarding functions.
	 * @param  string $content The content
	 * @param  array  $conf    The plugin's configuration vars
	 * @return string          The plugin content
	 */
	function main($content, $conf) {
		$time = $this->microtime_float();
		$this->init($conf);
        $this->pi_USER_INT_obj = 1;

        $paramA = (t3lib_div::_GP('mmfsearch') ? t3lib_div::_GP('mmfsearch') : array());
        $paramB = $this->piVars?$this->piVars:array();
        $param = t3lib_div::array_merge_recursive_overrule($paramB,$paramA);

		$this->conf['min_length']   = $conf['sword_minLength'];
		$this->conf['show_items']   = $conf['resultsPerPage'];

		$conf = $this->conf;

        $content = $this->index($content,$param,$conf);


		$param = $this->piVars;

		$content   .= $this->searchform($conf,$param);

		if($this->piVars['searchstring']) {
			$content   .= $this->searchfind($conf,$this->piVars['searchstring'],$param);
		}

		if($this->conf['displaySearchDuration'])
			$content.= '<br />'.$this->pi_getLL('debug.duration').": ".($this->microtime_float()-$time);

		return $this->pi_wrapInBaseClass($content);
	}

	/**
	 * Conducts the indexing process.
	 * The indexing process can only be executed by admin users.
	 *
	 * @param  string $content The plugin content
	 * @param  array  $param   The plugin configuration vars
	 * @return string          The indexing output
	 */
	function index($content,$param,$conf) {

        if(! md5($this->piVars['indexingPassword']) == $conf['indexingPassword']) {
            if(!$GLOBALS['TSFE']->fe_user) return $content;
            $groups = t3lib_div::intExplode(',',$GLOBALS['TSFE']->fe_user->user['usergroup']);
            if(!in_array($conf['grp_admin'],$groups)) return $content;
        }

		// Index a specific topic
		if($param['ind_topic']) {
			$content .= tx_mmforum_indexing::ind_topic($param['ind_topic'],$conf);
			echo $this->cObj->substituteMarker($this->pi_getLL('indexing.topicIndexed'),'###TOPIC###',$param['ind_topic']);
		}
		// Checking if an indexing is currently running
		if($param['ind_check']) {
			$content .= tx_mmforum_indexing::ind_check();
		}

			// Instantiate indexing class
		$indexing = t3lib_div::makeInstance('tx_mmforum_indexing');
		$indexing->conf = $this->conf;

		// Indexes all topics currently
		if($param['ind_auto']) {
			if($indexing->ind_check() == 0) {

                $intern_forums = array ();

                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'uid',
                    'tx_mmforum_forums',
                    'forum_internal = 1'
                );
                while ($row    = mysql_fetch_assoc($res)) {
                    array_push($intern_forums,$row[uid]);
                }

                $intern_forums_list = implode(',',$intern_forums);



				$noIndex_cond = (strlen($conf['noIndex_boardUIDs'])>0)?'forum_id NOT IN ('.$conf['noIndex_boardUIDs'].')':'1';

                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'*',
					'tx_mmforum_topics',
					'('.$noIndex_cond.') and deleted=0',
					'',
					'tx_mmforumsearch_index_write ASC',
					$conf['indexCount']
				);

				while ($row     = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				    $content   .= $indexing->ind_topic($row['uid'],$conf);
					# $content   .= $row['uid'].' **<br />';
				}
			} else {
				echo $this->pi_getLL('indexing.running');
			}
		}

		return $content;
	}

	function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	/**
	 * Outputs the search form.
	 * The search form lets the user specify, in which board he wishes to
	 * search, in which order the results are to be sorted, if unsolved topics
	 * are to be hidden and so on.
	 *
	 * @version 13. 04. 2007
	 * @param   array  $conf  The plugin's configuration vars
	 * @param   array  $param The parameter array for this function. Obsolete, since
	 *                        this array is identical to the this->piVars array.
	 * @return  string        The search form
	 */
	function searchform($conf,$param) {
		$template = $this->cObj->fileResource($conf['template']);
		$template = $this->cObj->getSubpart($template, "###SEARCHFORM###");

		$this->piVars['searchstring'] = stripslashes($this->piVars['searchstring']);

        $marker = array(
            '###LLL_SEARCH###'      => $this->pi_getLL('searchform.title'),
            '###LLL_SEARCHSTRING###'=> $this->pi_getLL('searchform.searchstring'),
            '###LLL_ADVANCED###'    => $this->pi_getLL('searchform.advanced'),
            '###LLL_INDEXING###'    => $this->pi_getLL('searchform.indexing'),
            '###LLL_SEARCHPLACE###' => $this->pi_getLL('searchform.searchplace'),
            '###LLL_SEARCHORDER###' => $this->pi_getLL('searchform.orderBy'),
            '###LLL_ONLYSOLVED###'  => $this->pi_getLL('searchform.onlySolved'),
            '###LLL_GROUP###'       => $this->pi_getLL('searchform.onePost'),
            '###LLL_QUICKGUIDE###'  => $this->pi_getLL('searchform.quickGuideExpand'),
            '###LLL_SUBMIT###'      => $this->pi_getLL('searchform.submit'),
            '###LABEL_QUICKGUIDE###'		=> $this->pi_getLL('searchform.quickGuide'),
            '###LABEL_QUICKGUIDE_INDEX###'  => $this->pi_getLL('searchform.quickGuideIndex'),
			'###LABEL_WILDCARD_HEADER###'	=> $this->pi_getLL('searchform.wildcard.header'),
			'###LABEL_WILDCARD_CONTENT###'	=> $this->pi_getLL('searchform.wildcard.content'),
			'###LABEL_NEGATIVE_HEADER###'	=> $this->pi_getLL('searchform.negative.header'),
			'###LABEL_NEGATIVE_CONTENT###'	=> $this->pi_getLL('searchform.negative.content'),
			'###LABEL_USERSEARCH_HEADER###'	=> $this->pi_getLL('searchform.usersearch.header'),
			'###LABEL_USERSEARCH_CONTENT###'=> $this->pi_getLL('searchform.usersearch.content'),
			'###LABEL_ORDERBY###'			=> $this->pi_getLL('searchform.orderBy'),
			'###LABEL_ONLYSOLVED###'		=> $this->pi_getLL('searchform.onlySolved'),
			'###LABEL_ONEPOST###'			=> $this->pi_getLL('searchform.onePost'),
			'###LABEL_QUICKGUIDE_EXPAND###'	=> $this->pi_getLL('searchform.quickGuideExpand'),
			'###LABEL_SUBMIT###'			=> $this->pi_getLL('searchform.submit'),
			'###IMG_SUBMIT###'				=> $this->conf['path_img'].$this->getLanguage().$conf['images.']['start_search'],
        );

		if($this->getIsAdmin()) {
            $linkParams[$this->prefixId] = array('ind_auto' => 1);
            $marker['###INDEX_LINK###'] = '&raquo; <a href="'.$this->pi_getPageLink($GLOBALS["TSFE"]->id,'',$linkParams).'" >'.$this->pi_getLL('searchform.startIndex').'</a>';
            $marker['###INDEX_HELP###'] = '(&#187;&nbsp;<a href="javascript:show_Index()"><strong>'.$this->pi_getLL('searchform.quickGuideExpand').'</strong></a>)';
            $marker['###LABEL_START_INDEX###'] = $this->pi_getLL('searchform.Index.header');
            $marker['###START_INDEX_CONTENT###'] = $this->pi_getLL('searchform.Index.content');
        } else {
            $template = $this->cObj->substituteSubpart($template, '###INDEXING_SECTION###', '');
        }

		$marker['###ACTIONLINK###']     = $this->pi_getPageLink($GLOBALS["TSFE"]->id);
		$marker['###SEARCHSTRING###']   = $this->escape($this->piVars['searchstring']);
		$marker['###PLACES###']        .= '<option selected="selected">'.$this->pi_getLL('searchform.searchAllBoards').'</option>';
		$cat_tree = $this->list_cat_tree();
		$count = 0;
		foreach($cat_tree as $val) {
			IF($val['type'] == 'C') {
				if($count>0) $marker['###PLACES###'] .= '</optgroup>';
				$marker['###PLACES###']     .= '<optgroup label="'.$this->escape($val['name']).'">';
			} else {
				IF($val['uid'] == $param['search_place']) $select = 'selected';
				else $select = '';

				$marker['###PLACES###']     .= '<option value="'.intval($val['uid']).'" '.$select.'>'.$this->escape($val['name']).'</option>';

				if($count == count($cat_tree)-1) $marker['###PLACES###'] .= '</optgroup>';
			}
			$count ++;
		}

		$orderOptionArray   = array(
			'2' => $this->pi_getLL('searchform.sorting.dateDesc'),
			'1' => $this->pi_getLL('searchform.sorting.dateAsc'),
			'3' => $this->pi_getLL('searchform.sorting.repliesMost'),
			'4' => $this->pi_getLL('searchform.sorting.repliesLeast'),
		);

		foreach ($orderOptionArray as $key => $val) {
			IF($this->piVars['search_order'] == $key)
				$marker['###ORDER_OPTION###']  .=  '<option value="'.$key.'" selected>'.$val.'</option>';
			else
				$marker['###ORDER_OPTION###']  .=  '<option value="'.$key.'">'.$val.'</option>';
		}

		IF (is_numeric($this->piVars['search_place']) OR $this->piVars['solved'] == 1 OR $this->piVars['search_order'] > 0) {
			$marker['###SEARCH_OPTIONS_DISPLAY###'] = 'block';
		} else {
			$marker['###SEARCH_OPTIONS_DISPLAY###'] = 'none';
		}

		IF (!empty($this->piVars['solved'])) {
			$marker['###SOLVED###'] = "checked=\"checked\"";
		} else {
			$marker['###SOLVED###'] = '';
		}

		IF ((empty($this->piVars['groupPost']) AND empty($this->piVars['searchstring'])) OR ($this->piVars['groupPost'] == 1) ) {
			$marker['###POSTGROUP###'] = "checked=\"checked\"";
		} else {
			$marker['###POSTGROUP###'] = '';
		}

		if($conf['debug_mode'] == 1) {
			t3lib_div::debug ($this->piVars);
		}
		
			// Include hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['search']['additionalFormMarkers'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['search']['additionalFormMarkers'] as $userFunction) {
				$params = array(
					'marker' => $marker,
				);
				$marker = t3lib_div::callUserFunction($userFunction, $params, $this);
			}
		}

		$content    = $this->cObj->substituteMarkerArrayCached($template, $marker);
		return $content;
	}

	/**
	 * Conducts a search and displays the results.
	 * This function gets the search results from the database, dependent
	 * of the search string.
	 *
	 * @version 11. 10. 2006
	 * @param   array  $conf         The plugin's configuration vars
	 * @param   string $searchstring The string of search words
	 * @param   array  $param        The parameter array for this function. Obsolete, since
	 *                               this array is identical to the this->piVars array.
	 * @return  string               The search results
	 */
	function searchfind($conf,$searchstring,$param) {
		$template		= $this->cObj->fileResource($conf['template']);
		$template       = $this->cObj->getSubpart($template, "###SEARCHRESULT###");
		$template_sub   = $this->cObj->getSubpart($template, "###SEARCHRESULT_SUB###");

		// Language dependent markers
		$marker = array(
			'###LABEL_RESULTS###'				=> $this->pi_getLL('search.results'),
			'###LABEL_TOPICINFO###'				=> $this->pi_getLL('search.topicinfo'),
			'###LABEL_REPLIES###'				=> $this->pi_getLL('search.replies'),
			'###LABEL_SOLVED###'				=> $this->pi_getLL('search.solved'),
			'###LABEL_CRUSER###'				=> $this->pi_getLL('search.cruser'),
			'###LABEL_CRDATE###'				=> $this->pi_getLL('search.crdate'),
            '###LLL_SEARCHRESULTS###'           => $this->pi_getLL('search.searchresults'),
			'###IMG_CORNER###'					=> $conf['path_img'].'search-haken.gif',
			'###IMG_BACKGROUND###'				=> $conf['path_img'].'search-back.gif',
		);

		// Replace all special characters with space characters
		$orgsearchstring	= $searchstring;
		$searchstring		= str_replace('"',' ',$searchstring);
		$searchstring		= str_replace('+',' ',$searchstring);
		$searchstring		= str_replace('=',' ',$searchstring);
		//$pattern			= "/[^a-zA-Z0-9äüöÄÜÖß\*:\$._]/";
		//$pattern			= '/[^[\w\*]]/';
		//$pattern			= '/[\W^\*]/';
		$pattern = '/[^\w\*]/';
		#$searchstring = utf8_decode($searchstring);
		$searchstring		= preg_replace($pattern, " ", $searchstring);
		//$searchstring		= utf8_encode($searchstring);


		$post_id_array = $this->get_search_results($searchstring,$param);

		IF (!is_array($post_id_array)) {
			$word_array = explode(' ',$searchstring);

			$good_words = array();
			$bad_words  = array();
			$good_word_count = 0;

			foreach($word_array as $val)
			{
				If(strtolower(substr($val,0,5)) == 'user:') {
					$username = substr($val,5) ;
				}

				// Check if search word reaches minimum length
				if(strlen($val) >= $conf['min_length']) {
					// Check if search word is negated
					if(substr($val,0,1) == '-') {
						$val        = substr($val,1);
						$word_id    = $this->word_id($val);
						IF($word_id) {
							foreach($word_id as $wordval) {
								array_push($bad_words,$wordval);
							}
						}
					} else {
						$word_id    =  $this->word_id($val);
						IF($word_id) {
							$good_word_count++;
							foreach($word_id as $wordval) {
								array_push($good_words,$wordval);
							}
						}
					}
				}
			}

			IF(count($good_words) > 0 OR isset($username))
				$good_posts = $this->find_posts($good_words,$param,$good_word_count,$username);
			IF(count($bad_words) > 0)
				$bad_posts  = $this->find_posts($bad_words,$param);

			IF($bad_posts AND $good_posts) {
				// Remove bad posts
				$post_id_array  = array_diff($good_posts,$bad_posts);
			} else {
				$post_id_array  = $good_posts;
			}

			IF(count($post_id_array) > 0) {
				$post_id_array = array_flip($post_id_array);
			}

			// Write search result to database for later requests
			$userGroups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
			sort($userGroups);
			$insertArray = array(
				'pid'			=> $this->getStoragePID(),
				'tstamp'        => time(),
				'cruser_id'     => $GLOBALS['TSFE']->fe_user->user['uid'],
				'search_string' => $searchstring,
				'array_string'  => serialize($post_id_array),
				'search_place'  => $param['search_place'],
				'solved'        => $param['solved'],
				'search_order'  => $param['search_order'],
				'groupPost'     => $param['groupPost'],
				'user_groups'	=> implode(',',$userGroups),
			);
			$query = $GLOBALS['TYPO3_DB']->INSERTquery('tx_mmforum_searchresults',$insertArray);
			$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, $query);
			if($conf['debug_mode'] == 1) {
				echo '<h1>Suche Indiziert</h1>';
			}
		}


		$treffer = count($post_id_array);

		IF($treffer > 0) {
			$find_array_split   = array_chunk($post_id_array, $conf['show_items'], true);     // Array in Pages Aufteilen
			IF(empty($param['page'])) $param['page'] = 0;

			if (!intval($this->conf['doNotUsePageBrowseExtension'])===0) $page = $param['page'] - 1;
			else $page = $param['page'];

			$post_id_array = $find_array_split[$page];
			foreach($post_id_array as $post_id => $values) {
				$topic_id	= tx_mmforum_pi1::get_topic_id($post_id);
				$topic_info	= $this->topic_information($topic_id);
				$post_info	= $this->post_information($post_id);

				$post_text	= $this->get_posttext($post_id);
				$post_text	= $this->clear_phpBB($post_text);

				$linkparams['tx_mmforum_pi1'] = array (
					'action'=> 'list_post',
					'tid' 	=> $topic_id,
					'pid'   => $post_id,
					'sword' => addslashes($orgsearchstring)
				);
                if(tx_mmforum_pi1::getIsRealURL()) $linkparams['tx_mmforum_pi1']['fid'] = $topic_info['forum_id'];

				$post_text  = $this->escape(tx_mmforum_tools::textCut($post_text,350,''));
				$word_array = explode(" ",$searchstring);

				// Cleaning empty elements
				foreach($word_array as $key => $value) {
					if($value == "") {
						unset($word_array[$key]);
					}
				}
				$word_array = array_values($word_array);

				// Highlight Text with each word
				foreach($word_array as $word) {
					$word   = str_replace('$','\$',$word);
					$replace = $this->cObj->wrap("\\0",$this->conf['matchWrap']);
					$post_text = preg_replace("/$word/i", $replace, $post_text);
				}

				$marker['###TITLE###']      = $this->pi_linkToPage($this->escape($topic_info['topic_title']),$conf['pid_forum'],'',$linkparams);
				$marker['###SHORTTEXT###']  = $post_text;
				$dummylinkParams['tx_mmforum_pi1'] = array('action'=>'list_post','tid'=>$topic_id);
                if(tx_mmforum_pi1::getIsRealURL()) $dummylinkParams['tx_mmforum_pi1']['fid'] = $topic_info['forum_id'];
				#$link = t3lib_div::getIndpEnv("HTTP_HOST").'/'.$this->pi_getPageLink($conf['pid_forum'],'',$dummylinkParams);
        $link = tx_mmforum_pi1::getAbsUrl($this->pi_getPageLink($conf['pid_forum'],'',$dummylinkParams));
				$link = $this->cObj->stdWrap($link,$conf['postPath.']);
				$marker['###POSTPATH###']   = $this->pi_linkToPage($link,$conf['pid_forum'],'_self',$linkparams);
				$marker['###VIEWS###']      = intval($topic_info['topic_views']);
				$marker['###ANSWERS###']    = intval($topic_info['topic_replies']);
				$marker['###CRDATE###']     = date("d.m.Y",$post_info['crdate']);
				$marker['###CRUSER###']     = tx_mmforum_pi4::get_username($post_info['poster_id']);

				if ($topic_info['solved'] == 1) {
					$marker['###SOLVED###']    = $this->pi_getLL('search.yes');
					$marker['###SOLVEDIMAGE###']    = '<img src="'.$conf['path_img'].$conf['images.']['solved'].'" title="'.$this->pi_getLL('search.topicSolved').'" alt="'.$this->pi_getLL('search.topicSolved').'" />';
				} else {
					$marker['###SOLVED###']    = $this->pi_getLL('search.no');
					$marker['###SOLVEDIMAGE###']    = '';
				}
				
				// Include hooks
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['search']['additionalPostMarkers'])) {
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum']['search']['additionalPostMarkers'] as $userFunction) {
						$params = array(
							'marker' => $marker,
							'topic_info' => $topic_info,
							'post_info' => $post_info,
							'post_text' => $post_text,
						);
						$marker = t3lib_div::callUserFunction($userFunction, $params, $this);
					}
				}

				$content .= $this->cObj->substituteMarkerArrayCached($template_sub, $marker);
			}
			$marker['###TREFFER###']    = $treffer;

			$marker['###PAGES###']     = $this->pagebar($treffer,$conf['show_items'],$param);

			$template   = $this->cObj->substituteMarkerArrayCached($template, $marker);
			$content    = $this->cObj->substituteSubpart($template,'###SEARCHRESULT_SUB###',$content);
		} else {
			$content = $this->pi_getLL('search.noResults');
		}
		return $content;
	}

	/**
	 * Displays the page navigation for the search result listing.
	 * @param int   $items The amount of search results listed
	 * @param int   $diff  The amount of search results to be listed on one page.
	 * @param array $param The parameters for the page navigation.
	 */
	function pagebar($items, $diff, $param) {
		$pages      =  ceil($items / $diff);
		$i = 1;
		IF(empty($param['page'])) $param['page'] = 1;

		$param['searchstring']    =   stripslashes($param['searchstring']);

		$linkparams[$this->prefixId] = array (
			'searchstring'   => $param['searchstring'],
			'page'           => ($param['page'] - 1),
			'search_place'   => $param['search_place'],
			'solved'         => $param['solved'],
			'groupPost'      => $param['groupPost']
		);

		if (intval($this->conf['doNotUsePageBrowseExtension'])===0) {
			unset($linkparams[$this->prefixId]['page']);
			return $this->getListGetPageBrowser($pages, $linkparams);
		}

		$content .= ' '.$this->pi_linkToPage(' &laquo; ',$GLOBALS["TSFE"]->id,$target='_self',$linkparams).' ';

		while ($i <= $pages) {

			IF($i > ($param['page']-4) AND $i < ($param['page']+4)) {
				$linkparams[$this->prefixId] = array (
					'searchstring'   => $param['searchstring'],
					'page'           => $i,
					'search_place'   => $param['search_place'],
					'solved'         => $param['solved'],
					'groupPost'      => $param['groupPost']
				);
				IF($param['page'] == $i)
					$content    .= ' '.$this->pi_linkToPage('<strong>['.$i.']</strong>',$GLOBALS["TSFE"]->id,$target='_self',$linkparams).' ';
				else
					$content    .= ' '.$this->pi_linkToPage($i,$GLOBALS["TSFE"]->id,$target='_self',$linkparams).' ';
			}
			$i++;
		}

		IF($param['page'] < $pages) {
			$linkparams[$this->prefixId] = array (
				'searchstring'   => $param['searchstring'],
				'page'           => ($param['page'] + 1),
				'search_place'   => $param['search_place'],
				'solved'         => $param['solved'],
				'groupPost'      => $param['groupPost']
			);
			$content .= ' '.$this->pi_linkToPage(' &raquo; ',$GLOBALS["TSFE"]->id,$target='_self',$linkparams).' ';
		}

		return $content;
	}

	/**
	 * Finds all posts and topics containing a certain word specified by
	 * the word UID.
	 * @param  array  $word_id_list An array of word UIDs that are to be searched
	 * @param  array  $param        The parameters for the search
	 * @param  int    $words        The amount of seach words (= count($word_id_list) )
	 * @param  string $username     Limits the posts searched to posts written by a
	 *                              certain user, specified by $username
	 * @return array                A numeric array of all posts, one of the words specified
	 *                              in $word_id_list was found in.
	 */
	function find_posts($word_id_list,$param,$words = 0,$username = '')
	{
		$word_id_list = implode(',',$word_id_list);
		IF(!empty($word_id_list)) {
			$mysql_option  .= ' AND word_id IN ('.$word_id_list.') ';
		}


		IF ($words > 0) {
			IF($param['groupPost']) {
				$mysql_group_option   .= ' topic_id HAVING count(post_id) >= '.$words.' ';
			} else {
				$mysql_group_option   .= ' post_id HAVING count(post_id) >= '.$words.' ';
			}
		}

		IF(is_numeric($param['search_place']) AND ($param['search_place'] > 0))
			$mysql_option   .= ' AND forum_id = '.mysql_escape_string($param['search_place']);

		IF($param['solved'] == 1)
			$mysql_option   .= ' AND solved = 1 ';

		IF(!empty($username)) {
			 $user_id = tx_mmforum_pi1::get_userid($username);
			 IF(is_numeric($user_id) AND $user_id != 0  ) {
					$mysql_option   .= ' AND post_cruser = '.$user_id;
			 }
		}

		if($GLOBALS['TSFE']->fe_user->user) {
			$userGroups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
			$grouprights_query = "";
			foreach($userGroups as $userGroup) {
				$fgrouprights_queries[] = ' FIND_IN_SET('.$userGroup.',reqUserGroups_f) ';
				$cgrouprights_queries[] = ' FIND_IN_SET('.$userGroup.',reqUserGroups_c) ';
			}
			$mysql_option .= " AND (".implode(' OR ',$fgrouprights_queries)." OR reqUserGroups_f='')";
			$mysql_option .= " AND (".implode(' OR ',$cgrouprights_queries)." OR reqUserGroups_c='')";
		}
		else $mysql_option .= ' AND reqUserGroups_f="" AND reqUserGroups_c="" ';

		switch($param['search_order']) {
			default:
				$order_option = 'post_crdate DESC';
			break;
			case "1":
				$order_option = 'post_crdate ASC';
			break;
			case "2":
				$order_option = 'post_crdate DESC';
			break;
			case "3":
				$order_option = 'topic_replies DESC';
			break;
			case "4":
				$order_option = 'topic_replies ASC';
			break;
		}

		IF ($words > 0) {
			$count_option = ', count(post_id) as treffer';
		} else {
			$count_option = '';
		}

		IF(!empty($mysql_option)) {
			// SELECT:
			$query = $GLOBALS['TYPO3_DB']->SELECTquery(
				'post_id, topic_id'.$count_option,
				'tx_mmforum_wordmatch',
				'1=1 '.$mysql_option.$this->getPidQuery(),
				$mysql_group_option ,
				$order_option,
				''
			);
			if($this->conf['debug_mode']) echo $query;

			$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, $query);
			$post_id_array = array();            // Array in denen die gefundenen Posts gespeichert werden
		}

		if(isset($res)) {
			$topic_tmp_array    = array();

			while($row  = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				array_push($post_id_array,$row['post_id']);
			}
		}

		return $post_id_array;
	}

	/**
	 * Loads a post text.
	 * @param  int    $post_id The post's UID
	 * @return string          The post's text
	 */
	function get_posttext($post_id) {
		return tx_mmforum_indexing::get_posttext($post_id);
	}

	/**
	 * Generates an array of search words out of a search string.
	 * Strips the search words of invalid special chars and checks if
	 * they are long enough.
	 * @param  array  $conf   The plugin's configuration vars
	 * @param  string $string The search string
	 * @return array          An array of search words
	 */
	function wordArray ($conf,$string) {
		return tx_mmforum_indexing::wordArray($conf,$string);
	}

	/**
	 * Determines which word UID stored in database match a certain word.
	 * @param  string $word The word to be matched
	 * @return array        An numeric array of word UIDs
	 */
	function word_id($word) {
		$word = mysql_escape_string($word);
		$word = str_replace('*','%',$word);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','tx_mmforum_wordlist',"word LIKE '$word'".$this->getPidQuery());

		$word_id_array = array();
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
			while (list($uid) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
				array_push($word_id_array,$uid);
			}
		}
		return $word_id_array;
	}

	/**
	 * Clears a string of all BBCodes
	 * @param  string $string The string to be cleared
	 * @return string         The cleared string
	 */
	function clear_phpBB($string) {
		return tx_mmforum_indexing::clear_phpBB($string);
	}

	/**
	 * Outputs statistical information about the search index.
	 * Output a line containing information about the amount of indexed word, word relations and
	 * indexed topics.
	 * @return string  A line containing information about the amount of indexed word, word relations and
	 *                 indexed topics.
	 */
	function search_stat () {
		list($words) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('COUNT(uid)','tx_mmforum_wordlist','1'.$this->getPidQuery()));
		list($match) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('COUNT(uid)','tx_mmforum_wordmatch','1'.$this->getPidQuery()));
		list($topic) = $GLOBALS['TYPO3_DB']->sql_fetch_row($GLOBALS['TYPO3_DB']->exec_SELECTquery('COUNT(uid)','tx_mmforum_topics','tx_mmforumsearch_index_write>0'.$this->getPidQuery()));

		$llMarker = array('words'=>$words,'matches'=>$match,'topics'=>$topic);
		$content = $this->cObj->substituteMarkerArray($this->pi_getLL('stats'),$llMarker,'###|###',1);

		return $content;
	}

	/**
	 * Returns all board UIDs of a certain category that are public (i.e. not internal)
	 * @param  int   $cat_id The category UID
	 * @return array         A numeric array of board UIDs
	 */
	function list_forum($cat_id) {
		$forum_array    = array();

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid,forum_name',
			'tx_mmforum_forums',
			'deleted="0" AND hidden="0" AND forum_internal="0" AND parentID="'.$cat_id.'"'.$this->getPidQuery().$this->getMayRead_forum_query()
		);

		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$tmp_array['uid']     = $row['uid'];
			$tmp_array['name']    = $row['forum_name'];
			$tmp_array['type']    = 'F';
			array_push($forum_array,$tmp_array);
		}

		return $forum_array;
	}

	/**
	 * Returns all UIDs of public categories.
	 * @return array  A numeric array of category UIDs
	 */
	function list_cat() {
		$cat_array    = array();

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid,forum_name as cat_title',
            'tx_mmforum_forums',
            'deleted="0" AND hidden="0" AND parentID="0" AND forum_internal="0"'.$this->getPidQuery().$this->getMayRead_forum_query(),
            '',
            'sorting ASC'
        );

		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$tmp_array['uid']     = $row['uid'];
			$tmp_array['name']    = $row['cat_title'];
			$tmp_array['type']    = 'C';
			array_push($cat_array,$tmp_array);
		}

		return $cat_array;
	}

	/**
	 * Lists all categories and boards in a single array. The boards are grouped
	 * by category.
	 * @return array  A numeric array containing all category and board UIDs
	 */
	function list_cat_tree() {
		$cat_tree   = array();
		foreach($this->list_cat() as $val) {
			array_push($cat_tree,$val);
			$forum_array    = $this->list_forum($val['uid']);
			foreach($forum_array as $subval) {
				array_push($cat_tree,$subval);
			}
		}
		return $cat_tree;
	}

	/**
	 * Loads the topics matching to a certain search string from the result table.
	 * @param  string $searchstring The search string
	 * @param  array  $param        The parameter array
	 * @return array                The result record
	 */
	function get_search_results($searchstring,$param) {
		$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_mmforum_searchresults','tstamp < '.(time() - (3600 * 2)));

		$searchstring = str_replace('*','\*',$searchstring);

		IF(!is_numeric($param['search_place'])) {
			$param['search_place'] = 0;
		}

		IF($param['solved'] == 1) {
			$solved = 1;
		} else {
			$solved = 0;
		}

		IF($param['groupPost'] == 1) {
			$groupPost = 1;
		} else {
			$groupPost = 0;
		}

		IF($param['search_order'] > 0) {
			$search_order = $param['search_order'];
		} else {
			$search_order = 0;
		}

		$grouprights_query = " AND user_groups=''";
		if($GLOBALS['TSFE']->fe_user->user) {
			$userGroups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
			sort($userGroups);
			$grouprights_query = " AND (user_groups = '".implode(',',$userGroups)."')";
			#$grouprights_query = "";
			#foreach($userGroups as $userGroup) {
			#	$grouprights_queries[] = ' FIND_IN_SET('.$userGroup.',user_groups) ';
			#}
			#$grouprights_query = " AND (".implode(' AND ',$grouprights_queries).")";
		}
		$query = $GLOBALS['TYPO3_DB']->SELECTquery(
			'*',
			'tx_mmforum_searchresults',
			'search_string LIKE "'.$searchstring.'" AND search_place = '.$param['search_place'].' AND solved = '.$solved.' AND search_order = '.$search_order.' AND groupPost = '.$groupPost.$this->getPidQuery().$grouprights_query,
			'',
			'',
			'1'
		);

		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, $query);
		$row  = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

		$search_array = unserialize($row['array_string']);

		return $search_array;

	}

	/**
	 * Returns information about a certain topic.
	 * @param  int   $topic_id The topic UID
	 * @return array           The topic record as array
	 */
	function topic_information($topic_id) {
		return tx_mmforum_indexing::topic_information($topic_id);
	}

	/**
	 * Returns information about a certain post.
	 * @param  int   $post_id The post UID
	 * @return array          The post record as array
	 */
	function post_information($post_id) {
		return tx_mmforum_indexing::post_information($post_id);
	}

	/**
	 * Determines a username from the user UID
	 * @param  int    $user_id The user UID
	 * @return string          The username
	 */
	function get_username($user_id) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('username','fe_users',"uid='$user_id'");
		if(is_resource($res)){
			list($username) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		}
		return $username;

	}

    /**
    * Check which language is set up in the TypoScript
    * @return string        The folder where language dependend images are stored
    */

    function getLanguage(){
        if($GLOBALS['TSFE']->config['config']['language'] == 'en'){
            $language = 'default/';
        }
        else{
            $language = $GLOBALS['TSFE']->config['config']['language'].'/';
        }
        return $language;
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
	function getPidQuery($conf=null, $tables="") {
		return tx_mmforum_indexing::getPidQuery($conf?$conf:$this->conf,$tables);
	}


    /**
	 * Generates a MySQL-query to determine in which boards the current user may read.
	 * @return string  A MySQL-WHERE-query, beginning with "AND", checking which boards the
	 *                 user that is currently logged in may read in.
	 * @author Martin Helmich <m.helmich@mittwald.de>
	 */
	function getMayRead_forum_query($prefix="") {
		if(strlen($prefix)>0) $prefix = "$prefix.";
		if(!$GLOBALS['TSFE']->fe_user->user) return " AND (".$prefix."grouprights_read='')";

		$groups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
		$groups = tx_mmforum_tools::processArray_numeric($groups);
		foreach($groups as $group) {
			$queryParts[] = "FIND_IN_SET($group,".$prefix."grouprights_read)";
		}
		$query = implode(' OR ',$queryParts);
		$query = " AND (($query) OR ".$prefix."grouprights_read='') ";

		return $query;
	}


}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi4/class.tx_mmforum_pi4.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/pi4/class.tx_mmforum_pi4.php"]);
}

?>
