
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
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (16, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[URL]|[URL]'          , '/\\[url=?\\]([a-z0-9:\\.\\\\\\\/-\\s]*?)\\[\\/url\\]/isS', '<a href="http://\\1" target="###TARGET###" class="###CSS_CLASS###">\\1</a>', 'LLL:newtopic.editor.link', 'url.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (17, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[URL=|]|[/URL]'       , '/\\[url=([a-z0-9:\\.\\\\\\\/-\\s]+?)\\](.*?)\\[\\/url\\]/isS', '<a href="http://\\1" target="###TARGET###" class="###CSS_CLASS###">\\2</a>', 'LLL:newtopic.editor.link', 'url.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (18, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[SIZE=|]|[/SIZE]'     , '/\\[size=([0-9])\\](.*?)\\[\\/size\\]/isS', '<span style="font-size: \\1em">\\2</span>', 'LLL:newtopic.editor.size', 'size.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (19, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 0, '[SIZE=|]|[/SIZE]'     , '/\\[size=([0-9]+(pt|px))\\](.*?)\\[\\/size\\]/isS', '<span style="font-size: \\1">\\3</span>', 'LLL:newtopic.editor.size', 'size.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (20, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[SIZE=|]|[/SIZE:|]'   , '/\\[size=([0-9]*?):[a-z0-9]{10}\\](.*?)\\[\\/size:[a-z0-9]{10}\\]/isS', '<span style="font-size: \\1">\\2</span>', 'LLL:newtopic.editor.size', 'size.gif');
INSERT INTO tx_mmforum_postparser (uid, pid, tstamp, crdate, cruser_id, deleted, hidden, bbcode, pattern, replacement, title, fe_inserticon) VALUES (21, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 1, '[URL=&quot;|&quot;]|[/URL]'     , '/\\[url=\\&quot\\;http:\\/\\/(.*?)\\&quot\\;\\](.*?)\\[\\/url\\]/isS', '<a href="http://\\1" target="###TARGET###" class="###CSS_CLASS###">\\2</a>', 'LLL:newtopic.editor.link', 'url.gif');


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
INSERT INTO tx_mmforum_syntaxhl (uid, pid, tstamp, crdate, deleted, hidden, lang_title, lang_pattern, lang_code, fe_inserticon) VALUES (2, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Typoscript' , '/\\[(?<tagname>ts|typoscript)\\](.*?)\\[\\/\g{tagname}\\]/isS' , 'typoscript' , 'ts.gif');
INSERT INTO tx_mmforum_syntaxhl (uid, pid, tstamp, crdate, deleted, hidden, lang_title, lang_pattern, lang_code, fe_inserticon) VALUES (3, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'HTML' , '/\\[html\\](.*?)\\[\\/html\\]/isS' , 'html4strict', 'html.gif');
INSERT INTO tx_mmforum_syntaxhl (uid, pid, tstamp, crdate, deleted, hidden, lang_title, lang_pattern, lang_code, fe_inserticon) VALUES (4, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Javascript' , '/\\[js\\](.*?)\\[\\/js\\]/isS' , 'javascript' , 'js.gif');
INSERT INTO tx_mmforum_syntaxhl (uid, pid, tstamp, crdate, deleted, hidden, lang_title, lang_pattern, lang_code, fe_inserticon) VALUES (5, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'SQL' , '/\\[sql\\](.*?)\\[\\/sql\\]/isS' , 'sql' , 'sql.gif');
INSERT INTO tx_mmforum_syntaxhl (uid, pid, tstamp, crdate, deleted, hidden, lang_title, lang_pattern, lang_code, fe_inserticon) VALUES (6, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'CSS' , '/\\[css\\](.*?)\\[\\/css\\]/isS' , 'css' , 'css.gif');

#
# Table structure for table 'tx_mmforum_userfields'
#

DROP TABLE IF EXISTS tx_mmforum_userfields;
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
  meta text NOT NULL,
  public tinyint(1) NOT NULL default '0',
  uniquefield tinyint(1) NOT NULL default '0',

  PRIMARY KEY (`uid`),
  KEY `pid` (`pid`)
);

#
# Data for table 'tx_mmforum_userfields'
#

INSERT INTO tx_mmforum_userfields (uid, pid, tstamp, crdate, cruser_id, sorting, deleted, hidden, label, config, meta, public) VALUES(7, 0, 1234651652, 1234651652, 1, 10, 0, 0, 'Name', 'datasource = name\r\nrequired = 1\r\nlabel = TEXT\r\nlabel {\r\n	value = Name\r\n	lang {\r\n		de = Name\r\n		fr = Nom\r\n	}\r\n}\r\ninput = HTML\r\ninput {\r\n	value = <input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###"  />\r\n}\r\n', 'a:8:{s:5:"label";a:3:{s:7:"default";s:4:"Name";s:2:"de";s:4:"Name";s:2:"fr";s:3:"Nom";}s:8:"required";b:1;s:7:"private";b:0;s:4:"link";s:4:"name";s:4:"type";s:4:"text";s:4:"text";a:2:{s:6:"length";i:-1;s:8:"validate";s:4:"none";}s:5:"radio";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}s:6:"select";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}}', 1);
INSERT INTO tx_mmforum_userfields (uid, pid, tstamp, crdate, cruser_id, sorting, deleted, hidden, label, config, meta, public) VALUES(8, 0, 1234811238, 1234651689, 1, 9, 0, 0, 'Email', 'datasource = email\r\nrequired = 1\r\nlabel = TEXT\r\nlabel {\r\n	value = Email\r\n	lang {\r\n		de = E-Mail\r\n	}\r\n}\r\nvalidate = /^[a-z0-9!#$%\\*\\/\\?\\|\\^\\{\\}`~&''\\+\\-=_]([a-z0-9!#$%\\*\\/\\?\\|\\^\\{\\}`~&''\\+\\-=_\\.]*?)[a-z0-9!#$%\\*\\/\\?\\|\\^\\{\\}`~&''\\+\\-=_]@([a-z0-9-\\.]+?)[a-z0-9]\\.([a-z\\.])+$/i\r\ninput = HTML\r\ninput {\r\n	value = <input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###"  />\r\n}\r\n', 'a:8:{s:5:"label";a:2:{s:7:"default";s:5:"Email";s:2:"de";s:6:"E-Mail";}s:8:"required";b:1;s:7:"private";b:1;s:4:"link";s:5:"email";s:4:"type";s:4:"text";s:4:"text";a:2:{s:6:"length";i:-1;s:8:"validate";s:5:"email";}s:5:"radio";a:1:{s:5:"value";a:1:{i:0;s:5:"Array";}}s:6:"select";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}}', 0);
INSERT INTO tx_mmforum_userfields (uid, pid, tstamp, crdate, cruser_id, sorting, deleted, hidden, label, config, meta, public) VALUES(9, 0, 1234652022, 1234652022, 1, 8, 0, 0, 'Website', 'datasource = www\r\nlabel = TEXT\r\nlabel {\r\n	value = Website\r\n	lang {\r\n		de = Webseite\r\n	}\r\n}\r\nvalidate = /^https?:\\/\\/([a-zA-Z0-9_\\-]+:[^\\s@:]+@)?((([a-zA-Z][a-zA-Z0-9\\-]+\\.)+[a-zA-Z\\-]+)|((2(5[0-5]|[0-4][0-9])|[01][0-9]{2}|[0-9]{1,2})\\.(2(5[0-5]|[0-4][0-9])|[01][0-9]{2}|[0-9]{1,2})\\.(2(5[0-5]|[0-4][0-9])|[01][0-9]{2}|[0-9]{1,2})\\.(2(5[0-5]|[0-4][0-9])|[01][0-9]{2}|[0-9]{1,2})))(:[0-9]{1,5})?(\\/[!~*''\\(\\)a-zA-Z0-9;\\/\\\\?:\\@&=\\+\\$,%#\\._-]*)*$/\r\ninput = HTML\r\ninput {\r\n	value = <input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###"  />\r\n}\r\n', 'a:8:{s:5:"label";a:2:{s:7:"default";s:7:"Website";s:2:"de";s:8:"Webseite";}s:8:"required";b:0;s:7:"private";b:0;s:4:"link";s:3:"www";s:4:"type";s:4:"text";s:4:"text";a:2:{s:6:"length";i:-1;s:8:"validate";s:3:"url";}s:5:"radio";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}s:6:"select";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}}', 1);
INSERT INTO tx_mmforum_userfields (uid, pid, tstamp, crdate, cruser_id, sorting, deleted, hidden, label, config, meta, public) VALUES(10, 0, 1234711761, 1234711761, 1, 7, 0, 0, 'Location', 'datasource = address\r\nlabel = TEXT\r\nlabel {\r\n	value = Location\r\n	lang {\r\n		de = Wohnort\r\n	}\r\n}\r\ninput = HTML\r\ninput {\r\n	value = <input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###"  />\r\n}\r\n', 'a:8:{s:5:"label";a:2:{s:7:"default";s:8:"Location";s:2:"de";s:7:"Wohnort";}s:8:"required";b:0;s:7:"private";b:0;s:4:"link";s:7:"address";s:4:"type";s:4:"text";s:4:"text";a:2:{s:6:"length";i:-1;s:8:"validate";s:4:"none";}s:5:"radio";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}s:6:"select";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}}', 1);
INSERT INTO tx_mmforum_userfields (uid, pid, tstamp, crdate, cruser_id, sorting, deleted, hidden, label, config, meta, public) VALUES(11, 0, 1234711810, 1234711810, 1, 6, 0, 0, 'Occupation', 'datasource = tx_mmforum_occ\r\nlabel = TEXT\r\nlabel {\r\n	value = Occupation\r\n	lang {\r\n		de = Beruf\r\n	}\r\n}\r\ninput = HTML\r\ninput {\r\n	value = <input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###"  />\r\n}\r\n', 'a:8:{s:5:"label";a:2:{s:7:"default";s:10:"Occupation";s:2:"de";s:5:"Beruf";}s:8:"required";b:0;s:7:"private";b:0;s:4:"link";s:14:"tx_mmforum_occ";s:4:"type";s:4:"text";s:4:"text";a:2:{s:6:"length";i:-1;s:8:"validate";s:4:"none";}s:5:"radio";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}s:6:"select";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}}', 1);
INSERT INTO tx_mmforum_userfields (uid, pid, tstamp, crdate, cruser_id, sorting, deleted, hidden, label, config, meta, public) VALUES(12, 0, 1234711848, 1234711848, 1, 5, 0, 0, 'Interests', 'datasource = tx_mmforum_interests\r\nlabel = TEXT\r\nlabel {\r\n	value = Interests\r\n	lang {\r\n		de = Interessen\r\n	}\r\n}\r\ninput = HTML\r\ninput {\r\n	value = <input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###"  />\r\n}\r\n', 'a:8:{s:5:"label";a:2:{s:7:"default";s:9:"Interests";s:2:"de";s:10:"Interessen";}s:8:"required";b:0;s:7:"private";b:0;s:4:"link";s:20:"tx_mmforum_interests";s:4:"type";s:4:"text";s:4:"text";a:2:{s:6:"length";i:-1;s:8:"validate";s:4:"none";}s:5:"radio";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}s:6:"select";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}}', 1);
INSERT INTO tx_mmforum_userfields (uid, pid, tstamp, crdate, cruser_id, sorting, deleted, hidden, label, config, meta, public) VALUES(13, 0, 1234712523, 1234711971, 1, 4, 0, 0, 'MSN', 'datasource = tx_mmforum_msn\r\nlabel = COA\r\nlabel {\r\n	10 = IMAGE\r\n	10 {\r\n		file = EXT:mm_forum/res/tmpl/default/img/default/buttons/icons/msn.png\r\n		file.width = 16\r\n		file.height = 16\r\n		params = style="vertical-align:middle;"\r\n		wrap = |&nbsp;\r\n	}\r\n\r\n	20 = TEXT\r\n	20.value = MSN\r\n}\r\ninput = HTML\r\ninput {\r\n	value = <input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###"  />\r\n}\r\n', 'a:8:{s:5:"label";a:1:{s:7:"default";s:3:"MSN";}s:8:"required";b:0;s:7:"private";b:0;s:4:"link";s:14:"tx_mmforum_msn";s:4:"type";s:6:"custom";s:4:"text";a:2:{s:6:"length";i:-1;s:8:"validate";s:4:"none";}s:5:"radio";a:1:{s:5:"value";a:1:{i:0;s:5:"Array";}}s:6:"select";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}}', 1);
INSERT INTO tx_mmforum_userfields (uid, pid, tstamp, crdate, cruser_id, sorting, deleted, hidden, label, config, meta, public) VALUES(14, 0, 1234712656, 1234712642, 1, 3, 0, 0, 'YIM', 'datasource = tx_mmforum_yim\r\nlabel = COA\r\nlabel {\r\n	10 = IMAGE\r\n	10 {\r\n		file = EXT:mm_forum/res/tmpl/default/img/default/buttons/icons/yim.png\r\n		file.width = 16\r\n		file.height = 16\r\n		params = style="vertical-align:middle;"\r\n		wrap = |&nbsp;\r\n	}\r\n\r\n	20 = TEXT\r\n	20.value = YIM\r\n}\r\ninput = HTML\r\ninput {\r\n	value = <input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###"  />\r\n}\r\n', 'a:8:{s:5:"label";a:1:{s:7:"default";s:3:"YIM";}s:8:"required";b:0;s:7:"private";b:0;s:4:"link";s:14:"tx_mmforum_yim";s:4:"type";s:6:"custom";s:4:"text";a:2:{s:6:"length";i:-1;s:8:"validate";s:4:"none";}s:5:"radio";a:1:{s:5:"value";a:1:{i:0;s:5:"Array";}}s:6:"select";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}}', 1);
INSERT INTO tx_mmforum_userfields (uid, pid, tstamp, crdate, cruser_id, sorting, deleted, hidden, label, config, meta, public) VALUES(15, 0, 1234712717, 1234712701, 1, 2, 0, 0, 'AIM', 'datasource = tx_mmforum_aim\r\nlabel = COA\r\nlabel {\r\n	10 = IMAGE\r\n	10 {\r\n		file = EXT:mm_forum/res/tmpl/default/img/default/buttons/icons/aim.png\r\n		file.width = 16\r\n		file.height = 16\r\n		params = style="vertical-align:middle;"\r\n		wrap = |&nbsp;\r\n	}\r\n\r\n	20 = TEXT\r\n	20.value = AIM\r\n}\r\ninput = HTML\r\ninput {\r\n	value = <input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###"  />\r\n}\r\n', 'a:8:{s:5:"label";a:1:{s:7:"default";s:3:"AIM";}s:8:"required";b:0;s:7:"private";b:0;s:4:"link";s:14:"tx_mmforum_aim";s:4:"type";s:6:"custom";s:4:"text";a:2:{s:6:"length";i:-1;s:8:"validate";s:4:"none";}s:5:"radio";a:1:{s:5:"value";a:1:{i:0;s:5:"Array";}}s:6:"select";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}}', 1);
INSERT INTO tx_mmforum_userfields (uid, pid, tstamp, crdate, cruser_id, sorting, deleted, hidden, label, config, meta, public) VALUES(16, 0, 1234712776, 1234712764, 1, 1, 0, 0, 'ICQ', 'datasource = tx_mmforum_icq\r\nlabel = COA\r\nlabel {\r\n	10 = IMAGE\r\n	10 {\r\n		file = EXT:mm_forum/res/tmpl/default/img/default/buttons/icons/icq.png\r\n		file.width = 16\r\n		file.height = 16\r\n		params = style="vertical-align:middle;"\r\n		wrap = |&nbsp;\r\n	}\r\n\r\n	20 = TEXT\r\n	20.value = ICQ\r\n}\r\ninput = HTML\r\ninput {\r\n	value = <input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###"  />\r\n}\r\n', 'a:8:{s:5:"label";a:1:{s:7:"default";s:3:"ICQ";}s:8:"required";b:0;s:7:"private";b:0;s:4:"link";s:14:"tx_mmforum_icq";s:4:"type";s:6:"custom";s:4:"text";a:2:{s:6:"length";i:-1;s:8:"validate";s:4:"none";}s:5:"radio";a:1:{s:5:"value";a:1:{i:0;s:5:"Array";}}s:6:"select";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}}', 1);
INSERT INTO tx_mmforum_userfields (uid, pid, tstamp, crdate, cruser_id, sorting, deleted, hidden, label, config, meta, public) VALUES(17, 0, 1234712802, 1234712789, 1, 0, 0, 0, 'Skype', 'datasource = tx_mmforum_skype\r\nlabel = COA\r\nlabel {\r\n	10 = IMAGE\r\n	10 {\r\n		file = EXT:mm_forum/res/tmpl/default/img/default/buttons/icons/skype.png\r\n		file.width = 16\r\n		file.height = 16\r\n		params = style="vertical-align:middle;"\r\n		wrap = |&nbsp;\r\n	}\r\n\r\n	20 = TEXT\r\n	20.value = Skype\r\n}\r\ninput = HTML\r\ninput {\r\n	value = <input type="text" name="###USERFIELD_NAME###" value="###USERFIELD_VALUE###"  />\r\n}\r\n', 'a:8:{s:5:"label";a:1:{s:7:"default";s:5:"Skype";}s:8:"required";b:0;s:7:"private";b:0;s:4:"link";s:16:"tx_mmforum_skype";s:4:"type";s:6:"custom";s:4:"text";a:2:{s:6:"length";i:-1;s:8:"validate";s:4:"none";}s:5:"radio";a:1:{s:5:"value";a:1:{i:0;s:5:"Array";}}s:6:"select";a:1:{s:5:"value";a:1:{i:0;a:1:{s:7:"content";s:0:"";}}}}', 1);