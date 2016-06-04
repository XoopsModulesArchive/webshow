<?php
// $Id: codebox.inc.php,v.67 2009/05/17 19:59:00 tcnet Exp $ //
// Flash Media Player by Jeroen Wijering ( http://www.jeroenwijering.com ) is licensed under a Creative Commons License (http://creativecommons.org/licenses/by-nc-sa/2.0/) //
// It allows you to use and modify the script for noncommercial purposes. //
// For commercial use you must purchase a license from Jereon Wijering at http://www.jeroenwijering.com/?order=form. //
// You must share a like any modifications. // 
// By installing and using the WebShow module you agree that you will not use it to display, distribute
// or syndicate a third party's copyright protected media without the owner's permission.  
// The WebShow software is not to be used to display or syndicate illegal or copyright protected media content.

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

// Create Links, Buttons and Code Boxes

defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

//** DOWNLOAD LINK AND BUTTON with PERMISSIONS.
$downlink = "";
$downbutton = "";
$group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
$ws_entrydownperm = explode(" ",$ws_entrydownperm);
if (count(array_intersect($group,$ws_entrydownperm)) > 0) {
  if(in_array('down',$showinfo) & in_array('down',$entryinfo)){
     if($listtype == "single"){
        //** If the file is from the wf downloads module.
        $searchstring = strToLower($listurl);
        $searchpattern = "wfdownloads/visit";
        $wfdwnok = strpos($searchstring,$searchpattern);
        if($wfdwnok){
            $downlink = "<a href=\"#\" title=\""._WS_DOWNLOAD." ".$ltitle."\" onclick= \"location='".$listurl."'\">"._WS_DOWNLOAD."</a>";
            $downbutton = "<a href=\"#\" title=\""._WS_DOWNLOAD." ".$ltitle."\" onclick= \"location='".$listurl."'\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/download.gif\" border=\"0\" alt=\""._WS_DOWNLOAD." ".$ltitle."\" /></a>"; 
        } else {
            $downlink = "<a href=\"#\" title=\""._WS_DOWNLOAD." ".$ltitle."\" onclick= \"location='".XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/wsdwnlod.php?lid=".$lid."'\">"._WS_DOWNLOAD."</a>";
            $downbutton = "<a href=\"#\" title=\""._WS_DOWNLOAD." ".$ltitle."\" onclick= \"location='".XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/wsdwnlod.php?lid=".$lid."'\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/download.gif\" border=\"0\" alt=\""._WS_DOWNLOAD." ".$ltitle."\" /></a>";
        }     
     } 
  }
}

//** MEDIA'S WEBSITE LINK, BUTTON and CODEBOX
$sitelink = '';
$sitebutton = '';
$sitecode = '';
// Site Link and Button displays if set in mod pref 'showinfo' and entry pref 'entryinfo'
if(in_array('site',$showinfo) & in_array('site',$entryinfo)){
 // If there is a site url create link and button
 if( $url != '' ){
  $sitelink = '<a href="'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/visit.php?cid='.$cid.'&amp;lid='.$lid.'" target="_blank" title="'._VISITWEBSITE.'">'._WS_WEBSITE.'</a>';
  $sitebutton = '<a href="'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/visit.php?cid='.$cid.'&amp;lid='.$lid.'" target="_blank" title="'._VISITWEBSITE.'"><img src="'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/images/link.gif" style="padding: 0 1px;" alt="'._VISITWEBSITE.'" /></a>';
   // If in mod pref 'codebox' create the code box then add link and button
   if( in_array('sitec',$codebox) ) {
      $sitecode = '<input name="wssitelink" type="text" value="'.$url.'" size ="40" onclick="javascript:focus();select();" readonly="readonly" />';     
      $sitecode = '<div class="wscodeformrow">'.$sitelink.$sitebutton.$sitecode.'</div>';
   }
 }
}

//** WEB FEED LINK, BUTTON and CODEBOX
$feedlink = '';
$feedbutton = '';
$feedcode = '';

// if this is a feed or directory create the feed link and button
if($listtype == "feed" || $listtype == "dir") {

  $feedbutton = '<a href="'.$listurl.'" target="_blank" title="'.$ltitle.'&nbsp;'._WS_WEBFEED.'"><img src="'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/images/webfeed.gif" style="padding: 0 1px;" alt="'.$ltitle.'&nbsp;'._WS_WEBFEED.'" /></a>'; 
  $feedlink = '<a href="'.$listurl.'" target="_blank" title="'.$ltitle.'&nbsp;'._WS_WEBFEED.'">'._WS_WEBFEED.'</a>'; 

  // if in mod pref 'codebox' create the code box and then add the link and button
  if ( in_array('feedc',$codebox) ) {
     $feedcode = '<input name="wsfeedlink" type="text" value="'.$listurl.'" size ="40" onclick="javascript:focus();select();" readonly="readonly" />';
     $feedcode = '<div class="wscodeformrow">'.$feedlink.$feedbutton.$feedcode.'</div>';
  }

  // Set null if 'feed' is not in mod pref 'showinfo' and in entry pref 'entryinfo'
  if(!in_array('feed',$showinfo) & !in_array('feed',$entryinfo)) {
     $feedlink = '';
     $feedbutton = '';
  }
}

//** OBJECT EMBED CODE BOX
$embed = '';
$embedbox = '';
$embedjs = '';
$embedjsbox = '';           

//Show the Object and Javascript Embed Codes for all entries except embed type.
if ($listtype != "embed"){
   // if embed is in mod pref 'codebox'
   if(in_array('emb',$codebox)){
      //** FLASH SCRIPT - pick a Jeroen Wijering Flash script according to file type and transition 
      $flashplayer = "mediaplayer.swf";
      $transition = getFV($lid,"transition");
      if($transition != "0") {
         if ( $srctype == "image" || $srctype == "feed") {
            $flashplayer = "imagerotator.swf";
         }
      }  
      $embed = "<object codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"".$width."\" height=\"".$height."\" classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\"><param value=\"".XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/".$flashplayer."\" name=\"movie\" /><param value=\"false\" name=\"menu\" /><param value=\"low\" name=\"quality\" /><param value=\"config=".XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/config.php?lid=".$lid."&amp;title=".$ltitle."\" name=\"flashvars\" /></object>";         
      $embed = $myts->htmlSpecialChars($embed);
      $embedbox = '<div class="wscodeformrow">'._WS_EMBED.'&nbsp;<input name="wsembedlink" type="text" value="'.$embed.'" size ="40" onclick="javascript:document.wscodeform.wsembedlink.focus();document.wscodeform.wsembedlink.select();" readonly="readonly" /></div>';
   }   

   //** EMBED JS CODE BOX
   // if embedJS is in mod pref 'codebox'
   if(in_array('embjs',$codebox)){ 
      $embedjs = $myts->htmlSpecialChars($movie);
      $embedjsbox = '<div class="wscodeformrow">'._WS_EMBEDLINKJS.'&nbsp;<input name="wsembedlinkjs" type="text" value="'.$embedjs.'" size ="40" onclick="javascript:document.wscodeform.wsembedlinkjs.focus();document.wscodeform.wsembedlinkjs.select();" readonly="readonly" /></div>';
   }
}

//** PERMALINK CODE BOX
$permalink = '';
$permalinkbox = '';
if(in_array('perma',$codebox)){
   $permalink = $myts->htmlSpecialChars('<a href="'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/singlelink.php?lid='.$lid.'" title="'.$ltitle.'">'.$ltitle.'</a>');
   $permalinkbox = '<div class="wscodeformrow"><a href="'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/singlelink.php?lid='.$lid.'" target="_self" title="'._WS_PAGE_VIEW.'">'._WS_PERMALINK.'</a>&nbsp;<input name="wspermalink" type="text" value="'.$permalink.'" size ="40" onclick="javascript:document.wscodeform.wspermalink.focus();document.wscodeform.wspermalink.select();" readonly="readonly" /></div>';
}

$wscodebox = $sitecode.$feedcode.$embedbox.$embedjsbox.$permalinkbox;
$xoopsTpl->assign('wscodebox',$wscodebox);
?>