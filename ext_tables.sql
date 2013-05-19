#
# Table structure for table 'fe_users'
#
CREATE TABLE fe_users (
    tx_mmforum_avatar blob NOT NULL,
    tx_mmforum_icq tinytext NOT NULL,
    tx_mmforum_aim tinytext NOT NULL,
    tx_mmforum_yim tinytext NOT NULL,
    tx_mmforum_msn tinytext NOT NULL,
    tx_mmforum_skype tinytext NOT NULL,
    tx_mmforum_md5 tinytext NOT NULL,
    tx_mmforum_posts int(11) DEFAULT '0' NOT NULL,
    tx_mmforum_user_sig text NOT NULL,
    tx_mmforum_prelogin int(11) DEFAULT '0' NOT NULL,
    tx_mmforum_interests tinytext NOT NULL,
    tx_mmforum_occ tinytext NOT NULL,
    tx_mmforum_reg_hash tinytext NOT NULL,
    tx_mmforum_pmnotifymode tinyint(1) unsigned default '0' NOT NULL
);

#
# Table structure for table 'fe_groups'
#
CREATE TABLE fe_groups (
    tx_mmforum_rank blob NOT NULL,
    tx_mmforum_rank_excl tinyint(1) DEFAULT '0' NOT NULL
);

# 
# Table structure for table 'tx_mmforum_favorites'
# 
CREATE TABLE tx_mmforum_favorites (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    deleted tinyint(4) unsigned default '0' NOT NULL,
    hidden tinyint(4) unsigned default '0' NOT NULL,
    user_id int(11) default '0' NOT NULL,
    topic_id int(11) default '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

# 
# Table structure for table 'tx_mmforum_forums'
# 
CREATE TABLE tx_mmforum_forums (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    deleted tinyint(4) unsigned default '0' NOT NULL,
    hidden tinyint(4) unsigned default '0' NOT NULL,
    forum_name tinytext  NOT NULL,
    forum_desc tinytext NOT NULL,
    forum_posts int(11) default '0' NOT NULL,
    forum_last_post_id int(11) default '0' NOT NULL,
    cat_id int(11) default '0' NOT NULL,
    forum_order int(11) default '0' NOT NULL,
    forum_topics int(11) default '0' NOT NULL,
    forum_internal tinyint(3) unsigned default '0' NOT NULL,
    sorting int(10) unsigned default '0' NOT NULL,
    grouprights_read blob NOT NULL,
    grouprights_write blob NOT NULL,
    grouprights_mod blob NOT NULL,
    userrights_read blob NOT NULL,
    userrights_write blob NOT NULL,
    userrights_mod blob NOT NULL,
    parentID int(11) default '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY parent_forum (parentID),
    KEY select_all (pid,deleted,hidden,sorting)
);

# 
# Table structure for table 'tx_mmforum_mailkey'
#
CREATE TABLE tx_mmforum_mailkey (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    code tinytext NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

# 
# Table structure for table 'tx_mmforum_post_alert'
# 
CREATE TABLE tx_mmforum_post_alert (
    uid int(11) NOT NULL auto_increment,
    pid int(11) default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    deleted tinyint(4) unsigned default '0' NOT NULL,
    hidden tinyint(4) unsigned default '0' NOT NULL,
    alert_text text NOT NULL,
    post_id int(11) default '0' NOT NULL,
    topic_id int(11) default '0' NOT NULL,
    mod_id int(11) default '0' NOT NULL,
    status tinyint(4) default '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

# 
# Table structure for table 'tx_mmforum_postparser'
# 
CREATE TABLE tx_mmforum_postparser (
    uid int(11) NOT NULL auto_increment,
    pid int(11) NOT NULL default '0',
    tstamp int(11) NOT NULL default '0',
    crdate int(11) NOT NULL default '0',
    cruser_id int(11) NOT NULL default '0',
    deleted tinyint(4) NOT NULL default '0',
    hidden tinyint(4) NOT NULL default '0',
    bbcode varchar(150) NOT NULL default '',
    pattern varchar(150) NOT NULL default '',
    replacement text NOT NULL,
    title tinytext NOT NULL,
    fe_inserticon tinytext NOT NULL,
    
    PRIMARY KEY (uid),
    KEY parent (pid)
);

# 
# Table structure for table 'tx_mmforum_posts'
# 
CREATE TABLE tx_mmforum_posts (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    deleted tinyint(4) unsigned default '0' NOT NULL,
    hidden tinyint(4) unsigned default '0' NOT NULL,
    topic_id int(11) default '0' NOT NULL,
    forum_id int(11) default '0' NOT NULL,
    poster_id int(11) default '0' NOT NULL,
    post_time int(11) default '0' NOT NULL,
    poster_ip tinytext NOT NULL,
    edit_time int(11) default '0' NOT NULL,
    edit_count int(11) default '0' NOT NULL,
    attachment tinytext NOT NULL,
    tx_mmforumsearch_index_write int(11) default '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY topic_id (topic_id),
    KEY forum_id (forum_id),
    KEY post_time (post_time),
    KEY deleted (deleted),
    KEY hidden (hidden),
	KEY is_read (deleted,forum_id,crdate,topic_id)
);

# 
# Table structure for table 'tx_mmforum_posts_text'
# 
CREATE TABLE tx_mmforum_posts_text (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    deleted tinyint(4) unsigned default '0' NOT NULL,
    hidden tinyint(4) unsigned default '0' NOT NULL,
    post_id int(11) unsigned default '0' NOT NULL,
    post_text text NOT NULL,
    cache_tstamp int(11) unsigned default '0' NOT NULL,
    cache_text text NOT NULL,

    PRIMARY KEY (uid),
    UNIQUE KEY unique_post_id (post_id),
    KEY parent (pid),
    KEY post_id (post_id)
);

#
# Table structure for table 'tx_mmforum_postsread'
#
CREATE TABLE tx_mmforum_postsread (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    deleted tinyint(4) unsigned default '0' NOT NULL,
    hidden tinyint(4) unsigned default '0' NOT NULL,
    user int(11) default '0' NOT NULL,
    topic_id int(11) default '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY user (user),
    KEY topic_id (topic_id),
    KEY parent (pid),
	KEY is_read (user,pid,topic_id)
);

# 
# Table structure for table 'tx_mmforum_smilies'
# 
CREATE TABLE tx_mmforum_smilies (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    deleted tinyint(4) unsigned default '0' NOT NULL,
    hidden tinyint(4) unsigned default '0' NOT NULL,
    code tinytext NOT NULL,
    smile_url tinytext NOT NULL,
    emoticon tinytext NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

# 
# Table structure for table 'tx_mmforum_topicmail'
# 
CREATE TABLE tx_mmforum_topicmail (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    deleted tinyint(4) unsigned default '0' NOT NULL,
    hidden tinyint(4) unsigned default '0' NOT NULL,
    user_id int(11) default '0' NOT NULL,
    topic_id int(11) default '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY user_id (user_id),
    KEY topic_id (topic_id)
);

# 
# Table structure for table 'tx_mmforum_topics'
# 
CREATE TABLE tx_mmforum_topics (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    deleted tinyint(4) unsigned default '0' NOT NULL,
    hidden tinyint(4) unsigned default '0' NOT NULL,
    topic_title tinytext NOT NULL,
    topic_poster int(11) default '0' NOT NULL,
    topic_time int(11) default '0' NOT NULL,
    topic_views int(11) default '0' NOT NULL,
    topic_replies int(11) default '0' NOT NULL,
    topic_last_post_id int(11) default '0' NOT NULL,
    forum_id int(11) default '0' NOT NULL,
    topic_first_post_id int(11) default '0' NOT NULL,
    topic_is varchar(255) default '0' NOT NULL,
    solved tinyint(1) unsigned default '0' NOT NULL,
    read_flag tinyint(1) default '0' NOT NULL,
    at_top_flag tinyint(1) default '0' NOT NULL,
    closed_flag tinyint(1) unsigned default '0' NOT NULL,
    poll_id int(11) default '0' NOT NULL,
    shadow_tid int(11) default '0' NOT NULL,
    shadow_fid int(11) default '0' NOT NULL,
    tx_mmforumsearch_index_write int(11) default '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY forum_id (forum_id),
    KEY select_all (pid,deleted,hidden,forum_id,at_top_flag,topic_last_post_id)
);

# 
# Table structure for table 'tx_mmforum_userconfig'
# 
CREATE TABLE tx_mmforum_userconfig (
    uid int(11) NOT NULL auto_increment,
    pid int(11) default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    deleted tinyint(4) unsigned default '0' NOT NULL,
    hidden tinyint(4) unsigned default '0' NOT NULL,
    userid int(11) default '0' NOT NULL,
    post_sort tinytext NOT NULL,
    ip varchar(16) NOT NULL default '',

    PRIMARY KEY (uid),
    KEY parent (pid)
);

# 
# Table structure for table 'tx_mmforum_searchresults'
# 
CREATE TABLE tx_mmforum_searchresults (
    uid int(11) NOT NULL auto_increment,
    pid int(11) default '0' NOT NULL,
    tstamp int(11) default '0' NOT NULL,
    crdate int(11) default '0' NOT NULL,
    cruser_id int(11) default '0' NOT NULL,
    deleted tinyint(4) default '0' NOT NULL,
    hidden tinyint(4) default '0' NOT NULL,
    search_string varchar(255) NOT NULL default '',
    array_string blob NOT NULL,
    search_place int(4) default '0' NOT NULL,
    solved int(1) default '0' NOT NULL,
    search_order int(2) default '0' NOT NULL,
    groupPost tinyint(1) default '0' NOT NULL,
    user_groups blob NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY search_string (search_string(166)),
    KEY tstamp (tstamp),
    KEY groupPosts (groupPost)
);

# 
# Table structure for table 'tx_mmforum_wordlist'
# 
CREATE TABLE tx_mmforum_wordlist (
    uid int(11) NOT NULL auto_increment,
    pid int(11) default '0' NOT NULL,
    tstamp int(11) default '0' NOT NULL,
    crdate int(11) default '0' NOT NULL,
    cruser_id int(11) default '0' NOT NULL,
    deleted tinyint(4) default '0' NOT NULL,
    hidden tinyint(4) default '0' NOT NULL,
    word varchar(255) NOT NULL default '',
    metaphone tinytext NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY word (word(166))
);

# 
# Table structure for table 'tx_mmforum_wordmatch'
# 
CREATE TABLE tx_mmforum_wordmatch (
    uid int(11) NOT NULL auto_increment,
    pid int(11) default '0' NOT NULL,
    tstamp int(11) default '0' NOT NULL,
    crdate int(11) default '0' NOT NULL,
    cruser_id int(11) default '0' NOT NULL,
    deleted tinyint(4) default '0' NOT NULL,
    hidden tinyint(4) default '0' NOT NULL,
    word_id int(11) default '0' NOT NULL,
    post_id int(11) default '0' NOT NULL,
    is_header tinyint(3) default '0' NOT NULL,
    topic_id int(11) default '0' NOT NULL,
    forum_id int(11) default '0' NOT NULL,
    solved tinytext NOT NULL,
    topic_title tinytext NOT NULL,
    topic_views int(11) default '0' NOT NULL,
    topic_replies int(11) default '0' NOT NULL,
    post_crdate int(10) default '0' NOT NULL,
    post_cruser int(11) default '0' NOT NULL,
    reqUserGroups_f blob NOT NULL,
    reqUserGroups_c blob NOT NULL,

    PRIMARY KEY (uid),
    KEY word_id (word_id),
    KEY post_user (post_cruser),
    KEY post_id (post_id,topic_id),
    KEY topic_id (topic_id),
    KEY parent (pid)
);

# 
# Table structure for table 'tx_mmforum_pminbox'
# 
CREATE TABLE tx_mmforum_pminbox (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    tstamp int(11) unsigned default '0' NOT NULL,
    crdate int(11) unsigned default '0' NOT NULL,
    cruser_id int(11) unsigned default '0' NOT NULL,
    deleted tinyint(4) unsigned default '0' NOT NULL,
    hidden tinyint(4) unsigned default '0' NOT NULL,
    sendtime tinytext NOT NULL,
    from_uid int(11) unsigned default '0' NOT NULL,
    from_name tinytext NOT NULL,
    to_uid int(11) unsigned default '0' NOT NULL,
    to_name tinytext NOT NULL,
    subject tinytext NOT NULL,
    message text NOT NULL,
    read_flg tinytext NOT NULL,
    mess_type tinyint(1) default '0' NOT NULL,
    notified tinyint(1) default '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
	KEY receipient (to_uid,mess_type)
);

#
# Table structure for table 'tx_mmforum_pminbox'
#
CREATE TABLE tx_mmforum_syntaxhl (
    uid int(10) unsigned NOT NULL auto_increment,
    pid int(10) unsigned NOT NULL default '0',
    tstamp int(10) unsigned NOT NULL default '0',
    crdate int(10) unsigned NOT NULL default '0',
    deleted tinyint(1) NOT NULL default '0',
    hidden tinyint(1) NOT NULL default '0',
    lang_title tinytext NOT NULL,
    lang_pattern tinytext NOT NULL,
    lang_code varchar(64) NOT NULL default '',
    fe_inserticon tinytext NOT NULL,
    
    PRIMARY KEY (uid),
    UNIQUE KEY lang_code (lang_code),
    KEY pid (pid)
);

# 
# Table structure for table 'tx_mmforum_userfields'
# 

CREATE TABLE tx_mmforum_userfields (
  uid int(10) unsigned NOT NULL auto_increment,
  pid int(10) unsigned NOT NULL default '0',
  tstamp int(10) unsigned NOT NULL default '0',
  crdate int(10) unsigned NOT NULL default '0',
  cruser_id int(10) unsigned NOT NULL default '0',
  sorting int(11) NOT NULL default '0',
  deleted tinyint(1) NOT NULL default '0',
  hidden tinyint(1) NOT NULL default '0',
  label tinytext NOT NULL,
  config text NOT NULL,
  public tinyint(1) NOT NULL default '0',
  uniquefield tinyint(1) NOT NULL default '0',
  
  PRIMARY KEY (uid),
  KEY pid (pid)
);

# 
# Table structure for table 'tx_mmforum_userfields_contents'
# 

CREATE TABLE tx_mmforum_userfields_contents (
  uid int(10) unsigned NOT NULL auto_increment,
  pid int(10) unsigned NOT NULL default '0',
  deleted tinyint(4) NOT NULL default '0',
  tstamp int(11) NOT NULL default '0',
  crdate int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  field_id int(11) NOT NULL default '0',
  field_value text NOT NULL,
  
  PRIMARY KEY (uid),
  KEY pid (pid)
);

#
# Table structure for table 'tx_mmforum_attachments'
#

CREATE TABLE tx_mmforum_attachments (
  uid int(10) unsigned NOT NULL auto_increment,
  pid int(10) unsigned NOT NULL default '0',
  deleted tinyint(1) NOT NULL default '0',
  hidden tinyint(1) NOT NULL default '0',
  tstamp int(11) NOT NULL default '0',
  crdate int(11) NOT NULL default '0',
  cruser_id int(11) NOT NULL default '0',
  file_type text NOT NULL,
  file_name text NOT NULL,
  file_path text NOT NULL,
  file_size int(11) NOT NULL default '0',
  downloads int(11) NOT NULL default '0',
  post_id int(11) NOT NULL default '0',
  
  PRIMARY KEY (uid),
  KEY pid (pid)
);

#
# Table structure for table 'tx_mmforum_polls'
#

CREATE TABLE tx_mmforum_polls (
  uid int(10) unsigned NOT NULL auto_increment,
  pid int(10) unsigned NOT NULL default '0',
  tstamp int(10) unsigned NOT NULL default '0',
  crdate int(10) unsigned NOT NULL default '0',
  deleted tinyint(1) NOT NULL default '0',
  hidden tinyint(1) NOT NULL default '0',
  cruser_id int(11) NOT NULL default '0',
  endtime int(11) NOT NULL default '0',
  question text NOT NULL,
  crfeuser_id int(11) NOT NULL default '0',
  votes int(11) NOT NULL default '0',
  
  PRIMARY KEY (uid),
  KEY pid (pid)
);

# 
# Table structure for table 'tx_mmforum_polls_answers'
# 

CREATE TABLE tx_mmforum_polls_answers (
  uid int(10) unsigned NOT NULL auto_increment,
  pid int(10) unsigned NOT NULL default '0',
  tstamp int(10) unsigned NOT NULL default '0',
  crdate int(10) unsigned NOT NULL default '0',
  deleted tinyint(1) NOT NULL default '0',
  hidden tinyint(1) NOT NULL default '0',
  poll_id int(11) NOT NULL default '0',
  votes int(11) NOT NULL default '0',
  answer text NOT NULL,
  
  PRIMARY KEY (uid),
  KEY pid (pid,poll_id)
);

# 
# Table structure for table 'tx_mmforum_polls_votes'
# 

CREATE TABLE tx_mmforum_polls_votes (
  uid int(10) unsigned NOT NULL auto_increment,
  pid int(10) unsigned NOT NULL default '0',
  tstamp int(10) unsigned NOT NULL default '0',
  crdate int(10) unsigned NOT NULL default '0',
  deleted tinyint(1) NOT NULL default '0',
  hidden tinyint(1) NOT NULL default '0',
  poll_id int(11) NOT NULL default '0',
  answer_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  
  PRIMARY KEY (uid),
  KEY pid (pid)
);

#
# Table structure for table 'tx_mmforum_ranks'
#

CREATE TABLE tx_mmforum_ranks (
  uid int(10) unsigned NOT NULL auto_increment,
  pid int(10) unsigned NOT NULL default '0',
  deleted tinyint(1) NOT NULL default '0',
  hidden tinyint(1) NOT NULL default '0',
  tstamp int(11) NOT NULL default '0',
  crdate int(11) NOT NULL default '0',
  cruser_id int(11) NOT NULL default '0',
  icon blob NOT NULL,
  title tinytext NOT NULL,
  color tinytext NOT NULL,
  minPosts int(11) NOT NULL default '0',
  special tinyint(1) NOT NULL default '0',
  
  PRIMARY KEY (uid),
  KEY pid (pid)
);

#
# Table structure for table 'tx_mmforum_postqueue'
#

CREATE TABLE tx_mmforum_postqueue (
  uid int(10) unsigned NOT NULL auto_increment,
  pid int(10) unsigned NOT NULL default '0',
  tstamp int(10) unsigned NOT NULL default '0',
  crdate int(10) unsigned NOT NULL default '0',
  deleted tinyint(1) NOT NULL default '0',
  hidden tinyint(1) unsigned NOT NULL default '0',
  topic tinyint(1) NOT NULL default '0',
  topic_forum int(10) unsigned NOT NULL default '0',
  topic_title tinytext NOT NULL,
  topic_poll int(10) unsigned NOT NULL default '0',
  topic_subscribe tinyint(1) NOT NULL default '0',
  post_parent int(10) unsigned NOT NULL default '0',
  post_text text NOT NULL,
  post_user int(10) unsigned NOT NULL default '0',
  post_time int(10) unsigned NOT NULL default '0',
  post_ip tinytext NOT NULL,
  post_attachment int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (uid),
  KEY parent (pid)
);

#
# Table structure for table "tx_mmforum_forummail"
#
CREATE TABLE tx_mmforum_forummail (
	uid int(11) unsigned auto_increment,
	pid int(11) unsigned default '0',
	tstamp int(11) unsigned default '0',
	crdate int(11) unsigned default '0',
	cruser_id int(11) unsigned default '0',
	deleted tinyint(4) unsigned default '0',
	hidden tinyint(4) unsigned default '0',
	user_id int(11) default '0',
	forum_id int(11) default '0',
	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY user_id (user_id),
	KEY forum_id (forum_id)
);

#
# Table structure for table "tx_mmforum_cache"
#
CREATE TABLE tx_mmforum_cache (
	uid int(11) unsigned auto_increment,
	tstamp int(11) unsigned default '0',
	cache_key tinytext NOT NULL,
	cache_value text NOT NULL,
	
	PRIMARY KEY (uid)
	KEY cache_key (cache_key(255))
);
