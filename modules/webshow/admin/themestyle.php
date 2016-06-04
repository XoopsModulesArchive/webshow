<?php
// $Id: include/playerform.inc.php,v.55 06/24/2007 10:20 tcnet Exp $ //
// Flash Media Player by Jereon Wijering ( http://www.jeroenwijering.com ) is licensed under a Creative Commons License (http://creativecommons.org/licenses/by-nc-sa/2.0/) //
// It allows you to use and modify the script for noncommercial purposes. //
// For commercial use you must purchase a license from Jereon Wijering at http://www.jeroenwijering.com/?order=form. //
//
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

if( ! function_exists( 'adminMenu' ) ) {
include '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include "../language/".$xoopsConfig['language']."/main.php";
} else {
	include "../language/english/main.php";
}
include '../include/functions.php';
include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";  
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';
$myts =& MyTextSanitizer::getInstance();
$eh = new ErrorHandler;
}

/* THEME COLOR ADMIN
*Admin page for theme color editor
*/
function themeColor()
{
global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $myts;
xoops_cp_header();
adminmenu(1);
$theme = isset($_GET['theme']) ? $_GET['theme'] : $xoopsConfig['theme_set'];
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : '';	
$playerid = isset($_GET['playerid']) ? intval($_GET['playerid']) : 1;
$styleoption = 2;

$themeresult = $xoopsDB->query("select bgcolor, backcolor, frontcolor, lightcolor from ".$xoopsDB->prefix("webshow_theme")." where themetitle = \"$theme\"");
list($themebgcolor, $themebackcolor, $themefrontcolor, $themelightcolor) = $xoopsDB->fetchRow($themeresult);

echo "<table class='outer'>
<th colspan='2'>
<h3>"._WSA_THEMEEDITOR."</h3>
</th>
<tr colspan='2'><td colspan='2'>
<span style=\"font-size:90%;\">"._WSA_THEMEEDITORDSC."</span>
</td></tr>";

echo "<tr colspan='2'><td width='45%'>";
$tform = new XoopsThemeForm("Theme Color Editor", "colorform", XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/themestyle.php', 'post');
$tform->setExtra('enctype="multipart/form-data"');
//** THEME SELECT
$theme_tray = new XoopsformElementTray("<b>"._WSA_THEMEPREVIEW."</b>",'' );
// example XoopsformSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
$theme_select=new XoopsformSelect("", 'theme', $theme, 3, false, $theme);
   $theme_select->setExtra("onchange='window.location=\"themestyle.php?playerid=".$playerid."&amp;lid=".$lid."&amp;theme=\"+this.value'");
   foreach ($xoopsConfig['theme_set_allowed'] as $themeset) {
      $theme_select->addOption($themeset,$themeset,false);
   }
$theme_tray->addElement($theme_select, true);	
$tform->addElement($theme_tray);
$css_bgclr = _WSA_CSS_BGCLR;
$css_bgclr_error = "";
$css_bkclr = _WSA_CSS_BKCLR;
$css_bkclr_error = "";
$css_ftclr= _WSA_CSS_FTCLR;
$css_ftclr_error = "";
$css_ltclr= _WSA_CSS_LTCLR;
$css_ltclr_error = "";
   if($themebgcolor ==""){
      $css_bgclr_error = _WSA_CSS_MISSING;
   }
   if($themebackcolor ==""){
      $css_bkclr_error = _WSA_CSS_MISSING;
   }
   if($themefrontcolor ==""){
      $css_ftclr_error = _WSA_CSS_MISSING;
   }
   if($themelightcolor ==""){
      $css_ltclr_error = _WSA_CSS_MISSING;
   }
$tform->addElement(new XoopsformColorPicker("<b>"._WSA_PLAYBGCLR."</b><br /><span style=\"font-size: 85%; font-weight: 500;\">".$css_bgclr."</span><br /><span style=\"font-size: 80%; font-weight: 500; color: #CC0000;\">".$css_bgclr_error."</span>",'themebgcolor',$themebgcolor),false);
$tform->addElement(new XoopsformColorPicker("<b>"._WSA_DISBKCLR."</b><br /><span style=\"font-size: 85%; font-weight: 500;\">".$css_bkclr."</span><br /><span style=\"font-size: 80%; font-weight: 500; color: #CC0000;\">".$css_bkclr_error."</span>",'themebackcolor',$themebackcolor),false);
$tform->addElement(new XoopsformColorPicker("<b>"._WSA_DISFTCLR."</b><br /><span style=\"font-size: 85%; font-weight: 500;\">".$css_ftclr."</span><br /><span style=\"font-size: 80%; font-weight: 500; color: #CC0000;\">".$css_ftclr_error."</span>",'themefrontcolor',$themefrontcolor),false);
$tform->addElement(new XoopsformColorPicker("<b>"._WSA_DISLTCLR."</b><br /><span style=\"font-size: 85%; font-weight: 500;\">".$css_ltclr."</span><br /><span style=\"font-size: 80%; font-weight: 500; color: #CC0000;\">".$css_ltclr_error."</span>",'themelightcolor',$themelightcolor),false);

//**HIDDEN VARS
$tform->addElement(new XoopsformHidden('op', "themeColorS"), false);
$tform->addElement(new XoopsformHidden('playerid', $playerid), false);
$tform->addElement(new XoopsformHidden('lid', $lid), false);

// SUBMIT BUTTONS	
$themebutton_tray = new XoopsformElementTray('' ,'');
$themesubmit_btn = new XoopsformButton('', 'post', "Save Changes", 'submit');
$themebutton_tray->addElement($themesubmit_btn);
$tform->addElement($themebutton_tray);	
$tform->display();

echo "<b>"._WSA_THEMERESTORE."</b>
<br /><span style=\"font-size:90%;\">"._WSA_THEMERESTOREDSC."</span>
<br />
<form method=\"post\" action=\"themestyle.php\">
<input type=\"button\" value=\""._WSA_THEMERESET."\" onClick=\"location='themestyle.php?op=themeFilter&amp;theme=".$theme."'\">
<input type=\"button\" value=\""._WSA_THEMERESETALL."\" onClick=\"location='themestyle.php?op=themeFilterAll&amp;theme=".$theme."'\">
</form>
</td><td align='center'>";
//** PLAYER DEMO
     if($lid == ""){
         $linkresult = $xoopsDB->query("SELECT lid FROM ".$xoopsDB->prefix("webshow_links")." WHERE lid>0 and listtype != 'embed' ORDER BY date DESC LIMIT 1");
         list($lid) = $xoopsDB->fetchRow($linkresult);
      }

      include "../include/movie.inc.php";
      $movie = loadMovie($lid, $playerid, $styleoption); 
     
     //** LINK SELECT
     $linktree = new XoopsTree($xoopsDB->prefix("webshow_links"),"lid","title");    
     ob_start();
     //**function makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
     $linktree->makeMySelBox('title','lid',$lid,0,'lid','window.location="player.php?op='.$playerop.'&amp;playerid='.$playerid.'&amp;theme='.$theme.'&amp;styleoption=2&amp;lid="+this.value');
     $linkselbox = ob_get_contents();
     ob_end_clean(); 
     echo "<div class=\"item\"><div class=\"itemHead\"><h3 style=\"margin:0px; padding:0px\">"._WSA_PREVIEW."</h3></div>";
     echo "<div class=\"itemBody\" style=\"margin: 0px; padding: 5px\">".$movie;   
     echo "<br />"._WSA_PREVIEWLINK."&nbsp;&nbsp;&nbsp;".$linkselbox;
     echo "<br />"._WSA_PREVIEW_DSC."</div></div>";

//**Player Select
$playertree= new XoopsTree($xoopsDB->prefix("webshow_player"),"playerid","playertitle");
ob_start();
//**function makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
$playertree->makeMySelBox("playertitle","playertitle",$playerid,0,$playerid,'window.location="themestyle.php?theme='.$theme.'&amp;playerid="+this.value');
$playerselbox = ob_get_contents();
ob_end_clean();
echo "<br />"._WSA_PREVIEWPLAYER."&nbsp;&nbsp;&nbsp;".$playerselbox."<br />";    

echo "</td></tr></table>";	
xoops_cp_footer();
}

/* THEME TABLE
* Color editor form and movie preview
* for use in player editor
*/
function themeTable($theme,$lid,$playerid, $playerop)
{
global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $myts;
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : $lid;	
$playerid = isset($_GET['playerid']) ? intval($_GET['playerid']) : $playerid;
$theme = isset($_GET['theme']) ? $_GET['theme'] : $theme;
if($theme == ""){
$theme = isset($_POST['theme']) ? $_POST['theme'] : $xoopsConfig['theme_set'];
}
$styleoption = 2; 
$result = $xoopsDB->query("select themetitle from ".$xoopsDB->prefix("webshow_theme")." where themetitle = \"$theme\"");
list($themetitle) = $xoopsDB->fetchRow($result);
if($themetitle == "") {
themeFilter($theme);
}
$themeresult = $xoopsDB->query("select bgcolor, backcolor, frontcolor, lightcolor from ".$xoopsDB->prefix("webshow_theme")." where themetitle = \"$theme\"");
list($themebgcolor, $themebackcolor, $themefrontcolor, $themelightcolor) = $xoopsDB->fetchRow($themeresult);
echo "<table>
<th colspan='2'>"._WSA_THEMEEDITOR."</th>
<tr colspan='2'><td colspan='2'>
<span style=\"font-size:90%;\">"._WSA_THEMEEDITORDSC."</span>
</td></tr>";

echo "<tr colspan='2'><td>";
$tform = new XoopsThemeForm("", "themecolorform", XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/themestyle.php', 'post');
$tform->setExtra('enctype="multipart/form-data"');
//** THEME SELECT
$theme_tray = new XoopsformElementTray("<b>"._WSA_THEMEPREVIEW."</b>",'' );
// example XoopsformSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
$theme_select=new XoopsformSelect("", 'theme', $theme, 3, false, $theme);
   $theme_select->setExtra("onchange='window.location=\"player.php?op=".$playerop."&amp;lid=".$lid."&amp;playerid=".$playerid."&styleoption=2&amp;theme=\"+this.value'");
   foreach ($xoopsConfig['theme_set_allowed'] as $themeset) {
      $theme_select->addOption($themeset,$themeset,false);
   }
$theme_tray->addElement($theme_select, true);	
$tform->addElement($theme_tray);
$css_bgclr = _WSA_CSS_BGCLR;
$css_bgclr_error = "";
$css_bkclr = _WSA_CSS_BKCLR;
$css_bkclr_error = "";
$css_ftclr= _WSA_CSS_FTCLR;
$css_ftclr_error = "";
$css_ltclr= _WSA_CSS_LTCLR;
$css_ltclr_error = "";
   if($themebgcolor ==""){
      $css_bgclr_error = _WSA_CSS_MISSING;
   }
   if($themebackcolor ==""){
      $css_bkclr_error = _WSA_CSS_MISSING;
   }
   if($themefrontcolor ==""){
      $css_ftclr_error = _WSA_CSS_MISSING;
   }
   if($themelightcolor ==""){
      $css_ltclr_error = _WSA_CSS_MISSING;
   }
$tform->addElement(new XoopsformColorPicker("<b>"._WSA_PLAYBGCLR."</b><span style=\"font-size: 85%; font-weight: 500;\"><br />".$css_bgclr."</span><br /><span style=\"font-size: 80%; font-weight: 500; color: #CC0000;\">".$css_bgclr_error."</span>",'themebgcolor',$themebgcolor),false);
$tform->addElement(new XoopsformColorPicker("<b>"._WSA_DISBKCLR."</b><span style=\"font-size: 85%; font-weight: 500;\"><br />".$css_bkclr."</span><br /><span style=\"font-size: 80%; font-weight: 500; color: #CC0000;\">".$css_bkclr_error."</span>",'themebackcolor',$themebackcolor),false);
$tform->addElement(new XoopsformColorPicker("<b>"._WSA_DISFTCLR."</b><span style=\"font-size: 85%; font-weight: 500;\"><br />".$css_ftclr."</span><br /><span style=\"font-size: 80%; font-weight: 500; color: #CC0000;\">".$css_ftclr_error."</span>",'themefrontcolor',$themefrontcolor),false);
$tform->addElement(new XoopsformColorPicker("<b>"._WSA_DISLTCLR."</b><span style=\"font-size: 85%; font-weight: 500;\"><br />".$css_ltclr."</span><br /><span style=\"font-size: 80%; font-weight: 500; color: #CC0000;\">".$css_ltclr_error."</span>",'themelightcolor',$themelightcolor),false);

//**HIDDEN VARS
$tform->addElement(new XoopsformHidden('playerop', $playerop), false);
$tform->addElement(new XoopsformHidden('op', "themeColorS"), false);
$tform->addElement(new XoopsformHidden('playerid', $playerid), false);
$tform->addElement(new XoopsformHidden('lid', $lid), false);

// SUBMIT BUTTONS	
$themebutton_tray = new XoopsformElementTray('' ,'');
$themesubmit_btn = new XoopsformButton('', 'post', _WSA_SAVECOLORS, 'submit');
$themebutton_tray->addElement($themesubmit_btn);
$tform->addElement($themebutton_tray);	
$tform->display();
// END THEMESTYLE FORM

//** THEMESTYLE ADMIN
echo "
<form method=\"post\" action=\"themestyle.php\">
<input type=\"button\" value=\""._WSA_THEMERESTORE."\" onClick=\"location='themestyle.php?op=themeFilterOne&amp;theme=".$theme."'\">
</form>";
echo "</td>";

echo "<td align='center'>";
//** PLAYER DEMO
     if($lid == ""){
         $linkresult = $xoopsDB->query("SELECT lid FROM ".$xoopsDB->prefix("webshow_links")." WHERE lid>0 and listtype != 'embed' ORDER BY date DESC LIMIT 1");
         list($lid) = $xoopsDB->fetchRow($linkresult);
      }

      include "../include/movie.inc.php";
      $movie = loadMovie($lid, $playerid, $styleoption); 
     
     //** LINK SELECT
     $linktree = new XoopsTree($xoopsDB->prefix("webshow_links"),"lid","title");    
     ob_start();
     //**function makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
     $linktree->makeMySelBox('title','lid',$lid,0,'lid','window.location="player.php?op='.$playerop.'&amp;playerid='.$playerid.'&amp;theme='.$theme.'&amp;styleoption=2&amp;lid="+this.value');
     $linkselbox = ob_get_contents();
     ob_end_clean(); 
     echo "<div class=\"item\"><div class=\"itemHead\"><h3 style=\"margin:0px; padding:0px\">"._WSA_PREVIEW."</h3></div>";
     echo "<div class=\"itemBody\" style=\"margin: 0px; padding: 5px\">".$movie;   
     echo "<br />"._WSA_PREVIEWLINK."&nbsp;&nbsp;&nbsp;".$linkselbox;
     echo "<br />"._WSA_PREVIEW_DSC."</div></div>";

echo "</td></tr></table>";	
}

/* THEME COLOR SAVE
* Saves assigned colors
*/
function themeColorS()
{
global $xoopsDB, $eh, $myts, $xoopsModule;

$lid = isset($_POST['lid']) ? intval($_POST['lid']) : "";
$playerid = isset($_POST['playerid']) ? intval($_POST['playerid']) : "";
$playerop = isset($_POST['playerop']) ? $_POST['playerop'] : "";
$styleoption = isset($_POST['styleoption']) ? intval($_POST['styleoption']) : "";
$theme = isset($_POST["theme"]) ? $_POST["theme"] : "";
$bgcolor = $myts->addSlashes($_POST["themebgcolor"]);
$backcolor = $myts->addSlashes($_POST["themebackcolor"]);
$frontcolor = $myts->addSlashes($_POST["themefrontcolor"]);
$lightcolor = $myts->addSlashes($_POST["themelightcolor"]);
$xoopsDB->query("update ".$xoopsDB->prefix("webshow_theme")." set bgcolor='$bgcolor', backcolor='$backcolor', frontcolor='$frontcolor', lightcolor='$lightcolor' WHERE themetitle=\"$theme\"") or $eh->show("0013");
redirect_header("player.php?op=".$playerop."&amp;lid=".$lid."&amp;playerid=".$playerid."&amp;theme=".$theme."&amp;styleoption=2",3,_WSA_THEMEMODIFIED);
}

function themeFilter($theme)
{
global $xoopsDB, $xoopsConfig, $xoopsModule, $eh;
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : "1";
$playerid = isset($_GET['playerid']) ? intval($_GET['playerid']) : "1";
$playerop = isset($_GET['playerop']) ? intval($_GET['playerop']) : "";
$styleoption = isset($_GET['styleoption']) ? intval($_GET['styleoption']) : "2";
   if($theme == ""){
      redirect_header("player.php?playerop=".$playerop."&amp;lid=".$lid."&amp;playerid=".$playerid."&amp;theme=".$theme."&amp;styleoption=2",2,_WSA_THEMEFILTERFAIL);  
   }

$bgcolor = "";
$backcolor = "";
$frontcolor = "";
$lightcolor = "";

//**PLAYER COLORS FROM THEME
//$file = XOOPS_ROOT_PATH.'/themes/'.$theme.'/style.css';
$file = xoops_getcss($theme, "main");  
 if ($file) {
   $themetitle = $theme;
   $themecss = file_get_contents($file);
   //** get Body tag from style sheet
   $bodytag = preg_match("/body {(.*)}/", $themecss, $matches);
   if ($bodytag == true) {
      $bodytag = $matches['1'];
      //**get body background-color
      $bodybgcolor = preg_match("/background-color: #(.*)/", $bodytag, $matches);
      if ($bodybgcolor == true) { 
         $bodybgcolor = "#".substr ( $matches['1'], 0, 6 );     
      } else {
         $bodybgcolor = preg_match("/background: #(.*)/", $bodytag, $matches);
         if ($bodybgcolor == true) { 
            $bodybgcolor = "#".substr ( $matches['1'], 0, 6 );
         }      
      }
      $bgcolor = $bodybgcolor;
   }

      //** BACKCOLOR
      //**Filter css
      $back = preg_match("/itemHead {(.*)}/", $themecss, $matches);
      if ($back == true) {
         $back = $matches['1'];
         //**get color
         $backclr = preg_match("/background-color: #(.*)/", $back, $matches);
         if ($backclr == true) { 
            $backclr = "#".substr ( $matches['1'], 0, 6 );           
            $backcolor = $backclr;
         }
      }
 
      //** FRONT COLOR
      //** filter css
      $front = preg_match("/itemHead {(.*)}/", $themecss, $matches);
      if ($front == true) {
         $front = $matches['1'];
         //** get color
         $frontclr = preg_match("/ color: #(.*)/", $front, $matches);
         if ($frontclr == true) {
            $frontclr = "#".substr ( $matches['1'], 0, 6 );
            $frontcolor = $frontclr;              
         }   
      }
 
      //** LIGHT COLOR
      //** filter css
      $light = preg_match("/a:hover {(.*)}/", $themecss, $matches);
      if ($light == true) {
         $light = $matches['1'];
         //** get color
         $lightclr = preg_match("/color: #(.*)/", $light, $matches);
         if ($lightclr == true) {
            $lightclr = "#".substr ( $matches['1'], 0, 6 );
            $lightcolor = $lightclr;               
         }   
      }

//** if themetitle exists in db update if not insert.
$result = $xoopsDB->query("select themetitle from ".$xoopsDB->prefix("webshow_theme")." where themetitle = \"$theme\"");
list($rowexists) = $xoopsDB->fetchRow($result);
   if($rowexists == ""){
      $sql = sprintf("INSERT INTO %s (themetitle, bgcolor, backcolor, frontcolor, lightcolor) VALUES ('%s', '%s', '%s', '%s', '%s')", $xoopsDB->prefix("webshow_theme"), $themetitle, $bgcolor, $backcolor, $frontcolor, $lightcolor);
      $xoopsDB->queryF($sql) or $eh->show("0013");
   }else{
      $xoopsDB->queryF("update ".$xoopsDB->prefix("webshow_theme")." set bgcolor='$bgcolor', backcolor='$backcolor', frontcolor='$frontcolor', lightcolor='$lightcolor' WHERE themetitle=\"$theme\"") or $eh->show("0013");   
   }
 }
}

/* FILTER SINGLE THEME
* Filters a themes CSS for colors
*/
function themeFilterOne()
{
global $xoopsDB, $xoopsConfig, $xoopsModule, $eh;
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : "1";
$playerid = isset($_GET['playerid']) ? intval($_GET['playerid']) : "1";
$playerop = isset($_GET['playerop']) ? $_GET['playerop'] : "newPlayer";
$theme = isset($_GET["theme"]) ? $_GET["theme"] : $theme;
$styleoption = 2;
themeFilter($theme);
redirect_header("player.php?playerop=".$playerop."&amp;lid=".$lid."&amp;playerid=".$playerid."&amp;theme=".$theme."&amp;styleoption=2",2,_WSA_THEMEMODIFIED);  
}

/* FILTER ALL
* Filters all allowed theme CSS for colors
*/
function themeFilterAll()
{
global $xoopsDB, $xoopsConfig, $xoopsModule, $eh;
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : "1";
$playerid = isset($_GET['playerid']) ? intval($_GET['playerid']) : "1";
$playerop = isset($_GET['playerop']) ? $_GET['playerop'] : "newPlayer";
$styleoption = 2;
//** if Table exists empty it.
$result = $xoopsDB->queryF("SELECT (themeid) FROM ".$xoopsDB->prefix('webshow_player')." WHERE themeid>0");
list($themeid)=$xoopsDB->fetchRow($result);
   if ($playerid == 0) {
   $sql = sprintf("DELETE FROM %s WHERE themeid >0", $xoopsDB->prefix("webshow_theme"));
   $xoopsDB->queryF($sql) or $eh->show("0013");
   }
   
$set = array();
$set = $xoopsConfig['theme_set_allowed'];
   foreach($set as $theme){
      themeFilter($theme);
   } 
   
$theme = $xoopsConfig['theme_set'];
echo "<br />player.php?op=".$playerop."&amp;lid=".$lid."&amp;playerid=".$playerid."&amp;theme=".$theme."&amp;styleoption=2";
redirect_header("player.php?op=".$playerop."&amp;lid=".$lid."&amp;playerid=".$playerid."&amp;theme=".$theme."&amp;styleoption=2",2,_WSA_THEMEMODIFIED);  	
}

if(!isset($_POST['op'])) {
	$op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
	$op = $_POST['op'];
}

switch ($op) {

case "themeColor":
	themeColor();
	break;

case "themeColorS":
	themeColorS();
	break;

case "themeTable":
	themeTable($theme);
	break;

case "themeFilterOne":
	themeFilterOne();
	break;	
		
case "themeFilterAll":
      
	themeFilterAll();
	break;	

case "themeFilter":
      $theme = isset($_GET["theme"]) ? $_GET["theme"] : $theme;
	themeFilter($theme);
	break;		
}

?>