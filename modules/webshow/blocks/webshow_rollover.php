<?php
// $Id: webshow_links.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
 * Function: b_webshow_links_show
 * Input   : $options[0] = Sort playlist links by date, hits, rating and random.
 *           $options[1]   = How many playlist links will be displayed.
  *           $options[2]   = Number of columns wide
   *           $options[3]   = Length of playlist title. 0 = Titles off.. 
    *           $options[4]   = Show link logo image.
    *           $options[5]   = Limit to selected category.
    *           $options[6]   = Show link statistics by selected sort.        
 * Output  : Returns the desired most recent, popular, rated or random links
 ******************************************************************************/
if (!defined('XOOPS_ROOT_PATH')){ exit(); }

/*************************/
//** webshow_rollover_show
  // $options[0] = Query sort options are date, views, hits, rating and random.
  // $options[1] = LIMIT number of rows to retrieve
  // $options[2] = # of columns in display 
  // $options[3] = TITLE length
  // $options[4] = ENTRY LOGO
  // $options[5] = Limit to this category
  // $options[6] = Display statistics
  // $options[7] = Show Description
/*************************/

function b_webshow_rollover_show($options) {
  global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
  $myts =& MyTextSanitizer::getInstance();

  $block = array();
  $min = '0'; //Query start row
  $orderby = ''; //Query sort order

  //** NEEDS WORK this should come from module config. Need module handler
  $path_logo = XOOPS_URL."/uploads/webshow/logo";
  $logowidth = "150";

   //** Sort by $options[0]
   if($options[0] == "random") {
      //** RANDOM
      // Count rows         
      if($options['5'] == 0 ){
         list ( $numrows ) = $xoopsDB -> fetchRow( $xoopsDB -> query ( "SELECT COUNT(*) FROM ".$xoopsDB->prefix("webshow_links")." WHERE lid > 0 and status > 0 and cid > 0 "));
      }else{
         // Category Limit
         list ( $numrows ) = $xoopsDB -> fetchRow( $xoopsDB -> query ( "SELECT COUNT(*) FROM ".$xoopsDB->prefix("webshow_links")." WHERE status > 0 and cid = ".$options[5].""));
      }
	  $numrows = $numrows - 1;

	  // Generate random row number
      mt_srand((double)microtime()*1000000);
	  $min = mt_rand(0, $numrows);
		
	  // Check that results with start row $min will return $options[1] number of rows
	  $limitcheck = $numrows - $min;
      // if not enough rows decrease the start row ($min) 
      if ( $limitcheck < $options[1] ) {
         $newlimit = $options[1] - $limitcheck;
         $min = $min - $newlimit;		
      }
   } else {
      $orderby = "ORDER BY $options[0] DESC";  // if not random add orderby to query
   }

   // Limit to category $options[5]
   if($options[5] == "0") {
      //$result = $xoopsDB->query("SELECT lid, cid, title, logourl, date, hits, views, rating, votes, entryperm FROM ".$xoopsDB->prefix("webshow_links")." WHERE status>0 and cid>0 ORDER BY ".$options[0]." DESC",$options[1],0);
      $sql = "SELECT lid, cid, title, logourl, date, hits, views, rating, votes, entryperm FROM ".$xoopsDB->prefix("webshow_links")." where status > 0 and cid > 0 $orderby";
   } else {
      //$result = $xoopsDB->query("SELECT lid, cid, title, logourl, date, hits, views, rating, votes, entryperm FROM ".$xoopsDB->prefix("webshow_links")." WHERE status>0 and cid = ".$options[5]." ORDER BY ".$options[0]." DESC",$options[1],0);			
      $sql = "SELECT lid, cid, title, logourl, date, hits, views, rating, votes, entryperm FROM ".$xoopsDB->prefix("webshow_links")." WHERE status > 0 and cid = $options[5] $orderby";
   }

   // example query($sql,LIMIT,START); 
   $result=$xoopsDB->query($sql,$options[1],$min); 
   while(list($lid, $cid, $title, $logourl, $date, $hits, $views, $rating, $votes, $ws_entryperm) = $xoopsDB->fetchRow($result)) { 
      $link = array();
      $link['id'] = $lid;
      $link['cid'] = $cid;

      //** TITLE
      $link['title'] = "";
      if ($options[3] > 0) {
         $title = $myts->undohtmlSpecialChars($title);
	     if (strlen($title) >= $options[3]) {
	        $title = substr($title,0,($options[3] -1))."...";
	     }
         $link['title'] = $title;
      }
   
      //** Option 4 ENTRY LOGO
      $link['logo'] = "";
      if($options[4] == 1){
         if($logourl != ""){
            $ps = strpos($logourl,"http://");
            if($ps === false) {
               $logourl = $path_logo."/".$logourl;
            }       
         }else{
            //If there is no entry selected show the stock logo.  
            //NEEDS WORK to use mod config "No Logo"
            $logourl = $path_logo."/stock/logo.gif"; 
         }
         $link['logo'] = $logourl;
      }

      //** OPTION6 Show the link statistics by sort method
      $link['stats'] = "";
      if ( $options[6] == 1) {
         $link['stats'] = $options[0];
	     if($options[0] == "date"){
		    $link['date'] = formatTimestamp($date,'s');
	     } elseif ($options[0] == "hits") {
		    $link['hits'] =  $hits;
	     } elseif ($options[0] == "views") {		  
		    $link['views'] =  $views;    
	     } elseif ($options[0] == "rating") {
		    $link['rating'] = number_format($rating, 2);
		    $link['votes'] = $votes;
	     }
      } elseif ($options[0] == "") {
		 // No stats for random
      }

      //** OPTION7 Show Description value is max str length
      $link['description'] = "";  
      if ( $options[7] > 0) {    	
        list ( $description ) = $xoopsDB -> fetchRow( $xoopsDB -> query ( "SELECT description FROM ".$xoopsDB->prefix("webshow_text")." WHERE lid = $lid "));
    	 if (strlen($description) >= $options[7]) {
    	    $description = substr($description,0,($options[7] -1))."...";
    	 }
        $link['description'] = $myts->displayTarea($description,0);
      }		
  
     $block['links'][] = $link;
  } //END WHILE LOOP
$block['columncount'] = $options[2];
$block['logowidth'] = $logowidth;
return $block;
}

/*************************/
//** webshow_rollover_edit
/*************************/

function b_webshow_rollover_edit($options) {
global $xoopsDB, $xoopsConfig;
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";

    $form = ""._MB_WEBSHOW_SORT."&nbsp;<select name='options[]'>";
    $form .= "<option value='date' ";
    if ( $options[0] == "date" ) {
        $form .= " selected='selected'";
    }
    $form .= ">"._MB_WEBSHOW_DATE."</option>\n";

    $form .= "<option value='views'";
    if($options[0] == "views"){
        $form .= " selected='selected'";
    }
    $form .= ">"._MB_WEBSHOW_VIEWS."</option>";

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
    
    $form .= "</select>\n";
    $form .= "<br /><br />"._MB_WEBSHOW_DISP."&nbsp;<input type='text' name='options[1]' value='".$options[1]."'/>&nbsp;"._MB_WEBSHOW_LINKS."<br /><br />";
    $form .= _MB_WEBSHOW_DISP." <input type='text' name='options[2]' value='".$options[2]."' />&nbsp;"._MB_WEBSHOW_COLUMNSWIDE."<br /><br />";
    $form .= _MB_WEBSHOW_CHARS."&nbsp;<input type='text' name='options[3]' value='".$options[3]."'/>&nbsp;"._MB_WEBSHOW_LENGTH."<br /><br />";
    //** OPTION4 Show Logo
    $form .= _MB_WEBSHOW_SHOWLINKLOGO."&nbsp;&nbsp;<input type='radio' name='options[4]' value='1'";
    if ($options[4] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
    $form .= "<input type='radio' name='options[4]' value='0'";
    if ($options[4] == 0) {
      $form .= " checked='checked'";
    }
    $form .= " />"._NO."<br /><br />";
    
    //**Category Select
	$cattree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","pid");    
	$form .=_MB_WEBSHOW_CATLIMIT;	
	ob_start();
	//**EXAMPLE makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")			
	$cattree->makeMySelBox("cattitle", "cattitle",$options[5],1,'options[5]');
	$catselbox = ob_get_contents();
	ob_end_clean();
	$form .= $catselbox;
    
    //** OPTION6 Show the link statistics
    $form .= "<br /><br />"._MB_WEBSHOW_SHOWLINKSTATS."&nbsp;&nbsp;&nbsp;<input type='radio' name='options[6]' value='1'";
    if ($options[6] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
    $form .= "<input type='radio' name='options[6]' value='0'";
    if ($options[6] == 0) {
      $form .= " checked='checked'";
    }
    $form .= " />"._NO;
    
    $form .= "<br /><br />"._MB_WEBSHOW_CHARS."&nbsp;<input type='text' name='options[7]' value='".$options[7]."'/>&nbsp;"._MB_WEBSHOW_LENGTH_DESC."<br /><br />";

    return $form;
}
?>