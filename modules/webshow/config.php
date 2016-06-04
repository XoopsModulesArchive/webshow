<?php
// $Id: movie.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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

include "header.php";
include XOOPS_ROOT_PATH."/header.php";
error_reporting(0); 
$xoopsLogger->activated = false; 
$myts = & MyTextSanitizer :: getInstance(); // MyTextSanitizer object

global $xoopsUser, $myts;

$lid = isset( $_GET['lid'] ) ? intval( $_GET['lid'] ) : exit();

$linkresult = $xoopsDB->query("select lid, cid, title, listurl, listcache, listtype, srctype, player, logourl, url, entryinfo, entryperm, entrydownperm from ".$xoopsDB->prefix("webshow_links")." where lid = $lid and status > 0");
list($lid, $cid, $ltitle, $listurl, $listcache, $listtype, $srctype, $playerid, $logourl, $url, $entryinfo, $ws_entryperm, $entrydownperm) = $xoopsDB->fetchRow($linkresult); 
if($lid == "") { exit(); } // Results check

//** View Permission
$group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
$ws_entryperm = explode(" ",$ws_entryperm);
if (count(array_intersect($group,$ws_entryperm)) < 1) {  die(" No Permission to view!");  }

//** VIEW HIT  Adds 1 if not submitter or admin.
if ($xoopsUser) { 
  $user = $xoopsUser->getVar('uid'); 
  if ($user != $submitter && !$xoopsUser->isAdmin($xoopsModule->mid()) ) { 
         updateviews($lid);
  } 
} else { 
  updateviews($lid);
}  

//** Set the Player's FLASH VARIABLES
$playervars = getPlayerVar($playerid);
$styleoption = $playervars['styleoption'];
$bgcolor = $playervars['bgcolor'];
$backcolor = $playervars['backcolor'];
$frontcolor = $playervars['frontcolor'];
$lightcolor = $playervars['lightcolor'];
$width = $playervars['width'];
$height = $playervars['height'];
$displaywidth = $playervars['displaywidth'];
$displayheight = $playervars['displayheight'];
$showdigits = $playervars['showdigits'];
$showfsbutton = $playervars['showfsbutton'];
$scroll = $playervars['scroll'];
$largecontrol = $playervars['largecontrol'];  
$searchbar = $playervars['searchbar'];
$searchlink = $playervars['searchlink'];

//** THEMESTYLE
// style option 2: Use colors assigned per theme
if($styleoption == "2") {
  $theme = isset($_GET['theme']) ? intval( $_GET['theme'] ) : $xoopsConfig['theme_set'];
  $themeresult = $xoopsDB->query("select bgcolor, backcolor, frontcolor, lightcolor from ".$xoopsDB->prefix("webshow_theme")." where themetitle = \"$theme\"");
  list($bgcolor, $backcolor, $frontcolor, $lightcolor) = $xoopsDB->fetchRow($themeresult);
}

//** Modify the Color Variables.
//$bgcolor is already set
$backcolor = str_replace ( '#','',$backcolor);	
$backcolor = '0x'.$backcolor;
$frontcolor = str_replace ( '#','',$frontcolor);
$frontcolor = '0x'.$frontcolor;
$lightcolor = str_replace ( '#','',$lightcolor);
$lightcolor = '0x'.$lightcolor;
$screencolor = str_replace ( '#','',$bgcolor);
$screencolor = '0x'.$screencolor;

//** Set the Player's FLASH BEHAVIOR VARIABLES
$flashvars = getFV($lid,"");
$start = $flashvars['start'];
$shuffle = $flashvars['shuffle'];
$replay = $flashvars['replay'];
$link = $flashvars['link'];
$linktarget = $flashvars['linktarget'];
$showicons = $flashvars['showicons'];
$stretch = $flashvars['stretch'];
$showeq = $flashvars['showeq'];
$rotatetime = $flashvars['rotatetime'];
$shownav = $flashvars['shownav'];
$transition = $flashvars['transition'];
$thumbslist = $flashvars['thumbslist'];
$captions = $flashvars['captions'];
$enablejs = $flashvars['enablejs'];
$playerlogo = $flashvars['playerlogo'];
$audio = $flashvars['audio'];
$searchbar = $flashvars['searchbar'];

//** Set file url
if($listtype == "feed" || $listtype == "dir"){  
   $fileurl = XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache;
} elseif ( $listtype == "single" ) {
   $fileurl = $listurl;
}

//** Build the xml 
$var = '<?xml version="1.0" ?>';
$var .= '<config>';

//** START ENTRY VARIABLES
$var .= '<file>'.urlencode($fileurl).'</file>';
$var .= '<id>'.$lid.'</id>';

//** TITLE for single only.  NOT WORKING as of JWPLAYER V3.16
if ($listtype == 'single'){
   $var .= '<title>'.$myts->htmlSpecialChars($ltitle).'</title>';
}  

//** File type for single
if ($listtype == 'single' & $srctype != 'image'){ 
   $listurlext = (substr($listurl,strlen($listurl)-3,3));  /* by ivan */ 
   $ps = strpos($listurlext,$srctype);
   if ($ps === false) {
      $var .= '<type>'.$srctype.'</type>';
   }	
}

//** Logo Image
if ( $logourl != '' ) {
   $ps = strpos($logourl,"http://");
   if( $ps === false ) {
      $logourl = XOOPS_URL."/".$xoopsModuleConfig['path_logo']."/".$logourl;
   }
   $var .= '<image>'.urlencode($logourl).'</image>'; 
}

//** PLAYER VARIABLES
$var .= '<width>'.$width.'</width>';
$var .= '<height>'.$height.'</height>';
$var .= '<displaywidth>'.$displaywidth.'</displaywidth>';
$var .= '<displayheight>'.$displayheight.'</displayheight>';

//** Color Variables
if ($styleoption != 1){
   $var .= '<backcolor>'.$backcolor.'</backcolor>';
   $var .= '<frontcolor>'.$frontcolor.'</frontcolor>';
   $var .= '<lightcolor>'.$lightcolor.'</lightcolor>';
   $var .= '<screencolor>'.$screencolor.'</screencolor>';
}

if($showdigits == 0) {$var .='<showdigits>false</showdigits>';} //default = true
if($showfsbutton == 0) {$var .= '<usefullscreen>false</usefullscreen>';} //default = true 
if($scroll == 1) {$var .= '<autoscroll>true</autoscroll>';} //default=false
if($largecontrol == 1) {$var .= '<largecontrol>true</largecontrol>';} //default=false
if($searchbar == 1 & $searchlink != "") {
  $var .= '<searchbar>true</searchbar>';  //default = true
  $var .= '<searchlink>'.$searchlink.'</searchlink>';
}

//** FLASH VARIABLES
if($start == 1) {$var .= '<autostart>true</autostart>';}  
if($shuffle == 0){$var .= '<shuffle>false</shuffle>';} //default=true   
if($replay == 1){$var .= '<repeat>true</repeat>';} //default=false

//** Screen Link url and target window 
if( $link != "0" ) {
   // linkfromdisplay turns on the Screen Link else Screen Link controls the player
   $var .= '<linkfromdisplay>true</linkfromdisplay>'; //default=false
   //** Screen Link Target
   if($linktarget == "_self") { $var .= '<linktarget>'.$linktarget.'</linktarget>'; }
   if($link == "site"){ $var .= '<link>'.urlencode($url).'</link>'; }
   if( $link == "page" ){
      $linkpage = urlencode(XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname").'/singlelink.php?lid='.$lid);
      $var .= '<link>'.$linkpage.'</link>';
   }

   //** Download
   // Screen Link must be set to download.          
   $downpath = '';
   $showdownload = '';
   $entryinfo = explode(' ', $entryinfo); 
   if ( $link == "down" & in_array('down',$entryinfo) ) {
      // Download Permission
      $group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
      $ws_entrydownperm = explode(" ",$ws_entrydownperm);
      if (count(array_intersect($group,$ws_entrydownperm)) > 0) {     
          //** If the file is from the wf downloads module.
          $searchstring = strToLower($listurl);
          $searchpattern = "wfdownloads/visit";
          $wfdwnok = strpos($searchstring,$searchpattern);
          if($wfdwnok != ""){
             $downpath = $listurl;
          } else {
              $downpath = XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/wsdwnlod.php?lid=".$lid;
          }
          $showdownload = 's'.$lid.'.addVariable("showdownload","true");';
          $var .= '<showdownload>true</showdownload>';
          $var .= '<link>'.urlencode($downpath).'</link>';
      }
   }
}

if($shownav == 1) {$var .= '<shownavigation>true</shownavigation>'; } //default=false 
if($transition != "0" & $srctype == "image") {$var .= '<transition>'.$transition.'</transition>';} //default = "0"
if($captions != "" ) {$var .= '<captions>'.urlencode($captions).'</captions>';} // default = ""
if($enablejs != 0) {          
  $var .= '<enablejs>true</enablejs>';   
  $var .= '<javascriptid>play'.$lid.'</javascriptid>';
  include 'mplay.inc.php';  
} else { 
  $enablejs = ""; //default = false
  $mplayjs = "";
  $jsid = "";
}

if($showicons == 0)  {$var .= '<showicons>false</showicons>';} //default = true
if($stretch != "false") {$var .= '<overstretch>'.$stretch.'</overstretch>';}//default='false'
if($showeq == 1) {$var .= '<showeq>true</showeq>';}//default=false}  
if($thumbslist == 0) {$var .= '<thumbsinplaylist>false</thumbsinplaylist>';}//default=true
if($rotatetime != 0) {$var .= '<rotatetime>'.$rotatetime.'</rotatetime>';}//default=0
if($playerlogo != "") {
  $playerlogo = urlencode(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/images/player/'.$playerlogo);          
  $var .= '<logo>'.$playerlogo.'</logo>';
}
if($xoopsModuleConfig['callbacklog'] == 1){
  $callback = urlencode(XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname").'/callback.php');
  $var .= '<callback>'.$callback.'</callback>';// default = ""
}else{
  $callback = "";
}
if($audio != "" ) {$var .= '<audio>'.urlencode($audio).'</audio>';} // default = ""
if($searchbar == 1) {
   $var .= '<searchbar>true</searchbar>';
   $searchlink = XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname").'/mediasearch.php?query=';
   $var .= '<searchlink>'.urlencode($searchlink).'</searchlink>';
}
   
$var .= '</config>';
header("Content-type: text/xml"); 
echo $var;
?>