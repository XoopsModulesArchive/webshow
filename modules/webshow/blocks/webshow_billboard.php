<?php
// $Id: webshow_billboard.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
 * Function: b_webshow_billboard_show
 * Input   : $options[0] = Select links by sort options or specific link.
 *           $options[1] = Select a specific playlist
 *           $options[2]   = Show title
  *           $options[3]   = Show logo
   *           $options[4]   = Show description
    *           $options[5]   = Show popup player link
    *           $options[6]   = Show Statistics
 * Output  : Features a playlist in a block.
 ******************************************************************************/
if (!defined('XOOPS_ROOT_PATH')){ exit(); }
 
function b_webshow_billboard_show($options) {
   global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
   $myts =& MyTextSanitizer::getInstance();
   $path_webshow = XOOPS_URL."/modules/webshow"; 
   $path_logo = XOOPS_URL."/uploads/webshow/logo"; 
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
       $result = $xoopsDB ->query("SELECT lid, cid, title, listurl, srctype, listtype, listcache, logourl, date, hits, views, rating, votes, entryperm FROM ".$xoopsDB->prefix("webshow_links")." WHERE status > 0 and cid = ".$options[2]." ".$where."");
   }else{
       $result = $xoopsDB->query("SELECT lid, cid, title, listurl, srctype, listtype, listcache, logourl, date, hits, views, rating, votes, entryperm FROM ".$xoopsDB->prefix('webshow_links')." WHERE status>0 and cid>0 ".$where."");
   }
     
 while($myrow = $xoopsDB->fetchArray($result)){
  $link = array();
  $link['id'] = $myrow['lid'];

  $link['title'] = "";    
  if ( $options[3] == 1) {	
  $link['title'] = $myts->htmlSpecialChars($myrow["title"]);  
  }

  //** Option 4 ENTRY LOGO
  $link['logo'] = "";
  $logourl = $myrow['logourl'];
  if($options[4] == 1){
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

  $link['description'] = "";  
  if ( $options[5] == 1) {	      	
    list ( $description ) = $xoopsDB -> fetchRow( $xoopsDB -> query ( "SELECT description FROM ".$xoopsDB->prefix("webshow_text")." WHERE lid =".$link['id']." "));
    $link['description'] = $myts->displayTarea($description,0);	
  }		
  
  //**POPUP LINK
  if($options[6] == 1) {
    $link['popmovie'] = 1;
  } 

  //** OPTION 7 Show STATISTICS
  if ( $options[7] == 1) {
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

   $block['links'][] = $link;
   } 
return $block;
}

function b_webshow_billboard_edit($options) {
global $xoopsDB, $xoopsConfig;
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
  
$form = _MB_WEBSHOW_BILLBOARD.'<br /><br />';
//** OPTION0  BILLBOARD selected by sort.
  $form = _MB_WEBSHOW_SORTDSC.'<br /><br />';   	  
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

    $form .= "<option value='views'";
    if($options[0] == "views"){
        $form .= " selected='selected'";
    }
    $form .= ">"._MB_WEBSHOW_VIEWS."</option>";

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
//** Example makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
$linktree->makeMySelBox('title', 'title',$options[1],1,'options[1]');
$linkselbox = ob_get_contents();
ob_end_clean();
$form .= $linkselbox."<br /><br />";

//**Category Select
$cattree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","pid");    
$form .=_MB_WEBSHOW_CATLIMIT;		
ob_start();
$cattree->makeMySelBox("cattitle", "cattitle",$options[2],1,'options[2]');
$catselbox = ob_get_contents();
ob_end_clean();
$form .= $catselbox."<br /><br />";

//** OPTION3 Show Title
$form .= "&nbsp;&nbsp;&nbsp;<input type='radio' name='options[3]' value='1'";
    if ($options[3] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
$form .= "<input type='radio' name='options[3]' value='0'";
    if ($options[3] == 0) {
        $form .= " checked='checked'";
    }
    $form .= " />"._NO."&nbsp;&nbsp;&nbsp;"._MB_WEBSHOW_SHOWLINKTITLE."<br /><br />";
    
//**OPTION4 Show Logo
$form .= "&nbsp;&nbsp;&nbsp;<input type='radio' name='options[4]' value='1'";
    if ($options[4] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
$form .= "<input type='radio' name='options[4]' value='0'";
    if ($options[4] == 0) {
        $form .= " checked='checked'";
    }
    $form .= " />"._NO."&nbsp;&nbsp;&nbsp;"._MB_WEBSHOW_SHOWLINKLOGO."<br /><br />";

//**OPTION5 Show DESCRIPTION
$form .= "&nbsp;&nbsp;&nbsp;<input type='radio' name='options[5]' value='1'";
    if ($options[5] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
$form .= "<input type='radio' name='options[5]' value='0'";
    if ($options[5] == 0) {
        $form .= " checked='checked'";
    }
    $form .= " />"._NO."&nbsp;&nbsp;&nbsp;"._MB_WEBSHOW_SHOWLINKDSC."<br /><br />";

//**OPTION 6 Show Pop Up Player Link
$form .= "&nbsp;&nbsp;&nbsp;<input type='radio' name='options[6]' value='1'";
    if ($options[6] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
$form .= "<input type='radio' name='options[6]' value='0'";
    if ($options[6] == 0) {
        $form .= " checked='checked'";
    }
    $form .= " />"._NO."&nbsp;&nbsp;&nbsp;"._MB_WEBSHOW_SHOWPOPUP."<br /><br />";

//**OPTION7 SHOW STATISTICS
$form .= "&nbsp;&nbsp;&nbsp;<input type='radio' name='options[7]' value='1'";
    if ($options[7] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
$form .= "<input type='radio' name='options[7]' value='0'";
    if ($options[7] == 0) {
        $form .= " checked='checked'";
    }
    $form .= " />"._NO."&nbsp;&nbsp;&nbsp;"._MB_WEBSHOW_SHOWLINKSTATS."<br /><br />";
return $form;
}
?>