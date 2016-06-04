<?php
// $Id: admin.php,v.67 2009/09/27 19:59:00 tcnet Exp $ //
//%%%%%%		Module Name 'WebShow'		%%%%%
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //

defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
* General Terms
*/
define("_WSA_ADMIN","WebShow Administration");
define("_WSA_EDITOR","Editor");
define("_WSA_GENERALSET","Preferences");
define("_WSA_GOTOMOD","Go to module");
define("_WSA_JWFLASH","This module uses <a href=\"http://www.jeroenwijering.com/?item=Flash_Media_Player\" target=\"_blank\">Jeroen Wijerings Flash Media Player</a> which is licensed under a <a href=\"http://creativecommons.org/licenses/by-nc-sa/2.0/\" target=\"_blank\">Creative Commons Attribution-NonCommercial-ShareAlike 2.0</a> license.  A <a href=\"http://www.jeroenwijering.com/?order=form\" target=\"_blank\">commercial license</a> must be purchased from Jeroen Wijering for commercial use.");
define("_WSA_PAGE_VIEW","Page");
define("_WSA_PAGENAV","Page: ");
define("_WSA_RESTORE","Restore Default");
define("_WSA_SELECTMEDIA","Select Media");
define("_WSA_WEBSHOWCONF","WebShow Module Configuration");
define("_WSA_WEBSHOW_DISCLAIMER","<b>Disclaimer: </b>By installing and using the WebShow module you agree that you will not use it to display, distribute or syndicate a third party's copyright protected media without the owner's permission.  The WebShow software is not to be used to display or syndicate illegal or copyright protected media.");
define("_WSA_WEBSHOW_DSC","<b>Introduction: </b>This module plays Flash media files (swf, flv, jpg, png, gif, mp3) enclosed in an atom, xspf or rss web feed.  It will filter a media directory by file type to automatically generate and save an xspf playlist.  Plays single media files from a url.  Custom player appearance and behavior.");
define("_WSA_WEBSHOW_GETSTARTED","<b>Get Started</b><br />Configure Module Preferences<br /><a href=\"category.php?op=newCat\" target=\"_self\" title=\"Set up a category\">Set up a category.</a><br /><a href=\"index.php?op=newLink\" target=\"_self\" title=\"Playlist Editor\">Add a New Entry.</a><br /><a href=\"player.php?playerid=1&op=modPlayer&lid=1\" target=\"_self\" title=\"Player Editor\">Customize the default player.</a>");

/**
* Index
*/
define("_WSA_AVAILABLE","Available");
define("_WSA_CREATETHEDIR","Create the Directory.");
define("_WSA_NOTAVAILABLE","Not Available");
define("_WSA_PATH_LOGO","Logo Path");
define("_WSA_PATH_MEDIA","Media Path");
define("_WSA_PATH_PLAYLIST","Playlist Path");
define("_WSA_PATH_FRAMEWORKS","Frameworks");
define("_WSA_PATH_XOOPSEDITOR","Xoopseditor");
define("_WSA_PATH_TAGMODULE","Tag Module");
define("_WSA_SETMPERM","Set the directory permissions.");

/**
* Log File
*/
define("_WSA_EMPTYLOG","Empty Log File");
define("_WSA_EVENT","Event");
define("_WSA_LINENUMBER","Line");
define("_WSA_LOGFILE","View Log File");
define("_WSA_REFERER","Referer");

/**
* MANAGER MENU TABLE
*/

define("_WSA_ACTION","ACTION");
define("_WSA_ADMINTABLE","WebShow Administration Table");
define("_WSA_ENTRYCOUNT","Entry Count");
define("_WSA_GETID","GET ID");
define("_WSA_ID","ID");
define("_WSA_OFFLINE","Off Line");
define("_WSA_ONLINE","On Line");
define("_WSA_PAGEHITS","Page Hits");
define("_WSA_RANK","Rank");
define("_WSA_STATUS","Status");
define("_WSA_STATUSREPORT","Status Report");
define("_WSA_STATUSABUSE","Abuse");
define("_WSA_STATUSAUTO","Auto Publish");
define("_WSA_STATUSNOCACHE","Error: No Cache - Confirm that the media exist at the given location and refresh the playlist cache");
define("_WSA_STATUSNOMEDIAURL","Media Location Error - Confirm that the media exist at the given location");
define("_WSA_STATUSEMPTYDIR","The media directory is empty");
define("_WSA_STATUSNODIR","The media directory does not exist");
define("_WSA_STATUSEXPIRED","Expired");
define("_WSA_STATUSEXPIRATION","Expiration");
define("_WSA_STATUSWAITING","Waiting Approval");
define("_WSA_VIEWS","Media Views");
define("_WSA_VOTE","Vote");

/**
* Category Editor
*/
define("_WSA_ADDNEWCAT","Add New Category");
define("_WSA_ADDSUB","Add a Subcategory");
define("_WSA_CATCONF","Category Configuration");
define("_WSA_CATDELETED","Category Deleted");
define("_WSA_CATDESC","Category Description");
define("_WSA_CATID","Cat ID");
define("_WSA_CATIMG_DSC","Category images are kept in %s"); // sentence ends with cat image path.
define("_WSA_CATIMGSELECT","Select a category image");
define("_WSA_CATIMGUPLOAD","Upload a category Image");
define("_WSA_CATMGMT","Category Manager");
define("_WSA_CATPATH","Category Path");
define("_WSA_CATTITLE","Category Title");
define("_WSA_MODCAT","Modify a Category");
define("_WSA_NEWCATADDED","New Category Added Successfully!");
define("_WSA_NOCAT","Please add a category first.");
define("_WSA_PARENT","Parent Category");
define("_WSA_PARENT_DSC","Select a parent category.");
define("_WSA_SUBCAT","Subcategory");
define("_WSA_WARNING","WARNING: Are you sure you want to delete this Category and ALL of its Subcategories, Entries and Comments?");

/**
* Media Entry Editor
*/
define("_WSA_LASTMODIFY","Last Modification: ");
define("_WSA_LINKCONF","Entry Configuration");
define("_WSA_LINKID","Entry ID: ");
define("_WSA_LINKMGMT","Media Manager");
define("_WSA_STATUSSWITCH","Status Switch: ");

/**
* Media Submit Form
*/
define("_WSA_ADDMEDIA","Add Media Entry");
define("_WSA_ALLOWCOMMENTS","Allow Comments");
define("_WSA_BLOCKDESCRIPTION","Block Description");
define("_WSA_BLOCKDESCRIPTION_DSC","Enter one sentence for use in the modules catalog, blocks and meta description.");
define("_WSA_CATEGORY","Category");
define("_WSA_CATEGORY_DSC","Please select a category from the list.");
define("_WSA_CREATED","Created");
define("_WSA_CREDITS","Credits");
define("_WSA_CREDIT1","Artist");
define("_WSA_CREDIT2","Album");
define("_WSA_CREDIT3","Label");
define("_WSA_DOWNLOAD","Download");
define("_WSA_EMBED","Embed Source");
define("_WSA_EMBED_DSC","Select an embed site from our list.");
define("_WSA_EMBEDLOGO","Embed Logo Found");
define("_WSA_EMBEDLOGO_DSC","The embed source will provide a thumbnail.");
define("_WSA_ENTRYINFO","Show Information");
define("_WSA_ENTRYINFO_DSC","Select the information to be displayed in the page view.");
define("_WSA_FEEDDATA","Feed Data");
define("_WSA_LISTURL","Location: ");
define("_WSA_MODENTRY","Modify Entry");
define("_WSA_MEDIAENTRY","Media Entry");
define("_WSA_MEDIATITLE","Media Title");
define("_WSA_NEWENTRYEDITOR","New Entry Editor");
define("_WSA_NOTIFYAPPROVE", "Notify me when this entry is approved");
define("_WSA_OPTIONS","Options: ");
define("_WSA_POPUP","PopUp");
define("_WSA_SETCREATEDATETIME","Reset Created Time ");
define("_WSA_SETDATETIME","Set Auto Publish Time ");
define("_WSA_SETEXPDATETIME","Set Expiration Time ");
define("_WSA_SITEURL","Website URL: ");
define("_WSA_STATISTICS","Statistics");
define("_WSA_SUBMITTER","Submitter");
define("_WSA_SUBMITFORMHEAD","Add New Media");
define("_WSA_SUBMITONCE","Submit your media link only once.");
define("_WSA_SUBMITPAGEHEAD","Media Submit Form");
define("_WSA_SUBMITTEXT","Submit links that point to your Flash media (flv, swf, mp3, jpg, gif, png) files, streams, podcast feeds or shared embed sites.  We do not accept media uploads at the moment.");
define("_WSA_SUBMIT_TOS","Review and abide by our Terms of Service. Do not contribute other's copyrighted materials without permission from the owner. Your User name and IP will be attached to this submission.");
define("_WSA_SUBMIT_FEED","Enter the link to a playlist feed in RSS, ATOM or XSPF xml file format.");
define("_WSA_SUBMIT_SINGLE","Enter an URL pointing to a video (.flv), audio (.mp3) or a image (jpg, gif, png) file.  We do not accept uploads.  Upload to a media storage site and publish the link here.");
define("_WSA_SUBMIT_EMBED","Select a media host to embed a file here.  Carefully follow the embed instructions on the submit form.");
define("_WSA_SUBMIT_DIR","Use FTP to upload a directory of media files.  Then enter the file type and directory name here.  The software will read the directory contents and create a playlist.");
define("_WSA_TAGS","Tags");
define("_WSA_TAGS_DSC","Tag this entry with keywords.");
define("_WSA_TEXTBODY","Text Body: ");
define("_WSA_TRACKDATA","Track Data");
define("_WSA_WEBSITE","Site");
define("_WSA_WEBFEED","Feed");

//** LOGO IMAGES
define("_WSA_IMAGEURL","Image URL: ");
define("_WSA_IMGURL","Image URL: ");
define("_WSA_LOGO_CURRENT","Current Logo: ");
define("_WSA_LOGO_FETCH","The logo has been fetched.");
define("_WSA_LOGO_FETCHFAIL","The logo fetch has failed.");
define("_WSA_LOGO_DELETED","Logo Deleted");
define("_WSA_LOGOBLANK","Leave blank for no logo.");
define("_WSA_LOGODIM","Maximum dimension:");
define("_WSA_LOGOIMAGE","Logo Image");
define("_WSA_LOGOIMAGE_DSC","Use one of these methods to designate an image.");
define("_WSA_LOGOPATH_DSC","Image Path: ");
define("_WSA_LOGOSELECT","Select a Stock Logo: ");
define("_WSA_LOGOSIZE","Maximum file size:");
define("_WSA_LOGOUPLOAD","Upload a Logo: ");
define("_WSA_LOGOURL","Logo from URL: ");
define("_WSA_LOGOURL_DSC","Enter a url pointing to the image.");

//** Player
define("_WSA_PLAYER","Player");
define("_WSA_PLAYERLOGO","Player Logo Image: ");
define("_WSA_PLAYERLOGO_DSC","Player Logos are in %s");
define("_WSA_PLAYERLOGOSELECT","Select a player logo:");
define("_WSA_PLAYERLOGOUPLOAD","Upload a player logo: ");
define("_WSA_HEIGHT","Height");
define("_WSA_WIDTH","Width");

//** Image Rotator
define("_WSA_TRANS_BGFADE","Background Fade");
define("_WSA_TRANS_BLOCKS","Blocks");
define("_WSA_TRANS_BUBBLES","Bubbles");
define("_WSA_TRANS_CIRCLES","Circles");
define("_WSA_TRANS_FADE","Fade");
define("_WSA_TRANS_FLASH","Flash");
define("_WSA_TRANS_FLUIDS","Fluids");
define("_WSA_TRANS_LINES","Lines");
define("_WSA_TRANS_RANDOM","Random");
define("_WSA_TRANS_SLOWFADE","Slow Fade");

//** Media Source (listtype)
define("_WSA_LISTTYPE","Media Source: "); 
define("_WSA_LISTTYPE_DIR","Media Directory");
define("_WSA_LISTTYPE_DIR_DSC","Enter your folder name here. (No slash)  ");
define("_WSA_LISTTYPE_DIR_LOC","FTP upload your media folder to ");
define("_WSA_LISTTYPE_DSC","Where is the media?");
define("_WSA_LISTTYPE_EMBED","Embed Link");
define("_WSA_LISTTYPE_EMBED_DSC","Enter the media ID Number");
define("_WSA_LISTTYPE_FEED","Web Feed");
define("_WSA_LISTTYPE_FEED_DSC","Enter the web feed URL.");
define("_WSA_LISTTYPE_SINGLE","Single File");
define("_WSA_LISTTYPE_SINGLE_DSC","Enter a URL pointing to the media file");

//** Media File Types
define("_WSA_SRC","Media Type: ");
define("_WSA_SRC_IMAGE","Image GIF, JPG, PNG");
define("_WSA_SRC_FLV","Video FLV");
define("_WSA_SRC_MP3","Audio MP3");
define("_WSA_SRC_MP4","Video MP4");
define("_WSA_SRC_SWF","Flash SWF");
define("_WSA_SRC_ALLMEDIA","All Flash Media");
define("_WSA_SRC_DSC","What is the media file type?");
define("_WSA_IMG","Image");
define("_WSA_FLV","FLV");
define("_WSA_MP3","MP3");
define("_WSA_MP4","MP4");
define("_WSA_SWF","SWF");
define("_WSA_ALLMEDIA","All Media Types");

//** Cache Playlist (listcache)
define("_WSA_LISTCACHE","Playlist Cache");
define("_WSA_LISTCACHE_CREATE","A playlist will be created when this form is submitted.");
define("_WSA_LISTCACHE_DELETED","Cached Playlist Deleted");
define("_WSA_LISTCACHE_DSC","Playlist Location:");
define("_WSA_LISTCACHE_ERROR","Error creating cache file");
define("_WSA_LISTCACHE_EXPIRED","Playlist Expired.");
define("_WSA_LISTCACHE_FETCH","This webfeed has been fetched and cached.");
define("_WSA_LISTCACHE_FETCH_ERROR","Failed to fetch the web feed. Please confirm the web feed location and refresh the cache.");
define("_WSA_LISTCACHE_FILE","Cached Playlist File");
define("_WSA_LISTCACHE_NAME","Cached Playlist Filename");
define("_WSA_LISTCACHE_NEW","A new playlist has been cached");
define("_WSA_LISTCACHE_PATH","Playlist Path");
define("_WSA_LISTCACHE_PATH_DSC","Path to playlist cache ");
define("_WSA_LISTCACHE_REFRESH","Refresh Playlist Cache");
define("_WSA_LISTCACHE_REFRESHDSC","Refresh the cached playlist now.");
define("_WSA_LISTCACHE_TIME","Cache Time");
define("_WSA_LISTCACHE_TIME_DSC","How often should this playlist be refreshed?");

/**
* Media Entry Flash Variables
*/
define("_WSA_AUDIO","Audio Program");
define("_WSA_AUDIODSC","Enter an URL pointing to a mp3 file to add audio to a slide show or second audio to other media.");
define("_WSA_CAPTIONS","Captions URL: ");
define("_WSA_CAPTIONSDSC","Enter a url pointing to a caption file or leave blank. For playlist entries, leave this blank and add caption urls to the playlist.");
define("_WSA_ENABLEJS","Enable Javascript:");
define("_WSA_ENABLEJSDSC","Select yes to activate javascript functions.<br />See include/mplay.inc.js file for options.");
define("_WSA_FALSE","False");
define("_WSA_FIT","Fit");
define("_WSA_FLASHVARS","Flash Variables");
define("_WSA_FLASHVARS_DSC","Set the Flash Media Player variables. The default settings work well for most situations");
define("_WSA_FVCONFIG","Configure Default");
define("_WSA_FVCONFIG_DSC","Configure the default settings for the Flash Player Variables");
define("_WSA_IMAGES_ONLY","Image Only Shows");
define("_WSA_LINKDIS","Screen Hyperlink: ");
define("_WSA_LINKDISDSC","With Screen Link Off a click on screen controls the media. Else, select a webpage to open. ");
define("_WSA_LINKTARGET","Link Target: ");
define("_WSA_LINKTARGETDSC","This is the target frame in which the link and fullscreen pages will show up.");
define("_WSA_MODULEPAGE","Module Page");
define("_WSA_MP3_ONLY","MP3 Only");
define("_WSA_NONE","None");
define("_WSA_REPEAT","Repeat: ");
define("_WSA_REPEATDSC","Continuous play.");
define("_WSA_ROTATETIME","Image Rotation Time: ");
define("_WSA_ROTATETIMEDSC","Rotate time in seconds.");
define("_WSA_SCREENLINKOFF","Screen Link Off");
define("_WSA_SHOWEQ","Show Animation: ");
define("_WSA_SHOWEQDSC","Display a (fake) spectrum analyzer.");
define("_WSA_SHOWICONS","Activity Icons:");
define("_WSA_SHOWICONSDSC","Display Activity and Play icons in Player.");
define("_WSA_SHOWNAV","Show Navigation: ");
define("_WSA_SHOWNAVDSC","Show the control bar.");
define("_WSA_SHUFFLE","Shuffle: ");
define("_WSA_SHUFFLEDSC","Shuffle playlist items");
define("_WSA_START","Auto Start: ");
define("_WSA_STARTDSC","Yes will automatically start the player.");
define("_WSA_STRETCH","Stretch Image/Video: ");
define("_WSA_STRETCHDSC","False = Scale proportionally to fit the display.<br />Fit = Scale disproportionally to exactly fit the display.<br />True = Scale proportionally to fill the display.<br />None = Original size.<br />");
define("_WSA_THUMB","Thumbnails: ");
define("_WSA_THUMBDSC","Set this to yes if you want to show thumbnails of  images in the playlist. Not used for Slide Show Player");
define("_WSA_TRANS_ROTATEOFF","Slide Show Player Off");
define("_WSA_TRANSITION","Slide Show Transition: ");
define("_WSA_TRANSITIONDSC","Selecting a transition will switch Media Player to Slide Show.");
define("_WSA_TRUE","True");

/**
* Player Editor
*/
define("_WSA_ADDNEWPLAYER","Add New Player");
define("_WSA_CLONE","Clone");
define("_WSA_CSS_MISSING","Missing Property! Please edit CSS.");
define("_WSA_CSS_BGCLR","body { background-color: # }");
define("_WSA_CSS_BKCLR","itemHead { background-color: # }");
define("_WSA_CSS_FTCLR","itemHead { color: # }");
define("_WSA_CSS_LTCLR","a:hover { color: # }");
define("_WSA_MODPLAYER","Modify Player");
define("_WSA_NEWPLAYER","New Player");
define("_WSA_NEWPLAYERADDED","New Player Added");
define("_WSA_PLAYERCONF","Player Configuration");
define("_WSA_PLAYERDELETED","Player deleted");
define("_WSA_PLAYERID","Player ID: ");
define("_WSA_PLAYERMGMT","Player Manager");
define("_WSA_PLAYERMODIFIED","Player Modified");
define("_WSA_PLAYERREFRESH","Submit changes to refresh the Player Demo.");
define("_WSA_PLAYERSTYLE","Style Options");
define("_WSA_PLAYERSTYLE_DSC","Select a color style option.");
define("_WSA_PLAYERSTYLE_OPT1","Monochrome");
define("_WSA_PLAYERSTYLE_OPT2","Color from Theme");
define("_WSA_PLAYERSTYLE_OPT3","Custom Player");
define("_WSA_PLAYERSTYLE_OPT4","Custom Player/Page");
define("_WSA_PLAYERTITLE","Player Name: ");
define("_WSA_PLAYERWARNING","WARNING: Are you sure you want to delete this Player? <br />Manually edit all entries using this player before deleting it.");
define("_WSA_PREVIEW","Preview");
define("_WSA_PREVIEW_DSC","Save your changes first!");
define("_WSA_PREVIEWLINK","Preview Source");
define("_WSA_PREVIEWPLAYER","Preview Player");
define("_WSA_SAVECOLORS","Save Colors");
define("_WSA_THEMEEDITOR","Theme Color Editor");
define("_WSA_THEMEEDITORDSC","Preview the found colors, edit the css or enter a color.");
define("_WSA_THEMEFILTER","Filter This Theme");
define("_WSA_THEMEFILTERALL","Filter All Themes");
define("_WSA_THEMEFILTERFAIL","Failed to filter the CSS.");
define("_WSA_THEMEMODIFIED","The colors used with this theme have been modifed.");
define("_WSA_THEMEPREVIEW","Theme Preview");
define("_WSA_THEMERESTORE","Restore from CSS");
define("_WSA_THEMERESTOREDSC","Delete current colors and restore from css.");

/**
* Player Flash Variables
*/
define("_WSA_DISBKCLR","Back Color: ");
define("_WSA_DISFTCLR","Front Color: ");
define("_WSA_DISLTCLR","Light Color: ");
define("_WSA_DISPLAYHEIGHT","Screen Height: ");
define("_WSA_DISPLAYWIDTH","Screen Width: ");
define("_WSA_DISPLAYWIDTH_DSC","Bottom tracks:<br /> Screen = Player<br /> Side tracks:<br />Screen < Player");
define("_WSA_LARGECONTROL","Large Controls");
define("_WSA_PLAYWIDTH","Player Width: ");
define("_WSA_PLAYHEIGHT","Player Height: ");
define("_WSA_PLAYBGCLR","Page Color: ");
define("_WSA_SCROLL","Scroll Bar Off: ");
define("_WSA_SCROLLDSC","Set this to true to let the playlist automatically scroll, based upon the mouse cursor. It defaults to false, at which you'll see a scrollbar.");
define("_WSA_SEARCHBAR","Display Search Bar");
define("_WSA_SEARCHBARDSC","Enter a search url below.");
define("_WSA_SEARCHLINK","Search Page URL");
define("_WSA_SEARCHLINKDSC","Leave blank to search this site or enter the search query url minus the search term.<br /> Example: http://video.google.com/videosearch?q=");
define("_WSA_SHOWDIGIT","Show Digits: ");
define("_WSA_SHOWDIGITDSC","Set this to false to hide the digits for % loaded, elapsed and remaining time in the players.");
define("_WSA_SHOWFSBUTTON","Full Screen Button:");
define("_WSA_SHOWFSBUTTONDSC","Set this to true to show the fullscreen button.");

//** Permissions
define("_WSA_APPROVEFORM","Auto Approve");
define("_WSA_APPROVEFORM_DESC","Automatically approve frontside submissions by these groups.");
define("_WSA_APPROVEPERM_WARN","Due to certain aspects of this module including copyright infringement, local moral or legal issues and the possibility of malicious code in submitted media files, it is recommended that automatic approval by anonymous users should not be allowed.  All submissions should be reviewed by a module admin.");
define("_WSA_DOWNLOADFORM","Download");
define("_WSA_DOWNLOADFORM_DESC","Select groups that are allowed to download");
define("_WSA_ENTRYPERM","View Permission");
define("_WSA_ENTRYPERMDOWN","Download Permission");
define("_WSA_ENTRYPERMDOWN_DESC","Assign which groups have permission to download this media file.");
define("_WSA_PERMFORM","Permissions Form");
define("_WSA_SUBMITFORM","Submit");
define("_WSA_SUBMITFORM_DESC","Allow users to submit an entry and, if published, modify that entry.");
define("_WSA_VIEWFORM","View");
define("_WSA_VIEWFORM_DESC","View Category");

//** Report

define("_WSA_BROKENREPORTS","Entry Reports");
define("_WSA_BROKENDELETED","Broken entry report deleted.");
define("_WSA_IGNORE","Ignore");
define("_WSA_IGNOREDESC","Ignore (Ignores the report and only deletes the <b>broken media entry report</b>)");
define("_WSA_DELETEDESC","Delete (Deletes <b>the reported website data</b> and <b>broken entry reports</b> for the media entry.)");
define("_WSA_NOBROKEN","No broken entries reported.");
define("_WSA_REPORT","Report");
define("_WSA_REPORTCOMMENT","Comment");
define("_WSA_REPORTER","Report Sender");

//** Modification Request
define("_WSA_APPROVE","APPROVE");
define("_WSA_LINKSWAITING","Waiting Validation");
define("_WSA_NOSUBMITTED","No New Entry Submissions.");
define("_WSA_MODREQDELETED","Modification Request Deleted.");
define("_WSA_MODREQUESTS","Entry Modification Requests");
define("_WSA_NOMODREQ","No Modification Requested.");
define("_WSA_ORIGINAL","Original");
define("_WSA_OWNER","Owner: ");
define("_WSA_PROPOSED","Proposed");
define("_WSA_USERMODREQ","Entry Modification Requested");

//** Submission Messages
define("_WSA_ERROREXIST","ERROR: The media you provided is already in the database!");
define("_WSA_ERRORTITLE","ERROR: You need to enter TITLE!");
define("_WSA_ERRORDESC","ERROR: You need to enter DESCRIPTION!");
define("_WSA_ERRORLINK","ERROR: You need to enter a Media Location!");
define("_WSA_ERRORTIME","ERROR: Publish time and expiration time are the same!");
define("_WSA_ERRORAUTOPUBTIME","ERROR: AUTO Publish time must be later than current time.");
define("_WSA_EXTENSION_INVALID","Invalid File Extension");
define("_WSA_DBUPDATED","Database Updated Successfully!");
define("_WSA_LINKWARNING","WARNING: Are you sure you want to delete this Entry?");
define("_WSA_LINKDELETED","Entry Deleted.");
define("_WSA_LOCATIONCHANGE","The media location has been changed");
define("_WSA_MIMETYPE_INVALID","Invalid Mimetype");
define("_WSA_TITLECHANGE","The entry's title has been changed.");
define("_WSA_FILESUCCESS","File Transfer Success.");

/**
* Votes and Ranking
*/
define("_WSA_ANONTOTALVOTES","Anonymous User Votes (total votes: %s)");
define("_WSA_IP","IP Address");
define("_WSA_NOREGVOTES","No Registered User Votes");
define("_WSA_NOUNREGVOTES","No Unregistered User Votes");
define("_WSA_RATING","Rating");
define("_WSA_TOTALRATE","Total Ratings");
define("_WSA_TOTALVOTES","Entry Votes (total votes: %s)");
define("_WSA_USER","User");
define("_WSA_USERAVG","User AVG Rating");
define("_WSA_USERTOTALVOTES","Registered User Votes (total votes: %s)");
define("_WSA_VOTEDELETED","Vote data deleted.");
define("_WSA_VOTESTATS","Vote Statistics");
?>