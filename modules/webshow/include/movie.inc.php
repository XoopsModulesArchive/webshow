<?php
// $Id: movie.inc.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

function embedMovie($lid, $playerid, $styleoption){

   global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsUser;
   $lid = !empty($lid) ? $lid : redirect_header("javascript:history.go(-1)",2,_WS_NOTEXIST);

   $result = $xoopsDB->query("select lid, srctype, listtype, listurl, logourl, submitter, player from ".$xoopsDB->prefix("webshow_links")." where status>0 and lid=$lid");
   list($lid, $srctype, $listtype, $listurl, $logourl, $submitter, $player) = $xoopsDB->fetchRow($result); 

   if ($listtype != "embed"){
      redirect_header("javascript:history.go(-1)",2,_WS_NOTALLOWED);
   }

   //** Get player variables.
   $playerid = !empty($playerid) ? $playerid : $player;
   $playervars = getPlayerVar($playerid);
   $pid = $playervars['pid'];     
   $playertitle = $playervars['playertitle'];
   if(!$styleoption) {
      $styleoption = $playervars['styleoption'];
   }
   $bgcolor = $playervars['bgcolor'];
   $backcolor = $playervars['backcolor'];
   $frontcolor = $playervars['frontcolor'];
   $lightcolor = $playervars['lightcolor'];
   $width = $playervars['width'];
   $height = $playervars['height'];

   //** THEMESTYLE
   // style option 2: Use colors assigned per theme
   if($styleoption == "2") {
      $theme = isset($_GET['theme']) ? $_GET['theme'] : $xoopsConfig['theme_set'];
      $themeresult = $xoopsDB->query("select bgcolor, backcolor, frontcolor, lightcolor from ".$xoopsDB->prefix("webshow_theme")." where themetitle = \"$theme\"");
      list($bgcolor, $backcolor, $frontcolor, $lightcolor) = $xoopsDB->fetchRow($themeresult);
   }

   //** Set the Color Variables.
   //$bgcolor is already set
   $backcolor = str_replace ( '#','',$backcolor);	
   $backcolor = '0x'.$backcolor;
   $frontcolor = str_replace ( '#','',$frontcolor);
   $frontcolor = '0x'.$frontcolor;
   $lightcolor = str_replace ( '#','',$lightcolor);
   $lightcolor = '0x'.$lightcolor;
   $screencolor = str_replace ( '#','',$bgcolor);
   $screencolor = '0x'.$screencolor;

   //** MEDIA VIEW HIT  Adds 1 if not submitter or admin.
   if ($xoopsUser) { 
      $user = $xoopsUser->getVar('uid'); 
      if ($user != $submitter && !$xoopsUser->isAdmin($xoopsModule->mid()) ) { 
         updateviews($lid);
      } 
   } else { 
      updateviews($lid);
   }          

   //** EMBED MEDIA PLUGIN        
   if ( file_exists(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/plugin/".$srctype.".php") ) {
      include XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/plugin/".$srctype.".php";        
   } else {
      $movie = _WS_ERROR_EMBEDPLUG;
   }

   // MEDIA HOST displays info about the embed host.  
   //  Host Variables are defined in the embed srctype plugin.   
   $hostname = !empty($hostname) ? $hostname: '';      
   $hostlink = !empty($hostlink) ? $hostlink: ''; 
   $embedmovie = array('movie' => $movie, 'hostname' => $hostname, 'hostlink' => $hostlink);

   return $embedmovie;
}


function loadMovie($lid, $playerid, $styleoption){

global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsUser;
$lid = !empty($lid) ? $lid : redirect_header("javascript:history.go(-1)",2,_WS_NOTEXIST);

$result = $xoopsDB->query("select lid, cid, title, url, srctype, listtype, listurl, entryinfo, listcache, cachetime, logourl, submitter, status, date, published, expired, player, entryperm, entrydownperm from ".$xoopsDB->prefix("webshow_links")." where lid=$lid");
list($lid, $cid, $ltitle, $url, $srctype, $listtype, $listurl, $entryinfo, $listcache, $cachetime, $logourl, $submitter, $status, $date, $published, $expired, $player, $ws_entryperm, $ws_entrydownperm) = $xoopsDB->fetchRow($result); 
$playerid = !empty($playerid) ? $playerid : $player;
$entryinfo = explode(' ', $entryinfo);

   //** VIEW HIT  Adds 1 if not submitter or admin.
   if ($xoopsUser) { 
      $user = $xoopsUser->getVar('uid'); 
      if ($user != $submitter && !$xoopsUser->isAdmin($xoopsModule->mid()) ) { 
         updateviews($lid);
      } 
   } else { 
      updateviews($lid);
   }

   //** Get player variables.
   $playervars = getPlayerVar($playerid);
   $pid = $playervars['pid'];     
   $playertitle = $playervars['playertitle'];
   if(!$styleoption) {
      $styleoption = $playervars['styleoption'];
   }
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

   //** Add the Color Variables
   $bgcolor = 's'.$lid.'.addParam("bgcolor","'.$bgcolor.'");';
   $backcolor = 's'.$lid.'.addVariable("backcolor","'.$backcolor.'");';
   $frontcolor = 's'.$lid.'.addVariable("frontcolor","'.$frontcolor.'");';
   $lightcolor = 's'.$lid.'.addVariable("lightcolor","'.$lightcolor.'");';
   $screencolor = 's'.$lid.'.addVariable("screencolor","'.$screencolor.'");';   
   if($styleoption == "1") {
      $bgcolor = "";
      $backcolor = "";
      $frontcolor = "";
      $lightcolor = "";
      $screencolor = "";
   }

   //** FLASH VARIABLES
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
  
   //** Pick the Player - pick a Jeroen Wijering Flash script according to file type and transition 
      if($transition != "0") {
         $flashplayer = "imagerotator.swf";
      }else {
         $flashplayer = "mediaplayer.swf";
      }
 
   //**Check PLAYLIST CACHE and set fileurl
   $cachereport = "";
   if($listtype != "single" || $listtype != "embed"){
      include 'playlist.php';
      $expired = timePlaylist($listcache,$cachetime);
      if($expired > 0){
         if($listtype == "dir") {     
            createPlaylist($lid);
            $listurl = XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache;
         } 
         if($listtype == "feed"){
            fetchPlaylist($lid);
         }
      }   
      $fileurl = urlencode(XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache);
   }

   $type = "";
   $singletitle = "";
   if ($listtype == 'single'){
     $singletitle = 's'.$lid.'.addVariable("title","'.$myts->htmlSpecialChars($ltitle).'");';
     // if single entry check for file extension, if no file extension add file type variable to the movie link.
     $fileurl = urlencode($listurl);
     if ($srctype != 'image'){
         $listurlext = (substr($listurl,strlen($listurl)-3,3));  /* by ivan */
        $ps = strpos($listurlext,$srctype);
         if ($ps === false) {  
             $type = 's'.$lid.'.addVariable("type","'.$srctype.'");'; 
         }
      }	
   }   
   if ($logourl != "") {
      $ps = strpos($logourl,"http://");
      if($ps === false) {
         $logourl = XOOPS_URL."/".$xoopsModuleConfig['path_logo']."/".$logourl;
      }
      $image = 's'.$lid.'.addVariable("image","'.urlencode($logourl).'");';  
   } else {
      $image ="";
   }
   if($showdigits == 0) {
       $showdigits = 's'.$lid.'.addVariable("showdigits","false");';
   } else {
      $showdigits =""; //default = true
   }
   if($showfsbutton == 0) {   
      $usefullscreen = 's'.$lid.'.addVariable("usefullscreen","false");';
   } else {
      $usefullscreen = "";//default = true
   } 
   if($scroll == 1) {        
      $scroll = 's'.$lid.'.addVariable("autoscroll","true");';
   } else { 
      $scroll = ""; //disable autoscroll. default=false
   } 
   if($largecontrol == 1) {          
      $largecontrols = 's'.$lid.'.addVariable("largecontrols","true");';
   } else { 
      $largecontrols = ""; //default = false
   }
   if($start == 0) {  
      $autostart = 's'.$lid.'.addVariable("autostart","false");';
   } else { 
      $autostart = 's'.$lid.'.addVariable("autostart","true");';
   }  
   if($shuffle == 1) {  
      $shuffle = 's'.$lid.'.addVariable("shuffle","true");';
   } else { 
      $shuffle = "";// default = false
   }      
   if($replay == 1) {  
      $replay = 's'.$lid.'.addVariable("repeat","true");';
   } else { 
      $replay = "";// default = false
   }

   //** Screen Link 
   if( $link != "0" ) {
      // linkfromdisplay turns on the Screen Link else Screen Link controls the player
      $linkfromdisplay = 's'.$lid.'.addVariable("linkfromdisplay","true");'; //default=false

      //** Screen Link Target
      if( $linktarget == "_self" ) {
         $linktarget =  's'.$lid.'.addVariable("linktarget","'.$linktarget.'");';
      } else {
         $linktarget = ""; // player default = _blank
      }

      if($link == "site"){
         $linksite = urlencode($url);
         $link = 's'.$lid.'.addVariable("link","'.$linksite.'");';
      } elseif ($link == "page"){
         $linkpage = urlencode(XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname").'/singlelink.php?lid='.$lid);
         $link = 's'.$lid.'.addVariable("link","'.$linkpage.'");';
      } 

      //** Download
      // Screen Link must be set to download.
      $downpath = '';
      $showdownload = ''; 
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
         } 
         $downpath = urlencode($downpath);
         $link = 's'.$lid.'.addVariable("link","'.$downpath.'");';
      }
   } else {
      // linkfromdisplay is off so the screen will control the player.  Turn screen link variables off.
      $link = '';
      $linkfromdisplay = '';
      $linktarget = "";
      $downpath = '';
      $showdownload = '';  
   }

   if($showicons == 0) {  
      $showicons = 's'.$lid.'.addVariable("showicons","false");';
   } else { 
      $showicons = "";// default = true
   }     
   if($stretch != "fit") {
      $overstretch = 's'.$lid.'.addVariable("overstretch","'.$stretch.'");';
   } else {
      $overstretch = ""; //default = false
   }
   if($showeq == 1) {  
      $showeq = 's'.$lid.'.addVariable("showeq","true");';
   } else { 
      $showeq = "";// default = false
   }   
   if($thumbslist == 0) {  
      $thumbsinplaylist = 's'.$lid.'.addVariable("thumbsinplaylist","false");';
   } else { 
      $thumbsinplaylist = "";// default = true
   }    
   if($rotatetime !="0") {
      $rotatetime = 's'.$lid.'.addVariable("rotatetime","'.$rotatetime.'");';
   } else {
      $rotatetime = ""; //default = 0
   }
   if($shownav != "0") { 
      $shownavigation = 's'.$lid.'.addVariable("shownavigation","true");'; 
   } else { 
      $shownavigation = "";
   }        
   if($transition != "0") {
      $transition = 's'.$lid.'.addVariable("transition","'.$transition.'");';
   } else {
      $transition = ""; //default = "0"
   }
   if($captions != "" ) {
      $captions = urlencode($captions);  
      $captions = 's'.$lid.'.addVariable("captions","'.$captions.'");';
   } else { 
      $captions = "";// default = ""
   }
   if($enablejs != 0) {          
      $enablejs = 's'.$lid.'.addVariable("enablejs","true");';   
      $jsid = 's'.$lid.'.addVariable("javascriptid","play'.$lid.'");';
   } else { 
      $enablejs = ""; //default = false
      $jsid = "";
   }
   if($playerlogo != "") {
      $playerlogo = urlencode(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/images/player/'.$playerlogo);          
      $playerlogo = 's'.$lid.'.addVariable("logo","'.$playerlogo.'");';
   } else {
      $playerlogo = "";
   }

   if($xoopsModuleConfig['callbacklog'] == 1){
      $callback = urlencode(XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname").'/callback.php');
      $callback = 's'.$lid.'.addVariable("callback","'.$callback.'");';
   }else{
      $callback = "";
   }

   if($audio != "" ) {  
      $audio = 's'.$lid.'.addVariable("audio","'.urlencode($audio).'");';
   } else { 
      $audio = "";// default = ""
   } 

   if($searchbar == 1) {
      $searchbar = 's'.$lid.'.addVariable("searchbar","true");';
      if($searchlink == ""){ 
         $searchlink = XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname").'/mediasearch.php?query=';
      }
      $searchlink = 's'.$lid.'.addVariable("searchlink","'.urlencode($searchlink).'");';
      $height = $height + 40;
   } else {
      $searchbar = "";// default = ""
      $searchlink = '';
   }

   //** Make movie Link
   $movie = '<script type="text/javascript" src="'.XOOPS_URL . '/modules/'. $xoopsModule->getVar('dirname') . '/swfobject.js"></script><div id="movie'.$lid.'"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div><script type="text/javascript">var s'.$lid.' = new SWFObject("'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/'.$flashplayer.'","play'.$lid.'","'.$width.'","'.$height.'","7");s'.$lid.'.addParam("allowfullscreen","true");'.$bgcolor.'s'.$lid.'.addVariable("file","'.$fileurl.'");s'.$lid.'.addVariable("id","'.$lid.'");s'.$lid.'.addVariable("width","'.$width.'");s'.$lid.'.addVariable("height","'.$height.'");s'.$lid.'.addVariable("displaywidth","'.$displaywidth.'");s'.$lid.'.addVariable("displayheight","'.$displayheight.'");'.$backcolor.$frontcolor.$lightcolor.$screencolor.$jsid.$type.$singletitle.$image.$showdigits.$usefullscreen.$scroll.$largecontrols.$autostart.$shuffle.$replay.$linkfromdisplay.$link.$linktarget.$showicons.$overstretch.$showeq.$thumbsinplaylist.$rotatetime.$shownavigation.$transition.$captions.$playerlogo.$enablejs.$showdownload.$callback.$audio.$searchbar.$searchlink.'s'.$lid.'.write("movie'.$lid.'");</script>';

   return $movie;
}
?>