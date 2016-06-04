<?php
// $Id: functions.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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

/*
//** Admin menu for xoops 2.2.4 only
function adminmenu ($currentoption = -1)
{
	$GLOBALS["xTheme"]->loadModuleAdminMenu($currentoption, "");
	return;
}
*/

//** Admin menu for 2.0.16
function adminMenu ( $currentoption = -1, $breadcrumb = '' )
{
	global $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
	$tblColors = Array();
	$tblColors[0] ='#DDE';
    $tblColors[$currentoption] = '#DDE';
    if (file_exists(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/language/'.$xoopsConfig['language'].'/modinfo.php')) {
		include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/language/'.$xoopsConfig['language'].'/modinfo.php';
    } else {
		include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/language/english/modinfo.php';
    }
	echo "<div id=\"navcontainer\"><ul style=\"padding: 3px 0; margin-left: 0; font: bold 10px Verdana, sans-serif; \">";
	echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"index.php\" style=\"padding: 3px 0.5em; margin-left: 0; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._MI_WEBSHOW_ADMENU1."</a></li>";
	echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"category.php\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._MI_WEBSHOW_ADMENU2."</a></li>";
	echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"index.php?op=newLink\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._MI_WEBSHOW_ADMENU3."</a></li>";
	echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"flashconfig.php\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._MI_WEBSHOW_ADMENU4."</a></li>";
	echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"player.php\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._MI_WEBSHOW_ADMENU5."</a></li>";
	echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"perm.php\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._MI_WEBSHOW_ADMENU6."</a></li>";
	echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"index.php?op=listNewLinks\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._MI_WEBSHOW_ADMENU7."</a></li>";
	echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"index.php?op=listBrokenLinks\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._MI_WEBSHOW_ADMENU8."</a></li>";
    echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"index.php?op=listModReq\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._MI_WEBSHOW_ADMENU9."</a></li>";
	echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"index.php?op=webShowHelp\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._MI_WEBSHOW_ADMENU10."</a></li>";    
	echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"../index.php\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._MI_WEBSHOW_ADMENU11."</a></li></ul></div><br />";
}

/**
 * Returns a module's option
 */
function webshow_getmoduleoption($option, $repmodule='webshow')
{
	global $xoopsModuleConfig, $xoopsModule;
	static $tbloptions= Array();
	if(is_array($tbloptions) && array_key_exists($option,$tbloptions)) {
		return $tbloptions[$option];
	}

	$retval=false;
	if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule && $xoopsModule->getVar('isactive'))) {
		if(isset($xoopsModuleConfig[$option])) {
			$retval= $xoopsModuleConfig[$option];
		}
	} else {
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname($repmodule);
		$config_handler =& xoops_gethandler('config');
		if ($module) {
		    $moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
	    	if(isset($moduleConfig[$option])) {
	    		$retval= $moduleConfig[$option];
	    	}
		}
	}
	$tbloptions[$option]=$retval;
	return $retval;
}

//Reusable Link Sorting Functions
function convertorderbyin($orderby) {
	switch (trim($orderby)) {
	case "titleA":
		$orderby = "title ASC";
		break;
	case "dateA":
		$orderby = "date ASC";
		break;
	case "hitsA":
		$orderby = "hits ASC";
		break;
	case "ratingA":
		$orderby = "rating ASC";
		break;
	case "votesA":
		$orderby = "votes ASC";
		break;
	case "viewsA":
		$orderby = "views ASC";
		break;
	case "lidA":
		$orderby = "lid ASC";
		break;
	case "cidA":
		$orderby = "cid ASC";
		break;
	case "cattitleA":
		$orderby = "cattitle ASC";
		break;
	case "pidA":
		$orderby = "pid ASC";
		break;
	case "titleD":
		$orderby = "title DESC";
		break;
	case "hitsD":
		$orderby = "hits DESC";
		break;
	case "ratingD":
		$orderby = "rating DESC";
		break;
	case "votesD":
		$orderby = "votes DESC";
		break;
	case "viewsD":
		$orderby = "views DESC";
		break;
	case "lidD":
		$orderby = "lid DESC";
		break;
	case "cidD":
		$orderby = "cid DESC";
		break;
	case "cattitleD":
		$orderby = "cattitle DESC";
		break;
	case "pidD":
		$orderby = "pid DESC";
		break;

	case"dateD":
	default:
		$orderby = "date DESC";
		break;
	}
	return $orderby;
}

function convertorderbytrans($orderby) {
            if ($orderby == "hits ASC")   $orderbyTrans = ""._WS_PAGEHITSLTOM."";
            if ($orderby == "hits DESC")    $orderbyTrans = ""._WS_PAGEHITSMTOL."";
            if ($orderby == "title ASC")    $orderbyTrans = ""._WS_TITLEATOZ."";
            if ($orderby == "title DESC")   $orderbyTrans = ""._WS_TITLEZTOA."";
            if ($orderby == "date ASC") $orderbyTrans = ""._WS_DATEOLD."";
            if ($orderby == "date DESC")   $orderbyTrans = ""._WS_DATENEW."";
            if ($orderby == "rating ASC")  $orderbyTrans = ""._WS_RATINGLTOH."";
            if ($orderby == "rating DESC") $orderbyTrans = ""._WS_RATINGHTOL."";
            if ($orderby == "votes ASC")   $orderbyTrans = ""._WS_VOTESLTOM."";
            if ($orderby == "votes DESC")    $orderbyTrans = ""._WS_VOTESMTOL."";
            if ($orderby == "views ASC")   $orderbyTrans = ""._WS_VIEWSLTOM."";
            if ($orderby == "views DESC")    $orderbyTrans = ""._WS_VIEWSMTOL."";
            if ($orderby == "lid ASC")   $orderbyTrans = ""._WS_LIDLTOH."";
            if ($orderby == "lid DESC")    $orderbyTrans = ""._WS_LIDHTOL."";
            if ($orderby == "cid ASC")   $orderbyTrans = ""._WS_LIDLTOH."";
            if ($orderby == "cid DESC")    $orderbyTrans = ""._WS_LIDHTOL."";
            if ($orderby == "pid ASC")   $orderbyTrans = ""._WS_PARENTLTOH."";
            if ($orderby == "pid DESC")    $orderbyTrans = ""._WS_PARENTHTOL."";
            if ($orderby == "cattitle ASC")    $orderbyTrans = ""._WS_CATTITLEATOZ."";
            if ($orderby == "cattitle DESC")   $orderbyTrans = ""._WS_CATTITLEZTOA."";

            return $orderbyTrans;
}

function convertorderbyout($orderby) {
            if ($orderby == "title ASC")            $orderby = "titleA";
            if ($orderby == "title DESC")              $orderby = "titleD";
            if ($orderby == "date ASC")            $orderby = "dateA";
            if ($orderby == "date DESC")            $orderby = "dateD";
            if ($orderby == "hits ASC")          $orderby = "hitsA";
            if ($orderby == "hits DESC")          $orderby = "hitsD";
            if ($orderby == "rating ASC")        $orderby = "ratingA";
            if ($orderby == "rating DESC")        $orderby = "ratingD";
            if ($orderby == "votes ASC")   $orderby = "votesA";
            if ($orderby == "votes DESC")    $orderby = "votesD";
            if ($orderby == "views ASC")   $orderby = "viewsA";
            if ($orderby == "views DESC")    $orderby = "viewsD";
            if ($orderby == "lid ASC")   $orderby = "lidA";
            if ($orderby == "lid DESC")    $orderby = "lidD";
            if ($orderby == "cid ASC")   $orderby = "cidA";
            if ($orderby == "cid DESC")    $orderby = "cidD";
            if ($orderby == "pid ASC")   $orderby = "pidA";
            if ($orderby == "pid DESC")    $orderby = "pidD";
            if ($orderby == "cattitle ASC")   $orderby = "cattitleA";
            if ($orderby == "cattitle DESC")    $orderby = "cattitleD";            
            return $orderby;
}

//updates rating data in itemtable for a given item
function updaterating($sel_id){
        global $xoopsDB;
        $query = "select rating FROM ".$xoopsDB->prefix("webshow_votedata")." WHERE lid = ".$sel_id."";
        $voteresult = $xoopsDB->query($query);
            $votesDB = $xoopsDB->getRowsNum($voteresult);
        $totalrating = 0;
            while(list($rating)=$xoopsDB->fetchRow($voteresult)){
                $totalrating += $rating;
            }
        $finalrating = $totalrating/$votesDB;
        $finalrating = number_format($finalrating, 4);
        $query =  "UPDATE ".$xoopsDB->prefix("webshow_links")." SET rating=$finalrating, votes=$votesDB WHERE lid = $sel_id";
        $xoopsDB->queryF($query) or exit();
}

//returns the total number of items in items table that are accociated with a given table $table id
function getTotalItems($sel_id, $status=""){
        global $xoopsDB, $mytree;
        $count = 0;
        $arr = array();
        $query = "select count(*) from ".$xoopsDB->prefix("webshow_links")." where cid=".$sel_id."";
        if($status!=""){
                $query .= " and status>=$status";
        }
        $result = $xoopsDB->query($query);
        list($thing) = $xoopsDB->fetchRow($result);
        $count = $thing;
        $arr = $mytree->getAllChildId($sel_id);
        $size = count($arr);
        for($i=0;$i<$size;$i++){
                $query2 = "select count(*) from ".$xoopsDB->prefix("webshow_links")." where cid=".$arr[$i]."";
                if($status!=""){
                        $query2 .= " and status>=$status";
                }
                $result2 = $xoopsDB->query($query2);
                list($thing) = $xoopsDB->fetchRow($result2);
                $count += $thing;
        }
        return $count;
}

/**
 * Meta keywords automatic's creation
 *  Original Code by Herve Thoussard (hervet)
 */
function webshow_extract_keywords($content)
{
	global $xoopsModuleConfig;
	$tmp=array();
	$myts =& MyTextSanitizer::getInstance();
	$content = str_replace ("<br />", " ", $content);
	$content = strip_tags($content);
	$content = $myts->undoHtmlSpecialChars($content);
	$content = strtolower($content);
	$search_pattern = array("&nbsp;","\t","\r\n","\r","\n",",",".","'",";",":",")","(",'"','?','!','{','}','[',']','<','>','/','+','-','_','\\','*');
	$replace_pattern = array(' ',' ',' ',' ',' ',' ',' ',' ','','','','','','','','','','','','','','','','','','','');
	$content = str_replace($search_pattern, $replace_pattern, $content);
	$keywords = explode(' ',$content);
	$keywords = array_unique($keywords);	
    // filter for length and common words.  Set in mod preference
	$commonwords = str_replace(array(",","|")," ", $xoopsModuleConfig['ws_keyword_common']);
	foreach($keywords as $keyword) {
	    // Keyword Length Limit 
		if(strlen($keyword)>=$xoopsModuleConfig['ws_keyword_limit'] && !is_numeric($keyword)) {
	        // Remove common words.
	        $strpos = strpos($commonwords,$keyword);	    
			if ($strpos === false) {
			      $tmp[]=$keyword;			
			}					              		
		}             	      	
	}
	
      // Keyword Count
	$tmp = array_slice($tmp,0,$xoopsModuleConfig['ws_keyword_count']);	

	if(count($tmp)>0) {
		return implode(' ',$tmp);	
	} else {
		if(!isset($config_handler) || !is_object($config_handler)) {
			$config_handler =& xoops_gethandler('config');
		}
		$xoopsConfigMetaFooter =& $config_handler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
		return $xoopsConfigMetaFooter['meta_keywords'];
	}
}

//** Update Page Hits
// Adds 1 to hits if not submitter or admin.
function updatehits($lid){ 
        global $xoopsDB; 
        $sql = sprintf("UPDATE %s SET hits = hits+1 WHERE lid = %u AND status > 0", $xoopsDB->prefix("webshow_links"), $lid); 
        $xoopsDB->queryF($sql) or exit(); 
} 

//** Update Views
// Adds 1 to views if not submitter or admin.
function updateviews($lid){
   global $xoopsDB;
     $sql = sprintf("UPDATE %s SET views = views+1 WHERE lid = %u", $xoopsDB->prefix("webshow_links"), $lid);
     $xoopsDB->queryF($sql);
}

function autoPublish()
{
   global $xoopsDB, $eh;
   $result=$xoopsDB->query("select lid, published from ".$xoopsDB->prefix("webshow_links")." where date=0 order by lid");
   while(list($lid, $published) = $xoopsDB->fetchRow($result)) {				
      if(time() > $published) {
          $xoopsDB->queryF("update ".$xoopsDB->prefix("webshow_links")." set status=1, date=".$published." where lid=".$lid."")  or $eh->show("0013");					
      }
   }
}

function autoExpire()
{
   global $xoopsDB, $eh;
   $result=$xoopsDB->query("select lid, expired from ".$xoopsDB->prefix("webshow_links")." where expired>0 order by lid");
   while(list($lid, $expired) = $xoopsDB->fetchRow($result)) {				
      if($expired <= time() & $expired > 0) {
		$xoopsDB->queryF("update ".$xoopsDB->prefix("webshow_links")." set status=-2, expired=0 where lid=".$lid."") or $eh->show("0013");					
      }
   }
}

//***** PLAYER STUFF

//** Check that default playerid1 exists, if not create it.
function player1Exist()
{
	global $xoopsDB;
	$player1result = $xoopsDB->queryF("SELECT (playerid) FROM ".$xoopsDB->prefix('webshow_player')." WHERE playerid = 1");
	list($playerid)=$xoopsDB->fetchRow($player1result);
    if ($playerid == 0) {	
         //** Get the default variables
         $playerid = 1;
         $playervars = getPlayerVardefault();
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
         include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php'; 
         $eh = new ErrorHandler;
         $xoopsDB->queryF("INSERT INTO " . $xoopsDB->prefix('webshow_player') . " VALUES ($playerid, $pid, '$playertitle', $styleoption, '$bgcolor', '$backcolor', '$frontcolor', '$lightcolor', $width, $height, $displaywidth, $displayheight, $showdigits, $showfsbutton, $scroll, $largecontrol);") or $eh->show("0013");
   }
   return '';
}


//** Get the Player Default Variables.
function getPlayerVardefault()
{
   global $xoopsDB, $eh;  	        
   $myts =& MyTextSanitizer::getInstance();
   // Initialize Player Variables
      //$playerid = '0';
      $pid = '0';
      $playertitle = 'Default';
      $styleoption = '1'; //Monochrome JWPlayer default
      $bgcolor = '';
      $backcolor = '';
      $frontcolor = '';
      $lightcolor = '';
      $width = '320';
      $height = '240';
      $displaywidth = '320';
      $displayheight = '180';
      $showdigits = '1';
      $showfsbutton = '1';
      $scroll = '0';
      $largecontrol = '0';
      $searchbar = '0';
      $searchlink = '';
   $playervars = array("pid" => $pid, "playertitle" => $playertitle, "styleoption" => $styleoption, "bgcolor" => $bgcolor, "backcolor" => $backcolor, "frontcolor" => $frontcolor, "lightcolor" => $lightcolor, "width" => $width, "height" => $height, "displaywidth" => $displaywidth, "displayheight" => $displayheight, "showdigits" => $showdigits, "showfsbutton" => $showfsbutton, "scroll" => $scroll, "largecontrol" => $largecontrol, "searchbar" => $searchbar, "searchlink" => $searchlink);
   return $playervars;
}

//** GET PLAYER VARIABLES
// Get the players flash variables, if they dont exist, get the default variables, 
// if the default row doesn't exist initialize the varibles, write them to row 0 and the entrys row, then return the values.
function getPlayerVar($playerid)
{
   global $xoopsDB, $eh;
   $playerid = !empty($playerid) ? $playerid : 1;
   $myts =& MyTextSanitizer::getInstance();
   //** GET the PLAYER VARIABLES
   $result = $xoopsDB->queryF("select playerid, pid, playertitle, styleoption, bgcolor, backcolor, frontcolor, lightcolor, width, height, displaywidth, displayheight, showdigits, showfsbutton, scroll, largecontrol, searchbar, searchlink from ".$xoopsDB->prefix("webshow_player")." where playerid=$playerid");
   list($playerid, $pid, $playertitle, $styleoption, $bgcolor, $backcolor, $frontcolor, $lightcolor, $width, $height, $displaywidth, $displayheight, $showdigits, $showfsbutton, $scroll, $largecontrol, $searchbar, $searchlink)=$xoopsDB->fetchRow($result);
   if (!$playerid) {
   // Player doesn't exist so get default player 1	        
      $result2 = $xoopsDB->queryF("select pid, playertitle, styleoption, bgcolor, backcolor, frontcolor, lightcolor, width, height, displaywidth, displayheight, showdigits, showfsbutton, scroll, largecontrol, searchbar, searchlink from ".$xoopsDB->prefix("webshow_player")." where playerid=1") or $eh->show("0013");
      list($pid, $playertitle, $styleoption, $bgcolor, $backcolor, $frontcolor, $lightcolor, $width, $height, $displaywidth, $displayheight, $showdigits, $showfsbutton, $scroll, $largecontrol, $searchbar, $searchlink)=$xoopsDB->fetchRow($result2);
      if (!$xoopsDB->getRowsNum($result2)>0) { 
         //** Get the default variables
         // The default player doesn't exist so get the default variables
         $playervars = getPlayerVardefault();
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
      }
   }
   // create the Player Variable array and return
   $playervars = array("playerid" => $playerid, "pid" => $pid, "playertitle" => $myts->htmlSpecialChars($playertitle), "styleoption" => $styleoption, "bgcolor" => $bgcolor, "backcolor" => $backcolor, "frontcolor" => $frontcolor, "lightcolor" => $lightcolor, "width" => $width, "height" => $height, "displaywidth" => $displaywidth, "displayheight" => $displayheight, "showdigits" => $showdigits, "showfsbutton" => $showfsbutton, "scroll" => $scroll, "largecontrol" => $largecontrol, "searchbar" => $searchbar, "searchlink" => $searchlink);
   return $playervars;
}

//******* FLASH VARIABLES
//** Check for the Flash Variable Default row exists.
function getFVdefault()
{
   global $xoopsDB, $eh;  	        
   $myts =& MyTextSanitizer::getInstance();
   // Initialize Flash Variables
   $start = 0;
   $shuffle = 1;
   $replay = 0;
   $link = 0;
   $linktarget = "_blank";
   $showicons = 1;
   $stretch = "false";
   $showeq = 0;
   $rotatetime = 5;
   $shownav = 0;
   $transition = 0;
   $thumbslist = 1;
   $captions = "";
   $enablejs = 1;
   $playerlogo = "";
   $audio = "";
   $flashvars = array("start" => $start, "shuffle" =>$shuffle, "replay" => $replay, "link" => $myts->htmlSpecialChars($link), "linktarget" => $myts->htmlSpecialChars($linktarget), "showicons" => $showicons, "stretch" => $myts->htmlSpecialChars($stretch), "showeq" => $showeq, "rotatetime" => $rotatetime, "shownav" => $shownav, "transition" => $myts->htmlSpecialChars($transition), "thumbslist" => $thumbslist, "captions" => $myts->htmlSpecialChars($captions), "enablejs" => $enablejs, "playerlogo" => $myts->htmlSpecialChars($playerlogo), "audio" => $myts->htmlSpecialChars($audio));
   return $flashvars;
}

//** GET FLASH VARIABLES
// Get the entry flash variables, if they dont exist, get the default variables, 
// if the default db row doesn't exist use the JW Player defaults.
function getFV($lid,$flashvar)
{
  global $xoopsDB, $eh;
  $myts =& MyTextSanitizer::getInstance();

  if ($lid != "") {   
     if ($flashvar) {
        //** Query db for the entry FLASH VARIABLES
        $result = $xoopsDB->queryF("select $flashvar from ".$xoopsDB->prefix("webshow_flashvar")." where lid=$lid") or $eh->show("0013");
        list($flashvar)=$xoopsDB->fetchRow($result);        
        return $flashvar;     
     } else {
        //** Query db for the entry FLASH VARIABLES
        $result = $xoopsDB->queryF("select lid, start, shuffle, replay, link, linktarget, showicons, stretch, showeq, rotatetime, shownav, transition, thumbslist, captions, enablejs, playerlogo, audio from ".$xoopsDB->prefix("webshow_flashvar")." where lid=$lid") or $eh->show("0013");
        list($lid, $start, $shuffle, $replay, $link, $linktarget, $showicons, $stretch, $showeq, $rotatetime, $shownav, $transition, $thumbslist, $captions, $enablejs, $playerlogo, $audio)=$xoopsDB->fetchRow($result);
     }
  }else{  
     //** Query db row 0 for modified default FLASH VARIABLES 
     $result = $xoopsDB->queryF("select lid, start, shuffle, replay, link, linktarget, showicons, stretch, showeq, rotatetime, shownav, transition, thumbslist, captions, enablejs, playerlogo, audio from ".$xoopsDB->prefix("webshow_flashvar")." where lid=0") or $eh->show("0013");
     list($lid, $start, $shuffle, $replay, $link, $linktarget, $showicons, $stretch, $showeq, $rotatetime, $shownav, $transition, $thumbslist, $captions, $enablejs, $playerlogo, $audio)=$xoopsDB->fetchRow($result);
  }

  if (empty($result)) {
     // GET DEFAULT FLASH VARIABLES
     $flashvars = getFVdefault();          
  } else {
     // create the FV array
     $flashvars = array("start" => $start, "shuffle" =>$shuffle, "replay" => $replay, "link" => $myts->htmlSpecialChars($link), "linktarget" => $myts->htmlSpecialChars($linktarget), "showicons" => $showicons, "stretch" => $myts->htmlSpecialChars($stretch), "showeq" => $showeq, "rotatetime" => $rotatetime, "shownav" => $shownav, "transition" => $myts->htmlSpecialChars($transition), "thumbslist" => $thumbslist, "captions" => $myts->htmlSpecialChars($captions), "enablejs" => $enablejs, "playerlogo" => $myts->htmlSpecialChars($playerlogo), "audio" => $myts->htmlSpecialChars($audio));
  }
  return $flashvars; 
}

function commentCount ($lid)
{
  global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
  $com_modid = $xoopsModule->getVar('mid');
  $comresult = $xoopsDB->query("SELECT count(*) FROM ".$xoopsDB->prefix("xoopscomments")." WHERE com_modid=$com_modid AND com_item_id=$lid ");
  $com_count = $xoopsDB->getRowsNum($comresult);
  $comments = $com_count;
  return $comments;
}

//** Frontside ADMIN Table

function wsadminTable($lid) {
  global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsUser;
  $myts =& MyTextSanitizer::getInstance();
  $tableon = '';
  
  if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
   if ( file_exists(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/language/".$xoopsConfig['language']."/admin.php") ) {
	 include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/language/".$xoopsConfig['language']."/admin.php";
   } else {
	 include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/language/english/admin.php";
   }

   if ($lid){
      $query = "lid=$lid";
   } else {
      $query = "lid>0";
   }

   $admintable = "<table class='outer' style='font-size: 90%;'><th colspan='4'>"._WSA_ADMINTABLE."</th>
   <tr class='head' colspan='4'><td>"._WSA_STATUS."</td><td>"._WSA_ID."</td><td>"._WSA_MEDIATITLE."</td><td>"._WSA_STATUSREPORT."</td></tr>"; 

   $amresult = $xoopsDB->query("SELECT lid, title, status, date, submitter, published, expired FROM ".$xoopsDB->prefix("webshow_links")." WHERE status<1 and $query ORDER BY date DESC");
   while(list($lid, $title, $status, $date, $submitter, $published, $expired) = $xoopsDB->fetchRow($amresult)) {
      $tableon = 1;
      $title = $myts->htmlSpecialChars($title);
      if($status == 0){
         $statusreport = "<span style='color: #FFCC00;'>"._WSA_STATUSWAITING." | "._WSA_SUBMITTER.": ".$submitter." | ".formatTimestamp($date,"m")."</span>";
         $statuslink = "admin/index.php?op=listNewLinks";
         $statusicon = "waiting.gif";
      }   	      
      if($status == -1){
         $statusreport = "<span style='color: #FF0000;'>"._WSA_STATUSAUTO.": ".formatTimestamp($published,'m')."</span>";
         $statuslink = "admin/index.php?op=modLink&amp;lid=".$lid;
         $statusicon = "offline.gif";    
      }		
      if($status == -2){
         $statusreport = "<span style='color: #FF0000;'>"._WSA_STATUSEXPIRED.": ". formatTimestamp($expired,'m')."</span>";
         $statuslink = "admin/index.php?op=modLink&amp;lid=".$lid;
         $statusicon = "offline.gif";    
      }
      if($status == -3){
         $statusreport = "<span style='color: #FF0000;'>"._WSA_STATUSNOMEDIAURL."</span>";
         $statuslink = "admin/index.php?op=modLink&amp;lid=".$lid;
         $statusicon = "offline.gif";    
      }
      if($status == -4){
         $statusreport = "<span style='color: #FF0000;'>"._WSA_STATUSNOCACHE."</span>";
         $statuslink = "admin/index.php?op=modLink&amp;lid=".$lid;
         $statusicon = "offline.gif";    
      }
      if($status == -5){
         $statusreport = "<span style='color: #FF0000;'>"._WSA_STATUSABUSE."</span>";   
         $statuslink = "admin/index.php?op=listBrokenLinks";
         $statusicon = "offline.gif";    
      }	
      $admintable .= "<tr colspan='4'>
      <td class = 'even'><a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/".$statuslink."\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/".$statusicon."\" /></a></td>
      <td class = 'odd'>".$lid."</td>
      <td class = 'even'><a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/singlelink.php?lid=$lid\" target=\"_self\" title=\""._WS_ADMIN."\">".$title."</a></td>
      <td class = 'odd'><a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/".$statuslink."\">".$statusreport."</a></td>
      </tr>";
   }

   //** MOD REQUESTED
   $modresult = $xoopsDB->query("SELECT lid, title FROM ".$xoopsDB->prefix("webshow_mod")." WHERE $query");
   while(list($lid, $title) = $xoopsDB->fetchRow($modresult)) {
      $tableon = 1;      
      $statusreport =  "<span style=\"color: #009900;\">"._WS_USERMODREQ."</span>";
      $statuslink = "admin/index.php?op=listModReq";
      $title = $myts->htmlSpecialChars($title);
      $admintable .= "<tr colspan='4'>
      <td class = 'even'><a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/".$statuslink."\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/online.gif\" /></a></td>
      <td class = 'odd'>".$lid."</td>
      <td class = 'even'>
      <a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/".$statuslink."\" target=\"_self\" title=\""._WS_MODIFY."\">".$title."</a>
      </td>
      <td class = 'odd'>
      <a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/".$statuslink."\">".$statusreport."</a>     
      </td></tr>";     				 
   }

   //** Abuse/Broken Report
   $reportresult = $xoopsDB->query("SELECT lid, rpttype, rptname FROM ".$xoopsDB->prefix("webshow_broken")." WHERE $query");
   while(list($lid, $rpttype, $rptname) = $xoopsDB->fetchRow($reportresult)) {
      $tableon = 1;      
      $admintable .= "<tr colspan='4'>
      <td class = 'odd'>---</td>
      <td class = 'odd'>".$lid."</td>
      <td class = 'odd'>---</td>
      <td class = 'odd'>
      <a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/admin/index.php?op=listBrokenLinks\"><span style=\"color: #009900;\">".$rptname." "._WS_REPORT."</a>     
      </td></tr>";     				 
   }
   
   $admintable .= "</table>";

   // if no results clear the table 
   if ( !$tableon ){
      $admintable = '';
   }
   return $admintable;
 }else {
   break;
 }
}

?>