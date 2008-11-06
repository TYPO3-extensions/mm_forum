<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

/*
 * Extend fe_user table
 */

$tempColumns = Array (
	"tx_mmforum_avatar" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_avatar",
		"config" => Array (
			"type" => "group",
			"internal_type" => "file",
			"allowed" => "gif,png,jpeg,jpg",	
			"max_size" => 300,	
			"uploadfolder" => "uploads/tx_mmforum",
			"show_thumbs" => 1,	
			"size" => 1,	
			"minitems" => 0,
			"maxitems" => 1,
		)
	),
	"tx_mmforum_icq" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_icq",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",
		)
	),
	"tx_mmforum_aim" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_aim",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",
		)
	),
	"tx_mmforum_yim" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_yim",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",
		)
	),
	"tx_mmforum_msn" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_msn",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",
		)
	),
    "tx_mmforum_skype" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_skype",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",
		)
	),
	"tx_mmforum_md5" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_md5",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",
		)
	),
	"tx_mmforum_posts" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_posts",		
		"config" => Array (
			"type" => "input",	
			"size" => "6",
			"eval" => "int",
			"checkbox" => "0",
			"range" => Array (
				"lower" => "0"
			),
			"default" => 0
		)
	),
	"tx_mmforum_user_sig" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_user_sig",		
		"config" => Array (
			"type" => "text",
			"cols" => "30",
			"rows" => "5",
			"wizards" => Array(
				"_PADDING" => 2,
				"RTE" => Array(
					"notNewRecords" => 1,
					"RTEonly" => 1,
					"type" => "script",
					"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
					"icon" => "wizard_rte2.gif",
					"script" => "wizard_rte.php",
				),
			),
		)
	),
	"tx_mmforum_prelogin" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_prelogin",		
		"config" => Array (
			"type" => "input",	
			"size" => "12",
			"max" => "20",
			"eval" => "datetime",
			"checkbox" => "0",
			"default" => 0
		)
	),
    "tx_mmforum_interests" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_interests",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",
		)
	),
	"tx_mmforum_occ" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_occ",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",
		)
	),
	"tx_mmforum_reg_hash" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.php:fe_users.tx_mmforum_reg_hash",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",
		)
	),
	'tx_mmforum_pmnotifymode'		=> array(
		'exclude'						=> 1,
		'label'							=> 'LLL:EXT:mm_forum/locallang_db.xml:fe_users.tx_mmforum_pmnotifymode',
		'config'						=> array(
			'type'							=> 'select',
			'items'							=> array(
				array('LLL:EXT:mm_forum/locallang_db.xml:fe_users.tx_mmforum_pmnotifymode.0', 0),
				array('LLL:EXT:mm_forum/locallang_db.xml:fe_users.tx_mmforum_pmnotifymode.1', 1),
				array('LLL:EXT:mm_forum/locallang_db.xml:fe_users.tx_mmforum_pmnotifymode.2', 2),
			),
		),
	)
);


t3lib_div::loadTCA("fe_users");
t3lib_extMgm::addTCAcolumns("fe_users",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("fe_users","tx_mmforum_avatar;;;;1-1-1, tx_mmforum_icq, tx_mmforum_aim, tx_mmforum_yim, tx_mmforum_msn, tx_mmforum_skype, tx_mmforum_md5, tx_mmforum_posts, tx_mmforum_user_sig;;;richtext[paste|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], tx_mmforum_prelogin, tx_mmforum_interests, tx_mmforum_occ, tx_mmforum_reg_hash");

/*
 * Setup for table 'fe_groups'
 */
 
$tempColumns = Array (
	"tx_mmforum_rank" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mm_forum/locallang_db.xml:fe_groups.tx_mmforum_rank",
		"config" => Array (
			"type" => "group",
			"internal_type" => "db",
			"allowed" => "tx_mmforum_ranks",
			"max_size" => 1,
			"size" => 2,
			"minitems" => 0,
			"maxitems" => 1,
		)
	),
    'tx_mmforum_rank_excl'      => array(
        'exclude'                   => 1,
        'label'                     => 'LLL:EXT:mm_forum/locallang_db.xml:fe_groups.tx_mmforum_rank_excl',
        'config'                    => array(
            'type'                      => 'check'
        ),
    ),
);


t3lib_div::loadTCA("fe_groups");
t3lib_extMgm::addTCAcolumns("fe_groups",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("fe_groups","tx_mmforum_rank, tx_mmforum_rank_excl");

/*
 * Setup for table 'tx_mmforum_forums'
 */

$TCA["tx_mmforum_forums"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:mm_forum/locallang_db.php:tx_mmforum_forums",		
		"label" => "forum_name",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",
        'dividers2tabs' => true,
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_forums.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, forum_name, forum_desc, forum_posts, forum_last_post_id, cat_id, forum_order, forum_topics, forum_posts_568ce612f3, forum_last_post_id_8724a146ef, forum_internal",
	)
);

/*
 * Setup for table 'tx_mmforum_topics'
 */

$TCA["tx_mmforum_topics"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:mm_forum/locallang_db.php:tx_mmforum_topics",		
		"label" => "topic_title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_topics.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, topic_title, topic_poster, topic_time, topic_views, topic_replies, topic_last_post_id, forum_id, topic_first_post_id, poll_id, shadow_tid, shadow_fid, tx_mmforumsearch_index_write",
	)
);

/*
 * Setup for table 'tx_mmforum_posts'
 */

$TCA["tx_mmforum_posts"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:mm_forum/locallang_db.php:tx_mmforum_posts",		
		"label" => "uid",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_posts.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, topic_id, forum_id, poster_id, post_time, poster_ip, edit_time, edit_count, attachment",
	)
);

/*
 * Setup for table 'tx_mmforum_posts_text'
 */

$TCA["tx_mmforum_posts_text"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:mm_forum/locallang_db.php:tx_mmforum_posts_text",		
		"label" => "uid",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_posts_text.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, post_text",
	)
);

/*
 * Setup for table 'tx_mmforum_smilies'
 */

$TCA["tx_mmforum_smilies"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:mm_forum/locallang_db.php:tx_mmforum_smilies",		
		"label" => "code",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_smilies.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, code, smile_url, emoticon",
	)
);

/*
 * Setup for table 'tx_mmforum_mailkey'
 */

$TCA["tx_mmforum_mailkey"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:mm_forum/locallang_db.php:tx_mmforum_mailkey",		
		"label" => "uid",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY crdate",	
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_mailkey.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "code",
	)
);

/*
 * Setup for table 'tx_mmforum_postsread'
 */

$TCA["tx_mmforum_postsread"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:mm_forum/locallang_db.php:tx_mmforum_postsread",		
		"label" => "uid",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_postsread.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, user, topic_id, post_id",
	)
);

/*
 * Setup for table 'tx_mmforum_topicmail'
 */

$TCA["tx_mmforum_topicmail"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:mm_forum/locallang_db.php:tx_mmforum_topicmail",		
		"label" => "uid",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_topicmail.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, user_id, topic_id",
	)
);

/*
 * Setup for table 'tx_mmforum_favorites'
 */

$TCA["tx_mmforum_favorites"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:mm_forum/locallang_db.php:tx_mmforum_favorites",		
		"label" => "uid",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_favorites.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, user_id, topic_id",
	)
);

/*
 * Setup for table 'tx_mmforum_pminbox'
 */

$TCA["tx_mmforum_pminbox"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox",		
		"label" => "subject",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_pm.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, sendtime, from_uid, from_name, to_uid, to_name, subject, message, read_flg",
	)
);

/*
 * Setup for table 'tx_mmforum_wordlist'
 */

$TCA["tx_mmforum_wordlist"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordlist',
		'label' => 'word',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_wordlist.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, word, metaphone",
	)
);

/*
 * Setup for table 'tx_mmforum_wordmatch'
 */

$TCA["tx_mmforum_wordmatch"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordmatch',		
		'label' => 'uid',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_wordmatch.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, word_id, post_id, is_header, topic_id, forum_id, solved, topic_title, topic_views, topic_replies",
	)
);

/*
 * Setup for table 'tx_mmforum_searchresults'
 */

$TCA["tx_mmforum_searchresults"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults',
		'label' => 'uid',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		"default_sortby" => "ORDER BY crdate",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_searchresults.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, search_string, array_string",
	)
);

/*
 * Setup for table 'tx_mmforum_userfields'
 */

$TCA["tx_mmforum_userfields"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_userfields',
		'label' => 'label',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		"default_sortby" => "ORDER BY sorting DESC",	
		"delete" => "deleted",
        'dividers2tabs' => true,	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_mmforum_searchresults.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, label, config",
	)
);

/*
 * Setup for table 'tx_mmforum_attachments'
 */
$TCA['tx_mmforum_attachments'] = array(
    'ctrl' => array(
        'title'             => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_attachments',
        'label'             => 'file_name',
        'tstamp'            => 'tstamp',
        'crdate'            => 'crdate',
        'cruser_id'         => 'cruser_id',
        'default_sortby'    => 'ORDER BY crdate',
        'delete'            => 'deleted',
        'enablecolumns'     => array(
            'disabled'          => 'hidden'
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
        'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_mmforum_attachments.gif'
    ),          
    'feInterface' => array(
        'fe_admin_fieldList' => 'hidden, file_type, file_name, file_path, file_size, downloads, post_id'
    )
);

/*
 * Setup for table 'tx_mmforum_polls'
 */
$TCA['tx_mmforum_polls'] = array(
    'ctrl' => array(
        'title'             => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls',
        'label'             => 'question',
        'tstamp'            => 'tstamp',
        'crdate'            => 'crdate',
        'cruser_id'         => 'cruser_id',
        'default_sortby'    => 'ORDER BY crdate',
        'delete'            => 'deleted',
        'enablecolumns'     => array(
            'disabled'          => 'hidden'
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
        'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_mmforum_polls.gif'
    ),          
    'feInterface' => array(
        'fe_admin_fieldList' => 'hidden, question, endtime, votes'
    )
);

/*
 * Setup for table 'tx_mmforum_polls_answers'
 */
$TCA['tx_mmforum_polls_answers'] = array(
    'ctrl' => array(
        'title'             => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls_answers',
        'label'             => 'answer',
        'tstamp'            => 'tstamp',
        'crdate'            => 'crdate',
        'cruser_id'         => 'cruser_id',
        'default_sortby'    => 'ORDER BY crdate',
        'delete'            => 'deleted',
        'enablecolumns'     => array(
            'disabled'          => 'hidden'
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
        'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_mmforum_polls_answers.gif'
    ),          
    'feInterface' => array(
        'fe_admin_fieldList' => 'hidden, answer, poll_id, votes'
    )
);

/*
 * Setup for table 'tx_mmforum_polls_votes'
 */
$TCA['tx_mmforum_polls_votes'] = array(
    'ctrl' => array(
        'title'             => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls_votes',
        'label'             => 'uid',
        'tstamp'            => 'tstamp',
        'crdate'            => 'crdate',
        'cruser_id'         => 'cruser_id',
        'default_sortby'    => 'ORDER BY crdate',
        'delete'            => 'deleted',
        'enablecolumns'     => array(
            'disabled'          => 'hidden'
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
        'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_mmforum_polls_votes.gif'
    ),          
    'feInterface' => array(
        'fe_admin_fieldList' => 'hidden, poll_id, answer_id, user_id'
    )
);

/*
 * Setup for table 'tx_mmforum_ranks'
 */
$TCA['tx_mmforum_ranks'] = array(
    'ctrl' => array(
        'title'             => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_ranks',
        'label'             => 'title',
        'tstamp'            => 'tstamp',
        'crdate'            => 'crdate',
        'cruser_id'         => 'cruser_id',
        'default_sortby'    => 'ORDER BY special DESC, minPosts',
        'delete'            => 'deleted',
        'enablecolumns'     => array(
            'disabled'          => 'hidden'
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
        'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_mmforum_ranks.gif'
    ),          
    'feInterface' => array(
        'fe_admin_fieldList' => 'hidden, icon, title, color, minPosts, special'
    )
);

/*
 * Setup for table 'tx_mmforum_postqueue'
 */
$TCA['tx_mmforum_postqueue'] = array(
    'ctrl' => array(
        'title'             => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue',
        'label'             => 'post_text',
        'tstamp'            => 'tstamp',
        'crdate'            => 'crdate',
        'cruser_id'         => 'cruser_id',
        'default_sortby'    => 'ORDER BY post_time DESC',
        'delete'            => 'deleted',
        'enablecolumns'     => array(
            'disabled'          => 'hidden'
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
        'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_mmforum_postqueue.gif'
    ),          
    'feInterface' => array(
        'fe_admin_fieldList' => 'hidden, topic, topic_forum, topic_title, topic_poll, topic_subscribe, post_parent, post_text, post_user, post_time, post_attachment, post_ip'
    )
);


 
/*
 * Include plugin 'mm_forum :: Forum'
 */
t3lib_div::loadTCA("tt_content");
$TCA["tt_content"]["types"]["list"]["subtypes_excludelist"][$_EXTKEY."_pi1"]="layout,select_key,pages";
t3lib_extMgm::addPlugin(Array("LLL:EXT:mm_forum/locallang_db.php:tt_content.list_type_pi1", $_EXTKEY."_pi1"),"list_type");
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:mm_forum/flexform_ds_pi1.xml'); 

if (TYPO3_MODE=="BE")	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_mmforum_pi1_wizicon"] = t3lib_extMgm::extPath($_EXTKEY)."pi1/class.tx_mmforum_pi1_wizicon.php";

/*
 * Include plugin 'mm_forum :: User registration'
 */
t3lib_div::loadTCA("tt_content");
$TCA["tt_content"]["types"]["list"]["subtypes_excludelist"][$_EXTKEY."_pi2"]="layout,select_key,pages";
t3lib_extMgm::addPlugin(Array("LLL:EXT:mm_forum/locallang_db.php:tt_content.list_type_pi2", $_EXTKEY."_pi2"),"list_type");

/*
 * Include plugin 'mm_forum :: Private messaging'
 */
t3lib_div::loadTCA("tt_content");
$TCA["tt_content"]["types"]["list"]["subtypes_excludelist"][$_EXTKEY."_pi3"]="layout, select_key, pages";
t3lib_extMgm::addPlugin(Array("LLL:EXT:mm_forum/locallang_db.php:tt_content.list_type_pi3", $_EXTKEY."_pi3"),"list_type");

/*
 * Include plugin 'mm_forum :: Search'
 */
t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi4']='layout,select_key,pages';
t3lib_extMgm::addPlugin(Array('LLL:EXT:mm_forum/locallang_db.xml:tt_content.list_type_pi4', $_EXTKEY.'_pi4'),'list_type');

/*
 * Include plugin 'mm_forum :: User settings'
 */
t3lib_div::loadTCA("tt_content");
$TCA["tt_content"]["types"]["list"]["subtypes_excludelist"][$_EXTKEY."_pi5"]="layout,select_key,pages";
t3lib_extMgm::addPlugin(Array("LLL:EXT:mm_forum/locallang_db.php:tt_content.list_type_pi5", $_EXTKEY."_pi5"),"list_type");

/*
 * Include plugin 'mm_forum :: Portal information'
 */
t3lib_div::loadTCA("tt_content");
$TCA["tt_content"]["types"]["list"]["subtypes_excludelist"][$_EXTKEY."_pi6"]="layout,select_key,pages";
t3lib_extMgm::addPlugin(Array("LLL:EXT:mm_forum/locallang_db.php:tt_content.list_type_pi6", $_EXTKEY."_pi6"),"list_type");

/*
 * Include backend module
 */
if (TYPO3_MODE=="BE")	{
	t3lib_extMgm::addModule("web","txmmforumM1","",t3lib_extMgm::extPath($_EXTKEY)."mod1/");
}
?>