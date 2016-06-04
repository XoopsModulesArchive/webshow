<?php
// $Id: webshow_category.php,v.50 2008/03/05 19:59:00 tcnet Exp $ //
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
 * Function: b_webshow_category_show
 * Input   :
   *           $options[0]   = Number of columns wide
   *           $options[1]   = Length of playlist title. 0 = Titles off.. 
    *           $options[2]   = Show category logo image.
    *           $options[3]   = Show category statistics.
    *           $options[4]   = Show sub categories.        
 * Output  : Returns a category menu
 ******************************************************************************/
if (!defined('XOOPS_ROOT_PATH')){ exit(); }

function b_webshow_category_show($options) {

   $myts =& MyTextSanitizer::getInstance();
   $path_webshow = XOOPS_URL."/modules/webshow"; 
   $path_logo = XOOPS_URL."/modules/webshow/images/category";

   global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
   $count = 1;
   $block = array();
   $result=$xoopsDB->query("SELECT cid, cattitle, imgurl FROM ".$xoopsDB->prefix("webshow_cat")." WHERE pid = 0 ORDER BY cattitle") or exit("Error");
   while($myrow = $xoopsDB->fetchArray($result)) {
      $cat = array();
      $cat['id'] = $myrow['cid'];
   
      //** OPTION 1 TITLE LENGTH
      $cat['title'] = "";
      if ($options[1] > 0) {				
         $title = $myts->htmlSpecialChars($myrow["cattitle"]);
   	     if (strlen($title) >= $options[1]) {
	        $title = substr($title,0,($options[1] -1))."...";
	     }
         $cat['title'] = $myts->htmlSpecialChars($myrow["cattitle"]);
      }

      //** Option 2 CATEGORY LOGO
      $cat['logo'] = "";
      if($options[2] == 1 & !empty($myrow['imgurl'])){
	     $imgurl = $myts->htmlSpecialChars($myrow['imgurl']);
         $cat['logo'] = $imgurl;
      }

      //** OPTION3 Show SUB CATEGORIES
      if ( $options[3] == 1) {    
         // get child category objects
         include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
         $mytree = new XoopsTree($xoopsDB->prefix("webshow_cat"),$cat['id'],"pid");
         $arr=array();
         $arr=$mytree->getFirstChild($cat['id'], "cattitle");
         $space = 0;
		 //$inferspace = 0;
         $sub_arr=array();
	     $subcategories = '';
         foreach($arr as $ele){
	        //if ($gperm_handler->checkRight('webshow_view', $ele['cid'], $groups, $xoopsModule->getVar('mid'))) {
               $subtitle = $myts->htmlSpecialChars($ele['cattitle']);
               if ($space>0) {
		      	   $subcategories .= "<br /> ";
               }
               $subcategories .= "&nbsp;-<a href=\"".$path_webshow."/playcat.php?cid=".$ele['cid']."\" title=\""._MB_WEBSHOW_CAT.": ".$subtitle."\">".$subtitle."</a>";
               $space++;
           // }

             //NEEDS WORK no infer categories atm                
               //** Get infercategories   
               $sub_arr=$mytree->getFirstChild($ele['cid'], "cattitle");
               $infercategories = "";
               foreach($sub_arr as $sub_ele){		    
                  //if ($gperm_handler->checkRight('webshow_view', $sub_ele['cid'], $groups, $xoopsModule->getVar('mid'))) {
                    $infertitle=$myts->htmlSpecialChars($sub_ele['cattitle']);
			        //if ($inferspace>0) {
                    //    $infercategories .= "";
                    //}
                    $infercategories .= "<br />&nbsp;--<a href=\"".$path_webshow."/playcat.php?cid=".$sub_ele['cid']."\" title=\""._MB_WEBSHOW_CAT.": ".$infertitle."\">".$infertitle."</a>";
		          //}
	           }
               //$cat['infer'] = $infercategories;
                $subcategories .= $infercategories;               
               //$inferspace++;
               $cat['sub'] = $subcategories;            
         }
      }
   $block['cats'][] = $cat;
   }

   //** OPTION 0 Column Count
   $block['columncount'] = $options[0];
return $block;
}

function b_webshow_category_edit($options) {
global $xoopsDB, $xoopsConfig;
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
    //** OPTION0 Column Count
    $form = _MB_WEBSHOW_DISP." <input type='text' name='options[0]' value='".$options[0]."' />&nbsp;"._MB_WEBSHOW_COLUMNSWIDE."<br /><br />";

    //** OPTION1 Title Length
    $form .= _MB_WEBSHOW_CHARS."&nbsp;<input type='text' name='options[1]' value='".$options[1]."'/>&nbsp;"._MB_WEBSHOW_LENGTH."<br /><br />";
 
    //** OPTION2 Show Logo
    $form .= _MB_WEBSHOW_SHOWCATLOGO."&nbsp;&nbsp;<input type='radio' name='options[2]' value='1'";
    if ($options[2] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
    $form .= "<input type='radio' name='options[2]' value='0'";
    if ($options[2] == 0) {
      $form .= " checked='checked'";
    }
    $form .= " />"._NO."<br /><br />";

    //** OPTION3 Show subcategory
    $form .= "<br /><br />"._MB_WEBSHOW_SHOWSUBCAT."&nbsp;&nbsp;&nbsp;<input type='radio' name='options[3]' value='1'";
    if ($options[3] == 1) {
        $form .= " checked='checked'";
    }
    $form .= " />"._YES;
    $form .= "<input type='radio' name='options[3]' value='0'";
    if ($options[3] == 0) {
      $form .= " checked='checked'";
    }
    $form .= " />"._NO;

    return $form;
}
?>