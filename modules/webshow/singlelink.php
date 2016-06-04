<?php
// $Id: singlelink.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
// Flash Media Player by Jereon Wijering ( http://www.jeroenwijering.com ) is licensed under a Creative Commons License (http://creativecommons.org/licenses/by-nc-sa/2.0/) //
// It allows you to use and modify the script for noncommercial purposes. //
// You must share a like any modifications. // 
// For commercial use you must purchase a license from Jereon Wijering at http://www.jeroenwijering.com/?order=form. //
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
//  ------------------------------------------------------------------------ //
//** singlelink.php displays the catalog entry, text body and flash player.

include "header.php";
$xoopsOption['template_main'] = 'webshow_singlelink.html';
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl -> assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="'  . XOOPS_URL . '/modules/' . $xoopsModule -> getvar( 'dirname' ) .  '/templates/style.css" />'); 
include_once 'include/functions.php';

include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mytree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","pid");
$myts =& MyTextSanitizer::getInstance();

$logowidth = $xoopsModuleConfig['logowidth'];
$xoopsTpl->assign('logowidth', $logowidth);
$metakey = ''; //Collects content for keyword search
$metadesc = '';
$movielid = ''; // Catch the entry lid

//** Get the entry data.
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : redirect_header('index.php',2,_WS_NOTEXIST);
$result = $xoopsDB->query("select l.lid, l.cid, l.title, l.url, l.srctype, l.listtype, l.listurl, l.entryinfo, l.listcache, l.cachetime, l.logourl, l.credit1, l.credit2, l.credit3, l.submitter, l.status, l.date, l.published, l.expired, l.hits, l.rating, l.votes, l.allowcomments, l.comments, l.player, l.entryperm, l.entrydownperm, l.views, t.description, t.bodytext from ".$xoopsDB->prefix("webshow_links")." l, ".$xoopsDB->prefix("webshow_text")." t where l.lid=$lid and l.lid=t.lid");
list($lid, $cid, $title, $url, $srctype, $listtype, $listurl, $entryinfo, $listcache, $cachetime, $logourl, $credit1, $credit2, $credit3, $submitter, $status, $date, $published, $expired, $hits, $rating, $votes, $allowcomments, $comments, $playerid,$ws_entryperm, $ws_entrydownperm, $views, $description, $bodytext) = $xoopsDB->fetchRow($result); 

$lid = !$lid ? redirect_header('index.php',2,_WS_NOTEXIST) : $lid;  //if no result redirect

//** IS ADMIN
if ( $xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid()) ) {
   $isadmin = true;
} else {
   $isadmin = false;
}

//** ADMIN Table
$admintable = "";
if($isadmin == true){
  $admintable = wsadminTable($lid);
  $xoopsTpl->assign('admintable', $admintable);
}

//** Entry Status Check  
//Redirects if status is less than 1 and user is not admin. Allows admin to view offline entry.
if($status <= 0 && !$isadmin){
   redirect_header("index.php",2,_WS_NOTEXIST);
}

//** Entry View Permission
$group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
$ws_entryperm = explode(" ",$ws_entryperm);
if (count(array_intersect($group,$ws_entryperm)) < 1){
        redirect_header("index.php", 3, _NOPERM);
}

//** Category Permission
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler =& xoops_gethandler('groupperm');
// example checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
if (!$gperm_handler->checkRight('webshow_view', $cid, $groups, $xoopsModule->getVar('mid'))) {
  redirect_header("javascript:history.go(-1)", 3, _NOPERM);
  exit();
}

//** SHOW INFO
// display selected content
$showinfo = $xoopsModuleConfig['showinfo2']; //SHOWINFO2 Show allowed Single page view contents as set in module preferences
$codebox = $xoopsModuleConfig['codebox']; // CODEBOX Display codeboxes as assigned in module preferences. 

//**  START ENTRY ITEMS 

//** ENTRY INFO
$entryinfo = explode(' ', $entryinfo); // ENTRYINFO  Display allowed items as set in entry editor

//** Media Location (listurl)
// Error Check for listurl 
if(!$listurl){
  // Missing media/playlist url reset status
  $xoopsDB->queryF("update ".$xoopsDB->prefix("webshow_links")." set status=-3 where lid=".$lid."");
  redirect_header('index.php',3,_WS_ERROR_NOMEDIALOCATION); //Missing Media Location
}
// if feed or dir check for cached playlist
if($listtype == "feed" && $listtype == "dir") {
  if(!$listcache){
     // Cache a new playlist
     if($listtype == "dir") {  
        createPlaylist($lid);
     } elseif ($listtype == "feed") {
        fetchPlaylist($lid);
     }
  }
  if ( file_exists(XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache) ) {
      $listurl =  XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache;
  } //ELSE use original listurl
} 
$listurl = $myts->htmlSpecialChars($listurl);

//** ENTRY TITLE
$ltitle = $myts->htmlSpecialChars($title);
$title = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/singlelink.php?lid=$lid\" target=\"_self\" title=\""._WS_VIEWMEDIA." - ".$ltitle."\" rel=\"nofollow\">".$ltitle."</a>";

//** ADMIN LINK and Entry Status Report
if ( $isadmin == true ) {
   $adminlink = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/admin/index.php?op=modLink&amp;lid=".$lid."\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/editicon.gif\" border=\"0\" alt=\""._WS_ADMIN."\" /></a>";
} else {
   $adminlink = '';
}	

//** MODIFY LINK. Display to admin and submitter only.
$modlink = '';
if ($xoopsUser) {
   $user = $xoopsUser->getVar('uid');
   if ($user == $submitter | $isadmin) {
       $modlink = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/modlink.php?lid=".$lid."\" rel=\"nofollow\" title=\""._WS_MODIFY."\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/modify.gif\" border=\"0\" alt=\""._WS_MODIFY."\" /></a>";  
   }
}

//** POPUP BUTTON
//** Java script opens popmovie.php in a new window.
$popbutton = '';
if(in_array('pop',$showinfo) & in_array('pop',$entryinfo)){         
   $popbutton = "<span onclick= \"window.open('popmovie.php?lid=".$lid."','popmovie".$lid."','directories=no,toolbar=no,location=no,menubar=no');return false;\" style=\"cursor:pointer;\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/popbutton.gif\" alt=\"".$ltitle." "._WS_POPUP." "._WS_PLAYER."\" /></span>";
}

//** New or Updated Button
// display new button for 7 days
$startdate = (time()-(86400 * 7)); 
$newbutton = $startdate < $date ? 1 : '';
// Updated Button 
$newbutton =  !$newbutton & $status == 2 ? 2 : ''; 

//** Popular Button
$popularbutton = ( $views >= $xoopsModuleConfig['popular'] ) ? 1 : '';            

//** Category Box
$catresult=$xoopsDB->query("SELECT cattitle, imgurl, catdesc FROM ".$xoopsDB->prefix("webshow_cat")." WHERE cid = $cid");
list($cattitle, $catimgurl, $catdesc) = $xoopsDB->fetchRow($catresult); 
$cattitle = $myts->htmlSpecialChars($cattitle);
$catdesc = !empty($catdesc) ? $myts->displayTarea($catdesc,0) : '';
$catimage = !empty($catimgurl) ? $myts->htmlSpecialChars($catimgurl) : '';

//** Block Description   
if( in_array('dsc',$showinfo) & in_array('dsc',$entryinfo) ){
   $description = $myts->displayTarea($description,0);
   $metadesc = strip_tags($description); // Collect Meta Description
} else {
   $description = '';
}
            
//** ENTRY LOGO            
if(in_array('logo',$showinfo) & in_array('logo',$entryinfo)){
   $logowidth = $xoopsModuleConfig['logowidth'];
   if($logourl != ''){               
      $ps = strpos($logourl,"http://");
      if($ps === false) {
         $logourl = XOOPS_URL."/".$xoopsModuleConfig['path_logo']."/".$logourl;
      }
   }else{
      if ($xoopsModuleConfig['nologo'] == 'stocklogo' ) {              
         $logourl = XOOPS_URL."/".$xoopsModuleConfig['path_logo']."/stock/logo.gif";
      }
   }
   $logourl = $myts->htmlSpecialChars($logourl);               
}else{
   $logourl = '';
   $logowidth = '';
}
$xoopsTpl->assign('logowidth', $logowidth);

//** CREDITS
if(in_array('cred',$showinfo) & in_array('cred',$entryinfo)){
    $credit1 = $myts->htmlSpecialChars($credit1);
    $credit2 = $myts->htmlSpecialChars($credit2);
    $credit3 = $myts->htmlSpecialChars($credit3);
    $credits = !$credit1 & !$credit2 & !$credit3 ? '' : 1; //template switch
} else {
   $credits = '';
   $credit1 = '';
   $credit2 = '';
   $credit3 = '';
}

//** STATISTICS
$stats = '';
if(in_array('stat',$showinfo) & in_array('stat',$entryinfo)){
      $stats = 1;
      $updated = formatTimestamp($published,"s"); 
      $date = formatTimestamp($date,"s");
   if ($votes == 1) {
      $votestring = _WS_ONEVOTE;
   } else {
      $votestring = sprintf(_WS_NUMVOTES,$votes);
   } 
}else{
   $hits = '';
   $votestring = '';
   $updated = '';
   $views = "0";
}

//** SUBMITTER
if(in_array('sbmt',$showinfo) & in_array('sbmt',$entryinfo)){ 
   if(@include_once XOOPS_ROOT_PATH."/class/userutility.php"){
      $submittername = XoopsUserUtility::getUnameFromId($submitter,0,'');
   }else{
      $submittername = XoopsUser::getUnameFromId($submitter);
   }
}else{$submittername = '';}

//** Comments
if($allowcomments == 0){ $comments = ''; }

//** RATE
if(in_array('rate',$showinfo) & in_array('rate',$entryinfo)){
   $rating = number_format($rating, 1);
} else {
   $rating = 'off';
}

//**
//**** CREATE THE MOVIE ITEMS
//**

//** LOAD THE MOVIE and assign movielid to template
$movielid = $lid;
$xoopsTpl->assign('movielid', $movielid);
include "include/movie.inc.php";
if($listtype == "embed"){
  $embedmovie = embedMovie($lid, $playerid,'');
  $movie = !empty($embedmovie['movie']) ? $embedmovie['movie'] : '';
  $hostname = !empty($embedmovie['hostname']) ? $myts->htmlSpecialChars($embedmovie['hostname']) : '';         
  $hostlink = !empty($embedmovie['hostlink']) ? $myts->htmlSpecialChars($embedmovie['hostlink']) : '';
  $url = !$url ? $hostlink : $url;  //if url is empty use hostlink
} else {
  $movie = loadMovie($lid, $playerid,'');
  $hostname = '';         
  $hostlink = '';
}
$xoopsTpl->assign('movie',$movie);

//** GET PLAYER VARS and assign to template
$playerresult = $xoopsDB->query("select bgcolor, backcolor, frontcolor, lightcolor, width, height, styleoption from ".$xoopsDB->prefix("webshow_player")." where playerid=$playerid");
list($bgcolor, $backcolor, $frontcolor, $lightcolor, $width, $height, $styleoption) = $xoopsDB->fetchRow($playerresult);
$xoopsTpl->assign('screen', array('bgcolor' => $bgcolor, 'backcolor' => $backcolor, 'frontcolor' => $frontcolor, 'lightcolor' => $lightcolor, 'width' => $width, 'height' => $height));
 
//** THEME FROM PLAYER
//** Uses the color style from this player for the pages color style.
$themefromplayer = ($styleoption == '4') ? 1 : '';
$xoopsTpl->assign('themefromplayer',$themefromplayer);

// Media's Website
$url = $myts->htmlSpecialChars($url);

// GET Links, buttons and codeboxes
include "include/codebox.inc.php";

//** TAG BAR
// Must have TAG Module by phppp installed
$tagbar="";
if($xoopsModuleConfig['tags'] & in_array('tag',$entryinfo) & in_array('tag',$showinfo)){
   if(file_exists(XOOPS_ROOT_PATH."/modules/tag/include/tagbar.php")) {
       include_once XOOPS_ROOT_PATH."/modules/tag/include/tagbar.php";
       $itemid = $lid;
       $catid = 0;
       //function tagBar($tags, $catid = 0, $modid = 0)
       $tagbar = tagBar($itemid, $catid);
       if($tagbar){
          $xoopsTpl->assign('tagbar',$tagbar);
          $metakey .= implode(' ',$tagbar["tags"]); 
       }
   }
}

//** Body Text
$bodytext = $myts -> displayTarea( $bodytext, 1 );

//** MEDIA LINK ARRAY
$xoopsTpl->assign('link', array('id' => $lid, 'cid' => $cid, 'cattitle' => $cattitle, 'catimage' => $catimage, 'catdesc' => $catdesc, 'rating' => $rating, 'ltitle' => $ltitle, 'title' => $title, 'newbutton' => $newbutton, 'popularbutton' => $popularbutton, 'listtype'=> $listtype, 'srctype' =>$srctype, 'listurl' => $listurl, 'url' => $url, 'description' => $description, 'logourl' => $logourl, 'tagbar' => $tagbar, 'popbutton' => $popbutton, 'adminlink' => $adminlink, 'modlink' => $modlink, 'allowcomments' => $allowcomments, 'comments' => $comments, 'credits' => $credits, 'credit1' => $credit1, 'credit2' => $credit2, 'credit3' => $credit3, 'stats' => $stats, 'date' => $date, 'updated' => $updated, 'hits' => $hits, 'views' => $views, 'votes' => $votestring, 'submitter' => $submitter, 'submittername' => $submittername, 'permalink' => $permalink, 'sitebutton' => $sitebutton, 'sitelink' => $sitelink, 'sitecode' => $sitecode, 'downbutton' => $downbutton, 'downlink' => $downlink, 'feedbutton' => $feedbutton, 'feedlink' => $feedlink, 'feedcode' => $feedcode, 'mail_subject' => rawurlencode(sprintf(_WS_INTRESTLINK,$xoopsConfig['sitename'])), 'mail_body' => rawurlencode(sprintf(_WS_INTLINKFOUND,$xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname").'/singlelink.php?lid='.$lid), 'bodytext' => $bodytext, 'hostname' => $hostname, 'hostlink' => $hostlink));

/*
** END MEDIA ENTRY ITEMS
*/

//** ENABLE JAVASCRIPT
//Feeds only. Activates the Javascript player controls and track data display
//Set "enable javascript" in the entry's Flash preferences to add the Flash variables enablejs & javascriptid to the movies code
// Add javascript from mplay.inc that uses the JW MEDIA Player API to provide player control and real time track data
$mplayjs = ''; // Holds the API javascript functions
if($listtype == "feed" || $listtype == "dir") {
   if( in_array('feeddata',$showinfo) || in_array('trackdata',$showinfo ) ) {
      if( in_array('feeddata',$entryinfo) || in_array('trackdata',$entryinfo ) ) {
         $flashresult = $xoopsDB->query("select enablejs from ".$xoopsDB->prefix("webshow_flashvar")." where lid=$lid");
         list($enablejs)=$xoopsDB->fetchRow($flashresult);
         if ($enablejs = 1) {
            include './include/mplay.inc.php';                      
         }
      }
   }
}
$xoopsTpl->assign('mplayjs',$mplayjs);

//** TRACK DATA TEMPLATE SWITCH
// wstrackdata sets the template to display track data returned by the JW MEDIA PLAYER API
$trackdata = '';
if( $listtype == "feed" || $listtype == "dir" ) {
   if ( in_array('trackdata',$showinfo) & in_array('trackdata',$entryinfo) ) {
      $trackdata = 1;
   }
}
$xoopsTpl->assign('wstrackdata', $trackdata);

//** Feed Data
// Get the xml feed data and parse to html
if( $listtype == "feed" || $listtype == "dir" ) {
   include "include/feeddata.inc.php";
}

//** PAGE HIT COUNTER  Adds 1 if not submitter or admin.
if ($xoopsUser) { 
   $user = $xoopsUser->getVar('uid'); 
   if ($user != $submitter | !$isadmin) { 
       $pagehit = updatehits($lid); 
   } 
} else { 
  $pagehit = updatehits($lid); 
}

//** Submit a Web Show
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler =& xoops_gethandler('groupperm');
if ($gperm_handler->checkRight('webshow_submit', 0, $groups, $xoopsModule->getVar('mid'))) {  
   $xoopsTpl->assign('submitlink', 1);
}

//** Category Select
ob_start();
//** example makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")	
$mytree->makeMySelBox("cattitle","cattitle",0,1,"cid",'window.location="playcat.php?cid="+this.value');	
$catselbox = ob_get_contents();
ob_end_clean();	
$xoopsTpl->assign('category_select', $catselbox);

//** TEXT VARIABLES
// Module Name
$wsmodname = $myts->htmlSpecialChars($xoopsModule->getVar('name'));
$xoopsTpl->assign('wsmodname', $wsmodname);

// Module Description
$wsmoddesc = $myts->displayTarea($xoopsModuleConfig['moddesc'],0);
$xoopsTpl->assign('wsmoddesc', $wsmoddesc);

// example define("_WS_THEREARESINGLE","There are %s category in the %s directory.");
$xoopsTpl->assign('therearesingle', sprintf(_WS_THEREARESINGLE, $ltitle, $cattitle, $xoopsConfig['sitename']." ".$wsmodname));

//** METATAGS
// Page Title
$pagetitle = $myts->htmlSpecialChars($ltitle).'&nbsp;'.$cattitle.'&nbsp;'.$wsmodname;
$xoopsTpl->assign('xoops_pagetitle',$pagetitle);

// Meta Description
$metadesc = $myts->htmlSpecialChars($metadesc);

// Meta Keywords
//include_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/include/functions.php';
$metakey = webshow_extract_keywords($pagetitle." ".$metadesc." ".$metakey);
$metakey = $myts->htmlSpecialChars($metakey);

// Assign meta tags 
if (is_object($xoTheme)) { 
   $xoTheme->addMeta( 'meta', 'keywords',$metakey);
   $xoTheme->addMeta( 'meta', 'description',$metadesc);     
   $xoTheme->addMeta( 'meta', 'title', $pagetitle); 
} else {  
   $xoopsTpl->assign('xoops_meta_keywords',$metakey);
   $xoopsTpl->assign('xoops_meta_description',$metadesc);       
}

include XOOPS_ROOT_PATH.'/include/comment_view.php';
include XOOPS_ROOT_PATH.'/footer.php';
?>