<?php
// $Id: index.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
include "header.php";
$xoopsOption['template_main'] = 'webshow_category.html';
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl -> assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="'  . XOOPS_URL . '/modules/' . $xoopsModule -> getvar( 'dirname' ) .  '/templates/style.css" />'); 
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mytree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","pid");
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler =& xoops_gethandler('groupperm');
$count = 1;
$metakey = ""; // build string for keywords

//** Count Categories
$numrows="";
$fullcountresult=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("webshow_cat")." where cid>0");
list($numrows) = $xoopsDB->fetchRow($fullcountresult);
if($numrows == 0){
   redirect_header("javascript:history.go(-1)", 1, _WS_NOTEXIST);
}
$thereare = sprintf(_WS_THEREARECAT,$numrows);
$xoopsTpl->assign('thereare', $thereare);

//** Category Block
$result=$xoopsDB->query("SELECT cid, cattitle, imgurl, catdesc FROM ".$xoopsDB->prefix("webshow_cat")." WHERE pid = 0 ORDER BY cattitle") or exit("Error");
while($myrow = $xoopsDB->fetchArray($result)) {
   //**Permissions
   // example checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
   if ($gperm_handler->checkRight('webshow_view', $myrow['cid'], $groups, $xoopsModule->getVar('mid'))) {
      $imgurl = '';
	  if ($myrow['imgurl'] && $myrow['imgurl'] != ""){
	    $imgurl = $myts->htmlSpecialChars($myrow['imgurl']);
	  }
      $totallink = getTotalItems($myrow['cid'], 1);

      // get child category objects
      $arr = array();
      $arr = $mytree->getFirstChild($myrow['cid'], "cattitle");
      $space = 0;
      $chcount = 0;
	  $subcategories = '';
	  foreach($arr as $ele){
	     if ($gperm_handler->checkRight('webshow_view', $ele['cid'], $groups, $xoopsModule->getVar('mid'))) {
		    $chtitle = $myts->htmlSpecialChars($ele['cattitle']);
		    $chdesc = $myts->htmlSpecialChars($ele['catdesc']);
            if ($chcount > 5) {
			   $subcategories .= "...";
			   break;
		    }
            if ($space>0) {
			   $subcategories .= "<br />";
            }
            $metakey.=$chtitle.' '.$chdesc.' ';
            $subcategories .= "--<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/playcat.php?cid=".$ele['cid']."\">".$chtitle."</a>: ".$chdesc; 

		$sub_arr=array();
		$sub_arr=$mytree->getFirstChild($ele['cid'], "cattitle");
		foreach($sub_arr as $sub_ele){
            if ($gperm_handler->checkRight('webshow_view', $sub_ele['cid'], $groups, $xoopsModule->getVar('mid'))) {
			  $inferchtitle=$myts->htmlSpecialChars($sub_ele['cattitle']);
			  $inferchdesc=$myts->htmlSpecialChars($sub_ele['catdesc']);
			  $subcategories .= "<br />&nbsp;&nbsp;--<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/playcat.php?cid=".$sub_ele['cid']."\">".$inferchtitle."</a>: ".$inferchdesc;
              $metakey.=$inferchtitle.' '.$inferchdesc.' ';
		   }
        }
         $space++;
         $chcount++;
         }
      }
      $xoopsTpl->append('categories', array('image' => $imgurl, 'id' => $myrow['cid'], 'cattitle' => $myts->htmlSpecialChars($myrow['cattitle']), 'catdesc' => $myts->htmlSpecialChars($myrow['catdesc']), 'subcategories' => $subcategories, 'totallink' => $totallink, 'count' => $count));
      $metakey.=$myts->htmlSpecialChars($myrow['cattitle']).' '.$myts->htmlSpecialChars($myrow['catdesc']).' ';
      $count++;
   }
}

//** Submit a Web Show
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler =& xoops_gethandler('groupperm');
if ($gperm_handler->checkRight('webshow_submit', 0, $groups, $xoopsModule->getVar('mid'))) {  
   $xoopsTpl->assign('submitlink', 1);
}
    
//** Category Select
ob_start();
//**function makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")	
$mytree->makeMySelBox('cattitle','cattitle',0,1,'cid', 'window.location="playcat.php?cid="+this.value');	
$catselbox = ob_get_contents();	
ob_end_clean();
$xoopsTpl->assign('category_select', $catselbox);

//** METATAGS
// Module Name
$wsmodname = $myts->htmlSpecialChars($xoopsModule->getVar('name'));
$xoopsTpl->assign('wsmodname', $wsmodname);

// Page Title
$pagetitle = $wsmodname;
$xoopsTpl->assign('xoops_pagetitle',$pagetitle); 

// Page Description
$wsmoddesc = $myts->displayTarea($xoopsModuleConfig['moddesc'],0);
$xoopsTpl->assign('wsmoddesc', $wsmoddesc);

// Meta Description Tag
$metadesc = strip_tags($thereare);
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