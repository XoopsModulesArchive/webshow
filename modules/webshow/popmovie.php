<?php
// $Id: popmovie.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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

include 'header.php';
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$lid = intval($_GET['lid']);

//** Get Playlist data
$result = $xoopsDB->query("select cid, title, srctype, listtype, listurl, listcache, cachetime, entryinfo, logourl, player, entryperm from ".$xoopsDB->prefix("webshow_links")." where lid=$lid and status>0");
list($cid, $title, $srctype, $listtype, $listurl, $listcache, $cachetime, $entryinfo, $logourl, $playerid, $ws_entryperm) = $xoopsDB->fetchRow($result);

//** Category Permission
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler =& xoops_gethandler('groupperm');
// example checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
if (!$gperm_handler->checkRight('webshow_view', $cid, $groups, $xoopsModule->getVar('mid'))) {
   echo"<script language=JavaScript>self.close(); </script>";
   exit();
}

//** Entry Permission
$group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
$ws_entryperm = explode(" ",$ws_entryperm);
if (count(array_intersect($group,$ws_entryperm)) < 1){
   echo"<script language=JavaScript>self.close(); </script>";
   exit();
}

$ltitle = $myts->htmlSpecialChars($title);

//** Load the flash player.
$movielid = $lid;
$playerid = !empty($playerid) ? $playerid : 1;
$playerresult = $xoopsDB->query("select backcolor, frontcolor, width, height from ".$xoopsDB->prefix("webshow_player")." where playerid=$playerid");
list($backcolor, $frontcolor, $width, $height) = $xoopsDB->fetchRow($playerresult);

   if ($listtype == "embed"){
       $mplayjs = "";
       include "plugin/".$srctype.".php";
       //** VIEW HIT  Adds 1 if not submitter or admin.
       if ($xoopsUser) { 
          if ($submitter = $xoopsUser->getVar('uid') | !$xoopsUser->isAdmin($xoopsModule->mid())) { 
             $viewhit = '<SCRIPT LANGUAGE="Javascript" SRC="callback.php?lid=' . $lid . '"></SCRIPT>';  
          } 
       } else { 
          $viewhit = '<SCRIPT LANGUAGE="Javascript" SRC="callback.php?lid=' . $lid . '"></SCRIPT>'; 
       }     
   $movie = $viewhit.'<div>'.$movie.'</div>';
   }else{ 
       $flashresult = $xoopsDB->query("select transition from ".$xoopsDB->prefix("webshow_flashvar")." where lid=$movielid");
       list($transition)=$xoopsDB->fetchRow($flashresult);
       //** FLASH SCRIPT - pick a Jeroen Wijering Flash script according to file type and transition 
       if($transition != "0" & $srctype == "image") {
          $flashplayer = "imagerotator.swf";
       } else {
          $flashplayer = "mediaplayer.swf";
       }
       $movie = '<script type="text/javascript" src="'.XOOPS_URL . '/modules/'. $xoopsModule->getVar('dirname') . '/swfobject.js"></script><div id="play'.$movielid.'"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div><script type="text/javascript">var s'.$movielid.' = new SWFObject("'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/'.$flashplayer.'","play'.$movielid.'","'.$width.'","'.$height.'","7");s'.$movielid.'.addParam("allowfullscreen","true");s'.$movielid.'.addVariable("title","'.$ltitle.'");s'.$movielid.'.addVariable("id","'.$movielid.'");s'.$movielid.'.addVariable("config","'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/config.php?lid='.$movielid.'");s'.$movielid.'.write("play'.$movielid.'");</script>';	
   }



//** NEW WINDOW
//** Set popup dimensions according to movie.inc.php dimensions  
$popwidth= $width + 20;
$popheight= $height + 90;
$pagestyle = XOOPS_URL.'/themes/'.$xoopsConfig['theme_set'].'/style.css';		
echo '<html>
   <head>
<meta http-equiv="content-type" content="text/html; charset='._CHARSET.'" />
<meta name="robots" content="noindex,nofollow,noarchive" />
<meta http-equiv="content-language" content="'._LANGCODE.'" />
<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/xoops.css" />
<link rel="stylesheet" type="text/css" media="screen" href="'.$pagestyle.'" />
<title>'.$ltitle.'</title>';
echo "</head><body class=\"item\" align=\"left\" onLoad=\"resizeTo($popwidth,$popheight), self.focus()\">";
echo "<div class=\"itemHead\" style=\"float: left; width: ".$width."px; margin: 5px 5px 0px 0px;\">
         <div class=\"itemTitle\" style=\"float: left; width: ".$width."px; text-align: center; font-size: 90%;\"><a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/singlelink.php?lid=".$lid."\" target=\"_blank\" title=\""._WS_VIEWMEDIA.": ".$ltitle."\" >".$ltitle."</a></div>
         </div>
          <div class=\"itemBody\" style=\"width: ".$width."px; text-align: center;\">".$movie."</div>";
echo "</body></html>";  
exit();
?>