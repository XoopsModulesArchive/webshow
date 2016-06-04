<?php
// $Id: admin/player.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
include '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include "../language/".$xoopsConfig['language']."/main.php";
} else {
	include "../language/english/main.php";
}
include '../include/functions.php';
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";  
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';
$eh = new ErrorHandler;
$myts =& MyTextSanitizer::getInstance();
include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
$playertree= new XoopsTree($xoopsDB->prefix("webshow_player"),"playerid","playertitle");
$linktree = new XoopsTree($xoopsDB->prefix("webshow_links"),"lid","title");

//**Player Menu
function menuPlayer()
{
global $xoopsDB, $xoopsModule;
$playerid = isset($_GET['playerid']) ? intval($_GET['playerid']) : 1;
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : '';
$styleoption = "";
$lang_styleoption = "";
echo "<table class='outer' style='font-size: 90%;'>
<tr><th colspan='6'>"._WSA_PLAYERMGMT."</th></tr>
<tr class='head' colspan='6'>
<td class='even' width='30px'><b>"._WSA_ID."</b></td>
<td class='odd' width='232 px'><b>"._WSA_PLAYERTITLE."</b></td>
<td class='even' width='165px'><b>"._WSA_PLAYERSTYLE."</b></td>
<td class='odd' width='55px'><b>"._WSA_WIDTH."</b></td>
<td class='even' width='50px'><b>"._WSA_HEIGHT."</b></td>
<td class='odd' width='195px'><input type=\"button\" value=\""._WSA_ADDNEWPLAYER."\" onClick=\"location='player.php?lid=$lid'\"></td></tr>
<tr><td colspan='6'><div style ='overflow: auto; height: 90px; width: 100%;'><table>";

$result=$xoopsDB->query("select playerid, playertitle, styleoption, width, height from ".$xoopsDB->prefix("webshow_player")." where playerid>0 order by playertitle");
while(list($playerid, $playertitle, $styleoption, $width, $height) = $xoopsDB->fetchRow($result)) {
if($styleoption == 1){
$lang_styleoption = _WSA_PLAYERSTYLE_OPT1;
}
if($styleoption == 2){
$lang_styleoption = _WSA_PLAYERSTYLE_OPT2;
}
if($styleoption == 3){
$lang_styleoption = _WSA_PLAYERSTYLE_OPT3;
}
if($styleoption == 4){
$lang_styleoption = _WSA_PLAYERSTYLE_OPT4;
}

echo "<tr><td class='even' width='30px' align='right'>".$playerid."</td>
<td class='odd' width='250px'>".$playertitle."</td>
<td class='even' width='180px'>".$lang_styleoption."</td>
<td class='odd' width='60px' align='right'>".$width."</td>
<td class='even' width='60px' align='right'>".$height."</td>
<td class='odd' width='180px'>
<input type=\"button\" value=\""._WS_MODIFY."\" onClick=\"location='player.php?op=modPlayer&amp;playerid=$playerid&amp;lid=$lid'\">
<input type=\"button\" value=\""._WSA_CLONE."\" onClick=\"location='player.php?op=clonePlayer&amp;playerid=$playerid&amp;lid=$lid'\">";
   if ($playerid != 1) {
      echo "<input type=\"button\" value=\""._DELETE."\" onClick=\"location='player.php?op=delPlayer&amp;playerid=$playerid&amp;lid=$lid'\"></td></tr>";
   }
 }		
echo "</table></div></td></tr></table><br />";
}
	
//** NEW PLAYER EDITOR
function newPlayer()
{
   global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsTpl, $myts, $eh, $linktree;
   xoops_cp_header();
   adminmenu(1);
   player1Exist();
   $lid = isset($_GET['lid']) ? intval($_GET['lid']) : '';	
   $playerid = isset($_GET['playerid']) ? intval($_GET['playerid']) : 1;

//** Get player variables.
         $playervars = getPlayerVar($playerid);
         $pid = $playervars['pid'];     
         //$playertitle = $playervars['playertitle'];
         $playertitle = "";
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

   $styleoption = isset($_GET['styleoption']) ? intval($_GET['styleoption']) : $styleoption;

   //** THEME FROM PLAYER
   //** Uses the color style from this player for the pages color style.
   if ( $styleoption == '4' ) {
     include_once "../include/playerstyle.inc.php";
     echo $themefromplayer;       
   }

   //** NEW PLAYER TABLE
   echo "<table class='outer'><tr><td colspan='2'>";
   menuPlayer();
   echo "</td></tr>";
   echo "<tr><td width='40%'>";
   //** NEW PLAYER FORM	
   $formlabel = _WSA_NEWPLAYER." "._WSA_EDITOR;
   $op = "newPlayerS";
   $btnlabel = _WSA_ADDNEWPLAYER;
   echo "<div>";  	
   include '../include/playerform.inc.php';
   echo "</div>";
   echo "</td><td align='center'>";

  //** THEMESTYLE
   // style option 2: Colors from theme css
   if($styleoption == 2) {
      $theme = isset($_GET['theme']) ? $_GET['theme'] : $xoopsConfig['theme_set'];
      //** THEME COLOR EDITOR
      include 'themestyle.php';      
      //** example themeTable($theme,$lid,$playerid,$playerop);
      themeTable($theme,$lid,$playerid,"modPlayer");
   }else{
      $linkresult = "";
      $movie = "";
      if($lid == ""){
         $linkresult = $xoopsDB->query("SELECT lid FROM ".$xoopsDB->prefix("webshow_links")." WHERE lid>0 and listtype != 'embed' ORDER BY date DESC LIMIT 1");
         list($lid) = $xoopsDB->fetchRow($linkresult);
      }
        
      if(!empty($lid)){
         include "../include/movie.inc.php";
         $movie = loadMovie($lid, $playerid, $styleoption); 
         
         //** LINK SELECT
         $linktree = new XoopsTree($xoopsDB->prefix("webshow_links"),"lid","title");    
         ob_start();
         //**function makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
         $linktree->makeMySelBox('title','lid',$lid,0,'lid','window.location="player.php?op=newPlayer&amp;styleoption='.$styleoption.'&amp;lid="+this.value');
         $linkselbox = ob_get_contents();
         ob_end_clean(); 
         echo "<div class=\"item\"><div class=\"itemHead\"><h3 style=\"margin:0px; padding:0px\">"._WSA_PREVIEW."</h3></div>";
         echo "<div class=\"itemBody\" style=\"margin: 0px; padding: 5px\">".$movie;   
         echo "<br />"._WSA_PREVIEWLINK."&nbsp;&nbsp;&nbsp;".$linkselbox;
         echo "<br />"._WSA_PREVIEW_DSC."</div>";
      } else {
         echo _WSA_STATUSEMPTYDIR;      
      }
   }
echo "</div></td></tr></table>";
xoops_cp_footer();
}

//**CLONE PLAYER EDITOR
function clonePlayer()
{
global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsTpl, $myts, $eh;
xoops_cp_header();
adminmenu(1);
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : '';	
$playerid = isset($_GET['playerid']) ? intval($_GET['playerid']) : redirect_header("javascript:history.go(-1)",2,_WS_NOTEXIST);
$playertitle = "";	

//** Get player variables.
         $playervars = getPlayerVar($playerid);
         $pid = $playervars['pid'];     
         $playertitle = $playervars['playertitle'];
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

$styleoption = isset($_GET['styleoption']) ? intval($_GET['styleoption']) : $styleoption;
//** THEME FROM PLAYER
//** Uses the color style from this player for the pages color style.
if ( $styleoption == '4' ) {
  include_once "../include/playerstyle.inc.php";
  echo $themefromplayer;       
}

echo "<table class='outer'><tr><td colspan='2'>";
menuPlayer();
echo "</td></tr><tr><td width='40%'>";

//** CLONE PLAYER FORM	
$formlabel = _WSA_CLONE." "._WSA_PLAYER;
$op = "clonePlayerS";
$btnlabel = _WSA_ADDNEWPLAYER;
include '../include/playerform.inc.php';

echo "</td><td align='center'>";
//** PLAYER DEMO
  //** THEMESTYLE
   // style option 2: Colors from theme css
   if($styleoption == 2) {
      $theme = isset($_GET['theme']) ? $_GET['theme'] : $xoopsConfig['theme_set'];
      //** THEME COLOR EDITOR
      include 'themestyle.php';      
      //** example themeTable($theme,$lid,$playerid,$playerop);
      themeTable($theme,$lid,$playerid,"modPlayer");
   }else{
      if($lid == ""){
         $linkresult = $xoopsDB->query("SELECT lid FROM ".$xoopsDB->prefix("webshow_links")." WHERE lid>0 and listtype != 'embed' ORDER BY date DESC LIMIT 1");
         list($lid) = $xoopsDB->fetchRow($linkresult);
      }
        $movie = "";
      if($lid !=""){
        include "../include/movie.inc.php";
        $movie = loadMovie($lid, $playerid, $styleoption);
      }     
     //** LINK SELECT
     $linktree = new XoopsTree($xoopsDB->prefix("webshow_links"),"lid","title");    
     ob_start();
     //**function makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
     $linktree->makeMySelBox('title','lid',$lid,0,'lid','window.location="player.php?op=clonePlayer&amp;lid="+this.value');
     $linkselbox = ob_get_contents();
     ob_end_clean(); 
     echo "<div class=\"item\"><div class=\"itemHead\"><h3 style=\"margin:0px; padding:0px\">"._WSA_PREVIEW."</h3></div>";
     echo "<div class=\"itemBody\" style=\"margin: 0px; padding: 5px\">".$movie;   
     echo "<br />"._WSA_PREVIEWLINK."&nbsp;&nbsp;&nbsp;".$linkselbox;
     echo "<br />"._WSA_PREVIEW_DSC."</div></div>";
  }
echo "</td></tr></table>";	
}

function newPlayerS()
{
global $xoopsDB, $xoopsModule, $xoopsTpl, $myts, $eh;
$pid = isset($_POST['pid']) ? intval($_POST['pid']) : "";  //Unused atm
$playertitle = isset($_POST['playertitle']) ? $_POST['playertitle'] : "";
   if (empty($playertitle)) {
   redirect_header("player.php",2,_WSA_ERRORTITLE);
   exit();
   }

$playertitleresult=$xoopsDB->queryF("SELECT (playertitle) from ".$xoopsDB->prefix("webshow_player")." WHERE playertitle=\"$playertitle\"");
list($origplayertitle)=$xoopsDB->fetchRow($playertitleresult);
   if ( $origplayertitle == $playertitle ) {
   redirect_header("player.php",2,_WSA_ERROREXIST);
   exit();
   }
$playertitle = $myts->addSlashes($playertitle);
$styleoption = $myts->addSlashes(intval($_POST["styleoption"]));
$bgcolor = $myts->addSlashes($_POST["bgcolor"]);	
$backcolor = $myts->addSlashes($_POST["backcolor"]);
$frontcolor = $myts->addSlashes($_POST["frontcolor"]);
$lightcolor = $myts->addSlashes($_POST["lightcolor"]);
$displayheight = $myts->addSlashes(intval($_POST["displayheight"]));
$showdigits = $myts->addSlashes(intval($_POST["showdigits"]));	    
$showfsbutton = $myts->addSlashes(intval($_POST["showfsbutton"]));     
$scroll = $myts->addSlashes(intval($_POST["scroll"]));
$width = $myts->addSlashes(intval($_POST["width"]));
$height = $myts->addSlashes(intval($_POST["height"]));
$displaywidth = $myts->addSlashes(intval($_POST["displaywidth"]));
$largecontrol = $myts->addSlashes(intval($_POST["largecontrol"]));
$searchbar = $myts->addSlashes(intval($_POST["searchbar"]));
$searchlink = $myts->addSlashes($_POST["searchlink"]);
$newid = $xoopsDB->genId($xoopsDB->prefix("webshow_player")."_playerid_seq");

$sql = sprintf("INSERT INTO %s (playerid, pid, playertitle, styleoption, bgcolor, backcolor, frontcolor, lightcolor, displaywidth, displayheight, showdigits, showfsbutton, scroll, width, height, largecontrol, searchbar, searchlink) VALUES (%u, %u, '%s', %u, '%s', '%s', '%s', '%s', %u, %u, %u, %u, %u, %u, %u, %u, '%s', %u)", $xoopsDB->prefix("webshow_player"), $newid, $pid, $playertitle, $styleoption, $bgcolor, $backcolor, $frontcolor, $lightcolor, $displaywidth, $displayheight, $showdigits, $showfsbutton, $scroll, $width, $height, $largecontrol, $searchbar, $searchlink);
$xoopsDB->query($sql) or $eh->show("0013");	
   if ($newid == 0) {
       $newid = $xoopsDB->getInsertId();
   }
redirect_header("player.php?op=modPlayer&amp;playerid=".$newid."",3,_WSA_NEWPLAYERADDED);				
}

//** MODIFY PLAYER
function modPlayer()
{
global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsTpl, $xoopsModuleConfig, $myts, $eh;
xoops_cp_header();
adminmenu(1);
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : '';
$playerid = isset($_GET['playerid']) ? intval($_GET['playerid']) : redirect_header("javascript:history.go(-1)",2,_WS_NOTEXIST);

//** Get player variables.
         $playervars = getPlayerVar($playerid);
         $pid = $playervars['pid'];     
         $playertitle = $playervars['playertitle'];
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
$styleoption = isset($_GET['styleoption']) ? intval($_GET['styleoption']) : $styleoption;

//** THEME FROM PLAYER
//** Uses the color style from this player for the pages color style.
if ( $styleoption == '4' ) {
  include_once "../include/playerstyle.inc.php";
  echo $themefromplayer;       
}
        
echo "<table class='outer'>";
echo "<tr><td colspan='2'>";
menuPlayer();
echo "</td></tr>";
echo "<tr><td width='35%'>";

//** MODIFY PLAYER FORM	
$formlabel = _WSA_MODPLAYER." ".$playertitle;	
$op = "modPlayerS";
$btnlabel = _WSA_MODPLAYER;
include '../include/playerform.inc.php';

echo "</td><td align='center'>";
//** PLAYER DEMO 
  //** THEMESTYLE
   // style option 2: Colors from theme css
   if($styleoption == 2) {
      $theme = isset($_GET['theme']) ? $_GET['theme'] : $xoopsConfig['theme_set'];
      //** THEME COLOR EDITOR
      include 'themestyle.php';      
      //** example themeTable($theme,$lid,$playerid,$playerop);
      themeTable($theme,$lid,$playerid,"modPlayer");
   }else{
      if($lid == ""){
         $linkresult = $xoopsDB->query("SELECT lid FROM ".$xoopsDB->prefix("webshow_links")." WHERE lid>0 and listtype != 'embed' ORDER BY date DESC LIMIT 1");
         list($lid) = $xoopsDB->fetchRow($linkresult);
      }
        $movie = "";
      if($lid !=""){
        include "../include/movie.inc.php";
        $movie = loadMovie($lid, $playerid, $styleoption); 
      }
     //** LINK SELECT
     $linktree = new XoopsTree($xoopsDB->prefix("webshow_links"),"lid","title");    
     ob_start();
     //**function makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
     $linktree->makeMySelBox('title','lid',$lid,0,'lid','window.location="player.php?op=modPlayer&amp;playerid='.$playerid.'&amp;lid="+this.value');
     $linkselbox = ob_get_contents();
     ob_end_clean(); 
     echo "<div class=\"item\"><div class=\"itemHead\"><h3 style=\"margin:0px; padding:0px\">"._WSA_PREVIEW."</h3></div>";
     echo "<div class=\"itemBody\" style=\"margin: 0px; padding: 5px\">".$movie;   
     echo "<br />"._WSA_PREVIEWLINK."&nbsp;&nbsp;&nbsp;".$linkselbox;
     echo "<br />"._WSA_PREVIEW_DSC."</div></div>";
  }
xoops_cp_footer();
}

function modPlayerS()
{
global $xoopsDB, $myts, $eh, $xoopsModule;
$lid = isset($_POST['lid']) ? intval($_POST['lid']) : "";
$playerid = intval($_POST['playerid']);
$pid = ""; //$pid = intval($_POST['pid']);  //unused atm
$playertitle = isset($_POST['playertitle']) ? $_POST['playertitle'] : "";
   if (empty($playertitle)) {
   redirect_header("player.php",2,_WSA_ERRORTITLE);
   exit();
   }

$playertitle = $myts->addSlashes($playertitle);
$styleoption = $myts->addSlashes(intval($_POST["styleoption"]));
$bgcolor = $myts->addSlashes($_POST["bgcolor"]);
$backcolor = $myts->addSlashes($_POST["backcolor"]);
$frontcolor = $myts->addSlashes($_POST["frontcolor"]);
$lightcolor = $myts->addSlashes($_POST["lightcolor"]);
$displayheight = $myts->addSlashes(intval($_POST["displayheight"]));
$showdigits = $myts->addSlashes(intval($_POST["showdigits"]));	    
$showfsbutton = $myts->addSlashes(intval($_POST["showfsbutton"]));     
$scroll = $myts->addSlashes(intval($_POST["scroll"]));
$width = $myts->addSlashes(intval($_POST["width"]));
$height = $myts->addSlashes(intval($_POST["height"]));
$displaywidth = $myts->addSlashes(intval($_POST["displaywidth"]));
$largecontrol = $myts->addSlashes(intval($_POST["largecontrol"]));
$searchbar = $myts->addSlashes(intval($_POST["searchbar"]));
$searchlink = $myts->addSlashes($_POST["searchlink"]);

$xoopsDB->query("update ".$xoopsDB->prefix("webshow_player")." set pid='$pid', playertitle='$playertitle', styleoption='$styleoption', bgcolor='$bgcolor', backcolor='$backcolor', frontcolor='$frontcolor', lightcolor='$lightcolor', displayheight='$displayheight', showdigits='$showdigits', showfsbutton = '$showfsbutton', scroll='$scroll', width='$width', height='$height', displaywidth='$displaywidth', largecontrol='$largecontrol', searchbar='$searchbar', searchlink='$searchlink' WHERE playerid=$playerid") or $eh->show("0013");
redirect_header("player.php?op=modPlayer&amp;lid=".$lid."&amp;playerid=".$playerid,3,_WSA_PLAYERMODIFIED);	
}

function delPlayer()
{
   	global $xoopsDB, $eh, $xoopsModule;
   	$playerid =  isset($_POST['playerid']) ? intval($_POST['playerid']) : intval($_GET['playerid']);
	$ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;
      if ( $ok == 1 ) {
		$sql = sprintf("DELETE FROM %s WHERE playerid = %u", $xoopsDB->prefix("webshow_player"), $playerid);
	    $xoopsDB->query($sql) or $eh->show("0013");
        redirect_header("player.php",1,_WSA_PLAYERDELETED);
		exit();		
       } else {
		xoops_cp_header();
		xoops_confirm(array('op' => 'delPlayer', 'playerid' => $playerid, 'ok' => 1), 'player.php', _WSA_PLAYERWARNING);
		xoops_cp_footer();
      }
}

if(!isset($_POST['op'])) {
	$op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
	$op = $_POST['op'];
}

switch ($op) {
case "menuPlayer":
	menuPlayer();
	break;

case "newPlayer":
	newPlayer();
	break;

case "newPlayerS":
	newPlayerS();
	break;	

case "modPlayer":
	modPlayer();
	break;

case "modPlayerS":
	modPlayerS();
	break;

case "clonePlayer":
	clonePlayer();
	break;	

case "clonePlayerS":
	newPlayerS();
	break;	
	
case "delPlayer":
	delPlayer();
	break;		
	
case "main":
default:
	newPlayer();
	break;
}
?>