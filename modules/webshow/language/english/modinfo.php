<?php
// $Id: modinfo.php,v.50 2007/03/01 19:59:00 tcnet Exp $
// Module Info
if (!defined('XOOPS_ROOT_PATH')){ exit(); }
// The name of this module
define("_MI_WEBSHOW_NAME","Web Show");

// A brief description of this module
define("_MI_WEBSHOW_DESC","Our media directory plays audio, video, images and animations.");

// Names of blocks for this module (Not all module has blocks)
define("_MI_WEBSHOW_BNAME1","Media Links");
define("_MI_WEBSHOW_BNAME2","Play It");
define("_MI_WEBSHOW_BNAME3","Billboard");
define("_MI_WEBSHOW_BNAME4","Tag Cloud");
define("_MI_WEBSHOW_BNAME5","Top Tags");
define("_MI_WEBSHOW_BNAME6","Category Menu");
define("_MI_WEBSHOW_BNAME7","Media Links Rollover");

// Sub menu titles
define("_MI_WEBSHOW_SMNAME1","Submit");
define("_MI_WEBSHOW_SMNAME2","Category");
define("_MI_WEBSHOW_SMNAME3","Top Ten");

// Names of admin menu items
define("_MI_WEBSHOW_ADMENU1","Index");
define("_MI_WEBSHOW_ADMENU2","Category");
define("_MI_WEBSHOW_ADMENU3","Media");
define("_MI_WEBSHOW_ADMENU4","Flash");
define("_MI_WEBSHOW_ADMENU5","Player");
define("_MI_WEBSHOW_ADMENU6","Permissions");
define("_MI_WEBSHOW_ADMENU7","Submissions");
define("_MI_WEBSHOW_ADMENU8","Reports");
define("_MI_WEBSHOW_ADMENU9","Modify Request");
define("_MI_WEBSHOW_ADMENU10","Help");
define("_MI_WEBSHOW_ADMENU11","Module");

// Title of config items
define('_MI_WEBSHOW_POPULAR','Popular Button');
define('_MI_WEBSHOW_POPULARDSC','Select the number of hits for media to be marked as popular.');
define('_MI_WEBSHOW_NEWLINKS','Index Listing Count');
define('_MI_WEBSHOW_NEWLINKSDSC','Enter the number of listings to display on the index page. Best if listingcount/columncount = whole number.');
define('_MI_WEBSHOW_PERPAGE','Catalog Listing Count ');
define('_MI_WEBSHOW_PERPAGEDSC','Enter the number of listings to display on the catalog pages.   Best if listingcount/columncount = whole number.');
define('_MI_WEBSHOW_LOGOPATH','Entry Logo Path');
define('_MI_WEBSHOW_LOGOPATHDSC','Enter the path to the logo images. This folder must have write permission.');
define('_MI_WEBSHOW_LOGOWIDTH','Maximum Entry Logo Width');
define('_MI_WEBSHOW_LOGOWIDTHDSC','Maximum allowed dimension of entry logo.');
define('_MI_WEBSHOW_LISTPATH','Path to Cached Playlist');
define('_MI_WEBSHOW_LISTPATHDSC','This folder must have write permission.');
define('_MI_WEBSHOW_MEDIAPATH','Media Directory Name');
define('_MI_WEBSHOW_MEDIAPATHDSC','Must be located in the webshow directory.');
define('_MI_WEBSHOW_MODDESC','Index Page Description');
define('_MI_WEBSHOW_MODDESC_DSC','Enter one short phrase for the meta description tag and index page header.');
define('_MI_WEBSHOW_CAPTCHA','CAPTCHA Confirmation');
define('_MI_WEBSHOW_CAPTCHADSC','Select Guest or All users to use CAPTCHA in the submit forms. Get Frameworks for Xoops 2.0.* and 2.2.*');
define('_MI_WEBSHOW_TAGS','Word Tags');
define('_MI_WEBSHOW_TAGSDSC','Requires TAGS Module by phppp.<br />Select Yes to turn on Tags.');
define('_MI_WEBSHOW_TEXTEDITOR','Text Editor Select');
define('_MI_WEBSHOW_TEXTEDITORDSC','Select a text editor.  Get Frameworks for Xoops 2.0.* and 2.2.*');
define('_MI_WEBSHOW_KEYCOUNT','Metatag Keyword Quantity');
define('_MI_WEBSHOW_KEYCOUNTDSC', '');
define('_MI_WEBSHOW_KEYLIMIT','Minimum Keyword Length');
define('_MI_WEBSHOW_KEYLIMITDSC','');
define('_MI_WEBSHOW_KEYCOMMON','Keyword Black List ');
define('_MI_WEBSHOW_KEYCOMMONDSC','Enter words to blacklist from the meta keyword tags');
define('_MI_WEBSHOW_LOG','Callback Log');
define('_MI_WEBSHOW_LOGDSC','Use the player API to log events.');
define('_MI_WEBSHOW_NOLOGO','Blank Logo in Catalog Views');
define('_MI_WEBSHOW_NOLOGODSC','If the entry logo is blank what should I display in the catalog pages?');
define('_MI_WEBSHOW_NOLOGOSTOCK','Use the stock logo');
define('_MI_WEBSHOW_NOLOGOBLANK','Leave it blank');
define('_MI_WEBSHOW_DESCLENGTH','Limit Description Length');
define('_MI_WEBSHOW_DESCLENGTHDSC','Limit the length of the description in the catalog pages.  Max 250 characters.');
define('_MI_WEBSHOW_COLUMN','Column Count');
define('_MI_WEBSHOW_COLUMNDSC','Number of columns in catalog pages.');

//** Show Info Catalog View
define('_MI_WEBSHOW_SHOWINFO','Catalog Page Contents');
define('_MI_WEBSHOW_SHOWINFO_DSC','Select items to display in the catalog pages.');
define('_MI_WEBSHOW_PLAYER','Player');
define('_MI_WEBSHOW_DESCRIPTION','Description');
define('_MI_WEBSHOW_LOGOIMAGE','Entry Logo');
define('_MI_WEBSHOW_CREDITS','Credits');
define('_MI_WEBSHOW_STATISTICS','Statistics');
define('_MI_WEBSHOW_RATE','Rate');
define('_MI_WEBSHOW_SUBMITTER','Submitter');
define('_MI_WEBSHOW_POPUP','Pop Up Button');

//** Show Info 2 Singlelink view
define('_MI_WEBSHOW_SHOWINFO2','Media Page Contents');
define('_MI_WEBSHOW_SHOWINFO2_DSC','Select items to display on the medias page.');
define('_MI_WEBSHOW_SHOWTAGS','Tag Bar');
define('_MI_WEBSHOW_DOWNLINK','Download Link/Button');
define('_MI_WEBSHOW_SITELINK','Site Link/Button');
define('_MI_WEBSHOW_FEEDLINK','Feed Link/Button');
define('_MI_WEBSHOW_FEEDDATA','Feed Data');
define('_MI_WEBSHOW_TRACKDATA','Track Data');
define('_MI_WEBSHOW_ID3','ID3 Tag Data');

//** Code Box Contents
define('_MI_WEBSHOW_CODEBOX','Display Code Boxes');
define('_MI_WEBSHOW_CODEBOX_DSC','Select link and embed codes to display.');
define('_MI_WEBSHOW_PERMALINK','Perma Link URL');
define('_MI_WEBSHOW_SITECODE','Web Site URL');
define('_MI_WEBSHOW_FEEDCODE','Web Feed URL');
define('_MI_WEBSHOW_EMBED','Embed Code');
define('_MI_WEBSHOW_EMBEDJS','Javascript Code');

// Text for notifications
define('_MI_WEBSHOW_GLOBAL_NOTIFY', 'Global');
define('_MI_WEBSHOW_GLOBAL_NOTIFYDSC', 'Global links notification options.');

define('_MI_WEBSHOW_CATEGORY_NOTIFY', 'Category');
define('_MI_WEBSHOW_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current category.');

define('_MI_WEBSHOW_LINK_NOTIFY', 'Media Entry');
define('_MI_WEBSHOW_LINK_NOTIFYDSC', 'Notification options that aply to the current media entry.');

define('_MI_WEBSHOW_GLOBAL_NEWCATEGORY_NOTIFY', 'New Category');
define('_MI_WEBSHOW_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notify me when a new category is created.');
define('_MI_WEBSHOW_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Receive notification when a new category is created.');
define('_MI_WEBSHOW_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New category');

define('_MI_WEBSHOW_GLOBAL_LINKMODIFY_NOTIFY', 'Entry Modification Requested');
define('_MI_WEBSHOW_GLOBAL_LINKMODIFY_NOTIFYCAP', 'Notify me of any entry modification request.');
define('_MI_WEBSHOW_GLOBAL_LINKMODIFY_NOTIFYDSC', 'Receive notification when any entry modification request is submitted.');
define('_MI_WEBSHOW_GLOBAL_LINKMODIFY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Entry Modification Requested');

define('_MI_WEBSHOW_GLOBAL_LINKBROKEN_NOTIFY', 'Broken Link Submitted');
define('_MI_WEBSHOW_GLOBAL_LINKBROKEN_NOTIFYCAP', 'Notify me of any broken link report.');
define('_MI_WEBSHOW_GLOBAL_LINKBROKEN_NOTIFYDSC', 'Receive notification when any broken link report is submitted.');
define('_MI_WEBSHOW_GLOBAL_LINKBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Broken Link Reported');

define('_MI_WEBSHOW_GLOBAL_LINKSUBMIT_NOTIFY', 'New Media Entry Submitted');
define('_MI_WEBSHOW_GLOBAL_LINKSUBMIT_NOTIFYCAP', 'Notify me when any new media entry is submitted (awaiting approval).');
define('_MI_WEBSHOW_GLOBAL_LINKSUBMIT_NOTIFYDSC', 'Receive notification when any new media entry is submitted (awaiting approval).');
define('_MI_WEBSHOW_GLOBAL_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New media entry submitted');

define('_MI_WEBSHOW_GLOBAL_NEWLINK_NOTIFY', 'New Media Entry');
define('_MI_WEBSHOW_GLOBAL_NEWLINK_NOTIFYCAP', 'Notify me when any new media entry is posted.');
define('_MI_WEBSHOW_GLOBAL_NEWLINK_NOTIFYDSC', 'Receive notification when any new media entry is posted.');
define('_MI_WEBSHOW_GLOBAL_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New media entry');

define('_MI_WEBSHOW_CATEGORY_LINKSUBMIT_NOTIFY', 'New Media Entry Submitted');
define('_MI_WEBSHOW_CATEGORY_LINKSUBMIT_NOTIFYCAP', 'Notify me when a new media entry is submitted (awaiting approval) to the current category.');
define('_MI_WEBSHOW_CATEGORY_LINKSUBMIT_NOTIFYDSC', 'Receive notification when a new link is submitted (awaiting approval) to the current category.');
define('_MI_WEBSHOW_CATEGORY_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New media entry submitted in category');

define('_MI_WEBSHOW_CATEGORY_NEWLINK_NOTIFY', 'New Media Entry');
define('_MI_WEBSHOW_CATEGORY_NEWLINK_NOTIFYCAP', 'Notify me when a new media entry is posted to the current category.');
define('_MI_WEBSHOW_CATEGORY_NEWLINK_NOTIFYDSC', 'Receive notification when a new link is posted to the current category.');
define('_MI_WEBSHOW_CATEGORY_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New media entry in category');

define('_MI_WEBSHOW_LINK_APPROVE_NOTIFY', 'Media Entry Approved');
define('_MI_WEBSHOW_LINK_APPROVE_NOTIFYCAP', 'Notify me when this media entry is approved.');
define('_MI_WEBSHOW_LINK_APPROVE_NOTIFYDSC', 'Receive notification when this media entry is approved.');
define('_MI_WEBSHOW_LINK_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Media Entry approved');
?>
