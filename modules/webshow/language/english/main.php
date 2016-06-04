<?php
// $Id: main.php,v.71 2009/05/15 19:59:00 tcnet Exp $ //
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
define("_WS_ADMIN","Administration Panel"); //Admin link
define("_WS_CATEGORY","Category");
define("_WS_CATSELECT","Select a Category:");
define("_WS_CREATED","Created");
define("_WS_DIRECTORY","Directory");
define("_WS_EMBED","Embed");
define("_WS_EMBEDLINKJS","Script");
define("_WS_FOOTERTEXT","A media directory where members submit links to Flash files, streams, podcast webfeeds or media sharing sites.");
define("_WS_HOST","Media Host");
define("_WS_INTRESTLINK","Interesting web show at %s");  // %s is your site name
define("_WS_INTLINKFOUND","Here is an interesting media clip I have found at %s");  // %s is your site name
define("_WS_HOME","Home");
define("_WS_MODIFY","Modify");
define("_WS_NEW","New");
define("_WS_NEWTHISWEEK","New This Week"); 
define("_MS_NOTOWNER","You are not the owner of this entry.");
define("_WS_PAGE","Page");
define("_WS_POPULAR","Popular"); 
define("_WS_POPUP","PopUp");
define("_WS_RANK","Rank");
define("_WS_REQUESTMOD","Request Entry Modification");
define("_WS_REQUIRED","<b>* = Required Field</b>");
define("_WS_SUBCATEGORY","Subcategory");
define("_WS_SUBMITLINK","Submit a Link");
define("_WS_TOP10","Top 10"); // %s is a category title
define("_WS_TOPHITS","Top Page Hits");
define("_WS_TOPVIEWS","Top Media Views");
define("_WS_UPDATED","This media entry has been updated");
define("_WS_UPTHISWEEK","Updated this week");
define("_WS_VIEWMEDIA","View Media");

/**
* THERE ARE ...
**/
define("_WS_THEREARE","There are %s shows in our media directory.");
define("_WS_THEREAREBY","We have %s shows published by %s."); // Used in Play Poster view
define("_WS_THEREARECAT","There are %s categories in our media directory.");
define("_WS_THEREARECATNONE","There are no entries in this category.");
define("_WS_THEREAREINDEX","The media directory has %s shows in %s categories."); // Used in index page
define("_WS_THEREARENONE","There are no entries in the media directory.");
define("_WS_THEREAREPLAYCAT","There are %s shows in the %s category.");  // Play Cat view
define("_WS_THEREARESINGLE","%s is from the %s category in the %s."); //Singlelink.php line 379.  Listing title, category title, sitename modname
define("_WS_THEREARESUBCAT","There are %s category in the %s directory.");

/**
* Player
*/
define("_WS_PAUSE","Pause");
define("_WS_PLAY","Play");
define("_WS_PLAYER","Player");
define("_WS_PLAYER_ON","Turn the Player On");
define("_WS_PLAYER_OFF","Turn the Player Off");
define("_WS_STOP","Stop");

/**
* Item Info
*/
define("_WS_CREDITS","Credits");
define("_WS_CREDIT1","Artist");
define("_WS_CREDIT2","Album");
define("_WS_CREDIT3","Label");
define("_WS_DESCRIPTION","Description");
define("_WS_DOWNLOAD","Download");
define("_WS_PAGEHITS","Page Hits");
define("_WS_PAGE_VIEW","View Media Page");
define("_WS_POSTER","Poster");
define("_WS_POSTERTITLE","Web Shows Posted By %s");
define("_WS_POSTERVIEW","View All");
define("_WS_POSTERVIEW_DSC","View all by this poster.");
define("_WS_STATISTICS","Statistics");
define("_WS_TITLE","Title");
define("_WS_VIEWS","Media Views");
define("_WS_WEBFEED","Feed");
define("_WS_WEBSITE","Site");

/*
* BUTTONS
*/
define("_WS_BUTTON_PAGE","Media Page"); // Page link button text
define("_WS_BUTTON_POPULAR","*Popular*"); // Popular Button text
define("_WS_BUTTON_NEW","*New*"); // New Button text
define("_WS_BUTTON_UPDATED","Updated"); // New Button text
define("_WS_BUTTON_PLAYERON","Player On");
define("_WS_BUTTON_PLAYEROFF","Player Off");

/*
* Item Info Box
*/
define("_WS_CODES","Codes");
define("_WS_CODES_DSC","Display Link and Embed Codes");
define("_WS_CODEBOX","Embed and Link Code");
define("_WS_CODEBOX_DSC","Click inside the box and then copy and paste to your site.");
define("_WS_HIDE","Hide");
define("_WS_PERMALINK","Perma");
define("_WS_SENDEMAIL","Email A Friend");
define("_WS_SHARE","Share");
define("_WS_SOCIALBOOKMARK","Social Bookmark");

/*
* ID3 TAGS
*/
define("_WS_ID3TAG","ID3 Tag Info");
define("_WS_ID3TITLE","Title");
define("_WS_ID3ARTIST","Artist");
define("_WS_ID3ALBUM","Album");
define("_WS_ID3COMPOSER","Composer");
define("_WS_ID3COPYRIGHT","Copyright");
define("_WS_ID3GENRE","Genre");
define("_WS_ID3YEAR","Year");

/*
* Feed and track data
*/

define("_WS_FEED_TEXT","Text Feed");
define("_WS_FEEDDATA","Feed Data");
define("_WS_TRACKAUDIO","Audio:");
define("_WS_TRACKAUTHOR","Author:"); 
define("_WS_TRACKCAPTIONS","Captions:");
define("_WS_TRACKCAT","Category:");
define("_WS_TRACKCITY","City:");
define("_WS_TRACKDATA","Track Data");
define("_WS_TRACKDATE","Date:");
define("_WS_TRACKDESC","Description:");
define("_WS_TRACKFILE","File:");
define("_WS_TRACKID","Id:");
define("_WS_TRACKIMAGE","Image:");
define("_WS_TRACKLAT","Latitude:");
define("_WS_TRACKLINK","Link:");
define("_WS_TRACKLONG","Longitude:");
define("_WS_TRACKSTART","Start:"); 
define("_WS_TRACKTITLE","Title:");
define("_WS_TRACKTYPE","Type:");

/**
 * Rating
 */
define("_WS_CANTVOTEOWN","You can't rate your own entry.");
define("_WS_DONOTVOTE","Do not vote for your own entry.");
define("_WS_ONEVOTE","1 vote");
define("_WS_NORATING","No rating selected.");
define("_WS_NUMVOTES","%s votes");
define("_WS_RATE","Rate");
define("_WS_RATING","Rating");
define("_WS_RATINGNO","No Rating");
define("_WS_RATETHISSITE","Rate This Show"); 
define("_WS_RATINGSCALE","Rate 1 (poor) - 10 (excellent)");
define("_WS_THANKURATE","Thanks for rating this show.");
define("_WS_TOPRATED","Top Rated");
define("_WS_VOTE","Vote");
define("_WS_VOTEONCE","One vote allowed per entry.");
define("_WS_VOTEONCE2","You have already rated this.");

/**
 * Report
 */
define("_WS_ALREADYREPORTED","This entry has been reported already.");
define("_WS_FORSECURITY","For security reasons your user name and IP address are temporarily recorded.");
define("_WS_REPORT","Report");
define("_WS_REPORT_DSC","Please report shows that are broken, abuse our Terms of Service, or display your copyrighted media.");
define("_WS_REPORTABUSE","Abuse");
define("_WS_REPORTBROKEN","Broken");
define("_WS_REPORTCOMMENT","Comment");
define("_WS_REPORTCOMMENT_DSC","Please describe your complaint.<br />Copyright reports must include the owner's name and the location of the original media.");
define("_WS_REPORTCOPYRIGHT","Copyright");
define("_WS_THANKSFORINFO","Thanks for the information. We'll look into your request shortly.");
define("_WS_THANKSFORHELP","Thank you for helping to maintain this directory's integrity.");

/*
* Response Messages
*/
define("_WS_ALLPENDING","Your information will be posted pending verification.");
define("_WS_CAPTCHA_INCORRECT","Incorrect Confirmation Code");
define("_WS_ISAPPROVED","We approved your media entry submission");
define("_WS_MUSTREGFIRST","Sorry, you don't have the permission to perform this action.<br />Please register or login first!");
define("_WS_NOTEXIST","Does not exist");
define("_WS_NOTALLOWED","Not Allowed");
define("_WS_RECEIVED","We received your media entry. Thanks!");
define("_WS_REQUESTDENIED","Request Denied.");
define("_WS_ERROR_EMBEDPLUG","Broken Media: The embed plugin has an error.");
define("_WS_ERROR_NOMEDIALOCATION","Broken Media: The media location could not be found.");

/**
* Search
*/

define("_WS_MEDIASEARCH","Media Search");
define("_WS_SEARCH","Search"); //used in search form
define("_WS_SEARCHFOUND","Media search found %s result(s) for <i>%s</i>");
define("_WS_SEARCHFOUNDNO","No search results were found.");
define("_WS_SEARCHRESULTS","Search results for %s"); // %s is search term
define("_WS_SEARCHTERM","Search Term");
define("_WS_SEARCHTERMENTER","Please enter a search term.");
define("_WS_SEARCHTERMNO","There was no search term available.");
define("_WS_SEARCHTERMSHORT","The search keyword must be longer than %s characters.");
define("_WS_SEARCHTERMX","Unused Term");

/**
* Sorting
*/
define("_WS_CATSORTEDBY","%s Category by %s"); // Category Pages
define("_WS_CURSORTEDBY","Sorted by %s"); //Used in sort form
define("_WS_MEDIASORTEDBY","Media Programs Listed by %s"); //Index Page Loop Header
define("_WS_SORTBY","Sort by:");
define("_WS_CATTITLEATOZ","Category (A to Z)");
define("_WS_CATTITLEZTOA","Category (Z to A)");
define("_WS_DATEOLD","Date (Oldest)");
define("_WS_DATENEW","Date (Latest)");
define("_WS_LIDLTOH","ID (Low)");
define("_WS_LIDHTOL","ID (High)");
define("_WS_PAGEHITSLTOM","Page Hits (Least)");
define("_WS_PAGEHITSMTOL","Page Hits (Most)");
define("_WS_PARENTLTOH","Parent ID (Low)");
define("_WS_PARENTHTOL","Parent ID (High)");
define("_WS_RATINGLTOH","Rating (Lowest)");
define("_WS_RATINGHTOL","Rating (Highest)");
define("_WS_TITLEATOZ","Title (A to Z)");
define("_WS_TITLEZTOA","Title (Z to A)");
define("_WS_VOTESLTOM","Votes (Least)");
define("_WS_VOTESMTOL","Votes (Most)");
define("_WS_VIEWSLTOM","Media Views (Least)");
define("_WS_VIEWSMTOL","Media Views (Most)");
?>