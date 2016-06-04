<?php
// $Id: webshow_playit.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
/******************************************************************************
 * Function: b_webshow_spotlight_show
 * Input   : $options[0] = Choose to select a playlist by hits, rating, random or specific link.
 *           $options[1] = Select a specific playlist
 *           $options[2]   = Show title
  *           $options[3]   = Show logo
   *           $options[4]   = Show description
    *           $options[5]   = Show popup player link
 * Output  : Displays the link content and loads it in the flash player
 ******************************************************************************/
if (!defined('XOOPS_ROOT_PATH')){ exit(); }
function b_webshow_playit_show($options) {
 global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
 include_once XOOPS_ROOT_PATH.'/modules/webshow/include/functions.php';
 $myts =& MyTextSanitizer::getInstance();
 $path_webshow = XOOPS_URL."/modules/webshow";
 $path_logo = XOOPS_URL."/uploads/webshow/logo";

// if (!is_object($GLOBALS['xoopsModule']) || $GLOBALS['xoopsModule']->getVar('dirname') != "webshow") {
 // $modhandler = &xoops_gethandler('module');
 // $module = &$modhandler->getByDirname("webshow");
//  $config_handler = &xoops_gethandler('config');
//  $moduleConfig = &$config_handler->getConfigsByCat(0,$module->getVar('mid'));
//} else {
//  $moduleConfig =& $GLOBALS['xoopsModuleConfig'];
//}
//$wsmodname = $moduleConfig['dirname'];

//** View Permissions
// $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
// $gperm_handler =& xoops_gethandler('groupperm');

$block = array();

//** Sort by
   if($options[0] == "link"){
      //** SPECIFIC
      $lid = $options[1];
      $where = "and lid = ".$lid." LIMIT 1";
   }elseif($options[0] == "random"){
       //** RANDOM
       //** Category Limit         
       if($options['2'] != 0 ){
          list ( $numrows ) = $xoopsDB -> fetchRow( $xoopsDB -> query ( "SELECT COUNT(*) FROM ".$xoopsDB->prefix("webshow_links")." WHERE status > 0 and cid = ".$options[2].""));
       }else{
          list ( $numrows ) = $xoopsDB -> fetchRow( $xoopsDB -> query ( "SELECT COUNT(*) FROM ".$xoopsDB->prefix("webshow_links")." WHERE status > 0 and cid > 0 "));
       }
	$numrows = $numrows - 1;
	mt_srand((double)microtime()*1000000);
	$entrynumber = mt_rand(0, $numrows);
      $where = "LIMIT ".$entrynumber.", 1";
   }else{
      //** OTHER SORTS date, rating, hits, vote
      $where = "ORDER BY ".$options[0]." DESC LIMIT 1";
   }

   if($options['2'] != 0 ){
       //** Category Limit   
       $result = $xoopsDB ->query("SELECT lid, cid, title, listurl, srctype, listtype, listcache, entryinfo, logourl, date, hits, rating, votes, entryperm, views FROM ".$xoopsDB->prefix("webshow_links")." WHERE status > 0 and cid = ".$options[2]." ".$where."");
   }else{
       $result = $xoopsDB->query("SELECT lid, cid, title, listurl, srctype, listtype, listcache, entryinfo, logourl, date, hits, rating, votes, entryperm, views FROM ".$xoopsDB->prefix('webshow_links')." WHERE status>0 and cid>0 ".$where."");
   }

   $myrow = $xoopsDB->fetchArray($result);

   //** get latest entry if random fetch is empty. 
   if($myrow['lid'] == ""){
     if($options['2'] != 0 ){  
       $result = $xoopsDB ->query("SELECT lid, cid, title, listurl, srctype, listtype, listcache, entryinfo, logourl, date, hits, rating, votes, entryperm, views FROM ".$xoopsDB->prefix("webshow_links")." WHERE status > 0 and cid = ".$options[2]." ORDER BY date DESC LIMIT 1");
     }else{
       $result = $xoopsDB->query("SELECT lid, cid, title, listurl, srctype, listtype, listcache, entryinfo, logourl, date, hits, rating, votes, entryperm, views FROM ".$xoopsDB->prefix('webshow_links')." WHERE status>0 and cid>0 ORDER BY date DESC LIMIT 1");
     }
     $myrow = $xoopsDB->fetchArray($result);
   }

 //**Group Category Permissions - Needs work.
 //** example checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
//  if (!$gperm_handler->checkRight('webshow_view', $myrow['cid'], $groups, $xoopsModule->getVar('mid'))) {
//     $myrow = $xoopsDB->fetchArray($result); //** ??   fetch array again if no perm in category
//  }
 
 //** Entry Permission - Needs work
//$group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
//$ws_entryperm = explode(" ",$ws_entryperm);
//if (count(array_intersect($group,$ws_entryperm)) > 0)
//{
//       $myrow = $xoopsDB->fetchArray($result); //** ?? . Fetch again if no entry perm
//   }

  $link = array();
  $link['path'] = $path_webshow;
  $link['id'] = $myrow['lid'];

  //** SHOW TITLE
  $link['title'] = "";
  $title = $myts->htmlSpecialChars($myrow["title"]);
  if ( $options[4] == 1) {	
     $link['title'] = $myts->htmlSpecialChars($myrow["title"]);
  } 

  //** Option 5 ENTRY LOGO
  $link['logo'] = "";
  $logourl = $myrow['logourl'];
  if($options[5] == 1){
     if($logourl != '') {
        $ps = strpos($logourl,"http://");
        if($ps === false) {
           $logourl = $path_logo."/".$logourl;
        }       
     }else{
        $logourl = $path_logo."/stock/logo.gif";
     }
     $link['logo'] = $logourl;
  }

  //** Option 6 Description  
  $link['description'] = "";
  if ( $options[6] == 1) {	
    list ( $description ) = $xoopsDB -> fetchRow( $xoopsDB -> query ( "SELECT description FROM ".$xoopsDB->prefix("webshow_text")." WHERE lid =".$link['id']." "));
    $link['description'] = $myts->displayTarea($description,0);
  }
  
  //** OPTION 7 POPUP BUTTON.	
  //** Java script opens popmovie.php in a new window.
  $link['popmovie'] = "";
  if($options[7] == 1) {
    $link['popmovie'] = 1;
  }

  //** OPTION 8 Show STATISTICS
  $link['stats'] = "";
  if ( $options[8] == 1) {
         $link['stats'] = 1;
         $link['date'] = formatTimestamp($myrow['date'],'s');
         $link['hits'] = $myrow['hits'];
         $link['views'] = $myrow['views'];         
         $link['rating'] = number_format($myrow['rating'], 2);
         $link['votes'] = $myrow['votes'];
  } else {
         $link['stats'] = "";
         $link['date'] = "";
         $link['hits'] =  "";
         $link['views'] =  "";           
         $link['rating'] = "";
         $link['votes'] = "";
  }

   //** Option 3 SHOW MOVIE
   $link['movie'] = ""; 
   if($options[3] != "0") {
      //** Load the flash player.
      $movielid = $link['id']; 
      $playerid = $options[3];
      //** LISTTYPE and LISTURL
      $listtype = $myrow['listtype'];
      $listurl = $myrow['listurl'];
      $srctype = $myrow['srctype'];
      //$listcache = $myrow['listcache'];
      $playerresult = $xoopsDB->query("select bgcolor, backcolor, frontcolor, lightcolor, width, height, displaywidth, displayheight, styleoption from ".$xoopsDB->prefix("webshow_player")." where playerid=$playerid");
      list($bgcolor, $backcolor, $frontcolor, $lightcolor, $width, $height, $displaywidth, $displayheight, $styleoption) = $xoopsDB->fetchRow($playerresult);
      if ($listtype == "embed"){
         //** Set the Color Variables.
         $backcolor = str_replace ( '#','',$backcolor);	
         $backcolor = '0x'.$backcolor;
         $frontcolor = str_replace ( '#','',$frontcolor);
         $frontcolor = '0x'.$frontcolor;
         $lightcolor = str_replace ( '#','',$lightcolor);
         $lightcolor = '0x'.$lightcolor;
         $screencolor = str_replace ( '#','',$bgcolor); 
         $screencolor = '0x'.$screencolor;
         $mplayjs = "";
         include XOOPS_ROOT_PATH."/modules/webshow/plugin/".$srctype.".php";
         $movie = '<SCRIPT LANGUAGE="Javascript" SRC="'.$path_webshow.'/callback.php?lid=' . $movielid . '"></SCRIPT><div style="width: '.$width.'px; float: left;">'.$movie.'</div>';
      }else{ 
         $flashresult = $xoopsDB->query("select transition from ".$xoopsDB->prefix("webshow_flashvar")." where lid=$movielid");
         list($transition)=$xoopsDB->fetchRow($flashresult);
         //** FLASH SCRIPT - pick a Jeroen Wijering Flash script according to file type and transition 
         if($transition != "0" & $srctype == "image") {
             $flashplayer = "imagerotator.swf";
         } else {
             $flashplayer = "mediaplayer.swf";
         }
         $movie = '<script type="text/javascript" src="'.$path_webshow.'/swfobject.js"></script><div id="play'.$movielid.'" style="width: '.$width.'px ; float: left;"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div><script type="text/javascript">var s'.$movielid.' = new SWFObject("'.$path_webshow.'/'.$flashplayer.'","play'.$movielid.'","'.$width.'","'.$height.'","7");s'.$movielid.'.addParam("allowfullscreen","true");s'.$movielid.'.addVariable("title","'.$title.'");s'.$movielid.'.addVariable("image","'.urlencode($logourl).'");s'.$movielid.'.addVariable("id","'.$movielid.'");s'.$movielid.'.addVariable("width","'.$width.'");s'.$movielid.'.addVariable("height","'.$height.'");s'.$movielid.'.addVariable("displaywidth","'.$displaywidth.'");s'.$movielid.'.addVariable("displayheight","'.$displayheight.'");s'.$movielid.'.addVariable("config","'.$path_webshow.'/config.php?lid='.$movielid.'");s'.$movielid.'.write("play'.$movielid.'");</script>';	
      }
      $link['movie'] = $movie;      
   }      
   $block['links'][] = $link;
   return $block;
}

//**********************************
//** PLAY IT BLOCK ADMIN
//**********************************
function b_webshow_playit_edit($options) {
global $xoopsDB, $xoopsConfig;
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";

//** Play It Form
$form = _MB_WEBSHOW_SORTDSC.'<br /><br />';
//** OPTION 0  SPOTLIGHT selected by sort.   	  
$form .= _MB_WEBSHOW_SORT."&nbsp;<select name='options[]'>";     
$form .= "<option value='date'";
    if ( $options[0] == "date" ) {
        $form .= " selected='selected'";
    }
    $form .= ">"._MB_WEBSHOW_DATE."</option>\n";

    $form .= "<option value='hits'";
    if($options[0] == "hits"){
        $form .= " selected='selected'";
    }
    $form .= ">"._MB_WEBSHOW_HITS."</option>";

    $form .= "<option value='rating'";
    if ( $options[0] == "rating" ) {
        $form .= " selected='selected'";
    }
    $form .= ">" . _MB_WEBSHOW_RATING . "</option>";

    $form .= "<option value='random'";
    if ( $options[0] == "random" ) {
        $form .= " selected='selected'";
    }
    $form .= ">" . _MB_WEBSHOW_RANDOM . "</option>";

    $form .= "<option value='link'";
    if ( $options[0] == "link" ) {
        $form .= " selected='selected'";
    }
    $form .= ">"._MB_WEBSHOW_SPECIFIC."</option>\n";    
    
    $form .= "</select><br /><br />\n";

//** OPTION1 Link select box
$linktree = new XoopsTree($xoopsDB->prefix("webshow_links"),"lid","title");    
$form .=_MB_WEBSHOW_SPECIFIC."&nbsp;&nbsp;&nbsp;";	
ob_start();
//** example makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
$linktree->makeMySelBox("title","title",$options[1],1,"options[1]");
$linkselbox = ob_get_contents();
ob_end_clean();
$form .= $linkselbox."<br /><br />";

//**OPTION2 Category Select
$cattree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","pid");    
$form .= _MB_WEBSHOW_CATLIMIT;	
	ob_start();	
	$cattree->makeMySelBox("cattitle","cattitle",$options[2],1,"options[2]");
	$catselbox = ob_get_contents();
	ob_end_clean();
	$form .= $catselbox."<br /><br />";

//**OPTION3 Player Select Box
$playertree = new XoopsTree($xoopsDB->prefix("webshow_player"),"playerid","playertitle");    
$form .=_MB_WEBSHOW_PLAYER."&nbsp;&nbsp;&nbsp;";	
ob_start(); 
$playertree->makeMySelBox("playertitle","playerid",$options[3],1,"options[3]");
$playerselbox = ob_get_contents();	
ob_end_clean();		 
$form .= $playerselbox;
$form .=_MB_WEBSHOW_PLAYERDSC."&nbsp;&nbsp;&nbsp;<br /><br />";

//** OPTION4 Show Title
$form .= "<input type='radio' name='options[4]' value='1'";
    if ($options[4] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
$form .= "<input type='radio' name='options[4]' value='0'";
    if ($options[4] == 0) {
        $form .= " checked='checked'";
    }
    $form .= " />"._NO."&nbsp;&nbsp;&nbsp;"._MB_WEBSHOW_SHOWLINKTITLE."<br /><br />";
    
//**OPTION5 Show Logo
$form .= "<input type='radio' name='options[5]' value='1'";
    if ($options[5] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
$form .= "<input type='radio' name='options[5]' value='0'";
    if ($options[5] == 0) {
        $form .= " checked='checked'";
    }
    $form .= " />"._NO."&nbsp;&nbsp;&nbsp;"._MB_WEBSHOW_SHOWLINKLOGO."<br /><br />";

//**OPTION6 Show DESCRIPTION
$form .= "<input type='radio' name='options[6]' value='1'";
    if ($options[6] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
$form .= "<input type='radio' name='options[6]' value='0'";
    if ($options[6] == 0) {
        $form .= " checked='checked'";
    }
    $form .= " />"._NO."&nbsp;&nbsp;&nbsp;"._MB_WEBSHOW_SHOWLINKDSC."<br /><br />";

//**OPTION7 Show Pop Up Player Link
$form .= "<input type='radio' name='options[7]' value='1'";
   if ($options[7] == 1) {
        $form .= " checked='checked'";
   }
    $form .= " />"._YES;
$form .= "<input type='radio' name='options[7]' value='0'";
    if ($options[7] == 0) {
        $form .= " checked='checked'";
    }
    $form .= " />"._NO."&nbsp;&nbsp;&nbsp;"._MB_WEBSHOW_SHOWPOPUP."<br /><br />";

//** OPTION8 Show the link statistics
    $form .= "<input type='radio' name='options[8]' value='1'";
    if ($options[8] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
    $form .= "<input type='radio' name='options[8]' value='0'";
    if ($options[8] == 0) {
      $form .= " checked='checked'";
    }
    $form .= " />"._NO."&nbsp;&nbsp;&nbsp"._MB_WEBSHOW_SHOWLINKSTATS;	
return $form;
}
?>