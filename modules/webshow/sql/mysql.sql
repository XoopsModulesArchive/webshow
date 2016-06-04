#
## Table structure for table `webshow_cat`
#

CREATE TABLE webshow_cat (
  cid int(5) unsigned NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  cattitle varchar(50) NOT NULL,
  catdesc text NOT NULL,
  imgurl varchar(150) NOT NULL,
  catbody text NOT NULL,
  PRIMARY KEY  (cid),
  KEY pid (pid)
) TYPE=MyISAM;

#
## Table structure for table `webshow_links`
#

CREATE TABLE webshow_links (
  lid int(11) unsigned NOT NULL auto_increment,
  title varchar(100) NOT NULL default '',
  cid int(5) unsigned NOT NULL default '0',
  submitter int(11) unsigned NOT NULL default '0',
  player tinyint(2) NOT NULL default '1',
  srctype varchar(250) NOT NULL,
  listtype varchar(6) NOT NULL,
  listurl varchar(255) NOT NULL,
  listcache varchar(250) NOT NULL default '',
  cachetime int(11) unsigned NOT NULL default '604800',
  status tinyint(2) NOT NULL default '0',
  date int(10) NOT NULL default '0',
  published int(10) unsigned NOT NULL default '0',
  expired int(10) unsigned NOT NULL default '0',
  entryinfo  varchar(250) NOT NULL default 'dsc logo cred stat rate sbmt pop tag feed site down',  
  entryperm varchar(64) NOT NULL default '',
  entrydownperm varchar(64) NOT NULL default '',
  logourl varchar(255) NOT NULL,
  url varchar(250) NOT NULL default '',
  hits int(11) unsigned NOT NULL default '0',
  views int(11) unsigned NOT NULL default '0',
  rating double(6,4) NOT NULL default '0.0000',
  votes int(11) unsigned NOT NULL default '0',
  allowcomments tinyint(2) NOT NULL default '1',
  comments int(11) unsigned NOT NULL default '0',
  credit1 varchar(100) NOT NULL default '',
  credit2 varchar(100) NOT NULL default '',
  credit3 varchar(100) NOT NULL default '',
  chain int(11) NOT NULL default '0',
  PRIMARY KEY  (lid),
  KEY status (status),
  KEY title (title(100))
) TYPE=MyISAM;

#
## Table structure for table `webshow_text`
#

CREATE TABLE webshow_text (
  lid int(11) unsigned NOT NULL default '0',
  description text NOT NULL,
  bodytext text NOT NULL,
  KEY lid (lid)
) TYPE=MyISAM;

#
## Table structure for table `webshow_mod`
#

CREATE TABLE webshow_mod (
  requestid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  cid int(5) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(250) NOT NULL default '',
  srctype varchar(4) NOT NULL default '',
  listurl varchar(250) NOT NULL default '',
  logourl varchar(250) NOT NULL default '',
  description text NOT NULL,
  bodytext text NOT NULL,
  player tinyint(2) NOT NULL default '1',
  chain int(11) NOT NULL default '0',
  modifysubmitter int(11) unsigned NOT NULL default '0',
  credit1 varchar(100) NOT NULL default '',
  credit2 varchar(100) NOT NULL default '',
  credit3 varchar(100) NOT NULL default '',
  ws_tag varchar(250) NOT NULL default '',
  PRIMARY KEY  (requestid)
) TYPE=MyISAM;

#
## Table structure for table `webshow_flashvar`
#

CREATE TABLE webshow_flashvar (
  lid int(11) unsigned NOT NULL,
  pid tinyint(2) unsigned NOT NULL,
  mediatype varchar(12) NOT NULL,
  start tinyint(2) NOT NULL default '1',
  shuffle tinyint(2) NOT NULL default '0',
  replay tinyint(2) NOT NULL default '0',
  link varchar(250) NOT NULL default '0',
  linktarget varchar(24) NOT NULL default '_blank',
  showicons tinyint(2) NOT NULL default '1',
  stretch varchar(6) NOT NULL default 'false',
  showeq tinyint(2) NOT NULL default '0',
  rotatetime smallint(3) NOT NULL default '5',
  shownav tinyint(2) NOT NULL default '0',
  transition varchar(24) NOT NULL default '0',
  thumbslist tinyint(2) NOT NULL default '0',
  captions varchar(250) NOT NULL default '',
  enablejs tinyint(2) NOT NULL default '0',
  playerlogo varchar(250) NOT NULL default '',
  audio varchar(250) NOT NULL default '',
  PRIMARY KEY  (lid),
  KEY mediatype (mediatype(12))
) TYPE=MyISAM;

#
## Table structure for table `webshow_player`
#

CREATE TABLE webshow_player (
  playerid int(11) unsigned NOT NULL auto_increment,
  pid int(11) unsigned NOT NULL default '0',
  playertitle varchar(24) NOT NULL default '',
  styleoption tinyint(2) NOT NULL default '0', 
  bgcolor varchar(7) NOT NULL default '',
  backcolor varchar(7) NOT NULL default '',
  frontcolor varchar(7) NOT NULL default '',
  lightcolor varchar(7) NOT NULL default '',
  width int(4) NOT NULL default '320',
  height int(4) NOT NULL default '240',
  displaywidth int(4) NOT NULL default '320',
  displayheight int(4) NOT NULL default '180',
  showdigits tinyint(2) NOT NULL default '1',
  showfsbutton tinyint(2) NOT NULL default '1',
  scroll tinyint(2) NOT NULL default '0',
  largecontrol tinyint(2) NOT NULL default '0',
  searchbar tinyint(2) NOT NULL default '0',
  searchlink varchar(250) NOT NULL default '',  
  PRIMARY KEY  (playerid),
  KEY pid (pid),
  KEY playertitle (playertitle(24))
) TYPE=MyISAM;

INSERT INTO `webshow_player` (
`playerid` ,
`pid` ,
`playertitle` ,
`styleoption` ,
`bgcolor` ,
`backcolor` ,
`frontcolor` ,
`lightcolor` ,
`width` ,
`height` ,
`displaywidth` ,
`displayheight` ,
`showdigits` ,
`showfsbutton` ,
`scroll` ,
`largecontrol`,
`searchbar` ,
`searchlink` 
)
VALUES ('1', '0', 'default', '1', '', '', '', '', '320', '240', '320', '180', '1', '1', '0', '0', '0', '');

#
## Table structure for table `webshow_theme`
#

CREATE TABLE webshow_theme (
  themeid int(11) unsigned NOT NULL auto_increment,
  themetitle varchar(24) NOT NULL default '',
  bgcolor varchar(7) NOT NULL default '',
  backcolor varchar(7) NOT NULL default '',
  frontcolor varchar(7) NOT NULL default '',
  lightcolor varchar(7) NOT NULL default '',
  PRIMARY KEY  (themeid),
  KEY themetitle (themetitle(24))
) TYPE=MyISAM;

#
## Table structure for table `webshow_broken`
#
CREATE TABLE webshow_broken (
  reportid int(5) NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  sender int(11) unsigned NOT NULL default '0',
  ip varchar(20) NOT NULL default '',
  rpttype int(5) NOT NULL default '0',
  rptname varchar(36) NOT NULL default '',
  rptcmt varchar(250) NOT NULL default '',
  PRIMARY KEY  (reportid),
  KEY lid (lid),
  KEY sender (sender),
  KEY ip (ip)
) TYPE=MyISAM;

#
## Table structure for table `webshow_votedata`
#

CREATE TABLE webshow_votedata (
  ratingid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) unsigned NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname)
) TYPE=MyISAM;


