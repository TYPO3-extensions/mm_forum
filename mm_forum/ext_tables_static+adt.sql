
#
# Table structure for table 'tx_mmforum_smilies'
#

DROP TABLE IF EXISTS tx_mmforum_smilies;
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
# Data for table 'tx_mmforum_smilies'
#

INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES ( 1, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':D'       , 'icon_biggrin.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES ( 2, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':-D'      , 'icon_biggrin.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES ( 3, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':grin:'   , 'icon_biggrin.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES ( 4, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':)'       , 'icon_smile.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES ( 5, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':-)'      , 'icon_smile.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES ( 6, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':smile:'  , 'icon_smile.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES ( 7, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':('       , 'icon_frown.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES ( 8, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':-('      , 'icon_frown.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES ( 9, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':sad:'    , 'icon_frown.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (10, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':o'       , 'icon_eek.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (11, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':-o'      , 'icon_eek.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (12, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':eek:'    , 'icon_eek.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (13, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':-?'      , 'icon_confused.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (14, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':???:'    , 'icon_confused.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (15, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, '8)'       , 'icon_cool.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (16, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, '8-)'      , 'icon_cool.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (17, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':cool:'   , 'icon_cool.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (18, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':lol:'    , 'icon_lol.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (19, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':x'       , 'icon_mad.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (20, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':-x'      , 'icon_mad.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (21, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':mad:'    , 'icon_mad.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (22, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':P'       , 'icon_razz.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (23, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':-P'      , 'icon_razz.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (24, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':razz:'   , 'icon_razz.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (25, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':oops:'   , 'icon_redface.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (26, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':cry:'    , 'icon_cry.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (27, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':evil:'   , 'icon_evil.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (28, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':roll:'   , 'icon_rolleyes.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (29, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':wink:'   , 'icon_wink.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (30, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ';)'       , 'icon_wink.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (31, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ';-)'      , 'icon_wink.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (32, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, ':bang:'   , 'banghead.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (33, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 1, 0, ':dumm:'   , 'banghead.gif', '');
INSERT INTO tx_mmforum_smilies (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, code, smile_url, emoticon) VALUES (34, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 1, 0, ':exclaim:', 'icon_exclaim.gif', '');

# 
# Table structure for table 'tx_mmforum_postparser'
# 

DROP TABLE IF EXISTS tx_mmforum_postparser;
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
    replacement text NOT NULL default '',
    title tinytext NOT NULL,
    fe_inserticon tinytext NOT NULL,
    
    PRIMARY KEY (uid),
    KEY parent (pid)
);

# 
# Data for table 'tx_mmforum_postparser'
# 

INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES ( 1, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, '[IMG]|[/IMG]'         , '/\\[img\\](.*?)\\[\\/img\\]/isS', '<img src="\\1" border="0" title="\\1" alt="\\1">', 'LLL:newtopic.editor.image', 'image.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES ( 2, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[IMG:|]|[/IMG]'       , '/\\[img:[a-z0-9]{10}\\](.*?)\\[\\/img:[a-z0-9]{10}\\]/isS', '<img src="\\1" border="0" title="\\1" alt="\\1">', 'LLL:newtopic.editor.image', 'image.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES ( 3, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, '[B]|[/B]'             , '/\\[b\\](.*?)\\[\\/b\\]/isS', '<strong>\\1</strong>', 'LLL:newtopic.editor.bold', 'bold.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES ( 4, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[B:|]|[/B]'           , '/\\[b:[a-z0-9]{10}\\](.*?)\\[\\/b:[a-z0-9]{10}\\]/isS', '<strong>\\1</strong>', 'LLL:newtopic.editor.bold', 'bold.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES ( 5, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, '[U]|[/U]'             , '/\\[u\\](.*?)\\[\\/u\\]/isS', '<u>\\1</u>', 'LLL:newtopic.editor.underline', 'underline.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES ( 6, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[U:|]|[/U]'           , '/\\[u\\](.*?)\\[\\/u\\]/isS', '<u>\\1</u>', 'LLL:newtopic.editor.underline', 'underline.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES ( 7, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, '[I]|[/I]'             , '/\\[i\\](.*?)\\[\\/i\\]/isS', '<i>\\1</i>', 'LLL:newtopic.editor.italic', 'italic.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES ( 8, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[I:|]]|[/I]'          , '/\\[i:[a-z0-9]{10}\\](.*?)\\[\\/i:[a-z0-9]{10}\\]/isS', '<i>\\1</i>', 'LLL:newtopic.editor.italic', 'italic.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES ( 9, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, '[COLOR=|]|[/COLOR]'   , '/\\[color=(#?[a-z0-9]*?)\\](.*?)\\[\\/color\\]/isS', '<span style="color:\\1">\\2</span>', 'LLL:newtopic.editor.color', 'color.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (10, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[COLOR=|:|]|[/COLOR]' , '/\\[color=(#?[a-z0-9]*?):[a-z0-9]{10}\\](.*?)\\[\\/color:[a-z0-9]{10}\\]/isS', '<span style="color:\\1">\\2</span>', 'LLL:newtopic.editor.color', 'color.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (11, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[LINK]|[/LINK]'       , '/\\[link\\]http:\\/\\/(.*?)\\[\\/link\\]/isS', '<a href="http://\\1" target="###TARGET###" class="###CSS_CLASS###">\\1</a>', 'LLL:newtopic.editor.link', 'url.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (12, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[URL]|[/URL]'         , '/\\[url\\]http:\\/\\/(.*?)\\[\\/url\\]/isS', '<a href="http://\\1" target="###TARGET###" class="###CSS_CLASS###">\\1</a>', 'LLL:newtopic.editor.link', 'url.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (13, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[URL="|"]|[/URL]'     , '/\\[url=\\"http:\\/\\/(.*?)\\"\\](.*?)\\[\\/url\\]/isS', '<a href="http://\\1" target="###TARGET###" class="###CSS_CLASS###">\\2</a>', 'LLL:newtopic.editor.link', 'url.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (14, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, '[URL=|]|[/URL]'       , '/\\[url=http:\\/\\/(.*?)\\](.*?)\\[\\/url\\]/isS', '<a href="http://\\1" target="###TARGET###" class="###CSS_CLASS###">\\2</a>', 'LLL:newtopic.editor.link', 'url.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (15, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[LINK]|[/LINK]'       , '/\\[link\\](.*?)\\[\\/link\\]/isS', '<a href="http://\\1" target="###TARGET###" class="###CSS_CLASS###">\\1</a>', 'LLL:newtopic.editor.link', 'url.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (16, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[URL]|[URL]'          , '/\\[url\\]([a-z0-9:\\.\\\\\\\/-\\s]*?)\\[\\/url\\]/isS', '<a href="http://\\1" target="###TARGET###" class="###CSS_CLASS###">\\1</a>', 'LLL:newtopic.editor.link', 'url.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (17, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[URL=|]|[/URL]'       , '/\\[url=([a-z0-9:\\.\\\\\\\/-\\s]*?)\\](.*?)\\[\\/url\\]/isS', '<a href="http://\\1" target="###TARGET###" class="###CSS_CLASS###">\\2</a>', 'LLL:newtopic.editor.link', 'url.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (18, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, '[SIZE=|]|[/SIZE]'     , '/\\[size=([0-9]*?(pt|px?))\\](.*?)\\[\\/size\\]/isS', '<span style="font-size: \\1">\\3</span>', 'LLL:newtopic.editor.size', 'size.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (19, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[SIZE=|]|[/SIZE:|]'   , '/\\[size=([0-9]*?):[a-z0-9]{10}\\](.*?)\\[\\/size:[a-z0-9]{10}\\]/isS', '<span style="font-size: \\1">\\2</span>', 'LLL:newtopic.editor.size', 'size.gif');


# 
# Table structure for table 'tx_mmforum_syntaxhl'
# 

DROP TABLE IF EXISTS tx_mmforum_syntaxhl;
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
# Data for table 'tx_mmforum_syntaxhl'
# 

INSERT INTO tx_mmforum_syntaxhl (uid, pid, tstamp, crdate, deleted, hidden, lang_title, lang_pattern, lang_code, fe_inserticon) VALUES (1, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'PHP' , '/\\[php\\](.*?)\\[\\/php\\]/isS' , 'php' , 'php.gif');
INSERT INTO tx_mmforum_syntaxhl (uid, pid, tstamp, crdate, deleted, hidden, lang_title, lang_pattern, lang_code, fe_inserticon) VALUES (2, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Typoscript' , '/\\[ts\\](.*?)\\[\\/ts\\]/isS' , 'ts' , 'ts.gif');
INSERT INTO tx_mmforum_syntaxhl (uid, pid, tstamp, crdate, deleted, hidden, lang_title, lang_pattern, lang_code, fe_inserticon) VALUES (3, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'HTML' , '/\\[html\\](.*?)\\[\\/html\\]/isS' , 'html', 'html.gif');
INSERT INTO tx_mmforum_syntaxhl (uid, pid, tstamp, crdate, deleted, hidden, lang_title, lang_pattern, lang_code, fe_inserticon) VALUES (4, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Javascript' , '/\\[js\\](.*?)\\[\\/js\\]/isS' , 'js' , 'js.gif');
INSERT INTO tx_mmforum_syntaxhl (uid, pid, tstamp, crdate, deleted, hidden, lang_title, lang_pattern, lang_code, fe_inserticon) VALUES (5, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'SQL' , '/\\[sql\\](.*?)\\[\\/sql\\]/isS' , 'sql' , 'sql.gif');
INSERT INTO tx_mmforum_syntaxhl (uid, pid, tstamp, crdate, deleted, hidden, lang_title, lang_pattern, lang_code, fe_inserticon) VALUES (6, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'CSS' , '/\\[css\\](.*?)\\[\\/css\\]/isS' , 'css' , 'css.gif');