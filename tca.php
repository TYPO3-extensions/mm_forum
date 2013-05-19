<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

/*
 * Table setup for table 'tx_mmforum_forums'
 */
$TCA['tx_mmforum_forums'] = array(
	'ctrl'          => $TCA['tx_mmforum_forums']['ctrl'],
	'interface'     => array(
		'showRecordFieldList' => 'hidden,forum_name,forum_desc,parentID,forum_last_post_id,forum_topics,forum_posts,grouprights_read,grouprights_write,grouprights_mod'
	),
	'feInterface'   => $TCA['tx_mmforum_forums']['feInterface'],
	'columns'       => array(
		'hidden'        => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'        => array(
				'type'          => 'check',
				'default'       => '0'
			)
		),
		'forum_name'    => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_name',
			'config'        => array(
				'type'          => 'input',
				'size'          => '30',
			)
		),
		'forum_desc'    => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_desc',
			'config'        => array(
				'type'          => 'input',
				'size'          => '30',
			)
		),
		'forum_posts'   => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_posts',
			'config'        => array(
				'type'          => 'input',
				'size'          => '8',
				'eval'          => 'int',
				'checkbox'      => '0',
				'range'         => array(
					'lower'         => '0'
				),
				'default'       => 0
			)
		),
		'forum_last_post_id' => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_last_post_id',
			'config'        => array(
				'type'          => 'group',
				'internal_type' => 'db',
				'allowed'       => 'tx_mmforum_posts',
				'size'          => 1,
				'minitems'      => 0,
				'maxitems'      => 1
			),
		),
		'forum_order'   => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_order',
			'config'        => array(
				'type'          => 'input',
				'size'          => '4',
				'max'           => '4',
				'eval'          => 'int',
				'checkbox'      => '0',
				'default'       => '0'
			)
		),
		'forum_topics'  => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_topics',
			'config'        => array(
				'type'          => 'input',
				'size'          => '8',
				'eval'          => 'int',
				'checkbox'      => '0',
				'range'         => array(
					'lower'         => '0'
				),
				'default'       => '0'
			)
		),
		'grouprights_read' => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_grouprights_read',
			'config'        => array(
				'type'          => 'select',
				'foreign_table' => 'fe_groups',
				'foreign_table_where' => '',
				'size'          => 4,
				'minitems'      => 0,
				'maxitems'      => 100,

			)
		),
        'grouprights_write' => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_grouprights_write',
			'config'        => array(
				'type'          => 'select',
				'foreign_table' => 'fe_groups',
				'foreign_table_where' => '',
				'size'          => 4,
				'minitems'      => 0,
				'maxitems'      => 100,

			)
		),
        'grouprights_mod' => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_grouprights_mod',
			'config'        => array(
				'type'          => 'select',
				'foreign_table' => 'fe_groups',
				'foreign_table_where' => '',
				'size'          => 4,
				'minitems'      => 0,
				'maxitems'      => 100,

			)
		),
       'userrights_read' => array(
            'exclude'       => 1,
            'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_userrights_read',
            'config'        => array(
                'type'          => 'group',
                'internal_type' => 'db',
                'allowed'       => 'fe_users',
                'size'          => 10,
                'minitems'      => 0,
                'maxitems'      => 100,
            )
        ),
        'userrights_write' => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_userrights_write',
			'config'        => array(
                'type'          => 'group',
                'internal_type' => 'db',
                'allowed'       => 'fe_users',
                'size'          => 10,
                'minitems'      => 0,
                'maxitems'      => 100,
            )
		),
        'userrights_mod' => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_userrights_mod',
			'config'        => array(
                'type'          => 'group',
                'internal_type' => 'db',
                'allowed'       => 'fe_users',
                'size'          => 10,
                'minitems'      => 0,
                'maxitems'      => 100,
            )
		),
		'parentID'      => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.parentID',
			'config'        => array(
				'type'          => 'select',
				'items' => array(
					array('LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.parentID.noParent', ''),
					array('', '--div--'),
				),
				'foreign_table'       => 'tx_mmforum_forums',
				'foreign_table_where' => 'AND tx_mmforum_forums.parentID = 0',
				'size'                => 1,
				'maxitems'            => 1
			),
		),
		'forum_internal' => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.forum_internal',
			'config'        => array(
				'type'          => 'check'
			),
		),
	),
	'types' => array(
		'0' => array('showitem' => '--div--;LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.tabs.general, forum_name;;;;1-1-1, hidden, forum_desc, parentID, forum_last_post_id, forum_topics, forum_posts, --div--;LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_forums.tabs.rights, grouprights_read, grouprights_write, grouprights_mod')
	)
);


/*
 * Table setup for table 'tx_mmforum_topics'
 */
$TCA['tx_mmforum_topics'] = array(
	'ctrl'          => $TCA['tx_mmforum_topics']['ctrl'],
	'interface'     => array(
		'showRecordFieldList' => 'hidden,topic_title,topic_poster,forum_id,topic_time,topic_views,topic_replies,topic_is,solved,at_top_flag,closed_flag,topic_first_post_id,topic_last_post_id,poll_id,shadow_tid,shadow_fid,tx_mmforumsearch_index_write'
	),
	'feInterface'   => $TCA['tx_mmforum_topics']['feInterface'],
	'columns'       => array(
		'hidden'        => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config'        => array(
				'type'          => 'check',
				'default'       => '0'
			)
		),
		'topic_title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.topic_title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'topic_poster' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.topic_poster',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_users',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'topic_time' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.topic_time',
			'config' => array(
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'datetime',
				'checkbox' => '0',
				'default' => '0'
			)
		),
		'topic_views' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.topic_views',
			'config' => array(
				'type' => 'input',
				'size' => '6',
				'eval' => 'int',
				'range' => array(
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'topic_replies' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.topic_replies',
			'config' => array(
				'type' => 'input',
				'size' => '6',
				'eval' => 'int',
				'range' => array(
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'topic_last_post_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.topic_last_post_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_posts',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'forum_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.forum_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_forums',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'topic_first_post_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.topic_first_post_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_posts',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'topic_is' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.topic_is',
			'config' => array(
				'type' => 'input',
				'max' => 255,
				'checkbox' => 0
			),
		),
		'solved' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.solved',
			'config' => array(
				'type' => 'check'
			),
		),
		'at_top_flag' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.at_top_flag',
			'config' => array(
				'type' => 'check'
			),
		),
		'closed_flag' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.closed_flag',
			'config' => array(
				'type' => 'check'
			),
		),
        'poll_id'       => array(
            'exclude'       => 1,
            'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.poll_id',
            'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_polls',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			),
        ),
        'shadow_tid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.shadow_tid',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_topics',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
        'shadow_fid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.shadow_fid',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_forums',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'tx_mmforumsearch_index_write' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.tx_mmforumsearch_index_write',
			'config' => array(
				'type' => 'input',
				'size' => 12,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0
			),
		),
	),
	'types' => array(
		'0' => array('showitem' => '--div--;LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.tabs.general,topic_title, hidden, topic_poster, topic_time, forum_id;;;;1-1-1, topic_first_post_id, topic_last_post_id, --div--;LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topics.tabs.advanced, topic_views, topic_replies, topic_is, solved;;;;1-1-1, at_top_flag, closed_flag, poll_id;;;;-1-1-1, shadow_tid, shadow_fid, tx_mmforumsearch_index_write')
	)
);

/*
 * Table setup for table 'tx_mmforum_posts'
 */
$TCA['tx_mmforum_posts'] = array(
	'ctrl' => $TCA['tx_mmforum_posts']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,topic_id,forum_id,poster_id,post_time,poster_ip,edit_time,edit_count,attachment,tx_mmforumsearch_index_write'
	),
	'feInterface' => $TCA['tx_mmforum_posts']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'topic_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_posts.topic_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_topics',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'forum_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_posts.forum_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_forums',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'poster_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_posts.poster_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_users',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'post_time' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_posts.post_time',
			'config' => array(
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'datetime',
				'default' => '0'
			)
		),
		'poster_ip' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_posts.poster_ip',
			'config' => array(
				'type' => 'input',
				'size' => '20',
			)
		),
		'edit_time' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_posts.edit_time',
			'config' => array(
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'datetime',
				'checkbox' => '0',
				'default' => '0'
			)
		),
		'edit_count' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_posts.edit_count',
			'config' => array(
				'type' => 'input',
				'size' => '4',
				'max' => '4',
				'eval' => 'int',
				'checkbox' => '0',
				'range' => array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
        'attachment'    => array(
            'exclude'       => 1,
            'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_posts.attachment',
            'config'        => array(
                'type'          => 'group',
				'internal_type' => 'db',
				'allowed'       => 'tx_mmforum_attachments',
				'size'          => 1,
				'minitems'      => 0,
				'maxitems'      => 1
            )
        ),
		'tx_mmforumsearch_index_write' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_posts.tx_mmforumsearch_index_write',
			'config' => array(
				'type' => 'input',
				'size' => 12,
				'max' => 20,
				'eval' => 'datetime',
				'default' => 0
			),
		),
	),
	'types' => array(
		'0' => array('showitem' => 'topic_id, hidden, poster_id, forum_id, post_time, poster_ip;;;;1-1-1-1, edit_time, edit_count, attachment, tx_mmforumsearch_index_write')
	)
);

/*
 * Table setup for table 'tx_mmforum_posts_text'
 */
$TCA['tx_mmforum_posts_text'] = array(
	'ctrl' => $TCA['tx_mmforum_posts_text']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,post_id,post_text'
	),
	'feInterface' => $TCA['tx_mmforum_posts_text']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'post_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_posts_text.post_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_posts',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'post_text' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_posts_text.post_text',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
				'wizards' => array(
					'_PADDING' => 2,
					'RTE' => array(
						'notNewRecords' => 1,
						'RTEonly' => 1,
						'type' => 'script',
						'title' => 'Full screen Rich Text Editing|Formatteret redigering i hele vinduet',
						'icon' => 'wizard_rte2.gif',
						'script' => 'wizard_rte.php',
					),
				),
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, post_id,post_text;;;richtext[paste|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts]')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_smilies'
 */
$TCA['tx_mmforum_smilies'] = array(
	'ctrl' => $TCA['tx_mmforum_smilies']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,code,smile_url,emoticon'
	),
	'feInterface' => $TCA['tx_mmforum_smilies']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'code' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_smilies.code',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'smile_url' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_smilies.smile_url',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'emoticon' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_smilies.emoticon',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, code, smile_url, emoticon')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_mailkey'
 */
$TCA['tx_mmforum_mailkey'] = array(
	'ctrl' => $TCA['tx_mmforum_mailkey']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'code'
	),
	'feInterface' => $TCA['tx_mmforum_mailkey']['feInterface'],
	'columns' => array(
		'code' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_mailkey.code',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'code;;;;1-1-1')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_postsread'
 */
$TCA['tx_mmforum_postsread'] = array(
	'ctrl' => $TCA['tx_mmforum_postsread']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,user,topic_id'
	),
	'feInterface' => $TCA['tx_mmforum_postsread']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'user' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postsread.user',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_users',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'topic_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postsread.topic_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_topics',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		)
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, user, topic_id')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_topicmail'
 */
$TCA['tx_mmforum_topicmail'] = array(
	'ctrl' => $TCA['tx_mmforum_topicmail']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,user_id,topic_id'
	),
	'feInterface' => $TCA['tx_mmforum_topicmail']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'user_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topicmail.user_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_users',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'topic_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_topicmail.topic_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_topics',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, user_id, topic_id')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_favorites'
 */
$TCA['tx_mmforum_favorites'] = array(
	'ctrl' => $TCA['tx_mmforum_favorites']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,user_id,topic_id'
	),
	'feInterface' => $TCA['tx_mmforum_favorites']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'user_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_favorites.user_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_users',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'topic_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_favorites.topic_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_topics',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, user_id, topic_id')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_pminbox'
 */
$TCA['tx_mmforum_pminbox'] = array(
	'ctrl' => $TCA['tx_mmforum_pminbox']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,sendtime,from_uid,from_name,to_uid,to_name,subject,message,read_flg,mess_type'
	),
	'feInterface' => $TCA['tx_mmforum_pminbox']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'sendtime' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.sendtime',
			'config' => array(
				'type' => 'input',
				'size' => 12,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0
			)
		),
		'from_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.from_uid',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_users',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'from_name' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.from_name',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'to_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.to_uid',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_users',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'to_name' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.to_name',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'subject' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.subject',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'message' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.message',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'read_flg' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.read_flg',
			'config' => array(
				'type' => 'check'
			)
		),
		'mess_type' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.mess_type',
			'config' => array(
				'type' => 'radio',
				'items' => array(
					array('LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.mess_type.inbox',0),
					array('LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.mess_type.outbox',1),
					array('LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_pminbox.mess_type.archive',2),
				),
			),
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, sendtime, from_uid, from_name, to_uid, to_name, subject, message, read_flg, mess_type')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_wordlist'
 */
$TCA['tx_mmforum_wordlist'] = array(
	'ctrl' => $TCA['tx_mmforum_wordlist']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,word,metaphone'
	),
	'feInterface' => $TCA['tx_mmforum_wordlist']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'word' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordlist.word',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'metaphone' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordlist.metaphone',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, word, metaphone')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_wordmatch'
 */
$TCA['tx_mmforum_wordmatch'] = array(
	'ctrl' => $TCA['tx_mmforum_wordmatch']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,word_id,post_id,is_header,topic_id,forum_id,solved,topic_title,topic_views,topic_replies'
	),
	'feInterface' => $TCA['tx_mmforum_wordmatch']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'word_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordmatch.word_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_wordlist',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'post_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordmatch.post_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_posts',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'is_header' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordmatch.is_header',
			'config' => array(
				'type' => 'check',
			)
		),
		'topic_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordmatch.topic_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_topics',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'forum_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordmatch.forum_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_mmforum_forums',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			),
		),
		'solved' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordmatch.solved',
			'config' => array(
				'type' => 'check'
			)
		),
		'topic_title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordmatch.topic_title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'topic_views' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordmatch.topic_views',
			'config' => array(
				'type' => 'input',
				'size' => '6',
				'eval' => 'int',
				'checkbox' => '0',
				'range' => array(
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'topic_replies' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_wordmatch.topic_replies',
			'config' => array(
				'type' => 'input',
				'size' => '6',
				'eval' => 'int',
				'checkbox' => '0',
				'range' => array(
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'reqUserGroups_f' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.user_group',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_groups',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 100
			),
		),
		'reqUserGroups_c' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.user_group',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_groups',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 100
			),
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, word_id, post_id, is_header, topic_id, forum_id, solved, topic_title, topic_views, topic_replies')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_searchresults'
 */
$TCA['tx_mmforum_searchresults'] = array(
	'ctrl' => $TCA['tx_mmforum_searchresults']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,search_string,array_string,search_place,solved,search_order,groupPost'
	),
	'feInterface' => $TCA['tx_mmforum_searchresults']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'search_string' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.search_string',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'array_string' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.array_string',
			'config' => array(
				'type' => 'input'
			)
		),
		'search_place' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.search_place',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'solved' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.solved',
			'config' => array(
				'type' => 'check'
			),
		),
		'search_order' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.search_order',
			'config' => array(
				'type' => 'radio',
				'items' => array(
					array('LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.search_order.dateDesc',2),
					array('LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.search_order.dateAsc',1),
					array('LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.search_order.repliesMost',3),
					array('LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.search_order.repliesLeast',4),
				),
			),
		),
		'groupPost' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.groupPost',
			'config' => array(
				'type' => 'check'
			),
		),
		'user_groups' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_searchresults.user_group',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_groups',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 100
			),
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, search_string, array_string, search_place, solved, search_order, groupPost')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_userfields'
 */
$TCA['tx_mmforum_userfields'] = array(
	'ctrl' => $TCA['tx_mmforum_userfields']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,label,config'
	),
	'feInterface' => $TCA['tx_mmforum_userfields']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'label' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_userfields.label',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'config' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_userfields.config',
			'config' => array(
				'type' => 'text',
				'cols' => '40',
				'rows' => '5',
			),
			'defaultExtras' => 'fixed-font : enable-tab',
		),
	),
	'types' => array(
		'0' => array('showitem' => '--div--;LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_userfields.tabDefault,hidden;;1;;1-1-1, label,--div--;LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_userfields.tabExtended,config')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_attachments'
 */
$TCA['tx_mmforum_attachments'] = array(
    'ctrl'          => $TCA['tx_mmforum_attachments']['ctrl'],
    'interface'     => array(
        'showRecordFieldList'   => 'hidden,file_type,file_name,file_path,file_size,downloads,post_id'
    ),
    'feInterface'   => $TCA['tx_mmforum_attachments']['feInterface'],
    'columns'       => array(
        'hidden'        => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'        => array(
				'type'          => 'check',
				'default'       => '0'
			)
		),
		'file_type'     => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_attachments.file_type',
			'config'        => array(
				'type'          => 'input',
				'size'          => '30',
			)
		),
		'file_path'     => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_attachments.file_path',
			'config'        => array(
				'type'          => 'input',
				'size'          => '30',
			)
		),
		'file_name'     => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_attachments.file_name',
			'config'        => array(
				'type'          => 'input',
				'size'          => '30',
			)
		),
		'file_size'     => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_attachments.file_size',
			'config'        => array(
				'type'          => 'input',
				'size'          => '16',
                'eval'          => 'int',
			)
		),
		'downloads'     => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_attachments.downloads',
			'config'        => array(
				'type'          => 'input',
				'size'          => '8',
                'eval'          => 'int',
			)
		),
        'post_id'       => array(
            'exclude'       => 1,
            'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_attachments.post_id',
            'config'        => array(
                'type'          => 'group',
                'internal_type' => 'db',
				'allowed'       => 'tx_mmforum_posts',
				'size'          => 1,
				'minitems'      => 1,
				'maxitems'      => 1
            )
        ),
    ),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, file_name, file_path, file_type, file_name, file_size, downloads, post_id')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_polls'
 */
$TCA['tx_mmforum_polls'] = array(
    'ctrl'          => $TCA['tx_mmforum_polls']['ctrl'],
    'interface'     => array(
        'showRecordFieldList'   => 'hidden,endtime,question,votes'
    ),
    'feInterface'   => $TCA['tx_mmforum_polls']['feInterface'],
    'columns'       => array(
        'hidden'        => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'        => array(
				'type'          => 'check',
				'default'       => '0'
			)
		),
		'question'      => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls.question',
			'config'        => array(
				'type'          => 'input',
				'size'          => '30',
			)
		),
        'endtime'       => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls.endtime',
			'config'        => array(
				'type'          => 'input',
				'size'          => '12',
				'eval'          => 'datetime',
				'checkbox'      => '1',
				'default'       => '0'
			)
		),
        'votes'         => array(
            'exclude'       => 1,
            'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls.votes',
            'config'        => array(
                'type'          => 'input',
                'size'          => '8',
                'eval'          => 'int'
            )
        ),
    ),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, endtime, question, votes')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_polls_answers'
 */
$TCA['tx_mmforum_polls_answers'] = array(
    'ctrl'          => $TCA['tx_mmforum_polls_answers']['ctrl'],
    'interface'     => array(
        'showRecordFieldList'   => 'hidden,answer,poll_id,votes'
    ),
    'feInterface'   => $TCA['tx_mmforum_polls_answers']['feInterface'],
    'columns'       => array(
        'hidden'        => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'        => array(
				'type'          => 'check',
				'default'       => '0'
			)
		),
		'answer'        => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls_answers.answer',
			'config'        => array(
				'type'          => 'input',
				'size'          => '30',
			)
		),
        'poll_id'       => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls_answers.poll_id',
			'config'        => array(
				'type'          => 'group',
                'internal_type' => 'db',
				'allowed'       => 'tx_mmforum_polls',
				'size'          => 1,
				'minitems'      => 1,
				'maxitems'      => 1
			)
		),
        'votes'         => array(
            'exclude'       => 1,
            'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls_answers.votes',
            'config'        => array(
                'type'          => 'input',
                'size'          => '8',
                'eval'          => 'int'
            )
        ),
    ),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, answer, poll_id, votes')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_polls_votes'
 */
$TCA['tx_mmforum_polls_votes'] = array(
    'ctrl'          => $TCA['tx_mmforum_polls_votes']['ctrl'],
    'interface'     => array(
        'showRecordFieldList'   => 'hidden,poll_id,answer_id,user_id'
    ),
    'feInterface'   => $TCA['tx_mmforum_polls_votes']['feInterface'],
    'columns'       => array(
        'hidden'        => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'        => array(
				'type'          => 'check',
				'default'       => '0'
			)
		),
		'poll_id'        => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls_votes.poll_id',
			'config'        => array(
				'type'          => 'group',
                'internal_type' => 'db',
				'allowed'       => 'tx_mmforum_polls',
				'size'          => 1,
				'minitems'      => 1,
				'maxitems'      => 1
			)
		),
        'answer_id'       => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls_votes.answer_id',
			'config'        => array(
				'type'          => 'group',
                'internal_type' => 'db',
				'allowed'       => 'tx_mmforum_polls_answers',
				'size'          => 1,
				'minitems'      => 1,
				'maxitems'      => 1
			)
		),
        'user_id'         => array(
            'exclude'       => 1,
            'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_polls_votes.user_id',
            'config'        => array(
				'type'          => 'group',
                'internal_type' => 'db',
				'allowed'       => 'fe_users',
				'size'          => 1,
				'minitems'      => 1,
				'maxitems'      => 1
			)
        ),
    ),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, poll_id, answer_id, user_id')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_ranks'
 */
$TCA['tx_mmforum_ranks'] = array(
    'ctrl'          => $TCA['tx_mmforum_ranks']['ctrl'],
    'interface'     => array(
        'showRecordFieldList'   => 'hidden,title,color,icon,minPosts,special'
    ),
    'feInterface'   => $TCA['tx_mmforum_ranks']['feInterface'],
    'columns'       => array(
        'hidden'        => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'        => array(
				'type'          => 'check',
				'default'       => '0'
			)
		),
		'title'         => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_ranks.title',
			'config'        => array(
				'type'          => 'input',
				'size'          => '30',
			)
		),
		'color'         => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_ranks.color',
			'config'        => array(
				"type"          => "input",
                "size"          => "10",
                'checkbox'      => '1',
                'default'       => '',
                "wizards"       => Array(
                    "_PADDING"      => 2,
                    "color"         => Array(
                        "title"         => "Color:",
                        "type"          => "colorbox",
                        "dim"           => "12x12",
                        "tableStyle"    => "border:solid 1px black;",
                        "script"        => "wizard_colorpicker.php",
                        "JSopenParams"  => "height=300,width=250,status=0,menubar=0,scrollbars=1",
                    ),
                ),
			)
		),
        'icon'          => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_ranks.icon',
            'config'        => array(
                "type"          => "group",
                "internal_type" => "file",
                "allowed"       => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],
                "max_size"      => 500,
                "uploadfolder"  => "uploads/tx_mmforum",
                "show_thumbs"   => 1,
                "size"          => 1,
                "minitems"      => 0,
                "maxitems"      => 1,
            )
        ),
        'minPosts'         => array(
            'exclude'       => 1,
            'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_ranks.minPosts',
            'config'        => array(
                'type'          => 'input',
                'size'          => '8',
                'eval'          => 'int',
                'checkbox'      => '1'
            )
        ),
        'special'         => array(
            'exclude'       => 1,
            'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_ranks.special',
            'config'        => array(
                'type'          => 'check',
            )
        ),
    ),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title,color,icon,minPosts,special')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * Table setup for table 'tx_mmforum_postqueue'
 */
$TCA['tx_mmforum_postqueue'] = array(
    'ctrl'          => $TCA['tx_mmforum_postqueue']['ctrl'],
    'interface'     => array(
        'showRecordFieldList'   => 'hidden,topic,topic_forum,topic_title,topic_poll,topic_subscribe,post_parent,post_text,post_user,post_time,post_attachment,post_ip'
    ),
    'feInterface'   => $TCA['tx_mmforum_postqueue']['feInterface'],
    'columns'       => array(
        'hidden'        => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'        => array(
				'type'          => 'check',
				'default'       => '0'
			)
		),
		'topic'			=> array(
			'exclude'		=> 1,
			'label'			=> 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue.topic',
			'config'		=> array(
				'type'			=> 'check'
			)
		),
		'topic_forum'	=> array(
			'exclude'		=> 1,
			'label'			=> 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue.topic_forum',
			'config'        => array(
				'type'          => 'group',
                'internal_type' => 'db',
				'allowed'       => 'tx_mmforum_forums',
				'size'          => 1,
				'minitems'      => 0,
				'maxitems'      => 1
			)
		),
		'topic_title'   => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue.topic_title',
			'config'        => array(
				'type'          => 'input',
				'size'          => '30',
			)
		),
		'topic_poll'	=> array(
			'exclude'		=> 1,
			'label'			=> 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue.topic_poll',
			'config'        => array(
				'type'          => 'group',
                'internal_type' => 'db',
				'allowed'       => 'tx_mmforum_polls',
				'size'          => 1,
				'minitems'      => 0,
				'maxitems'      => 1
			)
		),
		'topic_subscribe'=> array(
			'exclude'		=> 1,
			'label'			=> 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue.topic_subscribe',
			'config'		=> array(
				'type'			=> 'check'
			)
		),
		'post_parent'	=> array(
			'exclude'		=> 1,
			'label'			=> 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue.post_parent',
			'config'        => array(
				'type'          => 'group',
                'internal_type' => 'db',
				'allowed'       => 'tx_mmforum_topics',
				'size'          => 1,
				'minitems'      => 0,
				'maxitems'      => 1
			)
		),
		'post_text'		=> array(
			'exclude'		=> 1,
			'label'			=> 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue.post_text',
			'config'		=> array(
				'type'			=> 'text',
				'cols'			=> '50',
				'rows'			=> '8'
			)
		),
		'post_user'		=> array(
			'exclude'		=> 1,
			'label'			=> 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue.post_user',
			'config'        => array(
				'type'          => 'group',
                'internal_type' => 'db',
				'allowed'       => 'fe_users',
				'size'          => 1,
				'minitems'      => 1,
				'maxitems'      => 1
			)
		),
		'post_time'   	=> array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue.post_time',
			'config'        => array(
				'type'          => 'input',
				'size' 			=> 12,
				'max' 			=> 20,
				'eval' 			=> 'datetime',
				'default' 		=> 0
			)
		),
		'post_attachment'   => array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue.post_attachment',
			'config'        => array(
				'type'          => 'group',
                'internal_type' => 'db',
				'allowed'       => 'tx_mmforum_attachments',
				'size'          => 1,
				'minitems'      => 0,
				'maxitems'      => 1
			)
		),
		'post_ip'   	=> array(
			'exclude'       => 1,
			'label'         => 'LLL:EXT:mm_forum/locallang_db.xml:tx_mmforum_postqueue.post_ip',
			'config'        => array(
				'type'          => 'input',
				'size'          => '30',
			)
		),
    ),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, topic,topic_forum,topic_title,topic_poll,topic_subscribe,post_parent,post_text,post_user,post_time,post_attachment,post_ip')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);
?>
