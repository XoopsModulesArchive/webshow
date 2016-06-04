<?php
// $Id: topten.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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

//generates top 10 charts by rating, views, hits for each main category
include "header.php";
$xoopsOption['template_main'] = 'webshow_topten.html';
include XOOPS_ROOT_PATH."/header.php";

$xoopsTpl -> assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="'  . XOOPS_URL . '/modules/' . $xoopsModule -> getvar( 'dirname' ) .  '/templates/style.css" />'); 
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mytree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","pid");
$metakey=''; // collects meta keywords
$metadesc=''; // collects meta description
//** Order By
if(isset($_GET['orderby'])) {
   $orderby = convertorderbyin($_GET['orderby']);
} else {
    $orderby = "views DESC";
}
$orderbyTrans = convertorderbytrans($orderby);
$xoopsTpl->assign('lang_cursortedby', sprintf(_WS_CURSORTEDBY, convertorderbytrans($orderby)));

$arr=array();
$result=$xoopsDB->query("select cid, cattitle, imgurl from ".$xoopsDB->prefix("webshow_cat")." where pid=0");
$e = 0;
$rankings = array();
while(list($cid, $cattitle, $imgurl)=$xoopsDB->fetchRow($result)){
   //**Permissions
   $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
   $gperm_handler =& xoops_gethandler('groupperm');
   // example checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
   if ($gperm_handler->checkRight('webshow_view', $cid, $groups, $xoopsModule->getVar('mid'))) {
      $cattitle = $myts->htmlSpecialChars($cattitle);
      $metakey = $cattitle." "; //Collects meta description
      $rankings[$e]['cattitle'] = $myts->htmlSpecialChars($cattitle);
      $rankings[$e]['catimgurl'] = $imgurl;
      $query = "select lid, cid, title, hits, views, rating, votes, entryperm from ".$xoopsDB->prefix("webshow_links")." where status>0 and (cid=$cid";
      // get all child cat ids for a given cat id
      $arr=$mytree->getAllChildId($cid);
      $size = count($arr);
      for($i=0;$i<$size;$i++){
         $query .= " or cid=".$arr[$i]."";
      }
      $query .= ") order by ".$orderby;
      $result2 = $xoopsDB->query($query,10,0);
      $rank = 1;
      $orderbyout = convertorderbyout($orderby);
      $group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);	
      while(list($lid,$cid,$title,$hits,$views,$rating,$votes,$ws_entryperm)=$xoopsDB->fetchRow($result2)){
         //** Entry Permission
         $ws_entryperm = explode(" ",$ws_entryperm);
         if (count(array_intersect($group,$ws_entryperm)) > 0){
		    $title = $myts->htmlSpecialChars($title);
		    $metakey .= $title;
		    $rankings[$e]['links'][] = array('rank' => $rank, 'id' => $lid, 'cid' => $cid, 'title' => $title, 'hits' => $hits, 'views' => $views, 'rating' => number_format($rating, 2), 'votes' => $votes);		    
		    $rank++;
         }
	  }
	  $e++;
   }
}
$xoopsTpl->assign('rankings', $rankings);

//** Submit a Web Show
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler =& xoops_gethandler('groupperm');
if ($gperm_handler->checkRight('webshow_submit', 0, $groups, $xoopsModule->getVar('mid'))) {  
   $xoopsTpl->assign('submitlink', 1);
}

//** Category Select
ob_start();
//** example makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")	
$mytree->makeMySelBox("cattitle","cattitle",0,1,"cid",'window.location="playcat.php?cid="+this.value');	
$catselbox = ob_get_contents();
ob_end_clean();	
$xoopsTpl->assign('category_select', $catselbox);	

//** METATAGS
// Module Name
$wsmodname = $myts->htmlSpecialChars($xoopsModule->getVar('name'));
$xoopsTpl->assign('wsmodname', $wsmodname);

// Module Description
$wsmoddesc = $myts->displayTarea($xoopsModuleConfig['moddesc'],0);
$xoopsTpl->assign('wsmoddesc', $wsmoddesc);

// Page Title
if($orderbyout == "viewsD") {
$pagetitle = $wsmodname . ' ' . $myts->htmlSpecialChars(_WS_TOPVIEWS);
}
if($orderbyout == "ratingD") {
$pagetitle = $wsmodname . ' ' . $myts->htmlSpecialChars(_WS_TOPRATED);
}
if($orderbyout == "hitsD") {
$pagetitle = $wsmodname . ' ' . $myts->htmlSpecialChars(_WS_TOPHITS);
}
$xoopsTpl->assign('xoops_pagetitle',$pagetitle); 

// Page Description
// Get Number of rows in db
$numrows = '';  
list($numrows) = $xoopsDB->fetchRow($xoopsDB->query("select count(*) from ".$xoopsDB->prefix("webshow_links")." where status>0 and cid>0"));
if($numrows) {
   $thereare = sprintf(_WS_THEREARE,$numrows,"");
   $xoopsTpl->assign('thereare',$thereare);
}

// Number of categories
// $e = number of categories in the cat loop
$therearecat = sprintf(_WS_THEREARECAT, $e);
$xoopsTpl->assign('therearecat',$therearecat);

// Page Description
//define("_WS_THEREAREINDEX","Our media catalog has %s listings in %s categories.");
$xoopsTpl->assign('thereareindex',sprintf(_WS_THEREAREINDEX, $numrows, $e));

// Meta Description Tag
$metadesc = $thereare." ".$therearecat;
$metadesc = $myts->htmlSpecialChars($metadesc);

// Meta Keywords
include_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/include/functions.php';
$metakey = webshow_extract_keywords($pagetitle." ".$metadesc." ".$metakey);
$metakey = $myts->htmlSpecialChars($metakey);

// Assign meta tags 
if (is_object($xoTheme)) { 
   $xoTheme->addMeta( 'meta', 'keywords',$metakey);
   $xoTheme->addMeta( 'meta', 'description',$metadesc);     
   $xoTheme->addMeta( 'meta', 'title', $pagetitle); 
} else {  
   $xoopsTpl->assign('xoops_meta_keywords',$metakey);
   $xoopsTpl->assign('xoops_meta_description',$metadesc);       
}
    
include XOOPS_ROOT_PATH.'/footer.php';
?>